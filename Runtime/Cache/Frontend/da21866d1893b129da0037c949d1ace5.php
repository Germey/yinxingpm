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
    

<div id="content" class="wide project-detail">

  <div class="pull-right">
    <a href="#attachments" class="btn btn-small btn-link"><?php echo (L("attachment")); ?> (<?php echo count($attachments);?>)</a>
    <a href="#notes" class="btn btn-small btn-link"><?php echo (L("note")); ?> (<?php echo count($notes);?>)</a>
    <a class="btn btn-small btn-link" target="_blank" href="<?php echo D('Options')->getOption('webroot_apply');?>/userinfo/preview?id=<?php echo ($user['id']); ?>&token=<?php echo Crypt::en($user['id']);?>">打印预览推荐表</a>
    <?php if($user['status'] > 50): ?><a class="btn btn-small btn-link" target="_blank" href="<?php echo D('Options')->getOption('webroot_apply');?>/userinfo/preview?id=<?php echo ($user['id']); ?>&token=<?php echo Crypt::en($user['id']);?>">打印预览申请表</a><?php endif; ?>

    <?php if(can_edit($login_user) AND $user['status'] < 50): ?><a href="/user/ajax_delete_user?id=<?php echo ($user['id']); ?>" class="btn btn-small btn-link ajaxlink" ask="确定要删除这个候选人么？" onclick="">删除候选人</a><?php endif; ?>
</div>
<input type="hidden" id="recommend_id" value="<?php echo ($user['id']); ?>"/>
<div>
    <span style="font-size:32px;">
      <?php echo ($user["name"]); if($user['identifier']): ?><span class="muted detail_iden">[<?php echo ($user["identifier"]); ?>]</span><?php endif; ?>
    </span> 
    <span style="padding-left:20px;">
      <a href="#" id="change_status" data-type="select" data-pk="<?php echo ($user['id']); ?>" data-url="/user/ajax_save_status" data-title="修改状态">
          <?php echo ($user['status_name']); ?>
      </a>
      <?php if($user['status']==100): ?><span class="muted">
          「评级：<a href="#" id="change_status_note" data-type="select" data-pk="<?php echo ($user['id']); ?>" data-url="/user/ajax_save_status_note" data-title="备注">
              <span class="muted"><?php echo ($user['status_note']?$user['status_note']:'评级'); ?></span>
          </a>」
        </span><?php endif; ?>
    </span>
</div>
<script type="text/javascript">
  $('#change_status').editable({
      source: <?php echo ($user_status_xedit_str); ?>
  });
  $('#change_status_note').editable({
    source: <?php echo createJSONRank(4);?>
  });
</script>

  <!-- 基本信息 -->
  <h5 class="clear bg colortip" title="点开展开/合并" style="cursor: pointer;" onclick="toggle_content_block('_summary_content');">
    1. 个人简历信息<i class="icon-fullscreen"></i>
  </h5>
  <div id="_summary_content" <?php echo ($user['pm_display_toggle']['_summary_content']?'style="display:none"':''); ?>>
    <ul class="list_display" style="margin-top:5px">
      <?php if(is_array($all_columns)): foreach($all_columns as $key=>$one): if(in_array($key, array('name')) || $one['hidden']) { continue; } if(in_array($key, array('address'))) { $userinfo[$key] = $userinfo[$key] . '（' . $userinfo[$key.'_zipcode'] . '）'; } ?>
        <?php if($one['type'] == 'group'): echo display_group($userinfo[$key], $one['options'], $one['display_name']);?>
        <?php else: ?>
          <?php  $show = 1; if($one['status_range']) { $status_range = explode(',',$one['status_range']); if($userinfo['status']<$status_range[0] || $userinfo['status']>$status_range[1]) { $show = 0; } } ?>
          <?php if($show): echo display_column_li($one['display_name'], nl2br($userinfo[$key]), $one['display_colspan']); endif; endif; endforeach; endif; ?>
    </ul>
  </div>


  <!-- 问答题 -->
  <?php
 $question_map = array(2=>'2. 申请理由陈述', 3=>'3. 个人成长计划'); ?>
  <?php if(is_array($question_map)): foreach($question_map as $key=>$display): $questions = D("UserQuestionAnswers")->getQuestionAnswers($userinfo['id'], $key); $block_key = '_detail_content_'.$key; ?>
    <h5 class="clear bg colortip" style="cursor: pointer;" onclick="toggle_content_block('<?php echo ($block_key); ?>');" title="点击展开/合并">
      <?php echo ($display); ?> <i id="open_icon<?php echo ($block_key); ?>" class="icon-fullscreen"></i>
    </h5>
    <div id="<?php echo ($block_key); ?>" style="<?php echo ($user['pm_display_toggle'][$block_key]?'display:none;padding-left:20px':'padding-left:20px'); ?>">
        <?php if(is_array($questions)): foreach($questions as $key=>$answer): ?><div style="margin-bottom: 20px">
      <p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
        <span class="label label-info">问</span>
        <?php echo ($key); ?>
      </p>
      <p>
        <span class="label label-important pull-left">答</span>
        <div style="padding-left: 25px;"><?php echo nl2br(trim($answer));?></div>
      </p>
    </div><?php endforeach; endif; ?>

    </div><?php endforeach; endif; ?>

  <h5 class="clear bg colortip" title="点开展开/合并" style="cursor: pointer;" onclick="toggle_content_block('_recommend_content');">
    4. 推荐人信息 - <?php echo ($user['recommender_name']); ?><i class="icon-fullscreen"></i>
  </h5>
  <div id="_recommend_content" <?php echo ($user['pm_display_toggle']['_recommend_content']?'style="display:none"':''); ?>>
    <div class="embed_recommend">
        <!-- 基本信息 -->
  <h5 class="clear bg colortip">1. 推荐人信息</h5>
  <div>
    <ul class="list_display" style="margin-top:5px">
      <?php if(is_array($recommend_columns)): foreach($recommend_columns as $key=>$one): if($key=='name') break; if($key=='recommender_address') $user[$key] = $user[$key] . '（'. $user['recommender_address_zipcode'] .'）'; if($key=='recommender_address_zipcode') continue; ?>
        <?php if($one['type'] == 'group'): echo display_group($user[$key], $one['options'], $one['display_name']);?>
        <?php else: ?>
          <?php echo display_column_li($one['display_name'], nl2br($user[$key]), $one['display_colspan']); endif; endforeach; endif; ?>
    </ul>
  </div>

  <h5 class="clear bg colortip">2. 推荐理由（被推荐人信息）</h5>
  <div>
    <ul class="list_display" style="margin-top:5px">
      <?php if(is_array($recommend_columns)): foreach($recommend_columns as $key=>$one): if(strpos($key,'recommender')===0) continue; ?>
        <?php echo display_column_li($one['display_name'], nl2br($user[$key]), $one['display_colspan']); endforeach; endif; ?>
    </ul>
    <div class="clear"></div>
    <?php
 $questions = D("UserQuestionAnswers")->getQuestionAnswers($user['id'], 1); ?>
    <div style="padding:0 30px">    
      <?php if(is_array($questions)): foreach($questions as $key=>$answer): ?><div style="margin-bottom: 20px" class="clear">
          <p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
            <span class="label label-info">问</span>
            <?php echo ($key); ?>
          </p>
          <p>
            <span class="label label-important pull-left">答</span>
            <div style="padding-left: 25px;"><?php echo nl2br(trim($answer));?></div>
          </p>
        </div><?php endforeach; endif; ?>
    </div>
  </div>
    </div>
  </div>


    <!-- 审核信息 -->
  <?php
 $display_status = D("UserStatuses")->getAuditStatusIdNameMap(); $max_audit_status = max(array_keys($display_status)); $tmps = Utility::AssColumn(D("UserAudits")->where('user_id=%d',$user['id'])->order('id desc')->select()); foreach($tmps as $k=>$v) { $audits[$v['status']][] = $v; } $auditing_status = 0; foreach ($display_status as $status => $name) { if($status>$user['status']) { unset($display_status[$status]); } if($status==$user['status']) { $auditing_status = $active_status = $status; } } if($user['status']>$max_audit_status) { foreach ($audits as $k => $v) { if(!$v || !$v[0]['audit_time']) { unset($display_status[$k]); } } $active_status = max(array_keys($display_status)); } if(in_array($user['status'], array(20,60))) { $audit_opinions[4] = '驳回修改'; } ?>
