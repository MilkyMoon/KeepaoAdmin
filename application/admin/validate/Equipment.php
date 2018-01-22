<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午10:36
 */

namespace app\admin\validate;


use think\Validate;

class Equipment extends Validate
{
    protected $rule = [
        'name' => 'require',
        'equno' => 'require',
    ];

    protected $message = [
        'name.require' => '设备名称不能为空',
        'equno.require' => '设备号不能为空',
    ];
}