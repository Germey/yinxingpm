<?php
import("@.Lib.Autoload.Login");
class Common{

    function need_login($wap=false) {
      $uid = Login::GetLoginId();
      if($uid) 
          return $uid;
      
      // if ( is_get() ) {
      //   Session::Set('loginpage', $_SERVER['REQUEST_URI']);
      // } else {
      //   Session::Set('loginpage', $_SERVER['HTTP_REFERER']);
      //   Session::Set('loginpagepost', json_encode($_POST));
      // }
      if (true===$wap) {
        return redirect('login.php');  
      }
      return redirect(WEB_ROOT . '/account/login.php');  
    }
}