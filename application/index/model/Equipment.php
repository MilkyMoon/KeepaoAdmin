<?php
/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/15
 * Time: 下午2:43
 */

namespace app\index\model;


use think\Db;

class Equipment extends Common{
    protected $name = 'equipment';

    public function getEquipment($equId,$type){
        $map = [];

        if($equId){
            $map['equId'] = $equId;
        }

        if($type){
            $map['type'] = $type;
        }

        $list = $this->alias('equipment')
                ->where($map);
//                ->join("__ETYPE__ etype",'etype.id = equipment.type',"LEFT");
//                ->join("__IMGS imgs","imgs.imgId = equimg.imgId","LEFT");

        $list = $list->field('equipment.equId,equipment.equno,equipment.type,equipment.name,equipment.remark');
        $list = $list->select();

        $dataCount = sizeof($list);

        //计算赞的数量以及当前用户是否有赞
        for ($i = 0; $i<$dataCount; $i++) {
            $tmp = Db::table("equ_img")->alias('equimg')
                   ->where('equimg.equId',$list[$i]['equId'])
                   ->join("__IMGS__ imgs","imgs.imgId = equimg.imgId","LEFT");

            $tmp = $tmp->field('imgs.imgId,imgs.name,imgs.url,imgs.path,imgs.sort');
            $tmp = $tmp->select();

            $ins = Db::table("etype")->field('etype.name,etype.state,etype.Instructions')->where('id',$list[$i]['type'])->find();

            $list[$i]['img'] = $tmp;
            $list[$i]['instructions'] = $ins;
        }

        $data['list'] = $list;
        $data['dataCount'] = $dataCount;

        return $data;
    }
}