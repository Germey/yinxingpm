<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  lang="en">
<head>
  <meta http-equiv=content-type content="text/html; charset=UTF-8" />
  <title><?php if($pagetitle): echo ($pagetitle); ?>&nbsp;-&nbsp;<?php endif; echo ($INI['site_name']); ?></title>
  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge" /> -->
  <link rel="shortcut icon" href="/styles/css/images/favicon.ico" />
  <link rel="stylesheet" href="/styles/css/bootstrap.css"  media="screen" charset="utf-8" />
  
  <script src="/styles/js/jquery.js"></script>
  <script src="/styles/js/bootstrap.min.js"></script>
  <script src="/styles/js/jquery.validator.js"></script>
  <script src="/styles/js/jquery.placeholder.min.js"></script>
  <script src="/styles/js/custom/map.js"></script>

  <link rel="stylesheet" href="/styles/js/jquery.chosen/chosen.css"  media="screen" charset="utf-8" />
  <script src="/styles/js/jquery.chosen/chosen.jquery.min.js"></script>
  <script src="/styles/js/jquery.formatCurrency.js"></script>

  <script src="/styles/js/jquery.datetimepicker/bootstrap-datetimepicker.js"></script>
  <script src="/styles/js/jquery.datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>
  <link rel="stylesheet" href="/styles/js/jquery.datetimepicker/bootstrap-datetimepicker.css"  media="screen" />

  <script src="/styles/js/ueditor/editor_config.js"></script>
  <script src="/styles/js/ueditor/editor_all_min.js"></script>
  <script src="/styles/js/FBcomplete/jquery.fcbkcomplete.js"></script>
  <link rel="stylesheet" href="/styles/js/FBcomplete/style.css"  media="screen" charset="utf-8">

  <link href="/styles/js/select2/select2.css" rel="stylesheet"/>
  <script src="/styles/js/select2/select2.js"></script>

  <script src="/styles/js/custom/application.js"></script>
  <script src="/styles/js/custom/customize.js"></script>


  <script src="/styles/js/jquery.colorpicker/simplecolorpicker.js"></script>
  <link rel="stylesheet" href="/styles/js/jquery.colorpicker/simplecolorpicker.css"  media="screen" charset="utf-8">
  <link rel="stylesheet" href="/styles/css/main.css"  media="screen" charset="utf-8" />

  <script src="/styles/js/colortip/colortip-1.0-jquery.js"></script>
  <link rel="stylesheet" href="/styles/js/colortip/colortip-1.0-jquery.css" />

  <script src="/styles/js/bootstrap-editable/js/bootstrap-editable.min.js"></script>
  <link href="/styles/js/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" />

</head>
<body>
<div>

<?php if(Session::get('success')): ?><div class="alert alert-success" style="text-align: center"><button type="button" class="close" data-dismiss="alert">×</button><?php echo Session::get("success", true);?></div><?php endif; ?>
<?php if(Session::get('error')): ?><div class="alert alert-error"  style="text-align: center"><button type="button" class="close" data-dismiss="alert">×</button><?php echo Session::get("error", true);?></div><?php endif; ?>
<?php if(Session::get('ip_banned', true)): ?><div class="alert alert-error"  style="text-align: center"><button type="button" class="close" data-dismiss="alert">×</button>由于您多次密码输入失败，系统暂时无法登陆，请稍后再尝试</div><?php endif; ?>
  <div class="login_wrapper">
    <div class="login_container">
      <div class="login_block">
      <form class="login_form" action="__URL__/index" method="post">
        <input type="hidden" name="is_admin" value="">
        <input type="hidden" name="ref" value="<?php echo ($_GET['ref']); ?>">
        <div class="title">
          <a href="/"><img id="logo" src="<?php echo image_path('logo.png');?>"  style="max-width:212px;"></a>
        </div>
        <div class="input-prepend login_email login_input_span">
          <span class="add-on"><i class="icon-user"></i></span>
          <input type="text" class="input-block-level" name="email" placeholder="邮箱">
        </div>
        <div class="input-prepend login_password login_input_span">
          <span class="add-on"><i class="icon-password"></i></span>
          <input type="password" class="input-block-level" name="password" placeholder="密码">
        </div>
        <div class="input-prepend login_password login_input_span">
          <span class="add-on">验证码</span>
          <input type="text" class="input-block-level" name="verifycode" style="width:80px" placeholder="验证码">
          <img id="verifyimg" style="height:30px" src="/Common/captcha" onclick="jQuery('#verifyimg').attr('src','/Common/captcha');" title="点击刷新验证码" /> 
        </div>
        <div class="clear"></div>
        <div class="confirm" style="text-align:center"><button class="login_button btn btn-primary " type="submit">登 录</button></div>
      </form>
      <div class="clear"></div>
      </div>
    </div>
      <div class="powered_by"><small>技术支持：<a href="http://www.justering.com" target="_blank">佳信德润</a></small></div>
  </div>