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
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('name', 'param', true)) {
                $name = $request->param('name');
                return json($this->permission->select($name, $page));
            } else {
                return json($this->permission->select('', $page));
            }
        } else {
            if ($request->has('name', 'param', true)) {
                $name = $request->param('name');
                return json($this->permission->select($name));
            } else {
                return json($this->permission->select(''));
            }
        }
    }
}