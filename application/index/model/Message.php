<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/18
 * Time: 上午1:37
 */

namespace app\index\model;


class Message extends Common{
//    protected $name = 'message';

    public function getMessMail(){
        $data = $this->where('type',1)->where('state',1)->select();
        return $data;
    }
}