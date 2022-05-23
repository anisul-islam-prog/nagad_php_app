
<h1 class="heading"> Offer Assisgn to Group
    <button class="btn btn-lg btn-primary" id="open_bulk_upload"
        data-toggle="modal" 
        data-target="#myModal"> Offer Bulk Assign to Group 
    </button>
</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php if ($this->session->flashdata('message_success') && $this->session->flashdata('message_success') != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_success') .'</div>'; } ?>
<?php if ($this->session->flashdata('message_error') && $this->session->flashdata('message_error') != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_error') .'</div>'; } ?>


<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<?php echo form_open('administrator/ussd/do_offer_assign_to_group', array('class' => 'form-horizontal')); ?>


     <script type="text/javascript">
        $(function(){
            $("#start_date").datepicker();
            $("#start_time").timepicker();
            $("#end_date").datepicker();
            $("#end_time").timepicker();
        });
    </script>

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

    <div class="form-group">
        <label class="col-sm-2 control-label" for="cmp_ids"><span class="req text-danger">*</span> Offers : </label>
        <div class="col-sm-4">
            <?php echo form_multiselect('cmp_ids[]', $this->camp_list, $this->form_data->cmp_ids , 'id="cmp_ids" class="chosen-select" style="width:100%;height:34px;"'); ?>
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="group_ids"><span class="req text-danger">*</span> Groups : </label>
        <div class="col-sm-4">
            <?php echo form_multiselect('group_ids[]', $this->group_list, $this->form_data->grp_ids , 'id="grp_ids" class="chosen-select" style="width:100%;height:34px;"'); ?>
            <!--<span class="help-inline">Inline help text</span>-->
        </div>
    </div>

   <div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;
        
    </div></div>

<?php echo form_close(); ?>


    
    <!-- START BULK UPLOAD -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Offer Bulk Assign to Group </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart('administrator/ussd/upload_bulk_offer_assign', array('class' => 'form-horizontal')); ?>   
              <input type="hidden" name="menu_id" id="menu_id">
              
              <table>
                  <tr>
                        <td><label class="col-sm-5 control-label">Upload File:</label></td>
                        <td>
                            <input type="file"   class="form-control"  name="userfile" id="userfile"  required="required">
                      
                        </td>
                        
                        
                        
                        
                  </tr>
                  
                  <tr>
                      <td></td>
                      <td>
                          <div class="alert alert-info">
                              <a href="<?php echo base_url('uploads/SAMPLE/bulk_assign.xlsx'); ?>"> Click Here To Download Sample Format</a> 
                          </div>
                        </td>
                  </tr>
                  
                  
                  <tr>
                      <td></td>
                      
                      <td>&nbsp;</td>
                  </tr>
                  <tr>
                      <td></td>
                      <td><button type="submit" class="btn btn-lg btn-primary" id="delete" >Upload</button></td>
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