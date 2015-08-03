<?php

class ProjectGrantRecordsModel extends BaseModel 
{
    public function update($project_id, $is_planed, $data) {
        $project_id = intval($project_id);
        if(!$project_id) return NULL;
        if($is_planed) {
            $is_planed = 1;
        } else {
            $is_planed = 0;
        }

        M("ProjectGrantRecords")->where("project_id = $project_id and is_planed=$is_planed")->delete();

        for($i=0; $i<20; $i++) {
            $v['project_id'] = $project_id;
            $v['is_planed'] = $is_planed;
            $v['grant_time'] = $data['grant_time_'.$i];
            $v['amount'] = str_replace(',', '', $data['grant_amount_'.$i]);
            $v['invoice'] = $data['grant_invoice_'.$i];
            $v['note'] = $data['grant_note_'.$i];
            if(!$v['grant_time'] || !$v['amount']) {
                continue;
            }
            M('ProjectGrantRecords')->add($v);
        }
        return $project_id;
    }


    public function getGrantRecords($project_id) {
        $raw = M('ProjectGrantRecords')->order('grant_time')->getsByProjectId(intval($project_id));
        foreach ($raw as $k => $v) {
            if($v['is_planed']) {
                $res['planed'][] = $v;
            } else {
                $res['actual'][] = $v;
            }
        }
        return $res;
    }    
}
 