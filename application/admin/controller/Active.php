<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/22
 * Time: 上午10:39
 */

namespace app\admin\controller;


use think\Request;

class Active extends Common
{
    private $active;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->active = new \app\admin\model\Active();
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
            return json($this->active->add($request->param()));
        }
    }

    public function update(Request $request)
    {
        return json($this->active->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->active->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少删除字段'
                ]
            ]);
        }
    }

    public function select(Request $request)
    {
        $data = [];
        if ($request->has('actId', 'param', true)) {
            $data['actId'] = $request->param('actId');
        }
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->active->select($data, $page, $request->param('limit')));
            }
            return json($this->active->select($data, $page));
        }

        return json($this->active->select($data));
    }

    public function getimg(Request $request)
    {
        if ($request->has('actId', 'param', true)) {
            $actId = $request->param('actId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->active->getimg($actId, $page, $request->param('limit')));
                }
                return json($this->active->getimg($actId, $page));
            }
            return json($this->active->getimg($actId));
        } else {
            return json([
                'value' => false,
                'data' =>[
                    'message' => '活动Id不能为空'
                ]
            ]);
        }
    }
}