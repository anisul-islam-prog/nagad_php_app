<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ussd extends MY_Controller
{
    var $current_page = "ussd";

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
        } */
        /*MULTIPLE LOGIN CHECK */
        
        
         // Increasing default download file size and Execution time 
        ini_set('memory_limit', '999999999999M');
        ini_set('max_execution_time', 5000);
        
    }

    /**
     * Display Administrator Dashboard page
     * @return void
     */
    
    
    
    public function view_ussd_menu() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_ussd_menu';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $page_info['table_data'] = $this->menu_model->get_menus();

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF view_ussd_menu
    
    public function edit_ussd_menu() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_ussd_menu';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        
        
        $this->_set_rules_add_ussd();

        $menu_name = $this->input->post('menu_name');
        $menu_id = $this->input->post('menu_id');
        $is_active = $this->input->post('is_active');
        if($is_active==FALSE){
            $is_active=0;
        }
        else{
            $is_active=1;
        }
        
      
        if ($this->form_validation->run() == TRUE) {
            $is_exists = $this->menu_model->is_menu_exists_update($menu_name,$menu_id);
            if($is_exists['IS_EXISTS']==1){
                $page_info['message_error'] = 'Menu Name Already Exists !!';
            }
            else{
                $data['MENU_NAME'] = $menu_name;
                $data['ISACTIVE'] = $is_active;
                $data['UPDATEDBY'] = $this->session->userdata('user_login_name');
                $data['UPDATEDATE'] = date('d-M-Y');
                
               
                $update = $this->menu_model->update_menu($data,$menu_id);
                if($update==TRUE){
                    $page_info['message_success'] = 'Update is Successful';
                }
                else{
                    $page_info['message_error'] = 'Update is not Successful';
                }
                
            }
            
        }
        
        
        
        $page_info['table_data'] = $this->menu_model->get_menus();
        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF edit_ussd_menu
    
   
    
    public function add_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF add_group
    
    public function do_add_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/add_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        
        $this->_set_rules_add_group();

        $group_name = $this->input->post('group_name');
        $is_active = $this->input->post('is_active');
        if($is_active==FALSE){
            $is_active=0;
        }
        else{
            $is_active=1;
        }
        
      
        if ($this->form_validation->run() == TRUE) {
            $is_exists = $this->group_model->is_group_exists($group_name);
            if($is_exists['IS_EXISTS']==1){
                $page_info['message_error'] = 'Group Name Already Exists !!';
            }
            else{
                $data['GROUP_NAME'] = $group_name;
                $data['ISACTIVE'] = $is_active;
                $data['CREATEDBY'] = $this->session->userdata('user_login_name');
                
                $insert = $this->group_model->add_group($data);
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
    } // END OF do_add_group

    public function offer_assign_to_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/offer_assign_to_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();

        // Set offer Values
        
        $all_camps = $this->offer_model->get_offers();
        for($i=0; $i<count($all_camps); $i++) {
            $this->camp_list[$all_camps[$i]['OFFER_ID']] = $all_camps[$i]['CUST_OFFER_TITLE'];
        }   

        $campaigns = $this->offer_model->get_offers();
        $cmp_ids = array();
        for($i=0; $i<count($campaigns); $i++) {
            $cmp_ids[] = (int)$campaigns[$i]['OFFER_ID'];
        }
        $this->form_data->cmp_ids = array(); // $cmp_ids;

        // Set Group Values
        $all_groups = $this->group_model->get_groups();
        for($i=0; $i<count($all_groups); $i++) {
            $this->group_list[$all_groups[$i]['GROUP_ID']] = $all_groups[$i]['GROUP_NAME'];
        }   

        $groups = $this->group_model->get_groups();
        $grp_ids = array();
        for($i=0; $i<count($groups); $i++) {
            $grp_ids[] = (int)$groups[$i]['GROUP_ID'];
        }
        $this->form_data->grp_ids = array(); // $cmp_ids;


        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF offer_assign_to_group

    public function do_offer_assign_to_group(){
        // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/offer_assign_to_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();

        // Set offer Values
        $all_camps = $this->offer_model->get_offers();
        for($i=0; $i<count($all_camps); $i++) {
            $this->camp_list[$all_camps[$i]['OFFER_ID']] = $all_camps[$i]['CUST_OFFER_TITLE'];
        }   

        $campaigns = $this->offer_model->get_offers();
        $cmp_ids = array();
        for($i=0; $i<count($campaigns); $i++) {
            $cmp_ids[] = (int)$campaigns[$i]['OFFER_ID'];
        }
        $this->form_data->cmp_ids = array(); // $cmp_ids;

        // Set Group Values
        $all_groups = $this->group_model->get_groups();
        for($i=0; $i<count($all_groups); $i++) {
            $this->group_list[$all_groups[$i]['GROUP_ID']] = $all_groups[$i]['GROUP_NAME'];
        }   

        $groups = $this->group_model->get_groups();
        $grp_ids = array();
        for($i=0; $i<count($groups); $i++) {
            $grp_ids[] = (int)$groups[$i]['GROUP_ID'];
        }
        $this->form_data->grp_ids = array(); // $cmp_ids;
        
        
        // INSERT OPERATIONS
        $this->_set_rules_assign_group_campaign();
        
        $cmp_ids = $this->input->post('cmp_ids');
        $group_ids = $this->input->post('group_ids');
        
        $menu_id = $this->input->post('menu_id');
        
        $start_date = $this->input->post('start_date');
        $start_time = $this->input->post('start_time');
        $end_date = $this->input->post('end_date');
        $end_time = $this->input->post('end_time');
        
        //var_dump($start_time); DIE();
        
        $start_date_time = date("d-M-Y g:i:s a", strtotime($start_date.$start_time));
        $end_date_time = date("d-M-Y g:i:s a", strtotime($end_date.$end_time));

        $is_approved = $this->input->post('is_approved');
        if($is_approved==FALSE){
            $is_approved=0;
        }
        else{
            $is_approved=1;
        }
        
      
        if ($this->form_validation->run() == TRUE) {
            
            //$data = array();
            
            $cmp_count = count($cmp_ids);
            $grp_count = count($group_ids);
            
            $max_count = max(array($cmp_count,$grp_count));
            
            $total_count = $cmp_count * $grp_count ; 
            
            $is_data_ok = TRUE;
            $date_conflict=array();
            
            
            $i=0;
            foreach ($cmp_ids as $ckey => $cvalue) {
                foreach ($group_ids as $gkey => $gvalue) {
                    $data[$i]['OFFER_ID'] = $cvalue;
                    $data[$i]['GROUP_ID'] = $gvalue;
                    $data[$i]['MENU_ID'] = $menu_id;
                    $data[$i]['STARTDATE'] = $start_date_time;
                    $data[$i]['ENDDATE'] = $end_date_time;
                    $data[$i]['ISAPPROVED'] = $is_approved;
                    $data[$i]['CREATEDBY'] = $this->session->userdata('user_login_name');
                    $i++;
                    
                    $conflict_check = $this->offer_model->check_assign_date_conflict($cvalue,$gvalue,$menu_id,$start_date_time,$end_date_time);
                    if(count($conflict_check)>0){
                        array_push($date_conflict, array('OFFER_ID'=>$cvalue,'GROUP_ID'=>$gvalue,'MENU_ID'=>$menu_id));
                        $is_data_ok = FALSE;
                    }
                    
                    
                    //var_dump($conflict_check); die();
                    
                }
            }
          
            if($is_data_ok==TRUE){
                $insert = $this->offer_model->assign_offer($data);
                if($insert==TRUE){
                    $page_info['message_success'] = 'Add is Successful';
                }
                else{
                    $page_info['message_error'] = 'Add is not Successful';
                }
            }
            else{
                $page_info['message_error'] = 'Add is not Successful !! <br/> Reason: Date Conflicts With Existing Data. <br/>';
                foreach ($date_conflict as $value) {
                    $page_info['message_error'] = $page_info['message_error'].'<br/> '.'Offre ID-'.$value['OFFER_ID'].' ,'.'Group ID-'.$value['GROUP_ID'].' ,'.'Menu ID-'.$value['MENU_ID'];
                }
            }
            
            
            
            
        }
        
        

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF do_offer_assign_to_group
    
    private function generate_date($start_date,$start_time,$end_date,$end_time) {
        $start_date = str_replace('-', '/', $start_date);
        $s_date = explode('/', $start_date);
        $s_date[2] = '20'.$s_date[2];
        $start_date = implode('/', $s_date);
        $start_date_time = date("d-M-Y g:i:s a", strtotime($start_date.$start_time));

        $end_date = str_replace('-', '/', $end_date);
        $e_date = explode('/', $end_date);
        $e_date[2] = '20'.$e_date[2];
        $end_date = implode('/', $e_date);
        $end_date_time = date("d-M-Y g:i:s a", strtotime($end_date.$end_time));
        return array('start_date'=>$start_date_time,'end_date'=>$end_date_time);
     }




     public function upload_bulk_offer_assign(){
       // set page specific variables
        $page_info['title'] = 'Manage USSD'. $this->site_name;
        $page_info['view_page'] = 'administrator/offer_assign_to_group_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['menu_list'] = $this->menu_model->get_all_menu();

        // Set offer Values
        $all_camps = $this->offer_model->get_offers();
        for($i=0; $i<count($all_camps); $i++) {
            $this->camp_list[$all_camps[$i]['OFFER_ID']] = $all_camps[$i]['CUST_OFFER_TITLE'];
        }   

        $campaigns = $this->offer_model->get_offers();
        $cmp_ids = array();
        for($i=0; $i<count($campaigns); $i++) {
            $cmp_ids[] = (int)$campaigns[$i]['OFFER_ID'];
        }
        $this->form_data->cmp_ids = array(); // $cmp_ids;

        // Set Group Values
        $all_groups = $this->group_model->get_groups();
        for($i=0; $i<count($all_groups); $i++) {
            $this->group_list[$all_groups[$i]['GROUP_ID']] = $all_groups[$i]['GROUP_NAME'];
        }   

        $groups = $this->group_model->get_groups();
        $grp_ids = array();
        for($i=0; $i<count($groups); $i++) {
            $grp_ids[] = (int)$groups[$i]['GROUP_ID'];
        }
        $this->form_data->grp_ids = array(); // $cmp_ids;
        
        
        
        
        
        //****** UPLOADING FILE *******////
        $upload_path = './uploads/BULK_ASSIGN/' ;
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
            
            $current=2;

            for($i=2;$i<=$total;$i++){
                if( !is_null($sheetData[$i]['A']) && !is_null($sheetData[$i]['Q']) && !is_null($sheetData[$i]['R']) && !is_null($sheetData[$i]['S']) && !is_null($sheetData[$i]['T']) && !is_null($sheetData[$i]['U']) ){
                    
                    
                    
                    if(!is_null($sheetData[$i]['B'])){
                        $offer_ids = explode('|', $sheetData[$i]['B']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 1;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['C'])){
                        $offer_ids = explode('|', $sheetData[$i]['C']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 2;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['D'])){
                        $offer_ids = explode('|', $sheetData[$i]['D']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 3;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['E'])){
                        $offer_ids = explode('|', $sheetData[$i]['E']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 4;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['F'])){
                        $offer_ids = explode('|', $sheetData[$i]['F']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 5;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['G'])){
                        $offer_ids = explode('|', $sheetData[$i]['G']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 6;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['H'])){
                        $offer_ids = explode('|', $sheetData[$i]['H']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 7;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['I'])){
                        $offer_ids = explode('|', $sheetData[$i]['I']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 8;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['J'])){
                        $offer_ids = explode('|', $sheetData[$i]['J']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 9;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['K'])){
                        $offer_ids = explode('|', $sheetData[$i]['K']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 10;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['L'])){
                        $offer_ids = explode('|', $sheetData[$i]['L']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 11;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['M'])){
                        $offer_ids = explode('|', $sheetData[$i]['M']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 12;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['N'])){
                        $offer_ids = explode('|', $sheetData[$i]['N']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 13;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['O'])){
                        $offer_ids = explode('|', $sheetData[$i]['O']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 14;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                    if(!is_null($sheetData[$i]['P'])){
                        $offer_ids = explode('|', $sheetData[$i]['P']);
                        
                        $dates = $this->generate_date($sheetData[$i]['Q'], $sheetData[$i]['R'], $sheetData[$i]['S'], $sheetData[$i]['T']);
                        
                        foreach ($offer_ids as $offer) {
                            $data[$current]['OFFER_ID'] = $offer;
                            $data[$current]['GROUP_NAME'] = $sheetData[$i]['A'];
                            $data[$current]['MENU_ID'] = 15;
                            $data[$current]['STARTDATE'] = $dates['start_date'];
                            $data[$current]['ENDDATE'] = $dates['end_date'];
                            $data[$current]['ISAPPROVED'] = $sheetData[$i]['U'];
                            $data[$current]['CREATEDBY'] = $this->session->userdata('user_login_name');
                            $current++;
                        }
                    }
                    
                   
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
                
                // INSERTING INTO TEMPORARY TABLE
                if($data_is_ok==TRUE){
                    
                   // var_dump($data); die();
                    
                    $insert_tmp = $this->offer_model->assign_offer_tmp($data);
                    if($insert_tmp==TRUE){
                        $invalid_group_name = $this->offer_model->get_invalid_group_name();
                        if(count($invalid_group_name)==0){
                            $invalid_offer_id = $this->offer_model->get_invalid_offer_id();
                            //var_dump($invalid_offer_id); die();
                            if(is_null($invalid_offer_id[0]['OFFER_ID'])){
				$check_date_conflicts = $this->offer_model->get_conflicts_date();
                                if(count($check_date_conflicts)==0){
                                
                                    $final_insert = $this->offer_model->assign_offer_final();
                                        if($final_insert==TRUE){
                                            $page_info['message_success'] = 'Upload Successful !!';
                                        }
                                        else{
                                            $page_info['message_error'] = 'Upload Failed !!';
                                        }
                                }
                                else{
                                    $err_msg="Upload Failed. <br/>Reason: Date Conflicts with Existing Data. <br/>";
                                    foreach ($check_date_conflicts as $conflict) {
                                        $err_msg = $err_msg." Offer ID-".$conflict['OFFER_ID']." ,Group ID-".$conflict['GROUP_NAME']." ,Menu ID-".$conflict['MENU_ID']." <br/> ";
                                    }
                                    
                                   // var_dump($err_msg); die();
                                   $page_info['message_error'] = $err_msg;
                                }
                            }
                            else{
                                $err_msg='Upload Failed. <br/>Reason: Invalid Offer ID Found. <Br/>Offer ID: ';
                                foreach ($invalid_offer_id as $id) {
                                    $err_msg = $err_msg.' , '.$id['OFFER_ID'];
                                }
                                $page_info['message_error'] = $err_msg;
                            }
                        }
                        else{
                            $err_msg='Upload Failed. <br/>Reason: Invalid Segment ID Found. <Br/>Segment ID : ';
                                foreach ($invalid_group_name as $id) {
                                    $err_msg = $err_msg.' , '.$id['GROUP_NAME'];
                                }
                                $page_info['message_error'] = $err_msg;
                        }
                       
                      
                    }   
                    
                }
                  
      
        // TRUNCATING TEMPORARY TABLE
        $this->offer_model->truncate_assign_tmp_tbl();

        // load view
        //redirect('administrator/ussd/offer_assign_to_group');
        $this->load->view('layouts/default', $page_info);
     }// END OF upload_bulk_offer_assign
    
    public function view_assigned_offer() {
        // set page specific variables
        $page_info['title'] = 'View USSD Menu'. $this->site_name;
        $page_info['view_page'] = 'administrator/view_assigned_offer';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        
       
        
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
                
                if($this->input->post("group_id")!=''){
                    $page_info['group_id'] = $this->input->post("group_id");
                    $this->session->set_userdata('group_id', $this->input->post("group_id"));
                    $filter['group_id'] = $this->input->post("group_id");
                }
                else{
                    $this->session->unset_userdata('group_id');
                }
                
                if($this->input->post("menu_id")!=''){
                    $page_info['menu_id'] = $this->input->post("menu_id");
                    $this->session->set_userdata('menu_id', $this->input->post("menu_id"));
                    $filter['menu_id'] = $this->input->post("menu_id");
                }
                else{
                    $this->session->unset_userdata('menu_id');
                }
                
                if($this->input->post("is_approved")!=''){
                    $page_info['is_approved'] = $this->input->post("is_approved");
                    $this->session->set_userdata('is_approved', $this->input->post("is_approved"));
                    $filter['is_approved'] = $this->input->post("is_approved");
                }
                else{
                    $this->session->unset_userdata('is_approved');
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
            
             if($this->session->userdata('group_id')!=''){
                $page_info['group_id'] = $this->session->userdata('group_id');
                $filter['group_id'] = $this->session->userdata('group_id');
            }
             if($this->session->userdata('menu_id')!=''){
                $page_info['menu_id'] = $this->session->userdata('menu_id');
                $filter['menu_id'] = $this->session->userdata('menu_id');
            }
            if($this->session->userdata('is_approved')!=''){
                $page_info['is_approved'] = $this->session->userdata('is_approved');
                $filter['is_approved'] = $this->session->userdata('is_approved');
            }
          
            
            $per_page = $this->config->item('per_page');
            $uri_segment = $this->config->item('uri_segment');
            $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
            
            //var_dump($filter); die();

            $total_rows = $this->offer_model->view_assigned_offers_count($filter);
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
            
        $page_info['table_data'] = $this->offer_model->get_assigned_offers($filter, $page_offset, $per_page,$export);
        $records = $page_info['table_data'];
        
        
        // Exporting Data
        if($export!=FALSE){
            $this->export_assigned_offers($records);
        }
        
        // build paginated list
        $config = array();
        $config["base_url"] = base_url() . "administrator/ussd/view_assigned_offer/";
        $config["total_rows"] = $total_rows;
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
            
      
        // End of  Populating Report Data
       

        // load view
        $this->load->view('layouts/default', $page_info);
    } // END OF view_assigned_offer 
    
    
    private function export_assigned_offers($export_data){
        if ($export_data) {
            foreach($export_data as $key => $value) {
                
                $data[$key]['OFFER_ID']= $value['OFFER_ID'];
                $data[$key]['GROUP_ID']= $value['GROUP_NAME'];
                $data[$key]['MENU_NAME']= $value['MENU_NAME'];
                $data[$key]['START_DATE']= $value['STARTDATE'];
                $data[$key]['END_DATE']= $value['ENDDATE'];
                $data[$key]['IS_APPROVED']= $value['ISAPPROVED'];
               
            }
        
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=Assigned Offers-".date('Y-m-d').".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w');

            fputcsv($file, array(
                'Offer ID',
                'Group ID',
                'Menu Name',
                'Start Date',
                'End Date',
                'Is Approved'
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
    } // END OF export_assigned_offers
    
     
    public function edit_assigned_offer(){
        
        // UPDATE OPERATIONS
        $this->_set_rules_edit_assigned_group();
        
        $assign_id = $this->input->post('assign_id');
        $offer_id = $this->input->post('offer_id');
        $group_id = $this->input->post('group_id');
        $menu_id = $this->input->post('menu_id');
        
        $is_approved = $this->input->post('is_approved');
        if($is_approved==FALSE){
            $is_approved=0;
        }
        else{
            $is_approved=1;
        }
        
        if($this->form_validation->run() == TRUE) {
            
            $data['OFFER_ID'] = $offer_id;
            $data['GROUP_ID'] = $group_id;
            $data['MENU_ID'] = $menu_id;
            $data['ISAPPROVED'] = $is_approved;
            $data['CREATEDBY'] = $this->session->userdata('user_login_name');
            
            if($this->input->post('start_date')!=''){
                $start_date = $this->input->post('start_date');
                $start_time = $this->input->post('start_time');
                $start_date_time = date("d-M-Y g:i:s a", strtotime($start_date.$start_time));
                $data['STARTDATE'] = $start_date_time;
            }
            if($this->input->post('end_date')!=''){
                $end_date = $this->input->post('end_date');
                $end_time = $this->input->post('end_time');
                $end_date_time = date("d-M-Y g:i:s a", strtotime($end_date.$end_time));
                $data['ENDDATE'] = $end_date_time;
            }
            
            if($this->offer_model->is_valid_offer_id($offer_id)>0){
                if($this->group_model->is_valid_group_id($group_id)>0){
                    if($this->menu_model->is_valid_menu_id($menu_id)>0){
                        // Updating
                        $update = $this->offer_model->update_assigned_offer($data,$assign_id);
                        if($update==TRUE){
                             $this->session->set_flashdata('message_success', 'Update is Successful');
                        }
                        else{
                             $this->session->set_flashdata('message_error', 'Update is not Successful');
                        }
                    } 
                    else{
                        $this->session->set_flashdata('message_error', 'Update is not Successful <br/> Reason : Invalid Menu ID');
                    }
                } 
                else{
                    $this->session->set_flashdata('message_error', 'Update is not Successful <br/> Reason : Invalid Group ID');
                }
            } 
            else{
                $this->session->set_flashdata('message_error', 'Update is not Successful <br/> Reason : Invalid Offer ID');
            }
            
        }
        
        // load view
        //$this->view_assigned_offer();
        redirect('administrator/ussd/view_assigned_offer');
        //$this->load->view('layouts/default', $page_info);
    } // END OF edit_assigned_offer
     
    
    
   
    
    
    
    
    
    // validation rules
    private function _set_rules_add_ussd(){
        $this->form_validation->set_rules('menu_name', 'Menu Name', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_add_ussd
    
    
    
    private function _set_rules_add_group(){
        $this->form_validation->set_rules('group_name', 'Group Name', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_add_ussd
    
    
    
    
        
    private function _set_rules_assign_group_campaign(){
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('end_date', 'End Date', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('end_time', 'End Time', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('cmp_ids[]', 'Campaign', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('group_ids[]', 'Group', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_assign_group_campaign
    
    private function _set_rules_edit_assigned_group(){
        $this->form_validation->set_rules('offer_id', 'Offer ID', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('group_id', 'Group ID', 'required|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('menu_id', 'Menu ID', 'required|trim|xss_clean|strip_tags');
    } // end of _set_rules_edit_assigned_group
    
    
        
}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */