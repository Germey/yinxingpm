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
    <h4>
        问答题设置
        <a href="__URL__/question_edit" class="btn pull-right ajaxlink"><i class="icon-pencil"></i> 新建问答题</a>
    </h4>
    <ul id="myTab" class="nav nav-tabs">
        <?php if(is_array($user_types)): foreach($user_types as $key=>$one): ?><li <?php echo ($key==$hilighttypeid?"class='active'":""); ?> >
              <a href="#tab<?php echo ($key); ?>" data-toggle="tab"><?php echo ($one); ?></a>
            </li><?php endforeach; endif; ?>
    </ul>
    <div id="myTabContent" class="tab-content">
        <?php if(is_array($user_types)): foreach($user_types as $key=>$name): ?><div class="tab-pane fade in <?php echo ($key==$hilighttypeid?"active":""); ?>" id="tab<?php echo ($key); ?>">
                <table class="table table-striped table-hover">
                    <tr>
                        <th>编号</th>
                        <th>题目</th>
                        <th>备注</th>
                        <th>顺序</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($questions[$key])): foreach($questions[$key] as $key=>$one): ?><tr>
                            <td><?php echo ($one["id"]); ?></td>
                            <td><?php echo ($one["question"]); ?></td>
                            <td><?php echo ($one["note"]); ?></td>
                            <td><?php echo ($one["sequence"]); ?></td>
                            <td>
                                <a href="__URL__/question_edit?id=<?php echo ($one["id"]); ?>" class="ajaxlink">编辑</a>
                                <a href="__URL__/ajax_delete_question?id=<?php echo ($one["id"]); ?>" ask="确定删除吗？" class="ajaxlink">删除</a>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
            </div><?php endforeach; endif; ?>
      </div>

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