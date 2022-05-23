<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="<?php echo base_url('assets/favicon.ico'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <title><?php if (isset($title)) { echo $title; } else { echo $this->site_name. ' Admin Panel'; } ?></title>


    <!--styles-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui-1.8.22.custom.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/red.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/datepicker/datepicker.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/datepicker/timepicker.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/daterangepicker/daterangepicker.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/libs/chosen/chosen.css'); ?>" />


    <!--[if lte IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/ie.css'); ?>" />
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/html5.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/respond.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/excanvas.min.js'); ?>"></script>
    <![endif]-->


    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/fonts/fontface.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles/backend.css'); ?>" />
    
    
    
    
    


    <!--scripts-->
    
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery-1.7.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui-1.8.22.custom.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.debouncedresize.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.actual.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/jquery.cookie.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/ios-orientationchange-fix.js'); ?>"></script> <!-- fix for ios orientation change -->
    <script type="text/javascript" src="<?php echo base_url('assets/libs/antiscroll/antiscroll.js'); ?>"></script>   <!-- scrollbar -->
    <script type="text/javascript" src="<?php echo base_url('assets/libs/antiscroll/jquery-mousewheel.js'); ?>"></script>   <!-- scrollbar -->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/gebo_common.js'); ?>"></script>   <!-- gebo common functions -->
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/date.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/datepicker/bootstrap-datepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/datepicker/bootstrap-timepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/daterangepicker/daterangepicker.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/chosen/chosen.jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/libs/highcharts/highcharts.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/scripts/application-script.js'); ?>"></script>
    <script type="text/javascript">
     
     
        document.documentElement.className += 'js';    /* hide all elements & show preloader */
        var siteUrlJs = '<?php echo site_url(); ?>';
       jQuery(document).ready(function() {
           setTimeout('$("html").removeClass("js")', 300);    /* show all elements & remove preloader */
        });
        
    </script>


</head>
<body>

    
<div id="loading_layer" style="display:none">
    <img src="<?php echo base_url('assets/images/ajax_loader.gif'); ?>" alt="loading..." />
</div>

<div id="maincontainer" class="clearfix">


    <header><div class="navbar navbar-fixed-top"><div class="navbar-inner"><div class="container-fluid">

        <a class="brand" href="#"><i class="glyphicon glyphicon-home glyphicon-white"></i> <?php echo str_replace(':: ', '&nbsp;', $this->site_name); ?></a>

        <ul class="nav user_menu pull-right">
            <li><a href="<?php echo site_url('logout'); ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            <!--<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">User Links <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php /*echo base_url('administrator/profile'); */?>">My Profile</a></li>
                    <li><a href="<?php /*echo base_url('administrator/profile/password'); */?>">Change Password</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php /*echo site_url('logout'); */?>">Log Out</a></li>
                </ul>
            </li>-->
        </ul>
        <ul class="nav user_menu pull-right">
            <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->session->userdata('user_name'); ?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <!--<li><a href="<?php echo base_url().'administrator/user/edit_profile' ?>"> <i class="glyphicon glyphicon-user"></i> Update Profile</a></li>-->
                  <!--<li><a href="<?php echo base_url().'administrator/user/change_password' ?>"><i class="glyphicon glyphicon-refresh"></i> Change Password</a></li>-->
                  
                  <li class="divider"></li>
                  <li><a href="<?php echo site_url('logout'); ?>"> <i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                </ul>
                
            </li>
        </ul>
        
        <ul class="nav user_menu pull-right">
            <li><a href="<?php echo site_url('help'); ?>"><span class="glyphicon glyphicon-question-sign"></span> Help</a></li>
        </ul>

    </div></div></div></header><!--header ends-->