<div class="label label-warning alert-tip" id="alert-tip"><p>警告框</p></div>
<h5 class="clear bg colortip" title="点击展开/合并" style="cursor: pointer;" onclick="toggle_content_block('_audit_content');">
  # 考核过程 #<i class="icon-fullscreen"></i>
</h5>

<form method="post" action="/user/submit_audit" enctype="multipart/form-data" class="validator form-horizontal" style="padding:0 20px" onsubmit="return confirmit()">
  <div id="_audit_content" <?php echo ($user['pm_display_toggle']['_audit_content']?'style="display:none"':''); ?>>
    <ul id="audit_tab" class="nav nav-tabs">
      <?php if(is_array($display_status)): foreach($display_status as $key=>$one): ?><li class="<?php echo ($key==$active_status?'active common':'common'); ?>">
          <a href="#audit_<?php echo ($key); ?>" data-toggle="tab">
            <?php echo str_replace('待','',$one);?>&nbsp;&nbsp;<?php echo ($audits[$key]['audit_result']==1?'√':''); ?> <?php echo ($audits[$key]['audit_result']==2?'<span class="text-error">x</span>':''); ?>
          </a>
        </li><?php endforeach; endif; ?>
      <li class="pull-right"><a title="展开所有评审" onclick="expand_all_audit()"><i class="icon-chevron-down"></i></a></li>
    </ul>
    
    <div id="audit_tab_content" class="tab-content" style="padding: 0 10px;">
        <?php if(is_array($display_status)): foreach($display_status as $status=>$one): ?><div class="tab-pane fade <?php echo ($status==$active_status?'in active':''); ?>" id="audit_<?php echo ($status); ?>">
                <p class="alert alert-success audit_view_all_title"><?php echo str_replace('待','',$one);?></span></p>
                <?php if($status == 30): if ($status == 30) { $bg_survey_questions = D("UserTypeQuestions")->getsByTypeId(4); } else if ($status == 70) { $bg_survey_questions = D("UserTypeQuestions")->getsByTypeId(5); } $bg_survey_answers = D("BackgroundSurvey")->getAnswers($user['id']); $editable = auditEditable($login_user['role']); $survey_user = D("Users")->getById($user['bg_survey_user']); if($status == 30){ $status_name = "背景调查记录"; } else if ($status == 70) { $status_name = "考察访谈记录"; } ?>

<div class="print_content">
<div class="background_survey" id="background_survey_<?php echo ($status); ?>">
	<?php if(!$editable): if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="question">
				<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
		        <span class="label label-info">问</span>
		        <?php echo ($one['question']); ?>
		    	</p>
		    </div>
		    <div class="ans-item">
		    	<div class="answer" id="unedit_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></div>
		    	<div id="editable_<?php echo ($one['id']); ?>" style="display:none">
			    	<textarea class="answer editor" id="editor_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></textarea>
			    </div>
		    	<a target="<?php echo ($one['id']); ?>" class="edit">编辑</a>
		    </div><?php endforeach; endif; ?>
		<p><span>当前负责背景调查人员：</span>
			<a href="#" class="assert_bg_survey" data-type="select" data-pk="<?php echo ($user['id']); ?>" data-url="/user/ajax_assert_servey_user" data-title="指派背景调查人员">
	          <?php echo ($survey_user['username']); ?>
	      </a>
	  	</p>
	  	<input type="button" class="btn btn-danger" id="save_bg_survey_<?php echo ($status); ?>" value="保存">
	  	<input type="button" class="btn btn-primary" id="pirnt_bg_survey_<?php echo ($status); ?>" value="打印">
	<?php else: ?>
		<?php if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="question">
				<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
		        <span class="label label-info">问</span>
		        <?php echo ($one['question']); ?>
		    	</p>
		    </div>
		    <div class="ans-item">
		    	<div id="editable_<?php echo ($one['id']); ?>">
			    	<textarea class="answer editor" id="editor_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></textarea>
			    </div>
		    </div><?php endforeach; endif; ?>
		<input type="button" class="btn btn-danger" id="save_bg_survey_<?php echo ($status); ?>" value="保存"><br><br>
		<textarea id="email_msg_<?php echo ($status); ?>" placeholder="如果调查完毕，请写句话告诉管理员吧"><?php echo ($user['name']); ?>的背景调查已经填写完毕，请审核。</textarea>
		<input type="button" class="btn btn-primary" id="send_email_to_amdin_<?php echo ($status); ?>" value="发送邮件给项目负责人">
		<script>
			setInterval("save_all_<?php echo ($status); ?>()", 5000);
		</script><?php endif; ?>
</div>
</div>
<div id="survey_print_<?php echo ($status); ?>" style="display:none">
	<div class="container survey-print">
		<div class="survey-personal-info">
			<?php echo ($user["name"]); echo ($status_name); ?>表
		</div>
		<?php if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="qa-item" id="qa-item-<?php echo ($one['id']); ?>">
				<div class="question">
					<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
			        	<span class="label label-info">问</span>
			        	<?php echo ($one['question']); ?>
			    	</p>
			    </div>
			    <div class="answer">
			    	<p>
			        	<span class="label label-important pull-left">答</span>
			        </p>
			        <div style="padding-top: 25px;" class="answer-content"><?php echo ($bg_survey_answers[$one['id']]); ?></div>
			    </div>
			</div><?php endforeach; endif; ?>
	</div>
</div>

<script type="text/javascript">

$(function(){
	$(".edit").on("click", function() {
		$("#editable_"+$(this).attr("target")).css("display","block");
		$("#unedit_"+$(this).attr("target")).css("display","none");
		$(this).css("display","none");
	});
})

