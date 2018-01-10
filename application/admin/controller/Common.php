<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 下午8:31
 */

namespace app\admin\controller;

use app\admin\model\Admin;
use app\common\controller\Token;
use think\Exception;
use think\Request;

class Common
{
    /**
     * Function: checkAuth
     * Description: 检查ID为$sId的用户是否 有$rule权限
     * Author  : wry
     * DateTime: 18/1/3 下午10:13
     *
     * @param $sId 用户Id
     * @param $rule 权限规则
     *
     * @return string json数组 value ：是否有权限 message : 错误信息
     * @throws \think\exception\DbException
     */
    public function checkAuth($sId, $rule)
    {
        dump(Request::instance()->module());
        //检查用户状态
        $adminState = Admin::checkAdminState($sId);
        if (!$adminState['value']) {
            return json_encode($adminState, JSON_UNESCAPED_UNICODE);
        }

        $admin = Admin::get($sId);
        if (Count($admin->roles) == 0) {
            return json_encode(['value' => false, 'message' => '你还没有被赋予角色不能进行此操作，如有疑问请与超级管理员联系。'], JSON_UNESCAPED_UNICODE);
        }

        if (is_string($rule)) {

            //获取角色集合
            $roles = [];

            foreach ($admin->roles as $role) {
                if ($role->getData($role->getStateStr())) {
                    array_push($roles, $role);
                }
            }
            if (Count($roles) == 0) {
                return json_encode(['value' => false, 'message' => '你被赋予角色已经注销，如有疑问请与超级管理员联系。'], JSON_UNESCAPED_UNICODE);
            }

            //获取权限集合
            $permissions = [];
            foreach ($roles as $role) {
                foreach ($role->permissions as $permission) {
                    if ($permission->getData($permission->getStateStr())) {
                        array_push($permissions, $permission->getData($permission->getSelect()));
                    }
                }
            }

            $permissions = array_unique($permissions);
            if (in_array($rule, $permissions)) {
                return json_encode(['value' => true, 'message' => ''], JSON_UNESCAPED_UNICODE);
            }

        }

        return json_encode(['value' => false, 'message' => '你没有此操作的权限，如有疑问请与超级管理员联系。'], JSON_UNESCAPED_UNICODE);
    }

    public function login()
    {
        $_token = Request::instance()->param('_token');
        //判断token是否为空
        if (!isset($_token) || empty($_token))
        {
            if (!Request::instance()->has('username', 'post')) {
                return json_encode([
                    'value' => false,
                    'message' => '用户名不能为空'
                ], JSON_UNESCAPED_UNICODE);
            }

            if (!Request::instance()->has('password', 'post')) {
                return json_encode([
                    'value' => false,
                    'message' => '密码不能为空'
                ], JSON_UNESCAPED_UNICODE);
            }

            $username = Request::instance()->param('username');
            $password = md5(Request::instance()->param('password'));

            $admin = Admin::get([
                'account' => $username,
                'password' => $password
            ]);

            if (is_null($admin)) {
                return json_encode([
                    'value' => false,
                    'message' => '用户名或密码错误'
                ], JSON_UNESCAPED_UNICODE);
            }
            $iat = strtotime('now');
            $exp = strtotime(date('Y-m-d',strtotime("+1day", $iat)));
            $_token = Token::get_token($admin->account, $iat, $exp, Request::instance()->header()['host']);
            dump($_token);
            return json_encode([
                'value' => false,
                'message' => $_token
            ], JSON_UNESCAPED_UNICODE);

        } else
        {
            return Token::check_token($_token);
        }
    }

    public function register()
    {
        if (Request::instance()->isGet()) {
            $_token = Request::instance()->token();
            return json_encode([
                'value' => true,
                'message' => $_token
            ], JSON_UNESCAPED_UNICODE);
        }

        if (Request::instance()->isPost()) {
            $_token = Request::instance()->param('_token');
            if (!isset($_token) || empty($_token)) {
                if ($_token != session('__token__')) {
                    return json_encode([
                        'value' => false,
                        'message' => ''
                    ], JSON_UNESCAPED_UNICODE);
                }
                if (!Request::instance()->has('username', 'post')) {
                    return json_encode([
                        'value' => false,
                        'message' => '用户名不能为空'
                    ], JSON_UNESCAPED_UNICODE);
                }
                if (!Request::instance()->has('password', 'post')) {
                    return json_encode([
                        'value' => false,
                        'message' => '密码不能为空'
                    ], JSON_UNESCAPED_UNICODE);
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
                    return json_encode([
                        'value' => true,
                        'message' => '注册成功'
                    ], JSON_UNESCAPED_UNICODE);
                } catch (Exception $e) {
                    return json_encode([
                        'value' => false,
                        'message' => '注册失败'
                    ], JSON_UNESCAPED_UNICODE);
                }
            }
            return json_encode([
                'value' => false,
                'message' => ''
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function miss()
    {
        return json_encode(['value' => false, 'message' => '请求接口错误，请查看文档或与管理员联系。'], JSON_UNESCAPED_UNICODE);
    }
}