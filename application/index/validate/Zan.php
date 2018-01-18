<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/16
 * Time: 上午11:15
 */

namespace app\index\validate;


use think\Validate;

class Zan extends Validate {
    protected $rule = [
        'spoId' => 'require|number',
    ];

    protected $message = [
        'spoId.int' => 'id必须为数字类型',
        'spoId.require' => 'id不能为空',
    ];

    protected $scene = [
        'addUser' => ['phone'],
    ];
}