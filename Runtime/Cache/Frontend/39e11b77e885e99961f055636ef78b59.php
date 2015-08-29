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

<div class="navbar navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="/">
        <?php if($login_user['team']['title_logo']): ?><img src="<?php echo image_path($login_user['team']['title_logo']);?>" style="height:38px;">
          <?php else: ?>
          <?php echo ($login_user['team']['name']); endif; ?>
        </a>
      <div class="nav-collapse collapse" >
        <ul class="nav"><?php echo generate_nav($selector, $login_user);?></ul>
      </div>
        <div class="nav-right">
          <?php echo (L("welcome")); ?>, <?php echo ($login_user['username']); ?><span style="font-size:11px"> &nbsp; </span><a href="/login/logout">退出</a>
        </div>
    </div>
  </div>
</div>

<div class="container" style="padding-top:10px; min-height:500px">

    <div id="fix-alert">
        <?php if(Session::get('success')): ?><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><?php echo strip_tags(Session::get("success", true));?></div><?php endif; ?>
        <?php if(Session::get('error')): ?><div class="alert alert-error"  style="text-align: center"><button type="button" class="close" data-dismiss="alert">×</button><?php echo strip_tags(Session::get("error", true));?></div><?php endif; ?>
    </div>

    <script type="text/javascript">
        setTimeout("$('#fix-alert').slideUp('slow', function(){ $('#fix-alert').hide(); })", 2000);
    </script>

    <?php if($login_user['team']['logo']): ?><a href="/"><img alt="<?php echo ($login_user['team']['name']); ?>" id="logo" src="<?php echo image_path($login_user['team']['logo']);?>"></a>
    <?php else: ?>
        <div style="height:5px"></div><?php endif; ?>
    
