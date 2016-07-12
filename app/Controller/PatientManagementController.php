<?php

/**
 *  This file for accessing all data of patient like transaction details,patient profile details,staff giving points to patient, redemption by staff,patient badge,progress tracker etc.
 *  Here we fetch the all details related patient.
 *  Getting the list of all transaction details.
 *  Currently in which acclerated reward program level.
 *  All treatment plan details with progress tracker.
 *  Patient's interval plan with unlimited visit plans.
 *  Patient profile details.
 *  Patient's local badge.
 *  Screan for staff to assign points and start treatment plan for patient.
 *  At the right side show all details related to patient like (Total buzzydoc and legacy points,current treatment plan progress,current certificate earn,Current Accelerated progrom level,option for staff to end the treatmnet plan etc.)
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'php-bandwidth-master/source/Catapult');

/**
 *  This controller for accessing all data of patient like transaction details,patient profile details,staff giving points to patient, redemption by staff,patient badge,progress tracker etc.
 *  Here we fetch the all details related patient.
 *  Getting the list of all transaction details.
 *  Currently in which acclerated reward program level.
 *  All treatment plan details with progress tracker.
 *  Patient profile details.
 *  Patient's local badge.
 *  Screan for staff to assign points and start treatment plan for patient.
 *  At the right side show all details related to patient like (Total buzzydoc and legacy points,current treatment plan progress,current certificate earn,Current Accelerated progrom level,option for staff to end the treatmnet plan etc.)
 */
