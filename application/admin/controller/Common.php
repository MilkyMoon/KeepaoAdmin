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
use think\Controller;
use think\Exception;
use think\exception\HttpResponseException;
use think\Request;

class Common extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
        header('Content-Type:text/html; charset=utf-8');

        dump(session('sId'));
        //查看请求是否携带Token
        if (!$request->has('access-token', 'header', true)) {
            throw new HttpResponseException(json([
                'value' => false,
                'data' => [
                    'message' => '非法请求'
                ]
            ]));
        }

        $check_token = Token::check_token($request->header('access-token'));


        if (!$check_token['value']) {
            throw new HttpResponseException(json([
                'value' => false,
                'data' => [
                    'message' => 'token验证失败'
                ]
            ]));
        }

        //验证权限
        $path = Request::instance()->pathinfo();
        dump($path);
        $check_auth = $this->checkAuth(session('sId'), $path);

        if (!$check_auth['value']) {
            throw new HttpResponseException(json([
                'value' => false,
                'data' => [
                    'message' => $check_auth['data']['message']
                ]
            ]));
        }
    }

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
        //检查用户状态
        $adminState = Admin::checkAdminState($sId);
        if (!$adminState['value']) {
            return $adminState;
        }

        $admin = Admin::get($sId);
        if (Count($admin->roles) == 0) {
            return [
                'value' => false,
                'data' => [
                    'message' => '你还没有被赋予角色不能进行此操作，如有疑问请与超级管理员联系。'
                ]
            ];
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
                return [
                    'value' => false,
                    'data' => [
                        'message' => '你被赋予角色已经注销，如有疑问请与超级管理员联系。'
                    ]
                ];
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
            dump($permissions);
            if (in_array($rule, $permissions)) {
                return [
                    'value' => true,
                    'data' => [
                        'message' => '验证成功'
                    ]
                ];
            }
        }

        return [
            'value' => false,
            'data' => [
                'message' => '你没有此操作的权限，如有疑问请与超级管理员联系。'
            ]
        ];
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