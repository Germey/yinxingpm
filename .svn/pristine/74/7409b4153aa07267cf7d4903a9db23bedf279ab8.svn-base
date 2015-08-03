<?php

class Mailer {

  static private $from = null;
  function __construct()
  {
  }

  static private function EscapeHead($string, $encoding='GB2312')
  {
    $string  = mb_convert_encoding($string, $encoding, "UTF-8");
    return '=?' . $encoding . '?B?'. base64_Encode($string) .'?=';
  }

  static private function EscapePart($string, $encoding='GB2312')
  {
    $string = mb_convert_encoding($string, $encoding, 'UTF-8');
    return preg_replace_callback( '/([\x80-\xFF]+.*[\x80-\xFF]+)/' ,create_function ( '$m' ,"return \"=?$encoding?B?\".base64_encode(\$m[1]).\"?=\";") ,$string);
  }
  
  /*
   *  发送邮件的最后一步
   *  @param options  
      contentType    = true
      messageId    = null
      encoding    = 'UTF-8'
   */
  static function SendMail($from, $to, $subject, $message, $options=null, $bcc=array())
  {
    global $INI; $from = "{D('Options')->getOption('system']['sitename']} <{$from}>";
    if ( !isset($options['encoding']) )
      $options['encoding']   = 'UTF-8';

    if ( !isset($options['contentType']) )
      $options['contentType'] = 'text/plain';

    if ( 'UTF-8'!=$options['encoding'] )
      $message = mb_convert_encoding($message, $options['encoding'], 'UTF-8');

    $message = chunk_split(base64_encode($message));
    $subject = self::EscapeHead($subject, $options['encoding']);
    $from = self::EscapePart($from, $options['encoding']);
    $to = self::EscapePart($to, $options['encoding']);

    $headers = array(
        "Mime-Version: 1.0",
        "Content-Type: {$options['contentType']}; charset={$options['encoding']}",
        "Content-Transfer-Encoding: base64",
        "X-Mailer: ZTMailer/1.0",
        "From: {$from}",
        "Reply-To: {$from}",
        );
    if ($bcc) { 
      $bcc = join(', ', $bcc);
      $headers[] = "Bcc: {$bcc}";
    }
    $headers = join("\r\n", $headers);

    if ( isset($options['messageId']) )
      $headers["Message-Id"] = "<$options[messageId]>";

    return mail($to, $subject, $message, $headers);
  }


  /**
   * @param [type]  $froms       
   * @param [type]  $to          
   * @param [type]  $subject     
   * @param [type]  $message     
   * @param [type]  $options     
   * @param array   $bcc         
   * @param integer $batch_id    batch_id = 0 if single email 
   * @param integer $ob_email_id if $ob_email_id is set then it will not save this email to outbound email table
   */
  static function SmtpMail($froms, $to, $subject, $message, $options=null, $bcc=array(), $ob_email_id = 0)
  {
    if ( !isset($options['encoding']) )
      $options['encoding']   = 'UTF-8';

    if ( !isset($options['contentType']) )
      $options['contentType'] = 'text/html';

    if ( 'UTF-8'!=$options['encoding'] )
      $message = mb_convert_encoding($message, $options['encoding'], 'UTF-8');

    if($froms && $froms['email'] && $froms['pass']) {
      $host =$froms['host'];
      $port = $froms['port'];
      $ssl = $froms['ssl'];
      $user = $froms['email'];
      $pass = $froms['pass'];
      $from = $froms['from'];
      $reply = $froms['reply'];
      $site = $froms['name'];
    } else {

      $host = D('Options')->getOption('mail_smtp_host');
      $port = D('Options')->getOption('mail_smtp_port');
      $ssl = D('Options')->getOption('mail_smtp_ssl');
      $user = D('Options')->getOption('mail_smtp_user');
      $pass = D('Options')->getOption('mail_smtp_pass');
      $from = D('Options')->getOption('mail_smtp_from');
      $reply = D('Options')->getOption('mail_smtp_reply');
      $site = D('Options')->getOption('smtp_sitename');
    }
    $original_subject = $subject;
    $subject = self::EscapeHead($subject, $options['encoding']);
    $site = self::EscapeHead($site, $options['encoding']);
    $body = $message . $signature = D('Options')->getOption('mail_smtp_signature');

    $ishtml = ($options['contentType']=='text/html');
    //begin
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->CharSet = $options['encoding'];
    $mail->SMTPAuth   = true; 
    $mail->Host = $host;
    $mail->Port = $port;
    if ( $ssl=='ssl' ) {
      $mail->SMTPSecure = "ssl"; 
    } else if ( $ssl == 'tls' ) {
      $mail->SMTPSecure = "tls"; 
    }
    $mail->Username = $user;
    $mail->Password = $pass;
    if(!empty($from)){
      $mail->SetFrom($from, $site);
    }
    if(!empty($reply)){
      $mail->AddReplyTo($reply, $site);
    }
    foreach($bcc AS $bo) {
      $mail->AddBCC($bo);
    }
    $mail->Subject = $subject;
    if ( $ishtml ) {
      $mail->MsgHTML($body);
    } else {
      $mail->Body = $body;
    }


    if(!is_array($to)) {
        $tos = array($to);
    } else {
        $tos = $to;
    }
    foreach ($tos as $m) {
      $mail->AddAddress($m);
    }
    if($options['attachments']) {
      foreach ($options['attachments'] as $single_file){
        $mail->AddAttachment($single_file);
      }
    }
    $res = $mail->Send();

    return $res;
  }
}
?>
