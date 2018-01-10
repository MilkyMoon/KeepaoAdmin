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
    public function _initialize(){
        parent::_initialize();

        $param =  Request::instance()->param();
        $this->param = $param;
    }

    protected $beforeActionList = [
        //需要验证请求是否合法的方法
        'first'  =>  ['only'=>'select,details']
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

}