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


<h1 class="heading">Manage Users</h1>

<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>


<div class="row-fluid">
    <div class="span12">

        <div class="row control-row control-row-top">
            <div class="span6 left">
            <?php echo form_open('administrator/user/filter', array('class' => 'form-inline', 'id' => 'filter-form')); ?>

                <div class="form-group"><input type="text" name="filter_user" id="filter_user" value="<?php echo set_value('filter_user', $this->form_data->filter_user); ?>"
                                               placeholder="Name, Email, MSISDN" class="form-control input-sm" /></div>
                <div class="form-group"><?php echo form_dropdown('filter_role', $this->filter_role_list, $this->form_data->filter_role, 'id="filter_role" class="form-control chosen-select" style="width:auto;"'); ?></div>
                <div class="form-group"><?php echo form_dropdown('filter_locked', $this->filter_locked_list, $this->form_data->filter_locked, 'id="filter_locked" class="form-control chosen-select" style="width:auto;"'); ?></div>
                <div class="form-group"><?php echo form_dropdown('filter_active', $this->filter_active_list, $this->form_data->filter_active, 'id="filter_active" class="form-control chosen-select" style="width:auto;"'); ?></div>
                &nbsp;
                <div class="form-group">
                    <span class="btn-group btn-group-sm">
                        <button type="submit" name="filter_do" value="Filter" class="btn btn-default"><i class="glyphicon glyphicon-filter"></i> Filter</button>
                        <?php if (count($filter) > 0): ?>
                        <button type="submit" name="filter_clear" value="Clear" title="Clear Filter" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></button>
                        <?php endif; ?>
                    </span>
                </div>

            <?php echo form_close(); ?>
            </div>
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
