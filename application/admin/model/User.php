<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/18
 * Time: 上午10:00
 */

namespace app\admin\model;


use think\Model;

class User extends Model
{
    protected $pk = 'uId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    protected $readonly = ['name', 'openId'];

    public function select($data, $page = 1, $limit = 10)
    {
        $user = new User;
        if (isset($data['name'])) {
            $user = $user->whereOr('name', 'like', '%'.$data['name'].'%');
        }

        $user = $user->paginate($limit, false, ['page' => $page]);

        if ($user->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'message' => '查询成功',
                    'data' => $user
                ]
            ];
        }
        return [
            'value' => true,
            'data' => [
                'message' => '查询失败',
                'data' => $user
            ]
        ];
    }

    public function renew($data)
    {
        if (!isset($data['uId']) || empty($data['uId'])) {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少主键参数'
                ]
            ];
        }

        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;
        $user = new User;

        $result = $user->allowField(true)->isUpdate(true)->save($data);

        if (false == $result) {
            return [
                'value' => false,
                'data' => [
                    'message' => $user->getError()
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
}