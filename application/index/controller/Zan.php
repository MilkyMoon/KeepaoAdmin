<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/13
 * Time: 下午3:22
 */

namespace app\index\controller;

use app\index\model;

class Zan extends Common{
    /**
     * Function: thumb_up
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 点赞
     */
    public function thumb_up(){
        $zan = new model\Zan();
        $sport = new model\Sport();
        $user = new model\User();
        $point = new model\PointRuleDet();
        $param = $this->param;

        if (empty($param['uId']) || empty($param['spoId'])){
            return result_array(['error' => '参数错误!']);
        }

        //判断用户是否已经点赞
        $data = $zan->whetherhumbUp($param['uId'],$param['spoId']);
        if($data){
            return result_array(['error' => '您已经对此用户当日排行进行了点赞！']);
        }

        //查询被赞用户id
        $array = ['useId'];
        $data = $sport->getDataById($param['spoId'],$array);
        if(!$data){
            return result_array(['error' => $sport->getError()]);
        }
        $uid = $data['useId'];

        //添加点赞数据
        $data  = $zan->createData($param);
        if(!$data){
            return result_array(['error' => $zan->getError()]);
        }

        /***************************************************************/

        //被赞用户被赞数+1
        $data = $user->updateAddById('zan',$uid,1);
        if(!$data){
            return result_array(['error' => $user->getError()]);
        }

        //查询被赞用户赞数
        $data = $user->getDataById($uid,['zan']);

        //查询被赞规则+积分
        $data = $point->findRule(5,$data['zan']);
        //如果有满足相应规则，则加积分
        if($data){
            $data = $user->updateAddById('point',$uid,$data['reward']);
            if(!$data){
                return result_array(['error' => $user->getError()]);
            }
        }

        /***************************************************************/

        //点赞用户点赞数+1
        $data = $user->updateAddById('upZan',$param['uId'],1);
        if(!$data){
            return result_array(['error' => $user->getError()]);
        }

        //查询点赞用户点赞数
        $data = $user->getDataById($param['uId'],['upZan']);

        //查询点赞规则+积分
        $data = $point->findRule(6,$data['upZan']);
        //如果有满足相应规则，则加积分
        if($data){
            $data = $user->updateAddById('point',$param['uId'],$data['reward']);
            if(!$data){
                return result_array(['error' => $user->getError()]);
            }
        }

        return result_array(['data' => '点赞成功！']);
    }
}