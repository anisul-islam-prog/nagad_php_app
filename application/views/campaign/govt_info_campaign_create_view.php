<h1 class="heading"><?php if (1) echo ''; ?> Create Govt Info Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<script type="text/javascript">
    $(function () {
    
        $(".broadcast_time").timepicker({defaultTime: '06:00 AM'});
        $(".broadcast_date").datepicker();
		
        $("#end_time").timepicker();
        $("#broadcast_date_dyn").datepicker();

    });
</script>

<div class="well well-sm">
        <!---bucket insert-->

            <hr>
            <?php echo form_open('dnd_controllers/campaign/do_govt_info_campaign',  array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

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

                   <select id="department" name="department" class="form-control" <?php if($this->session->userdata('user_login_type')!='admin'){ echo 'disabled'; } ?>  <?php if($user_login_type!='admin'){ echo 'disabled'; } ?> >
                <?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['DEPARTMENT_ID']; ?>"  <?php if($this->session->userdata('user_department')==$dvalue['DEPARTMENT_ID']){ echo 'selected'; } ?> ><?php echo $dvalue['DNAME']; ?></option>
                <?php } ?> 
            </select>
			<?php if($this->session->userdata('user_login_type')!='admin'){?>
				<input type="hidden" name="department"  id="department" class="form-control" value="<?php echo $this->session->userdata('user_department') ?>" />
				<?php } ?>
                </div>
            </div>

            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="broadcast_date"><span class="req text-danger">*</span>Broadcast Date and Time :</label>
                <div class="col-sm-4 ">
				<div class="date_time_govt_div" style="padding-bottom:10px;" >
				<div style="padding-bottom:10px;">
				<input type="text" name="broadcast_date[]" required  placeholder="Enter Date" value="" class="form-control broadcast_date"  /> <input type="text" name="broadcast_time[]" placeholder="Enter Time"  value="" class="form-control broadcast_time"  />
				</div>
				</div>
                    <button type="button" id="addDateAndTime" required class="btn button-success">Add Another Date and Time</button>
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>
            </div>

            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="brand_name"><span class="req text-danger">*</span> Brand Name :</label>
                <div class="controls col-sm-4">
                    <select id="brand_name" name="brand_name" class="chosen-select form-control" onchange="getDeptAndMaskingAndBaseByBrandGovt();" <?php if($this->session->userdata('user_login_type')!='admin'){ echo 'disabled'; } ?> >
                                <option value="airtel" <?php if($this->session->userdata('user_brand_name')=='airtel'){ echo 'selected'; } ?>>Airtel</option>

                <option value="robi" <?php if($this->session->userdata('user_brand_name')=='robi'){ echo 'selected'; } ?>>Robi</option>
				
            </select>
			<?php if($this->session->userdata('user_login_type')!='admin'){?>
				<input type="hidden" name="brand_name"  id="brand_name" class="form-control" value="<?php echo $this->session->userdata('user_brand_name') ?>" />
				<?php } ?>
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


            <div class="form-group ">
                <label class="col-sm-2 control-label" for="campaign_text"><span class="req text-danger">*</span>Campaign Text :</label>
                <div class="col-sm-4">
                    <textarea name="campaign_text" rows="6" id="campaign_text"  class="form-control"></textarea>
                    <!--<input type="text" name="campaign_text" id="campaign_text" value="" class="form-control"  />-->
                    <span class="help-inline">Total: <span id="c_text_count"> 0 </span> Characters</span>
                </div>
				
            </div>

			<div class="form-group formSep">
			<label class="col-sm-2 control-label" for="is_unicode_check">Is Unicode Check ? </label>
                <div class="col-sm-1">
                    <input type="checkbox" value="1" name="is_unicode_check" id="is_unicode_check"  class="form-control input-sm" />
                </div>
			</div>


           

            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="commvet"><span class="req text-danger">*</span> COMMVET :</label>
                <div class="controls col-sm-4">
                    <select id="commvet" name="commvet" class="chosen-select form-control" >
						<option value="Govt. Info">Govt. Info</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        
                    </select>
                </div>
            </div>


            <div id="base_file_div" class="form-group formSep">
                <label class="col-sm-2 control-label" for="base"><span class="req text-danger">*</span> Previous Base :</label>
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
		
		$("#addDateAndTime").click(function(){			
        $(".date_time_govt_div").append('<div style="padding-bottom:10px;"><input type="text" name="broadcast_date[]" placeholder="Enter Date" value="" class="form-control broadcast_date"  /> <input type="text" name="broadcast_time[]" placeholder="Enter Time"  value="" class="form-control broadcast_time"  /></div>');
		$('.broadcast_date').datepicker();
		$('.broadcast_time').timepicker({defaultTime: '06:00 AM'});
    });
	
	
		
		
        var textarea = $("#campaign_text");
        textarea.keydown(function(event) {
            var numbOfchars = textarea.val();
            var len = numbOfchars.length;
            $("#c_text_count").text(len+1);
        });
    });
	



function getDeptAndMaskingAndBaseByBrandGovt(){	
        
     var brand_name = $('#brand_name').val();
      
	 $("#department ").empty();
     
	 $("#masking").empty();
	 
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getDeptAndMaskingAndbaseByBrandNameGovt/"; 
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
			
			
			
			for(var i=0;i<data['masking'].length;i++)
			{
				
				$("#masking").append("<option value='"+data['masking'][i]['ID']+"'>"+data['masking'][i]['MASKING_NAME']+"</option>"); 
				
 
			}
			
			$("#masking").trigger("chosen:updated"); 

	
			
			
                   
				   
		}
	});	
 
}


    function show_file_div(){
        if($('#is_previous_base').prop( "checked" )==true){
            $('#base_file_div').removeClass('hide_div');
        }
        else{
            $('#base_file_div').addClass('hide_div');
        }
    }



    function form_validation(){
        var department          = $('#department').val();
        var broadcast_date      = $('#broadcast_date').val();
        var brand_name          = $('#brand_name').val();
        var msisdn_type         = $('#msisdn_type').val();
       
        var masking             = $('#masking').val();
        var campaign_text       = $('#campaign_text').val();

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
        
        if(brand_name=='' || brand_name==null){
            alert('Please select Brand Name');
            return false;
        }
       
       
        if(masking=='' || masking==null){
            alert('Please select Masking');
            return false;
        } 
        
        if(campaign_text=='' || campaign_text==null){
            alert('Campaign Text field cannot be Empty');
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
