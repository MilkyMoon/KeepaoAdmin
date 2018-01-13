<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/13
 * Time: 下午8:50
 */

namespace app\admin\controller;


use think\Request;

class Config extends Common
{
    private $config;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->config = new \app\admin\model\Config();
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
            return json($this->config->add($request->param()));
        }
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->config->del($request->param('del')));
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
        return json($this->config->renew($request->param()));
    }

    public function select(Request $request)
    {
        if ($request->has('type', 'param', true)) {
            $type = $request->param('type');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('name', 'param', true)) {
                    $name = $request->param('name');

                    return json($this->config->select($name, $type, $page));
                } else {
                    return json($this->config->select('', $type, $page));
                }
            } else {
                if ($request->has('name', 'param', true)) {
                    $name = $request->param('name');

                    return json($this->config->select($name, $type));
                } else {
                    return json($this->config->select('', $type));
                }
            }
        } else {
            return json([
                'value' => false,
                'data' => [
                    'message' => '类型参数不能为空'
                ]
            ]);
        }
    }

}