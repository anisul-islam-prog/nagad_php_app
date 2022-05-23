<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_dnd extends MY_Controller {

    var $current_page = "Application_management";

    //var $tbl_user_department = "USERS_DEPARTMENT";

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

        $this->load->model('dnd_model/dnd_obd_model');
    }

    public function index($offset = 0) {
        $page_info['title']           = 'Manage DND and ODB' . $this->site_name;
        $page_info['view_page']       = 'dnd_department_view/dnd_view';
        $page_info['message_error']   = '';
        $page_info['message_success'] = '';
        $page_info['message_info']    = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }



        $this->load->view('layouts/default', $page_info);
    }

    public function do_action() {

        $session_user_name = $this->session->userdata('user_name');
        $inputfilename = $_FILES['upload_file']['tmp_name'];
        //$this->load->helper('file');
        //$string = read_file($inputfilename);
        $btn_includ = $this->input->post('add_dnd');
        $btn_exlude = $this->input->post('delete_dnd');
		
        
        if ($btn_includ) {
			
			 $uploadpath =  '/var/www/html/dndBase/dnd_base/'; 
        //$config['upload_path'] = './uploads/sms_base/';
        $config['upload_path'] = $uploadpath;
        $config['allowed_types'] = 'pdf|doc|docx|rtf|text|txt';
        //$config['max_size'] = '4096';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
		
		$department_id       = $this->input->post('deparment_id');
		
	   
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
		
		


       
		
		if(!$upload){
			//var_dump($_FILES['upload_file']['error']);die; 
			$this->session->set_flashdata('message_error', 'Upload Failed !!');
                redirect(base_url('dnd_controllers/manage_dnd'));
		}
		
		
		 if ($this->dnd_obd_model->add_dnd_number($filename)) {
				
                $this->session->set_flashdata('message_success', 'File is being processed.');
                redirect(base_url('dnd_controllers/manage_dnd'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
                redirect(base_url('dnd_controllers/manage_dnd'));
            }
		
			
			
           
        } elseif ($btn_exlude) {
            $file_data = file($inputfilename, FILE_IGNORE_NEW_LINES); 
             foreach ($file_data as $key => $value) {
				 $num_length = strlen((string)$value);
				
				if($num_length==10)
                $msisdn[$key] = '880'.$value;
				else if($num_length==13)
				$msisdn[$key] = $value;              
              } 
            if ($this->dnd_obd_model->remove_dnd_number($msisdn)) {
                $this->session->set_flashdata('message_success', 'Successfully Removed');
				 redirect(base_url('dnd_controllers/manage_dnd'));
            } else {
                $this->session->set_flashdata('message_error', 'Failed !!');
				 redirect(base_url('dnd_controllers/manage_dnd'));
            }
        }

       
    }

// do_action

    public function download_dnd($source) {
        
        $data = $this->dnd_obd_model->downloaa_dnd($source);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=DND-".$source.'-'.date('Y-m-d').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        
        foreach ($data as $value) {
            $rowD = $value;
            fputcsv($file, $rowD);
        }
        exit();
    }

    public function edit() {
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        $session_user_name = $this->session->userdata('user_name');
        //var_dump($session_user_id); die();
        $department_name = $this->input->post('dname');
        $is_active = $this->input->post('active');
        $id = $this->input->post('did');

        if (empty($department_name)) {
            $this->session->set_flashdata('message_error', 'Some fields are empty!');
            redirect('administrator/department');
        }
        $checkArray = array('ID !=' => $id, 'DEPARTMENT_NAME' => $department_name);
        $chk_data = $this->global_select_query->is_data_exists($this->tbl_user_department, $checkArray);
        if ($chk_data) {
            $this->session->set_flashdata('message_error', 'Department already exists!');
            redirect('dnd_controllers/department');
        }
        $insertArray = array('DEPARTMENT_NAME' => $department_name, 'IS_ACTIVE' => $is_active, 'UPDATED_BY' => $session_user_name);
        $data_insert = $this->global_update_query->update($this->tbl_user_department, array('ID' => $id), $insertArray);

        if ($data_insert) {
            $this->session->set_flashdata('message_success', 'Department save successful');
            redirect('dnd_controllers/department');
        }
    }

}

?>