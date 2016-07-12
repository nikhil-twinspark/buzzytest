<?php

/**
 * This file for staff admin login,logout and forgot password functionality.
 * 
 * 
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for staff admin login,logout and forgot password functionality.
 * 
 * 
 */
class StaffController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session and security componnents for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'Security');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Staff', 'Clinic', 'ProfileField', 'CardNumber', 'ClinicUser', 'User', 'Transaction', 'Refer', 'AccessStaff', 'Badge', 'TrainingVideo', 'ClinicNotification', 'GlobalRedeem', 'Doctor', 'RateReview', 'Notification', 'Promotion', 'ClinicLocation', 'RssFeed', 'TrainingVideo');

    /**
     * useing the security validation and unlock other action for security check.
     * @return type
     */
    public function beforeFilter() {
        $this->Security->validatePost = false;
        $this->Security->unlockedActions = array('basicreport', 'forgotpassword', 'logout', 'admin', 'sitepreferences', 'instructions', 'adminlogout', 'load_file', 'checkcard', 'reviewreport', 'awardpoint', 'awardpointindivisual');
        $this->Security->blackHoleCallback = 'blackhole';
        $sessionstaff = $this->Session->read('staff');
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login' && $this->params['action'] != 'forgotpassword' && $this->params['action'] != 'checkcard' && $this->params['action'] != 'basicreport' && $this->params['action'] != 'reviewreport' && $this->params['action'] != 'awardpoint' && $this->params['action'] != 'awardpointindivisual') {
            return $this->redirect('/staff/login/');
        }
        $this->layout = $this->layout;
    }

    /**
     * checking the any blackhole while login.
     * @param type $type
     * @return type
     */
    public function blackhole($type) {
        $this->log('Request has been blackholed: ' . $type, 'tests');
        $this->Session->setFlash(__('Looks like you attempted to pass that request incorrectly. 
Please refresh the page and try again.'));
        return $this->redirect(array('controller' => 'staff', 'action' => 'login'));
    }

    /**
     * Staff Admin Login from staff site and from super admin.
     * @return type
     */
    public function login() {
        $this->layout = "stafflogin";
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = explode('.', $_SERVER['HTTP_HOST']);

            $options['conditions'] = array('Clinic.api_user' => $host[0]);
            $credResult = $this->Clinic->find('first', $options);
            //condition to check existing staff url is valid.
            if (empty($credResult)) {
                $options1['conditions'] = array('Clinic.staff_url Like' => "%" . $_SERVER['HTTP_HOST'] . "%");
                $credResult = $this->Clinic->find('first', $options1);
            }
            if (empty($credResult)) {
                $chkdomain = str_replace($host[0] . '.', '', $_SERVER['HTTP_HOST']);
                if ($chkdomain == Domain_Name) {
                    $options['conditions'] = array('Clinic.staff_url LIKE ' => "%" . $host[0] . "%");
                    $credResult = $this->Clinic->find('first', $options);
                }
            }
            if (!empty($credResult)) {


                $optionsbadge['conditions'] = array(
                    'Badge.clinic_id' => 0
                );
                $Badge = $this->Badge->find('all', $optionsbadge);
                $optionsbadgech['conditions'] = array(
                    'Badge.value >' => 0, 'Badge.clinic_id' => $credResult['Clinic']['id']
                );
                $Badgech = $this->Badge->find('all', $optionsbadgech);
                //creating the local badge for clinic from global badges.
                if (empty($Badgech)) {
                    foreach ($Badge as $bg) {
                        $savebadge['Badge'] = array(
                            'name' => $bg['Badge']['name'],
                            'description' => $bg['Badge']['description'],
                            'clinic_id' => $credResult['Clinic']['id'],
                            'value' => $bg['Badge']['value'],
                            'created_on' => date('Y-m-d H:i:s')
                        );
                        $this->Badge->create();
                        $this->Badge->save($savebadge);
                    }
                }
                $query = "SELECT `Transaction`.`id` FROM `transactions` AS `Transaction` WHERE `Transaction`.`clinic_id` = " . $credResult['Clinic']['id'] . " AND `Transaction`.`activity_type` = 'Y' AND `Transaction`.`status` != 'Ordered/Shipped' AND `Transaction`.`status` != 'New' and Transaction.redeem_from=0";
                $transdet = $this->Transaction->query($query);
                //condition to get un-redemed transactions
                if (empty($transdet) && $credResult['Clinic']['is_buzzydoc'] == 1) {
                    $this->Session->write('staff.transchk', 1);
                } else {
                    $this->Session->write('staff.transchk', 0);
                }
                $getstaff = $this->Staff->find('first', array(
                    'conditions' => array(
                        'Staff.staff_role' => 'Doctor', 'Staff.clinic_id' => $credResult['Clinic']['id']
                    )
                ));
                //condition to get practice have a doctor or not.
                if (!empty($getstaff)) {
                    $this->Session->write('staff.haveDoc', 1);
                } else {
                    $this->Session->write('staff.haveDoc', 0);
                }
                $this->Session->write('staff.staff_logo', S3Path . $credResult['Clinic']['staff_logo_url']);
                $this->Session->write('staff.api_user', $credResult['Clinic']['api_user']);
                //condition for pratcie is buzzydoc or legacy.
                if (isset($credResult['Clinic']['is_buzzydoc']) && $credResult['Clinic']['is_buzzydoc'] == 1) {
                    $isbuzzy = 1;
                } else {
                    $isbuzzy = 0;
                }
                //condition to check pratice is like aur elite.
                if (isset($credResult['Clinic']['is_lite']) && $credResult['Clinic']['is_lite'] == 1) {
                    $islite = 1;
                } else {
                    $islite = 0;
                }
                //condition to check pratice setting for redirect to landing page or search page.
                if (isset($credResult['Clinic']['landing']) && $credResult['Clinic']['landing'] == 1) {
                    $landing = 1;
                } else {
                    $landing = 0;
                }
                if (isset($credResult['Clinic']['display_name']) && $credResult['Clinic']['display_name'] != '') {
                    $display_name = $credResult['Clinic']['display_name'];
                } else {
                    $display_name = $credResult['Clinic']['api_user'];
                }
                $this->Session->write('staff.display_name', $display_name);
                $this->Session->write('staff.is_buzzydoc', $isbuzzy);
                $this->Session->write('staff.is_lite', $islite);
                $this->Session->write('staff.landing', $landing);
                $this->Session->write('staff.clinic_id', $credResult['Clinic']['id']);
                setcookie('searchCust', '', time() + (86400 * 30), "/");
                setcookie('navigationOn', 0, time() + (86400 * 30), "/");
                $this->Session->write('staff.lead_log', $credResult['Clinic']['lead_log']);
                $this->Session->write('staff.log_time', $credResult['Clinic']['log_time']);
                $this->Session->write('staff.patient_url', $credResult['Clinic']['patient_url']);
                $ProField = $this->ProfileField->query('SELECT `ProfileField`.`id`, `ProfileField`.`profile_field`, `ProfileField`.`type`, `ProfileField`.`options`, `ProfileField`.`clinic_id` FROM `profile_fields` AS `ProfileField` WHERE ((`ProfileField`.`clinic_id` = 0) OR (`ProfileField`.`clinic_id` = ' . $credResult['Clinic']['id'] . ')) ');
                //all local profile field store in to session.
                $this->Session->write('staff.ProfileField', $ProField);
                $this->Session->write('staff.patient_url', $credResult['Clinic']['patient_url']);
            }
        }
        //direct login from super admin site.
        if (isset($this->request->pass[0])) {
            $sessionstaff = $this->Session->read('staff');
            $options1['conditions'] = array('Staff.id' => base64_decode($this->request->pass[0]));
            $StaffResult = $this->Staff->find('first', $options1);
            if (isset($sessionstaff['api_user'])) {
                if (!empty($StaffResult)) {
                    $this->Session->write('staff.var.staff_name', $StaffResult['Staff']['staff_id']);
                    $this->Session->write('staff.var.staff_fname', $StaffResult['Staff']['staff_first_name']);
                    $this->Session->write('staff.var.staff_password', $StaffResult['Staff']['staff_password']);
                    $this->Session->write('staff.staff_id', $StaffResult['Staff']['id']);
                    $this->Session->write('staff.staff_email', $StaffResult['Staff']['staff_email']);
                    $this->Session->write('staff.staff_role', $StaffResult['Staff']['staff_role']);
                    $this->Session->write('staff.is_superstaff', $StaffResult['Staff']['is_superstaff']);
                    setcookie('searchCust', '', time() + (86400 * 30), "/");
                    setcookie('navigationOn', 0, time() + (86400 * 30), "/");
                    if (isset($this->request->pass[1])) {
                        $this->Session->write('staff.search_from_api', base64_decode($this->request->pass[1]));
                    }
                    return $this->redirect(array('controller' => 'PatientManagement', 'action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Invalid Credentials'));
                }
            }
        }
        if ($this->request->is('post')) {


            $sessionstaff = $this->Session->read('staff');
            $options1['conditions'] = array('Staff.staff_id' => $this->request->data['login']['staff_name'], 'BINARY (`Staff`.`staff_password`) LIKE' => md5($this->request->data['login']['staff_password']), 'Staff.clinic_id' => $credResult['Clinic']['id']);
            $StaffResult = $this->Staff->find('first', $options1);
            //checking the credentials for staff for login.
            if (isset($sessionstaff['api_user'])) {
                if (!empty($StaffResult)) {
                    $this->Session->write('staff.var.staff_name', $this->request->data['login']['staff_name']);
                    $this->Session->write('staff.var.staff_fname', $StaffResult['Staff']['staff_first_name']);
                    $this->Session->write('staff.var.staff_password', $StaffResult['Staff']['staff_password']);
                    $this->Session->write('staff.staff_id', $StaffResult['Staff']['id']);
                    $this->Session->write('staff.staff_email', $StaffResult['Staff']['staff_email']);
                    $this->Session->write('staff.staff_role', $StaffResult['Staff']['staff_role']);
                    $this->Session->write('staff.is_superstaff', $StaffResult['Staff']['is_superstaff']);
                    setcookie('searchCust', '', time() + (86400 * 30), "/");
                    setcookie('navigationOn', 0, time() + (86400 * 30), "/");
                    return $this->redirect(array('controller' => 'PatientManagement', 'action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Invalid Credentials'));
                }
            }
            $this->Session->setFlash(__('Invalid Credentials'));
        }
    }

    /**
     * Get the report of all basic report for Practice like (Point Given,All refer data).
     */
    public function basicreport() {
        $this->layout = "staffLayout";

        $options1['conditions'] = array('Staff.id' => base64_decode($this->request->pass[0]));
        $credResult = $this->Staff->find('first', $options1);
        if (!empty($credResult)) {
            $rss = $this->RssFeed->find('all');
            $this->Session->write('staff.rssFeedsCount', count($rss));
            $this->Session->write('staff.rssFeeds', $rss);
            //get next free card number for auto assign.
            $getfreecard = $this->Api->get_freecardDetails($credResult['Staff']['clinic_id']);
            $this->set('FreeCardDetails', $getfreecard);
            $getallnotifications = $this->ClinicNotification->getNotification($credResult['Staff']['clinic_id']);
            $this->Session->write('staff.AllNotifications', $getallnotifications);
            $trainingvideo = $this->TrainingVideo->find('all');
            //get all training video and store in session for show at header.
            $this->Session->write('staff.trainingvideo', $trainingvideo);
            $this->_clinicId = $credResult['Staff']['clinic_id'];
            if (isset($credResult['Staff']['clinic_id'])) {
                $options3['conditions'] = array(
                    'Clinic.id' => $credResult['Staff']['clinic_id']
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
                    $staffaceess = $this->Api->accessstaff($credResult['Staff']['clinic_id']);
                    $this->Session->write('staff.staffaccess', $staffaceess);
                    $this->Session->write('staff.default_google_page', $ClientCredentials['Clinic']['google_review_page_url']);
                    $treatment = array();

                    $refchk = 0;
                }
            }
            $this->Session->write('staff.var.staff_name', $credResult['Staff']['staff_id']);
            $this->Session->write('staff.var.staff_fname', $credResult['Staff']['staff_first_name']);
            $this->Session->write('staff.var.staff_password', $credResult['Staff']['staff_password']);
            $this->Session->write('staff.clinic_id', $credResult['Staff']['clinic_id']);
            $this->Session->write('staff.staff_id', $credResult['Staff']['id']);
            $this->Session->write('staff.staff_email', $credResult['Staff']['staff_email']);
            $this->Session->write('staff.staff_role', $credResult['Staff']['staff_role']);
            $this->Session->write('staff.is_superstaff', $credResult['Staff']['is_superstaff']);
            setcookie('searchCust', '', time() + (86400 * 30), "/");
            setcookie('navigationOn', 0, time() + (86400 * 30), "/");
            $staffaceess = $this->Api->accessstaff($credResult['Staff']['clinic_id']);
            $this->set('auth', 1);
            $date_chk1 = date('Y-m-d', strtotime('-' . $staffaceess['AccessStaff']['report'] . ' days')) . ' 00:00:00';
            $date_chk2 = date("Y-m-d") . ' 00:00:00';
            $data = array('date_range_picker' => $date_chk1 . ' - ' . $date_chk2, 'transaction_type' => 'N');
            $totalpointgiventhisweek = $this->Api->getTotalPoints($data, $credResult['Staff']['clinic_id']);
            $allgiventrans = $this->Api->getPointhistory($data, $credResult['Staff']['clinic_id']);
            $transaction_given_array = array();
            $this->set('allgiventrans', $transaction_given_array);
            $i1 == 0;
            foreach ($allgiventrans as $val) {
                $transaction_given_array[$i1]['name'] = $val['first_name'] . ' ' . $val['last_name'];
                if ($val['card_number'] == '') {
                    $card = 'BuzzyDoc';
                } else {
                    $card = $val['card_number'];
                }
                $transaction_given_array[$i1]['card_number'] = $card;
                if ($val['authorization'] == '') {
                    $val['authorization'] = 'Manual';
                }
                $transaction_given_array[$i1]['description'] = $val['authorization'];
                $transaction_given_array[$i1]['points_dol'] = $val['amount'] / 50;
                $transaction_given_array[$i1]['points'] = $val['amount'];
                $transaction_given_array[$i1]['date'] = $val['date'];

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
                $transaction_given_array[$i1]['givenby'] = $givenbyname;

                $i1++;
            }
            $this->set('allgiventrans', $transaction_given_array);
            $this->set('totalpointgiventhisweek', $totalpointgiventhisweek);


            $data1 = array('date_range_picker' => $date_chk1 . ' - ' . $date_chk2, 'transaction_type' => 'Y');
            $totalpointredeemed = $this->Api->getTotalPoints($data1, $credResult['Staff']['clinic_id']);
            $this->set('totalamountredeemed', $totalpointredeemed);
            $Patients = $this->Api->getPointhistory($data1, $credResult['Staff']['clinic_id']);

            $transaction_array = array();
            $this->set('allredeemtrans', $transaction_array);
            $i == 0;
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
                $transaction_array[$i]['points'] = ($val['amount']) * -1;
                $transaction_array[$i]['points_dol'] = ($val['amount'] / 50) * -1;
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
            $this->set('allredeemtrans', $transaction_array);

            //end
            //total refer this week
            $allrefer = $this->Refer->find('all', array('conditions' => array('Refer.refdate BETWEEN ? AND ?' => array($date_chk1, $date_chk2), 'Refer.clinic_id' => $credResult['Staff']['clinic_id'])));
            $i = 0;
            foreach ($allrefer as $ref) {
                $user = $this->User->find('first', array('conditions' => array('User.id' => $ref['Refer']['user_id'])));
                $allrefer[$i]['Refer']['user'] = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                $i++;
            }
            $this->set('allrefer', $allrefer);
            $date1 = date('Y-m-d', strtotime('-' . $staffaceess['AccessStaff']['report'] . ' days'));
            $date2 = date('Y-m-d', strtotime('-1 days'));
            $this->set('time', $date1 . ' - ' . $date2);
            //end
        } else {
            $this->set('auth', 0);
        }
    }

    /**
     * Function for password reset for staff user.
     */
    public function forgotpassword() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $email = str_replace(' ', '+', $_POST['staff_email']);
        $staff = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.clinic_id' => $sessionstaff['clinic_id'], 'Staff.staff_email' => $email
            )
        ));
        if (empty($staff)) {
            echo 0;
        } else {


            $staff_email = $staff['Staff']['staff_email'];
            if ($staff_email != '') {
                $new_password = dechex(time()) . mt_rand(0, 100000);

                $this->Staff->query("UPDATE `staffs` SET `staff_password` = md5('" . $new_password . "') WHERE `id` = " . $staff['Staff']['id']);

                $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $sessionstaff['clinic_id'])));
                //email template use for send new password to staff user.
                $template_array = $this->Api->get_template(27);
                $link = str_replace('[password]', $new_password, $template_array['content']);
                $link1 = str_replace('[username]', $staff['Staff']['staff_id'], $link);
                $subject = str_replace('[emailid/staff_name]', $staff['Staff']['staff_id'], $template_array['subject']);
                $Email = new CakeEmail(MAILTYPE);

                $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                $Email->to($staff_email);
                $Email->subject($subject)
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array('msg' => $link1
                ));
                $Email->send();

                echo 1;
            } else {
                echo 0;
            }
        }
        exit;
    }

    /**
     * Logout staff user.
     * @return type
     */
    public function logout() {
        $this->Session->destroy();
        $this->Session->delete('staff.customer_search_results');
        return $this->redirect(array('controller' => 'staff', 'action' => 'login'));
    }

    /**
     * @deprecated
     * @return type
     */
    public function admin() {
        if ($this->request->is('post')) {

            if ($this->Api->verify_admin($this->request->data) == TRUE) {
                $this->Api->get_custom_customer_fields();
                $this->Session->write('staff.admin.loginName', $this->request->data['login']['admin_name']);
                $this->Session->write('staff.admin.loginPassword', $this->request->data['login']['admin_password']);
                return $this->redirect(array('controller' => 'staff', 'action' => 'sitepreferences'));
            }
            $this->Session->setFlash(__('Unable to login.'));
        }
    }

    /**
     * @deprecated
     * @return type
     */
    public function sitepreferences() {

        $sessionstaff = $this->Session->read('staff');
        if (empty($sessionstaff['admin'])) {
            return $this->redirect(array('controller' => 'PatientManagement', 'action' => 'index'));
        }
        if ($this->request->is('post')) {
            if ($this->request->data['final_submit'] == 'no') {
                if (isset($this->request->data['fields_tab'])) {
                    $GLOBALS['vars']['fields_tab'] = $this->request->data['fields_tab'];
                }
            } else {
                $this->Api->update_site_preferences($this->request->data);
                $sessionstaff = $this->Session->read('staff');
            }
        }
    }

    /**
     * Instruction page view.
     */
    public function instructions() {
        $this->layout = "staffLayout";
        $sessionstaff = $this->Session->read('staff');
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Instructions');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }

    /**
     * @deprecated
     * @return type
     */
    public function adminlogout() {
        $this->Session->delete('staff.admin');
        return $this->redirect(array('controller' => 'PatientManagement', 'action' => 'index'));
    }

    /**
     * @deprecated
     * @param type $what
     * @param type $which
     */
    function load_file($what, $which) {
        $sessionstaff = $this->Session->read('staff');
        $handle = fopen($which, 'r');
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                if (!empty($buffer)) {
                    $line_array = explode('||', $buffer);
                    if (!empty($line_array[1])) {
                        $cleaned_value = str_replace("[|]", "\n", trim($line_array[1]));
                    }
                    $preferences[$what][$line_array[0]] = $cleaned_value;
                }
            }



            if (isset($preferences['preferences'])) {
                $public_nav_items = explode(',', $preferences['preferences']['public_top_nav']);
                $this->Session->write('staff.preferences.public_top_nav', $public_nav_items);
                $loggedin_nav_items = explode(',', $preferences['preferences']['loggedin_top_nav']);
                $this->Session->write('staff.preferences.loggedin_top_nav', $loggedin_nav_items);
                $loggedin_customer_nav_items = explode(',', $preferences['preferences']['loggedin_customer_top_nav']);
                $this->Session->write('staff.preferences.loggedin_customer_top_nav', $loggedin_customer_nav_items);
                $public_footer_items = explode(',', $preferences['preferences']['public_footer_links']);
                $this->Session->write('staff.preferences.public_footer_links', $public_footer_items);
                $loggedin_footer_items = explode(',', $preferences['preferences']['loggedin_footer_links']);
                $this->Session->write('staff.preferences.loggedin_footer_links', $loggedin_footer_items);
                $loggedin_customer_footer_items = explode(',', $preferences['preferences']['loggedin_customer_footer_links']);
                $this->Session->write('staff.preferences.loggedin_customer_footer_links', $loggedin_customer_footer_items);
                // Custom Additional Customer Fields:
            }
            fclose($handle);
        }
    }

    /**
     * Function to find out the free card for practice.
     */
    public function checkcard() {
        $this->layout = null;

        $sessionstaff = $this->Session->read('staff');

        if (($this->request->data['card_number'] != '')) {
            $card = $this->CardNumber->find('first', array('conditions' => array('CardNumber.clinic_id' => $sessionstaff['clinic_id'], 'CardNumber.card_number' => $this->request->data['card_number'])));

            if (!empty($card)) {
                if (isset($card['CardNumber']['status']) && $card['CardNumber']['status'] == 2) {
                    echo 4;
                } else {
                    $checkuser = $this->ClinicUser->find('first', array('conditions' => array('ClinicUser.clinic_id' => $sessionstaff['clinic_id'], 'ClinicUser.card_number' => $this->request->data['card_number'])));
                    if (!empty($checkuser)) {
                        echo 4;
                    } else {

                        $this->CardNumber->query("UPDATE `card_numbers` SET `status` = 1 WHERE `clinic_id` =" . $sessionstaff['clinic_id'] . " and card_number=" . $this->request->data['card_number']);
                        echo 1;
                    }
                }
            } else {
                echo 3;
            }
        }


        exit;
    }

    public function notifications() {
        $this->layout = "staffLayout";
        $sessionstaff = $this->Session->read('staff');
        $options1['conditions'] = array('ClinicNotification.clinic_id' => $sessionstaff['clinic_id']);
        $allnotifications = $this->ClinicNotification->find('all', $options1);
        $this->set('notifications', $allnotifications);
    }

    /**
     *  function to short the array by column.
     * @param type $array
     * @param type $column
     * @param type $direction
     */
    function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
        $reference_array = array();

        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }

    /**
     * Get the report of all review report for Practice
     */
    public function reviewreport() {
        $this->layout = "staffLayout";
        $options1['conditions'] = array('Staff.id' => base64_decode($this->request->pass[0]));
        $credResult = $this->Staff->find('first', $options1);
        if (!empty($credResult)) {
            $rss = $this->RssFeed->find('all');
            $this->Session->write('staff.rssFeedsCount', count($rss));
            $this->Session->write('staff.rssFeeds', $rss);
            //get next free card number for auto assign.
            $getfreecard = $this->Api->get_freecardDetails($credResult['Staff']['clinic_id']);
            $this->set('FreeCardDetails', $getfreecard);
            $getallnotifications = $this->ClinicNotification->getNotification($credResult['Staff']['clinic_id']);
            $this->Session->write('staff.AllNotifications', $getallnotifications);
            $trainingvideo = $this->TrainingVideo->find('all');
            $this->Session->write('staff.trainingvideo', $trainingvideo);
            $this->_clinicId = $credResult['Staff']['clinic_id'];
            if (isset($credResult['Staff']['clinic_id'])) {
                $options3['conditions'] = array(
                    'Clinic.id' => $credResult['Staff']['clinic_id']
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
                    $staffaceess = $this->Api->accessstaff($credResult['Staff']['clinic_id']);
                    $this->Session->write('staff.staffaccess', $staffaceess);
                    $this->Session->write('staff.default_google_page', $ClientCredentials['Clinic']['google_review_page_url']);
                    $treatment = array();

                    $refchk = 0;
                }
            }
            //get all training video and store in session for show at header.
            $this->Session->write('staff.trainingvideo', $trainingvideo);
            $this->Session->write('staff.var.staff_name', $credResult['Staff']['staff_id']);
            $this->Session->write('staff.var.staff_fname', $credResult['Staff']['staff_first_name']);
            $this->Session->write('staff.var.staff_password', $credResult['Staff']['staff_password']);
            $this->Session->write('staff.staff_id', $credResult['Staff']['id']);
            $this->Session->write('staff.staff_email', $credResult['Staff']['staff_email']);
            $this->Session->write('staff.staff_role', $credResult['Staff']['staff_role']);
            $this->Session->write('staff.is_superstaff', $credResult['Staff']['is_superstaff']);
            $this->Session->write('staff.clinic_id', $credResult['Staff']['clinic_id']);
            setcookie('searchCust', '', time() + (86400 * 30), "/");
            setcookie('navigationOn', 0, time() + (86400 * 30), "/");
            $optionsclinic['conditions'] = array('Clinic.id' => $credResult['Staff']['clinic_id']);
            $clinicdetails = $this->Clinic->find('first', $optionsclinic);
            $optionlocs['conditions'] = array('ClinicLocation.clinic_id' => $credResult['Staff']['clinic_id']);
            $clinicLocation = $this->ClinicLocation->find('all', $optionlocs);
            $this->set('ClinicLocations', $clinicLocation);
            $this->set('auth', 1);
            $this->set('staff_id', $credResult['Staff']['id']);
            $date_chk1 = $clinicdetails['Clinic']['lead_log'];
            $date_chk2 = date('Y-m-d H:i:s', strtotime(date("Y-m-d", strtotime($date_chk1)) . " +7 days"));
            //list of added review.
            $query = "SELECT `RateReview`.*, `Staff`.`id`, `Staff`.`staff_id`, `clinics`.`api_user`, `User`.`first_name`, `User`.`last_name`, `ClinicUser`.`card_number`,`ClinicLocation`.`google_business_page_url`,`ClinicLocation`.`yahoo_business_page_url`,`ClinicLocation`.`yelp_business_page_url`,`ClinicLocation`.`healthgrades_business_page_url` FROM `rate_reviews` AS `RateReview` left JOIN `staffs` AS `Staff` ON (`Staff`.`id` = `RateReview`.`staff_id`) INNER JOIN `users` AS `User` ON (`User`.`id` = `RateReview`.`user_id`) INNER JOIN `clinic_users` AS `ClinicUser` ON (`ClinicUser`.`user_id` = `User`.`id`) INNER JOIN `clinics` AS `clinics` ON (`clinics`.`id` = `RateReview`.`clinic_id`) LEFT JOIN `clinic_locations` AS `ClinicLocation` ON (`ClinicLocation`.`id` = `RateReview`.`clinic_location_id`) WHERE ((`RateReview`.`notify_staff` = '1') OR (`RateReview`.`yahoo_notify` = '1' ) OR (`RateReview`.`yelp_notify` = '1' ) OR (`RateReview`.`healthgrades_notify` = '1' )) AND ";
            $query .= "`ClinicUser`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`rate` > 0 AND `RateReview`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`created_on` >= '" . $date_chk1 . "' AND `RateReview`.`created_on` <= '" . $date_chk2 . "' ";
            if ($credResult['Staff']['staff_role'] == 'Manager' || $credResult['Staff']['staff_role'] == 'M') {
                $query .= "AND `RateReview`.`staff_id` = " . $credResult['Staff']['id'] . "";
            }
            $ratereview = $this->RateReview->query($query);
            $this->set('Reviews', $ratereview);
            $querycheck = "SELECT `RateReview`.*, `Staff`.`id`, `Staff`.`staff_id`, `clinics`.`api_user`, `User`.`first_name`, `User`.`last_name`, `ClinicUser`.`card_number` FROM `rate_reviews` AS `RateReview` left JOIN `staffs` AS `Staff` ON (`Staff`.`id` = `RateReview`.`staff_id`) INNER JOIN `users` AS `User` ON (`User`.`id` = `RateReview`.`user_id`) INNER JOIN `clinic_users` AS `ClinicUser` ON (`ClinicUser`.`user_id` = `User`.`id`) INNER JOIN `clinics` AS `clinics` ON (`clinics`.`id` = `RateReview`.`clinic_id`) WHERE ((`RateReview`.`notify_staff` = '1' AND `RateReview`.`google_share` = '0') OR (`RateReview`.`yahoo_notify` = '1' AND `RateReview`.`yahoo_share` = '0') OR (`RateReview`.`yelp_notify` = '1' AND `RateReview`.`yelp_share` = '0') OR (`RateReview`.`healthgrades_notify` = '1' AND `RateReview`.`healthgrades_share` = '0')) AND ";
            $querycheck .= "`ClinicUser`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`rate` > 0 AND `RateReview`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`created_on` >= '" . $date_chk1 . "' AND `RateReview`.`created_on` <= '" . $date_chk2 . "' ";
            if ($credResult['Staff']['staff_role'] == 'Manager' || $credResult['Staff']['staff_role'] == 'M') {
                $querycheck .= "AND `RateReview`.`staff_id` = " . $credResult['Staff']['id'] . "";
            }
            $alreadyratereview = $this->RateReview->query($querycheck);
            $this->set('AlreadyReviews', $alreadyratereview);
            //end
        } else {
            $this->set('auth', 0);
        }
    }

    public function awardpoint() {
        $this->layout = '';
        $sessionstaff = $this->Session->read('staff');
        $options1['conditions'] = array('Staff.id' => $_POST['staff_id']);
        $credResult = $this->Staff->find('first', $options1);
        $optionsclinic['conditions'] = array('Clinic.id' => $credResult['Staff']['clinic_id']);
        $clinicdetails = $this->Clinic->find('first', $optionsclinic);
        $date_chk1 = $clinicdetails['Clinic']['lead_log'];
        $date_chk2 = date('Y-m-d H:i:s', strtotime(date("Y-m-d", strtotime($date_chk1)) . " +7 days"));
        //list of added review.

        $query = "SELECT `RateReview`.*, `Staff`.`id`, `Staff`.`staff_id`, `clinics`.`api_user`, `User`.`first_name`, `User`.`last_name`, `ClinicUser`.`card_number` FROM `rate_reviews` AS `RateReview` left JOIN `staffs` AS `Staff` ON (`Staff`.`id` = `RateReview`.`staff_id`) INNER JOIN `users` AS `User` ON (`User`.`id` = `RateReview`.`user_id`) INNER JOIN `clinic_users` AS `ClinicUser` ON (`ClinicUser`.`user_id` = `User`.`id`) INNER JOIN `clinics` AS `clinics` ON (`clinics`.`id` = `RateReview`.`clinic_id`) WHERE ((`RateReview`.`notify_staff` = '1' AND `RateReview`.`google_share` = '0') OR (`RateReview`.`yahoo_notify` = '1' AND `RateReview`.`yahoo_share` = '0') OR (`RateReview`.`yelp_notify` = '1' AND `RateReview`.`yelp_share` = '0') OR (`RateReview`.`healthgrades_notify` = '1' AND `RateReview`.`healthgrades_share` = '0')) AND ";
        $query .= "`ClinicUser`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`rate` > 0 AND `RateReview`.`clinic_id` = " . $credResult['Staff']['clinic_id'] . " AND `RateReview`.`created_on` >= '" . $date_chk1 . "' AND `RateReview`.`created_on` <= '" . $date_chk2 . "' ";
        if ($credResult['Staff']['staff_role'] == 'Manager' || $credResult['Staff']['staff_role'] == 'M') {
            $query .= "AND `RateReview`.`staff_id` = " . $credResult['Staff']['id'] . "";
        }
        $ratereview = $this->RateReview->query($query);
        foreach ($ratereview as $rt) {

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
                    'User.id' => $rt['RateReview']['user_id'],
                    'Clinics.id' => $rt['RateReview']['clinic_id']
                ),
                'fields' => array(
                    'User.*',
                    'Clinics.*',
                    'clinic_users.*'
                )
            ));
            $patients = $this->Notification->find('first', array('conditions' => array('Notification.earn_points' => 1, 'Notification.user_id' => $rt['RateReview']['user_id'])));
            if ($rt['RateReview']['notify_staff'] == 1 && $rt['RateReview']['google_share'] == 0) {
                $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $rt['RateReview']['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => 'Google Share');
                $ratePromotion = $this->Promotion->find('first', $options);
                $transe['Transaction'] = array(
                    'user_id' => $rt['RateReview']['user_id'],
                    'card_number' => $patientclinic['clinic_users']['card_number'],
                    'first_name' => $patientclinic['User']['first_name'],
                    'last_name' => $patientclinic['User']['last_name'],
                    'promotion_id' => $ratePromotion['Promotion']['id'],
                    'activity_type' => 'N',
                    'authorization' => $ratePromotion['Promotion']['display_name'],
                    'amount' => $ratePromotion['Promotion']['value'],
                    'clinic_id' => $rt['RateReview']['clinic_id'],
                    'staff_id' => $rt['RateReview']['staff_id'],
                    'doctor_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
                );
                $this->Transaction->create();
                $this->Transaction->save($transe['Transaction']);
                $saverate['RateReview'] = array(
                    'id' => $rt['RateReview']['id'],
                    'google_share' => 1
                );
                $this->RateReview->save($saverate);
            }
            if ($rt['RateReview']['yahoo_notify'] == 1 && $rt['RateReview']['yahoo_share'] == 0) {
                $options_yahoo['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $rt['RateReview']['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => 'Yahoo Share');
                $ratePromotionyahoo = $this->Promotion->find('first', $options_yahoo);
                $transe_yahoo['Transaction'] = array(
                    'user_id' => $rt['RateReview']['user_id'],
                    'card_number' => $patientclinic['clinic_users']['card_number'],
                    'first_name' => $patientclinic['User']['first_name'],
                    'last_name' => $patientclinic['User']['last_name'],
                    'promotion_id' => $ratePromotionyahoo['Promotion']['id'],
                    'activity_type' => 'N',
                    'authorization' => $ratePromotionyahoo['Promotion']['display_name'],
                    'amount' => $ratePromotionyahoo['Promotion']['value'],
                    'clinic_id' => $rt['RateReview']['clinic_id'],
                    'staff_id' => $rt['RateReview']['staff_id'],
                    'doctor_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
                );
                $this->Transaction->create();
                $this->Transaction->save($transe_yahoo['Transaction']);
                $saverateyahoo['RateReview'] = array(
                    'id' => $rt['RateReview']['id'],
                    'yahoo_share' => 1
                );
                $this->RateReview->save($saverateyahoo);
            }
            if ($rt['RateReview']['yelp_notify'] == 1 && $rt['RateReview']['yelp_share'] == 0) {
                $options_yelp['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $rt['RateReview']['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => 'Yelp Share');
                $ratePromotionyelp = $this->Promotion->find('first', $options_yelp);
                $transe_yelp['Transaction'] = array(
                    'user_id' => $rt['RateReview']['user_id'],
                    'card_number' => $patientclinic['clinic_users']['card_number'],
                    'first_name' => $patientclinic['User']['first_name'],
                    'last_name' => $patientclinic['User']['last_name'],
                    'promotion_id' => $ratePromotionyelp['Promotion']['id'],
                    'activity_type' => 'N',
                    'authorization' => $ratePromotionyelp['Promotion']['display_name'],
                    'amount' => $ratePromotionyelp['Promotion']['value'],
                    'clinic_id' => $rt['RateReview']['clinic_id'],
                    'staff_id' => $rt['RateReview']['staff_id'],
                    'doctor_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
                );
                $this->Transaction->create();
                $this->Transaction->save($transe_yelp['Transaction']);
                $saverateyelp['RateReview'] = array(
                    'id' => $rt['RateReview']['id'],
                    'yelp_share' => 1
                );
                $this->RateReview->save($saverateyelp);
            }
            if ($rt['RateReview']['healthgrades_notify'] == 1 && $rt['RateReview']['healthgrades_share'] == 0) {
                $options_healthgrades['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $rt['RateReview']['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => 'Healthgrades Share');
                $ratePromotionhealthgrades = $this->Promotion->find('first', $options_healthgrades);
                $transe_healthgrades['Transaction'] = array(
                    'user_id' => $rt['RateReview']['user_id'],
                    'card_number' => $patientclinic['clinic_users']['card_number'],
                    'first_name' => $patientclinic['User']['first_name'],
                    'last_name' => $patientclinic['User']['last_name'],
                    'promotion_id' => $ratePromotionhealthgrades['Promotion']['id'],
                    'activity_type' => 'N',
                    'authorization' => $ratePromotionhealthgrades['Promotion']['display_name'],
                    'amount' => $ratePromotionhealthgrades['Promotion']['value'],
                    'clinic_id' => $rt['RateReview']['clinic_id'],
                    'staff_id' => $rt['RateReview']['staff_id'],
                    'doctor_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'status' => 'New',
                    'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
                );
                $this->Transaction->create();
                $this->Transaction->save($transe_healthgrades['Transaction']);
                $saveratehealthgrades['RateReview'] = array(
                    'id' => $rt['RateReview']['id'],
                    'healthgrades_share' => 1
                );
                $this->RateReview->save($saveratehealthgrades);
            }
            $total = 0;
            if (isset($ratePromotion['Promotion']['value']) && $ratePromotion['Promotion']['value'] > 0) {
                $total = $total + $ratePromotion['Promotion']['value'];
            }
            if (isset($ratePromotionyahoo['Promotion']['value']) && $ratePromotionyahoo['Promotion']['value'] > 0) {
                $total = $total + $ratePromotionyahoo['Promotion']['value'];
            }
            if (isset($ratePromotionyelp['Promotion']['value']) && $ratePromotionyelp['Promotion']['value'] > 0) {
                $total = $total + $ratePromotionyelp['Promotion']['value'];
            }
            if (isset($ratePromotionhealthgrades['Promotion']['value']) && $ratePromotionhealthgrades['Promotion']['value'] > 0) {
                $total = $total + $ratePromotionhealthgrades['Promotion']['value'];
            }

            if ($patientclinic['Clinics']['is_buzzydoc'] == 1) {
                //getting the balance amount after point allocation and update to account.
                $alltrans = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $rt['RateReview']['user_id'],
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
                    'User.id' => $rt['RateReview']['user_id']
                ));
            } else {
                //getting the balance amount after point allocation and update to account for legacy patient.
                $alltrans = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $rt['RateReview']['user_id'],
                        'Transaction.clinic_id' => $rt['RateReview']['clinic_id'],
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
                    'ClinicUser.user_id' => $rate_array['user_id'],
                    'ClinicUser.clinic_id' => $rate_array['clinic_id']
                ));
            }
            if (!empty($patients) && $total > 0) {
                //mail send for new point earned to referer
                $template_array_yahoo = $this->Api->get_template(1);
                $link_yahoo = str_replace('[username]', $patientclinic['User']['first_name'], $template_array_yahoo['content']);
                $link1_yahoo = str_replace('[click_here]', '<a href="' . rtrim($patientclinic['Clinics']['patient_url'], '/') . '">Click Here</a>', $link_yahoo);
                $link2_yahoo = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link1_yahoo);
                $link3_yahoo = str_replace('[points]', $total, $link2_yahoo);
                $Email_yahoo = new CakeEmail(MAILTYPE);
                $Email_yahoo->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                $Email_yahoo->to($patientclinic['User']['email']);
                $Email_yahoo->subject($template_array_yahoo['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email_yahoo->viewVars(array('msg' => $link3_yahoo
                ));
                $Email_yahoo->send();
            }
        }
        if (!empty($ratereview)) {
            echo "You have successfully rewarded points to patient for all social business page.";
        } else {
            echo "You have already rewarded points to patient.";
        }
        die;
    }

    public function awardpointindivisual() {
        $this->layout = '';
        $sessionstaff = $this->Session->read('staff');

        $rateRate = $this->RateReview->find('first', array(
            'conditions' => array(
                'RateReview.id' => $_POST['tid']
            )
        ));
        if ($_POST['type'] == 1) {
            $promo_name = 'Google Share';
            $type = 'Google+';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'google_share' => 1
            );
        }
        if ($_POST['type'] == 2) {
            $promo_name = 'Yahoo Share';
            $type = 'Yahoo';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'yahoo_share' => 1
            );
        }
        if ($_POST['type'] == 3) {
            $promo_name = 'Yelp Share';
            $type = 'Yelp';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'yelp_share' => 1
            );
        }
        if ($_POST['type'] == 4) {
            $promo_name = 'Healthgrades Share';
            $type = 'Healthgrades';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'healthgrades_share' => 1
            );
        }
        $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $rateRate['RateReview']['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => $promo_name);
        $ratePromotion = $this->Promotion->find('first', $options);
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
                'User.id' => $rateRate['RateReview']['user_id'],
                'Clinics.id' => $rateRate['RateReview']['clinic_id']
            ),
            'fields' => array(
                'User.*',
                'Clinics.*',
                'clinic_users.*'
            )
        ));
        $transe['Transaction'] = array(
            'user_id' => $rateRate['RateReview']['user_id'],
            'card_number' => $patientclinic['clinic_users']['card_number'],
            'first_name' => $patientclinic['User']['first_name'],
            'last_name' => $patientclinic['User']['last_name'],
            'promotion_id' => $ratePromotion['Promotion']['id'],
            'activity_type' => 'N',
            'authorization' => $ratePromotion['Promotion']['display_name'],
            'amount' => $ratePromotion['Promotion']['value'],
            'clinic_id' => $rateRate['RateReview']['clinic_id'],
            'staff_id' => $rateRate['RateReview']['staff_id'],
            'doctor_id' => 0,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'New',
            'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
        );
        $this->Transaction->create();
        $this->Transaction->save($transe['Transaction']);
        $this->RateReview->save($saverate);
        $patients = $this->Notification->find('first', array('conditions' => array('Notification.earn_points' => 1, 'Notification.user_id' => $rateRate['RateReview']['user_id'])));
        if (!empty($patients) && $ratePromotion['Promotion']['value'] > 0) {
            //mail send for new point earned to referer
            $template_array = $this->Api->get_template(1);
            $link = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
            $link1 = str_replace('[click_here]', '<a href="' . rtrim($patientclinic['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
            $link2 = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link1);
            $link3 = str_replace('[points]', $ratePromotion['Promotion']['value'], $link2);
            $Email = new CakeEmail(MAILTYPE);
            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
            $Email->to($patientclinic['User']['email']);
            $Email->subject($template_array['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email->viewVars(array('msg' => $link3
            ));
            $Email->send();
        }
        if ($patientclinic['Clinics']['is_buzzydoc'] == 1) {
            //getting the balance amount after point allocation and update to account.
            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $rateRate['RateReview']['user_id'],
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
                'User.id' => $rateRate['RateReview']['user_id']
            ));
        } else {
            //getting the balance amount after point allocation and update to account for legacy patient.
            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $rateRate['RateReview']['user_id'],
                    'Transaction.clinic_id' => $rateRate['RateReview']['clinic_id'],
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
                'ClinicUser.user_id' => $rate_array['user_id'],
                'ClinicUser.clinic_id' => $rate_array['clinic_id']
            ));
        }
        echo "You have successfully rewarded " . $ratePromotion['Promotion']['value'] . ' points to patient for ' . $type . ' review.';
        die;
    }

}

?>
