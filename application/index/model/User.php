<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/10
 * Time: 下午3:49
 */
namespace app\index\model;


class User extends Common {
    protected $pk = 'uId';

    public function addUser($param){
        if ($this->findUser($param) != null) {
            $this->error = '用户已存在！';
            return false;
        }

        $data = $this->createData($param);

        return $data;
    }

    public function findUser($param) {
        if (isset($param['openId'])) {
            return $this->where('openId', $param['openId'])->find();
        } else if (isset($param['uid'])) {
            return $this->where('uId', $param['uid'])->find();
        } else if (isset($param['phone'])) {
            return $this->where('phone', $param['phone'])->find();
        } else {
            return false;
        }
    }
}