$("#pirnt_bg_survey_<?php echo ($status); ?>").on("click", function() {
	$("#save_bg_survey_<?php echo ($status); ?>").trigger("click");
	$.post("/user/ajax_get_bg_survey_answers",{
    	"id":<?php echo ($user[id]); ?>,
    },function(data) {
    	data = JSON.parse(data);
    	$.each(data, function(key, value) {
    		$("#qa-item-"+key+" .answer-content").html(value);
    	});
    	document.body.innerHTML = $("#survey_print_<?php echo ($status); ?>").html();
		setTimeout("window.print()",1000);
    });
	
});

function saveContent(question_id, editor_id){
	$.post("/user/ajax_save_bg_survey",{
    	"question_id":question_id,
    	"recommend_id":<?php echo ($user['id']); ?>,
    	"survey_id":<?php echo ($user['bg_survey_user']); ?>,
    	"answer":UE.getEditor(editor_id).getContent()
    },function(data) {
    	if(data>0) {
    		show_alert_tip("调查结果已保存");
    	}	
    })
}

  $('.assert_bg_survey').editable({ 
  	source: <?php echo getEditUsers();?>
  });

  $(".background_survey .answer").on("blur", function() {
    $.post("/user/ajax_save_bg_survey",{
    	"question_id":$(this).attr("question"),
    	"recommend_id":<?php echo ($user['id']); ?>,
    	"survey_id":<?php echo ($user['bg_survey_user']); ?>,
    	"answer":$(this).val()
    },function(data) {
    	if(data>0) {
    		show_alert_tip("调查结果已保存");
    	}	
    })
  });
  $("#send_email_to_amdin_<?php echo ($status); ?>").on("click", function() {
  	if (confirm("确定要发送邮件吗？")) {
  		$.post("/user/ajax_send_mail_to_admin",{
	    	"msg":$("#email_msg_<?php echo ($status); ?>").val(),
	    	"name":"<?php echo ($user['name']); ?>",
	    	"id":"<?php echo ($user['id']); ?>"
	    }, function(data) {
	    	if (data==1) {
	    		show_alert_tip("邮件已发送给管理员");
	    	}
	    });
  	}
  });
  $("#save_bg_survey_<?php echo ($status); ?>").on("click", function() {
  	<?php foreach($bg_survey_questions as $key => $val) { ?>
		saveContent(<?php echo ($val['id']); ?>,"editor_<?php echo ($val['id']); ?>");
	<?php } ?>
	$.post("/user/ajax_get_bg_survey_answers",{
    	"id":<?php echo ($user[id]); ?>,
    },function(data) {
    	data = JSON.parse(data);
    	$.each(data, function(key, value) {
    		$("#qa-item-"+key+" .answer-content").html(value);
    	})
    });
  });

function show_alert_tip(msg) {
    $("#alert-tip").html("<p>"+msg+"</p>").show();
    setTimeout("$('#alert-tip').slideUp('slow', function(){ $('#alert-tip').hide(); })", 5000);
}

function save_all_<?php echo ($status); ?>() {
	if($("#save_bg_survey_<?php echo ($status); ?>").is(":visible")) {
		$("#save_bg_survey_<?php echo ($status); ?>").trigger("click");
	}
}

<?php foreach($bg_survey_questions as $key => $val) { ?>
	//UE.getEditor("editor_<?php echo ($val['id']); ?>").setContent('<?php echo ($bg_survey_answers[$val["id"]]); ?>');
<?php } ?>

</script>
                <?php elseif($status == 40): ?>
                    <?php  $names = D("Users")->getField("id,username"); if($status == 40){ $status_name = "项目组初筛"; } else if ($status == 60) { $status_name = "秘书处二筛"; } else if ($status == 80) { $status_name = "秘书处终筛"; } ?>
<div class="send_to_audits">
	<div class="email_select" >
		<select class="chosen-select span2" multiple name="emails[]" data-placeholder="邮件列表"><?php echo Utility::Option($names,null);?></select>
	</div>
	<textarea class="msg" id="email_msg_<?php echo ($status); ?>" placeholder="请告诉大家您要说的话"><?php echo ($user['name']); ?>已经进入<?php echo ($status_name); ?>阶段，请大家共同讨论意见。</textarea>
	<input type="button" class="btn btn-primary" id="send_email_to_audits_<?php echo ($status); ?>" value="发送邮件给通知大家">
</div>
<script type="text/javascript">

$('#send_email_to_audits_<?php echo ($status); ?>').on("click", function() {
	var size = $(".email_select li.search-choice").size();
	var arr = new Array();
	$(".email_select li.search-choice span").each(function(){
		arr.push($(this).text());
	});
	if (size <= 0) {
		show_alert_tip("请至少选择一个人员");
	} else {
		if (confirm("确定要发送邮件吗？")) {
	  		$.post("/user/ajax_send_mail_to_audits",{
		    	"audits":arr,
		    	"msg":$("#email_msg_<?php echo ($status); ?>").val(),
		    	"name":"<?php echo ($user['name']); ?>",
		    	"id":"<?php echo ($user['id']); ?>",
		    }, function(data) {
		    	show_alert_tip(data);
		    });
	  	}
	}
});

</script>
                <?php elseif($status == 60): ?>
                    <?php  $names = D("Users")->getField("id,username"); if($status == 40){ $status_name = "项目组初筛"; } else if ($status == 60) { $status_name = "秘书处二筛"; } else if ($status == 80) { $status_name = "秘书处终筛"; } ?>
<div class="send_to_audits">
	<div class="email_select" >
		<select class="chosen-select span2" multiple name="emails[]" data-placeholder="邮件列表"><?php echo Utility::Option($names,null);?></select>
	</div>
	<textarea class="msg" id="email_msg_<?php echo ($status); ?>" placeholder="请告诉大家您要说的话"><?php echo ($user['name']); ?>已经进入<?php echo ($status_name); ?>阶段，请大家共同讨论意见。</textarea>
	<input type="button" class="btn btn-primary" id="send_email_to_audits_<?php echo ($status); ?>" value="发送邮件给通知大家">
</div>
<script type="text/javascript">

$('#send_email_to_audits_<?php echo ($status); ?>').on("click", function() {
	var size = $(".email_select li.search-choice").size();
	var arr = new Array();
	$(".email_select li.search-choice span").each(function(){
		arr.push($(this).text());
	});
	if (size <= 0) {
		show_alert_tip("请至少选择一个人员");
	} else {
		if (confirm("确定要发送邮件吗？")) {
	  		$.post("/user/ajax_send_mail_to_audits",{
		    	"audits":arr,
		    	"msg":$("#email_msg_<?php echo ($status); ?>").val(),
		    	"name":"<?php echo ($user['name']); ?>",
		    	"id":"<?php echo ($user['id']); ?>",
		    }, function(data) {
		    	show_alert_tip(data);
		    });
	  	}
	}
});

</script>
                <?php elseif($status == 70): ?>
                    <?php
 if ($status == 30) { $bg_survey_questions = D("UserTypeQuestions")->getsByTypeId(4); } else if ($status == 70) { $bg_survey_questions = D("UserTypeQuestions")->getsByTypeId(5); } $bg_survey_answers = D("BackgroundSurvey")->getAnswers($user['id']); $editable = auditEditable($login_user['role']); $survey_user = D("Users")->getById($user['bg_survey_user']); if($status == 30){ $status_name = "背景调查记录"; } else if ($status == 70) { $status_name = "考察访谈记录"; } ?>

