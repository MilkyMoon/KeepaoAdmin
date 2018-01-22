<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 下午10:34
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Equipment extends Model
{
    protected $pk = 'equId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function imgs()
    {
        return $this->belongsToMany('Imgs','equ_img', 'imgId', 'stoId');
    }

    public function add($data)
    {
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $equ = new Equipment;
        $result = $equ->allowField(true)->validate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $equ->getError()
                ]
            ];
        }
        $img = [];
        if (isset($data['imgs']) && !empty($data['imgs'])) {
            $arr = explode(',', $data['imgs']);
            $arr = array_unique($arr);
            $arr = array_filter($arr);
            foreach ($arr as $a) {
                $tmp = [];
                $tmp['equId'] = $equ->getAttr('stoId');
                $tmp['imgId'] = $a;
                array_push($img, $tmp);
            }
        }
        if (!empty($img))
            Db::table('equ_img')->insertAll($img);
        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $equ
            ]
        ];
    }

    public function del($data)
    {
        if (empty($data)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '删除参数不能为空'
                ]
            ];
        }


        $arr = explode(',', $data);
        $arr = array_unique($arr);
        $arr = array_filter($arr);

        foreach ($arr as $a) {
            $imgs = Db::table('equ_img')->where('stoId', $a)->select();
            foreach ($imgs as $img)
            {
                $tmp = Imgs::get($img['imgId']);
                unlink($tmp->getAttr('path').$tmp->getAttr('name'));
            }
        }

        Db::startTrans();
        try {
            Db::table('equipment')->where('equId', 'in', $data)->delete();
            Db::table('sto_equ')->where('equId', 'in', $data)->delete();
            Db::commit();
        }  catch (Exception $e) {
            Db::rollback();
            return [
                'value' => false,
                'data' => [
                    'message' => '删除失败'
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function renew($data)
    {
        if (!isset($data['equId']) || empty($data['equId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少主键参数'
                ]
            ];
        }

        $img = [];
        if (isset($data['imgs']) && !empty($data['imgs'])) {
            $arr = explode(',', $data['imgs']);
            $arr = array_unique($arr);
            $arr = array_filter($arr);
            foreach ($arr as $a) {
                $tmp = [];
                $tmp['equId'] = $data['equId'];
                $tmp['imgId'] = $a;
                array_push($img, $tmp);
            }
        }
        if (!empty($img))
            Db::table('equ_img')->insertAll($img);

        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $equ = new Equipment;

        $result = $equ->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $equ->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '修改成功'
            ]
        ];
    }

    public function select($data, $page = 1, $limit = 10)
    {
        $equ = new Equipment;
        if (isset($data['equno'])) {
            $equ = $equ->whereOr('equno', 'like', '%'.$data['equno'].'%');
        }

        if (isset($data['equId'])) {
            $equ = $equ->whereOr('equId', 'like', '%'.$data['equId'].'%');
        }

        $equ = $equ->paginate($limit, false, ['page' => $page]);

        if ($equ->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $equ
                ]
            ];
        }
        return [
            'value' => true,
            'data' => [
                'message' => '查询失败'
            ]
        ];
    }

    public function getimg($equId, $page = 1, $limit = 10)
    {
        if (empty($equId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $equ = Equipment::get($equId);
        if (is_null($equ)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '门店不存在'
                ]
            ];
        }
        $img = $equ->imgs()->paginate($limit, false, ['page' => $page]);
        $arr = [];

        foreach ($img as $i) {
            $tmp = $i->getData();
            $a = [];
            $a["imgId"] = $tmp["imgId"];
            $a["imgurl"] = Request::instance()->server('http_host').DS.'images'.DS.$tmp['name'];
            $a["url"] = $tmp["url"];
            $a["sort"] = $tmp["sort"];
            $a["createUser"] = $tmp["createUser"];
            $a["createTime"] = $tmp["createTime"];
            $a["modifyUser"] = $tmp["modifyUser"];
            $a["modifyTime"] = $tmp["modifyTime"];
            $a["createType"] = $tmp["createType"];
            $a["modifyType"] = $tmp["modifyType"];
            array_push($arr, $a);
        }
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $arr
            ]
        ];
    }
}