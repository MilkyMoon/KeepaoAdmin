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

        $stoId = !empty($param['stoId']) ? $param['stoId'] : '';

        $data = $coupon->couSelect($stoId);

        if(!$data){
            return result_array(['error' => $coupon->getError()]);
        }

        return result_array(['data' => $data]);
    }

    /**
     * Function: usercou_select
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 查询用户领取的优惠券
     *
     * @return \think\response\Json
     */
    public function usercou_select(){
        $param = $this->param;
        $coupon = new model\Coupon();

        $uId = !empty($param['uId']) ? $param['uId'] : '';
        $stoId = !empty($param['stoId']) ? $param['stoId'] : '';
        $date = !empty($param['date']) ? $param['date'] : date('Y-m-d',strtotime('now'));

        if(!$uId){
            return result_array(['error' => '用户id不能为空！']);
        }

        $data = $coupon->usercouSelect($uId,$stoId,$date);

        if(!$data){
            return result_array(['error' => $coupon->getError()]);
        }

        return result_array(['data' => $data]);
    }

    /**
     * Function: getCoupon
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 获取优惠券
     *
     * @return \think\response\Json
     */
    public function get_coupon(){
        $param = $this->param;
        $coupon = new model\Coupon();
        $usecou = new model\UseCou();

        $uId = !empty($param['uId']) ? $param['uId'] : '';
        $couId = !empty($param['couId']) ? $param['couId'] : '';

        if(!$uId || !$couId){
            return result_array(['error' => '用户id或优惠券id不能为空！']);
        }

        //判断是否已领取过
        $data = $usecou->hasCou($uId,$couId);
        if($data){
            return result_array(['error' => '您已领取过此优惠券！']);
        }

        //判断优惠券剩余数量
        $data = $coupon->getDataById($couId);
        if(!$data || $data['num'] <= $data['send']){
            return result_array(['error' => '优惠券已领完！']);
        }

        $param['useId'] = $uId;
        $param['state'] = 1;

//        dump($param);die();

        //写入用户优惠券表
        $data = $usecou->createData($param);
        if(!$data){
            return result_array(['error' => $usecou->getError()]);
        }

        //优惠券领取数量+1
        $data = $coupon->updateAddById('send',$couId,1);
        if(!$data){
            return result_array(['error' => $coupon->getError()]);
        }

        return result_array(['data' => '领取成功！']);
    }
}