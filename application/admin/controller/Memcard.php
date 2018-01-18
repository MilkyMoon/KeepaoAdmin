<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午4:58
 */

namespace app\admin\controller;

use think\Request;

class Memcard extends Common
{
    private $memcard;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->memcard = new \app\admin\model\Memcard();
    }

    public function select(Request $request)
    {
        $data = [];
        if ($request->has('page', 'param', true)) {
            $data['name'] = $request->param('name');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->memcard->select($data, $page, $request->param('limit')));
            }
            return json($this->memcard->select($data, $page));
        }
        return json($this->memcard->select($data));
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
            return json($this->memcard->add($request->param()));
        }
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->memcard->del($request->param('del')));
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
        return json($this->memcard->renew($request->param()));
    }
}