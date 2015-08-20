<?php if (!defined('THINK_PATH')) exit();?><!-- Parameters Needed: $title, $confirm_button_display-->
<div id="ajax-dialog" class="dialog-modal fade in" <?php echo ($modal_style); ?>>
    <div class="modal-header">
        <a  class="close" onclick="return X.boxClose();">×</a>
        <h4><?php echo ($title); ?></h4>
    </div>
    <div class="modal-body">
        
<form action="__URL__/question_submit"  method="post"  class="form-horizontal">
  <input type="hidden" value="<?php echo ($question['id']); ?>" name="id">
  <table class="table table-noborder">
    <tr>
      <td><b>类型</b></td>
      <td><select name="type_id"><?php echo Utility::Option($user_types, $question['type_id']);?></select></td>
    </tr>
    <tr>
      <td><b>题目</b></td>
      <td><input type="text" id="question" value="<?php echo ($question["question"]); ?>" name="question" class="span7"></td>
    </tr>
    <tr>
      <td><b>备注</b></td>
      <td><input type="text" id="note" value="<?php echo ($question["note"]); ?>" name="note" class="span7"></td>
    </tr>
    <tr>
      <td><b>排序</b></td>
      <td><input type="number" id="sequence" value="<?php echo ($question["sequence"]); ?>" name="sequence" class="span1"></td>
    </tr>
  </table>
</form>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" onclick="submit_form();" class="btn btn-success btn-small">
            <i class="icon-white icon-ok"></i><?php if(empty($confirm_button_display)): ?>确认<?php else: echo ($confirm_button_display); endif; ?>
        </a>
        <a href="#" onclick="return X.boxClose();" class="btn btn-small">取消</a>
    </div>
</div>
<script type="text/javascript">
    function submit_form(){
        $(".modal-body form:first").submit();
    }
</script>