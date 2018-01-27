<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/25
 * Time: 下午9:11
 */

namespace app\admin\model;


use think\Model;

class Etype extends Model
{
    protected $pk = 'conId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1];

    public function add($data)
    {
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $etype = new Etype;
        $result = $etype->validate(true)->allowField(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $etype->getError()
                ]
            ];
        }

        return [
            'value' => true,
            'data' => [
                'message' => '添加成功',
                'data' => $etype
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
        $count = Equipment::where('type', 'in', $data)->count();

        if ($count > 0) {
            return [
                'value' => false,
                'data' => [
                    'message' => '此类型正在使用不能删除'
                ]
            ];
        }
        Etype::destroy($data);
        return [
            'value' => true,
            'data' => [
                'message' => '删除成功'
            ]
        ];
    }

    public function renew($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '类型Id不能为空'
                ]
            ];
        }

        $etype = new Etype;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;

        $result = $etype->allowField(true)->isUpdate(true)->save($data);
        $flag = true;
        //dump($role);
        $msg = '更新成功';
        if (false == $result) {
            $flag = false;
            $msg = $etype->getError();
        }
        //dump($msg);
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
            ]
        ];

    }

    public function select($data, $page = 1, $limit = 10)
    {
        $etype = new Etype;
        if (isset($data['name']))
            $etype = $etype->where('name', 'like', '%'.$data['name'].'%');//->where('type', $type)->order('state')->paginate($limit, false, ['page' => $page]);
        $etype = $etype->paginate($limit, false, ['page' => $page]);
        $flag = false;
        $msg = '没有找到数据';
        if ($etype->count() > 0) {
            $flag = true;
            $msg = '查询成功';
        }
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
                'data' => $etype
            ]
        ];
    }
}