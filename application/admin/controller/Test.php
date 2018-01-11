<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/9
 * Time: 下午4:18
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use app\common\controller\Token;
use think\Request;

class Test extends Common
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {

    }
}