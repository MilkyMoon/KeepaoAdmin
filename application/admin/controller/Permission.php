<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/12
 * Time: 上午12:35
 */

namespace app\admin\controller;


use think\Request;

class Permission extends Common
{
    private $permission;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->permission = new \app\admin\model\Permission();
    }

    public function select(Request $request)
    {
        $data = [];

        if ($request->has('name', 'param', true)) {
            $data['name'] = $request->param('name');
        }

        if ($request->has('all', 'param', true)) {
            $data['all'] = $request->param('all');
        }

        if ($request->has('state', 'param', true)) {
            $data['state'] = $request->param('state');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->permission->select($data, $page, $request->param('limit')));
            }
            return json($this->permission->select($data, $page));
        }
        return json($this->permission->select($data));
    }
}