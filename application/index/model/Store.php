<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 下午5:14
 */

namespace app\index\model;



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
            $list = $list->field('*,(2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*('.$lat.'-latitude)/360),2)+COS(3.1415926535898*'.$lat.'/180)* COS(latitude * 3.1415926535898/180)*POW(SIN(3.1415926535898*('.$lng.'-longitude)/360),2))))*1000 as juli')->order('juli');
        }

        //$list = $this->query("select *,(2 * 6378.137* ASIN(SQRT(POW(SIN(3.1415926535898*(".$lat."-latitude)/360),2)+COS(3.1415926535898*".$lat."/180)* COS(latitude * 3.1415926535898/180)*POW(SIN(3.1415926535898*(".$lng."-longitude)/360),2))))*1000 as juli from `store`  where city = '".$city."' order by juli asc limit ".(($page-1)*$limit+1).",".$limit);
        //dump($list);

        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list->select();

        $dataCount = sizeof($list);

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }
}