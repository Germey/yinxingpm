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
    

<script type="text/javascript" src="/styles/js/highcharts/js/highcharts.js"></script>

<div id="content" class="wide">
    <ul class="nav nav-tabs" id="stat-nav">
      <li><a href="/stat/recommend">推荐人</a></li>
      <li class="active"><a href="/stat/candidate">候选人</a></li>
      <li><a href="/stat/partner">银杏伙伴</a></li>
      <li><a href="/stat/data">项目数据</a></li>
    </ul>
    <h5>候选人相关信息，包含了候选人数量，地区排名，年龄排名，性别比例</h5>
    <form class="filter" action="/stat/candidate" method="get" style="margin: 20px 0">
        <select class="chosen-select span2" multiple name="address_province[]" data-placeholder=" - 候选人省份 -"><?php echo getSelectOptions("user_recommends.address_province");?></select>
        <select class="chosen-select span2" multiple name="gender[]" data-placeholder=" - 候选人性别 -"><?php echo getSelectOptions("user_info.gender");?></select>
        <input type="submit" class="btn btn-stat" value="筛选">
        <a type="button" href="/stat/candidate" class="btn btn-stat" >重置</a>
    </form>
    <!-- <h5>您可输入如上字段进行模糊查询筛选</h5> -->
    <table class="table table-striped table-hover home-tb">
      <tr>
        <td class="l">候选人数量：</td>
        <td class="r"><?php echo ($candidate_count); ?></td>
      </tr>
    </table>

    <table class="stat">
      <?php  $column = array("候选人省份","人数"); $stat_type_name = '候选人省份分布'; $key_stat_type_key = 'candidate-province-rank'; $table_value = $candidate_province_rank; ?>
      <tr>
  <td class="out">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <?php if(is_array($column)): foreach($column as $key=>$one): ?><th><?php echo ($one); ?></th><?php endforeach; endif; ?>
            </tr>
        </thead>
        <?php if(is_array($table_value)): foreach($table_value as $key=>$one): ?><tr>
            <?php if($key_stat_type_key == 'recommend-num-rank'): ?><td><a href="/user/recommend?recommender_name=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'candidate-province-rank'): ?>
                <td><a href="/user/recommend?address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'partner-province-rank'): ?>
                <td><a href="/user/recommend?status=99&address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php else: ?>
                <td><?php echo ($key); ?></td><?php endif; ?>
                <td><?php echo ($one); ?></td>
          </tr><?php endforeach; endif; ?>
    </table>
  </td>
  <td class="out">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#<?php echo ($key_stat_type_key); ?>-tab1" data-toggle="tab"><?php echo ($stat_type_name); ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?php echo ($key_stat_type_key); ?>-tab1">
              <div id="<?php echo ($key_stat_type_key); ?>-chart" class="small_highchart"></div>
          </div>
        </div>
  </td>
<tr>



      <?php
 $column = array("候选人年龄","人数"); $stat_type_name='候选人年龄段统计'; $key_stat_type_key = 'candidate-age-rank'; $table_value = $candidate_age_rank; ?>
      <tr>
  <td class="out">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <?php if(is_array($column)): foreach($column as $key=>$one): ?><th><?php echo ($one); ?></th><?php endforeach; endif; ?>
            </tr>
        </thead>
        <?php if(is_array($table_value)): foreach($table_value as $key=>$one): ?><tr>
            <?php if($key_stat_type_key == 'recommend-num-rank'): ?><td><a href="/user/recommend?recommender_name=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'candidate-province-rank'): ?>
                <td><a href="/user/recommend?address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'partner-province-rank'): ?>
                <td><a href="/user/recommend?status=99&address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php else: ?>
                <td><?php echo ($key); ?></td><?php endif; ?>
                <td><?php echo ($one); ?></td>
          </tr><?php endforeach; endif; ?>
    </table>
  </td>
  <td class="out">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#<?php echo ($key_stat_type_key); ?>-tab1" data-toggle="tab"><?php echo ($stat_type_name); ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?php echo ($key_stat_type_key); ?>-tab1">
              <div id="<?php echo ($key_stat_type_key); ?>-chart" class="small_highchart"></div>
          </div>
        </div>
  </td>
