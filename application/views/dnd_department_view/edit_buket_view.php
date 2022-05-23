<h1 class="heading"><?php if(1)  echo ''; ?> Update Bucket Mapping </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<div class="clearfix"></div>

<div class="well well-sm"> 
  <?php echo form_open('dnd_controllers/department/edit_bucket_mapping', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="bucket_name">Bucket Name:</label>
                        <div class="col-sm-4">
                           <select name="bucket" id="bucket" required name="Department" class="chosen-select form-control"'>
                                <option value="<?php echo $bucket[0]['ID']; ?>"><?php echo $bucket[0]['BUCKET_NAME']; ?></option>
                                
                            </select>
                        </div>
                    </div>

<?php //var_dump($bucket_by_dep); ?>
                    <div class="form-group formSep">
                        <label class="col-sm-2 control-label" for="group_name">Department Name :</label>
                        <div class="col-sm-4"> 
                             <?php
                                $buckt = array();
                                $dp = array();
                                //var_dump($bucket_by_dep); die();
                                if ($bucket_by_dep) {
                                	foreach ($bucket_by_dep as $key) :  
                                    $buckt[$key['DEPARTMENT_ID']] = $key['DEPARTMENT_ID'];
                                	endforeach;
                                }
                                
                                foreach ($department_all as $keys) :  
                                    $dp[$keys['ID']] = $keys['DEPARTMENT_NAME'];
                                endforeach;
                            ?>

                            <?php
                            	//$existing=array(1,3,4,5);
                            ?>

                            <?php echo form_multiselect('department[]',$dp,$buckt, 'id="priv_ids" class="chosen-select form-control" multiple tabindex="4"'); ?>


                            <!-- <?php echo form_multiselect('department[]',$buckt,$buckt, 'id="priv_ids" class="chosen-select form-control" multiple tabindex="4"'); ?> -->

                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Update Bucket Mapping</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>      
</div>
