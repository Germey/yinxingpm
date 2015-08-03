<?php

// function sms_send($mobile, $content) {
//     if (empty($content)) {
//         return '短信内容不能为空';
//     }

//     if(!is_array($mobile)) {
//         $mobiles = array($mobile);
//     } else {
//         $mobiles = $mobile;
//     }
//     $mobiles = array_unique($mobiles);
    
//     $res = array();
//     foreach ($mobiles as $m) {
//         $res[strval($m)] = sms_send_smsbao($m, $content);
//     }
//     if(!is_array($mobile)) {
//         return $res[$mobile];
//     }
//     return $res;
// }

function sms_send_smsbao($mobile, $content, $account) {

    if (empty($content)) {
        return '短信内容不能为空';
    }

    if(!Utility::IsMobile($mobile)) {
        return 'Bad mobile';
    }

    $content = Utility::SmsContent($content);

    $smsapi = smsbao_api_url();                    //短信网关
    $charset = "utf8";                              //文件编码
    $user = $account["sms_user"];
    $pass = $account["sms_pass"];
    $pass = md5($pass);
    /*去掉这块 - IE有bug*/
    // if (function_exists('curl_exec') == 1)
    // {
    //     $sendurl = "http://{$smsapi}/sms?u={$user}&p={$pass}&m={$mobile}&c=".urlencode($content);
    //     $result = Utility::HttpRequest($sendurl);
    // } else {
        $snoopy = new snoopy();
        $sendurl = "http://{$smsapi}/sms?u={$user}&p={$pass}&m={$mobile}&c=".urlencode($content);
        $snoopy->fetch($sendurl);
        $result = $snoopy->results;
    // }
    
    if (trim(strval($result))=='') $result='-2';
    $code = intval(trim($result));
    if($code === 0) {
        return true;
    } else {
        return smsbao_status($code);
    }
}

function check_smsbao_balance($account){
    $user = $account['sms_user'];
    $pass = md5($account['sms_pass']);
    $smsapi = smsbao_api_url();
    $snoopy = new snoopy();
    $sendurl = "http://{$smsapi}/query?u={$user}&p={$pass}";
    $snoopy->fetch($sendurl);
    $result = $snoopy->results;
    $code = explode("\n", $result);

    //返回代码类似  0\r 33123,1223  0表示成功，第一个数字表示已发送量，第二个数字表示剩余
    if(count($code) === 1){
        return smsbao_status($statusStr);
    }else if ($code[0] == 0) {
        $balance = $code[1];
        $balance = explode(",", $code[1]);
        // $already = $balance[0];
        // $left = $balance[1];
        return $balance;
        // return array("already" => $already, "left" => $left);
    };
}

function smsbao_status($code){
        $statusStr = array(
        "0" => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "密码错误",
        "40" => "账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    );
    return $statusStr[$code];
}

function smsbao_api_url(){
    return  "api.smsbao.com"; 
}