<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午4:57
 */

namespace app\admin\validate;


use think\Validate;

class Memcard extends Validate
{
    protected $rule = [
        'name' => 'require',
        'day' => 'require',
        'money' => 'require',
        'point' => 'require',
    ];

    protected $message = [
        'name.require'  =>  '会员卡名称不能为空',
        'day.require' => '会员卡天数不能为空',
        'money.require' => '会员卡所需金额不能为空',
        'point.require' => '会员卡所需积分不能为空',
    ];

}