<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class User extends MY_Controller
{
    var $current_page = "user";
    var $user_role_list = array();
    var $user_region_list = array();
    var $user_area_list = array();
    var $user_rsp_list = array();

    var $filter_role_list = array();
    var $filter_locked_list = array();
    var $filter_active_list = array();

    function __construct()
    {
        parent::__construct();
        
        // check if already logged in
        if ( !$this->session->userdata('is_logged_in') ) {
            redirect('');
        } else {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type != 'admin') {
                redirect('');
            }
        }

        // load necessary library and helper
        $this->load->config("pagination");
        $this->load->library("pagination");
        $this->load->library('table');
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->model('user_role_model');
        $this->load->model('log_model');

         /*MULTIPLE LOGIN CHECK */
       /* $this->load->model('user_model');
        $login_ip = $this->user_model->get_user_login_ip();
        if($login_ip != $this->session->userdata('access_ip')){
             redirect(base_url().'/logout');
        }*/
        /*MULTIPLE LOGIN CHECK */


        // populate lists
        $user_roles = $this->user_role_model->get_roles();
        $this->user_role_list[] = 'Select User Role';
        $this->filter_role_list[] = 'All roles';
        if ($user_roles) {
            for ($i=0; $i<count($user_roles); $i++) {
                $this->user_role_list[$user_roles[$i]['ID']] = $user_roles[$i]['ROLE_NAME'];
                $this->filter_role_list[$user_roles[$i]['ID']] = $user_roles[$i]['ROLE_NAME'];
            }
        }

       

        $this->filter_locked_list[''] = 'Any';
        $this->filter_locked_list[1] = 'Locked';
        $this->filter_locked_list[0] = 'Unlocked';

        $this->filter_active_list[''] = 'Any';
        $this->filter_active_list[1] = 'Active';
        $this->filter_active_list[0] = 'Inactive';
    }

    /**
     * Display paginated list of companies
     * @return void
     */
    public function index()
	{
        // set page specific variables
        $page_info['title'] = 'Manage Users'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_list_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';

        $this->_set_fields();


        // gather filter options
        $filter = array();
        if ($this->session->flashdata('filter_user')) {
            $this->session->keep_flashdata('filter_user');
            $filter_user = $this->session->flashdata('filter_user');
            $this->form_data->filter_user = $filter_user;
            $filter['filter_user']['field'] = 'LOGIN_NAME_NAME_EMAIL_MSISDN';
            $filter['filter_user']['value'] = $filter_user;
        }
       // $filter
        if ($this->session->flashdata('filter_role')) {
            $this->session->keep_flashdata('filter_role');
            $filter_role = $this->session->flashdata('filter_role');
            $this->form_data->filter_role = $filter_role;
            $filter['filter_role']['field'] = 'ROLE_ID';
            $filter['filter_role']['value'] = $filter_role;
        }
        if ($this->session->flashdata('filter_locked') != '') {
            $this->session->keep_flashdata('filter_locked');
            $filter_locked = $this->session->flashdata('filter_locked');
            $this->form_data->filter_locked = $filter_locked;
            $filter['filter_locked']['field'] = 'USER_IS_LOCK';
            $filter['filter_locked']['value'] = $filter_locked;
        }
        if ($this->session->flashdata('filter_active') != '') {
            $this->session->keep_flashdata('filter_active');
            $filter_active = $this->session->flashdata('filter_active');
            $this->form_data->filter_active = $filter_active;
            $filter['filter_active']['field'] = 'USER_IS_ACTIVE';
            $filter['filter_active']['value'] = $filter_active;
        }
        $page_info['filter'] = $filter;


        $per_page = $this->config->item('per_page');
        $uri_segment = $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        $record_result = $this->user_model->get_paged_users($per_page, $page_offset, $filter);


        $page_info['records'] = $record_result['result'];
        $records = $record_result['result'];
        
        /* Activity Log */ $this->log_model->insert_log(2,'Open View Users Window.');

        // build paginated list
        $config = array();
        $config["base_url"] = base_url() . "administrator/user/index";
        $config["total_rows"] = $record_result['count'];
        $this->pagination->initialize($config);


        if ($records) {
            // customize and generate records table
            $tbl_heading = array(
                '0' => array('data'=> 'Login Name'),
                '1' => array('data'=> 'Name'),
                '2' => array('data'=> 'Email'),
                '3' => array('data'=> 'Msisdn'),
                '4' => array('data'=> 'Role'),
                '5' => array('data'=> 'Status', 'class' => 'center'),
                '6' => array('data'=> 'Action', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);

            for ($i = 0; $i<count($records); $i++) {

                $name_str = '';
                if ($records[$i]['USER_NAME'] != '') {
                    $name_str = $records[$i]['USER_NAME'];
                }
                $email_str = '';
                if ($records[$i]['USER_EMAIL'] != '') {
                    $email_str = $records[$i]['USER_EMAIL'];
                }
                $msisdn_str = '';
                if ($records[$i]['USER_MSISDN'] != '') {
                    $msisdn_str = $records[$i]['USER_MSISDN'];
                }
                $role_name_str = '';
                if ($records[$i]['ROLE_NAME'] != '') {
                    $role_name_str = $records[$i]['ROLE_NAME'];
                }

                $status_str = '';
                if ($records[$i]['USER_IS_LOCK'] == 1) {
                    $status_str .= '<span class="label label-danger">LOCKED</span>';
                } else {
                    $status_str .= '<span class="label label-success">UNLOCKED</span>';
                }
                $status_str .= '&nbsp;&nbsp;';
                if ($records[$i]['USER_IS_ACTIVE'] == 0) {
                    $status_str .= '<span class="label label-danger">INACTIVE</span>';
                } else {
                    $status_str .= '<span class="label label-success">ACTIVE</span>';
                }

                $action_str = '';
                $action_str .= anchor('administrator/user/edit/'. $records[$i]['ID'], '<i class="glyphicon glyphicon-edit"></i>', 'title="Edit"');
                //$action_str .= '&nbsp;&nbsp;&nbsp;';
                //$action_str .= anchor('administrator/user/delete/'. $records[$i]['Id'], '<i class="glyphicon glyphicon-trash"></i>', array('title'=>'Delete', 'onclick'=>'return confirm(\'Do you really want to delete this record?\')'));

                $tbl_row = array(
                    '0' => array('data'=> $records[$i]['USER_LOGIN_NAME']),
                    '1' => array('data'=> $name_str),
                    '2' => array('data'=> $email_str),
                    '3' => array('data'=> $msisdn_str),
                    '4' => array('data'=> $role_name_str),
                    '5' => array('data'=> $status_str, 'class' => 'center'),
                    '6' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
                );
                $this->table->add_row($tbl_row);
            }

            $page_info['records_table'] = $this->table->generate();
            $page_info['pagin_links'] = $this->pagination->create_links();

        } else {
            $page_info['records_table'] = '<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>No records found.</div>';
            $page_info['pagin_links'] = '';
        }

        
        // determine messages
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        // load view
		$this->load->view('layouts/default', $page_info);
	}

    public function filter()
    {
        $filter_user = $this->input->post('filter_user');
        $filter_role = $this->input->post('filter_role');
        $filter_locked = $this->input->post('filter_locked');
        $filter_active = $this->input->post('filter_active');
        $filter_clear = $this->input->post('filter_clear');

        if ($filter_clear == '') {
            if ($filter_user != '') {
                $this->session->set_flashdata('filter_user', $filter_user);
            }
            if ($filter_role > 0) {
                $this->session->set_flashdata('filter_role', $filter_role);
            }
            if ($filter_locked != '') {
                $this->session->set_flashdata('filter_locked', $filter_locked);
            }
            if ($filter_active != '') {
                $this->session->set_flashdata('filter_active', $filter_active);
            }
        } else {
            $this->session->unset_userdata('filter_user');
            $this->session->unset_userdata('filter_role');
            $this->session->unset_userdata('filter_locked');
            $this->session->unset_userdata('filter_active');
        }

        redirect('administrator/user');
    }

    /**
     * Display add user form
     * @return void
     */
    public function add()
    {
        // set page specific variables
        $page_info['title'] = 'Add New User'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = false;

        $this->_set_fields();
        $this->_set_rules();

        // determine messages
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        // load view
		$this->load->view('layouts/default', $page_info);
    }

    public function add_user()
    {
        $page_info['title'] = 'Add New User'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = false;

        $this->_set_fields();
        $this->_set_rules();

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layouts/default', $page_info);

        } else {
            
            
            $user_login_name = $this->input->post('user_login_name');
            $user_password = $this->input->post('user_password');
            $user_password_confirm = $this->input->post('user_password_confirm');
            $user_role_id = (int)$this->input->post('user_role_id');
            $user_name = $this->input->post('user_name');
            $user_email = $this->input->post('user_email');
            $user_msisdn = $this->input->post('user_msisdn');
           
            $user_is_lock = (int)$this->input->post('user_is_lock');
            $user_is_active = (int)$this->input->post('user_is_active');

            $data = array(
                'USER_LOGIN_NAME' => $user_login_name,
                'USER_PASSWORD' => $user_password,
                'USER_PASSWORD_CONFIRM' => $user_password_confirm,
                'ROLE_ID' => $user_role_id,
                'USER_NAME' => $user_name,
                'USER_EMAIL' => $user_email,
                'USER_MSISDN' => $user_msisdn,
                
                'USER_IS_LOCK' => $user_is_lock,
                'USER_IS_ACTIVE' => $user_is_active
            );

            $res = $this->user_model->add_user($data);

            if ($res > 0) {
               /* Activity Log */ $this->log_model->insert_log(2,'Add User. User- '.$user_login_name); 
                
                $this->session->set_flashdata('message_success', 'Add is successful.');
                redirect('administrator/user/edit/'. $res);
            } else {
                $page_info['message_error'] = $this->user_model->error_message .' Add is unsuccessful.';
                $this->load->view('layouts/default', $page_info);
            }
        }
    }

    public function edit($user_id = 0)
    {
        // set page specific variables
        $page_info['title'] = 'Edit User'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = true;

        $this->_set_fields();
        $this->_set_rules();

        // prefill form values
        $user_id = (int)$user_id;
		$user = $this->user_model->get_user($user_id);

        if ($user) {

            $msisdn_number = $user['USER_MSISDN'];
            if (substr($msisdn_number, 0, strlen('880')) == '880') {
                $msisdn_number = substr($msisdn_number, strlen('880'));
            }

            $this->form_data->user_id = (int)$user['ID'];
            $this->form_data->user_login_name = $user['USER_LOGIN_NAME'];
            $this->form_data->user_role_id = $user['ROLE_ID'];
            $this->form_data->user_name = $user['USER_NAME'];
            $this->form_data->user_email = $user['USER_EMAIL'];
            $this->form_data->user_msisdn = $msisdn_number;
            $this->form_data->user_is_lock = $user['USER_IS_LOCK'];
            $this->form_data->user_is_active = $user['USER_IS_ACTIVE'];
            
        } else {
            $this->session->set_flashdata('message_error', $this->user_model->error_message);
            redirect('administrator/user');
        }


        // determine messages
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        // load view
		$this->load->view('layouts/default', $page_info);
    }

    public function update_user()
    {
        
      
        
        // set page specific variables
        $page_info['title'] = 'Edit User'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = true;

        $this->_set_fields();
        $this->_set_rules();

        $user_id = (int)$this->input->post('user_id');

        if ($this->form_validation->run() == FALSE) {

            $this->form_data->user_id = $user_id;
            $this->load->view('layouts/default', $page_info);

        } else {

            $user_password = $this->input->post('user_password');
            $user_password_confirm = $this->input->post('user_password_confirm');
            $user_role_id = (int)$this->input->post('user_role_id');
            $user_name = $this->input->post('user_name');
            $user_email = $this->input->post('user_email');
            $user_msisdn = $this->input->post('user_msisdn');
            $user_is_lock = (int)$this->input->post('user_is_lock');
            $user_is_active = (int)$this->input->post('user_is_active');
            $user_login_name = $this->input->post('user_login_name');
            
            
          
            $data = array(
                'USER_PASSWORD' => $user_password,
                'USER_PASSWORD_CONFIRM' => $user_password_confirm,
                'ROLE_ID' => $user_role_id,
                'USER_NAME' => $user_name,
                'USER_EMAIL' => $user_email,
                'USER_MSISDN' => $user_msisdn,
                'USER_IS_LOCK' => $user_is_lock,
                'USER_IS_ACTIVE' => $user_is_active,
                'USER_LOGIN_NAME' => $user_login_name
            );

            if ($this->user_model->update_user($user_id, $data)) {
                $this->session->set_flashdata('message_success', 'Update is successful.');
                /* Activity Log */ $this->log_model->insert_log(2,'Edit User. User- '.$user_name); 
            } else {
                $this->session->set_flashdata('message_error', $this->user_model->error_message .' Update is unsuccessful.');
            }

            redirect('administrator/user/edit/'. $user_id);
        }
    } // End of update_user

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

        redirect('administrator/user');
    }
    
    
    
    public function edit_profile()
    {
        // set page specific variables
        $page_info['title'] = 'Update Profile'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_update_profile_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        
        $user_id = $this->session->userdata['Userid'];
        $page_info['user_info'] = $this->user_model->get_user($user_id);
            
        $this->load->view('layouts/default', $page_info);
        //    redirect('administrator/user/edit/'. $user_id);
       
    } // End of edit_profile
    
    public function do_edit_profile()
    {
        // set page specific variables
        $page_info['title'] = 'Update Profile'. $this->site_name;
        $page_info['view_page'] = 'administrator/user_update_profile_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        
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
                'USER_NAME'=>$name,
                'USER_MSISDN'=>$contact,
                'USER_EMAIL'=>$email
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
            $page_info['title'] = 'Change Password'. $this->site_name;
            $page_info['view_page'] = 'administrator/change_password_view';
            $page_info['message_error'] = '';
            $page_info['message_success'] = '';
            $page_info['message_info'] = '';


            $this->load->view('layouts/default', $page_info);
            //    redirect('administrator/user/edit/'. $user_id);
        } // End of change_password
        
        public function change_password_first(){
             // set page specific variables
            $page_info['title'] = 'Change Password'. $this->site_name;
            $page_info['view_page'] = 'administrator/change_password_view_first';
            $page_info['message_error'] = '';
            $page_info['message_success'] = '';
            $page_info['message_info'] = '';


            $this->load->view('layouts/default_2', $page_info);
            //    redirect('administrator/user/edit/'. $user_id);
        } // End of change_password
        
        
         public function do_chage_password(){
            // set page specific variables
            $page_info['title'] = 'Change Password'. $this->site_name;
            $page_info['view_page'] = 'administrator/change_password_view';
            $page_info['message_error'] = '';
            $page_info['message_success'] = '';
            $page_info['message_info'] = '';
            
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
                         'USER_PASSWORD'=>MD5($new_password),
                         'USER_PASSWORD_OLD'=>$db_password,
                         'USER_IS_DEFAULT_PASSWORD'=>1 ,
                         'PASSWORD_CHANGE_DATE'=> date('d-M-Y')     
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
        
        
        
        /*
        public function do_chage_password(){
            // set page specific variables
            $page_info['title'] = 'Change Password'. $this->site_name;
            $page_info['view_page'] = 'administrator/change_password_view';
            $page_info['message_error'] = '';
            $page_info['message_success'] = '';
            $page_info['message_info'] = '';
            
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
                 if(MD5($old_password) != $db_password){
                      $page_info['message_error'] = 'Old Password Did Not Matched !!';
                      $this->load->view('layouts/default', $page_info);
                 }
                 else{ // When Old Password Matched
                     
                     
                      //Validating New Password 
                      $validate = $this->validating_password_format($new_password);
                      
                      
                      
                      if($validate != 1){
                          //var_dump($validate); die();
                           $page_info['message_error'] = $validate;
                           $this->load->view('layouts/default', $page_info);
                      }
                      else{
                          $data = array(
                         'USER_PASSWORD'=>MD5($new_password),
                         'USER_PASSWORD_OLD'=>$db_password
                        );

                        $change =  $this->user_model->update_profile($user_id , $data);
                        if($change== TRUE){
                            $page_info['message_success'] = 'Password Successfully Changed';
                        }
                        else{
                            $page_info['message_error'] = 'Password Change Failed !!';
                        }

                        $this->load->view('layouts/default', $page_info);
                         }
                     
                 }
            }
           
            $this->load->view('layouts/default', $page_info);
            
        } // End of do_chage_password
         * 
         */
        
        
        public function do_chage_password_first(){
            // set page specific variables
            $page_info['title'] = 'Change Password'. $this->site_name;
            $page_info['view_page'] = 'administrator/change_password_view_first';
            $page_info['message_error'] = '';
            $page_info['message_success'] = '';
            $page_info['message_info'] = '';
            
            $this->_set_rules_change_password();

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layouts/default_2', $page_info);
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
                      $this->load->view('layouts/default_2', $page_info);
                      //die('old not matched');
                 }
                 else{ // When Old Password Matched
                     
                     //Checking New Password With Old Password
                    if((MD5($new_password) == $db_password) || (MD5($new_password) == $db_password2) ){
                     
                      $page_info['message_error'] = 'New Password Can not be similar with last two Old Passwords !!';
                      $this->load->view('layouts/default_2', $page_info);
                      //die('old not matched');
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

        
    


    // set empty default form field values
	private function _set_fields()
	{
        $this->form_data->user_id = 0;
        $this->form_data->user_login_name = '';
        $this->form_data->user_password = '';
        $this->form_data->user_password_confirm = '';
        $this->form_data->user_role_id = 0;
        $this->form_data->user_name = '';
        $this->form_data->user_email = '';
        $this->form_data->user_msisdn = '18';
        $this->form_data->user_region_ids = array();
        $this->form_data->user_area_ids = array();
        $this->form_data->user_rsp_ids = array();
        $this->form_data->user_is_lock = 0;
        $this->form_data->user_is_active = 1;

        $this->form_data->filter_user = '';
        $this->form_data->filter_role = '';
        $this->form_data->filter_locked = '';
        $this->form_data->filter_active = '';
	}

	// validation rules
	private function _set_rules()
	{
		$this->form_validation->set_rules('user_login_name', 'Login Name', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_password', 'Password', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_password_confirm', 'Confirm Password', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_role_id', 'User Role', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_name', 'User Name', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_email', 'Email Address', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_msisdn', 'Mobile Number', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_is_lock', 'Is Locked?', 'trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('user_is_active', 'Is Active?', 'trim|xss_clean|strip_tags');
	} // end of _set_rules
        
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
        
}

/* End of file user.php */
/* Location: ./application/controllers/administrator/user.php */