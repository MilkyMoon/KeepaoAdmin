<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/8
 * Time: 上午5:27
 */

namespace app\wechat\controller;

use EasyWeChat\Message\Text;

class Message extends Common {
    public function text(){
        $text = new Text(['content' => '您好！']);
    }
}