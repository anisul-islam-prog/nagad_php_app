
<h1 class="heading"> Add Default Offer</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">
<?php echo form_open('administrator/offer/add_default_offer', array('class' => 'form-inline', 'onsubmit'=>'return report_1_validation();')); ?>
    
    <div class="form-group col-sm-2"></div>
     
    <div class="form-group">
        <input type="text" name="offer_id" id="offer_id_search" value="<?php if(isset($offer_id)){ echo $offer_id; } ?>" placeholder="Offer ID" class="form-control input-sm"  />
    </div>
     
    
    
    <div class="form-group">
       <input type="submit" name="search" value="Search" class="btn btn-primary btn-sm" />&nbsp;
    </div>
    
        
   
    
   
  
<?php echo form_close(); ?>

    
</div>


<?php echo form_open('administrator/offer/do_add_default_offer', array('class' => 'form-horizontal')); ?>


     <script type="text/javascript">
        $(function(){
            $("#start_date").datepicker();
            $("#start_time").timepicker();
            $("#end_date").datepicker();
            $("#end_time").timepicker();
        });
    </script>
    
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="offer_id"><span class="req text-danger">*</span> Offer ID</label>
        <div class="controls col-sm-4">
            <input type="text" name="offer_id" value="<?php if(isset($offer_id)){ echo $offer_id; } ?>" readonly="readonly" id="offer_id" required="required" class="input-sm form-control">
            
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="offer_tittle"><span class="req text-danger">*</span> Offer Tittle</label>
        <div class="controls col-sm-4">
            <input type="text" name="offer_tittle" value="<?php if(isset($offer_tittle)){ echo $offer_tittle; } ?>" readonly="readonly" id="offer_tittle" required="required" class="input-sm form-control">
            
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="offer_text"><span class="req text-danger">*</span> Offer Text</label>
        <div class="controls col-sm-4">
            <textarea name="offer_text" readonly="readonly" id="offer_tittle" required="required" class="input-sm form-control"><?php if(isset($offer_text)){ echo $offer_text; } ?></textarea>
            
            
        </div>
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="menu_id"><span class="req text-danger">*</span> Menu ID</label>
        <div class="controls col-sm-4">
            <select id="menu_id" name="menu_id" class="chosen-select form-control" >
                <option value="">-- Select Menu Name --</option>
                <?php foreach ($menu_list as $menu) {
                     ?>
                <option value="<?php echo $menu['MENU_ID']; ?>"><?php echo $menu['MENU_NAME']; ?></option>
                <?php
                 } ?>
            </select>

        </div>
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="start_date"><span class="req text-danger">*</span> Start Date</label>
        <div class="col-sm-2">
            <input type="text" name="start_date" id="start_date" value="" class="form-control"  />
        </div>
        
        <label class="col-sm-2 control-label" for="start_time"><span class="req text-danger">*</span> Start Time</label>
        <div class="col-sm-2">
            <input type="text" name="start_time" id="start_time" value="" class="form-control"  />
        </div>
        <!--<span class="help-inline">Login Name is unique for each user.</span>-->
    </div>

    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="end_date"><span class="req text-danger">*</span> End Date</label>
        <div class="col-sm-2">
            <input type="text" name="end_date" id="end_date" value="" class="form-control"  />
        </div>
        
         <label class="col-sm-2 control-label" for="end_time"><span class="req text-danger">*</span> End Time</label>
        <div class="col-sm-2">
            <input type="text" name="end_time" id="end_time" value="" class="form-control"  />
        </div>
        <!--<span class="help-inline">Login Name is unique for each user.</span>-->
    </div>
    
    <div class="form-group formSep">
        <label class="col-sm-2 control-label" for="is_approved">Is Approved ? :</label>
        <div class="col-sm-1">
            <input type="checkbox" name="is_approved" id="is_approved"  class="form-control input-sm" />
        </div>
    </div>
    
     

    

   <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        
    </div></div>

<?php echo form_close(); ?>


    
   