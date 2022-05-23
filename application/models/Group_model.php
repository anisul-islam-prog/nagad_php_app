<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends CI_Model
{
    private $tbl_group = 'OFFER_GROUP_LIST';
    
    


    public $error_message = '';

    function __construct()
    {
        parent::__construct();
        
    }




    public function get_groups(){
        $this->db->where('ISACTIVE',1);
        $this->db->order_by('GROUP_ID','DESC');
        $query = $this->db->get($this->tbl_group);
       return $query->result_array();
    } // END OF get_groups
    
    public function is_group_exists($group_name){
       $query = $this->db->query('SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END as IS_EXISTS FROM '.$this->tbl_group.' WHERE LOWER(GROUP_NAME)= '."LOWER('".$group_name."')");
       $data =  $query->result_array();
       return $data[0];
    } // END OF is_group_exists
    
     public function add_group($data) {
         return $this->db->insert($this->tbl_group,$data);
     }// END OF add_group
     
    public function edit_group($data,$group_id) {
       $this->db->where('GROUP_ID',$group_id);
        return $this->db->update($this->tbl_group,$data);
    }// END OF edit_group
     
     
     
     public function is_valid_group_id($group_id){
       $query = $this->db->query('SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END as IS_EXISTS FROM '.$this->tbl_group.' WHERE  GROUP_ID = '.$group_id);
       $data =  $query->first_row();
       return $data->IS_EXISTS;
    } // END OF is_valid_group_id
    
} // END OF CLASS
