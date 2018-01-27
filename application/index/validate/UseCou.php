<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/27
 * Time: 上午12:59
 */

namespace app\index\validate;


use think\Validate;

class UseCou extends Validate {
    protected $rule = [
        'couId' => 'require',
        'useId' => 'require',
    ];

    protected $message = [
        'useId.require' => '用户id不能为空',
        'couId.require' => '优惠券id不能为空',
    ];

    protected $scene = [
        'get_coupon' => ['couId,useId'],
    ];
}