<tr>


      <tr>
        <td><h5>注：没有通过的初审的候选人不在年龄统计之中</h5></td>
      </tr>
      <?php
 $column = array("候选人性别","人数"); $stat_type_name='候选人性别比例统计排名'; $key_stat_type_key = 'candidate-gender-rank'; $table_value = $candidate_gender_rank; ?>
      <tr>
  <td class="out">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <?php if(is_array($column)): foreach($column as $key=>$one): ?><th><?php echo ($one); ?></th><?php endforeach; endif; ?>
            </tr>
        </thead>
        <?php if(is_array($table_value)): foreach($table_value as $key=>$one): ?><tr>
            <?php if($key_stat_type_key == 'recommend-num-rank'): ?><td><a href="/user/recommend?recommender_name=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'candidate-province-rank'): ?>
                <td><a href="/user/recommend?address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php elseif($key_stat_type_key == 'partner-province-rank'): ?>
                <td><a href="/user/recommend?status=99&address_province=<?php echo ($key); ?>"><?php echo ($key); ?></td>
            <?php else: ?>
                <td><?php echo ($key); ?></td><?php endif; ?>
                <td><?php echo ($one); ?></td>
          </tr><?php endforeach; endif; ?>
    </table>
  </td>
  <td class="out">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#<?php echo ($key_stat_type_key); ?>-tab1" data-toggle="tab"><?php echo ($stat_type_name); ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?php echo ($key_stat_type_key); ?>-tab1">
              <div id="<?php echo ($key_stat_type_key); ?>-chart" class="small_highchart"></div>
          </div>
        </div>
  </td>
<tr>


      <tr>
        <td><h5>注：没有通过的初审的候选人不在性别统计之中</h5></td>
      </tr>


    </table>

</div>

<script type="text/javascript">

$(function () {

    $('#candidate-gender-rank-chart').highcharts({
        credits: {text: ''},
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: { text: '候选人性别统计图' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b><br />'+ this.y + ' (' +Math.round(this.percentage*10)/10 + '%)';
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: '人数',
            data: <?php echo array_to_pie_chart_json($candidate_gender_rank);?>
        }]
    });

    $('#candidate-province-rank-chart').highcharts({
        credits: {text: ''},
        chart: {
            type: 'column'
        },
        title: { text: '候选人省份分布' },
        xAxis: [{
            categories: <?php echo json_encode(array_keys($candidate_province_rank));?>
        }],
        yAxis: [{
            labels: {
                format: '{value}人',
                style: {
                    color: '#89A54E'
                }
            },
            title: {
                text: '人数',
                style: {
                    color: '#89A54E'
                }
            }
        }],
        tooltip: {
            shared: true
        },
        series: [{
            name: '人数',
            color: '#4572A7',
            type: 'column',
            data: <?php echo array_to_pie_chart_json($candidate_province_rank);?>,
            tooltip: {
                valueSuffix: '个'
            }
        }]
    });

    $('#candidate-age-rank-chart').highcharts({
        credits: {text: ''},
        chart: {
            type: 'column'
        },
        title: { text: '候选人人年龄段统计' },
        xAxis: [{
            categories: <?php echo json_encode(array_keys($candidate_age_rank));?>
        }],
        yAxis: [{ 
            labels: {
                format: '{value}人',
                style: {
                    color: '#89A54E'
                }
            },
            title: {
                text: '人数',
                style: {
                    color: '#89A54E'
                }
            }
        }],
        tooltip: {
            shared: true
        },
        series: [{
            name: '年龄',
            color: '#4572A7',
            type: 'column',
            data: <?php echo array_to_pie_chart_json($candidate_age_rank);?>,
            tooltip: {
                valueSuffix: '个'
            }
        }]
    });
    
});
  
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