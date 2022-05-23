
<h1 class="heading">View Group</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>





<?php
if(isset($table_data))
{
?>

<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Group Name</th>
                            <th>Creation Date </th>
                            <th>Is Active</th>
                            <th>Is Data Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i=1;
                       foreach ($table_data as $data) {
                           
                            if($data['ISACTIVE']==1){
                                $is_active = 'Activated';
                            }
                            else if($data['ISACTIVE']==0){
                                $is_active = 'Deactivated';
                            }
                            
                            if($data['IS_DATA_UPDATED']==1){
                                $is_updated = 'Updated';
                            }
                            else if($data['IS_DATA_UPDATED']==0){
                                $is_updated = 'Not Updated';
                            }
                                
                           
                           ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data['GROUP_NAME']; ?></td>
                            <td><?php echo $data['CREATEDATE']; ?></td>
                            <td><?php echo $is_active; ?></td>
                            <td><?php echo $is_updated; ?></td>
                            <td>
                               <?php
                               if($this->role_model->isVisibleParentMenu(array(22))==TRUE)
                                { 
                                ?>
                                <button class="btn btn-primary btn-sm"  id="open_edit_group"
                                    data-toggle="modal" 
                                    data-target="#myModal"
                                    data-group_id="<?php echo $data['GROUP_ID']; ?>"
                                    data-is_active="<?php echo $data['ISACTIVE']; ?>"
                                    >
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                              </button>
                                <?php } ?>
                        </tr>
                        
                        <?php
                        $i++;
                       }
                       
                      ?>
                    </tbody>
                </table>


<?php
}
?>


<!-- START EDIT MODAL -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edit Group </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart(base_url('administrator/group/edit_group'), array('class' => 'form-horizontal')); ?>   
                <input type="hidden" name="group_id" id="group_id">
                
                
              <table>
                 
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Is Active ? :</label></td>
                        <td><input type="checkbox" name="is_active" value="1" id="is_active_edit"  class="form-control input-sm" /></td>
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
        $(document).on("click", "#open_edit_group", function () {
            var group_id = $(this).data('group_id');
           
            var is_active = $(this).data('is_active');
            
            //alert(is_active);
            
            $("#group_id").val(group_id);
            
            $('#is_active_edit').prop('checked', false); 
           
            if(is_active==1){
               $('#is_active_edit').prop('checked', true); 
            }
            else{
                $('#is_active_edit').prop('checked', false); 
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




 
