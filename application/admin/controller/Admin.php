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

class Admin extends Common
{
    private $admin;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //throw new HttpResponseException( json('123') );
        $this->admin = new \app\admin\model\Admin();

    }

    public function first(Request $request)
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

    public function select(Request $request)
    {
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('account', 'param', true)) {
                $account = $request->param('account');
                return json($this->admin->select($account, $page));
            } else {
                return json($this->admin->select('', $page));
            }
        } else {
            if ($request->has('account', 'param', true)) {
                $account = $request->param('account');
                return json($this->admin->select($account));
            } else {
                return json([
                    'value' => false,
                    'data' => [
                        'message' => '参数错误'
                    ]
                ]);
            }
        }
    }

    public function add(Request $request)
    {
        return json($this->admin->add($request->param()));
    }

    public function renew(Request $request)
    {
        return json($this->admin->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->admin->delete($request->param('del')));
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