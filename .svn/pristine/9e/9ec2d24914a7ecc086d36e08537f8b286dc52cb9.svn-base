<?php
class LoginAction extends BaseAction{

    public function index() {

        if ( $_POST ) {
             
            if(!Utility::CaptchaCheck($this->_post('verifycode'))) {
                Session::Set("error", "验证码有误，请重新输入");
                redirect("/login");
            }

            $email = $this->_get("email");
            $password = $this->_get("password");
            $users = D('Users');
            $login_user = $users->getLogin($_POST['email'], $_POST['password']);
            if ( !$login_user ) {
                Session::Set('error', '用户名或密码错误');
                $this->redirect("/frontend/login/index");
            } else if ($login_user['is_enabled']=='N' && $login_user['secret']) {
                Session::Set('error', '登录失败');
                $this->redirect("/frontend/login/index");
            } else {
                if (abs(intval($_POST['auto_login']))) {
                    Login::Logon($login_user, true);
                } else {
                    Login::Logon($login_user);
                }
                Session::set("success", "登录成功");
                if($_REQUEST['ref']) {
                    redirect($_REQUEST['ref']);
                } else {
                    redirect("/");
                }
            }
        } else {
            $this->display();
        }
    }

    public function logout(){
        Login::Logout();
        Session::set("success", "已退出");
        $this->redirect("/");
    }

}