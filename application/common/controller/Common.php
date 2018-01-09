<?php

/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 上午10:29
 */
namespace app\common\controller;

use think\Controller;
use think\Request;

class Common extends Controller {
    public function _initialize()
    {
        parent::_initialize();
        /*防止跨域*/
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
        header('Content-Type:text/html; charset=utf-8');
    }
}