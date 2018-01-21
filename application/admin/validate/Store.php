<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/17
 * Time: 下午5:05
 */

namespace app\admin\validate;


use think\Validate;

class Store extends Validate
{
    protected $rule = [
        'stoname'  =>  'require',
        'province'  =>  'require',
        'city'  =>  'require',
        'county'  =>  'require',
        'longitude' => 'require',
        'latitude' => 'require',
        'isdirect' => 'require',
    ];

    protected $message = [
        'stoname.require'  =>  '店铺名称不能为空',
        'province.require'  =>  '省不能为空',
        'city.require'  =>  '市不能为空',
        'county.require'  =>  '县不能为空',
        'longitude.require'  =>  '经度不能为空',
        'latitude.require'  =>  '纬度不能为空',
        'isdirect.require'  =>  '是否为直营不能为空',
    ];
}