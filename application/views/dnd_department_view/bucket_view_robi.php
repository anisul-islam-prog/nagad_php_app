<h1 class="heading"><?php if(1)  echo ''; ?> Robi Bucket Management </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="clearfix"></div>

    <div class="well well-sm">
    <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Add Bucket</a>
            </li>
            <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Bucket Mapping</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <!---bucket insert-->
            <div role="tabpanel" class="tab-pane active" id="home">
                <hr>
                <?php echo form_open('dnd_controllers/department/add_robi_bucket', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>                   
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="bucket_name">Bucket Name:</label>
                        <div class="col-sm-4">
                            <input type="text" required placeholder="Enter Bucket Name" name="bucket_name" id="bucket_name"  class="form-control input-sm" />
                            <input type="hidden" name="brand_value" id="brand_value" value="Robi" />
                        </div>
                    </div>
					
					
                    
                        
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Bucket</button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
            <!---bucket insert-->

            <!---bucket mapping-->
                <div role="tabpanel" class="tab-pane" id="profile">
                <hr>
                    <?php echo form_open('dnd_controllers/department/add_bucket_mapping', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="bucket_name">Bucket Name:</label>
                        <div class="col-sm-4">
                           <select name="bucket" id="bucket" required name="Department" class="chosen-select form-control"'>
                                <option>Select Please</option>
                                <?php foreach ($robi_bucket_all as $keys) {
                                   echo "<option value='".$keys['ID']."'>".$keys['BUCKET_NAME']."</option>";
                                }?>
                            </select>
                        </div>
                    </div>
					
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Bucket Mapping</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            <!---bucket mapping-->
          </div>
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
      <?php echo form_open('dnd_controllers/department/edit_robi_bucket', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

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
						<input type="hidden" name="b_value" id="b_value" value="Robi" />  
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
		
		var destination = "<?php echo base_url(); ?>dnd_controllers/department/getBucketPriority/";
     //alert(destination);
     $.ajax({
         method:"POST",
         dataType:"json",
         url:destination,
		 data:{brand_name:"Robi"},
         success: function(data) {
            console.log(data);
                         $('#bucket_priority').val(data.priority);
         }
     }); 
    $('button[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var id              = $(this).data('id');
            var dname       = $(this).data('dname');
			var bpriority   = $(this).data('priority');
			var bquota   = $(this).data('quota');
			
			
             
            $('#id').val(id);
            $('#dname').val(dname);
			$('#bpriority').val(bpriority); 
			$('#b_last_priority').val(bpriority);
			$('#bquota').val(bquota);		
            
            
        })
    });
	
	

</script>