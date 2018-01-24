<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/22
 * Time: 上午10:40
 */

namespace app\admin\validate;


use think\Validate;

class Active extends Validate
{
    protected $rule = [
        'startTime' => 'require',
        'endTime' => 'require',
        'details' => 'require',
    ];

    protected $message = [
        'startTime.require'  =>  '起始时间不能为空',
        'endTime.require' => '截止时间不能为空',
        'details.require' => '详情不能为空',
    ];
}