<div class="print_content">
<div class="background_survey" id="background_survey_<?php echo ($status); ?>">
	<?php if(!$editable): if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="question">
				<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
		        <span class="label label-info">问</span>
		        <?php echo ($one['question']); ?>
		    	</p>
		    </div>
		    <div class="ans-item">
		    	<div class="answer" id="unedit_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></div>
		    	<div id="editable_<?php echo ($one['id']); ?>" style="display:none">
			    	<textarea class="answer editor" id="editor_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></textarea>
			    </div>
		    	<a target="<?php echo ($one['id']); ?>" class="edit">编辑</a>
		    </div><?php endforeach; endif; ?>
		<p><span>当前负责背景调查人员：</span>
			<a href="#" class="assert_bg_survey" data-type="select" data-pk="<?php echo ($user['id']); ?>" data-url="/user/ajax_assert_servey_user" data-title="指派背景调查人员">
	          <?php echo ($survey_user['username']); ?>
	      </a>
	  	</p>
	  	<input type="button" class="btn btn-danger" id="save_bg_survey_<?php echo ($status); ?>" value="保存">
	  	<input type="button" class="btn btn-primary" id="pirnt_bg_survey_<?php echo ($status); ?>" value="打印">
	<?php else: ?>
		<?php if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="question">
				<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
		        <span class="label label-info">问</span>
		        <?php echo ($one['question']); ?>
		    	</p>
		    </div>
		    <div class="ans-item">
		    	<div id="editable_<?php echo ($one['id']); ?>">
			    	<textarea class="answer editor" id="editor_<?php echo ($one['id']); ?>" question="<?php echo ($one['id']); ?>"row="3" col="10"><?php echo ($bg_survey_answers[$one['id']]); ?></textarea>
			    </div>
		    </div><?php endforeach; endif; ?>
		<input type="button" class="btn btn-danger" id="save_bg_survey_<?php echo ($status); ?>" value="保存"><br><br>
		<textarea id="email_msg_<?php echo ($status); ?>" placeholder="如果调查完毕，请写句话告诉管理员吧"><?php echo ($user['name']); ?>的背景调查已经填写完毕，请审核。</textarea>
		<input type="button" class="btn btn-primary" id="send_email_to_amdin_<?php echo ($status); ?>" value="发送邮件给项目负责人">
		<script>
			setInterval("save_all_<?php echo ($status); ?>()", 5000);
		</script><?php endif; ?>
</div>
</div>
<div id="survey_print_<?php echo ($status); ?>" style="display:none">
	<div class="container survey-print">
		<div class="survey-personal-info">
			<?php echo ($user["name"]); echo ($status_name); ?>表
		</div>
		<?php if(is_array($bg_survey_questions)): foreach($bg_survey_questions as $key=>$one): ?><div class="qa-item" id="qa-item-<?php echo ($one['id']); ?>">
				<div class="question">
					<p style="background: #f4f4f4;padding:4px 0;font-weight: bold;">
			        	<span class="label label-info">问</span>
			        	<?php echo ($one['question']); ?>
			    	</p>
			    </div>
			    <div class="answer">
			    	<p>
			        	<span class="label label-important pull-left">答</span>
			        </p>
			        <div style="padding-top: 25px;" class="answer-content"><?php echo ($bg_survey_answers[$one['id']]); ?></div>
			    </div>
			</div><?php endforeach; endif; ?>
	</div>
</div>

<script type="text/javascript">

$(function(){
	$(".edit").on("click", function() {
		$("#editable_"+$(this).attr("target")).css("display","block");
		$("#unedit_"+$(this).attr("target")).css("display","none");
		$(this).css("display","none");
	});
})

$("#pirnt_bg_survey_<?php echo ($status); ?>").on("click", function() {
	$("#save_bg_survey_<?php echo ($status); ?>").trigger("click");
	$.post("/user/ajax_get_bg_survey_answers",{
    	"id":<?php echo ($user[id]); ?>,
    },function(data) {
    	data = JSON.parse(data);
    	$.each(data, function(key, value) {
    		$("#qa-item-"+key+" .answer-content").html(value);
    	});
    	document.body.innerHTML = $("#survey_print_<?php echo ($status); ?>").html();
		setTimeout("window.print()",1000);
    });
	
});

function saveContent(question_id, editor_id){
	$.post("/user/ajax_save_bg_survey",{
    	"question_id":question_id,
    	"recommend_id":<?php echo ($user['id']); ?>,
    	"survey_id":<?php echo ($user['bg_survey_user']); ?>,
    	"answer":UE.getEditor(editor_id).getContent()
    },function(data) {
    	if(data>0) {
    		show_alert_tip("调查结果已保存");
    	}	
    })
}

  $('.assert_bg_survey').editable({ 
  	source: <?php echo getEditUsers();?>
  });

  $(".background_survey .answer").on("blur", function() {
    $.post("/user/ajax_save_bg_survey",{
    	"question_id":$(this).attr("question"),
    	"recommend_id":<?php echo ($user['id']); ?>,
    	"survey_id":<?php echo ($user['bg_survey_user']); ?>,
    	"answer":$(this).val()
    },function(data) {
    	if(data>0) {
    		show_alert_tip("调查结果已保存");
    	}	
    })
  });
  $("#send_email_to_amdin_<?php echo ($status); ?>").on("click", function() {
  	if (confirm("确定要发送邮件吗？")) {
  		$.post("/user/ajax_send_mail_to_admin",{
	    	"msg":$("#email_msg_<?php echo ($status); ?>").val(),
	    	"name":"<?php echo ($user['name']); ?>",
	    	"id":"<?php echo ($user['id']); ?>"
	    }, function(data) {
	    	if (data==1) {
	    		show_alert_tip("邮件已发送给管理员");
	    	}
	    });
  	}
  });
  $("#save_bg_survey_<?php echo ($status); ?>").on("click", function() {
  	<?php foreach($bg_survey_questions as $key => $val) { ?>
		saveContent(<?php echo ($val['id']); ?>,"editor_<?php echo ($val['id']); ?>");
	<?php } ?>
	$.post("/user/ajax_get_bg_survey_answers",{
    	"id":<?php echo ($user[id]); ?>,
    },function(data) {
    	data = JSON.parse(data);
    	$.each(data, function(key, value) {
    		$("#qa-item-"+key+" .answer-content").html(value);
    	})
    });
  });

function show_alert_tip(msg) {
    $("#alert-tip").html("<p>"+msg+"</p>").show();
    setTimeout("$('#alert-tip').slideUp('slow', function(){ $('#alert-tip').hide(); })", 5000);
}

function save_all_<?php echo ($status); ?>() {
	if($("#save_bg_survey_<?php echo ($status); ?>").is(":visible")) {
		$("#save_bg_survey_<?php echo ($status); ?>").trigger("click");
	}
}

<?php foreach($bg_survey_questions as $key => $val) { ?>
	//UE.getEditor("editor_<?php echo ($val['id']); ?>").setContent('<?php echo ($bg_survey_answers[$val["id"]]); ?>');
<?php } ?>

