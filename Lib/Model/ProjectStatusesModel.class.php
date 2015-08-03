<?php

class ProjectStatusesModel extends BaseModel {


    public function getStatusIdNameMap() {
        $res = M('ProjectStatuses')->order('id')->getField('id, name');
        unset($res[1]);
        return $res;
    }
}
?>