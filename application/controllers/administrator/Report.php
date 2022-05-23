<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller
{
    var $current_page = "report";

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
        
        $this->load->model('report_model');
        
         /*MULTIPLE LOGIN CHECK */
       /* $this->load->model('user_model');
        $login_ip = $this->user_model->get_user_login_ip();
        if($login_ip != $this->session->userdata('access_ip')){
             redirect(base_url().'/logout');
        }*/
        /*MULTIPLE LOGIN CHECK */
        
        
    } // END OF CONSTRUCT
    
    
    public function campaign_eligibility() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_campaign_eligibility';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        // Start Populating Report Data
        $submit = $this->input->post('submit');
        
        /* Activity Log */ //$this->log_model->insert_log(2,'View Report-1.');

        $filter = array();

        if($submit!=FALSE){
            if($this->input->post("start_date")!=''){
                //$filter['start_date'] = $this->input->post("start_date");
                
                $filter['start_date'] = date("d-M-Y", strtotime($this->input->post("start_date")));
                
                $page_info['start_date'] = $this->input->post("start_date");
            }
           
            if($this->input->post("end_date")!=''){
                $filter['end_date'] =  date("d-M-Y", strtotime($this->input->post("end_date")));
                $page_info['end_date'] = $this->input->post("end_date");
            }
            
            if($this->input->post("msisdn")!=''){
                $page_info['msisdn'] = $this->input->post("msisdn");
                $filter['msisdn'] = '88018'.$this->input->post("msisdn");
            }
            
            $report_type = $this->input->post("report_type");
            
            $page_info['report_type']=$report_type;
            
            if($report_type==1){
                $page_info['table1_data'] = $this->report_model->get_campaign_eligibility($filter);
            }
            elseif($report_type==2){
                $page_info['table2_data'] = $this->report_model->get_campaign_eligibility_view($filter);
            }
            
          
            
        }


        // End of  Populating Report Data
        
        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF campaign_eligibility
    
    
    public function offer_wise_cust_browsing() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_offer_wise_cust_browsing';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        // Start Populating Report Data
        $submit = $this->input->post('submit');
        $export = $this->input->post('btn_export');
        
        /* Activity Log */ //$this->log_model->insert_log(2,'View Report-1.');
        
             if($this->input->post("offer_text")!=''){
                $filter['offer_text'] = $this->input->post("offer_text");
                $page_info['offer_text'] = $this->input->post("offer_text");
                
                if($this->input->post("from_date")!=''){
                
                    $filter['from_date'] = date("d-M-Y", strtotime($this->input->post("from_date")));

                    $page_info['from_date'] = $this->input->post("from_date");
                }

                if($this->input->post("to_date")!=''){
                    $filter['to_date'] =  date("d-M-Y", strtotime($this->input->post("to_date")));
                    $page_info['to_date'] = $this->input->post("to_date");
                }
                $page_info['table_data'] = $this->report_model->get_offer_wise_cust_browsing($filter);
                
               
                // Exporting Data
                if($export!=FALSE){
                    $records = $this->report_model->get_offer_wise_cust_list($filter);
                    $this->export_offer_wise_cust_browsing($records);
                }
                
            }
            else{
                $page_info['message_error'] = 'Offer Text Field Can not be Empty !!';
            }
        
        // End of  Populating Report Data
        
        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF offer_wise_cust_browsing
    
    
     public function export_offer_wise_cust_browsing($export_data) {
         if ($export_data) {
            foreach($export_data as $key => $value) {
                $data[$key]['GROUP_NAME']= $value['GROUP_NAME'];
                $data[$key]['CUSTOMER_MSISDN']= $value['CUSTOMER_MSISDN'];
                $data[$key]['MENU_NAME']= $value['MENU_NAME'];
                $data[$key]['CUST_OFFER_TITLE']= $value['CUST_OFFER_TITLE'];
                $data[$key]['VIEW_DATE']= $value['VIEW_DATE'];
            }
        
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=Offer_Wise_Customer_Browsing_List-".date('Y-m-d').".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w');

            fputcsv($file, array(
                'Segment Name',
                'MSISDN',
                'Menu(L1)',
                'Menu(L2)',
                'View Date'
                ));
                foreach ($data as $value) {
                    $rowD= $value;
                    fputcsv($file, $rowD);
                }
                exit();
        } 
        else {
            $this->session->set_flashdata('message_error', 'No records found .');
        }
     }
    
    
     
     
     
     
    
} // END OF CLASS