<h1 class="heading"><?php if (1) echo ''; ?> Create New SMS Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<script type="text/javascript">
    $(function () {
        $("#broadcast_date").datepicker({minDate: 1});
		$("#hello").multiDatesPicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker({minDate: 1});
        $("#end_time").timepicker();
        $("#broadcast_date_dyn").datepicker({minDate: 1});
    });
</script>

<div class="well well-sm">
<ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#staicsmstab" aria-controls="staicsmstab" role="tab" data-toggle="tab">Static</a>
            </li>
            <li role="presentation">
                <a href="#dynamicsmstab" aria-controls="dynamicsmstab" role="tab" data-toggle="tab">Dynamic</a>
            </li>
          </ul>
		  
		  <div class="tab-content">
            <!---bucket insert-->
            <div role="tabpanel" class="tab-pane active" id="staicsmstab">
                <hr>
				<?php echo form_open('dnd_controllers/campaign/do_campaing',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

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
                <?php if($user_login_type!='admin'){ ?>
				<?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['DEPARTMENT_ID']; ?>"  <?php if($user_department==$dvalue['DEPARTMENT_ID']){ echo 'selected'; } ?> ><?php echo $dvalue['DNAME']; ?></option>
                <?php } ?>
				<?php }else{?>
								<?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['DEPARTMENT_ID']; ?>"   ><?php echo $dvalue['DNAME']; ?></option>
                <?php } ?>

				<?php } ?>
            </select>
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="department"  id="department" class="form-control" value="<?php echo $user_department ?>" />
				<?php } ?>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="broadcast_date"><span class="req text-danger">*</span>Broadcast Date :</label>
        <div class="col-sm-4">
            <input type="text" name="broadcast_date" id="broadcast_date" value="" class="form-control"  />
					
           
        </div>
    </div>
	
	
	

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="brand_name"><span class="req text-danger">*</span> Brand Name :</label>
        <div class="controls col-sm-4">
            <select id="brand_name" name="brand_name" class="chosen-select form-control" onchange="getDeptAndMaskingAndBaseByBrand();"  <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
								<?php if($user_login_type!='admin'){ ?>
                                <option value="airtel" <?php if($this->session->userdata('user_brand_name')=='airtel'){ echo 'selected'; } ?>>Airtel</option>

                <option value="robi" <?php if($this->session->userdata('user_brand_name')=='robi'){ echo 'selected'; } ?>>Robi</option>
								<?php } else{?>
								<option value="airtel" <?php  echo 'selected';  ?>>Airtel</option>

                <option value="robi" >Robi</option>
								
								<?php } ?>
				
            </select>
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="brand_name"  id="brand_name" class="form-control" value="<?php echo $this->session->userdata('user_brand_name') ?>" />
				<?php } ?>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="msisdn_type"><span class="req text-danger">*</span> MSISDN Type :</label>
        <div class="controls col-sm-4">
            <select id="msisdn_type" name="msisdn_type" class="chosen-select form-control" >
                <option value="">-- Select MSISDN Type --</option>
                <option value="prepaid">Prepaid</option>
                <option value="postpaid">Postpaid</option>
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
        <label class="col-sm-2 control-label" for="campaign_text"><span class="req text-danger">*</span>Campaign Text :</label>
        <div class="col-sm-4">
            <textarea name="campaign_text" rows="6" id="campaign_text"  class="form-control"></textarea>
            <!--<input type="text" name="campaign_text" id="campaign_text" value="" class="form-control"  />-->
            <span class="help-inline">Total: <span id="c_text_count"> 0 </span> Characters</span>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="priority"><span class="req text-danger">*</span>Priority :</label>
        <div class="col-sm-2">
            <!--<p class="h4 form-control disabled"></p>-->
            <input type="text" name="priority"  id="priority" class="form-control" readonly />
        </div>
        <div class="col-sm-2">
            <a class="btn  btn-primary" onclick="return getPriority();">Get Priority</a>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="preference"><span class="req text-danger">*</span> Preference :</label>
        <div class="controls col-sm-4">
            <select id="preference" name="preference" class="chosen-select form-control" >
                
                <option value="ascending">Accending Order </option>
                <option value="arpu_as">ARPU - Accending </option>
                <option value="arpu_ds">ARPU - Decending </option>
                <option value="aon_as">AON - Accending </option>
                <option value="aon_ds">AON - Decending </option>
            </select>
            <span class="help-inline text-primary">If broadcast quota exceeds</span>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="commvet"><span class="req text-danger">*</span> COMMVET :</label>
        <div class="controls col-sm-4">
            <select id="commvet" name="commvet" class="chosen-select form-control" >                
                <option value="Yes">Yes</option>
                <option value="No">No</option>
                <option value="Govt. Info">Govt. Info</option>
            </select>
        </div>
    </div>
    
    

    
		
		<?php if($user_login_type=='admin'){?>
 <div class="form-group formSep">
	
        <label class="col-sm-2 control-label" for="is_test_check">Is Test ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_test_check" id="is_test_check"  class="form-control input-sm" />
        </div>
		  </div>

				<?php } ?>	 
