<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/10
 * Time: 下午3:48
 */

namespace app\index\controller;

use app\index\model;

class User extends Common {
    public function add_user(){
        $user = new model\User();
        $param = $this->param;

        $data = $user->addUser($param);

        if (!$data){
            return result_array(['error' => $user->getError()]);
        } else {
            //修改刚添加用户的以下信息
            $param['createUser'] = $data['uId'];
            $param['createTime'] = date("Y-m-d H:i:s");
            $param['modifyUser'] = $data['uId'];
            $param['modifyTime'] = date("Y-m-d H:i:s");
            $param['createType'] = 1;
            $param['modifyType'] = 1;

            $data = $user->updateDataById($param,$data['uId']);

            if (!$data){
                return result_array(['error' => $user->getError()]);
            }
        }
        return result_array(['data' => $data]);
    }

    /**
     * Function: update_user
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 更新用户信息
     *
     * @return \think\response\Json
     */
    public function update_user(){
        $user = new model\User();
        $param = $this->param;

        if(!$param['uId']){
            return result_array(['error' => '没有用户id']);
        }

        $data = $user->updateDataById($param,$param['uId']);

        if (!$data){
            return result_array(['error' => $user->getError()]);
        }

        return result_array(['data' => $data]);
    }

    public function find_user(){
        $user = new model\User();
        $param = $this->param;

        $data = $user->findUser($param);

        if (!$data){
            return result_array(['error' => '未找到用户']);
        }

        return result_array(['data' => $data]);
    }

    //设置本周运动目标
    public function set_goal(){
        $goal = new model\Goal();
        $param = $this->param;



    }
}