<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/22
 * Time: 上午10:40
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Active extends Model
{
    protected $pk = 'actId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function imgs()
    {
        return $this->belongsToMany('Imgs','act_img', 'imgId', 'actId');
    }

    public function add($data)
    {
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $active = new Active;
        $result = $active->allowField(true)->validate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $active->getError()
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
                $tmp['actId'] = $active->getAttr('actId');
                $tmp['imgId'] = $a;
                array_push($img, $tmp);
            }
        }
        if (!empty($img))
            Db::table('act_img')->insertAll($img);
        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $active
            ]
        ];
    }

    public function renew()
    {
        if (!isset($data['actId']) || empty($data['actId'])) {
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
                $tmp['stoId'] = $data['stoId'];
                $tmp['imgId'] = $a;
                array_push($img, $tmp);
            }
        }
        if (!empty($img))
            Db::table('sto_img')->insertAll($img);

        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $store = new Store;

        $result = $store->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $store->getError()
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
            $imgs = Db::table('act_img')->where('actId', $a)->select();
            foreach ($imgs as $img)
            {
                $tmp = Imgs::get($img['imgId']);
                unlink($tmp->getAttr('path').$tmp->getAttr('name'));
            }
        }


        Db::startTrans();
        try {
            Db::table('active')->where('stoId', 'in', $data)->delete();
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

    public function select($data, $page = 1, $limit = 10)
    {
        $active = new Active;

        if (isset($data['actId'])) {
            $active = $active->where('actId', $data['actId']);
        }

        $active = $active->paginate($limit, false, ['page' => $page]);
        if ($active->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $active
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

    public function getimg($actId, $page = 1, $limit = 10)
    {
        if (empty($actId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $actId = Active::get($actId);
        if (is_null($actId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '活动不存在'
                ]
            ];
        }
        $img = $actId->imgs()->paginate($limit, false, ['page' => $page]);
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