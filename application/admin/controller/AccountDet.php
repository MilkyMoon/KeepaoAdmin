<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 上午12:07
 */

namespace app\admin\controller;


use think\exception\ErrorException;
use think\exception\HttpResponseException;
use think\Request;

class AccountDet extends Common
{
    private $accountDet;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->check($request);
        $this->accountDet = new \app\admin\model\AccountDet();
    }


    protected function check(Request $request)
    {
        $admin = \app\admin\model\Admin::get([
            'sId' => session('sId')
        ]);

        $flag = false;
        if (is_null($admin) || $admin->getData('type') != 1) {
            if ($request->has('useId', 'param', true)) {
                $flag = true;
            }
            if ($request->has('stoId', 'param', true)) {
                if (session('sId') != $request->param('stoId')) {
                    $flag = true;
                }
            } else {
                $flag = true;
            }
        }

        if ($flag) {
            throw new HttpResponseException(json([
                'value' => false,
                'data' => [
                    'message' => '您没有此权限'
                ]
            ]));
        }
    }

    public function select(Request $request)
    {
        if (!$request->has('time', 'param', true)) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少时间参数'
                ]
            ]);
        }

        $time = strtotime($request->param('time'));
        if (!$time) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '时间格式错误'
                ]
            ]);
        }

        $data = [];
        if ($request->has('stoId', 'param', true)) {
            $data['stoId'] = $request->param('stoId');
        }

        if ($request->has('useId', 'param', true)) {
            $data['useId'] = $request->param('useId');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->accountDet->select($data, $time, $page, $request->param('limit')));
            }
            return json($this->accountDet->select($data, $time, $page));
        }
        return json($this->accountDet->select($data, $time));
    }


    public function selectRange(Request $request)
    {
        $flag = true;
        if (!$request->has('startTime', 'param', true)) {
            $flag = false;
        }
        if (!$request->has('endTime', 'param', true)) {
            $flag = false;
        }
        if (!$flag) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '缺少时间参数'
                ]
            ]);
        }
        $time[0] = strtotime($request->param('startTime'));
        $time[1] = strtotime($request->param('endTime'));
        if (!($time[0] && $time[1])) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '时间格式错误'
                ]
            ]);
        }

        $data = [];
        if ($request->has('stoId', 'param', true)) {
            $data['stoId'] = $request->param('stoId');
        }

        if ($request->has('useId', 'param', true)) {
            $data['useId'] = $request->param('useId');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->accountDet->selectRange($data, $time, $page, $request->param('limit')));
            }
            return json($this->accountDet->selectRange($data, $time, $page));
        }
        return json($this->accountDet->select($data, $time));
    }

}