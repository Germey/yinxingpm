<?php

class ProjectBudgetRequestsModel extends BaseModel {


    function _after_select(&$resultSet,$options){
        foreach ($resultSet as $index => $result) {
            $resultSet[$index]['detail'] = $result['detail'];
        }
        // return $resultSet;
     }

    function  _after_find(&$result,$options){
        // $result['detail'] = unserialize($result['detail']);
      
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
            $results[$k]['status_name'] = $this->getStatusName($v['status']);
            $results[$k]['audits'] = M("ProjectBudgetRequestAudits")->where('budget_request_id=' . $v['id'])->order('id desc')->select();
        }
        return $results;
    }

    public function getRequestById($id) {

        $id = intval($id);
        $result = $this->getById($id);

        return $result;
    }

}