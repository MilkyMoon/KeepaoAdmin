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
        if ($request->isGet()) {
            if (!session('?csrf')) {
                $csrf = $request->token();
                session('csrf', $csrf);
            }
            return json([
                'value' => true,
                'data' => [
                    'message' => '返回csrf',
                    'csrf' => session('csrf')
                ]
            ]);
        }
        if ($request->isPost())
        {
            if (!$request->has('aId', 'param', true))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '管理员Id不能为空'
                    ]
                ]);
            }

            if (!$request->has('roles', 'param', true))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '角色字符串不能为空'
                    ]
                ]);
            }
            return json($this->urlink->add($request->param('aId'), $request->param('roles')));
        }
    }

}