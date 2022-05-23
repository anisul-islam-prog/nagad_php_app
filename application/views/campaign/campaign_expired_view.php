<h1 class="heading"><?php if (1) echo ''; ?>  Campaign </h1>

<?php if ($message_success != '') {
    echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>' . $message_success . '</div>';
} ?>
<?php if ($message_error != '') {
    echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>' . $message_error . '</div>';
} ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>



<div class="well well-sm">

<?php  //if($campaignDetails[0]['CATEGORY_ID'] == 7 ) echo "OK";?>
    

     
<h2><?php echo $message; ?></h2>
</div>
