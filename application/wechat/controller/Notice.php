<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/8
 * Time: 下午4:10
 */

namespace app\wechat\controller;

use EasyWeChat\Foundation\Application;

class Notice extends Common {
    /**
     * Function: template
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 有链接模板消息
     *
     * @param openid     string
     * @param templateId string
     * @param url        string
     * @param data       array
     */
    public function template_url(){
        $conf = config("wechat");
        $app = new Application($conf);
        $notice = $app->notice;

        $userId = 'osFjFuHygwzSCuIYFUuHI92qMN8M';
        $templateId = 'fSLITkNEKbP5W6H-BDzT0BLet9laKln27GR34ArAcYw';
        $url = 'http://overtrue.me';
        $data = array(
            "first"  => "恭喜你购买成功！",
            "keywords1"   => "巧克力",
            "remark" => "欢迎再次购买！",
        );

        $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }

    /**
     * Function: template
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 非链接模板消息
     */
    public function template(){
        $conf = config("wechat");
        $app = new Application($conf);
        $notice = $app->notice;

        $messageId = $notice->send([
            'touser' => 'osFjFuHygwzSCuIYFUuHI92qMN8M',
            'template_id' => 'fSLITkNEKbP5W6H-BDzT0BLet9laKln27GR34ArAcYw',
            'url' => 'http://www.baidu.com',
            'data' => [
                "first"  => "恭喜你购买成功！",
                "keywords1"   => "巧克力",
                "remark" => "欢迎再次购买！",
            ],
        ]);
    }
}