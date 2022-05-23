<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Global_insert_query extends CI_Model
{
	public $error_message = '';
	var $tbl_department_msisdn_tpm  ='DND_DEPARMENT_TEMP_TBL';
	var $tbl_department_msisdn  ='DND_DEPARTMENT_MSISDN';
	function __construct()
	{
		 parent::__construct();
	}

	public function insert($tbl,$value = array())
	{
		$this->db->insert($tbl, $value);
        if ($this->db->affected_rows() > 0) {
            return $this->db->affected_rows();
        } else {
            $this->error_message = 'add unsuccessful. DB Error.';
            return false;
        }
	}

	public function insert_batch($tbl,$value)
	{
		return $this->db->insert_batch($tbl,$value);
	}

	public function add_batch_number($msisdn) {
		$dname = $this->input->post('dname');
        $this->db->trans_start();
        $batch_insert = $this->db->insert_batch($this->tbl_department_msisdn_tpm, $msisdn);
        if ($batch_insert) {
            $this->db->query('INSERT INTO ' . $this->tbl_department_msisdn . ' (DEPARTMENT_ID,MSISDN)
             (

	            	select  '.$dname.', MSISDN from (

			             SELECT DISTINCT MSISDN FROM ' . $this->tbl_department_msisdn_tpm . ' MINUS SELECT MSISDN FROM ' . $this->tbl_department_msisdn . ' WHERE DEPARTMENT_ID= '.$dname.'
			             
	             	)

             )');
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
?>