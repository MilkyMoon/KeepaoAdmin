<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/10
 * Time: 上午11:10
 */

namespace app\admin\controller;


use app\admin\model\User;
use think\Controller;
use think\exception\HttpResponseException;
use think\Request;
use think\Session;

class Admin extends Common
{
    private $admin;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //throw new HttpResponseException( json('123') );
        $this->admin = new \app\admin\model\Admin();

    }


    public function select(Request $request) {
        $data = [];
        if ($request->has('search', 'param', true)) {
            $data['search'] = $request->param('search');
        }

        if ($request->has('state', 'param', true)) {
            $data['state'] = $request->param('state');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->admin->select($data, $page, $request->param('limit')));
            }
            return json($this->admin->select($data, $page));
        }
        return json($this->admin->select($data));
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf')) {
                return json([
                    'value' => false,
                    'data' => [
                        'message' => '请不要重复提交数据',
                    ]
                ]);
            }
            session('csrf', md5($_SERVER['REQUEST_TIME_FLOAT']));
            return json($this->admin->add($request->param()));
        }

    }

    public function update(Request $request)
    {
        return json($this->admin->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            if (stripos('1', $request->param('del')) !== false) {
                return json([
                    'value' => false,
                    'data' => [
                        'message' => '不能删除超级管理员'
                    ]
                ]);
            }
            if (stripos(session('sId'), $request->param('del')) !== false) {
                return json([
                    'value' => false,
                    'data' => [
                        'message' => '不能删除自己'
                    ]
                ]);
            }
            return json($this->admin->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少删除参数'
                ]
            ]);
        }
    }

    public function getrole(Request $request)
    {
        if ($request->has('aId', 'param', true)) {
            return json($this->admin->getrole($request->param('aId')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少管理员Id'
                ]
            ]);
        }

    }

    public function getuser(Request $request)
    {
        if (!$request->has('type', 'param', true)) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少类型参数'
                ]
            ]);
        }
        if ($request->param('type') == 1) {
            $user = User::get($request->param('sId'));

        }
        if ($request->param('type') == 2) {
            $user = \app\admin\model\Admin::get($request->param('sId'));
        }

        if (isset($user) && !is_null($user)) {
            return json([
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $user->getAttr('name')
                ]
            ]);
        }
        return json([
            'value' => false,
            'data' => [
                'message' => '查询失败',
            ]
        ]);
    }

}