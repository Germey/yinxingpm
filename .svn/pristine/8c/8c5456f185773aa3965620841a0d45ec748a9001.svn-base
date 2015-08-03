<?php

class ProjectBatchesModel extends BaseModel {

    public function gets($project_ids) {

        $res = M('ProjectBatches')->order('id desc')->select();
        foreach ($res as $k => $v) {
            $res[$k]['content'] = htmlspecialchars_decode($v['content']);
        }

        return $res;
    }

    public function getLast() {
        $res = M('ProjectBatches')->order('id desc')->find();
        if(!$res) return null;

        $res['content'] = htmlspecialchars_decode($res['content']);
        $res['apply_status'] = 'ok';

        $start = strtotime($res['start_time']);
        $end = strtotime($res['end_time']);
        $current = time();

        if($current < $start) {
            $res['apply_status'] = 'early';
        } else if($current > $end) {
            $res['apply_status'] = 'late';
        }

        return $res; 
    }
}

?>