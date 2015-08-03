<?php
class BaseAction extends Action{

    public $INI = NULL;
    public $login_user = NULL;

    public function __construct() {

        env_init();
        $this->login_user = Login::GetSessionUser();
        if(!empty($this->login_user)){
            $this->assign("login_user", $this->login_user);
        } else {
            if(strcasecmp(MODULE_NAME, 'login') !== 0) {
                $this->redirect("/login?ref=" . $_SERVER['REQUEST_URI']);
            }
        }

        $this->INI = D('Options')->GetAllOptions();
        $this->assign("INI", $this->INI);

    }

    public function trans_param($param){
        $this->assign($param, $this->_param($param));
    }

    //检查用户是否有权限进行操作，每一个edit和detail，delete都需要先判断一下
    public function has_permission($node, $operation, $id, $param){
        //id=0时候是添加，暂时没想到好办法，就直接返回true了
        if($id == 0 || $this->login_user['user_group']['role'] == 'super_admin') {
            return true;
        }

        $model = D($node);
        if($model){
            $obj = $model->find($id);

            if($node == "Users"){
                $user_group = $model->getUserGroupId($id, $this->login_user['team_id']);
                $user_group_id = $user_group['id'];
            } else if($node == "Projects"){
                $user_group_id = $obj['user_group_id'];
            } else if($node == "Partners") {
                $team_id = $obj['team_id'];
            } else if($node == "ProjectNotes"|| $node == "PartnerNotes"){             
                $user_id = $obj['update_user_id'];

            }
            //admin能够编辑自己team里所有的东西
            $login_team_id = $this->login_user['team_id'];

            //最后开始判断login user是否有权限对xxx Node进行xxx operation
            //现在user_id user_group_id team_id已经准备齐了
            $permission = $this->permissions[$node][$operation];

            if(!$permission){
                return false;
            }
            if($permission == "group"){
                return $user_group_id==$this->login_user['user_group_id']?true:false;
            }else if($permission == "team"){
                return $team_id==$this->login_user['team_id']?true:false;
            }else if($permission == "self"){
                return $user_id==$this->login_user['id']?true:false;
            }
        }
        return false;
    }

    private $permissions = array(
        "Projects" => array(
            "view" => "group",
            "edit" => "group",
            "delete" => "group",
            ),        
        "Users" => array(
            "view" => "admin",
            "edit" => "admin",
            "delete" => "admin",
            ),
        "Partners" => array(
            "view" => "team",
            "edit" => "team",
            "delete" => "team",
            ),
        "ProjectNotes" => array(
            "view" => "group",
            "edit" => "self",
            "delete" => "self",
            ),
        "PartnerNotes" => array(
            "view" => "group",
            "edit" => "self",
            "delete" => "self",
            )
        );

    protected function permission_redirect($node, $operation, $id, $param){
        if(!$this->has_permission($node, $operation, $id, $param)){
            Session::Set("error", "你没有权限进行该操作($node,$operation,$id)");
            // if($_SERVER['HTTP_REFERER']){
            //     redirect($_SERVER['HTTP_REFERER']);
            // }
            // redirect("/");
        };
    }

    protected function permission_ajaxrefresh($node, $operation, $id, $param){
        if(!$this->has_permission($node, $operation, $id, $param)){
            Session::Set("error", "你没有权限进行操作($node,$operation,$id)");
            json(null, "refresh");
        };
    }

    protected function admin_allowed(){
        if($this->login_user['user_group']['role'] != 'admin' && $this->login_user['user_group']['role'] != 'super_admin'){
            Session::Set("error", "你没有权限进行该操作");
            if($_SERVER['HTTP_REFERER']){
                redirect($_SERVER['HTTP_REFERER']);
            }
            redirect("/");
        }
    }
}