<h1 class="heading"><?php if(1)  echo ''; ?> Role Management </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>


<?php //var_dump($robi_alls); die(); ?>

<div class="clearfix"></div>

    <div class="well well-sm">
    <hr>
        <?php echo form_open('dnd_controllers/user_management/update_role', array('class' => 'form-horizontal' , 'onsubmit' => 'return form_validation();')); ?>  

        <input type="hidden" name="role_id" value="<?php echo $this->uri->segment(4); ?>">                 
            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="robi_segment_name">Role Name:</label>
                <div class="col-sm-4">
                    <input type="text" required value="<?php echo $rolesDetails[0]['ROLE_NAME']; ?>" name="role_name" id="role_name"  class="form-control input-sm" />
                </div>
            </div>

            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="discription">Role Discription:</label>
                <div class="col-sm-4">
                    <textarea rows="2" name="discription" id="discription" class="form-control input-sm" />
                    <?php echo $rolesDetails[0]['ROLE_DESCRIPTION']; ?>
                    </textarea> 
                </div>
            </div>
<?php //print_r_pre($menuData); ?>
            <div class="form-group formSep">
                <label class="col-sm-2 control-label" for="priv_ids">Menu Privileges :</label>
                <div class="col-sm-4">
                    <?php
                        $menuArray = array();
                        foreach ($menuData as $key) :  
                            $menuArray[$key['MENUID']] = $key['MENUID'];
                        endforeach;
                        $menuList = array();
                        foreach ($allmenu as $key) :  
                            $menuList[$key['MENUID']] = $key['MENUNAME'];
                        endforeach;

                    ?>


                    <?php echo form_multiselect('menu[]',$menuList,$menuArray, 'id="priv_ids" class="chosen-select form-control" multiple tabindex="4"'); ?>
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
           <?php foreach ($rolesall as $key => $value) { ?>
            <tr>
                <td><?php echo $value['ID']; ?></td>
                <td><?php echo $value['ROLE_NAME']; ?></td>
                <td><?php echo $value['ROLE_DESCRIPTION']; ?></td>
                <td><a href="<?php echo base_url('dnd_controllers/user_management/update_role_view/'.$value['ID'].''); ?>" class="btn btn-sm btn-success" >update</a></td>
            </tr>
           <?php } ?>  
        </tbody>
        
    </table>
</div>


