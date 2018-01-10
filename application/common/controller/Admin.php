<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/10
 * Time: 上午10:48
 */

namespace app\common\controller;


class Admin
{
    protected $beforeActionList = [
        'auth'
    ];


    protected function auth()
    {
        return '123';
    }

    public function first()
    {

    }
}