<h1 class="heading"><?php if (1) echo ''; ?> Broadcast Robi Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="col-sm-12">

  <div class="row-fluid">
    			<?php echo form_open('dnd_controllers/campaign/update_campaign_global_priority_robi',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_priority_validation();')); ?>

            <div class="span12">
                 <table class="table table-bordered table-striped" id="broadcast_robi_table" style="margin-bottom: 0;"> 
                    <thead>
                    <tr>
                        <th>Campaign ID</th>
                        <th>Campaign Name</th>
						<th>Department Name</th>
						<th>Masking</th>
						<th width="600">Text</th>
						<th>Uploaded Base Count</th>
						<th>Target</th>
						<th>Action</th>
						<th></th>
                        <th>Global Priority</th>
                         
                    </tr>
                    </thead>
                    <tbody>
					<!--<?php $counter=1; ?>-->
                    <?php foreach ($allData as $key) { ?>
                    <tr>
                        <td><?php echo 'ROBI'.substr($key['DNAME'], 0, 3).$key['CAMPAIGN_ID']; ?></td>
                        <td><?php echo $key['CAMPAIGN_NAME']; ?></td>
						<td><?php echo $key['DNAME']; ?></td>
						<td><?php echo $key['MSNAME']; ?></td>						
						<td ><?php echo $key['CAMPAIGN_TEXT']; ?></td>
						<td><?php echo $key['BASE_COUNT']; ?></td>
						<td><?php echo $key['GENERATED_BASE']; ?></td>
						<td><a type="button" href="<?php echo site_url('dnd_controllers/campaign/start_download_process_robi/'.$key['CAMPAIGN_ID']); ?>"  <?php if($key['DOWNLOAD_TARGET']=='1') { echo 'disabled ';}?> style="font-size:12px;" class="btn btn-success pull-right <?php if($key['DOWNLOAD_TARGET']=='1') { echo 'deactive_btn ';}?>" name="download_process" id="download_process" > Start Download Process</a></td>
						<!--<td><a type="button" href="<?php echo site_url('dnd_controllers/campaign/download_target_file/'.$key['CAMPAIGN_ID']); ?>" style="font-size:12px;" class="btn btn-success pull-right" name="download_target" id="download_target" > Download Target Base</a></td> -->
						<input type="hidden" name='campaign_ids[]'  class="text-center" value="<?php echo $key['CAMPAIGN_ID']; ?>" />
                        <td><a type="button" href="<?php echo 'https://10.101.65.72/download_files_robi/target_base'.$key['CAMPAIGN_ID'].'.txt'?>" <?php if($key['DOWNLOAD_TARGET']=='0') { echo 'disabled ';}?> style="font-size:12px;" class="btn btn-success pull-right <?php if($key['DOWNLOAD_TARGET']=='0') { echo 'deactive_btn ';}?>" name="download_process" id="download_process" > Download Target Base</a></td>
						<td><input type="number" name='global_priority[]' min="1" class="text-center global_priority" required data-campaignID="<?php echo $key['CAMPAIGN_ID']; ?>" value="<?php echo $key['PRIORITY']; ?>" /></td>
						<!--<?php $counter++; ?>-->
                       
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
            </div>
			<div class="" style="margin-top:20px;margin-bottom:20px; clear:right; ">
					<button type="submit" style="clear:right;" class="btn btn-primary pull-right">Update Global Priority</button> 
					
					</div>
					
					<?php echo form_close(); ?>
					
					
        </div>
		<div class="" style="margin-top:20px!important;clear:right;"> 
					<a type="button" href="<?php echo site_url('dnd_controllers/campaign/start_broadcast_robi'); ?>" style="clear:left;margin-top:20px;" class="btn btn-success pull-right" name="broadcast_campaign" id="broadcast_campaign" <?php if($broadcast_btn_status=='false') { echo 'disabled';} else{ echo '';} ?> > Broadcast Campaign</a>
					
					</div>
   
</div> 

<script>
function form_priority_validation(){
   var valid = true;

        $.each($('.global_priority'), function (index1, item1) {

            $.each($('.global_priority').not(this), function (index2, item2) {

                if ($(item1).val() == $(item2).val()) {
                    alert('Global priority cannot be same of multiple campaign.');
                    valid = false;
                }

            });
        });

        return valid;



}

</script>


<script>
    $(document).ready(function() {
        $('#broadcast_robi_table').DataTable(
		 
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