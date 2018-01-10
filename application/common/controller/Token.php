<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/9
 * Time: 下午9:53
 */

namespace app\common\controller;

use app\admin\model\Admin;
use \Firebase\JWT\JWT;

class Token
{
    private static $key = "Keepao2018";

    public function __construct($aId, $iat, $exp)
    {

    }

    /**
     * Function: get_token
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * @param $aId      用户id
     * @param $iat      发送时间，时间戳
     * @param $exp      过期时间，时间戳
     * @param $aud      请求者网址
     * @return string
     */
    public static function get_token($aId, $iat, $exp, $aud)
    {
        $playload = array(
            'iss' => 'mskp',
            'aud' => $aud,
            'aId' => $aId,
            'iat' => $iat,
            'exp' => $exp
        );
        $jwt = JWT::encode($playload, md5(Token::$key));

        return $jwt;
    }

    public static function encode_token_admin($_token)
    {
        try{
            $token = JWT::decode($_token, md5(Token::$key), array('HS256'));
            return $token;
        }catch (\Exception $exception){
            return [
                'value' => false,
                'message' => 'token验证错误'
            ];
        }

    }

    public static function encode_token_user($_token)
    {
        try{
            $token = JWT::decode($_token, md5(Token::$key), array('HS256'));
            return $token;
        }catch (\Exception $exception){
            return result_array(['error' => '验证不通过']);
        }

    }

    public static function check_token($_token)
    {
        $token = Token::encode_token_admin($_token, md5(Token::$key), array('HS256'));
        if (is_array($token)) {
            return json_encode($token, JSON_UNESCAPED_UNICODE);
        }
        $admin = Admin::get([
            'account' => $token->aId,
        ]);
        if (is_null($admin)) {
            return json_encode([
                'value' => false,
                'message' => 'token失效'
            ], JSON_UNESCAPED_UNICODE);
        }

        $currentTime = time();
        if ($currentTime > $token->exp) {
            return json_encode([
                'value' => false,
                'message' => 'token过期'
            ], JSON_UNESCAPED_UNICODE);
        }
        return json_encode([
            'value' => true,
            'message' => 'token验证通过'
        ], JSON_UNESCAPED_UNICODE);

    }
}