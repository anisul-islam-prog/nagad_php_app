
<h1 class="heading">View / Edit Default Offer </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php if ($this->session->flashdata('message_success') && $this->session->flashdata('message_success') != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_success') .'</div>'; } ?>
<?php if ($this->session->flashdata('message_error') && $this->session->flashdata('message_error') != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_error') .'</div>'; } ?>


<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">
<?php echo form_open('administrator/offer/view_default_offer', array('class' => 'form-inline', 'onsubmit'=>'return report_1_validation();')); ?>

     <div class="form-group">
         <span class="text-info">Menu ID:</span>
        <input type="text" name="menu_id" id="menu_id" value="<?php if(isset($menu_id)){ echo $menu_id; } ?>" placeholder="Menu ID" class="form-control"  />
    </div>
    <div class="form-group">
        <span class="text-info">Offer ID:</span>
        <input type="text" name="offer_id" id="offer_id_search" value="<?php if(isset($offer_id)){ echo $offer_id; } ?>" placeholder="Offer ID" class="form-control"  />
    </div>
    
    
            
    
    
    <div class="form-group">
       <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-lg" />&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <!--<div class="form-group">
       <button type="submit" name="btn_export" value="export" class="btn btn-lg btn-default"><span class="glyphicon glyphicon-download-alt"></span> Export </button>
    </div>-->
        
        
   
    
   
  
<?php echo form_close(); ?>

    
     </div>



<?php
$sl=1;
if(isset($table_data))
{
?>

<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>SL.</th>
                            <th>OFFER ID</th>
                            <th>Menu Name</th>
                            <th>Default Text</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Is Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      
                       foreach ($table_data as $data) {
                           
                            if($data['IS_DEFAULT_OFFER']==1){
                                $is_active = 'Activated';
                            }
                            else if($data['IS_DEFAULT_OFFER']==0){
                                $is_active = 'Deactivated';
                            }
                                
                           
                           ?>
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $data['OFFER_ID']; ?></td>
                            <td><?php echo $data['MENU_NAME']; ?></td>
                            <td><?php echo $data['DEFAULT_TEXT']; ?></td>
                            <td><?php echo $data['START_DATE']; ?></td>
                            <td><?php echo $data['END_DATE']; ?></td>
                            <td><?php echo $is_active; ?></td>
                            
                            <td>
                               
                                <button class="btn btn-primary btn-sm"  id="open_edit_ussd_menu"
                                    data-toggle="modal" 
                                    data-target="#myModal"
                                    data-default_id="<?php echo $data['DEFAULT_ID']; ?>"
                                    data-is_active="<?php echo $data['IS_DEFAULT_OFFER']; ?>"
                                   
                                    >
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                              </button>
                                
                          
                            </td>
                           
                        </tr>
                        
                        <?php
                        $sl++;
                       }
                       
                      ?>
                    </tbody>
                </table>


<?php

}
?>

<div class="row control-row control-row-bottom">
        <div class="right">
            <?php if(isset($pagin_links)){ echo $pagin_links; } ?>
        </div>
    </div>

<!-- START EDIT MODAL -->

<script type="text/javascript">
    $(function(){
        $("#start_date").datepicker();
        $("#start_time").timepicker();
        $("#end_date").datepicker();
        $("#end_time").timepicker();
    });
</script>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edit Default Offer </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart(base_url('administrator/offer/edit_default_offer'), array('class' => 'form-horizontal')); ?>   
          <input type="hidden" name="default_id" id="default_id" >  
                
              <table>
                 
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Start Date:</label></td>
                        <td><input type="text" name="start_date" id="start_date" value="" class="form-control"  /></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Start Time:</label></td>
                        <td><input type="text" name="start_time" id="start_time" value="" class="form-control"  /></td>
                  </tr>
                   <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>End Date:</label></td>
                        <td><input type="text" name="end_date" id="end_date" value="" class="form-control"  /></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>End Time:</label></td>
                        <td><input type="text" name="end_time" id="end_time" value="" class="form-control"  /></td>
                  </tr>
                  
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Is Active ? :</label></td>
                        <td><input type="checkbox" name="is_active" id="is_active"  class="form-control input-sm" /></td>
                  </tr>
                  
                
                  <tr>
                      <td></td>
                      <td>&nbsp;</td>
                  </tr>
                  <tr>
                      <td></td>
                      <td><button type="submit" class="btn btn-sm btn-primary" id="delete" >Save changes</button></td>
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

<!--Start popup Edit Modal-->
    <script>
        $(document).on("click", "#open_edit_ussd_menu", function () {
            var default_id = $(this).data('default_id');
            var is_active = $(this).data('is_active');
            
            
            
            $("#default_id").val(default_id);
            
            $('#is_active').prop('checked', false); 
            
            if(is_active==1){
               $('#is_active').prop('checked', true); 
            }
            
            if(is_active==1){
               $('#is_active').prop('checked', true); 
            }
            
            
           
       });
    </script>
    <!-- End popup Edit Modal-->

    
   
   


<!--TABLE START-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/table/css/jquery.dataTables.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/table/resources/syntax/shCore.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/table/resources/demo.css'); ?>">
<style type="text/css" class="init">

</style>


<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/table/media/js/jquery.js'); ?>"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/table/media/js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/table/resources/syntax/shCore.js'); ?>"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/table/resources/demo.js'); ?>"></script>
<script type="text/javascript" language="javascript" class="init">


    var $j = $.noConflict(true);
    // $j is now an alias to the jQuery function; creating the new alias is optional.

    $j(document).ready(function() {
        $j('#smpl_tbl').dataTable();
    });
 
</script>

<!--TABLE ENDS-->




 