</script>
                <?php elseif($status == 80): ?>
                    <?php  $names = D("Users")->getField("id,username"); if($status == 40){ $status_name = "项目组初筛"; } else if ($status == 60) { $status_name = "秘书处二筛"; } else if ($status == 80) { $status_name = "秘书处终筛"; } ?>
<div class="send_to_audits">
	<div class="email_select" >
		<select class="chosen-select span2" multiple name="emails[]" data-placeholder="邮件列表"><?php echo Utility::Option($names,null);?></select>
	</div>
	<textarea class="msg" id="email_msg_<?php echo ($status); ?>" placeholder="请告诉大家您要说的话"><?php echo ($user['name']); ?>已经进入<?php echo ($status_name); ?>阶段，请大家共同讨论意见。</textarea>
	<input type="button" class="btn btn-primary" id="send_email_to_audits_<?php echo ($status); ?>" value="发送邮件给通知大家">
</div>
<script type="text/javascript">

$('#send_email_to_audits_<?php echo ($status); ?>').on("click", function() {
	var size = $(".email_select li.search-choice").size();
	var arr = new Array();
	$(".email_select li.search-choice span").each(function(){
		arr.push($(this).text());
	});
	if (size <= 0) {
		show_alert_tip("请至少选择一个人员");
	} else {
		if (confirm("确定要发送邮件吗？")) {
	  		$.post("/user/ajax_send_mail_to_audits",{
		    	"audits":arr,
		    	"msg":$("#email_msg_<?php echo ($status); ?>").val(),
		    	"name":"<?php echo ($user['name']); ?>",
		    	"id":"<?php echo ($user['id']); ?>",
		    }, function(data) {
		    	show_alert_tip(data);
		    });
	  	}
	}
});

</script><?php endif; ?>
                <?php if(is_array($audits[$status])): foreach($audits[$status] as $key=>$one): ?><div id="audit_list_<?php echo ($one['id']); ?>">            
                        <div style="margin: 10px 0 5px 0;">
                            <i class="icon-comment"></i>&nbsp;&nbsp;
                            <span class=""><b><?php echo ($one['audit_user_name_display']); ?>@<?php echo substr($one['audit_time'],0,16);?></b></span>&nbsp;&nbsp;
                            <span class="label label-<?php echo ($all_audit_opinions[$one['audit_result']]['label-class']); ?>"><?php echo ($all_audit_opinions[$one['audit_result']]['name']); ?></span>
                            <?php if($one['audit_user_id'] == $login_user['id']): ?><a href="/user/ajax_delete_audit?id=<?php echo ($one['id']); ?>" ask="确认删除该评审记录？" class="muted ajaxlink pull-right">x</a><?php endif; ?>
                        </div>
                        <blockquote>
                            <?php echo nl2br($one['audit_content']);?>
                            <?php if($one['audit_email']): ?><hr />
                            <p><i class="icon-envelope"></i> <?php echo ($one['audit_email']['to']); ?> 【邮件题目：<?php echo ($one['audit_email']['subject']); ?>】</p><?php endif; ?>
                        </blockquote>
                    </div><?php endforeach; endif; ?>

                <?php if($auditing_status == $status): ?><input type="hidden" name="user_id" value="<?php echo ($user['id']); ?>" />
                    <!--             <input type="hidden" name="id" value="<?php echo ($audits[$status]['id']); ?>" /> -->
                    <input type="hidden" name="status" value="<?php echo ($auditing_status); ?>" />
                    <input type="hidden" name="create_user_id" value="<?php echo ($audits[$status]['create_user_id']?$audits[$status]['create_user_id']:$login_user['id']); ?>" />
                    <input type="hidden" name="create_time" value="<?php echo ($audits[$status]['create_time']?$audits[$status]['create_time']:date('Y-m-d H:i:s')); ?>" />
                    <input type="hidden" name="audit_user_id" value="<?php echo ($audits[$status]['audit_user_id']?$audits[$status]['audit_user_id']:$login_user['id']); ?>" />
                    <input type="hidden" name="audit_user_name" value="<?php echo ($audits[$status]['audit_user_name']?$audits[$status]['audit_user_name']:$login_user['realname']); ?>" />

                    <?php if($audits[$status]['audit_user_id'] AND ($audits[$status]['audit_user_id'] != $login_user['user_id'])): ?><input type="hidden" name="actual_audit_user_id" value="<?php echo ($login_user['id']); ?>" />
                      <input type="hidden" name="actual_audit_user_name" value="<?php echo ($login_user['realname']); ?>" /><?php endif; ?>

                    <table class="table table-noborder">
                        <tr>
                            <td style="vertical-align: top"><b>考核意见</b></td>
                            <td><textarea name="audit_content" id="audit_content" class="span12" style="height: 100px"><?php echo ($audits[$status]['audit_content']); ?></textarea></td>
                        </tr>
                        <tr>
                            <td><b>通过否？</b></td>
                            <td>
                              <select name="audit_result"><?php echo Utility::Option($audit_opinions, $audits[$status]['audit_result']);?></select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>考核人</b></td>
                            <td><?php echo ($audits[$status]['audit_user_name']?$audits[$status]['audit_user_name']:$login_user['realname']); ?></td>
                        </tr>
                        <?php if($audits[$status]['audit_user_id'] AND ($audits[$status]['audit_user_id'] != $login_user['id'])): ?><tr>
                                <td><b>实际考核人</b></td><td><?php echo ($login_user['realname']); ?></td>
                            </tr><?php endif; ?>
                        <tr>
                            <td></td>
                            <td>
                              <input type="submit" class="btn btn-danger" value="提 交" />
                              <span class="muted"><?php echo M("UserStatuses")->where("id=%d",$user['status'])->getField('audit_tip');?></span>
                            </td>
                        </tr>
                    </table><?php endif; ?>
            </div><?php endforeach; endif; ?>
    </div>
  </div>
</form>

<script type="text/javascript">
  function expand_all_audit() {
    $(".audit_view_all_title").show();
    $("#audit_tab_content .tab-pane").addClass('in active');
  }

  $(document).ready(function() {
    $("#audit_tab li.common").click(function(){
      $(".audit_view_all_title").hide();
    });
  });

  function confirmit() {
    if(window.confirm('确认提交么？')) {
        return true;
    }
    return false;
  }

  function delete_audit_callback(id) {
    $("#audit_list_"+id).remove();
  }
</script>


  <?php  $module_name = 'user'; $module_object_id = $user['id']; $attachment_type = "user"; $attachment_object_id = $user['id']; ?>

  <!-- Notes -->
  <a name="notes"></a><div class="clear"></div>
<input type="hidden" id="module_name" value="<?php echo ($module_name); ?>">
<input type="hidden" id="object_id" value="<?php echo ($module_object_id); ?>">
<input type="hidden" id="login_user_name" value="<?php echo ($login_user['realname']); ?>">

<h5 class="clear bg">
  <?php if($note_title): echo ($note_title); ?>
  <?php else: ?>
    <?php echo (L("note")); endif; ?>
