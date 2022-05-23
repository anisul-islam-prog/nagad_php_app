<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller
{
    var $site_name = '';

    public function __construct()
    {
        parent::__construct();

        // load necessary library and helper
        //$this->load->library('output');
        //$this->load->model('company_model');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->model('role_model');


        if( ! ini_get('date.timezone') ) {
            date_default_timezone_set('Asia/Dhaka');

        }


       // $this->output->nocache();
        //$this->output->enable_profiler(FALSE);


        $this->site_name = ' :: DV-SMPP SOLUTION';

        //echo '<pre>'; print_r( $this->device ); echo '</pre>';
        //die();
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */