<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<?php $this->load->view('partials/login_header'); ?>

<div class="login_box">

    <?php echo form_open('login/do_login', array('id' => 'login_form')); ?>

        <div class="top_b">Sign in to DIVERGENT SMPP SOLUTION</div>

        <?php if (isset($message_error) && $message_error != '') : ?><div class="alert alert-danger"><?php echo $message_error; ?></div><?php endif; ?>
        <?php if (isset($message_success) && $message_success != '') : ?><div class="alert alert-success"><?php echo $message_success; ?></div><?php endif; ?>

        <div class="cnt_b">
            <div class="formRow" style="margin-bottom: 10px;">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input type="text" name="user_login" id="user_login" class="form-control input-sm" placeholder="Login ID" />
                </div>
            </div>
            <div class="formRow">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span><input type="password" name="user_password" id="user_password" class="form-control input-sm" placeholder="Password" />
                </div>
            </div>
        </div>

        <div class="btm_b clearfix">
            <button type="submit" class="btn btn-danger pull-right" style="margin-right: 45px;"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Sign In</button>
        </div>

    <?php echo form_close(); ?>

        

</div><!--login_box ends-->

<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#user_login').focus();
});
</script>


