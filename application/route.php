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
    'login' => ['admin/behavior/login', ['method' => 'post']],
    'register' => ['admin/behavior/register'],
    'first' => ['admin/admin/first'],
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
    'wechat/login/oauth' => ['wechat/login/wechat_oauth', ['method' => 'POST']],

    // 【前台】附近门店获取
    'index/store/select' => ['index/store/select', ['method' => 'GET']],
    // 【前台】门店搜索
    'index/store/search' => ['index/store/search', ['method' => 'GET']],

    'test' => ['admin/test/index', ['method' => 'GET|POST']],
    // MISS路由
//    '__miss__' => 'admin/behavior/miss',

];
