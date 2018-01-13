<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/13
 * Time: 下午4:09
 */

namespace app\admin\validate;


use think\Validate;

class Config extends Validate
{
    protected $rule = [
        'name' => 'require',
        'type' => 'require',
    ];

    protected $message = [
        'name.require'  =>  'name不能为空',
        'type.require' => '类型不能为空',
    ];
}