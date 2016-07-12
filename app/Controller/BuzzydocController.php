<?php

/**
 * This file is for buzzydoc site where we can signin,signup for any pratice.
 * Getting the details of Patient.
 * Patient acn serach all doctor and pratice.
 * Patient update there profile and send referal message to other friends.
 * Shcedule appoint for any pratice.
 * Like,save, rate and review the doctor or pratice.
 * Place order for amazon and tango.
 * Redeem product and service from pratice page.
 */
App::uses('AppController', 'Controller');
App::import('Vendor', 'facebook/facebook');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller is for buzzydoc site where we can signin,signup for any pratice.
 * Getting the details of Patient.
 * Patient acn serach all doctor and pratice.
 * Patient update there profile and send referal message to other friends.
 * Shcedule appoint for any pratice.
 * Like,save, rate and review the doctor or pratice.
 * Place order for amazon and tango.
 * Redeem product and service from pratice page.
 */
class BuzzydocController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session, api AND Security component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'Security');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'Reward', 'Staff', 'User', 'ClinicUser', 'ProfileFieldUser', 'Category', 'CardNumber', 'ProfileField', 'Document', 'Doctor', 'ClinicLocation', 'DoctorLocation', 'RateReview', 'IndustryType', 'Clinic', 'State', 'City', 'WishList', 'Transaction', 'Notification', 'Refer', 'UnregTransaction', 'ContestClinic', 'ClinicPromotion', 'Refpromotion', 'LeadLevel', 'IndustryType', 'CharacteristicInsurance', 'Badge', 'FacebookLike', 'PaymentDetail', 'UsersBadge', 'Badge', 'AdminSetting', 'GlobalRedeem', 'Invoice', 'ProductService', 'LevelupPromotion',
        'UserPerfectVisit',
        'UserAssignedTreatment',
        'UpperLevelSetting',
        'PhaseDistribution', 'StockImage', 'AccessStaff');

    /**
     * Its uses for through out buzzydoc project.
     * Security check for login where any blackhole.
     * Condition for mobile site or desktop.
     * Get the top pratice by most point given.
     * @return type
     */
    public function beforeFilter() {
        $this->Security->validatePost = false;
        $this->Security->unlockedActions = array('checkuser', 'forgotpassword', 'doctor', 'getdoctor', 'getcity', 'getcheckage', 'practice', 'thebuzz', 'map', 'termcondition', 'facebooklogin', 'searchpractice', 'searchdoc', 'morebuzz', 'getmsg', 'referpreview', 'redeemlocproduct', 'characteristiclike', 'savedoctor', 'unsavedoctor', 'editprofile', 'likeclinic', 'unlikeclinic', 'rate', 'recommend', 'appointment', 'searchresult', 'getdoctorviapincode', 'searchdoc', 'getcitylist', 'facebookpointallocation', 'placeorder', 'getUserProductServices', 'facebookpointallocationnew', 'getajaxuserpromotions', '_getUserPromotions', '_getUserClinics', 'resendrefer', 'sendReferral', 'setNotification', 'getmultilogin', 'getClinicLead', 'settings', 'saveuserprofileimage', 'getUserPromotion', 'getPointsDetails', 'buzzysignup', 'getSearchPractice', 'getPractice', 'checkuserexist', 'selectpractice', 'signup', 'getQuestionCard', 'ratereview', 'postRateReview', 'addTransactionForRateReview', 'clinicratereview', 'notifyStaff','notifyPatient', 'getcitystate');
        $this->Security->blackHoleCallback = 'blackhole';
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        //condition for mobile site or desktop.
        if ($iphone || $android || $palmpre || $ipod || $berry == true) {
            $this->Session->write('buzzy.is_mobile', 1);
            $this->set("mobile_device", 1);
        } else {
            $this->Session->write('buzzy.is_mobile', 0);
            $this->set("mobile_device", 0);
        }
        $sessionbuzzy = $this->Session->read('userdetail');

        if (empty($sessionbuzzy) &&
                $this->params['action'] != 'login' &&
                $this->params['action'] != 'buzzysignin' &&
                $this->params['action'] != 'buzzysignup' &&
                $this->params['action'] != 'getdoctor' &&
                $this->params['action'] != 'facebooklogin' &&
                $this->params['action'] != 'getcity' &&
                $this->params['action'] != 'getcheckage' &&
                $this->params['action'] != 'doctor' &&
                $this->params['action'] != 'practice' &&
                $this->params['action'] != 'thebuzz' &&
                $this->params['action'] != 'map' &&
                $this->params['action'] != 'termcondition' &&
                $this->params['action'] != 'signup' &&
                $this->params['action'] != 'mlogin' &&
                $this->params['action'] != 'mforgot' &&
                $this->params['action'] != 'searchpractice' &&
                $this->params['action'] != 'searchdoc' &&
                $this->params['action'] != 'morebuzz' &&
                $this->params['action'] != 'forgotpassword' &&
                $this->params['action'] != 'checkuser' &&
                $this->params['action'] != 'getmsg' &&
                $this->params['action'] != 'referpreview' &&
                $this->params['action'] != 'getSearchPractice' &&
                $this->params['action'] != 'getPractice' &&
                $this->params['action'] != 'checkuserexist' &&
                $this->params['action'] != 'selectpractice' &&
                $this->params['action'] != 'getQuestionCard' &&
                $this->params['action'] != 'ratereview' &&
                $this->params['action'] != 'postRateReview' &&
                $this->params['action'] != 'addTransactionForRateReview' &&
                $this->params['action'] != 'clinicratereview' &&
                $this->params['action'] != 'notifyStaff' &&
                $this->params['action'] != 'notifyPatient' &&
                $this->params['action'] != 'getcitystate'
        ) {
            return $this->redirect('/login');
        } else
        if (!empty($sessionbuzzy)) {
            $pincode = '';
            foreach ($sessionbuzzy->Profilefield as $profile) {
                if ($profile->ProfileField->profile_field == 'postal_code') {
                    $pincode = $profile->ProfileFieldUser->value;
                }
            }

            $data = array(
                'pincode' => $pincode
            );
            //getting the top clinic by most point given.
            $clinic = $this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/practicebymostpoint.json');

            $topclinic = json_decode($clinic);
            if (!empty($topclinic)) {
                if ($topclinic->topclinic->success == 1) {
                    $this->set('topclinic', $topclinic->topclinic->data);
                } else {
                    $this->set('topclinic', array());
                }
            } else {
                $this->set('topclinic', array());
            }
        }
    }

    /**
     * Action to show term condition page.
     */
    public function termcondition() {
        $this->layout = "buzzydocinner";
    }

    /**
     * checking the any blackhole while login.
     * @param type $type
     * @return type
     */
    public function blackhole($type) {
        $this->log('Request has been blackholed: ' . $type, 'tests');
        echo 'expire';
        die();
    }

    /**
     * facebook login and signup from buzzyodc site.
     * @return type
     */
    public function facebooklogin() {
        $facebook = new Facebook(array(
            'appId' => Facebook_APP_ID,
            'secret' => Facebook_Secret_Key
        ));
        $user = $facebook->getUser();
        //validate facebook user.
        if ($user) {
            try {
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }

        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
        } else {
            $loginUrl = $facebook->getLoginUrl(array(
                'scope' => 'email,user_birthday'
            ));
            return $this->redirect($loginUrl);
        }
        if (isset($user_profile['username'])) {
            
        } else {
            $user_profile['username'] = '';
        }
        if (isset($user_profile['birthday'])) {
            $dob = date('Y-m-d', strtotime($user_profile['birthday']));
        } else {
            $dob = '0000-00-00';
        }
        //condition to check facebook user already in our system.
        $Patients1 = $this->User->find('first', array(
            'conditions' => array(
                'User.email' => $user_profile['email'],
                'User.is_facebook' => 1
            ),
            'fields' => array(
                'User.*'
            )
        ));
        //if exist then apply for login.
        if (!empty($Patients1)) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $Patients1['User']['id'] . '.json'));

            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
                return $this->redirect('/dashboard/');
            } else {
                $this->Session->delete('userdetail');
                $this->Session->setFlash(__('Email already exists. Use different email id.'));
                return $this->redirect('/login/');
            }
        } else {
            //if not exist then apply for signup.
            $data = array(
                'first_name' => $user_profile['first_name'],
                'last_name' => $user_profile['last_name'],
                'email' => $user_profile['email'],
                'customer_username' => $user_profile['username'],
                'custom_date' => $dob,
                'gender' => $user_profile['gender'],
                'facebook_id' => $user_profile['id'],
                'is_facebook' => 1,
                'is_verified' => 1,
                'status' => 1
            );
            //condition to check patient is adult.
            $date13age = date("Y-m-d", strtotime("-13 year"));
            $Patients_ch = $this->User->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'User.email' => $data['email'],
                        'User.parents_email' => $data['email']
                    )
                ),
                'fields' => array(
                    'User.*'
                )
            ));

            if (!empty($Patients_ch)) {
                $ag_checked = 0;
                foreach ($Patients_ch as $pt) {

                    $date1_chd1 = $pt['User']['custom_date'];
                    $date1_chd1 = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd1)));
                    $date2_chd1 = date('Y-m-d');
                    $diff_chd = abs(strtotime($date2_chd1) - strtotime($date1_chd1));
                    $years_chd1 = floor($diff_chd / (365 * 60 * 60 * 24));
                    if ($years_chd1 > 18) {
                        $ag_checked = 1;
                    }
                }
            }
            //condition to check adult or child Patient exist in our system.
            if (isset($ag_checked) && !empty($Patients_ch) && $ag_checked == 1) {

                $date1_chd = $data['custom_date'];

                $date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
                $date2_chd = date('Y-m-d');
                $diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
                $years_chd = floor($diff_chd / (365 * 60 * 60 * 24));
                if ($years_chd < 18) {

                    $Patients_ch1 = array();

                    $Patients_ch1 = $this->User->find('first', array(
                        'conditions' => array(
                            'User.parents_email' => $data['email']
                        )
                    ));
                    if (!empty($Patients_ch1)) {
                        $child_exist = 1;
                    }
                    if (!empty($Patients_ch3)) {
                        $parent_exist = 1;
                    }
                }
            }

            if (!empty($Patients_ch) && $ag_checked == 0) {
                $Patients_ch1 = $this->User->find('first', array(
                    'conditions' => array(
                        'User.parents_email' => $data['email']
                    )
                ));

                if (!empty($Patients_ch1)) {
                    $parent_exist = 1;
                }
            }

            if (isset($years_chd) && $years_chd > 18) {
                $this->Session->setFlash(__('Email already exists. Use different email id.'));
                return $this->redirect('/login/');
            } else
            if (isset($parent_exist) && $parent_exist == 1) {
                $this->Session->setFlash(__('Email already exists. Use different email id.'));
                return $this->redirect('/login/');
            } else
            if (isset($child_exist) && $child_exist == 1) {
                $this->Session->setFlash(__('Username already exists. Use diffrent Username.'));
                return $this->redirect('/login/');
            } else {
                //if not exist then store in our system.
                $new_password = dechex(time()) . mt_rand(0, 100000);
                $fb_patient_data = array(
                    'custom_date' => $data['custom_date'],
                    'email' => strtolower($data['email']),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'password' => $new_password,
                    'points' => 0,
                    'enrollment_stamp' => date('Y-m-d H:i:s'),
                    'facebook_id' => $data['facebook_id'],
                    'is_facebook' => 1,
                    'status' => 1,
                    'is_verified' => 1,
                    'is_buzzydoc' => 1
                );
                $this->Session->write('fbuserdetail', $fb_patient_data);
                $sessionfbuser = $this->Session->read('buzzy');
                if ($sessionfbuser['is_mobile'] == 1) {
                    return $this->redirect('/buzzydoc/selectpractice/');
                } else {
                    return $this->redirect('/login/');
                }
            }
        }
    }

    /**
     * Its default login page for buzzydoc where we send the verify child account email send.
     * @return type
     */
    public function login() {
        $userId = base64_decode($this->params['id']);
        if (isset($this->request->pass[0]) && $this->request->pass[0] != 'Unsubscribe') {
            $Patients_get = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => base64_decode($this->request->pass[0])
                ),
                'fields' => array(
                    'User.*'
                )
            ));
            //condition to check request for verify child account.
            if (!empty($Patients_get) && ($Patients_get['User']['is_verified'] == '' || $Patients_get['User']['is_verified'] == 0)) {
                $template_array = $this->Api->get_template(4);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(
                    SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                ));
                $Email->to($Patients_get['User']['email']);
                $sub = str_replace('[clinic_name]', '', $template_array['subject']);
                $Email->subject($sub)
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $link = str_replace('[first_name]', $Patients_get['User']['first_name'], $template_array['content']);
                $link1 = str_replace('[username]', $Patients_get['User']['parents_email'], $link);
                $link2 = str_replace('[password]', $Patients_get['User']['password'], $link1);
                $link3 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link2);

                $Email->viewVars(array(
                    'msg' => $link3
                ));
                $Email->send();
                $sessionpatient = $this->Session->read('patient');
                $Patients_array['User'] = array(
                    'id' => $Patients_get['User']['id'],
                    'status' => 1,
                    'is_verified' => 1
                );
                $this->User->save($Patients_array);
                $this->Session->setFlash(__('Confirmation successful'));
            }
        }
        //condition for direct login from super admin and reward site.
        if (isset($userId) && $userId != '') {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $userId . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
                if (isset($this->request->pass[0]) && $this->request->pass[0] == 'Unsubscribe') {
                    return $this->redirect('/settings/');
                } else {
                    return $this->redirect('/dashboard/');
                }
            } else {
                $this->Session->delete('userdetail');
            }
        } else {
            $this->Session->delete('userdetail');
        }
        $this->layout = "buzzydoclanding";
        //getting the list of top 3 doctor for showing at landing page.
        $doctor = $this->Api->submit_cURL_Get(Buzzy_Name . '/api/gettop3doctor.json');

        $doctors = json_decode($doctor);
        if ($doctors->top3doc->success == 1) {

            $this->set('Doctors', $doctors->top3doc->data);
        } else {
            $this->set('Doctors', array());
        }

        $states = $this->State->find('all');

        $this->set('states', $states);
        //getting the list of all industry type.

        $industryType = $this->Clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = Clinic.id'
                    )
                ),
                array(
                    'table' => 'industry_types',
                    'alias' => 'IndustryType',
                    'type' => 'INNER',
                    'conditions' => array(
                        'IndustryType.id = Clinic.industry_type'
                    )
                )
            ),
            'conditions' => array(
                'Clinic.status' => 1,
                'AccessStaff.self_registration' => 1
            ),
            'group' => array('Clinic.industry_type'),
            'order' => array('IndustryType.name asc'),
            'fields' => array('IndustryType.*')
        ));
        $this->set('industryType', $industryType);
    }

    /**
     * Its main page after the login of patient.
     * We fetch the all details of patient to display at dashboard.
     * We getting the patient profile details,points earn from all clinics.
     * Treatment plan patient used with progress tracker.
     * How many doctor saved,clinic likes etc.
     * Getting the all stock images.
     * @return type
     */
    public function dashboard() {
        //getting the list of all stock images which is use for profile image update.
        $stockImages = $this->StockImage->find('all');
        if ($stockImages) {
            $stockImages = array_column($stockImages, 'StockImage');
        }
        $this->layout = "buzzydocUserLayout";
        $sessionbuzzy1 = $this->Session->read('userdetail');
        $optionsCli['conditions'] = array('ClinicUser.user_id' => $sessionbuzzy1->User->id);
        $UsersClinic = $this->ClinicUser->find('all', $optionsCli);
        $userallclinic = array();
        //getting the all clinics list belong to patient.
        foreach ($UsersClinic as $uclinic) {
            $userallclinic[] = $uclinic['ClinicUser']['clinic_id'];
        }
        $this->Session->write('usercheck.usersClinic', $userallclinic);
        $cliniclist = array();
        $buzzycliniclist = array();
        $i = 0;
        $ref = 0;
        $getredeemaccess == 0;
        foreach ($UsersClinic as $clnic) {
            $optionscl['conditions'] = array('Clinic.id' => $clnic['ClinicUser']['clinic_id']);
            $Clinic = $this->Clinic->find('first', $optionscl);

            $getredeemacc = $this->AccessStaff->getAccessForClinic($clnic['ClinicUser']['clinic_id']);
            if ($getredeemacc['AccessStaff']['amazon_redemption'] == 1) {
                $getredeemaccess++;
            }
            if ($getredeemacc['AccessStaff']['refer'] == 1) {
                $cliniclist[$ref]['clinic_id'] = $Clinic['Clinic']['id'];
                if ($Clinic['Clinic']['display_name'] == '') {
                    $cliniclist[$ref]['clinic_name'] = $Clinic['Clinic']['display_name'];
                } else {
                    $cliniclist[$ref]['clinic_name'] = $Clinic['Clinic']['api_user'];
                }
                $ref++;
            }
            if ($Clinic['Clinic']['is_buzzydoc'] == 1) {
                $buzzycliniclist[$i]['clinic_id'] = $Clinic['Clinic']['id'];
            }
            $i++;
        }

        $this->set('AccessRedeem', $getredeemaccess);
        $this->set('ClinicList', $cliniclist);
        $this->set('BuzzyClinicList', $buzzycliniclist);
        //getting the clinic's lead level's and industry type.
        foreach ($UsersClinic as $clnic) {
            $optionscl['conditions'] = array('Clinic.id' => $clnic['ClinicUser']['clinic_id']);
            $Clinic = $this->Clinic->find('first', $optionscl);
            $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $Clinic['Clinic']['industry_type'])));
            $leads = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $Clinic['Clinic']['industry_type'])));

            $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
            if ($ref_msg == '') {
                $rmsg = array();
            } else {
                $rmsg = $ref_msg;
            }
            $this->set('refer_msg', $rmsg);
            $this->set('leads', $leads);
            $this->set('defaultclinic', $clnic['ClinicUser']['clinic_id']);
            $this->set('industry_id', $ind['IndustryType']['id']);
            $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $Clinic['Clinic']['id'], 'AdminSetting.setting_type' => 'leadpoints')));
            $this->set('admin_settings', $admin_set);
            break;
        }


        $usersbg = $this->Transaction->query('SELECT sum(Transaction.amount) as share FROM `transactions` AS `Transaction` where Transaction.activity_type="N" and Transaction.user_id=' . $sessionbuzzy1->User->id);
        if ($sessionbuzzy1->User->id) {
            //getting the product and service of all clinic belong to patient.
            $this->Session->write('userClinics', $this->getUserProductServices($sessionbuzzy1->User->id));
        }
        //assign the badge according to points have patient.
        foreach ($usersbg as $ug) {
            if ($ug[0]['share'] > 0) {
                $optionsbadge['conditions'] = array(
                    'Badge.value <=' => $ug[0]['share'],
                    'Badge.clinic_id' => 0
                );
                $Badge = $this->Badge->find('all', $optionsbadge);
                foreach ($Badge as $bg) {
                    $optionsbadgeuser['conditions'] = array(
                        'UsersBadge.user_id' => $sessionbuzzy1->User->id,
                        'UsersBadge.badge_id' => $bg['Badge']['id']
                    );
                    $Badgeuser = $this->UsersBadge->find('first', $optionsbadgeuser);
                    if (empty($Badgeuser)) {
                        $savebadge['UsersBadge'] = array(
                            'user_id' => $sessionbuzzy1->User->id,
                            'badge_id' => $bg['Badge']['id'],
                            'created_on' => date('Y-m-d H:i:s')
                        );
                        $this->UsersBadge->create();
                        $this->UsersBadge->save($savebadge);
                    }
                }
            }
        }
        //getting the userdetail from api.
        $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $sessionbuzzy1->User->id . '.json'));
        if ($userdetails->userdetail->success == 1) {
            $this->Session->write('userdetail', $userdetails->userdetail->data);
        } else {
            return $this->redirect('/login/');
        }
        $sessionbuzzy = $this->Session->read('userdetail');
        //creating the array of patient detail for display at dashboard site.
        $dashboardData = array();
        $dashboardData['userDetail']['id'] = $sessionbuzzy->User->id;
        $dashboardData['userDetail']['name'] = ucwords($sessionbuzzy->User->first_name . ' ' . $sessionbuzzy->User->last_name);
        $dashboardData['userDetail']['fName'] = ucwords($sessionbuzzy->User->first_name);
        $dashboardData['userDetail']['lName'] = ucwords($sessionbuzzy->User->last_name);
        $dashboardData['userDetail']['email'] = $sessionbuzzy->User->email;
        $dashboardData['userDetail']['password'] = $sessionbuzzy->User->password;
        $dashboardData['userDetail']['profileImage'] = $sessionbuzzy->profileimage;
        $dashboardData['userDetail']['dob'] = ($sessionbuzzy->User->custom_date != '0000-00-00') ? $sessionbuzzy->User->custom_date : '';
        $dashboardData['userDetail']['age'] = '';
        $dashboardData['userDetail']['globalPoints'] = $sessionbuzzy->User->points;
        $dashboardData['userDetail']['parentsEmail'] = $sessionbuzzy->User->parents_email;
        $dashboardData['userDetail']['profile_img_url'] = $sessionbuzzy->User->profile_img_url;
        $dashboardData['userDetail']['stock_images'] = $stockImages;
        if ($dashboardData['userDetail']['dob'] && $dashboardData['userDetail']['dob'] != '----') {
            $from = new DateTime($dashboardData['userDetail']['dob']);
            $to = new DateTime('today');
            $dashboardData['userDetail']['age'] = $from->diff($to)->y;
        }
        $dashboardData['userDetail']['totalPointsShort'] = number_format($sessionbuzzy->totalpoints);
        $dashboardData['userDetail']['totalPoints'] = $sessionbuzzy->totalpoints;
        $dashboardData['userDetail']['netLocalPoints'] = $sessionbuzzy->totalpoints - $sessionbuzzy->User->points;
        $dashboardData['userDetail']['totalSaved'] = $sessionbuzzy->Save;
        $dashboardData['userDetail']['totalLikes'] = $sessionbuzzy->Like;
        $dashboardData['userDetail']['totalBadges'] = count($sessionbuzzy->Badge);
        $dashboardData['userDetail']['totalCheckIns'] = $sessionbuzzy->Checkin;
        $dashboardData['userDetail']['contactNumber'] = '';
        $dashboardData['userDetail']['address1'] = '';
        $dashboardData['userDetail']['address2'] = '';
        $dashboardData['userDetail']['city'] = '';
        $dashboardData['userDetail']['state'] = '';
        $dashboardData['userDetail']['zip'] = '';
        $dashboardData['userDetail']['gender'] = '';
        $dashboardData['userDetail']['ren'] = date('Y-m-d H:i:s');
        foreach ($sessionbuzzy->Profilefield as $userData) {
            if ($userData->ProfileField->profile_field == 'phone') {
                $dashboardData['userDetail']['contactNumber'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'street1') {
                $dashboardData['userDetail']['address1'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'street2') {
                $dashboardData['userDetail']['address2'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'state') {
                $dashboardData['userDetail']['state'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'city') {
                $dashboardData['userDetail']['city'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'postal_code') {
                $dashboardData['userDetail']['zip'] = $userData->ProfileFieldUser->value;
            } else
            if ($userData->ProfileField->profile_field == 'gender') {
                $dashboardData['userDetail']['gender'] = $userData->ProfileFieldUser->value;
            }
        }

        if (isset($sessionbuzzy->Badge)) {
            foreach ($sessionbuzzy->Badge as $key => $badges) {
                $dashboardData['badges'][$key] = $badges->UsersBadge->badge_id;
            }
        } else {
            $dashboardData['badges'] = '';
        }

        $optionsid['fields'] = array(
            'Badge.*'
        );
        //getting the list of badge for patient.
        $indData = $this->Badge->find('all', $optionsid);
        $dashboardData['systembadges'] = $indData;
        $state = $this->State->find('all');
        $stlist = '<select id="state" name="state" style="width:180px;"><option value="">Select State [*]</option>';
        foreach ($state as $st) {
            $stlist .= '<option value="' . $st['State']['state'] . '" ';
            if ($st['State']['state'] == $dashboardData['userDetail']['state']) {
                $stlist .= 'selected="selected"';
            }
            $stlist .= '>' . $st['State']['state'] . '</option>';
        }
        $stlist.='</select>';
        $this->set('statelist', $stlist);
        $options['joins'] = array(
            array(
                'table' => 'states',
                'alias' => 'State',
                'type' => 'INNER',
                'conditions' => array(
                    'State.state_code = City.state_code'
                )
            )
        );
        $options['conditions'] = array(
            'State.state' => $dashboardData['userDetail']['state']
        );
        $options['fields'] = array(
            'City.city'
        );
        $options['order'] = array(
            'City.city asc'
        );
        $cityresult = $this->City->find('all', $options);
        $html = '<select id="citydd" style="width:180px;" name="cityDd"><option value="">Select City</option>';
        foreach ($cityresult as $ct) {

            $html .= '<option value="';
            $html .= $ct["City"]["city"];
            $html .= '"';
            if ($dashboardData['userDetail']['city'] == $ct['City']['city']) {
                $html .= ' selected="selected"';
            }
            $html .='>';
            $html .= $ct["City"]["city"];
            $html .= '</option>';
        }
        $html .= '</select>';
        $this->set('citylist', $html);
        //getting the data for progress tracker.
        $visithistory1 = $this->UserPerfectVisit->find('all', array(
            'joins' => array(
                array(
                    'table' => 'upper_level_settings',
                    'alias' => 'UpperLevelSetting',
                    'type' => 'INNER',
                    'conditions' => array(
                        'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                    )
                ),
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinic',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinic.id = UpperLevelSetting.clinic_id'
                    )
                )
                ,
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = UpperLevelSetting.clinic_id'
                    )
                )
            ),
            'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*', 'Clinic.id', 'Clinic.api_user', 'Clinic.display_name'),
            'conditions' => array(
                'UserPerfectVisit.user_id' => $sessionbuzzy->User->id, 'AccessStaff.levelup' => 1, 'UpperLevelSetting.interval' => 0),
            'order' => array('UserPerfectVisit.date desc')
        ));
        if (empty($visithistory1)) {
            $visithistory1 = $this->UserPerfectVisit->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'upper_level_settings',
                        'alias' => 'UpperLevelSetting',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UpperLevelSetting.id = UserPerfectVisit.level_up_settings_id'
                        )
                    ),
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinic',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinic.id = UpperLevelSetting.clinic_id'
                        )
                    )
                    ,
                    array(
                        'table' => 'access_staffs',
                        'alias' => 'AccessStaff',
                        'type' => 'INNER',
                        'conditions' => array(
                            'AccessStaff.clinic_id = UpperLevelSetting.clinic_id'
                        )
                    )
                ),
                'fields' => array('UserPerfectVisit.*', 'UpperLevelSetting.*', 'Clinic.id', 'Clinic.api_user', 'Clinic.display_name'),
                'conditions' => array(
                    'UserPerfectVisit.user_id' => $sessionbuzzy->User->id, 'AccessStaff.interval' => 1, 'UpperLevelSetting.interval' => 1),
                'order' => array('UserPerfectVisit.date desc')
            ));
        }
        $visithistory = array();

        $v = 0;
        foreach ($visithistory1 as $visit) {
            $visitover = $this->UserAssignedTreatment->find('first', array(
                'conditions' => array(
                    'UserAssignedTreatment.treatment_id' => $visit['UpperLevelSetting']['id'], 'UserAssignedTreatment.user_id' => $sessionbuzzy->User->id)
            ));
            if (empty($visitover)) {
                $visithistory[$v] = $visit;
                $visithistory[$v]['Phase_distribution'] = $this->PhaseDistribution->find('all', array(
                    'conditions' => array(
                        'PhaseDistribution.upper_level_settings_id' => $visit['UpperLevelSetting']['id'])
                ));
                $v++;
            }
        }

        $levelhistory = array();
        foreach ($visithistory as $history) {
            if ($history['UserPerfectVisit']['is_perfect'] == 1)
                $perfect = 'Perfect';
            else
                $perfect = 'Not Perfect';
            if ($history['UserPerfectVisit']['is_treatment_over'] == 1)
                $treatment_over1 = 'End of Treatment';
            else
                $treatment_over1 = 'In Treatment';
            if ($history['UserPerfectVisit']['level_achieved'] == 0)
                $level = '--';
            else
                $level = $history['UserPerfectVisit']['level_achieved'];
            $levelhistory[$history['UpperLevelSetting']['treatment_name']]['record'][] = array('perfect' => $perfect, 'level_status' => $history['UserPerfectVisit']['level_achieved'], 'date' => $history['UserPerfectVisit']['date'], 'status' => $treatment_over1);
            $levelhistory[$history['UpperLevelSetting']['treatment_name']]['clinic_id'] = $history['Clinic']['id'];
            if ($history['Clinic']['display_name'] == '') {
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['clinic_name'] = $history['Clinic']['api_user'];
            } else {
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['clinic_name'] = $history['Clinic']['display_name'];
            }
            $levelhistory[$history['UpperLevelSetting']['treatment_name']]['treatment_details'] = $history['UpperLevelSetting'];
            $levelhistory[$history['UpperLevelSetting']['treatment_name']]['treatment_details']['phase_distribution'] = $history['Phase_distribution'];
            if ($history['UpperLevelSetting']['interval'] == 1) {
                $getintervaldetails = $this->getIntervalDetails($history['UpperLevelSetting']['id'], $sessionbuzzy->User->id, $history['Clinic']['id']);
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['interval_details'] = $getintervaldetails;
            } else {
                $levelhistory[$history['UpperLevelSetting']['treatment_name']]['interval_details'] = array();
            }
        }
        //echo "<pre>";print_r($levelhistory);die;
        $tangocard = new Sourcefuse\TangoCard(PLATFORM_ID, PLATFORM_KEY);
        $tangocard->setAppMode(TANGO_MODE);

        $response = $tangocard->listRewards();
        if ($response->success == 1) {

            $this->set('tangorewards', $response->brands);
        }
        $this->set('visithistory', $levelhistory);

        $this->set('userDashboard', $dashboardData);
        $this->set('perclinicbuzzpnt', $this->getPointsDetails($sessionbuzzy1));
        $this->set('userRegisteredClinics', self::_getUserClinics($sessionbuzzy1->User->id));
        $this->set('userClinicPromotions', $this->getUserPromotion($sessionbuzzy1->User->id));
        $optionsfb['conditions'] = array(
            'Clinic.is_buzzydoc' => 1,
            'Clinic.minimum_deposit >' => 0
        );
        $optionsfb['fields'] = array(
            'Clinic.id',
            'Clinic.api_user',
            'Clinic.display_name',
            'Clinic.facebook_url',
            'Clinic.fb_app_id',
            'Clinic.fb_app_key'
        );
        $clinicsFB = $this->Clinic->find('all', $optionsfb);
        $fbavailclinic = array();
        //getting the list of clinic where patient not like the facebook page.
        foreach ($clinicsFB as $clinicfb) {
            $paydet['conditions'] = array(
                'PaymentDetail.clinic_id' => $clinicfb['Clinic']['id']
            );
            $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
            $ernpst['conditions'] = array(
                'Transaction.clinic_id' => $clinicfb['Clinic']['id'],
                'Transaction.user_id' => $sessionbuzzy->User->id
            );
            $earninpast = $this->Transaction->find('first', $ernpst);
            if (!empty($getpayemntdetails) && !empty($earninpast) && $getpayemntdetails['PaymentDetail']['customer_account_profile_id'] > 0) {
                $checkfb['conditions'] = array(
                    'FacebookLike.clinic_id' => $clinicfb['Clinic']['id'],
                    'FacebookLike.user_id' => $sessionbuzzy->User->id
                );
                $checkfblike = $this->FacebookLike->find('first', $checkfb);
                if ($checkfblike['FacebookLike']['like_status'] != 1) {
                    $fbavailclinic[] = $clinicfb;
                }
            }
        }
        $this->set('fbAvailableClinic', $fbavailclinic);

        $this->render('/Buzzydoc/userDashboard');
    }

    /**
     * @depricated
     */
    public function practice1() {
        $this->layout = "buzzydocinner";
    }

    /**
     * Getting the list off doctor and details of all.
     * @return type
     */
    public function doctor() {
        $this->layout = "buzzydocinner";
        $docId = $this->params['id'];
        //checking the doctor exist or not.
        if (isset($this->params['pass'][0]) && !isset($this->params['pass'][1])) {
            $options3['conditions'] = array(
                'CONCAT(Doctor.first_name, " ", Doctor.last_name) LIKE' => '%' . $docId . '%',
                'Doctor.specialty' => $this->params['pass'][0]
            );
            $doctorlists = $this->Doctor->find('first', $options3);
            $docId1 = $doctorlists['Doctor']['id'];
        }
        if (isset($this->params['pass'][0]) && isset($this->params['pass'][1])) {
            $options3['conditions'] = array(
                'CONCAT(Doctor.first_name, " ", Doctor.last_name) LIKE' => '%' . $docId . '%',
                'Doctor.specialty' => $this->params['pass'][0] . '/' . $this->params['pass'][1]
            );
            $doctorlists = $this->Doctor->find('first', $options3);
            $docId1 = $doctorlists['Doctor']['id'];
        }
        //condition to get all details of doctor.
        if (isset($docId1) && $docId1 != '') {

            $doctordetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/doctor/' . $docId1 . '.json'));
            if ($doctordetails->Doctors->success == 1) {
                $options3cl['conditions'] = array('Clinic.id' => $doctorlists['Doctor']['clinic_id']);
                $cldetail = $this->Clinic->find('first', $options3cl);
                $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $cldetail['Clinic']['industry_type'])));
                $leads = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $cldetail['Clinic']['industry_type'])));

                $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
                if ($ref_msg == '') {
                    $rmsg = array();
                } else {
                    $rmsg = $ref_msg;
                }
                $this->set('refer_msg', $rmsg);
                $this->set('industry_id', $ind['IndustryType']['id']);
                $this->set('leads', $leads);
                $admin_set = $this->AdminSetting->find('first', array(
                    'conditions' => array(
                        'AdminSetting.clinic_id' => $cldetail['Clinic']['id'],
                        'AdminSetting.setting_type' => 'leadpoints'
                    )
                ));
                $this->set('admin_settings', $admin_set);
                $this->set('indty', $cldetail['Clinic']['industry_type']);

                $this->set('Doctors', $doctordetails->Doctors->data);
            } else {
                return $this->redirect('/doctor/');
            }
        } else {
            //getting the list of all doctor.
            $this->set('limit', 10);
            $this->set('offset', 0);
            $options1['fields'] = array(
                'IndustryType.id,IndustryType.name'
            );
            $industry = $this->IndustryType->find('all', $options1);
            $this->set('industry', $industry);

            $data = array(
                'limit' => 10,
                'offset' => 0
            );
            $doctorlist = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/doctorlist.json'));

            if ($doctorlist->doctorslist->success == 1) {
                $this->set('Doctorslist', $doctorlist->doctorslist->data);
                $this->render('/Buzzydoc/doctorlist');
            }
        }
    }

    public function getIntervalDetails($treatment_id, $user_id, $clinic_id) {
        if ($treatment_id) {
            $options['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $clinic_id,
                'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                'UserPerfectVisit.user_id' => $user_id,
                'UserPerfectVisit.level_achieved like' => '%Phase%'
            );
            $options['order'] = array('UserPerfectVisit.date desc');
            $data = $this->UserPerfectVisit->find('first', $options);
            if (!empty($data)) {
                $phaseval = explode(' ', $data['UserPerfectVisit']['level_achieved']);
                $nextphase = $phaseval[1] + 1;

                $optionsget['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $clinic_id,
                    'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                    'UserPerfectVisit.user_id' => $user_id,
                    'UserPerfectVisit.date >' => $data['UserPerfectVisit']['date'],
                    'UserPerfectVisit.is_perfect' => 1
                );
            } else {
                $optionsget['conditions'] = array(
                    'UserPerfectVisit.clinic_id' => $clinic_id,
                    'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                    'UserPerfectVisit.user_id' => $user_id,
                    'UserPerfectVisit.is_perfect' => 1
                );
                $nextphase = 1;
            }
            $datagetcount = $this->UserPerfectVisit->find('all', $optionsget);
            $getremain = count($datagetcount);

            //query to check treatment end
            $optionscheck['conditions'] = array(
                'UserPerfectVisit.clinic_id' => $clinic_id,
                'UserPerfectVisit.level_up_settings_id' => $treatment_id,
                'UserPerfectVisit.user_id' => $user_id
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
     * Getting the partice list with all details.
     * @return type
     */
    public function practice() {
        $this->layout = "buzzydocinner";
        $sessionbuzzy = $this->Session->read('userdetail');
        $clinicId = $this->params['id'];
        //condition to check valid pratice.
        if (isset($clinicId) && $clinicId != '') {
            $options['conditions'] = array(
                'Clinic.api_user' => $clinicId
            );
            $options['fields'] = array(
                'Clinic.id',
                'Clinic.industry_type'
            );
            $clinics = $this->Clinic->find('first', $options);
            $paydetchk['conditions'] = array(
                'PaymentDetail.clinic_id' => $clinics['Clinic']['id']
            );
            $getpayemntdetailschk = $this->PaymentDetail->find('first', $paydetchk);
            //getting the access for practice.
            $staffaceess = $this->AccessStaff->getAccessForClinic($clinics['Clinic']['id']);
            $this->set('staffaccess', $staffaceess);
            $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $clinics['Clinic']['industry_type'])));
            $leads = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $clinics['Clinic']['industry_type'])));
            $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
            if ($ref_msg == '') {
                $rmsg = array();
            } else {
                $rmsg = $ref_msg;
            }
            $this->set('refer_msg', $rmsg);
            $this->set('industry_id', $ind['IndustryType']['id']);
            $this->set('leads', $leads);
            $admin_set = $this->AdminSetting->find('first', array(
                'conditions' => array(
                    'AdminSetting.clinic_id' => $clinics['Clinic']['id'],
                    'AdminSetting.setting_type' => 'leadpoints'
                )
            ));
            $this->set('admin_settings', $admin_set);


            if (isset($sessionbuzzy->User->id)) {
                $data = array('user_id' => $sessionbuzzy->User->id);
            } else {
                $data = array('user_id' => 0);
            }
            //getting the details od pratice.
            $clinicdetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/clinic/' . $clinicId . '.json'));

            if ($clinicdetails->clinics->success == 1) {
                $sessionbuzzy = $this->Session->read('userdetail');

                if (isset($sessionbuzzy->User->id) && isset($clinicdetails->clinics->data->Clinic->fb_app_id)) {
                    $config = array(
                        'appId' => $clinicdetails->clinics->data->Clinic->fb_app_id,
                        'secret' => $clinicdetails->clinics->data->Clinic->fb_app_key,
                        'allowSignedRequest' => false
                    );
                    $facebook_url = $clinicdetails->clinics->data->Clinic->facebook_url;
                    $facebook = new Facebook($config);
                    $user = $facebook->getUser();
                    //condition to check patient like as facebook or not.
                    if ($facebook_url != '' && $user > 0) {
                        $page_id = $this->getFacebookId($facebook_url);
                        $user_fb_email = '';
                        $user_profile = $facebook->api('/me');
                        if (array_key_exists("email", $user_profile)) {
                            $user_fb_email = $user_profile['email'];
                        }

                        $qry = "SELECT page_id FROM page_fan WHERE page_id = '" . $page_id . "' AND uid = '" . $user . "'";
                        $isFan = $facebook->api(array(
                            "method" => "fql.query",
                            "query" => $qry
                        ));
                        // page id=139168709464290
                        // first like
                        $fbliked = 0;
                        if (!empty($sessionbuzzy->Fblikes)) {
                            foreach ($sessionbuzzy->Fblikes as $flike) {
                                if ($flike->FacebookLike->clinic_id == $clinicdetails->clinics->data->Clinic->id && $flike->FacebookLike->like_status == 1) {
                                    $fbliked = 1;
                                }
                            }
                        }
                        if ((count($isFan) > 0) && $fbliked == 0) { // first like
                            $this->set("loginUrl", "");
                            $options_pro['fields'] = array(
                                'Promotion.id',
                                'Promotion.value',
                                'Promotion.description',
                                'Promotion.operand'
                            );
                            $options_pro['conditions'] = array(
                                'Promotion.clinic_id' => $clinicdetails->clinics->data->Clinic->id,
                                'Promotion.description like' => '%Facebook Like%'
                            );

                            $Promotions = $this->Promotion->find('first', $options_pro);
                            $data['user_id'] = $sessionbuzzy->User->id;
                            $data['first_name'] = $sessionbuzzy->User->first_name;
                            $data['last_name'] = $sessionbuzzy->User->last_name;
                            if (!empty($Promotions)) {
                                $data['promotion_id'] = $Promotions['Promotion']['id'];
                                $data['amount'] = $Promotions['Promotion']['value'];
                            } else {
                                $data['amount'] = 100;
                            }
                            $getval = $this->Api->getPatientLevelForAcceleratedReward($clinicdetails->clinics->data->Clinic->id, $sessionbuzzy->User->id);
                            $data['amount'] = $data['amount'] * $getval;
                            $data['activity_type'] = 'N';
                            $data['authorization'] = 'facebook point allocation';
                            $data['clinic_id'] = $clinicdetails->clinics->data->Clinic->id;
                            $data['date'] = date('Y-m-d H:i:s');
                            $data['status'] = 'New';
                            $data['is_buzzydoc'] = 1;

                            $this->Transaction->create();

                            if ($this->Transaction->save($data)) {
                                //condition to check user's first transaction.
                                $getfirstTransaction = $this->Api->get_firsttransaction($sessionbuzzy->User->id, $clinicId);
                                if ($getfirstTransaction == 1 && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {

                                    $template_array = $this->Api->get_template(39);
                                    $link1 = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                                    $link = str_replace('[points]', $data['amount'], $link1);
                                    $link2 = str_replace('[clinic_name]', $clinicdetails->clinics->data->Clinic->api_user, $link);
                                    $Email = new CakeEmail(MAILTYPE);
                                    $Email->from(array(
                                        SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                    ));
                                    $Email->to($sessionbuzzy->User->email);
                                    $Email->subject($template_array['subject'])
                                            ->template('buzzydocother')
                                            ->emailFormat('html');
                                    $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                                    $Email->viewVars(array(
                                        'msg' => $link2
                                    ));
                                    $Email->send();
                                }
                                $options2['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $clinicdetails->clinics->data->Clinic->id, 'Notification.earn_points' => 1);
                                $Notifications = $this->Notification->find('first', $options2);
                                if (!empty($Notifications) && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {

                                    $template_array = $this->Api->get_template(1);
                                    $link = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                                    $link1 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link);
                                    $link2 = str_replace('[clinic_name]', $clinicdetails->clinics->data->Clinic->api_user, $link1);
                                    $link3 = str_replace('[points]', $data['amount'], $link2);
                                    $Email = new CakeEmail(MAILTYPE);
                                    $Email->from(array(
                                        SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                                    ));
                                    $Email->to($sessionbuzzy->User->email);
                                    $Email->subject($template_array['subject'])
                                            ->template('buzzydocother')
                                            ->emailFormat('html');
                                    $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                                    $Email->viewVars(array(
                                        'msg' => $link3,
                                        'Unsubscribe' => $buzzylogin
                                    ));
                                    $Email->send();
                                }
                                $optionsfblike['conditions'] = array(
                                    'FacebookLike.user_id' => $sessionbuzzy->User->id,
                                    'FacebookLike.clinic_id' => $clinicdetails->clinics->data->Clinic->id
                                );
                                $fblikes = $this->FacebookLike->find('first', $optionsfblike);
                                if (empty($fblikes)) {
                                    $ldata = array(
                                        'clinic_id' => $clinicdetails->clinics->data->Clinic->id,
                                        'user_id' => $sessionbuzzy->User->id,
                                        'like_status' => 1,
                                        'facebook_email' => ''
                                    );
                                    $this->FacebookLike->create();
                                    $this->FacebookLike->save($ldata);
                                } else {
                                    $this->FacebookLike->query("update facebook_likes set like_status=1 where user_id=" . $sessionbuzzy->User->id . " and clinic_id=" . $_POST['clinic_id']);
                                }
                                $totalpoint = $sessionbuzzy->User->points + $data['amount'];
                                $this->User->query("UPDATE `users` SET `points` = '" . $totalpoint . "' WHERE `id` =" . $sessionbuzzy->User->id);
                                $this->set('errorMsg', "We've credited " . $data['amount'] . " points to you as we found that you've already liked our Facebook page. Thanks!");
                            }
                        }
                    }
                }

                if ($clinicdetails->clinics->data->Clinic->is_buzzydoc == 1 && isset($sessionbuzzy->User->id)) {

                    $perclinicbuzzpnt = $this->getPointsDetails($sessionbuzzy);
                    $this->set('Perclinicbuzzpnt', $perclinicbuzzpnt);
                }

                $this->set('Clinics', $clinicdetails->clinics->data);
                $paydet['conditions'] = array(
                    'PaymentDetail.clinic_id' => $clinicdetails->clinics->data->Clinic->id
                );
                $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
                if (!empty($getpayemntdetails) && $getpayemntdetails['PaymentDetail']['customer_account_profile_id'] > 0) {
                    $this->set('paymentcheck', 1);
                } else {
                    $this->set('paymentcheck', 0);
                }
            } else {
                return $this->redirect('/practice/');
            }
        } else {
            //getting the all list of pratice.
            $this->set('limit', 10);
            $this->set('offset', 0);
            $options2['conditions'] = array(
                'CharacteristicInsurance.type' => 'insurance'
            );
            $insurence = $this->CharacteristicInsurance->find('all', $options2);
            $this->set('insurence', $insurence);
            $options1['fields'] = array(
                'IndustryType.id,IndustryType.name'
            );
            $industry = $this->IndustryType->find('all', $options1);
            $this->set('industry', $industry);
            $data = array(
                'limit' => 10,
                'offset' => 0
            );
            $cliniclist = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/cliniclist.json'));
            if ($cliniclist->cliniclists->success == 1) {
                $this->set('cliniclists', $cliniclist->cliniclists->data);
                $this->render('/Buzzydoc/practicelist');
            }
        }
    }

    /**
     * Getting the refferal message for practice.
     */
    public function getmsg() {
        $this->layout = "";
        $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $_POST['indty'])));
        $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
        $fname = 'reffralmessage' . $_POST['id'];
        echo $ref_msg->$fname;
        exit();
    }

    /**
     * Preview for referral mail goes to patient when refer to other user.
     */
    public function referpreview() {
        $clinic = $this->Clinic->find('first', array(
            'conditions' => array(
                'Clinic.id' => $_POST['clinic_id']
            )
        ));
        $refpromotion = $this->Refpromotion->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinic_promotions',
                    'alias' => 'ClinicPromotion',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClinicPromotion.promotion_id = Refpromotion.id'
                    )
                )
            ),
            'conditions' => array(
                'ClinicPromotion.clinic_id' => $clinic['Clinic']['clinic_id']
            )
        ));

        $promotion = '<br>';
        if (!empty($refpromotion)) {
            foreach ($refpromotion as $refp) {
                $promotion .= $refp['Refpromotion']['promotion_area'] . '<br>';
            }
        }

        $var = '<a class="close closebtn" onclick="close_form();" style="background: none repeat scroll 0 0 #FFFFFF;color:#000000;height: auto;padding: 5px 20px 5px 10px;right: 0;top: 0;">&times;</a>   
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #FFFFFF;">
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td background="' . CDN . 'img/header-bg2.jpg" bgcolor="#778cab" valign="top" style="background-size:cover; background-position:top;">
							<table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="left" >
                                        <img style="display:block; line-height:0px; font-size:0px; border:0px;margin-right: 90px;" src="' . S3Path . $clinic['Clinic']['patient_logo_url'] . '" width="246" height="88" alt="logo" />
                                    </td>
                                </tr>
								<tr>
									<td height="20"></td>
								</tr>
								<tr>
									<td height="30"></td>
								</tr>
								<tr>
									<td align="center" style=" height: 80px; font-family: Open Sans, Arial, sans-serif; font-size:14px; color:#ffffff; line-height:24px;">
									</td>
								</tr>
								<tr>
									<td height="50"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td height="50"></td>
					</tr>
					<tr>
						<td align="center" style="font-family: Open Sans, Arial, sans-serif; font-size:18px; font-weight: bold; color:#3498db;">' . $this->request->data['message'] . '.</td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
	<td align="center" style="font-family: Open Sans, Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:24px;">
        <a href="javascript:void(0)" style="background: none repeat scroll 0 0 #2FB888;
    color: #FFFFFF;
    display: block;
    margin: 10px 0 0;
    padding: 10px;
    text-decoration: none;
    width: 42%;">SURE I\'LL ACCEPT THIS REFERRAL!</a> <br />Click link to accept your referral and have someone from the practice reach out to you when you\'re ready ' . $promotion . '</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td height="50"></td>
					</tr>
				</table>
			</td>
		</tr>
        <tr>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="50"></td>
                    </tr>
                    <tr>
                        <td bgcolor="#333333">
                            <table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <table class="table3-3" width="183" border="0" align="left" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td height="16" style="padding-top: 10px;">
                                                    <a href="#"><img src="' . CDN . 'img/lamparski/lamparski_footer_image" alt="logo" title="logo"/> </a>
                                                </td>
                                            </tr>
                                        </table>
