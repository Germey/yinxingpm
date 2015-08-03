<?php
class UserGroupsModel extends BaseModel {
    function getTeamGroups($team_id){
        $groups = M()
           ->table("user_groups ug, user_group_team_mapping ugtm")
           ->field("ug.*")
           ->where("ug.id = ugtm.user_group_id and ugtm.team_id=$team_id")
           ->query("select %FIELD% from %TABLE% %WHERE%", true);
        $this->_after_select($groups);
        return $groups;
    }

    function getUserGroupInfo($user_ids = array()) {
        $sql = 'select user_id, g.* from user_group_mapping um, user_groups g where g.id = um.user_group_id and user_id in ('. implode(',', $user_ids) .')';
        return Utility::AssColumn($this->query($sql, true), 'user_id');
    }

    function _after_select(&$resultSet,$options){
        if($resultSet){
            $creator_ids = Utility::GetColumn($resultSet, "create_user_id");
            $users = M("Users")->where(array("id" => array("in", $creator_ids)))->select();
            $users = Utility::AssColumn($users);
            foreach ($resultSet as $index => $result) {
                $resultSet[$index]['create_user_name'] = $users[$result['create_user_id']]['username'];
            }
        }
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        if($result){
            $user = M("Users")->getByCreateUserId($result['create_user_id']);
            $result['create_user_name'] = $user['username'];
        }
        return $result;
    }

}