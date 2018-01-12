<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/11
 * Time: 上午10:31
 */

namespace app\index\model;


use think\Db;

class Sport extends Common{
    /**
     * Function: getChartList
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 获取排行榜
     *
     * @param     [datetime]    $data     时间
     * @param     [int]         $uid      用户id
     * @param     [int]         $sid      门店id
     */
    public function getChartList($date,$uid,$sid,$page,$limit){
        $map = [];

        //若要查门店
        if($sid) {
            $map['stoId'] = $sid;
        }

        $date = strtotime($date);

        $day= date("Y-m-d",$date).'';
        $tom = date("Y-m-d",strtotime("+1 day", $date)).'';

        $list = Db::table("(SELECT @i :=0) r,sport")
                ->where($map)
                ->whereTime('sport.createTime','between',[$day,$tom])
                ->join('__USER__ user','sport.useId = user.uId','LEFT');
//                ->join('__ZAN__ zan','sport.spoId = zan.spoId','LEFT');


        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }

        $list = $list->field('(@i:=@i+1) seniority,sport.*,user.*')->order('calorie desc');
//        count(DISTINCT zan.createUser) as num


        $list = $list->select();

        $dataCount = sizeof($list);

        //计算赞的数量以及当前用户是否有赞
        for ($i = 0; $i<$dataCount; $i++) {
            $tmp = Db::table("zan")->field('count(DISTINCT createUser) as num')->where('spoId',$list[$i]['spoId']);

            //若有用户id,判断用户是否点赞
            if ($uid){
                $tmp = $tmp->field('sum(case when createUser = '.$uid.' then 1 else 0 end) as praise');
            }

            $tmp = $tmp->select();

            $list[$i] = array_merge($list[$i], $tmp[0]);

        }



        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }
}