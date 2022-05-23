<h1 class="heading"><?php if(1)  echo ''; ?> Making Management </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="clearfix"></div>

    <div class="well well-sm">

        <?php echo form_open('dnd_controllers/department/add_masking', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>                   
            <div class="form-group">
                <label class="col-sm-2 control-label" for="masking_name">Masking Name:</label>
                <div class="col-sm-4">
                    <input type="text" required placeholder="Enter Masking Name" name="masking_name" id="masking_name"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="is_active">Is Active ? :</label>
                <div class="col-sm-1">
                    <input type="checkbox" value="1" checked name="is_active" id="is_active" />
                </div>
            </div>
                
            <div class="form-group form-actions">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Masking</button>
                </div>
            </div>
        <?php echo form_close(); ?>
          
    </div>


<div class="clearfix"></div>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
                <div class="row control-row control-row-top">
                    <div class="right">
                        <?php echo $pagin_links; ?>
                    </div>
                </div>
                <?php echo $records_table; ?>
                <div class="row control-row control-row-bottom">
                    <div class="right">
                        <?php echo $pagin_links; ?>
                    </div>
                </div>
            </div>
        </div>
   
</div>




<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('dnd_controllers/department/edit_masking', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Bucket Update</h4>
          </div>
          <div class="modal-body">
          <input type="hidden" id="id" name="did">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Bucket Name:</label>
                    <div class="col-sm-4">
                        <input type="text" required name="dname" id="dname"  class="form-control input-sm" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Is Active ? </label>
                    <div class="col-sm-4">
                         <input type="checkbox" value="1" name="active" id="active" />
                    </div>
                </div>
                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
    $('button[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var id              = $(this).data('id');
            var dname           = $(this).data('dname');
            var active          = $(this).data('active');
             
            $('#id').val(id);
            $('#dname').val(dname);
            if(active!=0){ $('#active').attr('checked', true); }else{ $('#active').attr('checked', false); }

              
            
        })
    });

</script>