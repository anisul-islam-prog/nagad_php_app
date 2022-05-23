<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dnd_user_model extends CI_Model
{
	var $tbl_dnd_user = 'DND_APP_USERS';
	var $tbl_dnd_department 	= "DND_USERS_DEPARTMENT";
	var $tbl_dnd_roles  		= "DND_APP_ROLES";
	function __construct()
	{
		parent::__construct();
	}

	public function select_user_role_by_menu($arrayName = array())
	{

		$this->db->select('TA.*,TB.MENUNAME as MNAME');
	    $this->db->from('DND_ROLE_MENU TA');
	    $this->db->join('DND_MENU TB', 'TB.MENUID = TA.MENUID', 'left');
	    if(count($arrayName)){
	    	$this->db->where($arrayName);
	    } 
	    $query = $this->db->get();
       //echo $this->db->last_query(); die();
	    return $query->result_array();
	}

	public function get_users_details()
	{
		$this->db->select('TA.*,
			TR.ROLE_NAME, TD.DEPARTMENT_NAME');

		$this->db->from('DND_APP_USERS TA');
		$this->db->join('DND_APP_ROLES TR', 'TA.ROLE_ID = TR.ID');
		$this->db->join('DND_USERS_DEPARTMENT TD', 'TA.DEPARTMENT_ID = TD.ID');
		//$this->db->where('DND_APP_USERS');
		//$this->db->group_by('student.student_id');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
	}
}
?>