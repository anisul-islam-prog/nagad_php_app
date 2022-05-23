<h1 class="heading"><?php if(1)  echo ''; ?> Add New Role </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('administrator/role/do_add_role', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="role_name"><span class="req text-danger">*</span> Role Name:</label>
    <div class="col-sm-4">
        <input type="text" name="role_name" id="role_name" required="required"  class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>
    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="role_description"><span class="req text-danger">*</span> Role Description:</label>
    <div class="col-sm-4">
        <textarea name="role_description" id="role_description" required="required" class="form-control input-sm"></textarea>
    </div>
</div>    
 
<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
    </div></div>




<?php echo form_close(); ?>
</div>