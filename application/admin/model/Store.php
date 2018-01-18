<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/16
 * Time: 下午11:19
 */

namespace app\admin\model;


use think\Model;

class Store extends Model
{
    protected $pk = 'stoId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';

    //添加默认值
    protected $insert = ['state' => 1, 'money' => 0];

    public function getStateAttr($value)
    {
        $status = [1 => '启用', 0 => '注销', 2 => '删除', null => '未知状态'];
        return $status[$value];
    }

    public function add($data)
    {

    }

    public function del($data)
    {

    }

    public function renew($data)
    {

    }

    public function select($data)
    {

    }

}