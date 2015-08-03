<?php

class Login
{
    static public $cookie_name = 'simplecrm';

    // static public function GetLoginId() {
    //     $u = self::GetLoginCookie(self::$cookie_name);

    //     if($u['is_enabled'] == 'Y') {
    //         $user_id = abs(intval($u['id']));
    //     }

    //     return $user_id;
    // }

    static public function Logon($user) {
        // if($isRemember) {
        //     $time = 30 * 86400;
        // } else {
        //     $time = 0;
        // }
        //获取用户的group id和team id信息
        $group_team = M()->table("user_group_mapping ugm, user_group_team_mapping ugtm")
            ->where("ugm.user_group_id = ugtm.user_group_id and ugm.user_id = " . $user['id'])
            ->field("ugm.user_group_id, ugtm.team_id ")
            ->query('select %FIELD% from %TABLE% %WHERE%',true);
        $group_team = $group_team[0];
        $user['user_group_id'] = $group_team['user_group_id'];
        $user['team_id'] = $group_team['team_id'];
        $user['team'] = M('Teams')->getById($group_team['team_id']);
        //用户有权限管理的组信息放到login_user里面
        $group = M("UserGroups")->find($group_team['user_group_id']);
        $user['role'] = $group['role'];
        if($group['role'] == 'admin'){//管理员组
            $groups = M("UserGroupTeamMapping")->where("team_id = " . $group_team['team_id'])->field("user_group_id")->select();
            $groups = Utility::GetColumn($groups, "user_group_id");
        }else{
            $groups = array($group_team['user_group_id']);
        }
        $user['user_group'] = D("UserGroups")->find($group_team['user_group_id']);
        $user['manage_group_ids'] = $groups;
        Session::set("login_user", $user);
        return $user;
    }

    // static public function NeedLogin() {
    //     $user_id = self::GetLoginId();
    //     return $user_id ? $user_id : False;
    // }

    static public function Logout(){
        $user = Session::set("login_user", null);
    }


    static public function GetSessionUser() {
        $user = Session::get("login_user");
        return $user;
    }

    static public function GetBan($ip){
        if($ip){
            $banned = M("BannedIp")->getByIp($ip);
            return $banned;
        }
    }

    static public function IncreaseBan($ip){
        $banned = M("BannedIp")->getByIp($ip);
        if($banned){
            $banned['count'] = $banned['count'] + 1;
            // $banned['date'] = date("Y-m-d H:i:s");
            M("BannedIp")->save($banned);
        }else{
            $param = array(
                "ip" => Utility::GetRemoteIp(),
                "date" => date("Y-m-d H:i:s"),
                "count" => 1,
                "type" => "login"
                );
            M("BannedIp")->add($param);
        }
    }

    static public function RemoveBan($ip){
        M("BannedIp")->where(array("ip" => $ip))->delete();
    }
}
