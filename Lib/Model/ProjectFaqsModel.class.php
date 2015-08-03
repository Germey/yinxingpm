<?php

class ProjectFaqsModel extends BaseModel {

    public function gets() {
        $f['project_id'] = array('in', $project_ids);
        $res = $this->where($f)->order('project_id, id')->select();
        foreach ($res as $v) {
            $res_as_project[$v['project_id']]['faqs'][] = $v;
            if(!$v['answer']) {     //有未回答的
                $res_as_project[$v['project_id']]['alert'] = 1;
            }
        }

        return $res_as_project;
    }

    public function getCurrent() {

    }

    public function getById() {

    }
}

?>