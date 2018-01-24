<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/22
 * Time: 上午3:49
 */

namespace app\index\controller;

use app\index\model;

class Coupon extends Common{
    public function cou_select(){
        $param = $this->param;
        $coupon = new model\Coupon();

        $uId     = !empty($param['uId']) ? $param['uId'] : '';

        if(!$uId){
            return result_array(['error' => '用户id不能为空！']);
        }

        $data = $coupon->couSelect($uId);

        if(!$data){
            return result_array(['error' => $coupon->getError()]);
        }

        return result_array(['data' => $data]);
    }
}