<!--
        <label class="col-sm-2 control-label" for="is_obd_check">Is OBD Check ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_obd_check" id="is_obd_check"  class="form-control input-sm"  />
        </div>
	

        <label class="col-sm-2 control-label" for="is_unicode_check">Is Unicode Check ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_unicode_check" id="is_unicode_check"  class="form-control input-sm" />
        </div>
	

        <label class="col-sm-2 control-label" for="is_previous_base"><span class="req text-danger">*</span>Is Previous Base ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_previous_base" id="is_previous_base" onclick="return show_file_div();" class="form-control input-sm" />
        </div>
		-->	


    
	

    

     

<div id="base_file_div" class="form-group formSep">
        <label class="col-sm-2 control-label" for="base">Uploaded Base :</label>
        <div class="controls col-sm-4">
               <?php
					$campaign_base = array();
					if($base_info)
					{
					foreach ($base_info as $key) :  
						$campaign_base[$key['ID']] = $key['FILE_NAME'].'- ( '.$key['TOTAL_NUMBER'].' )';
					endforeach;
					}
				?>
				<?php echo form_multiselect('base[]',$campaign_base,$campaign_base, 'id="base" class="chosen-select form-control" multiple tabindex="4"'); ?>

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
            <!---bucket insert-->

            <!---bucket mapping-->
                <div role="tabpanel" class="tab-pane" id="dynamicsmstab">
                <hr>
				
			<?php echo form_open_multipart('dnd_controllers/campaign/do_sms_dynamic_campaign',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation_dynamic();')); ?>

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

            <select id="department_dyn" name="department" class="form-control" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> onchange="getBaseByDept();" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
                <?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['DEPARTMENT_ID']; ?>"  <?php if($user_department==$dvalue['DEPARTMENT_ID']){ echo 'selected'; } ?> ><?php echo $dvalue['DNAME']; ?></option>
                <?php } ?>
            </select>
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="department"  id="department_dyn" class="form-control" value="<?php echo $user_department ?>" />
				<?php } ?>
        </div>
    
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="broadcast_date"><span class="req text-danger">*</span>Broadcast Date :</label>
        <div class="col-sm-4">
            <input type="text" name="broadcast_date" id="broadcast_date_dyn" value="" class="form-control"  />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="brand_name_dynamic"><span class="req text-danger">*</span> Brand Name :</label>
        <div class="controls col-sm-4">
		
		 <select id="brand_name_dynamic" name="brand_name_dynamic" class="chosen-select form-control" onchange="getDeptAndMaskingAndBaseByBrand_Dynamic();" <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
                                <option value="airtel" <?php if($this->session->userdata('user_brand_name')=='airtel'){ echo 'selected'; } ?> <?php if($user_login_type!='admin'){ echo 'readonly'; } ?>>Airtel</option>

                <option value="robi" <?php if($this->session->userdata('user_brand_name')=='robi'){ echo 'selected'; } ?> >Robi</option> 
            </select>
			
			<?php if($user_login_type!='admin'){?>
				<input type="hidden" name="brand_name_dynamic"  id="brand_name_dynamic" class="form-control" value="<?php echo $this->session->userdata('user_brand_name') ?>" />
				<?php } ?>
            
        </div>
    </div> 

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="msisdn_type_dyanamic"><span class="req text-danger">*</span> MSISDN Type :</label>
        <div class="controls col-sm-4">
            <select id="msisdn_type_dyanamic" name="msisdn_type_dyanamic" class="chosen-select form-control" >
                <option value="">-- Select MSISDN Type --</option>
                <option value="prepaid">Prepaid</option>
                <option value="postpaid">Postpaid</option>
                <option value="both">Both (Prepaid & Postpaid)</option>
                <option value="retailer">Retailer</option>
				
            </select>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="campaign_category_dynamic"><span class="req text-danger">*</span> Campaign Category:</label>
        <div class="controls col-sm-4">
            <select id="campaign_category_dynamic" name="campaign_category_dynamic" onchange="auto_select_dnd_obd(this.value);" class="chosen-select form-control" >
                <option value="">-- Select Campaign Category --</option>
                <?php $cat = campaign_category_list();  foreach($cat as $key => $value) { ?>
				<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php } ?>
            </select>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="masking_dynamic"><span class="req text-danger">*</span> Masking :</label>
        <div class="controls col-sm-4">
            <select id="masking_dynamic" name="masking_dynamic" class="chosen-select form-control" >
                <option value="">-- Select Masking --</option>
		<?php foreach ($masking_info as $mkey => $mvalue) { ?>
                    <option value="<?php echo $mvalue['ID'] ?>"><?php echo $mvalue['MASKING_NAME'] ?></option>
		<?php } ?>
            </select>
        </div>
    </div>
	
    
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="priority"><span class="req text-danger">*</span>Priority :</label>
        <div class="col-sm-2">
            <!--<p class="h4 form-control disabled"></p>-->
            <input type="text" name="priority"  id="priority_dyn" class="form-control" readonly />
        </div>
        <div class="col-sm-2">
            <a class="btn  btn-primary" onclick="return getPriorityForDynamic();">Get Priority</a>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="preference_dynamic"><span class="req text-danger">*</span> Preference :</label>
        <div class="controls col-sm-4">
            <select id="preference_dynamic" name="preference_dynamic" class="chosen-select form-control" >
               
                <option value="accending">Accending Order </option>
                <option value="apru_ac">ARPU - Accending </option>
                <option value="apru_dc">ARPU - Decending </option>
                <option value="aon_ac">AON - Accending </option>
                <option value="aon_dc">AON - Decending </option>
            </select>
            <span class="help-inline text-primary">If broadcast quota exceeds</span>
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="commvet_dyanamic"><span class="req text-danger">*</span> COMMVET :</label>
        <div class="controls col-sm-4">
            <select id="commvet_dyanamic" name="commvet_dyanamic" class="chosen-select form-control" >
                
                <option value="Yes">Yes</option>
                <option value="No">No</option>
                <option value="Govt. Info">Govt. Info</option>
            </select>
        </div>
    </div>
    
  

