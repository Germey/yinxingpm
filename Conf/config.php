<?php

return array(
    'LOAD_EXT_CONFIG' => 'static,db',    
    // 'SHOW_PAGE_TRACE'=>True,
    'APP_AUTOLOAD_PATH' => "@.Autoload.,ORG.Util.",

    'APP_GROUP_LIST' => "Frontend,Common,Admin",
    'DEFAULT_GROUP'   => "Frontend",
    'TMPL_FILE_DEPR'  => "/",
    'SHOW_PAGE_TRACE' => true,
    'URL_CASE_INSENSITIVE' =>true,
    "LOAD_EXT_FILE"=>"json,nav,widget,mail,snoopy,sms",

    'URL_MODEL'=>2,
    'OUTPUT_ENCODE'=>false,
    'URL_ROUTER_ON'   => true, //开启路由
    'URL_ROUTE_RULES' => array( //定义路由规则
        // 'project/:id\d' => 'Frontend/Project/index',        //直接ID不允许访问
        // 'project/detail/:id\d' => 'Frontend/Project/index',        //直接ID不允许访问
        'user/detail/:id' => 'Frontend/User/detail',
        'project/detail/:id' => 'Frontend/Project/detail',
        'contact/edit' => 'Frontend/Contact/edit',
        // 'contact/:id\d' => 'Frontend/Contact/index',        //直接ID不允许访问
        // 'partner/:id\d' => 'Frontend/Partner/index',
        // 'partner/detail/:id\d' => 'Frontend/Partner/detail',
        'school/detail/:id' => 'Frontend/School/detail',
        'error_page' => 'Common/Common/error_page',
    ),
    'LANG_SWITCH_ON' => true,

    'TMPL_STRIP_SPACE'    => false,
);

?>