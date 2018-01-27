<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/27
 * Time: 上午1:46
 */

namespace app\index\controller;

use app\index\model;

class Equipment extends Common{
    public function equ_select(){
        $param = $this->param;
        $equipment = new model\Equipment();

        $equId = !empty($param['equId']) ? $param['equId'] : '';
        $type = !empty($param['type']) ? $param['type'] : '';

        $data = $equipment->getEquipment($equId,$type);

        if(!$data){
            return result_array(['error' => $equipment->getError()]);
        }

        return result_array(['data' => $data]);
    }
}