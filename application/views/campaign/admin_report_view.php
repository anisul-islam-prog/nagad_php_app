<h1 class="heading"><?php if(1)  echo ''; ?>SMS Delivery Reports</h1>
<script type="text/javascript">
  
</script>


<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<script type="text/javascript">
    $(function () {
        $(".date").datepicker({minDate: 0});
        

    });
</script>

<div class="clearfix"></div>

<form style="margin-bottom: 3px; margin-left: 15px;" action="<?php echo base_url('dnd_controllers/campaign/admin_reports'); ?>" method="POST">  
    <div class="row">
        <div class="col-xs-1 ">
           Operator: 
        </div>
        <div class="controls col-xs-3 col-md-2">
        	<select name="brand_name" id="brand_name" class="chosen-select form-control">
        		
        		<option value="GP" <?php if($brand_name_selected=='GP') echo 'selected';?>>GP</option>
        		<option value="ROBI" <?php if($brand_name_selected=='ROBI') echo 'selected';?>>ROBI</option>
                        <option value="BL" <?php if($brand_name_selected=='BL') echo 'selected';?>>BANGLALINK</option>
                        <option value="TT" <?php if($brand_name_selected=='TT') echo 'selected';?>>TELETALK</option>
        		
        	</select>
        </div>
        
        <div class="col-xs-2 ">
           Campaign Date (Start): 
        </div>

        <div class="col-xs-4 col-md-2">
           <input name="start_date" type="text" class="form-control date">
        </div>
        
         <div class="col-xs-2 ">
           Campaign Date (End): 
        </div>

        <div class="col-xs-4 col-md-2">
           <input name="end_date"  type="text" class="form-control date">
        </div>
        <div class="col-xs-1">
           <input type="submit" name="search_report" value="Get Report" class="btn btn-sm btn-success" />   
        </div>

       

        
		
		

    </div>
</form>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
			
			
                 <table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Broadcast Date</th>
                        <th>Base Count</th>
                        <th>Target Count</th>                        
			<th>Total Delivered</th>
                        <th>Total Success</th>
                        <th>Total Fail</th>
                        <th>Success Rate</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($report_info)) foreach ($report_info as $key) { ?>
					
                    <tr>
			<td><?php echo $key['CAMPAIGN_DATE']; ?></td>
                        <td><?php echo $key['BASE_COUNT']; ?></td>                    
                        <td><?php echo $key['TARGET_COUNT']; ?></td>              
                        <td><?php echo $key['TOTAL_DELIVERED_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_SUCCESS_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_FAIL_COUNT']; ?></td>
                        <td><?php if($key['TARGET_COUNT']==0)$key['TARGET_COUNT']=1; echo (number_format($key['TOTAL_SUCCESS_COUNT']/$key['TARGET_COUNT']*100,2,'.','')).'%'; ?></td>
                                              
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
					 </div>
        </div>
   
</div>



