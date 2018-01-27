<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 下午4:36
 */
namespace app\admin\model;

use think\Db;
use think\Model;
use think\model\Pivot;

class Urlink extends Pivot
{
    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function add($aId, $roles)
    {
        $admin = Admin::get($aId);
        if (is_null($admin)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '用户不存在'
                ]
            ];
        }


        $arr = explode(',', $roles);
        $arr = array_unique($arr);
        $arr = array_filter($arr);
//        foreach ($arr as $a) {
//            $tmp = Role::get($a);
//            if (is_null($tmp)) {
//                return [
//                    'value' => false,
//                    'data' => [
//                        'message' => '角色数据错误'
//                    ]
//                ];
//            }
//        }
        //判读角色是否在数据库中1
        if (!empty($data)) {
            $count = Db::table('role')->where('sId', 'in', $arr)->count();
            if (Count($arr) != $count) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '角色数据错误'
                    ]
                ];
            }
        }


        Db::startTrans();
        try {
            $data = [];
            foreach ($arr as $a) {
                array_push($data, [
                    'aId' => $aId,
                    'rId' => $a,
                    'createUser' => session('sId'),
                    'modifyUser' => session('sId'),
                    'createType' => 2,
                    'modifyTime' => date('Y-m-d G:i:s', strtotime('now')),
                    'modifyType' => 2
                ]);
            }
            //dump($data);
            Db::table('urlink')->where('aId', $aId)->delete();
            if (empty($roles)) {
                Db::commit();
                return [
                    'value' => true,
                    'data' => [
                        'message' => '删除成功'
                    ]
                ];
            }
            Db::table('urlink')->insertAll($data);
            Db::commit();
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