<?php if($user_login_type=='admin'){?>
 <div class="form-group formSep">
	
        <label class="col-sm-2 control-label" for="is_test_check">Is Test ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_test_check" id="is_test_check"  class="form-control input-sm" />
        </div>
		  </div>

				<?php } ?>		  
<!--
        <label class="col-sm-2 control-label" for="is_obd_check">Is OBD Check ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_obd_check" id="is_obd_check"  class="form-control input-sm"  />
        </div>
	

        <label class="col-sm-2 control-label" for="is_unicode_check">Is Unicode Check ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_unicode_check" id="is_unicode_check"  class="form-control input-sm" />
        </div>
	

        <label class="col-sm-2 control-label" for="is_previous_base"><span class="req text-danger">*</span>Is Previous Base ? </label>
        <div class="col-sm-1">
            <input type="checkbox" value="1" name="is_previous_base" id="is_previous_base" onclick="return show_file_div();" class="form-control input-sm" />
        </div>
		-->	


  

<div id="base_file_div_dynamic" class="form-group formSep"> 
        <label class="col-sm-2 control-label" for="upload_dynamic_file"><span class="req text-danger">*</span>  Base File Upload :</label>
        <div class="controls col-sm-4">
            <input type="file" required name="upload_file" id="upload_dynamic_file" class="form-control input-sm">
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
                <input type="submit" name="dynamic_campaign_create" value="Add Campaign" class="btn btn-primary btn-lg" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="reset" value="Reset" class="btn btn-default btn-lg" />
            </div>
        </div>


<?php echo form_close(); ?>
	
              
                </div>
            <!---bucket mapping-->
          </div>

</div>
<style>
    .hide_div{visibility: hidden; }
