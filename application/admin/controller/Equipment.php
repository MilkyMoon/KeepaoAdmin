<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午10:34
 */

namespace app\admin\controller;


use think\Request;

class Equipment extends Common
{
    protected $equipment;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->equipment = new \app\admin\model\Equipment();
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
            return json($this->equipment->add($request->param()));
        }
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->equipment->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少删除字段'
                ]
            ]);
        }
    }

    public function update(Request $request)
    {
        return json($this->equipment->renew($request->param()));
    }

    public function select(Request $request)
    {
        $data = [];
        if ($request->has('equId', 'param', true)) {
            $data['equId'] = $request->param('equId');
        }

        if ($request->has('equno', 'param', true)) {
            $data['equno'] = $request->param('equno');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->equipment->select($data, $page, $request->param('limit')));
            }
            return json($this->equipment->select($data, $page));
        }
        return json($this->equipment->select($data));
    }

    public function getimg(Request $request)
    {
        if ($request->has('equId', 'param', true)) {
            $equId = $request->param('equId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->equipment->getimg($equId, $page, $request->param('limit')));
                }
                return json($this->equipment->getimg($equId, $page));
            }
            return json($this->equipment->getimg($equId));
        } else {
            return json([
                'value' => false,
                'data' =>[
                    'message' => '店铺Id不能为空'
                ]
            ]);
        }
    }
}