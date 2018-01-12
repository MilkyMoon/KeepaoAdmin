<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/10
 * Time: 下午7:38
 */

namespace app\admin\controller;

use app\admin\model\Admin;
use app\common\controller\Token;
use think\Controller;
use think\Exception;
use think\exception\HttpResponseException;
use think\Request;

class Behavior
{

    public function login()
    {

        if (!Request::instance()->has('account', 'post')) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '用户名不能为空'
                ]
            ]);
        }

        if (!Request::instance()->has('password', 'post')) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '密码不能为空'
                ]
            ]);
        }

        $account = Request::instance()->param('account');
        $password = md5(Request::instance()->param('password'));

        $admin = Admin::get([
            'account' => $account,
            'password' => $password
        ]);

        if (is_null($admin)) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '用户名或密码错误'
                ]
            ]);
        }

        try{
            $admin->key = $this->create_key(6);
            $admin->save();
        } catch (Exception $exception)
        {
            return json([
                'value' => false,
                'data' => [
                    'message' => $exception
                ]
            ]);
        }

        $iat = strtotime('now');
        $exp = strtotime("+1 week", $iat);
        $refresh_token = Token::get_token($admin->sId, $iat, $exp, Request::instance()->header()['host'], $admin->key);
        $exp = strtotime(date('Y-m-d',strtotime("+1 day", $iat)));
        $access_token = Token::get_token($admin->sId, $iat, $exp, Request::instance()->header()['host']);
        session('sId', $admin->sId);
        return json([
            'value' => true,
            'data' => [
                'message' => '登录成功',
                'access_token' => $access_token,
                'refresh_token' => $refresh_token
            ]
        ]);
    }

    public function register()
    {
        if (Request::instance()->isGet()) {
            $_token = Request::instance()->token();
            return json([
                'value' => true,
                'data' => [
                    'message' => $_token
                ]
            ]);
        }

        if (Request::instance()->isPost()) {
            $_token = Request::instance()->param('_token');
            if (!isset($_token) || empty($_token)) {
                if ($_token != session('__token__')) {
                    return json([
                        'value' => false,
                        'data' => [
                            'message' => '请求失败'
                        ]
                    ]);
                }
                if (!Request::instance()->has('username', 'post')) {
                    return json([
                        'value' => false,
                        'data' => [
                            'message' => '用户名不能为空'
                        ]
                    ]);
                }
                if (!Request::instance()->has('password', 'post')) {
                    return json([
                        'value' => false,
                        'data' => [
                            'message' => '密码不能为空'
                        ]
                    ]);
                }

                $username = Request::instance()->param('username');
                $password = md5(Request::instance()->param('password'));

                $admin = new Admin;
                $admin->data([
                    'account' => $username,
                    'password' => $password
                ]);

                try{
                    $admin->save();
                    return json([
                        'value' => true,
                        'data' => [
                            'message' => '注册成功'
                        ]
                    ]);
                } catch (Exception $e) {
                    return json([
                        'value' => false,
                        'data' => [
                            'message' => '注册失败'
                        ]
                    ]);
                }
            }
            return json([
                'value' => false,
                'data' => [
                    'message' => ''
                ]
            ]);
        }
    }

    public function miss()
    {
        return json([
            'value' => false,
            'data' => [
                'message' => '请求接口错误，请查看文档或与管理员联系。'
            ]
        ]);
    }

    private function create_key($length)
    {
        $randkey = '';
        for ($i = 0; $i < $length; $i++) {
            $randkey .= chr(mt_rand(33, 126));
        }
        return md5($randkey);
    }

    public function refresh(Request $request)
    {
        if ($request->has('refresh-token', 'header', true)) {
            return json(Token::refresh_token($request->header('refresh-token')));
        }
        return json([
            'value' => false,
            'data' => [
                'message' => '缺少refresk_token',
            ]
        ]);
    }

    public function getcsrf()
    {
        if (!session('?csrf')) {
            $csrf = md5($_SERVER['REQUEST_TIME_FLOAT']);
            session('csrf', $csrf);
        }
        return json([
            'value' => true,
            'data' => [
                'message' => '返回csrf',
                'csrf' => session('csrf')
            ]
        ]);
    }
}