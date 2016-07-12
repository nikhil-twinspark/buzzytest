<?php

/**
 *  This file for find out the details of point redeemed and point awarded details for practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller for find out the details of point redeemed and point awarded details for practice.
 */
class PointsHistoryController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField', 'ClinicUser', 'Transaction', 'GlobalRedeem', 'Refer', 'Doctor', 'Staff', 'TrainingVideo', 'ClinicNotification');

    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $layout = 'staffLayout';

    /**
     * fetching default value for clinic and store it to session.
     * @return type
     */
    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        //set next free card number for default search
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $trainingvideo = $this->TrainingVideo->find('all');
        //fetch all training video and set it to session to show at top of the page
        $this->Session->write('staff.trainingvideo', $trainingvideo);
        if (isset($sessionstaff['clinic_id'])) {
            $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
            $options3['fields'] = array('Clinic.analytic_code', 'Clinic.log_time', 'Clinic.lead_log', 'Clinic.id');
            $ClientCredentials = $this->Clinic->find('first', $options3);

            if (isset($ClientCredentials)) {
                //store analytic code to session
                $this->Session->write('staff.analytic_code', $ClientCredentials['Clinic']['analytic_code']);
            }
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }

    /**
     * default index page for this module.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            if ($this->request->data['date_range_picker']) {
                $dateRange = explode(' - ', $this->request->data['date_range_picker']);
                $curdate = $dateRange[1]. ' 23:59:59';
                $date = $dateRange[0]. ' 00:00:00';
                $data=array('date_range_picker'=>$date.' - '.$curdate,'transaction_type'=>$this->request->data['transaction_type']);
                $totalamount = $this->Api->getTotalPoints($data, $sessionstaff['clinic_id']);
                $this->set('totalamount', $totalamount);
                $Patients = $this->Api->getPointhistory($data, $sessionstaff['clinic_id']);
                $transaction_array = array();
                $this->set('transaction_array', $transaction_array);
                $this->set('search_result', $data);
                $i == 0;
                if (!empty($Patients)) {

                    foreach ($Patients as $val) {
                        $transaction_array[$i]['name'] = $val['first_name'] . ' ' . $val['last_name'];
                        if ($val['card_number'] == '') {
                            $card = 'BuzzyDoc';
                        } else {
                            $card = $val['card_number'];
                        }
                        $transaction_array[$i]['card_number'] = $card;
                        if ($val['authorization'] == '') {
                            $val['authorization'] = 'Manual';
                        }
                        $transaction_array[$i]['description'] = $val['authorization'];
                        if ($this->request->data['transaction_type'] == 'Y') {
                            $transaction_array[$i]['points'] = ($val['amount']) * -1;
                            $transaction_array[$i]['points_dol'] = ($val['amount'] / 50) * -1;
                        } else {
                            $transaction_array[$i]['points'] = $val['amount'];
                            $transaction_array[$i]['points_dol'] = $val['amount'] / 50;
                        }
                        $transaction_array[$i]['date'] = $val['date'];

                        $given = array();
                        $givenbyname = '';
                        if ($val['staff_id'] != '' || $val['staff_id'] != 0) {
                            $given = $this->Staff->find('first', array(
                                'conditions' => array(
                                    'Staff.id' => $val['staff_id']
                                )
                            ));
                            if (!empty($given)) {
                                $givenbyname = $given['Staff']['staff_id'];
                            }
                        }
                        if (empty($given)) {
                            $given = $this->Doctor->find('first', array(
                                'conditions' => array(
                                    'Doctor.id' => $val['doctor_id']
                                )
                            ));
                            if (!empty($given)) {
                                $givenbyname = $given['Doctor']['first_name'] . ' ' . $given['Doctor']['last_name'];
                            }
                        }

                        if ($givenbyname == '') {
                            $givenbyname = 'Autoassign';
                        }
                        $transaction_array[$i]['givenby'] = $givenbyname;

                        $i++;
                    }
                    $this->set('transaction_array', $transaction_array);
                    $this->set('date_range', $this->request->data['date_range_picker']);
                    $this->set('type', $this->request->data['transaction_type']);
                } else {
                    $this->set('date_range', $this->request->data['date_range_picker']);
                    $this->set('type', $this->request->data['transaction_type']);
                    $this->Session->setFlash('No Entries found in this date range.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Please select Date.', 'default', array(), 'bad');
            }
        }
    }

    /**
     *  for download xls report for point details.
     * @return type xls report
     */
    public function exportUserpoints() {
        //print_r($this->request->data);die;
        $sessionstaff = $this->Session->read('staff');
        $data = array('date_range_picker' => $this->request->data['date_range'], 'transaction_type' => $this->request->data['type']);
        $Patients = $this->Api->getPointhistory($data, $sessionstaff['clinic_id']);
        $totalamount = $this->Api->getTotalPoints($data, $sessionstaff['clinic_id']);
        $output = ob_clean();
        $csv_terminated = "\n";
        $csv_separator = ",";
        $csv_enclosed = '"';
        $csv_escaped = "\\";
        $schema_insert = "";
        $out = '';
        $field_name = array();
        $field_name[] = "Patient Name";
        $field_name[] = "Card Number";
        if ($this->request->data['type'] == 'Y') {
            $field_name[] = "Promotion";
        } else {
            $field_name[] = "Description";
        }
        $field_name[] = "Points";
        $field_name[] = "Amount ($)";
        $field_name[] = "Date";
        $field_name[] = "Given By";
        if ($this->request->data['type'] == 'Y') {
            $first = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes('Total Points Redeemed')) . $csv_enclosed;
        } else {
            $first = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes('Total Points Issued')) . $csv_enclosed;
        }
        $schema_insert .= $first;
        $schema_insert .= $csv_separator;
        $schema_insert .= $totalamount . ' (' . ($totalamount / 50) . ' $)';
        $schema_insert .= $csv_separator;
        $out .= $schema_insert;
        $out .= $csv_terminated;
        $schema_insert = '';
        if ($this->request->data['type'] == 'Y') {
            $schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes('Total Prizes Redeemed')) . $csv_enclosed;
            ;
            $schema_insert .= $csv_separator;
            $schema_insert .= count($Patients);
            $schema_insert .= $csv_separator;
            $out .= $schema_insert;
            $out .= $csv_terminated;
            $schema_insert = '';
        }
        $out .= $schema_insert;
        $out .= $csv_terminated;
        $schema_insert = '';
        for ($a = 0; $a < count($field_name); $a++) {
            $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes($field_name[$a])) . $csv_enclosed;
            $schema_insert .= $l;
            $schema_insert .= $csv_separator;
        }
        $out .= $schema_insert;
        $out .= $csv_terminated;

        $out .= '';
        $out .= $csv_terminated;
        $schema_insert = '';
        if (!empty($Patients)) {
            foreach ($Patients as $val) {
                $given = array();
                $givenbyname = '';
                if ($val['staff_id'] != '' || $val['staff_id'] != 0) {
                    $given = $this->Staff->find('first', array(
                        'conditions' => array(
                            'Staff.id' => $val['staff_id']
                        )
                    ));
                    if (!empty($given)) {
                        $givenbyname = $given['Staff']['staff_id'];
                    }
                }
                if (empty($given)) {
                    $given = $this->Doctor->find('first', array(
                        'conditions' => array(
                            'Doctor.id' => $val['doctor_id']
                        )
                    ));
                    if (!empty($given)) {
                        $givenbyname = $given['Doctor']['first_name'] . ' ' . $given['Doctor']['last_name'];
                    }
                }

                if ($givenbyname == '') {
                    $givenbyname = 'Autoassign';
                }
                $schema_insert = '';
                $answer = '';
                if ($val['card_number'] == '') {
                    $card = 'BuzzyDoc';
                } else {
                    $card = $val['card_number'];
                }
                $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val['first_name'] . ' ' . $val['last_name']) . $csv_enclosed;
                $schema_insert .= $csv_separator;
                $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $card) . $csv_enclosed;
                $schema_insert .= $csv_separator;
                $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val['authorization']) . $csv_enclosed;
                $schema_insert .= $csv_separator;
                if ($this->request->data['type'] == 'Y') {
                    $schema_insert .= $csv_enclosed .
                            str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, ($val['amount']) * -1) . $csv_enclosed;
                    $schema_insert .= $csv_separator;
                    $schema_insert .= $csv_enclosed .
                            str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, ($val['amount'] / 50) * -1) . $csv_enclosed;
                    $schema_insert .= $csv_separator;
                } else {
                    $schema_insert .= $csv_enclosed .
                            str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val['amount']) . $csv_enclosed;
                    $schema_insert .= $csv_separator;
                    $schema_insert .= $csv_enclosed .
                            str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val['amount'] / 50) . $csv_enclosed;
                    $schema_insert .= $csv_separator;
                }
                $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val['date']) . $csv_enclosed;
                $schema_insert .= $csv_separator;
                $schema_insert .= $csv_enclosed .
                        str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $givenbyname) . $csv_enclosed;
                $schema_insert .= $csv_separator;

                $schema_insert .= $csv_separator;
                $out .= $schema_insert;
                $out .= $csv_terminated;
            }
            $output = $out;
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Description: File Transfer");
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=PointsGivenDetails" . date("Y-m-d-H-i-s") . ".xlsx;");
            header("Content-Transfer-Encoding: binary");
            echo $output;
            die;
        } else {
            $this->Session->setFlash('No data found!', 'default', array(), 'bad');
            return $this->redirect('/PointsHistory/index/');
        }
    }

}

?>
