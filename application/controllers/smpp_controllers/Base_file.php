<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base_file extends MY_Controller {

    var $current_page = "Campaign_management";

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
		
		// Increasing default download file size and Execution time 
        ini_set('memory_limit', '999999999999M');
        ini_set('max_execution_time', 5000);
		ini_set('post_max_size', '999999999999M');
		$this->load->model('dnd_model/campaign_model');
        $this->load->model('dnd_model/base_model');
    }

    public function index($offset = 0) {
        $page_info['title'] = 'Upload SMS Base File' . $this->site_name;
        $page_info['view_page'] = 'base_file/upload_base_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
		$page_info['department_info'] = $this->campaign_model->select_join_campaign_new();
		//var_dump($this->campaign_model->select_join_campaign_new());die;
        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');

        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
        $this->load->view('layouts/default', $page_info);
    }

    public function obd_base_file($offset = 0) {
        $page_info['title'] = 'Upload OBD Base File' . $this->site_name;
        $page_info['view_page'] = 'base_file/upload_obd_base_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');

        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');

        }
        $this->load->view('layouts/default', $page_info);
    }
	
	public function employee_base_file($offset = 0) {
        $page_info['title'] = 'Upload Employee Base File' . $this->site_name;
        $page_info['view_page'] = 'base_file/upload_employee_base_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');

        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');

        }
        $this->load->view('layouts/default', $page_info);
    }
	
	


    public function do_upload_file() {
        $uploadpath =  '/var/www/html/dndBase/sms_base/'; 
        //$config['upload_path'] = './uploads/sms_base/';
        $config['upload_path'] = $uploadpath;
        $config['allowed_types'] = 'pdf|doc|docx|rtf|text|txt';
        //$config['max_size'] = '4096';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
		
		$department_id    = $this->input->post('deparment_id');
        $brand_name       = $this->input->post('brand_name');
		
	   
	   $ff = explode('.',$_FILES["upload_file"]['name']);
	   $ff[0] = preg_replace('/\s+/', '_', $ff[0]); 
	   $f_name = $ff[0].'_'.str_replace(':','-',date('d-m-Y_H:i:s'));
	   
	   $new_name = $f_name.'.'.$ff[1]; 
	   
       // $new_name = str_replace(':','-',date('d-m-Y_H:i:s')).'_'.$_FILES["upload_file"]['name']; 
        $config['file_name'] = $new_name;
		
        //$config['encrypt_name'] = true;

        $CI =& get_instance();
        $CI->load->library('upload', $config);
		//var_dump($uploadpath);die;
        $upload=$CI->upload->do_upload("upload_file");
		//echo $uploadpath;
		chmod($uploadpath.$new_name,0775);
		

		
        $session_user_name  = $this->session->userdata('user_name');
        $inputfilename      = $_FILES['upload_file']['tmp_name'];
        $filename           = $new_name;
		
		


        $btn_upload         = $this->input->post('upload_base');
		
		if(!$upload){
			//var_dump($_FILES['upload_file']['error']);die;
			$this->session->set_flashdata('message_error', 'Upload Failed !!');
                redirect(base_url('dnd_controllers/base_file'));
		}
        
		
        if ($btn_upload) {
			//var_dump(file($inputfilename, FILE_IGNORE_NEW_LINES)); exit;
			
            
			//print_r_pre($msisdn); die();
            if ($this->base_model->upload_base_number($filename,$department_id,$brand_name)) {
				
                $this->session->set_flashdata('message_success', 'File is being processed.');
                redirect(base_url('dnd_controllers/base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
                redirect(base_url('dnd_controllers/base_file'));
            }
        } 




    }
	
	public function do_robi_employee_upload_file() {
        
   
        $session_user_name = $this->session->userdata('user_name');
        $inputfilename = $_FILES['upload_employee_file_robi']['tmp_name'];
        //$this->load->helper('file');
        //$string = read_file($inputfilename);
        $btn_includ = $this->input->post('add_robi_emp');
        $btn_exlude = $this->input->post('delete_robi_emp');
		
        
        if ($btn_includ) {
			
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
			
            foreach ($file_data as $key => $value) {
				//$msisdn[$key]['CHANNEL'] = 'DND_APPLICATION';
				//$msisdn[$key]['CREATED_BY'] = $session_user_name;
				$num_length = strlen((string)$value);
				
				if($num_length==10)
                $msisdn[$key]['MSISDN'] = '880'.$value;
			else if($num_length==13)
				$msisdn[$key]['MSISDN'] = $value;
               
               
               
				
				
					
			} 
			
            if ($this->base_model->add_robi_employee_base_number($msisdn)) {
                $this->session->set_flashdata('message_success', 'Successfully Added');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            }
        } elseif ($btn_exlude) {
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
           foreach ($file_data as $key => $value) {
				//$msisdn[$key]['CHANNEL'] = 'DND_APPLICATION';
				//$msisdn[$key]['CREATED_BY'] = $session_user_name;
                $num_length = strlen((string)$value);
				
				if($num_length==10)
                $msisdn[$key]['MSISDN'] = '880'.$value;
			else if($num_length==13)
				$msisdn[$key]['MSISDN'] = $value;
				
				
					
			} 
            if ($this->base_model->delete_robi_employee_base_number($msisdn)) {
				//echo $this->db->last_query();exit;
                $this->session->set_flashdata('message_success', 'Successfully Removed');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            }
        }

      



    }
	
	
		public function do_airtel_employee_upload_file() {
        
   
        $session_user_name = $this->session->userdata('user_name');
        $inputfilename = $_FILES['upload_employee_file_airtel']['tmp_name'];
        //$this->load->helper('file');
        //$string = read_file($inputfilename);
        $btn_includ = $this->input->post('add_airtel_emp');
        $btn_exlude = $this->input->post('delete_airtel_emp');
		
        
        if ($btn_includ) {
			
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
			
            foreach ($file_data as $key => $value) {
				//$msisdn[$key]['CHANNEL'] = 'DND_APPLICATION';
				//$msisdn[$key]['CREATED_BY'] = $session_user_name;
                $num_length = strlen((string)$value);
				
				if($num_length==10)
                $msisdn[$key]['MSISDN'] = '880'.$value;
			else if($num_length==13)
				$msisdn[$key]['MSISDN'] = $value;
				
				
					
			} 
			
            if ($this->base_model->add_airtel_employee_base_number($msisdn)) {
                $this->session->set_flashdata('message_success', 'Successfully Added');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            }
        } elseif ($btn_exlude) {
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
             foreach ($file_data as $key => $value) {
				//$msisdn[$key]['CHANNEL'] = 'DND_APPLICATION';
				//$msisdn[$key]['CREATED_BY'] = $session_user_name;
                $num_length = strlen((string)$value);
				
				if($num_length==10)
                $msisdn[$key]['MSISDN'] = '880'.$value;
			else if($num_length==13)
				$msisdn[$key]['MSISDN'] = $value;
				
				
					
			} 
            if ($this->base_model->delete_airtel_employee_base_number($msisdn)) {
				//echo $this->db->last_query();exit;
                $this->session->set_flashdata('message_success', 'Successfully Removed');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
				redirect(base_url('dnd_controllers/base_file/employee_base_file'));
            }
        }

        
    }
	

    public function do_upload_obd_file() {
		$uploadpath = '/var/www/html/dndBase/obd_base/';
        //$config['upload_path'] = './uploads/sms_base/';
        $config['upload_path'] = $uploadpath;
        $config['allowed_types'] = 'pdf|doc|docx|rtf|text|txt';
       
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';

       // echo str_replace(':','-',date('d-m-Y_H:i:s')); exit;
	   
	  // echo $_FILES["upload_file"]['name']; exit;
	   
	   $ff = explode('.',$_FILES["upload_obd_file"]['name']);
	   $ff[0] = preg_replace('/\s+/', '_', $ff[0]); 
	   $f_name = $ff[0].'_'.str_replace(':','-',date('d-m-Y_H:i:s'));
	   
	   $new_name = $f_name.'.'.$ff[1];
	   
       // $new_name = str_replace(':','-',date('d-m-Y_H:i:s')).'_'.$_FILES["upload_file"]['name'];
        $config['file_name'] = $new_name;
		
        //$config['encrypt_name'] = true;

        $CI =& get_instance();
        $CI->load->library('upload', $config);

        $upload=$CI->upload->do_upload("upload_obd_file");
		
		/*
        $uploadpath = $_SERVER['DOCUMENT_ROOT'].'/dndBase/obd_base/';
        //$config['upload_path'] = './uploads/obd_base/';
        $config['upload_path'] =$uploadpath;
        $config['allowed_types'] = 'pdf|doc|docx|rtf|text|txt';
        $config['max_size'] = '4096';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
        //$config['encrypt_name'] = true;

      
		$ff = explode('.',$_FILES["upload_obd_file"]['name']);
	   
	    $f_name = $ff[0].'_'.str_replace(':','-',date('d-m-Y_H:i:s'));
	   
	   $new_name = $f_name.'.'.$ff[1];
	   


        $CI =& get_instance();
        $CI->load->library('upload', $config);

        $CI->upload->do_upload("upload_obd_file");
		
		//var_dump(pathinfo($uploadpath.$new_name)); exit;
		
		*/
		
		chmod($uploadpath.$new_name,0775);
		
		if(!$upload){
			$this->session->set_flashdata('message_error', 'Upload Failed !!');
                redirect(base_url('dnd_controllers/base_file/obd_base_file'));
		}
        
		
		//exit;

        $session_user_name  = $this->session->userdata('user_name');
        $inputfilename      = $_FILES['upload_obd_file']['tmp_name'];
        $filename           = $new_name;
        //var_dump($_FILES['upload_file']); die();
        //$this->load->helper('file');
        //$string = read_file($inputfilename);
        $btn_upload         = $this->input->post('upload_base');

        if ($btn_upload) {
            //var_dump(file($inputfilename, FILE_IGNORE_NEW_LINES)); exit;
			/*
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES);
            foreach ($file_data as $key => $value) {
                $msisdn[$key]['MSISDN'] = $value;
            
			*/
            //print_r_pre($msisdn); die();
            if ($this->base_model->upload_obd_base_number($filename)) {
                $this->session->set_flashdata('message_success', 'Successfully Added');
                redirect(base_url('dnd_controllers/base_file/obd_base_file'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
                redirect(base_url('dnd_controllers/base_file/obd_base_file'));
            }
        }




    }

// do_action

   
} // ENDS OF CLASS

?>