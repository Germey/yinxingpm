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
    <?php if($is_todo == 'Y'): ?><h4>
  申请处理列表&nbsp;
  <span class="simple">（展示签约前状态的所有申请人）</span>
  <?php if(can_audit($login_user)): ?><label class="checkbox pull-right">
        <input type="checkbox" class="ajaxlink" onclick="only_me(this)" <?php echo ($login_user['show_only_related_me']?'checked':''); ?>> 只显示和我有关的
    </label><?php endif; ?>
</h4>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>申请人</th>
      <th>推荐人</th>
      <th>申请类型</th>
      <th>更新时间</th>
      <?php if(is_array($display_status)): foreach($display_status as $key=>$one): ?><th><?php echo str_replace('待','',$one);?></th><?php endforeach; endif; ?>
    </tr>
  </thead>
  <?php if(is_array($users)): foreach($users as $key=>$one): ?><tr>
      <td>
        <a target="_blank" href="/user/detail/<?php echo ($one['id']); ?>"><?php echo ($one['name']); ?></a>
      </td>
      <td><?php echo ($one['apply_type_name']); ?></td>
      <td><?php echo substr($one['update_time'],0,16);?></td>
      <?php if(is_array($display_status)): foreach($display_status as $key=>$status): ?><td <?php echo ($key==$one['status']?'style="background:#dff0d8"':''); ?>>
          <!-- 如果已经完成，就不能再编辑，没有完成的就可以再编辑 -->
          <?php if($audits[$one['id']][$key]['audit_time']): ?><span class="text-success"><?php echo ($audits[$one['id']][$key]['audit_user_name_display']); ?> √</span>
          <?php elseif(can_admin($login_user)): ?>
            <a href="#" class="username" id="<?php echo ($one['id']); ?>-<?php echo ($key); ?>" data-type="select" data-pk="<?php echo ($one['id']); ?>" data-url="/user/ajax_change_audit_user" data-title="选择评审人">
              <span <?php echo ($login_user['id']==$audits[$one['id']][$key]['audit_user_id']?'class="text-error"':''); ?>>
                <?php echo ($audits[$one['id']][$key]?$audits[$one['id']][$key]['audit_user_name_display']:'未分配'); ?>
              </span>
            </a>
          <?php else: ?>
            <span <?php echo ($login_user['id']==$audits[$one['id']][$key]['audit_user_id']?'class="text-error"':''); ?>>
              <?php echo ($audits[$one['id']][$key]?$audits[$one['id']][$key]['audit_user_name_display']:'-'); ?>  
            </span><?php endif; ?>
        </td><?php endforeach; endif; ?>      
    </tr><?php endforeach; endif; ?>
</table>

<script type="text/javascript">
  $('.username').editable({
      source: <?php echo ($audit_user_json_string); ?>
  });  


  function only_me(item) {
      var to = 0;
      if($(item).attr('checked')==='checked') {
        to = 1;
      }
      X.get('/user/ajax_only_me?to='+to);    
  }
</script>
    <?php else: ?>
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs">    
            <?php if(is_array($sub_types)): foreach($sub_types as $key=>$one): ?><li class="<?php if($key == $status): ?>active<?php endif; ?>"><a href="/user/<?php echo ($action_name); ?>?status=<?php echo ($key); ?>"><?php echo ($one); ?></a></li><?php endforeach; endif; ?>
          </ul>
          <div class="tab-content">
            <h4 class="pull-left"  style="font-size:18px; margin-right: 10px; margin-top: 12px; color:#666;"><?php echo ($apply_type_name); ?></h4>
<?php if(can_edit($login_user)): ?><div class="btn-toolbar">
    <div class="btn-group">
        <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><i class="icon-share-alt"></i>导出 <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="javascript:void(0)" onclick="export_users('check', '<?php echo ($_GET['status']); ?>', '<?php echo ($_GET['apply_type_id']); ?>','')">导出选中成员基本资料</a></li>
          <li><a href="javascript:void(0)" onclick="export_users('all', '<?php echo ($_GET['status']); ?>', '<?php echo ($_GET['apply_type_id']); ?>','')">导出全部成员基本资料</a></li>
          <li class="divider"></li>
          <li><a href="javascript:void(0)" onclick="export_users('check', '<?php echo ($_GET['status']); ?>', '<?php echo ($_GET['apply_type_id']); ?>','school')">导出选中成员任教信息</a></li>
          <li><a href="javascript:void(0)" onclick="export_users('all', '<?php echo ($_GET['status']); ?>', '<?php echo ($_GET['apply_type_id']); ?>','school')">导出全部成员任教信息</a></li>
        </ul>
    </div>
    <button class="btn btn-small" onclick="massdelete()"><i class="icon-trash"></i><?php echo (L("delete")); ?></button>
</div><?php endif; ?>
<form method="get" action="/user/<?php echo ($action_name); ?>" class="view-filter">
    <input type="hidden" name="status" value="<?php echo ($_GET['status']); ?>" />
    <input type="hidden" name="apply_type_id" value="<?php echo ($_GET['apply_type_id']); ?>" />
    <?php echo display_filter_item('name',$_GET['name'],$all_columns);?>
    <?php echo display_filter_item('identifier',$_GET['identifier'],$all_columns);?>
    <?php echo display_filter_item('classify',$_GET['classify'],$all_columns);?>
    <?php echo display_filter_item('recommender_name',$_GET['mobile'],$all_columns);?>
    <?php if($_GET['status']==100): echo display_filter_item('status_note',$_GET['status_note'],$all_columns); endif; ?>
    <button class="btn btn-small"><?php echo (L("search")); ?></button>
    <!-- <button class="btn btn-small" onclick="resetURL()">重置</button> -->
