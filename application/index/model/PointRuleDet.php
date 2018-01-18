<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/18
 * Time: 上午2:52
 */

namespace app\index\model;


class PointRuleDet extends Common {
    public function findRule($prid,$condition){
        try{
            $data = $this->field('prdId,prId,condition,reward')->where('prId',$prid)->where('condition',$condition)->find();
            return $data;
        }catch (\Exception $e){
            $this->error = "查询规则失败！";
            return false;
        }
    }
}