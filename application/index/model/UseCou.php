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

    public function useCou($param){
        try{
            $result = $this->where('useId',$param['uId'])->where('couId',$param['couId'])->save($param);
            if (false == $result) {
                return false;
            }

            return true;
        }catch (\Exception $e){
            $this->error = '使用失败！';
            return false;
        }
    }
}