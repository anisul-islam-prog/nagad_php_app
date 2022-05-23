<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_cutout_times extends MY_Controller {

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

        $this->load->model('dnd_model/dnd_cutout_time_model');
    }

    public function index($offset = 0) {
        $page_info['title']             = 'Manage Cutout Times' . $this->site_name;
        $page_info['view_page']         = 'dnd_department_view/dnd_cutout_time_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';

        if ($this->session->flashdata('message_error')) {
            $page_info['message_error'] = $this->session->flashdata('message_error');
        }
        if ($this->session->flashdata('message_success')) {
            $page_info['message_success'] = $this->session->flashdata('message_success');
        }
        
        $page_info['cutout_times'] = $this->dnd_cutout_time_model->get_cutout_times();

        $this->load->view('layouts/default', $page_info);
    }

    public function update_time() {

        $update_category = $this->input->post('update_category');
		$update_category2 = $this->input->post('update_category_2');
		
        
        //var_dump($update_category);
        //die($update_category);
        
        if($update_category=='campaign_creation'){
            $update_data['CAMPAIGN_CREATION_START'] = date("H:i", strtotime($this->input->post('campaign_creation_start')));
            $update_data['CAMPAIGN_CREATION_ENDS']  = date("H:i", strtotime($this->input->post('campaign_creation_ends')));
        }
        elseif($update_category=='campaign_edit'){
            $update_data['CAMPAIGN_CANCELLATION_START'] = date("H:i", strtotime('00:01 AM'));
            $update_data['CAMPAIGN_CANCELLATION_ENDS']  = date("H:i", strtotime($this->input->post('campaign_cancellation_ends')));
        }
        elseif($update_category=='weekday_campaign_broadcast'){
            $update_data['BRDCST_WEEKDAY_START'] = date("H:i", strtotime($this->input->post('weekday_broadcast_start')));
            $update_data['BRDCST_WEEKDAY_ENDS']  = date("H:i", strtotime($this->input->post('weekday_broadcast_ends')));
        }
        elseif($update_category=='weekend_campaign_broadcast'){
            $update_data['BRDCST_WEEKEND_START'] = date("H:i", strtotime($this->input->post('weekend_broadcast_start')));
            $update_data['BRDCST_WEEKEND_ENDS']  = date("H:i", strtotime($this->input->post('weekend_broadcast_ends')));
        }
		
		
		
        
        
        
        if($this->dnd_cutout_time_model->update_cutout($update_data)){
			
            $this->session->set_flashdata('message_success', 'Successfully Updated');
        }
        else{
            $this->session->set_flashdata('message_error', 'Failed !!');
        }
        redirect(base_url('dnd_controllers/manage_cutout_times'));
    }
	
	public function update_break_time() {

        
		
        
            $update_data['JUMAPRAYER_START'] = date("H:i", strtotime($this->input->post('break_time_start')));
            $update_data['JUMAPRAYER_END']  = date("H:i", strtotime($this->input->post('break_time_ends')));
                
		
        
        
        
        if($this->dnd_cutout_time_model->update_cutout($update_data)){
			
            $this->session->set_flashdata('message_success', 'Successfully Updated');
        }
        else{
            $this->session->set_flashdata('message_error', 'Failed !!');
        }
        redirect(base_url('dnd_controllers/manage_cutout_times'));
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