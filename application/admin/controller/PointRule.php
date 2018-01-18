<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 上午10:39
 */

namespace app\admin\controller;


use think\Request;

class PointRule extends Common
{
    private $pointRule;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->pointRule = new \app\admin\model\PointRule();
    }

    public function select(Request $request) {
        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->pointRule->select($page, $request->param('limit')));
            }
            return json($this->pointRule->select($page));
        }
        return json($this->pointRule->select());
    }
}