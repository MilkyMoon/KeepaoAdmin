<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 上午9:46
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Role extends Model
{
    //设置主键
    protected $pk = 'sId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1];

    //设置状态字段
    protected $stateStr = 'state';

    public function getStateStr()
    {
        return $this->stateStr;
    }

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
    public function getStateAttr($value)
    {
        $status = [1 => '启用', 0 => '注销', null => '未知状态'];
        return $status[$value];
    }

    /**
     * Function: permissions
     * Description: 多对多关系描述
     * Author  : wry
     * DateTime: 18/1/3 下午5:38
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('Permission','\app\admin\model\Prlink', 'pId', 'rId');
    }


    public function select($name, $page = 1)
    {
        if (!empty($name))
            $role = Role::where('name', 'like', '%'.$name.'%')->order('state,sort')->paginate(10, false, ['page' => $page]);
        else
            $role = Role::order('state')->paginate(10, false, ['page' => $page]);
        $flag = false;
        $msg = '没找到数据';
        if ($role->count() > 0) {
            $flag = true;
            $msg = '';
        }
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
                'data' => $role
            ]
        ];
    }


    public function add($data)
    {
        if (isset($data['name']))
        {
            if ($this->checkAccount($data['name'])['value'])
            {
                return [
                    'value' => false,
                    'data'  => [
                        'message' => '角色已存在'
                    ]
                ];
            }
        }

        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $role = new Role;

        $result = $role->validate(true)->allowField(true)->save($data);
        $flag = true;
        $msg = '添加成功';
        if (false == $result) {
            $flag = false;
            $msg = $role->getError();
        }

        return [
            'value' => $flag,
            'data' => [
                'message' => $msg
            ]
        ];
    }

    /**
     * Function: checkAccount
     * Description: 检查$account变量中的数据在数据中是否存在
     * Author  : wry
     * DateTime: 18/1/11 上午12:29
     *
     * @param $accunt
     *
     * @return bool
     * @throws \think\exception\DbException
     */
    public function checkAccount($name, $sId = '') {
        $role = Role::get([
            'name' => $name
        ]);
        $flag = true;
        $msg = '账号已存在';
        if (is_null($role)) {
            $flag = false;
            $msg = '';
        } else {
            if (!empty($sId)) {
                if ($role->getAttr('sId') == $sId) {
                    $flag = false;
                    $msg = '';
                }
            }
        }

        return [
            'value' => $flag,
            'data' => [
                'message' => $msg
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
        //dump($data);
        $arr = explode(',', $data);
        $arr = array_unique($arr);
        $arr = array_filter($arr);
        foreach ($arr as $a) {
            $tmp = Urlink::get([
                'rId' => $a
            ]);
            if (!is_null($tmp)) {
                $tmp = Role::get($a);
                return  [
                    'value' => false,
                    'data' => [
                        'message' => '角色:<'.$tmp->getAttr('name').'>, 已关联到后台管理员,不能删除'
                    ]
                ];
            }
        }

        Db::startTrans();
        try {
            Db::table('role')->delete($arr);
            foreach ($arr as $a) {
                Db::table('prlink')->where(['rId' => (int)$a])->delete();
            }
            Db::commit();
        } catch (\Exception $e) {
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
        if (!isset($data['sId']) || empty($data['sId']))
        {
            return [
                'value' => false,
                'data' => [
                    'message' => '缺少主键'
                ]
            ];
        }

        if (isset($data['name']))
        {
            if ($this->checkAccount($data['name'], $data['sId'])['value'])
            {
                return [
                    'value' => false,
                    'data'  => [
                        'message' => '角色已存在'
                    ]
                ];
            }
        }
        $role = new Role;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;

        $result = $role->allowField(true)->isUpdate(true)->save($data);
        $flag = true;
        //dump($role);
        $msg = '更新成功';
        if (false == $result) {
            $flag = false;
            $msg = '更新失败';
        }
        //dump($msg);
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,

            ]
        ];

    }

    public function getper($rId)
    {
        if (empty($rId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '角色Id不能为空'
                ]
            ];
        }

        $role = Role::get($rId);
        if (is_null($role)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '用户不存在'
                ]
            ];
        }
        //dump($admin->roles());
        $permissions = [];
        foreach ($role->permissions as $permission) {
            if ($permission->getData($permission->getStateStr())) {
                array_push($permissions,[
                    'sId' => $permission->getData('sId'),
                    'name' => $permission->getData('name')
                ]);
            }
        }
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $permissions
            ]
        ];
    }
}