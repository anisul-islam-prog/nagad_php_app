<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php //var_dump ($this->role_model->isVisibleParentMenu(array(1, 2, 3, 4))); die(); ?>


  
<!-- sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">

    <div class="antiScroll"><div class="antiscroll-inner"><div class="antiscroll-content">

        <div class="sidebar_inner">

            <div id="side_accordion" class="accordion">

                <?php if ($this->session->userdata('logged_in_type') == 'admin'): ?>
                    <div class="single-sidebar <?php if ($this->current_page == 'dashboard') { echo ' sdb_h_active'; }?>">
                        <a href="<?php echo site_url('administrator/dashboard'); ?>">
                            <i class="glyphicon glyphicon-home"></i> Home
                        </a>
                    </div>

                    <div class="panel-group">
                        <div class="panel-heading">
                            <a href="#collapseUser" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                <i class="glyphicon glyphicon-user"></i> User Management
                            </a>
                        </div>
                        <div class="panel-body <?php if ($this->current_page == 'user_management') { echo ' in collapse'; } else { echo ' collapse'; } ?>" id="collapseUser">
                            <div class="accordion-inner">
                                <ul class="nav nav-list">
                                    <li>
                                        <a href="<?php echo site_url('dnd_controllers/user_management/user_add_view'); ?>">
                                            <i class="glyphicon glyphicon-adjust"></i> Add User 
                                        </a> 
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('dnd_controllers/user_management/user_lists'); ?>">
                                            <i class="glyphicon glyphicon-adjust"></i> User List
                                        </a> 
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('dnd_controllers/user_management/role'); ?>">
                                            <i class="glyphicon glyphicon-adjust"></i> Add Role
                                        </a> 
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                
                
                
                <div class="panel-group">
                        <div class="panel-heading">
                            <a href="#collapseReport" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                <i class="glyphicon glyphicon-user"></i> Dashboard and Report 
                            </a>
                        </div>
                        <div class="panel-body <?php if ($this->current_page == 'admin_report') { echo ' in collapse'; } else { echo ' collapse'; } ?>" id="collapseReport">
                            <div class="accordion-inner">
                                <ul class="nav nav-list">
								
								<?php if(in_array('Admin Reports', $this->session->userdata('user_menu')) || $this->session->userdata('user_login_type') == 'admin') { ?>
                                         <li>
                                        <a href="<?php echo site_url('dnd_controllers/campaign/admin_reports'); ?>">
                                            <i class="glyphicon glyphicon-adjust"></i> Reports 
                                        </a> 
                                    </li>
                                   <?php } ?>
					
								   
								   <?php if(in_array('msisdn_search', $this->session->userdata('user_menu')) || $this->session->userdata('user_login_type') == 'admin') { ?>
                                         <li>
                                        <a href="<?php echo site_url('dnd_controllers/campaign/msisdn_report_search'); ?>">
                                            <i class="glyphicon glyphicon-adjust"></i> Search  
                                        </a> 
                                    </li>
                                   <?php } ?>
								    
								    
								   
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                


                    
                <?php endif; ?>

            </div>

            <div class="push"></div>
        </div>

        
                
    </div></div></div>
    
    
    
  

</div><!--sidebar ends-->


