<h1 class="heading"><?php if (1) echo ''; ?> Create New OBD Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<script type="text/javascript">
    $(function () {
        $("#broadcast_start_date").datepicker();
		$("#broadcast_end_date").datepicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker();
        $("#end_time").timepicker();
    });
</script>

<div class="well well-sm">

		  
		 
				<?php echo form_open('dnd_controllers/campaign/do_obd_campaign',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

<!--    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="team_name"><span class="req text-danger">*</span>Department Name :</label>
        <div class="col-sm-4">
            <p class="h4 form-control disabled">Some team</p>
        </div>
    </div>-->


    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="campaing_name"><span class="req text-danger">*</span>Campaing Name :</label>
        <div class="col-sm-4">
            <input type="text" name="campaing_name" id="campaing_name" value="" class="form-control"  />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>
<?php //var_dump($department_info);?>
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="department"><span class="req text-danger">*</span> Department :</label>
        <div class="controls col-sm-4">
		<select id="department" name="department" class="form-control" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> onchange="getBaseByDept();" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
                <?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['DEPARTMENT_ID']; ?>"  <?php if($user_department==$dvalue['DEPARTMENT_ID']){ echo 'selected'; } ?> ><?php echo $dvalue['DNAME']; ?></option>
                <?php } ?>
            </select>
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="department"  id="department" class="form-control" value="<?php echo $user_department ?>" />
				<?php } ?>
        </div>
    </div>

    <div class="form-group ">
        <label class="col-sm-2 control-label" for="broadcast_start_date"><span class="req text-danger">*</span>Broadcast Start Date:</label>
        <div class="col-sm-4">
            <input type="text" name="broadcast_start_date" id="broadcast_start_date" value="" class="form-control"  />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>

    </div>
    <div class="form-group formSep">

        <label class="col-sm-2 control-label" for="broadcast_end_date"><span class="req text-danger">*</span>Broadcast End Date:</label>
        <div class="col-sm-4">
            <input type="text" name="broadcast_end_date" id="broadcast_end_date" value="" class="form-control"  />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="brand_name"><span class="req text-danger">*</span> Brand Name :</label>
        <div class="controls col-sm-4">
          <select id="brand_name" name="brand_name" class="chosen-select form-control" onchange="getDeptByBrand();" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
                                <option value="airtel" <?php if($this->session->userdata('user_brand_name')=='airtel'){ echo 'selected'; } ?>>Airtel</option>

                <option value="robi" <?php if($this->session->userdata('user_brand_name')=='robi'){ echo 'selected'; } ?>>Robi</option>
				
            </select>
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="brand_name"  id="brand_name" class="form-control" value="<?php echo $this->session->userdata('user_brand_name') ?>" />
				<?php } ?>
        </div>
    </div>
<!--
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="msisdn_type"><span class="req text-danger">*</span> MSISDN Type :</label>
        <div class="controls col-sm-4">
            <select id="msisdn_type" name="msisdn_type" class="chosen-select form-control" >
                <option value="">-- Select MSISDN Type --</option>
                <option value="prepaid">Prepaid</option>
                <option value="Postpaid">Postpaid</option>
                <option value="both">Both (Prepaid & Postpaid)</option>
                <option value="retailer">Retailer</option>
				<option value="employee">Employee</option>
            </select>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="campaign_category"><span class="req text-danger">*</span> Campaign Category:</label>
        <div class="controls col-sm-4">
            <select id="campaign_category" name="campaign_category" onchange="auto_select_dnd_obd(this.value);" class="chosen-select form-control" >
                <option value="">-- Select Campaign Category --</option>
                <?php $cat = campaign_category_list();  foreach($cat as $key => $value) { ?>
				<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php } ?>
            </select>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="masking"><span class="req text-danger">*</span> Masking :</label>
        <div class="controls col-sm-4">
            <select id="masking" name="masking" class="chosen-select form-control" >
                <option value="">-- Select Masking --</option>
		<?php foreach ($masking_info as $mkey => $mvalue) { ?>
                    <option value="<?php echo $mvalue['ID'] ?>"><?php echo $mvalue['MASKING_NAME'] ?></option>
		<?php } ?>
            </select>
        </div>
    </div> 
	    
  
    

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="preference"><span class="req text-danger">*</span> Preference :</label>
        <div class="controls col-sm-4">
            <select id="preference" name="preference" class="chosen-select form-control" >
                <option value="accending">Accending Order </option>
                <option value="apru_ac">ARPU - Accending </option>
                <option value="apru_dc">ARPU - Decending </option>
                <option value="aon_ac">AON - Accending </option>
                <option value="aon_dc">AON - Decending </option>
            </select>
            <span class="help-inline text-primary">If broadcast quota exceeds</span>
        </div>
    </div>
