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
        $this->header = $header;
        $this->param = $param;
    }

    protected $beforeActionList = [
        //需要验证请求是否合法的方法
        'first'  =>  ['only'=>'update_user,find_user,details']
    ];

    //判断请求是否合法
    protected function first(){
        $header = $this->header;

        $token = !empty($header['token']) ? $header['token'] : '';
        $data = '';
        if ($token){
            $data = Token::encode_token_user($header['token']);
        }

        if (!$data){
            throw new HttpResponseException(result_array(['error' => '用户未授权']));
        }

    }

}