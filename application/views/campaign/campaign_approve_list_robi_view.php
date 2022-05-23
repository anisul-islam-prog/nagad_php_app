<h1 class="heading"><?php if(1)  echo ''; ?>Approve Robi Campaigns</h1>
<script type="text/javascript">
    $(function () {
        $("#search_date").datepicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker();
        $("#end_time").timepicker();
    });
</script>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<div class="col-sm-12">

  <div class="row-fluid">
  			<?php echo form_open('dnd_controllers/campaign/base_generate_robi',  array('class' => 'form-horizontal', 'onsubmit' => '')); ?>

            <div class="span12" style="font-size:12px;">
                 <table class="table table-bordered table-striped" id="approve_campaign_table" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Team</th>
                        <th>Category</th>
                        <th>Campaign Name</th>
                        <th>Campaign Type</th>
                        <th>Publish Date</th>
                        <th>Campaign ID</th>
                        <th>Brand </th>
                        <th>Masking</th>
                        <th width="300" class="center">SMS</th>
						<th>Bucket Priority</th>
						<th>Dept Priority</th>
                        <th>Campaign Priority</th>			
						<th>Base Count</th>
                        
                        <th>MSISDN Type</th>
						<th>Is Approved</th>
						<th >Previous Base Check</th>
						<th>DND Check</th>
						<th>OBD Check</th>
						<th>Unicode Check</th>
                        
                        <th width="80" class="center">Approval</th> 
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allData as $key) { ?>
                    <tr>
                        <td><?php echo $key['dname']; ?></td>
                        <td><?php if($key['CATEGORY_ID'] == 1){
                            echo "Churn Prevention";
                        }elseif($key['CATEGORY_ID'] == 2){
                            echo "Revenue generating";
                        }elseif($key['CATEGORY_ID'] == 3){
                            echo "Govt. Info";
                        }elseif($key['CATEGORY_ID'] == 4){
                            echo "Promotional";
                        }elseif($key['CATEGORY_ID'] == 5){
                            echo "Notification";
                        }elseif($key['CATEGORY_ID'] == 6){
                            echo "Retailer";
                        }elseif($key['CATEGORY_ID'] == 7){
                            echo "Service";
                        }
                        else{
                            echo "";
                        } ?></td>
                        <td><?php echo $key['CAMPAIGN_NAME']; ?></td>
                        <td><?php if($key['IS_DYNAMIC'] == 1){
                                echo "Dynamic";
                            }elseif($key['IS_CRITICAL'] == 1){
                                echo "Critical";
                            }elseif($key['IS_DYNAMIC'] != 1 && $key['IS_CRITICAL'] != 1){
                                echo "Static";
                            }else{
                                echo "";
                            } ?></td>
                        <td><?php echo $key['BROADCAST_DATE']; ?></td>
                        <td><?php echo 'ROBI'.substr($key['dname'], 0, 3).$key['ID']; ?></td>
                        <td><?php echo $key['BRAND_NAME']; ?></td>
                        <td><?php echo $key['msname']; ?></td>
                        <td><?php echo $key['CAMPAIGN_TEXT']; ?></td>
						<td><?php echo $key['BUCKET_PRIORITY']; ?></td>
						<td><?php echo $key['dept_priority']; ?></td>
                        <td><?php echo $key['PRIORITY']; ?></td>					
												
                        <td><?php echo $key['BASE_COUNT']; ?></td>                       
                        <td><?php echo $key['MSISDN_TYPE']; ?></td>
                        <td><?php if($key['IS_APPROVED']=="1"){ echo 'YES';}else{ echo 'NO';} ?></td>  
						<td ><input type="checkbox" value="1" name="is_previous_base_<?php echo $key['ID']; ?>" id="is_previous_base"  class="form-control input-sm" <?php if($key['IS_PREVIOUS_CHECK']=="1"){ echo 'checked';} ?> <?php if($key['IS_APPROVED']=="1"){ echo 'disabled';} ?> /> </td>

						<td><input type="checkbox" value="1" name="is_dnd_check_<?php echo $key['ID']; ?>" id="is_dnd_check"  class="form-control input-sm" <?php if($key['IS_DND_CHECK']=="1"){ echo 'checked';} ?> <?php if($key['IS_APPROVED']=="1"){ echo 'disabled';} ?> /></td>
						<td> <input type="checkbox" value="1" name="is_obd_check_<?php echo $key['ID']; ?>" id="is_obd_check"  class="form-control input-sm" <?php if($key['IS_OBD_CHECK']=="1"){ echo 'checked';} ?> <?php if($key['IS_APPROVED']=="1"){ echo 'disabled';} ?> />  </td>
						<td ><input type="checkbox" value="1" name="is_unicode_check_<?php echo $key['ID']; ?>" id="is_unicode_check"  class="form-control input-sm" <?php if($key['IS_UNICODE']=="1"){ echo 'checked';} ?> <?php if($key['IS_APPROVED']=="1"){ echo 'disabled';} ?> /> </td>
                        <td width="80" class="center">
						<input type="checkbox" name="approval_cam[]" <?php if($key['IS_APPROVED']=="1"){ echo 'checked';} ?> <?php if($key['IS_APPROVED']=="1"){ echo 'disabled';} ?> class="form-control input-sm"  data-name="<?php echo $key['IS_APPROVED'];?>" id="approval_cam" value="<?php echo $key['ID']; ?>">

                        <!--<a href="<?php echo base_url('dnd_controllers/campaign/campaign_update_view/'.$key['ID'].''); ?>" class="btn btn-xs btn-success" >Approve / Broadcast</a>-->
						
						

                        <!--<button data-id="1" data-dname="Campaign-1" data-active="1" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>-->
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
					
            </div>
			<div class="pull-right" style="margin-top:20px;">
					<button type="submit" class="btn btn-primary">Generate Base</button>
					
					</div>
					<?php echo form_close(); ?>
					
        </div>
   
</div>

<style>
   
	#approve_campaign_table tbody tr td {
		vertical-align:middle;
		
	}  
	
	#approve_campaign_table  {
		 
  
	} 
	
	
</style>

<script>
    $(document).ready(function() {
        $('#approve_campaign_table').DataTable(
		{
			 scrollY:        "330px",
        scrollX:        "300px;",
        scrollCollapse: true,
			fixedColumns: true,
			columnDefs: [
            { width: '50%', targets: 6 }
        ]		
		}
		);
    } );
</script>



