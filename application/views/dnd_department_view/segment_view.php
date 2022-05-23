<h1 class="heading"><?php if(1)  echo ''; ?> Segment Management </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<?php //var_dump($robi_alls); die(); ?>

<div class="clearfix"></div>

    <div class="well well-sm">
    <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#robilist" aria-controls="home" role="tab" data-toggle="tab">Robi</a>
            </li>
            <li role="presentation">
                <a href="#airtellist" aria-controls="profile" role="tab" data-toggle="tab">Airtel</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <!---bucket insert-->
            <div role="tabpanel" class="tab-pane active" id="robilist">
                <hr>
                <?php echo form_open('dnd_controllers/department/add_segment_robi', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?> 
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_segment_name">Segment Name:</label>
                        <div class="col-sm-4">
                            <input type="text" required placeholder="Enter Robi Segment Name" name="robi_segment_name" id="robi_segment_name"  class="form-control input-sm" />
                            <!--<span class="help-inline">Inline help text</span>-->
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_msisdn_type"><span class="req text-danger">*</span> MSISDN Type:</label>
                        <div class="controls col-sm-4">
                            <select id="robi_msisdn_type" name="robi_msisdn_type" class="chosen-select form-control" >
                                <option value="">-- Select MSISDN Type --</option>
                                <option value="prepaid">Prepaid</option>
                                <option value="postpaid">Postpaid</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_segment_start">Segment Range:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Enter Segment Start" name="robi_segment_start" id="robi_segment_start"  class="form-control input-sm" />
                        </div>

                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Enter Segment End" name="robi_segment_end" id="robi_segment_end" class="form-control input-sm" />
                        </div>

                    </div>



                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_segment_daily">Segment Daily Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Daily Quota" name="robi_segment_daily" id="robi_segment_daily"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_segment_weekly">Segment Weekly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Weekly Quota" name="robi_segment_weekly" id="robi_segment_weekly"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="robi_segment_monthly">Segment Monthly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Monthly Quota" name="robi_segment_monthly" id="robi_segment_monthly"  class="form-control input-sm" />
                        </div>
                    </div>
                

                        
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Segment Robi</button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
            <!---bucket insert-->

            <!---bucket mapping-->
                <div role="tabpanel" class="tab-pane" id="airtellist">
                <hr>
                    <?php echo form_open('dnd_controllers/department/add_segment_airtel', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>                   
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtel_segment_name">Segment Name:</label>
                        <div class="col-sm-4">
                            <input type="text" required placeholder="Enter Airtel Segment Name" name="airtel_segment_name" id="airtel_segment_name"  class="form-control input-sm" />
                            <!--<span class="help-inline">Inline help text</span>-->
                        </div>
                    </div>
                    
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtle_msisdn_type"><span class="req text-danger">*</span> MSISDN Type:</label>
                        <div class="controls col-sm-4">
                            <select id="airtle_msisdn_type" name="airtle_msisdn_type" class="chosen-select form-control" >
                                <option value="">-- Select MSISDN Type --</option>
                                <option value="prepaid">Prepaid</option>
                                <option value="postpaid">Postpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtel_segment_start">Segment Range:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Enter Segment Start" name="airtel_segment_start" id="airtel_segment_start"  class="form-control input-sm" />
                        </div>

                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Enter Segment End" name="airtel_segment_end" id="airtel_segment_end" class="form-control input-sm" />
                        </div>

                    </div>



                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtel_segment_daily">Segment Daily Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Daily Quota" name="airtel_segment_daily" id="airtel_segment_daily"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtel_segment_weekly">Segment Weekly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Weekly Quota" name="airtel_segment_weekly" id="airtel_segment_weekly"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="airtel_segment_monthly">Segment Monthly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Enter Monthly Quota" name="airtel_segment_monthly" id="airtel_segment_monthly"  class="form-control input-sm" />
                        </div>
                    </div>


                        
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Segment Airtel</button>
                        </div>
                    </div>
                <?php echo form_close(); ?>
                </div>
            <!---bucket mapping-->
          </div>
</div>


<div class="well well-sm">
    <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Robi</a>
            </li>
            <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Airtel</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <!---bucket insert-->
            <div role="tabpanel" class="tab-pane active" id="home">
                <h3>ROBI Segment Name </h3>
                <hr>
                <table class="table">
                <?php if ($robi_alls == 0){?>
                    <div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>
                <?php } else { ?>
                    <thead>
                        <th>ID</th>
                        <th>SEGMENT NAME</th>
                        <th>MSISDN TYPE</th>
                        <th>START RANGE</th>
                        <th>END RANGE</th>
                        <th>DAILY QUOTA</th>
                        <th>WEEKLY QUOTA</th>
                        <th>MONTHLY QUOTA</th>
                        <th>ACTION</th>
                    </thead>
                    <?php foreach ($robi_alls as $key => $value) : ?>
                        <tr>
                            <td><?php echo $value['ID']; ?></td>
                            <td><?php echo $value['SEGMENT_NAME']; ?></td>
                            <td><?php echo $value['MSISDN_TYPE']; ?></td>
                            <td><?php echo $value['START_RANGE']; ?></td>
                            <td><?php echo $value['END_RANGE']; ?></td>
                            <td><?php echo $value['DAILY_QUOTA']; ?></td>
                            <td><?php echo $value['WEEKLY_QUOTA']; ?></td>
                            <td><?php echo $value['MONTHLY_QUOTA']; ?></td>
                            <td><button 
                                data-id="<?php echo $value['ID']; ?>"
                                data-rsname="<?php echo $value['SEGMENT_NAME']; ?>"
                                data-rmsisdn="<?php echo $value['MSISDN_TYPE'];?>"
                                data-rstart_range="<?php echo $value['START_RANGE']; ?>"
                                data-rend_range="<?php echo $value['END_RANGE']; ?>"
                                data-rsegment_daily="<?php echo $value['DAILY_QUOTA']; ?>"
                                data-rsegment_weekly="<?php echo $value['WEEKLY_QUOTA']; ?>"
                                data-rsegment_monthly="<?php echo $value['MONTHLY_QUOTA']; ?>" 
                                type="button" data-toggle="modal" data-target="#myModal"  class="btn btn-sm btn-success" >edit</button></td>
                        </tr>
                    <?php endforeach; }?>
                </table>
            </div>
            <!---bucket insert-->

            <!---bucket mapping-->
                <div role="tabpanel" class="tab-pane" id="profile">
                    <h3>AIRTEL Segment Name </h3>
                    <hr><?php //print_r_pre($robi_alls); ?>
                    <table class="table">
                        <?php if ($airtel_alls == 0){ ?>
                            <div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>
                       <?php }else{ ?>
                       <thead>
                            <th>ID</th>
                            <th>SEGMENT NAME</th>
                            <th>MSISDN TYPE</th>
                            <th>START RANGE</th>
                            <th>END RANGE</th>
                            <th>DAILY QUOTA</th>
                            <th>WEEKLY QUOTA</th>
                            <th>MONTHLY QUOTA</th>
                            <th>ACTION</th>
                        </thead>

                        <?php foreach ($airtel_alls as $keys => $values): ?>
                        <tr>
                            <td><?php echo $values['ID']; ?></td>
                            <td><?php echo $values['SEGMENT_NAME']; ?></td>
                            <td><?php echo $values['MSISDN_TYPE']; ?></td>
                            <td><?php echo $values['START_RANGE']; ?></td>
                            <td><?php echo $values['END_RANGE']; ?></td>
                            <td><?php echo $values['DAILY_QUOTA']; ?></td>
                            <td><?php echo $values['WEEKLY_QUOTA']; ?></td>
                            <td><?php echo $values['MONTHLY_QUOTA']; ?></td>
                            <td><button 
                                data-aid="<?php echo $values['ID']; ?>"
                                data-asname="<?php echo $values['SEGMENT_NAME']; ?>"
                                data-amsisdn="<?php echo $values['MSISDN_TYPE'];?>"
                                data-astart_range="<?php echo $values['START_RANGE']; ?>"
                                data-aend_range="<?php echo $values['END_RANGE']; ?>" 
                                data-asegment_daily="<?php echo $values['DAILY_QUOTA']; ?>" 
                                data-asegment_weekly="<?php echo $values['WEEKLY_QUOTA']; ?>"
                                data-asegment_monthly="<?php echo $values['MONTHLY_QUOTA']; ?>"
                                type="button" data-toggle="modal" data-target="#myModal1"  class="btn btn-sm btn-success" >edit</button></td>
                        </tr>
                    <?php endforeach; } ?>
                        
                    </table>
                </div>
            <!---bucket mapping-->
          </div>
</div>




<!-- Modal Robi-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('dnd_controllers/department/update_segment_robi', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Robi Segment Update</h4>
          </div>
          <div class="modal-body">
          <input type="hidden" id="id" name="id">
                                  
                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="rsname">Segment Name:</label>
                        <div class="col-sm-4">
                            <input type="text" required placeholder="Update Robi Segment Name" name="rsname" id="rsname"  class="form-control input-sm" />
                            <!--<span class="help-inline">Inline help text</span>-->
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="rmsisdn">MSISDN Type:</label>
                        <div class="col-sm-4">
                            <select id="rmsisdnss" name="rmsisdn" class="form-control">
                                <option value="prepaid">Prepaid</option>
                                <option value="postpaid">Postpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="rstart_range">Segment Range:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Update Segment Start" name="rstart_range" id="rstart_range"  class="form-control input-sm" />
                        </div>

                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Update Segment End" name="rend_range" id="rend_range" class="form-control input-sm" />
                        </div>

                    </div>



                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="robi_segment_daily">Segment Daily Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Daily Quota" name="rsegment_daily" id="rsegment_daily"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="robi_segment_weekly">Segment Weekly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Weekly Quota" name="rsegment_weekly" id="rsegment_weekly"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-3 control-label" for="robi_segment_monthly">Segment Monthly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Monthly Quota" name="rsegment_monthly" id="rsegment_monthly"  class="form-control input-sm" />
                        </div>
                    </div>

                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>



<!-- Modal Airtel-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('dnd_controllers/department/update_segment_airtel', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Airtel Segment Update</h4>
          </div>
          <div class="modal-body">
          <input type="hidden" id="aid" name="id">
                                  
                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="rsname">Segment Name:</label>
                        <div class="col-sm-4">
                            <input type="text" required placeholder="Update Airtel Segment Name" name="asname" id="asname"  class="form-control input-sm" />
                            <!--<span class="help-inline">Inline help text</span>-->
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="amsisdn">MSISDN Type:</label>
                        <div class="col-sm-4">
                            <select id="amsisdnss" name="amsisdn" class="form-control">
                                <option value="prepaid">Prepaid</option>
                                <option value="postpaid">Postpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="rstart_range">Segment Range:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Update Segment Start" name="astart_range" id="astart_range"  class="form-control input-sm" />
                        </div>

                        <div class="col-sm-2">
                            <input type="text" value="0.0" required placeholder="Update Segment End" name="aend_range" id="aend_range" class="form-control input-sm" />
                        </div>

                    </div>


                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="asegment_daily">Segment Daily Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Daily Quota" name="asegment_daily" id="asegment_daily"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="airtel_segment_weekly">Segment Weekly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Weekly Quota" name="asegment_weekly" id="asegment_weekly"  class="form-control input-sm" />
                        </div>
                    </div>

                    <div class="form-group formSep">
                        <label class="col-sm-4 control-label" for="airtel_segment_monthly">Segment Monthly Quota:</label>
                        <div class="col-sm-2">
                            <input type="text" value="0" required placeholder="Update Monthly Quota" name="asegment_monthly" id="asegment_monthly"  class="form-control input-sm" />
                        </div>
                    </div>



                
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>

      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
    $('button[data-toggle=modal], button[data-toggle=modal]').click(function () {
            var id               = $(this).data('id');
            var rsname           = $(this).data('rsname');
            var rmsisdn          = $(this).data('rmsisdn');
            var rstart_range     = $(this).data('rstart_range');
            var rend_range       = $(this).data('rend_range');
            var rsegment_daily   = $(this).data('rsegment_daily');
            var rsegment_weekly  = $(this).data('rsegment_weekly');
            var rsegment_monthly = $(this).data('rsegment_monthly');
             
            $('#id').val(id);
            $('#rsname').val(rsname);
            $("#rmsisdnss option[value='"+rmsisdn+"']").attr('selected', 'selected');
            $('#rstart_range').val(rstart_range);
            $('#rend_range').val(rend_range);
            $('#rsegment_daily').val(rsegment_daily);
            $('#rsegment_weekly').val(rsegment_weekly);
            $('#rsegment_monthly').val(rsegment_monthly);

              
            
        })
    });

    $(document).ready(function() {
    $('button[data-toggle=modal1], button[data-toggle=modal]').click(function () {
            var id               = $(this).data('aid');
            var asname           = $(this).data('asname');
            var amsisdn          = $(this).data('amsisdn');
            var astart_range     = $(this).data('astart_range');
            var aend_range       = $(this).data('aend_range');
            var asegment_daily   = $(this).data('asegment_daily');
            var asegment_weekly  = $(this).data('asegment_weekly');
            var asegment_monthly = $(this).data('asegment_monthly');

            $('#aid').val(id);
            $('#asname').val(asname);
            $("#amsisdnss option[value='"+amsisdn+"']").attr('selected', 'selected');
            $('#astart_range').val(astart_range);
            $('#aend_range').val(aend_range);
            $('#asegment_daily').val(asegment_daily);
            $('#asegment_weekly').val(asegment_weekly);
            $('#asegment_monthly').val(asegment_monthly);

              
            
        })
    });

</script>