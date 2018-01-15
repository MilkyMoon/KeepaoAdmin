<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/10
 * Time: 上午11:10
 */

namespace app\admin\controller;


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

    public function selectByAcc(Request $request)
    {
        if ($request->has('account', 'param', true)) {
            $account = $request->param('account');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->admin->select($account, $page, $request->param('limit')));
                }
                return json($this->admin->select($account, $page));
            }
            return json($this->admin->select($account));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '账号不能为空'
                ]
            ]);
        }
    }

    public function selectAll(Request $request) {
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->admin->select('', $page, $request->param('limit')));
            }
            return json($this->admin->select('', $page));
        }
        return json($this->admin->select(''));
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

}