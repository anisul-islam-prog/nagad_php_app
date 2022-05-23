<h1 class="heading"><?php if(1)  echo ''; ?>OBD Campaign List </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
                 <table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Team</th>
                        
                        <th>Campaign Name</th>
                        <th>Publish Start Date</th>
                        <th>Publish End Date</th>
                        <th>Campaign ID</th>
                        <th>Brand </th>
                       
                        <th>Base</th>
                        <th>Threshold</th>
                       
                        
                        <th>Publish</th>
						<th>Action</th>
                       <!-- <th width="80" class="center">ACTION</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allData as $key) { ?>
                    <tr>
                        <td><?php echo $key['dname']; ?></td>
                       
                        <td><?php echo $key['CAMPAIGN_NAME']; ?></td>
                        <td><?php echo $key['START_DATE']; ?></td>
                        <td><?php echo $key['END_DATE']; ?></td>
                        <td><?php echo $key['ID']; ?></td>
                        <td><?php echo $key['BRAND_NAME']; ?></td>
                        
                        <td><?php echo $key['BASE_COUNT']; ?></td>
                        <td><?php echo $key['THRESHOLD']; ?></td>
                        
                        
                        <td><?php  $currentDate = date('Y-m-d');
						$currentDate=date('Y-m-d', strtotime($currentDate));
						//echo $paymentDate; // echos today! 
						$contractDateBegin = date('Y-m-d', strtotime("".$key['START_DATE'].""));
						$contractDateEnd = date('Y-m-d', strtotime("".$key['END_DATE'].""));

						if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)) 
						{
							echo "YES";
						}
						else
						{
						echo "NO";  
						
						//var_dump(date_diff(date_create($key['START_DATE']),date_create($key['END_DATE']))->format("%a"));die;  
						} ?></td>
						<td><a href="<?php echo base_url('dnd_controllers/campaign/download_obd_base/'.$key['ID'].'/'.date_diff(date_create($key['START_DATE']),date_create($key['END_DATE']))->format("%a").'/'.$key['THRESHOLD'].'/'.$key['BASE_COUNT']); ?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Download Base</a></td>
						
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
            </div>
        </div>
   
</div>




<!-- Modal -->
<!--
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('dnd_controllers/department/campaing_update_view', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

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

</script>-->