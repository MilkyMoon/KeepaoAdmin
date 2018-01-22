<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/22
 * Time: 上午1:01
 */

namespace app\index\model;


class UseMes extends Common{
    public function messageSelect($uid,$type = '',$state = ''){
        $map = [];

        if($uid){
            $map['uId'] = $uid;
        }else{
            $this->error = '用户id为空！';
            return false;
        }

        if($type){
            $map['usemes.type'] = $type;
        }

        if($state){
            $map['usemes.state'] = $state;
        }

        $list = $this->alias('usemes')
                ->where($map)
                ->join('__MESSAGE__ message','usemes.mesId = message.mesId','LEFT');

        $list = $list->field('message.mesId,message.title,message.body,usemes.id,usemes.uId,usemes.type,usemes.state,usemes.createTime');
        $list = $list->select();

        $data['list'] = $list;
        $data['dataCount'] = sizeof($list);

        return $data;
    }
}