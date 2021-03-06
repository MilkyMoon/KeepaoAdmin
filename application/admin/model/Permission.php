<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/3
 * Time: 上午9:45
 */
namespace app\admin\model;

use think\Model;

class Permission extends Model
{
    //设置主键
    protected $pk = 'sId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //设置检索字段
    private $select = 'rule';

    //设置状态字段
    protected $stateStr = 'state';

    public function getStateStr()
    {
        return $this->stateStr;
    }


    public function getSelect()
    {
        return $this->select;
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
//        $status = [1 => '启用', 0 => '注销', null => '未知状态'];
//        return $status[$value];
//    }

    public function select($data, $page = 1, $limit = 10)
    {
        $permission = new Permission;

        if (isset($data['name']))
            $permission = $permission->where('name', 'like', '%'.$data['name'].'%');
        if (isset($data['state']))
            $permission = $permission->where('state', $data['state']);

        if (isset($data['all']) && $data['all'] == 1)
            $result = $permission->order('sort desc')->select();
        else
            $result  = $permission->where('state', 1)->order('sort desc')->paginate($limit, false, ['page' => $page]);
        return [
            'value' => true,
            'data' => [
                'message' => '',
                'data' => $result
            ]
        ];
    }
}