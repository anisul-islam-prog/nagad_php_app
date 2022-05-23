<h1 class="heading"><?php if (1) echo ''; ?> DND Management </h1>

<?php
if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
}
?>
<?php
if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
}
?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<script type="text/javascript">
    $(function () {
        $("#obd_date").datepicker();
    });
</script>


<div class="well well-sm">

    <div class="row">
        <div class="col-md-3 pull-right">
            <a href="<?php echo base_url('dnd_controllers/manage_dnd/download_dnd/all'); ?>" ><button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download DND Base</button></a>
        </div>
    </div>

    <br><br>

    <div class="row">
<?php echo form_open_multipart('dnd_controllers/manage_dnd/do_action', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>



        <div class="form-group formSep">
            <label class="col-sm-2 control-label" for="group_name">DND Upload:</label>
            <div class="col-sm-4">
                <input type="file" required name="upload_file" id="upload_file"  class="form-control input-sm" />
                <!--<span class="help-inline">Inline help text</span>-->
            </div>

            <div class="col-sm-offset-4">
                <table>
                    <tr>
                        <td><button type="submit" name="add_dnd" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Include DND Upload</button></td>
                        <td><button type="submit" name="delete_dnd" value="exclude" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" ><i class="glyphicon glyphicon-minus-sign" aria-hidden="true"></i> Exclude From DND</button></td>
                        <td><a href="<?php echo base_url('dnd_controllers/manage_dnd/download_dnd/local'); ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download Additional DND</a></td>
                    </tr>
                </table>
            </div>
        </div>




<?php echo form_close(); ?>
    </div>
</div>

<h1 class="heading"><?php if (1) echo ''; ?> OBD Management </h1>

<div class="well well-sm">

    <!--    <div class="row">
        <div class="col-md-3 pull-right">
            <a href="<?php echo base_url('dnd_controllers/manage_dnd/download_dnd/all'); ?>" ><button class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download DND Base</button></a>
        </div>
        </div>-->

    <!--<br><br>-->

    <div class="row">
<?php echo form_open_multipart('dnd_controllers/manage_dnd/do_action', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>



        <div class="form-group formSep">
            <label class="col-sm-2 control-label" for="group_name">OBD Upload:</label>
            <div class="col-sm-3">
                <input type="file" required name="upload_file" id="upload_file"  class="form-control input-sm" />
                <!--<span class="help-inline">Inline help text</span>-->
            </div>

            <label class="col-sm-2 control-label" for="group_name">Date:</label>
            <div class="col-sm-3">
                <input type="text" name="obd_date" id="obd_date" value="" class="form-control"  />
                <!--<span class="help-inline">Inline help text</span>-->
            </div>

        </div>
        <div class="form-group formSep">

            <div class="col-sm-offset-4">
                <table>
                    <tr>
                        <td><button type="submit" name="add_obd" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Include OBD Upload</button></td>
                        <td><button type="submit" name="delete_dnd" value="exclude" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" ><i class="glyphicon glyphicon-minus-sign" aria-hidden="true"></i> Reset OBD</button></td>
                        <td><a href="<?php echo base_url('dnd_controllers/manage_dnd/download_dnd/local'); ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download OBD</a></td>
                    </tr>
                </table>
            </div>
        </div>




<?php echo form_close(); ?>
    </div>
</div>







<script type="text/javascript">
    $(document).ready(function () {
        $('button[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var id = $(this).data('id');
            var dname = $(this).data('dname');
            var active = $(this).data('active');

            $('#id').val(id);
            $('#dname').val(dname);

            if (active != 0) {
                $('#active').attr('checked', true);
            } else {
                $('#active').attr('checked', false);
            }


            $('#usermail').val(user_email);

        })
    });
</script>