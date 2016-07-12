<?php

/**
 *  This file for manage all new lead added by patient and asiagn new card to new lead.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller for manage all new lead added by patient and asiagn new card to new lead.
 */
class LeadManagementController extends AppController {

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
    public $uses = array('Transaction', 'Clinic', 'Notification', 'User', 'Staffs', 'Refer', 'IndustryType', 'AdminSetting', 'LeadLevel', 'CardNumber', 'ClinicUser', 'UnregTransaction', 'TrainingVideo','RateReview','ClinicNotification');

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
     * getting the lead settings,lead count and new lead added for new notification.
     */
    public function index($id) {
        $sessionstaff = $this->Session->read('staff');
        //mark as new lead is attanded by staff user
        $redecnt = $this->ClinicNotification->query('update clinic_notifications set status="1" where clinic_id=' . $sessionstaff['clinic_id'].' and notification_type=3');
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $clinic_ind = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $sessionstaff['clinic_id'])));
        $indsurty = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $clinic_ind['Clinic']['industry_type'])));
        $this->set('LeadLevel', $indsurty);
        $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $sessionstaff['clinic_id'], 'AdminSetting.setting_type' => 'leadpoints')));
        $this->set('admin_settings', $admin_set);
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Leads');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }

    /**
     *  getting the list of all lead added for this clinic by patient.
     */
    public function getLead() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('refers.first_name', 'refers.last_name', 'users.first_name', 'refers.prefer_time', 'refers.phone', 'refers.refdate', 'refers.status');
        $sIndexColumn = "id";
        $sTable = 'refers';

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }
        //Ordering
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $_GET['sSearch'] = str_replace('%', '#$@19', $_GET['sSearch']);
        //Filtering
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE clinic_id=" . $sessionstaff['clinic_id'] . "";
        } else {
            $sWhere .=" AND clinic_id=" . $sessionstaff['clinic_id'] . "";
        }

        $sQuery = "SELECT * FROM   $sTable inner join users on users.id=$sTable.user_id $sWhere $sOrder $sLimit";
        $rResult = $this->Refer->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable inner join users on users.id=$sTable.user_id $sWhere $sOrder";
        $aResultFilterTotal = $this->Refer->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);



        $sQuery = "select * from $sTable inner join users on users.id=$sTable.user_id where clinic_id=" . $sessionstaff['clinic_id'] . "";
        $aResultTotal = $this->Refer->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $val) {
            if ($val['refers']['prefer_time'] == '') {
                $pref = 'NA';
            } else {
                $pref = $val['refers']['prefer_time'];
            }
            if ($val['refers']['phone'] == '') {
                $phone = 'NA';
            } else {
                $phone = $val['refers']['phone'];
            }
            $row = array();
            $row[] = $val['refers']['first_name'];
            $row[] = $val['refers']['last_name'];
            $row[] = $val['users']['first_name'] . ' ' . $val['users']['last_name'];
            $row[] = $pref;
            $row[] = $phone;
            $row[] = $val['refers']['refdate'];
            if ($val['refers']['status'] == 'Completed') {
                $row[] = "<span class='label label-success arrowed-in arrowed-in-right newstatus'>" . $val['refers']['status'] . "</span>";
            } else if ($val['refers']['status'] == 'Failed') {
                $row[] = "<span id='" . $val['refers']['id'] . "_redeem_td_id' onclick='changeStatusRedeem(" . $val['refers']['id'] . ")' class='label label-inverse arrowed newstatus'>" . $val['refers']['status'] . "</span>";
            } else if ($val['refers']['status'] == 'Accepted') {
                $row[] = "<span id='" . $val['refers']['id'] . "_redeem_td_id' onclick='changeStatusRedeem(" . $val['refers']['id'] . ")' class='label label-info arrowed-in arrowed-in-right newstatus'>" . $val['refers']['status'] . "</span>";
            } else if ($val['refers']['status'] == 'Pending') {
                $row[] = "<span id='" . $val['refers']['id'] . "_redeem_td_id' onclick='changeStatusRedeem(" . $val['refers']['id'] . ")' class='label label-warning newstatus'>" . $val['refers']['status'] . "</span>";
            } else {
                $row[] = "<span id='" . $val['refers']['id'] . "_redeem_td_id' onclick='changeStatusRedeem(" . $val['refers']['id'] . ")' class='label label-warning newstatus'>Pending</span>";
            }

            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }

    /**
     *  getting the details of referer.
     */
    public function getleaddetail() {
        $this->layout = null;
        $sessionstaff = $this->Session->read('staff');
        $sQuery = "SELECT * FROM refers inner join users on users.id=refers.user_id where refers.clinic_id=" . $sessionstaff['clinic_id'] . ' and refers.id=' . $_POST['id'] . ' and refers.is_recom!=1';
        $rResult = $this->Refer->query($sQuery);
        echo json_encode(array('refby' => $rResult[0]['users']['first_name'] . ' ' . $rResult[0]['users']['last_name'], 'refto' => $rResult[0]['refers']['first_name'] . ' ' . $rResult[0]['refers']['last_name']));
        exit;
    }

    /**
     *  assign new card to new lead patient and awarded the points to referer.
     */
    public function changestatus() {
        $this->layout = null;
        $status_name = $this->request->data['status'];
        $sessionstaff = $this->Session->read('staff');

        if (($this->request->data['id'] != '') && ($this->request->data['status'] != '')) {
            $card = $this->CardNumber->find('first', array('conditions' => array('CardNumber.clinic_id' => $sessionstaff['clinic_id'], 'CardNumber.card_number' => $this->request->data['card_number'], 'CardNumber.status' => 1)));
            //condition to check card number is exist for clinic
            if (empty($card) && $this->request->data['card_number'] != '') {
                echo 3;
            } else {
                $card = $this->ClinicUser->find('first', array('conditions' => array('ClinicUser.clinic_id' => $sessionstaff['clinic_id'], 'ClinicUser.card_number' => $this->request->data['card_number'])));
                //condition to check card number is free for assign to patient
                if (!empty($card) && $this->request->data['card_number'] != '') {
                    echo 3;
                } else {
                    $level = explode('-', $this->request->data['level']);
                    $refer['Refer'] = array('id' => $this->request->data['id'], 'status' => $this->request->data['status'], 'assigned_card' => $this->request->data['card_number'], 'points' => $this->request->data['points'], 'level' => $level[1]);
                    $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $sessionstaff['clinic_id'])));
                    //assign card to new lead
                    if ($this->Refer->save($refer) && $this->request->data['status'] == 'Completed') {

                        $redinfo = $this->Refer->find('first', array('conditions' => array('Refer.id' => $this->request->data['id'])));
                        //email to refferer for new card number assigned


                        $template_array = $this->Api->get_template(23);
                        $link = str_replace('[click_here]', '<a href="' . rtrim($sessionstaff['patient_url'], '/') . '">Click Here</a>', $template_array['content']);
                        $link1 = str_replace('[card_number]', $this->request->data['card_number'], $link);
                        $link2 = str_replace('[username]', $redinfo['Refer']['first_name'], $link1);
                        $Email2 = new CakeEmail(MAILTYPE);
                        $Email2->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                        $Email2->to($redinfo['Refer']['email']);
                        $Email2->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email2->viewVars(array('msg' => $link2
                        ));
                        $Email2->send();


                        if ($sessionstaff['is_buzzydoc'] == 1) {
                            $isbuz = 1;
                        } else {
                            $isbuz = 0;
                        }

                        //code end hear
                        $user = $this->User->find('first', array('conditions' => array('User.id' => $redinfo['Refer']['user_id'])));
                        $usercl = $this->ClinicUser->find('first', array('conditions' => array('ClinicUser.user_id' => $redinfo['Refer']['user_id'], 'ClinicUser.clinic_id' => $sessionstaff['clinic_id'])));
                        $data['user_id'] = $user['User']['id'];
                        $data['card_number'] = $usercl['ClinicUser']['card_number'];
                        $data['first_name'] = $user['User']['first_name'];
                        $data['last_name'] = $user['User']['last_name'];
                        $data['activity_type'] = 'N';
                        $data['amount'] = $this->request->data['points'];
                        $getval = $this->Api->getPatientLevelForAcceleratedReward($sessionstaff['clinic_id'], $user['User']['id']);
                        $data['amount'] = $data['amount'] * $getval;
                        $data['authorization'] = 'Referring a friend';
                        $data['clinic_id'] = $sessionstaff['clinic_id'];
                        $data['staff_id'] = $sessionstaff['staff_id'];
                        $data['date'] = date('Y-m-d H:i:s');
                        $data['status'] = 'New';
                        $data['is_buzzydoc'] = $isbuz;
                        $this->Transaction->save($data);
                        //point awarded for lead completion to referer
                        if ($sessionstaff['is_buzzydoc'] == 1) {
                            $totalpoint = $user['User']['points'] + $data['amount'];
                            $this->User->query("UPDATE `users` SET `points` = '" . $totalpoint . "' WHERE `id` =" . $user['User']['id']);
                        } else {
                            
                            $totalpoint = $usercl['ClinicUser']['local_points'] + $data['amount'];
                            $this->User->query("UPDATE `clinic_users` SET `local_points` = '" . $totalpoint . "' WHERE `user_id` =" . $user['User']['id'] . ' and clinic_id=' . $sessionstaff['clinic_id']);
                        }
                        $getfirstTransaction = $this->Api->get_firsttransaction($user['User']['id'], $sessionstaff['clinic_id']);
                        //mail send for new point earned to referer for first time
                        if ($getfirstTransaction == 1 && $data['amount']>0) {
                            $template_array = $this->Api->get_template(39);
                            $link1 = str_replace('[username]', $user['User']['first_name'], $template_array['content']);
                            $link = str_replace('[points]', $data['amount'], $link1);
                            $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link);
                            $Email = new CakeEmail(MAILTYPE);
                            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                            $Email->to($user['User']['email']);
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array('msg' => $link2
                            ));
                            $Email->send();
                        }
                        $patients = $this->Notification->find('first', array('conditions' => array('Notification.earn_points' => 1, 'Notification.user_id' => $user['User']['id'])));

                        if (!empty($patients) && $data['amount']>0) {


                            $rewardlogin = rtrim($sessionstaff['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($usercl['ClinicUser']['card_number']) . "/" . base64_encode($user['User']['id']) . "/Unsubscribe";
                            //mail send for new point earned to referer
                            $template_array = $this->Api->get_template(1);
                            $link = str_replace('[username]', $user['User']['first_name'], $template_array['content']);
                            $link1 = str_replace('[click_here]', '<a href="' . rtrim($sessionstaff['patient_url'], '/') . '">Click Here</a>', $link);
                            $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link1);
                            $link3 = str_replace('[points]', $data['amount'], $link2);
                            $Email = new CakeEmail(MAILTYPE);
                            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                            $Email->to($user['User']['email']);
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array('msg' => $link3
                            ));
                            $Email->send();
                        }

                        if (($status_name == 'Failed')) {
                            echo 1;
                        } elseif (($status_name == 'Completed')) {
                            echo 2;
                        } else {
                            echo 4;
                        }
                    } else {
                        echo 1;
                    }
                }
            }
        }


        exit;
    }
    /**
     * get the all reports for referrals
     */
    public function referralsreport(){
        
        $sessionstaff = $this->Session->read('staff');
        $TotalRefer = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' group by clinic_id');
        if (empty($TotalRefer)) {
            $this->set('TotalRefer', 0);
        } else {
            $this->set('TotalRefer', $TotalRefer[0][0]['total']);
        }
        $TotalReferPending = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Pending" group by clinic_id');
        if (empty($TotalReferPending)) {
            $this->set('TotalReferPending', 0);
        } else {
            $this->set('TotalReferPending', $TotalReferPending[0][0]['total']);
        }
        $TotalReferAccpeted = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Accepted" group by clinic_id');
        if (empty($TotalReferAccpeted)) {
            $this->set('TotalReferAccpeted', 0);
        } else {
            $this->set('TotalReferAccpeted', $TotalReferAccpeted[0][0]['total']);
        }
        $TotalReferFailed = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Failed" group by clinic_id');
        if (empty($TotalReferFailed)) {
            $this->set('TotalReferFailed', 0);
        } else {
            $this->set('TotalReferFailed', $TotalReferFailed[0][0]['total']);
        }
        $TotalReferCompleted = $this->Refer->query('select count(refers.id) as total,level from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Completed" group by level');
        $completed = array();
        $i = 0;
        foreach ($TotalReferCompleted as $comp) {
            $completed[$i]['total'] = $comp[0]['total'];
            $lead = $this->LeadLevel->find('first', array('conditions' => array('LeadLevel.id' => $comp['refers']['level'])));
            $completed[$i]['leadname'] = $lead['LeadLevel']['leadname'];
            $i++;
        }
        $this->set('TotalReferCompleted', $completed);
    }

}

?>
