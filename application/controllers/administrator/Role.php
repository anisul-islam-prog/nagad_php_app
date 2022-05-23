<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Role extends MY_Controller
{
    var $current_page = "role";
    var $priv_list = array();

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
        $this->load->model('user_role_model');
        $this->load->model('log_model');
        
         /*MULTIPLE LOGIN CHECK */
        /*$this->load->model('user_model');
        $login_ip = $this->user_model->get_user_login_ip();
        if($login_ip != $this->session->userdata('access_ip')){
             redirect(base_url().'/logout');
        }*/
        /*MULTIPLE LOGIN CHECK */


        $all_privs = $this->user_role_model->get_all_privileges();
        for($i=0; $i<count($all_privs); $i++) {
            $this->priv_list[$all_privs[$i]['ID']] = $all_privs[$i]['PRIV_NAME'];
        }
    }

    /**
     * Display paginated list of companies
     * @return void
     */
    public function index()
	{
        // set page specific variables
        $page_info['title'] = 'Manage Roles'. $this->site_name;
        $page_info['view_page'] = 'administrator/role_list_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';

        $this->_set_fields();
        
        /* Activity Log */ $this->log_model->insert_log(2,'Open Manage Role Window.');

        $per_page = $this->config->item('per_page');
        $uri_segment = $this->config->item('uri_segment');
        $page_offset = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;

        $record_result = $this->user_role_model->get_paged_roles($per_page, $page_offset);
        //echo '<pre>'; print_r( $record_result ); echo '</pre>'; //die();


        $page_info['records'] = $record_result['result'];
        $records = $record_result['result'];

        // build paginated list
        $config = array();
        $config["base_url"] = base_url() . "administrator/role";
        $config["total_rows"] = $record_result['count'];
        $this->pagination->initialize($config);


        if ($records) {
            // customize and generate records table
            $tbl_heading = array(
                '0' => array('data'=> 'Role Name'),
                '1' => array('data'=> 'Description'),
                '2' => array('data'=> 'Action', 'class' => 'center', 'width' => '80')
            );
            $this->table->set_heading($tbl_heading);

            $tbl_template = array (
                'table_open'          => '<table class="table table-bordered table-striped" id="smpl_tbl" style="margin-bottom: 0;">',
                'table_close'         => '</table>'
            );
            $this->table->set_template($tbl_template);

            for ($i = 0; $i<count($records); $i++) {

                $role_str = '';
                if ($records[$i]['ROLE_NAME'] != '') {
                    $role_str = $records[$i]['ROLE_NAME'];
                }
                $desc_str = '';
                if ($records[$i]['ROLE_DESCRIPTION'] != '') {
                    $desc_str = $records[$i]['ROLE_DESCRIPTION'];
                }

                $action_str = '';
                $action_str .= anchor('administrator/role/edit/'. $records[$i]['ID'], '<i class="glyphicon glyphicon-edit"></i>', 'title="Edit"');
                //$action_str .= '&nbsp;&nbsp;&nbsp;';
                //$action_str .= anchor('administrator/role/delete/'. $records[$i]['Id'], '<i class="glyphicon glyphicon-trash"></i>', array('title'=>'Delete', 'onclick'=>'return confirm(\'Do you really want to delete this record?\')'));

                $tbl_row = array(
                    '0' => array('data'=> $role_str),
                    '1' => array('data'=> $desc_str),
                    '2' => array('data'=> $action_str, 'class' => 'center', 'width' => '80')
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
        
        
    public function add()
    {
        // set page specific variables
        $page_info['title'] = 'Edit Role'. $this->site_name;
        $page_info['view_page'] = 'administrator/role_add_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        // load view
	$this->load->view('layouts/default', $page_info);
    } // END OF ADD 
    
    public function do_add_role()
    {
        // set page specific variables
        $page_info['title'] = 'Edit Role'. $this->site_name;
        $page_info['view_page'] = 'administrator/role_add_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        
        $this->_set_rules();

       
        if ($this->form_validation->run() == TRUE) {
            $role_name = $this->input->post('role_name');
            $role_description = $this->input->post('role_description');
            
            $is_name_exists = $this->user_role_model->is_role_name_exists($role_name);
            if(count($is_name_exists)==0){
                $add = $this->user_role_model->add_role($data=array('ROLE_NAME'=>$role_name ,'ROLE_DESCRIPTION'=>$role_description));
                if($add==TRUE){
                    $page_info['message_success'] = 'Add is Successful !!';
                }
                else{
                    $page_info['message_error'] = 'Add is Not Successful !!';
                }
            }
            else{
                    $page_info['message_error'] = 'Add is Not Successful !!<br/> Reason: Role Name Already Exists  !!';
            }
            
            
        }
        
        // load view
	$this->load->view('layouts/default', $page_info);
    } // END OF do_add_role 

    public function edit($role_id = 0)
    {
        // set page specific variables
        $page_info['title'] = 'Edit Role'. $this->site_name;
        $page_info['view_page'] = 'administrator/role_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = true;

        $this->_set_fields();
        $this->_set_rules();

        // prefill form values
        $role_id = (int)$role_id;
	$role = $this->user_role_model->get_role($role_id);

        // prefill form values
        $role_id = (int)$role_id;
	$role = $this->user_role_model->get_role($role_id);
        if ( $role ) {
            $this->form_data->role_id = (int)$role['ID'];
            $this->form_data->role_name = $role['ROLE_NAME'];
            $this->form_data->role_description = $role['ROLE_DESCRIPTION'];
            /* Activity Log */ $this->log_model->insert_log(2,'Edit Role info. Role-'.$role['ROLE_NAME']);
            $role_privs = $this->user_role_model->get_role_privileges($role_id);
            $priv_ids = array();
            for($i=0; $i<count($role_privs); $i++) {
                $priv_ids[] = (int)$role_privs[$i]['ID'];
            }
            $this->form_data->priv_ids = $priv_ids;
        } else {
            $this->session->set_flashdata('message_error', $this->role_model->error_message);
            redirect('administrator/role');
        }


        // determine messages
        if ($this->session->flashdata('message_error')) { $page_info['message_error'] = $this->session->flashdata('message_error'); }
        if ($this->session->flashdata('message_success')) { $page_info['message_success'] = $this->session->flashdata('message_success'); }

        // load view
		$this->load->view('layouts/default', $page_info);
    }

    public function update_role()
    {
        // set page specific variables
        $page_info['title'] = 'Edit Role'. $this->site_name;
        $page_info['view_page'] = 'administrator/role_form_view';
        $page_info['message_error'] = '';
        $page_info['message_success'] = '';
        $page_info['message_info'] = '';
        $page_info['is_edit'] = true;

        $this->_set_fields();
        $this->_set_rules();

        $role_id = (int)$this->input->post('role_id');

        if ($this->form_validation->run() == FALSE) {

            $this->form_data->role_id = $role_id;
            $this->load->view('layouts/default', $page_info);

        } else {

            $role_name = $this->input->post('role_name');
            $role_description = $this->input->post('role_description');
            $priv_ids = $this->input->post('priv_ids');
            if ($priv_ids === false) {
                $priv_ids = array();
            }
            //echo '<pre>'; print_r( $priv_ids ); echo '</pre>'; die();
            $data = array(
                'ROLE_DESCRIPTION' => $role_description,
                'PRIVILEGES_IDS' => $priv_ids
            );

            if ($this->user_role_model->update_role($role_id, $data)) {
                $this->session->set_flashdata('message_success', 'Update is successful.');
            } else {
                $this->session->set_flashdata('message_error', $this->role_model->error_message .' Update is unsuccessful.');
            }

            redirect('administrator/role/edit/'. $role_id);
        }
    }


    // set empty default form field values
	private function _set_fields()
	{
        $this->form_data->role_id = 0;
		$this->form_data->role_name = '';
		$this->form_data->role_description = '';
        $this->form_data->priv_ids = array();
	}

	// validation rules
	private function _set_rules()
	{
		$this->form_validation->set_rules('role_name', 'Role Name', 'required|trim|xss_clean|strip_tags');
		$this->form_validation->set_rules('role_description', 'Role Description', 'trim|xss_clean|strip_tags');
	}
        
       
        
}

/* End of file role.php */
/* Location: ./application/controllers/administrator/role.php */