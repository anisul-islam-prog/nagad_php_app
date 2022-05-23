<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_management extends MY_Controller
{
	
	var $current_page 			= "user_management";
    var $tbl_dnd_user           = "DND_APP_USERS";
	var $tbl_dnd_department 	= "DND_USERS_DEPARTMENT";
    var $tbl_bucket_department  = "DND_BUCKET_DEPARTMENTS";
    var $tbl_bucket             = "DND_BUCKET";
    var $tbl_masking            = "DND_MASKING";
    var $tbl_department_msisdn  = "DND_DEPARTMENT_TEST_MSISDN";
    var $tbl_dnd_segment  		= "DND_SEGMENT";
    var $tbl_dnd_roles  		= "DND_APP_ROLES";
    var $tbl_dnd_menu  			= "DND_MENU";
    var $tbl_dnd_menu_role  	= "DND_ROLE_MENU";
	function __construct()
	{
		parent::__construct();

        if ( !$this->session->userdata('is_logged_in') ) {
            redirect('');
        } else {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type != 'admin') {
                redirect('');
            }
        }
        $this->load->library('form_validation');
        $this->load->config('auth');
        $this->load->model('dnd_model/dnd_department_model');
        $this->load->model('dnd_model/global_select_query');
        $this->load->model('dnd_model/global_insert_query');
        $this->load->model('dnd_model/global_update_query');
        $this->load->model('dnd_model/global_delete_query');
        $this->load->model('dnd_model/dnd_user_model');
        $this->load->model('user_model');
        $this->load->model('user_role_model');
        $this->load->model('log_model');
        $this->load->config("pagination");
        $this->load->library("pagination");
        $this->load->library('table');
        //$this->load->library('form_validation');
	}

	public function index()
	{
		echo "";
	}

	public function user_add_view()
	{
		$page_info['title']               = 'Manage User'. $this->site_name;
        $page_info['view_page']           = 'dnd_department_view/dnd_add_user_view';
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $page_info['roles'] = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);
        $page_info['departments'] = $this->global_select_query->is_data_exists($this->tbl_dnd_department);
		
		$brand_name='robi';
		if($this->session->userdata('user_login_type')=='admin'){
		$checkarray=array('BRAND_NAME'=>$brand_name);
		$departments = $this->global_select_query->is_data_exists($this->tbl_dnd_department,$checkarray);		
		}
		else
		{
			$user_brand_name=$this->session->userdata('user_brand_name');
			$checkarray=array('BRAND_NAME'=>$user_brand_name);
			$departments = $this->global_select_query->is_data_exists($this->tbl_dnd_department,$checkarray);			
			
			
		}
		
		$page_info['departments'] = $departments;


		$this->load->view('layouts/default', $page_info); 
	}

	public function add_user()
	{
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
        $page_info['is_edit']           = false;
        $page_info['is_edit']           = true;

        
        $user_id = (int)$this->input->post('user_id');

        	$user_login_name        = $this->input->post('user_login_name');
            $user_password          = $this->input->post('user_password');
            $user_password_confirm  = $this->input->post('user_password_confirm');
            $user_department_id     = (int)$this->input->post('user_department_id');
			$user_brand_name        = $this->input->post('brand_name');
            $user_role_id           = (int)$this->input->post('user_role_id');
			
			if($user_role_id==1)
				$user_login_type    = "admin";
            $user_name              = $this->input->post('user_name');
            $user_email             = $this->input->post('user_email');
            $user_msisdn            = $this->input->post('user_msisdn');           
            $is_group_manager       = (int)$this->input->post('is_group_manager');
            $user_is_lock           = (int)$this->input->post('user_is_lock');
            $user_is_active         = (int)$this->input->post('user_is_active');
			$mail_noti         = (int)$this->input->post('mail_noti');

            if (empty($user_login_name) && empty($user_password) && empty($user_password_confirm) && empty($user_department_id) && empty($user_role_id) && empty($user_name) && empty($user_msisdn)){
                $this->session->set_flashdata('message_error', 'Some fields are Empty.');
                redirect('dnd_controllers/user_management/user_add_view');
            }
            if($this->global_select_query->is_data_exists($this->tbl_dnd_user, array('USER_NAME'=>$user_login_name, 'USER_EMAIL'=>$user_email, 'USER_MSISDN' =>$user_msisdn))){
                $this->session->set_flashdata('message_error','User Already Exists.');
                redirect('dnd_controllers/user_management/user_add_view');
            }
            if($user_password != $user_password_confirm){

                $this->session->set_flashdata('message_error', 'Password Mismatch');
                redirect('dnd_controllers/user_management/user_add_view');
             
            //$this->global_insert_query->insert($this->tbl_dnd_user, $data);
            //print_r_pre($data); die();
            }else{
                if($this->global_insert_query->insert($this->tbl_dnd_user,array(
                    'USER_LOGIN_NAME'   => $user_login_name,
                    'USER_PASSWORD'     => md5($user_password),
                    //'USER_PASSWORD_CONFIRM' => md5($user_password_confirm),
                    'DEPARTMENT_ID'     => $user_department_id,
                    'ROLE_ID'           => $user_role_id,					
                    'USER_NAME'         => $user_name,
					'BRAND_NAME'        => $user_brand_name,
                    'USER_EMAIL'        => $user_email,
                    'USER_MSISDN'       => $user_msisdn,
                    'IS_GROUP_MANAGER'  => $is_group_manager,                
                    'USER_IS_LOCK'      => $user_is_lock,
                    'USER_IS_ACTIVE'    => $user_is_active,
					'USER_LOGIN_TYPE'   => $user_login_type,
					'MAIL_CHECK'        => $mail_noti
					))){
                $this->session->set_flashdata('message_success', 'User added successfully.');
                redirect('dnd_controllers/user_management/user_lists');
                }
            }
        //}
	}
	
	
	public function getDeptByBrandName()
	{
		$brand_name = $this->input->get_post('brand_name');
		$checkarray=array('BRAND_NAME'=>$brand_name);
		$departments = $this->global_select_query->is_data_exists($this->tbl_dnd_department,$checkarray);
				
		echo json_encode(array('departments' => $departments));	 
		
	}
	
		public function getDeptByBrandNameInBase()
	{
		$brand_name = $this->input->get_post('brand_name');
		$checkarray=array('BRAND_NAME'=>$brand_name);
		$departments = $this->global_select_query->is_data_exists($this->tbl_dnd_department,$checkarray);
				
		echo json_encode(array('departments' => $departments));	 
		
	}


    public function user_lists()
    {
        $page_info['title']               = 'Manage User'. $this->site_name;
        $page_info['view_page']           = 'dnd_department_view/dnd_user_list_view';
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        //$page_info['users'] = $this->global_select_query->is_data_exists($this->tbl_dnd_user);
        //$page_info['roles'] = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);
        //$page_info['departments'] = $this->global_select_query->is_data_exists($this->tbl_dnd_department_department);
		$user_list = array();
		$user_list = $this->dnd_user_model->get_users_details();
		$page_info['get_UD']=array();
		
        $page_info['get_UD'] = $user_list;
		//var_dump($this->db->last_query());die;
        $this->load->view('layouts/default', $page_info); 
    }

    public function update_user_view()
    {
        $page_info['title']               = 'Manage User'. $this->site_name;
        $page_info['view_page']           = 'dnd_department_view/update_user_view';
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $data = $this->uri->segment(4);

        $userdetails= $this->global_select_query->select_join_user_new($data);
        $page_info['usersDetails']   =  $userdetails;
		

       
       $page_info['get_role']        = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);
	   $checkarray=array('BRAND_NAME'=>$userdetails[0]['BRAND_NAME']);
       $page_info['get_department']  = $this->global_select_query->is_data_exists($this->tbl_dnd_department, $checkarray);
         //print_r_pre($page_info['usersDetails']); die();
        //$page_info['role_all']        = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);


        //var_dump($page_info['get_user_by_role']);

       
        
        //var_dump($page_info['usersDetails']); die();

        $this->load->view('layouts/default', $page_info);

    }

    public function update_users()
    {
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        $user_id                = $this->input->post('user_id');
        $user_login_name        = $this->input->post('user_login_name');
        $user_password          = $this->input->post('user_password');
        $user_password_confirm  = $this->input->post('user_password_confirm');
        $user_department_id     = (int)$this->input->post('user_department_id');
        $user_role_id           = (int)$this->input->post('user_role_id');
        $user_name              = $this->input->post('user_name');
        $user_email             = $this->input->post('user_email');
        $user_msisdn            = $this->input->post('user_msisdn');  
        $is_group_manager       = $this->input->post('is_group_manager'); 
		$mail_check       		= $this->input->post('mail_check');
		$is_user_lock       	= $this->input->post('user_is_lock');
		$is_user_active       	= $this->input->post('user_is_active'); 		

      


        if($this->global_update_query->update($this->tbl_dnd_user,array('ID'=>$user_id),array(
            'USER_LOGIN_NAME'   =>$user_login_name,
            'USER_PASSWORD'     =>md5($user_password),
            'DEPARTMENT_ID'     =>$user_department_id,
            'ROLE_ID'           =>$user_role_id,
            'USER_NAME'         =>$user_name,
            'USER_EMAIL'        =>$user_email,
            'USER_MSISDN'       =>$user_msisdn,
            'IS_GROUP_MANAGER'  =>$is_group_manager,
			'MAIL_CHECK'  		=>$mail_check,
			'USER_IS_LOCK'  	=>$is_user_lock,
			'USER_IS_ACTIVE'  	=>$is_user_active
			
			))){
            $this->session->set_flashdata('message_success', 'User Update successfully.');
            redirect('dnd_controllers/user_management/user_lists');

            //var_dump($bd); die();
        }
        else{
            $this->session->set_flashdata('message_error', 'User Update Failed.');
            redirect('dnd_controllers/user_management/update_user_view/'.$user_id);
        }
    }

	public function role()
	{
		$page_info['title']               = 'Manage Role'. $this->site_name;
        $page_info['view_page']           = 'dnd_department_view/add_role_view';
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $page_info['roles'] = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);
		$this->load->view('layouts/default', $page_info);
	}

	public function add_role()
	{
		$page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        $role_name 	= $this->input->post('role_name');
        $discription 	= $this->input->post('discription');
        if(empty($role_name)){
        	$this->session->set_flashdata('message_error', 'Some fields are empty');
        	redirect('dnd_controllers/user_management/role');
        }
       if($this->global_select_query->is_data_exists($this->tbl_dnd_roles,array('ROLE_NAME'=>$role_name))){
       		$this->session->set_flashdata('message_error', 'Role is already exists');
        	redirect('dnd_controllers/user_management/role');
       }

       if($this->global_insert_query->insert($this->tbl_dnd_roles,array('ROLE_NAME'=>$role_name,'ROLE_DESCRIPTION'=>$discription))){
       		$this->session->set_flashdata('message_success', 'Role Saved Successful');
        	redirect('dnd_controllers/user_management/role');
       }else{
       		$this->session->set_flashdata('message_error', 'Role Save failed');
        	redirect('dnd_controllers/user_management/role');
       }
	}

	public function update_role_view()
	{
		$page_info['title']               = 'Manage Role'. $this->site_name;
        $page_info['view_page']           = 'dnd_department_view/update_roles_view';
        $page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        $uisegment = $this->uri->segment(4);

        $page_info['rolesDetails'] = $this->global_select_query->is_data_exists($this->tbl_dnd_roles,array('ID'=>$uisegment));
        $page_info['rolesall'] = $this->global_select_query->is_data_exists($this->tbl_dnd_roles);
        $page_info['menuData'] = $this->dnd_user_model->select_user_role_by_menu(array('TA.ROLEID'=>$uisegment));
        $page_info['allmenu'] = $this->global_select_query->is_data_exists($this->tbl_dnd_menu);


		$this->load->view('layouts/default', $page_info);
	}

	public function update_role()
	{
		$page_info['message_error']       = '';
        $page_info['message_success']     = '';
        $page_info['message_info']        = '';

        
        $role_id 	= $this->input->post('role_id');
        $role_name 	= $this->input->post('role_name');
        $discription 	= $this->input->post('discription');
        $menu 	= $this->input->post('menu');

        if($this->global_select_query->is_data_exists($this->tbl_dnd_roles,array('ID !='=>$role_id,'ROLE_NAME'=>$role_name))){
        	$this->session->set_flashdata('message_error', 'Role is already exists');
        	redirect('dnd_controllers/user_management/update_role_view/'.$role_id);
        }

        if(!$this->global_update_query->update($this->tbl_dnd_roles,array('ID'=>$role_id),array('ROLE_NAME'=>$role_name,'ROLE_DESCRIPTION'=>$discription))){
        	$this->session->set_flashdata('message_error', 'Role Update Failed!');
        	redirect('dnd_controllers/user_management/update_role_view/'.$role_id);
        }

        foreach ($menu as $key => $value) {
        	$data[$key]['ROLEID'] = $role_id;
        	$data[$key]['MENUID'] = $value;
        }

        if($this->global_select_query->is_data_exists($this->tbl_dnd_menu_role,array('ROLEID'=>$role_id))){
        	 $this->global_delete_query->delete($this->tbl_dnd_menu_role,array('ROLEID'=>$role_id));
        }

        if($this->global_insert_query->insert_batch($this->tbl_dnd_menu_role,$data)){
			$role_menu = $this->user_model->get_users_role_by_menu(array('ROLEID'=>$role_id));
			$privilage_name = array();
                        foreach($role_menu as $k=>$v){
                            $privilage_name[] = $v['MENUNAME'];
                        }
						$this->session->set_userdata('user_menu', $privilage_name);
        	$this->session->set_flashdata('message_success', 'Role Update Successful!');
        	redirect('dnd_controllers/user_management/update_role_view/'.$role_id);
        }

        //print_r_pre($data); die();

	}

	// set empty default form field values
	private function _set_fields()
	{
        $this->form_data->user_id               = 0;
        $this->form_data->user_login_name       = '';
        $this->form_data->user_password         = '';
        $this->form_data->user_password_confirm = '';
        $this->form_data->user_role_id          = 0;
        $this->form_data->user_department_id    = 0;
        $this->form_data->user_name             = '';
        $this->form_data->user_email            = '';
        $this->form_data->user_msisdn           = '18';
        $this->form_data->user_region_ids       = array();
        $this->form_data->user_area_ids         = array();
        $this->form_data->user_rsp_ids          = array();
        $this->form_data->user_is_lock          = 0;
        $this->form_data->user_is_active        = 1;
        $this->form_data->filter_user           = '';
        $this->form_data->filter_role           = '';
        $this->form_data->filter_locked         = '';
        $this->form_data->filter_active         = '';
	}

	// validation rules
	private function _set_rules()
	{
		$this->form_validation->set_rules('user_login_name', 'Login Name', 'required');
		$this->form_validation->set_rules('user_password', 'Password', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_password_confirm', 'Confirm Password', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_role_id', 'User Role', 'callback_check_default');
        $this->form_validation->set_rules('user_department_id', 'User Department', 'callback_check_default');
		$this->form_validation->set_rules('user_name', 'User Name', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_email', 'Email Address', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_msisdn', 'Mobile Number', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_is_lock', 'Is Locked?', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_is_active', 'Is Active?', 'trim|xss_clean|strip_tags');
	} // end of _set_rules

    function select_validate()
    {
        $choice = $this->input->post("user_role_id");
        if(is_null($choice)){
            $choice = array();
        }

        $user_role_id = implode(',', $choice);

        if($user_role_id !=''){
            return true;
        }
        else{
            $this->form_validation->set_message('select_validate', 'You need to select a least one element');
            return false;
        }
    }
        
        private function _set_rules_edit()
	{
		$this->form_validation->set_rules('user_id', 'User ID', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('Contact', 'Contact', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('MailId', 'Mail Address', 'required|trim|xss_clean|strip_tags');
		
	} // end of _set_rules_edit
        
        private function _set_rules_change_password(){

            $this->form_validation->set_rules('old_password', 'Old Password', 'required|trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('re_password', 'Re-Type Password', 'required|trim|xss_clean|strip_tags');
        } // End of _set_rules_change_password

        private function delete($user_id = 0)
        {
        $user_id = (int)$user_id;
        $res = $this->user_model->delete_user($user_id);

        if ($res > 0) {
            $this->session->set_flashdata('message_success', 'Delete is successful.');
            /* Activity Log */ $this->log_model->insert_log(2,'Delete User. User ID- '.$user_id); 
        } else {
            $this->session->set_flashdata('message_error', 'Delete is unsuccessful.');
        }

        redirect('dnd_controllers/user_management');
    }

    public function edit_profile()
    {
        // set page specific variables
        $page_info['title']             = 'Update Profile'. $this->site_name;
        $page_info['view_page']         = 'administrator/user_update_profile_form_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
        
        
        $user_id = $this->session->userdata['Userid'];
        $page_info['user_info'] = $this->user_model->get_user($user_id);
            
        $this->load->view('layouts/default', $page_info);
        //    redirect('administrator/user/edit/'. $user_id);
       
    }

    public function do_edit_profile()
    {
        // set page specific variables
        $page_info['title']             = 'Update Profile'. $this->site_name;
        $page_info['view_page']         = 'administrator/user_update_profile_form_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';
        
        
        $this->_set_rules_edit();

        if ($this->form_validation->run() == FALSE) {
            $user_id = $this->session->userdata['Userid'];
            $page_info['user_info'] = $this->user_model->get_user($user_id);
            $this->load->view('layouts/default', $page_info);
        } 
        else {
        
            $user_id = $this->input->post('user_id');
            $name = $this->input->post('name');
            $contact = $this->input->post('Contact');
            $email = $this->input->post('MailId');


            $data = array(
                'USER_NAME'  =>$name,
                'USER_MSISDN'=>$contact,
                'USER_EMAIL' =>$email
            );

            $update = $this->user_model->update_profile($user_id,$data);

            if($update==TRUE){
                $this->session->set_userdata('user_name', $name);
                 $page_info['message_success'] = 'Update Successful';
                 /* Activity Log */ $this->log_model->insert_log(2,'User Edit Profile. User ID- '.$name);
            }
            else{
                $page_info['message_error'] = 'Update Not Successful';
            }
            
            $page_info['user_info'] = $this->user_model->get_user($user_id);
            $this->load->view('layouts/default', $page_info);
        
        }
       

        //    redirect('administrator/user/edit/'. $user_id);
       
    } // End of edit_profile

    public function change_password(){        
         // set page specific variables
        $page_info['title']             = 'Change Password'. $this->site_name;
        $page_info['view_page']         = 'administrator/change_password_view';
        $page_info['message_error']     = '';
        $page_info['message_success']   = '';
        $page_info['message_info']      = '';


        $this->load->view('layouts/default', $page_info);
        //    redirect('administrator/user/edit/'. $user_id);
    } // End of change_password

    public function do_chage_password(){
            // set page specific variables
            $page_info['title']             = 'Change Password'. $this->site_name;
            $page_info['view_page']         = 'administrator/change_password_view';
            $page_info['message_error']     = '';
            $page_info['message_success']   = '';
            $page_info['message_info']      = '';

            //var_dump($this->input->post('old_password'));
            
            $this->_set_rules_change_password();

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/default', $page_info);
            } 
            else {
                 $user_id = $this->session->userdata['Userid'];
                 $old_password = $this->input->post('old_password');
                 $new_password = $this->input->post('new_password');
                 $re_password = $this->input->post('re_password');
                 
                
                 // Checking Old Password
                 $userArray = $this->user_model->get_user($user_id);
                // var_dump($userArray);                 die();
                 $db_password = $userArray['USER_PASSWORD'];
                 $db_password2 = $userArray['USER_PASSWORD_OLD'];
                 if(MD5($old_password) != $db_password){
                     
                      $page_info['message_error'] = 'Old Password Did Not Matched !!';
                      $this->load->view('layouts/default', $page_info);
                      //die('old not matched');
                 }
                 else{ // When Old Password Matched
                     
                     //Checking New Password With Old Password
                    if((MD5($new_password) == $db_password) || (MD5($new_password) == $db_password2) ){
                     
                      $page_info['message_error'] = 'New Password Can not be similar with last two Old Passwords !!';
                      $this->load->view('layouts/default', $page_info);
                      //die('old not matched');
                    }
                    else{
                        
                        //Validating New Password 
                      $validate = $this->validating_password_format($new_password);
                      
                      if($validate != 1){
                          //var_dump($validate); die();
                           $page_info['message_error'] = $validate;
                           $this->load->view('layouts/default', $page_info);
                      }
                      else{
                          $data = array(
                         'USER_PASSWORD'            =>MD5($new_password),
                         'USER_PASSWORD_OLD'        =>$db_password,
                         'USER_IS_DEFAULT_PASSWORD' =>1 ,
                         'PASSWORD_CHANGE_DATE'     => date('d-M-Y')     
                        );

                        $change =  $this->user_model->update_profile($user_id , $data);
                        if($change== TRUE){
                            $page_info['message_success'] = 'Password Successfully Changed';
                            $page_info['view_page'] = 'administrator/change_password_view';
                            /* Activity Log */ $this->log_model->insert_log(2,'User change Password. User ID- '.$user_id);
                            $this->load->view('layouts/default', $page_info);
                        }
                        else{
                            $page_info['message_error'] = 'Password Change Failed !!';
                            $this->load->view('layouts/default', $page_info);
                        }
                        }
                     } 
                }
            }
           
            //$this->load->view('layouts/default', $page_info);
            
        } // End of do_chage_password

        public function change_password_first(){
             // set page specific variables
            $page_info['title']             = 'Change Password'. $this->site_name;
            $page_info['view_page']         = 'administrator/change_password_view_first';
            $page_info['message_error']     = '';
            $page_info['message_success']   = '';
            $page_info['message_info']      = '';

            /*$page_info['title']               = 'Manage Department'. $this->site_name;
            $page_info['view_page']           = 'dnd_department_view/update_roles_view';
            $page_info['message_error']       = '';
            $page_info['message_success']     = '';
            $page_info['message_info']        = '';*/


            $this->load->view('layouts/default_2', $page_info);
            //    redirect('administrator/user/edit/'. $user_id);
        } // End of change_password


        private function set_rules_change_password(){
            
            $this->form_validation->set_rules('old_password', 'Old Password', 'required|trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('re_password', 'Re-Type Password', 'required|trim|xss_clean|strip_tags');

        } // End of _set_rules_change_password

        public function do_chage_password_first(){
            // set page specific variables
            $this->load->library('form_validation');
            $page_info['title']             = 'Change Password'. $this->site_name;
            $page_info['view_page']         = 'administrator/change_password_view_first';
            $page_info['message_error']     = '';
            $page_info['message_success']   = '';
            $page_info['message_info']      = '';
           // die('dfd');
            $this->_set_rules_change_password();
            
            //var_dump($this->set_rules_change_password()); die();
            if ($this->form_validation->run() == FALSE) {
                //die('afd');
                $this->load->view('layouts/default_2', $page_info);
            } 
            else {
                 $user_id       = $this->session->userdata['Userid'];
                 $old_password  = $this->input->post('old_password');
                 $new_password  = $this->input->post('new_password');
                 $re_password   = $this->input->post('re_password');
                 
                
                 // Checking Old Password
                 $userArray = $this->user_model->get_user($user_id);
                //var_dump($userArray);                 die();
                 $db_password = $userArray['USER_PASSWORD'];
                 $db_password2 = $userArray['USER_PASSWORD_OLD'];
                 //print_r_pre($old_password);die();
                 if(MD5($old_password) != $db_password){
                      $page_info['message_error'] = 'Old Password Did Not Matched !!';
                      $this->load->view('layouts/default_2', $page_info);
                      die('old not matched');
                 }
                 else{ // When Old Password Matched
                     
                     //Checking New Password With Old Password
                    if((MD5($new_password) == $db_password) || (MD5($new_password) == $db_password2) ){
                     
                      $page_info['message_error'] = 'New Password Can not be similar with last two Old Passwords !!';
                      $this->load->view('layouts/default_2', $page_info);
                      //die('olds not matched');
                    }
                    else{
                        
                        //Validating New Password 
                      $validate = $this->validating_password_format($new_password);
                      
                      if($validate != 1){
                          //var_dump($validate); die();
                           $page_info['message_error'] = $validate;
                           $this->load->view('layouts/default_2', $page_info);
                      }
                      else{
                          $data = array(
                         'USER_PASSWORD'=>MD5($new_password),
                         'USER_PASSWORD_OLD'=>$db_password,
                         'USER_IS_DEFAULT_PASSWORD'=>1     
                        );

                        $change =  $this->user_model->update_profile($user_id , $data);
                        if($change== TRUE){
                            $page_info['message_success'] = 'Password Successfully Changed';
                            $page_info['view_page'] = 'administrator/change_password_view';
                            /* Activity Log */ $this->log_model->insert_log(2,'User change Default Password. User ID- '.$user_id);
                            $this->load->view('layouts/default', $page_info);
                        }
                        else{
                            $page_info['message_error'] = 'Password Change Failed !!';
                            $this->load->view('layouts/default_2', $page_info);
                        }
                        }
                     } 
                }
            }
           
            //$this->load->view('layouts/default', $page_info);
            
        } // End of do_chage_password_first

        public function validating_password_format($password){
            // validating password
            //var_dump(strtolower($this->session->userdata['user_login_name'])); die();
            if (strpos(strtolower($password), strtolower($this->session->userdata['user_login_name'])) !== false) {
                return 'Password should not Contain User Login Name.';
            }elseif ($password == '') {
                return 'Password is required.';
            
            }
            elseif (strlen($password) < 8) {
                return 'Password must have at least 8 (eight) characters.';
                
            } 
            else {

                preg_match_all ("/[A-Z]/", $password, $matches);
                $uppercase = count($matches[0]);

                preg_match_all ("/[a-z]/", $password, $matches);
                $lowercase = count($matches[0]);

                preg_match_all ("/\d/i", $password, $matches);
                $numbers = count($matches[0]);

                preg_match_all ("/[^A-z0-9]/", $password, $matches);
                $special = count($matches[0]);

                if ($uppercase <= 0) {
                    return 'Password should contain at least one Uppercase letter.';
                   
                } elseif ($lowercase <= 0) {
                   return 'Password should contain at least one Lowercase letter.';
                    
                } elseif ($numbers <= 0) {
                    return $this->error_message = 'Password should contain at least one Numeric character.';
                   
                } elseif ($special <= 0) {
                    return $this->error_message = 'Password should contain at least one Special character.';
                   
                }
                else{
                    return TRUE;
                }
            }
        
            
            
        } // End of validating_password_format
        
}
?>