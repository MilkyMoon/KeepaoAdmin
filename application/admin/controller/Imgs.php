<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午8:40
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Imgs extends Common
{
    protected $img;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->img = new \app\admin\model\Imgs();
    }

    public function add()
    {
        // 获取表单上传文件
        $files = request()->file('img');
        if (sizeof($files) < 1) {
            return json([
                'value' => false,
                'data' => [
                    'message' => '没有上传图片'
                ]
            ]);
        }
        $data = [];
        $flag = true;
        $message = '';
        foreach($files as $file){
            //dump($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data[] = [
                    'path' => ROOT_PATH . 'public' . DS . 'uploads' . DS,
                    'name' => $info->getSaveName()
                ];
            }else{
                $message = $file->getError();
                $flag = false;
            }
        }
        if (!empty($data)){
            if ($flag) {
                $img = new \app\admin\model\Imgs();
                return json($img->add($data));
            }
        }

        return json([
            'value' => false,
            'data' => [
                'message' => $message
            ]
        ]);
    }

    public function update(Request $request)
    {
        $data = [];
        if ($request->has('url', 'param', true)) {
            $data['url'] = $request->param('url');
        }

        if ($request->has('sort', 'param', true)) {
            $data['sort'] = $request->param('sort');
        }

        if ($request->has('imgId', 'param', true)) {
            $data['imgId'] = $request->param('imgId');
        }

        return json($this->img->renew($data));
    }

    public function delete(Request $request)
    {

        if ($request->has('del', 'param', true) && $request->has('table', 'param', true)) {
            $data['table'] = $request->param('table');
            $data['del'] = $request->param('del');
            return json($this->img->del($data));
        } else {
            return [
                'value' => false,
                'data' =>[
                    'message' => '缺少删除参数'
                ]
            ];
        }
    }

    public function select(Request $request)
    {
        $data = [];

        if ($request->has('imgId', 'param', true)) {
            $data['imgId'] = $request->param('imgId');
        }

        if ($request->has('page', 'param', true)) {
            $page = $request->param('page');
            if ($request->has('limit', 'param', true)) {
                return json($this->img->select($data, $page, $request->param('limit')));
            }
            return json($this->img->select($data, $page));
        }
        return json($this->img->select($data));
    }


}