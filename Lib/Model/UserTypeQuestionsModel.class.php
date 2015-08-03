<?php

class UserTypeQuestionsModel extends BaseModel {

    public function gets($type_id=0, $all=0) {
        if(!$all) {
            $f['is_deleted'] = 0;
        }
        $raw = $this->where($f)->order('sequence,id')->select();
        foreach ($raw as $k => $v) {
            $res[$v['type_id']][] = $v;
        }
        if($type_id) {
            return $res[$type_id];
        }
        return $res;
    }

}
?>