</form>

<table class="table table-striped table-hover">
<tr>
    <th width="10"><input type="checkbox" onclick="checkall(this)"></th>
    <?php if(is_array($list_views)): foreach($list_views as $key=>$v): ?><th class="nowrap"><?php echo column_item_key($v,$all_columns, 'user/'.$action_name);?></th><?php endforeach; endif; ?>
</tr>
<?php if(is_array($users)): foreach($users as $key=>$one): ?><tr class="projectlist <?php if($one['id'] == $_GET['highlight_id']): ?>success<?php endif; ?>" value="<?php echo ($one['id']); ?>" id='tr<?php echo ($one['id']); ?>'>
    <td width="10"><input type="checkbox" id="<?php echo ($one['id']); ?>" value="<?php echo ($one['id']); ?>::<?php echo ($one['email']); ?>::<?php echo ($one['mobile']); ?>"></td>
    <?php if(is_array($list_views)): foreach($list_views as $key=>$v): $class=""; if(strlen($one[$v]) < 20) { $class = 'nowrap'; } ?>
      <td class="<?php echo ($class); ?>"><?php echo column_item_value($v, $one, $list_columns);?></td><?php endforeach; endif; ?>
  </tr><?php endforeach; endif; ?>
</table>
<div class="text-right"><?php echo ($pagestring); ?><div>

<script type="text/javascript">
    //待改进
    $(document).ready(function() {
        $(".icon-edit-small").hide();
    });
    
    $(document).ready(function() {
        $('.icon-edit-small').parent().parent().mouseover(function(){
            var id = $(this).parent().attr('value');
            $("#icon-edit-holder-"+id).hide();
            $("#icon-edit-"+id).show();
        });

        $('.icon-edit-small').parent().parent().mouseout(function(){
            var id = $(this).parent().attr('value');
            $("#icon-edit-"+id).hide();
            $("#icon-edit-holder-"+id).show();
        });

        showTabs();

    });

    //显示部分选项卡
    function showTabs() {
        pathname = window.location.pathname;
        path = pathname.split('/')[2];
        //去掉推荐表审核
        $(".wide .nav-tabs li:eq(0)").remove();
        switch(path) {
            case "ever":
                $(".wide .nav-tabs li:lt(8)").css("display","block");
            break;
            case "fail":
                //$(".wide .nav-tabs li:eq(9)").css("display","block");
            break;
            case "success":
                //$(".wide .nav-tabs li:eq(8)").css("display","block");
            break;
            case "recommend":
                $(".wide .nav-tabs li").css("display","block");
            break;

        }
    }

    //抽离出来，给别的页面也一样用
    function checkall(item) {  
        var checked = $(item).attr('checked');
        $(".projectlist input:checkbox").each(function() {
              var vs = this.id;
              if(checked == 'checked') {
                    this.checked = true;
                    $('#tr'+vs).addClass('info');
              } else {
                    this.checked = false;
                    $('#tr'+vs).removeClass('info');
              }
        });
    }

    $(".projectlist input:checkbox").click(function(){
        // alert(this.value);
          var vs = this.id;
          if(this.checked) {
                $('#tr'+vs).addClass('info');
          } else {
                $('#tr'+vs).removeClass('info');
          }            
    });

    function stat() {
        var ids = get_checked_ids('id');
        if(ids) {
            return window.open('/stat/project?ids='+ids);
        }
    }


    function massdelete() {
        var e = '';
        var count = 0;
        $(".projectlist input:checkbox").each(function() {
              if(this.checked) {
                    var vs = this.id;
                    if(vs) {
                        count++;
                        e += vs + ',';
                    }
              }
        });
        if(!count) return;
        if(window.confirm("确认删除这"+count+"个项目？")) {
            return X.get('/user/massdelete?ids='+e);
        }
    }

    function massdelete_callback(ids_str) {
        var ids = ids_str.split(',');
        // console.log(ids);
        var i = 0;
        var id = 0;
        for(i=0; i<ids.length;i++) {
            id = ids[i];
            if(id) {
                $("#tr"+id).hide('slow', function(){ $("#tr"+id).remove(); });
            }
        }
    }

    function export_users(type, status, type_id, infotype) {
        var ids = '';
        if(type == 'all') {
            if(!window.confirm("确认导出所有该状态的成员？")) {
                return;
            }
        } else {
            ids = get_checked_ids('id');
            if(!ids || !window.confirm("确认导出选中的成员？")) {
                return;
            }  
        }
        return window.open('/user/export_users?status='+status+'&ids='+ids+'&apply_type_id='+type_id+'&infotype='+infotype);
    }

    function get_checked_ids(from) {
        var ids = '';
        $(".projectlist input:checkbox").each(function() {
              if(this.checked) {
                if(from=='id') {
                    var vs = this.id;
                    if(vs) {
                        ids += vs + ',';
                    }
                } else {
                    var vs = this.value.split('::');
                    ids += vs[0] + ',';                    
                }
              }
        });

        if(!ids) return alert('至少要选中一条记录');
        return ids;
    }

    //点击重置按钮时，把URL参数都去掉
    function resetURL() {
        search = window.location.search;
        param = search.split('&');
        newURL = "http://" + window.location.host + window.location.pathname + param[0];
        alert(newURL);
        document.URL = newURL;
    }

</script>
          </div>
        </div><?php endif; ?>
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