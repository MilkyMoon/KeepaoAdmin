<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午8:37
 */

namespace app\admin\controller;


use think\Request;

class Message extends Common
{
    private $message;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->message = new \app\admin\model\Message();
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
            return json($this->message->add($request->param()));
        }
    }

    public function update(Request $request)
    {
        return json($this->message->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->message->del($request->param('del')));
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '删除字段不能为空'
                ]
            ]);
        }
    }

    public function select(Request $request)
    {
        if (!$request->has('type', 'param', true)) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '类型参数不能为空'
                ]
            ]);
        }
        $type = $request->param('type');
        $data = [];
        if ($request->has('title', 'param', true)) {
            $data['title'] = $request->param('title');
        }
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->message->select($data, $type, $page, $request->param('limit')));
            }
            return json($this->message->select($data, $type, $page));
        }
        return json($this->message->select($data, $type));
    }
}