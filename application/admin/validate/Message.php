<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午8:37
 */

namespace app\admin\validate;


use think\Validate;

class Message extends Validate
{
    protected $rule = [
        'title' => 'require',
        'type' => 'require',
    ];

    protected $message = [
        'title.require'  =>  '标题不能为空',
        'type.require' => '类型不能为空',
    ];
}