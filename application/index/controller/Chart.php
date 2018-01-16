<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/11
 * Time: 上午10:11
 */

namespace app\index\controller;

use app\index\model\Sport;

/**
 * Class Chart
 *
 * 排行榜
 *
 * @package app\index\controller
 */
class Chart extends Common {
    public function chart_select(){
        $chart = new Sport();
        $param = $this->param;

        $date  = !empty($param['date']) ? $param['date'] : date('Y-m-d',strtotime('now'));
        $uid   = !empty($param['uid']) ? $param['uid'] : '';
        $sid   = !empty($param['sid']) ? $param['sid'] : '';
        $page  = !empty($param['page']) ? $param['page'] : '';
        $limit = !empty($param['limit']) ? $param['limit'] : '';

        $data = $chart->getChartList($date,$uid,$sid,$page,$limit);

        if (!$data){
            return result_array(['error' => $chart->getError()]);
        }

        return result_array(['data' => $data]);
    }

    /**
     * Function: user_select
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 用户在排行榜中的排名
     *
     * @return \think\response\Json
     */
    public function user_select(){
        $chart = new Sport();
        $param = $this->param;

        $date  = !empty($param['date']) ? $param['date'] : date('Y-m-d',strtotime('now'));
        $uid   = !empty($param['uid']) ? $param['uid'] : '';
        $sid   = !empty($param['sid']) ? $param['sid'] : '';

        if (!$uid){
            return result_array(['error' => '用户id为空']);
        }

        $data = $chart->getChartList($date,$uid,$sid,'','');

        foreach ($data['list'] as $item){
//            print_r($data);
            if($uid == $item['uId']){
                $data = $item;
                break;
            }
        }

        if (!$data){
            return result_array(['error' => $chart->getError()]);
        }

        return result_array(['data' => $data]);
    }
}