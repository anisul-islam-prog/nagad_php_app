<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function smppInsert($smpptbl, $where = array()) {
        $result = array();
       
        if ($where) {

            $inID = $this->db->insert($smpptbl, $where);
            if ($this->db->affected_rows() > 0) {
                return array('referenceId' => $where['REFERENCEID'],'status' => 'SUCCESS', 'info' => 'Smpp insertion successful.','smsId' => $where['REQUESTID']);
            } else {
                return array('referenceId' => $where['REFERENCEID'],'status' => 'FAILED', 'info' => 'DB Error! Smpp insertion failed.');
            }
        } else {
            return array('referenceId' => $where['REFERENCEID'],'status' => 'FAILED', 'info' => 'Api parameters missing!');
        }
    }
    
    public function getNextId($prefix,$sms_type) {
        $sql = "";
		if ($sms_type=='USSD')
		{
        if ($prefix == '18' || $prefix == '16') {
                  $sql = "SELECT OUTBOX_ROBI_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
                    
                } else if ($prefix == '17') {
                    $sql = "SELECT OUTBOX_GP_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
                } else if ($prefix == '19') {
                    $sql = "SELECT OUTBOX_API_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
                } else if ($prefix == '15') {
                    $sql = "SELECT OUTBOX_TT_ID_SEQ.NEXTVAL AS NEXTID FROM dual";  
                }
		}

		else 	
		{
			
			  $sql = "SELECT OUTBOX_API_ID_SEQ.NEXTVAL AS NEXTID FROM dual"; 
		}
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	 
    }
    
    public function getNextReqId($prefix,$sms_type) {
         $sql = "";
		 if ($sms_type=='USSD')
		 {
			if ($prefix == '18' || $prefix == '16') {
                  $sql = "SELECT OUTBOX_ROBI_REQID_SEQ.NEXTVAL AS NEXTREQID FROM dual"; 
                    
                } else if ($prefix == '17') {
                    $sql = "SELECT OUTBOX_GP_REQID_SEQ.NEXTVAL AS NEXTREQID FROM dual";
                } else if ($prefix == '19') {
                    $sql = "SELECT OUTBOX_API_REQID_SEQ.NEXTVAL AS NEXTREQID FROM dual";
                } else if ($prefix == '15') {
                    $sql = "SELECT OUTBOX_TT_REQID_SEQ.NEXTVAL AS NEXTREQID FROM dual";
                }
		 }
		else 
		{
			  $sql = "SELECT OUTBOX_API_REQID_SEQ.NEXTVAL AS NEXTREQID FROM dual"; 
		}
  
		
		$query 	= $this->db->query($sql); 
		$data = $query->result_array();
		return $data;   	 
    }

}

?>
