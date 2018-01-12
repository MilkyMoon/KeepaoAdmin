<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/11
 * Time: 上午11:50
 */

namespace app\admin\validate;


use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'name'  =>  'require',
    ];

    protected $message = [
        'name.require'  =>  '角色名不能为空',
    ];
}