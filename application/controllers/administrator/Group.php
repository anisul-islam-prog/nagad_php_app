<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends MY_Controller
{
    var $current_page = "group";

    function __construct()
    {
        parent::__construct();
        
        $this->ci =& get_instance();

        // check if already logged in
        if ( !$this->session->userdata('is_logged_in') ) {
            redirect('');
        } else {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type != 'admin') {
                redirect('');
            }
        }
        
        //error_reporting(0);
        
        // load necessary library and helper
        $this->load->config("pagination");
        $this->load->library("pagination");
        $this->load->library('table');
        $this->load->library('form_validation');
        $this->load->library('excel');
        $this->load->model('offer_model');
        $this->load->model('menu_model');
        $this->load->model('retailer_group_model');
        $this->load->model('group_model');
        $this->load->model('log_model');
        
         /*MULTIPLE LOGIN CHECK */
        /*$this->load->model('user_model');
        $login_ip = $this->user_model->get_user_login_ip();
        if($login_ip != $this->session->userdata('access_ip')){
             redirect(base_url().'/logout');
        }*/
        /*MULTIPLE LOGIN CHECK */
        
    } // END OF CONSTRUCT
    
    
     public function view_group() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_group';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $page_info['table_data'] = $this->group_model->get_groups();

        // load view
        $this->load->view('layouts/default', $page_info);
     } // END OF view_group
     
     
    public function edit_group() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_group';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $group_id = $this->input->post('group_id');
        $is_active = $this->input->post('is_active');
        if($is_active==FALSE){
            $is_active=0;
        }
        else{
            $is_active=1;
        }
        
        $update = $this->group_model->edit_group($data=array('ISACTIVE'=>$is_active),$group_id);
                
        if($update==TRUE){
            $page_info['message_success'] = 'Update is Successful';
        }
        else{
            $page_info['message_error']='Update is not Successful';
        }
        
        
        $page_info['table_data'] = $this->group_model->get_groups();
        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF edit_group 
     
     
     
     public function add_retailer_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_retailer_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF add_retailer_group
    
    public function do_add_retailer_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_retailer_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $this->_set_rules_add_rtl();

        $retailer_group_name = $this->input->post('retailer_group_name');
        $commission_amount = $this->input->post('commission_amount');
        
      
        if ($this->form_validation->run() == TRUE) {
            $is_exists = $this->retailer_group_model->is_rtl_grp_exists($retailer_group_name);
            if($is_exists['IS_EXISTS']==1){
                $page_info['message_error'] = 'Retailer Group Name Already Exists !!';
            }
            else{
                $data['RETAIL_GROUP_NAME'] = $retailer_group_name;
                $data['COMMISSION_AMOUNT'] = $commission_amount;
                $data['CREATEDBY'] = $this->session->userdata('user_login_name');
                
                $insert = $this->retailer_group_model->add_retailer_group($data);
                if($insert==TRUE){
                    $page_info['message_success'] = 'Add is Successful';
                }
                else{
                    $page_info['message_error'] = 'Add is not Successful';
                }
                
            }
            
        }

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF do_add_retailer_group
    
    
    private function _set_rules_add_rtl(){
        $this->form_validation->set_rules('retailer_group_name', 'Retailer Group Name', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('commission_amount', 'Commission Amount', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_add_rtl_menu
    
} // END OF CLASS