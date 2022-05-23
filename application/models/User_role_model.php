<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class User_role_model extends CI_Model
{
    private $tbl_roles = 'OFFER_APP_ROLES';
    private $tbl_privs = 'OFFER_APP_PRIVILEGES';
    private $tbl_role_privs = 'OFFER_APP_ROLE_PRIVILEGES';
    public $error_message = '';

    function __construct()
    {
        parent::__construct();
    }

    public function get_role($role_id)
    {
        $role_id = (int)$role_id;

        $this->db->limit(1);
        $this->db->where('ID', $role_id);
        $query = $this->db->get($this->tbl_roles);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
    }

    public function get_roles()
    {
        $this->db->order_by('ROLE_NAME','ASC');
        $query = $this->db->get($this->tbl_roles);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_paged_roles($limit, $offset = 0, $filter = array())
    {
        $result = array();
        $result['result'] = false;
        $result['count'] = 0;

        /*if (is_array($filter) && count($filter) > 0) {
            foreach($filter as $key => $value) {
                if ($key == 'filter_loginoremail') {
                    $this->db->where("(user_login LIKE '%". $value['value'] ."%' OR user_email LIKE '%". $value['value'] ."%')", '', false);
                } else {
                    $this->db->where($filter[$key]['field'], $filter[$key]['value']);
                }
            }
        }*/

        $this->db->from($this->tbl_roles);
        $this->db->order_by('ROLE_NAME','ASC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result['result'] = $query->result_array();

            // record count
            /*if (is_array($filter) && count($filter) > 0) {
                foreach($filter as $key => $value) {
                    if ($key == 'filter_loginoremail') {
                        $this->db->where("(user_login LIKE '%". $value['value'] ."%' OR user_email LIKE '%". $value['value'] ."%')", '', false);
                    } else {
                        $this->db->where($filter[$key]['field'], $filter[$key]['value']);
                    }
                }
            }*/

            $this->db->select('COUNT(*) AS NUM_ROWS');
            $this->db->from($this->tbl_roles);
            $query = $this->db->get();
            $result['count'] = $query->first_row()->NUM_ROWS;
        }

        return $result;
    }
    
    public function is_role_name_exists($role_name) {
        $this->db->where("LOWER(ROLE_NAME) = LOWER('".$role_name."')");
        $query = $this->db->get($this->tbl_roles);
        return $query->result_array();
    }


    /*******************************************************************************************************************
     ********************     A D D     /     U P D A T E     /     D E L E T E     ************************************
     ******************************************************************************************************************/

  public function add_role($data) {
      return $this->db->insert($this->tbl_roles,$data);
  } // END OF add_role
    
    public function update_role($role_id, $role)
    {
        $role_id = (int)$role_id;
        if ($role_id > 0) {

            // data validation
            $priv_ids = $role['PRIVILEGES_IDS'];
            unset($role['ROLE_NAME']);
            unset($role['PRIVILEGES_IDS']);

            // update role
            $this->db->where('id', $role_id);
            $this->db->update($this->tbl_roles, $role);

            if ($this->db->affected_rows() > 0) {

                //echo '<pre>'; var_dump( $priv_ids ); echo '</pre>'; die();
                $this->update_role_privileges($role_id, $priv_ids);
                return true;

            } else {
                $this->error_message = 'User Role not updated. DB Error.';
                return false;
            }

        } else  {
            $this->error_message = 'Invalid id.';
            return false;
        }
    }


    /*******************************************************************************************************************
     ******************************     R O L E     P R I V I L E G E S     ********************************************
     ******************************************************************************************************************/

    public function get_all_privileges()
    {
        $all_privs = array();
        $query = $this->db->get($this->tbl_privs);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return $all_privs;
    }

    public function get_role_privileges($role_id)
    {
        $role_id = (int)$role_id;
        $role_privs = array();

        if ($role_id > 0) {

            $this->db->select($this->tbl_role_privs .'.ID AS ROLE_PRIV_ID, '. $this->tbl_privs .'.*');
            $this->db->from($this->tbl_role_privs);
            $this->db->join($this->tbl_privs, $this->tbl_role_privs .'.PRIVILEGE_ID = '. $this->tbl_privs. '.ID', 'left');
            $this->db->where('ROLE_ID', $role_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $role_privs = $query->result_array();
            }

        } else  {
            $this->error_message = 'Invalid id.';
        }

        return $role_privs;
    }

    public function update_role_privileges($role_id, $priv_ids=array())
    {
        $role_id = (int)$role_id;
        if ($role_id > 0 && is_array($priv_ids)) {

            $existing_privs = $this->get_role_privileges($role_id);
            $existing_privs_ids = array();
            $insert_privs_ids = array();
            $delete_privs_ids = array();

            for ($i=0; $i<count($existing_privs); $i++) {
                $existing_privs_ids[] = $existing_privs[$i]['ID'];
            }

            $tempi = $priv_ids;
            $tempj = $existing_privs_ids;
            for($i=0; $i<count($tempi); $i++) {
                for($j=0; $j<count($tempj); $j++) {
                    if ($tempi[$i] == $tempj[$j]) {
                        unset($priv_ids[$i]);
                        unset($existing_privs_ids[$j]);
                        break;
                    }
                }
            }

            $insert_privs_ids = $priv_ids;
            $delete_privs_ids = $existing_privs_ids;
            
            
            
            foreach ($insert_privs_ids as $value) {
                
                //$current = $insert_privs_ids[$i];
                
                //var_dump($insert_privs_ids); die();
                
                $this->add_role_privilege($role_id, $value);
            }
            if ($delete_privs_ids && count($delete_privs_ids) > 0) {
                foreach ($delete_privs_ids as $val) {
                    $this->delete_role_privilege($role_id, $val);
                }
            }

        } else {
            $this->error_message = 'Invalid parameters.';
            return false;
        }
    }

    private function add_role_privilege($role_id, $priv_id)
    {
        $role_id = (int)$role_id;
        $priv_id = (int)$priv_id;
        
        

        if ($role_id > 0 && $priv_id > 0) {

            $data = array(
                'ROLE_ID' => $role_id,
                'PRIVILEGE_ID' => $priv_id
            );
            $this->db->insert($this->tbl_role_privs, $data);
            $sql = $this->db->last_query();
            
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                $this->error_message = 'Add unsuccessful. DB error.';
                return false;
            }

        } else {
            $this->error_message = 'Invalid id.';
            return false;
        }
    }

    private function delete_role_privilege($role_id, $priv_id)
    {
        $role_id = (int)$role_id;
        $priv_id = (int)$priv_id;

        if ($role_id > 0 && $priv_id > 0) {

            $this->db->where('ROLE_ID', $role_id);
            $this->db->where('PRIVILEGE_ID', $priv_id);
            $this->db->delete($this->tbl_role_privs);

            $res = $this->db->affected_rows();
            if ($res > 0) {
                return true;
            } else {
                $this->error_message = 'Delete unsuccessful. DB error.';
                return false;
            }

        } else {
            $this->error_message = 'Invalid id.';
            return false;
        }
    }
}

/* End of file user_role_model.php */
/* Location: ./application/models/user_role_model.php */