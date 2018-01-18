<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午4:41
 */

namespace app\admin\controller;


use think\Request;

class Coupon extends Common
{
    public $coupon;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->coupon = new \app\admin\model\Coupon();
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
            return json($this->coupon->add($request->param()));
        }
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->coupon->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少删除参数'
                ]
            ]);
        }

    }

    public function update(Request $request)
    {
        return json($this->coupon->renew($request->param()));
    }

    public function selectAll(Request $request)
    {
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->coupon->select($page, $request->param('limit')));
            }
            return json($this->coupon->select($page));
        }
        return json($this->coupon->select());
    }
}