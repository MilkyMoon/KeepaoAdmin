<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午9:36
 */

namespace app\admin\model;


use think\Model;

class Message extends Model
{
    //设置主键
    protected $pk = 'mesId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    public function getStateAttr($value)
    {
        $status = [1 => '启用', 0 => '注销', null => '未知状态'];
        return $status[$value];
    }

    public function add($data)
    {
        $message = new Message;
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;

        $result = $message->validate(true)->allowField()->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $message->getError()
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


    }

    public function renew($data)
    {
        if (!isset($data['mesId']) || empty($data['mesId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => 'mesId不能为空'
                ]
            ];
        }

        $message = new Message;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;

        $result = $message->validate(true)->allowField(true)->isUpdate(true)->save($data);
        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $message->getError()
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

    public function select($data, $page = 1, $limit)
    {
        if (isset($data['title'])) {
            $result = Message::where('titl', 'like', '%'.$data['title'].'%')->paginate($limit, false, ['page' => $page]);
        } else {
            $result = Message::paginate($limit, false, ['page' => $page]);
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
}