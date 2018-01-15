<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/15
 * Time: 上午9:55
 */

namespace app\admin\model;


use app\index\model\User;
use think\Db;
use think\Model;

class PointRule extends Model
{
    protected $pk = 'pdId';

    //设置自动插入生成时间
    protected $createTime = 'createTime';

    //设置自动插入修改时间
    protected $updateTime = 'modifyTime';


    public function select($page = 1, $limit = 10)
    {

        $result = PointRule::paginate($limit, false, ['page' => $page]);
        if ($result->count() > 0) {
            return [
                'value' => true,
                'data' => [
                    'messsage' => '查询成功',
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