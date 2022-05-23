<script>
function form_validation(){
   var old_password = $('#old_password').val();
   var new_password = $('#new_password').val();
   var re_password = $('#re_password').val();
   
   if(old_password=='' || old_password== null){
       alert('Old Password Field Can not be Empty !!');
       return false;
   }
   else if(new_password=='' || new_password== null){
       alert('New Password Field Can not be Empty !!');
       return false;
   }
   else if(re_password=='' || re_password== null){
       alert('Re-Type Password Field Can not be Empty !!');
       return false;
   }
   else if(new_password != re_password){
       alert('New Password and Re-Type Password din not matched !!');
       return false;
   }
   else{
       return true; 
   }
        
        //alert(name+'--'+contact+'--'+email);
    
} // End of form_validation
</script>


<h1 class="heading"><?php if(1)  echo ''; ?> Change Password</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('dnd_controllers/user_management/do_chage_password_first', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="old_password">Old Password:</label>
    <div class="col-sm-4">
        <input type="password" name="old_password" id="old_password"  class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="new_password">New Password:</label>
    <div class="col-sm-4">
        <input type="password" name="new_password" id="new_password"  class="form-control input-sm" />
        <span class="help-inline">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>
</div>

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="re_password">Re-Type Password:</label>
    <div class="col-sm-4">
        <input type="password" name="re_password" id="re_password"  class="form-control input-sm" />
        <span class="help-inline">At least 8 (eight) characters. Must have at least one lowercase, uppercase, numeric and symbol letter.</span>
    </div>
</div>

    
<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="<?php  echo 'Change'; ?> " class="btn btn-primary btn-lg" />&nbsp;&nbsp;
    </div></div>




<?php echo form_close(); ?>
</div>
