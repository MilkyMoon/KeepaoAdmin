<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/5
 * Time: 上午11:55
 */

namespace app\index\controller;

use Hooklife\ThinkphpWechat\Wechat;
use think\Request;

class Common extends \app\common\controller\Common {
    public $param;
    public function _initialize(){
        parent::_initialize();

        $param =  Request::instance()->param();
        $this->param = $param;
    }
}