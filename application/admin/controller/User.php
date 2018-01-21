<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午11:05
 */

namespace app\admin\controller;


use think\Request;

class User extends Common
{
    protected $user;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->user = new \app\admin\model\User();
    }

    public function select(Request $request)
    {
        $data = [];
        if ($request->has('name', 'param', true)) {
            $data['name'] = $request->param('name');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->user->select($data, $page, $request->param('limit')));
            }
            return json($this->user->select($data, $page));
        }
        
        return json($this->user->select($data));
    }

    public function update(Request $request)
    {
        return json($this->user->renew($request->param()));
    }
}