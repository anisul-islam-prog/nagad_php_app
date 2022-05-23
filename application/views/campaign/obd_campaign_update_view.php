<h1 class="heading"><?php if (1) echo ''; ?> Update OBD Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<script type="text/javascript">
    $(function () {
        $("#broadcast_date").datepicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker();
        $("#end_time").timepicker();
    });
</script>

<div class="well well-sm">
    <?php echo form_open('dnd_controllers/campaign/update_campaign', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>
    <input type="hidden" name="campaign_id" value="<?php echo $campaignDetails[0]['ID']; ?>" >
    <input type="hidden" name="department" value="<?php echo $campaignDetails[0]['DEPARTMENT_ID']; ?>" >
    <input type="hidden" name="broadcast_start_date" value="<?php echo $campaignDetails[0]['START_DATE']; ?>" >
    <!--    <div class="form-group formSep">
            <label class="col-sm-2 control-label" for="team_name"><span class="req text-danger">*</span>Department Name :</label>
            <div class="col-sm-4">
                <p class="h4 form-control disabled">Some team</p>
            </div>
        </div>-->

    <input type="hidden" name="campaign_id" value="<?php echo $this->uri->segment(4); ?>">
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="campaing_name"><span class="req text-danger">*</span>Campaing Name :</label>
        <div class="col-sm-4">
            <input type="text" name="campaing_name" id="campaing_name"
                   value="<?php echo $campaignDetails[0]['CAMPAIGN_NAME']; ?>" class="form-control"  />
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>


    <!-- <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="department"><span class="req text-danger">*</span> Department :</label>
        <div class="controls col-sm-4">
            <select id="department" name="department" class="chosen-select form-control" <?php if($user_role_id!=1) { echo 'disabled'; } ?>  >
<?php foreach ($department_info as $dkey => $dvalue) { ?>
                    <option value="<?php echo $dvalue['ID'] ?>" <?php if($user_department==$dvalue['ID']){ echo 'selected'; } ?> ><?php echo $dvalue['ID'] ?></option>
<?php } ?>
            </select>
        </div>
    </div> -->

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="brand_name"><span class="req text-danger">*</span> Brand Name :</label>
        <div class="controls col-sm-4">
            <select id="brand_name" name="brand_name" class="chosen-select form-control" >
                <option value="<?php echo $campaignDetails[0]['BRAND_NAME']; ?>"><?php echo $campaignDetails[0]['BRAND_NAME']; ?></option>
                <option value="robi">Robi</option>
                <option value="airtel">Airtel</option>
            </select>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="msisdn_type"><span class="req text-danger">*</span> MSISDN Type :</label>
        <div class="controls col-sm-4">
            <select id="msisdn_type" name="msisdn_type" class="chosen-select form-control" >
                <option value="<?php echo $campaignDetails[0]['MSISDN_TYPE']; ?>"><?php echo $campaignDetails[0]['MSISDN_TYPE']; ?></option>
                <option value="prepaid">Prepaid</option>
                <option value="Postpaid">Postpaid</option>
                <option value="both">Both (Prepaid & Postpaid)</option>
                <option value="evc">EVC</option>
				<option value="employee">Employee</option>
            </select>
        </div>
    </div>
    <?php  //if($campaignDetails[0]['CATEGORY_ID'] == 7 ) echo "OK";?>
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="campaign_category"><span class="req text-danger">*</span> Campaign Category:</label>
        <div class="controls col-sm-4">
            <select id="campaign_category" name="campaign_category" onchange="auto_select_dnd_obd(this.value);" class="chosen-select form-control" >
                <option value="">-- Select Campaign Category --</option>
                <?php $cat = campaign_category_list();  foreach($cat as $key => $value) { ?>

                    <?php if($key == $campaignDetails[0]['CATEGORY_ID']){ ?>
                        <option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php }else{ ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } } ?>
            </select>
        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="masking"><span class="req text-danger">*</span> Masking :</label>
        <div class="controls col-sm-4">
            <select id="masking" name="masking" class="chosen-select form-control" >
                <option value="">-- Select Masking --</option>
                <?php foreach ($masking_info as $mkey => $mvalue) { ?>
                    <?php if($mvalue['ID'] == $campaignDetails[0]['MASKING_ID']){ ?>
                        <option selected value="<?php echo $mvalue['ID']; ?>"><?php echo $mvalue['MASKING_NAME']; ?></option>
                    <?php }else{ ?>
                        <option value="<?php echo $mvalue['ID']; ?>"><?php echo $mvalue['MASKING_NAME']; ?></option>
                    <?php } } ?>
            </select>
        </div>
    </div>
    <?php //mb_strlen($string, 'utf8'); ?>



    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="preference"><span class="req text-danger">*</span> Preference :</label>
        <div class="controls col-sm-4">
            <select id="preference" name="preference" class="chosen-select form-control" >

                <?php $cat = campaign_preference_list();  foreach($cat as $key => $value) { ?>

                    <?php if($key == $campaignDetails[0]['PREFERENCE']){ ?>
                        <option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php }else{ ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } } ?>

            </select>
            <span class="help-inline text-primary">If broadcast quota exceeds</span>
        </div>
    </div>









    <?php //var_dump($selected_base_info) ; ?>
    <div id="base_file_div" class="form-group formSep ">
        <label class="col-sm-2 control-label" for="base"><span class="req text-danger">*</span> Previous Base :</label>
        <div class="controls col-sm-4">
            <?php
            $buckt = array();
            foreach ($base_info as $key) :
                $buckt[$key['ID']] = $key['FILE_NAME'];
            endforeach;

            $old_buckt = $selected_base_info;
            foreach ($selected_base_info as $key) :
                $old_buckt[$key['ID']]= $key['BASE_ID'];
            endforeach;

            ?>


            <?php echo form_multiselect('base[]',$buckt, $old_buckt, 'id="base" class="chosen-select form-control" multiple tabindex="4"'); ?>

        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="threshold_per_day"><span class="req text-danger">*</span>Threshold :</label>
        <div class="col-sm-4">
            <input type="text" name="threshold_per_day" id="threshold_per_day" value="<?php echo $campaignDetails[0]['THRESHOLD']; ?>"  class="form-control"></input>
        </div>
    </div>



    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="remark"><span class="req text-danger">*</span>REMARKS :</label>
        <div class="col-sm-4">
            <textarea name="remark" id="remark"  class="form-control"><?php echo $campaignDetails[0]['REMARKS'] ?></textarea>
        </div>
    </div>


    <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-6">
            <input type="submit" value="Update Campaign" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        </div></div>




    <?php echo form_close(); ?>
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
    });


    function show_file_div(){
        //  var d = <?php if($campaignDetails[0]['IS_PREVIOUS_CHECK']==1){ echo 'checked'; } ?>
        //console.log('d');
        if($('#is_previous_base').prop( "checked" )==true){
            $('#base_file_div').removeClass('hide_div');
        }
        else{
            $('#base_file_div').addClass('hide_div');
        }
    }
    s

    function auto_select_dnd_obd(campaign_category){
        //alert(campaign_category);
    }//auto_select_dnd_obd



    function form_validation(){

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
        if(base=='' || base==null){
            alert('Please select Previous Base File');
            return false;
        }
        // }




    }//form_validation

</script>