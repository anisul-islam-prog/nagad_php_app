<h1 class="heading"><?php if (1) echo ''; ?> Broadcasted Airtel Campaign List</h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="col-sm-12">

  <div class="row-fluid">

            <div class="span12">
                 <table class="table table-bordered table-striped" id="broadcasted_airtel_table" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Campaign ID</th>
						<th>Masking Name</th>
                        <th>Campaign Name</th>						
						<th>Brand Name</th>
						<th width="250">Campaign Text</th>						
						<th>Unique Base Count</th>
						<th>Target Base Count</th>
						<th>Delivered Count</th>
						<th>Download Delivered Base</th>						
						<th>Campaign Status</th>		
						
						<th>Action</th>
                        
                          
                    </tr> 
                    </thead>
                    <tbody>
					
                    <?php foreach ($allData as $key) { ?>
                    <tr> 
                        <td><?php echo 'AT'.substr($key['DNAME'], 0, 3).$key['CAMPAIGN_ID']; ?></td> 
						<td><?php echo $key['MASKING_NAME']; ?></td>
                        <td><?php echo $key['CAMPAIGN_NAME']; ?></td>
						<td><?php echo $key['BRAND_NAME']; ?></td>	
						<td width="270"><?php echo $key['CAMPAIGN_TEXT']; ?></td>	 
						<td><?php echo $key['BASE_COUNT']; ?></td>
						<td><?php echo $key['GENERATED_BASE']; ?></td>  
						<td><?php echo $key['DELIVERED_COUNT']; ?></td>
					    <td><a href="<?php echo site_url('dnd_controllers/campaign/download_delivered_history_when_stopped/'.$key['CAMPAIGN_ID']); ?>"  <?php if($key['IS_PAUSED']!=2) {?>disabled<?php } ?> class="btn btn-success">Download</a></td>	    					
						
						<td><?php if($key['IS_PAUSED']==1) {?>Paused<?php }else if($key['IS_PAUSED']==2){?>Cancelled<?php }else{ if((($key['ALL_COUNT']*1.0)/($key['GENERATED_BASE']*1.0))*100>98 ){ ?>Completed<?php } else { if($key['ALL_COUNT']==0){ ?> In Queue <?php } else{ ?> Broadcasting <?php } } }?></td>	    					
						
						<td width="200" class="center"><a href="<?php echo site_url('dnd_controllers/campaign/resume_or_pause/'.$key['CAMPAIGN_ID'].'/'.$key['IS_PAUSED']); ?>" <?php if($key['IS_PAUSED']==2 || (($key['ALL_COUNT']*1.0)/($key['GENERATED_BASE']*1.0))*100>98 ) {?>disabled<?php } ?> class="btn <?php if($key['IS_PAUSED']==0) {?>btn-warning<?php }else{?>btn-success<?php }?>"><?php if($key['IS_PAUSED']==1) {?>Resume<?php }else{?>Pause<?php }?></a>			
						<a href="<?php echo site_url('dnd_controllers/campaign/stop_campaign/'.$key['CAMPAIGN_ID']); ?>"  <?php if($key['IS_PAUSED']==2 || (($key['ALL_COUNT']*1.0)/($key['GENERATED_BASE']*1.0))*100>98) {?>disabled<?php } ?> class="btn btn-danger">Stop</a></td> 
						                     
						 
                       
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
            </div>
			
					
					
					
        </div>
		
</div>

<script>
  $(document).ready(function() {
        $('#broadcasted_airtel_table').DataTable(
		{
			 scrollY:        "330px",
        scrollX:        "300px;",
        scrollCollapse: true,
			fixedColumns: true,
			columnDefs: [
            { width: '50%', targets: 6 }
        ]		
		});
    } );

</script>