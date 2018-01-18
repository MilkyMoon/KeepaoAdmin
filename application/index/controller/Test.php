<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/18
 * Time: 上午3:26
 */

namespace app\index\controller;


class Test extends Common{
    public function test(){
        $user = new \app\index\model\User();

        $list = $user->getDataById('1');
        $data['data'] = $list;
        $data['count'] = sizeof($list);

        return result_array(['data' => $data]);
    }
}