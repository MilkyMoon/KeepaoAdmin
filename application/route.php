<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


\think\Route::group('admin',[
    //登录
    'login' => ['admin/behavior/login', ['method' => 'post']],
    //注册
    'register' => ['admin/behavior/register'],
    //刷新Token
    'refresh' => ['admin/behavior/refresh', ['method' => 'get']],
    //获取表单令牌
    'getcsrf' => ['admin/common/getcsrf', ['method' => 'get']],

    //后台管理员
    'admin/add' => ['admin/admin/add', ['method' => 'post']],
    'admin/select' => ['admin/admin/select', ['method' => 'get']],
    'admin/delete' => ['admin/admin/delete', ['method' => 'post']],
    'admin/update' => ['admin/admin/update', ['method' => 'post']],
    'admin/getrole' => ['admin/admin/getrole', ['method' => 'get']],
    'admin/addrole' => ['admin/urlink/add'],

    //后台角色
    'role/add' => ['admin/role/add', ['method' => 'post']],
    'role/select' => ['admin/role/select', ['method' => 'get']],
    'role/delete' => ['admin/role/delete', ['method' => 'post']],
    'role/update' => ['admin/role/update', ['method' => 'post']],
    'role/getper' => ['admin/role/getper', ['method' => 'get']],
    'role/addper' => ['admin/prlink/add'],

    //后台权限
    'permission/select' => ['admin/permission/select', ['method' => 'get']],

    //1:平台信息，2:常见问题，3:门店配置
    'config/add' => ['admin/config/add', ['method' => 'post']],
    'config/select' => ['admin/config/select', ['method' => 'get']],
    'config/delete' => ['admin/config/delete', ['method' => 'post']],
    'config/update' => ['admin/config/update', ['method' => 'post']],

//    '__miss__' => 'admin/behavior/miss',
]);

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    // 【微信】网页授权登录
    'wechat/login/oauth' => ['wechat/login/wechat_oauth', ['method' => 'GET']],
    // 【微信】网页授权自动登录
    'wechat/login/relogin' => ['wechat/login/relogin', ['method' => 'POST']],


    // 【前台】添加用户
    'index/user/add' => ['index/user/add_user', ['method' => 'POST']],
    // 【前台】查找用户
    'index/user/find' => ['index/user/find_user', ['method' => 'GET']],
    // 【前台】修改用户
    'index/user/update' => ['index/user/update_user', ['method' => 'POST']],

    // 【前台】查询排行榜
    'index/chart/select' => ['index/chart/chart_select', ['method' => 'POST']],
    'index/chart/user' => ['index/chart/user_select', ['method' => 'POST']],

    // 【前台】附近门店获取
    'index/store/select' => ['index/store/store_select', ['method' => 'GET']],
    // 【前台】门店搜索
    'index/store/search' => ['index/store/search', ['method' => 'GET']],

    'test' => ['admin/test/index', ['method' => 'GET|POST']],
    // MISS路由
//    '__miss__' => 'admin/behavior/miss',

];
