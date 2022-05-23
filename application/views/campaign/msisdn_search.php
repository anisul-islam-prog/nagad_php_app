<h1 class="heading"><?php if(1)  echo ''; ?>MSISDN Search</h1>
<?php echo form_open('dnd_controllers/campaign/msisdn_report_search',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

    <div class="row">
        
        

        <div class="col-sm-3">
		 <div class="row">       

        <div class="col-sm-1" style="margin-top:5px;">
		880-
		</div>
		 <div class="col-sm-10">
		 <input type="text" name="msisdn" id="msisdn" style="font-size:14px;" value="<?php echo $msisdn ; ?>" class="form-control input-sm" />   
		</div>
		
                    
        </div>
		</div>
		
                    
      <div class="col-sm-1">
		 <input type="submit" name="msisdn_search" value="Search" class="btn btn-sm btn-success" />   
		</div>

           

    </div>
<?php echo form_close(); ?>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<div class="col-sm-12" style="margin-top:20px;">

  <div class="row-fluid">
            <div class="span12">
                 <table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>MSISDN</th>
                        <th>Dept Name</th>
						<th>Campaign ID</th>
                        <th>Campaign Name</th>                       
                        <th>Mask Name</th>
						<th>Broadcast Date</th>
                        <th>Campaign Text</th>                        
                        <th>Segment</th>
                       
                    </tr>
                    </thead>
                    <tbody>
					<?php if($msisdn_info){?>
					<?php foreach ($msisdn_info as $key) { ?>
					
					   <tr>
                      
                        <td><?php echo $key['MSISDN']; ?></td>
						<td><?php echo $key['DNAME']; ?></td>
                        <td class=" vallign-center"><?php echo $key['ID']; ?></td>
                        <td class="vallign-center"><?php echo $key['CAMPAIGN_NAME']; ?></td>  
                        <td><?php echo $key['MSNAME']; ?></td>
						<td><?php echo $key['BROADCAST_DATE']; ?></td>
                        <td ><?php echo $key['CAMPAIGN_TEXT']; ?></td>
						 <td ><?php $now = time(); // or your date as well
							$date = strtotime($key['BROADCAST_DATE']);
							$datediff = $now - $date;

							$datediffInDays=floor($datediff / (60 * 60 * 24));  if($datediffInDays<=0) echo 'Today';else if($datediffInDays<=3) echo 'Last 3 days'; else if($datediffInDays>3)echo 'Before Last 3 days'; else echo 'Undefined Date' ?></td>  
                      
                       
                    </tr>
					
					
					<?php } }else if( count($msisdn_info)==null && $noData=='0'){?>
					
					
					<?php echo 'No record founds'; ?>
					
					
					
					<?php }?>
                    </tbody>
                    </table> 
            </div>
        </div>
   
</div>

<script>
function form_validation(){
    var searchedNumber          = $('#msisdn').val();
   
    if(searchedNumber=='' || searchedNumber==null){
        alert('MSISDN cannot be empty!');
        return false;
    }
   





}
</script>