</h5>
<div style="padding-left:20px;">
  <?php if(can_edit($login_user)): ?><div>
      <input onclick="$('#note_more').show(); $('#note_content').show()" id="note_title" type="text" class="span6" placeholder="<?php echo (L("add_note_title")); ?>"/>
      <a id="note_more_tip" style="display:none" class="btn btn-link btn-small" href="javascript:void(0)" onclick="$('#note_content').toggle()"><?php echo (L("more_content")); ?></a>
    </div>
    <p id="note_more" style="display:none">
      <textarea id="note_content" class="span8" style="height:65px;display:none;" placeholder="<?php echo (L("more_note_content")); ?>"></textarea>
      <a href="javascript:void(0)"  onclick="save_note()" class="btn btn-small btn-danger"><?php echo (L("save")); ?></a>
      <a href="javascript:void(0)"  onclick="cancel_note()" class="btn btn-link btn-small"><?php echo (L("cancel")); ?></a>
    </p><?php endif; ?>
  <div id="notes_block" style="max-height: 800px; overflow-y:scroll">
    <?php if(is_array($notes)): foreach($notes as $key=>$one): ?><blockquote id="noteblock<?php echo ($one['id']); ?>">
        <p><strong><?php echo ($one['title']); ?></strong></p>
        <p><?php echo nl2br($one['content']);?></p>
        <small class="muted">
          <i><?php echo ($one['update_user']['realname']); ?> &nbsp; <?php echo ($one['update_time']); ?></i>
          <?php if(($one['create_user_id']==$login_user['id']) OR ( $login_user['role']=='admin' )): ?>&nbsp;<a href="javascript:void(0)" onclick="delete_note(<?php echo ($one['id']); ?>)" class="delete" ><?php echo (L("delete")); ?></a><?php endif; ?>
        </small>
      </blockquote><?php endforeach; endif; ?>
  </div>
</div>


<script type="text/javascript">
  function save_note() {
    var title = $.trim($("#note_title").val());
    if(!title) return $("#note_title").focus();

    var content = $.trim($("#note_content").val());

    X.get('/note/ajax_save?module='+ $("#module_name").val() +'&object_id='+parseInt($("#object_id").val()) + '&title=' + encodeURIComponent(title) + '&content=' + encodeURIComponent(content));
  }

  function save_note_callback(id) {
    var id = parseInt(id);
    if(!id) {
      return alert('Failed!');
    }

    var str = '<blockquote id="noteblock' + id + '">';
    str += '<p><strong>'+ $.trim($("#note_title").val()) +'</strong></p>';
    str += '<p>'+ $.trim($("#note_content").val()) +'</p>';
    str += '<small class="muted">';
    str += '<i>'+ $('#login_user_name').val() +'</i>';
    str += '&nbsp;<a href="javascript:void(0)" onclick="delete_note('+ id +')" class="delete">删除</a>';
    str += '</small></blockquote>';

    $('#notes_block').prepend(str);
    cancel_note();
  }

  function cancel_note() {
    $('#note_more').hide(); 
    $('#note_more_tip').hide(); 
    $('#note_title').attr('value','');
    $('#note_content').attr('value','');
  }

  function delete_note(id) {
    if(confirm('确定删除该备注？')) {
      X.get('/note/ajax_delete?module='+ $("#module_name").val() +'&id='+parseInt(id)+'&object_id='+parseInt($("#object_id").val()));
    }
  }

  function delete_note_callback(id) {
    $("#noteblock"+id).hide('slow', function(){ $("#noteblock"+id).remove(); });
  }
</script>

  <!-- attachments -->
  <a name="attachments"></a>
  <div class="clear"></div>

<script type="text/javascript" src="/styles/js/jquery.uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript" src="/styles/js/jquery.uploadify/swfobject.js"></script>
<link rel="stylesheet" href="/styles/js/jquery.uploadify/uploadify.css" type="text/css" media="screen" charset="utf-8" />

<input type="hidden" id="xxxs" value="<?php echo ($module_name); ?>">
<input type="hidden" id="object_id" value="<?php echo ($module_object_id); ?>">
<input type="hidden" id="module_name" value="<?php echo ($module_name); ?>">
<input type="hidden" id="login_user_name" value="<?php echo ($login_user['realname']); ?>">

