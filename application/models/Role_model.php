<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends CI_Model
{
    //private $tbl_role = 'OFFER_TBL_ROLE';
    //private $tbl_role_menu = 'OFFER_TBL_ROLE_MENU';
    //private $tbl_app_role_privileges = 'OFFER_APP_ROLE_PRIVILEGES';
    //private $tbl_app_users = 'OFFER_APP_USERS';
    //private $tbl_menu = 'OFFER_TBL_MENU';

    private $tbl_role = 'DND_APP_ROLES';
    private $tbl_role_menu = 'DND_ROLE_MENU';
    private $tbl_app_role_privileges = 'OFFER_APP_ROLE_PRIVILEGES';
    private $tbl_app_users = 'OFFER_APP_USERS';
    private $tbl_menu = 'DND_MENU';
    
    
    
    
    
    
    public $error_message = '';

    function __construct()
    {
        parent::__construct();
    }




//
//   public function get_paged_srs($limit, $offset = 0, $rId, $aId, $rspId)
//    {
//
//        //echo  "limit".$limit;
//        //echo  "Offset".$offset;
//
//
//        $result = array();
//        $result['result'] = false;
//        $result['count'] = 0;
//
//        if($rId!='ALL') {
//            $this->db->where('Region', $rId);
//        }
//        if($aId!='ALL')
//        {
//
//            $this->db->where('Area', $aId);
//        }
////        if($rspId!='ALL')
////        {
////
////            $this->db->where('rsp', $rId);
////        }
//
//       // $count1 = $this->db->get($this->tbl_rdo);
//      //  $this->db->query('SELECT * FROM 'TBL_SR_TRACKING_SUMMARY'');
//
//
//        $query = $this->db->get($this->tbl_rdo, $limit, $offset);
//        if ($query->num_rows() > 0) {
//
//            $result['result'] = $query->result_array();
//
//            //echo
//
//           // print_r($result);
//            //die();
//            //echo "TEst"
//           $this->db->from($this->tbl_rdo);
//            // $this->db->where('Region',$rId);
//
//           $result['count']  =  $count1->num_rows();
//         //  $result['count']  = 270;
//        }
//
//        return $result;
//    }

   public  function isVisibleMenu(  $menuId)
   {
       $userid = (int)$this->session->userdata['Userid'];
       $permittedMenu = $this->get_permitted_menu($userid);

       for( $i=0; $i<count($permittedMenu); $i++)
       {
           if($menuId == $permittedMenu[$i]['MENUID'])
           {
                return '';
           }

       }
       return 'hidden="true"';



   }

//    public  function isVisibleParentMenu( $menuIds)
//    {
//        $userid = (int)$this->session->userdata['Userid'];
//        $permittedMenu = $this->get_permitted_menu($userid);
//
////        echo "<pre>";
////        print_r($menuIds);
////        echo "</pre>";
//        //die();
//
//        for($i=0; $i<count($menuIds); $i++){
//            // foreach($menuIds as $menuId) {
//            {
//                $menuId = $menuIds[$i];
////            echo $menuId;
////            die();
//                for( $j=0; $j<count($permittedMenu); $j++)
//                {
//                    // echo $permittedMenu[$j]['MENUID'];
//                    echo $permittedMenu[$j]['MENUID'];
//                    echo $menuId;
//                    if($menuId == $permittedMenu[$j]['MENUID'])
//                    {
//                        echo $permittedMenu[$j]['MENUID'];
//                        echo $menuId;
//                        return '';
//                    }
//                }
//            }
//            die();
//            return 'hidden="true"';
//        }
//    }
    public  function isVisibleParentMenu($menuIds)
    {
        //var_dump($this->session->userdata['PMVD_permitted_menu']); die();
        
        $return = false ; 
        $permittedMenu = $this->session->userdata('PMVD_permitted_menu');
        
       // var_dump($permittedMenu); die();
        
        $new_permitted_menu = array();
        foreach ($permittedMenu as $value) {
                array_push($new_permitted_menu, (int)$value['PRIVILEGE_ID']);
        }
       
        foreach ($menuIds as $current_menu){
         
           $return =  in_array($current_menu, $new_permitted_menu);
           //var_dump($return) ; 
           if($return==TRUE){
               return TRUE;
           }
        }
        return $return;
    }


    public function test()
    {
        $sql ="select   menuid  from ".$this->tbl_role_menu." a , tbl_user  b where a.roleid=b.roleid and  b.userid =93";
        $query = $this->db->query($sql);
        $menuArray = $query->result_array();
        return $menuArray;


    }
  public function get_permitted_menu($userId)
  {
      /*$sql ="select menuid  from tbl_role_menu a , tbl_user  b where a.roleid=b.roleid and  b.userid =".$userId;
      $query = $this->db->query($sql);
      $menuArray = $query->result_array();

      return $menuArray;*/
      //$sql ="select   menuid  from tbl_role_menu a , tbl_user  b where a.roleid=b.roleid and  b.userid =".$userId;
      $sql = "select PRIVILEGE_ID from ".$this->tbl_app_role_privileges." where ROLE_ID = (select ROLE_ID from ".$this->tbl_app_users." where ID =".$userId.")";
        $query = $this->db->query($sql);
      $menuArray = $query->result_array();
      
      //var_dump($menuArray); die();
      
      return $menuArray;

  }

   public function get_menus()
   {


    //   $userid = (int)$this->session->userdata['Userid'];

       $query = $this->db->query("Select * from ".$this->tbl_menu);

//        $query = $this->db->get($this->tbl_region);
       $arrQuery = $query->result_array();
      // $region[]='All';
       for($i=0; $i<count($arrQuery); $i++)
       {
           $menu[$arrQuery[$i]['MENUID']]=$arrQuery[$i]['MENUNAME'];
       }

       return  $menu;
   }


    public function add_role($role)
    {

        if (is_array($role)) {


            return  $this->db->insert($this->tbl_role, $role);

        } else {
            $this->error_message = 'Invalid parameter.';
            return false;
        }
    }


    public function get_role_id($role)
    {
        $query = $this->db->query(" select roleid from ".$this->tbl_role."  where role= '" .$role."'");
        $arrQuery = $query->result_array();
        return  $arrQuery;

    }
    public function get_role_info($roleId)
    {
       $sql = "select Role, Roleid from ".$this->tbl_role."  where roleid= " .$roleId;
       $query = $this->db->query($sql );
       $arrQuery = $query->result_array();
       return  $arrQuery;

    }

    public  function delete_role_menu($roleid)
    {
        $this->db->where('Roleid', $roleid);
        $this->db->delete($this->tbl_role_menu );
    }

    public function add_role_menu($role_menu)
    {
        if (is_array($role_menu)) {


            return  $this->db->insert_batch($this->tbl_role_menu, $role_menu);

        } else {
            $this->error_message = 'Invalid parameter.';
            return false;
        }
    }

   public function get_paged_roles( $limit, $offset = 0, $filter = array())
   {
       $result = array();
       $result['result'] = false;
       $result['count'] = 0;
       $this->db->select('Roleid,Role');
       $this->db->Where('Roleid !=',-1);
       $query = $this->db->get($this->tbl_role);

       if ($query->num_rows() > 0) {

           $result['result'] = $query->result_array();

           $this->db->from($this->tbl_role);
           // $this->db->where('Region',$rId);

           $result['count']  =  $query->num_rows();
           //  $result['count']  = 270;
       }


       return $result;


   }

   public  function get_menus_by_role($roleId)
   {



       $sql = "select   a.menuid , b.menuname    from  tbl_role_menu a  , tbl_menu b where   a.menuid  = b.menuid and a.roleid = ".$roleId;
       $query = $this->db->query($sql );
       $arrQuery = $query->result_array();
       if($arrQuery)
       {
           for($i=0; $i<count($arrQuery); $i++)
           {
               $menus[$arrQuery[$i]['MENUID']]=$arrQuery[$i]['MENUNAME'];
           }
       }
       else{
           $menus = array();

       }


       return  $menus;
   }


}

/* End of file company_model.php */
/* Location: ./application/models/company_model.php */