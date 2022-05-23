<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Global_delete_query extends CI_Model
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function delete($tbl,$value = array())
	{
		$this -> db -> where($value);
  		$this -> db -> delete($tbl);
  		return true; 
	}
	
	public function delete_rows($tbl,$value = array())
	{
		$this -> db -> where($value);
  		$this -> db -> delete($tbl);
  		return $this->db->affected_rows();; 
	}

	
}
?>