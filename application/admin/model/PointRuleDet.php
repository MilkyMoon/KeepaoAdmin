<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午2:58
 */

namespace app\admin\model;


use think\Model;

class PointRuleDet extends Model
{
    protected $pk = 'prdId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function add($data)
    {
        if (isset($data['condition'])) {
            if ($this->checkAccount($data['condition'])) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '条件已经存在'
                    ]
                ];
            }
        }
        $pointRuleDet = new PointRuleDet;
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;

        $result = $pointRuleDet->allowField(true)->validate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $pointRuleDet->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $pointRuleDet
            ]
        ];
    }

    public function checkAccount($condition, $prdId = '') {
        $pointRuleDet = PointRuleDet::get([
            'condition' => $condition
        ]);
        $flag = true;
        if (is_null($pointRuleDet)) {
            $flag = false;
        } else {
            if (!empty($prdId)) {
                if ($pointRuleDet->getAttr('prdId') == $prdId) {
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
                'data' => [
                    'message' => '删除参数不能为空'
                ]
            ];
        }
        PointRuleDet::destroy($data);
        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function renew($data)
    {
        if (isset($data['prId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => 'prId参数不能为修改'
                ]
            ];
        }

        if (!isset($data['prdId']) || empty($data['prdId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => 'prdId参数不能为空'
                ]
            ];
        }

        if (isset($data['condition'])) {
            if ($this->checkAccount($data['condition'], $data['prdId'])) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '条件已经存在'
                    ]
                ];
            }
        }

        $pointRuleDet = new PointRuleDet;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;

        $result = $pointRuleDet->validate('PointRuleDet.edit')->allowField(true)->isUpdate(true)->save($data);

        $flag = true;
        //dump($role);
        $msg = '更新成功';
        if (false == $result) {
            $flag = false;
            $msg = $pointRuleDet->getError();
        }
        //dump($msg);
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
            ]
        ];
    }

    public function select($type, $page = 1, $limit = 10)
    {
        if (!empty($type))
            $result = PointRuleDet::where('prId', $type)->paginate($limit, false, ['page' => $page]);
        else
            $result = paginate($limit, false, ['page' => $page]);
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