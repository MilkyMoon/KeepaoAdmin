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

    //获取表单令牌
    'getuser' => ['admin/admin/getuser', ['method' => 'get']],

    //后台管理员
    'admin/add' => ['admin/admin/add', ['method' => 'post']],
    'admin/selectAll' => ['admin/admin/selectAll', ['method' => 'get']],
    'admin/selectByName' => ['admin/admin/selectByName', ['method' => 'get']],
    'admin/delete' => ['admin/admin/delete', ['method' => 'post']],
    'admin/update' => ['admin/admin/update', ['method' => 'post']],
    'admin/getrole' => ['admin/admin/getrole', ['method' => 'get']],
    'admin/addrole' => ['admin/urlink/add'],

    //后台角色
    'role/add' => ['admin/role/add', ['method' => 'post']],
    'role/selectAll' => ['admin/role/selectAll', ['method' => 'get']],
    'role/selectByName' => ['admin/role/selectByName', ['method' => 'get']],
    'role/delete' => ['admin/role/delete', ['method' => 'post']],
    'role/update' => ['admin/role/update', ['method' => 'post']],
    'role/getper' => ['admin/role/getper', ['method' => 'get']],
    'role/addper' => ['admin/prlink/add'],

    //后台权限
    'permission/selectAll' => ['admin/permission/selectAll', ['method' => 'get']],
    'permission/selectByName' => ['admin/permission/selectByName', ['method' => 'get']],

    //1:平台信息，2:常见问题，3:门店配置
    'config/add' => ['admin/config/add', ['method' => 'post']],
    'config/selectAll' => ['admin/config/selectAll', ['method' => 'get']],
    'config/selectByName' => ['admin/config/selectByName', ['method' => 'get']],
    'config/delete' => ['admin/config/delete', ['method' => 'post']],
    'config/update' => ['admin/config/update', ['method' => 'post']],

    //积分规则
    'prule/selectAll' => ['admin/pointRule/selectAll', ['method' => 'get']],
    'prules/add' => ['admin/pointRuleDet/add', ['method' => 'post']],
    'prules/selectAll' => ['admin/pointRuleDet/selectAll', ['method' => 'get']],
    'prules/delete' => ['admin/pointRuleDet/delete', ['method' => 'post']],
    'prules/update' => ['admin/pointRuleDet/update', ['method' => 'post']],

    //会员卡
    'memcard/add' => ['admin/memcard/add', ['method' => 'post']],
    'memcard/selectAll' => ['admin/memcard/selectAll', ['method' => 'get']],
    'memcard/selectByName' => ['admin/memcard/selectByName', ['method' => 'get']],
    'memcard/delete' => ['admin/memcard/delete', ['method' => 'post']],
    'memcard/update' => ['admin/memcard/update', ['method' => 'post']],

    //优惠券
    'coupon/add' => ['admin/coupon/add', ['method' => 'post']],
    'coupon/selectAll' => ['admin/coupon/selectAll', ['method' => 'get']],
    'coupon/delete' => ['admin/coupon/delete', ['method' => 'post']],
    'coupon/update' => ['admin/coupon/update', ['method' => 'post']],

    //优惠券
    'message/add' => ['admin/message/add', ['method' => 'post']],
    'message/selectAll' => ['admin/message/selectAll', ['method' => 'get']],
    'message/delete' => ['admin/message/delete', ['method' => 'post']],
    'message/update' => ['admin/message/update', ['method' => 'post']],

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
    // 【前台】门店详情
    'index/store/details' => ['index/store/store_details', ['method' => 'GET']],
    // 【前台】门店搜索
    'index/store/search' => ['index/store/search', ['method' => 'GET']],

    'test' => ['admin/test/index', ['method' => 'GET|POST']],
    // MISS路由
//    '__miss__' => 'admin/behavior/miss',

];
