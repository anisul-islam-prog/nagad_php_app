
<h1 class="heading"> Campaign Eligibility </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php if ($this->session->flashdata('message_success') && $this->session->flashdata('message_success') != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_success') .'</div>'; } ?>
<?php if ($this->session->flashdata('message_error') && $this->session->flashdata('message_error') != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $this->session->flashdata('message_error') .'</div>'; } ?>


<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">
<?php echo form_open('administrator/report/campaign_eligibility', array('class' => 'form-inline', 'onsubmit'=>'return report_1_validation();')); ?>

      <script type="text/javascript">
        $(function(){
            $("#start_date").datepicker();
            $("#end_date").datepicker();
        });
        
    </script>
    
    <table>
        <tr>
            <td>
                
                    <span class="text-info">MSISDN:</span><br/>
                    <div class="input-group">
                        <span class="input-group-addon">88018</span><input type="text" name="msisdn" required="required" id="msisdn" value="<?php if(isset($msisdn)){ echo $msisdn; } ?>" placeholder="Customer MSISDN" class="form-control input-sm inline"  />
                    </div>
                
            </td>
            <td>
                <div class="form-group">
                    <span class="text-info">Start Date:</span><br/>
                    <input type="text" name="start_date" value="<?php if(isset($start_date)){ echo $start_date; } ?>" placeholder="From Date" id="start_date"  class="form-control input-sm" />
                </div>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <div class="form-group">
                    <span class="text-info">End Date:</span><br/>
                    <input type="text" name="end_date" value="<?php if(isset($end_date)){ echo $end_date; } ?>" placeholder="To Date" id="end_date"  class="form-control input-sm" />
                </div>
            </td>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <div class="form-group">
                    <span class="text-info">Report Type:</span><br/>
                    <select name="report_type" class="form-control input-sm">
                        <option value="1" <?php if(isset($report_type) && $report_type==1){ echo "selected='selectede'"; } ?>>Assigned Offers</option>
                        <option value="2" <?php if(isset($report_type) && $report_type==2){ echo "selected='selectede'"; } ?>>View History</option>
                    </select>
                </div>
            </td>
            <td>
                
                <div class="form-group">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                   <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm" />&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </td>
        </tr>
    </table>
    
        
    
    
   
        
        
   
    
   
  
<?php echo form_close(); ?>

    
     </div>



<?php
if(isset($table1_data))
{
?>

<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Segment Name</th>
                            <th>Menu(L1)</th>
                            <th>Menu(L2)</th>
                            <th>Offer SMS</th>
                            <th>Offer Start Date</th>
                            <th>Offer End Date</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i=1;
                       foreach ($table1_data as $data) {
                          
                           ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data['GROUP_NAME']; ?></td>
                            <td><?php echo $data['MENU_NAME']; ?></td>
                            <td><?php echo $data['CUST_OFFER_TITLE']; ?></td>
                            <td><?php echo $data['CUSTOMEROFFER_TEXT2']; ?></td>
                            <td><?php echo $data['STARTDATE']; ?></td>
                            <td><?php echo $data['ENDDATE']; ?></td>
                           
                           
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

<?php
if(isset($table2_data))
{
?>

<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Group Name</th>
                            <th>Menu(L1)</th>
                            <th>Menu(L2)</th>
                            <th>Offer SMS</th>
                            <th>View Date</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i=1;
                      
                      //var_dump($table2_data); die();
                       foreach ($table2_data as $data) {
                          
                           ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data['GROUP_NAME']; ?></td>
                            <td><?php echo $data['MENU_NAME']; ?></td>
                            <td><?php echo $data['CUST_OFFER_TITLE']; ?></td>
                            <td><?php echo $data['CUSTOMER_OFFER_TEXT']; ?></td>
                            <td><?php echo $data['VIEW_DATE']; ?></td>
                           
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



<div class="row control-row control-row-bottom">
        <div class="right">
            <?php if(isset($pagin_links)){ echo $pagin_links; } ?>
        </div>
    </div>

<!-- START EDIT MODAL -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edit Offer </h4>
      </div>
        
      <div class="modal-body">
          <?php echo form_open_multipart(base_url('administrator/offer/edit_offer'), array('class' => 'form-horizontal')); ?>   
                <input type="hidden" name="offer_id" id="offer_id">
                
                
              <table>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Customer Offer Title:</label></td>
                        <td><input type="text" name="offer_title" id="offer_title" required="required" class="form-control input-sm" /></td>
                  </tr>
                  
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Retail Offer Title:</label></td>
                        <td><input type="text" name="retail_offer_title" id="retail_offer_title" required="required" class="form-control input-sm" /></td>
                  </tr>
                  
                
                  
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Offer Details:</label></td>
                        <td><textarea name="offer_details" id="offer_details" required="required" class="form-control input-sm"></textarea></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Customer Offer Text:</label></td>
                        <td><textarea name="customer_offer_text" id="customer_offer_text" required="required" class="form-control input-sm"></textarea></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Retail Offer Text:</label></td>
                        <td><textarea name="retail_offer_text" id="retail_offer_text" required="required" class="form-control input-sm"></textarea></td>
                  </tr>

                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Is Retailer Visible ? :</label></td>
                        <td><input type="checkbox" name="is_retailer_visible" value="1" id="is_retailer_visible_edit"  class="form-control input-sm" /></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Offer Amount:</label></td>
                        <td><input type="text" name="offer_amount" id="offer_amount" required="required" class="form-control input-sm" /></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Offer Amount Description:</label></td>
                        <td><textarea name="offer_amount_description" id="offer_amount_description" required="required" class="form-control input-sm"></textarea></td>
                  </tr>
                  <tr>
                        <td><label class="control-label"><span class="req text-danger">*</span>Commission Amount:</label></td>
                        <td><input type="text" name="commission_amount" id="commission_amount"  class="form-control input-sm" /></td>
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
        $(document).on("click", "#open_edit_ussd_menu", function () {
            var offer_id = $(this).data('offer_id');
            var offer_title = $(this).data('cust_ofr_ttl');
            var retail_offer_title = $(this).data('rtl_ofr_ttl');
            var customer_offer_text = $(this).data('cust_ofr_txt');
            var retail_offer_text = $(this).data('rtl_ofr_txt');
            var is_retailer_visible = $(this).data('rtl_isvsbl');
            var offer_details = $(this).data('ofr_details');
            var offer_amount = $(this).data('ofr_amount');
            var offer_amount_description = $(this).data('ofr_amount_dscrp');
            var commission_amount = $(this).data('com_amount');
            
            
            
            $("#offer_id").val(offer_id);
            $("#offer_title").val(offer_title);
            $("#retail_offer_title").val(retail_offer_title);
            $("#customer_offer_text").val(customer_offer_text);
            $("#retail_offer_text").val(retail_offer_text);
            $("#is_retailer_visible").val(is_retailer_visible);
            $("#offer_details").val(offer_details);
            $("#offer_amount").val(offer_amount);
            $("#offer_amount_description").val(offer_amount_description);
            $("#commission_amount").val(commission_amount);
            
            
            
            $('#is_retailer_visible_edit').prop('checked', false); 
            $('#is_customer_visible_edit').prop('checked', false); 
            
            
            if(is_retailer_visible==1){
               $('#is_retailer_visible_edit').prop('checked', true); 
            }
            
            if(is_customer_visible==1){
               $('#is_customer_visible_edit').prop('checked', true); 
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
    
    
     <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).attr('href'));
            
            $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
        })
    </script>
    


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




 
