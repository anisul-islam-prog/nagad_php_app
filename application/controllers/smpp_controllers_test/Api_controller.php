<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_controller extends MY_Controller {

    var $current_page = "Sms_management";

    //var $tbl_user_department = "USERS_DEPARTMENT";

    function __construct() {
        parent::__construct();



        $this->load->model('smpp_model_test/Api_model');
    }

    public function index() {
        echo json_encode(array('status' => 'FAILED'));
    }

    function IsNullOrEmptyString($str) {
        return (!isset($str) || trim($str) === '');
    }

    public function outbox_insertion() {
        $dataArray = json_decode(file_get_contents('php://input'), true);

        //$array_length = count($data);

        $response = array();

       

        foreach ($dataArray as $data) {

            //forloop implementation starts
            //$userMenuTable = $this->config->item('UserMenuTable');
            $userName = '';
            $password = '';
            $message = '';
            $priority = 0;
            $msisdn = '';
            $sender = '';
            $refId = '';
            $is_unicode = 0;
            $sms_type = '';
            $prefix = '';
            $number_format_ok = false;

            if ($this->IsNullOrEmptyString($data['referenceId'])) {
                array_push($response,array('status' => 'FAILED', 'info' => 'Reference ID is empty!'));
               continue;
            } else {
                $refId = trim($data['referenceId']);
            }

            if ($this->IsNullOrEmptyString($data['username'])) {
               array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'User name is empty!'));
            
               continue;
            } else {
                $userName = trim($data['username']);
            }


            if ($this->IsNullOrEmptyString($data['password'])) {
                array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Password is empty!'));
               continue;
            } else {
                $password = trim($data['password']);
            }

            if ($userName == 'nagad' && $password == 'N@g@d$Adm!n6') {

                

                if ($this->IsNullOrEmptyString($data['message'])) {
                    array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Message is empty!'));
                    continue;
                } else {
                    $message = trim($data['message']);
                }
                if ($this->IsNullOrEmptyString($data['sender'])) {


                    $sender = 'Nagad';
                } else {
                    $sender = trim($data['sender']);
                }
                if ($this->IsNullOrEmptyString($data['msisdn'])) {
                    array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Msisdn is empty!'));
                    continue;
                } else {
                    $msisdn = trim($data['msisdn']);
                }

                if ($this->IsNullOrEmptyString($data['priority'])) {
                    $priority = 0;
                } else {
                    $priority = (int) trim($data['priority']);
                }

                if ($this->IsNullOrEmptyString($data['txn_channel'])) {
                    $sms_type = 'APP';
                } else {
                    $sms_type = trim($data['txn_channel']);
                }



                if ($this->IsNullOrEmptyString($data['is_unicode'])) {
                    $is_unicode = 0;
                } else {
                    
                } {
                    $is_unicode = (int) trim($data['is_unicode']);
                }

                //detecting outbox table 
                $sms_table = '';
				
				//checking the msisdn count
                if (strlen($msisdn) == 10) {
                        $msisdn = '880' . $msisdn;
                    } else if (strlen($msisdn) == 11) {
                        $msisdn = '88' . $msisdn;
                    } else if (strlen($msisdn) == 13) {
                        $msisdn = $msisdn;
                    } else {
                        array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'MSISDN format is wrong!'));
                    
                        continue;
                }
				
				$prefix = substr($msisdn, 3, 2);
				
				$Api_masking = 'Nagad';
				$Api_masking_TT = 'NAGAD';

                if ($sms_type == 'APP') {
                    $sms_table = 'OUTBOX_API';
					$sender = $Api_masking;
                    $number_format_ok = true;
                } else if($sms_type == 'USSD'){                    

                    //$prefix = substr($msisdn, 3, 2);

                    if ($prefix == '18' || $prefix == '16') {
                        //$sms_table = 'OUTBOX_ROBI';
                        $sms_table = 'OUTBOX_ROBI';

                        $number_format_ok = true;
                    } else if ($prefix == '17') {
                        $sms_table = 'OUTBOX_GP';
                        //$sms_table = 'OUTBOX_API';
						$sender = $Api_masking;
                        $number_format_ok = true;
                    } else if ($prefix == '19') {
                        //$sms_table = 'OUTBOX_BL';
                        $sms_table = 'OUTBOX_API';
						$sender = $Api_masking;
                        $number_format_ok = true;
                    } else if ($prefix == '15') {
                        $sms_table = 'OUTBOX_TT';
                        //$sms_table = 'OUTBOX_API';
			$sender = $Api_masking_TT;
                        $number_format_ok = true;
                    }
                }
                else{
                    $sms_table = 'OUTBOX_API';
					$sender = $Api_masking;
                    $number_format_ok = true;
                }
                if ($number_format_ok == true) {
                    // detecting outbox table finished

                    $tblId = $this->Api_model->getNextId($prefix,$sms_type);

                    $tblReqId = $this->Api_model->getNextReqId($prefix,$sms_type);

                    $requestId = '';
                    $date = new DateTime(); //this returns the current date time
                    $currentDateInString = $date->format('YmdHis');
                    $requestId .= $currentDateInString . '' . $tblReqId[0]['NEXTREQID'];

					if (is_null($tblId))
					{
						continue;
					}						
                    $postData = array('ID' => $tblId[0]['NEXTID'], 'SENDER' => $sender, 'RECEIVER' => $msisdn, 'MESSAGE' => $message, 'PRIORITY' => $priority, 'IS_UNICODE' => $is_unicode, 'REQUESTID' => $requestId, 'REFERENCEID' => $refId);
                    $getData = $this->Api_model->smppInsert($sms_table, $postData);
                   array_push($response,$getData);
                } else {
                    array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Wrong MSISDN format!'));
                    continue;
                }
            } else {
                array_push($response,array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Authentication failed!'));
                continue;
            }

            //ends
        }

        echo responsJson($response);
        die();
    }

}

?>