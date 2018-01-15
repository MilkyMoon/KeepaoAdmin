<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午2:59
 */

namespace app\admin\validate;


use think\Validate;

class PointRuleDet extends Validate
{
    protected $rule = [
        'prId' => 'require',
        'condition' => 'require',
        'reward' => 'require',
    ];

    protected $message = [
        'prId.require'  =>  '规则类型不能为空',
        'condition.require' => '条件不能为空',
        'reward.require' => '获得积分数不能为空',
    ];

    protected $scene = [
        'edit' => ['condition', 'reward']
    ];
}