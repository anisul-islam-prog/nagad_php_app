
<h1 class="heading"> User Management</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<?php echo form_open('dnd_controllers/user_management/add_user', array('class' => 'form-horizontal')); ?>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_login_name"><span class="req">*</span> Login Name</label>
        <div class="col-sm-4">
            <input type="text" required name="user_login_name" id="user_login_name" value="" class="form-control"  />
        </div>
        <span class="help-block">Login Name is unique for each user.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password"><span class="req">*</span> Password</label>
        <div class="col-sm-4">
            <input type="password" required name="user_password" id="user_password" value="" class="form-control" />
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password_confirm"><span class="req">*</span> Confirm Password</label>
        <div class="col-sm-4">
            <input type="password" required name="user_password_confirm" id="user_password_confirm" value="" class="form-control" />      <!--<span class="help-inline">Inline help text</span>-->
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
       <label class="col-sm-2 control-label" for="user_department_id"><span class="req">*</span>User Department </label>
       <div class="controls col-sm-4">
           <select class="chosen-select form-control" id="user_department_id" name="user_department_id">
               <option value=""> --- Select Please --- </option>
               <?php foreach ($departments as $key => $value) { ?>
                <option value="<?php echo $value['ID']; ?>"><?php echo $value['DEPARTMENT_NAME']; ?></option>
            <?php } ?>
           </select>
       </div>
   </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_role_id"><span class="req">*</span> User Role</label>
        <div class="controls col-sm-4">
        <select class="chosen-select form-control" id="user_role_id" name="user_role_id">
            <option value=""> --- Select Please --- </option>
            <?php foreach ($roles as $key => $value) { ?>
                <option value="<?php echo $value['ID']; ?>"><?php echo $value['ROLE_NAME']; ?></option>
            <?php } ?>
        </select>
            
        </div>
    </div>    

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_name"><span class="req">*</span> User Name</label>
        <div class="col-sm-4">
            <input type="text" required name="user_name" id="user_name" value="" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_email">Email Address</label>
        <div class="col-sm-4">
            <input type="text" required name="user_email" id="user_email" value="" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_msisdn"><span class="req">*</span> Mobile Number</label>
        <div class="col-sm-3 input-group">
            <span class="input-group-addon">880</span>
            <input type="text" required name="user_msisdn" id="user_msisdn" value="" class="form-control number-text" maxlength="10" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="is_group_manager"><span class="req text-danger">*</span>Is Group Manager ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_group_manager" id="is_group_manager"  class="form-control input-sm" />
        </div>
    </div>

  <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_is_lock1">Is Locked?</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="user_is_lock" id="user_is_lock1" value="1"  /> Yes
            </label>&nbsp;&nbsp;
            <label class="radio-inline">
                <input type="radio" name="user_is_lock" checked="checked" id="user_is_lock2" value="0"  /> No
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="user_is_active1">Is Active?</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="user_is_active" checked="checked" id="user_is_active1" value="1"  /> Yes
            </label>&nbsp;&nbsp;
            <label class="radio-inline">
                <input type="radio" name="user_is_active" id="user_is_active2" value="0"  /> No
            </label>
        </div>
    </div>

    <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="user_id" value="1" />
        <input type="submit" value="Add User" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        <input type="reset" value="Reset" class="btn btn-default btn-lg" />
    </div></div>

<?php echo form_close(); ?>
