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
    <ul class="nav nav-tabs">
      <li><a href="/stat/index">基本统计</a></li>
      <li class="active"><a href="/stat/project">项目统计</a></li>
    </ul>

    <p class="text-error">注：1. 以下统计只包括正式资助的项目<br />　　2. 可从项目列表页选择N个项目跳转到本页统计</p>
    <?php if($show_project_titles): ?><p class="text-error">本次统计的项目为：</p>
        <blockquote>
            <?php if(is_array($show_project_titles)): foreach($show_project_titles as $key=>$t): ?><p><?php echo ($key+1); ?>. <?php echo ($t); ?></p><?php endforeach; endif; ?>
        </blockquote><?php endif; ?>
<!--     <form action="/stat/project" method="get" style="margin-bottom: 50px;">
        <select class="chosen-select span2" multiple name="samounts[]" data-placeholder=" - 合同金额 -"><?php echo Utility::Option($amount_range, $samounts);?></select>        
        <input type="submit" class="btn btn-small" value="筛选" />
        <a class="btn btn-link" href="/stat/project">重置</a>
    </form>
 -->
    <table class="stat">

      <?php  $stat_type_name='资助规模'; $stat_type_data = $amount_data; $key_stat_type_key = 'amount'; $stat_type_pcount_map = $amount_pcount_map; $stat_type_amount_map = $amount_amount_map; $search_key = "contract_amount_range"; ?>
      <tr>
  <td class="out">
    <table class="table table-striped table-hover">
        <thead><tr><th><?php echo ($stat_type_name); ?></th><th>项目数</th><th>申请金额</th><th>合同金额</th><th>实际资助金额</th></tr></thead>
        <?php if(is_array($stat_type_data)): foreach($stat_type_data as $key=>$one): ?><tr>
            <td><b>
                <?php if($search_key): ?><a target="_blank" href="/project/all/?search_from=stat&<?php echo ($search_key); ?>=<?php echo urlencode($key);?>"><?php echo ($key); ?></a>
                <?php else: ?>
                    <?php echo ($key); endif; ?>
            </b></td>
            <td><?php echo ($one['pcount']); ?></td>
            <td><?php echo ($one['need_budget']); ?>万</td><td><?php echo ($one['contract_budget']); ?>万</td><td><?php echo ($one['had_grant_amount']); ?>万</td></tr><?php endforeach; endif; ?>
    </table>
  </td>
  <td class="out">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#<?php echo ($key_stat_type_key); ?>tab1" data-toggle="tab"><?php echo ($stat_type_name); ?>-项目数</a></li>
          <li class=""><a href="#<?php echo ($key_stat_type_key); ?>tab2" data-toggle="tab"><?php echo ($stat_type_name); ?>-合同金额</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?php echo ($key_stat_type_key); ?>tab1">
              <div id="project_<?php echo ($key_stat_type_key); ?>_project_pie" class="small_highchart"></div>
          </div>
          <div class="tab-pane" id="<?php echo ($key_stat_type_key); ?>tab2">
              <div id="project_<?php echo ($key_stat_type_key); ?>_amount_pie" class="small_highchart"></div>
          </div>
        </div>
  </td>
<tr>

<script type="text/javascript">

$(function () {
        // 项目类型
        var project_id_str = 'project_'+'<?php echo ($key_stat_type_key); ?>'+'_project_pie';
        $('#'+project_id_str).highcharts({
            credits: {text: ''},
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: { text: '<?php echo ($stat_type_name); ?> - 项目数' },
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
                name: '项目数',
                data: <?php echo array_to_pie_chart_json($stat_type_pcount_map);?>
            }]
        });

        var amount_id_str = 'project_'+'<?php echo ($key_stat_type_key); ?>'+'_amount_pie';
        $('#'+amount_id_str).highcharts({
            credits: {text: ''},
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: { text: '<?php echo ($stat_type_name); ?> - 项目金额数' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b><br />'+ this.y + '万 (' +Math.round(this.percentage*10)/10 + '%)';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '合同额',
                data: <?php echo array_to_pie_chart_json($stat_type_amount_map);?>
            }]
        });
    });
  
