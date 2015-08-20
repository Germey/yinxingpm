<?php

class UserStatusesModel extends BaseModel {

    public function getStatusIdNameMap($apply_type_id=1) {
        $f['id'] = array('gt',1);
        // $f['apply_type_id'] = $apply_type_id;
        return $this->where($f)->getField('id,name',true);
    }

    public function getAuditStatusIdNameMap($apply_type_id=1) {
        $f['id'] = array('gt',1);
        $f['with_audit'] = 1;
        // $f['apply_type_id'] = $apply_type_id;
        return $this->where($f)->getField('id,name',true);
    }
    
    //返回以各个状态名为键值，ID为键名的数组
    public function getAuditStatusNameIdMap() {
        return $this->getField('name,id',true);
    }
}

?>