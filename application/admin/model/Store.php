<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午11:19
 */

namespace app\admin\model;


use think\Db;
use think\Exception;
use think\Model;
use think\Request;

class Store extends Model
{
    protected $pk = 'stoId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1, 'moeny' => 0];

//    public function getStateAttr($value)
//    {
//        $status = [1 => '启用', 0 => '注销', 2 => '删除', null => '未知状态'];
//        return $status[$value];
//    }

    public function coupons()
    {
        return $this->belongsToMany('Coupon','sto_con', 'conId', 'stoId');
    }

    public function equs()
    {
        return $this->belongsToMany('Equipment','sto_equ', 'equId', 'stoId');
    }

    public function imgs()
    {
        return $this->belongsToMany('Imgs','sto_img', 'imgId', 'stoId');
    }

    public function add($data)
    {
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $data['stono'] = 'Kp'.strtotime('now').$this->create_key(5);
        $store = new Store;
        $result = $store->allowField(true)->validate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $store->getError()
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
                $tmp['stoId'] = $store->getAttr('stoId');
                $tmp['imgId'] = $a;
                array_push($img, $tmp);
            }
        }
        if (!empty($img))
            Db::table('sto_img')->insertAll($img);
        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $store
            ]
        ];
    }

    public  function checkName($name, $stoId = '')
    {
        $store = Store::get([
            'stoname' => $name
        ]);

        $flag = true;
        if (is_null($store)) {
            $flag = false;
        } else {
            if (!empty($store) && $store->getAttr('stoId') == $stoId) {
                $flag = false;
            }
        }

        return $flag;
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

        $count = Db::table('sto_equ')->where('stoId', 'in', $data)->count();

        if ($count > 0) {
            return [
                'value' => false,
                'data' => [
                    'message' => '商店中还有设备，删除前请先转移或删除设备'
                ]
            ];
        }

        $arr = explode(',', $data);
        $arr = array_unique($arr);
        $arr = array_filter($arr);

        foreach ($arr as $a) {
            $imgs = Db::table('sto_img')->where('stoId', $a)->select();
            foreach ($imgs as $img)
            {
                $tmp = Imgs::get($img['imgId']);
                unlink($tmp->getAttr('path').$tmp->getAttr('name'));
            }
        }


        Db::startTrans();
        try {
            Db::table('store')->where('stoId', 'in', $data)->delete();
            Db::table('sto_img')->where('stoId', 'in', $data)->delete();
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
        if (!isset($data['stoId']) || empty($data['stoId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少主键参数'
                ]
            ];
        }

//        if (isset($data['stoname'])) {
//            if ($this->checkName($data['stoname'], $data['stoId'])) {
//                return [
//                    'value' => false,
//                    'data' => [
//                        'message' => '店名已经存在'
//                    ]
//                ];
//            }
//        }

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

    public function select($data, $page = 1, $limit = 10)
    {
        $store = new Store;
        if (isset($data['search'])) {
            $store = $store->whereOr('stoname', 'like', '%'.$data['search'].'%');
            $store = $store->whereOr('stono', 'like', '%'.$data['search'].'%');
        }

        if (isset($data['id'])) {
            $store = $store->where('stoId', $data['id']);
        }

        $store = $store->paginate($limit, false, ['page' => $page]);

        if ($store->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $store
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

    private function create_key($length)
    {
        $randkey = '';
        for ($i = 0; $i < $length; $i++) {
            $randkey .= chr(mt_rand(48, 57));
        }
        return $randkey;
    }

    public function getcou($stoId, $page = 1, $limit = 10)
    {
        if (empty($stoId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $store = Store::get($stoId);
        if (is_null($store)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '门店不存在'
                ]
            ];
        }
        //dump($admin->roles());
        $coupons = $store->coupons()->paginate($limit, false, ['page' => $page]);
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $coupons
            ]
        ];
    }
    public function getequ($stoId, $page = 1, $limit = 10)
    {
        if (empty($stoId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $store = Store::get($stoId);
        if (is_null($store)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '门店不存在'
                ]
            ];
        }
        //dump($admin->roles());
        $equs = $store->equs()->paginate($limit, false, ['page' => $page]);
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $equs
            ]
        ];
    }

    public function getimg($stoId, $page = 1, $limit = 10)
    {
        if (empty($stoId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $store = Store::get($stoId);
        if (is_null($store)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '门店不存在'
                ]
            ];
        }
        $img = $store->imgs()->paginate($limit, false, ['page' => $page]);
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

    public function getuser($data, $page= 1, $limit = 10)
    {
        $admin = new Admin;

        $result = $admin->where('stoId', $data)->paginate($limit, false, ['page' => $page]);

        return [
            'value' => true,
            'data' => [
                'message' => '',
                'data' => $result
            ]
        ];
    }
}