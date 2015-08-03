<?php
class BaseModel extends Model{

    public $login_user = NULL;

    public $_auto = array (
            array('create_time', 'date', Model::MODEL_INSERT, 'function',array('Y-m-d H:i:s')),
            array('update_time', 'date', Model::MODEL_BOTH, 'function',array('Y-m-d H:i:s')),
            array('team_id', 'getLoginUserTeamId', Model::MODEL_INSERT, 'callback'),
            array('user_group_id', 'getLoginUserGroupId', Model::MODEL_INSERT, 'callback'),
            array('owned_user_id', 'getLoginUserId', Model::MODEL_INSERT, 'callback'),
            array('create_user_id', 'getLoginUserId', Model::MODEL_INSERT, 'callback'),
            array('update_user_id', 'getLoginUserId', Model::MODEL_BOTH, 'callback'),
    );

    public function getLoginUser() {
        if(!$this->login_user) {
            $this->login_user = Login::GetSessionUser();
        }
        return $this->login_user;
    }

    public function getLoginUserId() {
        $u = $this->getLoginUser();
        return $u['id'];
    }

    public function getLoginUserGroupId() {
        $u = $this->getLoginUser();
        return $u['user_group_id'];
    }

    public function getLoginUserTeamId() {
        $u = $this->getLoginUser();
        return $u['team_id'];
    }
}