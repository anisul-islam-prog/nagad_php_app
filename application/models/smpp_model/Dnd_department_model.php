<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dnd_department_model extends CI_Model
{
	var $tbl_dnd_users_department = 'DND_USERS_DEPARTMENT';
    var $tbl_dnd_bucket           = 'DND_BUCKET';
	function __construct()
	{
		parent::__construct();
	}

	public function get_last_data_department(){
		$this->db->order_by('PRIORITY', 'DESC');
		$this->db->limit('1');
        $query = $this->db->get($this->tbl_dnd_users_department);
		//echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    } // END OF is_data_exists

    public function get_last_data_bucket(){
        $this->db->order_by('PRIORITY', 'DESC');
        $this->db->limit('1');
        $query = $this->db->get($this->tbl_dnd_bucket);
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
        
    } // END OF is_data_exists
    
    public function getOperatorPriority($brand_name) {
        // GETTING BUCKET ID
        /*$this->db->where('DEPARTMENT_ID',$department_id);
        $b_query = $this->db->get($this->tbl_bucket_mapping);
        $b_data = $b_query->first_row();
        $bucket_id = $b_data->BUCKET_ID;
        */

        //$op = "SELECT coalesce (max(PRIORITY)+1, 1) AS PRIORITY FROM DND_USERS_DEPARTMENT WHERE BRAND_NAME = '".$brand_name."'";
        $OP = "SELECT coalesce (max(PRIORITY)+1, 1) AS PRIORITY FROM DND_USERS_DEPARTMENT WHERE BRAND_NAME = '".$brand_name."'";
        // GETTING PRIORITY
        //$this->db->select('coalesce (max(PRIORITY)+1,1) AS PRIORITY ');
        //$this->db->where('BRAND_NAME', $brand_name);
        //var_dump($brand_name); die();
        $Op_query = $this->db->query($OP);
        $br_data = $Op_query->first_row();
        //return $this->db->last_query();
        //return $OP;
       return $br_data->PRIORITY;
    } //getPriority
	
	public function getBucketPriority($brand_name)
	{
		$BP = "SELECT coalesce (max(PRIORITY)+1, 1) AS PRIORITY FROM DND_BUCKET WHERE BRAND_NAME = '".$brand_name."'";
		$bp_query = $this->db->query($BP);
        $bp_data = $bp_query->first_row();
        //return $this->db->last_query();
        
       return $bp_data->PRIORITY;
	}


    public function get_segment_val_by_range($operator,$start,$end,$type){ 
        $sql ="select * from DND_SEGMENT where OPERATOR_NAME='".$operator."' and MSISDN_TYPE='".$type."' and ((".$start." between start_range and end_range ) OR (".$end." between start_range and end_range)) ";
        //$sql = "select * from DND_SEGMENT where START_TIME <= '".$start."' and END_TIME >= '".$end."'";
        $query  = $this->db->query($sql);
       
        return $query->num_rows();
        
    } // END OF get_segment_val_by_range

    public function get_segment_val_by_range_or_id($id,$operator,$start,$end,$type){
        $sql ="select * from DND_SEGMENT where ID <>'".$id."' and OPERATOR_NAME='".$operator."' and MSISDN_TYPE='".$type."'  and ((".$start." between start_range and end_range ) OR (".$end." between start_range and end_range))";
        //$sql = "select * from DND_SEGMENT where START_TIME <= '".$start."' and END_TIME >= '".$end."'";
        $query  = $this->db->query($sql);
        //echo $this->db->last_query();die();
        return $query->num_rows();
        
    } // END OF get_segment_val_by_range



    public function update_sequecely($lastID,$data,$brandName)
    {
		//echo $lastID.'--'.$data; exit;
		if($lastID > $data){
			$sql = "update DND_USERS_DEPARTMENT set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
        }
		else{
			$sql = "update DND_USERS_DEPARTMENT set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY-1 END where PRIORITY <= '".$data."' and PRIORITY >= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
		}
    	$query 	= $this->db->query($sql);

    //echo $this->db->last_query(); die();

        return true;
    }
	
	public function updateBucket_sequencialy($lastID,$data,$brandName)
    {
        //$sql = "update DND_BUCKET set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' and BRAND_NAME = '".$brandName."' ";

        
    	if($lastID > $data){
			$sql = "update DND_BUCKET set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
        }
		else{
			$sql = "update DND_BUCKET set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY-1 END where PRIORITY <= '".$data."' and PRIORITY >= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
		}
    	$query 	= $this->db->query($sql);

    //echo $this->db->last_query(); die(); 

        return true;
    }
	
	
	public function updateBucket_sequencialy_with_last_quota($lastID,$data,$brandName,$quota_remaining)
    {
        //$sql = "update DND_BUCKET set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' and BRAND_NAME = '".$brandName."' ";

        
    	if($lastID > $data){
			$sql = "update DND_BUCKET set QUOTA_REMAINING=".$quota_remaining.", PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
        }
		else{
			$sql = "update DND_BUCKET set QUOTA_REMAINING=".$quota_remaining.", PRIORITY= CASE WHEN (PRIORITY = ".$lastID." ) THEN ".$data." ELSE  PRIORITY-1 END where PRIORITY <= '".$data."' and PRIORITY >= '".$lastID."' and BRAND_NAME = '".$brandName."' ";
		}
    	$query 	= $this->db->query($sql);

    //echo $this->db->last_query(); die();

        return true;
    }
	
	

    public function select_custom_limit_department($tbl, $limit, $offset=0, $value = array()){
        //var_dump($value); die();
        if(count($value)){
        	$this->db->where($value);
        }
        $this->db->from($tbl);
        $this->db->order_by('PRIORITY','ASC');
        $this->db->limit($limit, $offset);

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
}
?>