<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/17
 * Time: 下午5:05
 */

namespace app\admin\controller;


use app\admin\model\Imgs;
use think\Db;
use think\Request;

class Store extends Common
{
    protected $store;

    protected $equ;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->store = new \app\admin\model\Store();
        $this->equ = new \app\admin\model\Equipment();
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
            return json($this->store->add($request->param()));
        }
    }

    public function update(Request $request)
    {
        return json($this->store->renew($request->param()));
    }

    public function delete(Request $request)
    {
        if ($request->has('del', 'param', true)) {
            return json($this->store->del($request->param('del')));
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
        if ($request->has('search', 'param', true)) {
            $data['search'] = $request->param('search');
        }

        if ($request->has('id', 'param', true)) {
            $data['id'] = $request->param('id');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->store->select($data, $page, $request->param('limit')));
            }
            return json($this->store->select($data, $page));
        }

        return json($this->store->select($data));
    }



    public function addequ(Request $request)
    {
        if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf')) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '请不要重复提交数据',
                ]
            ]);
        }
        session('csrf', md5($_SERVER['REQUEST_TIME_FLOAT']));

        if (!$request->has('stoId', 'param', true)) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少主键参数'
                ]
            ]);
        }

        $equ = $this->equ->add($request->param());

        if (!$equ['value']) {
            return json($equ);
        }

        Db::table('sto_equ')->insert([
            'stoId' => $request->param('stoId'),
            'equId' => $equ['data']['data']->getAttr('equId'),
            'state' => 1
        ]);

        return json([
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $equ['data']['data']
            ]
        ]);
    }

    public function getcou(Request $request)
    {
        if ($request->has('stoId', 'param', true)) {
            $stoId = $request->param('stoId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->store->getcou($stoId, $page, $request->param('limit')));
                }
                return json($this->store->getcou($stoId, $page));
            }
            return json($this->store->getcou($stoId));
        } else {
            return json([
                'value' => false,
                'data' =>[
                    'message' => '店铺Id不能为空'
                ]
            ]);
        }
    }

    public function getequ(Request $request)
    {
        if ($request->has('stoId', 'param', true)) {
            $stoId = $request->param('stoId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->store->getcou($stoId, $page, $request->param('limit')));
                }
                return json($this->store->getcou($stoId, $page));
            }
            return json($this->store->getcou($stoId));
        } else {
            return json([
                'value' => false,
                'data' =>[
                    'message' => '店铺Id不能为空'
                ]
            ]);
        }
    }

    public function getimg(Request $request)
    {
        if ($request->has('stoId', 'param', true)) {
            $stoId = $request->param('stoId');
            if ($request->has('page', 'param', true)) {
                $page = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->store->getimg($stoId, $page, $request->param('limit')));
                }
                return json($this->store->getimg($stoId, $page));
            }
            return json($this->store->getimg($stoId));
        } else {
            return json([
                'value' => false,
                'data' =>[
                    'message' => '店铺Id不能为空'
                ]
            ]);
        }
    }

    public function getuser(Request $request)
    {
        if ($request->has('stoId', 'param', true)) {
            $data = $request->param('stoId');
            if ($request->has('page', 'param', true)) {
                $data = $request->param('page');
                if ($request->has('limit', 'param', true)) {
                    return json($this->store->getuser($data, $page, $request->param('limit')));
                }
                return json($this->store->getimg($data, $page));
            }
            return json($this->store->getimg($data));
        } else {
            return json([
               'value' => false,
               'data' => [
                   'message' => '缺少店铺ID'
               ]
            ]);
        }
    }


}