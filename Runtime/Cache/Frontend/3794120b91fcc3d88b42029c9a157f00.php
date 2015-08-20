<?php if (!defined('THINK_PATH')) exit();?><!-- Parameters Needed: $title, $confirm_button_display-->
<div id="ajax-dialog" class="dialog-modal fade in" <?php echo ($modal_style); ?>>
    <div class="modal-header">
        <a  class="close" onclick="return X.boxClose();">×</a>
        <h4><?php echo ($title); ?></h4>
    </div>
    <div class="modal-body">
        
<div class=" pull-left" style="width: 320px">
    <form action="__URL__/password_submit" onsubmit="return check_pass();"  method="post"  class="form-horizontal dialog_validator">
        <input type="hidden" name="id" value="<?php echo ($user["id"]); ?>">
        <div class="control-group">
            <label class="control-label" for="point_title">密码</label>
            <div class="controls">
                <div id="password"></div>
                <script  type="text/javascript" >
                    var pwdwidget = null;
                    function init(){
                        pwdwidget = new PasswordWidget('password','password');
                        pwdwidget.MakePWDWidget();
                        check_pass_same();
                    }

                    function check_pass_same(){
                        $("#password2").keyup(check_pass_op);
                    }

                    function check_pass_op(){
                        var password2 = $("#password2").val();
                            var password = $("#password_id").val();
                            if(password != password2){
                                $("#pass_same_hint").show();
                                return false;
                            }else{
                                $("#pass_same_hint").hide();
                                return true;
                            }
                    }

                    function check_pass(){
                        var same_pass = check_pass_op();
                        if(same_pass){
                            if(pwdwidget.passwordStrength() < 3){
                                $("#pass_strenth_hint").show();
                            }else{
                                return true;
                            }
                            return false;
                        }else{
                            return false;
                        }
                    }
                </script>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="point_title">再次输入</label>
            <div class="controls">
              <input type="password" id="password2" class="strong-password"  name="password2" placeholder="请再次输入新密码">
              <span id="pass_same_hint" class="red_hint" style="display: none">密码不一致</span>
              <span id="pass_strenth_hint" class="red_hint" style="display: none">密码强度需要在中等以上</span>
            </div>
        </div>
    </form>
</div>
<div class="pull-right" style="width:220px">
    <div class='para'>
    </div>
    <div>
        <ul class="pass_tips">
            <li>密码需要包含8-16个字符，最好不要包含常见的单词，姓名。</li>
            <li>需要包含大写字母、小写字母、数字以及符号</li>
            <!-- <li>最近三次密码不能重复</li> -->
        </ul>
    </div>
</div>


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