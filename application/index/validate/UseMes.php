<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/16
 * Time: 上午11:15
 */

namespace app\index\validate;


use think\Validate;

class UseMes extends Validate {
    protected $rule = [
        'uId' => 'require|number',
    ];

    protected $message = [
        'uId.require' => '内容不能为空',
        'uId.number' => 'id为数字',
    ];

    protected $scene = [
        'message_add' => ['uId'],
    ];
}