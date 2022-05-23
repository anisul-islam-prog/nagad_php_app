
<h1 class="heading"><?php if($is_edit) echo 'Edit'; else echo 'Add New'; ?> User</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<?php if ( !$is_edit) : ?>
<?php echo form_open('administrator/user/add_user', array('class' => 'form-horizontal')); ?>
<?php else : ?>
<?php echo form_open('administrator/user/update_user', array('class' => 'form-horizontal')); ?>
<?php endif; ?>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_login_name"><span class="req">*</span> Login Name</label>
        <div class="col-sm-4">
            <input type="text" name="user_login_name" id="user_login_name" value="<?php echo set_value('user_login_name', $this->form_data->user_login_name); ?>" class="form-control" <?php if ($is_edit) { echo ' readonly="readonly"'; } ?> />
        </div>
        <span class="help-block">Login Name is unique for each user.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password"><span class="req">*</span> Password</label>
        <div class="col-sm-4">
            <input type="password" name="user_password" id="user_password" value="<?php echo set_value('user_password', $this->form_data->user_password); ?>" class="form-control" />
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password_confirm"><span class="req">*</span> Confirm Password</label>
        <div class="col-sm-4">
            <input type="password" name="user_password_confirm" id="user_password_confirm" value="<?php echo set_value('user_password_confirm', $this->form_data->user_password_confirm); ?>" class="form-control" />      <!--<span class="help-inline">Inline help text</span>-->
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_role_id"><span class="req">*</span> User Role</label>
        <div class="controls col-sm-4">
            <?php echo form_dropdown('user_role_id', $this->user_role_list, $this->form_data->user_role_id, 'id="user_role_id" onchange="show_rsp_code()"   class="chosen-select form-control"'); ?>
        </div>
    </div>

   

    

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_name"><span class="req">*</span> User Name</label>
        <div class="col-sm-4">
            <input type="text" name="user_name" id="user_name" value="<?php echo set_value('user_name', $this->form_data->user_name); ?>" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_email">Email Address</label>
        <div class="col-sm-4">
            <input type="text" name="user_email" id="user_email" value="<?php echo set_value('user_email', $this->form_data->user_email); ?>" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_msisdn"><span class="req">*</span> Mobile Number</label>
        <div class="col-sm-3 input-group">
            <span class="input-group-addon">880</span>
            <input type="text" name="user_msisdn" id="user_msisdn" value="<?php echo set_value('user_msisdn', $this->form_data->user_msisdn); ?>" class="form-control number-text" maxlength="10" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>



  <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_is_lock1">Is Locked?</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="user_is_lock" id="user_is_lock1" value="1" <?php echo set_radio('user_is_lock', '1', $this->form_data->user_is_lock == '1'); ?> /> Yes
            </label>&nbsp;&nbsp;
            <label class="radio-inline">
                <input type="radio" name="user_is_lock" id="user_is_lock2" value="0" <?php echo set_radio('user_is_lock', '0', $this->form_data->user_is_lock == '0'); ?> /> No
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="user_is_active1">Is Active?</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="user_is_active" id="user_is_active1" value="1" <?php echo set_radio('user_is_active', '1', $this->form_data->user_is_active == '1'); ?> /> Yes
            </label>&nbsp;&nbsp;
            <label class="radio-inline">
                <input type="radio" name="user_is_active" id="user_is_active2" value="0" <?php echo set_radio('user_is_active', '0', $this->form_data->user_is_active == '0'); ?> /> No
            </label>
        </div>
    </div>

    <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="user_id" value="<?php echo set_value('user_id', $this->form_data->user_id); ?>" />
        <input type="submit" value="<?php if($is_edit) echo 'Update'; else echo 'Add'; ?> User" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        <input type="reset" value="Reset" class="btn btn-default btn-lg" />
    </div></div>

<?php echo form_close(); ?>
