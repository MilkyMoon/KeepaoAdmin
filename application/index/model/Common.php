<?php

/**
 * Created by PhpStorm.
 * User: PengZong
 * Date: 18/1/9
 * Time: 上午10:05
 */
namespace app\index\model;

use think\Model;
use app\index\validate;
use think\Request;

class Common extends Model {
    //字段过滤
    protected $field = true;
    /**
     * [getDataById 根据主键获取详情]
     * @PengZong
     * @DateTime  2017-04-28T21:16:34+0800
     * @param     string                   $id [主键]
     * @return    [array]
     */
    public function getDataById($id = '',$field = '*')
    {
        $data = $this->field($field)->where($this->getPk(),$id)->find();
        if (!$data) {
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }

    /**
     * [createData 新建]
     * @PengZong
     * @DateTime  2017-04-28T21:19:06+0800
     * @param     array                    $param [description]
     * @return    [array]                         [description]
     */
    public function createData($param)
    {
        try {
            $result = $this->validate('index/'.$this->name)->save($param);
            if (false == $result) {
                return false;
            }
            return true;
        } catch(\Exception $e) {
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * [delDataById 根据id删除数据]
     * @PengZong
     * @DateTime  2017-04-30T20:57:55+0800
     * @param     string                   $id     [主键]
     * @param     boolean                  $delSon [是否删除子孙数据]
     * @return    [type]                           [description]
     */
    public function delDataById($id = '', $delSon = false)
    {

        $this->startTrans();
        try {
            $this->where($this->getPk(), $id)->delete();
            if ($delSon && is_numeric($id)) {
                // 删除子孙
                $childIds = $this->getAllChild($id);
                if($childIds){
                    $this->where($this->getPk(), 'in', $childIds)->delete();
                }
            }
            $this->commit();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            $this->rollback();
            return false;
        }
    }

    /**
     * [delDatas 批量删除数据]
     * @PengZong
     * @DateTime  2017-05-4T20:59:34+0800
     * @param     array                   $ids    [主键数组]
     * @param     boolean                 $delSon [是否删除子孙数据]
     * @return    [type]                          [description]
     */
    public function delDatas($ids = [], $delSon = false)
    {
        if (empty($ids)) {
            $this->error = '删除失败';
            return false;
        }

        // 查找所有子元素
        if ($delSon) {
            foreach ($ids as $k => $v) {
                if (!is_numeric($v)) continue;
                $childIds = $this->getAllChild($v);
                $ids = array_merge($ids, $childIds);
            }
            $ids = array_unique($ids);
        }

        try {
            $this->where($this->getPk(), 'in', $ids)->delete();
            return true;
        } catch (\Exception $e) {
            $this->error = '操作失败';
            return false;
        }

    }

    /**
     * [enableDatas 批量启用、禁用]
     * @AuthorHTL
     * @DateTime  2017-02-11T21:01:58+0800
     * @param     string                   $ids    [主键数组]
     * @param     integer                  $status [状态1启用0禁用]
     * @param     [boolean]                $delSon [是否删除子孙数组]
     * @return    [type]                           [description]
     */
    public function enableDatas($ids = [], $status = 1, $delSon = false)
    {
        if (empty($ids)) {
            $this->error = '删除失败';
            return false;
        }

        // 查找所有子元素
        if ($delSon && $status === '0') {
            foreach ($ids as $k => $v) {
                $childIds = $this->getAllChild($v);
                $ids = array_merge($ids, $childIds);
            }
            $ids = array_unique($ids);
        }
        try {
            $this->where($this->getPk(),'in',$ids)->setField('status', $status);
            return true;
        } catch (\Exception $e) {
            $this->error = '操作失败';
            return false;
        }
    }

    /**
     * 获取所有子孙
     */
    public function getAllChild($id, &$data = [])
    {
        $map['pid'] = $id;
        $childIds = $this->where($map)->column($this->getPk());
        if (!empty($childIds)) {
            foreach ($childIds as $v) {
                $data[] = $v;
                $this->getAllChild($v, $data);
            }
        }
        return $data;
    }

    /**
     * Function: updateAddById
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * @param $field        要自增的字段名称
     * @param $id           主键id
     * @param $num          +多少
     */
    public function updateAddById($field,$id,$num){
        try{
            $this->where($this->getPk(),$id)->inc($field,$num)->update();
            return true;
        }catch (\Exception $e){
            $this->error = '数据更新失败';
            return false;
        }

    }

    /**
     * [saveData 添加]
     * PengZong
     * DateTime: 2017-04-26T17:07:18+0800
     *
     * @param  $param        [添加数组]
     * @return bool
     */
    public function saveData($param) {
        try{
            $this->data($param)->allowField(true)->save();
            return true;
        }catch (\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * [updateDataById 编辑]
     * PengZong
     * DateTime: 2017-04-26T17:32:18+0800
     *
     * @param  $param        [编辑数组]
     * @param  $id           [主键]
     * @return bool
     */
    public function updateDataById($param, $id){
        if (empty($id)) {
            $this->error = '编辑失败';
            return false;
        }
        try{
            $pk = $this->getPk();
            $result = $this->validate('index/'.$this->name)->save($param, [$pk => $id]);
            if (false == $result) {
                return false;
            }

            return $this->getDataById($this->$pk);
        }catch (\Exception $e){
            $this->error = '编辑失败';
            return false;
        }
    }


    /**
     * Function:delDataById 删除
     * Author  : PengZong
     * DateTime: 2017-04-26T18:45:12+0800
     *
     * @param  string $id       [主键]
     * @return bool
     */
    public function deleteDataById($id = '') {
        if (empty($id)) {
            $this->error = '删除失败';
            return false;
        }
        try {
            $this->where($this->getPk(), $id)->delete();
            return true;
        } catch(\Exception $e) {
            $this->error = '删除失败';
            return false;
        }
    }


    /**
     * Function: enableData
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * @param  string    $id          [主键]
     * @param  int       $status      [状态1启用0禁用]
     * @return bool
     */
    public function enableData($id = '', $status = 1){
        if (empty($id)) {
            $this->error = '操作失败';
            return false;
        }
        //更改状态
        if($status == 0){$status = 1;}else{$status = 0;}

        try{
            $this->where($this->getPk(),$id)->setField('status', $status);
            return true;
        }catch (\Exception $e){
            $this->error = '操作失败';
            return false;
        }
    }

    /**
     * Function: saveDatas
     * Author  : PengZong
     * DateTime: ${DATE} ${TIME}
     *
     * 批量添加
     */
    public function saveDatas($array){
        try{
            $this->saveAll($array);
            return true;
        }catch (\Exception $e){
            $this->error = '添加失败！';

            return false;
        }
    }

}