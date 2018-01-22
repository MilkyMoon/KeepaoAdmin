<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 下午4:57
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Memcard extends Model
{
    protected $pk = 'memId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1];

    public function add($data)
    {
        if (isset($data['day'])) {
            if ($this->checkDay($data['day'])) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '要修改的天数已存在'
                    ]
                ];
            }
        }

        $mem = new Memcard;
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;

        $result = $mem->validate(true)->allowField(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $mem->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $mem
            ]
        ];
    }

    private function checkDay($day, $memId = '')
    {
        $mem = Memcard::get([
            'day' => $day
        ]);
        $flag = true;
        if (is_null($mem)) {
            $flag = false;
        } else {
            if (!empty($memId)) {
                if ($mem->getAttr('memId') == $memId) {
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

        $count = Db::table('use_mem')->where('memId', 'in', $data)->where('state', 1)->count();
        if ($count > 0) {
            return [
                'value' => false,
                'data' => [
                    'message' => '会员卡正在被使用，不能删除'
                ]
            ];
        }

        Memcard::destroy($data);
        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function renew($data)
    {
        if (!isset($data['memId']) || empty($data['memId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '会员卡Id不能为空'
                ]
            ];
        }

        if (isset($data['condition'])) {
            if ($this->checkDay($data['day'], $data['memId'])) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '要修改的天数已经存在'
                    ]
                ];
            }
        }

        $mem = new Memcard;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $result = $mem->validate(true)->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $mem->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'message' => [
                'message' => '修改成功'
            ]
        ];
    }

    public function select($data, $page = 1, $limit = 10)
    {
        $memcard = new Memcard;
        if (isset($data['name'])) {
            $memcard = $memcard->where('name', 'like', '%'.$name.'%');//->paginate($limit, false, ['page' => $page]);
        }
        $memcard = $memcard->paginate($limit, false, ['page' => $page]);

        if ($result->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $memcard
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