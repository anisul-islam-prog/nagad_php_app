<h1 class="heading"><?php if(1)  echo ''; ?>Robi Campaign List</h1>
<script type="text/javascript">
    $(function () {
        $("#search_date").datepicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker();
        $("#end_time").timepicker();
    });
</script>
<!--
<form style="margin-bottom: 3px; margin-left: 15px;" action="<?php echo base_url('dnd_controllers/campaign/campaing_list_robi'); ?>" method="POST"> 
    <div class="row">
        <div class="controls col-xs-4 col-md-2">
        	<select name="searchSelect" id="searchSelect" class="chosen-select form-control">
        		<option>Select A Word</option>
        		<option value="DEPARTMENT_ID">Team</option>
        		<option value="CATEGORY_ID">Category</option>
        		<option value="ID">Campaign ID</option>
                <option value="BRAND_NAME">Brand</option>
                <option value="MASKING_ID">Masking</option>
        	</select>
        </div>

        <div class="col-xs-4 col-md-2">
            <input type="text" class="form-control input-sm" name="txtSearch" placeholder="Search" id="txtSearch"/>
        </div>

        <div class="col-sm-2">
            <input type="text" name="search_date" id="search_date" value="<?php echo date('m/d/Y'); ?>" class="form-control input-sm"  />
            
        </div>

        <input type="submit" name="search" value="Search" class="btn btn-sm btn-success" />       

    </div>
</form>
-->

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
                 <table class="table table-bordered table-striped" id="camapaign_list_table_robi" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Team</th>
                        <th>Category</th>
                        <th>Campaign Name</th>
                        <th>Campaign Type</th>
                        <th>Publish Date</th>
						<th>Publish Time (Govt. Info)</th>
                        <th>Campaign ID</th>
                        <th class="center">Brand </th>
                        <th>Masking</th>
                        <th width="300" class="center">SMS</th>
                        <th>Priority</th>
                        <th>Uploaded Base</th>
						<th>Target Count</th>
                        
                        <th>MSISDN Type</th>
                        
						
                        <th width="80" class="center">ACTION</th>
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
						<td class="center vallign-center"><?php echo $key['GOVT_INFO_TIME']; ?></td>
                        <td class=" vallign-center"><?php echo $key['ID']; ?></td>
                        <td class="vallign-center"><?php echo $key['BRAND_NAME']; ?></td> 
                        <td><?php echo $key['msname']; ?></td>
                        <td ><?php echo $key['CAMPAIGN_TEXT']; ?></td>
                        <td><?php echo $key['PRIORITY']; ?></td>
                        <td><?php echo $key['BASE_COUNT']; ?></td>
						<td><?php echo $key['TARGET_COUNT']; ?></td> 						
                        
                        <td ><?php if($key['MSISDN_TYPE']=='0'){ echo "";}else{ echo $key['MSISDN_TYPE']; }?></td> 
                        
                        <td width="80" class="center">

                        <a href="<?php echo base_url('dnd_controllers/campaign/campaign_update_view_robi/'.$key['ID'].'');?>"  data-dynamic="<?php echo $key['IS_DYNAMIC']; ?>"  <?php if($key['IS_APPROVED']==1){ echo 'disabled'; }?>  class="btn btn-sm btn-success <?php if($key['IS_APPROVED']==1){ echo 'deactive_btn'; }?>" >update</a>

                        <!--<button data-id="1" data-dname="Campaign-1" data-active="1" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>-->
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table> 
            </div>
        </div>
   
</div>

<style>
   
	#smpl_tbl tbody tr td {
		vertical-align:middle;
	}  
	
	#smpl_tbl thead tr th {
		vertical-align:middle;
		wordwrap:no-break;
	}	
	.deactive_btn{
		pointer-events: none;
       cursor: default;
	}
	
</style>

<script>
$(document).ready(function() {
        $('#camapaign_list_table_robi').DataTable(
		{
			 scrollY:        "360px",
        scrollX:        "30px;",
        scrollCollapse: true,
			fixedColumns: true,
			columnDefs: [
            { width: '50%', targets: 6 }
        ]		
		});
    } );
</script>




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