<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/16
 * Time: 上午11:15
 */

namespace app\index\validate;


use think\Validate;

class Message extends Validate {
    protected $rule = [
        'body' => 'require',
    ];

    protected $message = [
        'body.require' => '内容不能为空',
    ];

    protected $scene = [
        'message_add' => ['body'],
    ];
}