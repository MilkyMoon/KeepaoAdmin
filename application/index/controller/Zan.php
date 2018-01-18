<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/13
 * Time: 下午3:22
 */

namespace app\index\controller;

use app\index\model;

class Zan extends Common{
    /**
     * Function: thumb_up
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 点赞
     */
    public function thumb_up(){
        $zan = new model\Zan();
        $param = $this->param;

        $uid   = !empty($param['uid']) ? $param['uid'] : '';
        $spoId = !empty($param['spoid']) ? $param['spoid'] : '';

        $data  = $zan->thumbUp($uid);

        return result_array(['data' => $data]);
    }
}