<h1 class="heading"><?php if(1)  echo ''; ?> Add New Group </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('administrator/ussd/do_add_group', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="group_name">Group Name:</label>
    <div class="col-sm-4">
        <input type="text" name="group_name" id="group_name"  class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>


<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="is_active">Is Active ? :</label>
    <div class="col-sm-1">
        <input type="checkbox" name="is_active" id="is_active"  class="form-control input-sm" />
    </div>
</div>

    
<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
    </div></div>




<?php echo form_close(); ?>
</div>