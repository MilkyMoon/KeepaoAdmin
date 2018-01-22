<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/11
 * Time: 下午4:17
 */

namespace app\index\model;


class Zan extends Common{
    protected $name = 'zan';

    public function whetherhumbUp($uid,$spoId){
        try{
            $data = $this->where('spoId',$spoId)->where('createUser',$uid)->find();

            if($data){
                return true;
            }

            return false;
        }catch (\Exception $e){
            $this->error = '您未对此用户当日排行点赞！';
            return false;
        }
    }
}