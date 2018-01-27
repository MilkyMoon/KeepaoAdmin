<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/12
 * Time: 下午3:40
 */

namespace app\admin\controller;


use think\Request;

class Prlink extends Common
{
    private $prlink;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->prlink = new \app\admin\model\Prlink();
    }

    public function add(Request $request)
    {

        if ($request->isPost())
        {
            if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf'))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '请不要重复提交数据',
                    ]
                ]);
            }
            //session('csrf', md5($_SERVER['REQUEST_TIME_FLOAT']));

            if (!$request->has('rId', 'param', true))
            {
                return json([
                    'value' => false,
                    'data'  => [
                        'message' => '角色Id不能为空'
                    ]
                ]);
            }


            return json($this->prlink->add($request->param('rId'), $request->param('permissions')));
        }
    }

}