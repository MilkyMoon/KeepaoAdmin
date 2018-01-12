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
    public function select(){
        $chart = new Sport();
        $param = $this->param;

        $date  = !empty($param['date']) ? $param['date'] : date('Y-m-d',strtotime('now'));
        $uid   = !empty($param['uid']) ? $param['uid'] : '';
        $sid   = !empty($param['sid']) ? $param['sid'] : '';
        $page  = !empty($param['page']) ? $param['page'] : '';
        $limit = !empty($param['limit']) ? $param['limit'] : '';

        $data = $chart->getChartList($date,$uid,$sid,$page,$limit);

        if (!$data){
            return result_array(['erroe' => $chart->getError()]);
        }

        return result_array(['data' => $data]);
    }
}