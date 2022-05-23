<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    var $current_page = "dashboard";

    function __construct()
    {
        parent::__construct();

        // check if already logged in
        if ( !$this->session->userdata('is_logged_in') ) 
        {
            redirect('');
        } 
        else 
        {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type != 'admin') 
            {
                redirect('');
            }
        }
        
        
        $this->load->model('smpp_model/global_select_query');
        $this->load->model('smpp_model/global_insert_query');
        $this->load->model('smpp_model/global_update_query');
        $this->load->model('smpp_model/global_delete_query');
    }
  

    /**
     * Display Administrator Dashboard page
     * @return void
     */
    public function index()
    {
        $page_info['title'] = 'Dashboard'. $this->site_name;
        $page_info['view_page'] = 'administrator/dashboard_view';
        $user_menu = $this->session->userdata('user_menu');
        // load view
        //print_r($user_menu); die();
        
        $checkArray = array('TRUNC(DELIVERED_DATE)'=>'TRUNC(SYSDATE)');
        
        $report_robi_info = $this->global_select_query->select_data_by_where_single_column('SMPP_ROBI','TRUNC(DELIVERED_DATE)','TRUNC(SYSDATE)');
        $report_gp_info = $this->global_select_query->select_data_by_where_single_column('SMPP_GP','TRUNC(DELIVERED_DATE)','TRUNC(SYSDATE)');
         $report_bl_info = $this->global_select_query->select_data_by_where_single_column('SMPP_BL','TRUNC(DELIVERED_DATE)','TRUNC(SYSDATE)');
        $report_tt_info = $this->global_select_query->select_data_by_where_single_column('SMPP_TT','TRUNC(DELIVERED_DATE)','TRUNC(SYSDATE)');
	//var_dump($report_robi_info);die;
        $page_info['gp_info'] = $report_robi_info;
        $page_info['robi_info'] = $report_robi_info;
        $page_info['bl_info'] = $report_bl_info;
        $page_info['tt_info'] = $report_tt_info;
        
        $this->load->view('layouts/default', $page_info);
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */