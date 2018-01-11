<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/11
 * Time: 上午12:41
 */

namespace app\admin\validate;


use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'account'  =>  'require',
        'password' =>  'require',
    ];

    protected $message = [
        'account.require'  =>  '帐户不能为空',
        'password.require' =>  '密码不能为空',
    ];

}