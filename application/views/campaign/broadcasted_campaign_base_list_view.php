<h1 class="heading"><?php if (1) echo ''; ?> Broadcasted Campaign Base List</h1>

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
                 <table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Campaign ID</th>
                        <th>Campaign Name</th>
						<th>Base File</th>
						<th>Base Count</th>
						<th>Base Status</th>
                        
                          
                    </tr>
                    </thead>
                    <tbody>
					
                    <?php foreach ($allData as $key) { ?>
                    <tr>
                        <td><?php echo $key['CAMPAIGN_ID']; ?></td>
                        <td><?php echo $key['CAMPAIGN_NAME']; ?></td>
						<td><?php if($key['BASE_FILE_NAME']){ echo $key['BASE_FILE_NAME'];} else{ echo "Employee Base";};?></td>
						<td><?php echo $key['BASE_COUNT']; ?></td>
						
						<td>Success</td>
						
                        
						
                       
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
            </div>
			
					
					
					
        </div>
		
</div>

<script>

</script>