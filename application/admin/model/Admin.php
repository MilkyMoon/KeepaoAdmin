<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 下午7:43
 */
namespace app\admin\model;

use think\Db;
use think\Exception;
use think\exception\DbException;
use think\Model;
use think\Request;

class Admin extends Model
{
    //设置主键
    protected $pk = 'sId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1, 'gender' => 0, 'money' => 0];

    //设置状态字段
    protected $stateStr = 'state';

    //类型数组
    protected $statusArr = [1 => '初始状态', 2 => '启用', 3 => '注销', null => '未知状态'];
    protected $genderArr = [1 => '男', 2 => '女', 0 => '未知', null => '未知'];


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
//    public function getStateAttr($value)
//    {
//        return $this->statusArr[$value];
//    }

    /**
     * Function: getGenderAttr
     * Description: tp5获取器根据数据库取出相应的字段的值 自动匹配对应字符串
     * Author  : wry
     * DateTime: 18/1/3 上午10:33
     *
     * @param $value 由tp5自动注入
     *
     * @return mixed 返回$status数组中对应value
     */
//    public function getGenderAttr($value)
//    {
//        return $this->genderArr[$value];
//    }

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
//    public function getTypeAttr($value)
//    {
//        $status = [1 => '直营', 2 => '加盟', null => '未知状态'];
//        return $status[$value];
//    }

    /**
     * Function: roles
     * Description: 多对多关系描述
     * Author  : wry
     * DateTime: 18/1/3 下午5:38
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Role','\app\admin\model\Urlink', 'rId', 'aId');
    }

    /**
     * Function: checkAdminState
     * Description: 检查用户Id为$Id的用户，帐号的状态
     * Author  : wry
     * DateTime: 18/1/3 下午9:08
     *
     * @param $id 用户Id
     *
     * @return array value 是否可用 message 错误信息
     * @throws \think\exception\DbException
     */
    public static function checkAdminState($id)
    {
        $value = false;
        $message = '';

        $admin = Admin::get($id);

        if ($admin == null) {
            return [
                'value' => $value,
                'data' => [
                    'message' => '没有此用户。'
                ]
            ];
        }

        switch ($admin->getData('state')) {
            case 0:
                $message = '你的帐号已注销不能进行此操作，如有疑问请联系客服。';
                break;
            case 1:
                $value = true;
                break;
            default:
                $message = '你的帐号有异常不能进行此操作，如有疑问请联系客服。';
        }
        return [
            'value' => $value,
            'data' => [
                'message' => $message
            ]
        ];
    }

    public function select($data, $page = 1, $limit = 10)
    {
        $admin = new Admin;
        if (isset($data['search'])) {
            $admin = $admin->where('account', 'like', '%'.$data['search'].'%');
            $admin = $admin->whereOr('name', 'like', '%'.$data['search'].'%');
        }
            //->paginate($limit, false, ['page' => $page]);
        if (isset($data['state']) && $data['state'] != 2)
            $admin = $admin->where('state', $data['state']);
        $admin = $admin->where('state', '<>', 2)->paginate($limit, false, ['page' => $page]);
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
                'data' => $admin
            ]
        ];
    }


    public function add($data)
    {
        if (isset($data['account']))
        {
            if ($this->checkAccount($data['account'])['value'])
            {
                return [
                    'value' => false,
                    'data'  => [
                        'message' => '帐户已存在'
                    ]
                ];
            }
        }
        if (isset($data['password']))
        {
            $data['password'] = md5($data['password']);
        }
        $data['createUser'] = session('sId');
        $data['modifyUser'] = session('sId');
        $data['createType'] = 2;
        $data['modifyType'] = 2;
        $user = new Admin;

        $result = $user->validate(true)->allowField(true)->save($data);
        $flag = true;
        $msg = '添加成功';
        if (false == $result) {
            $flag = false;
            $msg = $user->getError();
        }

        return [
            'value' => $flag,
            'data' => [
                'message' => $msg,
                'data' => $user
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
    public function checkAccount($accunt, $sId = '') {
        $admin = Admin::get([
            'account' => $accunt
        ]);
        $flag = true;
        $msg = '账号已存在';
        if (is_null($admin)) {
            $flag = false;
            $msg = '';
        } else {
            if (!empty($sId)) {
                if ($admin->getAttr('sId') == $sId) {
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

        Db::startTrans();
        try {
            $arr = explode(",", $data);
            $arr = array_unique($arr);
            $arr = array_filter($arr);

            Db::table('admin')->where('sId', 'in', $arr)->update(['state' => 2, 'account' => '']);
            foreach ($arr as $a) {
                Db::table('urlink')->where(['aId' => (int)$a])->delete();
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
        if (isset($data['state']) && $data['state'] == 0) {
            if (1 == $data['sId']) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '不能注销超级管理员'
                    ]
                ];
            }

            if (session('sId') == $data['sId']) {
                return [
                    'value' => false,
                    'data' => [
                        'message' => '不能注销自己'
                    ]
                ];
            }
        }

        if (isset($data['password']))
        {
            $data['password'] = md5($data['password']);
        }

        if (isset($data['account']))
        {
            if ($this->checkAccount($data['account'], $data['sId'])['value'])
            {
                return [
                    'value' => false,
                    'data'  => [
                        'message' => '帐户已存在'
                    ]
                ];
            }
        }
        $admin = new Admin;
        $data['modifyUser'] = session('sId');
        $data['modifyType'] = 2;

        $result = $admin->allowField(true)->isUpdate(true)->save($data);
        $flag = true;
        $msg = '更新成功';
        if (false == $result) {
            $flag = false;
            $msg = '更新失败';
        }
        //dump($msg);
        return [
            'value' => $flag,
            'data' => [
                'message' => $msg
            ]
        ];
    }

    public function getrole($aId)
    {

        if (empty($aId)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '管理员Id不能为空'
                ]
            ];
        }

        $admin = Admin::get($aId);
        if (is_null($admin)) {
            return [
                'value' => false,
                'data' => [
                    'message' => '用户不存在'
                ]
            ];
        }
        //dump($admin->roles());
        $roles = [];
        foreach ($admin->roles as $role) {
            if ($role->getData($role->getStateStr())) {
                array_push($roles,[
                    'sId' => $role->getData('sId'),
                    'name' => $role->getData('name')
                ]);
            }
        }
        return [
            'value' => true,
            'data' => [
                'message' => '查询成功',
                'data' => $roles
            ]
        ];
    }
}