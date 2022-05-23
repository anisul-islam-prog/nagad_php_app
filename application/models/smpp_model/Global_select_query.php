<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Global_select_query extends CI_Model
{
	public $error_message = '';

    function __construct()
    {
        parent::__construct();
        
    }

    public function is_data_exists($tbl , $value = array()){
        
        $this->db->where($value);
        $query = $this->db->get($tbl);
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    } // END OF is_data_exists
	
	public function get_Last_id($tbl){
		
		$que = "SELECT * FROM (SELECT ID FROM ".$tbl." ORDER BY CREATION_DATE DESC) WHERE ROWNUM = 1";
        
		$query = $this->db->query($que); 
		
		//print_r_pre( $query->result_array()[0]['ID']);
		
        return $query->result_array()[0]['ID'];
        
    } // END OF get_Last_id
	
	public function get_All_base_by_campaign_id($tbl,$id){
		
		$que = "SELECT * FROM ".$tbl." WHERE CAMPAIGN_ID = '".$id."' ORDER BY CREATION_DATE DESC ";
        $query = $this->db->query($que); 
		return $query->result_array();
        
    } // END OF get_All_base_by_campaign_id

    public function select_get_all_data_by_where($tbl , $value = array()){
        
        $this->db->where($value);
        $query = $this->db->get($tbl);
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            //echo $this->db->last_query(); die();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    } // END OF is_data_exists
    
     public function select_data_by_where_single_column($tbl , $key,$value){
        
        $this->db->where($key,$value,false);
        $query = $this->db->get($tbl);
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            //echo $this->db->last_query(); die();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    }

    public function get_record_count($tbl , $value = array()){
        $this->db->select('count(*) AS TOTAL');
        $this->db->where($value);
        $query = $this->db->get($tbl);
        $data = $query->first_row();
       // var_dump($data->TOTAL); die();
        return (int)$data->TOTAL;
        
    } // END OF get_record_count
    
    public function select_custom_limit($tbl, $limit, $offset=0, $value = array()){
        //var_dump($value); die();

        $this->db->where($value);
        $this->db->order_by('ID','ASC');
            $this->db->limit($limit, $offset);
        $this->db->from($tbl);
        
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    } // END OF is_data_exists
	
	 public function select_custom_limit_where($tbl, $limit, $offset=0, $value = array()){
        //var_dump($value); die();

        $this->db->where($value);
        $this->db->order_by('ID','ASC');
            $this->db->limit($limit, $offset);
        $this->db->from($tbl);
        
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    }


    public function select_join_department_where($datas){
       // echo $datas; die();
       	$this->db->select('DND_BUCKET_DEPARTMENTS.*,DND_USERS_DEPARTMENT.DEPARTMENT_NAME as DNAME');
	    $this->db->from('DND_BUCKET_DEPARTMENTS');
	    $this->db->join('DND_USERS_DEPARTMENT', 'DND_BUCKET_DEPARTMENTS.DEPARTMENT_ID = DND_USERS_DEPARTMENT.ID', 'left'); 
	    $this->db->where('DND_BUCKET_DEPARTMENTS.BUCKET_ID',$datas);

	    $query = $this->db->get();
       // echo $this->db->last_query(); die();
	    return $query->result_array();
    } // END OF select_join_department_where


     public function select_join_user_new($datas){
       // echo $datas; die();
        $this->db->select('TA.*,TB.DEPARTMENT_NAME as DNAME,TC.ROLE_NAME as RNAME');
        $this->db->from('DND_APP_USERS TA');
        $this->db->join('DND_USERS_DEPARTMENT TB', 'TA.DEPARTMENT_ID = TB.ID', 'left');
        $this->db->join('DND_APP_ROLES TC', 'TA.ROLE_ID = TC.ID', 'left'); 
        $this->db->where('TA.ID',$datas);
        $query = $this->db->get();
        return $query->result_array();
    } // END OF select_join_department_where


    public function select_get_all_data_by_join_download(){
        
        $this->db->select('DND_DEPARTMENT_MSISDN.MSISDN as number,DND_USERS_DEPARTMENT.DEPARTMENT_NAME as DNAME');
        $this->db->from('DND_DEPARTMENT_MSISDN');
        $this->db->join('DND_USERS_DEPARTMENT', 'DND_DEPARTMENT_MSISDN.DEPARTMENT_ID = DND_USERS_DEPARTMENT.ID', 'left'); 
        $query = $this->db->get();
       // echo $this->db->last_query(); die();
        return $query->result_array();
        
    } // END OF is_data_exists

}
?>