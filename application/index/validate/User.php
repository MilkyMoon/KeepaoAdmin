<?php

/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/10
 * Time: 下午4:56
 */
namespace app\index\validate;

use think\Validate;

class User extends Validate {
    protected $rule = [
        'phone' => 'number|length:11',
    ];

    protected $message = [
        'phone.length' => '手机号长度为11',
        'phone.require' => '手机号不能为空',
    ];

    protected $scene = [
        'addUser' => ['phone'],
    ];
}