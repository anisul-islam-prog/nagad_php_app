
<h1 class="heading"><?php if (1) echo ''; ?> Upload OBD MSISDN Base </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">



    <div class="row">
        <?php echo form_open_multipart('dnd_controllers/base_file/do_upload_obd_file', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>



        <div class="form-group formSep">
            <label class="col-sm-3 control-label" for="group_name">Upload OBD Base File:</label>
            <div class="col-sm-4">
                <input type="file" required name="upload_obd_file" id="upload_obd_file"  class="form-control input-sm" />
                <!--<span class="help-inline">Inline help text</span>-->
            </div>

            <div class="col-sm-offset-4">
                <table>
                    <tr>
                        <td><button type="submit" name="upload_base" value="upload_base" class="btn btn-primary btn-sm" ><i class="glyphicon glyphicon-upload" aria-hidden="true"></i> Upload Base File </button></td>
                    </tr>
                </table>
            </div>
        </div>




        <?php echo form_close(); ?>
    </div>
</div>


