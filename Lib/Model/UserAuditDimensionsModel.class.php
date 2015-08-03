<?php

class UserAuditDimensionsModel extends BaseModel {

    public function gets($type_id) {
        $raw = $this->where('apply_type_id=%d',$type_id)->order('sequence desc, id')->select();
        foreach ($raw as $v) {
            $res[$v['status']][$v['key']] = $v;
        }

        return $res;
    }

}

?>