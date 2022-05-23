<script>
function form_validation(){
   var name = $('#name').val();
   var contact = $('#Contact').val();
   var email = $('#MailId').val();
   
   if(name=='' || name== null){
       alert('Name Field Can not be Empty !!');
       return false;
   }
   else if(contact=='' || contact== null){
       alert('Contact Field Can not be Empty !!');
       return false;
   }
   else if(email=='' || email== null){
       alert('Email Address Field Can not be Empty !!');
       return false;
   }
   else{
       return true; 
   }
        
        //alert(name+'--'+contact+'--'+email);
    
} // End of form_validation
</script>


<h1 class="heading"><?php if(1)  echo ''; ?> Update Profile</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('dnd_controllers/user_management/do_edit_profile', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    <input type="hidden" name="user_id" value="<?php echo $user_info['ID']; ?>" >


<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="name">Name:</label>
    <div class="col-sm-4">
        <input type="text" name="name" id="name" value="<?php echo $user_info['USER_NAME']; ?>" class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="Contact">Contact No:</label>
    <div class="col-sm-4">
        <input type="text" name="Contact" id="Contact" value="<?php echo $user_info['USER_MSISDN']; ?>" class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="MailId">Mail Address:</label>
    <div class="col-sm-4">
        <input type="text" name="MailId" id="MailId" value="<?php echo $user_info['USER_EMAIL']; ?>"  class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

    
<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="<?php  echo 'Update'; ?> " class="btn btn-primary btn-lg" />&nbsp;&nbsp;
    </div></div>




<?php echo form_close(); ?>
</div>
