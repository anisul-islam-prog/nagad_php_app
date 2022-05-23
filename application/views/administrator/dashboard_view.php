<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="row-fluid">
    <h1 class="heading">SMPP Dashboard</h1>
</div>


<div class="row-fluid row">

    <!-- application environment -->
    <div class="col-xs-5">
        
        <div style="background-color: grey;margin-bottom: 5%;text-align: center; color:white;">SMS Delivery Status</div>
    
        <div class="row" style="border:1px solid black;">
        <div class="col-xs-6 center">
                GP 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: greenyellow;">
        
        In Progress
         
        </div>
        </div>
        
        <div class="row" style="border:1px solid black;">
            <div class="col-xs-6 center">
                ROBI 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: yellow;">
        
        Paused
         
        </div>
        </div>
        
        <div class="row" style="border:1px solid black;">
        <div class="col-xs-6 center">
                BANGLALINK 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: red;">
        
        Stopped
         
        </div>
        </div>
            
        <div class="row" style="border:1px solid black;">
             <div class="col-xs-6 center">
                TELETALK 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: #0CA1E6;">
        
        Completed
         
        </div>
        </div>
    </div>
    <div class="col-xs-2"> 
    </div>
    
    <div class="col-xs-5"> 
        
        
        <div style="background-color: grey;margin-bottom: 5%;text-align: center; color:white;">Database Connection Status</div>
    
        <div class="row" style="border:1px solid black;">
        <div class="col-xs-6 center">
                GP 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: greenyellow;">
        
        Okay
         
        </div>
        </div>
        
        <div class="row" style="border:1px solid black;">
            <div class="col-xs-6 center">
                ROBI 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: greenyellow;">
        
        Okay
         
        </div>
        </div>
        
        <div class="row" style="border:1px solid black;">
        <div class="col-xs-6 center">
                BANGLALINK 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: red;color:white;">
        
        Connection Failed!
         
        </div>
        </div>
            
        <div class="row" style="border:1px solid black;">
             <div class="col-xs-6 center">
                TELETALK 
        </div>
        <div class="col-xs-6" style="text-align: center; background-color: greenyellow;">
        
        Okay
         
        </div>
        </div>
    </div>
    
    </div>

<div class="row-fluid row">
    
    
    <div class="col-xs-12">
        
        <div style="background-color: grey;margin-bottom: 1%;text-align: center; color:white;">Today's Delivery Report</div>
        
        <table class="table table-bordered " id="delivery_status_tbl" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Brand Name</th>
                        <th>Base Count</th>
                        <th>Target Count</th>                        
			<th>Total Delivered</th>
                        <th>Total Success</th>
                        <th>Total Fail</th>
                        <th>Success Rate</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                         <?php if(isset($gp_info)) foreach ($gp_info as $key) { ?>
					
                    <tr class="">
			<td><img src='<?php echo base_url('assets/images/gp-logo.jpg'); ?>' width="90" height='60' /> </td>
                        <td><?php echo $key['BASE_COUNT']; ?></td>                    
                        <td><?php echo $key['TARGET_COUNT']; ?></td>              
                        <td><?php echo $key['TOTAL_DELIVERED_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_SUCCESS_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_FAIL_COUNT']; ?></td>
                        <td><?php if($key['TARGET_COUNT']==0)$key['TARGET_COUNT']=1; echo (number_format($key['TOTAL_SUCCESS_COUNT']/$key['TARGET_COUNT']*100,2,'.','')).'%'; ?></td>
                        
                       
                    </tr>
                    <?php } ?>
                    <?php if(isset($robi_info)) foreach ($robi_info as $key) { ?>
					
                    <tr class="">
			<td><img src='<?php echo base_url('assets/images/robi-logo.jpg'); ?>' width="90" height='60' /> </td>
                        <td><?php echo $key['BASE_COUNT']; ?></td>                    
                        <td><?php echo $key['TARGET_COUNT']; ?></td>              
                        <td><?php echo $key['TOTAL_DELIVERED_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_SUCCESS_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_FAIL_COUNT']; ?></td>
                        <td><?php if($key['TARGET_COUNT']==0)$key['TARGET_COUNT']=1; echo (number_format($key['TOTAL_SUCCESS_COUNT']/$key['TARGET_COUNT']*100,2,'.','')).'%'; ?></td>
                        
                       
                    </tr>
                    <?php } ?>
                    
                      <?php if(isset($bl_info)) foreach ($bl_info as $key) { ?>
					
                    <tr class="">
			<td><img src='<?php echo base_url('assets/images/banglalink_logo.jpg'); ?>' width="90" height='60'  /> </td>
                        <td><?php echo $key['BASE_COUNT']; ?></td>                    
                        <td><?php echo $key['TARGET_COUNT']; ?></td>              
                        <td><?php echo $key['TOTAL_DELIVERED_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_SUCCESS_COUNT']; ?></td>
                        <td><?php echo $key['TOTAL_FAIL_COUNT']; ?></td>
                        <td><?php if($key['TARGET_COUNT']==0)$key['TARGET_COUNT']=1; echo (number_format($key['TOTAL_SUCCESS_COUNT']/$key['TARGET_COUNT']*100,2,'.','')).'%'; ?></td>
                        
                       
                    </tr>
                    <?php } ?>
                    
                      <?php if(isset($tt_info)) foreach ($tt_info as $key) { ?>
					
                    <tr class="">
                        <td><img src='<?php echo base_url('assets/images/teletalk-logo.jpg'); ?>' width="90" height='60' /> </td>
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
    
    
    <style>
   
	#delivery_status_tbl tbody tr td {
		vertical-align:middle;
                text-align: center;
		
	}  
	
	
	
	
</style>
    
    
    
    
    
    

