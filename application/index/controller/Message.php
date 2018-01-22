<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/18
 * Time: 上午1:36
 */

namespace app\index\controller;

use app\index\model;

class Message extends Common{
    public function message_add(){
        $param = $this->param;
        $message = new model\Message();
        $usemes = new model\UseMes();

        $body     = !empty($param['body']) ? $param['body'] : '';
        $muid     = !empty($param['muid']) ? $param['muid'] : '';
        $type     = !empty($param['type']) ? $param['type'] : '';

        if(!$body){
            return result_array(['error' => '信息不能为空！']);
        }

        //如果没有指定留言类型，默认为用户留言
        if(!$type){
            $type = 2;
            $param['type'] = 2;  //用户留言
        }

        if($type == 2 && !$muid){
            return result_array(['error' => '消息类型为用户留言，但用户id为空！']);
        }

        $param['state'] = 1;
        //添加消息到消息表
        $data = $message->createData($param);
        if(!$data){
            return result_array(['error' => $message->getError()]);
        }

        if($type == 2 && $muid){
            //将消息添加到用户消息表
            $param['uId'] = $muid;
            $param['mesId'] = $message->mesId;

            $data = $usemes->createData($param);
            if(!$data){
                return result_array(['error' => $usemes->getError()]);
            }
        }



        return result_array(['data' => '发送成功！']);
    }

    public function message_select(){
        $param = $this->param;
        $message = new model\Message();
        $usemes = new model\UseMes();

        $uid = !empty($param['uId']) ? $param['uId'] : '';
        $type = !empty($param['type']) ? $param['type'] : '';
        $state = !empty($param['state']) ? $param['state'] : '';

        if(!$uid){
            return result_array(['error' => '用户id不能为空！']);
        }

        $data = $message->getMessMail();

        if(!$data){
            return result_array(['error' => $message->getError()]);
        }

        $udata = $usemes->messageSelect($uid,1);

        if(!$udata){
            return result_array(['error' => $usemes->getError()]);
        }

        $uarray = [];
        $array  = [];

        //将以添加的站内消息mesId保存到数组中
        for($i = 0;$i < sizeof($udata['list']); $i++){
            array_push($uarray,$udata['list'][$i]['mesId']);
        }

        for($i = 0; $i<sizeof($data); $i++){
            if(!in_array($data[$i]['mesId'],$uarray)){
                $data[$i]['uId'] = $uid;
                array_push($array,$data[$i]->getData());
            }
        }

        //添加站内消息
        $data = $usemes->saveDatas($array);

        if(!$data){
            return result_array(['error' => $usemes->getError()]);
        }

        //再次查询全部消息
        $data = $usemes->messageSelect($uid,$type,$state);

        if(!$data){
            return result_array(['error' => $usemes->getError()]);
        }

        return result_array(['data' => $data]);
    }
}