</script>

      <?php  $stat_type_name='城市'; $stat_type_data = $add_data; $key_stat_type_key = 'address'; $stat_type_pcount_map = $add_pcount_map; $stat_type_amount_map = $add_amount_map; $search_key = "address_city"; ?>
      <tr>
  <td class="out">
    <table class="table table-striped table-hover">
        <thead><tr><th><?php echo ($stat_type_name); ?></th><th>项目数</th><th>申请金额</th><th>合同金额</th><th>实际资助金额</th></tr></thead>
        <?php if(is_array($stat_type_data)): foreach($stat_type_data as $key=>$one): ?><tr>
            <td><b>
                <?php if($search_key): ?><a target="_blank" href="/project/all/?search_from=stat&<?php echo ($search_key); ?>=<?php echo urlencode($key);?>"><?php echo ($key); ?></a>
                <?php else: ?>
                    <?php echo ($key); endif; ?>
            </b></td>
            <td><?php echo ($one['pcount']); ?></td>
            <td><?php echo ($one['need_budget']); ?>万</td><td><?php echo ($one['contract_budget']); ?>万</td><td><?php echo ($one['had_grant_amount']); ?>万</td></tr><?php endforeach; endif; ?>
    </table>
  </td>
  <td class="out">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#<?php echo ($key_stat_type_key); ?>tab1" data-toggle="tab"><?php echo ($stat_type_name); ?>-项目数</a></li>
          <li class=""><a href="#<?php echo ($key_stat_type_key); ?>tab2" data-toggle="tab"><?php echo ($stat_type_name); ?>-合同金额</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?php echo ($key_stat_type_key); ?>tab1">
              <div id="project_<?php echo ($key_stat_type_key); ?>_project_pie" class="small_highchart"></div>
          </div>
          <div class="tab-pane" id="<?php echo ($key_stat_type_key); ?>tab2">
              <div id="project_<?php echo ($key_stat_type_key); ?>_amount_pie" class="small_highchart"></div>
          </div>
        </div>
  </td>
<tr>

<script type="text/javascript">

$(function () {
        // 项目类型
        var project_id_str = 'project_'+'<?php echo ($key_stat_type_key); ?>'+'_project_pie';
        $('#'+project_id_str).highcharts({
            credits: {text: ''},
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: { text: '<?php echo ($stat_type_name); ?> - 项目数' },
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
                name: '项目数',
                data: <?php echo array_to_pie_chart_json($stat_type_pcount_map);?>
            }]
        });

        var amount_id_str = 'project_'+'<?php echo ($key_stat_type_key); ?>'+'_amount_pie';
        $('#'+amount_id_str).highcharts({
            credits: {text: ''},
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: { text: '<?php echo ($stat_type_name); ?> - 项目金额数' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b><br />'+ this.y + '万 (' +Math.round(this.percentage*10)/10 + '%)';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '合同额',
                data: <?php echo array_to_pie_chart_json($stat_type_amount_map);?>
            }]
        });
    });
  
</script>

    </table>
    <div id="result_code_chart" style="height:600px"></div>

    <table class="table table-bordered">
        <tr>
            <th>统计项</th>
            <?php if(is_array($result_ranges)): foreach($result_ranges as $key=>$r): ?><th  style="text-align:center"><?php echo get_quarter_display_name($r);?></th><?php endforeach; endif; ?>
            <th style="text-align:center">小计</th>
        </tr>
        <?php if(is_array($result_codes)): foreach($result_codes as $item_key=>$one): ?><tr>
                <td><?php echo ($one); ?></td>
                <?php $sum = 0; ?>
                <?php if(is_array($result_ranges)): foreach($result_ranges as $key=>$r): $value = $result_data_by_quarter[$r][$item_key]; if(intval($value)) { $sum += $value; } ?>
                    <td style="text-align:center"><?php echo ($value); ?></td><?php endforeach; endif; ?>
                <td style="text-align:center"><?php echo ($sum?$sum:'-'); ?></td>
            </tr><?php endforeach; endif; ?>
    </table>

</div>

<script type="text/javascript">

    $(".chosen-select").chosen();
    
    $(function () {

       $('#result_code_chart').highcharts({
            credits: {text: ''},
            title: {
                text: '量化统计项',
                x: -20 //center
            },
            xAxis: {
                categories: <?php echo json_encode($result_ranges);?>
            },
            yAxis: {
                title: {
                    text: '统计量'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: <?php echo json_encode($code_data);?>
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