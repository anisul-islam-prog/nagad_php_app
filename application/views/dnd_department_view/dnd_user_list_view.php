<h1 class="heading"><?php if(1)  echo ''; ?> User List </h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<div role="tabpanel" class="tab-pane" id="profile">
    <hr><?php //print_r_pre($getUD); ?>
    <table class="table">
        <thead>
            <th>ID</th>
            <th>LOGIN NAME</th>
            <th>ROLE NAME</th>
            <th>DEPARTMENT NAME</th>
            <th>USER NAME</th>
            <th>E-MAIL</th>
            <th>MOBILE</th>
            <th>LOCKED</th>
            <th>ACTIVE</th>
            <th class="center">ACTION</th>
        </thead>
        <tbody>
           <?php foreach ($get_UD as $u_dtail ): ?>
            <tr>
              <td><?php echo $u_dtail['ID'] ?></td>
              <td><?php echo $u_dtail['USER_LOGIN_NAME'] ?></td>
              <td><?php echo $u_dtail['ROLE_NAME'] ?></td>
              <td><?php echo $u_dtail['DEPARTMENT_NAME'] ?></td>
              <td><?php echo $u_dtail['USER_NAME'] ?></td>
              <td><?php echo $u_dtail['USER_EMAIL'] ?></td>
              <td><span>880</span><?php echo $u_dtail['USER_MSISDN'] ?></td>
              <td><?php 
              if($u_dtail['USER_IS_LOCK'] == 1){
                echo "YES";
              }else{
                echo "NO";
              }
              ?></td>
              <td><?php 
              if($u_dtail['USER_IS_ACTIVE'] == 1){
                echo "YES";
              }else{
                echo "NO";
              }
              ?></td>
              <td><a href="<?php echo base_url('dnd_controllers/user_management/update_user_view/'.$u_dtail['ID'].''); ?>" class="btn btn-sm btn-success" >update</a>
              <!--<a href="<?php echo base_url('dnd_controllers/user_management/delete/'.$u_dtail['ID'].''); ?>" class="btn btn-sm btn-danger" >delete</a></td>-->
            </tr>
          <?php endforeach; ?>
        </tbody>
        
    </table>
</div>