<table class="table3-3" width="392" border="0" align="right" cellpadding="0" cellspacing="0">       
<tr>
    <td  align="right" style=" padding-top: 5px;">
        <table>
            <tr>
                <td>
                    <span style="font-family: Open Sans, Arial, sans-serif; font-size:13px; text-align: right; color:#fff; line-height:28px;">Follow Us &nbsp;</span>
                </td>';
        if (isset($clinic['Clinic']['twitter_url']) && $clinic['Clinic']['twitter_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['twitter_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/twitter.png" height=""/> </a></td>';
        }
        if (isset($clinic['Clinic']['facebook_url']) && $clinic['Clinic']['facebook_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['facebook_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/facebook.png" height=""/> </a></td>';
        }
        if (isset($clinic['Clinic']['google_url']) && $clinic['Clinic']['google_url'] != '') {
            $var .= ' <td><a href="' . $clinic['Clinic']['google_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/googleplus.png" height=""/> </a></td>';
        }
        if (isset($clinic['Clinic']['instagram_url']) && $clinic['Clinic']['instagram_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['instagram_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/instagram.png" height=""/> </a></td>';
        }
        if (isset($clinic['Clinic']['pintrest_url']) && $clinic['Clinic']['pintrest_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['pintrest_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/pinterest.png" height=""/> </a></td>';
        }
        if (isset($clinic['Clinic']['yelp_url']) && $clinic['Clinic']['yelp_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['yelp_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/yelp.png" height=""/></a></td>';
        }
        if (isset($clinic['Clinic']['youtube_url']) && $clinic['Clinic']['youtube_url'] != '') {
            $var .= '<td><a href="' . $clinic['Clinic']['youtube_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/you-tube.png" height=""/></a></td>';
        }
        if (isset($clinic['Clinic']['healthgrade_url']) && $clinic['Clinic']['healthgrade_url'] != '') {
            $var .= ' <td><a href="' . $clinic['Clinic']['healthgrade_url'] . '" target="_blank"><img src="' . CDN . 'img/reward_imges/HealthGrades.png" height=""/> </a></td>';
        }
        $var .= '</tr>
        </table>
    </td>
</tr>
                                            <tr>
                                                <td class="footer-link" style="font-family: Open Sans, Arial, sans-serif; font-size:13px; text-align: right; color:#fff; line-height:28px;">
                                                        <span >Support: help@buzzydoc.com<br>
                                                        (888) 696-4753<br>
                                                        Your information is safe </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="20"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>   
	</table>';
        echo $var;
        die();
    }

    /**
     * Getting the all activty through out the system.
     */
    public function thebuzz() {
        $this->layout = "buzzydocinner";
        $data = array(
            'limit' => 10,
            'offset' => 0
        );
        $this->set('limit', 10);
        $this->set('offset', 10);
        $activitydetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/activity.json'));
        if ($activitydetails->usersactivity->success == 1) {
            $this->set('activitydetails', $activitydetails->usersactivity->data);
        } else {
            $this->set('activitydetails', array());
        }
    }

    /**
     * Redemption of product and service for any pratice by patient.
     */
    public function redeemlocproduct() {
        if ($this->request->data['user_id'] == '' && $this->request->data['product_id'] == '' && $this->request->data['points'] == '') {
            echo 2;
        } else {
            $checkpoint = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['user_id'])));
            //condition to check patient have enough points.
            if ($checkpoint['User']['points'] >= $this->request->data['points']) {
                //calling procedure to redeem product and service.
                $redeemres = $this->Transaction->query('CALL sp_redeem_points(' . $this->request->data['user_id'] . ',' . $this->request->data['product_id'] . ',' . $this->request->data['points'] . ',"' . date("Y-m-d H:i:s") . '","null")');

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
                $optionscgettrans['conditions'] = array('Transaction.id' => $redeemres[0]['redemption_details']['transaction_id']);
                $trandetails = $this->Transaction->find('first', $optionscgettrans);
                $optionscgetcard['conditions'] = array('ClinicUser.clinic_id' => $trandetails['Transaction']['clinic_id'], 'ClinicUser.user_id' => $trandetails['Transaction']['user_id']);
                $getcardnumber = $this->ClinicUser->find('first', $optionscgetcard);
                $transactionupdate = array(
                    'id' => $trandetails['Transaction']['id'],
                    'card_number' => $getcardnumber['ClinicUser']['card_number']
                );
                $this->Transaction->save($transactionupdate);

                $template_array_red = $this->Api->save_notification($trandetails['Transaction'], 2, $redeemres[0]['redemption_details']['transaction_id']);
                //depricated condition.
                if (DEBIT_FROM_BANK == 1) {
                    foreach ($redeemres as $dt) {
                        $paytoclinic = $dt['redemption_details']['points_to_be_deducted'];
                        if ($paytoclinic > 0) {
                            $options8['conditions'] = array('Staff.clinic_id' => $dt['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.redemption_mail' => 1);
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
                $template_array1 = $this->Api->get_template(13);
                $link_new = str_replace('[username]', $checkpoint['User']['first_name'], $template_array1['content']);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                $Email->to($checkpoint['User']['email']);
                $Email->subject($template_array1['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');

                $Email->viewVars(array('msg' => $link_new,
                    'orderdetails' => $orderdetail
                ));
                $Email->send();
                echo 1;
            } else {
                echo 0;
            }
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $this->request->data['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            }
        }

        die();
    }

    /**
     * function to find the more activity.
     */
    public function morebuzz() {
        $this->layout = "";
        $data = array(
            'limit' => $_POST['limit'],
            'offset' => $_POST['offset']
        );
        $activitydetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/activity.json'));
        $html = '';
        if ($activitydetails->usersactivity->success == 1) {
            if (count($activitydetails->usersactivity->data) > 0) {
                foreach ($activitydetails->usersactivity->data as $promo) {
                    $givenName = '';
                    if ($promo->Transaction->activity_type == 'like clinic') {
                        $givenName = $promo->Transaction->first_name;
                        $html .= '                          
                            <li>
                              <span class="date-detail">' . date("M d,Y", strtotime($promo->Transaction->date)) . '</span>
                              <div class="data-container">
                                <div class="listing-point">&nbsp;</div>
                                <div class="doc-small-img">
                                <img class="" alt="user picture" title="" src="' . CDN . 'img/images_buzzy/user_small_pic.png">
                                </div>
                                <span class="doc-place-name">' . ((strlen($givenName) > 30) ? substr($givenName, 0, 30) . "..." : $givenName) . '</span>
                                <p class="listing-description">Liked the ' . ((strlen($promo->Transaction->given_name) > 30) ? substr($promo->Transaction->given_name, 0, 30) . "..." : $promo->Transaction->given_name) . ' practice </p>
                              </div>
                            </li>';
                    } else
                    if ($promo->Transaction->activity_type == 'save doctor') {
                        $givenName = $promo->Transaction->first_name;
                        $html .= '<li>
                            <span class="date-detail">' . date("M d,Y", strtotime($promo->Transaction->date)) . '</span>
                            <div class="data-container">
                              <div class="listing-point">&nbsp;</div>
                              <div class="doc-small-img">
                              <img class="" alt="user picture" title="" src="' . CDN . 'img/images_buzzy/user_small_pic.png">
                              </div>
                              <span class="doc-place-name">' . ((strlen($givenName) > 30) ? substr($givenName, 0, 30) . "..." : $givenName) . '</span>
                              <p class="listing-description">saved the Dr. ' . ((strlen($promo->Transaction->given_name) > 30) ? substr($promo->Transaction->given_name, 0, 30) . "..." : $promo->Transaction->given_name) . '  </p>
                            </div>
                          </li>';
                    } else
                    if ($promo->Transaction->activity_type == 'earn badge') {
                        $givenName = $promo->Transaction->first_name;
                        $html .= '<li>
              <span class="date-detail">' . date("M d,Y", strtotime($promo->Transaction->date)) . '</span>
              <div class="data-container">
                <div class="listing-point">
                  <div class="badgeImg">';

                        if ($promo->Transaction->buzzy_name == 'Newbie') {

                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point1.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 1') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point2.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 2') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point3.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 3') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point4.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 4') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point5.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 5') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point5.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 6') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point6.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 7') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point7.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 8') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point8.png">';
                        }
                        if ($promo->Transaction->buzzy_name == 'Level 9') {
                            $html .= '<img class="" alt="" title="" src="' . CDN . 'img/images_buzzy/badge_point9.png">';
                        }

                        $html .= '</div>
                  <p class="point-num">' . $promo->Transaction->buzzy_value . '</p>
                  <p class="point-word">Points</p>
                </div>
                <div class="doc-small-img">
                  <img class="" alt="user picture" title="" src="' . CDN . 'img/images_buzzy/user_small_pic.png">
                </div>
                <span class="doc-place-name">' . ((strlen($givenName) > 30) ? substr($givenName, 0, 30) . "..." : $givenName) . '</span>
                <p class="listing-description">' . ((strlen($promo->Transaction->authorization) > 30) ? substr($promo->Transaction->authorization, 0, 30) . "..." : $promo->Transaction->authorization) . '</p>
              </div>
            </li>';
                    } else
                    if ($promo->Transaction->activity_type == 'redeemed') {
                        $givenName = $promo->Transaction->first_name;
                        $html .= '<li>
                            <span class="date-detail">' . date("M d,Y", strtotime($promo->Transaction->date)) . '</span>
                            <div class="data-container">
                              <div class="listing-point">';
                        if ($promo->Transaction->amount < 0) {
                            $html .= $promo->Transaction->amount;
                        } else {
                            $html .= '+' . $promo->Transaction->amount;
                        }
                        $html .= '</div>
                              <div class="doc-small-img">
                              <img class="" alt="user picture" title="" src="' . CDN . 'img/images_buzzy/user_small_pic.png">
                              </div>
                              <span class="doc-place-name">' . ((strlen($givenName) > 30) ? substr($givenName, 0, 30) . "..." : $givenName) . '</span>';
                        if ($promo->Transaction->authorization == 'global points expired') {
                            $html .= '<p class="listing-description" title="' . $promo->Transaction->authorization . '">' . ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization, 0, 20) . '...' : $promo->Transaction->authorization) . '  </p>';
                        } else if ($promo->Transaction->product_service_id > 0) {
                            $html .= '<p class="listing-description" title="' . $promo->Transaction->authorization . '">' . ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization, 0, 20) . '...' : $promo->Transaction->authorization) . '  </p>';
                        } else {
                            $html .= '<p class="listing-description" title="Have redeemed the reward ' . $promo->Transaction->authorization . '">Have redeemed the reward ' . ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization, 0, 20) . '...' : $promo->Transaction->authorization) . '  </p>';
                        }

                        $html .= '</div>
                          </li>';
                    } else
                    if ($promo->Transaction->activity_type == 'get point') {
                        $gavePoints = $promo->Transaction->authorization . ' to ' . $promo->Transaction->first_name;
                        $html .= '                                
                            <li>
                              <span class="date-detail">' . date("M d,Y", strtotime($promo->Transaction->date)) . '</span>
                              <div class="data-container">
                                <div class="listing-point">';
                        if ($promo->Transaction->amount < 0) {
                            $html .= $promo->Transaction->amount;
                        } else {
                            $html .= '+' . $promo->Transaction->amount;
                        }
                        $html .= '</div>
                                <div class="doc-small-img">
                                <img class="" alt="user picture" title="" src="' . CDN . 'img/images_buzzy/user_small_pic.png">
                                </div>
                                <span class="doc-place-name" title="' . $promo->Transaction->given_name . '">' . ((strlen($promo->Transaction->given_name) > 22) ? substr($promo->Transaction->given_name, 0, 22) . '...' : $promo->Transaction->given_name) . '</span>
                                <p class="listing-description" title="Gave points for ' . $gavePoints . '">Gave points for ' . ((strlen($gavePoints) > 30) ? substr($gavePoints, 0, 30) . "..." : $gavePoints) . '</p>
                              </div>
                            </li>';
                    }
                }
            } else {
                $html = '';
            }
        } else {
            $html = '';
        }
        echo $html;
        die();
    }

    /**
     * Search doctor by specility and pincode for show at landing page.
     */
    public function getdoctor() {
        $this->layout = "";
        $data = array(
            'specialty' => $_POST['specialty'],
            'pincode' => $_POST['pincode']
        );
        $doctor = $this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/getspecialitydoc.json');
        $alldoctor = json_decode($doctor);
        $html = '';
        if ($alldoctor->specilitydoc->success == 1) {
            if (count($alldoctor->specilitydoc->data) > 0) {
                foreach ($alldoctor->specilitydoc->data as $toprated) {
                    $totalrate = 0;
                    foreach ($toprated as $trKey => $trVal) {

                        if ($trKey == 0 && isset($trVal->totalrate)) {
                            $totalrate = round($trVal->totalrate);
                        }
                    }

                    $html .= '<li>
                  <a href="/doctor/' . $toprated->dc->first_name . ' ' . $toprated->dc->last_name . '/' . $toprated->dc->specialty . '" style="cursor: pointer">
                    <div class="doctor-detials cf">
                      <div class="doctor-image">';
                    if ($toprated->dc->gender == 'Male') {
                        $html .= '<img alt="buzzydoc overview" title="' . $toprated->dc->first_name . '" src="' . CDN . 'img/images_buzzy/doctor-male.png">';
                    } else {
                        $html .= '<img alt="buzzydoc overview" title="' . $toprated->dc->first_name . '" src="' . CDN . 'img/images_buzzy/doctor-female.png">';
                    }
                    $html .= '</div>
                      <div class="doctor-name">
                      <h3>Dr. ' . $toprated->dc->first_name . ' ' . $toprated->dc->last_name . ',' . $toprated->dc->specialty . '</h2>
                        <div class="rating">';
                    if (isset($totalrate) && $totalrate > 0) {
                        $rate = $totalrate;
                    } else {
                        $rate = 0;
                    }
                    $grey = 5 - $rate;
                    for ($i = 0; $i < $rate; $i ++) {
                        $html .= '<span class="fullstar"></span>';
                    }
                    for ($i1 = 0; $i1 < $grey; $i1 ++) {
                        $html .= '<span class="greystar"></span>';
                    }

                    $html .= '</div>
                      </div>
                      <div class="doctor-address">
                        <h4>' . $toprated->dc->address . '</h4>
                        <p>
                          ' . $toprated->dc->city . ', ' . $toprated->dc->state . '</p>
                      </div>
                    </div>
                  </a>
                </li>';
                }
            } else {
                $html = '<li>
                  <a href="javascript:void(0)" style="cursor: default">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
            }
        } else {
            $html = '<li>
                  <a href="javascript:void(0)" style="cursor: default">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
        }
        echo $html;
        exit();
    }

    /**
     * Pratice characteristic like by patient for pratice and doctor.
     * @return type
     */
    public function characteristiclike() {
        $this->layout = "";

        $doctor = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/patientcharacteristiclike.json');
        $alldoctor = json_decode($doctor);
        if ($alldoctor->characteristiclike->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }

            $data = array(
                'success' => 1,
                'data' => $alldoctor->characteristiclike->data
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Doctor save by patient.
     * @return type
     */
    public function savedoctor() {
        $this->layout = "";

        $doctor = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/activitysavedoc.json');
        $alldoctor = json_decode($doctor);
        if ($alldoctor->save->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $data = array(
                'success' => 1,
                'data' => $alldoctor->save->data
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient delete doctor from save list.
     * @return type
     */
    public function unsavedoctor() {
        $this->layout = "";

        $doctor = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/activitysavedoc.json');
        $alldoctor = json_decode($doctor);
        if ($alldoctor->save->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $data = array(
                'success' => 1,
                'data' => $alldoctor->save->data,
                'likes' => $userdetails->userdetail->data->Save
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Update patient profile details and change password.
     * @return type
     */
    public function editprofile() {
        $this->layout = "";
        $edit = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/editprofile.json');
        $editprofile = json_decode($edit);
        if ($editprofile->edit->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $data = array(
                'success' => 1,
                'data' => $editprofile->edit->data,
                'state' => $editprofile->edit->state,
                'city' => $editprofile->edit->city
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => $editprofile->edit->data
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient add pratice to like list.
     * @return type
     */
    public function likeclinic() {
        $this->layout = "";
        $clinic = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/activitylike.json');
        $likecl = json_decode($clinic);
        if ($likecl->like->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $data = array(
                'success' => 1,
                'data' => $likecl->like->data
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient delete pratice from like list.
     * @return type
     */
    public function unlikeclinic() {
        $this->layout = "";
        $clinic = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/activitylike.json');
        $likecl = json_decode($clinic);
        if ($likecl->like->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $data = array(
                'success' => 1,
                'data' => $likecl->like->data,
                'likes' => $userdetails->userdetail->data->Like
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient give rate and review to doctor and pratice.
     * @return type
     */
    public function rate() {
        $this->layout = "";

        $clinic = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/rateReview.json');
        $likecl = json_decode($clinic);
        if ($likecl->rate->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $_POST['user_id'] . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
                return $this->redirect('/login/');
            }
            $html = '';
            $rateshow = '';
            if (isset($_POST['doctor_id'])) {
                $doctordetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/doctor/' . $_POST['doctor_id'] . '.json'));
                $grey = 5 - $doctordetails->Doctors->data->Rate;

                for ($i = 0; $i < $doctordetails->Doctors->data->Rate; $i ++) {

                    $html .= '<span class = "fullstar"></span>';
                }
                for ($i1 = 0; $i1 < $grey; $i1 ++) {

                    $html .= '<span class = "greystar"></span>';
                }
                $rateshow .= '(' . number_format((float) $doctordetails->Doctors->data->Rate, 1, '.', '') . ')';
            } else {
                $clinicdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/clinic/' . $_POST['clinic_name'] . '.json'));
                $grey = 5 - $clinicdetails->clinics->data->Rate;

                for ($i = 0; $i < $clinicdetails->clinics->data->Rate; $i ++) {

                    $html .= '<span class = "fullstar"></span>';
                }
                for ($i1 = 0; $i1 < $grey; $i1 ++) {

                    $html .= '<span class = "greystar"></span>';
                }
                $rateshow .= '(' . number_format((float) $clinicdetails->clinics->data->Rate, 1, '.', '') . ')';
            }
            $data = array(
                'success' => 1,
                'data' => $likecl->rate->data,
                'stars' => $html,
                'rateshow' => $rateshow
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient recommend the partice to other user.
     */
    public function recommend() {
        $this->layout = "";
        $clinic = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/recommend.json');
        $likecl = json_decode($clinic);
        if ($likecl->Recommend->success == 1) {
            $data = array(
                'success' => 1,
                'data' => $likecl->Recommend->data
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Patient shcedule an appointment at pratice.
     */
    public function appointment() {
        $this->layout = "";
        $clinic = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/appointment.json');
        $likecl = json_decode($clinic);
        if ($likecl->appointment->success == 1) {
            $data = array(
                'success' => 1,
                'data' => $likecl->appointment->data
            );
        } else {
            $data = array(
                'success' => 0,
                'data' => 'Bad Request'
            );
        }
        echo json_encode($data);
        die();
    }

    /**
     * Result page for both doctor and pratice view the list of both.
     * @return type
     */
    public function searchresult() {
        $this->layout = "buzzydocinner";
        if ($this->request->data['key'] == '') {
            return $this->redirect('/dashboard/');
        }
        $data = array(
            'key' => $this->request->data['key']
        );
        $searchresult = $this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/serarchdocorclinic.json');
        $sresult = json_decode($searchresult);
        if ($sresult->serachresult->success == 1) {

            $this->set('SearchResult', $sresult->serachresult->data);
            $this->set('searchkey', $this->request->data['key']);
        } else {
            $this->set('SearchResult', $sresult->serachresult->data);
            $this->set('searchkey', $this->request->data['key']);
        }
    }

    /**
     * Getting the list of doctor via pincode.
     */
    public function getdoctorviapincode() {
        $this->layout = "";

        $data = array(
            'specialty' => $_POST['specialty'],
            'pincode' => $_POST['pincode']
        );
        $doctor = $this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/getspecialitydoc.json');
        $alldoctorid = json_decode($doctor);
        $html = '';
        if ($alldoctorid->specilitydoc->success == 1) {
            if (count($alldoctorid->specilitydoc->data) > 0) {

                foreach ($alldoctorid->specilitydoc->data as $toprated) {
                    $totalrate = 0;
                    foreach ($toprated as $trKey => $trVal) {

                        if ($trKey == 0 && isset($trVal->totalrate)) {
                            $totalrate = round($trVal->totalrate);
                        }
                    }

                    $html .= '<li>
                  <a href="#">
                    <div class="doctor-detials cf">
                      <div class="doctor-image">';
                    if ($toprated->dc->gender == 'Male') {
                        $html .= '<img alt="buzzydoc overview" title="buzzydoc overview" src="' . CDN . 'img/images_buzzy/doctor-male.png">';
                    } else {
                        $html .= '<img alt="buzzydoc overview" title="buzzydoc overview" src="' . CDN . 'img/images_buzzy/doctor-female.png">';
                    }
                    $html .= '</div>
                      <div class="doctor-name">
                        <h3>Dr. ' . $toprated->dc->first_name . ' ' . $toprated->dc->last_name . ',' . $toprated->dc->specialty . '</h2>
                        <div class="rating">';
                    if (isset($totalrate) && $totalrate > 0) {
                        $rate = $totalrate;
                    } else {
                        $rate = 0;
                    }
                    $grey = 5 - $rate;
                    for ($i = 0; $i < $rate; $i ++) {
                        $html .= '<span class="fullstar"></span>';
                    }
                    for ($i1 = 0; $i1 < $grey; $i1 ++) {
                        $html .= '<span class="greystar"></span>';
                    }
                    $html .= '</div>
                      </div>
                      <div class="doctor-address">
                        <h4>' . $toprated->dc->address . '</h4>
                        <p>
                          ' . $toprated->dc->city . ', ' . $toprated->dc->state . '</p>
                      </div>
                    </div>
                  </a>
                </li>';
                }
            } else {
                $html = '<li>
                  <a href="javascript:void(0)" style="cursor: default">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
            }
        } else {
            $html = '<li>
                  <a href="javascript:void(0)" style="cursor: default">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
        }
        echo $html;
        exit();
    }

    /**
     * Search doctor list using many parameter.
     */
    public function searchdoc() {
        $this->layout = "";

        $doctor = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/serarchdoc.json');
        $alldoctorid = json_decode($doctor);
        $cnt = 0;
        $html = '';
        if ($alldoctorid->doctorslist->success == 1) {
            $cnt = count($alldoctorid->doctorslist->data);
            if (count($alldoctorid->doctorslist->data) > 0) {

                foreach ($alldoctorid->doctorslist->data as $Doctors) {
                    $totalrate = 0;
                    foreach ($Doctors as $trKey => $trVal) {

                        if ($trKey == 0 && isset($trVal->totalrate)) {
                            $totalrate = round($trVal->totalrate);
                        }
                    }

                    $html .= '<div class="filter-search-results cf">
                        <div class="result-img-wrap">';

                    $docprofilePath1 = AWS_server . AWS_BUCKET . '/img/docprofile/' . $Doctors->ClinicName . '/' . $Doctors->Doctor->email;

                    $retcode = $this->Api->is_exist_img($docprofilePath1);
                    if ($retcode == 200) {
                        $html .= '<img src="' . $docprofilePath1 . '" width="62px" height="72px" class="thumb-picture">';
                    } else {
                        if ($Doctors->Doctor->gender == 'Male') {
                            $html .= ' <img class="thumb-picture" height="72px" width="62px" alt="doctor picture" title="doctor" src="' . CDN . 'img/images_buzzy/doctor-male.png">';
                        } else {
                            $html .= ' <img class="thumb-picture" height="72px" width="62px" alt="doctor picture" title="doctor" src="' . CDN . 'img/images_buzzy/doctor-female.png">';
                        }
                    }

                    $d1 = new DateTime(date('Y-m-d'));
                    $d2 = new DateTime($Doctors->Doctor->dob);
                    $diff = $d2->diff($d1);

                    $html .= '</div>
                        <div class="result-detail">
                            <div class="result-detail-top-row">
                                <h4 class="result-heading">Dr. ' . $Doctors->Doctor->first_name . ' ' . $Doctors->Doctor->last_name . ' ,' . $Doctors->Doctor->degree . '</h4>
                                <div class="result-view-profile-btn-wrap">
                                    <a href="/doctor/' . $Doctors->Doctor->first_name . ' ' . $Doctors->Doctor->last_name . '/' . $Doctors->Doctor->specialty . '" class="result-view-profile-btn" title="View Full Profile">View Full Profile</a>
                                </div>
                            </div>
                            <h5 class="address-heading">' . $Doctors->Doctor->address . '</h5>
                            <p class="address-detials">
                        ' . $Doctors->Doctor->city . ' ,' . $Doctors->Doctor->state . ' ,' . $Doctors->Doctor->pincode . '</p>
                            <ul class="result-doctor-list">
                                <li>Specializes in ' . $Doctors->Doctor->specialty . '</li>
                                <li>' . $Doctors->Doctor->gender . '</li>
                                <li>Age ' . $diff->y . '</li>
                            </ul>
                            <ul class="result-main-btn">
                                <li>
                                    <a>
                                        <div class="rating tab-rating">';
                    if (isset($Doctors->Rate) && $Doctors->Rate > 0) {
                        $rate = $Doctors->Rate;
                    } else {
                        $rate = 0;
                    }
                    $grey = 5 - $rate;
                    for ($i = 0; $i < $rate; $i ++) {
                        $html .= '<span class="fullstar"></span>';
                    }
                    for ($i1 = 0; $i1 < $grey; $i1 ++) {
                        $html .= '<span class="greystar"></span>';
                    }
                    $html .= '</div>
                                        <span class="sub-txt">(' . $rate . ')</span>
                                    </a>
                                </li>
                                <li><a>Save <span class="sub-txt">(' . $Doctors->Save . ')</span></a></li>

                            </ul>
                        </div>
                    </div>
';
                }
            } else {
                $html = '<div class="filter-search-results cf">
                        <div class="result-img-wrap">
                     
                      No Doctor Found!
                      
                    </div>
                  </div>';
            }
        } else {
            $html = '<div class="filter-search-results cf">
                        <div class="result-img-wrap">
                     
                      No Doctor Found!
                      
                    </div>
                  </div>';
        }
        $data = array(
            'cnt' => $cnt,
            'data' => $html
        );

        echo json_encode($data);
        exit();
    }

    /**
     * Searhc clinic via many parameter and view the list.
     */
    public function searchpractice() {
        $this->layout = "";
        $doctor = $this->Api->submit_cURL(json_encode($_POST), Buzzy_Name . '/api/searchclinic.json');
        $alldoctorid = json_decode($doctor);
        $cnt = 0;
        $html = '';
        if ($alldoctorid->clinicslist->success == 1) {
            $cnt = count($alldoctorid->clinicslist->data);
            if (count($alldoctorid->clinicslist->data) > 0) {

                foreach ($alldoctorid->clinicslist->data as $Clinics) {

                    $html .= '<div class="filter-search-results cf">
                <div class="result-img-wrap">';
                    $ch = curl_init(S3Path . $Clinics->Clinic->buzzydoc_logo_url);

                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_exec($ch);
                    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if (isset($Clinics->Clinic->buzzydoc_logo_url) && $Clinics->Clinic->buzzydoc_logo_url != '') {
                        $html .= '<img src="' . S3Path . $Clinics->Clinic->buzzydoc_logo_url . '"  alt="' . $Clinics->Clinic->api_user . '" class="thumb-picture" title="' . $Clinics->Clinic->api_user . '" />';
                    } else {
                        $html .= '<img class="thumb-picture" title="' . $Clinics->Clinic->api_user . '" alt="' . $Clinics->Clinic->api_user . '" src="' . CDN . 'img/images_buzzy/clinic.png">';
                    }
                    if (isset($Clinics->Pointshare) && $Clinics->Pointshare > 0) {
                        $tpoint = $Clinics->Pointshare;
                    } else {
                        $tpoint = 0;
                    }
                    if ($Clinics->Clinic->display_name == '') {
                        $clinicname = $Clinics->Clinic->api_user;
                    } else {
                        $clinicname = $Clinics->Clinic->display_name;
                    }
                    $html .= '</div>
                <div class="result-detail">
                  <div class="result-detail-top-row">
                    <h4 class="result-heading"><a href="/practice/' . $Clinics->Clinic->api_user . '" title="View Full Profile">' . $clinicname . '</a></h4>
                    
                    <div class="result-view-profile-btn-wrap">
                      <a href="/practice/' . $Clinics->Clinic->api_user . '" class="result-view-profile-btn" title="View Full Profile">View Full Profile</a>
                    </div>
                  </div>
                  <h5 class="address-heading">';
                    if (isset($Clinics->PrimeOffices)) {
                        $html .= $Clinics->PrimeOffices->ClinicLocation->address;
                    }
                    $html .= '</h5>
                      <p class="address-detials">';
                    if (isset($Clinics->PrimeOffices)) {
                        $html .= $Clinics->PrimeOffices->ClinicLocation->city . ' ,' . $Clinics->PrimeOffices->ClinicLocation->state . ' ,' . $Clinics->PrimeOffices->ClinicLocation->pincode;
                    }
                    $html .= '</p>
                      <ul class="result-doctor-list">';
                    $d = count($Clinics->Doctors);
                    $d1 = 1;
                    foreach ($Clinics->Doctors as $doc) {
                        $html .= '<li>' . $doc->Doctor->first_name . ' ' . $doc->Doctor->last_name;
                        if ($d != $d1) {
                            $html .= ",";
                        }
                        $html . '</li>';
                        $d1 ++;
                    }
                    $html .= '</ul>
                  
                  <ul class="result-main-btn">
                    <li>
                      <a>
                        <div class="rating tab-rating">';
                    if (isset($Clinics->Rate) && $Clinics->Rate > 0) {
                        $rate = $Clinics->Rate;
                    } else {
                        $rate = 0;
                    }
                    $grey = 5 - $rate;
                    for ($i = 0; $i < $rate; $i ++) {
                        $html .= '<span class="fullstar"></span>';
                    }
                    for ($i1 = 0; $i1 < $grey; $i1 ++) {
                        $html .= '<span class="greystar"></span>';
                    }
                    $html .= '</div>
                        <span class="sub-txt">(' . $rate . ')</span>
                      </a>
                    </li>
                    <li><a>Likes <span class="sub-txt">(' . $Clinics->Likes . ')</span></a></li>
                    <li><a>Reviews <span class="sub-txt">(' . $Clinics->TotalReview . ')</span></a></li>
                    <li><a>Check-ins <span class="sub-txt">(' . $Clinics->TotalCheckin . ')</span></a></li>
                  
                  </ul>
                </div>
              </div>
';
                }
            } else {
                $html = '<div class="filter-search-results cf">
                        <div class="result-img-wrap">
                     
                      No Practice Found!
                      
                    </div>
                  </div>';
            }
        } else {
            $html = '<div class="filter-search-results cf">
                        <div class="result-img-wrap">
                     
                      No Practice Found!
                      
                    </div>
                  </div>';
        }

        $data = array(
            'cnt' => $cnt,
            'data' => $html
        );

        echo json_encode($data);
        exit();
    }

    /**
     * Patient signin at buzzydoc site.
     */
    public function buzzysignin() {
        $this->layout = "";
        $data = array(
            'username' => $_POST['username'],
            'password' => $_POST['password']
        );

        $activitydetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/patientlogin.json'));

        if ($activitydetails->logincheck->success == 1) {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $activitydetails->logincheck->data . '.json'));
            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
            } else {
                $this->Session->delete('userdetail');
            }
        } else {
            $this->Session->delete('userdetail');
        }

        if ($activitydetails->logincheck->success == 1) {
            echo $activitydetails->logincheck->success;
        } else {
            if ($activitydetails->logincheck->data == 'Invalid Credentials') {
                echo 2;
            } else
            if ($activitydetails->logincheck->data == 'Your Account has been blocked.Please contact to buzzydoc admin.') {
                echo 4;
            } else {
                echo 3;
            }
        }
        die();
    }

    /**
     * Cheking the patient emailid for forgot password.
     */
    public function checkuser() {
        $this->layout = "";
        $data['conditions'] = array(
            'User.email' => $_POST['email']
        );
        $users = $this->User->find('all', $data);
        $xml = '';
        if (count($users) > 1) {
            $xml .= '<select name="cardcheck" id="cardcheck"><option value="">Select User</option>';
            foreach ($users as $user) {
                $datauser['conditions'] = array(
                    'ClinicUser.user_id' => $user['User']['id']
                );
                $clinicusers = $this->ClinicUser->find('first', $datauser);
                if (!empty($clinicusers)) {
                    $xml .= "<option value='" . $clinicusers['ClinicUser']['card_number'] . "'>" . $clinicusers['ClinicUser']['card_number'] . "</option>";
                } else {
                    $xml .= "<option value='buzzydoc_" . $user['User']['id'] . "'>" . $user['User']['first_name'] . " " . $user['User']['last_name'] . "</option>";
                }
            }
            $xml .= '</select>';
            echo $xml;
        } else {
            echo '&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">';
        }
        die();
    }

    /**
     * Forgot password for patient.
     */
    public function forgotpassword() {
        $this->layout = "";
        $data = array(
            'email' => $_POST['email'],
            'cardnumber' => $_POST['cardnumber']
        );
        $activitydetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/forgotpassword.json'));
        if ($activitydetails->logincheck->success == 1) {
            echo $activitydetails->logincheck->data;
        }
        die();
    }

    /**
     * Patient signup at buzzdoc site.
     */
    public function buzzysignup() {
        $this->layout = '';
        $jsonData = json_decode($_POST['jsonData'], TRUE); // for local
        $data['first_name'] = $jsonData[0];
        $data['last_name'] = $jsonData[1];
        $data['dob'] = date("Y-m-d", strtotime($jsonData[2]));
        $data['email'] = $jsonData[3];
        $data['phone'] = $jsonData[5];
        $data['street1'] = $jsonData[6];
        $data['street2'] = $jsonData[7];
        $data['state'] = $jsonData[8];
        $data['city'] = $jsonData[9];
        $data['postal_code'] = $jsonData[10];
        $data['gender'] = $jsonData[11];
        $data['password'] = $jsonData[12];
        $data['clinic_id'] = $jsonData[17];
        $data['card_number'] = $jsonData[18];
        $data['action'] = $jsonData[19];
        if ($jsonData[15] == 'child') {
            $data['parents_email'] = $jsonData[4];
            $data['perent_check'] = $jsonData[16];
        }
        if (isset($jsonData[20])) {
            $data['facebook_id'] = $jsonData[20];
            $data['is_facebook'] = $jsonData[21];
        } else {
            $data['facebook_id'] = 0;
            $data['is_facebook'] = 0;
        }
        $this->Session->delete('fbuserdetail');

        $activitydetails = json_decode($this->Api->submit_cURL(json_encode($data), Buzzy_Name . '/api/patientsignup.json'));

        if ($activitydetails->signupcheck->data == 'Sign Up completed, use your credentials for login.') {
            $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $activitydetails->signupcheck->user_id . '.json'));

            if ($userdetails->userdetail->success == 1) {
                $this->Session->write('userdetail', $userdetails->userdetail->data);
                echo json_encode(array(
                    'data' => $activitydetails->signupcheck->data
                ));
            }
        } else {
            echo json_encode(array(
                'data' => $activitydetails->signupcheck->data
            ));
        }
        die();
    }

    /**
     * Get city for particular state.
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
                    'States.state = "' . $_POST['state_code'] . '"'
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
        $html = '<option value="">Select City[*]</option>';
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
     * Get city list for particular state.
     */
    public function getcitylist() {
        $this->layout = '';
        $state = $_POST['state_code'];
        $data['conditions'] = array(
            'City.state_code' => $state
        );
        $data['order'] = array(
            'City.city asc'
        );
        $city = $this->City->find('all', $data);
        $data = array(
            'data' => $city
        );
        echo json_encode($data);
        die();
    }

    /**
     * Patient Logut from buzzydoc site.
     * @return type
     */
    public function logout() {
        $this->Session->destroy();
        return $this->redirect(array(
                    'controller' => 'buzzydoc',
                    'action' => 'login'
        ));
    }

    /**
     * Map location display for pratice all office,
     */
    public function map() {
        $sessionbuzzy = $this->Session->read('userdetail');
        $add = '';
        foreach ($sessionbuzzy->Profilefield as $fields) {
            if ($fields->ProfileField->profile_field == 'street1') {
                $add .= ' ' . $fields->ProfileFieldUser->value;
            }
            if ($fields->ProfileField->profile_field == 'street2') {
                $add .= ' ' . $fields->ProfileFieldUser->value;
            }
            if ($fields->ProfileField->profile_field == 'city') {
                $add .= ' ' . $fields->ProfileFieldUser->value;
                $this->set('title1', $fields->ProfileFieldUser->value);
            }
            if ($fields->ProfileField->profile_field == 'state') {
                $add .= ' ' . $fields->ProfileFieldUser->value;
            }
            if ($fields->ProfileField->profile_field == 'postal_code') {
                $add .= ' ' . $fields->ProfileFieldUser->value;
            }
        }
        $prepAddr = str_replace(' ', '+', $add);
        $this->set('address1', $add);
        $Address = urlencode($prepAddr);
        $request_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=" . $Address . "&sensor=true";
        $xml = simplexml_load_file($request_url) or die("url not loading");
        $status = $xml->status;
        if ($status == "OK") {
            $latitude = $xml->result->geometry->location->lat;

            $longitude = $xml->result->geometry->location->lng;
        }
        if (isset($latitude)) {
            $this->set('latitude', $latitude);
        } else {
            $this->set('latitude', '');
        }
        if (isset($longitude)) {
            $this->set('longitude', $longitude);
        } else {
            $this->set('longitude', '');
        }
        $options_user2['conditions'] = array(
            'ClinicLocation.id' => base64_decode($this->request->pass[0])
        );
        $location = $this->ClinicLocation->find('first', $options_user2);
        if (!empty($location)) {
            $this->set('address2', $location['ClinicLocation']['address'] . ' ' . $location['ClinicLocation']['city'] . ' ' . $location['ClinicLocation']['state'] . ' ' . $location['ClinicLocation']['pincode']);
            $this->set('title2', $location['ClinicLocation']['city']);
            $latitude1 = $location['ClinicLocation']['latitude'];
            $longitude1 = $location['ClinicLocation']['longitude'];
            $this->set('latitude1', $latitude1);
            $this->set('longitude1', $longitude1);
        } else {
            $options_user2['conditions'] = array(
                'Doctor.id' => base64_decode($this->request->pass[0])
            );
            $location1 = $this->Doctor->find('first', $options_user);
            $add1 = $location1['Doctor']['address'] . ' ' . $location1['Doctor']['city'] . ' ' . $location1['Doctor']['state'] . ' ' . $location1['Doctor']['pincode'];
            $prepAddr1 = str_replace(' ', '+', $add1);
            $this->set('address2', $add1);
            $this->set('title2', $location1['Doctor']['city']);
            $Address1 = urlencode($prepAddr1);
            $request_url1 = "https://maps.googleapis.com/maps/api/geocode/xml?address=" . $Address1 . "&sensor=true";
            $xml1 = simplexml_load_file($request_url1) or die("url not loading");
            $status1 = $xml1->status;
            if ($status1 == "OK") {
                $latitude1 = $xml1->result->geometry->location->lat;

                $longitude1 = $xml1->result->geometry->location->lng;
            }
            $this->set('latitude1', $latitude1);
            $this->set('longitude1', $longitude1);
        }
        $this->layout = "buzzydocinner";
    }

    /**
     * Checking the age for patient while update details.
     */
    public function getcheckage() {
        $this->layout = '';
        $currentDate = DateTime::createFromFormat('d-m-Y', date('d-m-Y'));
        $userDate = DateTime::createFromFormat('d-m-Y', date($_POST['custom_date']));

        $timestamp_start = $userDate->getTimestamp(); // $startdate;
        $timestamp_end = $currentDate->getTimestamp();

        $difference = abs($timestamp_end - $timestamp_start);

        $days = floor($difference / (60 * 60 * 24));
        $months = floor($difference / (60 * 60 * 24 * 30));
        $years = floor($difference / (60 * 60 * 24 * 365));
        $xml = '';

        // 4747
        if ($days >= 4748 && $days <= 6573) { // below 18 yrs
            $xml = "<div><p class='radio-btn-wrap'>Choose email
                    <input type='radio' name='emailprovide' value='own'  checked> <label for='Own'>Own</label>
                    <input type='radio' name='emailprovide' value='perent'> <label for='Perent'>Parent</label>
                    </p>
                    </div>";

            $xml .= "<div id='emailvalid'><div class='content-col-1'>";
            $xml .= "<input name='email' id='email' type='email' class='input email' placeholder='Email [*]' onblur='checkpatientexist();' maxlength='50'/>";
            $xml .= "</div><div class='content-col-2'>";
            $xml .= "<input name='parents_email' id='parents_email' type='email' class='input email' placeholder='Username [*]' maxlength='50' onblur='checkpatientexist();'/>";
            $xml .= "</div>";
            $xml .= "</div>";
        } else if ($days <= 4747) { // below 18 yrs
            $xml = "<div class='content-col-1'>";
            $xml .= "<input name='email' id='email' type='email' class='input email' placeholder='Email [*]' onblur='checkpatientexist();' maxlength='50'/>";
            $xml .= "</div>";
            $xml .= "<div class='content-col-2'>";
            $xml .= "<input name='parents_email' id='parents_email' type='email' class='input email' placeholder='Username [*]' maxlength='50' onblur='checkpatientexist();'/>";
            $xml .= "</div>";
        } else {
            $xml = "<div class='content-col-1'>";
            $xml .= "<input name='email' id='email' type='email' class='input email' placeholder='Email [*]' onblur='checkpatientexist();' maxlength='50'/>";
            $xml .= "</div>";
            $xml .= "<div class='content-col-2'>";
            $xml .= "";
            $xml .= "</div>";
        }
        echo $xml;
        die();
    }

    /**
     * Signup page for mobile user.
     */
    public function signup() {
        $this->layout = "";
        $this->set('clinic_id', $this->request->data['search_practice']);
        $this->set('card_number', $this->request->data['send_card_number']);
    }

    /**
     * Login page for mobile user.
     */
    public function mlogin() {
        $this->layout = "";
    }

    /**
     * Forgot password page for mobile user.
     */
    public function mforgot() {
        $this->layout = "";
    }

    /**
     * Place an order for amazon and tango.
     */
    public function placeorder() {
        $this->layout = "";
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
     * Point allocation when patient facebook like for any pratice.
     * @return type
     */
    public function facebookpointallocation() {
        $this->layout = "";
        $Patients1 = array();
        $data = array();
        $sessionbuzzy = $this->Session->read('userdetail');

        $options_clinic['conditions'] = array(
            'Clinic.id' => $_POST['clinic_id']
        );
        $clinic = $this->Clinic->find('first', $options_clinic);
        $fbliked = 0;
        if (!empty($sessionbuzzy->Fblikes)) {
            foreach ($sessionbuzzy->Fblikes as $flike) {
                if ($flike->FacebookLike->clinic_id == $_POST['clinic_id'] && $flike->FacebookLike->like_status == 1) {
                    $fbliked = 1;
                }
            }
        }

        $config = array(
            'appId' => Facebook_APP_ID,
            'secret' => Facebook_Secret_Key,
            'allowSignedRequest' => false
        );

        $facebook = new Facebook($config);
        $user = $facebook->getUser();

        if ($fbliked == 0) {

            $options_pro['fields'] = array(
                'Promotion.id',
                'Promotion.value',
                'Promotion.description',
                'Promotion.operand'
            );
            $options_pro['conditions'] = array(
                'Promotion.clinic_id' => $_POST['clinic_id'],
                'Promotion.description like' => '%Facebook Like%'
            );

            $Promotions = $this->Promotion->find('first', $options_pro);
            $optionsfblike['conditions'] = array(
                'FacebookLike.user_id' => $sessionbuzzy->User->id,
                'FacebookLike.clinic_id' => $_POST['clinic_id'],
                'FacebookLike.like_status' => 1
            );
            $fblikes = $this->FacebookLike->find('first', $optionsfblike);

            if (empty($fblikes)) {
                $data['user_id'] = $sessionbuzzy->User->id;
                $data['card_number'] = '';
                $data['first_name'] = $sessionbuzzy->User->first_name;
                $data['last_name'] = $sessionbuzzy->User->last_name;
                if (!empty($Promotions)) {
                    $data['promotion_id'] = $Promotions['Promotion']['id'];
                    $data['amount'] = $Promotions['Promotion']['value'];
                } else {
                    $data['amount'] = 100;
                }
                $getval = $this->Api->getPatientLevelForAcceleratedReward($_POST['clinic_id'], $sessionbuzzy->User->id);
                $data['amount'] = $data['amount'] * $getval;
                $data['activity_type'] = 'N';
                $data['authorization'] = 'facebook point allocation';
                $data['clinic_id'] = $_POST['clinic_id'];
                $data['date'] = date('Y-m-d H:i:s');
                $data['status'] = 'New';
                $data['is_buzzydoc'] = 1;
                $this->Transaction->create();

                if ($this->Transaction->save($data)) {
                    $getfirstTransaction = $this->Api->get_firsttransaction($sessionbuzzy->User->id, $_POST['clinic_id']);
                    if ($getfirstTransaction == 1 && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {
                        $template_array = $this->Api->get_template(39);
                        $link1 = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                        $link = str_replace('[points]', $data['amount'], $link1);
                        $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link);
                        $Email = new CakeEmail(MAILTYPE);

                        $Email->from(array(
                            SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                        ));
                        $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                        $Email->to($sessionbuzzy->User->email);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array(
                            'msg' => $link2
                        ));
                        $Email->send();
                    }
                    $options2['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $_POST['clinic_id'], 'Notification.earn_points' => 1);
                    $Notifications = $this->Notification->find('first', $options2);
                    if (!empty($Notifications) && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {

                        $template_array = $this->Api->get_template(1);
                        $link = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                        $link1 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link);
                        $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link1);
                        $link3 = str_replace('[points]', $data['amount'], $link2);
                        $Email = new CakeEmail(MAILTYPE);

                        $Email->from(array(
                            SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                        ));
                        $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                        $Email->to($sessionbuzzy->User->email);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array(
                            'msg' => $link3,
                            'Unsubscribe' => $buzzylogin
                        ));
                        $Email->send();
                    }

                    $ldata = array(
                        'clinic_id' => $_POST['clinic_id'],
                        'user_id' => $sessionbuzzy->User->id,
                        'like_status' => 1,
                        'facebook_email' => ''
                    );
                    $this->FacebookLike->create();

                    $this->FacebookLike->save($ldata);

                    $totalpoint = $sessionbuzzy->User->points + $data['amount'];
                    $this->User->query("UPDATE `users` SET `points` = '" . $totalpoint . "' WHERE `id` =" . $sessionbuzzy->User->id);
                    $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $sessionbuzzy->User->id . '.json'));
                    if ($userdetails->userdetail->success == 1) {
                        $this->Session->write('userdetail', $userdetails->userdetail->data);
                    } else {
                        $this->Session->delete('userdetail');
                        return $this->redirect('/login/');
                    }

                    $data = array(
                        'success' => 1,
                        'data' => $data['amount']
                    );
                    echo json_encode($data);
                    exit();
                }
            } else {
                $data = array(
                    'success' => 0,
                    'data' => $data['amount']
                );
                echo json_encode($data);
                exit();
            }
        }
        exit;
    }

    /**
     * Fetch clinic and products/services belongs to user
     * @param int $userId
     * @return array: Clinic Display Names
     */
    public function getUserProductServices($userId) {
        $userClinics = $response = array();
        if ($userId) {
            $userClinics = $this->Clinic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.clinic_id = Clinic.id'
                        )
                    ),
                ),
                'conditions' => array(
                    'clinic_users.user_id' => $userId, 'Clinic.is_buzzydoc' => 1),
                'fields' => array('Clinic.display_name', 'Clinic.id')
            ));
            if ($userClinics) {
                $userClinics = array_column($userClinics, 'Clinic');
                foreach ($userClinics as $val) {
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
                            'product_services.clinic_id' => $val['id'], 'product_services.status' => 1, 'product_services.type !=' => 3, 'AccessStaff.product_service' => 1),
                        'fields' => array('product_services.points', 'product_services.title', 'product_services.id', 'product_services.from_us', 'product_services.clinic_id'),
                        'order' => array('product_services.points asc')
                    ));

                    if ($userProducts) {
                        $userProducts = array_column($userProducts, 'product_services');
                        $response[$val['display_name']] = $userProducts;
                    }
                }
            }
        }
        return $response;
    }

    /**
     * Getting the points details from all pratice for patient.
     * @param type $sessionbuzzy
     * @return type
     */
    public function getPointsDetails($sessionbuzzy) {
        $getglbpoint = $this->Transaction->find('all', array(
            'conditions' => array(
                'Transaction.user_id' => $sessionbuzzy->User->id,
                'Transaction.is_buzzydoc' => 1,
                'Transaction.clinic_id !=' => 0
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
        return $perclinicbuzzpnt;
    }

    /**
     * Fetch promotion belongs to user clinics
     *
     * @param int $userId
     * @return array: Clinic Display Names
     */
    public function getUserPromotion($userId) {
        $userClinics = self::_getUserClinics($userId);
        $promotions = array();
        if ($userClinics) {
            $promotions = self::_getUserPromotions($userClinics);
        }
        return $promotions;
    }

    /**
     * Chnage profile picture for patient account.
     */
    public function saveuserprofileimage() {
        $this->layout = "";
        $success = array('success' => 0);
        if ($this->request->is('post')) {
            if (isset($this->request->data['id']) && isset($this->request->data['profile_img_url'])) {
                $img_url = explode('.net/', $this->request->data['profile_img_url']);
                $success = array('success' => 1);
                $this->User->save(array('id' => $this->request->data['id'], 'profile_img_url' => $img_url[1]));
            }
        }
        echo json_encode($success);
        exit;
    }

    /**
     * Getting the all notification setting for patient.
     */
    public function settings() {
        $this->layout = "buzzydocinner";

        $sessionbuzzy = $this->Session->read('userdetail');
        $optionsCli['conditions'] = array('ClinicUser.user_id' => $sessionbuzzy->User->id);
        $UsersClinic = $this->ClinicUser->find('all', $optionsCli);
        $userallclinic = array();
        foreach ($UsersClinic as $uclinic) {
            $userallclinic[] = $uclinic['ClinicUser']['clinic_id'];
        }
        $this->Session->write('usercheck.usersClinic', $userallclinic);
        foreach ($userallclinic as $uac) {
            $optionsfind['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $uac);
            $Notificationfind = $this->Notification->find('first', $optionsfind);
            if (empty($Notificationfind)) {
                $notification_array['Notification'] = array('reward_challenges' => 0, 'order_status' => 0, 'earn_points' => 0, 'points_expire' => 0, 'user_id' => $sessionbuzzy->User->id, 'clinic_id' => $uac);
                $this->Notification->create();
                $this->Notification->save($notification_array);
            }
        }
        $checknotification = array('reward_challenges' => 1, 'order_status' => 1, 'earn_points' => 1, 'points_expire' => 1);
        foreach ($userallclinic as $uac) {
            $options2['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $uac);
            $Notifications = $this->Notification->find('first', $options2);
            if ($Notifications['Notification']['reward_challenges'] == 0) {
                $checknotification['reward_challenges'] = 0;
            }
            if ($Notifications['Notification']['order_status'] == 0) {
                $checknotification['order_status'] = 0;
            }
            if ($Notifications['Notification']['earn_points'] == 0) {
                $checknotification['earn_points'] = 0;
            }
            if ($Notifications['Notification']['points_expire'] == 0) {
                $checknotification['points_expire'] = 0;
            }
        }
        if (empty($userallclinic)) {
            $this->set('HaveNotifications', 0);
        } else {
            $this->set('HaveNotifications', 1);
        }
        $this->set('Notifications', $checknotification);
        $Refers = $this->Refer->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinic',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinic.id = Refer.clinic_id'
                    )
                )),
            'conditions' => array(
                'Refer.clinic_id' => $userallclinic, 'Refer.user_id' => $sessionbuzzy->User->id, 'Refer.email !=' => ''
            ),
            'fields' => array('Refer.*', 'Clinic.display_name', 'Clinic.api_user')
        ));

        $this->set('Refers', $Refers);
        $Documents = $this->Document->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinic',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinic.id = Document.clinic_id'
                    )
                )),
            'conditions' => array(
                'Document.clinic_id' => $userallclinic
            ),
            'fields' => array('Document.*', 'Clinic.display_name', 'Clinic.api_user')
        ));
        $this->set('Documents', $Documents);
        $Patients_getchild = $this->User->find('all', array(
            'conditions' => array(
                'User.email' => $sessionbuzzy->User->email,
                'User.id !=' => $sessionbuzzy->User->id
            )
        ));
        $date11_chd = $sessionbuzzy->User->custom_date;
        $date21_chd = date('Y-m-d');
        $diff_chd1 = abs(strtotime($date21_chd) - strtotime($date11_chd));
        $years_chd1 = floor($diff_chd1 / (365 * 60 * 60 * 24));
        $child_detail = array();
        if (count($Patients_getchild) > 0 && $years_chd1 > 18) {
            $cnt = 0;
            foreach ($Patients_getchild as $child) {
                $date1_chd = $child['User']['custom_date'];
                $date2_chd = date('Y-m-d');
                $diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
                $years_chd = floor($diff_chd / (365 * 60 * 60 * 24));
                if ($years_chd < 18) {
                    $child_detail[] = $child;
                    $cnt++;
                    $this->Session->write('usercheck.is_parent', 1);
                }
            }
        }
        $this->set('ChildDetails', $child_detail);
        $sessionbuzzycheck = $this->Session->read('usercheck');
        if (isset($sessionbuzzycheck['is_parent']) && $sessionbuzzycheck['is_parent'] == 0 && $sessionbuzzycheck['parent_id'] != '') {
            $child_getparent = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $sessionbuzzycheck['parent_id']
                )
            ));
            $parent_detail = array();
            if (count($child_getparent) > 0) {
                $parent_detail[] = $child_getparent;
                $this->Session->write('usercheck.is_parent', 0);
            }
            $this->set('ParentDetails', $parent_detail);
        }
        $cliniclist = array();
        $i = 0;
        $ref = 0;
        foreach ($UsersClinic as $clnic) {
            $optionscl['conditions'] = array('Clinic.id' => $clnic['ClinicUser']['clinic_id']);
            $Clinic = $this->Clinic->find('first', $optionscl);
            $getredeemacc = $this->AccessStaff->getAccessForClinic($clnic['ClinicUser']['clinic_id']);
            if ($getredeemacc['AccessStaff']['refer'] == 1) {
                $cliniclist[$ref]['clinic_id'] = $Clinic['Clinic']['id'];
                if ($Clinic['Clinic']['display_name'] == '') {
                    $cliniclist[$ref]['clinic_name'] = $Clinic['Clinic']['display_name'];
                } else {
                    $cliniclist[$ref]['clinic_name'] = $Clinic['Clinic']['api_user'];
                }
                $ref++;
            }
            $i++;
        }
        $this->set('ClinicList', $cliniclist);
        foreach ($UsersClinic as $clnic) {
            $optionscl['conditions'] = array('Clinic.id' => $clnic['ClinicUser']['clinic_id']);
            $Clinic = $this->Clinic->find('first', $optionscl);
            $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $Clinic['Clinic']['industry_type'])));
            $leads = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $Clinic['Clinic']['industry_type'])));

            $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
            if ($ref_msg == '') {
                $rmsg = array();
            } else {
                $rmsg = $ref_msg;
            }
            $this->set('refer_msg', $rmsg);
            $this->set('leads', $leads);
            $this->set('defaultclinic', $clnic['ClinicUser']['clinic_id']);
            $this->set('industry_id', $ind['IndustryType']['id']);
            $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $Clinic['Clinic']['id'], 'AdminSetting.setting_type' => 'leadpoints')));
            $this->set('admin_settings', $admin_set);
            break;
        }
    }

    /**
     * Getting the lead setting for pratice.
     */
    public function getClinicLead() {

        $optionscl['conditions'] = array('Clinic.id' => $_POST['clinic_id']);
        $Clinic = $this->Clinic->find('first', $optionscl);
        $ind = $this->IndustryType->find('first', array('conditions' => array('IndustryType.id' => $Clinic['Clinic']['industry_type'])));
        $leads = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $Clinic['Clinic']['industry_type'])));
        $ref_msg = json_decode($ind['IndustryType']['reffralmessages']);
        if ($ref_msg == '') {
            $rmsg = array();
        } else {
            $rmsg = $ref_msg;
        }
        $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $Clinic['Clinic']['id'], 'AdminSetting.setting_type' => 'leadpoints')));
        $settings = array();
        if (!empty($admin_set)) {
            if ($admin_set['AdminSetting']['setting_data'] != '') {
                $settings = json_decode($admin_set['AdminSetting']['setting_data']);
            }
        }
        $leadsetting = '';
        foreach ($leads as $ld) {
            $point1 = '';
            foreach ($settings as $set => $setval) {

                if ($set == $ld['LeadLevel']['id']) {
                    $point1 = $setval;
                }
            }
            $leadsetting .='<tr>
                                    <td class="center col-md-6">' . $ld['LeadLevel']['leadname'] . '</td>
                                    <td class="center col-md-6">';
            if ($point1 != '') {

                $leadsetting .= $point1;
            } else {
                $leadsetting .=$ld['LeadLevel']['leadpoints'];
            }
            $leadsetting .=' points</td>';

            $leadsetting .='</tr>';
        }

        $recomm = '';
        if ($rmsg->cnt > 1) {

            for ($k = 1; $k <= $rmsg->cnt; $k++) {
                $fname = 'reffralmessage' . $k;

                if ($k == 1) {

                    $recomm .='<label class="col-md-2 checkbox-heading">Quick Recommendations :</label>';
                } else {
                    $recomm .='<label class="col-md-2 checkbox-heading">&nbsp;</label>';
                }
                $recomm .=' <div class="col-md-10"><div class="radio clearfix">';
                if ($k == 1) {
                    $recomm .='<div class="co-md-12"><label ><input class="ace" type="radio" id="msg" name="msg" checked="checked" onclick="setmsg(' . $k . ');"><span class="lbl">' . $rmsg->$fname . '</span></label></div>';
                } else {
                    $recomm .='<div class="co-md-12"><label ><input class="ace" type="radio" id="msg" name="msg" onclick="setmsg(' . $k . ');"><span class="lbl">' . $rmsg->$fname . '</span></label></div>';
                }

                $recomm .='</div></div>';
            }
        }

        $data = array('leadsplan' => $leadsetting, 'indty' => $ind['IndustryType']['id'], 'ref_clinic_id' => $_POST['clinic_id'], 'message' => $rmsg->reffralmessage1, 'defaultmsg' => $recomm);

        echo json_encode($data);

        exit;
    }

    /**
     * Child account login for adult patient.
     * @return type
     */
    public function getmultilogin() {
        $sessionbuzzy = $this->Session->read('userdetail');
        $sessionbuzzycheck = $this->Session->read('usercheck');
        if ($this->request->data['parent_login'] == 1) {
            $this->Session->write('usercheck.is_parent', 0);
            $this->Session->write('usercheck.parent_id', $this->request->data['parent_id']);
            return $this->redirect(Buzzy_Name . 'buzzydoc/login/' . base64_encode($this->request->data['child_id']));
        } else {
            $this->Session->write('usercheck.parent_id', '');
            $this->Session->write('usercheck.is_parent', 1);
            return $this->redirect(Buzzy_Name . 'buzzydoc/login/' . base64_encode($this->request->data['parent_id']));
        }

        exit;
    }

    /**
     * Change the notification setting for patient.
     */
    public function setNotification() {
        $sessionbuzzy = $this->Session->read('userdetail');
        $sessionbuzzycheck = $this->Session->read('usercheck');
        if (isset($_POST['reward_challenges']) && $_POST['reward_challenges'] == 1) {
            $reward_challenges = 1;
        } else {
            $reward_challenges = 0;
        }
        if (isset($_POST['order_status']) && $_POST['order_status'] == 1) {
            $order_status = 1;
        } else {
            $order_status = 0;
        }
        if (isset($_POST['earn_points']) && $_POST['earn_points'] == 1) {
            $earn_points = 1;
        } else {
            $earn_points = 0;
        }
        if (isset($_POST['points_expire']) && $_POST['points_expire'] == 1) {
            $points_expire = 1;
        } else {
            $points_expire = 0;
        }
        foreach ($sessionbuzzycheck['usersClinic'] as $clinic) {
            $options2['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $clinic);
            $Notifications = $this->Notification->find('first', $options2);

            if (empty($Notifications)) {
                $notification_array['Notification'] = array('reward_challenges' => $reward_challenges, 'order_status' => $order_status, 'earn_points' => $earn_points, 'points_expire' => $points_expire, 'user_id' => $sessionbuzzy->User->id, 'clinic_id' => $clinic);

                $this->Notification->create();
                $this->Notification->save($notification_array);
            } else {
                $notification_array['Notification'] = array('id' => $Notifications['Notification']['id'], 'reward_challenges' => $reward_challenges, 'order_status' => $order_status, 'earn_points' => $earn_points, 'points_expire' => $points_expire, 'user_id' => $sessionbuzzy->User->id, 'clinic_id' => $clinic);

                $this->Notification->save($notification_array);
            }
        }

        echo 1;
        exit;
    }

    /**
     * Send refeeral email to other user.
     */
    public function sendReferral() {

        $sessionbuzzy = $this->Session->read('userdetail');
        $options2['conditions'] = array('ClinicUser.user_id' => $sessionbuzzy->User->id, 'ClinicUser.clinic_id' => $_POST['clinic_id']);
        $ClinicUser = $this->ClinicUser->find('first', $options2);
        if (!empty($ClinicUser)) {
            $Refers_check['conditions'] = array('Refer.email' => $_POST['email'], 'Refer.user_id' => $sessionbuzzy->User->id, 'Refer.clinic_id' => $_POST['clinic_id']);
            $ReferCheck = $this->Refer->find('first', $Refers_check);
            if (empty($ReferCheck)) {
                $Refers_array['Refer'] = array('card_number' => $ClinicUser['ClinicUser']['card_number'], 'first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'email' => $_POST['email'], 'message' => $_POST['message'], 'user_id' => $sessionbuzzy->User->id, 'clinic_id' => $_POST['clinic_id'], 'status' => 'Pending', 'refdate' => date('Y-m-d H:i:s'));

                $this->Refer->create();
                $this->Refer->save($Refers_array);
                $ref_id = $this->Refer->getLastInsertId();
                $template_array_red = $this->Api->save_notification($Refers_array['Refer'], 4, $ref_id);
                $refpromotion = $this->Refpromotion->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_promotions',
                            'alias' => 'ClinicPromotion',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicPromotion.promotion_id = Refpromotion.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicPromotion.clinic_id' => $_POST['clinic_id']
                    )
                ));
                $template_array = $this->Api->get_template(9);


                $Email = new CakeEmail(MAILTYPE);
                if (empty($sessionbuzzy->User->email)) {
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                } else {
                    $Email->from(array($sessionbuzzy->User->email => 'BuzzyDoc'));
                }
                $Email->to($_POST['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $promotion = '<br>';
                if (!empty($refpromotion)) {
                    foreach ($refpromotion as $refp) {
                        $promotion.= $refp['Refpromotion']['promotion_area'] . '<br>';
                    }
                }
                $link = str_replace('[accept_link]', "<a href='" . rtrim($Clinic['Clinic']['patient_url'], '/') . "/rewards/lead/" . base64_encode($ref_id) . "' style='background: none repeat scroll 0 0 #2FB888;color: #FFFFFF;display: block;margin: 10px 0 0;padding: 10px;text-decoration: none;width: 42%;'>SURE I'LL ACCEPT THIS REFERRAL!</a>" . $promotion, $template_array['content']);
                $link1 = str_replace('[description]', $_POST['message'], $link);
                $link2 = str_replace('[username]', $_POST['first_name'], $link1);
                $link3 = str_replace('[clinic_name]', $Clinic['Clinic']['api_user'], $link2);
                $optionscl['conditions'] = array('Clinic.id' => $_POST['clinic_id']);
                $Clinic = $this->Clinic->find('first', $optionscl);
                $Email->viewVars(array('msg' => $link3
                ));
                $Email->send();

                $data = array('success' => 1);
            } else {
                $data = array('success' => 2);
            }
        } else {
            $data = array('success' => 0);
        }
        echo json_encode($data);
        exit;
    }

    /**
     * Resend referral email to user.
     */
    public function resendrefer() {

        $this->layout = "";
        $sessionbuzzy = $this->Session->read('userdetail');
        $refer = $this->Refer->find('first', array('conditions' => array('Refer.id' => $_POST['ref_id'])));

        if (!empty($refer)) {
            $optionscl['conditions'] = array('Clinic.id' => $refer['Refer']['clinic_id']);
            $Clinic = $this->Clinic->find('first', $optionscl);
            $Email = new CakeEmail(MAILTYPE);
            if (empty($sessionbuzzy->User->email)) {
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
            } else {
                $Email->from(array($sessionbuzzy->User->email => 'BuzzyDoc'));
            }
            $template_array = $this->Api->get_template(9);
            $link = str_replace('[accept_link]', "<a href='" . rtrim($Clinic['Clinic']['patient_url'], '/') . "/rewards/lead/" . base64_encode($refer['Refer']['id']) . "' style='background: none repeat scroll 0 0 #2FB888;color: #FFFFFF;display: block;margin: 10px 0 0;padding: 10px;text-decoration: none;width: 42%;'>SURE I'LL ACCEPT THIS REFERRAL!</a>", $template_array['content']);
            $link1 = str_replace('[description]', $refer['Refer']['message'], $link);
            $link2 = str_replace('[username]', $refer['Refer']['first_name'], $link1);
            $link3 = str_replace('[clinic_name]', $Clinic['Clinic']['api_user'], $link2);
            $Email->to($refer['Refer']['email']);
            $Email->subject($template_array['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email->viewVars(array('msg' => $link3
            ));
            $Email->send();
            echo 1;
        } else {
            echo 0;
        }
        die;
    }

    /**
     * Getting the list of all pratice belong to patient.
     * @param type $userId
     * @return type
     */
    protected function _getUserClinics($userId) {
        $userClinics = $response = array();
        if ($userId) {
            $userClinics = $this->Clinic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.clinic_id = Clinic.id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.user_id' => $userId
                ),
                'group' => array(
                    'Clinic.id'
                ),
                'fields' => array(
                    'Clinic.display_name',
                    'Clinic.id'
                )
            ));
        }
        if ($userClinics) {
            $userClinics = array_column($userClinics, 'Clinic');
        }
        return $userClinics;
    }

    /**
     * Getting the list of promotion belong the pratice by patient liked.
     * @param type $userClinics
     * @return type
     */
    protected function _getUserPromotions($userClinics) {
        foreach ($userClinics as $val) {
            $clinicPromotions = $this->Promotion->find('all', array('conditions' => array(
                    'Promotion.clinic_id' => $val['id'], 'Promotion.default' => 1, 'Promotion.public' => 1, 'Promotion.is_lite' => 0, 'Promotion.is_global' => 0),
                'fields' => array('Promotion.description', 'Promotion.display_name'),
                'order' => array('Promotion.sort' => 'asc')
            ));
            $clinicCustomPromotions = $this->Promotion->find('all', array('conditions' => array(
                    'Promotion.clinic_id' => $val['id'], 'Promotion.default' => 0, 'Promotion.public' => 1, 'Promotion.is_lite' => 0, 'Promotion.is_global' => 0),
                'fields' => array('Promotion.description', 'Promotion.display_name'),
                'order' => array('Promotion.sort' => 'asc')
            ));

            $clinicLevelUpPromotions = $this->LevelupPromotion->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'access_staffs',
                        'alias' => 'AccessStaff',
                        'type' => 'INNER',
                        'conditions' => array(
                            'AccessStaff.clinic_id = LevelupPromotion.clinic_id'
                        )
                    )
                ),
                'conditions' => array(
                    'LevelupPromotion.clinic_id' => $val['id'], 'LevelupPromotion.public' => 1, 'AccessStaff.levelup' => 1),
                'fields' => array('LevelupPromotion.description')
            ));
            $clinicPromotions1 = array();
            foreach ($clinicPromotions as $clpr) {
                if ($clpr['Promotion']['display_name'] != '') {
                    $clinicPromotions1[] = $clpr['Promotion']['display_name'];
                } else {
                    $clinicPromotions1[] = $clpr['Promotion']['description'];
                }
            }
            $clinicCustomPromotions1 = array();
            foreach ($clinicCustomPromotions as $clpr1) {
                if ($clpr1['Promotion']['display_name'] != '') {
                    $clinicCustomPromotions1[] = $clpr1['Promotion']['display_name'];
                } else {
                    $clinicCustomPromotions1[] = $clpr1['Promotion']['description'];
                }
            }
            if ($clinicLevelUpPromotions) {
                $clinicLevelUpPromotions = array_column($clinicLevelUpPromotions, 'LevelupPromotion');
                $clinicLevelUpPromotions = array_column($clinicLevelUpPromotions, 'description');
            } else {
                $clinicLevelUpPromotions = array();
            }
            $promotions[$val['display_name']] = array('promotions' => $clinicPromotions1, 'levelUpPromotions' => $clinicLevelUpPromotions, 'custompromotions' => $clinicCustomPromotions1);
        }
        return $promotions;
    }

    /**
     * Getting the levelup promotion for pratice.
     */
    public function getajaxuserpromotions() {
        $this->layout = "";
        $promotions = $response = array();
        if (!empty($_POST) && isset($_POST['clinic_id'])) {
            $promotions = self::_getUserPromotions(array(array('id' => $_POST['clinic_id'], 'display_name' => $_POST['display_name'])));
        }

        if ($promotions && isset($promotions[$_POST['display_name']])) {
            $promotions = $promotions[$_POST['display_name']];
            foreach ($promotions['promotions'] as $val) {
                $response['promotions'][] = '<li>' . $val . '</li>';
            }
            foreach ($promotions['levelUpPromotions'] as $val) {
                $response['levelUpPromotions'][] = '<li>' . $val . '</li>';
            }
            foreach ($promotions['custompromotions'] as $val) {
                $response['cuspromotions'][] = '<li>' . $val . '</li>';
            }
            $response = array('promotions' => implode('', $response['promotions']), 'levelUpPromotions' => implode('', $response['levelUpPromotions']), 'cusPromotions' => implode('', $response['cuspromotions']));
        }
        echo json_encode($response);
        die;
    }

    /**
     * Facebook point allocation from pratice page.
     * @return type
     */
    public function facebookpointallocationnew() {
        $this->layout = "";
        $data = array();
        $sessionbuzzy = $this->Session->read('userdetail');
        $fbid = explode('//', rtrim($_POST['clinic_id'], '/'));
        $fbdeturl = end(explode('/', $fbid[1]));
        $options_clinic['conditions'] = array(
            'Clinic.facebook_url LIKE' => '%' . $fbdeturl . '%'
        );
        $options_clinic['fields'] = array(
            'Clinic.id'
        );
        $clinic = $this->Clinic->find('first', $options_clinic);
        if (!empty($clinic)) {
            $fbliked = 0;
            if (!empty($sessionbuzzy->Fblikes)) {
                foreach ($sessionbuzzy->Fblikes as $flike) {
                    if ($flike->FacebookLike->clinic_id == $clinic['Clinic']['id'] && $flike->FacebookLike->like_status == 1) {
                        $fbliked = 1;
                    }
                }
            }
            $config = array(
                'appId' => Facebook_APP_ID,
                'secret' => Facebook_Secret_Key,
                'allowSignedRequest' => false
            );
            $facebook = new Facebook($config);
            $user = $facebook->getUser();

            if ($fbliked == 0) {

                $options_pro['fields'] = array(
                    'Promotion.id',
                    'Promotion.value',
                    'Promotion.description',
                    'Promotion.operand'
                );
                $options_pro['conditions'] = array(
                    'Promotion.clinic_id' => $clinic['Clinic']['id'],
                    'Promotion.description like' => '%Facebook Like%'
                );

                $Promotions = $this->Promotion->find('first', $options_pro);
                $optionsfblike['conditions'] = array(
                    'FacebookLike.user_id' => $sessionbuzzy->User->id,
                    'FacebookLike.clinic_id' => $clinic['Clinic']['id'],
                    'FacebookLike.like_status' => 1
                );
                $fblikes = $this->FacebookLike->find('first', $optionsfblike);

                if (empty($fblikes)) {
                    $data['user_id'] = $sessionbuzzy->User->id;
                    $data['card_number'] = '';
                    $data['first_name'] = $sessionbuzzy->User->first_name;
                    $data['last_name'] = $sessionbuzzy->User->last_name;
                    $data['activity_type'] = 'N';
                    if (!empty($Promotions)) {
                        $data['promotion_id'] = $Promotions['Promotion']['id'];
                        $data['amount'] = $Promotions['Promotion']['value'];
                    } else {
                        $data['amount'] = 100;
                    }
                    $getval = $this->Api->getPatientLevelForAcceleratedReward($clinic['Clinic']['id'], $sessionbuzzy->User->id);
                    $data['amount'] = $data['amount'] * $getval;
                    $data['authorization'] = 'facebook point allocation';
                    $data['clinic_id'] = $clinic['Clinic']['id'];
                    $data['date'] = date('Y-m-d H:i:s');
                    $data['status'] = 'New';
                    $data['is_buzzydoc'] = 1;
                    $this->Transaction->create();


                    if ($this->Transaction->save($data)) {
                        $getfirstTransaction = $this->Api->get_firsttransaction($sessionbuzzy->User->id, $clinic['Clinic']['id']);
                        if ($getfirstTransaction == 1 && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {
                            $template_array = $this->Api->get_template(39);
                            $link1 = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                            $link = str_replace('[points]', $data['amount'], $link1);
                            $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link);
                            $Email = new CakeEmail(MAILTYPE);

                            $Email->from(array(
                                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                            ));

                            $Email->to($sessionbuzzy->User->email);
                            $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array(
                                'msg' => $link2
                            ));
                            $Email->send();
                        }
                        $options2['conditions'] = array('Notification.user_id' => $sessionbuzzy->User->id, 'Notification.clinic_id' => $clinic['Clinic']['id'], 'Notification.earn_points' => 1);
                        $Notifications = $this->Notification->find('first', $options2);
                        if (!empty($Notifications) && $sessionbuzzy->User->email != '' && $data['amount'] > 0) {

                            $template_array = $this->Api->get_template(1);
                            $link = str_replace('[username]', $sessionbuzzy->User->first_name, $template_array['content']);
                            $link1 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link);
                            $link2 = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $link1);
                            $link3 = str_replace('[points]', $data['amount'], $link2);
                            $Email = new CakeEmail(MAILTYPE);

                            $Email->from(array(
                                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                            ));

                            $Email->to($sessionbuzzy->User->email);
                            $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($sessionbuzzy->User->id) . '/Unsubscribe';
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array(
                                'msg' => $link3
                            ));
                            $Email->send();
                        }


                        $ldata = array(
                            'clinic_id' => $clinic['Clinic']['id'],
                            'user_id' => $sessionbuzzy->User->id,
                            'like_status' => 1,
                            'facebook_email' => ''
                        );
                        $this->FacebookLike->create();

                        $this->FacebookLike->save($ldata);

                        $totalpoint = $sessionbuzzy->User->points + $data['amount'];
                        $this->User->query("UPDATE `users` SET `points` = '" . $totalpoint . "' WHERE `id` =" . $sessionbuzzy->User->id);
                        $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $sessionbuzzy->User->id . '.json'));
                        if ($userdetails->userdetail->success == 1) {
                            $this->Session->write('userdetail', $userdetails->userdetail->data);
                        } else {
                            $this->Session->delete('userdetail');
                            return $this->redirect('/login/');
                        }

                        $data = array(
                            'success' => 1,
                            'data' => $data['amount']
                        );
                        echo json_encode($data);
                        exit();
                    }
                } else {
                    $data = array(
                        'success' => 0
                    );
                    echo json_encode($data);
                    exit();
                }
            }
        } else {
            $data = array(
                'success' => 0
            );
            echo json_encode($data);
            exit();
        }
        exit;
    }

    /**
     * Getting the list of pratice who have self registration On.
     */
    public function getSearchPractice() {
        $this->layout = "";
        $searchresult = $this->Clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = Clinic.id'
                    )
                )
            ),
            'conditions' => array(
                'Clinic.industry_type' => $_POST['type'],
                'Clinic.status' => 1,
                'AccessStaff.self_registration' => 1
            ),
            'fields' => array('Clinic.id', 'Clinic.api_user', 'Clinic.display_name'),
            'order' => array('Clinic.display_name asc', 'Clinic.api_user asc')
        ));

        $string = '<option value="">Select Practice Name [*]</option>';

        foreach ($searchresult as $clinicresult) {

            if ($clinicresult['Clinic']['display_name'] == '') {
                $clinicname = $clinicresult['Clinic']['api_user'];
            } else {
                $clinicname = $clinicresult['Clinic']['display_name'];
            }
            $string .='<option value="' . $clinicresult['Clinic']['id'] . '">' . $clinicname . '</option>';
        }


        echo $string;
        die;
    }

    /**
     * Getting the all free card number for pratice.
     */
    public function getPractice() {
        $this->layout = "";
        $searchresult = $this->Clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = Clinic.id'
                    )
                )
            ),
            'conditions' => array(
                'Clinic.id' => $_POST['practice'],
                'Clinic.status' => 1,
                'AccessStaff.self_registration' => 1,
                'AccessStaff.auto_assign' => 1
            )
        ));
        if (!empty($searchresult)) {
            $cardnumber = $this->CardNumber->find('first', array(
                'conditions' => array(
                    'CardNumber.clinic_id' => $_POST['practice'],
                    'CardNumber.status' => 1
                ),
                'fields' => array('CardNumber.card_number'),
                'order' => array('CardNumber.card_number asc')
            ));
            echo $cardnumber['CardNumber']['card_number'];
        } else {
            echo '';
        }
        die;
    }

    /**
     * Function to check duplicate user in our system.
     */
    public function checkuserexist() {
        $this->layout = "";
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
                    'clinic_users.clinic_id' => $_POST['clinic_id'],
                    'User.email' => $_POST['email'],
                    'User.parents_email' => $_POST['parents_email'],
                    'User.custom_date' => $_POST['dob'],
                    'User.blocked !=' => 1
                ),
                'fields' => array('User.id')
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
                    'clinic_users.clinic_id' => $_POST['clinic_id'],
                    'User.email' => $_POST['email'],
                    'User.custom_date <=' => $date13age,
                    'User.blocked !=' => 1
                ),
                'fields' => array('User.id')
            ));
        }

        if (empty($users_field_check)) {
            if (isset($_POST['parents_email'])) {
                $users_field = $this->User->find('all', array(
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
                        'clinic_users.clinic_id !=' => $_POST['clinic_id'],
                        'User.email' => $_POST['email'],
                        'User.parents_email' => $_POST['parents_email'],
                        'User.custom_date' => $_POST['dob'],
                        'User.blocked !=' => 1
                    ),
                    'fields' => array('User.email', 'User.id', 'clinic_users.clinic_id')
                ));
            } else {
                $date13age = date("Y-m-d", strtotime("-18 year"));
                $users_field = $this->User->find('all', array(
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
                        'clinic_users.clinic_id !=' => $_POST['clinic_id'],
                        'User.email' => $_POST['email'],
                        'User.custom_date <=' => $date13age,
                        'User.blocked !=' => 1
                    ),
                    'fields' => array('User.email', 'User.id', 'clinic_users.clinic_id')
                ));
            }
            if (count($users_field) > 0) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
        exit;
    }

    /**
     * Getting the pratice list by industry.
     */
    public function selectpractice() {
        $this->layout = "";
        $industryType = $this->IndustryType->find('all', array(
            'order' => array('IndustryType.name asc')
        ));
        $this->set('industryType', $industryType);
    }

    public function getQuestionCard() {
        $this->layout = "";
        $searchresult = $this->Clinic->find('first', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'access_staffs',
                    'type' => 'INNER',
                    'conditions' => array(
                        'access_staffs.clinic_id = Clinic.id'
                    )
                )
            ),
            'conditions' => array(
                'access_staffs.auto_assign !=' => 1,
                'access_staffs.self_registration' => 1,
                'Clinic.id' => $_POST['practice']
            ),
            'fields' => array('Clinic.patient_question_mark')
        ));
        $html = '';
        if (!empty($searchresult)) {
            if (isset($searchresult) && $searchresult['Clinic']['patient_question_mark']) {
                $html = '<img height="148" width="182" alt="" src="' . S3Path . $searchresult['Clinic']['patient_question_mark'] . '">';
            } else {
                $html = '<img height="148" width="182" src="' . CDN . 'img/reward_imges/imghover.png" alt="">';
            }
        }
        echo $html;
        die;
    }

    /**
     * rate and review page for patient.
     * @return type
     */
    public function ratereview() {
        $this->layout = "buzzydocratereview";
        $param = base64_decode($this->params['id']);
        $this->set('param', $param);
        $data = explode('-', $param);
        $rateRate = $this->RateReview->find('first', array(
            'conditions' => array(
                'RateReview.clinic_id' => $data[2],
                'RateReview.staff_id' => $data[1],
                'RateReview.user_id' => $data[3],
                'RateReview.identifier' => $data[0]
            )
        ));
        $this->set('alreadyRateReview', 0);

        $clinicDetails = $this->Clinic->find('first', array(
            'conditions' => array(
                'Clinic.id' => $data[2]
            ),
            'fields' => array('Clinic.display_name', 'Clinic.about', 'Clinic.patient_url', 'Clinic.patient_logo_url', 'Clinic.api_user')
        ));
        $this->set('ClinicDetails', $clinicDetails['Clinic']);
        $this->set('ClinicLocation', array());
        if ($data[4] == 0) {
            $this->set('GoogleUrl', '');
            $this->set('YahooUrl', '');
            $this->set('YelpUrl', '');
            $this->set('HealthgradesUrl', '');
        } else {
            $cliniclocation = $this->ClinicLocation->find('first', array(
                'conditions' => array(
                    'ClinicLocation.id' => $data[4]
                )
            ));
            $this->set('ClinicLocation', $cliniclocation);
            if ($cliniclocation['ClinicLocation']['google_business_page_url'] != '') {
                $this->set('GoogleUrl', $cliniclocation['ClinicLocation']['google_business_page_url']);
            }
            if ($cliniclocation['ClinicLocation']['yahoo_business_page_url'] != '') {
                $this->set('YahooUrl', $cliniclocation['ClinicLocation']['yahoo_business_page_url']);
            }
            if ($cliniclocation['ClinicLocation']['yelp_business_page_url'] != '') {
                $this->set('YelpUrl', $cliniclocation['ClinicLocation']['yelp_business_page_url']);
            }
            if ($cliniclocation['ClinicLocation']['healthgrades_business_page_url'] != '') {
                $this->set('HealthgradesUrl', $cliniclocation['ClinicLocation']['healthgrades_business_page_url']);
            }
        }
        $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $data[2], 'Promotion.is_global' => 0, 'Promotion.default' => 2);
        $ratePromotion = $this->Promotion->find('all', $options);
        $rate = array();
        $total_earn=0;
        foreach ($ratePromotion as $prarray) {
            if ($prarray['Promotion']['description'] == 'Rate') {
                $rate['Rate'] = 'Get ' . $prarray['Promotion']['value'] . ' points for rating.';
            }
            if ($prarray['Promotion']['description'] == 'Review') {
                $rate['Review'] = 'Get ' . $prarray['Promotion']['value'] . ' points for posting a review.';
            }
            if ($prarray['Promotion']['description'] == 'Facebook Share') {
                $rate['Facebook Share'] = 'Share your review on Facebook using ' . '#' . $clinicDetails['Clinic']['api_user'] . ' and earn ' . $prarray['Promotion']['value'] . ' points!';
                $rate['Facebook Share1'] = 'If you want to earn ' . $prarray['Promotion']['value'] . ' more points, simply share your review on Facebook using ' . '#' . $clinicDetails['Clinic']['api_user'] . ' now';
                $total_earn=$total_earn+$prarray['Promotion']['value'];
            }
            if ($prarray['Promotion']['description'] == 'Google Share') {
                $rate['Google Share'] = ' Share your review on Google+ and score another ' . $prarray['Promotion']['value'] . ' points!';
                if ($cliniclocation['ClinicLocation']['google_business_page_url'] != '') {
                 $total_earn=$total_earn+$prarray['Promotion']['value'];   
                }
            }
            if ($prarray['Promotion']['description'] == 'Yahoo Share') {
                $rate['Yahoo Share'] = ' Share your review on Yahoo and score another ' . $prarray['Promotion']['value'] . ' points!';
                if ($cliniclocation['ClinicLocation']['yahoo_business_page_url'] != '') {
                 $total_earn=$total_earn+$prarray['Promotion']['value'];   
                }
            }
            if ($prarray['Promotion']['description'] == 'Yelp Share') {
                $rate['Yelp Share'] = ' Share your review on Yelp and score another ' . $prarray['Promotion']['value'] . ' points!';
                if ($cliniclocation['ClinicLocation']['yelp_business_page_url'] != '') {
                 $total_earn=$total_earn+$prarray['Promotion']['value'];   
                }
            }
            if ($prarray['Promotion']['description'] == 'Healthgrades Share') {
                $rate['Healthgrades Share'] = ' Share your review on Healthgrades and score another ' . $prarray['Promotion']['value'] . ' points!';
                if ($cliniclocation['ClinicLocation']['healthgrades_business_page_url'] != '') {
                 $total_earn=$total_earn+$prarray['Promotion']['value'];   
                }
            }
        }
        $this->set('TotalMoreEarn', $total_earn);
        $this->set('RateReview', $rate);
        $posturl = Buzzy_Name . 'clinicratereview/' . $this->params['id'];
        $this->set('shareUrl', $posturl);
        $this->set('facebook', 0);
        $this->set('google', 0);
        $this->set('yahoo', 0);
        $this->set('yelp', 0);
        $this->set('healthgrades', 0);
        if (!empty($rateRate)) {
            $this->set('alreadyRateReview', 1);
            if ($rateRate['RateReview']['facebook_share'] != 1 && $rateRate['RateReview']['rate'] > 2) {
                $this->set('alreadyRateReview', 2);
                $this->set('facebook', 1);
            }
            if ($rateRate['RateReview']['notify_staff'] != 1 && $rateRate['RateReview']['rate'] > 2 && $data[4] != 0 && $cliniclocation['ClinicLocation']['google_business_page_url'] != '') {
                $this->set('alreadyRateReview', 2);
                $this->set('google', 1);
            }
            if ($rateRate['RateReview']['yahoo_notify'] != 1 && $rateRate['RateReview']['rate'] > 2 && $data[4] != 0 && $cliniclocation['ClinicLocation']['yahoo_business_page_url'] != '') {
                $this->set('alreadyRateReview', 2);
                $this->set('yahoo', 1);
            }
            if ($rateRate['RateReview']['yelp_notify'] != 1 && $rateRate['RateReview']['rate'] > 2 && $data[4] != 0 && $cliniclocation['ClinicLocation']['yelp_business_page_url'] != '') {
                $this->set('alreadyRateReview', 2);
                $this->set('yelp', 1);
            }
            if ($rateRate['RateReview']['healthgrades_notify'] != 1 && $rateRate['RateReview']['rate'] > 2 && $data[4] != 0 && $cliniclocation['ClinicLocation']['healthgrades_business_page_url'] != '') {
                $this->set('alreadyRateReview', 2);
                $this->set('healthgrades', 1);
            }
            $this->set('currentRateReview', $rateRate);
        }
        if (count($data) == 5) {
            $this->set('identifier', $this->params['id']);
        } else {
            $this->set('identifier', '');
        }
    }

    /**
     * rate and review page for patient.
     * @return type
     */
    public function clinicratereview() {
        $this->layout = "";
        $param = base64_decode($this->params['id']);
        $data = explode('-', $param);
        $rateRate = $this->RateReview->find('first', array(
            'conditions' => array(
                'RateReview.clinic_id' => $data[2],
                'RateReview.staff_id' => $data[1],
                'RateReview.user_id' => $data[3],
                'RateReview.identifier' => $data[0]
            )
        ));

        $clinicDetails = $this->Clinic->find('first', array(
            'conditions' => array(
                'Clinic.id' => $data[2]
            ),
            'fields' => array('Clinic.*')
        ));
        $this->set('ClinicDetails', $clinicDetails['Clinic']);

        $this->set('currentRateReview', $rateRate['RateReview']);
        $otherrateRate = $this->RateReview->find('all', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = RateReview.user_id'
                    )
                )
            ),
            'conditions' => array(
                'RateReview.clinic_id' => $data[2],
                'RateReview.id !=' => $rateRate['RateReview']['id'],
                'RateReview.user_id >' => 0,
                'RateReview.rate >' => 0
            ),
            'fields' => array('RateReview.*', 'User.*'),
            'order' => array('RateReview.created_on desc')
        ));
        $this->set('otherRateReview', $otherrateRate);
    }

    /**
     * rate and review page for patient.
     * @return type
     */
    public function postRateReview() {
        $this->layout = "";
        $param = base64_decode($_POST['identifer']);
        $data = explode('-', $param);
        $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $data[2], 'Promotion.is_global' => 0, 'Promotion.default' => 2);
        $ratePromotion = $this->Promotion->find('all', $options);
        $rateRate = $this->RateReview->find('first', array(
            'conditions' => array(
                'RateReview.clinic_id' => $data[2],
                'RateReview.staff_id' => $data[1],
                'RateReview.user_id' => $data[3],
                'RateReview.identifier' => $data[0]
            )
        ));
        if (!empty($rateRate)) {
            if (isset($_POST['share']) && $_POST['share'] == 1 && $rateRate['RateReview']['rate'] > 2) {
                $saverate['RateReview'] = array(
                    'id' => $rateRate['RateReview']['id'],
                    'clinic_id' => $data[2],
                    'staff_id' => $data[1],
                    'user_id' => $data[3],
                    'identifier' => $data[0],
                    'facebook_share' => 1
                );
                $this->RateReview->save($saverate);
                $total_val = 0;
                if ($rateRate['RateReview']['facebook_share'] != 1) {
                    $this->addTransactionForRateReview($saverate['RateReview'], $ratePromotion, 3);
                    foreach ($ratePromotion as $prarray) {
                        if ($prarray['Promotion']['description'] == 'Facebook Share') {
                            $total_val = $total_val + $prarray['Promotion']['value'];
                        }
                    }
                    if ($total_val > 0) {
                        $this->notifyPatient($saverate['RateReview'], $total_val);
                    }
                }
                echo 1;
            } else if (isset($_POST['share']) && $_POST['share'] == 2 && $rateRate['RateReview']['rate'] > 2) {

                echo 2;
            } else {
                echo 0;
            }
        } else {
            $saverate['RateReview'] = array(
                'clinic_id' => $data[2],
                'staff_id' => $data[1],
                'user_id' => $data[3],
                'identifier' => $data[0],
                'rate' => $_POST['rate'],
                'review' => $_POST['review'],
                'clinic_location_id' => $data[4],
                'created_on' => date('Y-m-d H:i:s')
            );
            $this->RateReview->create();
            $this->RateReview->save($saverate);
            $total_val = 0;
            if ($_POST['rate'] > 0) {
                $this->addTransactionForRateReview($saverate['RateReview'], $ratePromotion, 1);
                foreach ($ratePromotion as $prarray) {
                    if ($prarray['Promotion']['description'] == 'Rate') {
                        $total_val = $total_val + $prarray['Promotion']['value'];
                    }
                }
            }
            if ($_POST['review'] != '') {
                $this->addTransactionForRateReview($saverate['RateReview'], $ratePromotion, 2);
                foreach ($ratePromotion as $prarray) {
                    if ($prarray['Promotion']['description'] == 'Review') {
                        $total_val = $total_val + $prarray['Promotion']['value'];
                    }
                }
            }
            if ($total_val > 0) {
                $this->notifyPatient($saverate['RateReview'], $total_val);
            }
            echo 1;
        }
        die;
    }

    public function addTransactionForRateReview($rate_array, $promotion_array, $type) {
        $this->layout = "";
        $rate = array();
        foreach ($promotion_array as $prarray) {
            if ($type == 1 && $prarray['Promotion']['description'] == 'Rate') {
                $rate = $prarray;
            }
            if ($type == 2 && $prarray['Promotion']['description'] == 'Review') {
                $rate = $prarray;
            }
            if ($type == 3 && $prarray['Promotion']['description'] == 'Facebook Share') {
                $rate = $prarray;
            }
            if ($type == 4 && $prarray['Promotion']['description'] == 'Google Share') {
                $rate = $prarray;
            }
        }
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
                'User.id' => $rate_array['user_id'],
                'Clinics.id' => $rate_array['clinic_id']
            ),
            'fields' => array(
                'User.*',
                'Clinics.*',
                'clinic_users.*'
            )
        ));
        $transe['Transaction'] = array(
            'user_id' => $rate_array['user_id'],
            'card_number' => $patientclinic['clinic_users']['card_number'],
            'first_name' => $patientclinic['User']['first_name'],
            'last_name' => $patientclinic['User']['last_name'],
            'promotion_id' => $rate['Promotion']['id'],
            'activity_type' => 'N',
            'authorization' => $rate['Promotion']['display_name'],
            'amount' => $rate['Promotion']['value'],
            'clinic_id' => $rate_array['clinic_id'],
            'staff_id' => $rate_array['staff_id'],
            'doctor_id' => 0,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'New',
            'is_buzzydoc' => $patientclinic['Clinics']['is_buzzydoc']
        );
        $this->Transaction->create();
        $this->Transaction->save($transe['Transaction']);

        if ($patientclinic['Clinics']['is_buzzydoc'] == 1) {
            //getting the balance amount after point allocation and update to account.
            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $rate_array['user_id'],
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
                'User.id' => $rate_array['user_id']
            ));
        } else {
            //getting the balance amount after point allocation and update to account for legacy patient.
            $alltrans = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $rate_array['user_id'],
                    'Transaction.clinic_id' => $rate_array['clinic_id'],
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
        return 1;
    }

    public function notifyPatient($rate_array, $total_val) {
        $this->layout = "";
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
                'User.id' => $rate_array['user_id'],
                'Clinics.id' => $rate_array['clinic_id']
            ),
            'fields' => array(
                'User.*',
                'Clinics.*',
                'clinic_users.*'
            )
        ));
        $getfirstTransaction = $this->Api->get_firsttransaction($rate_array['user_id'], $rate_array['clinic_id']);
        //condition to check patient get the point first time if yes then send congrats mail to patient.
        if ($getfirstTransaction == 1 && $total_val > 0) {
            if ($patientclinic['User']['email'] != '') {
                $template_array = $this->Api->get_template(39);
                $link1 = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
                $link = str_replace('[points]', $total_val, $link1);
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
        //Code to check notification setting for patient.
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
                'Users.id' => $rate_array['user_id'],
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
        if ($total_val > 0) {
            foreach ($patients as $pat) {
                $template_array = $this->Api->get_template(1);
                $link = str_replace('[username]', $pat['Users']['first_name'], $template_array['content']);
                $link1 = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
                $link2 = str_replace('[clinic_name]', $pat['Clinics']['api_user'], $link1);
                $link3 = str_replace('[points]', $total_val, $link2);
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
    }

    public function notifyStaff() {
        $this->layout = "";
        $sessionpatient = $this->Session->read('staff');
        $param = base64_decode($_POST['identifer']);
        $data = explode('-', $param);
        $Email = new CakeEmail(MAILTYPE);
        $staff_id = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.id' => $data[1]
            )
        ));
        $user_id = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $data[3]
            )
        ));
        $check_notification = $this->RateReview->find('first', array(
            'conditions' => array(
                'id' => $_POST['rate_id']
            )
        ));
        $socail_site = '';
        $socialcount = 0;
        if ($_POST['google'] == 1 && $check_notification['RateReview']['notify_staff'] == 0) {
            $saverate['RateReview'] = array(
                'id' => $_POST['rate_id'],
                'notify_staff' => 1
            );
            $this->RateReview->save($saverate);
            $socail_site .='google+, ';
            $socialcount++;
        }
        if ($_POST['yahoo'] == 1 && $check_notification['RateReview']['yahoo_notify'] == 0) {
            $saverate['RateReview'] = array(
                'id' => $_POST['rate_id'],
                'yahoo_notify' => 1
            );
            $this->RateReview->save($saverate);
            $socail_site .='yahoo, ';
            $socialcount++;
        }
        if ($_POST['yelp'] == 1 && $check_notification['RateReview']['yelp_notify'] == 0) {
            $saverate['RateReview'] = array(
                'id' => $_POST['rate_id'],
                'yelp_notify' => 1
            );
            $this->RateReview->save($saverate);
            $socail_site .='yelp, ';
            $socialcount++;
        }
        if ($_POST['healthgrades'] == 1 && $check_notification['RateReview']['healthgrades_notify'] == 0) {
            $saverate['RateReview'] = array(
                'id' => $_POST['rate_id'],
                'healthgrades_notify' => 1
            );
            $this->RateReview->save($saverate);
            $socail_site .='healthgrades, ';
            $socialcount++;
        }
        $socail_site = rtrim($socail_site, ", ");
        if ($socialcount > 0) {
            $allreview = $this->RateReview->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User.id = RateReview.user_id'
                        )
                    )),
                'conditions' => array(
                    'RateReview.id' => $_POST['rate_id']
                ),
                'fields' => array('RateReview.id', 'RateReview.clinic_id', 'User.first_name', 'User.last_name', 'RateReview.created_on')
            ));
            $allreview['RateReview']['platform']=$socail_site;
            $template_array_red = $this->Api->save_notification($allreview, 3);
            $template_array = $this->Api->get_template(44);
            $link = str_replace('[staff_name]', $staff_id['Staff']['staff_id'], $template_array['content']);
            $link1 = str_replace('[username]', $user_id['User']['first_name'] . ' ' . $user_id['User']['last_name'], $link);
            $link2 = str_replace('[Identifier]', $data[0], $link1);
            $link3 = str_replace('[social_site]', $socail_site, $link2);

            $Email1 = new CakeEmail(MAILTYPE);

            $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

            $Email1->to($staff_id['Staff']['staff_email']);
            $Email1->subject($template_array['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email1->viewVars(array('msg' => $link3
            ));
            $Email1->send();

            echo 1;
        } else {
            echo 0;
        }
        exit();
    }

    public function getcitystate() {

        $this->layout = "";
        $state = $this->State->find('all');
        $stlist = '<select id="state" name="state" style="width:180px;" onchange="getstate();"><option value="">Select State [*]</option>';
        foreach ($state as $st) {
            $stlist .= '<option value="' . $st['State']['state'] . '" ';
            if ($st['State']['state'] == $_POST['state']) {
                $stlist .= 'selected="selected"';
            }
            $stlist .= '>' . $st['State']['state'] . '</option>';
        }
        $stlist.='</select>';
        $options['joins'] = array(
            array(
                'table' => 'states',
                'alias' => 'State',
                'type' => 'INNER',
                'conditions' => array(
                    'State.state_code = City.state_code'
                )
            )
        );
        $options['conditions'] = array(
            'State.state' => $_POST['state']
        );
        $options['fields'] = array(
            'City.city'
        );
        $options['order'] = array(
            'City.city asc'
        );
        $cityresult = $this->City->find('all', $options);
        $html = '<select id="citydd" style="width:180px;" name="cityDd"><option value="">Select City</option>';
        foreach ($cityresult as $ct) {

            $html .= '<option value="';
            $html .= $ct["City"]["city"];
            $html .= '"';
            if ($_POST['city'] == $ct['City']['city']) {
                $html .= ' selected="selected"';
            }
            $html .='>';
            $html .= $ct["City"]["city"];
            $html .= '</option>';
        }
        $html .= '</select>';
        $data = array(
            'state' => $stlist,
            'city' => $html
        );
        echo json_encode($data);
        exit;
    }

}

?>
