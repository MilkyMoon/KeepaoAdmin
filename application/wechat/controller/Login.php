<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 17/12/14
 * Time: 上午2:00
 * 后台系统、第三方登录
 */

namespace app\wechat\controller;

use EasyWeChat\Foundation\Application;
use think\Session;

class Login extends Common {

    /**
     * Function: index
     * Author  : PengZong
     * DateTime: ${DATE}
     *
     * 请求授权登录
     *
     * @return $this
     */
    public function wechat_oauth(){
        $conf= config("wechat");
        $app = new Application($conf);

        $oauth = $app->oauth;

        // 未登录
        if (empty(Session::get('wechat_user'))) {
            Session::set('target_url', '/index/login/index');    //session请求地址
            // return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            $oauth->redirect()->send();
            //$response = $app->oauth->scopes(['snsapi_userinfo'])->redirect();
        }

        // 已经登录过
        $user=Session::get('wechat_user');

        return $user;

    }
}