<?php

/**
 * This file for add and delete review for other practices.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for add and delete review for other practices.
 */
class PatientRateReviewController extends AppController {

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
    public $uses = array('Promotion', 'User', 'Clinic', 'RateReview', 'Refer', 'Transaction', 'Doctor', 'TrainingVideo', 'Promotion', 'Notification', 'RateReview', 'ClinicLocation', 'GlobalRedeem', 'ClinicNotification');

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
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);

        if (isset($sessionstaff['clinic_id'])) {
            $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
            $options3['fields'] = array('Clinic.analytic_code', 'Clinic.log_time', 'Clinic.lead_log', 'Clinic.id', 'Clinic.google_review_page_url');
            $ClientCredentials = $this->Clinic->find('first', $options3);

            if (isset($ClientCredentials)) {
                //store analytic code to session
                $this->Session->write('staff.analytic_code', $ClientCredentials['Clinic']['analytic_code']);
                $this->Session->write('staff.google_review_url', $ClientCredentials['Clinic']['google_review_page_url']);
            }
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }

    /**
     * Getting the list off all review added by staff for Practice.
     */
    public function index($id) {
        $sessionstaff = $this->Session->read('staff');
        if ($sessionstaff['staffaccess']['AccessStaff']['rate_review'] == 1) {
            
        } else {
            $this->render('/Elements/access');
        }
        $redecnt = $this->ClinicNotification->query('update clinic_notifications set status="1" where clinic_id=' . $sessionstaff['clinic_id'] . ' and notification_type=2');
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $optionlocs['conditions'] = array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id']);
        $clinicLocation = $this->ClinicLocation->find('all', $optionlocs);
        $this->set('ClinicLocations', $clinicLocation);
        //list of added review.
        $ratereview = $this->RateReview->find('all', array(
            'joins' => array(
                array(
                    'table' => 'staffs',
                    'alias' => 'Staff',
                    'type' => 'left',
                    'conditions' => array(
                        'Staff.id = RateReview.staff_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = RateReview.user_id'
                    )
                ),
                array(
                    'table' => 'clinic_users',
                    'alias' => 'ClinicUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClinicUser.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'clinics',
                    'alias' => 'clinics',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinics.id = RateReview.clinic_id'
                    )
                ),
                array(
                    'table' => 'clinic_locations',
                    'alias' => 'ClinicLocation',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ClinicLocation.id = RateReview.clinic_location_id'
                    )
                )),
            'conditions' => array(
                'ClinicUser.clinic_id' => $sessionstaff['clinic_id'], 'RateReview.rate >' => 0, 'RateReview.clinic_id' => $sessionstaff['clinic_id']
            ),
            'fields' => array('RateReview.*', 'Staff.id', 'Staff.staff_id', 'clinics.api_user', 'User.first_name', 'User.last_name', 'ClinicUser.card_number','ClinicLocation.google_business_page_url','ClinicLocation.yahoo_business_page_url','ClinicLocation.yelp_business_page_url','ClinicLocation.healthgrades_business_page_url'),
            'order' => array('RateReview.created_on desc')
        ));
        $this->set('Reviews', $ratereview);
    }

    public function awardpoint($id) {
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
        $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => $promo_name);
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
        echo "You have successfully rewarded " . $ratePromotion['Promotion']['value'] . ' points to patient for '.$type.' review.';
        die;
    }
    public function awardpointAll($id) {
        $this->layout = '';
        $sessionstaff = $this->Session->read('staff');
        $rateRate = $this->RateReview->find('first', array(
            'conditions' => array(
                'RateReview.id' => $_POST['tid']
            )
        ));
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
        $patients = $this->Notification->find('first', array('conditions' => array('Notification.earn_points' => 1, 'Notification.user_id' => $rateRate['RateReview']['user_id'])));
        $alltype=explode(',',$_POST['type']);
        $total_points=0;
        foreach($alltype as $atype){
          if ($atype == 1) {
            $promo_name = 'Google Share';
            $type = 'Google+';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'google_share' => 1
            );
        }
        if ($atype == 2) {
            $promo_name = 'Yahoo Share';
            $type = 'Yahoo';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'yahoo_share' => 1
            );
        }
        if ($atype == 3) {
            $promo_name = 'Yelp Share';
            $type = 'Yelp';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'yelp_share' => 1
            );
        }
        if ($atype == 4) {
            $promo_name = 'Healthgrades Share';
            $type = 'Healthgrades';
            $saverate['RateReview'] = array(
                'id' => $rateRate['RateReview']['id'],
                'healthgrades_share' => 1
            );
        }  
        $options['conditions'] = array('Promotion.is_lite' => 0, 'Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 2, 'Promotion.description' => $promo_name);
        $ratePromotion = $this->Promotion->find('first', $options);
        $total_points=$total_points+$ratePromotion['Promotion']['value'];
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
        }
        
        
        
        
        
        

        if (!empty($patients) && $total_points > 0) {
            //mail send for new point earned to referer
            $template_array = $this->Api->get_template(1);
            $link = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
            $link1 = str_replace('[click_here]', '<a href="' . rtrim($patientclinic['Clinics']['patient_url'], '/') . '">Click Here</a>', $link);
            $link2 = str_replace('[clinic_name]', $patientclinic['Clinics']['api_user'], $link1);
            $link3 = str_replace('[points]', $total_points, $link2);
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
        echo "You have successfully rewarded " . $total_points . ' points to patient for All Social Platform review.';
        die;
    }

}

?>
