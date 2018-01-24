<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/18
 * Time: 上午3:26
 */

namespace app\index\controller;


use app\common\controller\Token;
use think\Request;

class Test extends Common{
    public function test(){
        $iat = strtotime('now');  //发放时间
        $exp = strtotime('+1 week', $iat);  //失效时间
        //生成token
        $data['token'] = Token::get_token('osFjFuHygwzSCuIYFUuHI92qMN8D',$iat,$exp,Request::instance()->header()['host']);

        return result_array(['data' => $data]);
    }
}