</style>
<script>
    $(document).ready(function() {
        var textarea = $("#campaign_text");
        textarea.keydown(function(event) {
            var numbOfchars = textarea.val();
            var len = numbOfchars.length;
            $("#c_text_count").text(len+1);
        });
		
		 $("#msisdn_type").change(function(event) {
            if($(this).val()=="employee")
				$('#base_file_div').addClass('hide_div');
			else{
				$('#base_file_div').removeClass('hide_div');
			}
				
        });
		$("#msisdn_type_dyanamic").change(function(event) {
            if($(this).val()=="employee")
				$('#base_file_div_dynamic').addClass('hide_div');
			else{
				$('#base_file_div_dynamic').removeClass('hide_div');
			}				
        });
    });


    function show_file_div(){
        if($('#is_previous_base').prop( "checked" )==true){
            $('#base_file_div').removeClass('hide_div');
        }
        else{
            $('#base_file_div').addClass('hide_div');
        }
    }
    
    function getPriority(){	
        
        var department = $('#department').val();
        var brand_name = $('#brand_name').val();
       
        var broadcast_date = $('#broadcast_date').val();
        if(department=='' || department==null){
            alert('Please select Department');
            return false;
        }
        if(broadcast_date=='' || broadcast_date==null){
            alert('Please select Broadcast Date');
            return false;
        }
        //console.log(department);
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getPriority/"; 
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{department:department, broadcast_date:broadcast_date, brand_name:brand_name},
		success: function(data) {
			//console.log(data);
                        $('#priority').val(data.priority);
		}
	});	
}

  function getBaseByDept(){	
         var brand_name = $('#brand_name').val();
        var department = $('#department').val();
       
     $("#base").empty();
	 //$("#base").trigger("chosen:updated");
	 //$(".chzn-results").html(""); 
        //console.log(department);
		//$("#base").chosen("destroy");
		$("#base").trigger("chosen:updated"); 
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getAllBaseByDepartment/";
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{department:department,brand_name:brand_name},
		success: function(data) {
			console.log(data); 
			for(var i=0;i<data.length;i++)
			{
				console.log(data[i]['ID']);
				$("#base").append("<option value='"+data[i]['ID']+"'>"+data[i]['FILE_NAME']+" ("+data[i]['TOTAL_NUMBER']+")</option>"); 
				
 
			}
			$("#base").trigger("chosen:updated"); 	
                   
				   
		}
	});
 
}



function getDeptAndMaskingAndBaseByBrand(){	
        
     var brand_name = $('#brand_name').val();
      
	 $("#department ").empty();
     $("#base").empty();
	 $("#masking").empty();
 
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getDeptAndMaskingAndbaseByBrandName/"; 
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

            console.log(data['base']);
			
			for(var i=0;i<data['base'].length;i++)
			{
				
				$("#base").append("<option value='"+data['base'][i]['ID']+"'>"+data['base'][i]['FILE_NAME']+" ("+data['base'][i]['TOTAL_NUMBER']+")</option>"); 
				
 
			}
			
			for(var i=0;i<data['masking'].length;i++)
			{
				
				$("#masking").append("<option value='"+data['masking'][i]['ID']+"'>"+data['masking'][i]['MASKING_NAME']+"</option>"); 
				
 
			}
			
			$("#masking").trigger("chosen:updated"); 

	
			$("#base").trigger("chosen:updated");
			
                   
				   
		}
	});	
 
}


function getDeptAndMaskingAndBaseByBrand_Dynamic(){	
        
     var brand_name = $('#brand_name_dynamic').val();
      
	 $("#department_dyn ").empty();
     
	 $("#masking_dynamic").empty();

	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getDeptAndMaskingAndbaseByBrandNameForDynamic/"; 
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{brand_name:brand_name},
		success: function(data) {
			console.log(JSON.stringify(data));  
			for(var i=0;i<data['departments'].length;i++)
			{
				
				$("#department_dyn").append("<option value='"+data['departments'][i]['DEPARTMENT_ID']+"'>"+data['departments'][i]['DNAME']+"</option>"); 
				
 
			}
			
		
			
			for(var i=0;i<data['masking'].length;i++)
			{
				
				$("#masking_dynamic").append("<option value='"+data['masking'][i]['ID']+"'>"+data['masking'][i]['MASKING_NAME']+"</option>"); 
				
 
			}
			
			$("#masking_dynamic").trigger("chosen:updated"); 

	
			
			
                   
				   
		}
	});	
 
}

    function getPriorityForDynamic(){

        var department = $('#department_dyn').val();
        var brand_name = $('#brand_name_dynamic').val();

        var broadcast_date = $('#broadcast_date_dyn').val();
        if(department=='' || department==null){
            alert('Please select Department');
            return false;
        }
        if(broadcast_date=='' || broadcast_date==null){
            alert('Please select Broadcast Date');
            return false;
        }
        //console.log(department);
        var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getPriority/";
        //alert(destination);
        $.ajax({
            method:"POST",
            dataType:"json",
            url:destination,
            data:{department:department, broadcast_date:broadcast_date},
            success: function(data) {
                //console.log(data);
                $('#priority_dyn').val(data.priority);
            }
        });
    }//getPriority

