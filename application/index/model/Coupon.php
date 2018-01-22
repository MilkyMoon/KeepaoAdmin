<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/22
 * Time: 上午3:49
 */

namespace app\index\model;


class Coupon extends Common{
    public function couSelect($uid){
        $map = [];

        if(!$uid){
            $this->error = 'id不能为空！';
            return false;
        }

        $data = $this->where();
    }
}