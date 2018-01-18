<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午2:58
 */

namespace app\admin\controller;


use think\Request;

class PointRuleDet extends Common
{
    private $pointRuleDet;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->pointRuleDet = new \app\admin\model\PointRuleDet();
    }

    public function select(Request $request)
    {
        if ($request->has('prId', 'param', true))
        {
            $type = $request->param('prId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->pointRuleDet->select($type, $page, $request->param('limit')));
                }
                return json($this->pointRuleDet->select($type, $page));
            }
            return json($this->pointRuleDet->select($type));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '规则类型不能为空'
                ]
            ]);
        }
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
            return json($this->pointRuleDet->add($request->param()));
        }
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->pointRuleDet->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '删除参数不能为空'
                ]
            ]);
        }
    }

    public function update(Request $request)
    {
        return json($this->pointRuleDet->renew($request->param()));
    }
}