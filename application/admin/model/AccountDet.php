<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/13
 * Time: 上午11:01
 */

namespace app\admin\model;


use think\Model;

class AccountDet extends Model
{
    protected $pk = 'adId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    /**
     * Function: getStateAttr
     * Description: tp5获取器根据数据库取出相应的字段的值 自动匹配对应字符串
     * Author  : wry
     * DateTime: 18/1/3 上午10:33
     *
     * @param $value 由tp5自动注入
     *
     * @return mixed 返回$status数组中对应value
     */
    public function getTypeAttr($value)
    {
        $status = [1 => '购买会员', 2 => '缴纳押金', 3 => '退押金', 4 => '充值', 5 => '提现', null => '未知'];
        return $status[$value];
    }

    public function select($data, $time, $page = 1, $limit = 10) {
        if (isset($data['stoId'])) {
            $result = AccountDet::where('stoId', $data['stoId'])->whereTime('createTime', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }

        if (isset($data['useId'])) {
            $result = AccountDet::where('useId', $data['useId'])->whereTime('createTime', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }
        if (!isset($result)) {
            $result = AccountDet::whereTime('createTime', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }

        if ($result->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $result
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


    public function selectRange($data, $time, $page = 1, $limit = 10) {
        if (isset($data['stoId'])) {
            $result = AccountDet::where('stoId', $data['stoId'])->whereTime('createTime', 'between', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }

        if (isset($data['useId'])) {
            $result = AccountDet::where('useId', $data['useId'])->whereTime('createTime', 'between', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }

        if (!isset($result)) {
            $result = AccountDet::whereTime('createTime', 'between', $time)->order('createTime desc')->paginate($limit, false, ['page' => $page]);
        }
        dump(123);
        if ($result->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $result
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