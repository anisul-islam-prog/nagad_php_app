<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    private $tbl_menu = 'OFFER_USSD_MENU';
    
    


    public $error_message = '';

    function __construct()
    {
        parent::__construct();
        
    }


    public function get_menus(){
        $this->db->order_by('MENU_ID','ASC');
       $query = $this->db->get($this->tbl_menu);
       return $query->result_array();
    }// END OF get_menus
    
    public function get_all_menu(){
        $this->db->where('ISACTIVE',1);
        $this->db->order_by('MENU_ID','ASC');
       $query = $this->db->get($this->tbl_menu);
       return $query->result_array();
    }// END OF get_all_menu

    public function is_menu_exists($menu_name){
        $query = $this->db->query('SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END as IS_EXISTS FROM '.$this->tbl_menu.' WHERE LOWER(MENU_NAME)= '."LOWER('".$menu_name."')");
        $data =  $query->result_array();
       return $data[0];
    } // END OF is_menu_exists
    
    public function is_menu_exists_update($menu_name,$menu_id){
        $query = $this->db->query('SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END as IS_EXISTS FROM '.$this->tbl_menu.' WHERE MENU_ID != '.$menu_id.' AND LOWER(MENU_NAME)= '."LOWER('".$menu_name."')");
        $data =  $query->result_array();
       return $data[0];
    } // END OF is_menu_exists_update
    
    
    
    public function add_menu($data){
        return($this->db->insert($this->tbl_menu,$data));
    }// END OF AD MENU
    
    public function update_menu($data,$menu_id){
        $this->db->where('MENU_ID',$menu_id);
        return($this->db->update($this->tbl_menu,$data));
    }// END OF update_menu
    
    public function is_valid_menu_id($menu_id){
       $query = $this->db->query('SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END as IS_EXISTS FROM '.$this->tbl_menu.' WHERE  MENU_ID = '.$menu_id);
       $data =  $query->first_row();
       return $data->IS_EXISTS;
    } // END OF is_valid_menu_id
    
    public function get_menu($menu_id){
       $this->db->where('MENU_ID',$menu_id);
       $query = $this->db->get($this->tbl_menu);
       return $query->first_row();
    } // END OF get_menu
    
    
    
    
} // END OF CLASS
