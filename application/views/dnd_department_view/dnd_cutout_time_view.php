<h1 class="heading"><?php if (1) echo ''; ?> Cutout Time Management </h1>

<?php
if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
}
?>
<?php
if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
}
?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<script type="text/javascript">
    $(function () {
        $("#campaign_creation_start").timepicker({defaultTime: '<?php echo $cutout_times->CAMPAIGN_CREATION_START ?>'});
        $("#campaign_creation_ends").timepicker({defaultTime: '<?php echo $cutout_times->CAMPAIGN_CREATION_ENDS ?>'});
        
        $("#campaign_cancellation_start").timepicker({defaultTime: '<?php echo $cutout_times->CAMPAIGN_CANCELLATION_START ?>'});
        $("#campaign_cancellation_ends").timepicker({defaultTime: '<?php echo $cutout_times->CAMPAIGN_CANCELLATION_ENDS ?>'});
        
        $("#weekday_broadcast_start").timepicker({defaultTime: '<?php echo $cutout_times->BRDCST_WEEKDAY_START ?>'});
        $("#weekday_broadcast_ends").timepicker({defaultTime: '<?php echo $cutout_times->BRDCST_WEEKDAY_ENDS ?>'});
        $("#weekend_broadcast_start").timepicker({defaultTime: '<?php echo $cutout_times->BRDCST_WEEKEND_START ?>'});
        $("#weekend_broadcast_ends").timepicker({defaultTime: '<?php echo $cutout_times->BRDCST_WEEKEND_ENDS ?>'});
        
        
    });
</script>

<?php
//echo $cutout_times->CAMPAIGN_CREATION_START; 
//var_dump($cutout_times); die();
?>


<div class="well well-sm">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#campaign_ceration" aria-controls="campaign_ceration" role="tab" data-toggle="tab">Campaign Creation</a>
        </li>
        <li role="presentation">
            <a href="#campaign_cancellation" aria-controls="profile" role="tab" data-toggle="tab">Campaign Update</a>
        </li>
        <li role="presentation">
            <a href="#weekday_campaign_broadcast" aria-controls="profile" role="tab" data-toggle="tab">Campaign Broadcast</a>
        </li>
    <!--    <li role="presentation">
            <a href="#weekend_campaign_broadcast" aria-controls="profile" role="tab" data-toggle="tab">Weekend Campaign Broadcast</a>
        </li>
     -->   
    </ul>

    <br/>

    <!-- Tab panes -->
    <div class="tab-content">

        <!--CAMPAIGN CREATION CUTOUT TIME-->
        <div role="tabpanel" class="tab-pane active" id="campaign_ceration">
            <?php echo form_open_multipart('dnd_controllers/manage_cutout_times/update_time', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

            <input type="hidden" name="update_category" value="campaign_creation">


            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="campaign_creation_start">Campaign Creation Start:</label>
                <div class="col-sm-2">
                    <input type="text" required name="campaign_creation_start" id="campaign_creation_start"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <label class="col-sm-2 control-label" for="campaign_creation_ends">Campaign Creation Ends:</label>
                <div class="col-sm-2">
                    <input type="text" required name="campaign_creation_ends" id="campaign_creation_ends"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <div class="col-sm-offset-4">
                    <button type="submit" name="add_dnd" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Update</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
        
        
        <!--CAMPAIGN cancellation CUTOUT TIME-->
        <div role="tabpanel" class="tab-pane" id="campaign_cancellation">
            <?php echo form_open_multipart('dnd_controllers/manage_cutout_times/update_time', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

            <input type="hidden" name="update_category" value="campaign_edit">


            <div class="form-group formSep">
            <!--    <label class="col-sm-2 control-label" for="campaign_cancellation_start">Campaign Cancellation Start:</label>
                <div class="col-sm-2">
                    <input type="text" required name="campaign_cancellation_start" id="campaign_cancellation_start"  class="form-control input-sm" />
                    <span class="help-inline">Inline help text</span>
                </div>
        -->
                <label class="col-sm-4 control-label" for="campaign_cancellation_ends">Campaign Cancellation Ends:</label>
                <div class="col-sm-2">
                    <input type="text" required name="campaign_cancellation_ends" id="campaign_cancellation_ends"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <div class="col-sm-offset-4">
                    <button type="submit" name="" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Update</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>

        <!--CAMPAIGN Weekday Broadcast Schedule-->
        <div role="tabpanel" class="tab-pane" id="weekday_campaign_broadcast">
            <h2> Weekday </h2>
            <?php echo form_open_multipart('dnd_controllers/manage_cutout_times/update_time', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

            <input type="hidden" name="update_category" value="weekday_campaign_broadcast">


            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="weekday_broadcast_start">Broadcast Start:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekday_broadcast_start" id="weekday_broadcast_start"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <label class="col-sm-2 control-label" for="weekday_broadcast_ends">Broadcast Ends:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekday_broadcast_ends" id="weekday_broadcast_ends"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <div class="col-sm-offset-4">
                    <button type="submit" name="" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Update</button>
                </div>
            </div>
            
            <h4 class="text-danger">Note: Saturday to Thursday is Considered as <strong>Week Day </strong></h4>

            <?php echo form_close(); ?>
            <br/>
            <h2> Weekend </h2>
            <?php echo form_open_multipart('dnd_controllers/manage_cutout_times/update_time', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

            <input type="hidden" name="update_category" value="weekend_campaign_broadcast">


            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="weekend_broadcast_start">Broadcast Start:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekend_broadcast_start" id="weekend_broadcast_start"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <label class="col-sm-2 control-label" for="weekend_broadcast_ends">Broadcast Ends:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekend_broadcast_ends" id="weekend_broadcast_ends"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <div class="col-sm-offset-4">
                    <button type="submit" name="" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Update</button>
                </div>
            </div>
            
            <h4 class="text-danger">Note: Only Friday is Considered as <strong>Weekend </strong></h4>

            <?php echo form_close(); ?>
            
        </div>
        
        <!--CAMPAIGN Weekend Broadcast Schedule-->
        <div role="tabpanel" class="tab-pane" id="weekend_campaign_broadcast">
            <?php echo form_open_multipart('dnd_controllers/manage_cutout_times/update_time', array('class' => 'form-horizontal', 'onsubmit' => 'return form_validation();')); ?>

            <input type="hidden" name="update_category" value="weekend_campaign_broadcast">


            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="weekend_broadcast_start">Broadcast Start:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekend_broadcast_start" id="weekend_broadcast_start"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <label class="col-sm-2 control-label" for="weekend_broadcast_ends">Broadcast Ends:</label>
                <div class="col-sm-2">
                    <input type="text" required name="weekend_broadcast_ends" id="weekend_broadcast_ends"  class="form-control input-sm" />
                    <!--<span class="help-inline">Inline help text</span>-->
                </div>

                <div class="col-sm-offset-4">
                    <button type="submit" name="" value="include" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Update</button>
                </div>
            </div>
            
            <h4 class="text-danger">Note: Only Friday is Considered as <strong>Weekend </strong></h4>

            <?php echo form_close(); ?>
        </div>

    </div>



</div>
