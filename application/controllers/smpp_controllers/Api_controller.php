<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_controller extends MY_Controller
{

    var $current_page = "Sms_management";
    var $ENABLE_GP_USSD = false;
    var $ENABLE_GP_API  = false;
    var $ENABLE_ROBI_USSD  = false;
    var $ENABLE_ROBI_API   = false;
    var $ENABLE_BL_USSD   = false;
    var $ENABLE_BL_API   = false;
    var $ENABLE_TT_USSD   = false;
    var $ENABLE_TT_API   = false;
    var $NODE_ID          = 1;

    //var $tbl_user_department = "USERS_DEPARTMENT";

    function __construct()
    {
        parent::__construct();



        $this->load->model('smpp_model/Api_model');
    }

    public function index()
    {
        echo json_encode(array('status' => 'FAILED'));
    }

    function IsNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }

    public function outbox_insertion()
    {
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
            $priority_filter = 99;
            $number_format_ok = false;

            if ($this->IsNullOrEmptyString($data['referenceId'])) {
                array_push($response, array('status' => 'FAILED', 'info' => 'Reference ID is empty!'));
                continue;
            } else {
                $refId = trim($data['referenceId']);
            }

            if ($this->IsNullOrEmptyString($data['username'])) {
                array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'User name is empty!'));

                continue;
            } else {
                $userName = trim($data['username']);
            }


            if ($this->IsNullOrEmptyString($data['password'])) {
                array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Password is empty!'));
                continue;
            } else {
                $password = trim($data['password']);
            }

            if ($userName == 'nagad' && $password == 'N@g@d$Adm!n6') {



                if ($this->IsNullOrEmptyString($data['message'])) {
                    array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Message is empty!'));
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
                    array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Msisdn is empty!'));
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
                    $is_unicode = (int) trim($data['is_unicode']);
                }

                //detecting outbox table 
                $sms_table = '';
                $brand_name = '';
                $sms_channel = '';

                //checking the msisdn count
                if (strlen($msisdn) == 10) {
                    $msisdn = '880' . $msisdn;
                } else if (strlen($msisdn) == 11) {
                    $msisdn = '88' . $msisdn;
                } else if (strlen($msisdn) == 13) {
                    $msisdn = $msisdn;
                } else {
                    array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'MSISDN format is wrong!'));

                    continue;
                }

                /*****************  MNP START ******************/
                if (isset($data['operator'])) {
                    if ($this->IsNullOrEmptyString($data['operator'])) {
                        $prefix = substr($msisdn, 3, 2);
                    } else {
                        $mno_code = trim($data['operator']);
                        if ($mno_code == 'ROBI')
                            $prefix = '18';
                        else if ($mno_code == 'GP')
                            $prefix = '17';
                        else if ($mno_code == 'BLINK')
                            $prefix = '19';
                        else if ($mno_code == 'TTALK')
                            $prefix = '15';
                        else
                            $prefix = substr($msisdn, 3, 2);
                    }
                } else {
                    $prefix = substr($msisdn, 3, 2);
                }
                /****************** MNP END   *******************/

                $Api_masking = 'Nagad';
                $Api_masking_TT = 'Nagad';

                /*for BULK API ONLY */
                $ROBI_API_MASKING = 'Nagad';
                $GP_API_MASKING = 'NAGAD';
                $BL_API_MASKING = 'Nagad';
                /*for BULK API ONLY */

                if ($priority != $priority_filter) {
                    if ($prefix == '18' || $prefix == '16') {
                        if ($this->ENABLE_ROBI_API && $this->ENABLE_ROBI_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_ROBI_API';
                                $sender = $ROBI_API_MASKING;
                            } else {
                                $sms_table = 'OUTBOX_ROBI';
                            }
                        } else if ($this->ENABLE_ROBI_API) {
                            $sms_table = 'OUTBOX_ROBI_API';
                            $sender = $ROBI_API_MASKING;
                        } else if ($this->ENABLE_ROBI_USSD) {
                            $sms_table = 'OUTBOX_ROBI';
                        } else {
                            $sms_table = 'OUTBOX_API';
                            $sender = $Api_masking;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '17' || $prefix == '13') {
                        if ($this->ENABLE_GP_API && $this->ENABLE_GP_USSD) {
                            if ($sms_type == 'APP') {
                                $msisdn = substr($msisdn, 2, 11);
                                if ($is_unicode == 0) {
                                    $is_unicode = 1;
                                } else {
                                    $is_unicode = 3;
                                }
                                $sms_table = 'OUTBOX_GP_API';
                                $sender = $GP_API_MASKING;
                            } else {
                                $message = str_replace("\n", ' ', $message);
                                $sms_table = 'OUTBOX_GP';
                                $sender = 'NAGAD';
                            }
                        } else if ($this->ENABLE_GP_API) {
                            $msisdn = substr($msisdn, 2, 11);
                            if ($is_unicode == 0) {
                                $is_unicode = 1;
                            } else {
                                $is_unicode = 3;
                            }
                            $sms_table = 'OUTBOX_GP_API';
                            $sender = $GP_API_MASKING;
                        } else if ($this->ENABLE_GP_USSD) {
                            $message = str_replace("\n", ' ', $message);
                            $sms_table = 'OUTBOX_GP';
                            $sender = 'NAGAD';
                        } else {
                            $sms_table = 'OUTBOX_API';
                            $sender = $Api_masking;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '19' || $prefix == '14') {
                        if ($this->ENABLE_BL_API && $this->ENABLE_BL_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_BL_API';
                                $sender = $BL_API_MASKING;
                            } else {
                                $sms_table = 'OUTBOX_BL';
                                $sender = 'NAGAD';
                            }
                        } else if ($this->ENABLE_BL_API) {
                            $sms_table = 'OUTBOX_BL_API';
                            $sender = $BL_API_MASKING;
                        } else if ($this->ENABLE_BL_USSD) {
                            $sms_table = 'OUTBOX_BL';
                            $sender = 'NAGAD';
                        } else {
                            $sms_table = 'OUTBOX_API';
                            $sender = $Api_masking;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '15') {
                        if ($this->ENABLE_TT_API && $this->ENABLE_TT_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_TT_API';
                                $sender = $Api_masking_TT;
                            } else {
                                $sms_table = 'OUTBOX_TT';
                                $sender = 'NAGAD';
                            }
                        } else if ($this->ENABLE_TT_API) {
                            $sms_table = 'OUTBOX_TT_API';
                            $sender = $Api_masking_TT;
                        } else if ($this->ENABLE_TT_USSD) {
                            $sms_table = 'OUTBOX_TT';
                            $sender = 'NAGAD';
                        } else {
                            $sms_table = 'OUTBOX_API';
                            $sender = $Api_masking;
                        }
                        $number_format_ok = true;
                    }
                } else {
                    //if priority == priority_filter 
                    if ($prefix == '18' || $prefix == '16') {
                        $brand_name = 'ROBI';
                        if ($this->ENABLE_ROBI_API && $this->ENABLE_ROBI_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = $ROBI_API_MASKING;
                                $sms_channel = $sms_type;
                            } else {
                                $sms_table = 'OUTBOX_PENDING';
                                $sms_channel = $sms_type;
                            }
                        } else if ($this->ENABLE_ROBI_API) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $ROBI_API_MASKING;
                            $sms_channel = $sms_type;
                        } else if ($this->ENABLE_ROBI_USSD) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sms_channel = $sms_type;
                        } else {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $Api_masking;
                            $sms_channel = $sms_type;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '17' || $prefix == '13') {
                        $brand_name = 'GP';
                        if ($this->ENABLE_GP_API && $this->ENABLE_GP_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_channel = $sms_type;
                                $msisdn = substr($msisdn, 2, 11);
                                if ($is_unicode == 0) {
                                    $is_unicode = 1;
                                } else {
                                    $is_unicode = 3;
                                }
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = $GP_API_MASKING;
                            } else {
                                $message = str_replace("\n", ' ', $message);
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = 'NAGAD';
                                $sms_channel = $sms_type;
                            }
                        } else if ($this->ENABLE_GP_API) {
                            $msisdn = substr($msisdn, 2, 11);
                            if ($is_unicode == 0) {
                                $is_unicode = 1;
                            } else {
                                $is_unicode = 3;
                            }
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $GP_API_MASKING;
                            $sms_channel = $sms_type;
                        } else if ($this->ENABLE_GP_USSD) {
                            $message = str_replace("\n", ' ', $message);
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = 'NAGAD';
                            $sms_channel = $sms_type;
                        } else {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $Api_masking;
                            $sms_channel = $sms_type;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '19' || $prefix == '14') {
                        $brand_name = 'BLINK';
                        if ($this->ENABLE_BL_API && $this->ENABLE_BL_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = $BL_API_MASKING;
                                $sms_channel = $sms_type;
                            } else {
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = 'NAGAD';
                                $sms_channel = $sms_type;
                            }
                        } else if ($this->ENABLE_BL_API) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $BL_API_MASKING;
                            $sms_channel = $sms_type;
                        } else if ($this->ENABLE_BL_USSD) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = 'NAGAD';
                            $sms_channel = $sms_type;
                        } else {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $Api_masking;
                            $sms_channel = $sms_type;
                        }
                        $number_format_ok = true;
                    } else if ($prefix == '15') {
                        $brand_name = 'TTALK';
                        if ($this->ENABLE_TT_API && $this->ENABLE_TT_USSD) {
                            if ($sms_type == 'APP') {
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = $Api_masking_TT;
                                $sms_channel = $sms_type;
                            } else {
                                $sms_table = 'OUTBOX_PENDING';
                                $sender = 'NAGAD';
                                $sms_channel = $sms_type;
                            }
                        } else if ($this->ENABLE_TT_API) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $Api_masking_TT;
                            $sms_channel = $sms_type;
                        } else if ($this->ENABLE_TT_USSD) {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = 'NAGAD';
                            $sms_channel = $sms_type;
                        } else {
                            $sms_table = 'OUTBOX_PENDING';
                            $sender = $Api_masking;
                            $sms_channel = $sms_type;
                        }
                        $number_format_ok = true;
                    }
                }

                if ($number_format_ok == true) {
                    // detecting outbox table finished

                    $tblId = $this->Api_model->getNextId($prefix, $sms_table);

                    $tblReqId = $this->Api_model->getNextReqId($prefix, $sms_table);

                    $requestId = '';
                    $date = new DateTime(); //this returns the current date time
                    $currentDateInString = $date->format('YmdHis');
                    $requestId .= $currentDateInString . '' . $tblReqId[0]['NEXTREQID'];

                    if (is_null($tblId)) {
                        continue;
                    }
                    if ($sms_table == 'OUTBOX_PENDING') {
                        $postData = array('ID' => $tblId[0]['NEXTID'], 'SENDER' => $sender, 'RECEIVER' => $msisdn, 'MESSAGE' => $message, 'PRIORITY' => $priority, 'IS_UNICODE' => $is_unicode, 'REQUESTID' => $requestId,'BRAND_NAME' => $brand_name, 'SMS_CHANNEL'=> $sms_channel, 'REFERENCEID' => $refId, 'NODEID' => $this->NODE_ID);
                    } else {
                        $postData = array('ID' => $tblId[0]['NEXTID'], 'SENDER' => $sender, 'RECEIVER' => $msisdn, 'MESSAGE' => $message, 'PRIORITY' => $priority, 'IS_UNICODE' => $is_unicode, 'REQUESTID' => $requestId, 'REFERENCEID' => $refId, 'NODEID' => $this->NODE_ID);
                    } //echo $sms_table;
                    //print_r($postData);die();
                    $getData = $this->Api_model->smppInsert($sms_table, $postData);
                    array_push($response, $getData);
                } else {
                    array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Wrong MSISDN format!'));
                    continue;
                }
            } else {
                array_push($response, array('referenceId' => $refId, 'status' => 'FAILED', 'info' => 'Authentication failed!'));
                continue;
            }

            //ends
        }

        echo responsJson($response);
        die();
    }
}
