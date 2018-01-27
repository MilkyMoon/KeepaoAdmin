<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/22
 * Time: 上午3:49
 */

namespace app\index\model;


class Coupon extends Common{
    //查询所有可用或店铺发行的优惠券
    public function couSelect($stoId){
        $map = [];

        //如果有店铺id
        if($stoId){
           $map['stoId'] = $stoId;
        }

        $map['state'] = 1;

        $date = date('Y-m-d',strtotime('now'));

        $list = $this->alias('coupon')
                ->where($map)
                ->whereTime('coupon.startDate','<=',$date)
                ->whereTime('coupon.endDate','>=',$date);

        $list = $list->field('coupon.startDate,coupon.endDate,coupon.condition,coupon.stoId,coupon.num,coupon.send,coupon.discount,coupon.title,coupon.content');
        $list = $list->select();

        $dataCount = sizeof($list);

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }

    /**
     * Function: couSelect
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 用户拥有的优惠券
     *
     * @param $stoId
     * @return mixed
     */
    public function usercouSelect($uId,$stoId,$date){
        $map = [];

        //如果有用户id
        if($uId){
            $map['user_cou.useId'] = $uId;
        }

        //如果有店铺id
        if($stoId){
            $map['coupon.stoId'] = $stoId;
        }

        $map['user_cou.state'] = 1;
        $map['coupon.state'] = 1;

        $list = $this->alias('coupon')
            ->where($map)
            ->join('__USE_COU__ user_cou','user_cou.couId = coupon.couId','LEFT');

        if($date){
            $list = $list->whereTime('coupon.startDate','<=',$date)->whereTime('coupon.endDate','>=',$date);
        }

        $list = $list->field('coupon.startDate,coupon.endDate,coupon.condition,coupon.stoId,coupon.num,coupon.send,coupon.discount,coupon.state,user_cou.usecouId,coupon.title,coupon.content');
        $list = $list->select();

        $dataCount = sizeof($list);

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }
}