function auto_select_dnd_obd(campaign_category){
    //alert(campaign_category);
}//auto_select_dnd_obd


function form_validation(){
    var department          = $('#department').val();
    var broadcast_date      = $('#broadcast_date').val();
    var brand_name          = $('#brand_name').val();
    var msisdn_type         = $('#msisdn_type').val();
    var campaign_category   = $('#campaign_category').val();
    var masking             = $('#masking').val();
    var campaign_text       = $('#campaign_text').val();
    var priority            = $('#priority').val();
    var preference          = $('#preference').val();
    var commvet             = $('#commvet').val();
    var is_dnd_check        = $('#is_dnd_check').val();
    var is_obd_check        = $('#is_obd_check').val();
    var is_previous_base    = $('#is_previous_base').val();
    var base                = $('#base').val();
    //console.log(is_previous_base,base);
    if(department=='' || department==null){
        alert('Please select Department');
        return false;
    }
    if(broadcast_date=='' || broadcast_date==null){
        alert('Please select Broadcast Date');
        return false;
    }
    if(brand_name=='' || brand_name==null){
        alert('Please select Brand Name');
        return false;
    }
    if(msisdn_type=='' || msisdn_type==null){
        alert('Please select MSISDN Type');
        return false;
    }
    if(campaign_category=='' || campaign_category==null){
        alert('Please select Campaign Category');
        return false;
    }
    if(masking=='' || masking==null){
        alert('Please select Masking');
        return false;
    }
    if(broadcast_date=='' || broadcast_date==null){
        alert('Please select Broadcast Date');
        return false;
    }
    if(campaign_text=='' || campaign_text==null){
        alert('Campaign Text field cannot be Empty');
        return false;
    }
    if(priority=='' || priority==null){
        alert('Priority Field cannot be empty');
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
        //if(base=='' || base==null){
           // alert('Please select Previous Base File');
           // return false;
        //}
   // }




}//form_validation


    function form_validation_dynamic(){
        var department          = $('#department_dyn').val();
        var broadcast_date      = $('#broadcast_date_dyn').val();
        var brand_name          = $('#brand_name_dynamic').val();
        var msisdn_type         = $('#msisdn_type_dyanamic').val();
        var campaign_category   = $('#campaign_category_dynamic').val();
        var masking             = $('#masking_dynamic').val();
        var priority            = $('#priority_dyn').val();
        var preference          = $('#preference_dynamic').val();
        var commvet             = $('#commvet_dyanamic').val();
        var is_dnd_check        = $('#is_dnd_check').val();
        var is_obd_check        = $('#is_obd_check').val();
        console.log('masking'+masking);

        if(department=='' || department==null){
            alert('Please select Department');
            return false;
        }
        if(broadcast_date=='' || broadcast_date==null){
            alert('Please select Broadcast Date');
            return false;
        }
        if(brand_name=='' || brand_name==null){
            alert('Please select Brand Name');
            return false;
        }
        if(msisdn_type=='' || msisdn_type==null){
            alert('Please select MSISDN Type');
            return false;
        }
        if(campaign_category=='' || campaign_category==null){
            alert('Please select Campaign Category');
            return false;
        }
        if(masking=='' || masking==null){
            alert('Please select Masking');
            return false;
        }


        if(priority=='' || priority==null){
            alert('Priority Field cannot be empty');
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
        if(base=='' || base==null){
            alert('Please select Previous Base File');
            return false;
        }
        // }




    }//form_validation
    
</script>
