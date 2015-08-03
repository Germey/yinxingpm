
<?php

class UserAuditsModel extends BaseModel {

    function _after_select(&$resultSet,$options){
        foreach ($resultSet as $k => $v) {
            $resultSet[$k] = $this->_after_find($v);
        }
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        if($result) {        
            $result['dimension_detail'] = unserialize($result['dimension_detail']);
            $result['audit_email'] = unserialize($result['audit_email']);
            $result['audit_user_name_display'] = $result['audit_user_name']; 
            if($result['actual_audit_user_name'] && $result['actual_audit_user_name']!==$result['audit_user_name']) {
                $result['audit_user_name_display'] = $result['actual_audit_user_name'] . '（<span title="拟定">' . $result['audit_user_name'] . '</span>）';
            }
        }

        return $result;
    }

}

?>