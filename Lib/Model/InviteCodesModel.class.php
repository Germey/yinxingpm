<?php
class InviteCodesModel extends BaseModel {

    function _after_select(&$resultSet,$options){
        foreach ($resultSet as $index => $result) {
            if($result['is_bind']>0) {
                $resultSet[$index]['bind_emails'] = M("CmsUsers")->where('invite_code=%s',$result['code'])->getField('email',true);
            }
        }
        return $resultSet;
     }

}