<?php

// 项目官员更替记录
class ProjectManageRecordsModel extends BaseModel 
{
    public function update($project_id, $data) {
        $project_id = intval($project_id);
        if(!$project_id) return NULL;
        M("ProjectManageRecords")->where("project_id = $project_id")->delete();

        $last_id = 0;
        for($i=0; $i<20; $i++) {
            $v['project_id']    = $project_id;
            $v['change_time']   = $data['change_time_'.$i];
            $v['from_manager_id']    = intval($data['from_manager_id_'.$i]);
            $v['from_manager_name']  = M('Users')->getFieldById($v['from_manager_id'],'realname');
            $v['manager_id']    = intval($data['manager_id_'.$i]);
            $v['manager_name']  = M('Users')->getFieldById($v['manager_id'],'realname');
            $v['status_note']   = $data['status_note_'.$i];
            $v['note']          = $data['note_'.$i];

            if(!$v['project_id'] || !$v['change_time'] || !$v['manager_id']) {
                continue;
            }
            $v['create_time']  = date('Y-m-d H:i:s');
            $v['create_user_id']  = parent::getLoginUserId();

            $last_id = $v['manager_id'];
            D('ProjectManageRecords')->add($v);
        }
        return $last_id;
    }

    public function getManageRecords($project_id) {
        $raw = M('ProjectManageRecords')->order('change_time')->getsByProjectId(intval($project_id));
        return $raw;
    }    
}
 