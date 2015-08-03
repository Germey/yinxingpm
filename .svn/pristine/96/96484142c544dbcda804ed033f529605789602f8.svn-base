<?php

class ProjectMeetingsModel extends BaseModel {

    public function getByProjectId($project_id, $type) {

        $filter['project_id'] = intval($project_id);
        $filter['meeting_type'] = $type ? $type : 'meeting';

        $ms = M('ProjectMeetings')->where($filter)->order('id desc')->select();
        foreach ($ms as $k => $v) {
            $ms[$k]['meeting_content'] = htmlspecialchars_decode($v['meeting_content']);
        }

        return $ms;
    }

    public function getById($id) {
        
        $m = M('ProjectMeetings')->getById($id);
        $m['meeting_content'] = htmlspecialchars_decode($m['meeting_content']);
        return $m;
    }
}
?>