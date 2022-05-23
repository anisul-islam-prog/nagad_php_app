<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<?php $this->load->view('partials/header'); ?>
<? echo "This is Test";?>
<h1 class="heading">Manage Company</h1>
<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>
<?php echo form_open('administrator/outlet/save_person', array('class' => 'form-horizontal')); ?>
<div class="form-group formSep">
    <label class="col-sm-2 control-label" for="OutletName">Outlet Name</label>
    <div class="col-sm-4">
        <input type="text" name="OutletName" id="OutletName" value="<?php echo set_value('OutletName', "sdf"); ?>" class="form-control input-sm" />
        <!--<span class="help-block">Inline help text</span>-->
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="OutletCode">Outlet Code</label>
    <div class="col-sm-2">
        <input type="text" name="OutletCode" id="OutletCode" value="<?php echo set_value('OutletCode', 123); ?>" class="form-control input-sm" />
        <!--<span class="help-block">Inline help text</span>-->
    </div>
</div>

<div class="form-group form-actions"><div class="col-sm-offset-2 col-sm-10">


        <input type="submit" value="<?php  echo   'save'; ?> Person" class="btn btn-primary btn-lg" />&nbsp;&nbsp;


    </div></div>

<?php echo form_close(); ?>

<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/footer'); ?>