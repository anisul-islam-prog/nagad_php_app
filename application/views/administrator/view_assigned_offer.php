
<h1 class="heading">View / Edit Assigned Offer </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php if ($this->session->flashdata('message_success') && $this->session->flashdata('message_success') != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_success') .'</div>'; } ?>
<?php if ($this->session->flashdata('message_error') && $this->session->flashdata('message_error') != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_error') .'</div>'; } ?>


<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">
<?php echo form_open('administrator/ussd/view_assigned_offer', array('class' => 'form-inline', 'onsubmit'=>'return report_1_validation();')); ?>

      <script type="text/javascript">
        $(function(){
            $("#date_range").daterangepicker();
        });
        
    </script>
    
    <table>
        
        <tr>
            <td>
                <span class="text-info">Date Range:</span><br/>
                <div class="form-group">
                    <input type="text" name="date_range" id="date_range" value="<?php if(isset($date_range)){ echo $date_range; } ?>" placeholder="Date Range" id="date_range"  class="form-control input-sm" />
                </div> 
            </td>
            <td>
                <div class="form-group">
                    <span class="text-info">Offer ID:</span><br/>
                    <input type="text" name="offer_id" id="offer_id_search" value="<?php if(isset($offer_id)){ echo $offer_id; } ?>" placeholder="Offer ID" class="form-control input-sm"  />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <span class="text-info">Group ID:</span><br/>
                    <input type="text" name="group_id" id="group_id_search" value="<?php if(isset($group_id)){ echo $group_id; } ?>" placeholder="Group ID" class="form-control input-sm"  />
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <span class="text-info">Menu ID:</span><br/>
                    <input type="text" name="menu_id" id="menu_id_search" value="<?php if(isset($menu_id)){ echo $menu_id; } ?>" placeholder="Menu ID" class="form-control input-sm"  />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <span class="text-info">Is Approved:</span><br/>
                    <input type="text" name="is_approved" id="is_approved_search"  value="<?php if(isset($is_approved)){ echo $is_approved; } ?>" placeholder="Is Approved ? 0 or 1 " class="form-control input-sm"  />
                </div>
            </td>
            <td>
                <br/> &nbsp;&nbsp;&nbsp;
                <div class="form-group">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm" />&nbsp;
                 </div>
                 <div class="form-group">
                    <button type="submit" name="btn_export" value="export" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-download-alt"></span> Export </button>
                 </div>
            </td>
        </tr>
        
    </table>
    
    
    
    
    
    
    
    
    
    
    
            
    
    
    
        
        
   
    
   
  
<?php echo form_close(); ?>

    
</div>



<?php
if(isset($table_data))
{
?>

<table class="table table-bordered table-striped" id="smpl_tbl_disabled" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>OFFER ID</th>
                            <th>GROUP ID</th>
                            <th>MENU NAME</th>
                            <th>START DATE</th>
                            <th>END DATE</th>
                            <th>IS APPROVED</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      
                       foreach ($table_data as $data) {
                           
                          
                           ?>
                        <tr>
                            <td><?php echo $data['OFFER_ID']; ?></td>
                            <td><?php echo $data['GROUP_NAME']; ?></td>
                            <td><?php echo $data['MENU_NAME']; ?></td>
                            <td><?php echo $data['STARTDATE']; ?></td>
                            <td><?php echo $data['ENDDATE']; ?></td>
                            <td><?php echo $data['ISAPPROVED']; ?></td>
                           
                            <td>
                                <?php
                               if($this->role_model->isVisibleParentMenu(array(21))==TRUE)
                                { 
                                ?>
                                <button class="btn btn-primary btn-sm"  id="open_edit_assign"
                                        data-toggle="modal" 
                                        data-target="#myModal"
                                        data-assign_id="<?php echo $data['ASSIGN_ID']; ?>"
                                        data-group_id="<?php echo $data['GROUP_ID']; ?>"
                                        data-offer_id="<?php echo $data['OFFER_ID']; ?>"
                                        data-menu_id="<?php echo $data['MENU_ID']; ?>"
                                        data-is_approved="<?php echo $data['ISAPPROVED']; ?>"
                                       
                                        >
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                              </button>
                                <?php } ?>
                                <a>
                            <!--<button class="btn btn-danger btn-sm"  id="open_edit_ussd_menu"
                                        data-toggle="modal" data-target="#confirm-delete"
                                        >
                                <i class="glyphicon glyphicon-trash"></i> Delete
                              </button>-->
                                </a>
                            </td>
                           
                        </tr>
                        
                        <?php
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
        <h4 class="modal-title">Edit Assigned Offer </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart(base_url('administrator/ussd/edit_assigned_offer'), array('class' => 'form-horizontal')); ?>   
          <input type="hidden" name="assign_id" id="assign_id" >  
                
              <table>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Offer ID:</label></td>
                        <td><input type="text" name="offer_id" id="offer_id" required="required" class="form-control input-sm" /></td>
                  </tr>
                  
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Group ID:</label></td>
                        <td><input type="text" name="group_id" id="group_id" required="required" class="form-control input-sm" /></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Menu ID:</label></td>
                        <td><input type="text" name="menu_id" id="menu_id" required="required" class="form-control input-sm" /></td>
                  </tr>
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
                        <td><label class="control-label"><span class="req text-danger">*</span>Is Approved ? :</label></td>
                        <td><input type="checkbox" name="is_approved" id="is_approved"  class="form-control input-sm" /></td>
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

<!-- END EDIT MODAL -->


<!--Start popup Edit Modal-->
    <script>
        $(document).on("click", "#open_edit_assign", function () {
            
           
            
            var assign_id = $(this).data('assign_id');
            var offer_id = $(this).data('offer_id');
            var group_id = $(this).data('group_id');
            var menu_id = $(this).data('menu_id');
            var is_approved = $(this).data('is_approved');
            
            
           
           $("#start_date").val('');
           $("#end_date").val('');
            // alert(is_approved);
            
            $("#assign_id").val(assign_id);
            $("#offer_id").val(offer_id);
            $("#group_id").val(group_id);
            $("#menu_id").val(menu_id);
            
            
            $('#is_approved').prop('checked', false); 
            
            
            if(is_approved==1){
               $('#is_approved').prop('checked', true); 
            }
           
           
       });
    </script>
    <!-- End popup Edit Modal-->

    
    <!--START DELETE MODAL-->
    
<!--    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
            
                <div class="modal-body">
                    <p>You are about to delete this Menu .</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>-->
    
    <!--END DELETE MODAL-->
    
<!--    
     <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).attr('href'));
            
            $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
        })
    </script>-->
    


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




 
