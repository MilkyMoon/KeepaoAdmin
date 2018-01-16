<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午8:37
 */

namespace app\admin\controller;


use think\Request;

class Message extends Common
{
    private $message;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->message = new \app\admin\model\Message();
    }

    public function add(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function del(Request $request)
    {

    }

    public function select(Request $request)
    {

    }
}