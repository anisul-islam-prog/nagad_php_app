<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaign extends MY_Controller {

    var $current_page           = "Campaign_management";	
	var $report_page           = "report";
    var $tbl_bucket_department  = "DND_BUCKET_DEPARTMENTS";
    var $tbl_campaing           = "DND_CAMPAIGN";
    var $tbl_campaing_robi      = "ROBI_CAMPAIGN";
	var $tbl_campaing_base      = "DND_CAMPAIGN_BASE";
    var $tbl_obd_campaign_base  = "DND_OBD_CAMPAIGN_BASE";
	var $tbl_base_file          = 'DND_NUMBER_FILE';
	var $tbl_campaing_base_robi = "ROBI_CAMPAIGN_BASE";
    var $tbl_base_file_robi 	= 'ROBI_NUMBER_FILE';
	var $tbl_obd_base_file      = 'DND_OBD_NUMBER_FILE';
	var $tbl_campaign_obd       = "DND_CAMPAIGN_OBD";
	var $tbl_base_gen_flag      = 'DND_EXECUTE_SP';
	var $tbl_campaign_selected  = 'DND_CAMPAIGN_SELECTED';
	var $tbl_campaign_pause     = 'DND_CAMPAIGN_PAUSE';


    function __construct() {
        parent::__construct();

        if (!$this->session->userdata('is_logged_in')) {
            redirect('');
        } else {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type != 'admin') {
                redirect('');
            }
        }
        ini_set('memory_limit', '999999999999M');
        ini_set('max_execution_time', 5000);
        ini_set('post_max_size', '999999999999M');

        $this->load->model('dnd_model/campaign_model');
        $this->load->model('dnd_model/global_select_query');
        $this->load->model('dnd_model/global_insert_query');
        $this->load->model('dnd_model/global_update_query');
        $this->load->model('dnd_model/global_delete_query');
        $this->load->model('dnd_model/base_model');
        $this->load->config("pagination");
        $this->load->library("pagination");
        $this->load->library('table');
    }

    public function index($offset = 0) {
        $page_info['title']             = 'Create Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
		$cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CREATION_START","CAMPAIGN_CREATION_ENDS");

		if($cam_per){
			$page_info['view_page']      = 'campaign/campaign_create_view';
			//echo "create";
		}else{
			$page_info['view_page']      = 'campaign/campaign_expired_view';
			$page_info['message']        = ' CAMPAIGN CREATE TIME EXPIRED';
			//echo "error";
		}
		
        $page_info['user_department'] = $this->session->userdata('user_department');
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		
		$page_info['user_login_type']    = $this->session->userdata('user_login_type');
		
		if($this->session->userdata('user_login_type')=='admin'){
		$departments = $this->campaign_model->select_join_campaign_new();
		$page_info['masking_info']    = $this->campaign_model->getMasking('airtel');
		}
		else
		{
			$user_brand_name=$this->session->userdata('user_brand_name');
			if($user_brand_name=='airtel')
			$departments = $this->campaign_model->select_join_campaign_new_by_where($this->session->userdata('user_department'));
			else if($user_brand_name=='robi')
			$departments = $this->campaign_model->get_campaigns_robi_where($this->session->userdata('user_department'));	
		//var_dump($this->session->userdata('user_department'));die;
			
			$page_info['masking_info']    = $this->campaign_model->getMasking($user_brand_name);
			//var_dump($user_brand_name);die();
		}
			
		
        $page_info['department_info'] = $departments;
		//var_dump($departments[0]['DEPARTMENT_ID']);die;
        //in this department $data hold id
        //var_dump($this->campaign_model->select_join_campaign_new());die;
		
        
         if($departments)
			      $page_info['base_info'] =$this->campaign_model->getAllBaseByDeptId(($departments[0]['DEPARTMENT_ID']));
			else
			   $page_info['base_info'] =null;
        //var_dump($page_info['base_info']);
        //die();
        

        $this->load->view('layouts/default', $page_info);
    }
	
	public function getDeptAndMaskingAndbaseByBrandName()
	{
		$brand_name = $this->input->get_post('brand_name');
		if($brand_name=='robi')
		{
			$departments = $this->campaign_model->get_departments_robi();
			$base=$this->campaign_model->getAllBaseByDeptId_robi($departments[0]['DEPARTMENT_ID']);


		}
		else{
			$departments = $this->campaign_model->select_join_campaign_new(); 
			$base=$this->campaign_model->getAllBaseByDeptId($departments[0]['DEPARTMENT_ID']);

			
		}
		
		
		$get_all_mask=$this->campaign_model->getMasking($brand_name);
		
		echo json_encode(array('departments' => $departments,'base' => $base,'masking' => $get_all_mask));	 
		//echo json_encode(array('base' => $base)); 
		//echo json_encode(array('get_all_mask' => $get_all_mask)); 
	}
	
	
	public function getDeptByBrandName()
	{
		$brand_name = $this->input->get_post('brand_name');
		if($brand_name=='robi')
		{
			$departments = $this->campaign_model->get_departments_robi();
		}
		else{
			$departments = $this->campaign_model->select_join_campaign_new(); 
			
		}
		
		$base=$this->campaign_model->getAllBaseByDeptId($departments[0]['DEPARTMENT_ID']);
		
		
		echo json_encode(array('departments' => $departments));	 
		//echo json_encode(array('base' => $base)); 
		//echo json_encode(array('get_all_mask' => $get_all_mask)); 
	}
	
		public function getDeptAndMaskingAndbaseByBrandNameGovt()
	{
		$brand_name = $this->input->get_post('brand_name');
		if($brand_name=='robi')
		{
			$departments = $this->campaign_model->get_departments_robi();
		}
		else{
			$departments = $this->campaign_model->select_join_campaign_new(); 
			
		}
		
		
		
		$get_all_mask=$this->campaign_model->getMasking($brand_name);
		
		echo json_encode(array('departments' => $departments,'masking' => $get_all_mask));	 
		
	}
	
	
	public function getDeptAndMaskingAndbaseByBrandNameForDynamic()
	{
		$brand_name = $this->input->get_post('brand_name');
		if($brand_name=='robi')
		{
			$departments = $this->campaign_model->get_departments_robi();
		}
		else{
			$departments = $this->campaign_model->select_join_campaign_new(); 
			
		}
		
		
		
		$get_all_mask=$this->campaign_model->getMasking($brand_name);
		
		echo json_encode(array('departments' => $departments,'masking' => $get_all_mask));	  
		
	}
	
	public function msisdn_report_search(){
		$page_info['report']     = true;
		$this->current_page           = "admin_report";
		$page_info['title']            = 'MSISDN search ' . $this->site_name;
        $page_info['view_page']       = 'campaign/msisdn_search';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		
		
		$msisdn = $this->input->post('msisdn');
		
		
		if(isset($_POST['msisdn_search'])){
            
			$result = substr($msisdn, 0, 2);
			if($result=='16'){
				$searchvalue='880'.$msisdn;
				$campaign_ids = $this->campaign_model->getAirtelCampaignsByMSISDN($searchvalue);
				if(count($campaign_ids)>0){
					$camp_data=array();
				
			foreach ($campaign_ids as $key => $value) {
				$camp_info = $this->campaign_model->get_campaigns_info_for_msisdn($value['CAMPAIGN_ID']);
				
				$camp_data[$key]['MSISDN']='880'.$msisdn;
				$camp_data[$key]['DNAME']=$camp_info[0]['DNAME'];				
				$camp_data[$key]['CAMPAIGN_NAME']=$camp_info[0]['CAMPAIGN_NAME'];
				$camp_data[$key]['MSNAME']=$camp_info[0]['MSNAME'];
				$camp_data[$key]['CAMPAIGN_TEXT']=$camp_info[0]['CAMPAIGN_TEXT'];
				$camp_data[$key]['BROADCAST_DATE']=$camp_info[0]['BROADCAST_DATE'];
				$camp_data[$key]['ID']=$camp_info[0]['ID'];
								
			
		}
				
			
		$page_info['msisdn_info']      = $camp_data;
				}
				else
					$page_info['msisdn_info']      = null;
				//var_dump($campaign_ids);die;
				
			}
                     
	  
			else if(($result=='18')){
				$searchvalue='880'.$msisdn;
				$campaign_ids = $this->campaign_model->getRobiCampaignsByMSISDN($searchvalue);
				if(count($campaign_ids)>0){
					$camp_data=array();
				
			foreach ($campaign_ids as $key => $value) {
				$camp_info = $this->campaign_model->get_campaigns_info_for_msisdn($value['CAMPAIGN_ID']); 
				
				$camp_data[$key]['MSISDN']='880'.$msisdn;
				$camp_data[$key]['DNAME']=$camp_info[0]['DNAME'];				
				$camp_data[$key]['CAMPAIGN_NAME']=$camp_info[0]['CAMPAIGN_NAME'];
				$camp_data[$key]['MSNAME']=$camp_info[0]['MSNAME'];
				$camp_data[$key]['CAMPAIGN_TEXT']=$camp_info[0]['CAMPAIGN_TEXT'];
				$camp_data[$key]['BROADCAST_DATE']=$camp_info[0]['BROADCAST_DATE'];
				$camp_data[$key]['ID']=$camp_info[0]['ID'];
								
			
		}
				
			
		$page_info['msisdn_info']      = $camp_data;
				}
				else
					$page_info['msisdn_info']      = null;
			}
			 
		   $page_info['noData']           = 0;
		   $page_info['msisdn']           =$msisdn;
        }else{
			$page_info['noData']           = 1;
			 $page_info['msisdn']           ='';
			 $page_info['msisdn_info']      = null;
            
            
        }
		
		
		$this->load->view('layouts/default', $page_info);  
	}
	
	

	public function admin_reports()
	{
		$this->current_page           = "admin_report";
		$page_info['title']            = 'Admin Reports ' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$page_info['view_page']      = 'campaign/admin_report_view';
        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
		
		$page_info['user_department'] = $this->session->userdata('user_department') ;
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		
		$page_info['user_login_type']    = $this->session->userdata('user_login_type');
		
		
		
		
		
		
        //SEARCH DATA
         $brand_name = $this->input->post('brand_name');
		
         
         $report_duration = $this->input->post('report_duration');
		 
	 $page_info['brand_name_selected']      = $brand_name;
		
	 $page_info['duration_selected']      = $report_duration;
		 
	 $btn_search = $this->input->post('search_report');
         
         $report_info = array();
	
		
        if(isset($_POST['search_report'])){
            $report_info = $this->global_select_query->select_get_all_data_by_where('ROBI_CAMPAIGN_REPORT');
	
        }
        
        $page_info['report_info']      = $report_info;
	
        $this->load->view('layouts/default', $page_info);

		 
      
	}

	
	
	
	
	
	
		public function user_reports() 
	{
		$this->current_page           = "admin_report";
		
		$page_info['title']            = 'User Reports ' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
		
		$page_info['user_department'] = $this->session->userdata('user_department') ;
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		
		$page_info['user_login_type']    = $this->session->userdata('user_login_type');
		
		
		$brandName='airtel';
		$departments = $this->campaign_model->getDepartmentIDAndNameByBrand($brandName);
		
		
        //SEARCH DATA
         $brand_name = $this->input->post('brand_name');
         $report_type = $this->input->post('report_type');
         $report_duration = $this->input->post('report_duration');
		 
		 $page_info['brand_name_selected']      = $brand_name;
		 $page_info['type_selected']      = $report_type;
		 $page_info['duration_selected']      = $report_duration;
		 
		$btn_search = $this->input->post('search_report');
        $btn_export = $this->input->post('export_report');
		 
       
		$page_info['view_page']      = 'campaign/user_report_view';
		
		//if export button clicked
		if ($btn_export) {
									
			if($report_type=="customer"){
				
			$page_info['report_type']      = 'customer';	 
			
			$camp_data = $this->campaign_model->getUserReportUniqueCustomerBySegment($brand_name,$report_duration);
			$camp_info = array();
			foreach ($camp_data as $key => $value) {
				$camp_info[$key]['BROADCAST_DATE']=$camp_data[$key]['BROADCAST_DATE'];
				$camp_info[$key]['OPERATOR_NAME']=$camp_data[$key]['OPERATOR_NAME'];
				$camp_info[$key]['SEGMENT_TYPE']=$camp_data[$key]['SEGMENT_TYPE'];
				$camp_info[$key]['SEGMENT_NAME']=$camp_data[$key]['SEGMENT_NAME'];
				$camp_info[$key]['UPLOAD_COUNT']=$camp_data[$key]['UPLOAD_COUNT'];
				$camp_info[$key]['GEN_COUNT']=$camp_data[$key]['GEN_COUNT'];
				$camp_info[$key]['SMS_DELIVERED_COUNT']=$camp_data[$key]['SMS_DELIVERED_COUNT'];
				$camp_info[$key]['UNIQUE_CUSTOMER_COUNT']=$camp_data[$key]['UNIQUE_CUSTOMER_COUNT'];
				$camp_info[$key]['MAX_SMS']=$camp_data[$key]['MAX_SMS'];
			}
			
			
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=Unique Customers covered & Maximum number of SMS broadcasted to a single customer -".date('Y-m-d').".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w'); 

            fputcsv($file, array(
				'Date',
                'Brand Name',
                'Segment',
                'ARPU Segment wise breakdown',
                'Total Sub Count Uploaded',
				'TG count in generated file',
				'SMS Delivery Count',
				'Total Unique Customers covered',
				'Max Broadcast sent to single customer'				
                ));
                foreach ($camp_info as $value) {
                    $rowD= $value;
                    fputcsv($file, $rowD); 
                }
                exit();
        
			
			
			}
					
			
			elseif($report_type=="campaign") {				
					 
			
			$camp_data = $this->campaign_model->getUserReportTotalBroadcastInfoBySegment($brand_name,$report_duration);
			
			$camp_info = array();
			foreach ($camp_data as $key => $value) {
				$camp_info[$key]['BROADCAST_DATE']=$camp_data[$key]['BROADCAST_DATE'];
				$camp_info[$key]['CAMPAIGN_NAME']=$camp_data[$key]['CAMPAIGN_NAME'];
				$camp_info[$key]['OPERATOR_NAME']=$camp_data[$key]['OPERATOR_NAME'];
				$camp_info[$key]['SEGMENT_TYPE']=$camp_data[$key]['SEGMENT_TYPE'];
				$camp_info[$key]['UPLOAD_COUNT']=$camp_data[$key]['UPLOAD_COUNT'];
				$camp_info[$key]['GEN_COUNT']=$camp_data[$key]['GEN_COUNT'];
				$camp_info[$key]['DELIVERED_COUNT']=$camp_data[$key]['DELIVERED_COUNT'];
				$camp_info[$key]['DELIVERED_SUCESSESS_PERCENT']=$camp_data[$key]['DELIVERED_SUCESSESS_PERCENT'];
			}
			
		 header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=Total Campaign SMS Broadcasted-".date('Y-m-d').".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w');

            fputcsv($file, array(
                'Date',
                'Brand Name',
                'Campaign Name',
                'Campaign Creator',
                'Segment',
				'Total SMS Count',
				'ARPU Segment wise breakdown',
				'Total Sub Count Uploaded',
				'TG count in generated file',
				'SMS Delivery Count',
				'SMS Delivery %'
				
				
                ));
                foreach ($camp_info as $value) {
                    $rowD= $value;
                    fputcsv($file, $rowD);
                }
                exit();
            
				
				
			}
			
			
			
		}
		//if export button not clicked, report will be shown
		else{
			if(isset($_POST['search_user_report'])){
			
			
			if($report_type=="customer"){
				
			$page_info['report_type']      = 'customer';	 
			
			$camp_data = $this->campaign_model->getUserReportUniqueCustomerBySegment($brand_name,$report_duration);	
			
			$formatted_array = array();

			foreach( $camp_data as $element ) {
			$formatted_array[ $element['OPERATOR_NAME'] ][] = $element;
			}
			
			$page_info['report_info']      = $formatted_array; 
			}
					
			//if report type campaign
			elseif($report_type=="campaign") {				
				$brandName='airtel';
			$formatted_array = array();	
			$formatted_sum_array =array();			
			if($report_duration=='daily'){
			$camp_data = $this->campaign_model->getUserReportTotalBroadcastInfoBySegment($brand_name,$report_duration);
			foreach( $camp_data as $element ) {
			$formatted_array[ $element['CAMPAIGN_NAME'] ][] = $element;
			}
			
			}
			
			$sum_data = $this->campaign_model->getUserReportSum($brandName,$report_duration);  
			
			//var_dump($camp_data);die;
			

			
		foreach( $sum_data as $element ) {
			$formatted_sum_array[ $element['SEGMENT_TYPE'] ][] = $element; 
			}
			if($brand_name=='robi')
				$page_info['brand_name_total']      = ucfirst($report_duration).' Total Robi';
			else
				$page_info['brand_name_total']      = ucfirst($report_duration).' Total Airtel';
				
		$page_info['report_type']      = 'campaign';		
			
		$page_info['report_info']      = $formatted_array;
		$page_info['report_info_total']      = $formatted_sum_array;
            
				
				
			}
			//report type campaign end
			
            
        }else{
			//var_dump("off set"); die();
			
			$brandName='airtel';	
						
			$report_duration ='daily';
			$camp_data = $this->campaign_model->getUserReportTotalBroadcastInfoBySegment($brandName,$report_duration);   
			
			$sum_data = $this->campaign_model->getUserReportSum($brandName,$report_duration);  
			
			$formatted_array = array();
			$formatted_sum_array = array();

			foreach( $camp_data as $element ) {
			$formatted_array[ $element['CAMPAIGN_NAME'] ][] = $element;
			}
			
			foreach( $sum_data as $element ) {
			$formatted_sum_array[ $element['SEGMENT_TYPE'] ][] = $element;
			}
			
			//var_dump($formatted_array);die;
		$page_info['report_type']      = 'campaign';	
		$page_info['brand_name_total']      = 'Daily Total Airtel';			
			
		$page_info['report_info']      = $formatted_array;
		$page_info['report_info_total']      = $formatted_sum_array;
		
            
        }
		}
		
        
		
        $this->load->view('layouts/default', $page_info);
	}
	
	
	public function getJsonCampaignsCountByDeptAndDuration()
	{
		
		$duration = $this->input->get_post('duration');
		$brand_name = $this->input->get_post('brand_name');
		
		
		$departments = $this->campaign_model->getDepartmentIDAndNameByBrand($brand_name);
		$dept_data=array();
		foreach ($departments as $key => $value) {
			if($brand_name=='robi')
				$campaign_count= $this->campaign_model->getCampaignCountHistoryByDate(intval($value['ID']),$duration);
			else if($brand_name=='airtel')
				$campaign_count= $this->campaign_model->getCampaignCountHistoryByDateAirtel(intval($value['ID']),$duration);
			$dept_data[$key]['type']='stackedColumn';
			$dept_data[$key]['showInLegend']=true;
			
			$dept_data[$key]['name']=$value['DEPARTMENT_NAME']; 
			$dept_data[$key]['dataPoints']=array();
			$camp_data=array();
			
			foreach ($campaign_count as $key2 => $val) {
				$camp_data[$key2]['y']=intval($val['Y']);
				$camp_data[$key2]['x']=date("c", strtotime($val['X'])); 
			}
			
			$dept_data[$key]['dataPoints']=$camp_data; 
			
			
			
						
			
		}
		//var_dump($campaign_count);exit;
		echo json_encode($dept_data); 
		
	}
	
	
	
	
	public function dashboard() {
		$this->current_page           = "admin_report";
		$page_info['title']            = 'Dashboard ' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
		
		$page_info['view_page']      = 'campaign/dashboard_view';
		
		
		 $this->load->view('layouts/default', $page_info);
		
    }
	
	public function getJsonCampaignsCountByDept()
	{
		$brand_name = $this->input->get_post('brand_name');
		$duration = $this->input->get_post('duration');
		
		
		$departments = $this->campaign_model->getDepartmentIDAndNameByBrand($brand_name);
		$dept_data=array();
		foreach ($departments as $key => $value) {
			if($brand_name=='airtel')
			$campaign_count= $this->campaign_model->getCampaignCount(intval($value['ID']));
		else if($brand_name=='robi')
			$campaign_count= $this->campaign_model->getCampaignCountRobi(intval($value['ID']));
			
			
			
					
			$dept_data[$key]['indexLabel']=$value['DEPARTMENT_NAME'];
			$dept_data[$key]['y']=$campaign_count[0]['CAMPAIGN_COUNT'];
						
			
		}
		echo json_encode($dept_data); 
		
	}
	
	public function getJsonCampaignsCountByBrandName()
	{
		$brand_name = $this->input->get_post('brand_name');
		$duration = $this->input->get_post('duration');	
		
		if($brand_name=='airtel')
			$campaign_count= $this->campaign_model->getCampaignCountAirtelByDate(); 
		else if($brand_name=='robi')
			$campaign_count= $this->campaign_model->getCampaignCountRobiByDate();
		
		$camp_data = array();
		foreach ($campaign_count as $key => $value) {
			
			$camp_data[$key]['x']= $value['BROADCASTED_DATE'];
			$camp_data[$key]['y']=(int) $value['COUNT']; 
						
			
		}
		echo json_encode($camp_data);			
	}
	
	
	 public function download_delivered_history_when_stopped() {
		$campaign_id = $this->uri->segment(4);
        
        $data = $this->campaign_model->download_stopped_base($campaign_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=DND-".$campaign_id.'-'.date('Y-m-d').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
    }
	
	
	public function download_obd_base() {
		$campaign_id = $this->uri->segment(4);
        
        $data = $this->campaign_model->get_obd_base_according_to_duration($campaign_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=OBD-".$campaign_id.'-'.date('Y-m-d').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
    }
	
		 public function download_delivered_history_when_stopped_robi() {
		$campaign_id = $this->uri->segment(4);
        
        $data = $this->campaign_model->download_stopped_base_robi($campaign_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=DND-".$campaign_id.'-'.date('Y-m-d').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
    }
	
	
	
	public function getAllBaseByDepartment()
	{
		$brand_name = $this->input->get_post('brand_name');
		$department_id = $this->input->get_post('department');
		if($brand_name=='airtel')
		$priority=$this->campaign_model->getAllBaseByDeptId($department_id);
		else if($brand_name=='robi')
			$priority=$this->campaign_model->getAllBaseByDeptId_robi($department_id);
 		
		echo json_encode($priority);  
	}
	
	 public function govt_info_campaign() {
        $page_info['title']             = 'Create Govt Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
		$cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CREATION_START","CAMPAIGN_CREATION_ENDS");

		if($cam_per){
			$page_info['view_page']      = 'campaign/govt_info_campaign_create_view';
			//echo "create";
		}else{
			$page_info['view_page']      = 'campaign/campaign_expired_view';
			$page_info['message']        = ' CAMPAIGN CREATE TIME EXPIRED';
			
		}
		//echo "Test---".$cam_per; die();
        $page_info['user_department'] = $this->session->userdata('user_department') ;
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		
		if($this->session->userdata('user_login_type')=='admin'){
		$departments = $this->campaign_model->select_join_campaign_new();
		$page_info['masking_info']    = $this->campaign_model->getMasking('airtel');
		}
		else
		{
			$user_brand_name=$this->session->userdata('user_brand_name');
			if($user_brand_name=='airtel')
			$departments = $this->campaign_model->select_join_campaign_new_by_where($this->session->userdata('user_department'));
			else if($user_brand_name=='robi')
			$departments = $this->campaign_model->get_campaigns_robi_where($this->session->userdata('user_department'));	
		//var_dump($this->session->userdata('user_department'));die;
			
			$page_info['masking_info']    = $this->campaign_model->getMasking($user_brand_name);
			//var_dump($user_brand_name);die();
		}
		
		$page_info['department_info'] = $departments;
		
		$page_info['base_info']       = $this->campaign_model->getAllBase();

        $this->load->view('layouts/default', $page_info);
    }
	
	 public function do_govt_info_campaign()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $session_user_name   = $this->session->userdata('user_name');
        $department_id       = (int)$this->input->post('department');
        //echo $department_id ; die();
        //print_r_pre($_POST); die();
        $campaing_name       = $this->input->post('campaing_name');
        $broadcast_date      = $this->input->post('broadcast_date');
		$broadcast_time      = $this->input->post('broadcast_time');
		
        $brand_name          = $this->input->post('brand_name');
        $msisdn_type         = $this->input->post('msisdn_type');
        $campaign_category   = $this->input->post('campaign_category');
        $masking             = $this->input->post('masking');
        $campaign_text       = $this->input->post('campaign_text');
		$unicode_check       = $this->input->post('is_unicode_check');
        $preference          = $this->input->post('preference');
        $commvet             = $this->input->post('commvet');    
        
        $base                = $this->input->post('base');
        $remark              = $this->input->post('remark');
        //$broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));
		
		/*
		$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing,array('CAMPAIGN_NAME'=>$campaing_name));	 
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/critical_campaign');
		}
		*/
			

        $BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
        //var_dump($BUCKET_ID[0]['BUCKET_ID']);
		
		$update_data = array(); 
		
		foreach ($broadcast_date as $key => $value) {
						
				$update_data[$key]['CAMPAIGN_NAME'] = $campaing_name;
				$update_data[$key]['CREATED_BY'] = $session_user_name;
				$update_data[$key]['DEPARTMENT_ID'] = $department_id;
				$update_data[$key]['BRAND_NAME'] = $brand_name;
				$update_data[$key]['MSISDN_TYPE'] = $msisdn_type;
				$update_data[$key]['IS_GOVT_INFO'] = 1;
				$update_data[$key]['MASKING_ID'] = $masking;
				$update_data[$key]['CAMPAIGN_TEXT'] = $campaign_text;
				$update_data[$key]['IS_UNICODE'] = $unicode_check;
				
				$update_data[$key]['PREFERENCE'] = $preference;
				$update_data[$key]['BROADCAST_DATE'] = date("d-M-Y", strtotime($value)); 
				$update_data[$key]['GOVT_INFO_TIME'] = date("H:i", strtotime($broadcast_time[$key]));
				$update_data[$key]['COMMVET'] = $commvet;
				$update_data[$key]['REMARKS'] = $remark;
				$update_data[$key]['BUCKET_ID'] = $BUCKET_ID[0]['BUCKET_ID'];	
				$priority= $this->getPriorityByDate($department_id,$value);
				if($priority) 
					$update_data[$key]['PRIORITY']=$priority;
				else
					$update_data[$key]['PRIORITY']=1;
				
										
				
                //$cam_ids[$key]['ID'] = $value;
				
				//$cam_approval[$key]['IS_APPROVED'] = '1';
            }	
			
		   

        //print_r_pre($insertArray);
        $data_insert_rows = $this->global_insert_query->insert_batch($this->tbl_campaing,$update_data);
		
       

        if($data_insert_rows){
			

            $LAST_CAMPAIGN_ID = $this->global_select_query->get_Last_id($this->tbl_campaing);
			$update_status=false;
			
						//var_dump($LAST_CAMPAIGN_ID);die;
	
			for($i=1;$i<=$data_insert_rows;$i++)
			{
				$TOTAL_BASE_COUNT = 0;
				foreach($base as $kes => $value){
                $data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID;
                $data[$kes]['BASE_ID'] = $value;
                $BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file,array('ID'=>$value));
                $data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
                $data[$kes]['CREATED_BY'] = $session_user_name;
                $TOTAL_BASE_COUNT+=$BASE_COUNT[0]['TOTAL_NUMBER'];
            }
				
				
				
            

            $data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base,$data);
			 if($data_insert_1){
                $data_update = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$LAST_CAMPAIGN_ID),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
				$LAST_CAMPAIGN_ID=$LAST_CAMPAIGN_ID+1;
				$update_status=true;
               
            }
			else{
				$update_status=true;
				
			}
				
			}
			
			if($update_status)
			{
				$this->session->set_flashdata('message_success','Govt Info created successfully.');
					redirect('dnd_controllers/campaign/govt_info_campaign');
			}
			else{
				$this->session->set_flashdata('message_error','Govt Info creation failed.');
					redirect('dnd_controllers/campaign/govt_info_campaign');
				
			}
			
			

            


            

        }

        $this->load->view('layouts/default', $page_info);
    }
	
	
	public function campaign_approve()
    {
        $page_info['title']             = 'Campaign List' . $this->site_name;
        $page_info['view_page']         = 'campaign/campaign_approve_list_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$brandName='airtel';
		$page_info['allData']           = $this->campaign_model->get_todays_campaign($brandName);

		//var_dump($page_info['allData']); exit;

        $department_data= $this->global_select_query->is_data_exists($this->tbl_campaing);
       
        $uri_segment =  $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;


        $per_page = $this->config->item('per_page');
        //SEARCH DATA
         $searchSelect = $this->input->post('searchSelect');
         $txtSearch = $this->input->post('txtSearch');
         $search_date = $this->input->post('search_date');
         $search_date_new = date("d-M-Y", strtotime($search_date));
         //$s_data = array('ID'=>'326');
         //$result_data = $this->campaign_model->searchCampaignlists($s_data);
        // var_dump($result_data); die();

        /*if(isset($_POST['search'])){
            $array = array('searchSelect'=>$searchSelect,'txtSearch'=>$txtSearch,'search_date'=>$search_date_new);
           $page_info['allData']           = $this->campaign_model->getCampaignlist($array);
        }else{
            $page_info['allData']           = $this->campaign_model->getCampaignlist();
            
        } */
        // GENERATING TABLE

        $record_result = $this->global_select_query->select_custom_limit($this->tbl_campaing,$per_page,$page_offset);
       
        //print_r_pre($record_result);die();

        $page_info['records']   = $record_result[0]['ID'];
        $config                 = array();
        $config["base_url"]     = base_url()."dnd_controllers/campaign/campaing_list";
        $config["total_rows"]   = count($department_data);
        $config['per_page']     = $this->config->item('per_page');
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
  
        if ($record_result) {

            $tbl_heading = array(
                '0' => array('data'=> 'ID'),
                '1' => array('data'=> 'Team Category'),
                '2' => array('data'=> 'Category'),
                '3' => array('data'=> 'Campaign Name'),
                '4' => array('data'=> 'Campaign ID'),
                '5' => array('data'=> 'Brand '),
                '6' => array('data'=> 'Masking'),
                '7' => array('data'=> 'SMS'),
                '8' => array('data'=> 'Priority'),
                '9' => array('data'=> 'Base'),
                '10' => array('data'=> 'Target'),
                '11' => array('data'=> 'MSISDN Type'),
                '12' => array('data'=> 'publish'),
                '13' => array('data'=> 'Priority '),
                '14' => array('data'=> 'CAMPAIGN TEXT'),
                '15' => array('data'=> 'ACTION', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);
            $i=1;
           //print_r_pre($record_result);die();
            foreach ($record_result as $key) {
                
                $role_str = $key['CAMPAIGN_NAME'];
                if($key['IS_DND_CHECK']==1){
                    $desc_str = "Active";
                }else{
                    $desc_str = "N/A";
                }
                $action_str = '';
                $action_str .= '<button data-id="'.$key['ID'].'" data-dname="'.$key['CAMPAIGN_NAME'].'" data-active="'.$key['IS_DND_CHECK'].'" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>';
                $tbl_row = array(
                    '0' => array('data'=> $i),
                    '1' => array('data'=> $role_str),
                    '2' => array('data'=> $key['BROADCAST_DATE']),
                    '3' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '4' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '5' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '6' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '7' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '8' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '9' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '10' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '11' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '12' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '13' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '14' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '15' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
                );
                $this->table->add_row($tbl_row);
                $i++; 
            }

            $page_info['records_table'] = $this->table->generate();
        } else {
            $page_info['records_table'] = '<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>';
            $page_info['pagin_links'] = '';
        }        
        

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);

    } //campaign_approve
	
	
		
	public function campaign_approve_robi()
    {
        $page_info['title']             = 'Campaign List' . $this->site_name;
        $page_info['view_page']         = 'campaign/campaign_approve_list_robi_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$brandName='robi';
		$page_info['allData']           = $this->campaign_model->get_todays_campaign_robi();

		//var_dump($page_info['allData']); exit; 

        $department_data= $this->global_select_query->is_data_exists($this->tbl_campaing);
       
        $uri_segment =  $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;


        

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);

    }
	
	
	public function base_generate()
	{
		$approval_cam_ids  = $this->input->post('approval_cam');
		
		
		$is_previous_base  = $this->input->post('is_previous_base');
		$approval_dnd_checks  = $this->input->post('is_dnd_check');
		$approval_obd_checks  = $this->input->post('is_obd_check');
		if(!$approval_cam_ids ){
					$this->session->set_flashdata('message_error', 'No campaign is selected.');
					redirect('dnd_controllers/campaign/campaign_approve');
				}
				
				$cam_ids = array();
				$obd_ids = array();
				$dnd_ids = array();
				$update_data = array();
		foreach ($approval_cam_ids as $key => $value) {
			array_push($cam_ids,$value);
			
				$update_data[$key]['ID'] = $value;
				$update_data[$key]['IS_APPROVED'] = 1;
				
				if($this->input->post('is_dnd_check_'.$value)){
					$update_data[$key]['IS_DND_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_DND_CHECK'] = 0;
				}
				
				if($this->input->post('is_obd_check_'.$value)){
					$update_data[$key]['IS_OBD_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_OBD_CHECK'] = 0;
				}
				
				if($this->input->post('is_previous_base_'.$value)){
					$update_data[$key]['IS_PREVIOUS_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_PREVIOUS_CHECK'] = 0;
				}
				
				if($this->input->post('is_unicode_check_'.$value)){
					$update_data[$key]['IS_UNICODE'] = 1;
				}
				else{
					$update_data[$key]['IS_UNICODE'] = 0;
				}
			
                //$cam_ids[$key]['ID'] = $value;
				
				//$cam_approval[$key]['IS_APPROVED'] = '1';
            }	
		
			
			

		
		
		$data_update=$this->global_update_query->update_batch('DND_CAMPAIGN','ID',$update_data);
		
		$base_flag_array = array('EXECUTION_CHANNEL'=>'WEB','SP_NAME'=>'PROC_DND_BUCKET');
		$this->global_insert_query->insert($this->tbl_base_gen_flag,$base_flag_array);
		
		 if($data_update){
					$this->session->set_flashdata('message_success', 'Base generation is being processed');
					redirect('dnd_controllers/campaign/campaign_approve');
				}else{
					$this->session->set_flashdata('message_error', 'Base genereation failed.');
					redirect('dnd_controllers/campaign/campaign_approve');
				}
						
		
	}
	
	
	public function base_generate_robi()
	{
		$approval_cam_ids  = $this->input->post('approval_cam');
		
		
		$is_previous_base  = $this->input->post('is_previous_base');
		$approval_dnd_checks  = $this->input->post('is_dnd_check');
		$approval_obd_checks  = $this->input->post('is_obd_check');
		if(!$approval_cam_ids ){
					$this->session->set_flashdata('message_error', 'No campaign is selected.');
					redirect('dnd_controllers/campaign/campaign_approve_robi');
				}
				
				$cam_ids = array();
				$obd_ids = array();
				$dnd_ids = array();
				$update_data = array();
		foreach ($approval_cam_ids as $key => $value) {
			array_push($cam_ids,$value);
			
				$update_data[$key]['ID'] = $value;
				$update_data[$key]['IS_APPROVED'] = 1;
				
				if($this->input->post('is_dnd_check_'.$value)){
					$update_data[$key]['IS_DND_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_DND_CHECK'] = 0;
				}
				
				if($this->input->post('is_obd_check_'.$value)){
					$update_data[$key]['IS_OBD_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_OBD_CHECK'] = 0;
				}
				
				if($this->input->post('is_previous_base_'.$value)){
					$update_data[$key]['IS_PREVIOUS_CHECK'] = 1;
				}
				else{
					$update_data[$key]['IS_PREVIOUS_CHECK'] = 0;
				}
				
				if($this->input->post('is_unicode_check_'.$value)){
					$update_data[$key]['IS_UNICODE'] = 1;
				}
				else{
					$update_data[$key]['IS_UNICODE'] = 0;
				}
			
                
            }	
		
			
			

		
		
		$data_update=$this->global_update_query->update_batch('ROBI_CAMPAIGN','ID',$update_data);
		
		$base_flag_array = array('EXECUTION_CHANNEL'=>'WEB','SP_NAME'=>'PROC_ROBI_LOGIC'); 
		$this->global_insert_query->insert('ROBI_EXECUTE_SP',$base_flag_array);
		
		 if($data_update){
					$this->session->set_flashdata('message_success', 'Base generation is being processed');
					redirect('dnd_controllers/campaign/campaign_approve_robi');
				}else{
					$this->session->set_flashdata('message_error', 'Base genereation failed.');
					redirect('dnd_controllers/campaign/campaign_approve_robi');
				}
						
		
	}
	
	public function broadcast_campaign()
	{
		$page_info['title']             = 'Broadcast Airtel Campaign' . $this->site_name;
        $page_info['view_page']         = 'campaign/broadcast_campaign_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$brandname='airtel';
		$get_campaigns= $this->campaign_model->get_approved_campaign($brandname);
		$SP_STATUS_BASE_GEN = $this->global_select_query->is_data_exists('DND_EXECUTE_SP',array('STATUS'=>0,'SP_NAME'=>'PROC_DND_BUCKET'));	
		if($SP_STATUS_BASE_GEN)
			$page_info['broadcast_btn_status']      = 'false';
		else{
			$page_info['broadcast_btn_status']      = 'true';
		}
			
		
		$result_data=array();
		foreach($get_campaigns as $key=> $value)
		{
			 $camp_name=$this->campaign_model->get_campaigns_info(intval($value['CAMPAIGN_ID']));
			 //var_dump($camp_name);die;
			 $result_data[$key]['CAMPAIGN_ID']=$value['CAMPAIGN_ID'];
			 $result_data[$key]['CAMPAIGN_NAME']=$camp_name[0]['CAMPAIGN_NAME'];
			 $result_data[$key]['CAMPAIGN_TEXT']=$camp_name[0]['CAMPAIGN_TEXT'];
			 $result_data[$key]['DNAME']=$camp_name[0]['DNAME'];
			 $result_data[$key]['MSNAME']=$camp_name[0]['MSNAME'];
			 $result_data[$key]['DOWNLOAD_TARGET']=$camp_name[0]['DOWNLOAD_TARGET'];
			 $result_data[$key]['BASE_COUNT']=$camp_name[0]['BASE_COUNT'];
			 $result_data[$key]['GENERATED_BASE']=$camp_name[0]['GENERATED_BASE'];
			 //$result_data[$key]['CAMPAIGN_NAME']=$camp_name[0]['CAMPAIGN_NAME'];
			 $result_data[$key]['PRIORITY']=$value['PRIORITY'];
			 
			 
			
		}
		$page_info['allData']           = $result_data;
		
		
		
		
		if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);
	}
	
	
	public function broadcast_campaign_robi()
	{
		$page_info['title']             = 'Broadcast Robi Campaign' . $this->site_name;
        $page_info['view_page']         = 'campaign/broadcast_campaign_view_robi';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$brandname='robi';
		$get_campaigns= $this->campaign_model->get_approved_campaign_robi();
		$SP_STATUS_BASE_GEN = $this->global_select_query->is_data_exists('ROBI_EXECUTE_SP',array('STATUS'=>0,'SP_NAME'=>'PROC_ROBI_LOGIC'));	
		if($SP_STATUS_BASE_GEN)
			$page_info['broadcast_btn_status']      = 'false';
		else{
			$page_info['broadcast_btn_status']      = 'true';
		}
			
		
		$result_data=array();
		foreach($get_campaigns as $key=> $value)
		{
			 $camp_name=$this->campaign_model->get_campaigns_info_robi(intval($value['CAMPAIGN_ID']));
			 //var_dump($camp_name);die;
			 $result_data[$key]['CAMPAIGN_ID']=$value['CAMPAIGN_ID'];
			 $result_data[$key]['CAMPAIGN_NAME']=$camp_name[0]['CAMPAIGN_NAME'];
			 $result_data[$key]['CAMPAIGN_TEXT']=$camp_name[0]['CAMPAIGN_TEXT'];
			 $result_data[$key]['DNAME']=$camp_name[0]['DNAME'];
			 $result_data[$key]['MSNAME']=$camp_name[0]['MSNAME'];
			 $result_data[$key]['DOWNLOAD_TARGET']=$camp_name[0]['DOWNLOAD_TARGET'];
			 $result_data[$key]['BASE_COUNT']=$camp_name[0]['BASE_COUNT'];
			 $result_data[$key]['GENERATED_BASE']=$camp_name[0]['GENERATED_BASE'];
			 //$result_data[$key]['CAMPAIGN_NAME']=$camp_name[0]['CAMPAIGN_NAME'];
			 $result_data[$key]['PRIORITY']=$value['PRIORITY'];
			 
			 
			
		}
		$page_info['allData']           = $result_data;
		
		
		
		
		if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);
	}
	
	public function resume_or_pause()
	{
		$campiddsegment = $this->uri->segment(4);
		$pausesegement = $this->uri->segment(5);
		if($pausesegement==1){
		$update_status=$this->global_update_query->update($this->tbl_campaing,array('ID'=>$campiddsegment),array('IS_PAUSED'=>0));
		$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>0,'BRAND_NAME'=>'airtel');
		$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		}
		else{
			$update_status=$this->global_update_query->update($this->tbl_campaing,array('ID'=>$campiddsegment),array('IS_PAUSED'=>1));
			$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>1,'BRAND_NAME'=>'airtel');
			$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		}
				
			
		if($update_status)
		{
			$this->session->set_flashdata('message_success','Campaign status update success');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list');
		}
		else{
			$this->session->set_flashdata('message_error','Campaign status update failed');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list');
		}
			
		
	}
	
	public function resume_or_pause_robi()
	{
		$campiddsegment = $this->uri->segment(4);
		$pausesegement = $this->uri->segment(5);
		if($pausesegement==1){
		$update_status=$this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$campiddsegment),array('IS_PAUSED'=>0));
		$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>0,'BRAND_NAME'=>'robi');
		$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		}
		else{
			$update_status=$this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$campiddsegment),array('IS_PAUSED'=>1));
			$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>1,'BRAND_NAME'=>'robi');
			$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		}
				
			
		if($update_status)
		{
			$this->session->set_flashdata('message_success','Campaign status update success');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list_robi');
		}
		else{
			$this->session->set_flashdata('message_error','Campaign status update failed');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list_robi');
		}
			
		
	}
	
	
	public function stop_campaign()
	{
		$campiddsegment = $this->uri->segment(4);
		
		
		$update_status=$this->global_update_query->update($this->tbl_campaing,array('ID'=>$campiddsegment),array('IS_PAUSED'=>2));
		$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>2,'BRAND_NAME'=>'airtel');
		$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		
		
		
			
		if($update_status)
		{
			$this->session->set_flashdata('message_success','Campaign stopped successfully');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list_robi');
		}
		else{
			$this->session->set_flashdata('message_error','Campaign status update failed');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list_robi');
		}
			
		
	}
	
	public function stop_campaign_robi()
	{
		$campiddsegment = $this->uri->segment(4);
		
		
		$update_status=$this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$campiddsegment),array('IS_PAUSED'=>2));
		$insert_array = array('CAMPAIGN_ID'=>$campiddsegment,'PAUSE_STATUS'=>2,'BRAND_NAME'=>'robi');
		$this->global_insert_query->insert($this->tbl_campaign_pause,$insert_array);
		
		
		
			
		if($update_status)
		{
			$this->session->set_flashdata('message_success','Campaign stopped successfully');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list');
		}
		else{
			$this->session->set_flashdata('message_error','Campaign status update failed');
					redirect('dnd_controllers/campaign/broadcasted_campaign_list');
		}
			
		
	}
	
	
	public function start_broadcast()		
	{
		
		$base_flag_array = array('EXECUTION_CHANNEL'=>'WEB','SP_NAME'=>'PROC_DND_FINAL_BROADCAST');
		$rows=$this->global_insert_query->insert($this->tbl_base_gen_flag,$base_flag_array);
		if($rows)
		{
			$this->session->set_flashdata('message_success','SMS Broadcast has been started');
					redirect('dnd_controllers/campaign/broadcast_campaign');
		}
		else{
			$this->session->set_flashdata('message_error','SMS Broadcast has been failed');
					redirect('dnd_controllers/campaign/broadcast_campaign');
		}
			
	
	}
	
	public function start_broadcast_robi()		
	{
		
		$base_flag_array = array('EXECUTION_CHANNEL'=>'WEB','SP_NAME'=>'PROC_DND_FINAL_BROADCAST_ROBI');
		$rows=$this->global_insert_query->insert('ROBI_EXECUTE_SP',$base_flag_array);
		if($rows)
		{
			$this->session->set_flashdata('message_success','SMS Broadcast has been started');
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
		}
		else{
			$this->session->set_flashdata('message_error','SMS Broadcast has been failed');
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
		}
			
	
	}
	
	
	public function broadcasted_campaign_base_list()
	{
		$page_info['title']             = 'Broadcast Campaign' . $this->site_name;
        $page_info['view_page']         = 'campaign/broadcasted_campaign_base_list_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		
		$page_info['allData']           = $this->campaign_model->get_broadcasted_campaign_base(); 

		
		
		if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);
	} 
	
	public function broadcasted_campaign_list()
	{
		$page_info['title']             = 'Broadcast Campaign' . $this->site_name;
        $page_info['view_page']         = 'campaign/broadcasted_campaign_list_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$broadcasted_data =$this->campaign_model->get_broadcasted_campaign(); 
		
		$page_info['allData']           = $broadcasted_data; 
		//var_dump($broadcasted_data);die;
		
				//var_dump($this->campaign_model->get_broadcasted_campaign());die;
		if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);
	}//broadcasted_campaign_list
	
	public function broadcasted_campaign_list_robi()
	{
		$page_info['title']             = 'Broadcast Campaign' . $this->site_name;
        $page_info['view_page']         = 'campaign/broadcasted_campaign_list_robi_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$broadcasted_data =$this->campaign_model->get_broadcasted_campaign_robi(); 
		
		$page_info['allData']           = $broadcasted_data; 
		//var_dump($broadcasted_data);die;
		
				//var_dump($this->campaign_model->get_broadcasted_campaign());die;
		if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);
	}//broadcasted_campaign_list
	
	
	
	public function update_campaign_global_priority()
	{
		$campaign_global_priority       = $this->input->post('global_priority');
		if(!$campaign_global_priority)
			{
			$this->session->set_flashdata('message_error','Generated base list is empty.');
					redirect('dnd_controllers/campaign/broadcast_campaign');
			}
			
		
		$campaign_ids       = $this->input->post('campaign_ids');
		foreach ($campaign_ids  as $key => $value) {
			
                $cam_info[$key]['ID'] = $value;
				
				
            }	
			
		foreach ($campaign_global_priority as $key => $value) {			
               
				$cam_info[$key]['GLOBAL_PRIORITY'] = $value;
            }
			
			
		foreach ($cam_info as $key => $value) {	
	 		
			$rows=$this->global_update_query->update($this->tbl_campaing,array('ID'=>$value['ID']),array('GLOBAL_PRIORITY'=>$value['GLOBAL_PRIORITY']));
			$rows2=$this->global_update_query->update($this->tbl_campaign_selected,array('CAMPAIGN_ID'=>$value['ID']),array('PRIORITY'=>$value['GLOBAL_PRIORITY']));
			//var_dump($this->db->last_query());die;
			
			
 
               
				
            }				
			
			//$rows=$this->global_update_query->update($this->tbl_campaing,$cam_info,$cam_priority);
			
			if($rows2)
			{
			$this->session->set_flashdata('message_success','Global priority update successfully');
					redirect('dnd_controllers/campaign/broadcast_campaign');
			}
			else{
			$this->session->set_flashdata('message_error','Global priority update failed');
					redirect('dnd_controllers/campaign/broadcast_campaign');
			}
		
			
		
		
       
	}
		
	public function update_campaign_global_priority_robi()
	{
		$campaign_global_priority       = $this->input->post('global_priority');
		if(!$campaign_global_priority)
			{
			$this->session->set_flashdata('message_error','Generated base list is empty.');
					redirect('dnd_controllers/campaign/broadcast_campaign');
			}
			
		
		$campaign_ids       = $this->input->post('campaign_ids');
		foreach ($campaign_ids  as $key => $value) {
			
                $cam_info[$key]['ID'] = $value;
				
				
            }	
			
		foreach ($campaign_global_priority as $key => $value) {			
               
				$cam_info[$key]['GLOBAL_PRIORITY'] = $value;
            }
			
			
		foreach ($cam_info as $key => $value) {	
	 		
			$rows=$this->global_update_query->update($this->tbl_campaing,array('ID'=>$value['ID']),array('GLOBAL_PRIORITY'=>$value['GLOBAL_PRIORITY']));
			$rows2=$this->global_update_query->update($this->tbl_campaign_selected,array('CAMPAIGN_ID'=>$value['ID']),array('PRIORITY'=>$value['GLOBAL_PRIORITY']));
			//var_dump($this->db->last_query());die;
			
			
 
               
				
            }				
			
			//$rows=$this->global_update_query->update($this->tbl_campaing,$cam_info,$cam_priority);
			
			if($rows2)
			{
			$this->session->set_flashdata('message_success','Global priority update successfully');
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
			}
			else{
			$this->session->set_flashdata('message_error','Global priority update failed');
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
			}
		
			
		
		
       
	}
	
 

	public function download_target_file(){
		$cmp_id = $this->uri->segment(4);
		$data = $this->campaign_model->get_campaign_target_file($cmp_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=TARGET_BASE-".'-'.date('Y-m-d').".csv");
		header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache"); 
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
	}
	
	public function start_download_process(){
		$page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

		$cmp_id = $this->uri->segment(4);
		$data = $this->campaign_model->update_download_status($cmp_id);
		
		if($data)
				{
				$this->session->set_flashdata('message_success', 'Download Process starts');
					redirect('dnd_controllers/campaign/broadcast_campaign');
				}else{
					$this->session->set_flashdata('message_error', 'Download Process fails');  
					redirect('dnd_controllers/campaign/broadcast_campaign');
				}
        $this->load->view('layouts/default', $page_info);
	}
	
	public function start_download_process_robi(){
		$page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

		$cmp_id = $this->uri->segment(4);
		$data = $this->campaign_model->update_download_status_robi($cmp_id);
		
		if($data)
				{
				$this->session->set_flashdata('message_success', 'Download Process starts');
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
				}else{
					$this->session->set_flashdata('message_error', 'Download Process fails');  
					redirect('dnd_controllers/campaign/broadcast_campaign_robi');
				}
        $this->load->view('layouts/default', $page_info);
	}
	
	public function download_target_file_robi(){
		$cmp_id = $this->uri->segment(4);
		$data = $this->campaign_model->get_campaign_target_file_robi($cmp_id);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=TARGET_BASE-".'-'.date('Y-m-d').".csv");
		header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache"); 
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
	}
	
	
	
    public function critical_campaign() {
        $page_info['title']             = 'Create Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }

            $page_info['view_page']      = 'campaign/critical_campaign_create_view';
            //echo "create";

        //echo "Test---".$cam_per; die();
        $page_info['user_department'] = $this->session->userdata('user_department') ;
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		$page_info['user_login_type']    = $this->session->userdata('user_login_type');
        
		if($this->session->userdata('user_login_type')=='admin'){
		$departments = $this->campaign_model->select_join_campaign_new();
		$page_info['masking_info']    = $this->campaign_model->getMasking('airtel');
		}
		else
		{
			$user_brand_name=$this->session->userdata('user_brand_name');
			if($user_brand_name=='airtel')
			$departments = $this->campaign_model->select_join_campaign_new_by_where($this->session->userdata('user_department'));
			else if($user_brand_name=='robi')
			$departments = $this->campaign_model->get_campaigns_robi_where($this->session->userdata('user_department'));	
		//var_dump($this->session->userdata('user_department'));die;
			
			$page_info['masking_info']    = $this->campaign_model->getMasking($user_brand_name);
			//var_dump($user_brand_name);die();
		}
		
		$page_info['department_info'] = $departments;
		$page_info['base_info'] = array();
		if($departments)
		$page_info['base_info']       = $this->campaign_model->getAllBaseByDeptId(($departments[0]['DEPARTMENT_ID']));
		


        $this->load->view('layouts/default', $page_info);
    }
	
	public function getDepartment()
    {
        $user_department = $this->session->userdata('user_department');
        $user_role_id    = $this->session->userdata('user_role_id');
        $department_info = $this->campaign_model->select_join_campaign_new();

        
    }//getDepartment

    public function getPriority() {
        $department_id = $this->input->get_post('department');
        //var_dump($department_id); die();
        $brand_name = $this->input->get_post('brand_name');
        $broadcast_date = $this->input->get_post('broadcast_date');
        $broadcast_date = date("d-M-Y", strtotime($broadcast_date));
        $priority = $this->campaign_model->getPriority($department_id,$broadcast_date,$brand_name);
        echo json_encode(array('priority'=>$priority));    
    } //getPriority
	
	public function getPriorityByDate($department_id,$broadcast_date) {        
        $broadcast_date = date("d-M-Y", strtotime($broadcast_date));
        $priority = $this->campaign_model->getPriority($department_id,$broadcast_date);
        return $priority; 
    } //getPriority

    public function do_campaing()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $session_user_name   = $this->session->userdata('user_name');
        $department_id       = (int)$this->input->post('department');
        
		//print_r_pre($_POST); die();
		$campaing_name       = $this->input->post('campaing_name');
        $broadcast_date      = $this->input->post('broadcast_date');
        $brand_name          = $this->input->post('brand_name');		
        $msisdn_type         = $this->input->post('msisdn_type');
        $campaign_category   = $this->input->post('campaign_category'); 
        $masking             = $this->input->post('masking');
        $campaign_text       = $this->input->post('campaign_text');
        $priority            = $this->input->post('priority');
        $preference          = $this->input->post('preference');
        $commvet             = $this->input->post('commvet');
        $is_test_check       = $this->input->post('is_test_check');
		$is_obd_check        = $this->input->post('is_obd_check');
        $is_previous_base    = $this->input->post('is_previous_base');
        $is_unicode_checked  = $this->input->post('is_unicode_check');
        $base                = $this->input->post('base');
        $remark              = $this->input->post('remark');
        $broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));
		
		
		// Check Exist Data by Brand Name
		if ($brand_name == 'robi') {
			$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
		}elseif ($brand_name == 'airtel') {
			$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
		}// Check Exist Data by Brand Name
			
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign');
		}
		 
        $BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
		//var_dump($BUCKET_ID[0]['BUCKET_ID']);
		
		if ($brand_name == 'robi'){
			$LAST_CAMPAIGN_ID = $this->campaign_model->getRobiNextId();
		}elseif($brand_name == 'airtel'){
			$LAST_CAMPAIGN_ID = $this->campaign_model->getNextId();
		}
        $insertArray = array(
			'ID'                =>$LAST_CAMPAIGN_ID[0]['NEXTID'],
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name,
            'DEPARTMENT_ID'     =>$department_id,
            'BRAND_NAME'        =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'CAMPAIGN_TEXT'     =>$campaign_text,
            'PRIORITY'          =>$priority,
            'PREFERENCE'        =>$preference,
            'COMMVET'           =>$commvet,
            'BROADCAST_DATE'    =>$broadcast_date_new,
			'BASE_COUNT'        =>0, 
            'IS_PREVIOUS_CHECK' =>$is_previous_base,
            'REMARKS'           =>$remark,
            'IS_TEST_CHECK'      =>$is_test_check,
            'IS_OBD_CHECK'      =>$is_obd_check,
            'IS_UNICODE'        =>$is_unicode_checked,
            'BUCKET_ID'         =>$BUCKET_ID[0]['BUCKET_ID']);
       
	    //print_r_pre($insertArray);
        // Insert by Brand Name
	    if ($brand_name == 'robi'){
			$data_insert = $this->campaign_model->insertRobiCampaign($insertArray);
		}elseif($brand_name == 'airtel'){
			$data_insert = $this->campaign_model->insertCampaign($insertArray);
		}// End Insert by Brand Name
		
        
		//echo $this->db->last_query(); //die();
		//print_r_pre($data);
		
		if($data_insert){
			 //var_dump($LAST_CAMPAIGN_ID[0]['NEXTID']);die;
			
			
			
			$TOTAL_BASE_COUNT = 0;
			if($msisdn_type=="employee"){
				$insertArray= array('CAMPAIGN_ID'=>$LAST_CAMPAIGN_ID[0]['NEXTID'],'BASE_ID'=>'0','BASE_COUNT'=>'','CREATED_BY'=>$session_user_name);
				//insert by Brand Name
				if($brand_name == 'robi'){
					$data_insert_1 = $this->global_insert_query->insert($this->tbl_campaing_base_robi,$insertArray);
				}elseif ($brand_name == 'airtel') {
					$data_insert_1 = $this->global_insert_query->insert($this->tbl_campaing_base,$insertArray);
				}//insert by Brand Name
				
				
				if($data_insert_1){
					$this->session->set_flashdata('message_success', 'Static SMS campaign save successful');
					redirect('dnd_controllers/campaign');
				}else{
					$this->session->set_flashdata('message_error', 'Static SMS campaign save failed');
					redirect('dnd_controllers/campaign'); 
				}

			}
			else{
				if($base){
					$base_ids= array();
					foreach($base as $kes => $value){
						$data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID[0]['NEXTID']; 
						$data[$kes]['BASE_ID'] = $value;
						if ($brand_name == 'robi'){
							$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file_robi,array('ID'=>$value));
						}elseif($brand_name == 'airtel'){
							$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file,array('ID'=>$value));
						}
						
						$data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
						$data[$kes]['CREATED_BY'] = $session_user_name;						
						array_push($base_ids,intval($value));
						
					}

					if ($brand_name == 'robi'){
						$total_base= $this->campaign_model->getUniqueCountFromMultipleBaseRobi($base_ids);
					
						$TOTAL_BASE_COUNT=$total_base[0]['UNIQUE_COUNT'];
						$data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base_robi,$data); 
						if($data_insert_1){
							$data_update = $this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$LAST_CAMPAIGN_ID[0]['NEXTID']),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
					}
					}elseif($brand_name == 'airtel'){
						$total_base= $this->campaign_model->getUniqueCountFromMultipleBase($base_ids);
					
					$TOTAL_BASE_COUNT=$total_base[0]['UNIQUE_COUNT'];
					$data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base,$data); 
					if($data_insert_1){
						$data_update = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$LAST_CAMPAIGN_ID[0]['NEXTID']),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
					}

					}

					
				}
				else{
					$data_update = true;
				}
				
			
				 if($data_update){
					 $session_user_mail   = $this->session->userdata('user_mail');
					 $session_user_mail_check   = (int)$this->session->userdata('user_mail_check');
					 //var_dump('mail sending');var_dump($session_user_mail_check);die;
					 if($session_user_mail_check ==1){
						 
								$message= 'Campaign Name: '.$campaing_name.'<br>'.'Broadcast Date: '.$broadcast_date.'\n'.'Campaign Category: '.$campaign_category.'\n'.'Campaign Text:'.$campaign_text.'\n'.'Masking: '.$masking;
 
						 
						 
						 //$session_user_mail='hasibul.hoque@robi.com.bd';
						 
						 $config = Array(
              'protocol' => 'smtp',
              'smtp_host' => 'gateway5.robi.com.bd',
              'smtp_port' => 1003,
              'smtp_user' => 'dnd', // change it to yours
              'smtp_pass' => 'DneD@5656#', // change it to yours 
              'mailtype' => 'html',
              'charset' => 'iso-8859-1',
              'wordwrap' => TRUE
            );
			
			
			
			
					 
					
            $this->load->library('email', $config);
            
            
            $this->email->set_newline("\r\n");
            $this->email->from('dnd@robi.com.bd'); // change it to yours
            $this->email->to($session_user_mail);// change it to yours
            $this->email->subject('Campaign Creation Alert');
            $this->email->message($message); 
			
			$this->email->send();
            echo $this->email->print_debugger();  
            
					 }
					 
					 
					 //mail sending
					 
           

					 //mail sending
					
					
											
												
							
					$this->session->set_flashdata('message_success', 'Static SMS campaign save successful');
					redirect('dnd_controllers/campaign');
				}else{
					$this->session->set_flashdata('message_error', 'Static SMS campaign save failed');  
					redirect('dnd_controllers/campaign');
				}
			}
		
        }

        $this->load->view('layouts/default', $page_info);
    }
	
	
	
    public function do_critical_campaign()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $session_user_name   = $this->session->userdata('user_name');
        $department_id       = (int)$this->input->post('department');
        //echo $department_id ; die();
        //print_r_pre($_POST); die();
        $campaing_name       = $this->input->post('campaing_name');
        $broadcast_date      = $this->input->post('broadcast_date');
        $brand_name          = $this->input->post('brand_name');
        $msisdn_type         = $this->input->post('msisdn_type'); 
        $campaign_category   = $this->input->post('campaign_category');
        $masking             = $this->input->post('masking');
        $campaign_text       = $this->input->post('campaign_text');
		$priority            = $this->input->post('priority');
        $preference          = $this->input->post('preference');
        $commvet             = $this->input->post('commvet');
        $is_test_check        = $this->input->post('is_test_check');
        $is_obd_check        = $this->input->post('is_obd_check');
        $is_unicode_checked    = $this->input->post('is_unicode_check');
        $is_previous_base    = $this->input->post('is_previous_base');
        $base                = $this->input->post('base');
        $remark              = $this->input->post('remark');
        $broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));
		
		
        // Check Exist Data by Brand Name
        if ($brand_name == 'robi') {
        	$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
        }elseif ($brand_name == 'airtel') {
        	$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
        }
			
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/critical_campaign');
		}
		

        $BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
        //var_dump($BUCKET_ID[0]['BUCKET_ID']);

        //Get Last ID by Brand Name
        if ($brand_name == 'robi'){
			$LAST_CAMPAIGN_ID = $this->campaign_model->getRobiNextId();
		}elseif($brand_name == 'airtel'){
			$LAST_CAMPAIGN_ID = $this->campaign_model->getNextId();
		}//Get Last ID by Brand Name
        $insertArray = array(
			'ID'                =>$LAST_CAMPAIGN_ID[0]['NEXTID'],
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name,
            'DEPARTMENT_ID'     =>$department_id,
            'BRAND_NAME'        =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'CAMPAIGN_TEXT'     =>$campaign_text,
            'PREFERENCE'        =>$preference,
            'COMMVET'           =>$commvet,
            'BROADCAST_DATE'    =>$broadcast_date_new,
            'BASE_COUNT'        =>0,
            'IS_PREVIOUS_CHECK' =>$is_previous_base,
            'REMARKS'           =>$remark,
            'PRIORITY'          =>$priority,
            'IS_TEST_CHECK'      =>$is_test_check,
            'IS_OBD_CHECK'      =>$is_obd_check,
            'IS_UNICODE'        =>$is_unicode_checked,
            'IS_CRITICAL'       =>1,
            'BUCKET_ID'         =>$BUCKET_ID[0]['BUCKET_ID']);

        //print_r_pre($insertArray);
        // Insert by Brand Name
        if ($brand_name == 'robi') {
        	$data_insert = $this->global_insert_query->insert($this->tbl_campaing_robi,$insertArray);
        }elseif ($brand_name == 'airtel') {
        	$data_insert = $this->global_insert_query->insert($this->tbl_campaing,$insertArray);
        }// Insert by Brand Name
        
        //echo $this->db->last_query(); //die();
        //print_r_pre($data);

        if($data_insert){

           

            $TOTAL_BASE_COUNT = 0;
            foreach($base as $kes => $value){
                $data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID[0]['NEXTID'];
                $data[$kes]['BASE_ID'] = $value;
                // Base Count by Brand Name
                if ($brand_name == 'robi') {
                	$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file_robi,array('ID'=>$value));
                }elseif ($brand_name == 'airtel') {
                	$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file,array('ID'=>$value));
                }// Base Count by Brand Name
                
                $data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
                $data[$kes]['CREATED_BY'] = $session_user_name;
                $TOTAL_BASE_COUNT+=$BASE_COUNT[0]['TOTAL_NUMBER'];
            }

            // Insert Batch by Brand Name
            if ($brand_name == 'robi') {
            	$data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base_robi,$data);
            }elseif ($brand_name == 'airtel') {
            	$data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base,$data);
            }// Insert Batch by Brand Name
            


            if($data_insert_1){
            	// Update by Brand Name
            	if ($brand_name == 'robi') {
            		$data_update = $this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$LAST_CAMPAIGN_ID[0]['NEXTID']),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
            	}elseif ($brand_name == 'airtel') {
            		$data_update = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$LAST_CAMPAIGN_ID[0]['NEXTID']),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
            	}// Update by Brand Name
                

                if($data_update){
                    $this->session->set_flashdata('message_success', 'Critical campaign save successful');
                    redirect('dnd_controllers/campaign/critical_campaign');
                }else{
                    $this->session->set_flashdata('message_error', 'Critical campaign save failed');
                    redirect('dnd_controllers/campaign/critical_cmapaign');
                }
            }

        }

        $this->load->view('layouts/default', $page_info);
    }

	

    public function do_sms_dynamic_campaign()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      ='';

        $session_brand_name = $this->session->userdata('user_brand_name');

        $uploadpath = '/var/www/html/dndBase/dyn_base/';
        //$config['upload_path'] = './uploads/obd_base/';
        $config['upload_path'] =$uploadpath;
        $config['allowed_types'] = 'pdf|doc|docx|rtf|text|txt';
        
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
        //$config['encrypt_name'] = true;
       
	   
	   $ff = explode('.',$_FILES["upload_file"]['name']);
	   $ff[0] = preg_replace('/\s+/', '_', $ff[0]); 
	   $f_name = $ff[0].'_'.str_replace(':','-',date('d-m-Y_H:i:s'));
	   
	   $new_name = $f_name.'.'.$ff[1];
	   $config['file_name'] = $new_name;
	   
	   


        $CI =& get_instance();
        $CI->load->library('upload', $config);

        $upload=$CI->upload->do_upload("upload_file");
		chmod($uploadpath.$new_name,0775);
        $inputfilename      = $_FILES['upload_file']['tmp_name']; 
        $filename           = $new_name;
        $btn_upload         = $this->input->post('dynamic_campaign_create');
		
		if(!$upload){
			$this->session->set_flashdata('message_error', 'Upload Failed !!');
                redirect(base_url('dnd_controllers/campaign'));
		}
        /* 
		if($btn_upload)
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
		
        foreach ($file_data as $key => $value) {
            $dynamiclist = preg_split("/[|:]/", $value);
            $msisdn[$key]['MSISDN'] = $dynamiclist[0];
            $msisdn[$key]['DYN_TEXT'] = $dynamiclist[1];
        }
		*/

        // Upload File by Brand Name
		if ($session_brand_name == 'robi') {
			$file_id=$this->base_model->upload_dynamic_base_number_robi($filename);
		}elseif ($session_brand_name == 'airtel') {
			$file_id=$this->base_model->upload_dynamic_base_number($filename);
		}// Upload File by Brand Name
	 
            
        if(!file_id) {
            $this->session->set_flashdata('message_error', 'Base file processing failed');
            redirect('dnd_controllers/campaign');
        }

        $session_user_name   = $this->session->userdata('user_name');
        $department_id       = (int)$this->input->post('department');

        $campaing_name       = $this->input->post('campaing_name');
        $broadcast_date      = $this->input->post('broadcast_date');
        $brand_name          = $this->input->post('brand_name_dynamic');
        $msisdn_type         = $this->input->post('msisdn_type_dyanamic');
        $campaign_category   = $this->input->post('campaign_category_dynamic');
        $masking             = $this->input->post('masking_dynamic');
        $priority            = $this->input->post('priority');
        $preference          = $this->input->post('preference_dynamic');
        $commvet             = $this->input->post('commvet_dyanamic');
        $is_test_check        = $this->input->post('is_test_check');
        $is_obd_check        = $this->input->post('is_obd_check');
        $is_unicode_checked    = $this->input->post('is_unicode_check');
        $remark              = $this->input->post('remark');
        $broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));
		
		// Check Exist Data by Brand Name
		if ($brand_name == 'robi') {
			$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
		}elseif ($brand_name == 'airtel') {
			$campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing,array('CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));
		}// Check Exist Data by Brand Name
			
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/critical_campaign');
		}

        $BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
        //var_dump($BUCKET_ID[0]['BUCKET_ID']);
        $insertArray = array(
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name,
            'DEPARTMENT_ID'     =>$department_id,
            'BRAND_NAME'        =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'PRIORITY'          =>$priority,
            'PREFERENCE'        =>$preference,
            'COMMVET'           =>$commvet,
            'BROADCAST_DATE'    =>$broadcast_date_new,
            'BASE_COUNT'        =>1,
            'REMARKS'           =>$remark,
            'IS_TEST_CHECK'      =>$is_test_check,
            'IS_OBD_CHECK'      =>$is_obd_check,
            'IS_UNICODE'        =>$is_unicode_checked,
            'IS_DYNAMIC'        =>1,
            'BUCKET_ID'         =>$BUCKET_ID[0]['BUCKET_ID']);

        //print_r_pre($insertArray);
        //echo $this->db->last_query(); //die();
        //print_r_pre($data);



        //Insert By Brand Name
        if ($brand_name == 'robi') {
        	$data_insert = $this->global_insert_query->insert($this->tbl_campaing_robi,$insertArray);
        }elseif ($brand_name == 'airtel') {
        	$data_insert = $this->global_insert_query->insert($this->tbl_campaing,$insertArray);
        }// End Insert By Brand Name

        


        if($data_insert){
        	//Get Last id By Brand Name
            if ($brand_name == 'robi') {
	        	$LAST_CAMPAIGN_ID = $this->global_select_query->get_Last_id($this->tbl_campaing_robi);
	        }elseif ($brand_name == 'airtel') {
	        	$LAST_CAMPAIGN_ID = $this->global_select_query->get_Last_id($this->tbl_campaing);
	            //print_r_pre($LAST_CAMPAIGN_ID); die();
	        }//Get Last id By Brand Name



            $TOTAL_BASE_COUNT = 0;


                $data['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID;
                $data['BASE_ID'] = $file_id;

                // Get All Data by Brand Name
                if ($brand_name == 'robi') {
		        	$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file_robi,array('ID'=>$file_id));
		        }elseif ($brand_name == 'airtel') {
		        	$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file,array('ID'=>$file_id));
		        }// Get All Data by Brand Name

                
                $data['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
                $data['CREATED_BY'] = $session_user_name;
                $TOTAL_BASE_COUNT+=$BASE_COUNT[0]['TOTAL_NUMBER'];


            //Insert By Brand Name
            if ($brand_name == 'robi') {
	        	$data_insert_1 = $this->global_insert_query->insert($this->tbl_campaing_base_robi,$data);
	        }elseif ($brand_name == 'airtel') {
	        	$data_insert_1 = $this->global_insert_query->insert($this->tbl_campaing_base,$data);
	        }// End Insert By Brand Name

            

            if($data_insert_1){
            	// Update By Brand Name
            	if ($brand_name == 'robi') {
		        	$data_update = $this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$LAST_CAMPAIGN_ID),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
		        }elseif ($brand_name == 'airtel') {
		        	$data_update = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$LAST_CAMPAIGN_ID),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));
		        }// End Update By Brand Name
                

                if($data_update){
						 $session_user_mail   = $this->session->userdata('user_mail');
					 $session_user_mail_check   = (int)$this->session->userdata('user_mail_check');
					 //var_dump('mail sending');var_dump($session_user_mail_check);die;
					 if($session_user_mail_check ==1){
						 
								$message= 'Campaign Name: '.$campaing_name.'\n'.'Broadcast Date: '.$broadcast_date.'\n'.'Campaign Category: '.$campaign_category.'\n'.'Campaign Text:'.$campaign_text.'\n'.'Masking: '.$masking;
 
						 
						 
						 //$session_user_mail='hasibul.hoque@robi.com.bd';
						 
						 $config = Array(
              'protocol' => 'smtp',
              'smtp_host' => 'gateway5.robi.com.bd',
              'smtp_port' => 1003,
              'smtp_user' => 'dnd', // change it to yours
              'smtp_pass' => 'DneD@5656#', // change it to yours 
              'mailtype' => 'html',
              'charset' => 'iso-8859-1',
              'wordwrap' => TRUE
            );
			
			
			
			
					 
					
            $this->load->library('email', $config);
            
            
            $this->email->set_newline("\r\n");
            $this->email->from('dnd@robi.com.bd'); // change it to yours
            $this->email->to($session_user_mail);// change it to yours
            $this->email->subject('Campaign Creation Alert');
            $this->email->message($message); 
			
			$this->email->send();
            echo $this->email->print_debugger();  
            
					 }
                    $this->session->set_flashdata('message_success', 'Dynamic SMS campaign save successful');
                    redirect('dnd_controllers/campaign');
                }else{
                    $this->session->set_flashdata('message_error', 'Dynamic SMS campaign save failed');
                    redirect('dnd_controllers/campaign');
                }
            }

        }

        $this->load->view('layouts/default', $page_info);
    }



    //obd campagin creation
    public function do_obd_campaign()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $session_user_name   = $this->session->userdata('user_name');
        $department_id       = (int)$this->input->post('department');
        //echo $department_id ; die();
        //print_r_pre($_POST); die();
        $campaing_name       = $this->input->post('campaing_name');
        $broadcast_start_date      = $this->input->post('broadcast_start_date');
        $broadcast_end_date      = $this->input->post('broadcast_end_date');
        $brand_name          = $this->input->post('brand_name');
        $msisdn_type         = $this->input->post('msisdn_type');
        $campaign_category   = $this->input->post('campaign_category');
        $masking             = $this->input->post('masking');
        $preference          = $this->input->post('preference');
        $base                = $this->input->post('base');
        $threshold_per_day   = $this->input->post('threshold_per_day');
        $is_obd_priority     = $this->input->post('is_obd_priority');
        $remark              = $this->input->post('remark');
        $broadcast_start_date_new  = date("d-M-Y", strtotime($broadcast_start_date));
        $broadcast_end_date_new    = date("d-M-Y", strtotime($broadcast_end_date));


        $campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaign_obd,array('CAMPAIGN_NAME'=>$campaing_name,'START_DATE'=>$broadcast_start_date_new));
			
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/obd_campaign');
		}
		
        $BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
        //var_dump($BUCKET_ID[0]['BUCKET_ID']);
        $insertArray = array(
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name,
            'DEPARTMENT_ID'     =>$department_id,
            'BRAND_NAME'        =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'PREFERENCE'        =>$preference,
            'START_DATE'        =>$broadcast_start_date_new,
            'END_DATE'          =>$broadcast_end_date_new,
            'BASE_COUNT'        =>0,
            'THRESHOLD'         =>$threshold_per_day,
            'IS_OBD_PRIORITY'   =>$is_obd_priority,
            'REMARKS'           =>$remark,
            'BUCKET_ID'         =>$BUCKET_ID[0]['BUCKET_ID']);

        //print_r_pre($insertArray);
        $data_insert = $this->global_insert_query->insert($this->tbl_campaign_obd,$insertArray);
        //echo $this->db->last_query(); //die();
        //print_r_pre($data);

        if($data_insert){  

            $LAST_CAMPAIGN_ID = $this->global_select_query->get_Last_id($this->tbl_campaign_obd);
            //print_r_pre($LAST_CAMPAIGN_ID); die();

            $TOTAL_BASE_COUNT = 0;
            foreach($base as $kes => $value){
                $data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID;
                $data[$kes]['BASE_ID'] = $value;
                $BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_obd_base_file,array('ID'=>$value));
                $data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
                $data[$kes]['CREATED_BY'] = $session_user_name;
                $TOTAL_BASE_COUNT+=$BASE_COUNT[0]['TOTAL_NUMBER'];

            }
            $data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_obd_campaign_base,$data);

            if($data_insert_1){
                $data_update = $this->global_update_query->update($this->tbl_campaign_obd,array('ID'=>$LAST_CAMPAIGN_ID),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));

                if($data_update){
                    $this->session->set_flashdata('message_success', 'OBD campaign save successful');
                    redirect('dnd_controllers/campaign/obd_campaign');
                }else{
                    $this->session->set_flashdata('message_error', 'OBD campaign save failed');
                    redirect('dnd_controllers/campaign/obd_campaign');
                }
            }

        }

        $this->load->view('layouts/default', $page_info);
    }


    public function campaing_list()
    {
        $page_info['title']             = 'Campaign List' . $this->site_name;
        $page_info['view_page']         = 'campaign/campaing_list_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$page_info['allData']           = $this->campaign_model->getCampaignlist();


        $department_data= $this->global_select_query->is_data_exists($this->tbl_campaing);
       
        $uri_segment =  $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;


        $per_page = $this->config->item('per_page');
        //SEARCH DATA
         $searchSelect = $this->input->post('searchSelect');
         $txtSearch = $this->input->post('txtSearch');
         $search_date = $this->input->post('search_date');
         $search_date_new = date("d-M-Y", strtotime($search_date));
		 
         //$s_data = array('ID'=>'326');
         //$result_data = $this->campaign_model->searchCampaignlists($s_data);
        // var_dump($result_data); die();

        if(isset($_POST['search'])){
            $array = array('searchSelect'=>$searchSelect,'txtSearch'=>$txtSearch,'search_date'=>$search_date_new);
           $page_info['allData']           = $this->campaign_model->getCampaignlist($array);
        }else{
			if($this->session->userdata('user_login_type')!='admin') 
			{
				//var_dump($this->session->userdata('user_login_type'));die;
				$array = array('TA.DEPARTMENT_ID'=>$this->session->userdata('user_department'));
				$page_info['allData']           = $this->campaign_model->getCampaignlistByDept($array);
			}
			else{
				$page_info['allData']           = $this->campaign_model->getCampaignlist();
			}
            
            
        }
        // GENERATING TABLE

        $record_result = $this->global_select_query->select_custom_limit($this->tbl_campaing,$per_page,$page_offset);
       
        //print_r_pre($record_result);die();

        $page_info['records']   = $record_result[0]['ID'];
        $config                 = array();
        $config["base_url"]     = base_url()."dnd_controllers/campaign/campaing_list";
        $config["total_rows"]   = count($department_data);
        $config['per_page']     = $this->config->item('per_page');
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
  
        if ($record_result) {

            $tbl_heading = array(
                '0' => array('data'=> 'ID'),
                '1' => array('data'=> 'Team Category'),
                '2' => array('data'=> 'Category'),
                '3' => array('data'=> 'Campaign Name'),
                '4' => array('data'=> 'Campaign ID'),
                '5' => array('data'=> 'Brand '),
                '6' => array('data'=> 'Masking'),
                '7' => array('data'=> 'SMS'),
                '8' => array('data'=> 'Priority'),
                '9' => array('data'=> 'Base'),
                '10' => array('data'=> 'Target'),
                '11' => array('data'=> 'MSISDN Type'),
                '12' => array('data'=> 'publish'),
                '13' => array('data'=> 'Priority '),
                '14' => array('data'=> 'CAMPAIGN TEXT'),
                '15' => array('data'=> 'ACTION', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);
            $i=1;
           //print_r_pre($record_result);die();
            foreach ($record_result as $key) {
                
                $role_str = $key['CAMPAIGN_NAME'];
                if($key['IS_DND_CHECK']==1){
                    $desc_str = "Active";
                }else{
                    $desc_str = "N/A";
                }
                $action_str = '';
                $action_str .= '<button data-id="'.$key['ID'].'" data-dname="'.$key['CAMPAIGN_NAME'].'" data-active="'.$key['IS_DND_CHECK'].'" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>';
                $tbl_row = array(
                    '0' => array('data'=> $i),
                    '1' => array('data'=> $role_str),
                    '2' => array('data'=> $key['BROADCAST_DATE']),
                    '3' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '4' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '5' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '6' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '7' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '8' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '9' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '10' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '11' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '12' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '13' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '14' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '15' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
                );
                $this->table->add_row($tbl_row);
                $i++; 
            }

            $page_info['records_table'] = $this->table->generate();
        } else {
            $page_info['records_table'] = '<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>';
            $page_info['pagin_links'] = '';
        }
        
        

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);

    }
	
	
	
	 public function campaing_list_robi()
    {
        $page_info['title']             = 'Campaign List' . $this->site_name;
        $page_info['view_page']         = 'campaign/campaing_list_robi';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$page_info['allData']           = $this->campaign_model->getCampaignlistRObi();


        $department_data= $this->global_select_query->is_data_exists($this->tbl_campaing_robi);
       
        $uri_segment =  $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;


        $per_page = $this->config->item('per_page');
        //SEARCH DATA
         $searchSelect = $this->input->post('searchSelect');
         $txtSearch = $this->input->post('txtSearch');
         $search_date = $this->input->post('search_date');
         $search_date_new = date("d-M-Y", strtotime($search_date));
		 
         //$s_data = array('ID'=>'326');
         //$result_data = $this->campaign_model->searchCampaignlists($s_data);
        // var_dump($result_data); die();

        if(isset($_POST['search'])){
            $array = array('searchSelect'=>$searchSelect,'txtSearch'=>$txtSearch,'search_date'=>$search_date_new);
           $page_info['allData']           = $this->campaign_model->getCampaignlistRObi($array);
        }else{
			if($this->session->userdata('user_login_type')!='admin') 
			{
				//var_dump($this->session->userdata('user_login_type'));die; 
				$array = array('TA.DEPARTMENT_ID'=>$this->session->userdata('user_department'));
				$page_info['allData']           = $this->campaign_model->getCampaignlistByDeptRobi($array);
			}
			else{
				$page_info['allData']           = $this->campaign_model->getCampaignlistRObi();
			}
            
            
        }
        // GENERATING TABLE

        $record_result = $this->global_select_query->select_custom_limit($this->tbl_campaing_robi,$per_page,$page_offset);
       
        //print_r_pre($record_result);die();

        $page_info['records']   = $record_result[0]['ID'];
        $config                 = array();
        $config["base_url"]     = base_url()."dnd_controllers/campaign/campaing_list_robi";
        $config["total_rows"]   = count($department_data);
        $config['per_page']     = $this->config->item('per_page');
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();
  
        if ($record_result) {

            $tbl_heading = array(
                '0' => array('data'=> 'ID'),
                '1' => array('data'=> 'Team Category'),
                '2' => array('data'=> 'Category'),
                '3' => array('data'=> 'Campaign Name'),
                '4' => array('data'=> 'Campaign ID'),
                '5' => array('data'=> 'Brand '),
                '6' => array('data'=> 'Masking'),
                '7' => array('data'=> 'SMS'),
                '8' => array('data'=> 'Priority'),
                '9' => array('data'=> 'Base'),
                '10' => array('data'=> 'Target'),
                '11' => array('data'=> 'MSISDN Type'),
                '12' => array('data'=> 'publish'),
                '13' => array('data'=> 'Priority '),
                '14' => array('data'=> 'CAMPAIGN TEXT'),
                '15' => array('data'=> 'ACTION', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);
            $i=1;
           //print_r_pre($record_result);die();
            foreach ($record_result as $key) {
                
                $role_str = $key['CAMPAIGN_NAME'];
                if($key['IS_DND_CHECK']==1){
                    $desc_str = "Active";
                }else{
                    $desc_str = "N/A";
                }
                $action_str = '';
                $action_str .= '<button data-id="'.$key['ID'].'" data-dname="'.$key['CAMPAIGN_NAME'].'" data-active="'.$key['IS_DND_CHECK'].'" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>';
                $tbl_row = array(
                    '0' => array('data'=> $i),
                    '1' => array('data'=> $role_str),
                    '2' => array('data'=> $key['BROADCAST_DATE']),
                    '3' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '4' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '5' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '6' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '7' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '8' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '9' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '10' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '11' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '12' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '13' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '14' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '15' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
                );
                $this->table->add_row($tbl_row);
                $i++; 
            }

            $page_info['records_table'] = $this->table->generate();
        } else {
            $page_info['records_table'] = '<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>';
            $page_info['pagin_links'] = '';
        }
        
        

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);

    }
	
	 public function obd_campaign_list()
    {
        $page_info['title']             = 'OBD Campaign List' . $this->site_name;
        $page_info['view_page']         = 'campaign/obd_campaign_list_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		$page_info['allData']           = $this->campaign_model->getObdCampaignlist();
		//var_dump($allData); die();

        $department_data= $this->global_select_query->is_data_exists($this->tbl_campaign_obd);
       
        $uri_segment =  $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

       //var_dump($page_offset);die();
        $per_page = $this->config->item('per_page');
        $record_result = $this->global_select_query->select_custom_limit($this->tbl_campaing,$per_page,$page_offset);
       
        //var_dump($per_page);die();
		
		$page_info['user_login_type']    = $this->session->userdata('user_login_type') ; 

        $page_info['records']   = $record_result[0]['ID'];
        $config                 = array();
        $config["base_url"]     = base_url()."dnd_controllers/campaign/obd_campaign_list";
        $config["total_rows"]   = count($department_data);
        $config['per_page']     = $this->config->item('per_page');
        $this->pagination->initialize($config);
        $page_info['pagin_links'] = $this->pagination->create_links();


            // GENERATING TABLE
  /*
        if ($record_result) {

            $tbl_heading = array(
                '0' => array('data'=> 'ID'),
                '1' => array('data'=> 'Team Category'),
                '2' => array('data'=> 'Category'),
                '3' => array('data'=> 'Campaign Name'),
                '4' => array('data'=> 'Campaign ID'),
                '5' => array('data'=> 'Brand '),
                '6' => array('data'=> 'Masking'),
                '7' => array('data'=> 'Base'),
                '8' => array('data'=> 'Target'),
                '9' => array('data'=> 'MSISDN Type'),
                '10' => array('data'=> 'publish'),
                '11' => array('data'=> 'ACTION', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl2" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);
            $i=1;
            foreach ($record_result as $key) {
                
                $role_str = $key['CAMPAIGN_NAME'];

                $action_str = '';
                $action_str .= '<button data-id="'.$key['ID'].'" data-dname="'.$key['CAMPAIGN_NAME'].'" data-active="'.$key['IS_DND_CHECK'].'" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>';
                $tbl_row = array(
                    '0' => array('data'=> $i),
                    '1' => array('data'=> $role_str),
                    '2' => array('data'=> $key['START_DATE']),
                    '3' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '4' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '5' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '6' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '7' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '8' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '9' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '10' => array('data'=> $key['CAMPAIGN_TEXT']),
                    '11' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
                );
                $this->table->add_row($tbl_row);
                $i++; 
            }

            $page_info['records_table'] = $this->table->generate();
        } else {
            $page_info['records_table'] = '<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>';
            $page_info['pagin_links'] = '';
        }
  */




        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $this->load->view('layouts/default', $page_info);

    }

    public function campaign_update_view()
    {
        $page_info['title']             = 'Update Airtel Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaing_update_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
        
        $cmsegment = $this->uri->segment(4);
        
        $is_group_manager = $this->session->userdata('user_group_manager') ;
	$campaignDetails = $this->global_select_query->is_data_exists($this->tbl_campaing,array('ID'=>$cmsegment));	
        $broadCastDate = $campaignDetails[0]['BROADCAST_DATE'];
        //print_r($broadCastDate); die();
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        //$source = '2012-Oct-31';
        $date = new DateTime($broadCastDate);
        $broadCastDate_1 = $date->format('Ymd');
        $current_date=date("Ymd");
        
        $is_true=(int)$broadCastDate_1 >= (int)$current_date;
		$is_future=(int)$broadCastDate_1 > (int)$current_date; 
        //echo 'test'.$is_true; die();
        if ($is_group_manager ) {
            $page_info['view_page'] = 'campaign/campaing_update_view';
        }	
        else {
            //print_r($broadCastDate); die();
            $cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CANCELLATION_START","CAMPAIGN_CANCELLATION_ENDS");

            if($cam_per && $is_true || $is_future){
                    $page_info['view_page'] = 'campaign/campaing_update_view';
                    //echo "create";
            }else{
                    $page_info['view_page'] = 'campaign/campaign_expired_view';
                    $page_info['message']   = ' CAMPAIGN UPDATE TIME EXPIRED';
                    //echo "error";
            }
        }
		
        $page_info['campaignDetails'] = $this->global_select_query->is_data_exists($this->tbl_campaing,array('ID'=>$cmsegment));
		//print_r_pre($page_info['campaignDetails'][0]['BROADCAST_DATE']); die();
		
		
        $page_info['is_group_manager']    = $is_group_manager ;
		$page_info['masking_info']        = $this->campaign_model->getMasking($page_info['campaignDetails'][0]['BRAND_NAME']);
		$page_info['base_info']           = $this->campaign_model->getAllBaseByDeptId($page_info['campaignDetails'][0]['DEPARTMENT_ID']);
		$page_info['selected_base_info']  = $this->global_select_query->get_All_base_by_campaign_id($this->tbl_campaing_base,$cmsegment);
		//print_r_pre($page_info['selected_base_info']);
        $this->load->view('layouts/default', $page_info);
    }
	
	
	   public function campaign_update_view_robi()
    {
        $page_info['title']             = 'Update Robi Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/campaing_update_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
        
        $cmsegment = $this->uri->segment(4);
        
        $is_group_manager = $this->session->userdata('user_group_manager') ;
	$campaignDetails = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('ID'=>$cmsegment));	
        $broadCastDate = $campaignDetails[0]['BROADCAST_DATE'];
        //print_r($broadCastDate); die();
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        //$source = '2012-Oct-31';
        $date = new DateTime($broadCastDate);
        $broadCastDate_1 = $date->format('Ymd');
        $current_date=date("Ymd");
        
        $is_true=(int)$broadCastDate_1 >= (int)$current_date;
		$is_future=(int)$broadCastDate_1 > (int)$current_date; 
        //echo 'test'.$is_true; die();
        if ($is_group_manager ) {
            $page_info['view_page'] = 'campaign/campaing_update_view_robi';
        }	
        else {
            //print_r($broadCastDate); die();
            $cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CANCELLATION_START","CAMPAIGN_CANCELLATION_ENDS");

            if($cam_per && $is_true || $is_future){
                    $page_info['view_page'] = 'campaign/campaing_update_view_robi';
                    //echo "create";
            }else{
                    $page_info['view_page'] = 'campaign/campaing_update_view_robi';
                    $page_info['message']   = ' CAMPAIGN UPDATE TIME EXPIRED';
                    //echo "error";
            }
        }
		
        $page_info['campaignDetails'] = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('ID'=>$cmsegment));
		//print_r_pre($page_info['campaignDetails'][0]['BROADCAST_DATE']); die();
		
		
        $page_info['is_group_manager']    = $is_group_manager ;
		$page_info['masking_info']        = $this->campaign_model->getMasking($page_info['campaignDetails'][0]['BRAND_NAME']);
		$page_info['base_info']           = $this->campaign_model->getAllBaseByDeptId_robi($page_info['campaignDetails'][0]['DEPARTMENT_ID']);
		$page_info['selected_base_info']  = $this->global_select_query->get_All_base_by_campaign_id($this->tbl_campaing_base_robi,$cmsegment);
		//print_r_pre($page_info['selected_base_info']);
        $this->load->view('layouts/default', $page_info);
    }


    public function obd_campaign_update_view()
    {
        $page_info['title']             = 'Update Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/obd_campaign_update_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $cmsegment = $this->uri->segment(4);

        $is_group_manager = $this->session->userdata('user_group_manager') ;
        $campaignDetails = $this->global_select_query->is_data_exists($this->tbl_campaign_obd,array('ID'=>$cmsegment));
        $broadCastDate = $campaignDetails[0]['START_DATE'];
        //print_r($broadCastDate); die();
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        //$source = '2012-Oct-31';
        $date = new DateTime($broadCastDate);
        $broadCastDate_1 = $date->format('Ymd');
        $current_date=date("Ymd");

        $is_true=(int)$broadCastDate_1 >= (int)$current_date;
        //var_dump($is_true.' '.$broadCastDate_1.' '.$current_date);die;
        //echo 'test'.$is_true; die();
        if ($is_group_manager ) {
            $page_info['view_page'] = 'campaign/obd_campaign_update_view';
        }
        else {
            //print_r($broadCastDate); die();
            $cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CANCELLATION_START","CAMPAIGN_CANCELLATION_ENDS");
            //var_dump($cam_per.'is true: '.$is_true.' ');die;
            if($cam_per && $is_true){
                $page_info['view_page'] = 'campaign/obd_campaign_update_view';

            }else{
                $page_info['view_page'] = 'campaign/campaign_expired_view';
                $page_info['message']   = ' CAMPAIGN UPDATE TIME EXPIRED';
                //echo "error";
            }
        }

        $page_info['campaignDetails'] = $this->global_select_query->is_data_exists($this->tbl_campaign_obd,array('ID'=>$cmsegment));
        //print_r_pre($page_info['campaignDetails'][0]['BROADCAST_DATE']); die();


        $page_info['is_group_manager']    = $is_group_manager ;
        $page_info['masking_info']        = $this->campaign_model->getMasking();
        $page_info['base_info']           = $this->campaign_model->getAllObdBase();
        $page_info['selected_base_info']  = $this->global_select_query->get_All_base_by_campaign_id($this->tbl_obd_campaign_base,$cmsegment);
        //print_r_pre($page_info['selected_base_info']);
        $this->load->view('layouts/default', $page_info);
    }


    public function update_campaign()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		
	if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        
        $session_user_name      = $this->session->userdata('user_name');
        $department_id          = (int)$this->input->post('department');
        //echo $department_id ; die();
        //print_r_pre($_POST); die();
        $campaign_id            =  $this->input->post('campaign_id'); 
        //echo $cmsegment ; die();
        $campaing_name          = $this->input->post('campaing_name');
        $broadcast_date         = $this->input->post('broadcast_date');
        $brand_name             = $this->input->post('brand_name');
        $msisdn_type            = $this->input->post('msisdn_type');
        $campaign_category      = $this->input->post('campaign_category');
        $masking                = $this->input->post('masking');
        $campaign_text          = $this->input->post('campaign_text');
        $priority               = $this->input->post('priority');
        $old_priority           = $this->input->post('old_priority');
        $preference             = $this->input->post('preference');
        $commvet                = $this->input->post('commvet');
		$broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));  
        $base                   = $this->input->post('base');
        $remark                 = $this->input->post('remark');
        //$broadcast_date_new = date("d-M-Y", strtotime($broadcast_date));
		 
		 $campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing,array('ID<>'=>$campaign_id,'CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));	
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/campaign_update_view');
		}
		 
        //$BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
		//var_dump($BUCKET_ID[0]['BUCKET_ID']);
        $insertArray = array(
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name
            //,'DEPARTMENT_ID'  =>$department_id
            ,'BRAND_NAME'       =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'CAMPAIGN_TEXT'     =>$campaign_text,
            'PRIORITY'          =>$priority
            ,'PREFERENCE'       =>$preference,
            'COMMVET'           =>$commvet
            //,'BROADCAST_DATE' =>$broadcast_date_new
            ,'BASE_COUNT'       =>1,             
            'REMARKS'           =>$remark
           

            //,'BUCKET_ID'      =>$BUCKET_ID[0]['BUCKET_ID']
            );


       
	    //print_r_pre($insertArray);
        $data_update = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$campaign_id),$insertArray);
            //echo $this->db->last_query(); //die();
            //print_r_pre($data);
        $getCam_Dep_Date =  $this->campaign_model->getCampaignByDepartmentAndDate($department_id,$broadcast_date,$priority,$old_priority);
        //echo $this->db->last_query(); //die();
            //print_r_pre($getCam_Dep_Date);
        if($data_update && $getCam_Dep_Date){

            $LAST_CAMPAIGN_ID = $campaign_id;
			
								
				
            //print_r_pre($LAST_CAMPAIGN_ID); die();

            $base_ids= array();
					foreach($base as $kes => $value){
						$data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID;
						$data[$kes]['BASE_ID'] = $value;
						$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file,array('ID'=>$value));
						$data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
						$data[$kes]['CREATED_BY'] = $session_user_name;						
						array_push($base_ids,intval($value));
						
					}
					$total_base= $this->campaign_model->getUniqueCountFromMultipleBase($base_ids);
					
					$TOTAL_BASE_COUNT=$total_base[0]['UNIQUE_COUNT'];
            $data_delete = $this->global_delete_query->delete($this->tbl_campaing_base,array('CAMPAIGN_ID'=>$campaign_id));
            //echo $data_delete; die();
            if($data_delete){
                    $data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base,$data);


                    if($data_insert_1){
                            $data_update_2 = $this->global_update_query->update($this->tbl_campaing,array('ID'=>$campaign_id),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));

                             if($data_update_2){
                                    $this->session->set_flashdata('message_success', 'Campaign update successful');
                                    redirect('dnd_controllers/campaign/campaing_list');
                            }else{
                                    $this->session->set_flashdata('message_error', 'Campaign update failed');
                                    redirect('dnd_controllers/campaign/campaing_list');
                            }
                    }
            }
		
        }

        $this->load->view('layouts/default', $page_info);
    }
	
	
	
	  public function update_campaign_robi()
    {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
		
	if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        
        $session_user_name      = $this->session->userdata('user_name');
        $department_id          = (int)$this->input->post('department');
        //echo $department_id ; die();
        //print_r_pre($_POST); die();
        $campaign_id            =  $this->input->post('campaign_id'); 
        //echo $cmsegment ; die();
        $campaing_name          = $this->input->post('campaing_name');
        $broadcast_date         = $this->input->post('broadcast_date');
        $brand_name             = $this->input->post('brand_name');
        $msisdn_type            = $this->input->post('msisdn_type');
        $campaign_category      = $this->input->post('campaign_category');
        $masking                = $this->input->post('masking');
        $campaign_text          = $this->input->post('campaign_text');
        $priority               = $this->input->post('priority');
        $old_priority           = $this->input->post('old_priority');
        $preference             = $this->input->post('preference');
        $commvet                = $this->input->post('commvet');
		$broadcast_date_new  = date("d-M-Y", strtotime($broadcast_date));  
        $base                   = $this->input->post('base');
        $remark                 = $this->input->post('remark');
        //$broadcast_date_new = date("d-M-Y", strtotime($broadcast_date));
		 
		 $campaignNameExist = $this->global_select_query->is_data_exists($this->tbl_campaing_robi,array('ID<>'=>$campaign_id,'CAMPAIGN_NAME'=>$campaing_name,'BROADCAST_DATE'=>$broadcast_date_new));	
		if($campaignNameExist)
		{
			$this->session->set_flashdata('message_error','Campaign name already exists');
					redirect('dnd_controllers/campaign/campaign_update_view_robi');
		}
		 
        //$BUCKET_ID = $this->global_select_query->select_get_all_data_by_where($this->tbl_bucket_department,array('DEPARTMENT_ID'=>$department_id));
		//var_dump($BUCKET_ID[0]['BUCKET_ID']);
        $insertArray = array(
            'CAMPAIGN_NAME'     =>$campaing_name,
            'CREATED_BY'        =>$session_user_name
            //,'DEPARTMENT_ID'  =>$department_id
            ,'BRAND_NAME'       =>$brand_name,
            'MSISDN_TYPE'       =>$msisdn_type,
            'CATEGORY_ID'       =>$campaign_category,
            'MASKING_ID'        =>$masking,
            'CAMPAIGN_TEXT'     =>$campaign_text,
            'PRIORITY'          =>$priority
            ,'PREFERENCE'       =>$preference,
            'COMMVET'           =>$commvet
            //,'BROADCAST_DATE' =>$broadcast_date_new
            ,'BASE_COUNT'       =>1,             
            'REMARKS'           =>$remark
           

            //,'BUCKET_ID'      =>$BUCKET_ID[0]['BUCKET_ID']
            );


       
	    //print_r_pre($insertArray);
        $data_update = $this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$campaign_id),$insertArray);
            //echo $this->db->last_query(); //die();
            //print_r_pre($data);
        $getCam_Dep_Date =  $this->campaign_model->getCampaignByDepartmentAndDateRobi($department_id,$broadcast_date,$priority,$old_priority);
        //echo $this->db->last_query(); //die();
            //print_r_pre($getCam_Dep_Date);
        if($data_update && $getCam_Dep_Date){

            $LAST_CAMPAIGN_ID = $campaign_id; 
			
								
				
            //print_r_pre($LAST_CAMPAIGN_ID); die();

            $base_ids= array();
					foreach($base as $kes => $value){
						$data[$kes]['CAMPAIGN_ID'] = $LAST_CAMPAIGN_ID;
						$data[$kes]['BASE_ID'] = $value;
						$BASE_COUNT = $this->global_select_query->select_get_all_data_by_where($this->tbl_base_file_robi,array('ID'=>$value));
						$data[$kes]['BASE_COUNT'] = $BASE_COUNT[0]['TOTAL_NUMBER'];
						$data[$kes]['CREATED_BY'] = $session_user_name;						
						array_push($base_ids,intval($value));
						
					}
					$total_base= $this->campaign_model->getUniqueCountFromMultipleBaseRobi($base_ids);
					
					$TOTAL_BASE_COUNT=$total_base[0]['UNIQUE_COUNT'];
            $data_delete = $this->global_delete_query->delete($this->tbl_campaing_base_robi,array('CAMPAIGN_ID'=>$campaign_id));
            //echo $data_delete; die();
            if($data_delete){
                    $data_insert_1 = $this->global_insert_query->insert_batch($this->tbl_campaing_base_robi,$data);


                    if($data_insert_1){
                            $data_update_2 = $this->global_update_query->update($this->tbl_campaing_robi,array('ID'=>$campaign_id),array('BASE_COUNT'=>$TOTAL_BASE_COUNT));

                             if($data_update_2){
                                    $this->session->set_flashdata('message_success', 'Campaign update successful');
                                    redirect('dnd_controllers/campaign/campaing_list_robi');
                            }else{
                                    $this->session->set_flashdata('message_error', 'Campaign update failed');
                                    redirect('dnd_controllers/campaign/campaing_list_robi');
                            }
                    }
            }
		
        }

        $this->load->view('layouts/default', $page_info);
    }

    public function obd_campaign() {
        $page_info['title']             = 'Create Campaign' . $this->site_name;
        //$page_info['view_page']       = 'campaign/obd_campaign_create_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
        $cam_per = $this->campaign_model->getCampaignCreatePermission("CAMPAIGN_CREATION_START","CAMPAIGN_CREATION_ENDS");
        if($cam_per){
            $page_info['view_page']      = 'campaign/obd_campaign_create_view';
            //echo "create";
        }else{
            $page_info['view_page']      = 'campaign/campaign_expired_view';
            $page_info['message']        = ' CAMPAIGN CREATE TIME EXPIRED';
            //echo "error";
        }
        //echo "Test---".$cam_per; die();
        $page_info['user_department'] = $this->session->userdata('user_department') ;
        $page_info['user_role_id']    = $this->session->userdata('user_role_id') ;
		$page_info['user_login_type']    = $this->session->userdata('user_login_type') ; 
        $page_info['department_info'] = $this->campaign_model->select_join_campaign_new();
        //in this department $data hold id
        $page_info['masking_info']    = $this->campaign_model->getMasking('airtel');
        $page_info['base_info']       = $this->campaign_model->getAllObdBase();
        //var_dump($page_info['base_info']);
        //die();
        

        $this->load->view('layouts/default', $page_info);
    }

   
} // ENDS OF CLASS

?>