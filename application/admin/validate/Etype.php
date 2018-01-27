<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/25
 * Time: 下午9:11
 */

namespace app\admin\validate;


class Etype
{
    protected $rule = [
        'name' => 'require|unique:etype',
    ];

    protected $message = [
        'name.require' => '类型名称不能为空',
        'name.unique'  => '名称已经存在',
    ];
}