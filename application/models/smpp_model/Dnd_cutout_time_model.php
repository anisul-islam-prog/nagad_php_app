<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dnd_cutout_time_model extends CI_Model {

    public $error_message = '';
    private $tbl_dnd_temp = 'DND_TMP_DND_TABLE';
    private $tbl_dnd_local = 'DND_LOCAL';
    private $tbl_dnd_remote = 'DND_REMOTE';
    private $tbl_dnd_all = 'VIEW_DND_ALL';
    private $tbl_cutout_time = 'DND_CUTOUT_TIME';

    function __construct() {
        parent::__construct();
    }

    public function get_cutout_times() {
        $query = $this->db->get($this->tbl_cutout_time);
		//print_r_pre($query->first_row()); 
        $data = $query->first_row(); // num_rows() // last_query()
		
		
        return $data;
    }

// add_dnd_number
    
    public function update_cutout($data) {
        return $this->db->update($this->tbl_cutout_time,$data);
    } // update_cutout

   
    
}

?>