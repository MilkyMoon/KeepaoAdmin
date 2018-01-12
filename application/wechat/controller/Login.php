<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 17/12/14
 * Time: 上午2:00
 * 后台系统、第三方登录
 */

namespace app\wechat\controller;

use app\common\controller\Token;
use app\index\model\User;
use EasyWeChat\Foundation\Application;
use think\Request;
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
            Session::set('target_url', '/wechat/login/wechat_oauth');    //session请求地址
            // return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            $oauth->redirect()->send();
            //$response = $app->oauth->scopes(['snsapi_userinfo'])->redirect();
        }

        // 已经登录过
        $wechatuser=Session::get('wechat_user');
        $original = $wechatuser['original'];

        $userModel = new User();
        $param['openId'] = $original['openid'];
        $user = $userModel->findUser($param);

        if (!$user){  //第一次授权时将用户天骄到数据库
            $param['openId']  = $original['openid'];
            $param['gender']  = $original['sex'];
            $param['heading'] = $original['headimgurl'];
            $param['name']    = $original['nickname'];

            $user = $userModel->addUser($param);

            if (!$user){
                return result_array(['error' => $userModel->getError()]);
            }
        } else { //用户已经授权过的，查询用户信息，始终保证前端获取到的是最新信息
            $user = $userModel->findUser($param);

            if (!$user){
                return result_array(['error' => $userModel->getError()]);
            }
        }

        $data['data'] = $user;

        $iat = strtotime('now');  //发放时间
        $exp = strtotime('+1 week', $iat);  //失效时间
        //生成token
        $data['token'] = Token::get_token($user['openId'],$iat,$exp,Request::instance()->header()['host']);


        return result_array(['data' => $data]);
    }

}