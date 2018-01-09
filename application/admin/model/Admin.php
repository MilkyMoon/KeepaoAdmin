<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 下午7:43
 */
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //设置主键
    protected $pk = 'sId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

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
        $status = [1 => '初始状态', 2 => '启用', 3 => '注销', null => '未知状态'];
        return $status[$value];
    }

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
    public function getGenderAttr($value)
    {
        $status = [1 => '男', 2 => '女', 0 => '未知', null => '未知'];
        return $status[$value];
    }

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
            return ['value' => $value, 'message' => '没有此用户。'];
        }

        switch ($admin->getData('state')) {
            case 1:
                $message = '你的帐号正在审核中不能进行此操作，请耐心等待。';
                break;
            case 2:
                $value = true;
                break;
            case 3:
                $message = '你的帐号已注销不能进行此操作，如有疑问请联系客服。';
                break;
            default:
                $message = '你的帐号有异常不能进行此操作，如有疑问请联系客服。';
        }
        return ['value' => $value, 'message' => $message];
    }
}