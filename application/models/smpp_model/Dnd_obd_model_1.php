<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dnd_obd_model extends CI_Model {

    public $error_message = '';
    private $tbl_dnd_temp = 'DND_TMP_DND_TABLE';
    private $tbl_dnd_local = 'DND_LOCAL';
    private $tbl_dnd_remote = 'DND_REMOTE';
    private $tbl_dnd_all = 'VIEW_DND_ALL';

    function __construct() {
        parent::__construct();
    }

	/*
    public function add_dnd_number($msisdn) {
		$session_user_name = $this->session->userdata('user_name');
        $this->db->trans_start();
        $batch_insert = $this->db->insert_batch($this->tbl_dnd_temp, $msisdn);
        if ($batch_insert) {
            $this->db->query("INSERT INTO ".$this->tbl_dnd_local." (MSISDN,CREATED_BY,CHANNEL) select MSISDN,'".$session_user_name."','WEB_APPLICATION' from
(( SELECT MSISDN FROM " . $this->tbl_dnd_temp . " MINUS SELECT MSISDN FROM " . $this->tbl_dnd_local . "))");
        }
		//echo $this->db->last_query(); die();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) { 
            return FALSE;
        } else {
            return TRUE;
        }
    }
	*/
	
	public function add_dnd_number($filename) {
		$session_user_name = $this->session->userdata('user_name');
        $this->db->trans_start();
       
            $this->db->query("INSERT INTO DND_FILE (FILE_NAME,CREATED_BY, SERVER_TYPE) values('".$filename."' ,'".$session_user_name."',1)");
       
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) { 
            return FALSE;
        } else {
            return TRUE;
        }
    }

// add_dnd_number

    public function remove_dnd_number($msisdn) {  
		
		$output = array_chunk($msisdn, 1000, true);
		foreach ($output as $key => $value) {
			//var_dump($value);die;
			$this->db->where_in('MSISDN', $value);
			$this->db->delete($this->tbl_dnd_local); 
		}
        		
		return true;
        //$remove = $this->db->query('DELETE FROM '.$this->tbl_dnd_local.' WHERE MSISDN IN ()');
    }

// remove_dnd_number

    public function downloaa_dnd($source) {
        $this->db->select('MSISDN');
        if ($source == 'local') {
		$checkArray= array('CHANNEL'=>'WEB_APPLICATION');
			$this->db->where($checkArray);
            $query = $this->db->get($this->tbl_dnd_local);
        } elseif ($source == 'all') {
            $query = $this->db->get($this->tbl_dnd_local);
        }
        return $query->result_array();
    }

//downloaa_dnd
    
}

?>