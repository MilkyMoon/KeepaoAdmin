<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/8
 * Time: 上午11:50
 */

namespace app\wechat\controller;


use EasyWeChat\Foundation\Application;
use think\config\driver\Json;

class Menu extends Common {
    public function add_menu(){
        $conf = config("wechat");
        $app = new Application($conf);

        $menu = $app->menu;

//        $content = file_get_contents("php://input");


        $button = [];
        $button = $_POST["buttons"];

//        $buttons = [
//            [
//                "type" => "click",
//                "name" => "今日歌曲",
//                "key"  => "V1001_TODAY_MUSIC"
//            ],
//            [
//                "name"       => "菜单",
//                "sub_button" => [
//                    [
//                        "type" => "view",
//                        "name" => "搜索",
//                        "url"  => "http://www.soso.com/"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "视频",
//                        "url"  => "http://v.qq.com/"
//                    ],
//                    [
//                        "type" => "click",
//                        "name" => "赞一下我们",
//                        "key" => "V1001_GOOD"
//                    ],
//                ],
//            ],
//        ];

        $menu->add($button);
    }

    //获取菜单列表
    public function menu_list(){
        $conf = config("wechat");
        $app = new Application($conf);

        $menu = $app->menu;
        $menus = $menu->all();

        return $menus;
    }

    //删除全部菜单
    public function del_menus(){
        $conf = config("wechat");
        $app = new Application($conf);

        $menu = $app->menu;

        $menu->destroy();
    }

    //按照id删除菜单
    public function del_menu($id){
        $conf = config("wechat");
        $app = new Application($conf);

        $menu = $app->menu;

        $menu->destroy($id);
    }
}