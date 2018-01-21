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
use think\Request;

class Token
{
    private static $key = "Keepao2018";

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
    public static function get_token($aId, $iat, $exp, $aud, $psd = '')
    {
        $playload = array(
            'iss' => 'mskp',
            'aud' => $aud,
            'aId' => $aId,
            'iat' => $iat,
            'exp' => $exp,
            'psd' => $psd
        );
        $jwt = JWT::encode($playload, md5(Token::$key));

        return $jwt;
    }

    public static function encode_token_admin($_token)
    {
        try{
            $token = JWT::decode($_token, md5(Token::$key), array('HS256'));
            return $token;
        } catch (\Exception $exception){
            return [
                'value' => false,
                'data' => [
                    'message' => '非法请求'
                ]
            ];
        }

    }

    public static function encode_token_user($_token)
    {
        try{
            $token = JWT::decode($_token, md5(Token::$key), array('HS256'));
            return $token;
        }catch (\Exception $exception){
            return false;
        }
    }

    public static function check_token($_token)
    {
        $token = Token::encode_token_admin($_token, md5(Token::$key), array('HS256'));

        if (is_array($token)) {
            return $token;
        }
        if ($token->aId != session('sId'))
        {
            return [
                'value' => false,
                'data' => [
                    'message' => '非法请求'
                ]
            ];
        }
        //dump(date('Y-m-d h-i-s', $token->exp));

        return [
            'value' => true,
            'data' => [
                'message' => 'token验证通过'
            ]
        ];
    }

    /**
     * Function: refresh_token
     * Description: 刷新access_token
     * Author  : wry
     * DateTime: 18/1/11 上午11:21
     *
     * @param $refresh_token
     *
     * @return array|object|\think\response\Json
     * @throws \think\exception\DbException
     */
    public static function refresh_token($refresh_token)
    {
        //dump(session('sId'));
        $token = Token::encode_token_admin($refresh_token, md5(Token::$key), array('HS256'));
        //dump($token);
        if (is_array($token)) {
            return $token;
        }

        if ($token->aId != session('sId'))
        {
            return [
                'value' => false,
                'data' => [
                    'message' => 'token invalid'
                ]
            ];
        }

        //dump(date('Y-m-d h-i-s', $token->exp));
        $admin = Admin::get([
            'sId' =>  $token->aId,
            'key' => $token->psd
        ]);

        if (is_null($admin)) {
            return [
                'value' => false,
                'data' => [
                    'message' => 'token invalid'
                ]
            ];
        }

//        try{
//            $admin->key = Token::create_key(6);
//            $admin->save();
//        } catch (Exception $exception)
//        {
//            return json([
//                'value' => false,
//                'data' => [
//                    'message' => $exception
//                ]
//            ]);
//        }

        $iat = strtotime('now');
        $exp = strtotime("+1 day", $iat);
        $access_token = Token::get_token($admin->sId, $iat, $exp, Request::instance()->header()['host']);

        return [
            'value' => true,
            'data' => [
                'message' => 'token验证通过',
                'access_token' => $access_token
            ]
        ];
    }

    private static function create_key($length)
    {
        $randkey = '';
        for ($i = 0; $i < $length; $i++) {
            $randkey .= chr(mt_rand(33, 126));
        }
        return md5($randkey);
    }
}