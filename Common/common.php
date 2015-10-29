<?php

import("ORG.Crypt.Crypt");

//在BaseAction中调用
function env_init(){
}


function is_select_active($link){
    $action_url = __ACTION__;
    $action_url = str_replace("/index.php", "", $action_url);
    if($link == $action_url){
        return true;
    }
    return false;
}

function gen_page_link($name) {
  if(D("Options")->GetOption('rewrite_no')) {
    return '/page/?name=' . $name;
  } else {
    return '/' . $name;
  }
}


function image_path($image=null, $type="", $size=false) {
  if (!$image) return null;
  if(strpos($image, "/") !== 0){
    $image = '/' . trim($image);
  }

  if($type == "point"){
      $image_size_map = array(
        'thumbnail' => array('w' => 220, 'h' => 150),
        'big' => array('w' => 1000, 'h' => 360),
      );
  }else if($type == "page"){
      $image_size_map = array(
        'thumbnail' => array('w' => 260, 'h' => 150),
        'big' => array('w' => 1000, 'h' => 360),
      );
  }else{
      $image_size_map = array(
        'thumbnail' => array('w' => 220, 'h' => 150),
        'big' => array('w' => 1000, 'h' => 360),
      );
  }



  if ($size === true) {
      $size = 'thumbnail';
  }

  if (is_string($size)) {
      if($size != ""){
        $postfix = "_" . $size;
      }else{
        $postfix = "";
      }
      $path = WWW_ROOT . '/uploads' . $image;

      $image = preg_replace('#(\w+)\.(\w+)$#', "\\1$postfix.\\2", $image);
      $dest = '/uploads' . $image;
      
      $dest_path = WWW_ROOT . $dest;
      if (!file_exists($dest_path) && file_exists($path) ) {
         Image::Convert($path, $dest_path, $image_size_map[$size]['w'], $image_size_map[$size]['h'], Image::MODE_CUT, D("Options")->getOption('grey_upload_image'));
         //grayscale the image
      }      
  } else {
      $dest = '/uploads' . $image;
  }
  return D('Options')->GetOption('webroot_pm') . $dest;
}


function pagestring($count, $pagesize, $wap=false) {

  if(isset($_GET['page']) && intval($_GET['page'])==0) {
      $pagesize = $count;
  }
  $p = new Pager($count, $pagesize, 'page');
  return array($pagesize, $p->pageNo, $p->genBasic());
}


// that the recursive feature on mkdir() is broken with PHP 5.0.4 for
function RecursiveMkdir($path) {
  if (!file_exists($path)) {
    RecursiveMkdir(dirname($path));
    @mkdir($path, 0777);
  }
}


function upload_file($input_name, $type='page') {
  $year = date('Y'); 
  $day = date('md');
  $time = date('Hmis');
  $z = $_FILES[$input_name];
  if($z && $z['error']==0){
    $file_info = pathinfo($z["name"]);
    $n = md5($z['name']).$time . "." . $file_info['extension'];
    RecursiveMkdir( IMG_ROOT . '/' . "{$type}/{$year}/{$day}" );
    $file = "{$type}/{$year}/{$day}/{$n}";
    $path = UPLOAD_ROOT . '/' . $file;
    //convert the encoding to make sure Chinese displays correct for upload files.
    $path=iconv("utf-8","gbk",$path); 
    move_uploaded_file($z["tmp_name"],$path);
  }
  return $file;
}

function upload_image($input, $image=null, $type='page', $scale=false) {
  $year = date('Y'); $day = date('md'); $n = time().rand(1000,9999).'.jpg';
  $z = $_FILES[$input];
  if ($z && strpos($z['type'], 'image')===0 && $z['error']==0) {
    if (!$image) {
      RecursiveMkdir( IMG_ROOT . '/' . "{$type}/{$year}/{$day}" );
      $image = "{$type}/{$year}/{$day}/{$n}";
      $path = IMG_ROOT . '/' . $image;
    } else {
      RecursiveMkdir( dirname(IMG_ROOT .'/' .$image) );
      $path = IMG_ROOT . '/' .$image;
      
      $postfixs = array('_index','_big');
      foreach ($postfixs as $fix) {
        $index_image = preg_replace('#(\w+)\.(\w+)$#', "\\1$fix.\\2", $path);
        unlink($index_image);
      }
    }

    move_uploaded_file($z['tmp_name'], $path);

    return $image;
  }
  return $image;
}


function csubstr($str,$start,$len) {
  $strlen = strlen($str);
  $clen = 0;
  for($i=0; $i<$strlen; $i++,$clen++) {
    if ($clen >= $start+$len) {
      break;
    }
    if(ord(substr($str,$i,1))>0xa0) {
      if ($clen>=$start) {
        $tmpstr.=substr($str,$i,3);
      }
      $i = $i+2;
      $clen++;
    } else {
      if ($clen >= $start)
      $tmpstr .= substr($str,$i,1);
    }
  }
  return $tmpstr;
}

function get_short($str,$len, $ending="...") {
  $tempstr = csubstr($str,0,$len);
  if ($str<>$tempstr) {
    $tempstr .= $ending;
  }
  return $tempstr; 
}

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}


