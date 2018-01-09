<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/8
 * Time: 上午2:34
 */

namespace app\wechat\controller;

use Hooklife\ThinkphpWechat\Wechat;
use EasyWeChat\Foundation\Application;
use think\Config;

class Wx extends Common {
    public function serve(){
        $conf= Config::get("wechat");

//        $options = [
//            'app_id'  => 'wxbc45d17cfa983e08',                                      // AppID
//            'secret'  => 'd2de6b7da64a225c08a6ab3e788e986e',                        // AppSecret
//            'token'   => 'FlappyWorld',                                             // Token
//            'aes_key' => 'bdjFkM6kWxx7TGYTdOQ6SyOCTLuNVWWRkovM1cSH6rA',             // EncodingAESKey，安全模式下请一定要填写！！！
//            'log' => [
//                'level' => 'debug',
//                'file' => '/tmp/easywechat.log',
//            ],
//            // ...
//        ];

        $app = new Application($conf);
        $server = $app->server;

        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....

            return "您好！欢迎关注我!".$message;
        });

//        $user = $app->user;
//
//        $server->setMessageHandler(function($message) use ($user) {
//            $fromUser = $user->get($message->FromUserName);
//
//            return "{$fromUser->nickname} 您好！欢迎关注 !";
//        });

        $server->serve()->send();
    }

    public function index(){
        return "123123";
    }
}