class PatientManagementController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array(
        'Html',
        'Form',
        'Session'
    );

    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array(
        'Session',
        'Api'
    );

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array(
        'Promotion',
        'Clinic',
        'user',
        'User',
        'City',
        'State',
        'Transaction',
        'TransactionDeleteLog',
        'ClinicUser',
        'ProfileField',
        'ProfileFieldUser',
        'Notification',
        'CardNumber',
        'UnregTransaction',
        'Refer',
        'UsersBadge',
        'Badge',
        'Invoice',
        'Doctor',
        'Staff',
        'PaymentDetail',
        'GlobalRedeem',
        'RedemptionDetail',
        'LevelupPromotion',
        'UpperLevelSetting',
        'PhaseDistribution',
        'UserAssignedTreatment',
        'UserPerfectVisit',
        'TreatmentSetting',
        'AccessStaff',
        'ProductService',
        'RedemptionDetail',
        'CouponAvail',
        'TrainingVideo',
        'WatchList',
        'MilestoneReward',
        'CardLog',
        'RssFeed',
        'RateReview',
        'ClinicLocation',
        'ClinicNotification'
    );

    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $layout = 'staffLayout';
    protected $_clinicId = null;
    protected $_treatmentSettings = array();
    protected $_badgeId = array();
    protected $_userId = null;
    protected $_badgeMessages = array();
    protected $_upperLevelSettingid;
    protected $_phaseDistribution;
    protected $_phaseVisits = array();
    protected $_phaseBadges = array();
    protected $_phasePoints = array();
    protected $_phaseBadgeId = array();
    protected $_isBonus = null;

    /**
     * @return the $_phaseDistribution
     */
    protected function getPhaseDistribution() {
        return $this->_phaseDistribution;
    }

    /**
     * @param field_type $_phaseDistribution
     */
    protected function setPhaseDistribution($_phaseDistribution) {
        if (empty($_phaseDistribution) && !empty($this->_upperLevelSettingid)) {
            $phase_distribution = $this->requestAction('/LevelupPromotions/getPhaseDistribution', array(
                'pass' => array(
                    $this->_upperLevelSettingid
                )
            ));
            if ($phase_distribution) {
                $_phaseDistribution = $phase_distribution[$this->_upperLevelSettingid]['phase_distribution'];
            }
        }
        $this->_phaseDistribution = $_phaseDistribution;
    }

    /**
     * @return the $_upperLevelSettingid
     */
    protected function getUpperLevelSettingid() {
        return $this->_upperLevelSettingid;
    }

    /**
     * @param field_type $_upperLevelSettingid
     */
    protected function setUpperLevelSettingid($_upperLevelSettingid) {
        $this->_upperLevelSettingid = $_upperLevelSettingid;
        return;
    }

    /**
     * @return the $_userId
     */
    protected function getUserId() {
        return $this->_userId;
    }

    /**
     * @param field_type $_userId
     */
    protected function setUserId($_userId) {
        $this->_userId = $_userId;
        return;
    }

    /**
     * fetching default value for clinic and store it to session.
     * @return type
     */
    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        $rss = $this->RssFeed->find('all');
        $this->Session->write('staff.rssFeedsCount', count($rss));
        $this->Session->write('staff.rssFeeds', $rss);
        //get next free card number for auto assign.
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $trainingvideo = $this->TrainingVideo->find('all');
        //get all training video and store in session for show at header.
        $this->Session->write('staff.trainingvideo', $trainingvideo);
        if (isset($sessionstaff['customer_info']['User']['id'])) {
            $this->setUserId($sessionstaff['customer_info']['User']['id']);
        }
        $this->_clinicId = $sessionstaff['clinic_id'];
        if (isset($sessionstaff['clinic_id'])) {

            //adding a 100 new card to clinic when card balance is 0
            $getbalncecard['conditions'] = array('CardNumber.clinic_id' => $sessionstaff['clinic_id'], 'CardNumber.status' => 1);
            $cardbalcheck = $this->CardNumber->find('first', $getbalncecard);

            if (empty($cardbalcheck)) {
                $getlastcard['conditions'] = array('CardNumber.clinic_id' => $sessionstaff['clinic_id']);
                $getlastcard['order'] = array('CardNumber.card_number desc');
                $cardlast = $this->CardNumber->find('first', $getlastcard);

                $cardlogarr['CardLog']['clinic_id'] = $sessionstaff['clinic_id'];
                $cardlogarr['CardLog']['no_of_card'] = 100;
                $cardlogarr['CardLog']['range_from'] = $cardlast['CardNumber']['card_number'] + 1;
                $cardlogarr['CardLog']['range_to'] = $cardlast['CardNumber']['card_number'] + 100;
                $cardlogarr['CardLog']['log_date'] = date('Y-m-d H:i:s');

                $this->CardLog->create();
                if ($this->CardLog->save($cardlogarr)) {

                    for ($i = $cardlogarr['CardLog']['range_from']; $i <= $cardlogarr['CardLog']['range_to']; $i++) {
                        $options1['conditions'] = array('CardNumber.clinic_id' => $sessionstaff['clinic_id'], 'CardNumber.card_number' => $i);
                        $cardcheck = $this->CardNumber->find('first', $options1);
                        if (empty($cardcheck)) {
                            $this->CardNumber->create();
                            $carddata['CardNumber'] = array('clinic_id' => $sessionstaff['clinic_id'], 'card_number' => $i, 'status' => 1);
                            $this->CardNumber->save($carddata);
                        }
                    }
                }
            }
            $paydetchk['conditions'] = array(
                'PaymentDetail.clinic_id' => $sessionstaff['clinic_id']
            );
            $getpayemntdetailschk = $this->PaymentDetail->find('first', $paydetchk);
            $options3['conditions'] = array(
                'Clinic.id' => $sessionstaff['clinic_id']
            );
            $options3['fields'] = array(
                'Clinic.analytic_code',
                'Clinic.log_time',
                'Clinic.lead_log',
                'Clinic.notify_log',
                'Clinic.id',
                'Clinic.is_buzzydoc',
                'Clinic.google_review_page_url'
            );
            $ClientCredentials = $this->Clinic->find('first', $options3);

            if (isset($ClientCredentials)) {
                //get the access data for pratice.
                $staffaceess = $this->Api->accessstaff($sessionstaff['clinic_id']);
                $this->Session->write('staff.staffaccess', $staffaceess);
                $this->Session->write('staff.default_google_page', $ClientCredentials['Clinic']['google_review_page_url']);
                $treatment = array();
                //getting the list of all treatmnet plan for pratice.
                if ($ClientCredentials['Clinic']['is_buzzydoc'] == 1) {
                    $optionstretment['conditions'] = array(
                        'UpperLevelSetting.clinic_id' => $sessionstaff['clinic_id'],
                        'UpperLevelSetting.status' => 1, 'UpperLevelSetting.interval' => $sessionstaff['staffaccess']['AccessStaff']['interval']
                    );
                    $optionstretment['fields'] = array(
                        'UpperLevelSetting.id',
                        'UpperLevelSetting.treatment_name'
                    );
                    $treatment = $this->UpperLevelSetting->find('all', $optionstretment);
                }
                $this->Session->write('staff.Treatments', $treatment);
                //store analytic code to session
                $this->Session->write('staff.analytic_code', $ClientCredentials['Clinic']['analytic_code']);
                $refchk = 0;
            }
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }
        $this->layout = $this->layout;
    }

    /**
     * Index file to show all summury for practice.like basic reports, point issue today,buzzydoc balance,refeer,point dispance,order redeem,points redeem tec.
     * Total card purchesed,free card etc
     * @param type $id
     */
    public function index($id) {
        $sessionstaff = $this->Session->read('staff');
        $staffreport = array();
        if ($sessionstaff['staffaccess']['AccessStaff']['staff_reward_program'] == 1) {
            $reports = $this->Api->getClinicReportingData(0, $sessionstaff['clinic_id'], $sessionstaff['staff_id'], 1);
            $strept = 0;
            foreach ($reports as $report) {
                $staffreport[$strept]['goal_name'] = $report['details_for']['Goal']['goal_name'];
                $staffreport[$strept]['goal_id'] = $report['details_for']['Goal']['id'];
                $strept++;
            }
        }
        $this->set('StaffReport', $staffreport);
        if ($sessionstaff['staff_role'] == 'Administrator' || $sessionstaff['staff_role'] == 'A' || $sessionstaff['staff_role'] == 'Doctor') {
            $this->set('dashboard', 1);
        }
        if ($sessionstaff['staff_role'] == 'M' || $sessionstaff['staff_role'] == 'Manager') {
            $this->set('dashboard', 0);
        }
        if (($id == 1 && $id != '')) {

            $this->set('dashboard', 1);
        }
        if (($id == 0 && $id != '')) {
            $this->set('dashboard', 0);
        }
        $Totalcardpurch = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' group by clinic_id');
        if (empty($Totalcardpurch)) {
            $this->set('Totalcardpurch', 0);
            $total1 = 0;
        } else {
            $this->set('Totalcardpurch', $Totalcardpurch[0][0]['total']);
            $total1 = $Totalcardpurch[0][0]['total'];
        }
        $Totalcardissue = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' and status=1 and text!="" group by clinic_id');
        if (empty($Totalcardissue)) {
            $this->set('Totalcardissue', 0);
            $total2 = 0;
        } else {
            $this->set('Totalcardissue', $Totalcardissue[0][0]['total']);
            $total2 = $Totalcardissue[0][0]['total'];
        }
        $Totalcardreg = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' and status=2 group by clinic_id');
        if (empty($Totalcardreg)) {
            $this->set('Totalcardreg', 0);
            $total3 = 0;
        } else {
            $this->set('Totalcardreg', $Totalcardreg[0][0]['total']);
            $total3 = $Totalcardreg[0][0]['total'];
        }
        $this->set('Totalcardbalance', $total1 - ($total2 + $total3));
        $afrom = date("Y-m-d", strtotime("-1 year"));
        $ato = date("Y-m-d");

        $active_total = $this->Transaction->query('select count(card_number) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number !="" and date between "' . $afrom . '" and "' . $ato . '"  group by card_number');

        $this->set('TotalActive', count($active_total));
        $OrderRedeemed = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and (status="New" or status="Redeemed") group by activity_type');
        $OrderRedeemed1 = $this->GlobalRedeem->query('select count(id) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" group by activity_type');
        $OrderInoffice = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and status="In Office" group by activity_type');
        $OrderShipped = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and status="Ordered/Shipped" group by activity_type');
        $totlred = $OrderRedeemed1[0][0]['total'] + $OrderRedeemed[0][0]['total'] + $OrderInoffice[0][0]['total'] + $OrderShipped[0][0]['total'];
        if ($totlred == 0) {
            $this->set('OrderRedeemed', 0);
        } else {
            $this->set('OrderRedeemed', $totlred);
        }
        $TotalRefer = $this->Refer->query('select count(id) as total from refers where clinic_id=' . $sessionstaff['clinic_id'] . ' group by clinic_id');

        if (empty($TotalRefer)) {
            $this->set('TotalRefer', 0);
        } else {
            $this->set('TotalRefer', $TotalRefer[0][0]['total']);
        }
        $PointDisbursed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="N"');
        if (empty($PointDisbursed)) {
            $this->set('PointDisbursed', 0);
        } else {
            $this->set('PointDisbursed', number_format($PointDisbursed[0][0]['total'], 2));
        }
        $PointRedeemed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y"');
        $PointRedeemed1 = $this->GlobalRedeem->query('select sum(amount) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y"');
        $totpntred = $PointRedeemed[0][0]['total'] + $PointRedeemed1[0][0]['total'];
        if ($totpntred == 0) {
            $this->set('PointRedeemed', 0);
        } else {
            $this->set('PointRedeemed', number_format(ltrim($totpntred, '-'), 2));
        }

        $options3['conditions'] = array('Invoice.clinic_id' => $sessionstaff['clinic_id']);
        $options3['order'] = array('Invoice.payed_on desc');
        $Invoice = $this->Invoice->find('first', $options3);
        if ($Invoice['Invoice']['current_balance'] == 0) {
            $this->set('CurrentBalance', 0);
        } else {
            $this->set('CurrentBalance', number_format($Invoice['Invoice']['current_balance'], 2));
        }
        $redemptiondetail = $this->RedemptionDetail->query('select sum(points_redeemed) as total from redemption_details where clinic_redeem=' . $sessionstaff['clinic_id'] . ' and clinic_point_used!=' . $sessionstaff['clinic_id'] . ' and status !=1');
        if ($redemptiondetail[0][0]['total'] > 0) {
            $this->set('CreditBalance', number_format($redemptiondetail[0][0]['total'] / 50, 2));
        } else {
            $this->set('CreditBalance', 0);
        }

        $afrom = date("Y-m-d") . ' 00:00:00';
        $ato = date("Y-m-d H:i:s");
        $str = 'and date between "' . $afrom . '" and "' . $ato . '"';

        $Pointissuetoday = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . '  ' . $str . 'and activity_type="N"');
        if ($Pointissuetoday[0][0]['total'] > 0) {
            $this->set('PointIssueToday', number_format($Pointissuetoday[0][0]['total'], 2));
        } else {
            $this->set('PointIssueToday', 0);
        }
    }

    /**
     * Function to convert number into indian number format.
     * @param type $num
     * @return string
     */
    function formatInIndianStyle($num) {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {
            $decimalpart = "00";
        }
        if (!($pos === false)) {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);
        }

        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, - 3);
            $numexceptlastdigits = substr($num, 0, - 3);
            $formatted = $this->makeComma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num, 2);
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {
            $stringtoreturn = "-" . substr($stringtoreturn, 2);
        }

        return $stringtoreturn;
    }

    /**
     * Function to change number to 2 decimal number format.
     * @param type $input
     * @return string
     */
    function makeComma($input) {
        if (strlen($input) <= 2) {
            return $input;
        }
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->makeComma($length) . "," . substr($input, - 2);
        return $formatted_input;
    }

    /**
     * If patient loss the card then staff can assign new card to patient then old card number will blocked and no longer use for pratice.
     */
    public function checkcardnumber() {
        $this->layout = null;
        $sessionstaff = $this->Session->read('staff');

        $cardnumber = $this->CardNumber->find('first', array(
            'conditions' => array(
                'CardNumber.clinic_id' => $sessionstaff['clinic_id'],
                'CardNumber.card_number' => $this->request->data['new_card']
            )
        ));

        if (!empty($cardnumber)) {
            $cardnumberstatus = $this->CardNumber->find('first', array(
                'conditions' => array(
                    'CardNumber.clinic_id' => $sessionstaff['clinic_id'],
                    'CardNumber.card_number' => $this->request->data['new_card'],
                    'CardNumber.status' => 1
                )
            ));

            if (empty($cardnumberstatus)) {
                echo 2;
            } else {
                $query = 'update card_numbers set status=3 where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number=' . $this->request->data['old_card'];
                $changestatus = $this->CardNumber->query($query);
                $query_cu = 'update clinic_users set card_number=' . $this->request->data['new_card'] . ' where clinic_id=' . $sessionstaff['clinic_id'] . ' and user_id=' . $this->request->data['user_id'];
                $this->ClinicUser->query($query_cu);
                $querynew = 'update card_numbers set status=2 where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number=' . $this->request->data['new_card'];
                $changestatusnew = $this->CardNumber->query($querynew);
                $querytrans = 'update transactions set card_number=' . $this->request->data['new_card'] . ' where user_id=' . $this->request->data['user_id'];
                $changestatustrans = $this->CardNumber->query($querytrans);
                $transe['Transaction'] = array(
                    'user_id' => $this->request->data['user_id'],
                    'card_number' => $this->request->data['new_card'],
                    'activity_type' => 'N',
                    'first_name' => $sessionstaff['customer_info']['User']['first_name'],
                    'authorization' => 'Loss Card',
                    'amount' => '-50',
                    'clinic_id' => $sessionstaff['clinic_id'],
                    'staff_id' => $sessionstaff['staff_id'],
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $sessionstaff['is_buzzydoc']
                );
                $this->Transaction->create();
                $this->Transaction->save($transe['Transaction']);

                if ($sessionstaff['is_buzzydoc'] == 1) {
                    $glbp = $sessionstaff['customer_info']['User']['points'] - 50;
                    $this->User->updateAll(array(
                        'User.points' => $glbp
                            ), array(
                        'User.id' => $this->request->data['user_id']
                    ));
                    $localpoint = $this->ClinicUser->find('first', array(
                        'conditions' => array(
                            'ClinicUser.user_id' => $this->request->data['user_id'],
                            'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                        )
                    ));
                    $points = $localpoint['ClinicUser']['local_points'] . '(' . $glbp . ')';
                } else {
                    $alltrans = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $this->request->data['user_id'],
                            'Transaction.clinic_id' => $sessionstaff['clinic_id'],
                            'Transaction.is_buzzydoc !=' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.user_id'
                        )
                    ));
                    if (empty($alltrans)) {
                        $points = 0;
                    } else {
                        $points = $alltrans[0]['points'];
                    }
                    $this->ClinicUser->updateAll(array(
                        'ClinicUser.local_points' => $points
                            ), array(
                        'ClinicUser.user_id' => $this->request->data['user_id'],
                        'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                    ));
                    $localpoint = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $this->request->data['user_id']
                        )
                    ));
                    $points = $points . '(' . $localpoint['User']['points'] . ')';
                }

                $this->Session->write('staff.customer_info.total_points', $points);
                $this->Session->write('staff.customer_info.card_number', $this->request->data['new_card']);
                echo 3;
            }
        } else {
            echo 1;
        }
        exit();
    }

    /**
     * This is the default landing page for patient after serach by staff member.Here we fetch the all details related patient.
     * Getting the list of all transaction details.
     * Currently in which acclerated reward program level.
     * All treatment plan details with progress tracker.
     * Patient profile details.
     * Patient's badge.
     * Screan for staff to assign points and start treatment plan for patient.
     * At the right side show all details related to patient like (Total buzzydoc and legacy points,current treatment plan progress,current certificate earn,Current Accelerated progrom level,option for staff to end the treatmnet plan etc.)
     * @return type
     */
    public function recordpoint() {
        $sessionstaff = $this->Session->read('staff');
        $this->Session->write('staff.search', 0);
        $this->updateSession($sessionstaff);
        $optionsloc['conditions'] = array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id']);
        $cliniclocation = $this->ClinicLocation->find('all', $optionsloc);
        $this->set('clinicLocation', $cliniclocation);
        //condition to select patient from search list and view all details.
        if ($this->request->is('post') && isset($this->request->data['action']) && $this->request->data['action'] == 'selected_customer') {
            //condition for unregisterd card number search by staff.
            if ($this->request->data['user_id'] == 0) {
                //getting the card number details with point assigned by staff.
                $cardnumber = $this->CardNumber->find('all', array(
                    'conditions' => array(
                        'CardNumber.clinic_id' => $sessionstaff['clinic_id'], 'CardNumber.card_number like ' => '%' . $this->request->data['card_number'] . '%', 'CardNumber.status' => 1),
                    'fields' => array('CardNumber.card_number', 'CardNumber.text')
                ));
                $unreguserdata = json_decode($cardnumber[0]['CardNumber']['text']);
                $data[$this->request->data['user_id']]['card_number'] = $this->request->data['card_number'];
                if (isset($unreguserdata) && !empty($unreguserdata)) {
                    $data[$this->request->data['user_id']]['first_name'] = $unreguserdata->first_name;
                    $data[$this->request->data['user_id']]['last_name'] = $unreguserdata->last_name;
                    $data[$this->request->data['user_id']]['email'] = $unreguserdata->email;
                    $data[$this->request->data['user_id']]['parents_email'] = $unreguserdata->parents_email;
                    $data[$this->request->data['user_id']]['custom_date'] = $unreguserdata->custom_date;
                } else {
                    $data[$this->request->data['user_id']]['first_name'] = '';
                    $data[$this->request->data['user_id']]['last_name'] = '';
                    $data[$this->request->data['user_id']]['email'] = '';
                    $data[$this->request->data['user_id']]['parents_email'] = '';
                    $data[$this->request->data['user_id']]['custom_date'] = '0000-00-00';
                }

                $unrgtrans = $this->UnregTransaction->query('select sum(amount) as total from unreg_transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number="' . $this->request->data['card_number'] . '"');
                if ($unrgtrans[0][0]['total'] != '') {
                    $totalpt1 = $unrgtrans[0][0]['total'];
                } else {
                    $totalpt1 = 0;
                }
                $data[$this->request->data['user_id']]['total_points'] = $totalpt1;
                $data[$this->request->data['user_id']]['User']['id'] = 0;
                $data[$this->request->data['user_id']]['User']['status'] = 1;
                //store all card details in to session.
                foreach ($data as $dt) {
                    $this->Session->write('staff.customer_info', $dt);
                }
                $this->Session->write('staff.customer_search_results', array());
                $this->Session->delete('staff.customer_search_results');
                $this->Session->write('staff.unreg_customer_search_results', array());
                $this->Session->delete('staff.unreg_customer_search_results');
            } else {
                //condition for registerd patinet and get details.
                $this->setUserId($this->request->data['user_id']);
                $fsusers = $this->User->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'clinic_users.user_id' => $this->request->data['user_id'], 'clinic_users.card_number' => $this->request->data['card_number']),
                    'fields' => array('clinic_users.*', 'User.*')
                ));

                if (!empty($fsusers)) {
                    $data = array();
                    $fromclinic = 0;
                    foreach ($fsusers as $key => $value) {
                        if ($value['clinic_users']['clinic_id'] == $sessionstaff['clinic_id']) {
                            $fromclinic = 1;
                        }
                        $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $value['clinic_users']['user_id'];
                        $users_field = $this->ProfileFieldUser->query($sQuery);

                        $data[$value['clinic_users']['user_id']] = $users_field;
                        $data[$value['clinic_users']['user_id']]['card_number'] = $value['clinic_users']['card_number'];
                        $data[$value['clinic_users']['user_id']]['custom_date'] = $value['User']['custom_date'];
                        $data[$value['clinic_users']['user_id']]['email'] = $value['User']['email'];
                        $data[$value['clinic_users']['user_id']]['parents_email'] = $value['User']['parents_email'];
                        $data[$value['clinic_users']['user_id']]['first_name'] = $value['User']['first_name'];
                        $data[$value['clinic_users']['user_id']]['last_name'] = $value['User']['last_name'];
                        $data[$value['clinic_users']['user_id']]['customer_password'] = $value['User']['password'];
                        $data[$value['clinic_users']['user_id']]['clinic_id'] = $value['clinic_users']['clinic_id'];
                        $data[$value['clinic_users']['user_id']]['internal_id'] = $value['User']['internal_id'];


                        $localpoint = $this->ClinicUser->find('first', array(
                            'conditions' => array(
                                'ClinicUser.user_id' => $value['clinic_users']['user_id'],
                                'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                            )
                        ));

                        if ($sessionstaff['is_buzzydoc'] == 1) {
                            $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                        } else {

                            $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                        }
                        $data[$value['clinic_users']['user_id']]['User'] = $value['User'];
                    }

                    foreach ($data as $dt) {
                        $this->Session->write('staff.customer_info', $dt);
                    }
                    //store all details with transaction in session for registered card number.
                    $this->Session->write('staff.fromclinic', $fromclinic);
                    $this->Session->write('staff.customer_search_results', array());
                    $this->Session->delete('staff.customer_search_results');
                    $this->Session->write('staff.unreg_customer_search_results', array());
                    $this->Session->delete('staff.unreg_customer_search_results');
                }
            }
            $this->Session->write('staff.permission', 1);

            $sessionstaffnew = $this->Session->read('staff');
            //Checking patient is also staff member.
            $localpoint = $this->Staff->find('first', array(
                'conditions' => array(
                    'Staff.staff_email' => $sessionstaffnew['customer_info']['email'],
                    'Staff.staff_email !=' => '',
                    'Staff.clinic_id' => $sessionstaff['clinic_id']
                )
            ));
            //condition to set permission for staff to not have access to assign points for patient who have staff member emaail id.
            if (!empty($localpoint)) {
                if ($sessionstaffnew['staff_role'] == 'Doctor' || $sessionstaffnew['staff_role'] == 'D') {
                    if ($localpoint['Staff']['staff_role'] == 'Administrator' || $localpoint['Staff']['staff_role'] == 'A' || $localpoint['Staff']['staff_role'] == 'Manager' || $localpoint['Staff']['staff_role'] == 'M') {

                        $this->Session->write('staff.permission', 1);
                    } else {
                        $this->Session->write('staff.permission', 0);
                    }
                }
                if ($sessionstaffnew['staff_role'] == 'Administrator' || $sessionstaffnew['staff_role'] == 'A') {
                    if ($localpoint['Staff']['staff_role'] == 'Manager' || $localpoint['Staff']['staff_role'] == 'M') {

                        $this->Session->write('staff.permission', 1);
                    } else {
                        $this->Session->write('staff.permission', 0);
                    }
                }
                if ($sessionstaffnew['staff_role'] == 'Manager' || $sessionstaffnew['staff_role'] == 'M') {

                    $this->Session->write('staff.permission', 0);
                }
            }
        } else if ($this->request->is('post')) {
            //code for search patient from system.
            //removing extra value from search keyword.
            $this->request->data['customer_card'] = str_replace(array('\\', '$', '#', '^', '/'), '', $this->request->data['customer_card']);
            $cardchk1 = explode(';', $this->request->data['customer_card']);
            $cardchk2 = explode('?', $this->request->data['customer_card']);
            if (count($cardchk1) == 1 && count($cardchk2) == 1) {
                $this->request->data['customer_card'] = $this->request->data['customer_card'];
            } else if (count($cardchk1) == 2 && count($cardchk2) == 2) {
                $getcard = explode('?', $cardchk1[1]);
                $this->request->data['customer_card'] = $getcard[0];
            } else {
                $this->request->data['customer_card'] = '#$%^';
            }
            //code to check search parameter for own clinic or search from full system.
            if (isset($this->request->data['ownclinic']) && ($this->request->data['ownclinic'] == 1 || $this->request->data['ownclinic'] == 'on')) {
                $ownclinic = 1;
                $this->Session->write('staff.ownclinic', $ownclinic);
            } else {
                $ownclinic = 0;
                $this->Session->write('staff.ownclinic', $ownclinic);
            }
            //scan end here..
            //condition for search from all clinic for buzzydoc pratice.
            if ($sessionstaff['is_buzzydoc'] == 1 && $ownclinic == 0) {
                $users = $this->User->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )),
                    'conditions' => array(
                        'OR' => array('clinic_users.card_number like ' => '%' . $this->request->data['customer_card'] . '%', 'User.email like ' => '%' . $this->request->data['customer_card'] . '%', 'User.parents_email like ' => '%' . $this->request->data['customer_card'] . '%',
                            'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $this->request->data['customer_card'] . '%'
                        )),
                    'fields' => array('clinic_users.card_number,clinic_users.user_id,clinic_users.clinic_id', 'CONCAT(User.first_name, " ", User.last_name) AS first_name', 'User.email', 'User.custom_date')
                ));
            } else {
                //condition for search from own clinic for buzzydoc pratice.
                $users = $this->User->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )),
                    'conditions' => array(
                        'OR' => array('clinic_users.card_number like ' => '%' . $this->request->data['customer_card'] . '%', 'User.email like ' => '%' . $this->request->data['customer_card'] . '%', 'User.parents_email like ' => '%' . $this->request->data['customer_card'] . '%',
                            'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $this->request->data['customer_card'] . '%'
                        ), 'clinic_users.clinic_id' => $sessionstaff['clinic_id']),
                    'fields' => array('clinic_users.card_number,clinic_users.user_id,clinic_users.clinic_id', 'CONCAT(User.first_name, " ", User.last_name) AS first_name', 'User.email', 'User.custom_date')
                ));
            }

            $forfnln = explode(' ', $this->request->data['customer_card']);
            $queryl = '';
            foreach ($forfnln as $flname) {
                $queryl.='(text like "%' . $flname . '%") and ';
            }
            $queryl = rtrim($queryl, ' and ');
            //getting the all unregisterd card.
            $cardnumber = $this->CardNumber->query('select * from card_numbers as CardNumber where (card_number like "%' . $this->request->data['customer_card'] . '%" or ' . $queryl . ') and clinic_id=' . $sessionstaff['clinic_id'] . ' and status=1');

            $total = count($users) + count($cardnumber);
            //condition for check result is not 0.
            if ($total != 0) {
                if ($total > 1) {
                    //if get single result then direct go to that account.
                    $this->Session->write('staff.customer_search_results', $users);
                    $this->Session->write('staff.unreg_customer_search_results', $cardnumber);
                } else {
                    //If list is not empty.
                    if (!empty($users)) {
                        //getting user details and store it to session.
                        $fsusers = $this->User->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = User.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'User.id' => $users[0]['clinic_users']['user_id'], 'clinic_users.clinic_id' => $users[0]['clinic_users']['clinic_id']),
                            'fields' => array('clinic_users.*', 'User.*')
                        ));

                        if (!empty($fsusers)) {
                            $data = array();
                            $fromclinic = 0;
                            foreach ($fsusers as $key => $value) {

                                if ($value['clinic_users']['clinic_id'] == $sessionstaff['clinic_id']) {
                                    $fromclinic = 1;
                                }
                                $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $value['clinic_users']['user_id'];
                                $users_field = $this->ProfileFieldUser->query($sQuery);

                                $data[$value['clinic_users']['user_id']] = $users_field;
                                $data[$value['clinic_users']['user_id']]['card_number'] = $value['clinic_users']['card_number'];
                                $data[$value['clinic_users']['user_id']]['custom_date'] = $value['User']['custom_date'];
                                $data[$value['clinic_users']['user_id']]['email'] = $value['User']['email'];
                                $data[$value['clinic_users']['user_id']]['parents_email'] = $value['User']['parents_email'];
                                $data[$value['clinic_users']['user_id']]['first_name'] = $value['User']['first_name'];
                                $data[$value['clinic_users']['user_id']]['last_name'] = $value['User']['last_name'];
                                $data[$value['clinic_users']['user_id']]['customer_password'] = $value['User']['password'];
                                $data[$value['clinic_users']['user_id']]['clinic_id'] = $value['clinic_users']['clinic_id'];
                                $data[$value['clinic_users']['user_id']]['internal_id'] = $value['clinic_users']['internal_id'];
                                $localpoint = $this->ClinicUser->find('first', array(
                                    'conditions' => array(
                                        'ClinicUser.user_id' => $value['clinic_users']['user_id'],
                                        'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                                    )
                                ));

                                if ($sessionstaff['is_buzzydoc'] == 1) {
                                    $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                                } else {

                                    $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                                }

                                $data[$value['clinic_users']['user_id']]['User'] = $value['User'];
                            }

                            foreach ($data as $dt) {
                                $this->Session->write('staff.customer_info', $dt);
                            }
                            $this->Session->write('staff.fromclinic', $fromclinic);
                            $this->Session->write('staff.customer_search_results', array());
                            $this->Session->delete('staff.customer_search_results');
                            $this->Session->write('staff.unreg_customer_search_results', array());
                            $this->Session->delete('staff.unreg_customer_search_results');
                        }
                    } else {
                        //store unregisterd card number details in to session.
                        $unreguserdata = json_decode($cardnumber[0]['CardNumber']['text']);
                        $data[0]['card_number'] = $cardnumber[0]['CardNumber']['card_number'];
                        if (isset($unreguserdata) && !empty($unreguserdata)) {

                            $data[0]['first_name'] = $unreguserdata->first_name;
                            $data[0]['last_name'] = $unreguserdata->last_name;
                            $data[0]['email'] = $unreguserdata->email;
                            $data[0]['parents_email'] = $unreguserdata->parents_email;
                            $data[0]['custom_date'] = $unreguserdata->custom_date;
                            $data[0]['parents_email'] = $unreguserdata->parents_email;
                        } else {


                            $data[0]['first_name'] = '';
                            $data[0]['last_name'] = '';
                            $data[0]['email'] = '';
                            $data[0]['custom_date'] = '0000-00-00';
                            $data[0]['parents_email'] = '';
                        }
                        $unrgtrans = $this->UnregTransaction->query('select sum(amount) as total from unreg_transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number="' . $cardnumber[0]['CardNumber']['card_number'] . '"');
                        if ($unrgtrans[0][0]['total'] != '') {
                            $totalpt = $unrgtrans[0][0]['total'];
                        } else {
                            $totalpt = 0;
                        }
                        $data[0]['total_points'] = $totalpt;
                        $data[0]['User']['id'] = 0;
                        $data[0]['User']['status'] = 1;

                        foreach ($data as $dt) {
                            $this->Session->write('staff.customer_info', $dt);
                        }

                        $this->Session->write('staff.customer_search_results', array());
                        $this->Session->delete('staff.customer_search_results');
                        $this->Session->write('staff.unreg_customer_search_results', array());
                        $this->Session->delete('staff.unreg_customer_search_results');
                    }
                    $this->Session->write('staff.permission', 1);
                    $sessionstaffnew = $this->Session->read('staff');
                    $localpoint = $this->Staff->find('first', array(
                        'conditions' => array(
                            'Staff.staff_email' => $sessionstaffnew['customer_info']['email'],
                            'Staff.staff_email !=' => '',
                            'Staff.clinic_id' => $sessionstaff['clinic_id']
                        )
                    ));

                    if (!empty($localpoint)) {
                        if ($localpoint['Staff']['staff_role'] == 'Administrator' || $localpoint['Staff']['staff_role'] == 'A' || $localpoint['Staff']['staff_role'] == 'Doctor') {

                            $this->Session->write('staff.permission', 0);
                        } else {

                            if ($sessionstaffnew['staff_role'] == 'Manager' || $sessionstaffnew['staff_role'] == 'M') {

                                $this->Session->write('staff.permission', 0);
                            } else {

                                $this->Session->write('staff.permission', 1);
                            }
                        }
                    }
                }
            }
            $sessionstaff = $this->Session->read('staff');

            if ($total == 0) {

                $this->Session->setFlash('No records found', 'default', array(), 'bad');
                $this->redirect(array('action' => 'index', 0));
            }
        } else if (isset($sessionstaff['customer_search_results']) || isset($sessionstaff['customer_info'])) {
            
        } else {
            $this->redirect(array('action' => 'index'));
        }
        //code end for search list of card number for registerd or unregisterd.

        $sessionstaffcheck = $this->Session->read('staff');
        //If pratice is buzzydoc then fetch all treatment plans and treatment plan usesag for patient.
        if ($sessionstaffcheck['is_buzzydoc'] == 1) {

            $optionschecktretup1['conditions'] = array('TreatmentSetting.user_id' => $sessionstaffcheck['customer_info']['User']['id']);
            $optionschecktretup1['order'] = array('TreatmentSetting.created_at desc');
            $checktretup1 = $this->TreatmentSetting->find('first', $optionschecktretup1);

            $treatdate = '';
            if (!empty($checktretup1)) {
                $treatdate = $checktretup1['TreatmentSetting']['created_at'];
            }

            $optionschecktretup['conditions'] = array('UserPerfectVisit.user_id' => $sessionstaffcheck['customer_info']['User']['id']);
            $optionschecktretup['order'] = array('UserPerfectVisit.date desc');
            $checktretup = $this->UserPerfectVisit->find('first', $optionschecktretup);
            $treatdate1 = '';
            if (!empty($checktretup)) {
                $treatdate1 = $checktretup['UserPerfectVisit']['date'];
            }
            if ($treatdate > $treatdate1) {
                $check = 'TreatmentSetting.id desc';
            } else {
                $check = 'UserPerfectVisit.id desc';
            }

            $optionslevelsetting['conditions'] = array('UpperLevelSetting.clinic_id' => $sessionstaffcheck['clinic_id'], 'UpperLevelSetting.status' => 1, 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']);
            $promotionlevel = $this->UpperLevelSetting->find('all', $optionslevelsetting);

            $promotionlevel1 = $this->UpperLevelSetting->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'treatment_settings',
                        'alias' => 'TreatmentSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TreatmentSetting.upper_level_setting_id = UpperLevelSetting.id'
                        )
                    ),
                    array(
                        'table' => 'user_perfect_visits',
                        'alias' => 'UserPerfectVisit',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'UserPerfectVisit.level_up_settings_id = TreatmentSetting.upper_level_setting_id'
                        )
                    )
                ),
                'conditions' => array(
                    'TreatmentSetting.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UpperLevelSetting.status' => 1, 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']
                ),
                'fields' => array(
                    'UpperLevelSetting.*', 'UserPerfectVisit.*'
                ),
                'order' => array($check)
            ));

            $settingwithpromo = array();
            //getting the list of all treatmnet plan's promotion.
            foreach ($promotionlevel as $prolevel) {
                $optionslev['conditions'] = array('LevelupPromotion.clinic_id' => $sessionstaffcheck['clinic_id'], 'LevelupPromotion.public' => 1, 'LevelupPromotion.id' => explode(',', $prolevel['UpperLevelSetting']['global_promotion_ids']));
                $optionslev['order'] = array('LevelupPromotion.sort' => 'ASC');
                $promotion12 = $this->LevelupPromotion->find('all', $optionslev);
                $settingwithpromo[$prolevel['UpperLevelSetting']['id'] . '#-' . str_replace(' ', '_', $prolevel['UpperLevelSetting']['treatment_name']) . '#-' . $prolevel['UpperLevelSetting']['soft_delete'] . '#-' . $prolevel['UpperLevelSetting']['interval']] = array_column($promotion12, 'LevelupPromotion');
            }
            $settingwithpromo1 = array();
            foreach ($promotionlevel1 as $prolevel) {
                if ($prolevel['UpperLevelSetting']['soft_delete'] == 1) {
                    $chekdel = 1;
                } else {
                    $chekdel = 0;
                }
                $optionslev['conditions'] = array('LevelupPromotion.clinic_id' => $sessionstaffcheck['clinic_id'], 'LevelupPromotion.public' => 1, 'LevelupPromotion.id' => explode(',', $prolevel['UpperLevelSetting']['global_promotion_ids']));
                $optionslev['order'] = array('LevelupPromotion.sort' => 'ASC');
                $promotion12 = $this->LevelupPromotion->find('all', $optionslev);
                $settingwithpromo1[$prolevel['UpperLevelSetting']['id'] . '#-' . str_replace(' ', '_', $prolevel['UpperLevelSetting']['treatment_name']) . '#-' . $chekdel] = array_column($promotion12, 'LevelupPromotion');


                $optionsperfe['conditions'] = array('UserPerfectVisit.clinic_id' => $sessionstaffcheck['clinic_id'], 'UserPerfectVisit.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UserPerfectVisit.level_up_settings_id' => $prolevel['UpperLevelSetting']['id']);
                $getvisit = $this->UserPerfectVisit->find('all', $optionsperfe);


                $settingwithpromo1[$prolevel['UpperLevelSetting']['id'] . '#-' . str_replace(' ', '_', $prolevel['UpperLevelSetting']['treatment_name']) . '#-' . $chekdel]['visits'] = $getvisit;
            }

            if (!empty($settingwithpromo)) {
                $this->set('global_promotions', $settingwithpromo);
            }
            if (!empty($settingwithpromo1)) {
                $this->set('global_promotions_run', $settingwithpromo1);
            }
        }
        //getting the local promotion list for buzzydoc or legacy pratice.
        if ($sessionstaffcheck['is_lite'] == 1) {
            $options['conditions'] = array('Promotion.is_lite' => 1, 'Promotion.clinic_id' => $sessionstaffcheck['clinic_id']);
            $options['order'] = array('Promotion.sort' => 'ASC');
            $promotion11 = $this->Promotion->find('all', $options);
        } else {
            $optionscat['conditions'] = array('Promotion.is_lite !=' => 1, 'Promotion.clinic_id' => $sessionstaffcheck['clinic_id'], 'Promotion.public' => 1, 'Promotion.is_global' => 0, 'Promotion.default !=' => 2);
            $optionscat['order'] = array('Promotion.sort' => 'asc');
            $promotion11 = $this->Promotion->find('all', $optionscat);
        }
        $this->set('promotions', $promotion11);
        $alltrans = array();
        //Getting the all transaction details for Registered Patient.
        if (isset($sessionstaffcheck['customer_info']['User']['id']) && $sessionstaffcheck['customer_info']['User']['id'] != 0) {
            //getting all transaction details.
            //getting the points earned transaction.
            $alltrans2 = $this->Transaction->find('all', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id'],
                    'Transaction.amount !=' => 0,
                    'Transaction.activity_type ' => 'N'
                ),
                'order' => array(
                    'Transaction.date desc'
            )));
            //getting the point redeemd transaction
            $alltrans3 = $this->Transaction->find('all', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id'],
                    'Transaction.activity_type ' => 'Y'
                ),
                'order' => array(
                    'Transaction.date desc'
            )));
            //getting the redeemption details from tango or amazon.
            $alltrans1 = $this->Transaction->find('all', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.clinic_id' => 0,
                    'Transaction.amount !=' => 0
                ),
                'order' => array(
                    'Transaction.date desc'
            )));
            //merge all transaction in to one array.
            $alltrans = array_merge_recursive($alltrans2, $alltrans1, $alltrans3);

            function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {
                foreach ($array as $subarray) {
                    $keys[] = $subarray['Transaction'][$subkey];
                }
                array_multisort($keys, $sortType, $array);
            }

            sortBySubkey($alltrans, 'date');
        } else {
            //Getting the all transaction details for Unregistered Patient.
            $alltransunreg = array();
            if (isset($sessionstaffcheck['customer_info']['card_number'])) {
                $alltransunreg = $this->UnregTransaction->find('all', array(
                    'conditions' => array(
                        'UnregTransaction.user_id' => 0,
                        'UnregTransaction.card_number' => $sessionstaffcheck['customer_info']['card_number'],
                        'UnregTransaction.clinic_id' => $sessionstaffcheck['clinic_id']
                    ),
                    'order' => array(
                        'UnregTransaction.date desc'
                )));
            }

            if (!empty($alltransunreg)) {
                $i = 0;
                foreach ($alltransunreg as $tarns) {
                    $alltrans[$i]['Transaction']['id'] = $tarns['UnregTransaction']['id'];
                    $alltrans[$i]['Transaction']['user_id'] = $tarns['UnregTransaction']['user_id'];
                    $alltrans[$i]['Transaction']['card_number'] = $tarns['UnregTransaction']['card_number'];
                    $alltrans[$i]['Transaction']['first_name'] = $tarns['UnregTransaction']['first_name'];
                    $alltrans[$i]['Transaction']['last_name'] = $tarns['UnregTransaction']['last_name'];
                    $alltrans[$i]['Transaction']['promotion_id'] = $tarns['UnregTransaction']['promotion_id'];
                    $alltrans[$i]['Transaction']['activity_type'] = $tarns['UnregTransaction']['activity_type'];
                    $alltrans[$i]['Transaction']['authorization'] = $tarns['UnregTransaction']['authorization'];
                    $alltrans[$i]['Transaction']['amount'] = $tarns['UnregTransaction']['amount'];
                    $alltrans[$i]['Transaction']['date'] = $tarns['UnregTransaction']['date'];
                    $i++;
                }
            }
        }
        $newalltrans = array();
        $couponnotredeem = 0;
        $couonarray = array();
        $c = 0;
        //code for filter all transaction details in type.
        foreach ($alltrans as $at) {
            //Manualy point assigned by staff.
            if ($at['Transaction']['treatment_id'] < 1 && $at['Transaction']['promotion_id'] < 1 && $at['Transaction']['activity_type'] == 'N') {
                $newalltrans['M'] = 'Manual point entry';
            }
            //Points from free form pormotions.
            if ($at['Transaction']['treatment_id'] < 1 && $at['Transaction']['promotion_id'] > 0 && $at['Transaction']['activity_type'] == 'N') {
                $newalltrans['P'] = 'Points from free-form promotion';
            }
            //All redeemed transactions.
            if ($at['Transaction']['activity_type'] == 'Y') {
                $newalltrans['R'] = 'Redeemed history';
            }
            //Points from treatment plans.
            if ($at['Transaction']['treatment_id'] > 0 && $at['Transaction']['activity_type'] == 'N') {
                $getname['conditions'] = array('UpperLevelSetting.id' => $at['Transaction']['treatment_id'], 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']);
                $getname['fields'] = array('UpperLevelSetting.treatment_name', 'UpperLevelSetting.id');
                $treatmentname = $this->UpperLevelSetting->find('first', $getname);
                $newalltrans[$at['Transaction']['treatment_id']] = $treatmentname['UpperLevelSetting']['treatment_name'];
            }
            //Getting the list of all coupon earned by patient.
            if ($at['Transaction']['amount'] == 0 && $at['Transaction']['activity_type'] == 'Y' && $at['Transaction']['status'] == 'Active') {
                $couponnotredeem++;
                $getcouname['conditions'] = array('ProductService.id' => $at['Transaction']['product_service_id']);
                $getcouname['fields'] = array('ProductService.title', 'ProductService.points', 'ProductService.coupon_image');
                $couponname = $this->ProductService->find('first', $getcouname);
                $couonarray[$c]['ProductService'] = $couponname['ProductService'];
                $couonarray[$c]['ProductService']['tid'] = $at['Transaction']['id'];
                $c++;
            }
        }
        //call function to maintain accelerated rewards program level.
        $this->Api->get_firsttransaction($sessionstaffcheck['customer_info']['User']['id'], $sessionstaffcheck['clinic_id']);
        //Getting the all accelerated rewards program level for patient.
        $tier_achived = $this->Api->getPatientAllLevelAchieved($sessionstaffcheck['clinic_id'], $sessionstaffcheck['customer_info']['User']['id']);
        $this->set('tier_achived', $tier_achived);
        //getting the currently accelerated rewards program points.
        $AcceleratedPoints = $this->Api->getPatientAcceleratedPoints($sessionstaffcheck['clinic_id'], $sessionstaffcheck['customer_info']['User']['id']);
        //getting the points earn from practice.
        $PointsFromClinic = $this->Api->getPatientPointsFromClinic($sessionstaffcheck['clinic_id'], $sessionstaffcheck['customer_info']['User']['id'], $sessionstaffcheck['is_buzzydoc']);
        //Getting the current accelerated rewards program level
        $current_tier_achived = $this->Api->getPatientCurrentLevel($sessionstaffcheck['clinic_id'], $sessionstaffcheck['customer_info']['User']['id']);
        $this->set('current_tier', $current_tier_achived);
        $this->set('PointsFromClinic', $PointsFromClinic);
        $this->set('AcceleratedPoints', $AcceleratedPoints);
        $this->set('coupon_list', $couonarray);
        $this->set('coupon_not_redeem', $couponnotredeem);
        $this->set('transactions_type', $newalltrans);
        $this->set('transactions', $alltrans);
        //getting the list of doctors.
        $alldoc = $this->Doctor->find('all', array(
            'conditions' => array(
                'Doctor.clinic_id' => $sessionstaffcheck['clinic_id']
        )));
        $this->set('doctors', $alldoc);
        //getting the super doctor details.
        $optionsdoc['conditions'] = array('Staff.clinic_id' => $sessionstaffcheck['clinic_id'], 'Staff.staff_role' => 'Doctor');
        $superdoc = $this->Staff->find('first', $optionsdoc);
        $this->set('superdoctors', $superdoc);
        //getting the list of staff users.
        $optionsstf['conditions'] = array('Staff.clinic_id' => $sessionstaffcheck['clinic_id'], 'Staff.active' => '1');
        $allstaff = $this->Staff->find('all', $optionsstf);
        $this->set('staffs', $allstaff);
        //getting the patient state.
        if (isset($sessionstaffcheck['customer_info']) && !empty($sessionstaffcheck['customer_info'])) {
            $state = $this->State->find('all');
            $this->set('states', $state);
            foreach ($sessionstaffcheck['customer_info'] as $customer) {

                if (isset($customer['PF']['profile_field']) && $customer['PF']['profile_field'] == 'state') {

                    $optionsstate['joins'] = array(
                        array('table' => 'states',
                            'alias' => 'States',
                            'type' => 'INNER',
                            'conditions' => array(
                                'States.state_code = City.state_code',
                                'States.state = "' . $customer['PFU']['value'] . '"'
                            )
                        )
                    );
                    $options['fields'] = array('City.city');
                    $cityresult = $this->City->find('all', $optionsstate);

                    $this->set('city', $cityresult);
                }
            }
        } else {
            $state = $this->State->find('all');
            $this->set('states', $state);
            $this->set('city', array());
        }
        //Checking practice have a payment details or not.
        $paydet['conditions'] = array('PaymentDetail.clinic_id' => $sessionstaffcheck['clinic_id']);
        $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
        if (!empty($getpayemntdetails) && $getpayemntdetails['PaymentDetail']['customer_account_profile_id'] > 0) {
            $this->set('paymentcheck', 1);
        } else {
            $this->set('paymentcheck', 0);
        }
        //Code to fetch all details for registered patient.
        if (!empty($sessionstaffcheck['customer_info']['User']['id'])) {
            //Code start to feathc Vip points for patient.
            $getfirsttransaction = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.activity_type' => 'N',
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id']
                ),
                'fields' => array('Transaction.date'),
                'order' => array('Transaction.date asc')
            ));
            $getlastdate = explode('-', $getfirsttransaction['Transaction']['date']);
            $lstdt = date('Y') . '-' . $getlastdate[1] . '-' . $getlastdate[2];
            $diffdefcheck = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
            $expdatechek = floor($diffdefcheck / (60 * 60 * 24));
            //condition for checking the last date from vip points earn.
            if ($expdatechek < 0) {
                $lstdt = (date('Y') - 1) . '-' . $getlastdate[1] . '-' . $getlastdate[2];
            }
            $diffdef = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
            $expdate = floor($diffdef / (60 * 60 * 24));
            //getting the total points in this year for vip member.
            $getvip = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.activity_type' => 'N',
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id'],
                    'Transaction.date >=' => $lstdt,
                    'Transaction.date <=' => date('Y-m-d H:i:s')
                ),
                'fields' => array('sum(Transaction.amount) AS total')
            ));

            $dayreamin = 365 - $expdate;
            if ($getvip[0]['total'] < 0) {
                $getvip[0]['total'] = 0;
            }
            //getting the milestone for patient related to point earn this year.
            $coupondet = $this->MilestoneReward->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'product_services',
                        'alias' => 'ProductService',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ProductService.id = MilestoneReward.coupon_id'
                        )
                    )
                ),
                'conditions' => array(
                    'MilestoneReward.clinic_id' => $sessionstaffcheck['clinic_id'], 'MilestoneReward.points >=' => $getvip[0]['total']),
                'order' => array('MilestoneReward.points asc'),
                'fields' => array('ProductService.*', 'MilestoneReward.*')
            ));

            if (empty($coupondet)) {
                //getting the list of coupon related to milestone.
                $coupondet = $this->MilestoneReward->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'product_services',
                            'alias' => 'ProductService',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ProductService.id = MilestoneReward.coupon_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'MilestoneReward.clinic_id' => $sessionstaffcheck['clinic_id'], 'MilestoneReward.points <=' => $getvip[0]['total']),
                    'order' => array('MilestoneReward.points desc'),
                    'fields' => array('ProductService.*', 'MilestoneReward.*')
                ));
            }

            if ($getvip[0]['total'] > 0) {
                $vipstring = $getvip[0]['total'];
                if ($getvip[0]['total'] > 1) {
                    $vipstring.=' points earned so far this year <br>' . $dayreamin;
                } else {
                    $vipstring.=' point earned so far this year <br>' . $dayreamin;
                }
                if ($dayreamin > 1) {
                    $vipstring.=' days left in calender year<br><i>Next Office Reward Milestone<br>' . $coupondet['ProductService']['title'] . ' at ' . $coupondet['MilestoneReward']['points'] . ' points</i>';
                } else {
                    $vipstring.=' day left in calender year<br><i>Next Office Reward Milestone<br>' . $coupondet['ProductService']['title'] . ' at ' . $coupondet['MilestoneReward']['points'] . ' points</i>';
                }

                $this->set('vip_points', $vipstring);
            } else {
                $this->set('vip_points', 0);
            }
            //store day left in vip member cycle for patient.
            $this->Session->write('staff.customer_info.dayleft', $dayreamin);
            //getting the life time points for patient and store it to session.
            $getoverall = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.activity_type' => 'N',
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id']
                ),
                'fields' => array('sum(Transaction.amount) AS total')
            ));
            if ($getoverall[0]['total'] > 0) {
                $this->Session->write('staff.customer_info.lifetime_points', $getoverall[0]['total']);
            } else {
                $this->Session->write('staff.customer_info.lifetime_points', 0);
            }
            //calculate year,month and days for vip points earning from date.
            $getfirstpoint = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.activity_type' => 'N',
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id']
                ),
                'order' => array('Transaction.date asc')
            ));
            $diff = abs(strtotime(date('Y-m-d H:i:s')) - strtotime($getfirstpoint['Transaction']['date']));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            if ($years == 1) {
                $yr = 'year';
            } else {
                $yr = 'years';
            }
            if ($months == 1) {
                $mn = 'month';
            } else {
                $mn = 'months';
            }
            if ($days == 1) {
                $dy = 'day';
            } else {
                $dy = 'days';
            }
            if ($years > 0) {
                $datefromfirst = 'Patient has been earning for ' . $years . ' ' . $yr . ', ' . $months . ' ' . $mn . ' and ' . $days . ' ' . $dy;
            } else if ($years < 1 && $months > 0) {
                $datefromfirst = 'Patient has been earning for ' . $months . ' ' . $mn . ' and ' . $days . ' ' . $dy;
            } else {
                $datefromfirst = 'Patient has been earning for ' . $days . ' ' . $dy;
            }
            $this->set('firstpoint', $datefromfirst);
            //getting the product and service for pratice to redeeem by staff for patient.
            $userProducts = $this->Clinic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'product_services',
                        'alias' => 'product_services',
                        'type' => 'INNER',
                        'conditions' => array(
                            'product_services.clinic_id = Clinic.id'
                        )
                    ), array(
                        'table' => 'access_staffs',
                        'alias' => 'AccessStaff',
                        'type' => 'INNER',
                        'conditions' => array(
                            'AccessStaff.clinic_id = product_services.clinic_id'
                        )
                    )
                ),
                'conditions' => array(
                    'product_services.clinic_id' => $sessionstaffcheck['clinic_id'], 'product_services.status' => 1, 'product_services.type !=' => 3, 'AccessStaff.product_service' => 1),
                'fields' => array('product_services.points', 'product_services.title', 'product_services.id', 'product_services.from_us', 'product_services.clinic_id'),
                'order' => array('product_services.points asc')
            ));

            if ($userProducts) {
                $userProducts = array_column($userProducts, 'product_services');
                $response[$sessionstaffcheck['display_name']] = $userProducts;
            }

            $this->set('productandservice', $response);
            //getting points for patient to redeem product and service.
            $getglbpoint = $this->Transaction->find('all', array(
                'conditions' => array(
                    'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'Transaction.is_buzzydoc' => 1,
                    'Transaction.clinic_id' => $sessionstaffcheck['clinic_id']
                ),
                'group' => array(
                    'Transaction.clinic_id'
                ),
                'fields' => array(
                    'sum(Transaction.amount) AS total',
                    'Transaction.clinic_id',
                    'Transaction.user_id'
                )
            ));

            $perclinicbuzzpnt = array();
            foreach ($getglbpoint as $glbpt) {

                $getglberedem = $this->GlobalRedeem->find('first', array(
                    'conditions' => array(
                        'GlobalRedeem.clinic_id' => $glbpt['Transaction']['clinic_id'],
                        'GlobalRedeem.user_id' => $glbpt['Transaction']['user_id']
                    ),
                    'fields' => array(
                        'sum(GlobalRedeem.amount) AS total,GlobalRedeem.clinic_id'
                    )
                ));

                $paytoclinic = $glbpt[0]['total'];
                if ($getglberedem[0]['total'] != '') {
                    $perclinicbuzzpnt[$glbpt['Transaction']['clinic_id']] = $paytoclinic + $getglberedem[0]['total'];
                } else {
                    $perclinicbuzzpnt[$glbpt['Transaction']['clinic_id']] = $paytoclinic;
                }
            }

            $this->set('perclinicbuzzpnt', $perclinicbuzzpnt);
            //Getting the list of all active treatment plans for pratice who have a bonus points..
            $bonustreat['conditions'] = array('TreatmentSetting.clinic_id' => $sessionstaffcheck['clinic_id'], 'TreatmentSetting.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'TreatmentSetting.bonus' => 1, 'TreatmentSetting.status' => 1);
            $getBonusTreatment = $this->TreatmentSetting->find('all', $bonustreat);
            $bonusarr = $bonusTreatArr = array();
            foreach ($getBonusTreatment as $btret) {
                $bonusarr[] = $btret['TreatmentSetting']['upper_level_setting_id'];
            }

            $this->Session->write('staff.bonus_treatment', $bonusarr);

            if (!empty($bonusarr)) {
                $upperlevelbonustreat['conditions'] = array('UpperLevelSetting.clinic_id' => $sessionstaffcheck['clinic_id'], 'UpperLevelSetting.status' => 1, 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']);
                $upperlevelbonustreat['fields'] = array('UpperLevelSetting.bonus_points', 'UpperLevelSetting.bonus_message', 'UpperLevelSetting.id');
                $upperlevelbonustreat = $this->UpperLevelSetting->find('all', $upperlevelbonustreat);
                if (!empty($upperlevelbonustreat)) {
                    $upperlevelbonustreat = array_column($upperlevelbonustreat, 'UpperLevelSetting');
                }

                foreach ($upperlevelbonustreat as $value) {
                    $bonusTreatArr[$value['id']] = array('bonus_points' => $value['bonus_points'], 'bonus_message' => $value['bonus_message']);
                }
            }
            $this->Session->write('staff.bonus_treatment_details', $bonusTreatArr);
            //Assign the local badge to patient according to points earn from clinic.
            $usersbg = $this->Transaction->query('SELECT sum(Transaction.amount) as share FROM `transactions` AS `Transaction` where Transaction.activity_type="N" and Transaction.user_id=' . $sessionstaffcheck['customer_info']['User']['id']);

            foreach ($usersbg as $ug) {
                if ($ug[0]['share'] > 0) {
                    $optionsbadge['conditions'] = array(
                        'Badge.value <=' => $ug[0]['share'], 'Badge.clinic_id' => 0
                    );
                    $Badge = $this->Badge->find('all', $optionsbadge);
                    foreach ($Badge as $bg) {
                        $optionsbadgeuser['conditions'] = array(
                            'UsersBadge.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                            'UsersBadge.badge_id' => $bg['Badge']['id']
                        );
                        $Badgeuser = $this->UsersBadge->find('first', $optionsbadgeuser);
                        if (empty($Badgeuser)) {
                            $savebadge['UsersBadge'] = array(
                                'user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                                'badge_id' => $bg['Badge']['id'],
                                'created_on' => date('Y-m-d H:i:s')
                            );
                            $this->UsersBadge->create();
                            $this->UsersBadge->save($savebadge);
                        }
                    }
                    if ($sessionstaffcheck['customer_info']['clinic_id'] == $sessionstaffcheck['clinic_id']) {
                        $optionsbadgeforclinic['conditions'] = array(
                            'Badge.value <=' => $ug[0]['share'], 'Badge.clinic_id' => $sessionstaffcheck['clinic_id'], 'Badge.value !=' => 0
                        );
                        $BadgeClinic = $this->Badge->find('all', $optionsbadgeforclinic);
                        foreach ($BadgeClinic as $bgc) {
                            $optionsbadgeusercl['conditions'] = array(
                                'UsersBadge.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                                'UsersBadge.badge_id' => $bgc['Badge']['id']
                            );
                            $BadgeuserCl = $this->UsersBadge->find('first', $optionsbadgeusercl);
                            if (empty($BadgeuserCl)) {
                                $savebadgecl['UsersBadge'] = array(
                                    'user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                                    'badge_id' => $bgc['Badge']['id'],
                                    'created_on' => date('Y-m-d H:i:s')
                                );
                                $this->UsersBadge->create();
                                $this->UsersBadge->save($savebadgecl);
                            }
                        }
                    }
                }
            }
            $treatmentover = $this->UpperLevelSetting->find('all', array(
                'conditions' => array(
                    'UpperLevelSetting.clinic_id' => $sessionstaffcheck['clinic_id'], 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval'])
            ));

            $treatment_over = array();
            //getting the list of treatment plan who already completed for patient.
            if (!empty($treatmentover)) {
                $tret = 0;
                foreach ($treatmentover as $treat) {
                    $checkuser['conditions'] = array('UserAssignedTreatment.clinic_id' => $sessionstaffcheck['clinic_id'], 'user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UserAssignedTreatment.treatment_id' => $treat['UpperLevelSetting']['id']);
                    $checktreamntexist = $this->UserAssignedTreatment->find('first', $checkuser);
                    if (empty($checktreamntexist)) {
                        $treatment_over[$tret] = array('treatment_id' => $treat['UpperLevelSetting']['id'], 'treatment_name' => $treat['UpperLevelSetting']['treatment_name']);
                        $tret++;
                    }
                }
            }
            //getting the list of treatment plan who already finished for patient.
            $treatmentFinished = array();
            $finished_treatments = $this->UserAssignedTreatment->find('all', array(
                'fields' => array('UserAssignedTreatment.treatment_id'),
                'conditions' => array(
                    'UserAssignedTreatment.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UserAssignedTreatment.clinic_id' => $sessionstaffcheck['clinic_id'])
            ));
            if ($finished_treatments) {
                $treatmentFinished = array_column($finished_treatments, 'UserAssignedTreatment');
            }
            //getting  the list of running treatment plan for patient.
            $visitcheck = $this->UserPerfectVisit->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'upper_level_settings',
                        'alias' => 'UpperLevelSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                        )
                    )
                ),
                'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*'),
                'conditions' => array(
                    'UserPerfectVisit.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UpperLevelSetting.status' => 1, 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']),
                'group' => array('UserPerfectVisit.level_up_settings_id')
            ));
            //getting  the visit history treatment plan for patient.
            $visithistory = $this->UserPerfectVisit->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'upper_level_settings',
                        'alias' => 'UpperLevelSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                        )
                    )
                ),
                'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*'),
                'conditions' => array(
                    'UserPerfectVisit.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'UserPerfectVisit.clinic_id' => $sessionstaffcheck['clinic_id'], 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']),
                'order' => array('UserPerfectVisit.date desc')
            ));

            $v = 0;
            foreach ($visithistory as $visit) {
                $visithistory[$v]['Phase_distribution'] = $this->PhaseDistribution->find('all', array(
                    'conditions' => array(
                        'PhaseDistribution.upper_level_settings_id' => $visit['UpperLevelSetting']['id'])
                ));
                $v++;
            }
            //getting the treatment list for patinet with all phase distribution.
            $userAssignedTreatments = $this->TreatmentSetting->find('all', array(
                'fields' => array('TreatmentSetting.upper_level_setting_id'),
                'conditions' => array(
                    'TreatmentSetting.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'TreatmentSetting.clinic_id' => $sessionstaffcheck['clinic_id'])
            ));
            if (!empty($userAssignedTreatments)) {
                $userAssignedTreatments = array_column($userAssignedTreatments, 'TreatmentSetting');
                $userAssignedTreatments = array_column($userAssignedTreatments, 'upper_level_setting_id');
            }
            //making the array for treatmnet plan for display at progress tracker.
            $levelhistory = array();
            foreach ($visithistory as $history) {
                if ($history['UserPerfectVisit']['is_perfect'] == 1)
                    $perfect = 'Perfect';
                else
                    $perfect = 'Not Perfect';
                if ($history['UserPerfectVisit']['is_treatment_over'] == 1)
                    $treatment_over1 = 'Ended';
                else
                    $treatment_over1 = 'In Progress';
                if ($history['UserPerfectVisit']['level_achieved'] == 0)
                    $level = '--';
                else
                    $level = $history['UserPerfectVisit']['level_achieved'];
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['record'][] = array('perfect' => $perfect, 'level_status' => $history['UserPerfectVisit']['level_achieved'], 'date' => $history['UserPerfectVisit']['date'], 'status' => $treatment_over1);
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['treatment_details'] = $history['UpperLevelSetting'];
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['treatment_details']['phase_distribution'] = $history['Phase_distribution'];

                if ($history['UpperLevelSetting']['interval'] == 1) {
                    $getintervaldetails = $this->getIntervalDetails($history['UpperLevelSetting']['id']);
                    $levelhistory[$history['UpperLevelSetting']['treatment_name']]['interval_details'] = $getintervaldetails;
                } else {
                    $levelhistory[$history['UpperLevelSetting']['treatment_name']]['interval_details'] = array();
                }
            }
            foreach ($levelhistory as $data => $vhistory) {
                $in = 1;
                foreach ($vhistory['record'] as $vs) {
                    if ($vs['status'] == 'In Treatment' || $vs['status'] == 'In Progress') {
                        $in++;
                    }
                }
                $levelhistory[$data]['current_date'] = $vhistory['record'][0]['date'];
                if ($in == count($vhistory['record'])) {
                    $levelhistory[$data]['status'] = 1;
                } else {

                    $levelhistory[$data]['status'] = 0;
                }
            }
            $sort = array();
            foreach ($levelhistory as $k => $v) {
                $sort['current_date'][$k] = $v['current_date'];
                $sort['status'][$k] = $v['status'];
            }
            array_multisort($sort['status'], SORT_ASC, $sort['current_date'], SORT_DESC, $levelhistory);

            //Getting the list of badges earn by patient for local pratice.
            $badges = $this->UsersBadge->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'badges',
                        'alias' => 'Badge',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Badge.id = UsersBadge.badge_id'
                        )
                    )
                ),
                'fields' => array('UsersBadge.*', 'Badge.*'),
                'conditions' => array(
                    'UsersBadge.user_id' => $sessionstaffcheck['customer_info']['User']['id'], 'Badge.clinic_id' => $sessionstaffcheck['clinic_id'], 'Badge.value !=' => 0)
            ));

            $this->Session->write('staff.customer_info.usersBadge', $badges);
            $this->Session->write('staff.customer_info.visithistory', $levelhistory);
            $this->Session->write('staff.customer_info.visitcheck', $visitcheck);
            $this->Session->write('staff.customer_info.treatment_over', $treatment_over);
            $this->set('userAssignedTreatments', $userAssignedTreatments);
            $this->set('treatmentFinished', $treatmentFinished);
            $alreadyrate = array();
            foreach ($cliniclocation as $clnloc) {
                $optionsrtev['conditions'] = array(
                    'RateReview.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                    'RateReview.clinic_id' => $sessionstaffcheck['clinic_id'],
                    'RateReview.clinic_location_id' => $clnloc['ClinicLocation']['id'],
                    'facebook_share' => 1
                );
                if ($clnloc['ClinicLocation']['google_business_page_url'] != '') {
                    $optionsrtev['conditions']['RateReview.notify_staff'] = 1;
                }
                if ($clnloc['ClinicLocation']['yahoo_business_page_url'] != '') {
                    $optionsrtev['conditions']['RateReview.yahoo_notify'] = 1;
                }
                if ($clnloc['ClinicLocation']['yelp_business_page_url'] != '') {
                    $optionsrtev['conditions']['RateReview.yelp_notify'] = 1;
                }
                if ($clnloc['ClinicLocation']['healthgrades_business_page_url'] != '') {
                    $optionsrtev['conditions']['RateReview.healthgrades_notify'] = 1;
                }
                $getratereview = $this->RateReview->find('all', $optionsrtev);
                if (!empty($getratereview)) {
                    $alreadyrate[] = $clnloc['ClinicLocation']['id'];
                }
            }

            $alreadyrate = array_unique($alreadyrate);
            $this->set('alreadyrate', $alreadyrate);
            $this->set('rateReview', $getratereview);
        } else {
            $this->Session->write('staff.customer_info.visithistory', array());
            $this->Session->write('staff.customer_info.visitcheck', array());
            $this->Session->write('staff.customer_info.treatment_over', array());
        }
        if (isset($this->request->data['quick_assign']) && $this->request->data['quick_assign'] == 1) {
            return $this->redirect('/PatientManagement/recordpoint/3');
        }
    }

    /**
     * Function for point allocate to patient by staff user.
     * Staff user can allocate points using promotion list and treatment plans.
     * Staff can assign points to registerd and unregisterd card number.
     * @return type
     */
    public function pointallocation() {
        $sessionstaff = $this->Session->read('staff');
        //Point allocation via treatmnet plan.

        if ($this->request->data && ($sessionstaff['staffaccess']['AccessStaff']['levelup'] == 1 || $sessionstaff['staffaccess']['AccessStaff']['interval'] == 1)) {

            $data = $this->request->data;
            if (isset($sessionstaff['bonus_treatment']) && !empty($sessionstaff['bonus_treatment'])) {
                foreach ($sessionstaff['bonus_treatment'] as $bonus) {
                    $data['global_promo'][$bonus] = array('bonus' => 500); // 500 is just to maintain logic. Value is not used as junk value for promotion id while saving transactions.
                }
            }

            $promotionsIds = array();
            $this->setUserId($this->request->data['user_id']);
            if ($data['global_promo']) {
                $i = 1;
                foreach ($data['global_promo'] as $key => $val) {
                    $exemptCount = $data['exempt_count_' . $key];
                    $this->setUpperLevelSettingid($key);
                    $this->setPhaseDistribution();
                    $this->_badgeId = array();
                    $this->_treatmentSettings = array();
                    if ($this->requestAction('/LevelupPromotions/getTreatments', array(
                                'pass' => array(
                                    $key
                                )
                            ))) {
                        $treatmentPromotions = $this->_treatmentSettings = array_column($this->requestAction('/LevelupPromotions/getTreatments', array(
                                    'pass' => array(
                                        $key
                                    )
                                )), 'UpperLevelSetting');

                        $promotionsIds = array_column($treatmentPromotions, 'global_promotion_ids');

                        if ($promotionsIds && $val) {

                            $publishedPromotionArray = explode(',', $promotionsIds[0]);
                            $inputArray = array(
                                'level_up_settings_id' => $key,
                                'clinic_id' => $this->_clinicId,
                                'user_id' => $this->request->data['user_id']
                            );
                            if (!isset($val['bonus'])) {
                                $data['global_promotion'] = $val;
                                $this->_isBonus = 0;
                            } else {
                                $this->_isBonus = 1;
                                $data['global_promotion'] = $val;
                                unset($val['bonus']);
                            }

                            $data['global_promotion_data'] = $treatmentPromotions[0];
                            if (count($val) + $exemptCount == count($publishedPromotionArray)) {
                                $inputArray['is_perfect'] = 1;
                            } else {
                                $inputArray['is_perfect'] = 0;
                            }
                            $inputArray['interval'] = $treatmentPromotions[0]['interval'];
                            if ($data['global_promotion_data'] && isset($data['global_promotion_data']) && $data['global_promotion_data']['soft_delete'] == 0) {
                                continue;
                            }
                            //calling funtion to record visit for treatment plan.
                            $this->setUserVisitsData($inputArray, $data);
                        }
                    }
                    $i++;
                }
            }
        }
        //Staff can not assign points to unregisterd card number who not have emailid,first name and last name for buzzydoc pratice.
        if ($sessionstaff['is_buzzydoc'] == 1 && $sessionstaff['customer_info']['User']['id'] == 0 && $sessionstaff['customer_info']['first_name'] == '' && $sessionstaff['customer_info']['last_name'] == '' && ($sessionstaff['customer_info']['custom_date'] == '0000-00-00' || $sessionstaff['customer_info']['custom_date'] == '')) {
            $this->Session->setFlash('Before giving points assign this card to a patient', 'default', array(), 'bad');
            return $this->redirect('/PatientManagement/recordpoint/3');
        } else {
            //condition for assign points using promotion.
            if (isset($this->request->data['promo_id'])) {
                //staff can assign points using multiple promotion at a time.
                foreach ($this->request->data['promo_id'] as $pro) {

                    $options1['conditions'] = array(
                        'Promotion.id' => $pro
                    );
                    $promotion = $this->Promotion->find('first', $options1);
                    $multiply = 1;
                    // Code for a logic when "For Check In" promotion is already given to any patient at multiple time then its double the value for promotion.
                    if ($promotion['Promotion']['description'] == 'For Check In' || $promotion['Promotion']['description'] == 'Perfect Score Check-in') {
                        // for dev
                        $optionschktrn['conditions'] = array(
                            'Transaction.user_id' => $this->request->data['user_id'],
                            'Transaction.promotion_id' => $pro
                        );
                        $transcheck = $this->Transaction->find('first', $optionschktrn);
                        if (empty($transcheck)) {
                            $multiply = 1;
                        } else {
                            $multiply = 2;
                        }
                    }
                    if ($sessionstaff['staffaccess']['AccessStaff']['tier_setting'] == 1) {
                        $pointval = (($sessionstaff['staffaccess']['AccessStaff']['base_value'] * $this->request->data['calculate_amount']) / 100) * 50;
                        $this->request->data['amount'] = number_format($pointval, 2, '.', '');
                    }
                    //Condition for promotion oprand value for calculation.
                    if (isset($this->request->data['amount']) && $this->request->data['amount'] != '') {
                        if ($promotion['Promotion']['operand'] == '+') {
                            $amount = (int) $this->request->data['amount'] + (int) ($promotion['Promotion']['value'] * $multiply);
                        } else {
                            $amount = (int) $this->request->data['amount'] * (int) ($promotion['Promotion']['value'] * $multiply);
                        }
                    } else {
                        $amount = $promotion['Promotion']['value'] * $multiply;
                    }

                    //condition for registerd patient to get balance amount.
                    if ($this->request->data['user_id'] != 0) {
                        $balanceamount = $this->Transaction->find('first', array(
                            'conditions' => array(
                                'Transaction.user_id' => $this->request->data['user_id'],
                                'Transaction.clinic_id' => $sessionstaff['clinic_id']
                            ),
                            'fields' => array(
                                'SUM(Transaction.amount) AS points'
                            ),
                            'group' => array(
                                'Transaction.user_id'
                            )
                        ));
                        $balanceamount1 = $this->GlobalRedeem->find('first', array(
                            'conditions' => array(
                                'GlobalRedeem.user_id' => $this->request->data['user_id'],
                                'GlobalRedeem.clinic_id' => $sessionstaff['clinic_id']
                            ),
                            'fields' => array(
                                'SUM(GlobalRedeem.amount) AS points'
                            ),
                            'group' => array(
                                'GlobalRedeem.user_id'
                            )
                        ));
                        $leftamount = $balanceamount[0]['points'] + $balanceamount1[0]['points'];
                    } else {
                        //condition for unregisterd patient to get balance amount.
                        $balanceamount = $this->UnregTransaction->find('first', array(
                            'conditions' => array(
                                'UnregTransaction.card_number' => $this->request->data['card_number'],
                                'UnregTransaction.clinic_id' => $sessionstaff['clinic_id']
                            ),
                            'fields' => array(
                                'SUM(UnregTransaction.amount) AS points'
                            )
                        ));
                        $leftamount = $balanceamount[0]['points'];
                    }
                    $testamount = $amount * (-1);
                    //condition for negative promotion
                    //negative promotion staff can to give to any promotion who have less amount in account in compare to negative promotion.
                    if ($amount < 0 && $testamount > $leftamount) {
                        $notprocide = 1;
                    } else {
                        $notprocide = 0;
                    }
                    //procide allocation if condition full fill.
                    if ($notprocide == 0) {
                        if ($this->request->data['user_id'] != 0) {
                            //accelerated rewards program applicable for own clinic patient only.
                            if ($this->request->data['searchclinic'] == $sessionstaff['clinic_id']) {
                                //get the multiplier value for patinet related to acclerated reward program level.
                                $getval = $this->Api->getPatientLevelForAcceleratedReward($sessionstaff['clinic_id'], $this->request->data['user_id']);
                                $amount = $amount * $getval;
                            }
                            $transe['Transaction'] = array(
                                'user_id' => $this->request->data['user_id'],
                                'card_number' => $this->request->data['card_number'],
                                'first_name' => $this->request->data['first_name1'],
                                'last_name' => $this->request->data['last_name1'],
                                'promotion_id' => $pro,
                                'activity_type' => 'N',
                                'authorization' => $this->request->data['transaction_description'] . ' + ' . $promotion['Promotion']['description'],
                                'amount' => $amount,
                                'clinic_id' => $sessionstaff['clinic_id'],
                                'staff_id' => $this->request->data['staff_id'],
                                'doctor_id' => $this->request->data['doctor_id'],
                                'date' => date('Y-m-d H:i:s'),
                                'status' => 'New',
                                'is_buzzydoc' => $sessionstaff['is_buzzydoc']
                            );
                            $this->Transaction->create();
                            $this->Transaction->save($transe['Transaction']);
                        } else {
                            $transe['UnregTransaction'] = array(
                                'user_id' => $this->request->data['user_id'],
                                'card_number' => $this->request->data['card_number'],
                                'first_name' => $this->request->data['first_name1'],
                                'last_name' => $this->request->data['last_name1'],
                                'promotion_id' => $pro,
                                'activity_type' => 'N',
                                'authorization' => $this->request->data['transaction_description'] . ' + ' . $promotion['Promotion']['description'],
                                'amount' => $amount,
                                'clinic_id' => $sessionstaff['clinic_id'],
                                'staff_id' => $this->request->data['staff_id'],
                                'doctor_id' => $this->request->data['doctor_id'],
                                'date' => date('Y-m-d H:i:s'),
                                'status' => 'New'
                            );
                            $this->UnregTransaction->create();
                            $this->UnregTransaction->save($transe['UnregTransaction']);
                        }
                    }
                }
                //procide allocation if condition full fill.
                if ($notprocide == 0) {
                    //condition for registered patinet.
                    if ($this->request->data['user_id'] != 0) {
                        //condition for buzzydoc clinic.
                        if ($sessionstaff['is_buzzydoc'] == 1) {
                            //getting the balance amount after point allocation and update to account.
                            $alltrans = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $this->request->data['user_id'],
                                    'Transaction.is_buzzydoc' => 1
                                ),
                                'fields' => array(
                                    'SUM(Transaction.amount) AS points'
                                ),
                                'group' => array(
                                    'Transaction.user_id'
                                )
                            ));
                            if (empty($alltrans)) {
                                $points = 0;
                            } else {
                                $points = $alltrans[0]['points'];
                            }

                            $this->User->updateAll(array(
                                'User.points' => $points
                                    ), array(
                                'User.id' => $this->request->data['user_id']
                            ));
                            $localpoint = $this->ClinicUser->find('first', array(
                                'conditions' => array(
                                    'ClinicUser.user_id' => $this->request->data['user_id'],
                                    'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                                )
                            ));
                            $points = $localpoint['ClinicUser']['local_points'] . '(' . $points . ')';
                        } else {
                            //getting the balance amount after point allocation and update to account for legacy patient.
                            $alltrans = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $this->request->data['user_id'],
                                    'Transaction.clinic_id' => $sessionstaff['clinic_id'],
                                    'Transaction.is_buzzydoc !=' => 1
                                ),
                                'fields' => array(
                                    'SUM(Transaction.amount) AS points'
                                ),
                                'group' => array(
                                    'Transaction.user_id'
                                )
                            ));
                            if (empty($alltrans)) {
                                $points = 0;
                            } else {
                                $points = $alltrans[0]['points'];
                            }
                            $this->ClinicUser->updateAll(array(
                                'ClinicUser.local_points' => $points
                                    ), array(
                                'ClinicUser.user_id' => $this->request->data['user_id'],
                                'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                            ));
                            $localpoint = $this->User->find('first', array(
                                'conditions' => array(
                                    'User.id' => $this->request->data['user_id']
                                )
                            ));
                            $points = $points . '(' . $localpoint['User']['points'] . ')';
                        }
                        $usersbg = $this->Transaction->query('SELECT sum(Transaction.amount) as share FROM `transactions` AS `Transaction` where Transaction.activity_type="N" and Transaction.user_id=' . $this->request->data['user_id']);
                        //condition to check user is elegible for any badge after point earn then assign the new badge.
                        foreach ($usersbg as $ug) {
                            if ($ug[0]['share'] > 0) {
                                $optionsbadge['conditions'] = array(
                                    'Badge.value <=' => $ug[0]['share'], 'Badge.clinic_id' => 0
                                );
                                $Badge = $this->Badge->find('all', $optionsbadge);
                                foreach ($Badge as $bg) {
                                    $optionsbadgeuser['conditions'] = array(
                                        'UsersBadge.user_id' => $this->request->data['user_id'],
                                        'UsersBadge.badge_id' => $bg['Badge']['id']
                                    );
                                    $Badgeuser = $this->UsersBadge->find('first', $optionsbadgeuser);
                                    if (empty($Badgeuser)) {
                                        $savebadge['UsersBadge'] = array(
                                            'user_id' => $this->request->data['user_id'],
                                            'badge_id' => $bg['Badge']['id'],
                                            'created_on' => date('Y-m-d H:i:s')
                                        );
                                        $this->UsersBadge->create();
                                        $this->UsersBadge->save($savebadge);
                                    }
                                }
                                if ($sessionstaff['customer_info']['clinic_id'] == $sessionstaff['clinic_id']) {
                                    $optionsbadgeforclinic['conditions'] = array(
                                        'Badge.value <=' => $ug[0]['share'], 'Badge.clinic_id' => $sessionstaff['clinic_id'], 'Badge.value !=' => 0
                                    );
                                    $BadgeClinic = $this->Badge->find('all', $optionsbadgeforclinic);
                                    foreach ($BadgeClinic as $bgc) {
                                        $optionsbadgeusercl['conditions'] = array(
                                            'UsersBadge.user_id' => $this->request->data['user_id'],
                                            'UsersBadge.badge_id' => $bgc['Badge']['id']
                                        );
                                        $BadgeuserCl = $this->UsersBadge->find('first', $optionsbadgeusercl);
                                        if (empty($BadgeuserCl)) {
                                            $savebadgecl['UsersBadge'] = array(
                                                'user_id' => $this->request->data['user_id'],
                                                'badge_id' => $bgc['Badge']['id'],
                                                'created_on' => date('Y-m-d H:i:s')
                                            );
                                            $this->UsersBadge->create();
                                            $this->UsersBadge->save($savebadgecl);
                                        }
                                    }
                                }
                            }
                        }
                        //checking the patient belong to own clinic or not.
                        if ($this->request->data['searchclinic'] == $sessionstaff['clinic_id']) {
                            $getfirstTransaction = $this->Api->get_firsttransaction($this->request->data['user_id'], $sessionstaff['clinic_id']);
                            //condition to check patient get the point first time if yes then send congrats mail to patinet.
                            if ($getfirstTransaction == 1) {
                                $patientclinic = $this->User->find('first', array(
                                    'joins' => array(
                                        array(
                                            'table' => 'clinic_users',
                                            'alias' => 'clinic_users',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'clinic_users.user_id = User.id'
                                            )
                                        ),
                                        array(
                                            'table' => 'clinics',
                                            'alias' => 'Clinics',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'Clinics.id = clinic_users.clinic_id'
                                            )
                                        )
                                    ),
                                    'conditions' => array(
                                        'User.id' => $this->request->data['user_id'],
                                        'Clinics.id' => $sessionstaff['clinic_id']
                                    ),
                                    'fields' => array(
                                        'User.*',
                                        'Clinics.*'
                                    )
                                ));
                                if ($patientclinic['User']['email'] != '' && $amount > 0) {
                                    $template_array = $this->Api->get_template(39);
                                    $link1 = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
                                    $link = str_replace('[points]', $amount, $link1);
                                    $link2 = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link);
                                    $Email = new CakeEmail(MAILTYPE);
                                    $Email->from(array(
                                        SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                    ));

                                    $Email->to($patientclinic['User']['email']);
                                    $Email->subject($template_array['subject'])
                                            ->template('buzzydocother')
                                            ->emailFormat('html');
                                    $Email->viewVars(array(
                                        'msg' => $link2
                                    ));
                                    $Email->send();
                                }
                            }
                        }
                        //Code to check notification setting for patinet.
                        $patients = $this->Notification->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'users',
                                    'alias' => 'Users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Users.id = Notification.user_id'
                                    )
                                ),
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = Users.id'
                                    )
                                ),
                                array(
                                    'table' => 'clinics',
                                    'alias' => 'Clinics',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Clinics.id = clinic_users.clinic_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'Notification.earn_points' => 1,
                                'Users.id' => $this->request->data['user_id'],
                                'Users.email !=' => ''
                            ),
                            'fields' => array(
                                'Users.*',
                                'Clinics.*',
                                'clinic_users.card_number'
                            ),
                            'group' => array(
                                'clinic_users.user_id'
                            )
                        ));
                        //if notification on for earn point reminder then mail goes to patient.
                        if ($amount > 0) {
                            foreach ($patients as $pat) {
                                $template_array = $this->Api->get_template(1);
                                $link = str_replace('[username]', $pat['Users']['first_name'], $template_array['content']);
                                $link1 = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
                                $link2 = str_replace('[clinic_name]', $pat['Clinics']['api_user'], $link1);
                                $link3 = str_replace('[points]', $amount, $link2);
                                $Email = new CakeEmail(MAILTYPE);
                                $Email->from(array(
                                    SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                ));
                                $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($pat['Users']['id']) . "/Unsubscribe";
                                $Email->to($pat['Users']['email']);
                                $Email->subject($template_array['subject'])
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email->viewVars(array(
                                    'msg' => $link3
                                ));
                                $Email->send();
                            }
                        }
                    } else {
                        $alltrans = $this->UnregTransaction->find('first', array(
                            'conditions' => array(
                                'UnregTransaction.user_id' => 0,
                                'UnregTransaction.card_number' => $this->request->data['card_number'],
                                'UnregTransaction.clinic_id' => $sessionstaff['clinic_id']
                            ),
                            'fields' => array(
                                'SUM(UnregTransaction.amount) AS points'
                            ),
                            'group' => array(
                                'UnregTransaction.card_number'
                            )
                        ));
                        if (empty($alltrans)) {
                            $points = 0;
                        } else {
                            $points = $alltrans[0]['points'];
                        }
                    }
                    $this->Session->write('staff.customer_info.total_points', $points);
                }

                if (!empty($this->_badgeMessages) && $notprocide == 0) {
                    $badgeSuccessMessage = $this->request->data['first_name1'] . ' ' . $this->request->data['last_name1'] . implode(', ', $this->_badgeMessages);
                    $this->Session->setFlash('Transaction added successfully.' . '<br>' . $badgeSuccessMessage, 'default', array(), 'good');
                    return $this->redirect('/PatientManagement/recordpoint/2');
                } else if (!empty($this->_badgeMessages) && $notprocide == 1) {
                    $badgeSuccessMessage = $this->request->data['first_name1'] . ' ' . $this->request->data['last_name1'] . implode(', ', $this->_badgeMessages);
                    $this->Session->setFlash('Patient does not have enough point from your practice to recieve ' . $amount . '<br>' . $badgeSuccessMessage, 'default', array(), 'good');
                    return $this->redirect('/PatientManagement/recordpoint/2');
                } else if (empty($this->_badgeMessages) && $notprocide == 1) {

                    $this->Session->setFlash('Patient does not have enough point from your practice to recieve ' . $amount, 'default', array(), 'good');
                    return $this->redirect('/PatientManagement/recordpoint/1');
                } else {
                    $this->Session->setFlash('Transaction added successfully.', 'default', array(), 'good');
                    return $this->redirect('/PatientManagement/recordpoint/2');
                }
            } else {
                //condition to allocate point manually by staff user.
                if (isset($this->request->data['amount']) && $this->request->data['amount'] != '') {
                    if ($sessionstaff['staffaccess']['AccessStaff']['tier_setting'] == 1) {
                        $pointval = (($sessionstaff['staffaccess']['AccessStaff']['base_value'] * $this->request->data['calculate_amount']) / 100) * 50;
                        $this->request->data['amount'] = number_format($pointval, 2, '.', '');
                    }
                    $amount = (int) $this->request->data['amount'];
                    //condition to assign point to registered user.
                    if ($this->request->data['user_id'] != 0) {
                        //if user is belong to own pratice then apply acclerated reward program level.
                        if ($this->request->data['searchclinic'] == $sessionstaff['clinic_id']) {
                            $getval = $this->Api->getPatientLevelForAcceleratedReward($sessionstaff['clinic_id'], $this->request->data['user_id']);
                            $amount = $amount * $getval;
                        }
                        $transe['Transaction'] = array(
                            'user_id' => $this->request->data['user_id'],
                            'card_number' => $this->request->data['card_number'],
                            'first_name' => $this->request->data['first_name1'],
                            'last_name' => $this->request->data['last_name1'],
                            'activity_type' => 'N',
                            'authorization' => $this->request->data['transaction_description'],
                            'amount' => $amount,
                            'clinic_id' => $sessionstaff['clinic_id'],
                            'staff_id' => $this->request->data['staff_id'],
                            'doctor_id' => $this->request->data['doctor_id'],
                            'date' => date('Y-m-d H:i:s'),
                            'status' => 'New',
                            'is_buzzydoc' => $sessionstaff['is_buzzydoc']
                        );

                        $this->Transaction->create();
                        if ($this->Transaction->save($transe['Transaction'])) {
                            if ($sessionstaff['is_buzzydoc'] == 1) {
                                $alltrans = $this->Transaction->find('first', array(
                                    'conditions' => array(
                                        'Transaction.user_id' => $this->request->data['user_id'],
                                        'Transaction.is_buzzydoc' => 1
                                    ),
                                    'fields' => array(
                                        'SUM(Transaction.amount) AS points'
                                    ),
                                    'group' => array(
                                        'Transaction.user_id'
                                    )
                                ));
                                if (empty($alltrans)) {
                                    $points = 0;
                                } else {
                                    $points = $alltrans[0]['points'];
                                }
                                $this->User->updateAll(array(
                                    'User.points' => $points
                                        ), array(
                                    'User.id' => $this->request->data['user_id']
                                ));
                                $localpoint = $this->ClinicUser->find('first', array(
                                    'conditions' => array(
                                        'ClinicUser.user_id' => $this->request->data['user_id'],
                                        'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                                    )
                                ));
                                $points = $localpoint['ClinicUser']['local_points'] . '(' . $points . ')';
                            } else {
                                $alltrans = $this->Transaction->find('first', array(
                                    'conditions' => array(
                                        'Transaction.user_id' => $this->request->data['user_id'],
                                        'Transaction.clinic_id' => $sessionstaff['clinic_id'],
                                        'Transaction.is_buzzydoc !=' => 1
                                    ),
                                    'fields' => array(
                                        'SUM(Transaction.amount) AS points'
                                    ),
                                    'group' => array(
                                        'Transaction.user_id'
                                    )
                                ));
                                if (empty($alltrans)) {
                                    $points = 0;
                                } else {
                                    $points = $alltrans[0]['points'];
                                }
                                $this->ClinicUser->updateAll(array(
                                    'ClinicUser.local_points' => $points
                                        ), array(
                                    'ClinicUser.user_id' => $this->request->data['user_id'],
                                    'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                                ));
                                $localpoint = $this->User->find('first', array(
                                    'conditions' => array(
                                        'User.id' => $this->request->data['user_id']
                                    )
                                ));
                                $points = $points . '(' . $localpoint['User']['points'] . ')';
                            }
                            $usersbg = $this->Transaction->query('SELECT sum(Transaction.amount) as share FROM `transactions` AS `Transaction` where Transaction.activity_type="N" and Transaction.user_id=' . $this->request->data['user_id']);
                            //code to allot new badge if availabe points come in badge range for user.
                            foreach ($usersbg as $ug) {
                                if ($ug[0]['share'] > 0) {
                                    $optionsbadge['conditions'] = array(
                                        'Badge.value <=' => $ug[0]['share'], 'Badge.clinic_id' => 0
                                    );
                                    $Badge = $this->Badge->find('all', $optionsbadge);
                                    foreach ($Badge as $bg) {
                                        $optionsbadgeuser['conditions'] = array(
                                            'UsersBadge.user_id' => $this->request->data['user_id'],
                                            'UsersBadge.badge_id' => $bg['Badge']['id']
                                        );
                                        $Badgeuser = $this->UsersBadge->find('first', $optionsbadgeuser);
                                        if (empty($Badgeuser)) {
                                            $savebadge['UsersBadge'] = array(
                                                'user_id' => $this->request->data['user_id'],
                                                'badge_id' => $bg['Badge']['id'],
                                                'created_on' => date('Y-m-d H:i:s')
                                            );
                                            $this->UsersBadge->create();
                                            $this->UsersBadge->save($savebadge);
                                        }
                                    }
                                }
                            }
                            if ($this->request->data['searchclinic'] == $sessionstaff['clinic_id']) {
                                $getfirstTransaction = $this->Api->get_firsttransaction($this->request->data['user_id'], $sessionstaff['clinic_id']);
                                if ($getfirstTransaction == 1) {
                                    $patientclinic = $this->User->find('first', array(
                                        'joins' => array(
                                            array(
                                                'table' => 'clinic_users',
                                                'alias' => 'clinic_users',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'clinic_users.user_id = User.id'
                                                )
                                            ),
                                            array(
                                                'table' => 'clinics',
                                                'alias' => 'Clinics',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'Clinics.id = clinic_users.clinic_id'
                                                )
                                            )
                                        ),
                                        'conditions' => array(
                                            'User.id' => $this->request->data['user_id'],
                                            'Clinics.id' => $sessionstaff['clinic_id']
                                        ),
                                        'fields' => array(
                                            'User.*',
                                            'Clinics.*'
                                        )
                                    ));
                                    if ($patientclinic['User']['email'] != '' && $amount > 0) {
                                        $template_array = $this->Api->get_template(39);
                                        $link1 = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
                                        $link = str_replace('[points]', $amount, $link1);
                                        $link2 = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link);
                                        $Email = new CakeEmail(MAILTYPE);
                                        $Email->from(array(
                                            SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                        ));
                                        $Email->to($patientclinic['User']['email']);
                                        $Email->subject($template_array['subject'])
                                                ->template('buzzydocother')
                                                ->emailFormat('html');
                                        $Email->viewVars(array(
                                            'msg' => $link2
                                        ));
                                        $Email->send();
                                    }
                                }
                            }
                            //checking the notification setting for patient.
                            $patients = $this->Notification->find('all', array(
                                'joins' => array(
                                    array(
                                        'table' => 'users',
                                        'alias' => 'Users',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'Users.id = Notification.user_id'
                                        )
                                    ),
                                    array(
                                        'table' => 'clinic_users',
                                        'alias' => 'clinic_users',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'clinic_users.user_id = Users.id'
                                        )
                                    ),
                                    array(
                                        'table' => 'clinics',
                                        'alias' => 'Clinics',
                                        'type' => 'INNER',
                                        'conditions' => array(
                                            'Clinics.id = clinic_users.clinic_id'
                                        )
                                    )
                                ),
                                'conditions' => array(
                                    'Notification.earn_points' => 1,
                                    'Users.id' => $this->request->data['user_id'],
                                    'Users.email !=' => ''
                                ),
                                'fields' => array(
                                    'Users.*',
                                    'Clinics.*',
                                    'clinic_users.card_number'
                                ),
                                'group' => array(
                                    'clinic_users.user_id'
                                )
                            ));
                            if ($amount > 0) {
                                foreach ($patients as $pat) {
                                    $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($pat['Users']['id']) . "/Unsubscribe";
                                    $template_array = $this->Api->get_template(1);
                                    $link = str_replace('[username]', $pat['Users']['first_name'], $template_array['content']);
                                    $link1 = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
                                    $link2 = str_replace('[clinic_name]', $pat['Clinics']['api_user'], $link1);
                                    $link3 = str_replace('[points]', $amount, $link2);
                                    $Email = new CakeEmail(MAILTYPE);
                                    $Email->from(array(
                                        SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                    ));
                                    $Email->to($pat['Users']['email']);
                                    $Email->subject($template_array['subject'])
                                            ->template('buzzydocother')
                                            ->emailFormat('html');
                                    $Email->viewVars(array(
                                        'msg' => $link3
                                    ));
                                    $Email->send();
                                }
                            }
                            $this->Session->write('staff.customer_info.total_points', $points);
                            if (!empty($this->_badgeMessages)) {
                                $badgeSuccessMessage = $this->request->data['first_name1'] . ' ' . $this->request->data['last_name1'] . implode(', ', $this->_badgeMessages);
                                $this->Session->setFlash('Transaction added successfully.' . '<br>' . $badgeSuccessMessage, 'default', array(), 'good');
                            } else {
                                $this->Session->setFlash('Transaction added successfully.', 'default', array(), 'good');
                            }
                            return $this->redirect('/PatientManagement/recordpoint/2');
                        } else {
                            $this->Session->setFlash('Transaction not added', 'default', array(), 'bad');
                            return $this->redirect('/PatientManagement/recordpoint/1');
                        }
                    } else {
                        //allocate manual points to unregistered patinet.
                        $transe['UnregTransaction'] = array(
                            'user_id' => $this->request->data['user_id'],
                            'card_number' => $this->request->data['card_number'],
                            'first_name' => $this->request->data['first_name'],
                            'last_name' => $this->request->data['last_name'],
                            'promotion_id' => $pro,
                            'activity_type' => 'N',
                            'authorization' => $this->request->data['transaction_description'] . ' + ' . $promotion['Promotion']['description'],
                            'amount' => $amount,
                            'clinic_id' => $sessionstaff['clinic_id'],
                            'staff_id' => $this->request->data['staff_id'],
                            'doctor_id' => $this->request->data['doctor_id'],
                            'date' => date('Y-m-d H:i:s'),
                            'status' => 'New'
                        );
                        $this->UnregTransaction->create();
                        $this->UnregTransaction->save($transe['UnregTransaction']);

                        $alltrans = $this->UnregTransaction->find('first', array(
                            'conditions' => array(
                                'UnregTransaction.user_id' => 0,
                                'UnregTransaction.card_number' => $this->request->data['card_number'],
                                'UnregTransaction.clinic_id' => $sessionstaff['clinic_id']
                            ),
                            'fields' => array(
                                'SUM(UnregTransaction.amount) AS points'
                            ),
                            'group' => array(
                                'UnregTransaction.card_number'
                            )
                        ));
                        if (empty($alltrans)) {
                            $points = 0;
                        } else {
                            $points = $alltrans[0]['points'];
                        }
                        $this->Session->write('staff.customer_info.total_points', $points);
                        if (!empty($this->_badgeMessages)) {
                            $badgeSuccessMessage = $this->request->data['first_name1'] . ' ' . $this->request->data['last_name1'] . implode(', ', $this->_badgeMessages);
                            $this->Session->setFlash('Transaction added successfully.' . '<br>' . $badgeSuccessMessage, 'default', array(), 'good');
                        } else {
                            $this->Session->setFlash('Transaction added successfully.', 'default', array(), 'good');
                        }
                        return $this->redirect('/PatientManagement/recordpoint/2');
                    }
                } else if (isset($data['global_promo'])) {
                    if (!empty($this->_badgeMessages)) {
                        $badgeSuccessMessage = $this->request->data['first_name1'] . ' ' . $this->request->data['last_name1'] . implode(', ', $this->_badgeMessages);
                        $this->Session->setFlash($badgeSuccessMessage, 'default', array(), 'good');
                    }
                    return $this->redirect('/PatientManagement/recordpoint/2');
                } else if (!empty($sessionstaff['bonus_treatment'])) {
                    return $this->redirect('/PatientManagement/recordpoint/2');
                } else {
                    $this->Session->setFlash('Please fill an amount or select any accomplishment', 'default', array(), 'bad');
                    return $this->redirect('/PatientManagement/recordpoint/1');
                }
            }
        }

        exit();
    }

    /**
     * Delete Transaction history for registered and unregistered patient by staff user.
     * @param type $tid
     * @param type $cid
     * @param type $card
     * @return type
     */
    public function deletehistory() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if ($_POST['user_id'] != 0) {
            $transdet = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.id' => $_POST['tid']
                )
            ));
            $getglberedem = $this->GlobalRedeem->find('first', array(
                'conditions' => array(
                    'GlobalRedeem.clinic_id' => $sessionstaff['clinic_id'],
                    'GlobalRedeem.user_id' => $_POST['user_id']
                ),
                'fields' => array('sum(GlobalRedeem.amount) AS total')
            ));
            $paytoclinic = 0;
            if ($getglberedem[0]['total'] != '') {
                $paytoclinic = $paytoclinic + $getglberedem[0]['total'];
            }
            $getlocalredem = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.clinic_id' => $sessionstaff['clinic_id'],
                    'Transaction.user_id' => $_POST['user_id']
                ),
                'fields' => array('sum(Transaction.amount) AS total')
            ));
            if ($getlocalredem[0]['total'] != '') {
                $paytoclinic = $paytoclinic + $getlocalredem[0]['total'];
            }
            if (!empty($transdet) && $transdet['Transaction']['amount'] <= $paytoclinic) {
                if ($this->Transaction->deleteAll(array('Transaction.id' => $_POST['tid'], 'Transaction.user_id' => $_POST['user_id'], 'Transaction.activity_type' => 'N'))) {

                    $transedel['TransactionDeleteLog'] = array(
                        'user_id' => $transdet['Transaction']['user_id'],
                        'staff_id' => $sessionstaff['staff_id'],
                        'card_number' => $transdet['Transaction']['card_number'],
                        'first_name' => $transdet['Transaction']['first_name'],
                        'last_name' => $transdet['Transaction']['last_name'],
                        'activity_type' => $transdet['Transaction']['activity_type'],
                        'promotion_id' => $transdet['Transaction']['promotion_id'],
                        'reward_id' => $transdet['Transaction']['reward_id'],
                        'authorization' => $transdet['Transaction']['authorization'],
                        'amount' => $transdet['Transaction']['amount'],
                        'clinic_id' => $transdet['Transaction']['clinic_id'],
                        'date' => date('Y-m-d H:i:s'),
                        'status' => $transdet['Transaction']['status']
                    );
                    $this->TransactionDeleteLog->create();
                    $this->TransactionDeleteLog->save($transedel['TransactionDeleteLog']);
                    $alltrans = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $_POST['user_id'],
                            'Transaction.is_buzzydoc' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.user_id'
                        )
                    ));
                    if (empty($alltrans)) {
                        $points = 0;
                    } else {
                        $points = $alltrans[0]['points'];
                    }
                    $this->User->updateAll(array(
                        'User.points' => $points
                            ), array(
                        'User.id' => $_POST['user_id']
                    ));

                    $alltrans1 = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $_POST['user_id'],
                            'Transaction.clinic_id' => $sessionstaff['clinic_id'],
                            'Transaction.is_buzzydoc !=' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.user_id'
                        )
                    ));
                    if (empty($alltrans1)) {
                        $points1 = 0;
                    } else {
                        $points1 = $alltrans1[0]['points'];
                    }
                    $this->ClinicUser->updateAll(array(
                        'ClinicUser.local_points' => $points1
                            ), array(
                        'ClinicUser.user_id' => $_POST['user_id'],
                        'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                    ));
                    if ($sessionstaff['is_buzzydoc'] == 1) {
                        $points2 = $points1 . '(' . $points . ')';
                    } else {
                        $points2 = $points1 . '(' . $points . ')';
                    }
                    $this->Session->write('staff.customer_info.total_points', $points2);
                    echo 1;
                } else {

                    echo 3;
                }
            } else {

                echo 2;
            }
        } else {

            $transdet = $this->UnregTransaction->find('first', array(
                'conditions' => array(
                    'UnregTransaction.id' => $_POST['tid']
                )
            ));

            $transedel['TransactionDeleteLog'] = array(
                'user_id' => $transdet['UnregTransaction']['user_id'],
                'staff_id' => $sessionstaff['staff_id'],
                'card_number' => $transdet['UnregTransaction']['card_number'],
                'first_name' => $transdet['UnregTransaction']['first_name'],
                'last_name' => $transdet['UnregTransaction']['last_name'],
                'activity_type' => $transdet['UnregTransaction']['activity_type'],
                'promotion_id' => $transdet['UnregTransaction']['promotion_id'],
                'reward_id' => $transdet['UnregTransaction']['reward_id'],
                'authorization' => $transdet['UnregTransaction']['authorization'],
                'amount' => $transdet['UnregTransaction']['amount'],
                'clinic_id' => $transdet['UnregTransaction']['clinic_id'],
                'date' => date('Y-m-d H:i:s'),
                'status' => $transdet['UnregTransaction']['status']
            );
            $this->TransactionDeleteLog->create();
            $this->TransactionDeleteLog->save($transedel['TransactionDeleteLog']);

            if ($this->UnregTransaction->deleteAll(array(
                        'UnregTransaction.id' => $_POST['tid'],
                        'UnregTransaction.user_id' => $_POST['user_id'],
                        'UnregTransaction.card_number' => $_POST['card_number']
                    ))) {
                $alltrans = $this->UnregTransaction->find('first', array(
                    'conditions' => array(
                        'UnregTransaction.user_id' => $_POST['user_id'],
                        'UnregTransaction.card_number' => $_POST['card_number'],
                        'UnregTransaction.clinic_id' => $sessionstaff['clinic_id']
                    ),
                    'fields' => array(
                        'SUM(UnregTransaction.amount) AS points'
                    ),
                    'group' => array(
                        'UnregTransaction.card_number'
                    )
                ));
                if (empty($alltrans)) {
                    $unregpoints = 0;
                } else {
                    $unregpoints = $alltrans[0]['points'];
                }
                $this->Session->write('staff.customer_info.total_points', $unregpoints);
                echo 1;
            } else {

                echo 3;
            }
        }
        die;
    }

    /**
     * Get city listing according to state.
     */
    public function getcity() {
        $this->layout = "";
        $options['joins'] = array(
            array(
                'table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $_POST['state'] . '"'
                )
            )
        );
        $options['fields'] = array(
            'City.city'
        );
        $options['order'] = array(
            'City.city asc'
        );
        $cityresult = $this->City->find('all', $options);
        $html = '<option value="">Select City</option>';
        foreach ($cityresult as $ct) {

            $html .= '<option value="';
            $html .= $ct["City"]["city"];
            $html .= '">';
            $html .= $ct["City"]["city"];
            $html .= '</option>';
        }
        echo $html;
        exit();
    }

    /**
     * End of treatmnet plan by staff user for any patient.
     */
    public function deactivatetreatment() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');

        $deactivetret['user_id'] = $_POST['user_id'];
        $deactivetret['treatment_id'] = $_POST['treatment_id'];
        $deactivetret['clinic_id'] = $sessionstaff['clinic_id'];
        $deactivetret['status'] = 1;
        $deactivetret['created_on'] = date('Y-m-d H:i:s');
        $this->UserAssignedTreatment->create();
        $this->UserAssignedTreatment->save($deactivetret);
        $lastvisit['conditions'] = array('UserPerfectVisit.clinic_id' => $sessionstaff['clinic_id'], 'UserPerfectVisit.user_id' => $_POST['user_id'], 'UserPerfectVisit.level_up_settings_id' => $_POST['treatment_id']);
        $lastvisit['order'] = array('UserPerfectVisit.date desc');
        $checktreamntexist = $this->UserPerfectVisit->find('first', $lastvisit);
        $queryup = 'update user_perfect_visits set is_treatment_over=1 where id=' . $checktreamntexist['UserPerfectVisit']['id'];
        $this->UserPerfectVisit->query($queryup);

        $treatment_over = array();
        if (!empty($sessionstaff['customer_info'])) {
            $treatmentover = $this->UpperLevelSetting->find('all', array(
                'conditions' => array(
                    'UpperLevelSetting.clinic_id' => $sessionstaff['clinic_id'], 'UpperLevelSetting.interval' => $sessionstaff['staffaccess']['AccessStaff']['interval'])
            ));


            if (!empty($treatmentover)) {
                $tret = 0;
                foreach ($treatmentover as $treat) {
                    $check['conditions'] = array('UserAssignedTreatment.clinic_id' => $sessionstaff['clinic_id'], 'user_id' => $sessionstaff['customer_info']['User']['id'], 'UserAssignedTreatment.treatment_id' => $treat['UpperLevelSetting']['id']);
                    $checktreamntexist = $this->UserAssignedTreatment->find('first', $check);
                    if (empty($checktreamntexist)) {
                        $treatment_over[$tret] = array('treatment_id' => $treat['UpperLevelSetting']['id'], 'treatment_name' => $treat['UpperLevelSetting']['treatment_name']);
                        $tret++;
                    }
                }
            }
            $visitcheck = $this->UserPerfectVisit->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'upper_level_settings',
                        'alias' => 'UpperLevelSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                        )
                    )
                ),
                'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*'),
                'conditions' => array(
                    'UserPerfectVisit.user_id' => $sessionstaff['customer_info']['User']['id'],
                    'UserPerfectVisit.clinic_id' => $sessionstaff['clinic_id'], 'UpperLevelSetting.interval' => $sessionstaff['staffaccess']['AccessStaff']['interval']),
                'group' => array('UserPerfectVisit.level_up_settings_id')
            ));
            $visithistory = $this->UserPerfectVisit->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'upper_level_settings',
                        'alias' => 'UpperLevelSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                        )
                    )
                ),
                'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*'),
                'conditions' => array(
                    'UserPerfectVisit.user_id' => $sessionstaff['customer_info']['User']['id'],
                    'UserPerfectVisit.clinic_id' => $sessionstaff['clinic_id'], 'UpperLevelSetting.interval' => $sessionstaff['staffaccess']['AccessStaff']['interval']),
                'order' => array('UserPerfectVisit.level_up_settings_id desc,UserPerfectVisit.date asc')
            ));
            $levelhistory = array();
            foreach ($visithistory as $history) {
                if ($history['UserPerfectVisit']['is_perfect'] == 1)
                    $perfect = 'Perfect';
                else
                    $perfect = 'Not Perfect';
                if ($history['UserPerfectVisit']['is_treatment_over'] == 1)
                    $treatment_over = 'End';
                else
                    $treatment_over = '--';
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['record'][] = array('perfect' => $perfect, 'level_status' => $history['UserPerfectVisit']['level_achieved'], 'date' => $history['UserPerfectVisit']['date'], 'status' => $treatment_over1);
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['treatment_details'] = $history['UpperLevelSetting'];
            }

            $this->Session->write('staff.customer_info.visithistory', $levelhistory);

            $this->Session->write('staff.customer_info.visitcheck', $visitcheck);
            $this->Session->write('staff.customer_info.treatment_over', $treatment_over);
        } else {
            $this->Session->write('staff.customer_info.visithistory', array());
            $this->Session->write('staff.customer_info.visitcheck', array());
            $this->Session->write('staff.customer_info.treatment_over', array());
        }
        if (empty($treatment_over)) {
            echo '';
        } else {
            $treatmentoption = '';

            foreach ($treatment_over as $over) {

                $treatmentoption .=' <option value="' . $over['treatment_id'] . '">' . $over['treatment_name'] . '</option>';
            }
            echo $treatmentoption;
        }

        exit;
    }

    /**
     * Update unregistered card number and tag any email to card.
     * @return type
     */
    public function updateunregcustomer() {
        $sessionpatient = $this->Session->read('staff');
        if (isset($this->request->data['parents_email'])) {
            $email = $this->request->data['parents_email'];
            $username = $this->request->data['aemail'];
        } else {
            $email = $this->request->data['email'];
            $username = '';
        }
        $unrescard = $this->CardNumber->find('all', array(
            'conditions' => array(
                'CardNumber.clinic_id' => $sessionpatient['clinic_id'],
                'CardNumber.status' => 1,
                'CardNumber.card_number !=' => $this->request->data['card_number']
            )
        ));

        $check = 0;
        //cindition to check email id tag for patient is adult or child.
        if (isset($_POST['parents_email'])) {
            $users_field_check = $this->User->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.clinic_id' => $sessionpatient['clinic_id'],
                    'User.email' => $_POST['parents_email'],
                    'User.parents_email' => $_POST['aemail'],
                    'User.email !=' => '',
                    'User.parents_email !=' => ''
                ),
                'fields' => array(
                    'User.id'
                )
            ));
            if (empty($users_field_check)) {
                $users_field_check = $this->User->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'clinic_users.clinic_id' => $sessionpatient['clinic_id'],
                        'User.parents_email' => $_POST['aemail'],
                        'User.parents_email !=' => ''
                    ),
                    'fields' => array(
                        'User.id'
                    )
                ));
            }
            foreach ($unrescard as $unas) {
                $det = json_decode($unas['CardNumber']['text']);
                if ($det->email == $email && isset($det->parents_email) && $det->parents_email == $username && $email != '') {
                    $check = 1;
                }
                if (isset($det->parents_email) && $det->parents_email == $username && $username != '') {
                    $check = 1;
                }
            }
        } else {
            $date18age = date("Y-m-d", strtotime("-18 year"));
            $users_field_check = $this->User->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.clinic_id' => $sessionpatient['clinic_id'],
                    'User.email' => $_POST['email'],
                    'User.email !=' => '',
                    'User.custom_date <=' => $date13age
                ),
                'fields' => array(
                    'User.id'
                )
            ));
            foreach ($unrescard as $unas) {
                $det = json_decode($unas['CardNumber']['text']);
                if ($det->email == $email && isset($det->parents_email) && $det->parents_email == '' && $email != '') {
                    $check = 1;
                }
            }
        }

        if (empty($users_field_check) && $check == 0) {

            $dob = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];
            $text = json_encode(array(
                'first_name' => $this->request->data['first_name'],
                'last_name' => $this->request->data['last_name'],
                'email' => $email,
                'parents_email' => $username,
                'custom_date' => $dob
            ));
            $query = 'update card_numbers set text="' . addslashes($text) . '" where clinic_id=' . $sessionpatient['clinic_id'] . ' and card_number=' . $this->request->data['card_number'];
            $unrescard = $this->CardNumber->query($query);
            $this->Session->write('staff.customer_info.first_name', $this->request->data['first_name']);
            $this->Session->write('staff.customer_info.last_name', $this->request->data['last_name']);
            $this->Session->write('staff.customer_info.email', $email);
            $this->Session->write('staff.customer_info.parents_email', $username);
            $this->Session->write('staff.customer_info.custom_date', $dob);
            if ($sessionpatient['is_buzzydoc'] == 0) {
                $this->Session->setFlash('Selected record updated successfully.', 'default', array(), 'good');
            } else {
                $this->Session->setFlash('Patient info is saved successfully. Staff can now proceed with giving points to user. Card will remain unassigned until email address is updated.', 'default', array(), 'good');
            }

            // $this->redirect(array('action' => 'patientinfo'));
            return $this->redirect('/PatientManagement/recordpoint/3');
        } else {
            $this->Session->setFlash('User already exists', 'default', array(), 'bad');
            return $this->redirect('/PatientManagement/recordpoint/3');
        }
    }

    /**
     * Assign card number to patient for buzzydoc pratice.
     * If email id registered with any other pratice card number then link the card number.
     * @return type
     */
    public function assigncard() {
        $sessionpatient = $this->Session->read('staff');
        //condition for link card number for existing user.
        if ($this->request->is('post') && isset($this->request->data['type']) && $this->request->data['type'] == 1) {
            $options['conditions'] = array(
                'User.id' => $this->request->data['uid']
            );

            $user1 = $this->User->find('first', $options);
            if (!empty($user1)) {
                $optionscl['conditions'] = array(
                    'ClinicUser.user_id' => $user1['User']['id'],
                    'ClinicUser.card_number' => $this->request->data['card_number']
                );

                $userclinic = $this->ClinicUser->find('first', $optionscl);

                if (empty($userclinic)) {
                    $alltrans = $this->UnregTransaction->find('all', array(
                        'conditions' => array(
                            'UnregTransaction.user_id' => 0,
                            'UnregTransaction.card_number' => $this->request->data['card_number'],
                            'UnregTransaction.clinic_id' => $sessionpatient['clinic_id']
                        )
                    ));
                    //if card number have transaction then copy to new users account.
                    foreach ($alltrans as $newtran) {
                        $datatrans['user_id'] = $user1['User']['id'];
                        $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                        $datatrans['card_number'] = $this->request->data['card_number'];
                        $datatrans['first_name'] = $user1['User']['first_name'];
                        $datatrans['last_name'] = $user1['User']['last_name'];
                        $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                        $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                        $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                        $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                        $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                        $datatrans['date'] = $newtran['UnregTransaction']['date'];
                        $datatrans['status'] = $newtran['UnregTransaction']['status'];
                        $datatrans['is_buzzydoc'] = 1;
                        $this->Transaction->create();
                        $this->Transaction->save($datatrans);
                        $this->UnregTransaction->deleteAll(array(
                            'UnregTransaction.id' => $newtran['UnregTransaction']['id'],
                            false
                        ));
                    }

                    $allpoints = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $user1['User']['id'],
                            'Transaction.clinic_id' => $sessionpatient['clinic_id'],
                            'Transaction.is_buzzydoc' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.card_number'
                        )
                    ));

                    if ($allpoints[0]['points'] > 0) {
                        $newpoints = $allpoints[0]['points'] + $user1['points'];
                    } else {
                        $newpoints = 0 + $allpoints[0]['points'];
                    }

                    $Patients_array['ClinicUser'] = array(
                        'clinic_id' => $sessionpatient['clinic_id'],
                        'user_id' => $user1['User']['id'],
                        'card_number' => $this->request->data['card_number'],
                        'local_points' => 0
                    );
                    $this->ClinicUser->create();
                    $this->ClinicUser->save($Patients_array);
                    //update card number status.
                    $query = 'update card_numbers set status=2,text="" where clinic_id=' . $sessionpatient['clinic_id'] . ' and card_number=' . $this->request->data['card_number'];
                    $unrescard = $this->CardNumber->query($query);
                    $queryuser = 'update users set points=' . $newpoints . ' where id=' . $user1['User']['id'];
                    $usersave = $this->User->query($queryuser);

                    $data = array();
                    //updating profile filed.
                    $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $user1['User']['id'];
                    $users_field = $this->ProfileFieldUser->query($sQuery);

                    $data[$user1['User']['id']] = $users_field;
                    $data[$user1['User']['id']]['card_number'] = $this->request->data['card_number'];
                    $data[$user1['User']['id']]['custom_date'] = $user1['User']['custom_date'];
                    $data[$user1['User']['id']]['email'] = $user1['User']['email'];
                    $data[$user1['User']['id']]['parents_email'] = $user1['User']['parents_email'];
                    $data[$user1['User']['id']]['first_name'] = $user1['User']['first_name'];
                    $data[$user1['User']['id']]['last_name'] = $user1['User']['last_name'];
                    $data[$user1['User']['id']]['customer_password'] = $user1['User']['password'];
                    $data[$user1['User']['id']]['status'] = $user1['User']['status'];
                    $data[$user1['User']['id']]['clinic_id'] = $sessionpatient['clinic_id'];
                    if ($sessionpatient['is_buzzydoc'] == 1) {
                        $data[$user1['User']['id']]['total_points'] = '0(' . $newpoints . ')';
                    } else {
                        $data[$user1['User']['id']]['total_points'] = 0;
                    }
                    $data[$user1['User']['id']]['User'] = $user1['User'];

                    foreach ($data as $dt) {
                        $this->Session->write('staff.customer_info', $dt);
                    }
                    $this->Session->write('staff.customer_search_results', array());
                    $this->Session->delete('staff.customer_search_results');
                    $this->Session->write('staff.unreg_customer_search_results', array());
                    $this->Session->delete('staff.unreg_customer_search_results');

                    $clinic = $this->Clinic->find('first', array(
                        'conditions' => array(
                            'Clinic.id' => $sessionpatient['clinic_id']
                        )
                    ));
                    if ($user1['User']['email'] != '') {
                        $template_array = $this->Api->get_template(2);
                        $link = str_replace('[username]', $user1['User']['first_name'], $template_array['content']);
                        $link1 = str_replace('[link_url]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">' . rtrim($clinic['Clinic']['patient_url'], '/') . '</a>', $link);
                        $link2 = str_replace('[card_number]', $this->request->data['card_number'], $link1);
                        $link3 = str_replace('[password]', $user1['User']['password'], $link2);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(
                            SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                        ));
                        $Email->to($user1['User']['email']);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array(
                            'msg' => $link3
                        ));
                        $Email->send();
                    }
                    $this->Session->setFlash('BuzzyDoc Rewards Card Linked Successfully', 'default', array(), 'good');

                    // $this->redirect(array('action' => 'patientinfo'));
                    return $this->redirect('/PatientManagement/recordpoint/3');
                } else {
                    $this->Session->setFlash('User already exists', 'default', array(), 'bad');

                    // $this->redirect(array('action' => 'patientinfo'));
                    return $this->redirect('/PatientManagement/recordpoint/3');
                }
            }
        } else {
            //assign card number to patient.
            $is_verified = 1;
            //conidtion to check card is assign to adult or child.
            if (isset($this->request->data['parents_email'])) {
                $options['conditions'] = array(
                    'User.email' => $this->request->data['parents_email'],
                    'User.parents_email' => $this->request->data['aemail']
                );
                $user1 = $this->User->find('first', $options);
                if (empty($user1) && $this->request->data['aemail'] != '') {
                    $options['conditions'] = array(
                        'User.parents_email' => $this->request->data['aemail']
                    );
                    $user1 = $this->User->find('first', $options);
                }

                if ($this->request->data['emailprovide'] == 'own') {
                    $is_verified = 1;
                } else {
                    $is_verified = 0;
                }
            } else {
                $is_verified = 1;
                $date13age = date("Y-m-d", strtotime("-18 year"));
                $options['conditions'] = array(
                    'User.email' => $this->request->data['email'],
                    'User.custom_date <=' => $date13age
                );
                $user1 = $this->User->find('first', $options);
            }
            //condition for assigning the card to buzzydoc patient.
            if (!empty($user1)) {
                $optionscl['conditions'] = array(
                    'ClinicUser.user_id' => $user1['User']['id']
                );

                $userclinic = $this->ClinicUser->find('first', $optionscl);

                if (empty($userclinic)) {
                    $alltrans = $this->UnregTransaction->find('all', array(
                        'conditions' => array(
                            'UnregTransaction.user_id' => 0,
                            'UnregTransaction.card_number' => $this->request->data['card_number'],
                            'UnregTransaction.clinic_id' => $sessionpatient['clinic_id']
                        )
                    ));

                    foreach ($alltrans as $newtran) {
                        $datatrans['user_id'] = $user1['User']['id'];
                        $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                        $datatrans['card_number'] = $this->request->data['card_number'];
                        $datatrans['first_name'] = $user1['User']['first_name'];
                        $datatrans['last_name'] = $user1['User']['last_name'];
                        $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                        $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                        $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                        $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                        $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                        $datatrans['date'] = $newtran['UnregTransaction']['date'];
                        $datatrans['status'] = $newtran['UnregTransaction']['status'];
                        $datatrans['is_buzzydoc'] = 1;
                        $this->Transaction->create();
                        $this->Transaction->save($datatrans);
                        $this->UnregTransaction->deleteAll(array(
                            'UnregTransaction.id' => $newtran['UnregTransaction']['id'],
                            false
                        ));
                    }

                    $allpoints = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $user1['User']['id'],
                            'Transaction.clinic_id' => $sessionpatient['clinic_id'],
                            'Transaction.is_buzzydoc' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.card_number'
                        )
                    ));

                    if ($allpoints[0]['points'] > 0) {
                        $newpoints = $allpoints[0]['points'] + $user1['points'];
                    } else {
                        $newpoints = 0 + $allpoints[0]['points'] + $user1['points'];
                    }

                    $Patients_array['ClinicUser'] = array(
                        'clinic_id' => $sessionpatient['clinic_id'],
                        'user_id' => $user1['User']['id'],
                        'card_number' => $this->request->data['card_number'],
                        'local_points' => 0
                    );
                    $this->ClinicUser->create();
                    $this->ClinicUser->save($Patients_array);
                    $query = 'update card_numbers set status=2 where clinic_id=' . $sessionpatient['clinic_id'] . ' and card_number=' . $this->request->data['card_number'];
                    $unrescard = $this->CardNumber->query($query);
                    $queryuser = 'update users set is_buzzydoc=0,points=' . $newpoints . ' where id=' . $user1['User']['id'];
                    $usersave = $this->User->query($queryuser);

                    $data = array();

                    $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $user1['User']['id'];
                    $users_field = $this->ProfileFieldUser->query($sQuery);

                    $data[$user1['User']['id']] = $users_field;
                    $data[$user1['User']['id']]['card_number'] = $this->request->data['card_number'];
                    $data[$user1['User']['id']]['custom_date'] = $user1['User']['custom_date'];
                    $data[$user1['User']['id']]['email'] = $user1['User']['email'];
                    $data[$user1['User']['id']]['parents_email'] = $user1['User']['parents_email'];
                    $data[$user1['User']['id']]['first_name'] = $user1['User']['first_name'];
                    $data[$user1['User']['id']]['last_name'] = $user1['User']['last_name'];
                    $data[$user1['User']['id']]['customer_password'] = $user1['User']['password'];
                    $data[$user1['User']['id']]['status'] = $user1['User']['status'];
                    $data[$user1['User']['id']]['clinic_id'] = $sessionpatient['clinic_id'];
                    if ($sessionpatient['is_buzzydoc'] == 1) {
                        $data[$user1['User']['id']]['total_points'] = '0(' . $newpoints . ')';
                    } else {
                        $data[$user1['User']['id']]['total_points'] = 0;
                    }
                    $data[$user1['User']['id']]['User'] = $user1['User'];

                    foreach ($data as $dt) {
                        $this->Session->write('staff.customer_info', $dt);
                    }
                    $this->Session->write('staff.customer_search_results', array());
                    $this->Session->delete('staff.customer_search_results');
                    $this->Session->write('staff.unreg_customer_search_results', array());
                    $this->Session->delete('staff.unreg_customer_search_results');

                    $clinic = $this->Clinic->find('first', array(
                        'conditions' => array(
                            'Clinic.id' => $sessionpatient['clinic_id']
                        )
                    ));
                    if ($user1['User']['email'] != '') {
                        $template_array = $this->Api->get_template(3);
                        $link = str_replace('[username]', $user1['User']['first_name'], $template_array['content']);
                        $link1 = str_replace('[click_here]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">Click Here</a>', $link);
                        $link2 = str_replace('[card_number]', $this->request->data['card_number'], $link1);
                        $link3 = str_replace('[password]', $user1['User']['password'], $link2);
                        $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(
                            SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                        ));
                        $Email->to($user1['User']['email']);
                        $Email->subject($sub)
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array(
                            'msg' => $link3
                        ));
                        $Email->send();
                    }
                    $this->Session->setFlash('BuzzyDoc Rewards Card Assigned Successfully', 'default', array(), 'good');
                    return $this->redirect('/PatientManagement/recordpoint/3');
                } else {
                    $this->Session->setFlash('User already exists', 'default', array(), 'bad');
                    return $this->redirect('/PatientManagement/recordpoint/3');
                }
            } else {
                $optionscl['conditions'] = array(
                    'ClinicUser.card_number' => $this->request->data['card_number'],
                    'ClinicUser.clinic_id' => $sessionpatient['clinic_id']
                );

                $userclinic = $this->ClinicUser->find('first', $optionscl);

                if (empty($userclinic)) {
                    if (isset($this->request->data['parents_email'])) {
                        $email = $this->request->data['parents_email'];
                        $username = $this->request->data['aemail'];
                    } else {
                        $email = $this->request->data['email'];
                        $username = '';
                    }
                    $dob = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];

                    $password = mt_rand(100000, 999999);
                    $newPatients_array['User'] = array(
                        'email' => $email,
                        'parents_email' => $username,
                        'custom_date' => $dob,
                        'first_name' => $this->request->data['first_name'],
                        'last_name' => $this->request->data['last_name'],
                        'enrollment_stamp' => date('Y-m-d H:i:s'),
                        'customer_password' => md5($password),
                        'password' => $password,
                        'status' => 1,
                        'is_verified' => $is_verified,
                        'is_buzzydoc' => 0,
                        'blocked' => 0,
                        'points' => 0
                    );
                    $this->User->create();
                    $this->User->save($newPatients_array);
                    $user_id = $this->User->getLastInsertId();
                    $optionsusdet['conditions'] = array(
                        'User.id' => $user_id
                    );

                    $userdetail = $this->User->find('first', $optionsusdet);
                    $Patients_array['ClinicUser'] = array(
                        'clinic_id' => $sessionpatient['clinic_id'],
                        'user_id' => $user_id,
                        'card_number' => $this->request->data['card_number'],
                        'local_points' => 0
                    );
                    $this->ClinicUser->create();
                    $this->ClinicUser->save($Patients_array);
                    $query = 'update card_numbers set status=2 where clinic_id=' . $sessionpatient['clinic_id'] . ' and card_number=' . $this->request->data['card_number'];
                    $unrescard = $this->CardNumber->query($query);

                    $alltrans = $this->UnregTransaction->find('all', array(
                        'conditions' => array(
                            'UnregTransaction.user_id' => 0,
                            'UnregTransaction.card_number' => $this->request->data['card_number'],
                            'UnregTransaction.clinic_id' => $sessionpatient['clinic_id']
                        )
                    ));

                    foreach ($alltrans as $newtran) {
                        $datatrans['user_id'] = $user_id;
                        $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                        $datatrans['card_number'] = $this->request->data['card_number'];
                        $datatrans['first_name'] = $this->request->data['first_name'];
                        $datatrans['last_name'] = $this->request->data['last_name'];
                        $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                        $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                        $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                        $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                        $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                        $datatrans['date'] = $newtran['UnregTransaction']['date'];
                        $datatrans['status'] = $newtran['UnregTransaction']['status'];
                        $datatrans['is_buzzydoc'] = 1;
                        $this->Transaction->create();
                        $this->Transaction->save($datatrans);
                        $this->UnregTransaction->deleteAll(array(
                            'UnregTransaction.id' => $newtran['UnregTransaction']['id'],
                            false
                        ));
                    }

                    $allpoints = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $user_id,
                            'Transaction.clinic_id' => $sessionpatient['clinic_id'],
                            'Transaction.is_buzzydoc' => 1
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.card_number'
                        )
                    ));

                    if ($allpoints[0]['points'] > 0) {
                        $newpoints = $allpoints[0]['points'];
                    } else {
                        $newpoints = 0;
                    }

                    $queryuser = 'update users set points=' . $newpoints . ' where id=' . $user_id;
                    $usersave = $this->User->query($queryuser);

                    $data = array();

                    $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $user_id;
                    $users_field = $this->ProfileFieldUser->query($sQuery);

                    $data[$user_id] = $users_field;
                    $data[$user_id]['card_number'] = $this->request->data['card_number'];
                    $data[$user_id]['custom_date'] = $dob;
                    $data[$user_id]['email'] = $email;
                    $data[$user_id]['parents_email'] = $username;
                    $data[$user_id]['first_name'] = $this->request->data['first_name'];
                    $data[$user_id]['last_name'] = $this->request->data['last_name'];
                    $data[$user_id]['customer_password'] = $password;
                    $data[$user_id]['status'] = 1;
                    $data[$user_id]['clinic_id'] = $sessionpatient['clinic_id'];
                    if ($sessionpatient['is_buzzydoc'] == 1) {
                        $data[$user_id]['total_points'] = '0(' . $newpoints . ')';
                    } else {
                        $data[$user_id]['total_points'] = 0;
                    }
                    $data[$user_id]['User'] = $userdetail['User'];

                    foreach ($data as $dt) {
                        $this->Session->write('staff.customer_info', $dt);
                    }
                    $this->Session->write('staff.customer_search_results', array());
                    $this->Session->delete('staff.customer_search_results');
                    $this->Session->write('staff.unreg_customer_search_results', array());
                    $this->Session->delete('staff.unreg_customer_search_results');

                    $clinic = $this->Clinic->find('first', array(
                        'conditions' => array(
                            'Clinic.id' => $sessionpatient['clinic_id']
                        )
                    ));

                    if ($userdetail['User']['email'] != '') {
                        if ($is_verified == 1) {
                            $template_array = $this->Api->get_template(3);
                            $link = str_replace('[username]', $userdetail['User']['first_name'], $template_array['content']);
                            $link1 = str_replace('[click_here]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">Click Here</a>', $link);
                            $link2 = str_replace('[card_number]', $this->request->data['card_number'], $link1);
                            $link3 = str_replace('[password]', $userdetail['User']['password'], $link2);
                            $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
                            $Email = new CakeEmail(MAILTYPE);
                            $Email->from(array(
                                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                            ));
                            $Email->to($userdetail['User']['email']);
                            $Email->subject($sub)
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array('msg' => $link3));
                            $Email->send();
                        } else {
                            $template_array = $this->Api->get_template(36);

                            $link = str_replace('[click_here]', "<a href='" . rtrim($clinic['Clinic']['patient_url'], '/') . "/rewards/login/" . base64_encode($clinic['Clinic']['id']) . "/" . base64_encode($user_id) . "' >Click Here</a>", $template_array['content']);
                            $link1 = str_replace('[card_number]', $this->request->data['card_number'], $link);
                            $link2 = str_replace('[first_name]', $userdetail['User']['first_name'], $link1);
                            $link3 = str_replace('[last_name]', $userdetail['User']['last_name'], $link2);
                            $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
                            $Email = new CakeEmail(MAILTYPE);
                            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                            $Email->to($userdetail['User']['email']);
                            $Email->subject($sub)
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array('msg' => $link3));
                            $Email->send();
                        }
                    }

                    $this->Session->setFlash('BuzzyDoc Rewards Card Assigned Successfully', 'default', array(), 'good');

                    // $this->redirect(array('action' => 'patientinfo'));
                    return $this->redirect('/PatientManagement/recordpoint/3');
                } else {
                    $this->Session->setFlash('Card Already Assigned.', 'default', array(), 'bad');

                    // $this->redirect(array('action' => 'patientinfo'));
                    return $this->redirect('/PatientManagement/recordpoint/3');
                }
            }
        }
    }

    /**
     * Getting the unique username while assign card to patient by staff user.
     */
    public function checkusername() {
        $this->layout = "";
        if ($_POST['fname'] != '' && $_POST['lname'] != '') {
            echo $this->Api->get_username($_POST['fname'] . $_POST['lname']);
        } else {
            echo '';
        }
        exit();
    }

    /**
     * Check unique patinet while profile update and assign card to patient.
     */
    public function checkuserexist() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        if (isset($_POST['parents_email']) && $_POST['parents_email'] != undefined) {
            $users_field_check = $this->User->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.clinic_id' => $sessionpatient['clinic_id'],
                    'User.email' => $_POST['email'],
                    'User.parents_email' => $_POST['parents_email']
                ),
                'fields' => array(
                    'User.id'
                )
            ));
        } else {
            $date13age = date("Y-m-d", strtotime("-18 year"));
            $users_field_check = $this->User->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.clinic_id' => $sessionpatient['clinic_id'],
                    'User.email' => $_POST['email'],
                    'User.custom_date <=' => $date13age
                ),
                'fields' => array(
                    'User.id'
                )
            ));
        }

        if (empty($users_field_check)) {

            if (isset($_POST['parents_email']) && $_POST['parents_email'] != undefined) {

                $users_field = $this->User->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'clinic_users.clinic_id !=' => $sessionpatient['clinic_id'],
                        'User.email' => $_POST['email'],
                        'User.parents_email' => $_POST['parents_email']
                    ),
                    'fields' => array(
                        'User.email',
                        'User.id',
                        'clinic_users.clinic_id'
                    )
                ));
            } else {
                $date13age = date("Y-m-d", strtotime("-18 year"));
                $users_field = $this->User->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = User.id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'clinic_users.clinic_id !=' => $sessionpatient['clinic_id'],
                        'User.email' => $_POST['email'],
                        'User.custom_date <=' => $date13age
                    ),
                    'fields' => array(
                        'User.email',
                        'User.id',
                        'clinic_users.clinic_id'
                    )
                ));
            }
            if (count($users_field) > 0) {
                echo $users_field['User']['id'];
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit();
    }

    /**
     * Update patient all information by staff user.
     * Change password for patient.
     * @return type
     */
    public function updatecustomer() {
        $sessionpatient = $this->Session->read('staff');

        foreach ($this->request->data as $allfield1 => $allfieldval1) {
            $checkfield = explode('_', $allfield1);
            if ($checkfield[0] == 'other') {

                $findfield = str_replace('other_', '', $allfield1);
                $newarray[$findfield] = $allfieldval1;
                unset($this->request->data[$allfield1]);
            }
        }

        foreach ($this->request->data as $allfield => $allfieldval) {
            if (is_array($allfieldval)) {
                $this->request->data[$allfield] = implode(',', $allfieldval);
            } else {
                $this->request->data[$allfield] = $allfieldval;
            }

            if (isset($newarray[$allfield])) {

                $this->request->data[$allfield] = $this->request->data[$allfield] . '###' . $newarray[$allfield];
            }
        }
        $fl_array['User']['id'] = $this->request->data['id'];
        if (isset($this->request->data['date_year'])) {
            $this->request->data['custom_date'] = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];
        }
        if ($this->request->data['new_password'] != '') {
            $fl_array['User']['customer_password'] = md5($this->request->data['new_password']);
            $fl_array['User']['password'] = $this->request->data['new_password'];
        }
        if (isset($this->request->data['parents_email'])) {
            $fl_array['User']['email'] = strtolower($this->request->data['parents_email']);
        }
        if (isset($this->request->data['aemail'])) {
            $fl_array['User']['parents_email'] = $this->request->data['aemail'];
        }
        if (isset($this->request->data['email'])) {
            $fl_array['User']['email'] = strtolower($this->request->data['email']);
        }
        if (isset($this->request->data['custom_date'])) {
            $fl_array['User']['custom_date'] = $this->request->data['custom_date'];
        }
        if (isset($this->request->data['internal_id'])) {
            $fl_array['User']['internal_id'] = $this->request->data['internal_id'];
        }
        $fl_array['User']['first_name'] = $this->request->data['first_name'];
        $fl_array['User']['last_name'] = $this->request->data['last_name'];
        $this->User->save($fl_array);

        foreach ($sessionpatient['ProfileField'] as $val) {

            if ($sessionpatient['is_buzzydoc'] == 1) {
                if (isset($this->request->data[$val['ProfileField']['profile_field']])) {
                    $pr_val = $this->request->data[$val['ProfileField']['profile_field']];
                    $records_pf_vl = array(
                        "ProfileFieldUser" => array(
                            "user_id" => $this->request->data['id'],
                            "profilefield_id" => $val['ProfileField']['id'],
                            "value" => $pr_val,
                            "clinic_id" => $val['ProfileField']['clinic_id']
                        )
                    );
                    //create and update profile filed details for patient.
                    $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id=" . $sessionpatient['clinic_id'] . " or clinic_id='0' or clinic_id='') and user_id=" . $this->request->data['id'] . " and profilefield_id=" . $val['ProfileField']['id']);
                    if (empty($ProfileField_val)) {
                        $this->ProfileFieldUser->create();
                        $this->ProfileFieldUser->save($records_pf_vl);
                    } else {

                        $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $pr_val . "' WHERE `profilefield_id` = " . $val['ProfileField']['id'] . " AND `user_id` =" . $this->request->data['id']);
                    }
                }
            } else {

                // if($val['ProfileField']['profile_field']!='street1' && $val['ProfileField']['profile_field']!='street2'){
                if (isset($this->request->data[$val['ProfileField']['profile_field']])) {
                    $pr_val = $this->request->data[$val['ProfileField']['profile_field']];
                } else {
                    $pr_val = '';
                }
                $records_pf_vl = array(
                    "ProfileFieldUser" => array(
                        "user_id" => $this->request->data['id'],
                        "profilefield_id" => $val['ProfileField']['id'],
                        "value" => $pr_val,
                        "clinic_id" => $val['ProfileField']['clinic_id']
                    )
                );

                // $ProfileField_val = $this->ProfileFieldUser->find('first',array('conditions'=>array('ProfileFieldUser.user_id'=>$this->request->data['id'],'ProfileFieldUser.profilefield_id'=>$val['ProfileField']['id'])));

                $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id=" . $sessionpatient['clinic_id'] . " or clinic_id='0' or clinic_id='') and user_id=" . $this->request->data['id'] . " and profilefield_id=" . $val['ProfileField']['id']);
                if (empty($ProfileField_val)) {
                    $this->ProfileFieldUser->create();
                    $this->ProfileFieldUser->save($records_pf_vl);
                } else {

                    $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $pr_val . "' WHERE `profilefield_id` = " . $val['ProfileField']['id'] . " AND `user_id` =" . $this->request->data['id']);
                }
            }
            // }
        }

        $users = $this->User->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinic_users',
                    'alias' => 'clinic_users',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinic_users.user_id = User.id'
                    )
                )
            ),
            'conditions' => array(
                'User.id' => $this->request->data['id']
            ),
            'fields' => array(
                'clinic_users.*',
                'User.*'
            )
        ));

        $data = array();
        foreach ($users as $key => $value) {
            $users_field = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    ),
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinic',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinic.id = clinic_users.clinic_id'
                        )
                    ),
                    array(
                        'table' => 'profile_field_users',
                        'alias' => 'PFU',
                        'type' => 'INNER',
                        'conditions' => array(
                            'PFU.user_id = User.id'
                        )
                    ),
                    array(
                        'table' => 'profile_fields',
                        'alias' => 'PF',
                        'type' => 'INNER',
                        'conditions' => array(
                            'PF.id = PFU.profilefield_id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.user_id' => $value['clinic_users']['user_id']
                ),
                'fields' => array(
                    'PF.*',
                    'PFU.*'
                )
            ));

            $localpoint = $this->ClinicUser->find('first', array(
                'conditions' => array(
                    'ClinicUser.user_id' => $value['clinic_users']['user_id'],
                    'ClinicUser.clinic_id' => $sessionpatient['clinic_id']
                )
            ));
            $points = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
            $data[$value['clinic_users']['user_id']] = $users_field;
            $data[$value['clinic_users']['user_id']]['card_number'] = $value['clinic_users']['card_number'];
            $data[$value['clinic_users']['user_id']]['custom_date'] = $value['User']['custom_date'];
            $data[$value['clinic_users']['user_id']]['email'] = $value['User']['email'];
            $data[$value['clinic_users']['user_id']]['parents_email'] = $value['User']['parents_email'];
            $data[$value['clinic_users']['user_id']]['first_name'] = $value['User']['first_name'];
            $data[$value['clinic_users']['user_id']]['last_name'] = $value['User']['last_name'];
            $data[$value['clinic_users']['user_id']]['total_points'] = $points;
            $data[$value['clinic_users']['user_id']]['User'] = $value['User'];
            $data[$value['clinic_users']['user_id']]['clinic_id'] = $value['clinic_users']['clinic_id'];
            $data[$value['clinic_users']['user_id']]['internal_id'] = $value['User']['internal_id'];
        }

        foreach ($data as $dt) {
            $this->Session->write('staff.customer_info', $dt);
        }

        $this->Session->setFlash('Selected record updated successfully.', 'default', array(), 'good');

        // $this->redirect(array('action' => 'patientinfo'));
        return $this->redirect('/PatientManagement/recordpoint/3');
    }

    /**
     * Send verifecation mail for child to parents.
     */
    public function sendVerify() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        $Email = new CakeEmail(MAILTYPE);

        if (isset($sessionpatient['customer_info']['card_number'])) {

            $clinic = $this->Clinic->find('first', array(
                'conditions' => array(
                    'Clinic.id' => $sessionpatient['clinic_id']
                )
            ));
            $template_array = $this->Api->get_template(36);
            $link = str_replace('[click_here]', "<a href=" . rtrim($clinic['Clinic']['patient_url'], '/') . "/rewards/login/" . base64_encode($sessionpatient['clinic_id']) . "/" . base64_encode($sessionpatient['customer_info']['User']['id']) . ">Click Here</a>", $template_array['content']);
            $link1 = str_replace('[card_number]', $sessionpatient['customer_info']['card_number'], $link);
            $link2 = str_replace('[first_name]', $sessionpatient['customer_info']['first_name'], $link1);
            $link3 = str_replace('[last_name]', $sessionpatient['customer_info']['last_name'], $link2);
            $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
            $Email->from(array(
                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
            ));
            $Email->to($sessionpatient['customer_info']['email']);
            $Email->subject($sub)
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email->viewVars(array(
                'msg' => $link3
            ));
            $Email->send();

            echo 1;
        } else {
            echo 0;
        }

        exit();
    }

    /**
     * Check email is unique for our system for both adult and child while upadting the patinet record.
     */
    public function checkemail() {
        $this->layout = "";
        if (isset($_POST['email']) && isset($_POST['parents_email'])) {

            if (isset($_POST['parents_email']) && $_POST['parents_email'] != '' && $_POST['email'] != '') {
                $users_field = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email' => $_POST['email'],
                        'User.parents_email' => $_POST['parents_email']
                    )
                ));
                if (!empty($users_field)) {
                    $check = 1;
                } else {
                    $check = 0;
                }

                $users_field1 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.parents_email' => $_POST['email']
                    )
                ));
                if (!empty($users_field1)) {
                    $check1 = 1;
                } else {
                    $check1 = 0;
                }
                $users_field2 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email' => $_POST['parents_email']
                    )
                ));
                if (!empty($users_field2)) {
                    $check2 = 1;
                } else {
                    $check2 = 0;
                }
                $users_field3 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email !=' => $_POST['email'],
                        'User.parents_email' => $_POST['parents_email']
                    )
                ));
                if (!empty($users_field3)) {
                    $check3 = 1;
                } else {
                    $check3 = 0;
                }
                if ($check == 1) {
                    echo 1;
                } else
                if ($check1 == 1) {
                    echo 2;
                } else
                if ($check2 == 1) {
                    echo 4;
                } else
                if ($check3 == 1) {
                    echo 4;
                } else {
                    echo 0;
                }
            } else
            if (isset($_POST['parents_email']) && $_POST['parents_email'] == '' && $_POST['email'] != '') {
                $users_field = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.parents_email' => $_POST['email']
                    )
                ));
                if (!empty($users_field)) {
                    $check2 = 1;
                } else {
                    $check2 = 0;
                }

                if ($check2 == 1) {
                    echo 2;
                } else {
                    echo 0;
                }
            } else
            if (isset($_POST['parents_email']) && $_POST['parents_email'] != '' && $_POST['email'] == '') {
                $users_field = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email' => $_POST['parents_email']
                    )
                ));
                if (!empty($users_field)) {
                    $check2 = 1;
                } else {
                    $check2 = 0;
                }
                $users_field1 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.parents_email' => $_POST['parents_email']
                    )
                ));
                if (!empty($users_field1)) {
                    $check1 = 1;
                } else {
                    $check1 = 0;
                }

                if ($check2 == 1 || $check1 == 1) {
                    echo 4;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {

            if ($_POST['email'] != '') {
                $date13age = date("Y-m-d", strtotime("-18 year"));
                $users_field = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.parents_email' => $_POST['email']
                    )
                ));
                if (!empty($users_field)) {
                    $check2 = 1;
                } else {
                    $check2 = 0;
                }
                $users_field2 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email' => $_POST['email'],
                        'User.custom_date <' => $date13age
                    )
                ));
                if (!empty($users_field2)) {
                    $check3 = 1;
                } else {
                    $check3 = 0;
                }
                $users_field1 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id !=' => $_POST['id'],
                        'User.email' => $_POST['email'],
                        'User.parents_email' => '',
                        'User.custom_date <' => $date13age
                    )
                ));
                if (!empty($users_field1)) {
                    $check1 = 1;
                } else {
                    $check1 = 0;
                }
                if ($check1 == 1) {
                    echo 3;
                } else
                if ($check2 == 1) {
                    echo 2;
                } else
                if ($check3 == 1) {
                    echo 2;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }

        exit();
    }

    /**
     * Modify input data for user visits
     * @param Array $data            
     */
    public function setUserVisitsData($data, $requestData = array()) {

        $input = $this->setAchievedLevels($data);

        if ($input) {
            $requestData['level_achieved'] = $input['level_achieved'];
            $requestData['is_treatment_over'] = $input['is_treatment_over'];
            $requestData['level_up_settings_id'] = $input['level_up_settings_id'];

            $this->UserPerfectVisit->create();
            $this->UserPerfectVisit->saveAll($input);
            $this->_saveUserPhasePoints($requestData);
            $this->setUserLevelBadges($input);
            if ($input['is_treatment_over'] == 1) {
                $input = array('treatment_id' => $input['level_up_settings_id'], 'clinic_id' => $input['clinic_id'], 'user_id' => $this->getUserId(), 'status' => 1, 'created_on' => date('Y-m-d H:i:s'));
                $this->UserAssignedTreatment->create();
                $this->UserAssignedTreatment->save($input);
            };
        }
    }

    /**
     * Get perfect/non-perfect visits of users
     * @param string $perfect if true then return perfect visits
     * @return array
     */
    public function getUserVisits($treatmentId, $perfect = false) {
        if ($treatmentId) {
            $options['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $this->_clinicId,
                'UserPerfectVisit.level_up_settings_id' => $treatmentId,
                'UserPerfectVisit.user_id' => $this->getUserId()
            );

            if ($perfect == true) {
                $options['conditions']['UserPerfectVisit.is_perfect'] = 1;
            }

            $data = $this->UserPerfectVisit->find('all', $options);
            if ($data) {
                return array_column($data, 'UserPerfectVisit');
            }
            return array();
        }
    }

    /**
     * Get perfect/non-perfect visits of users for inteval treatment
     * @param string $perfect if true then return perfect visits
     * @return array
     */
    public function getUserVisitsForInterval($treatmentId, $perfect = false) {
        $getlastlevelcomplete = $this->getLastLevelcomplete($treatmentId);

        if ($treatmentId) {
            if (!empty($getlastlevelcomplete)) {
                $options['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $this->_clinicId,
                    'UserPerfectVisit.level_up_settings_id' => $treatmentId,
                    'UserPerfectVisit.user_id' => $this->getUserId(),
                    'UserPerfectVisit.date >' => $getlastlevelcomplete['UserPerfectVisit']['date']
                );
            } else {
                $options['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $this->_clinicId,
                    'UserPerfectVisit.level_up_settings_id' => $treatmentId,
                    'UserPerfectVisit.user_id' => $this->getUserId()
                );
            }
            if ($perfect == true) {
                $options['conditions']['UserPerfectVisit.is_perfect'] = 1;
            }

            $data = $this->UserPerfectVisit->find('all', $options);
            if ($data) {
                return array_column($data, 'UserPerfectVisit');
            }
            return array();
        }
    }

    /**
     * Check the last level is perfect and phase is completed.
     * @param type $treatmentId
     * @return string
     */
    public function getLastLevelcomplete($treatmentId) {
        if ($treatmentId) {
            $options['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $this->_clinicId,
                'UserPerfectVisit.level_up_settings_id' => $treatmentId,
                'UserPerfectVisit.user_id' => $this->getUserId(),
                'UserPerfectVisit.level_achieved like' => '%Phase%'
            );
            $options['order'] = array('UserPerfectVisit.date desc');
            $data = $this->UserPerfectVisit->find('first', $options);
            if ($data) {
                return $data;
            }
            return '';
        }
    }

    /**
     * Tells if user achieved or not achieved the level
     * @return array | null
     */
    public function setAchievedLevels($data) {
        $treatment = $this->_treatmentSettings[0];
        //condition for interval reward plan to count visit for patient.
        if ($data['interval'] == 0) {
            $userTotalVisits = count($this->getUserVisits($data['level_up_settings_id']));
            $userPerfectVisits = count($this->getUserVisits($data['level_up_settings_id'], true));
        } else {
            $userTotalVisits = count($this->getUserVisitsForInterval($data['level_up_settings_id']));
            $userPerfectVisits = count($this->getUserVisitsForInterval($data['level_up_settings_id'], true));
        }
        $total_visits = $treatment['total_visits'];
        $i = 1;
        $val = 0;
        foreach ($this->getPhaseDistribution() as $key => $elem) {
            $val = $val + $elem['visits'];
            //setup phase and badge details for interval reward plan.
            if ($data['interval'] == 0) {
                $this->_phaseVisits['Phase ' . $elem['phase']] = $val;
                $this->_phaseBadges['Phase ' . $elem['phase']] = $elem['badge_name'];
                $this->_phasePoints['Phase ' . $elem['phase']] = $elem['points'];
                $this->_phaseBadgeId['Phase ' . $elem['phase']] = $elem['badge_id'];
            } else {
                $getlastlevelcomplete = $this->getLastLevelcomplete($data['level_up_settings_id']);
                if (isset($getlastlevelcomplete['UserPerfectVisit']['level_achieved'])) {
                    $getphase = explode(' ', $getlastlevelcomplete['UserPerfectVisit']['level_achieved']);
                    $nextphase = $getphase[1] + 1;
                } else {
                    $nextphase = 1;
                }
                $this->_phaseVisits['Phase ' . $nextphase] = $val;
                $this->_phaseBadges['Phase ' . $nextphase] = $elem['badge_name'];
                $this->_phasePoints['Phase ' . $nextphase] = $elem['points'];
                $this->_phaseBadgeId['Phase ' . $nextphase] = $elem['badge_id'];
            }
        }

        $data['is_treatment_over'] = 0;
        //condition to continus visit for interval reward plan.
        if (($userTotalVisits + 1) == $total_visits && $data['interval'] == 0) {
            $data['is_treatment_over'] = 1;
        }
        $data['level_achieved'] = 0;
        foreach ($this->_phaseVisits as $key => $val) {
            if (($userPerfectVisits + 1) == $val && $data['is_perfect'] == 1) {
                $data['level_achieved'] = $key;
                break;
            }
        }

        return $data;
    }

    /**
     * Set user badges for upper level program
     * @param int $userId Patient Id
     */
    public function setUserLevelBadges($input) {
        if ($input['level_achieved'] != '0') {
            $this->_badgeMessages[] = ' has attained ' . $this->_phaseBadges[$input['level_achieved']] . ' for ' . $this->_treatmentSettings[0]['treatment_name'];
            $this->setBadgeId($this->_phaseBadgeId[$input['level_achieved']]);
        }
        if ($input['is_treatment_over'] == 1) {
            $response = $this->isLevelAchieved($input['level_up_settings_id']);
            if ($response == 0) {
                $this->_badgeMessages[] = ' has attained ' . COMPLETION_BADGE . ' on completion of ' . $this->_treatmentSettings[0]['treatment_name'];
                $this->setBadgeId(null, COMPLETION_BADGE);
            }
        }

        if ($input['user_id'] && !empty($this->_badgeId)) {
            foreach ($this->_badgeId as $value) {
                $this->UsersBadge->save(array('user_id' => $this->getUserId(), 'badge_id' => $value, 'created_on' => date('Y-m-d H:i:s')));
            }
        }
    }

    /**
     * Get badge id using id and type
     * @param type $badgeId
     * @param type $type
     */
    public function setBadgeId($badgeId = null, $type = null) {
        if ($badgeId != null) {
            $data = $this->Badge->query("SELECT id FROM badges where id=" . $badgeId . ";");
        } elseif ($badgeId == null && $type == COMPLETION_BADGE) {
            $data = $this->Badge->query("SELECT id FROM badges where name='" . $type . "';");
        }
        if ($data) {
            $this->_badgeId[] = $data[0]['badges']['id'];
        }
    }

    /**
     * Test if user acheieved level or not
     * @param int $treatmentId Treatment Id of patient
     * @param string $level Phase 1 | Phase 2 | Phase 3
     * @return boolean
     */
    public function isLevelAchieved($treatmentId = null, $level = null) {
        if ($treatmentId) {
            if ($level == null) {
                $count = count($this->_phaseBadgeId);
                $level = $this->_phaseBadgeId['Phase ' . $count];
            }
            $options['conditions'] = array('UserPerfectVisit.level_achieved' => $level, 'level_up_settings_id' => $treatmentId, 'UserPerfectVisit.clinic_id' => $this->_clinicId, 'user_id' => $this->getUserId());
            $data = $this->UserPerfectVisit->find('all', $options);
            if ($data) {
                return 1;
            }
        }
        return 0;
    }

    /**
     * Save user points on basis of phase
     * @param Array $data
     * @return boolean 0 | 1 
     */
    protected function _saveUserPhasePoints($data) {
        if ($data) {
            $sessionStaff = $this->Session->read('staff');
            $localpoint = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['user_id']
                )
            ));
            $userLocalPoints = $localpoint['User']['points'];
            $userpointsshow = '0(' . $userLocalPoints . ')';
            $this->Session->write('staff.customer_info.User.points', $userLocalPoints);
            $this->Session->write('staff.customer_info.total_points', $userpointsshow);
            $amount = $bonusAmount = 0;
            $badge = null;

            if ($data['level_achieved'] != '0') {
                $badge = $this->_phaseBadges[$data['level_achieved']];
                $amount = $this->_phasePoints[$data['level_achieved']];
            }
            //condition to apply accelerated reward program at point allocation.
            if ($sessionStaff['staffaccess']['AccessStaff']['independent_earning'] == 1 && $sessionStaff['staffaccess']['AccessStaff']['tier_setting'] == 1) {
                $getval = $this->Api->getPatientLevelForAcceleratedReward($sessionStaff['clinic_id'], $this->getUserId());
                $amount = $amount * $getval;
            }
            $response = $this->isLevelAchieved($data['level_up_settings_id']);
            $badge2 = null;
            if ($data['is_treatment_over'] == 1 && $response == 0) {
                $badge2 = COMPLETION_BADGE;
            }

            foreach ($data['global_promotion'] as $key => $val) {
                if ($this->_isBonus == 1 && $key == 'bonus') {

                    $bonusAmount = $data['global_promotion_data'][0]['bonus_points'];
                    if ($sessionStaff['staffaccess']['AccessStaff']['independent_earning'] == 1 && $sessionStaff['staffaccess']['AccessStaff']['tier_setting'] == 1) {
                        $getval = $this->Api->getPatientLevelForAcceleratedReward($sessionStaff['clinic_id'], $this->getUserId());
                        $bonusAmount = $bonusAmount * $getval;
                    }
                    $description = $data['global_promotion_data']['bonus_message'];
                    $this->_badgeMessages[] = ' Received ' . $data['global_promotion_data']['bonus_points'] . ' bonus points';
                    $this->TreatmentSetting->query('UPDATE treatment_settings set bonus=0 where user_id=' . $this->getUserId() . ' AND clinic_id=' . $this->_clinicId . ' AND upper_level_setting_id=' . $this->_upperLevelSettingid . ' ');
                } else {
                    $details = $this->requestAction('/LevelupPromotions/getPublishedPromotions', array(
                        'pass' => array(
                            $val
                        )
                    ));
                    $details = array_column($details, 'LevelupPromotion');
                    $description = $details[0]['description'];
                }

                $transaction = array(
                    'user_id' => $this->getUserId(),
                    'card_number' => $data['card_number'],
                    'first_name' => $data['first_name1'],
                    'last_name' => $data['last_name1'],
                    'promotion_id' => $val,
                    'activity_type' => 'N',
                    'authorization' => $description,
                    'amount' => $bonusAmount,
                    'clinic_id' => $sessionStaff['clinic_id'],
                    'staff_id' => $data['staff_id'],
                    'doctor_id' => $data['doctor_id'],
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $sessionStaff['is_buzzydoc'],
                    'promotion_type' => '1',
                    'treatment_id' => $this->_upperLevelSettingid
                );
                $points = $userLocalPoints + $amount + $bonusAmount;
                $this->Transaction->create();
                $this->Transaction->save($transaction);
            }

            if ($badge2 != null && $badge2 == COMPLETION_BADGE && $amount == 0) {

                $query = 'SELECT COUNT(*) as total FROM `user_perfect_visits`  WHERE `level_up_settings_id`=' . $data['level_up_settings_id'] . '  AND user_id=' . $this->getUserId() . ' AND clinic_id =' . $sessionStaff['clinic_id'] . ' AND is_perfect=1 AND id >( SELECT id FROM `user_perfect_visits` WHERE level_up_settings_id=' . $data['level_up_settings_id'] . ' AND is_perfect=1 AND user_id=' . $this->getUserId() . ' AND clinic_id =' . $sessionStaff['clinic_id'] . ' AND level_achieved!="0" ORDER BY id DESC LIMIT 1);';
                $output = $this->UserPerfectVisit->query($query);
                if ($output) {
                    if ($output[0][0]['total'] == 0) {
                        if ($data['is_treatment_over'] == 1 && $response == 0) {
                            $query = 'SELECT COUNT(*) as total FROM `user_perfect_visits`  WHERE `level_up_settings_id`=' . $data['level_up_settings_id'] . '  AND user_id=' . $this->getUserId() . ' AND clinic_id =' . $sessionStaff['clinic_id'] . ' AND is_perfect=1';
                            $output = $this->UserPerfectVisit->query($query);
                        }
                    }
                    $amount = $output[0][0]['total'] * POINTS_PER_DOLLAR;
                }
                $points = $points + $amount;
                $this->saveUserPoints($sessionStaff, $this->getUserId(), $data, $badge2, $amount);
            }
            $this->User->save(array('id' => $this->getUserId(), 'points' => $points));
            $pointsshow = '0(' . $points . ')';
            $this->Session->write('staff.customer_info.User.points', $points);
            $this->Session->write('staff.customer_info.total_points', $pointsshow);
            if ($badge != '' && $amount != 0) {
                $usid = $this->getUserId();
                $this->saveUserPoints($sessionStaff, $usid, $data, $badge, $amount, $this->_upperLevelSettingid);

                $this->sendNotificationEmail($usid, $amount);
            }
            return;
        }
    }

    /**
     * Allocate points to patinet when treatmnet phase is completed.
     * @param type $sessionStaff
     * @param type $usid
     * @param type $data
     * @param type $badge
     * @param type $amount
     * @param type $treatment_id
     */
    public function saveUserPoints($sessionStaff, $usid, $data, $badge, $amount, $treatment_id) {

        $transaction = array(
            'user_id' => $usid,
            'card_number' => $data['card_number'],
            'first_name' => $data['first_name1'],
            'last_name' => $data['last_name1'],
            'promotion_id' => 0,
            'activity_type' => 'N',
            'authorization' => $badge,
            'amount' => $amount,
            'clinic_id' => $sessionStaff['clinic_id'],
            'staff_id' => $data['staff_id'],
            'doctor_id' => $data['doctor_id'],
            'date' => date('Y-m-d H:i:s'),
            'status' => 'New',
            'is_buzzydoc' => $sessionStaff['is_buzzydoc'],
            'promotion_type' => '1',
            'treatment_id' => $treatment_id
        );
        $this->Transaction->create();
        $this->Transaction->save($transaction);
    }

    /**
     * Send notification mail when get points after treatmnet phase is completed.
     * @param type $usid
     * @param type $amount
     */
    public function sendNotificationEmail($usid, $amount) {
        $sessionstaff = $this->Session->read('staff');
        $getfirstTransaction = $this->Api->get_firsttransaction($usid, $sessionstaff['clinic_id']);
        if ($getfirstTransaction == 1) {

            $patientclinic = $this->User->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    ),
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinics',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinics.id = clinic_users.clinic_id'
                        )
                    )
                ),
                'conditions' => array(
                    'User.id' => $usid,
                    'Clinics.id' => $sessionstaff['clinic_id']
                ),
                'fields' => array(
                    'User.*',
                    'Clinics.*'
                )
            ));
            if ($patientclinic['User']['email'] != '' && $amount > 0) {
                $template_array = $this->Api->get_template(39);
                $link1 = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
                $link = str_replace('[points]', $amount, $link1);
                $link2 = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(
                    SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                ));
                $Email->to($patientclinic['User']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array(
                    'msg' => $link2
                ));
                $Email->send();
            }
        }
        $patients = $this->Notification->find('all', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'Users',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Users.id = Notification.user_id'
                    )
                ),
                array(
                    'table' => 'clinic_users',
                    'alias' => 'clinic_users',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinic_users.user_id = Users.id'
                    )
                ),
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinics',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinics.id = clinic_users.clinic_id'
                    )
                )
            ),
            'conditions' => array(
                'Notification.earn_points' => 1,
                'Users.id' => $usid,
                'Users.email !=' => ''
            ),
            'fields' => array(
                'Users.*',
                'Clinics.*',
                'clinic_users.card_number'
            ),
            'group' => array(
                'clinic_users.user_id'
            )
        ));
        if ($amount > 0) {
            foreach ($patients as $pat) {
                $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($pat['Users']['id']) . "/Unsubscribe";
                $template_array = $this->Api->get_template(1);
                $link = str_replace('[username]', $pat['Users']['first_name'], $template_array['content']);
                $link1 = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
                $link2 = str_replace('[clinic_name]', $pat['Clinics']['api_user'], $link1);
                $link3 = str_replace('[points]', $amount, $link2);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(
                    SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                ));
                $Email->to($pat['Users']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array(
                    'msg' => $link3
                ));
                $Email->send();
            }
        }
    }

    /**
     * Getting the weekly or over all report for practice and show at dashboard.
     * getting details like (Total points redeem,total order redeem,total refer,point disbursted,current balance,credit balance)
     */
    public function getrecord() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if ($_POST['get'] == 'week') {
            $afrom = date("Y-m-d", strtotime("-7 day"));
            $ato = date("Y-m-d");
            $str = 'and date between "' . $afrom . '" and "' . $ato . '"';
            $str1 = 'and refdate between "' . $afrom . '" and "' . $ato . '"';
        } else {
            $str = '';
            $str1 = '';
        }

        $OrderRedeemed = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . '  and activity_type="Y" ' . $str . ' and (status="New" or status="Redeemed") group by activity_type');
        $OrderRedeemed1 = $this->GlobalRedeem->query('select count(id) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" ' . $str . ' group by activity_type');
        $OrderInoffice = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" ' . $str . ' and status="In Office" group by activity_type');
        $OrderShipped = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" ' . $str . ' and status="Ordered/Shipped" group by activity_type');
        $totlred = $OrderRedeemed1[0][0]['total'] + $OrderRedeemed[0][0]['total'] + $OrderInoffice[0][0]['total'] + $OrderShipped[0][0]['total'];
        if ($totlred == 0) {
            $OrderRedeemed = 0;
        } else {
            $OrderRedeemed = $totlred;
        }
        $TotalRefer = $this->Refer->query('select count(id) as total from refers where clinic_id=' . $sessionstaff['clinic_id'] . ' ' . $str1 . ' group by clinic_id');
        if (empty($TotalRefer)) {
            $TotalRefer = 0;
        } else {
            $TotalRefer = $TotalRefer[0][0]['total'];
        }
        $PointDisbursed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . '  ' . $str . 'and activity_type="N"');
        if (empty($PointDisbursed)) {
            $PointDisbursed = 0;
        } else {
            $PointDisbursed = number_format($PointDisbursed[0][0]['total'], 2);
        }
        $PointRedeemed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' ' . $str . ' and activity_type="Y"');
        $PointRedeemed1 = $this->GlobalRedeem->query('select sum(amount) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' ' . $str . ' and activity_type="Y"');
        $totpntred = $PointRedeemed[0][0]['total'] + $PointRedeemed1[0][0]['total'];
        if ($totpntred == 0) {
            $PointRedeemed = 0;
        } else {
            $PointRedeemed = number_format(ltrim($totpntred, '-'), 2);
        }

        $options3['conditions'] = array('Invoice.clinic_id' => $sessionstaff['clinic_id']);
        $options3['order'] = array('Invoice.payed_on desc');
        $Invoice = $this->Invoice->find('first', $options3);
        if ($Invoice['Invoice']['current_balance'] == 0) {
            $CurrentBalance = 0;
        } else {
            $CurrentBalance = number_format($Invoice['Invoice']['current_balance'], 2);
            ;
        }

        $redemptiondetail = $this->RedemptionDetail->query('select sum(points_redeemed) as total from redemption_details where clinic_redeem=' . $sessionstaff['clinic_id'] . ' and clinic_point_used!=' . $sessionstaff['clinic_id'] . ' and status !=1');
        if ($redemptiondetail[0][0]['total'] > 0) {
            $CreditBalance = number_format($redemptiondetail[0][0]['total'] / 50, 2);
        } else {
            $CreditBalance = 0;
        }
        echo json_encode(array('OrderRedeemed' => $OrderRedeemed, 'TotalRefer' => $TotalRefer, 'PointDisbursed' => $PointDisbursed, 'PointRedeemed' => $PointRedeemed, 'CurrentBalance' => $CurrentBalance, 'CreditBalance' => $CreditBalance));
        exit;
    }

    /**
     * Save treatment settings
     * @return JsonSerializable
     */
    public function savetreatmentsettings() {
        $this->layout = "";
        $response = array(
            'success' => 0,
            'message' => 'Cannot save.'
        );
        if ($this->request->data) {
            $data = $this->request->data;
            $data['clinic_id'] = $this->_clinicId;
            $data['user_id'] = $this->getUserId();
            $data['created_at'] = date('Y-m-d H:i:s');
            if (isset($data['remove']) && $data['remove'] == 1) {
                $perfectVisits = $this->UserPerfectVisit->find('first', array(
                    'fields' => array(
                        'UserPerfectVisit.id'
                    ),
                    'conditions' => array(
                        'UserPerfectVisit.level_up_settings_id' => $data['upper_level_setting_id'],
                        'UserPerfectVisit.user_id' => $data['user_id']
                    )
                ));
                if ($perfectVisits) {
                    $response['message'] = 'Cannot delete. Treatment started.';
                } else {
                    $this->TreatmentSetting->query('DELETE FROM `treatment_settings` WHERE clinic_id = ' . $this->_clinicId . ' and upper_level_setting_id=' . $data['upper_level_setting_id'] . ' and user_id=' . $this->getUserId());
                    $response['message'] = 'Removed Successfully.';
                    $response['success'] = 1;
                }
            } else {
                $output = $this->TreatmentSetting->save($data);
                if ($output) {
                    $response['success'] = 1;
                    $response['message'] = 'Saved Successfully.';
                }
            }
        }
        echo json_encode($response, true);
        exit();
    }

    /**
     * Get treatment conditions
     * @return Array
     */
    public function getTreatmentConditions() {
        $response = $this->TreatmentSetting->find('all', array('conditions' => array('TreatmentSetting.user_id' => $this->getUserId())));
        $checks = array();
        $response = $this->TreatmentSetting->find('all', array(
            'joins' => array(
                array(
                    'table' => 'upper_level_settings',
                    'alias' => 'upper_level_settings',
                    'type' => 'INNER',
                    'conditions' => array(
                        'upper_level_settings.id = TreatmentSetting.upper_level_setting_id'
                    )
                )
            ),
            'conditions' => array(
                'TreatmentSetting.user_id' => $this->getUserId(), 'TreatmentSetting.clinic_id' => $this->_clinicId, 'TreatmentSetting.status' => 1)
            , 'fields' => array('upper_level_settings.treatment_name', 'TreatmentSetting.*')
        ));
        if (!empty($response)) {
            foreach ($response as $val) {
                if (!$val['TreatmentSetting']['first_visit'] && !$val['TreatmentSetting']['perfect_visit']) {
                    $checks['nocheck'][] = $val['TreatmentSetting']['upper_level_setting_id'];
                } elseif ($val['TreatmentSetting']['first_visit'] && $val['TreatmentSetting']['perfect_visit']) {
                    $checks['perfect'][] = $val['TreatmentSetting']['upper_level_setting_id'];
                } elseif ($val['TreatmentSetting']['first_visit'] && !$val['TreatmentSetting']['perfect_visit']) {
                    $checks['mandatory'][$val['TreatmentSetting']['upper_level_setting_id']] = $val['upper_level_settings']['treatment_name'];
                }
            }
        }
        return $checks;
    }

    /**
     * display at point record screan for bonus point treatment.
     */
    public function getbonus() {
        $response = $this->UpperLevelSetting->find('first', array('conditions' => array('UpperLevelSetting.id' => $_POST['treatment_id'])));
        $str = '';
        if (!empty($response) && $response['UpperLevelSetting']['bonus_points'] > 0) {
            $str .='<div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="0" class="ace" id="bonus_available" name="bonus_available">&nbsp;
                                        <span class="lbl"> Award ' . $response['UpperLevelSetting']['bonus_points'] . ' bonus points</span>
                                    </label>
                                </div><div class="checkbox">
                                    <label>
                                        <span class="lbl">Note: ' . $response['UpperLevelSetting']['bonus_message'] . '. </span>
                                    </label>
                                </div>';
        }
        echo $str;
        die;
    }

    /**
     * Place order through amazon and tango by staff user for patient.
     */
    public function placeorder() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $place = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/placeAnOrder.json');
        $allorder = json_decode($place);
        $errorclinic = '';
        if ($allorder->placeanorder->save->errorid != '') {
            $errorclinic = rtrim($allorder->placeanorder->save->errorid, ',');
        }
        if ($allorder->placeanorder->errorid != '') {
            $errorclinic = rtrim($allorder->placeanorder->errorid, ',');
        }
        if ($allorder->placeanorder->success == 1) {
            $data = array(
                'success' => 1,
                'data' => $allorder->placeanorder->data,
                'error' => $errorclinic,
                'pointremain' => $allorder->placeanorder->pointremain
            );
            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $_POST['user_id'],
                    'Transaction.is_buzzydoc' => 1
                ),
                'fields' => array(
                    'SUM(Transaction.amount) AS points'
                ),
                'group' => array(
                    'Transaction.user_id'
                )
            ));

            if (empty($alltrans)) {
                $points = 0;
            } else {
                $points = $alltrans[0]['points'];
            }
            $this->User->updateAll(array(
                'User.points' => $points
                    ), array(
                'User.id' => $_POST['user_id']
            ));
            $localpoint = $this->ClinicUser->find('first', array(
                'conditions' => array(
                    'ClinicUser.user_id' => $_POST['user_id'],
                    'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                )
            ));
            $points = $localpoint['ClinicUser']['local_points'] . '(' . $points . ')';
            $this->Session->write('staff.customer_info.total_points', $points);
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request',
                'error' => $errorclinic
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Redeem product and services by staff user for patient.
     */
    public function redeemlocproduct() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->data['user_id'] == '' && $this->request->data['product_id'] == '' && $this->request->data['points'] == '') {
            echo 0;
        } else {
            if ($this->request->data['staff_id'] != "") {
                $staffId = $this->request->data['staff_id'];
            } else {
                $staffId = "";
            }
            $checkpoint = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['user_id'])));
            $redeemres = $this->Transaction->query('CALL sp_redeem_points(' . $this->request->data['user_id'] . ',' . $this->request->data['product_id'] . ',' . $this->request->data['points'] . ',"' . date("Y-m-d H:i:s") . '",' . $staffId . ')');
            $optionspro['conditions'] = array('ProductService.id' => $this->request->data['product_id']);
            $product = $this->ProductService->find('first', $optionspro);
            $optionscli['conditions'] = array('Clinic.id' => $product['ProductService']['clinic_id']);
            $fromclinic = $this->Clinic->find('first', $optionscli);
            if ($fromclinic['Clinic']['display_name'] == '') {
                $clinicname = $fromclinic['Clinic']['api_user'];
            } else {
                $clinicname = $fromclinic['Clinic']['display_name'];
            }
            $orderdetail = array('Order Number' => $redeemres[0]['redemption_details']['transaction_id'], 'Redeemed From' => $clinicname, 'Product/Service' => $product['ProductService']['title'], 'Points Redeemed' => $product['ProductService']['points']);
            //depricated condition.
            if (DEBIT_FROM_BANK == 1) {
                foreach ($redeemres as $dt) {
                    $paytoclinic = $dt['redemption_details']['points_to_be_deducted'];
                    if ($paytoclinic > 0) {
                        $options8['conditions'] = array('Staff.clinic_id' => $dt['redemption_details']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.redemption_mail' => 1);
                        $Staff = $this->Staff->find('first', $options8);
                        $stemail = '';
                        $stname = '';
                        if (!empty($Staff)) {
                            $stemail = $Staff['Staff']['staff_email'];
                            $stname = $Staff['Staff']['staff_id'];
                        }

                        if ($stemail == '') {
                            $options9['conditions'] = array('Staff.clinic_id' => $dt['redemption_details']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.staff_role' => 'Doctor');
                            $Staff1 = $this->Staff->find('first', $options9);
                            $stemail = $Staff1['Staff']['staff_email'];
                            $stname = $Staff1['Staff']['staff_id'];
                        }

                        if ($stemail == '') {
                            $options9['conditions'] = array('Staff.clinic_id' => $dt['redemption_details']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Administrator', 'Staff.staff_role' => 'A'));
                            $Staff2 = $this->Staff->find('first', $options9);
                            $stemail = $Staff2['Staff']['staff_email'];
                            $stname = $Staff2['Staff']['staff_id'];
                        }
                        if ($stemail == '') {
                            $options10['conditions'] = array('Staff.clinic_id' => $dt['redemption_details']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Manager', 'Staff.staff_role' => 'M'));
                            $Staff3 = $this->Staff->find('first', $options10);
                            $stemail = $Staff3['Staff']['staff_email'];
                            $stname = $Staff3['Staff']['staff_id'];
                        }


                        if ($stemail == '') {
                            $stemail = SUPER_ADMIN_EMAIL_STAFF;
                        }

                        $options['conditions'] = array('Clinic.id' => $dt['redemption_details']['clinic_id']);
                        $options['fields'] = array('Clinic.minimum_deposit', 'Clinic.api_user', 'Clinic.is_buzzydoc');
                        $minimumdeposit = $this->Clinic->find('first', $options);
                        $threshold = $minimumdeposit['Clinic']['minimum_deposit'] / 2;
                        $options4['conditions'] = array('Invoice.clinic_id' => $dt['redemption_details']['clinic_id']);
                        $options4['order'] = array('Invoice.payed_on desc');
                        $findlastpay = $this->Invoice->find('first', $options4);
                        $current_pay = $paytoclinic / 50;
                        $current_bal = $findlastpay['Invoice']['current_balance'] - $current_pay;
                        $ord_id = mt_rand(100000, 999999);
                        $Invoice_array['Invoice'] = array(
                            'clinic_id' => $dt['redemption_details']['clinic_id'],
                            'user_id' => $this->request->data['user_id'],
                            'amount' => $current_pay,
                            'invoice_id' => $ord_id,
                            'mode' => 'Debit',
                            'current_balance' => $current_bal,
                            'payed_on' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->Invoice->create();
                        $this->Invoice->save($Invoice_array);
                        $reachthres = $current_bal - $threshold;
                        $template_array = $this->Api->get_template(12);
                        $link = str_replace('[staff_name]', $stname, $template_array['content']);
                        $link1 = str_replace('[reduced_amount]', $current_pay, $link);
                        $link2 = str_replace('[current_balance]', $current_bal, $link1);
                        $link3 = str_replace('[away_amount]', $reachthres, $link2);
                        $Email1 = new CakeEmail(MAILTYPE);
                        $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                        $Email1->to($stemail);
                        $Email1->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email1->viewVars(array('msg' => $link3
                        ));
                        $Email1->send();

                        if ($threshold >= $current_bal && $minimumdeposit['Clinic']['is_buzzydoc'] == 1) {
                            if ($current_bal <= 0) {
                                $cb = explode('-', $current_bal);
                                $amountpay = $cb[1] + $threshold + 1;
                                $curnbal = $threshold + 1;
                            } else {
                                $amountpay = $threshold;
                                $curnbal = $threshold + $current_bal;
                            }
                            $transactionFee = .35 + $amountpay * (.75 / 100);

                            $totalcredit1 = $amountpay + $transactionFee;
                            $totalcredit = number_format($totalcredit1, 2, '.', '');
                            $paydet['conditions'] = array('PaymentDetail.clinic_id' => $dt['redemption_details']['clinic_id']);
                            $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
                            $transaction = new AuthorizeNetTransaction;
                            $transaction->amount = $totalcredit;
                            $transaction->customerProfileId = $getpayemntdetails['PaymentDetail']['customer_account_id'];
                            $transaction->customerPaymentProfileId = $getpayemntdetails['PaymentDetail']['customer_account_profile_id'];

                            $transaction_id = mt_rand(100000, 999999);
                            $lineItem = new AuthorizeNetLineItem;
                            $lineItem->itemId = $transaction_id;
                            $lineItem->name = $sku;
                            $lineItem->description = "Amazon gift card";
                            $lineItem->quantity = "1";
                            $lineItem->unitPrice = $amountpay;
                            $lineItem->taxable = "true";
                            $transaction->lineItems[] = $lineItem;
                            $request = new AuthorizeNetCIM;
                            $response = $request->createCustomerProfileTransaction("AuthCapture", $transaction);


                            if ($response->xml->messages->message->code == 'I00001') {
                                $transactionResponse = $response->getTransactionResponse();
                                $trnsid = $transactionResponse->transaction_id;
                                $date2 = date("Y-m-d H:i:s");
                                $Invoice_array['Invoice'] = array(
                                    'clinic_id' => $dt['redemption_details']['clinic_id'],
                                    'amount' => $amountpay,
                                    'transaction_fee' => $transactionFee,
                                    'invoice_id' => $trnsid,
                                    'mode' => 'Credit',
                                    'current_balance' => $curnbal,
                                    'payed_on' => $date2,
                                    'status' => 1
                                );
                                $this->Invoice->create();
                                $this->Invoice->save($Invoice_array);
                                $template_array = $this->Api->get_template(10);
                                $link = str_replace('[staff_name]', $stname, $template_array['content']);
                                $link1 = str_replace('[credit_amount]', $amountpay, $link);
                                $link2 = str_replace('[current_balance]', $curnbal, $link1);
                                $Email2 = new CakeEmail(MAILTYPE);
                                $Email2->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                                $Email2->to($stemail);
                                $Email2->subject($template_array['subject'])
                                        ->template('buzzydocother')
                                        ->emailFormat('html');

                                $Email2->viewVars(array('msg' => $link2
                                ));
                                $Email2->send();
                            } else {
                                $clinicfautid.=$minimumdeposit['Clinic']['api_user'] . ',';

                                $template_array = $this->Api->get_template(11);
                                $link = str_replace('[staff_name]', $stname, $template_array['content']);
                                $Email2 = new CakeEmail(MAILTYPE);
                                $Email2->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                                $Email2->to($stemail);
                                $Email2->subject($template_array['subject'])
                                        ->template('buzzydocother')
                                        ->emailFormat('html');

                                $Email2->viewVars(array('msg' => $link
                                ));
                                $Email2->send();
                            }


//                            }
                        }
                    }
                }
            }
            if (isset($this->request->data['redeem_notes']) && $this->request->data['redeem_notes'] != '') {
                $transactionupdate = array(
                    'id' => $redeemres[0]['redemption_details']['transaction_id'],
                    'redeem_by' => 1,
                    'redeem_notes_by_staff' => $this->request->data['redeem_notes'],
                    'date' => date('y-m-d H:i:s')
                );
                $this->Transaction->save($transactionupdate);
            }
            $optionscgettrans['conditions'] = array('Transaction.id' => $redeemres[0]['redemption_details']['transaction_id']);
            $trandetails = $this->Transaction->find('first', $optionscgettrans);
            $optionscgetcard['conditions'] = array('ClinicUser.clinic_id' => $trandetails['Transaction']['clinic_id'], 'ClinicUser.user_id' => $trandetails['Transaction']['user_id']);
            $getcardnumber = $this->ClinicUser->find('first', $optionscgetcard);
            $transactionupdatecardnumber = array(
                'id' => $trandetails['Transaction']['id'],
                'card_number' => $getcardnumber['ClinicUser']['card_number']
            );
            $this->Transaction->save($transactionupdatecardnumber);
            $template_array_red = $this->Api->save_notification($trandetails['Transaction'], 2, $redeemres[0]['redemption_details']['transaction_id']);
            if ($checkpoint['User']['email'] != '') {

                $template_array = $this->Api->get_template(13);
                $link = str_replace('[username]', $checkpoint['User']['first_name'], $template_array['content']);

                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                $Email->to($checkpoint['User']['email']);

                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');

                $Email->viewVars(array('msg' => $link,
                    'orderdetails' => $orderdetail
                ));
                $Email->send();
            }

            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $checkpoint['User']['id'],
                    'Transaction.is_buzzydoc' => 1
                ),
                'fields' => array(
                    'SUM(Transaction.amount) AS points'
                ),
                'group' => array(
                    'Transaction.user_id'
                )
            ));

            if (empty($alltrans)) {
                $points = 0;
            } else {
                $points = $alltrans[0]['points'];
            }

            $this->User->query("UPDATE `users` SET `points` = '" . $points . "' WHERE `id` =" . $checkpoint['User']['id']);
            $localpoint = $this->ClinicUser->find('first', array(
                'conditions' => array(
                    'ClinicUser.user_id' => $checkpoint['User']['id'],
                    'ClinicUser.clinic_id' => $sessionstaff['clinic_id']
                )
            ));
            $points = $localpoint['ClinicUser']['local_points'] . '(' . $points . ')';
            $this->Session->write('staff.customer_info.total_points', $points);


            echo 1;
        }



        die();
    }

    /**
     * Get patient list on search
     * @author Maninder Bali
     * @since 2015/11/06
     */
    public function getpatients() {
        $sessionstaff = $this->Session->read('staff');
        $this->Session->write('staff.search', 0);
        $inputData = $_POST;
        $searchString = str_replace(array(
            '\\',
            '$',
            '#',
            '^',
            '/'
                ), '', $inputData['searchString']);
        $cardchk1 = explode(';', $searchString);
        $cardchk2 = explode('?', $searchString);
        if (count($cardchk1) == 1 && count($cardchk2) == 1) {
            $searchString = $searchString;
        } else
        if (count($cardchk1) == 2 && count($cardchk2) == 2) {
            $getcard = explode('?', $cardchk1[1]);
            $searchString = $getcard[0];
        } else {
            $searchString = '#$%^';
        }
        if (isset($inputData['ownClinic']) && ($inputData['ownClinic'] == 1 || $inputData['ownClinic'] == 'on')) {
            $ownclinic = 1;
        } else {
            $ownclinic = 0;
        }
        if ($inputData['is_buzzydoc'] == 1 && $ownclinic == 0) {
            $users = $this->user->getUsers($searchString);
        } else {
            $users = $this->user->getUsers($searchString, $inputData['clinic_id']);
        }

        $forfnln = explode(' ', $searchString);
        $queryl = '';
        foreach ($forfnln as $flname) {
            $queryl .= '(text like "%' . $flname . '%") and ';
        }
        $queryl = rtrim($queryl, ' and ');
        $cardnumber = $this->CardNumber->query('select * from card_numbers as CardNumber where (card_number like "%' . $searchString . '%" or ' . $queryl . ') and clinic_id=' . $inputData['clinic_id'] . ' and status=1');
        $records = array();
        $total = count($users) + count($cardnumber);
        if ($total != 0) {
            $records = array('<h1>Registered Patient</h1>');
            foreach ($users as $val) {
                $dataSet = array_merge($val['clinic_users'], $val[0], $val['user']);
                if ($inputData['is_buzzydoc'] == 1 && $dataSet['email'] != '') {
                    $dataSet['email'] = self::mask_email($dataSet['email'], '*', 70);
                }
                if ($dataSet['clinic_id'] == $inputData['clinic_id']) {
                    $ofThisClinic = '<i title="Our" class="menu-icon fa fa-certificate green"></i>';
                } else {
                    $ofThisClinic = '';
                }

                $records[] = "<div id='$dataSet[user_id]' class='hand-icon grey' onClick='submitSearchForm($dataSet[user_id],$dataSet[card_number])'>
                    <p class='patientname1 col-sm-3 col-xs-6'><a href='javascript:void(0)'>$dataSet[first_name]</a></p>
                    <p class='patientname2 col-sm-3'><a href='javascript:void(0)'>$dataSet[email]</a></p>
                    <p class='patientname3 align-right col-sm-3'><a href='javascript:void(0)'>$dataSet[custom_date]</a></p>
                    <p class='patientId align-right col-sm-3 col-xs-6'><a href='javascript:void(0)'>$dataSet[card_number]</a>$ofThisClinic</p>
                    </div>";
            }
        }
        $unregisteredCards = array();

        if (count($cardnumber) > 0) {
            $unregisteredCards = array('<h1>New/unregistered cards</h1>');
        }
        foreach ($cardnumber as $unreguser) {

            $unreguserdata = json_decode($unreguser['CardNumber']['text']);

            if (isset($unreguserdata) && !empty($unreguserdata)) {

                $first_name = $unreguserdata->first_name;
                $last_name = $unreguserdata->last_name;
                $name = $first_name . ' ' . $last_name;
                $email = $unreguserdata->email;

                $dob = date("Y-m-d", strtotime($unreguserdata->custom_date));
                ;
            } else {
                $name = "";
                $email = '';
                $dob = '';
            }
            if ($inputData['is_buzzydoc'] == 1 && $email != '') {
                $email = self::mask_email($email, '*', 70);
            }
            $dataSet = array(
                'id' => $unreguser['CardNumber']['id'],
                'card_number' => $unreguser['CardNumber']['card_number'],
                'clinic_id' => $unreguser['CardNumber']['clinic_id'],
                'status' => $unreguser['CardNumber']['status'],
                'first_name' => $name,
                'email' => $email,
                'dob' => $dob
            );

            $unregisteredCards[] = "<div id='$dataSet[id]' class='hand-icon grey' onClick='submitSearchForm(0,$dataSet[card_number])'>
                <p class='patientname1 col-sm-3 col-xs-6'><a href='javascript:void(0)'>$dataSet[first_name]</a></p>
                <p class='patientname2 col-sm-3'><a href='javascript:void(0)'>$dataSet[email]</a></p>
                <p class='patientname3 align-right col-sm-3'><a href='javascript:void(0)'>$dataSet[custom_date]</a></p>
                <p class='patientId align-right col-sm-3 col-xs-6'><a href='javascript:void(0)'>$dataSet[card_number]</a><i title='Our' class='menu-icon fa fa-certificate green'></i></p>
                </div>";
        }


        $this->Session->delete('staff.search_from_api');
        echo json_encode(array(
            'customer_search_results' => $records,
            'unreg_customer_search_results' => $unregisteredCards
        ));
        die();
    }

    /**
     * Email masking for buzzydoc patient.
     * @param type $email
     * @param type $mask_char
     * @param type $percent
     * @return type
     */
    function mask_email($email, $mask_char, $percent = 50) {
        list( $user, $domain ) = preg_split("/@/", $email);
        $len = strlen($user);
        $mask_count = floor($len * $percent / 100);
        $offset = floor(( $len - $mask_count ) / 2);
        $masked = substr($user, 0, $offset)
                . str_repeat($mask_char, $mask_count)
                . substr($user, $mask_count + $offset);
        $masked1 = self::mask_other($domain);
        if ($masked1 != '') {
            return( $masked . '@' . $masked1 );
        } else {
            return( $masked);
        }
    }

    /**
     * Mask other value like fist name,last name etc.
     * @param type $email
     * @return type
     */
    function mask_other($email) {
        $len = strlen($email);
        $showLen = floor($len / 2);
        $str_arr = str_split($email);
        for ($ii = $showLen; $ii < $len; $ii++) {
            $str_arr[$ii] = '*';
        }
        $em[0] = implode('', $str_arr);
        $new_name = implode('@', $em);
        return( $new_name);
    }

    /**
     * Update patient point value after every transaction in session.
     * @param type $sessionstaff
     */
    public function updateSession($sessionstaff) {
        if (isset($sessionstaff['customer_search_results']) && isset($sessionstaff['customer_search_results'][0]['clinic_users'])) {
            $user_id = $sessionstaff['customer_search_results'][0]['clinic_users']['user_id'];
            $clinic_id = $sessionstaff['customer_search_results'][0]['clinic_users']['clinic_id'];
            $userDetail = $this->User->find('first', array(
                'conditions' => array(
                    'id' => $user_id
                )
            ));
            $this->Session->write('staff.customer_info.total_points', $userDetail['User']['points']);
        }
    }

    /**
     * Getting the list of all global training video for display at the top of the page. 
     */
    public function gettrainingvideo() {
        $this->layout = "";
        $response = array(
            'success' => 0,
            'embed_code' => ''
        );
        if ($this->request->data) {
            $training_id = $this->request->data['training_id'];
            $options3['conditions'] = array('TrainingVideo.id' => $training_id);
            $TrainingVideo = $this->TrainingVideo->find('first', $options3);
            if (!empty($TrainingVideo)) {
                $response['success'] = 1;
                $response['embed_code'] = $TrainingVideo['TrainingVideo']['video_embed'];
                $transe['WatchList'] = array(
                    'staff_id' => $this->request->data['staff_id'],
                    'traiing_video_id' => $training_id,
                    'watched_on' => date('Y-m-d H:i:s'),
                );
                $this->WatchList->create();
                $this->WatchList->save($transe);
            } else {
                $response = array(
                    'success' => 0,
                    'embed_code' => ''
                );
            }
        }
        echo json_encode($response, true);
        exit();
    }

    /**
     * Ajax filter for transaction type like(Redeem record,point given report by pormotion,point given report by manual points,treatment plan visit transaction.)
     */
    public function transactionTypeDetails() {
        $sessionstaffcheck = $this->Session->read('staff');
        //getting the list given point details.
        $alltrans2 = $this->Transaction->find('all', array(
            'conditions' => array(
                'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                'Transaction.clinic_id' => $sessionstaffcheck['clinic_id'],
                'Transaction.amount !=' => 0,
                'Transaction.activity_type ' => 'N'
            ),
            'order' => array(
                'Transaction.date desc'
        )));
        //getting the list of redeemed transactions.
        $alltrans3 = $this->Transaction->find('all', array(
            'conditions' => array(
                'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                'Transaction.clinic_id' => $sessionstaffcheck['clinic_id'],
                'Transaction.activity_type ' => 'Y'
            ),
            'order' => array(
                'Transaction.date desc'
        )));
        //getting the list of transaction redeem at tango and amazon.
        $alltrans1 = $this->Transaction->find('all', array(
            'conditions' => array(
                'Transaction.user_id' => $sessionstaffcheck['customer_info']['User']['id'],
                'Transaction.clinic_id' => 0,
                'Transaction.amount !=' => 0
            ),
            'order' => array(
                'Transaction.date desc'
        )));
        $alltrans = array_merge_recursive($alltrans2, $alltrans1, $alltrans3);

        function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {
            foreach ($array as $subarray) {
                $keys[] = $subarray['Transaction'][$subkey];
            }
            array_multisort($keys, $sortType, $array);
        }

        sortBySubkey($alltrans, 'date');
        $newalltrans = array();
        if ($_POST['transactionType'] == 'R') {
            foreach ($alltrans as $at) {
                if ($at['Transaction']['activity_type'] == 'Y' && $_POST['transactionType'] == 'R') {
                    $newalltrans[] = $at;
                }
            }
        }
        if ($_POST['transactionType'] > 0) {
            foreach ($alltrans as $at) {
                if ($at['Transaction']['treatment_id'] == $_POST['transactionType'] && $at['Transaction']['activity_type'] == 'N') {
                    $getname['conditions'] = array('UpperLevelSetting.id' => $at['Transaction']['treatment_id'], 'UpperLevelSetting.interval' => $sessionstaffcheck['staffaccess']['AccessStaff']['interval']);
                    $getname['fields'] = array('UpperLevelSetting.treatment_name', 'UpperLevelSetting.id');
                    $treatmentname = $this->UpperLevelSetting->find('first', $getname);
                    $newalltrans[] = $at;
                }
            }
        }
        if ($_POST['transactionType'] == 'M') {
            foreach ($alltrans as $at) {
                if ($at['Transaction']['treatment_id'] < 1 && $at['Transaction']['promotion_id'] < 1 && $at['Transaction']['activity_type'] == 'N' && $_POST['transactionType'] == 'M') {
                    $newalltrans[] = $at;
                }
            }
        }
        if ($_POST['transactionType'] == 'P') {

            foreach ($alltrans as $at) {
                if ($at['Transaction']['treatment_id'] < 1 && $at['Transaction']['promotion_id'] > 0 && $at['Transaction']['activity_type'] == 'N' && $_POST['transactionType'] == 'P') {
                    $newalltrans[] = $at;
                }
            }
        }
        if ($_POST['transactionType'] == '0') {

            $newalltrans = $alltrans;
        }
        $str = '<tr><td width="10%" class="amountpositive">Delete</td><td width="30%" class="amountpositive">Date</td><td width="10%" class="amountpositive">Points</td><td width="50%" class="amountpositive">For</td></tr>';
        foreach ($newalltrans as $transaction_info) {
            $amount_to_show = $transaction_info['Transaction']['amount'];
            $str .='<tr>
                                <td width="10%" class="firstCol">';
            if ($transaction_info['Transaction']['activity_type'] == 'Y' || $transaction_info['Transaction']['promotion_type'] == '1') {
                $str .='<a title="Delete" href="javascript:void(0);"  class="btn btn-xs btn-danger" style="cursor:default;"><i class="ace-icon glyphicon glyphicon-trash grey"></i></a>';
            } else {
                $str .='<a title="Delete" href="/PatientManagement/deletehistory/' . $transaction_info['Transaction']['id'] . '/' . $sessionstaffcheck['customer_info']['User']['id'] . '/' . $sessionstaffcheck['customer_info']['card_number'] . '"  class="btn btn-xs btn-danger"><i class="ace-icon glyphicon glyphicon-trash"></i></a>';
            }
            $str .='</td><td width="30%">' . $transaction_info['Transaction']['date'] . ': </td>';
            if ($transaction_info['Transaction']['activity_type'] == 'Y') {
                $str .='<td width="10%" class="amountpositive">' . round($amount_to_show) . '</td>';
            } else {
                $str .='<td width="10%" class="amountpositive">+' . round($amount_to_show) . '</td>';
            }
            $str .='<td width="50%"> &#8211; ' . $transaction_info['Transaction']['authorization'];
            if ($sessionstaffcheck['is_buzzydoc'] == 1 && $transaction_info['Transaction']['status'] == 'Active' && $transaction_info[Transaction]['activity_type'] == 'Y' && $transaction_info[Transaction]['amount'] == '0') {
                $str .='<span id="coupon_' . $transaction_info['Transaction']['id'] . '">
				  <select name="redeem_status_' . $transaction_info['Transaction']['id'] . '" id="redeem_status_' . $transaction_info['Transaction']['id'] . '" onchange="changestatus(' . $transaction_info[Transaction][id] . ',' . $sessionstaffcheck['clinic_id'] . ')">';
                if ($transaction_info['Transaction']['status'] != 'Active') {
                    $str .='<option value="Redeemed" selected="selected">Redeemed</option>';
                } else {
                    $str .='<option value="Active" selected="selected">Active</option>
   	 <option value="Redeemed">Redeemed</option> ';
                }
                $str .='</select></span>';
            }
            $str .='</td>
                            </tr>';
        }
        echo $str;
        die;
    }

    /**

     * Get interval reward plan details for user.
     * @param type $treatment_id
     * @return type
     */
    public function getIntervalDetails($treatment_id) {
        if ($treatment_id) {
            $options['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $this->_clinicId,
                'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                'UserPerfectVisit.user_id' => $this->getUserId(),
                'UserPerfectVisit.level_achieved like' => '%Phase%'
            );
            $options['order'] = array('UserPerfectVisit.date desc');
            $data = $this->UserPerfectVisit->find('first', $options);
            if (!empty($data)) {
                $phaseval = explode(' ', $data['UserPerfectVisit']['level_achieved']);
                $nextphase = $phaseval[1] + 1;

                $optionsget['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $this->_clinicId,
                    'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                    'UserPerfectVisit.user_id' => $this->getUserId(),
                    'UserPerfectVisit.date >' => $data['UserPerfectVisit']['date'],
                    'UserPerfectVisit.is_perfect' => 1
                );
            } else {
                $optionsget['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $this->_clinicId,
                    'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                    'UserPerfectVisit.user_id' => $this->getUserId(),
                    'UserPerfectVisit.is_perfect' => 1
                );
                $nextphase = 1;
            }
            $datagetcount = $this->UserPerfectVisit->find('all', $optionsget);
            $getremain = count($datagetcount);

            //query to check treatment end
            $optionscheck['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $this->_clinicId,
                'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                'UserPerfectVisit.user_id' => $this->getUserId()
            );
            $optionscheck['order'] = array('UserPerfectVisit.date desc');
            $datacheck = $this->UserPerfectVisit->find('first', $optionscheck);
            if (isset($datacheck['UserPerfectVisit']['is_treatment_over']) && $datacheck['UserPerfectVisit']['is_treatment_over'] == 1) {
                return $interval_details = array('Phase' => 'Over', 'Visit' => 0, 'CurrentPhase' => $data['UserPerfectVisit']['level_achieved']);
            } else {
                return $interval_details = array('Phase' => 'Phase ' . $nextphase, 'Visit' => $getremain);
            }
        }
        return array();
    }

    /**
     * Get persent value set for accelerated reward plan.
     */
    public function getPointVal() {
        $this->layout = "";
        $sessionstaffcheck = $this->Session->read('staff');
        $pointval = (($sessionstaffcheck['staffaccess']['AccessStaff']['base_value'] * $_POST['dollar_val']) / 100) * 50;
        echo number_format($pointval, 2, '.', '');

        exit();
    }

    /**
     * Get the staff report
     */
    public function getStaffReport() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $staffreport = array();
        if ($sessionstaff['staffaccess']['AccessStaff']['staff_reward_program'] == 1) {
            $reports = $this->Api->getClinicReportingData(0, $sessionstaff['clinic_id'], $sessionstaff['staff_id'], 1);
            foreach ($reports as $report) {
                if ($_POST['goal_id'] == $report['details_for']['Goal']['id']) {
                    $staffreport['goal_name'] = $report['details_for']['Goal']['goal_name'];
                    $staffreport['target'] = $report['details_for']['GoalSetting']['target_value'];
                    if (isset($report['return'])) {
                        $staffreport['goal_achieved'] = $report['return'][0][0]['user_count'];
                    } else {
                        $staffreport['goal_achieved'] = 0;
                    }
                }
            }
        }

        echo json_encode($staffreport, true);
        exit();
    }

    /**
     * Send mail for account info to patient.
     */
    public function sendAccountInfo() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        $Email = new CakeEmail(MAILTYPE);
        if (isset($sessionpatient['customer_info']['card_number'])) {
            if ($sessionpatient['display_name'] != '') {
                $clinic_name = $sessionpatient['display_name'];
            } else {
                $clinic_name = $sessionpatient['api_user'];
            }

            $clinic = $this->Clinic->find('first', array(
                'conditions' => array(
                    'Clinic.id' => $sessionpatient['clinic_id']
                )
            ));
            $template_array = $this->Api->get_template(42);
            $link1 = str_replace('[card_number]', $sessionpatient['customer_info']['card_number'], $template_array['content']);
            $link = str_replace('[first_name]', $sessionpatient['customer_info']['first_name'], $link1);
            $link2 = str_replace('[last_name]', $sessionpatient['customer_info']['last_name'], $link);
            $link3 = str_replace('[emailid]', $sessionpatient['customer_info']['email'], $link2);
            $link4 = str_replace('[account_password]', $sessionpatient['customer_info']['customer_password'], $link3);
            $link5 = str_replace('[click_here]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">Click Here</a>', $link4);
            $link6 = str_replace('[reset_link]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '/rewards/forgotpassword">Reset Password</a>', $link5);
            $link7 = str_replace('[username]', $sessionpatient['customer_info']['first_name'], $link6);
            $subject = str_replace('[clinic_name]', $clinic_name, $template_array['subject']);
            $Email = new CakeEmail(MAILTYPE);
            $Email->from(array(
                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
            ));

            $Email->to($sessionpatient['customer_info']['email']);
            $Email->subject($subject)
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email->viewVars(array(
                'msg' => $link7
            ));
            $Email->send();
            echo 1;
        } else {
            echo 0;
        }

        exit();
    }

    /**
     * Check internal id is unique for our system for both adult and child while upadting the patinet record.
     */
    public function checkInternalId() {
        $this->layout = "";
        $checkusers_field = $this->User->find('first', array(
            'conditions' => array(
                'User.internal_id' => $_POST['internal_id'],
                'User.id !=' => $_POST['user_id']
            )
        ));
        if (empty($checkusers_field)) {
            echo 0;
        } else {
            echo 1;
        }

        exit();
    }

    /**
     * Send mail for rate and review to patient.
     */
    public function sendRequestReview() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        $phone_val = '';
        foreach ($sessionpatient['customer_info'] as $pfield) {
            if (isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field'] == 'phone') {

                $phone_val = $pfield['PFU']['value'];
            }
        }
        $Email = new CakeEmail(MAILTYPE);
        $staff_id = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.id' => $sessionpatient['staff_id']
            )
        ));
        if (isset($sessionpatient['customer_info']['email']) && $sessionpatient['customer_info']['email'] != '') {
            $alreadyshare = 0;
            if ($_POST['location_id'] > 0) {
                $alreadyrate = $this->RateReview->find('first', array(
                    'conditions' => array(
                        'RateReview.clinic_id' => $sessionpatient['clinic_id'],
                        'RateReview.clinic_location_id' => $_POST['location_id'],
                        'RateReview.google_share' => 1,
                        'RateReview.yahoo_share' => 1,
                        'RateReview.yelp_share' => 1,
                        'RateReview.healthgrades_share' => 1,
                        'RateReview.facebook_share' => 1,
                        'RateReview.user_id' => $sessionpatient['customer_info']['User']['id']
                    )
                ));
                if (!empty($alreadyrate)) {
                    $alreadyshare = 1;
                }
            }
            if ($alreadyshare == 1) {
                echo 2;
            } else {
                if ($sessionpatient['display_name'] != '') {
                    $clinic_name = $sessionpatient['display_name'];
                } else {
                    $clinic_name = $sessionpatient['api_user'];
                }

                $clinic = $this->Clinic->find('first', array(
                    'conditions' => array(
                        'Clinic.id' => $sessionpatient['clinic_id']
                    )
                ));
                $optionlocs['conditions'] = array('ClinicLocation.id' => $_POST['location_id']);
                $clinicLocation = $this->ClinicLocation->find('first', $optionlocs);
                if ($_POST['location_id'] == 0) {
                    $query = "SELECT Promotion.* FROM promotions AS Promotion WHERE Promotion.is_lite = '0' AND Promotion.clinic_id = " . $sessionpatient['clinic_id'] . " AND Promotion.is_global=0 AND Promotion.default=2 AND Promotion.description !='Google Share' AND Promotion.description !='Yahoo Share' AND Promotion.description !='Yelp Share' AND Promotion.description !='Healthgrades Share' AND Promotion.default=2";
                    $query1 = "SELECT sum(Promotion.value) AS total FROM promotions AS Promotion WHERE Promotion.is_lite = '0' AND Promotion.clinic_id = " . $sessionpatient['clinic_id'] . " AND Promotion.is_global=0 AND Promotion.default=2 AND Promotion.description !='Google Share' AND Promotion.description !='Yahoo Share' AND Promotion.description !='Yelp Share' AND Promotion.description !='Healthgrades Share' AND Promotion.default=2";
                } else {
                    $query = "SELECT Promotion.* FROM promotions AS Promotion WHERE Promotion.is_lite = '0' AND Promotion.clinic_id = " . $sessionpatient['clinic_id'] . " AND Promotion.is_global=0 AND";
                    $query1 = "SELECT sum(Promotion.value) AS total FROM promotions AS `Promotion` WHERE Promotion.is_lite = '0' AND Promotion.clinic_id = " . $sessionpatient['clinic_id'] . " AND Promotion.is_global=0 AND";
                    if ($clinicLocation['ClinicLocation']['google_business_page_url'] == '') {
                        $query .= " Promotion.description !='Google Share' AND";
                        $query1 .= " Promotion.description !='Google Share' AND";
                    }
                    if ($clinicLocation['ClinicLocation']['yahoo_business_page_url'] == '') {
                        $query .= " Promotion.description !='Yahoo Share' AND";
                        $query1 .= " Promotion.description !='Yahoo Share' AND";
                    }
                    if ($clinicLocation['ClinicLocation']['yelp_business_page_url'] == '') {
                        $query .= " Promotion.description !='Yelp Share' AND";
                        $query1 .= " Promotion.description !='Yelp Share' AND";
                    }
                    if ($clinicLocation['ClinicLocation']['healthgrades_business_page_url'] == '') {
                        $query .= " Promotion.description !='Healthgrades Share' AND";
                        $query1 .= " Promotion.description !='Healthgrades Share' AND";
                    }
                    $query .= " Promotion.default=2";
                    $query1 .= " Promotion.default=2";
                }
                $ratePromotion = $this->Promotion->query($query1);
                $ratePromotion1 = $this->Promotion->query($query);
                $rating_str = '';
                foreach ($ratePromotion1 as $ratreview) {
                    $rating_str .= '<ul><li><span style="color: #000000;">';
                    if ($ratreview['Promotion']['description'] == 'Rate') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Ratings Us';
                    }
                    if ($ratreview['Promotion']['description'] == 'Review') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Reviewing Us';
                    }
                    if ($ratreview['Promotion']['description'] == 'Facebook Share') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Sharing on Facebook';
                    }
                    if ($ratreview['Promotion']['description'] == 'Google Share') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Sharing on Google+';
                    }
                    if ($ratreview['Promotion']['description'] == 'Yahoo Share') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Sharing on Yahoo';
                    }
                    if ($ratreview['Promotion']['description'] == 'Yelp Share') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Sharing on Yelp';
                    }
                    if ($ratreview['Promotion']['description'] == 'Healthgrades Share') {
                        $rating_str .=$ratreview['Promotion']['value'] . ' pts for Sharing on Healthgrades';
                    }

                    $rating_str .='</span></li></ul>';
                }

                $template_array = $this->Api->get_template(43);
                $subject = str_replace('[clinic_name]', $clinic_name, $template_array['subject']);
                $subject1 = str_replace('[staff_name]', $staff_id['Staff']['staff_id'], $subject);
                $link1 = str_replace('[username]', $sessionpatient['customer_info']['first_name'], $template_array['content']);
                $link2 = str_replace('[points]', $ratePromotion[0][0]['total'], $link1);
                $identifer = base64_encode(mt_rand(100000, 999999) . '-' . $sessionpatient['staff_id'] . '-' . $sessionpatient['clinic_id'] . '-' . $sessionpatient['customer_info']['User']['id'] . '-' . $_POST['location_id']);
                $link3 = str_replace('[click_here]', '<a href="' . Buzzy_Name . 'ratereview/' . $identifer . '">Click Here</a>', $link2);
                $link4 = str_replace('[points_breakdown]', $rating_str, $link3);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(
                    SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                ));
                $Email->to($sessionpatient['customer_info']['email']);
                $Email->subject($subject1)
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array(
                    'msg' => $link4
                ));
                $Email->send();
                if ($phone_val != '' && $template_array['sms_body'] != '' && $sessionpatient['staffaccess']['AccessStaff']['sms'] == 1) {
                    $sms_link1 = str_replace('[username]', $sessionpatient['customer_info']['first_name'], $template_array['sms_body']);
                    $sms_link2 = str_replace('[points]', $ratePromotion[0][0]['total'], $sms_link1);
                    $longUrl = Buzzy_Name . 'ratereview/' . $identifer ;
                    $apiKey = 'AIzaSyDBdKRBt_jK7J2TRKB0bzg1nfKmP9KtzDc';
                    $postData = array('longUrl' => $longUrl);
                    $jsonData = json_encode($postData);
                    $curlObj = curl_init();
                    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
                    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curlObj, CURLOPT_HEADER, 0);
                    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
                    curl_setopt($curlObj, CURLOPT_POST, 1);
                    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
                    $response = curl_exec($curlObj);
                    $json = json_decode($response);
                    curl_close($curlObj);
                    $sms_link3 = str_replace('[click_here]', $json->id, $sms_link2);
                    $sms_link4 = str_replace('[clinic_name]', $clinic_name, $sms_link3);
                    $cred = new Catapult\Credentials(BANDWIDTH_USER_ID, BANDWIDTH_API_TOKEN, BANDWIDTH_API_SECRET);
                    $client = new Catapult\Client($cred);
                    try {
                        $messageSend = new Catapult\Message(array(
                            "from" => new Catapult\PhoneNumber('+12054199750'),
                            "to" => new Catapult\PhoneNumber(COUNTRY_CODE . $phone_val),
                            "text" => new Catapult\TextMessage($sms_link4)
                        ));
                    } catch (\CatapultApiException $e) {
                        
                    }
                }
                echo 1;
            }
        } else {
            echo 0;
        }

        exit();
    }

    public function readnotification() {
        $this->layout = "";
        $redecnt = $this->ClinicNotification->query('update clinic_notifications set status="1" where id=' . $_POST['notification_id']);
        echo 1;
        exit();
    }

    public function setsession() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        $this->Session->write('staff.col', $_POST['session']);
        $this->Session->write('staff.search', 0);
        echo 1;
        exit();
    }

}

?>
