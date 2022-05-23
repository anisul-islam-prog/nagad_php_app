
<h1 class="heading">Manage Roles</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>


<div class="row-fluid">
    <div class="span12">

        <div class="row control-row control-row-top">
            <div class="right">
                <?php echo $pagin_links; ?>
            </div>
        </div>


        <?php echo $records_table; ?>


        <div class="row control-row control-row-bottom">
            <div class="right">
                <?php echo $pagin_links; ?>
            </div>
        </div>

    </div>
</div>
