<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 下午4:36
 */
namespace app\admin\model;

use think\Db;
use think\model\Pivot;

class Prlink extends Pivot
{
    public function add($rId, $permisssions)
    {
        $role = Role::get($rId);
        if (is_null($role)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色不存在'
                ]
            ];
        }

        $arr = explode(',', $permisssions);
        $arr = array_unique($arr);
        //判读权限是否在数据库中
        $count = Db::table('permission')->where('sId', 'in', $arr)->count();
        if (Count($arr) != $count) {
            return [
                'value' => false,
                'data' => [
                    'message' => '权限数据错误'
                ]
            ];
        }

        Db::startTrans();
        try {
            $data = [];
            foreach ($arr as $a) {
                array_push($data, [
                    'rId' => $rId,
                    'pId' => $a,
                    'createUser' => session('sId'),
                    'modifyUser' => session('sId'),
                    'createType' => 2,
                    'modifyTime' => date('Y-m-d G:i:s', strtotime('now')),
                    'modifyType' => 2
                ]);
            }
            //dump($data);
            Db::table('prlink')->where('rId', $rId)->delete();
            Db::table('prlink')->insertAll($data);
        } catch (\Exception $e) {
            Db::rollback();
            return [
                'value' => false,
                'data' => [
                    'message' => '添加失败'
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '添加成功'
            ]
        ];
    }
}