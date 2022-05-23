<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <link rel="shortcut icon" href="<?php echo base_url('assets/favicon.ico'); ?>" />


    <title><?php if (isset($title)) { echo $title; } else { echo $this->site_name; } ?></title>

    <!--styles-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui-1.8.22.custom.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/chosen/chosen.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fonts/fontface.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/login.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/frontend.css'); ?>" />

    <!--scripts-->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery-1.7.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui-1.8.22.custom.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.cookie.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.actual.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/chosen/chosen.jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/application-script.js'); ?>"></script>
    <script type="text/javascript">
        var baseUrlJs = '<?php echo base_url(); ?>';
    </script>

</head>

<body>

<div id="wrap">
<div class="header-wrap">


    <p class="" align="center" style="margin-top:5%;">
        <a href="<?php echo base_url('home'); ?>">
            <img src="<?php echo base_url('assets/images/xsm-logo.png.pagespeed.ic.RdW64fuwRl.webp'); ?>" alt="ROBI" />
        </a>
        
    </p>


    <?php if ($this->current_page != 'login') : ?>
    <div class="controls">


        <div class="pull-right btn-group">
            <button class="btn">Hello</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<?php echo base_url('profile'); ?>"><?php echo lang('header_menu_update'); ?></a></li>
                <li><a href="<?php echo base_url('profile/password'); ?>"><?php echo lang('header_menu_password'); ?></a></li>
                <li class="divider"></li>
                <li><a href="<?php echo base_url('logout'); ?>"><?php echo lang('header_menu_logout'); ?></a></li>
            </ul>
        </div>

        <form method="get" action="<?php echo base_url('search'); ?>" class="form-search pull-right">
            <div class="input-append">
                <input type="text" name="s" value="<?php if (isset($search_term) && $search_term != '') { echo $search_term; } ?>"
                    class="input-medium search-query" placeholder="<?php echo lang('header_placeholder_search'); ?>" />
                <button type="submit" class="btn"><i class="icon-search"></i></button>
            </div>
        </form>


        <?php if ($this->exam_site_link != ''): ?>
        <div class="pull-right" style="margin-right: 15px;">
            <?php echo anchor($this->exam_site_link, lang('header_label_take_exams'), array('class' => 'btn', 'target' => '_blank')); ?>
        </div>
        <?php endif; ?>


        <div class="btn-group pull-right" style="margin-right: 15px;">

            <?php if ($this->links): ?>
            <a id="main-links" class="btn" title="<?php echo lang('home_header_links'); ?>" data-placement="bottom"
               data-original-title="<?php echo lang('home_header_links'); ?>"><i class="icon-url"></i>
            </a>
            <script type="text/javascript"> var linksHtmlJS = '<?php echo $this->links_html; ?>'; </script>
            <?php endif; ?>

            <?php if ($this->language == 'en') : ?>
                <a href="<?php echo base_url('language'); ?>" class="btn">বাংলা</a>
            <?php elseif ($this->language == 'bn'): ?>
                <a href="<?php echo base_url('language'); ?>" class="btn">English</a>
            <?php endif; ?>

        </div>


    </div>
    <?php endif; ?>

    
    <?php if ($this->current_page == 'landing' || $this->current_page == 'login') { echo '</div>'; }?>

</div><!--header-wrap ends-->


<?php if ($this->current_page != 'landing' && $this->current_page != 'login') : ?>
<div class="headersub-wrap"><div class="left"></div><div class="right"></div>

    <?php if ($this->notices) { echo $this->notices_html; } ?>

</div><!--headersub-wrap ends-->
<?php endif; ?>
