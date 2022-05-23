<h1 class="heading"><?php if(1)  echo ''; ?> Add New Offer 
    <button class="btn btn-lg btn-primary" id="open_bulk_upload"
    data-toggle="modal" 
    data-target="#myModal"> Offer Bulk Upload 
    </button>
</h1>




<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="well well-sm">
<?php echo form_open('administrator/offer/do_add_offer', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="offer_title"><span class="req text-danger">*</span>Customer Offer Title:</label>
    <div class="col-sm-4">
        <input type="text" name="offer_title" id="offer_title" required="required" class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
  
</div>
    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="retail_offer_title"><span class="req text-danger">*</span>Retail Offer Title:</label>
    <div class="col-sm-4">
        <input type="text" name="retail_offer_title" id="retail_offer_title" required="required" class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>  
    
 

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="offer_details"><span class="req text-danger">*</span>Offer Details:</label>
    <div class="col-sm-4">
        <textarea name="offer_details" id="offer_details" required="required" class="form-control input-sm"></textarea>
    </div>
</div>     

<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="customer_offer_text"><span class="req text-danger">*</span>Customer Offer Text:</label>
    <div class="col-sm-4">
        <textarea name="customer_offer_text" id="customer_offer_text" required="required" class="form-control input-sm"></textarea>
    </div>
</div>  
    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="retail_offer_text"><span class="req text-danger">*</span> Retail Offer Text:</label>
    <div class="col-sm-4">
        <textarea name="retail_offer_text" id="retail_offer_text" required="required" class="form-control input-sm"></textarea>
    </div>
</div>  
    

    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="is_retailer_visible"><span class="req text-danger">*</span>Is Retailer Visible ? </label>
    <div class="col-sm-1">
        <input type="checkbox" name="is_retailer_visible" id="is_retailer_visible"  class="form-control input-sm" />
    </div>
</div>    
    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="menu_name"><span class="req text-danger">*</span> Menu Name</label>
    <div class="controls col-sm-4">
        <select id="menu_name" name="menu_name" class="chosen-select form-control" >
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
    <label class="col-sm-2 control-label" for="offer_amount"><span class="req text-danger">*</span> Offer Amount:</label>
    <div class="col-sm-4">
        <input type="text" name="offer_amount" id="offer_amount" required="required" class="form-control input-sm" />
        <!--<span class="help-inline">Inline help text</span>-->
    </div>
</div>

    
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="offer_amount_description"><span class="req text-danger">*</span> Offer Amount Description:</label>
    <div class="col-sm-4">
        <textarea name="offer_amount_description" id="offer_amount_description" required="required" class="form-control input-sm"></textarea>
    </div>
</div>   
    
   
    
<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
    <input type="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
</div></div>




<?php echo form_close(); ?>
</div>


<!-- START BULK UPLOAD -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Upload Bulk Offer </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart('administrator/offer/upload_bulk_offer', array('class' => 'form-horizontal')); ?>   
              <input type="hidden" name="menu_id" id="menu_id">
              
              <table>
                  <tr>
                        <td><label class="col-sm-5 control-label">Upload File:</label></td>
                        <td><input type="file"   class="form-control"  name="userfile" id="userfile"  required="required"></td>
                  </tr>
                  
                  <tr>
                      <td></td>
                      <td>
                          <div class="alert alert-info">
                              <a href="<?php echo base_url('uploads/SAMPLE/bulk_offer.xlsx'); ?>"> Click Here To Download Sample Format</a> 
                          </div>
                        </td>
                  </tr>
                  
                  <tr>
                      <td></td>
                      <td>&nbsp;</td>
                  </tr>
                  <tr>
                      <td></td>
                      <td><button type="submit" class="btn btn-sm btn-primary" id="delete" >Upload</button></td>
                  </tr>
              </table>
              
              <?php echo form_close(); ?>
        <!--</form> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
        
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- ENDS BULK UPLOAD -->


<!--Start popup Edit Modal-->
    <script>
    /*    $(document).on("click", "#open_bukl_upload", function () {
            
       });*/
    </script>
    <!-- End popup Edit Modal-->