<div id="content" class="wide">
    <h4>邮箱相关设置</h4>
    <form action="__URL__/email" method="post" class="form-horizontal" enctype="multipart/form-data">
        <h5>1. 评审成功后需要密送的邮箱（管理员邮箱）&nbsp;&nbsp;
            <input type="text" class="span3" name="audit_success_notify_email" value="<?php echo ($audit_success_notify_email); ?>" />
        </h5>
        <h5>2. 评审完马上给发成功/失败通知&nbsp;&nbsp;
            <?php echo Utility::Radios('audit_email_enable_realtime_notice_user', array(0=>'关闭',1=>'开启'), $audit_email_enable_realtime_notice_user, true);?>
        </h5>

        <h5>3. 不同评审状态下成功/失败邮件模板设置</h5>
        <ul id="myTab" class="nav nav-tabs">
            <?php if(is_array($audit_user_ststuses)): foreach($audit_user_ststuses as $key=>$one): ?><li <?php echo ($key==$hilighttypeid?"class='active'":""); ?> >
                  <a href="#tab<?php echo ($one['id']); ?>" data-toggle="tab"><?php echo ($one['name']); ?></a>
                </li><?php endforeach; endif; ?>
        </ul>
        <div id="myTabContent" class="tab-content">
        <?php  $email_tos = array(''=>'-无-','recommender_email'=>'推荐人', 'email'=>'被推荐人(准伙伴)'); ?>
            <?php if(is_array($audit_user_ststuses)): foreach($audit_user_ststuses as $key=>$one): ?><div class="tab-pane fade in <?php echo ($key==$hilighttypeid?"active":""); ?>" id="tab<?php echo ($one['id']); ?>">
                    <h5><?php echo ($one['name']); ?> - 评审成功后给通知邮件</h5>
                    <blockquote>
                        <p>1. 留空即不再通知</p>
                        <p>2. 邮件内容中的替换字段：[#name#]：姓名；[#audit_content#]：评审意见；[#recommender_name#]：推荐人姓名</p>
                        <?php if($one['id'] < 60): ?><p class="text-error"><b>3. 当前状态下邮件只发送给推荐人</b></p>
                        <?php else: ?>
                            <p class="text-error"><b>3. 当前状态下邮件发送给准伙伴（申请人），抄送给推荐人</b></p><?php endif; ?>
                    </blockquote>
                    <p>
                        邮件题目：<input type="text" class="span7" name="audit_email_success_subject_<?php echo ($one['id']); ?>" value="<?php echo ($success_subjects[$one['id']]); ?>" />
                        发送给：<select type="text" class="span2" name="audit_email_success_to_<?php echo ($one['id']); ?>"><?php echo Utility::Option($email_tos,$success_tos[$one['id']]);?></select>
                    </p>
                    <textarea name="audit_email_success_body_<?php echo ($one['id']); ?>" class="editor" id="audit_email_success_<?php echo ($one['id']); ?>">
                        <?php echo ($success_bodys[$one['id']]); ?>
                    </textarea>
                    <?php if($one['id'] > 20): ?><hr />
                        <h5>评审失败后给通知邮件</h5>
                        <p>
                            邮件题目：<input type="text" class="span7" name="audit_email_fail_subject_<?php echo ($one['id']); ?>" value="<?php echo ($fail_subjects[$one['id']]); ?>" />
                            发送给：<select type="text" class="span2" name="audit_email_fail_to_<?php echo ($one['id']); ?>"><?php echo Utility::Option($email_tos,$fail_tos[$one['id']]);?></select>
                        </p>
                        <textarea name="audit_email_fail_body_<?php echo ($one['id']); ?>" class="editor" id="audit_email_fail_body_<?php echo ($one['id']); ?>"><?php echo ($fail_bodys[$one['id']]); ?></textarea><?php endif; ?>
                    <?php if($one['id'] == 60): ?><hr />
                        <h5>驳回修改的通知邮件</h5>
                        <p>
                            邮件题目：<input type="text" class="span7" name="audit_email_neededit_subject_<?php echo ($one['id']); ?>" value="<?php echo ($neededit_subjects[$one['id']]); ?>" />
                            发送给：<select type="text" class="span2" name="audit_email_neededit_to_<?php echo ($one['id']); ?>"><?php echo Utility::Option($email_tos,$neededit_tos[$one['id']]);?></select>
                        </p>
                        <textarea name="audit_email_neededit_body_<?php echo ($one['id']); ?>" class="editor" id="audit_email_neededit_body_<?php echo ($one['id']); ?>">
                            <?php echo ($neededit_bodys[$one['id']]); ?>
                        </textarea><?php endif; ?>
                </div><?php endforeach; endif; ?>
        </div>

        <br /><input type="submit" class="btn btn-danger" value="保存" />
    </form>
</div>
</div>
<div class="clear"></div>
<div id="modaldialog"></div>
</div>  
<div class="footer" >
    <div class="container">
        <small>技术支持：<a href="http://www.justering.com" target="_blank">佳信德润</a></small>
    </div>
</div>
<!-- Subscribe Modal -->
<div id="feedback_modal" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true"  style="width:400px" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">XXX</h4>
    </div>
        <form class="form-horizontal validator" >
            <div class="modal-body">
                    <div class="control-group">
                        <label class="control-label require" for="input_name">您的姓名</label>
                        <div class="controls"><input type="text" name="subscriber_name" id="subscriber_name" class="input-medium span3"></div>
                    </div>
                    <div class="control-group ">
                        <label class="control-label" for="input_email">您的邮箱</label>
                        <div class="controls"><input type="text"  name="subscriber_email" id="subscriber_email" class="input-medium span3"></div>
                    </div>            
            </div>
        </form>
    <div class="modal-footer">
        <button class="btn btn-primary" onclick="submit_subscribe()">订 阅</button>
    </div>
</div>

<script type="text/javascript">
    function submit_subscribe(){
        var name = $("#subscriber_name").val();
        var email = $("#subscriber_email").val();
        X.get("/common/common/subscribe?name=" + name +"&email=" + email + "");
    }
</script>
</div>
</body>
</html>



<script type="text/javascript">
    $(document).ready(function(){
        $('.mytooltip').tooltip();
        $(".datepicker").datetimepicker({language: 'zh-CN',startView: 2,minView: 2,format: "yyyy-mm-dd",autoclose: true,todayBtn: true});
        $(".colortip").colorTip({color:'black'});
    });

</script>