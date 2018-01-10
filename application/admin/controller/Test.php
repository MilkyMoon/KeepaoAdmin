<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/9
 * Time: 下午4:18
 */

namespace app\admin\controller;


use app\admin\model\Admin;

class Test
{
    public function index()
    {
        return Admin::get(1);
    }
}