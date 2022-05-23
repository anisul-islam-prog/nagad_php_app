<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class User_model extends CI_Model
{
    //private $tbl_users = 'DND_APP_USERS';
    //private $tbl_roles = 'OFFER_APP_ROLES';
    //private $tbl_department = 'DEPARTMENT_NAME';
    var $tbl_users = 'DND_APP_USERS';
    var $tbl_dnd_department     = "DND_USERS_DEPARTMENT";
    var $tbl_dnd_roles          = "DND_APP_ROLES";
    var $tbl_dnd_roleMenu       = "DND_ROLE_MENU";
    var $tbl_dnd_menu           = "DND_MENU";
   
    
    public $error_message = '';

    function __construct()
    {
        parent::__construct();
    }

    public function get_user($user_id)
    {
        $user_id = (int)$user_id;

        $this->db->limit(1);
        $this->db->where('ID', $user_id);
        $query = $this->db->get($this->tbl_users);

        if ($query->num_rows() > 0) {
            $user = $query->row_array();
             return $user;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
    }

    public function get_user_by_login_name($login_name)
    {
        $login_name = trim($login_name);

        $this->db->limit(1);
        $this->db->where('USER_LOGIN_NAME', $login_name);
        $query = $this->db->get($this->tbl_users);

        if ($query->num_rows() > 0) {
            $user = $query->row_array();
           
            return $user;
        } else {
            $this->error_message = 'No record found.';
            return false;
        }
    }

    public function get_users()
    {
        $this->db->order_by('USER_NAME','ASC');
        $query = $this->db->get($this->tbl_users);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
     
    public function get_users_credntial($username)
    {

        $this->db->select('*');
        $this->db->where('USER_LOGIN_NAME',$username);
        // $this->db->order_by('Username');
        $query = $this->db->get($this->tbl_users);
        //echo $this->db->last_query(); die();
        $arrQuery = $query->result_array();
       
        return  $arrQuery;
    } // End of get_users_credntial

    public function get_users_role_by_menu($roleID = array())
    {
        if($roleID){
             $this->db->where($roleID);
        }
        $this->db->select('TA.*,TB.MENUNAME as MENUNAME');
        $this->db->join($this->tbl_dnd_menu.' TB', 'TA.MENUID = TB.MENUID', 'left');
        $query = $this->db->get($this->tbl_dnd_roleMenu. ' TA');
        $arrQuery = $query->result_array();
        return  $arrQuery;
    } // End of get_users_credntial

    public function get_users_login_credntial($username)
    {

        $this->db->select('TA.*,TB.BRAND_NAME as bname');
        $this->db->from($this->tbl_users.' TA');
        $this->db->join($this->tbl_dnd_department.' TB', 'TA.DEPARTMENT_ID = TB.ID', 'left');
        $this->db->where('TA.USER_LOGIN_NAME',$username);
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $arrQuery = $query->result_array();
       
        return  $arrQuery;
    } // End of get_users_credntial
    
     public function lock_user($user_id){
         $this->db->where('USER_LOGIN_NAME', $user_id);
         $data = array('USER_IS_LOCK'=>1);
         return $this->db->update($this->tbl_users, $data);
     } // lock_user

    public function get_paged_users($limit, $offset = 0, $filter = array())
    {
        $result = array();
        $result['result'] = false;
        $result['count'] = 0;

        if (is_array($filter) && count($filter) > 0) {
            foreach($filter as $key => $value) {
                if ($key == 'filter_user') {
                    $this->db->where("(USER_LOGIN_NAME LIKE '%". $value['value'] ."%' OR USER_NAME LIKE '%". $value['value'] ."%' OR USER_EMAIL LIKE '%". $value['value'] ."%' OR USER_MSISDN LIKE '%". $value['value'] ."%')", '', false);
                } else {
                    $this->db->where($filter[$key]['field'], $filter[$key]['value']);
                }
            }
        }

        $this->db->select($this->tbl_users .'.*' .', '. $this->tbl_roles .'.ROLE_NAME');
        $this->db->from($this->tbl_users);
        $this->db->join($this->tbl_roles, $this->tbl_users .'.ROLE_ID = '. $this->tbl_roles. '.ID', 'left');

        $this->db->order_by('USER_LOGIN_NAME','ASC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result['result'] = $query->result_array();

            // record count
            if (is_array($filter) && count($filter) > 0) {
                foreach($filter as $key => $value) {
                    if ($key == 'filter_user') {
                        $this->db->where("(USER_LOGIN_NAME LIKE '%". $value['value'] ."%' OR USER_NAME LIKE '%". $value['value'] ."%' OR USER_EMAIL LIKE '%". $value['value'] ."%' OR USER_MSISDN LIKE '%". $value['value'] ."%')", '', false);
                    } else {
                        $this->db->where($filter[$key]['field'], $filter[$key]['value']);
                    }
                }
            }

            $this->db->select('COUNT(*) AS NUM_ROWS');
            $this->db->from($this->tbl_users);
            $this->db->join($this->tbl_roles, $this->tbl_users .'.ROLE_ID = '. $this->tbl_roles. '.ID', 'left');
            $query = $this->db->get();
            $result['count'] = $query->first_row()->NUM_ROWS;
        }

        return $result;
    }



    /*******************************************************************************************************************
     ********************     A D D     /     U P D A T E     /     D E L E T E     ************************************
     ******************************************************************************************************************/

    public function add_user($user)
    {
        $CI = get_instance();
        $CI->load->helper('email');

        if (is_array($user)) {

            // validating password
            
            if (strpos(strtolower($user['USER_PASSWORD']), strtolower($user['USER_LOGIN_NAME']))  !== false) {
                $this->error_message = 'Password should not Contain User Login Name.';
                return false;
            } 
            
            
            if ($user['USER_PASSWORD'] == '') {
                $this->error_message = 'Password is required.';
                return false;
            } elseif ($user['USER_PASSWORD'] != $user['USER_PASSWORD_CONFIRM']) {
                $this->error_message = 'Password and Confirm Password does not match.';
                return false;
            } elseif (strlen($user['USER_PASSWORD']) < 8) {
                $this->error_message = 'Password must have at least 8 (eight) characters.';
                return false;
            }else {

                $password = $user['USER_PASSWORD'];

                preg_match_all ("/[A-Z]/", $password, $matches);
                $uppercase = count($matches[0]);

                preg_match_all ("/[a-z]/", $password, $matches);
                $lowercase = count($matches[0]);

                preg_match_all ("/\d/i", $password, $matches);
                $numbers = count($matches[0]);

                preg_match_all ("/[^A-z0-9]/", $password, $matches);
                $special = count($matches[0]);

                if ($uppercase <= 0) {
                    $this->error_message = 'Password should contain at least one Uppercase letter.';
                    return false;
                } elseif ($lowercase <= 0) {
                    $this->error_message = 'Password should contain at least one Lowercase letter.';
                    return false;
                } elseif ($numbers <= 0) {
                    $this->error_message = 'Password should contain at least one Numeric character.';
                    return false;
                } elseif ($special <= 0) {
                    $this->error_message = 'Password should contain at least one Special character.';
                    return false;
                }
            }
            if (isset($user['USER_PASSWORD_CONFIRM'])) {
                unset($user['USER_PASSWORD_CONFIRM']);
            }
            $password_md5 = md5($user['USER_PASSWORD']);
            $user['USER_PASSWORD'] = $password_md5;
            $user['USER_PASSWORD_OLD'] = $password_md5;

            // checking unique login name
            $existing_user = $this->get_user_by_login_name($user['USER_LOGIN_NAME']);
            if ($existing_user) {
                $this->error_message = 'Login Name already exists.';
                return false;
            }

            // user email validation
            if (isset($user['USER_EMAIL']) && $user['USER_EMAIL'] != '') {
                // check if email address is valid
                if ( !valid_email($user['USER_EMAIL']) ) {
                    $this->error_message = 'Invalid email address. Please try a different one.';
                    return false;
                }
            }

            // validate mobile number
            if ($user['USER_MSISDN'] == '') {
                $this->error_message = 'Mobile Number is required.';
                return false;
            } elseif ( strlen($user['USER_MSISDN']) != 10 ) {
                $this->error_message = 'Invalid Mobile Number. Please enter ten (10) characters.';
                return false;
            } elseif ( !ctype_digit($user['USER_MSISDN']) ) {
                $this->error_message = 'Invalid Mobile Number. Please enter numeric characters only.';
                return false;
            }
            $user['USER_MSISDN'] = "880". $user['USER_MSISDN'];



            $this->db->insert($this->tbl_users, $user);

            if ($this->db->affected_rows() > 0) {
                $inserted_user = $this->get_user_by_login_name($user['USER_LOGIN_NAME']);
                if ($inserted_user) {
                    $user_id = $inserted_user['ID'];
                    
                    return $user_id;
                }

            } else {
                $this->error_message = 'User add unsuccessful. DB Error.';
                return false;
            }

        } else {
            $this->error_message = 'Invalid parameter.';
            return false;
        }
    }

    public function update_user($user_id, $user)
    {
        $CI = get_instance();
        $CI->load->helper('email');

        if ($user_id > 0) {

            $old_user = $this->get_user($user_id);
            if ( !$old_user ) {
                $this->error_message = 'User not found.';
                return false;
            }

            // validating password
            
            
            
            if ($user['USER_PASSWORD'] != '') {
                
                $user_login_name = $user['USER_LOGIN_NAME'];
                unset($user['USER_LOGIN_NAME']);
                
                
                if (strpos(strtolower($user['USER_PASSWORD']), strtolower($user_login_name))  !== false) {
                    $this->error_message = 'Password should not Contain User Login Name.';
                    return false;
                }

                if ($user['USER_PASSWORD'] != $user['USER_PASSWORD_CONFIRM']) {
                    $this->error_message = 'Password and Confirm Password does not match.';
                    return false;
                } elseif (strlen($user['USER_PASSWORD']) < 8) {
                    $this->error_message = 'Password must have at least 8 (eight) characters.';
                    return false;
                } else {

                    $password = $user['USER_PASSWORD'];

                    preg_match_all ("/[A-Z]/", $password, $matches);
                    $uppercase = count($matches[0]);

                    preg_match_all ("/[a-z]/", $password, $matches);
                    $lowercase = count($matches[0]);

                    preg_match_all ("/\d/i", $password, $matches);
                    $numbers = count($matches[0]);

                    preg_match_all ("/[^A-z0-9]/", $password, $matches);
                    $special = count($matches[0]);

                    if ($uppercase <= 0) {
                        $this->error_message = 'Password should contain at least one Uppercase letter.';
                        return false;
                    } elseif ($lowercase <= 0) {
                        $this->error_message = 'Password should contain at least one Lowercase letter.';
                        return false;
                    } elseif ($numbers <= 0) {
                        $this->error_message = 'Password should contain at least one Numeric character.';
                        return false;
                    } elseif ($special <= 0) {
                        $this->error_message = 'Password should contain at least one Special character.';
                        return false;
                    }

                    $user['USER_PASSWORD'] = md5($user['USER_PASSWORD']);
                    $user['USER_PASSWORD_OLD'] = $old_user['USER_PASSWORD_OLD'];
                }

            } elseif (isset($user['USER_PASSWORD'])) {
                unset($user['USER_PASSWORD']);
            }
            if (isset($user['USER_PASSWORD_CONFIRM'])) {
                unset($user['USER_PASSWORD_CONFIRM']);
            }

            // user email validation
            if (isset($user['USER_EMAIL']) && $user['USER_EMAIL'] != '') {
                // check if email address is valid
                if ( !valid_email($user['USER_EMAIL']) ) {
                    $this->error_message = 'Invalid email address. Please try a different one.';
                    return false;
                }
            }

            // validate mobile number
            if ($user['USER_MSISDN'] == '') {
                $this->error_message = 'Mobile Number is required.';
                return false;
            } elseif ( strlen($user['USER_MSISDN']) != 10 ) {
                $this->error_message = 'Invalid Mobile Number. Please enter ten (10) characters.';
                return false;
            } elseif ( !ctype_digit($user['USER_MSISDN']) ) {
                $this->error_message = 'Invalid Mobile Number. Please enter numeric characters only.';
                return false;
            }
            $user['USER_MSISDN'] = "880". $user['USER_MSISDN'];

           

            if (isset($user['USER_LOGIN_NAME'])) { unset($user['USER_LOGIN_NAME']); } 
           

            // update user
            $this->db->where('id', $user_id);
            $this->db->update($this->tbl_users, $user);
            if ($this->db->affected_rows() > 0) {
               return true;
            } else {
                $this->error_message = 'User not updated. DB Error.';
                return false;
            }

        } else  {
            $this->error_message = 'Invalid id.';
            return false;
        }
    }
    
    public function update_profile($user_id,$data){
         $this->db->where('ID', $user_id);
         return $this->db->update($this->tbl_users, $data);
    }
    
    public function update_success_login_time_and_session($user_id,$user_session_id) {
        /*
        $this->db->where('USER_LOGIN_NAME',$user_id);
        $this->db->set('USER_LAST_SUCCESS_LOGIN_TIME = SYSDATE' );
        $this->db->update($this->tbl_users);
        */
        
        $ip = ''; //$this->input->ip_address();
        
        $this->db->query("UPDATE  ".$this->tbl_users." SET LOGIN_STATUS=1,SESSION_ID='".$user_session_id."', USER_LAST_SUCCESS_LOGIN_TIME = SYSDATE WHERE USER_LOGIN_NAME='".$user_id."'");
        
    }
	
	public function check_user_session_id() 
	{
		
		$this->db->where('ID',$this->session->userdata('Userid'));
        $this->db->select('SESSION_ID');
        $query = $this->db->get($this->tbl_users);
        $data = $query->first_row();

        //var_dump($data->SESSION_ID); var_dump($this->session->userdata('Userid')); var_dump(session_id()); exit;  
        //session_start();

        //echo $this->session->userdata('user_current_session'); exit;
        if($this->session->userdata('session_id')!= $data->SESSION_ID){
            return false; 
        }
        else{
            return true;
        }
		
	}
	
	public function update_logout_time($user_id) { 
        /*
        $this->db->where('USER_LOGIN_NAME',$user_id);
        $this->db->set('USER_LAST_SUCCESS_LOGIN_TIME = SYSDATE' );
        $this->db->update($this->tbl_users);
        */
        
        $ip = ''; //$this->input->ip_address();
        
        $this->db->query("UPDATE  ".$this->tbl_users." SET USER_LAST_LOGOUT_TIME = SYSDATE WHERE USER_LOGIN_NAME='".$user_id."'");
        
    }
    
    public function get_user_login_ip() {
         $user_id = $this->session->userdata('Userid');
         $this->db->select('LAST_ACCESS_IP');
         $this->db->where('ID',$user_id);
         $query = $this->db->get($this->tbl_users);
         
         $data = $query->result_array();
         
         return $data[0]['LAST_ACCESS_IP'];
         
     } 
    
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */