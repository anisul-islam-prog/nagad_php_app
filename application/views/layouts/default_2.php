<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<?php $this->load->view('partials/header_2'); ?>


<!-- main content -->
<div id="contentwrapper">
    <div class="main_content">

        <?php   echo "<pre>";
         // print_r($users);
            echo "</pre>";

        //die();
        ?>

        <?php   if (isset($view_page)) { $this->load->view($view_page); } ?>

    </div>
</div><!--contentwrapper ends-->


<?php //$this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/footer'); ?>