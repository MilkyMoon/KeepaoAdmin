<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/11
 * Time: 上午11:26
 */

namespace app\admin\controller;


use think\Request;

class Role extends Common
{
    private $role;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->role = new \app\admin\model\Role();
    }

    public function select(Request $request)
    {
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('name', 'param', true)) {
                $name = $request->param('name');
                return json($this->role->select($name, $page));
            } else {
                return json($this->role->select('', $page));
            }
        } else {
            if ($request->has('name', 'param', true)) {
                $name = $request->param('name');
                return json($this->role->select($name));
            } else {
                return json($this->role->select(''));
            }
        }
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
            if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf'))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '请不要重复提交数据',
                    ]
                ]);
            }
            session('csrf', $request->token());

            return json($this->role->add($request->param()));
        }
    }

    public function update(Request $request)
    {
        return json($this->role->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->role->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少删除参数'
                ]
            ]);
        }
    }
}