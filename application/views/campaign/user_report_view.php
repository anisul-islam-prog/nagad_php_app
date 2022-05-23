<h1 class="heading"><?php if(1)  echo ''; ?>User Reports</h1>
<script type="text/javascript">
  
</script>


<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<form style="margin-bottom: 3px; margin-left: 15px;" action="<?php echo base_url('dnd_controllers/campaign/user_reports'); ?>" method="POST"> 
    <div class="row">
        <div class="controls col-xs-4 col-md-2">
        	<select name="brand_name" id="brand_name" class="chosen-select form-control">
        		
        		<option value="airtel" <?php if($brand_name_selected=='airtel') echo 'selected';?>>Airtel</option>
        		<option value="robi" <?php if($brand_name_selected=='robi') echo 'selected';?>>Robi</option>
        		
        	</select>
        </div>

        <div class="col-xs-4 col-md-2">
            <select name="report_duration" id="report_duration" class="chosen-select form-control">
        		
        		<option value="daily" <?php if($duration_selected=='daily') echo 'selected';?>>Daily Reports</option>
				<option value="weekly" <?php if($duration_selected=='weekly') echo 'selected';?>>Weekly Reports</option>
        		<option value="monthly" <?php if($duration_selected=='monthly') echo 'selected';?>>Monthly Reports</option>
        		
        	</select>
        </div>

        <div class="col-sm-4">
		<select name="report_type" id="report_type" class="chosen-select form-control">        		
        		<option value="campaign" <?php if($type_selected=='campaign') echo 'selected';?>>Total SMS broadcasted</option>
				<option value="customer" <?php if($type_selected=='customer') echo 'selected';?>>Unique Customers covered & Maximum number of SMS broadcasted to a single customer</option>
        		       		
        	</select>
            
        </div>

        <input type="submit" name="search_user_report" value="Get Report" class="btn btn-sm btn-success" /> 
		<input type="submit" name="export_report" value="Export Report" class="btn btn-sm btn-success" /> 		

    </div>
</form>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
			
			<?php if($report_type=="customer"){?>
                 <table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Date</th>
						<th>Brand Name</th>                       
                        <th>Segment</th>
						<th>ARPU Segment wise breakdown</th>
						<th>Total Sub Count Uploaded</th>                        
						<th>TG count in generated file</th>
						<th>SMS Delivery Count</th>
						<th>Total Unique Customers covered</th>
						<th>Max Broadcast sent to single customer</th>	  
                        
                    </tr>
                    </thead>
                    <tbody>
					
					
					   <?php if($report_info){ foreach ($report_info as $row) { ?>
					<tr>
						<td class='vertical-center' rowspan="<?=count($row)?>" width="100"><?php echo $row[0]['BROADCAST_DATE']; ?></td> 
						<td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['OPERATOR_NAME']; ?></td>
                        
						
						 <?php foreach( $row as $key ){ ?>
						 
						<td><?php echo $key['SEGMENT_TYPE']; ?></td>  
						<td><?php echo $key['SEGMENT_NAME']; ?></td>
						<td><?php echo $key['UPLOAD_COUNT']; ?></td>						
                       
                        <td><?php echo $key['GEN_COUNT']; ?></td>
						<td><?php echo $key['SMS_DELIVERED_COUNT']; ?></td>						
                       
                        <td><?php echo $key['UNIQUE_CUSTOMER_COUNT']; ?></td>
						<td><?php echo $key['MAX_SMS']; ?></td> 
						  </tr><tr>
						 <?php }?>
                        
                    
						
                        
                       
                    </tr>

                    <?php }}?>
					
					
					
					  
                        
                    
						
                        
                       
                   
					
                   
                    </tbody>
                    </table>
				
					
					<?php } else{?>
					<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
						<th>Date</th>
						<th>Brand Name</th>
                        <th>Campaign Name</th>
						<th>Campaign Creator</th>
                        <th>Segment</th>
						<th>ARPU Segment wise breakdown</th>
						<th>Total Sub Count Uploaded</th>                        
						<th>TG count in generated file</th>
						<th>SMS Delivery Count</th>
						<th>SMS Delivery %</th>
						
										
											                        
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($report_info){ foreach ($report_info as $row) { ?>
					<tr>
						<td class='vertical-center' rowspan="<?=count($row)?>" width="100"><?php echo $row[0]['BROADCAST_DATE']; ?></td> 
						<td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['OPERATOR_NAME']; ?></td>
                        <td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['CAMPAIGN_NAME']; ?></td>
						<td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['CREATED_BY']; ?></td>
						<td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['SEGMENT_TYPE']; ?></td>
						 <?php foreach( $row as $key ){ ?>
						 
						  
						<td><?php echo $key['SEGMENT_NAME']; ?></td>
						<td><?php echo $key['UPLOAD_COUNT']; ?></td>						
                       
                        <td><?php echo $key['GEN_COUNT']; ?></td>
						<td><?php echo $key['DELIVERED_COUNT']; ?></td>						
                       
                        <td><?php echo $key['DELIVERED_SUCESSESS_PERCENT'].'%'; ?></td> 
						  </tr><tr>
						 <?php }?>
                        
                    
						
                        
                       
                    </tr>

                    <?php }}else{} ?>
					
					 <?php if($report_info_total){ foreach ($report_info_total as $row) { ?>
					<tr>
						 
						<td class='vertical-center center'  colspan="4" rowspan="<?=count($row)?>"><?php echo $brand_name_total; ?></td>
						<td class='vertical-center' rowspan="<?=count($row)?>"><?php echo $row[0]['SEGMENT_TYPE']; ?></td> 
                        
						
						 <?php foreach( $row as $key ){ ?>
						 
						 
						<td><?php echo $key['SEGMENT_NAME']; ?></td>
						<td><?php echo $key['UPLOAD_COUNT']; ?></td>						
                       
                        <td><?php echo $key['GEN_COUNT']; ?></td>
						<td><?php echo $key['DELIVERED_COUNT']; ?></td>						
                       
                        <td><?php  if($key['GEN_COUNT']==0)$key['GEN_COUNT']=1; echo ($key['DELIVERED_COUNT']/$key['GEN_COUNT']*100).'%'; ?></td>
						
						  </tr><tr>
					 <?php }?>
					  </tr>

                    <?php }}?>
                    </tbody>
                    </table>
					<?php }?>
            </div>
        </div>
   
</div>

<style>
.vertical-center{
	vertical-align:middle !important;
}
</style>


