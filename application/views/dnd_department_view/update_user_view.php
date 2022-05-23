
<h1 class="heading"> User Management</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



    <?php echo form_open('dnd_controllers/user_management/add_user', array('class' => 'form-horizontal','onsubmit' => 'return form_validation();')); ?>
    <input type="hidden" name="role_id" value="<?php echo $this->uri->segment(4); ?>">

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_login_name"><span class="req">*</span> Login Name</label>
        <div class="col-sm-4">
            <input type="text" required name="user_login_name" id="user_login_name" 
            value="<?php echo $usersDetails[0]['USER_LOGIN_NAME']; ?>" class="form-control"  />
        </div>
        <span class="help-block">Login Name is unique for each user.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password"><span class="req">*</span> Password</label>
        <div class="col-sm-4">
            <input type="password" required name="user_password" id="user_password" 
            value="<?php echo $usersDetails[0]['USER_PASSWORD']; ?>" class="form-control" />
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_password_confirm"><span class="req">*</span> Confirm Password</label>
        <div class="col-sm-4">
            <input type="password" required name="user_password_confirm" id="user_password_confirm" value="<?php echo $usersDetails[0]['USER_PASSWORD']; ?>" class="form-control" />      <!--<span class="help-inline">Inline help text</span>-->
        </div>
        <span class="help-block">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>

    <div class="form-group formSep">
       <label class="col-sm-2 control-label" for="user_department_id"><span class="req">*</span>User Department </label>
       <div class="controls col-sm-4">
           <select class="chosen-select form-control" id="user_department_id" name="user_department_id">
               <option value="<?php echo $usersDetails[0]['DEPARTMENT_ID']; ?>">
                <?php echo $usersDetails[0]['DNAME']; ?></option>

               <?php foreach ($get_department as $key => $d_value) { ?>
                <option value="<?php echo $d_value['ID']; ?>"><?php echo $d_value['DEPARTMENT_NAME']; ?></option>
            <?php } ?>
           </select>
       </div>
    </div>

    <div class="form-group formSep">
       <label class="col-sm-2 control-label" for="user_role_id"><span class="req">*</span>User Department </label>
       <div class="controls col-sm-4">
           <select class="chosen-select form-control" id="user_role_id" name="user_role_id">
               <option value="<?php echo $usersDetails[0]['ROLE_ID']; ?>">
                <?php echo $usersDetails[0]['RNAME']; ?></option>

               <?php foreach ($get_role as $key => $r_value) { ?>
                <option value="<?php echo $r_value['ID']; ?>"><?php echo $r_value['ROLE_NAME']; ?></option>
            <?php } ?>
           </select>
       </div>
    </div>  

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_name"><span class="req">*</span> User Name</label>
        <div class="col-sm-4">
            <input type="text" required name="user_name" id="user_name" 
            value="<?php echo $usersDetails[0]['USER_NAME']; ?>" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_email">Email Address</label>
        <div class="col-sm-4">
            <input type="text" required name="user_email" id="user_email" 
            value="<?php echo $usersDetails[0]['USER_EMAIL']; ?>" class="form-control" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="user_msisdn"><span class="req">*</span> Mobile Number</label>
        <div class="col-sm-3 input-group">
            <span class="input-group-addon">880</span>
            <input type="text" required name="user_msisdn" id="user_msisdn" 
            value="<?php echo $usersDetails[0]['USER_MSISDN']; ?>" class="form-control number-text" maxlength="10" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="is_group_manager"><span class="req text-danger">*</span>Is Group Manager ? </label>
        <div class="col-sm-1">
        <?php if($usersDetails[0]['IS_GROUP_MANAGER'] == 1){
        ?>
            <input type="checkbox" checked="checked" value="1" name="is_group_manager" id="is_group_manager"  class="form-control input-sm" />
        <?php } else{ ?>
            <input type="checkbox" value="1" name="is_group_manager" id="is_group_manager"  class="form-control input-sm" />
            <?php } ?>
        </div>
    </div>

    <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="user_id" value="1" />
        <input type="submit" value="Update User" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        <input type="reset" value="Reset" class="btn btn-default btn-lg" />
    </div></div>

<?php echo form_close(); ?>
