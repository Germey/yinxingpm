<?php
class ValidateAction extends BaseAction{
    
    function check_username(){
        $v = $this->_request("v");
        $user_id = $this->_request("n");
        $user_id = str_replace("id-", "", $user_id);
        $u = M("Users")->getByUsername($v);
        if ( empty($u) || $u['id'] == $user_id) {
            Output::Json(0);
        }else{
            Output::Json(null, 1);
        }
    }

    function check_email(){
        $v = $this->_request("v");
        $user_id = $this->_request("n");
        $user_id = str_replace("id-", "", $user_id);
        $u = M("Users")->getByEmail($v);
        if ( empty($u) || $u['id'] == $user_id ) {
            Output::Json(0);
        }else{
            Output::Json(null, 1);
        }
    }
}