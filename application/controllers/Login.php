<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller
{
    var $current_page = "login";

    function __construct()
    {
        parent::__construct();
        $this->load->config('auth');
		
        $this->load->model('log_model');
        $this->load->model('role_model');
        $this->load->model('user_model');

        // check if already logged in
        
        if ($this->session->userdata('is_logged_in')) {
            $logged_in_type = $this->session->userdata('logged_in_type');
            if ($logged_in_type == 'admin') {
                redirect('administrator/dashboard');
            } elseif ($logged_in_type == 'report') {
                redirect('administrator/report');
            } else {
                redirect('logout');
            }
        }
         
    }

    /**
     * Display login form
     * @return void
     */
    function index()
    {
        $page_info['title']             = 'Login'. $this->site_name;
        $page_info['message_error']     = '';
        $page_info['redirect_url']      = '';
        $page_info['name']              = 'asdasdas dasdasds';

        if ($this->session->flashdata('show_box')) { $page_info['show_box'] = $this->session->flashdata('show_box'); }
        else { $page_info['show_box'] = '1'; }
        // determine messages
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        // load view
        $this->load->view('login_view', $page_info);
    }

    /**
     * Validate and Authenticate Username and Password then redirect to the dashboard
     * @return void
     */
    public function do_login()
    {
        $username = trim($this->input->post('user_login'));
        $password = trim($this->input->post('user_password'));
        
		
		
      
        // Checking for 5 times wrong password 
        
        if(!$this->session->userdata('login_attempt')){
            $this->session->set_userdata('login_attempt', 1);
        }
        else{
            $attempt = $this->session->userdata('login_attempt');
            $attempt++;
            $this->session->set_userdata('login_attempt', $attempt);
        }
        
        
        if($this->session->userdata('login_attempt')>=5){
            $lock_user = $this->user_model->lock_user($username);
            $this->session->set_flashdata('message_error', 'User is  Locked');
             $this->session->set_userdata('login_attempt', 0);
        }
        
        // Checking for 5 times wrong password 
        
        
       
         //var_dump ($userRoleMenu); die();
         $userArray = $this->user_model->get_users_login_credntial($username);
		 
		 // CSRF CHECKING
	   $token =$this->input->post('token');
	   if($token != $this->session->userdata('token')){
		    $this->session->set_flashdata('message_error', 'Invalid Token');
			redirect('');
	   }
         
         //var_dump( $userArray);die('die');
         if($userArray)
         {
             
             $Userid             = $userArray[0]['ID'];
             $Password           = $userArray[0]['USER_PASSWORD'];
             $user_name          = $userArray[0]['USER_NAME'];
             $user_login_name    = $userArray[0]['USER_LOGIN_NAME'];
             $role_id            = $userArray[0]['ROLE_ID'];
             $user_msisdn        = $userArray[0]['USER_MSISDN'];
             $user_department    = $userArray[0]['DEPARTMENT_ID'];
			 $user_group_manager = $userArray[0]['IS_GROUP_MANAGER'];
			 $user_login_type = $userArray[0]['USER_LOGIN_TYPE'];
			 $mail_check = $userArray[0]['MAIL_CHECK'];
			 $user_mail = $userArray[0]['USER_EMAIL'];
			 
			 
             $brand_name = $userArray[0]['bname'];
             
             
             if( $userArray[0]['USER_IS_LOCK']== 1)
             { 
                 $this->session->set_flashdata('message_error', 'User is  locked');
                 redirect('');
             }
			 

             if($userArray[0]['USER_IS_ACTIVE']== 1)
             {
                 
                
                 if($Password ==MD5($password))
                 {
					 //session_start();
                     $user_current_session = session_id(); 
					 
                    
                     $this->session->set_userdata('is_logged_in', true);
                     $this->session->set_userdata('Userid', $Userid);
                     $this->session->set_userdata('user_name', $user_name);
                     $this->session->set_userdata('user_login_name', $user_login_name);
                     $this->session->set_userdata('logged_in_type', 'admin');
					 $this->session->set_userdata('user_login_type', $user_login_type);
                     $this->session->set_userdata('user_role_id', $role_id);
                     $this->session->set_userdata('user_msisdn', $user_msisdn);
                     $this->session->set_userdata('login_attempt', 0);
                     $this->session->set_userdata('user_department', $user_department);
                     $this->session->set_userdata('user_group_manager', $user_group_manager);
                     $this->session->set_userdata('user_brand_name', $brand_name);
					 $this->session->set_userdata('user_mail', $user_mail);
					 $this->session->set_userdata('user_mail_check', $mail_check);
					 $this->session->set_userdata('session_id', $user_current_session);
                    // $this->session->set_userdata('')
					
					//var_dump($user_mail);var_dump($mail_check);die;

                     $role_menu = $this->user_model->get_users_role_by_menu(array('TA.ROLEID'=>$role_id));
					 
                     //print_r_pre($role_menu); die();
                     //$permited_menus = $this->role_model->get_permitted_menu($Userid); 
                     //$this->session->set_userdata('PMVD_permitted_menu', $permited_menus);
                     
                    $privilage_name = array();
                        foreach($role_menu as $k=>$v){
                            $privilage_name[] = $v['MENUNAME'];
                        }
                    //print_r_pre($privilage_name); die();
                    $this->session->set_userdata('user_menu', $privilage_name);
					
					
                   
                      //print_r_pre($privilage_name); die();
                     // Iserting Successful Login Time
                     $this->user_model->update_success_login_time_and_session($username,$user_current_session);
					 
                     
                     if($userArray[0]['USER_IS_DEFAULT_PASSWORD']== 1) // Checking for first Login
                     {
                       /* Activity Log */ $this->log_model->insert_log(1,'Login');
					   
                        redirect('administrator/dashboard');
                     }
                     else{ // When First Login
					 
                        redirect('dnd_controllers/user_management/change_password_first');
                     }
                 }
                 else
                 {
                     $this->session->set_flashdata('message_error', 'Invalid Login ID or Password');
                     redirect('');
                 }
             }
             else
             {
                 $this->session->set_flashdata('message_error', 'User is  Inactive');
                 redirect('');
             }
         }
         else
        {
            $this->session->set_flashdata('message_error', 'Invalid Login ID or Password');
            redirect('');
        }
    }

  public function validate_user($username,$password)
  {

      $userArray = $this->user_model->get_users_credntial($username);

      if($userArray[0]['USERNAME']==$username  and  $userArray[0]['PASSWORD']== $password) 
      {

          return true;
      }
//      else  return false;
//      echo "<pre>";
//      print_r($userArray);
//      echo "</pre>";
//      die();
  }


}

/* End of file login.php */
/* Location: ./application/controllers/login.php */