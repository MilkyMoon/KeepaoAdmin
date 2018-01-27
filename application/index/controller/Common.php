<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/5
 * Time: 上午11:55
 */

namespace app\index\controller;

use app\common\controller\Token;
use Hooklife\ThinkphpWechat\Wechat;
use think\exception\HttpResponseException;
use think\Request;

class Common extends \app\common\controller\Common {
    public $param;
    public $header;
    public function _initialize(){
        parent::_initialize();

        $param  =  Request::instance()->param();
        $header = Request::instance()->header();
//        $this->header = $header;
        $this->param = $param;
    }

    protected $beforeActionList = [
        'first'  =>  ['only'=>'thumb_up,update_user,find_user,store_details,store_select,statistics,user_select,message_add,cou_select,message_select,chart_select,cou_select,usercou_select,get_coupon,equ_select'],   //需要验证请求是否合法的方法

        'second' =>  ['only'=>'thumb_up,set_goal,message_add,get_coupon'], //需要添加数据的时候默认参数的方法
        'third'  =>  ['only'=>'update_user']    //需要更新数据的时候默认参数的方法
    ];

    //判断请求是否合法
    protected function first(){
        $param = $this->param;

        $token = !empty($param['token']) ? $param['token'] : '';
        $data = '';
        if ($token){
            $data = Token::encode_token_user($param['token']);
        }

        if (!$data){
            throw new HttpResponseException(result_array(['error' => '用户未授权']));
        }
    }

    //添加数据的时候默认参数
    public function second(){
        $param = $this->param;

        $uid = !empty($param['uId']) ? $param['uId'] : '';

        if(!$uid){
            throw new HttpResponseException(result_array(['error' => $param]));
        }

        $param['createUser'] = $param['uId'];
        $param['createTime'] = date("Y-m-d H:i:s");
        $param['modifyUser'] = $param['uId'];
        $param['modifyTime'] = date("Y-m-d H:i:s");
        $param['createType'] = 1;
        $param['modifyType'] = 1;

        $this->param = $param;
    }

    //更新数据的时候默认参数
    public function third(){
        $param = $this->param;

        $uid = !empty($param['uId']) ? $param['uId'] : '';

        if(!$uid){
            throw new HttpResponseException(result_array(['error' => '参数错误！']));
        }

        $param['modifyUser'] = $param['uId'];
        $param['modifyTime'] = date("Y-m-d H:i:s");

        $this->param = $param;
    }
}