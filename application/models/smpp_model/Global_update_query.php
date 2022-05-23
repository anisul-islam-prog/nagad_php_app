<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Global_update_query extends CI_Model
{
	private $tbl_campaing   = "DND_CAMPAIGN";
	
	function __construct()
	{
		 parent::__construct();
	}

	public function update($tbl,$index = array(),$value = array())
	{
		//echo "asdasd"; die();
		$this->db->where($index);
        $this->db->update($tbl, $value);
        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $this->db->affected_rows();
        } else {
            $this->error_message = 'Data update unsuccessful. DB error.';
            return false;
        }
	} 
	
	public function update_batch($tbl, $where = array(), $value  = array())
	{
		return $this->db->update_batch($tbl,$value,$where );
	}
	
	
	public function approve_campaigns($data){
			$this->db->where_in('ID',$data);
			$this->db->set('IS_APPROVED',1);
			$update = $this->db->update($this->tbl_campaing);
			return $update;
	}
}
?>