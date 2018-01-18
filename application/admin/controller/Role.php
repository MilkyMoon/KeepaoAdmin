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
        $data = [];
        if ($request->has('name', 'param', true)) {
            $data['name'] = $request->param('name');
        }

        if ($request->has('state', 'param', true)) {
            $data['state'] = $request->param('state');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->role->select($data, $page, $request->param('limit')));
            }
            return json($this->role->select($data, $page));
        }
        return json($this->role->select($data));
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

    public function getper(Request $request)
    {
        if ($request->has('rId', 'param', true)) {
            return json($this->role->getper($request->param('rId')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少角色Id'
                ]
            ]);
        }

    }
}