<h5 class="clear bg"><?php echo (L("attachment")); ?><a href='javascript(0);' name='attachments'></a></h5>
<div class="attachment">
  <div>
      <input type="file" id="upload-image"/>
      <span class="muted">选择文件后可以在文件后面的输入框中输入备注，确认文件无误后点击“<b>确认保存</b>”按钮即可保存附件</span>
      
      <?php if(can_edit($login_user)): ?><div class="edit_dir"><a class="btn btn-small btn-primary ajaxlink" href="/attachment/render_grant_record?id=<?php echo ($attachment_object_id); ?>&type=<?php echo ($attachment_type); ?>"><i class="icon-edit icon-white" ></i>编辑目录</a></a></div><?php endif; ?>
      <p id="custom-queue"></p>
      <p class="clear" id="save_attachment_btn" style="display:none"><a href="javascript:void(0)" onclick="save_attachment()" class="btn btn-small btn-danger">确认保存</a></p>
  </div>
  <table id='show_attachments' class="table table-striped table-hover">
    <tr id="attachment_list">
      <th class='attachment_first'><?php echo (L("filename")); ?></th>
      <th><?php echo (L("size")); ?></th>
      <th><?php echo (L("create_time")); ?></th>
      <th><?php echo (L("note")); ?></th>
      <th><?php echo (L("action")); ?></th>
    </tr>
    <?php if(is_array($nodir)): foreach($nodir as $key=>$one): ?><tr>
            <td><?php echo ($one['title']); ?></td>
            <td><?php echo formatBytes($one['size']);?></td>
            <td><?php echo ($one['create_time']); ?></td>
            <td><?php echo ($one['content']); ?></td>
            <td nowrap>
              <small>
                <a href="/attachment/download?id=<?php echo ($one['id']); ?>&module=<?php echo ($module_name); ?>"><?php echo (L("download")); ?></a>
                <?php if(file_enable_preview($one['path'])): ?>&nbsp;
                  <a href="/uploads<?php echo ($one['path']); ?>" target="_blank" title="可以预览pdf或者图片文件"><?php echo (L("preview")); ?></a><?php endif; ?>
                <?php if(can_edit($login_user)): ?>&nbsp;<a href='javascript:void(0)' onclick="delete_attachment('<?php echo ($one['id']); ?>',this)"><?php echo (L("delete")); ?></a><?php endif; ?>
                 <b class='<?php echo ($one["id"]); ?>'>
                    <a href='javascript:void(0);' class="click_change_dir">更换目录</a>
                    <div class='change_dir'>
                        <select id='dirname' name='dirname'>
                            <?php if(is_array($dir_data)): foreach($dir_data as $key=>$one): ?><option value='<?php echo ($one["id"]); ?>'><?php echo ($one["dir_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <a herf='javascript:void(0);' class='btn btn-danger btn-small update_dir' style='text-indent: 0px;'>确定</a>
                    </div>
                 </b>
              </small>
            </td>
        </tr><?php endforeach; endif; ?>
    <?php if(is_array($diretories)): foreach($diretories as $keys=>$value): ?><tr class='dir<?php echo ($value["id"]); ?>'>
          <!-- <td class='dir_header' colspan='6'><?php if($value["child"]): ?><i class='icon-folder-close'></i><b><?php echo ($value['dirname']); else: ?><b style='padding-left:17px;'><?php echo ($value['dirname']); ?></b><?php endif; ?></b></td> -->

          <td class='dir_header' colspan='6'><i class='icon-folder-close'></i><b><?php echo ($value['dirname']); ?>&nbsp;(<?php echo count($value["child"]);?>)</b></td>
       </tr>
       <?php if(is_array($value["child"])): foreach($value["child"] as $key=>$v): ?><tr class='dir<?php echo ($value["id"]); ?>' style='display:none;'>
              <td><?php echo ($v['title']); ?></td>
              <td nowrap><?php echo formatBytes($v['size']);?></td>
              <td nowrap><?php echo substr($v['create_time'],0,16);?></td>
              <td><?php echo ($v['content']); ?></td>
              <td nowrap>
                <small>
                  <a href="/attachment/download?id=<?php echo ($v['id']); ?>&module=<?php echo ($module_name); ?>"><?php echo (L("download")); ?></a>
                  <?php if(file_enable_preview($v['path'])): ?>&nbsp;
                    <a href="/uploads<?php echo ($v['path']); ?>" target="_blank" title="可以预览pdf或者图片文件"><?php echo (L("preview")); ?></a><?php endif; ?>
                  <?php if(can_edit($login_user)): ?>&nbsp;<a href='javascript:void(0)' onclick="delete_attachment('<?php echo ($v['id']); ?>',this)"><?php echo (L("delete")); ?></a><?php endif; ?>
                  <b class='<?php echo ($v["id"]); ?>'>
                    &nbsp;<a href='javascript:void(0);' class="click_change_dir">换目录</a>
                    <div class='change_dir'>
                        <select id='dirname' name='dirname'>
                            <?php if(is_array($dir_data)): foreach($dir_data as $key=>$one): if($value["id"] != $one["id"]): ?><option value='<?php echo ($one["id"]); ?>'><?php echo ($one["dir_name"]); ?></option><?php endif; endforeach; endif; ?>
                        </select>
                        <a herf='javascript:void(0);' class='btn btn-danger btn-small update_dir' style='text-indent: 0px;'>确定</a>
                    </div>
                 </b>                
                </small>
              </td>
          </tr><?php endforeach; endif; endforeach; endif; ?>

  </table>
</div>

<script type="text/javascript">

  var attachments = new Map();

// upload-btn
  $(document).ready(function() {
      $('#upload-image').uploadify({
          'uploader' : '/styles/js/jquery.uploadify/uploadify.swf',
          'buttonImg': '/styles/js/jquery.uploadify/select.png',
          'script' : '/styles/js/jquery.uploadify/uploadify.php',
          'cancelImg' : '/styles/js/jquery.uploadify/cancel.png',
          'width': 170,
          'folder' : '/attachment',
          'auto' : true,
          'queueID' : 'custom-queue',
          'multi' : true,
          'simUploadLimit' : 1,
          'removeCompleted' : false,
          'onComplete' : function(event, ID, fileObj, response, data) {
              var file = new Array(response, fileObj.name, fileObj.size);
              //check if same file size
              attachments.put(ID, file);
              var str = '<textarea class="attachment-note" id="note'+ID+'" placeholder="附件备注"></textarea><select name="dir_name" id="dir_name'+ID+'"><option value="">无</option><?php if(is_array($diretories)): foreach($diretories as $keys=>$value): ?><option value="'+"<?php echo ($value['id']); ?>"+'">'+"<?php echo ($value['dirname']); ?>"+'</option><?php endforeach; endif; ?></select>';
              var filename = file[file.length-2];
              var textId = 'note'+ID;
              var type='__ACTION__'.split('/')[1].toLowerCase();
              $("#upload-image"+ID).after(str);
              $("#save_attachment_btn").show();
              X.post('/attachment/check_attachment_exist?'+'id='+<?php echo ($_GET['id']); ?>+'&title='+filename+'&type='+type+'&textId='+textId);
          },
          'onCancel' : function(event, ID, fileObj, data) {
              attachments.remove(ID);
              $("#note"+ID).remove();
              $("#dir_name"+ID).remove();

          }
      });
  });
function dir_duplicate_alert_callback(title,textId){
  $('#'+textId).prev().find('.percentage').html(' - Completed <span style="color:red">　　　　(文件已存在)</span>');
}

  function save_attachment() {
    
    if(!attachments.size()) return;
    var vs = attachments.values();
    var keys = attachments.keys();
    var path, filename, size, note, dirname;
    var single;
    for(var i=0; i<vs.length; i++) {
      single = vs[i];
      path = single[0];
      filename = single[1];
      size = single[2];
      note = $('#note'+ keys[i]).val();
      dirname = $('#dir_name' + keys[i]).val();
      X.get('/attachment/ajax_save?module='+ $("#module_name").val() +'&object_id='+parseInt($("#object_id").val()) 
            + '&path=' + encodeURIComponent(path) + '&title=' + encodeURIComponent(filename)
            + '&size=' + encodeURIComponent(size) + '&content=' + encodeURIComponent(note) + '&mapid=' + keys[i] + '&dir_id='+encodeURIComponent(dirname), true);
    }
  }

/************************每个附件上传成功后的回调函数**********************************/

  function save_attatchment_callback(id, mapid, dir_id) {
    // alert(dir_id);
    var item = attachments.get(mapid);
    var html = '<tr class="dir'+dir_id+'" id="attachment'+ id +'">';
    html += '<td style="background:#DFF0D8; text-indent:20px;">'+ item[1] + '</td>';
    html += '<td style="background:#DFF0D8; text-indent:20px;">'+ item[2] + 'B</td>';
    html += '<td style="background:#DFF0D8; text-indent:20px;">'+ '刚刚' + '</td>';
    html += '<td style="background:#DFF0D8; text-indent:20px;">'+ $('#note'+ mapid).val() + '</td>';
    html += '<td style="background:#DFF0D8; text-indent:20px;"><small>'+ '<a href="/attachment/download?id=' + id +'&module='+ $("#module_name").val() +'"><?php echo (L("download")); ?></a>';
    html += '&nbsp;<a href="javascript:void(0)" onclick="delete_attachment('+id+')"><?php echo (L("delete")); ?></a></small></td>';
    html += '<td style="background:#DFF0D8; text-indent:20px;"></td>';
    html += '</tr>';
    for(var i=1,l=aT.length;i<l;i++){
      
        aTr=oShow_atta.getElementsByTagName('tr');
        for(var a=1;a<aTr.length;a++){
            aTr[a].style.display='table-row';
        }
        if(aT[i].children[0].className != '' && aT[i].children[0].children[0].tagName == 'I'){
              aT[i].children[0].children[0].className='icon-folder-open';

        }
        aT[i].click=!aT[i].click;
    }

    if(dir_id){
      var last_tr = $("#show_attachments").find(".dir" + dir_id + "");
      var last_tr = last_tr.get(last_tr.length -1);
      $(last_tr).after(html);
    }else{
      $("#attachment_list").after(html);
    }
    //取消掉图片框
    $('#upload-image').uploadifyCancel(mapid);

  }

  function delete_attachment(id,This) {
    if(confirm('确定删除该附件？')) {
      $("#attachment"+id).addClass('error');
      X.get('/attachment/ajax_delete?module='+ $("#module_name").val() +'&id='+parseInt(id)+'&object_id='+parseInt($("#object_id").val()));
      var oShow_atta=document.getElementById('show_attachments');
      oShow_atta.getElementsByTagName('tbody')[0].removeChild(This.parentNode.parentNode.parentNode);
    }
  }

  function delete_attachment_callback(id) {
    $("#attachment"+id).hide('slow', function(){
      $("#attachment"+id).remove();
    });
  }
/*******************切换目录开始*****************************/

$('#show_attachments .click_change_dir').each(function(index,element){
    $(element)[0].t=true;
    $(element).click(function(){
      $('#show_attachments .change_dir').hide();
      if($(element)[0].t){
        for(var i=0;i<$('#show_attachments .click_change_dir').length;i++){
          $('#show_attachments .click_change_dir')[i].t=true;
        }
        $(element).parent().find(".change_dir").show();
        $(element)[0].t=false;
      }else{
        $(element).parent().find(".change_dir").hide();
        $(element)[0].t=true;
      }
  })
});

/******************切换目录结束*******************************/


/************************目录打开与闭合*******************************/

    var oShow_atta=document.getElementById('show_attachments');
    var aT=aTr=oShow_atta.getElementsByTagName('tr');
    for(var i=1,l=aTr.length;i<l;i++){
      aTr[i].onclick=function(){
        aTr=oShow_atta.getElementsByTagName('tr');
        for(var a=1;a<aTr.length;a++){
          var T=this.children[0].children[0];
          if(aTr[a].className == this.className && T != undefined && T.className == 'icon-folder-open' && aTr[a] != this){
            aTr[a].style.display='none';
          }else if(aTr[a].className == this.className && T != undefined && T.className == 'icon-folder-close'){
            aTr[a].style.display='table-row';
          }
        }
        if(this.children[0].className != '' && T != undefined && T.className == 'icon-folder-open' && T.tagName == 'I'){
              this.children[0].children[0].className='icon-folder-close';
        }else if(T != undefined && T.tagName == 'I'){
              this.children[0].children[0].className='icon-folder-open';
        }
      }
    }

/*********************已上传的附件更换目录***********************/
function window_refresh(){
    window.location.reload();
} 
var type='__ACTION__'.split('/')[1].toLowerCase();
var url_update_dir='<?php echo U("attachment/ajax_update_dir");?>'
var aSelect=oShow_atta.getElementsByTagName('select');
var aBottom=getClass(oShow_atta,'update_dir');
var pid=parseInt((window.location+'').split('/').pop());
for(var i=0;i<aBottom.length;i++){
  aBottom[i].index=i;
  aBottom[i].onclick=function(){
    var id=$(this).parent().parent().attr('class');
    var iData='?dir_id='+aSelect[this.index].value+'&object_id='+pid+'&type='+type+'&id='+id;
    X.post(url_update_dir+iData);
  }
}
/*********************************目录创建与删除*****************************************/
    var url_delete_dir="<?php echo U('attachment/ajax_delete_dir');?>";
    var url_save_dir="<?php echo U('attachment/save_dir');?>"
    function getClass(oParent,sClass){
        if(document.getElementsByClassName){
            return oParent.getElementsByClassName(sClass);
        }
        var oElement=oParent.getElementsByTagName('*');
        var aArr=[];
        var re=RegExp('\\b'+sClass+'\\b');
        for(var i=0;i<oElement.length;i++)
        {
            if(re.test(oElement[i].className))
            {
                aArr.push(oElement[i]);
            }
        }
        return aArr;
    };

    function lstChild(obj){
        var last=null;
        for(var i=obj.childNodes.length-1;i>0;i--)
        {
            if(obj.childNodes[i].nodeType==1)
            {
                return obj.childNodes[i];
            }
        }
    };

    function attachment_delete_dir(oBody){
        var oA=oBody.getElementsByTagName('a');
        var oInput=oBody.getElementsByTagName('input');
        var sInputValue='';
        for(var i=0,l=oInput.length;i<l;i++){
            oInput[i].onfocus=function(){
                sInputValue=this.value;
                this.value='';
            }
            oInput[i].onblur=function(){
                var pid=parseInt((window.location+'').split('/').pop());
                var This=this.parentNode.parentNode;
                var sValue=this.value;
                var _this=this;
                if(this.value == ''){
                    this.value=sInputValue;
                }
                if(this.value != sInputValue){
                    var dir_name=this.value;
                    var type='__ACTION__'.split('/')[1].toLowerCase();
                    var id = $(this).parent().parent().attr('class');
                    var data='?dir_name='+dir_name+'&object_id='+pid+'&type='+type+'&id='+id;
                    X.post(url_save_dir+data);
                }
            }
        }
        for(var i=0,l=oA.length;i<l;i++){
            oA[i].onclick=function(){
                var T=this.parentNode.parentNode;
                if(parseInt(T.children[1].innerHTML) != 0){
                    alert('该目录下有文件，请先删除文件后再操作');
                    return false;
                }
                oBody.removeChild(T);
                X.post(url_delete_dir+'?id='+$(this).parent().parent().attr('class'));
            }
        }
    }

    function attachment_initialize(){
        var oContent=document.getElementById('ajax-dialog');
        var oAdd=document.getElementById('add_att_dir');
        var oTable=getClass(oContent,'edit_att_dir')[0];
        var oBody=oTable.getElementsByTagName('tbody')[0];
        attachment_delete_dir(oBody);
        oAdd.onclick=function(){
            oBody=oTable.getElementsByTagName('tbody')[0];
            iIndex=oBody.children.length>1?parseInt(lstChild(oBody).children[0].innerHTML):'';
            iIndex=isNaN(iIndex)?0:iIndex;

            var oTr=document.createElement('tr');
            oTr.innerHTML='<td><input type="text" value="请输入目录名"/></td><td><a href="javascript:void(0);">删除</a></td></tr>';
            oBody.appendChild(oTr);
            attachment_delete_dir(oBody);
            return false;
        }

    }
</script>

  <div style="padding-left: 20px;">
    <p>资源库外链：
      <a href="#" id="change_extra_link" data-type="text" data-pk="<?php echo ($user['id']); ?>" data-url="/user/ajax_save_extra_link" data-title="资源库外链"><?php echo ($user['extra_file_link']?$user['extra_file_link']:'点击编辑链接(http://)'); ?></a> <span class="muted">（可以直接输入网盘中该会员的资料链接）</span>
      <?php if($user['extra_file_link']): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo ($user['extra_file_link']); ?>" target="_blank">新窗口打开</a><?php endif; ?>  
    </p>
    <?php if($user['extra_file_link']): ?><iframe src="<?php echo ($user['extra_file_link']); ?>" width="100%" height="500px"></iframe><?php endif; ?>  
  </div>
</div>

<script type="text/javascript">

  function toggle_content_block(key) {
    $('#'+key).toggle(500);
    X.get('/user/ajax_save_content_block_toggle?block='+key+'&id='+$("#recommend_id").val());
  }

</script>


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