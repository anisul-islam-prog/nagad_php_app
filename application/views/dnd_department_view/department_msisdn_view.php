<h1 class="heading"><?php if (1) echo ''; ?> Department MSISDN Management </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">
   
    <div class="row">
<?php echo form_open_multipart('dnd_controllers/department/do_action', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="group_name">Department Name :</label>
        <div class="col-sm-3">
             <select class="form-control" name="dname">
                 <option value="">Select please</option>
                 <?php 
                    foreach ($department as $key) {
                        echo "<option value='".$key['ID']."'>".$key['DEPARTMENT_NAME']."</option>";
                    }
                 ?>
             </select>
        </div>
        
        
    </div>


    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="group_name">MSISDN Upload:</label>
        <div class="col-sm-3">
            <input type="file" required name="upload_file" id="upload_file"  class="form-control input-sm" />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
        
        <div class="col-sm-6">
            <table>
                <tr>
                    <td><button type="submit" name="add_dnd" value="include" class="btn btn-primary btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Include MSISDN Upload</button></td>

                    <td><button type="submit" name="delete_dnd" value="exclude" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure?')" ><i class="glyphicon glyphicon-minus-sign" aria-hidden="true"></i> Exclude From MSISDN</button></td>

                    <td><a href="<?php echo base_url('dnd_controllers/department/download_department_msisdn'); ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download Additional MSISDN</a></td>

                </tr>
            </table>
        </div>
    </div>



    
<?php echo form_close(); ?>
    </div>
</div>
