<?php
function header_nav($user) {
  $a = array(
    // "/user/todo" => '待办事项',
    "/user/recommend" => '候选人',
    "/user/success" => '伙伴们',
    "/user/fail" => '备选库',
    "/user/recomlist" => '推荐人',
    // "/project/all" =>  '所有项目',
    // "/project/ing" =>  '在资助项目',
    // "/partner" =>  '所有机构',
    // "/mail" =>  L('mass_mail'),
    // "/sms" =>  L('mass_sms'),
    // "/setting" => L('setting'),
  );

  $a['/stat'] = '统计';
  // $a['/attachment/index'] = '附件';
  $a['/setting'] = '设置';
  return $a;
}

function admin_header_nav(){
  return array(
    "/admin/index" => array(
        "display" => L('home'),
      ),
    "/admin/team" => array(
        "display" => "Team管理",
      ),
  );
}

function generate_admin_nav($selector=""){
  current_frontend(admin_header_nav(), $selector);
}


function generate_nav($selector="", $user){
  current_frontend(header_nav($user), $selector);
}


function get_manage_sub_nav($selector=""){
  
  if(empty($selector)){
    $selector = strtolower(MODULE_NAME);
  }
  $manage_nav = manage_header_nav();
  foreach ($manage_nav as $link => $info) {
    if(strpos($link, $selector) !== false){
      return $info['sub'];
    }
  }
  return $manage_nav["/manage"]['sub'];
}

function current_frontend($nav, $selector="") {

  $url_with_param = __SELF__;

  if(strpos($url_with_param, "?") !== false){
    $url_with_param = substr($url_with_param, 0, strpos($url_with_param, "?"));
  }
  if(strpos($url_with_param, "#") !== false){
    $url_with_param = substr($url_with_param, 0, strpos($url_with_param, "#"));
  }
  if(!$selector) {
    $selector = strtolower($url_with_param); 
  }
  $html = '';
  
  foreach($nav as $l=>$n) {
    if(is_array($n)) {
      if (strpos(trim($selector, '/'),trim($l, '/')) !== false 
        ||($l == $selector)&($l == '/')
        ) {
        $html .= '<li class="dropdown active">';
      } else {
        $html .= '<li class="dropdown">';
      }
      $html .= '<a href="' . $l . '" class="dropdown-toggle">'.$n['display'] . '</a>';
      if($n['sub']){
        $html .= '<ul class="dropdown-menu" >';
        foreach ($n['sub'] as $sublink => $subname) {
          $html .= '<li><a href="' . $sublink . '"><i class="' . $n['icon'][$sublink] . '"></i>' . $subname . '</a></li>';
        }
        $html .= '</ul>';
      }
      $html .= '</li>';
    } else {      
      if (strpos(trim($selector, '/'), trim($l, '/')) === 0) {
        $html .= "<li class=\"active\"><a href=\"{$l}\">{$n}</a></li>";
      } else {
        $html .= "<li><a href=\"{$l}\">{$n}</a></li>";
      }
    }
  }

  echo $html;
}