function get_file_encoding($filename){
  $file_handle = fopen($filename, "r");
  $line = fgets($file_handle);
  fclose($file_handle);
  return get_string_encoding($line);
}

function get_string_encoding($str){
  if(is_utf8($str)) return "UTF-8";
  $encoding=mb_detect_encoding($str, array('GB2312','EUC-CN','GBK','UCS-2','UTF-16','BIG5','ASCII'));
  if(empty($encoding)){
      $encoding=detect_utf_encoding($str);
  }
  return $encoding;
}

//用这个方法判断utf-8比较靠谱
function is_utf8($string) {    
    // From http://w3.org/International/questions/qa-forms-utf-8.html
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);    
} // function is_utf8

function detect_utf_encoding($text) {
  $first2 = substr($text, 0, 2);
  $first3 = substr($text, 0, 3);
  $first4 = substr($text, 0, 3);
 
  if ($first3 == UTF8_BOM) return 'UTF-8';
  elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
  elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
  elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
  elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';

 return 'UTF-8';
}

/**************************************************************
*
*    使用特定function对数组中所有元素做处理
*    @param    string    &$array        要处理的字符串
*    @param    string    $function    要执行的函数
*    @return boolean    $apply_to_keys_also        是否也应用到key上
*    @access public
*
*************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
}

//判断PHP数组是否索引数组（列表/向量表）
function is_list($arr) {
    if (!is_array($arr) ) {
        return false;
    } else {
        $keys = array_keys($arr);
        $idx = 0;
        foreach ($keys as $k) {
            if(intval($k) !== $idx++)
              return false;
        }
    }
    return true;
}

function array_to_map($arr) {
    foreach ($arr as $v) {
        $rs[$v] = $v;
    }
    return $rs;
}

function get_post_meta_data($data) {
    foreach ($data as $k => $v) {
        $pos =  strpos($k, '_meta_');
        if( $pos === 0 ) {
          $metas[$k] = $v;
        }        
    }
    return $metas;
}

  function formatBytes($bytes) {
        if($bytes >= 1073741824) {
            $bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
        } elseif($bytes >= 1048576) {
            $bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
        } elseif($bytes >= 1024) {
            $bytes = round($bytes / 1024 * 100) / 100 . 'KB';
        } else {
            $bytes = $bytes . 'Bytes';
        }
        return $bytes;
    }



function current_url() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function url_append($url, $append){
   $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
   $url .= $separator . $append;
   return $url;
}

function generate_order_link($current_key,$type=NULL){
    $param = $_GET;
    //标识是否是当前排序字段,0:不是,1:正序,2:倒序
    $current = 0;
    if($param['order'] ){
      $order = explode("|", $param['order']);
      if($current_key == $order[0]){
        $order[1] = ($order[1]=='desc')?'asc':'desc';
        $current = ($order[1]=='desc')?1:2;
      }else{
        $order[0] = $current_key;
        $order[1] = 'asc';
      }
    }else{
      $order = array($current_key, 'asc');
    }

    // 数字排序标记
    if($type=='number') {
      $order[2] = '1';
    }

    $param['order'] = implode('|', $order);
    unset($param['_URL_']);
    $param = http_build_query($param);
    return array($param, $current);
}

  function encrypt_request($m) {
    $id = $m[2];
    if(is_numeric($id)) {
      return str_replace($m[2], encrypt_id($m[2]), $m[0]);
    }
    return $m[0];
  }

  //xor + convert it to base 31 + reverse
  function encrypt_id($id) {
    $key  = 311879973;
    $id = strrev(($id ^ $key) . rand(1,9));   //后面加个随机数，防止^出来的数字最后一位是0，解出来就不对了
    $eid = 'GSE' . base_convert($id, 10, 31);
    return $eid;
  }

  function decrypt_id($eid) {
    $key  = 311879973;
    if(strpos($eid, 'GSE') === 0) {
      $eid = substr($eid, 3);
      return ((strrev(base_convert($eid, 31, 10))/10) ^ $key);
    }
    return $eid;
  }

  function moneyit($val, $with_sign=true) {
      if(!$val) return '-';
      $n = str_replace(',', '', $val);
      $c = is_float($n) ? 1 : number_format($n,2);
      $d = '.';
      $t = ',';
      $sign = ($n < 0) ? '-' : '';
      $i = $n=number_format(abs($n),2); 
      $j = (($j = $i.length) > 3) ? $j % 3 : 0;
      if($with_sign) {
          $symbol = '￥';
      }  else {
          $symbol = '';
      }
      $res = $symbol.$sign .($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j));
      // return str_replace('.00', '', $res);
      return $res;
  }
  

  function display_map_link($address) {    
      $address = str_replace(' ', '', $address);
      if(!$address) return '';
      return '&nbsp;<a target="_blank" title="单击查看地图" href="http://ditu.google.cn/maps?f=q&q='. urlencode($address).'&ie=UTF8&z=14"><i class="icon-map-marker"></i>'.$tip.'</a>';
  }

  function get_age_by_birthday($birthday) {
      if(!$birthday) return;
      $year = intval(substr($birthday, 0, 4));
      if($year<1900 || $year>2100) {
          return;
      }
      return date('Y') - $year;
  }


  function file_enable_preview($filename) {
      $filename = strtolower($filename);
      $enabled_suffixs = array('pdf', 'txt', 'png', 'jpg', 'jpeg', 'gif','bmp');
      $suffix = strtolower(array_pop(explode('.',$filename)));
      return in_array($suffix, $enabled_suffixs);
  }

  function array_to_pie_chart_json($a) {
      $max_value = max(array_values($a));
      $has_selected = false;
      foreach ($a as $k => $v) {
          $item = array();
          $item['name'] = $k;
          $item['y'] = $v;
          if($max_value == $v && !$has_selected) {
              $item['selected'] = true;
              $item['sliced'] = true;
              $has_selected = true;
          }
          $rs[] = $item;
      }
      return json_encode($rs);
  }

  function can_admin($user) {
      if($user['role']=='admin') {
          return true;
      }
      return false;
  }

  function can_edit($user) {
      if($user['role']=='role_read' || $user['role']=='role_expert' || $user['role']=='role_audit' || $user['role']=='role_finance') {
          return false;
      }
      return true;
  }

  function can_finance($user, $project) {
      if($user['role']=='admin' || $user['role']=='role_finance' || $user['role']=='role_project') {
          return true;
      }
      return false;
  }

  function can_edit_project(&$user, &$proejct) {
      // if($user['role'] == 'admin' || $user['id'] == $proejct['manager_id']) {
      if($user['role'] == 'admin' || $user['role']=='role_project') {
          return true;
      }
      return false;
  }


  function can_audit(&$user) {
      if($user['role'] == 'role_read' || $user['role'] == 'role_volunteer') {
          return false;
      }
      return true;
  }

  function has_submit_apply($status) {
      return intval($status)>=60?1:0;
  }

  function get_quarter_ranges($start, $end) {
      // 检时间格式
      
      $tmp = explode('-', $start);
      $start_year = $tmp[0];
      $start_quarter = get_quarter_by_month($tmp[1]);

      $tmp = explode('-', $end);
      $end_year = $tmp[0];
      $end_quarter = get_quarter_by_month($tmp[1]);

      $loop_quarter = $start_quarter;
      for($i=$start_year;$i<=$end_year;$i++) {
          for($j=$loop_quarter; $j<=4;$j++) {    
              $range[] = $i . $j;
              if($i==$end_year && $j==$end_quarter) {
                  break;
              }
          }
          $loop_quarter=1;
      }

      return $range;
  }

  function get_quarter_by_month($month) {
      $map = array('1'=>1, '2'=>1, '3'=>1, '4'=>2, '5'=>2, '6'=>2, '7'=>3, '8'=>3, '9'=>3, '10'=>4, '11'=>4, '12'=>4);
      return $map[intval($month)];
  }

  // str 20151这样格式
    function get_quarter_display_name($str) {
      $map = array('1'=>'一', '2'=>'二', '3'=>'三', '4'=>'四', );
      return substr($str, 0, 4) . $map[substr($str, 4)] . '季度';
    }


    function json_encode_for_xedit($array) {
        if(!$array) return false;
        if(is_list($array)) $array = array_to_map($array);
        
        foreach ($array as $k => $v) {
            $temp_array[] = array('value'=>$k, 'text'=>$v);
        }
        return json_encode($temp_array);
    }

  /**
   * 把对象转换成数组
   * @param   object  $object 要转换的对象
   * @return  array
   */
  function objectToArray($object) {
      if( count($object)==0 ) {
        if(is_array($object)){
          return array();
        }else{
          return trim((string)$object);
        }
      } 
      $result = array();
      $object = is_object($object) ? get_object_vars($object) : $object;
      foreach ($object as $key => $val) {
          // if(is_array($val) && count($val) == 0){
          //   $val = null;
          // }
          // 
          $val = (is_object($val) || is_array($val)) ? objectToArray($val) : $val;
          $result[$key] = $val;
      }
      return $result;
  }

  function generate_order_str($order_by, $desc, $as_number){
      if($as_number) {
          return $order_by . '*1 ' . $desc;
      }
      return "convert(" . $order_by . " USING gbk) COLLATE gbk_chinese_ci " . $desc;
  }

  function getSelectOptions($options, $extra = null) {
    //$result = D("UserInfo")->Distinct(true)->field($options)->select();
    //$result = D("UserRecommends")->join('user_info ON user_recommends.recommend_user_id = user_info.id')->distinct(true)->field($options)->select();
    $sql = "select distinct ". $options . " from user_info, user_recommends where user_recommends.recommend_user_id = user_info.id";
    if ($extra == "partner") {
      $sql .= " and status = 99";
    } 
    $result = D("")->query($sql);
    $res = "";
    if (strstr($options, ".")) {
      $options = explode( ".", $options)[1];
    }
    foreach ($result as $key => $value) {

      $res .= '<option value="'.$value[$options].'">'.$value[$options]."</option>";
    }
    return $res;
  }