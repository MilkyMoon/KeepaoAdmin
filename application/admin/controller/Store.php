<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/17
 * Time: 下午5:05
 */

namespace app\admin\controller;


use think\Request;

class Store extends Common
{
    protected $store;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->store = new \app\admin\validate\Store();
    }

    public function add(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function select(Request $request)
    {

    }
}