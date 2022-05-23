<h1 class="heading"><?php if(1)  echo ''; ?> Role Management </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<?php //var_dump($robi_alls); die(); ?>

<div class="clearfix"></div>

<div class="well well-sm">
    <hr>
        <?php echo form_open('dnd_controllers/user_management/update_role', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>

    <div class="form-group formSep">
       <label class="col-sm-2 control-label" for="user_department_id"><span class="req">*</span>User Department </label>
       <div class="controls col-sm-4">
           <select class="chosen-select form-control" id="user_department_id" name="user_department_id">
               <option value=""> --- Select Please --- </option>
               <?php foreach ($roles as $key => $value) { ?>
                <option value="<?php echo $value['ID']; ?>"><?php echo $value['ROLE_NAME']; ?></option>
            <?php } ?>
           	</select>
       	</div>
   	</div>
<?php //print_r_pre($menuData); ?>
            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="priv_ids">Menu Privileges :</label>
                <div class="col-sm-4">
                    
                </div>
            </div>

                
            <div class="form-group form-actions">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary btn-lg" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Role</button>
                </div>
            </div>
        <?php echo form_close(); ?>  
    </div>


<div role="tabpanel" class="tab-pane" id="profile">
    <h3>Role List </h3>
    <hr><?php //print_r_pre($robi_alls); ?>
    <table class="table">
        <thead>
            <th>ID</th>
            <th>ROLES NAME</th>
            <th>DESCRIPTION</th>
            <th>ACTION</th>
        </thead>
        <tbody>
           
            <tr>
                <td><?php echo $value['ID']; ?></td>
                <td><?php echo $value['ROLE_NAME']; ?></td>
                <td><?php echo $value['ROLE_DESCRIPTION']; ?></td>
                <td><a href="" class="btn btn-sm btn-success" >update</a></td>
            </tr>
            
        </tbody>
        
    </table>
</div>