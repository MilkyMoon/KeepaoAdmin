<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/12
 * Time: 上午1:25
 */

namespace app\admin\controller;


use think\Request;

class Urlink extends Common
{
    private $urlink;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->urlink = new \app\admin\model\Urlink();
    }

    public function add(Request $request)
    {
        if ($request->isPost())
        {
            if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf'))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '请不要重复提交数据',
                    ]
                ]);
            }
            session('csrf', md5($_SERVER['REQUEST_TIME_FLOAT']));

            if (!$request->has('aId', 'param', true))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '管理员Id不能为空'
                    ]
                ]);
            }
            
            return json($this->urlink->add($request->param('aId'), $request->param('roles')));
        }
    }

}