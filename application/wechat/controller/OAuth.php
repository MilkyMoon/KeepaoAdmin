<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/5
 * Time: 上午11:53
 */

namespace app\wechat\controller;

use EasyWeChat\Foundation\Application;
use think\Session;


class OAuth extends Common {
        /**
     * Function: oauth_callback
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 授权成功回调
     */
    function oauth_callback(){
        $conf= config("wechat");
        $app = new Application($conf);
        $oauth = $app->oauth;

        $user = $oauth->user();  //获取已授权的用户
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用

        Session::set('wechat_user',$user);
        $targetUrl =empty(Session::get('target_url')) ? '/index.php/index/oauth/oauth_success' : Session::get('target_url');
        header('location:'. $targetUrl); // 跳转到 user/profile
    }

    //授权成功之后开始输出信息
    function oauth_success(){
        $user=Session::get('wechat_user');
        //将用户的基本信息保存在数据库中，然后提供下次进行使用
        print_r($user);
    }
}