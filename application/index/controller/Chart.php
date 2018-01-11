<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/11
 * Time: 上午10:11
 */

namespace app\index\controller;

use app\index\model\Sport;

/**
 * Class Chart
 *
 * 排行榜
 *
 * @package app\index\controller
 */
class Chart extends Common {
    public function select(){
        $chart = new Sport();
        $param = $this->param;


    }
}