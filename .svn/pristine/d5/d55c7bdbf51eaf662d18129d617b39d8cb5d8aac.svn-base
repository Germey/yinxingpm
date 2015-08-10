<?php
class UsersModel extends BaseModel {
    
    protected $SECRET_KEY = '@4!@#$%@';
    protected $_validate    =   array();
    
    // 定义自动完成
    public $_auto    =   array(
        array("password", "genPassword", Model:: MODEL_BOTH, "callback")
    );
    public function getLogin($email, $unpass, $en=true, $subdomain = "") {
        if($en) $password = $this->genPassword($unpass); 
        $field = strpos($email, '@') ? 'email' : 'username';
        $param = array($field => $email, "password" => $password);
        
        $user = $this->where($param)->find();
        $sql ="SELECT u.* from users u, user_group_mapping ugm, user_group_team_mapping ugtm, teams t
               WHERE
                u.id = ugm.user_id AND
                ugm.user_group_id = ugtm.user_group_id AND
                ugtm.team_id = t.id AND
                u.$field = '$email' AND
                u.password = '$password'
        ";
        //如果有二级域名
        if($subdomain){
            $sql .= " AND t.subdomain = '$subdomain'";
        }
        $sql .=" limit 1";
        $user = $this->query($sql);
        if($user){
            return $user[0];
        }else{
            return array();
        }
    }

    public function genPassword($p) {
        return md5($p . $this->SECRET_KEY);
    }

    public function getUserByNameAndPass($name, $pass){
        $user = $this->where(array("username" => $name, "password"=> $this->genPassword($pass)))->find();
        return $user;
    }

    //array or string are both okey for input
    public function getGroupUsers($group_ids, $page_num=-1, $pagesize=20){
        if(is_array($group_ids)){
            $ids_str = implode(",", $group_ids);
        }else{
            $ids_str = $group_ids;
        }
        $model = M()
       ->table("user_group_mapping ugm, users u, user_groups ug")
       ->field("u.*, ug.id as ug_id, ug.name as group_name, ug.role group_role, ug.create_user_id group_create_user_id")
       ->where("u.id = ugm.user_id and ug.id = ugm.user_group_id and ugm.user_group_id in (" . $ids_str . ")");
       if($page_num != -1){
            $model->limit(($page_num-1) * $pagesize . " , " . $pagesize);
       }
       $users = $model->query("select %FIELD% from %TABLE% %WHERE% %LIMIT%", true);
       return $users;
    }

    //查找用户在某一个team里的group
    public function getUserGroupId($user_id, $team_id){
        $groups = D("UserGroups")->getTeamGroups($team_id);
        $group_ids = Utility::GetColumn($groups);
        $group = M("UserGroupMapping")->where(array(
            "user_group_id" => array("in", $group_ids),
            "user_id" => $user_id
            ))->getField("user_group_id");
        return $group;
    }

    public function getProjectManager($get_id_name_map = true) {
        $us = $this->getGroupUsers(array(1,2),1, 100);
        if($get_id_name_map) {
            foreach ($us as $k => $v) {
                $res[$v['id']] = $v['realname'];
            }
            return $res;
        } else {
            return $us;
        }
    }


}