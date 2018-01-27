<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 下午5:14
 */

namespace app\index\model;



use think\Db;

class Store extends Common{
    /**
     * [getDataList 获取列表]
     * @PengZong
     * @DateTime  2017-04-25T21:07:18+0800
     * @param     [string]                   $keywords [关键字]
     * @param     [number]                   $page     [当前页数]
     * @param     [number]                   $limit    [每页数量]
     * @return    [array]                              [description]
     */
    public function getDataList($keywords,$city,$lng,$lat,$page,$limit){
        $map = [];

        //若有城市
        if($city){
            $map['city'] =  $city;
        }

        //若有查询关键字
        if($keywords){
            $map['stoname'] = ['like', '%'.$keywords.'%'];
        }

        $list = $this->where($map);

        //若有经纬度,计算距离排序
        if($lng && $lat){
            $list = $list->field('(2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*('.$lat.'-latitude)/360),2)+COS(3.1415926535898*'.$lat.'/180)* COS(latitude * 3.1415926535898/180)*POW(SIN(3.1415926535898*('.$lng.'-longitude)/360),2))))*1000 as juli')->order('juli');
        }

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list->field('stoId,stono,stoname,county,province,city,address,state,longitude,latitude,isdirect');

        $list = $list->select();

        $dataCount = sizeof($list);

        for ($i = 0; $i<$dataCount; $i++) {
            $tmp = $this->getStoreImg($list[$i]['stoId']);
            if($tmp){
                $list[$i]['img'] = $tmp;
            }
        }

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }

    /**
     * Function: getStoreDevices
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 门店设备
     *
     * @param $sid
     */
    public function getStoreDevices($sid){
        if(!$sid){
            $this->error = '找不到门店设备';
            return false;
        }

        $map['stoId'] = $sid;

        $list = Db::table('sto_equ')->alias('stoequ')
            ->where($map)
            ->join('__EQUIPMENT__ equipment','sto_equ.equId = equipment.equId','LEFT');

        $list = $list->field('equipment.equId,equipment.equno,equipment.type,equipment.name,equipment.remark');

        $list = $list->select();

        return $list;
    }

    /**
     * Function: getStoreDevices
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 门店配置
     *
     * @param $sid
     */
    public function getStoreConfig($sid){
        if(!$sid){
            $this->error = '找不到门店配置';
            return false;
        }

        $map['stoId'] = $sid;

        $list = Db::table('sto_con')->alias('stocon')
            ->where('stocon.stoId',$sid)
            ->where('config.type',3)
            ->join('__CONFIG__ config','stocon.conId = config.conId','LEFT');

        $list = $list->field('config.conId,config.type,config.name,config.value,config.state,config.isDefault');

        $list = $list->select();

        return $list;
    }

    /**
     * Function: getStoreImg
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 门店图片
     *
     * @param $sid
     */
    public function getStoreImg($sid){
        if(!$sid){
            $this->error = '找不到门店图片';
            return false;
        }

        $map['stoId'] = $sid;

        $list = Db::table('sto_img')->alias('stoimg')
            ->where('stoimg.stoId',$sid)
            ->join('__IMGS__ imgs','stoimg.imgId = imgs.imgId','LEFT')->order('imgs.sort','desc');

        $list = $list->field('imgs.imgId,imgs.name,imgs.url,imgs.sort');

        $list = $list->select();

        return $list;
    }
}