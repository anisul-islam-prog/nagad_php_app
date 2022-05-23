<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base_model extends CI_Model {

    public $error_message = '';
    private $tbl_dnd_temp = 'DND_TMP_DND_TABLE';
    private $tbl_dnd_local = 'DND_LOCAL';
    private $tbl_dnd_remote = 'DND_REMOTE';
    private $tbl_dnd_all = 'VIEW_DND_ALL';
    
    private $tbl_obd_temp = 'DND_TMP_OBD_TABLE';
    private $tbl_obd_local = 'DND_OBD_LOCAL';
    
    private $tbl_base_temp = 'DND_TMP_BASE_TABLE_UPLOAD';
    private $tbl_obd_base_temp = 'DND_TMP_OBD_BASE_TABLE_UPLOAD';
	private $tbl_emp_base_temp = 'DND_TMP_EMP_BASE_TABLE_UPLOAD';
    private $tbl_base = 'DND_NUMBER_BASE';
    private $tbl_obd_number_base = 'DND_OBD_NUMBER_BASE';
    private $tbl_base_file = 'DND_NUMBER_FILE';
    private $tbl_base_robi      = 'ROBI_NUMBER_BASE';
    private $tbl_base_file_robi = 'ROBI_NUMBER_FILE';
    private $tbl_obd_base_file = 'DND_OBD_NUMBER_FILE';
	private $tbl_emp_robi = 'DND_EMPLOYEE_MSISDN_ROBI';
	private $tbl_emp_airtel = 'DND_EMPLOYEE_MSISDN_AIRTEL';
    

    function __construct() {
        parent::__construct();
    }

    
    public function upload_base_number($inputfilename,$department_id,$brand_name) {
        $this->db->trans_start();
        
        $file_data['FILE_NAME']=$inputfilename;
        $file_data['CREATED_BY'] = $this->session->userdata('user_name');
        $file_data['CAMPAIGN_TYPE'] = 'sms';
        $file_data['DEPARTMENT_ID'] = $department_id;
        $file_data['SERVER_TYPE'] = 1;
        
        if($brand_name == 'robi'){
            $this->db->insert($this->tbl_base_file_robi,$file_data);
        }elseif($brand_name == 'airtel'){
            $this->db->insert($this->tbl_base_file,$file_data);
        }
        
        //$file_id = $this->db->insert_id();
        
        

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

	public function add_robi_employee_base_number($msisdn) {
        $this->db->trans_start();
        
        
        $created_by = $this->session->userdata('user_name');
		
    


        $batch_insert = $this->db->insert_batch($this->tbl_emp_base_temp, $msisdn);
		

        if ($batch_insert) {            
            
            
            $this->db->query("INSERT INTO " . $this->tbl_emp_robi . " (MSISDN,CREATED_BY) ( SELECT  MSISDN, '".$created_by."' FROM (SELECT MSISDN FROM " . $this->tbl_emp_base_temp .  " MINUS SELECT MSISDN FROM " . $this->tbl_emp_robi . ") )");
            
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
	public function delete_robi_employee_base_number($msisdn) {
        
        
       $this->db->where_in('MSISDN', $msisdn);
        return $this->db->delete($this->tbl_emp_robi);
       
    }
	
	public function add_airtel_employee_base_number($msisdn) {
        $this->db->trans_start();
        
        
        $created_by = $this->session->userdata('user_name');
		
    


        $batch_insert = $this->db->insert_batch($this->tbl_emp_base_temp, $msisdn);
		

        if ($batch_insert) {            
            
            
            $this->db->query("INSERT INTO " . $this->tbl_emp_airtel . " (MSISDN,CREATED_BY) ( SELECT  MSISDN, '".$created_by."' FROM (SELECT MSISDN FROM " . $this->tbl_emp_base_temp .  " MINUS SELECT MSISDN FROM " . $this->tbl_emp_airtel . ") )");
            
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
	
	public function delete_airtel_employee_base_number($msisdn) {
        
        
       $this->db->where_in('MSISDN', $msisdn);
        return $this->db->delete($this->tbl_emp_airtel);
       
    }


    public function upload_dynamic_base_number($inputfilename) {
        $this->db->trans_start();

        $file_data['FILE_NAME']=$inputfilename;
        $file_data['CREATED_BY'] = $this->session->userdata('user_name');
		$file_data['CAMPAIGN_TYPE'] = 'dynamic';
		$file_data['STATUS'] = 0;
		$file_data['SERVER_TYPE'] = 2;
        $this->db->insert($this->tbl_base_file,$file_data);
        //$file_id = $this->db->insert_id();
		
        $max_id_query = $this->db->query('SELECT MAX(ID) AS MAX_ID from '.$this->tbl_base_file);
        $max_id_info = $max_id_query->first_row();
        $file_id=$max_id_info->MAX_ID;

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
			return FALSE;
        }
		else{
			return $file_id;
		}

        
    }

    public function upload_dynamic_base_number_robi($inputfilename) {
        $this->db->trans_start();

        $file_data['FILE_NAME']=$inputfilename;
        $file_data['CREATED_BY'] = $this->session->userdata('user_name');
        $file_data['CAMPAIGN_TYPE'] = 'dynamic';
        $file_data['STATUS'] = 0;
        $file_data['SERVER_TYPE'] = 1;
        $this->db->insert($this->tbl_base_file_robi,$file_data);
        //$file_id = $this->db->insert_id();
        
        $max_id_query = $this->db->query('SELECT MAX(ID) AS MAX_ID from '.$this->tbl_base_file_robi);
        $max_id_info = $max_id_query->first_row();
        $file_id=$max_id_info->MAX_ID;

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        else{
            return $file_id;
        }

        
    }


    public function upload_obd_base_number($inputfilename) {
        $this->db->trans_start();

        $file_data['FILE_NAME']=$inputfilename;
		$file_data['SERVER_TYPE'] = 1;

        $file_data['CREATED_BY'] = $this->session->userdata('user_name');



        //$file_id = $this->db->insert_id();
		
		

        $max_id_query = $this->db->query('SELECT MAX(ID) AS MAX_ID from '.$this->tbl_obd_base_file);
		
        $max_id_info = $max_id_query->first_row();
		//var_dump($max_id_info->MAX_ID);
		//echo $max_id_info->MAX_ID.'wefergege'; exit;
		
		if($max_id_info->MAX_ID== NULL)			
			$file_id=1;
		else {
		$file_id=$max_id_info->MAX_ID+1;
	}
        $file_data['ID']=$file_id;
        //var_dump($file_id);die; 
        $this->db->insert($this->tbl_obd_base_file,$file_data);


		/*

        $batch_insert = $this->db->insert_batch($this->tbl_obd_base_temp, $msisdn);
        if ($batch_insert) {
            $total_count_query = $this->db->query('SELECT count(DISTINCT MSISDN) AS TOTAL from '.$this->tbl_obd_base_temp);
            $total_count_info = $total_count_query->first_row();
            $total_count=$total_count_info->TOTAL;

            //$this->db->query('INSERT INTO ' . $this->tbl_obd_number_base . ' (FILE_ID,MSISDN) ( SELECT '.$file_id.', MSISDN FROM (SELECT DISTINCT MSISDN FROM ' . $this->tbl_obd_base_temp .  ') )');

            $this->db->query('UPDATE '.$this->tbl_obd_base_file.' SET TOTAL_NUMBER='.$total_count.' WHERE ID='.$file_id);
        }
		*/

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

// add_dnd_number

    
    
}

?>