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

    public function update_user(){
        $user = new model\User();
        $param = $this->param;

        $param['modifyUser'] = $param['uId'];
        $param['modifyTime'] = date("Y-m-d H:i:s");

        $data = $user->updateDataById($param,$param['uId']);

        if (!$data){
            return result_array(['error' => $user->getError()]);
        }

        return result_array(['data' => $data]);
    }

    public function del_user(){

    }

    public function find_user(){
        $user = new model\User();
        $param = $this->param;

        $data = $user->findUser($param);
    }
}