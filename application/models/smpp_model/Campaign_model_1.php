<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaign_model extends CI_Model {

    public $error_message       = '';
    private $tbl_dnd_temp       = 'DND_TMP_DND_TABLE';
    private $tbl_dnd_local      = 'DND_LOCAL';
    private $tbl_dnd_remote     = 'DND_REMOTE';
    private $tbl_dnd_all        = 'VIEW_DND_ALL';
    
    private $tbl_obd_temp       = 'DND_TMP_OBD_TABLE';
    private $tbl_obd_local      = 'DND_OBD_LOCAL';
    
    private $tbl_base_temp      = 'DND_TMP_BASE_TABLE_UPLOAD';
    private $tbl_base           = 'DND_NUMBER_BASE';
    private $tbl_base_file      = 'DND_NUMBER_FILE';
    private $tbl_base_robi      = 'ROBI_NUMBER_BASE';
    private $tbl_base_file_robi = 'ROBI_NUMBER_FILE';
    private $tbl_obd_base_file  = 'DND_OBD_NUMBER_FILE';
    
    private $tbl_department     = 'DND_USERS_DEPARTMENT';
    private $tbl_bucket         = 'DND_BUCKET';
    private $tbl_bucket_mapping = 'DND_BUCKET_DEPARTMENTS';
    private $tbl_masking        = 'DND_MASKING';
    private $tbl_campaign       = 'DND_CAMPAIGN';
    private $tbl_campaign_robi  = 'ROBI_CAMPAIGN';
    private $tbl_obd_campaign   = 'DND_CAMPAIGN_OBD';
	private $tbl_target_base   = 'DND_CAMPAIGN_TARGET_MSISDN';
	private $tbl_target_base_robi   = 'ROBI_CAMPAIGN_TARGET_MSISDN';
	private $tbl_target_base_history   = 'DND_CAMPAIGN_TARGET_HISTORY';
	private $tbl_robi_base_history   = 'ROBI_CAMPAIGN_TARGET_HISTORY';
	private $tbl_pause_history   = 'DND_CAMPAIGN_PAUSE_HISTORY';
	private $tbl_pause_history_robi   = 'DND_CAMPAIGN_PAUSE_HISTORY';
	private $tbl_airtel_outbox = 'DND_OUTBOX';
	private $tbl_robi_outbox = 'ROBI_OUTBOX';
	
	private $tbl_user_report  = 'DND_REPORT_USR';
	private $tbl_user_report_customer  = 'DND_REPORT_USR_CUSTOMER';

    

    function __construct() {
        parent::__construct();
    }

    public function getDepartmentDetailss() {
        $this->db->select('d.*, b.ID as BUCKET_ID, b.BUCKET_NAME');
        $this->db->join($this->tbl_bucket_mapping.' bm','bm.DEPARTMENT_ID=d.ID','LEFT');
        $this->db->join($this->tbl_bucket.' b','bm.BUCKET_ID=b.ID','LEFT');
        $query = $this->db->get($this->tbl_department.' d');
        $data = $query->result_array();
        return $data;
    }// getDepartmentDetails
	
	  public function getDepartmentIDAndNameByBrand($brandname) {
		$this->db->where('BRAND_NAME',$brandname);   
        $this->db->select('ID, DEPARTMENT_NAME');       
        $query = $this->db->get($this->tbl_department);
        $data = $query->result_array();
        return $data;
    }
	
	 public function getCampaignCount($dept_id) {
		 $this->db->where('IS_SUBMITTED',1);
		 $this->db->where('DEPARTMENT_ID',$dept_id);    
        $this->db->select('COUNT( UNIQUE CAMPAIGN_ID ) AS CAMPAIGN_COUNT');       
        $query = $this->db->get($this->tbl_target_base_history);
        $data = $query->result_array();
        return $data;
    }
	
	
	public function getCampaignCountRobi($dept_id) {
		$this->db->where('IS_SUBMITTED',1);
		 $this->db->where('DEPARTMENT_ID',$dept_id);    
        $this->db->select('COUNT( UNIQUE CAMPAIGN_ID ) AS CAMPAIGN_COUNT');     
        $query = $this->db->get($this->tbl_robi_base_history);
        $data = $query->result_array();
        return $data;
    }
	
	
		public function getCampaignCountRobiByDate() {
		 $sql="select count (CAMPAIGN_COUNT) AS COUNT,BROADCASTED_DATE from (select  count(unique CAMPAIGN_ID) AS CAMPAIGN_COUNT,TRUNC(DELIVERY_TIME) AS BROADCASTED_DATE  from ROBI_OUTBOX GROUP BY CAMPAIGN_ID,DELIVERY_TIME) GROUP BY BROADCASTED_DATE ORDER BY BROADCASTED_DATE";      
        $query = $this->db->query($sql);
        $data = $query->result_array(); 
        return $data;
    }
	
	public function getCampaignCountAirtelByDate() {
		 $sql="select count (CAMPAIGN_COUNT) AS COUNT,BROADCASTED_DATE from (select  count(unique CAMPAIGN_ID) AS CAMPAIGN_COUNT,TRUNC(DELIVERY_TIME) AS BROADCASTED_DATE  from DND_OUTBOX_STATUS GROUP BY CAMPAIGN_ID,DELIVERY_TIME) GROUP BY BROADCASTED_DATE ORDER BY BROADCASTED_DATE";      
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignCountByDateLastDay($dept_id) {
		 $this->db->where('DEPARTMENT_ID',$dept_id);
		//$this->db->where('BROADCAST_DATE',$dept_id);		 
        $this->db->select('COUNT( UNIQUE CAMPAIGN_ID ) AS CAMPAIGN_COUNT');       
        $query = $this->db->get($this->tbl_target_base_history);
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignCountByDate($dept_id) {
		 
        $sql = " select distinct BROADCAST_DATE AS x, count(campaign_id)  AS y
from ( select UNIQUE CAMPAIGN_ID,TRUNC(DATABASESUBMITTIME) AS BROADCAST_DATE from ROBI_OUTBOX1_CURRENT where TRUNC(DATABASESUBMITTIME) BETWEEN trunc(sysdate) - (to_number(to_char(sysdate,'DD')) - 1) AND TRUNC(SYSDATE -1) GROUP BY DATABASESUBMITTIME,CAMPAIGN_ID ORDER BY BROADCAST_DATE
) group by BROADCAST_DATE order by BROADCAST_DATE " ; 
		$query 	= $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignCountHistoryByDate($dept_id,$duration) {
		
		
		 
        $sql = " select distinct  L.BROADCAST_DATE AS x, count(campaign_id)  AS y
from ( select UNIQUE CAMPAIGN_ID,TRUNC(DELIVERY_TIME) AS BROADCAST_DATE from ROBI_OUTBOX_STATUS,DND_CAMPAIGN    GROUP BY DATABASESUBMITTIME,CAMPAIGN_ID ORDER BY BROADCAST_DATE
)L left join DND_CAMPAIGN C ON L.CAMPAIGN_ID=C.ID WHERE C.DEPARTMENT_ID=".$dept_id." group by L.BROADCAST_DATE,C.DEPARTMENT_ID order by L.BROADCAST_DATE" ; 
		$query 	= $this->db->query($sql);
        $data = $query->result_array(); 
        return $data;
    }
	
	public function getCampaignCountHistoryByDateAirtel($dept_id,$duration) { 
		 
        $sql = " select distinct  L.BROADCAST_DATE AS x, count(campaign_id)  AS y
from ( select UNIQUE CAMPAIGN_ID,TRUNC(DELIVERY_TIME) AS BROADCAST_DATE from DND_OUTBOX_STATUS,DND_CAMPAIGN    GROUP BY DELIVERY_TIME,CAMPAIGN_ID ORDER BY BROADCAST_DATE
)L left join DND_CAMPAIGN C ON L.CAMPAIGN_ID=C.ID WHERE C.DEPARTMENT_ID=".$dept_id." group by L.BROADCAST_DATE,C.DEPARTMENT_ID order by L.BROADCAST_DATE" ; 
		$query 	= $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignIdsByDeptId($dept_id) {
		 $this->db->where('DEPARTMENT_ID',$dept_id);
		$this->db->distinct();
        $this->db->select('CAMPAIGN_ID');        
        $query = $this->db->get($this->tbl_target_base_history);
        $data = $query->result_array();
        return $data;
    }
	
	
	public function get_obd_base_according_to_duration($camp_id,$limit) {
		

	$sql = " SELECT MSISDN FROM DND_OBD_NUMBER_BASE WHERE file_id IN (SELECT BASE_ID FROM DND_OBD_CAMPAIGN_BASE WHERE CAMPAIGN_ID = ".$camp_id.") and  ROWNUM <= ".$limit." ORDER BY MSISDN ";  
	$query 	= $this->db->query($sql); 
        $data = $query->result_array();  
        return $data;
    }
	
	
	public function getCampaignIdsByDeptIdAndDuration($dept_id,$duration,$brandname) {
		if($brandname=='robi'){
			
			if($duration=='weekly')
			
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM ROBI_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id."  ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(C.BROADCAST_DATE)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM ROBI_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id." ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(C.BROADCAST_DATE)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM ROBI_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id." ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)= TRUNC(SYSDATE-1) ";  
		
		}
		else
		{
			if($duration=='weekly')
			
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM DND_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id."  ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(C.BROADCAST_DATE)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM DND_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id." ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(C.BROADCAST_DATE)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT D.CAMPAIGN_ID FROM (SELECT DISTINCT CAMPAIGN_ID FROM DND_CAMPAIGN_TARGET_HISTORY WHERE DEPARTMENT_ID =".$dept_id." ) D LEFT JOIN DND_CAMPAIGN C ON D.CAMPAIGN_ID=C.ID where  TRUNC(C.BROADCAST_DATE)= TRUNC(SYSDATE-1) ";  
		
			
		}
		
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   
		
		 
    }
	
	
	
	
	public function getBroadcastedCampaignIdHistory() {
		 
		$this->db->distinct();		
        $this->db->select('CAMPAIGN_ID');        
        $query = $this->db->get($this->tbl_target_base_history);
        $data = $query->result_array();
        return $data;
    }
	
	public function getBroadcastedCampaignIdHistoryAirtel($duration) {  
		
		if($duration=='weekly')
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM DND_OUTBOX_STATUS where  TRUNC(DELIVERY_TIME)< TRUNC(SYSDATE) AND  TRUNC(DELIVERY_TIME)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM DND_OUTBOX_STATUS where  TRUNC(DELIVERY_TIME)< TRUNC(SYSDATE) AND  TRUNC(DELIVERY_TIME)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM DND_OUTBOX_STATUS where  TRUNC(DELIVERY_TIME)= TRUNC(SYSDATE-1) ";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   
    }
	
	
	
	
	public function getBroadcastedCampaignIdHistoryRobi($duration) {
		
		if($duration=='weekly')
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM ROBI_OUTBOX where  TRUNC(DATABASESUBMITTIME)< TRUNC(SYSDATE) AND  TRUNC(DATABASESUBMITTIME)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM ROBI_OUTBOX where  TRUNC(DATABASESUBMITTIME)< TRUNC(SYSDATE) AND  TRUNC(DATABASESUBMITTIME)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT DISTINCT CAMPAIGN_ID FROM ROBI_OUTBOX where  TRUNC(DATABASESUBMITTIME)= TRUNC(SYSDATE-1) ";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   		
    }
	
	
	
	public function getUserReportTotalBroadcastInfoBySegment($brandname,$duration){
		if($duration=='weekly')
			$sql = " SELECT * FROM DND_REPORT_USR where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT * FROM DND_REPORT_USR where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT * FROM DND_REPORT_USR where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)= TRUNC(SYSDATE-1) ";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	
		
		
	}
	
	
	
	public function insertCampaign($data){
		
        $this->db->insert('DND_CAMPAIGN',$data);       
       if ($this->db->affected_rows() > 0) {
            return $this->db->affected_rows();
        } else {
            $this->error_message = 'add unsuccessful. DB Error.';
            return false;
        }
		
	}

	public function insertRobiCampaign($data){
		
        $this->db->insert('ROBI_CAMPAIGN',$data);       
       if ($this->db->affected_rows() > 0) {
            return $this->db->affected_rows();
        } else {
            $this->error_message = 'add unsuccessful. DB Error.';
            return false;
        }
		
	}
	
	function getNextId() {
  $sql = "SELECT DND_CAMPAIGN_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	 
}

	function getRobiNextId() {
  $sql = "SELECT ROBI_CAMPAIGN_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	 
}

	function getNextCriticalId() {
  $sql = "SELECT DND_CRITICAL_CAMPAIGN_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	 
}
	
	public function getCampaignId($campaign_name,$broadcast_date,$brandname){
		$this->db->where('BROADCAST_DATE',$broadcast_date);
		$this->db->where('BRAND_NAME',$brandname);
		$this->db->where('BRAND_NAME',$brandname);
        $this->db->select('COUNT(*) AS SMS_COUNT');       
        $query = $this->db->get('DND_OUTBOX_STATUS');
        $data = $query->result_array();
        return $data;
		
	}
	
	public function getUserReportUniqueCustomerBySegment($brandname,$duration){
		if($duration=='weekly')
			$sql = " SELECT * FROM DND_REPORT_USR_CUSTOMER where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-7) ";  
		else if($duration=='monthly')
			$sql = " SELECT * FROM DND_REPORT_USR_CUSTOMER where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-30) ";  
		else
			$sql = " SELECT * FROM DND_REPORT_USR_CUSTOMER where OPERATOR_NAME='".$brandname. "' AND TRUNC(BROADCAST_DATE)= TRUNC(SYSDATE-1) ";  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	
		
		
	}
	
	public function getUserReportSum($brandname,$duration){
		if($duration=='weekly')
			$sql= "SELECT SEGMENT_TYPE,SEGMENT_NAME , SUM(UPLOAD_COUNT) AS UPLOAD_COUNT, SUM(GEN_COUNT) AS GEN_COUNT, SUM(DELIVERED_COUNT) AS DELIVERED_COUNT FROM DND_REPORT_USR WHERE OPERATOR_NAME = '".$brandname."' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-7) GROUP BY SEGMENT_TYPE,SEGMENT_NAME";
		else if($duration=='monthly')
			$sql= "SELECT SEGMENT_TYPE,SEGMENT_NAME , SUM(UPLOAD_COUNT) AS UPLOAD_COUNT, SUM(GEN_COUNT) AS GEN_COUNT, SUM(DELIVERED_COUNT) AS DELIVERED_COUNT FROM DND_REPORT_USR WHERE OPERATOR_NAME = '".$brandname."' AND TRUNC(BROADCAST_DATE)< TRUNC(SYSDATE) AND  TRUNC(BROADCAST_DATE)>=TRUNC(SYSDATE-30) GROUP BY SEGMENT_TYPE,SEGMENT_NAME";
		else
			$sql= "SELECT SEGMENT_TYPE,SEGMENT_NAME , SUM(UPLOAD_COUNT) AS UPLOAD_COUNT, SUM(GEN_COUNT) AS GEN_COUNT, SUM(DELIVERED_COUNT) AS DELIVERED_COUNT FROM DND_REPORT_USR WHERE OPERATOR_NAME = '".$brandname."' AND TRUNC(BROADCAST_DATE)= TRUNC(SYSDATE-1) GROUP BY SEGMENT_TYPE,SEGMENT_NAME";

			  
        $query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	
	} 
	
	public function getSMSCount($campaign_ids) {
		$this->db->where_in('CAMPAIGN_ID',$campaign_ids);
		$this->db->where('STATUS',1);
        $this->db->select('COUNT(*) AS SMS_COUNT');       
        $query = $this->db->get('DND_OUTBOX_STATUS');
        $data = $query->result_array();
        return $data;
    }
	
	
	
	public function getSMSCountByCampId($campaign_id) {
		$this->db->where('CAMPAIGN_ID',$campaign_id);
		$this->db->where('STATUS',1);
        $this->db->select('COUNT(*) AS SMS_COUNT');       
        $query = $this->db->get('DND_OUTBOX_STATUS');
        $data = $query->result_array();
        return $data;
    }
	
	public function getUniqueCountFromMultipleBase($base_ids){
		$this->db->where_in('FILE_ID',$base_ids);
		$this->db->distinct();
        $this->db->select('COUNT( distinct MSISDN) AS UNIQUE_COUNT');        
        $query = $this->db->get($this->tbl_base);
        $data = $query->result_array();
        return $data;
	}

	public function getUniqueCountFromMultipleBaseRobi($base_ids){
		$this->db->where_in('FILE_ID',$base_ids);
		$this->db->distinct();
        $this->db->select('COUNT( distinct MSISDN) AS UNIQUE_COUNT');        
        $query = $this->db->get($this->tbl_base_robi);
        $data = $query->result_array();
        return $data;
	}
	

    public function getCampaignCreatePermission($start="",$ends="") {
		$que="SELECT ".$start." ,".$ends."  FROM DND_CUTOUT_TIME";

		$query = $this->db->query($que);
        //var_dump($query);
		$data = $query->result_array();

		
		$current_date      =date("Ymd");
		$current_time      =date("Hi"); //24 hour formate 
		$current_date_time =date("YmdHi"); //24 hour formate

		if(isset($data[0][$start]))
		$cam_start_time = date("G:i A", strtotime($data[0][$start])) ;
		else
			return false;
		if(isset($data[0][$start]))
		$cam_end_time   = date("G:i A", strtotime($data[0][$ends]));
		else
			return false;
		//"02:30 PM";
		
		//"3:30 PM";
		$get_start_am   = explode(" ",$cam_start_time);
		$get_end_am     = explode(" ",$cam_end_time);
		
		$get_start_hour = explode(":",$get_start_am[0]);
		$get_end_hour   = explode(":",$get_end_am[0]);
		
		$new_cam_start_time=0; //0830;
		$new_cam_end_time=0;  //0930;

		if("PM" == $get_start_am[1]){ //CHECK AM/PM START TIME
			$time = (int)($get_start_hour[0]+12);
			$new_cam_start_time = $time.$get_start_hour[1];
			
		}else{
			$new_cam_start_time=$get_start_hour[0].$get_start_hour[1];
			
		}
		
		if("PM" == $get_end_am[1]){ //CHECK AM/PM ENDS TIME
			$time = (int)($get_end_hour[0]+12);
			$new_cam_end_time = $time.$get_end_hour[1];
			//echo "change time </br>".$time.$get_end_hour[1];
		}else{
			$new_cam_end_time=$get_end_hour[0].$get_end_hour[1];
			//echo "not Change time </br>";
		}

        //var_dump($current_time.''.$new_cam_start_time.' '.$new_cam_end_time);die;
		if( $new_cam_start_time < $current_time && $current_time < $new_cam_end_time ) {
			return true;
		}else{

			 return false;
		}
		
		
    }// getCampaignCreatePermission
	
	
    public function select_join_campaign_new(){
       // echo $datas; die();
        $this->db->select('TA.*,TB.DEPARTMENT_NAME as DNAME');
        $this->db->from('DND_BUCKET_DEPARTMENTS TA');
        $this->db->join('DND_USERS_DEPARTMENT TB', 'TA.DEPARTMENT_ID = TB.ID', 'left');
        $this->db->where('TB.BRAND_NAME','airtel');
        $query = $this->db->get();
		//VAR_DUMP($query->result_array()); exit;
        return $query->result_array();
    } // END OF select_join_department_where
	
	 public function get_departments_robi(){
       // echo $datas; die();
        $this->db->select('ID AS DEPARTMENT_ID,DEPARTMENT_NAME as DNAME'); 
        
        $this->db->where('BRAND_NAME','robi');
        $query = $this->db->get('DND_USERS_DEPARTMENT');
		//VAR_DUMP($query->result_array()); exit;
        return $query->result_array();
    }
	
	public function get_campaigns_robi_where($dept_id){
       // echo $datas; die();
        
        $this->db->where('DEPARTMENT_ID',$dept_id);
        $this->db->where('BRAND_NAME','robi');
		$this->db->select('ID AS DEPARTMENT_ID,DEPARTMENT_NAME as DNAME');
        $query = $this->db->get('DND_USERS_DEPARTMENT');
		//VAR_DUMP($query->result_array()); exit;
        return $query->result_array();
    }
	
	public function select_join_campaign_new_by_where($dept_id){
       
	   $this->db->where('TA.DEPARTMENT_ID',$dept_id);
        $this->db->select('TA.*,TB.DEPARTMENT_NAME as DNAME');
        $this->db->from('DND_BUCKET_DEPARTMENTS TA');
        $this->db->join('DND_USERS_DEPARTMENT TB', 'TA.DEPARTMENT_ID = TB.ID', 'left');
        //$this->db->where('TA.ID',$dats);
        $query = $this->db->get();
		//VAR_DUMP($query->result_array()); exit;
        return $query->result_array();
    } // END OF select_join_department_where
	
	public function get_todays_campaign($brandname){
       
        $this->db->where('TRUNC(TA.BROADCAST_DATE)','TRUNC(sysdate)',false);
		$this->db->where('TA.IS_GOVT_INFO',0);
		$this->db->where('TA.BRAND_NAME',$brandname);
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TB.PRIORITY as dept_priority,TF.PRIORITY as BUCKET_PRIORITY, TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file.' TD','TA.BASE_ID=TD.ID','LEFT');
		$this->db->join($this->tbl_bucket_mapping.' TE','TB.ID=TE.DEPARTMENT_ID','LEFT');
		$this->db->join($this->tbl_bucket_mapping.' TE','TB.ID=TE.DEPARTMENT_ID','LEFT');
		$this->db->join($this->tbl_bucket.' TF','TE.BUCKET_ID=TF.ID','LEFT');
		$this->db->order_by('TF.PRIORITY','ASC');
		$this->db->order_by('TB.PRIORITY','ASC');		
		$this->db->order_by('TA.PRIORITY','ASC');
		
        $query = $this->db->get($this->tbl_campaign.' TA');
        $data = $query->result_array();
		
		//echo $this->db->last_query(); exit;
		
        return $data;
    }



	// END OF select_join_department_where
	
	
	
		public function get_todays_campaign_robi(){
       
        $this->db->where('TRUNC(TA.BROADCAST_DATE)','TRUNC(sysdate)',false); 
		$this->db->where('TA.IS_GOVT_INFO',0);		
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TB.PRIORITY as dept_priority,TF.PRIORITY as BUCKET_PRIORITY, TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file_robi.' TD','TA.BASE_ID=TD.ID','LEFT');		
		$this->db->join($this->tbl_bucket_mapping.' TE','TB.ID=TE.DEPARTMENT_ID','LEFT');
		$this->db->join($this->tbl_bucket.' TF','TE.BUCKET_ID=TF.ID','LEFT');
		$this->db->order_by('TF.PRIORITY','ASC');
		$this->db->order_by('TB.PRIORITY','ASC');		
		$this->db->order_by('TA.PRIORITY','ASC');
		
        $query = $this->db->get($this->tbl_campaign_robi.' TA');
        $data = $query->result_array();
		
		//echo $this->db->last_query(); exit;
		
        return $data;
    } // END OF select_join_department_where
	
	
	
	
	public function getAirtelCampaignsByMSISDN($msisdn){ 
		$sql = " SELECT  CAMPAIGN_ID FROM DND_OUTBOX_STATUS WHERE STATUS='1' AND RECEIVER='".$msisdn."'";
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   
	
	}
	
	public function getRobiCampaignsByMSISDN($msisdn){ 
		$sql = " SELECT  CAMPAIGN_ID FROM ROBI_OUTBOX_STATUS WHERE STATUS='1' AND RECEIVER='".$msisdn."'";  
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   
	
	}
	
	
	public function get_approved_campaign($brandname){ 
		$sql = " SELECT  CAMPAIGN_ID,PRIORITY FROM DND_CAMPAIGN_SELECTED TA WHERE IS_SUBMITTED='0' AND TRUNC(CREATETIME)=TRUNC(SYSDATE) AND BRAND_NAME='".$brandname."'";
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;
        	
	}
	
	public function get_approved_campaign_robi(){ 
		$sql = " SELECT  CAMPAIGN_ID,PRIORITY FROM ROBI_CAMPAIGN_SELECTED TA WHERE IS_SUBMITTED='0' AND TRUNC(CREATETIME)=TRUNC(SYSDATE) ";
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;
        	
	}
	
	public function get_campaigns_name($camp_id){
		$sql = "  SELECT  CAMPAIGN_NAME FROM  DND_CAMPAIGN  WHERE  ID =".$camp_id." AND ROWNUM<2" ; 
		$query 	= $this->db->query($sql);
		$data = $query->result_array();
		return $data;	

        
	
	}
	
	public function get_campaigns_info($camp_id){
		$this->db->where('TA.ID',$camp_id);
		$this->db->select('TA.*, TB.DEPARTMENT_NAME as DNAME,TC.MASKING_NAME as MSNAME, GET_GEN_BASE(TA.ID) AS GENERATED_BASE');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');     
				
        $query = $this->db->get($this->tbl_campaign.' TA');
        $data = $query->result_array();
		
		
		return $data;	

        
	
	}
	
	
	
	
	public function get_campaigns_info_for_msisdn($camp_id){
		$this->db->where('TA.ID',$camp_id);
		$this->db->select('TA.*, TB.DEPARTMENT_NAME as DNAME,TC.MASKING_NAME as MSNAME');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');     
				
        $query = $this->db->get($this->tbl_campaign.' TA');
        $data = $query->result_array();
		
		
		return $data;	

        
	
	}
	
	public function get_campaigns_info_robi($camp_id){
		$this->db->where('TA.ID',$camp_id);
		$this->db->select('TA.*, TB.DEPARTMENT_NAME as DNAME,TC.MASKING_NAME as MSNAME, GET_GEN_BASE_ROBI(TA.ID) AS GENERATED_BASE');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');     
				
        $query = $this->db->get($this->tbl_campaign_robi.' TA');
        $data = $query->result_array();
		
		
		return $data;	

        
	
	}
	

	
	
	public function get_broadcasted_campaign_base(){ 
		$sql = " SELECT  CAMPAIGN_ID,  GET_CAMPAIGN_NAME(CAMPAIGN_ID) AS CAMPAIGN_NAME, GET_DEPT_NAME(CAMPAIGN_ID) AS DNAME, GET_BASE_FILE_NAME(BASE_ID) AS BASE_FILE_NAME, GET_BASE_COUNT(BASE_ID) AS BASE_COUNT FROM DND_CAMPAIGN_SELECTED TA  WHERE IS_SUBMITTED='1' " ;
		$query 	= $this->db->query($sql);
		$data = $query->result_array();
		return $data;	
        
	}
	
	public function get_broadcasted_campaign(){ 
		//$sql = " SELECT  CAMPAIGN_ID,  GET_CAMPAIGN_NAME(CAMPAIGN_ID) AS CAMPAIGN_NAME, PRIORITY,GET_IS_PAUSED_STATUS(CAMPAIGN_ID) AS IS_PAUSED FROM DND_CAMPAIGN_SELECTED TA  WHERE IS_SUBMITTED='1' " ;
		//$query 	= $this->db->query($sql);
		//$data = $query->result_array();
		
	
		
		$this->db->where('TA.BRAND_NAME','airtel');
		$this->db->where('TA.IS_SUBMITTED',1); 
		

		$this->db->select("TA.CAMPAIGN_ID,TB.CAMPAIGN_TEXT, TB.CAMPAIGN_NAME,TB.IS_PAUSED,TB.BRAND_NAME,TC.DEPARTMENT_NAME AS DNAME, TB.BASE_COUNT, TD.MASKING_NAME, GET_GEN_BASE(TA.CAMPAIGN_ID) AS 
		GENERATED_BASE,GET_DELIVERED_COUNT_AIRTEL(TA.CAMPAIGN_ID) AS DELIVERED_COUNT,GET_ALL_DELI_COUNT_AIRTEL(TA.CAMPAIGN_ID) AS ALL_COUNT
		"); 
		
		$this->db->join($this->tbl_campaign.' TB','TA.CAMPAIGN_ID=TB.ID','LEFT');
		$this->db->join($this->tbl_department.' TC','TB.DEPARTMENT_ID=TC.ID','LEFT');
		$this->db->join($this->tbl_masking.' TD','TB.MASKING_ID=TD.ID','LEFT');
		
        $query = $this->db->get('DND_CAMPAIGN_SELECTED TA');
        $data = $query->result_array();
		return $data;	
        
	}
	
	public function get_broadcasted_campaign_robi(){ 
		
		$this->db->where('TA.IS_SUBMITTED',1);
		$this->db->select("TA.CAMPAIGN_ID,TB.CAMPAIGN_TEXT, TB.CAMPAIGN_NAME,TB.IS_PAUSED,TB.BRAND_NAME,TC.DEPARTMENT_NAME AS DNAME, TB.BASE_COUNT, TD.MASKING_NAME, GET_GEN_BASE_ROBI(TA.CAMPAIGN_ID)
		AS GENERATED_BASE,GET_DELIVERED_COUNT_ROBI(TA.CAMPAIGN_ID) AS DELIVERED_COUNT,GET_ALL_DELI_COUNT_ROBI(TA.CAMPAIGN_ID) AS ALL_COUNT"); 		
		$this->db->join($this->tbl_campaign_robi.' TB','TA.CAMPAIGN_ID=TB.ID','LEFT');
		$this->db->join($this->tbl_department.' TC','TB.DEPARTMENT_ID=TC.ID','LEFT'); 
		$this->db->join($this->tbl_masking.' TD','TB.MASKING_ID=TD.ID','LEFT');
		
        $query = $this->db->get('ROBI_CAMPAIGN_SELECTED TA');  
        $data = $query->result_array();
		return $data;	        
	}
	
	public function get_campaign_target_file($cmp_id){
		$this->db->select('MSISDN');
		$this->db->where('CAMPAIGN_ID',$cmp_id);
		$query = $this->db->get($this->tbl_target_base);
		$data = $query->result_array();
		return $data;
		
	} 
	
	
	public function update_download_status($cmp_id){
		$sql = " INSERT INTO DND_DOWNLOAD_BASE (CAMPAIGN_ID,DOWNLOAD_TYPE,STATUS)  VALUES(".$cmp_id.",1,4)"; 
		$query 	= $this->db->query($sql);		
		return true;	
		
	}
	
	public function update_download_status_robi($cmp_id){
		$sql = " INSERT INTO DND_DOWNLOAD_BASE_ROBI (CAMPAIGN_ID,DOWNLOAD_TYPE,STATUS)  VALUES(".$cmp_id.",1,4)"; 
		$query 	= $this->db->query($sql);		
		return true;	
		
	}
	
	public function get_campaign_target_file_robi($cmp_id){
		$this->db->select('MSISDN');
		$this->db->where('CAMPAIGN_ID',$cmp_id);
		$query = $this->db->get($this->tbl_target_base_robi);
		$data = $query->result_array();
		return $data;
		
	}
	
	
	
    
    public function getMasking($brandname) {
        $this->db->where('IS_ACTIVE',1);
		$this->db->where('BRAND_NAME',$brandname);
        $query = $this->db->get($this->tbl_masking);
        $data = $query->result_array();
        return $data;
    }// getMasking
    
    public function getPriority($department_id,$broadcast_date,$brandname) {
        // GETTING BUCKET ID
        /*$this->db->where('DEPARTMENT_ID',$department_id);
        $b_query = $this->db->get($this->tbl_bucket_mapping);
        $b_data = $b_query->first_row();
        $bucket_id = $b_data->BUCKET_ID;
        */
        // GETTING PRIORITY
        $this->db->select('coalesce (max(PRIORITY)+1,1) AS PRIORITY');
        $this->db->where('DEPARTMENT_ID',$department_id);
        $this->db->where('TRUNC(BROADCAST_DATE)',$broadcast_date);
        if($brandname == 'robi'){
        	$br_query = $this->db->get($this->tbl_campaign_robi);
        }elseif ($brandname == 'airtel') {
        	$br_query = $this->db->get($this->tbl_campaign);
        }
        $br_data = $br_query->first_row();
		
        //echo $this->db->last_query(); die();
        return $br_data->PRIORITY;
    } //getPriority
	
	
	public function getMaxSMSCountWithMSISDN($camp_ids)
	{		  
		$ids=implode(',',$camp_ids);
		$sql = "SELECT  MSISDN, MAX_SMS  FROM (SELECT O.RECEIVER AS MSISDN,  COUNT(  O.RECEIVER) AS MAX_SMS FROM DND_OUTBOX_STATUS O WHERE O.STATUS=1 AND  O.CAMPAIGN_ID IN ($ids)   GROUP BY O.RECEIVER   ORDER BY MAX_SMS DESC)   where rownum<2" ;
		$query 	= $this->db->query($sql);		
        $data = $query->result_array();
        return $data;	
	}
    
     public function getAllBase() {
		$this->db->where('CAMPAIGN_TYPE','sms');
		$this->db->where('STATUS','1');		
        $this->db->select('ID, FILE_NAME');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_base_file);
        $data = $query->result_array();
        return $data;
     }
	 
	  public function getAllBaseByDeptId($dept_id) {
		$this->db->where(array('CAMPAIGN_TYPE'=>'sms','DEPARTMENT_ID'=>$dept_id));
		$this->db->where('STATUS','1');		
        $this->db->select('ID, FILE_NAME ,TOTAL_NUMBER');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_base_file);
        $data = $query->result_array();
		//var_dump($this->db->last_query());die;
        return $data;
     }

     public function getAllBaseByDeptId_robi($dept_id) {
		$this->db->where(array('CAMPAIGN_TYPE'=>'sms','DEPARTMENT_ID'=>$dept_id));
		$this->db->where('STATUS','1');		
        $this->db->select('ID, FILE_NAME ,TOTAL_NUMBER');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_base_file_robi);
        $data = $query->result_array();
		//var_dump($this->db->last_query());die;
        return $data;
     }
	
	 public function getAllDynamicBase() {
		$this->db->where('CAMPAIGN_TYPE','dynamic');
		$this->db->where('STATUS','1');		
        $this->db->select('ID, FILE_NAME');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_base_file);
        $data = $query->result_array();
        return $data; 
     }
	 
	  public function getAllDynamicBaseByDeptId($dept_id) {
		$this->db->where(array('CAMPAIGN_TYPE'=>'dynamic','DEPARTMENT_ID'=>$dept_id));
		$this->db->where('STATUS','1');		
        $this->db->select('ID, FILE_NAME');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_base_file);
        $data = $query->result_array();
        return $data;
     }

    public function getAllObdBase() {
        $this->db->select('ID, FILE_NAME');
        $this->db->order_by('ID','DESC');
        $query = $this->db->get($this->tbl_obd_base_file);
        $data = $query->result_array();
        return $data;
    }

     public function searchCampaignlists($s_data) {
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_campaign.' TA');
        $s_data2 = array('TA.CAMPAIGN_NAME'=>'Welcome');
        $this->db->where($s_data2);
        $data = $query->result_array();
        return $data;
    }

     public function getCampaignlist($arrayName = array()) {
        if(count($arrayName)){
            //if($value['searchSelect']){
               // $this->db->where('',$value['searchSelect']);
           // }
            if($arrayName['txtSearch']){
                $this->db->where('TA.'.$arrayName['searchSelect'],$arrayName['txtSearch']);
            }
            if($arrayName['search_date']){
                $this->db->where('TA.BROADCAST_DATE',$arrayName['search_date']);
           }
        }
        $this->db->where('TA.IS_TEST_CHECK',0);
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_campaign.' TA');
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignlistRobi() {
      
        $this->db->where('TA.IS_TEST_CHECK',0);
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file_robi.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_campaign_robi.' TA');
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignlistByDept($arrayInfo = array()) { 
       
        $this->db->where($arrayInfo);
     
        $this->db->where('TA.IS_TEST_CHECK',0);
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_campaign.' TA');
        $data = $query->result_array();
        return $data;
    }
	
	public function getCampaignlistByDeptRobi($arrayInfo = array()) { 
       
        $this->db->where($arrayInfo);
     
        $this->db->where('TA.IS_TEST_CHECK',0);
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_base_file_robi.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_campaign_robi.' TA');
        $data = $query->result_array();
        return $data;
    }
	
	public function download_stopped_base() {
        $this->db->select('RECEIVER');
        
        $query = $this->db->get($this->tbl_pause_history);
       
        return $query->result_array();
    }
	
	public function download_stopped_base_robi() {
        $this->db->select('RECEIVER');
        
        $query = $this->db->get($this->tbl_pause_history_robi);
       
        return $query->result_array();
    }
	
	public function getObdCampaignlist() {
        $this->db->select('TA.*, TB.DEPARTMENT_NAME as dname,TC.MASKING_NAME as msname,TD.FILE_NAME as bname');
        $this->db->join($this->tbl_department.' TB','TA.DEPARTMENT_ID=TB.ID','LEFT');
        $this->db->join($this->tbl_masking.' TC','TA.MASKING_ID=TC.ID','LEFT');
        $this->db->join($this->tbl_obd_base_file.' TD','TA.BASE_ID=TD.ID','LEFT');
        $query = $this->db->get($this->tbl_obd_campaign.' TA');
        $data = $query->result_array();
        return $data;
    }
	
    public function getCampaignByDepartmentAndDate($department_id,$broadcast_date,$priority,$old_priority) {
            /*
            $sql = "SELECT PRIORITY FROM DND_CAMPAIGN WHERE TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."' ORDER BY PRIORITY DESC " ; // AND PRIORITY = '".$priority."'
            $query 	= $this->db->query($sql);
            $data = $query->result_array();
            //print_r_pre($data);

            $data_last_value = $query->first_row();
            //print_r_pre($data_last_value->PRIORITY);

            $sql_1 = "SELECT * FROM (SELECT PRIORITY FROM DND_CAMPAIGN WHERE TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'ORDER BY PRIORITY DESC ) WHERE ROWNUM = 1" ;
            $query_1 	= $this->db->query($sql_1);
            $data_1 = $query_1->result_array();
            $last_priority = $data_1[0]['PRIORITY'];
            */
           if ($old_priority > $priority) {
               $sql = "UPDATE DND_CAMPAIGN SET PRIORITY= CASE WHEN (PRIORITY = ".$old_priority." ) THEN ".$priority." ELSE  PRIORITY+1 END WHERE PRIORITY >= '".$priority."' and PRIORITY <= '".$old_priority."' AND TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'";    
           } else {
               $sql = "UPDATE DND_CAMPAIGN SET PRIORITY= CASE WHEN (PRIORITY = ".$old_priority." ) THEN ".$priority." ELSE  PRIORITY-1 END WHERE PRIORITY >= '".$old_priority."' and PRIORITY <= '".$priority."' AND TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'";
           }
            
        

        $query 	= $this->db->query($sql);

        //print_r_pre($last_priority,"Last Priority And UPDate prio ".$priority);

       // echo $this->db->last_query(); die();

        return true;
    }
	
	
	 public function getCampaignByDepartmentAndDateRobi($department_id,$broadcast_date,$priority,$old_priority) {
            /*
            $sql = "SELECT PRIORITY FROM DND_CAMPAIGN WHERE TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."' ORDER BY PRIORITY DESC " ; // AND PRIORITY = '".$priority."'
            $query 	= $this->db->query($sql);
            $data = $query->result_array();
            //print_r_pre($data);

            $data_last_value = $query->first_row();
            //print_r_pre($data_last_value->PRIORITY);

            $sql_1 = "SELECT * FROM (SELECT PRIORITY FROM DND_CAMPAIGN WHERE TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'ORDER BY PRIORITY DESC ) WHERE ROWNUM = 1" ;
            $query_1 	= $this->db->query($sql_1);
            $data_1 = $query_1->result_array();
            $last_priority = $data_1[0]['PRIORITY'];
            */
           if ($old_priority > $priority) {
               $sql = "UPDATE ROBI_CAMPAIGN SET PRIORITY= CASE WHEN (PRIORITY = ".$old_priority." ) THEN ".$priority." ELSE  PRIORITY+1 END WHERE PRIORITY >= '".$priority."' and PRIORITY <= '".$old_priority."' AND TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'";    
           } else {
               $sql = "UPDATE ROBI_CAMPAIGN SET PRIORITY= CASE WHEN (PRIORITY = ".$old_priority." ) THEN ".$priority." ELSE  PRIORITY-1 END WHERE PRIORITY >= '".$old_priority."' and PRIORITY <= '".$priority."' AND TRUNC(BROADCAST_DATE) = '".$broadcast_date."' AND DEPARTMENT_ID = '".$department_id."'";
           }
            
        

        $query 	= $this->db->query($sql);

        //print_r_pre($last_priority,"Last Priority And UPDate prio ".$priority);

       // echo $this->db->last_query(); die();

        return true;
    }
    
	public function update_sequecely($campaign_id,$department_id,$broadcast_date,$lastID,$data)
    {
		
    	//$sql = "UPDATE DND_CAMPAIGN set PRIORITY= CASE WHEN (PRIORITY = ".$lastID." AND ID = ".$campaign_id." ) THEN ".$data." ELSE  PRIORITY+1 END where PRIORITY >= '".$data."' and PRIORITY <= '".$lastID."' ";
    	$sql = "SELECT * FROM DND_CAMPAIGN WHERE ID = '".$campaign_id."' AND DEPARTMENT_ID = '".$department_id."'" ;
		$query 	= $this->db->query($sql);

		echo $this->db->last_query(); die();

        return true;
    }
    
}

?>