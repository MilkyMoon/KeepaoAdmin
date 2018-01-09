<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 上午10:40
 */

namespace app\index\controller;

use think\Request;

class Store extends Common {
    public function select(){

        dump(Request::instance()->get());
    }
}