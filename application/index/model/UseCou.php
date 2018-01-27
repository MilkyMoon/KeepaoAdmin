<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/27
 * Time: 上午12:50
 */

namespace app\index\model;


class UseCou extends Common{
    protected $name = 'use_cou';

    public function hasCou($uid,$couid){
        try{
            $data = $this->where('useId',$uid)->where('couId',$couid)->find();

            if($data){
                return true;
            }

            return false;
        }catch (\Exception $e){
            $this->error = '您未拥有此优惠券！';
            return false;
        }
    }
}