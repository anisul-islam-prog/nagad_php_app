<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('log_model');
    }

    /**
     * Logging out user; destroy all session data.
     * @return void
     */
    function index()
    {
        $this->log_model->insert_log(1,'Logout');
		$this->session->unset_userdata('session_id');  
        $this->session->sess_destroy(); 
        redirect('');
    }
}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */