
<h1 class="heading"><?php if($is_edit) echo 'Edit'; else echo 'Add New'; ?> Role</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<?php if ( !$is_edit ) : ?>
<?php echo form_open('administrator/role/add_role', array('class' => 'form-horizontal')); ?>
<?php else : ?>
<?php echo form_open('administrator/role/update_role', array('class' => 'form-horizontal')); ?>
<?php endif; ?>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="role_name">Role Name</label>
        <div class="col-sm-4">
            <input type="text" name="role_name" id="role_name" value="<?php echo set_value('role_name', $this->form_data->role_name); ?>" class="form-control" <?php if ($is_edit) { echo ' readonly="readonly"'; } ?> />
        </div>
        <!--<span class="help-inline">Login Name is unique for each user.</span>-->
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="role_description">Role Description</label>
        <div class="col-sm-4">
            <textarea name="role_description" id="role_description" class="form-control" rows="4" cols="30"><?php echo set_value('role_description', $this->form_data->role_description); ?></textarea>
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="priv_ids">Privileges</label>
        <div class="col-sm-4">
            <?php echo form_multiselect('priv_ids[]', $this->priv_list, $this->form_data->priv_ids, 'id="priv_ids" class="chosen-select" style="width:100%;height:34px;"'); ?>
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="role_id" value="<?php echo set_value('role_id', $this->form_data->role_id); ?>" />
        <input type="submit" value="<?php if($is_edit) echo 'Update'; else echo 'Add'; ?> Role" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        <input type="reset" value="Reset" class="btn btn-default btn-lg" />
    </div></div>

<?php echo form_close(); ?>