-->

<div id="base_file_div" class="form-group ">
        <label class="col-sm-2 control-label" for="base"><span class="req text-danger">*</span> Uploaded Base :</label>
        <div class="controls col-sm-4">		
               <?php
					$campaign_base = array();
					foreach ($base_info as $key) :  
						$campaign_base[$key['ID']] = $key['FILE_NAME'];
					endforeach;
				?>
				<?php echo form_multiselect('base[]',$campaign_base,$campaign_base, 'id="base" class="chosen-select form-control" multiple tabindex="4"'); ?>

        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="threshold_per_day"><span class="req text-danger">*</span>Threshold :</label>
        <div class="col-sm-4">
            <input type="text" name="threshold_per_day" id="threshold_per_day" placeholder="Per Day Value" class="form-control"></input>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="is_obd_priority">Priority Over SMS ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_obd_priority" id="is_obd_priority"  class="form-control input-sm" checked=""  />
        </div>

    </div>



    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="remark">REMARKS :</label>
        <div class="col-sm-4">
            <textarea name="remark" id="remark"  class="form-control"></textarea>
        </div>
    </div>

        <div class="form-group form-actions">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Campaign" class="btn btn-primary btn-lg" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="reset" value="Reset" class="btn btn-default btn-lg" />
            </div>
        </div>


<?php echo form_close(); ?>
               
          

</div>
<style>
    .hide_div{visibility: hidden; }
</style>
<script>
    $(document).ready(function() {

    });


    function show_file_div(){
        if($('#is_previous_base').prop( "checked" )==true){
            $('#base_file_div').removeClass('hide_div');
        }
        else{
            $('#base_file_div').addClass('hide_div');
        }
    }
    

function auto_select_dnd_obd(campaign_category){
    //alert(campaign_category);
}//auto_select_dnd_obd



function getDeptByBrand(){	
        
     var brand_name = $('#brand_name').val();
      
	 $("#department ").empty();
     //$("#base").empty();
	
 
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getDeptByBrandName/"; 
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{brand_name:brand_name},
		success: function(data) {
			
			for(var i=0;i<data['departments'].length;i++)
			{
				
				$("#department").append("<option value='"+data['departments'][i]['DEPARTMENT_ID']+"'>"+data['departments'][i]['DNAME']+"</option>"); 
				
 
			}
			/*
			for(var i=0;i<data['base'].length;i++)
			{
				
				$("#base").append("<option value='"+data['base'][i]['ID']+"'>"+data['base'][i]['FILE_NAME']+"</option>"); 
				
 
			}
			*/
			
			
                   
				   
		}
	});	
 
}

function form_validation(){
    var department          = $('#department').val();
    var broadcast_start_date      = $('#broadcast_start_date').val();
    var broadcast_end_date      = $('#broadcast_end_date').val();
    var brand_name          = $('#brand_name').val();
    var msisdn_type         = $('#msisdn_type').val();
    var campaign_category   = $('#campaign_category').val();
    var masking             = $('#masking').val();
    var preference          = $('#preference').val();
    var threshold           = $('#threshold_per_day').val();



    var base                = $('#base').val();

    if(department=='' || department==null){
        alert('Please select Department');
        return false;
    }
    if(broadcast_start_date=='' || broadcast_start_date==null){
        alert('Please select Broadcast Date');
        return false;
    }
    if(broadcast_end_date=='' || broadcast_end_date==null){
        alert('Please select Broadcast Date');
        return false;
    }
    if(brand_name=='' || brand_name==null){
        alert('Please select Brand Name');
        return false;
    }
   
  
    if(threshold=='' || threshold==null){
        alert('Threshold cannot be empty');
        return false;
    }


    /*if(preference=='' || preference==null){
        alert('Please select Preference');
        return false;
    }*/
    /*if(commvet=='' || commvet==null){
        alert('Please select COMMVET');
        return false;
    }*/
   // if($('#is_previous_base').prop( "checked" )==true){
        if(threshold=='' || threshold==null){
            alert('Please select Previous Base File');
            return false;
        }
   // }
    
    
    
    
}//form_validation
    
</script>
