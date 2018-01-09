<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/7
 * Time: 下午3:27
 */

namespace app\wechat\controller;

use Hooklife\ThinkphpWechat\Wechat;
use EasyWeChat\Foundation\Application;

class Material extends Common {
    //上传图片
    public function img_upload(){
        $conf= config("wechat");
        $app = new Application($conf);

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

        $result = $app->material->uploadImage($file);

        //var_dump($result);
    }

    //上传视频
    public function video_upload(){
        $conf= config("wechat");
        $app = new Application($conf);

        $file = request()->file('file');

        $result = $app->material->uploadImage($file);
    }

    //上传音频
    public function voice_upload(){
        $conf= config("wechat");
        $app = new Application($conf);

        $file = request()->file('image');

        $result = $app->material->uploadImage("/path/to/your/image.jpg");
    }

    //获取素材文件列表
    public function material_list(){
        $conf= config("wechat");
        $app = new Application($conf);

        $list = $app->material->list('image', 0, 10);
    }
}