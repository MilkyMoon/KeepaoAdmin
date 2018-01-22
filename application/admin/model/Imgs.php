<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午7:21
 */

namespace app\admin\model;


use think\Db;
use think\Model;
use think\Request;

class Imgs extends Model
{
    protected $pk = 'imgId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function add($data)
    {
        $arr = [];

        foreach ($data as $d)
        {
            $d['createUser'] = session('sId');
            $d['modifyUser'] = session('sId');
            $d['createType'] = 2;
            $d['modifyType'] = 2;
            array_push($arr, $d);
        }
        $img = new Imgs;

        $result = $img->allowField(true)->saveAll($arr);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $img->getError()
                ]
            ];
        }
        $arr = [];
        foreach ($result as $r) {
            $a = [];
            $a['imgUrl'] = Request::instance()->server('http_host').DS.'images'.DS.$r['name'];
            $a['imgId'] = $r['imgId'];
            array_push($arr, $a);
        }
        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $arr
            ]
        ];
    }

    public function renew($data)
    {
        if (!isset($data['imgId']) || empty($data['imgId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少主键参数'
                ]
            ];
        }
        $stoimg = new Imgs;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $result = $stoimg->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $img->getError()
                ]
            ];
        }
        return [
            'value' => true,
            'data' => [
                'message' => '修改成功',
                'data' => $result
            ]
        ];
    }

    public function del($data)
    {
        $table = ['sto_img', 'equ_img'];
        if  (empty($data)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少删除参数'
                ]
            ];
        }

        if ($data['table'] < 0 || $data['table'] > sizeof($table)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '表参数错误'
                ]
            ];
        }

        $arr = explode(',', $data['del']);
        $arr = array_unique($arr);
        $arr = array_filter($arr);

        foreach ($arr as $a) {
            $img = Imgs::get($a);
            if (!is_null($img)) {
                unlink($img->getAttr('path').$img->getAttr('name'));
            }
        }
        Db::table($table[$data['table']])->where('imgId', 'in', $data['del'])->delete();
        Imgs::destroy($data);
        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function select($data, $page = 1, $limit = 10)
    {
        $img = new Imgs;

        if (isset($data['imgId'])) {
            $img = $img->where('imgId', $data['imgId']);
        }

        $img = $img->order('sort desc')->paginate($limit, false, ['page' => $page]);

        if ($img->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $img
                ]
            ];
        }

        return [
            'value' => false,
            'data' => [
                'message' => '查询失败'
            ]
        ];
    }
}