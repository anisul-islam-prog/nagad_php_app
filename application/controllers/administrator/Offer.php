<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends MY_Controller
{
    var $current_page = "offer";

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
        
        error_reporting(0);
        
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
       /* $this->load->model('user_model');
        $login_ip = $this->user_model->get_user_login_ip();
        if($login_ip != $this->session->userdata('access_ip')){
             redirect(base_url().'/logout');
        }*/
        /*MULTIPLE LOGIN CHECK */
        
        // Increasing default download file size and Execution time 
        ini_set('memory_limit', '999999999999M');
        ini_set('max_execution_time', 5000);
        
        
    } // END OF CONSTRUCT
    
    
    public function view_offer() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_offer';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $all_retailers = $this->offer_model->get_retailer_group();
        for($i=0; $i<count($all_retailers); $i++) {
            $this->rtl_group_list[$all_retailers[$i]['RETAIL_GROUP_ID']] = $all_retailers[$i]['RETAIL_GROUP_NAME'];
        }   
        $retailers = $all_retailers;
        $rtl_ids = array();
        for($i=0; $i<count($retailers); $i++) {
            $grp_ids[] = (int)$retailers[$i]['RETAIL_GROUP_ID'];
        }
        $this->form_data->grp_ids = array(); // $cmp_ids;
        
        
        
        // Start Populating Report Data
        $submit = $this->input->post('submit');
        $export = $this->input->post('btn_export');
        
       
        /* Activity Log */ //$this->log_model->insert_log(2,'View Report-1.');

        $filter['date_filter'] = 'no';


        if($submit!=FALSE || $export!=FALSE){
            if($this->input->post("date_range")!=''){
                $page_info['date_range'] = $this->input->post("date_range");
                $this->session->set_userdata('date_range', $this->input->post("date_range"));
                $date_range = explode('-', $this->input->post("date_range"));
                $filter['date_from'] = date("d-M-Y", strtotime($date_range[0]));
                $filter['date_to'] = date("d-M-Y", strtotime($date_range[1]));
                $filter['date_filter'] = 'yes';
            }
            else{
                $this->session->unset_userdata('date_range');
            }

            if($this->input->post("offer_id")!=''){
                $page_info['offer_id'] = $this->input->post("offer_id");
                $this->session->set_userdata('offer_id', $this->input->post("offer_id"));
                $filter['offer_id'] = $this->input->post("offer_id");
            }
            else{
                $this->session->unset_userdata('offer_id');
            }

            
            if($this->input->post("menu_id")!=''){
                $page_info['menu_id'] = $this->input->post("menu_id");
                $this->session->set_userdata('menu_id', $this->input->post("menu_id"));
                $filter['menu_id'] = $this->input->post("menu_id");
            }
            else{
                $this->session->unset_userdata('menu_id');
            }

        }


        if($this->session->userdata('date_range')!=''){
            $page_info['date_range'] = $this->session->userdata('date_range');
            $date_range = explode('-', $this->session->userdata('date_range'));
            $filter['date_from'] = date("d-M-Y", strtotime($date_range[0]));
            $filter['date_to'] = date("d-M-Y", strtotime($date_range[1]));
            $filter['date_filter'] = 'yes';
        }

         if($this->session->userdata('offer_id')!=''){
            $page_info['offer_id'] = $this->session->userdata('offer_id');
            $filter['offer_id'] = $this->session->userdata('offer_id');
        }

        
         if($this->session->userdata('menu_id')!=''){
            $page_info['menu_id'] = $this->session->userdata('menu_id');
            $filter['menu_id'] = $this->session->userdata('menu_id');
        }
       

        $per_page = $this->config->item('per_page');
        $uri_segment = $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        //var_dump($filter); die();

        $total_rows = $this->offer_model->get_offers_pegined_count($filter);
        $page_info['record_total'] = (int)$total_rows;
        $page_info['record_start'] = (int)$page_offset + 1;
        $page_info['record_end'] = (int)$page_offset + (int)$per_page;
        if ($page_info['record_end'] > $total_rows) {
            $page_info['record_end'] = $total_rows;
        }


        if($export!=FALSE){
            $export=TRUE;
        }
        else{
             $export=FALSE;
        }
            
        $page_info['table_data'] = $this->offer_model->get_offers_pegined($filter, $page_offset, $per_page,$export);
        $records = $page_info['table_data'];
        
        
        // Exporting Data
        if($export!=FALSE){
            $this->export_offers($records);
        }
        
        // build paginated list
        $config = array();
        $config["base_url"] = base_url() . "administrator/offer/view_offer/";
        $config["total_rows"] = $total_rows;
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
            
      
        // End of  Populating Report Data
        
        // load view
        $this->load->view('layouts/default', $page_info);
     } // END OF view_offer
     
     
     private function export_offers($export_data){
        
        if ($export_data) {
            foreach($export_data as $key => $value) {
                
                if($data['RETAILER_ISVISIBLE']==1){
                    $is_active = 'Activated';
                }
                else if($data['RETAILER_ISVISIBLE']==0){
                    $is_active = 'Deactivated';
                }
                
                
                $data[$key]['OFFER_ID']= $value['OFFER_ID'];
                $data[$key]['CUST_OFFER_TITLE']= $value['CUST_OFFER_TITLE'];
                $data[$key]['RETAIL_OFFER_TITLE']= $value['RETAIL_OFFER_TITLE'];
                $data[$key]['CUSTOMER_OFFER_TEXT']= $value['CUSTOMER_OFFER_TEXT'];
                $data[$key]['RETAIL_OFFER_TEXT']= $value['RETAIL_OFFER_TEXT'];
                $data[$key]['MENU_NAME']= $value['MENU_NAME'];
                
                $data[$key]['RETAILER_ISVISIBLE']= $is_active;
                $data[$key]['OFFER_DETAILS']= $value['OFFER_DETAILS'];
                $data[$key]['OFFER_AMOUNT']= $value['OFFER_AMOUNT'];
                $data[$key]['OFFER_AMOUNT_DESCRIPTION']= $value['OFFER_AMOUNT_DESCRIPTION'];
                $data[$key]['CREATEDATE']= $value['CREATEDATE'];
               
            }
        
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=Offers-".date('Y-m-d').".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w');

            fputcsv($file, array(
                'Offer ID',
                'CUSTOMER OFFER TITLE',
                'RETAIL OFFER TITLE',
                'CUSTOMER OFFER TEXT',
                'RETAIL OFFER TEXT',
                'MENU NAME',
                'RETAILER ISVISIBLE',
                'OFFER DETAILS',
                'OFFER AMOUNT',
                'OFFER AMOUNT DESCRIPTION',
                'CREATEDATE'
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
    } // END OF export_offers
     
     
     
     
     
     
    public function add_offer(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_offer_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $page_info['menu_list'] = $this->menu_model->get_all_menu();

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF add_offer
    
    public function do_add_offer(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_offer_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();
        
        $this->_set_rules_add_offer();

        $offer_title = $this->input->post('offer_title');
        
        $is_retailer_visible = $this->input->post('is_retailer_visible');
        if($is_retailer_visible==FALSE){
            $is_retailer_visible=0;
        }
        else{
            $is_retailer_visible=1;
        }
        
      
        if ($this->form_validation->run() == TRUE) {
            //$is_exists = $this->offer_model->is_offer_exists($offer_title);
            //if($is_exists['IS_EXISTS']==1){
            if(1==2){
                $page_info['message_error'] = 'Offer Title Already Exists !!';
            }
            else{
                $data['CUST_OFFER_TITLE'] = $offer_title;
                $data['RETAIL_OFFER_TITLE'] = $this->input->post('retail_offer_title');
                
                
                $data['OFFER_DETAILS'] = $this->input->post('offer_details');
                $data['CUSTOMER_OFFER_TEXT'] = $this->input->post('customer_offer_text');
                $data['RETAIL_OFFER_TEXT'] = $this->input->post('retail_offer_text');
                //$data['CUSTOMER_ISVISIBLE'] = $is_customer_visible;
                $data['RETAILER_ISVISIBLE'] = $is_retailer_visible;
                $data['MENU_ID'] = $this->input->post('menu_name');
                
                $data['OFFER_AMOUNT'] = $this->input->post('offer_amount');
                $data['OFFER_AMOUNT_DESCRIPTION'] = $this->input->post('offer_amount_description');
               
                $data['CREATEDBY'] = $this->session->userdata('user_login_name');
                
                $insert = $this->offer_model->add_offer($data);
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
    } // END OF do_add_offer
    
     public function upload_bulk_offer(){
         // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_offer_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();
        
        
        // UPLOADING FILE
        $upload_path = './uploads/BULK_OFFER/' ;
        if(!dir($upload_path)){
            mkdir($upload_path);
        }
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size']	= '9999999';

        $this->load->library('upload', $config);
        if($this->upload->do_upload()){
            $upload_data = $this->upload->data();
            $name_array = $upload_data['file_name'];
            $upload_path = $upload_path.$upload_data['file_name'];
            
            $objPHPExcel = new PHPExcel();
            $file_path = $upload_path;
            $objPHPExcel = PHPExcel_IOFactory::load($file_path);
            $objPHPExcel->setActiveSheetIndex(0);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            @unlink($file_path);
            
            $total = count($sheetData);
            
            $data_is_ok = TRUE;

            for($i=2;$i<=$total;$i++){
                if( !is_null($sheetData[$i]['A']) && !is_null($sheetData[$i]['B']) && !is_null($sheetData[$i]['C']) && !is_null($sheetData[$i]['D']) && !is_null($sheetData[$i]['E']) && !is_null($sheetData[$i]['F']) && !is_null($sheetData[$i]['G']) && !is_null($sheetData[$i]['H']) && !is_null($sheetData[$i]['I'])    ){
                    $data[$i]['CUST_OFFER_TITLE'] = str_replace("'", "-", $sheetData[$i]['A']);
                    $data[$i]['RETAIL_OFFER_TITLE'] = str_replace("'", "-", $sheetData[$i]['B']);
                    $data[$i]['OFFER_DETAILS'] = str_replace("'", "-", $sheetData[$i]['C']);
                    $data[$i]['CUSTOMER_OFFER_TEXT'] = str_replace("'", "-", $sheetData[$i]['D']);
                    $data[$i]['RETAIL_OFFER_TEXT'] = str_replace("'", "-", $sheetData[$i]['E']);
                    $data[$i]['RETAILER_ISVISIBLE'] = $sheetData[$i]['F'];
                    $data[$i]['OFFER_AMOUNT'] = $sheetData[$i]['G'];
                    $data[$i]['OFFER_AMOUNT_DESCRIPTION'] = str_replace("'", "-", $sheetData[$i]['H']);
                    $data[$i]['MENU_ID'] = $sheetData[$i]['I'];
                    $data[$i]['CREATEDBY'] = $this->session->userdata('user_login_name');
                }
                else{
                    $data_is_ok = FALSE;
                    $page_info['message_error'] = 'Upload Failed !! <br/> Reason : Value Missing . ';
                }
            }
            
            
            }
            else{ // WHEN UPLOAD FAILED
                $page_info['message_error'] = $this->ci->upload->display_errors();
            }    
                
                // INSERTING INTO REMPORARY TABLE
                if($data_is_ok==TRUE){
                    $insert_tmp = $this->offer_model->add_offer_tmp($data);
                    if($insert_tmp==TRUE){
                        
                        
                        $duplicate_title_in_file = $this->offer_model->get_duplicate_offer_title_in_file();
                        /* if(count($duplicate_title_in_file)==0){
                            $duplicate_offer_title = $this->offer_model->get_duplicate_offer_title();
                            if(count($duplicate_offer_title)==0){
                                $duplicate_retail_offer_title = $this->offer_model->get_duplicate_retail_offer_title();
                                if(count($duplicate_retail_offer_title)==0){*/
                                     $invalid_menu_id = $this->offer_model->get_invalid_menu_id();
                                    if(count($invalid_menu_id)==0){
                                        $final_insert = $this->offer_model->add_offer_bulk($data);
                                        if($final_insert==TRUE){
                                            $page_info['message_success'] = 'Upload Successful !!';
                                        }
                                        else{
                                            $page_info['message_error'] = 'Upload Failed !!';
                                        }
                                    }
                                    else{
                                        $err_msg='Upload Failed. <br/>Reason: Invalid Menu ID. <Br/>Menu ID: ';
                                        foreach ($invalid_menu_id as $title) {
                                            $err_msg = $err_msg.' , '.$title['MENU_ID'];
                                        }
                                        $page_info['message_error'] = $err_msg;
                                    }    
                               /* }
                                else{
                                    $err_msg='Upload Failed. <br/>Reason: Retailer Offer Title already Exists. <Br/>Retailer Offer Title: ';
                                    foreach ($duplicate_offer_title as $title) {
                                        $err_msg = $err_msg.' , '.$title['RETAIL_OFFER_TITLE'];
                                    }
                                    $page_info['message_error'] = $err_msg;
                                }
                            }
                            else{
                                $err_msg='Upload Failed. <br/>Reason: Offer Title already Exists. <Br/>Offer Title: ';
                                foreach ($duplicate_offer_title as $title) {
                                    $err_msg = $err_msg.' , '.$title['CUST_OFFER_TITLE'];
                                }
                                $page_info['message_error'] = $err_msg;
                            }
                        }
                        else{
                            $err_msg='Upload Failed. <br/>Reason: Duplicate Offer Title found in File. <Br/>Offer Title: ';
                                foreach ($duplicate_title_in_file as $title) {
                                    $err_msg = $err_msg.' , '.$title['CUST_OFFER_TITLE'];
                                }
                                $page_info['message_error'] = $err_msg;
                        }
                       */
                      
                    }   
                    
                }
                  
                
                
                
                
           
        
        
        // TRUNCATING TEMPORARU TABLE
        $this->offer_model->truncate_tmp_tbl();

        // load view
        $this->load->view('layouts/default', $page_info);
     }// END OF upload_bulk_offer
     
    
     public function add_default_offer(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_add_default_offer';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();
        
        if($this->input->post('search')){
            $offer_id = $this->input->post('offer_id');
            $offer_details = $this->offer_model->get_offer($offer_id);
            //var_dump($offer_details); die();
            if(count($offer_details)>0){
                $page_info['offer_id'] = $offer_details->OFFER_ID;
                $page_info['offer_tittle'] = $offer_details->CUST_OFFER_TITLE;
                $page_info['offer_text'] = $offer_details->CUSTOMER_OFFER_TEXT;
            }
            else{
                $page_info['message_error'] = 'Invalid Offer ID !!';
            }
            
        }
        

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF add_default_offer
    
    public function do_add_default_offer(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_add_default_offer';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();
        
        
        
        $this->_set_rules_add_default_offer();

        $offer_id = $this->input->post('offer_id');
        $menu_id = $this->input->post('menu_id');
        
        
        
      
        if ($this->form_validation->run() == TRUE) {
            $is_valid_offer_id = $this->offer_model->is_offer_exists($offer_id);
            if($is_valid_offer_id['IS_EXISTS']==1){
                $page_info['message_error'] = 'Invalid Offer ID !!';
            }
            else{
                $m_name = $this->menu_model->get_menu($menu_id);
                $menu_name = $m_name->MENU_NAME;
                $d_text = $this->offer_model->get_offer($offer_id);
                $default_text=$d_text->CUSTOMER_OFFER_TEXT;

                $start_date = $this->input->post('start_date');
                $start_time = $this->input->post('start_time');
                $end_date = $this->input->post('end_date');
                $end_time = $this->input->post('end_time');
                $start_date_time = date("d-M-Y g:i:s a", strtotime($start_date.$start_time));
                $end_date_time = date("d-M-Y g:i:s a", strtotime($end_date.$end_time));

                $is_approved = $this->input->post('is_approved');
                if($is_approved==FALSE){
                    $is_approved=0;
                }
                else{
                    $is_approved=1;
                }
                $data['MENU_ID'] = $menu_id;
                $data['MENU_NAME'] = $menu_name;
                $data['DEFAULT_TEXT'] = $default_text;
                $data['IS_DEFAULT_OFFER'] = $is_approved;
                $data['START_DATE'] = $start_date_time;
                $data['END_DATE'] = $end_date_time;
                $data['OFFER_ID'] = $offer_id;
                
                
                //Checking Date Conflict 
                $date_conflict = $this->offer_model->check_date_conflict($menu_id,$start_date_time,$end_date_time);
                
                //var_dump($date_conflict); die();
                
                if(count($date_conflict) == 0 ){
                   $insert = $this->offer_model->add_default_offer($data);
                    if($insert==TRUE){
                        $page_info['message_success'] = 'Add is Successful';
                    }
                    else{
                        $page_info['message_error'] = 'Add is not Successful';
                    }
                    
                }
                else{
                    $page_info['message_error'] = 'Date Range Conflicts with previous assigned default offer of this Menu ID';
                }
            }
            
        }
        
        

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF do_add_default_offer
    
    
    public function view_default_offer() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_default_offer';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        // Start Populating Report Data
        $submit = $this->input->post('submit');
        $export = $this->input->post('btn_export');
        
       
        /* Activity Log */ //$this->log_model->insert_log(2,'View Report-1.');

        $filter['date_filter'] = 'no';


        if($submit!=FALSE || $export!=FALSE){
            
            if($this->input->post("offer_id")!=''){
                $page_info['offer_id'] = $this->input->post("offer_id");
                $this->session->set_userdata('offer_id', $this->input->post("offer_id"));
                $filter['offer_id'] = $this->input->post("offer_id");
            }
            else{
                $this->session->unset_userdata('offer_id');
            }
            
            if($this->input->post("menu_id")!=''){
                $page_info['menu_id'] = $this->input->post("menu_id");
                $this->session->set_userdata('menu_id', $this->input->post("menu_id"));
                $filter['menu_id'] = $this->input->post("menu_id");
            }
            else{
                $this->session->unset_userdata('menu_id');
            }

        }



         if($this->session->userdata('offer_id')!=''){
            $page_info['offer_id'] = $this->session->userdata('offer_id');
            $filter['offer_id'] = $this->session->userdata('offer_id');
        }

        
         if($this->session->userdata('menu_id')!=''){
            $page_info['menu_id'] = $this->session->userdata('menu_id');
            $filter['menu_id'] = $this->session->userdata('menu_id');
        }
       

        if($export!=FALSE){
            $export=TRUE;
        }
        else{
             $export=FALSE;
        }
            
        $page_info['table_data'] = $this->offer_model->get_default_offers($filter);
        $records = $page_info['table_data'];
        
        
        // Exporting Data
        if($export!=FALSE){
            $this->export_default_offers($records);
        }
        
        // build paginated list
        $config = array();
        $config["base_url"] = base_url() . "administrator/offer/view_offer/";
        $config["total_rows"] = $total_rows;
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
            
      
        // End of  Populating Report Data
        
        // load view
        $this->load->view('layouts/default', $page_info);
     } // END OF view_default_offer
        
    
     //view_default_offer
     
    public function edit_offer(){
        
        $offer_id = $this->input->post('offer_id');
        $offer_title = $this->input->post('offer_title');
        //$retail_offer_title = $this->input->post('retail_offer_title');
        
        // var_dump($this->input->post('is_retailer_visible')); die();
       
        $is_retailer_visible = $this->input->post('is_retailer_visible');
        if($is_retailer_visible==FALSE){
            $is_retailer_visible=0;
        }
        else{
            $is_retailer_visible=1;
        }
        
         $this->_set_rules_add_offer();
      
        if ($this->form_validation->run() == TRUE) {
            //$is_exists = $this->offer_model->is_offer_exists_edit($offer_title,$offer_id);
            //if($is_exists['IS_EXISTS']==1){
            if(1==2){
                $this->session->set_flashdata('message_error','Offer Title Already Exists !!');
            }
            else{
                $data['CUST_OFFER_TITLE'] = $offer_title;
                $data['RETAIL_OFFER_TITLE'] = $this->input->post('retail_offer_title');
                $data['OFFER_DETAILS'] = $this->input->post('offer_details');
                $data['CUSTOMER_OFFER_TEXT'] = $this->input->post('customer_offer_text');
                $data['RETAIL_OFFER_TEXT'] = $this->input->post('retail_offer_text');
                $data['RETAILER_ISVISIBLE'] = $is_retailer_visible;
                $data['OFFER_AMOUNT'] = $this->input->post('offer_amount');
                $data['OFFER_AMOUNT_DESCRIPTION'] = $this->input->post('offer_amount_description');
                $data['CREATEDBY'] = $this->session->userdata('user_login_name');
                
                $update = $this->offer_model->edit_offer($data,$offer_id);
                
                if($update==TRUE){
                    $this->session->set_flashdata('message_success','Update is Successful');
                }
                else{
                    $this->session->set_flashdata('message_error', 'Update is not Successful');
                }
                
                
            }
            
        }
         else{
            $this->session->set_flashdata('message_error', validation_errors());
        }

        // load view
        //$this->view_offer();
        redirect('administrator/offer/view_offer');
        //$this->load->view('layouts/default', $page_info);
    } // END OF edit_offer
    
    
    public function add_comission(){
        
        //var_dump($this->input->post('offer_id_com')); die();
        
        $this->_set_rules_add_commission();
      
        if($this->form_validation->run() == TRUE) {
            
            $offer_id = $this->input->post('offer_id_com');
            $amount = $this->input->post('amount');
            $grp_ids = $this->input->post('grp_ids');
            
            $is_commission = $this->input->post('is_commission');
            if($is_commission==FALSE){
                $is_commission=0;
            }
            else{
                $is_commission=1;
            }
            
            $i=1;
            foreach ($grp_ids as $gkey => $gvalue) {
                $data[$i]['OFFER_ID'] = $offer_id;
                $data[$i]['RETAIL_GROUP_ID'] = $gvalue;
                $data[$i]['COMMISSION_AMOUNT'] = $amount;
                $data[$i]['ISCOMMISSION'] = $is_commission;
                $i++;
            }
                
            $add = $this->offer_model->add_commission($data,$offer_id,$grp_ids);
            
           

            if($add==TRUE){
                $this->session->set_flashdata('message_success','Add is Successful');
            }
            else{
                $this->session->set_flashdata('message_error', 'Add is not Successful');
            }
          
            
        }
        else{
            $this->session->set_flashdata('message_error', validation_errors());
        }

        // load view
        //$this->view_offer();
        redirect('administrator/offer/view_offer');
        //$this->load->view('layouts/default', $page_info);
    } // END OF add_comission
    
    public function edit_default_offer(){
        
        // UPDATE OPERATIONS
       
        $default_id = $this->input->post('default_id');
        
        $is_approved = $this->input->post('is_active');
        if($is_approved==FALSE){
            $data['IS_DEFAULT_OFFER'] = 0;
        }
        else{
            $data['IS_DEFAULT_OFFER'] = 1;
        }
          
        
        if($this->input->post('start_date')!=''){
            $start_date = $this->input->post('start_date');
            $start_time = $this->input->post('start_time');
            $start_date_time = date("d-M-Y g:i:s a", strtotime($start_date.$start_time));
            $data['START_DATE'] = $start_date_time;
        }
        if($this->input->post('end_date')!=''){
            $end_date = $this->input->post('end_date');
            $end_time = $this->input->post('end_time');
            $end_date_time = date("d-M-Y g:i:s a", strtotime($end_date.$end_time));
            $data['END_DATE'] = $end_date_time;
        }
        
       // var_dump($data); die();
       
        // Updating
        $update = $this->offer_model->update_default_offer($data,$default_id);
        if($update==TRUE){
             $this->session->set_flashdata('message_success', 'Update is Successful');
        }
        else{
             $this->session->set_flashdata('message_error', 'Update is not Successful');
        }
               
       
        
        // load view
        //$this->view_assigned_offer();
        redirect('administrator/offer/view_default_offer');
        //$this->load->view('layouts/default', $page_info);
    } // END OF edit_default_offer
    
    private function _set_rules_add_commission(){
        $this->form_validation->set_rules('offer_id_com', 'Offer ID', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('grp_ids[]', 'Retailer Group', 'required|trim|xss_clean|strip_tags');
        //$this->form_validation->set_rules('amount', 'Commission Amount', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_add_commission
    
    private function _set_rules_add_offer(){
        $this->form_validation->set_rules('offer_title', 'Offer Title', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('retail_offer_title', 'Retail Offer Title', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_details', 'Offer Details', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('customer_offer_text', 'Customer Offer Text', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('retail_offer_text', 'Retails Offer Text', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_details', 'Offer Details', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_details', 'Offer Details', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_details', 'Offer Details', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_details', 'Offer Details', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_amount', 'Offer Amount', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('offer_amount_description', 'Offer Amoun Description', 'required|trim|xss_clean|strip_tags');
       
    } // end of _set_rules_add_offer
    
    
    private function _set_rules_add_default_offer(){
        $this->form_validation->set_rules('offer_id', 'OPffer ID', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('menu_id', 'Menu ID', 'required|trim|xss_clean|strip_tags');
         $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('end_date', 'End Date', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('end_time', 'End Time', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_add_default_offer
    
    
    
    
    
    

    
} // END OF CLASS