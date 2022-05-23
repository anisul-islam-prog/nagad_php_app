<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{
    private $tbl_log = 'DND_APP_LOG';
    
    


    public $error_message = '';

    function __construct()
    {
        parent::__construct();
        
    }




    public function insert_log($input_type,$action){

        $data['BROWSER'] = $this->input->user_agent();
        $data['CLIENT_IP'] = $this->input->ip_address();
        $data['INPUT_TYPE']=$input_type;
        $data['USER_ID'] = $this->session->userdata('user_login_name');
        $data['ACTION'] = $action;
        return $this->db->insert($this->tbl_log,$data);
    } // END OF upload_mirror_msisdn_tmp
    
} // END OF CLASS
