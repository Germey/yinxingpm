<?php


    //functions
      function utf8_urldecode($str) {
          $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
          $str = str_replace('\\', '', $str);
          return html_entity_decode($str,null,'UTF-8');;
      }


      function email_filter(&$subject, &$body, $param) {
          $subject    = str_replace('[#user_name#]', $param['to_name'], $subject);

          //user_name with format
          $body = preg_replace('/\<a\s(.+)\[#user_name#\]\<\/a\>/', $param['to_name'], $body);
          
          //others, to_name, fix image path 
          $replaced   = array('[#user_name#]', 'src="/uploads/page/');
          $replacing  = array($param['to_name'],  'src="'. D('Options')->getOption('webroot_pm') .'/uploads/page/');
          $body = str_replace($replaced, $replacing, $body);

          return array('subject' => $subject, 'body' => $body);
      }

      function email_content_filter($str) {
          $str = str_replace('"','\'',$str);
          /*if(strpos('[#signature#]', $str) === FALSE) {
            $str .= '<br />[#signature#]'; 
          }*/
          return $str;
      }


      function sync_event_users() {
          $current_users = OutboundUsers::GetPartnerUsers(1);
          $current_users_by_mobile = Utility::AssColumn($current_users, 'mobile');
          $users = Users::GetUsers(NULL, 0, 50000);

          global $g_user_type;
          global $g_active_status;
          foreach ($users as $i => $eu) {
            if(!$current_users_by_mobile[$eu['mobile']]) {
              $new_id = OutboundUsers::AddCommonUser(1, $eu);
              $new_ids[] = $new_id;

              //添加默认标签
              if($eu['user_type'] && $eu['user_type']!=='other') {
                $gid = OutboundUserGroups::AddGroup(1, $g_user_type[$eu['user_type']]);
                  OutboundUserGroups::AddMap($new_id, $gid);
              }
              if($eu['active_status']) {
                $gid = OutboundUserGroups::AddGroup(1, $g_active_status[$eu['active_status']]);
                  OutboundUserGroups::AddMap($new_id, $gid);
              }
              $current_users_by_mobile[$eu['mobile']] = 1;
            }
          }
          return $new_ids;
      }

      function GenAttachmentLink($serialize_file, $length=0) {
          if(!$serialize_file) return NULL;
          $str = NULL;

          $files = unserialize($serialize_file);

          foreach ($files as $idx => $file) {
              $checkfile = iconv('UTF-8','GB2312',$file);
              if(!$file || !file_exists($checkfile)) {
                  //log error
                  continue;
              }
              $file_name = substr($file, strrpos($file, '/')+1);
              if($length) $short_name = get_short($file_name, $length);
              $file_www_path = str_replace('//', '/', substr($file, strpos($file, '/uploads')));
              $str .= "<div><a target='_blank' title='$file_name' href='$file_www_path'>$short_name</a></div>";
          }
          return $str; 
      }
?>