<?php

class ProjectChangesModel extends BaseModel {


    function _after_select(&$resultSet,$options){
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        return $result;
    }

    public function getStatusName($key) {
        $statuses = array(0 => '<span style="color:#B0281A">草稿</span>', 
                1 => '<span class="text-error">驳回修改</span>', 
                10 => '<span class="text-warning">提交审核中</span>', 
                20 => '<span class="text-success">审核通过</span>', 
                100 => '<span class="text-error">未通过</span>');

        if(isset($key)) return $statuses[intval($key)];

        return $statuses;
    }


    public function gets($project_id, $status=array('gt',0)) {

        $f['project_id'] = intval($project_id);
        if($status) {
            $f['status'] = $status;
        }

        $results = $this->where($f)->order('id desc')->select();
        foreach ($results as $k => $v) {
      
            // $results[$k]['type_name'] = $this->getTypes($v['change_type']);
            $results[$k]['status_name'] = $this->getStatusName($v['status']);
            $results[$k]['audits'] = M("ProjectChangeAudits")->where('change_id=' . $v['id'])->order('id desc')->select();
        }

        return $results;
    }

    public function getChangeById($id) {

        $id = intval($id);
        $result = $this->getById($id);

        // $result['detail'] = unserialize($result['detail']);

        return $result;
    }

}