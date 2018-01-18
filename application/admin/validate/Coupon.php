<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午4:42
 */

namespace app\admin\validate;


use think\Validate;

class Coupon extends Validate
{
    protected $rule = [
        'startDate' => 'require',
        'endDate' => 'require',
        'condition' => 'require',
        'stoId' => 'require',
        'discount' => 'require',
        'num' => 'require',
    ];

    protected $message = [
        'startDay.require'  =>  '起始时间不能为空',
        'endDate.require' => '截止时间不能为空',
        'condition.require' => '条件不能为空',
        'stoId.require' => '门店Id不能为空',
        'discount.require' => '优惠价格不能为空',
        'num.require' => '数量不能为空',
    ];
}