<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午4:42
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Coupon extends Model
{
    //设置主键
    protected $pk = 'couId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1, 'send' => 0];

//    public function getStateAttr($value)
//    {
//        $status = [1 => '启用', 0 => '注销', 2 => '删除', null => '未知状态'];
//        return $status[$value];
//    }

    public function add($data)
    {
        if (isset($data['condition']) && isset($data['discount']) && isset($data['startDate'])) {
            if ($this->check($data['condition'], $data['discount'], $data['startDate'])) {
                return [
                    'value' => false,
                    'data'  => [
                        'message' => '已有相同优惠券'
                    ]
                ];
            }
        }

        if (isset($data['stoId'])) {
            $store = Store::get($data['stoId']);
            if (is_null($store)) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '没有此门店'
                    ]
                ];
            }
        }
        $coupon = new Coupon;
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;

        $result = $coupon->validate(true)->allowField(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $coupon->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $coupon
            ]
        ];
    }

    protected function check($condition, $discount, $startDate,$conId = '') {
        $coupon = Coupon::get([
            'condition' => $condition,
            'discount' => $condition,
            'state' => 1
        ]);
        $flag = true;
        if (is_null($coupon)) {
            $flag = false;
        } else {
            if (!empty($sId)) {
                if ($coupon->getData('conId') == $conId) {
                    $flag = false;
                }
            } else {
                if (strtotime($startDate) > strtotime($coupon->getAttr('endDate'))) {
                    $flag = false;
                }
            }
        }

        return $flag;
    }

    public function del($data)
    {
        if (empty($data)) {
            return [
                'value' => false,
                'data' =>[
                    'message' => '缺少删除参数'
                ]
            ];
        }

        $count = Db::table('use_cou')->alias('uc')->join('coupon w', 'w.couId = uc.couId')->where('w.couId', 'in', $data)->whereTime('w.endDate', '>=', date('Y-m-d', strtotime('now')))->count();

        if ($count > 0) {
            return [
                'value' => false,
                'data' => [
                    'message' => '优惠券正在使用，不能删除'
                ]
            ];
        }

        Db::table('coupon')->where('couId', 'in', $data)->update(['state' =>  2]);
        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function renew()
    {
        if (!isset($data['couId']) || empty($data['couId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少couId参数'
                ]
            ];
        }

        if (isset($data['condition']) && isset($data['discount']) && isset($data['startDate'])) {
            if ($this->check($data['condition'], $data['discount'], $data['startDate'], $data['couId'])) {
                return [
                    'value' => fasle,
                    'data'  => [
                        'message' => '已有相同优惠券'
                    ]
                ];
            }
        }

        $coupon = new Coupon;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $result = $coupon->validate(true)->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => fasle,
                'data' => [
                    'message' => $coupon->getError()
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

    public function select($page = 1, $limit = 10)
    {
        $result = Coupon::paginate($limit, false, ['page' => $page]);
        $flag = false;
        $msg = '没有找到数据';
        if ($admin->count() > 0) {
            $flag = true;
            $msg = '查询成功';
        }
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
                'data' => $result
            ]
        ];
    }

    public function selectDet($couId, $page = 1, $limit = 10)
    {
        $result = Db::table('cou_det')->where('couId', $couId)->paginate($limit, false, ['page' => $page]);
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $result
            ]
        ];
    }
}