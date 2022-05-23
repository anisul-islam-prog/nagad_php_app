<h1 class="heading"><?php if(1)  echo ''; ?> Add Robi Department </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('dnd_controllers/department/add_department', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="group_name">Department Name:</label>
    <div class="col-sm-4">
        <input type="text" required name="department_name" id="group_name"  class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

<input type="hidden" id="brand_name" name="brand_name" value="robi" class="" >
<input type="hidden" value="1" name="is_active" id="is_active" />

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="is_active">Priority :</label>
    <div class="col-sm-4">
        <input type="text" readonly class="form-control input-sm" name="Priority" id="Priority" />
    </div>
</div>

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="quota">Quota :</label>
    <div class="col-sm-4">
        <input type="text" class="form-control input-sm" name="quota" id="quota" />
    </div>
</div>

    
<div class="form-group form-actions">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Department</button>
    </div>
</div>
<?php echo form_close(); ?>
</div>



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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('dnd_controllers/department/edit', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Robi Department Update</h4>
          </div>
          <div class="modal-body">
          <input type="hidden" id="id" name="did">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Department Name:</label>
                    <div class="col-sm-4">
                        <input type="text" required name="dname" id="dname"  class="form-control input-sm" />
                    </div>
                </div>
				<input type="hidden" id="bname" name="bname" value="robi" class="" >


                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Priority </label>
                    <div class="col-sm-4">
                         <input type="text" required class="form-control input-sm" name="priority" id="priority" />
                         <input type="hidden" name="prioritya" id="prioritya" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="group_name">Quota :</label>
                    <div class="col-sm-4">
                        <input type="text" required name="quotas" id="quotas" class="form-control input-sm" />
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
		var brand_name = $('#brand_name').val();
         
    var destination = "<?php echo base_url(); ?>dnd_controllers/department/getOperatorPriority/";
     //alert(destination);
     $.ajax({
         method:"POST",
         dataType:"json",
         url:destination,
         data:{brand_name:brand_name},
         success: function(data) {
            // console.log("fhfk");
                         $('#Priority').val(data.priority);
         }
     }); 
    $('button[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var id          = $(this).data('id');
            var dname       = $(this).data('dname');
            var bname       = $(this).data('brand');
            var priority    = $(this).data('priority');
            var quota       = $(this).data('quota');

            $('#id').val(id);
            $('#dname').val(dname);
            $("#bnamesss option[value='"+bname+"']").attr('selected', 'selected');
            $('#priority').val(priority);
            $('#prioritya').val(priority);
            $('#quotas').val(quota);

            
            
        })
    });
    function getOperatorPriority()
    { 
        // alert('Please select Broadcast Date');
    
    }//getPriority
</script>