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
    <ul class="nav nav-tabs">
      <li class="active"><a href="/stat/index">基本统计</a></li>
      <!-- <li><a href="/stat/project">项目统计</a></li> -->
    </ul>
    
    <h5>常用统计数据（除第一行项目数统计，以下统计只包含正式资助的项目）</h5>
<!--     <form action="/stat/index" method="get">
        <select class="chosen-select span3" multiple name="sbatchs[]" data-placeholder=" - 项目期 -"><?php echo Utility::Option($batch_range, $sbatchs);?></select>        
        <input type="submit" class="btn btn-small" value="筛选" />
        <a class="btn btn-link" href="/stat/index">重置</a>
    </form>
 -->
    <table class="table table-striped table-hover home-tb">
      <tr>
        <td class="l">总项目数：</td><td class="r"><a target="_blank" href="/project/all"><?php echo ($project_count); ?>个</a></td>
        <td class="l">资助中/已结束项目数：</td><td class="r"><?php echo ($ing_project_count); ?>&nbsp;<span class="muted">/</span>&nbsp;<?php echo ($end_project_count); ?>个</td>
        <td class="l">未通过：</td><td class="r"><?php echo ($fail_project_count); ?>个</td>
      </tr>
      <tr>
        <td class="l">总申请/资助额：</td><td class="r"><?php echo moneyit($all_need_budget);?>&nbsp;<span class="muted">/</span>&nbsp;<?php echo moneyit($total_contract_budget);?></td>
        <td class="l">已拨款额：</td><td class="r"><?php echo moneyit($had_grant_amount);?></td>
        <td class="l">待拨款额：</td><td class="r"><?php echo moneyit($total_contract_budget - $had_grant_amount);?></td>
      </tr>
      <tr>
        <td class="l">最大项目金额：</td><td class="r"><a target="_blank" href="/project/detail/<?php echo ($big_budget_id); ?>"><?php echo moneyit($big_budget);?></a></td>
        <td class="l">最小项目金额：</td><td class="r"><a target="_blank" href="/project/detail/<?php echo ($small_budget_id); ?>"><?php echo moneyit($small_budget);?></a></td>
        <td class="l">平均项目金额：</td><td class="r"><?php echo moneyit($average_budget);?></td>
      </tr>
      <tr>
        <td class="l">最大项目周期：</td><td class="r"><a target="_blank" href="/project/detail/<?php echo ($long_period_id); ?>"><?php echo ($long_period); ?>月</a></td>
        <td class="l">最短项目周期：</td><td class="r"><a target="_blank" href="/project/detail/<?php echo ($short_period_id); ?>"><?php echo ($short_period); ?>月</a></td>
        <td class="l">平均项目周期：</td><td class="r"><?php echo ($average_period); ?>月</td>
      </tr>
<!--       <tr>
        <td class="l">申请机构数：</td><td class="r"><a target="_blank" href="/partner"><?php echo ($partner_count); ?>个</a></td>
        <td class="l">机构平均项目数：</td><td class="r"><?php echo ($partner_average_project_count); ?>个</td>
        <td class="l">机构最大/最小项目数：</td><td class="r"><a target="_blank" href="/partner/detail/<?php echo ($partner_max_project_id); ?>"><?php echo ($partner_max_project_count); ?></a> / <?php echo ($partner_min_project_count); ?>个</td>
      </tr> -->
    </table>

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