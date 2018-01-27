<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 上午10:40
 */

namespace app\index\controller;

use app\index\model;
use think\Db;

class Store extends Common {
    /**
     * Function: select
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * @param   lng   [double]          经度
     * @param   lat   [double]          纬度
     * @param   city  [string]          城市
     * @param   page  [int]             当前页数
     * @param   limit [int]             每页数量
     */
    public function store_select(){
        $store = new model\Store();

        $param = $this->param;

        $keywords = !empty($param['keywords']) ? $param['keywords'] : '';
        $city     = !empty($param['city']) ? $param['city'] : '';
        $lng      = !empty($param['lng']) ? $param['lng'] : '';
        $lat      = !empty($param['lat']) ? $param['lat'] : '';
        $page     = !empty($param['page']) ? $param['page'] : '';
        $limit    = !empty($param['limit']) ? $param['limit'] : '';

        $data = $store->getDataList($keywords,$city,$lng,$lat,$page,$limit);

        if(!$data){
            return result_array(['error' => $store->getError()]);
        }

        return result_array(['data' => $data]);
    }

    /**
     * Function: details
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 门店详细信息
     *
     * @param  id   int  门店id
     * @return Json
     */
    public function store_details(){
        $store = new model\Store();

        $param = $this->param;
        $id = !empty($param['id']) ? $param['id'] : '';

        //要显示的字段
        $array = ['stoId','stono','stoname','county','province','city','address','longitude','latitude','isdirect'];

        $data = $store->getDataById($id,$array);

        if (!$data){
            return result_array(['error' => $store->getError()]);
        }

        $config = $store->getStoreConfig($id);      //门店配置
        $devices = $store->getStoreDevices($id);    //门店设备
        $images = $store->getStoreImg($id);         //门店图片

        if(!$config){$config = '';}
        if(!$devices){$devices = '';}
        if(!$images){$images = '';}

        $data['devices'] = $devices;
        $data['config']  = $config;
        $data['images']  = $images;

        return result_array(['data' => $data]);
    }
}