<?php

/**
 *  This file for creating the treatment plan from global promotion and set the phash destribution and badge assign to treatment plan.
 * Staff can create interval rewards plan here for continus treatment plan.
 * Staff can stop interval rewards plan using end of plan button.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller for creating the treatment plan from global promotion and set the phash destribution and badge assign to treatment plan.
 * Staff can create interval rewards plan here for continus treatment plan.
 * Staff can stop interval rewards plan using end of plan button.
 */
class LevelupPromotionsController extends AppController {

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
        'LevelTwoSetting',
        'TreatmentSetting',
        'LevelThreeCombo',
        'LevelThreeSetting',
        'ProductService',
        'User',
        'staff',
        'Clinic',
        'Refer',
        'Transaction',
        'Promotion',
        'BankAccount',
        'BeanstreamPayment',
        'LevelupPromotion',
        'Badge',
        'UpperLevelSetting',
        'PhaseDistribution',
        'AccessStaff',
        'TrainingVideo',
        'RateReview',
        'ClinicNotification'
    );

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
                $staffaceess = $this->Api->accessstaff($sessionstaff['clinic_id']);
                $this->Session->write('staff.staffaccess', $staffaceess);
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
     *  getting the all global promotion list and check that they all are use as levelup promotion or not.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array(
            'Promotion.clinic_id' => 0,
            'Promotion.is_global' => 1
        );
        $options6['order'] = array(
            'Promotion.sort ASC'
        );
        $gloPromotionlist = $this->Promotion->find('all', $options6);
        $options7['conditions'] = array(
            'LevelupPromotion.clinic_id' => $sessionstaff['clinic_id'],
            'LevelupPromotion.public' => 1
        );
        $options7['order'] = array(
            'LevelupPromotion.sort ASC'
        );
        $Promotionlist = $this->LevelupPromotion->find('all', $options7);
        $prolist = array();
        foreach ($Promotionlist as $prls) {
            $prolist[] = $prls['LevelupPromotion']['promotion_id'];
        }
        $this->set('GlobalPromotions', $gloPromotionlist);
        $this->set('Promotions', $prolist);
        if ($sessionstaff['staff_role'] != 'Doctor') {
            $this->render('/Elements/accessbuzzydoc');
        }
    }

    /**
     *  ordering for levelup promotion.
     */
    public function sortpromotions() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if (isset($_POST['data'])) {
            $data = json_decode($_POST['data'], true);
            if (!empty($data)) {
                foreach ($data as $value) {
                    $this->Promotion->query('UPDATE levelup_promotions set sort="' . $value['position'] . '" where id=' . $value['id']);
                }
                $status = 'Grid sorted successfully';
                echo $status;
            }
        }
        exit();
    }

    /**
     *  get all data of published promotion and global promotion.
     */
    public function getdata() {
        $this->layout = "";
        $Promotion = $this->getPublishedPromotions();

        $response = array(
            'aaData' => array()
        );
        $i = 0;

        foreach ($Promotion as $value) {

            $editDeleteString = "<a title='Edit' m_id='" . $value['LevelupPromotion']['id'] . "' href='" . Staff_Name . "LevelupPromotions/edit/" . $value['LevelupPromotion']['id'] . "'  class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;";
            $response['aaData'][$i] = array(
                $value['LevelupPromotion']['display_name'],
                $editDeleteString
            );
            $i ++;
        }

        echo json_encode($response);
        exit();
    }

    public function promotions() {
        
    }

    /**
     *  get the all treatment plans.
     */
    public function levelupsettings() {
        $sessionstaff = $this->Session->read('staff');
        $clients = $this->AccessStaff->getAccessForClinic($sessionstaff['clinic_id']);
        $this->set('staff_access', $clients);
        $phaseDistribution = $this->getPhaseDistribution('', 0);
        $this->set('phaseDistribution', $phaseDistribution);
        $Promotion = $this->getPublishedPromotions();
        $this->set('allactivepro', $Promotion);
        if ($sessionstaff['staff_role'] != 'Doctor') {
            $this->render('/Elements/accessbuzzydoc');
        }
    }

    /**
     *  get the all interval plans.
     */
    public function intervallevelupsettings() {
        $sessionstaff = $this->Session->read('staff');
        $clients = $this->AccessStaff->getAccessForClinic($sessionstaff['clinic_id']);
        $this->set('staff_access', $clients);
        $phaseDistribution = $this->getPhaseDistribution('', 1);
        $this->set('phaseDistribution', $phaseDistribution);
        $Promotion = $this->getPublishedPromotions();
        $this->set('allactivepro', $Promotion);
        if ($sessionstaff['staff_role'] != 'Doctor') {
            $this->render('/Elements/accessbuzzydoc');
        }
    }

    /**
     *  create a new teratment plan for clinic.
     */
    public function addsettings() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (!empty($data['global_promotion_ids'])) {
                $data['global_promotion_ids'] = implode(',', $data['global_promotion_ids']);
            }
            $options7['conditions'] = array(
                'UpperLevelSetting.treatment_name'=>$data['treatment_name'],
                'clinic_id' => $sessionstaff['clinic_id']
            );
            $checkplan = $this->UpperLevelSetting->find('all', $options7);
            if(empty($checkplan)){
            $settingsArray = array('clinic_id' => $sessionstaff['clinic_id'], 'treatment_name' => $data['treatment_name'], 'global_promotion_ids' => $data['global_promotion_ids'], 'bonus_points' => $data['bonus_points'], 'bonus_message' => $data['bonus_message'], 'total_visits' => $data['total_visits'], 'total_points' => $data['total_points'], 'interval' => 0, 'created_at' => date('Y-m-d H:i:s'));
            if ($this->UpperLevelSetting->save($settingsArray)) {
                unset($data['treatment_name'], $data['global_promotion_ids'], $data['total_visits'], $data['total_points'], $data['bonus_points'], $data['bonus_message']);
                $i = $j = $k = 1;
                $setting_id = $this->UpperLevelSetting->getLastInsertId();
                //adding the phase destribution and badge for treatment plans
                foreach ($data as $key => $val) {
                    if ($i == $j) {
                        $keyVal = explode('_', $key);
                        $point = 'points_' . $keyVal[1];
                        $visits = 'visits_' . $keyVal[1];
                        $badge = 'badge_' . $keyVal[1];
                        $badgeArray = array('name' => $data[$badge], 'value' => 0, 'description' => $data[$badge], 'clinic_id' => $sessionstaff['clinic_id'], 'created_on' => date('Y-m-d H:i:s'));
                        $this->Badge->create();
                        $this->Badge->save($badgeArray);
                        $badge_id = $this->Badge->getLastInsertId();
                        $phaseArray = array('upper_level_settings_id' => $setting_id, 'phase' => $k, 'visits' => $data[$visits], 'points' => $data[$point], 'badge_id' => $badge_id, 'badge_name' => $data[$badge], 'phase_name' => 'Phase ' . $k);
                        $this->PhaseDistribution->create();
                        //pr($phaseArray); die("phase array die");
                        $this->PhaseDistribution->save($phaseArray);

                        unset($data[$badge]);
                        unset($data[$point]);
                        unset($data[$visits]);
                        $j +=3;
                        $k++;
                    }
                    $i++;
                }

                $this->Session->setFlash('Treatment saved successfully', 'default', array(), 'good');
                $this->redirect(array('action' => 'levelupsettings'));
            } else {
                $this->Session->setFlash('Unable to save Treatment', 'default', array(), 'bad');
            }
            }else{
                $this->Session->setFlash('Unable to save Treatment.Treatment already exist.', 'default', array(), 'bad');
            }
        }
        $this->set('promotions', $this->getPublishedPromotions());
    }

    /**
     *  create a new interval plan for clinic.
     */
    public function addinterval() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (!empty($data['global_promotion_ids'])) {
                $data['global_promotion_ids'] = implode(',', $data['global_promotion_ids']);
            }
            $options7['conditions'] = array(
                'UpperLevelSetting.treatment_name'=>$data['treatment_name'],
                'clinic_id' => $sessionstaff['clinic_id']
            );
            $checkplan = $this->UpperLevelSetting->find('all', $options7);
            if(empty($checkplan)){
            $settingsArray = array('clinic_id' => $sessionstaff['clinic_id'], 'treatment_name' => $data['treatment_name'], 'global_promotion_ids' => $data['global_promotion_ids'], 'bonus_points' => $data['bonus_points'], 'bonus_message' => $data['bonus_message'], 'total_visits' => $data['total_visits'], 'total_points' => $data['total_points'], 'interval' => 1, 'created_at' => date('Y-m-d H:i:s'));
            if ($this->UpperLevelSetting->save($settingsArray)) {
                unset($data['treatment_name'], $data['global_promotion_ids'], $data['total_visits'], $data['total_points'], $data['bonus_points'], $data['bonus_message']);
                $i = $j = $k = 1;
                $setting_id = $this->UpperLevelSetting->getLastInsertId();
                //adding the phase destribution and badge for treatment plans
                foreach ($data as $key => $val) {
                    if ($i == $j) {
                        $keyVal = explode('_', $key);
                        $point = 'points_' . $keyVal[1];
                        $visits = 'visits_' . $keyVal[1];
                        $badge = 'badge_' . $keyVal[1];
                        $badgeArray = array('name' => $data[$badge], 'value' => 0, 'description' => $data[$badge], 'clinic_id' => $sessionstaff['clinic_id'], 'created_on' => date('Y-m-d H:i:s'));
                        $this->Badge->create();
                        $this->Badge->save($badgeArray);
                        $badge_id = $this->Badge->getLastInsertId();
                        $phaseArray = array('upper_level_settings_id' => $setting_id, 'phase' => $k, 'visits' => $data[$visits], 'points' => $data[$point], 'badge_id' => $badge_id, 'badge_name' => $data[$badge], 'phase_name' => 'Phase ' . $k);
                        $this->PhaseDistribution->create();
                        $this->PhaseDistribution->save($phaseArray);

                        unset($data[$badge]);
                        unset($data[$point]);
                        unset($data[$visits]);
                        $j +=3;
                        $k++;
                    }
                    $i++;
                }

                $this->Session->setFlash('Interval Reward saved successfully', 'default', array(), 'good');
                $this->redirect(array('action' => 'intervallevelupsettings'));
            } else {
                $this->Session->setFlash('Unable to save Interval Reward', 'default', array(), 'bad');
            }
            }else{
                $this->Session->setFlash('Unable to save Interval Reward.Treatment already exist.', 'default', array(), 'bad');
            }
        }
        $this->set('promotions', $this->getPublishedPromotions());
    }

    /**
     *  edit levelup promotion.
     * @param type $id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $Promotions = $this->LevelupPromotion->find('first', array(
            'conditions' => array(
                'LevelupPromotion.promotion_id' => $id,
                'LevelupPromotion.clinic_id' => $sessionstaff['clinic_id']
            )
        ));
        //pr($Promotions);
        $this->set('promotion', $Promotions);
        if (isset($this->request->data['LevelupPromotion']['action']) && $this->request->data['LevelupPromotion']['action'] == 'update') {

            $options['conditions'] = array(
                'OR' => array(
                    'LevelupPromotion.display_name' => trim($this->request->data['display_name']),
                    'LevelupPromotion.description' => trim($this->request->data['description'])
                ),
                'LevelupPromotion.id !=' => $this->request->data['id'],
                'clinic_id' => $sessionstaff['clinic_id']
            );
            $ind = $this->LevelupPromotion->find('first', $options);

            if (empty($ind)) {
                if ($this->request->data['alloted'] == 1) {
                    $proarra['LevelupPromotion'] = array(
                        'id' => $this->request->data['id'],
                        'display_name' => trim($this->request->data['display_name']),
                        'description' => trim($this->request->data['description'])
                    );
                } else {
                    $proarra['LevelupPromotion'] = array(
                        'id' => $this->request->data['id'],
                        'display_name' => trim($this->request->data['display_name']),
                        'description' => trim($this->request->data['description'])
                    );
                }

                if ($this->LevelupPromotion->save($proarra)) {
                    $this->Session->setFlash('The promotion has been updated.', 'default', array(), 'good');
                    $this->set('promotion', $this->request->data);
                    $this->redirect(array(
                        'action' => "edit/$id"
                    ));
                } else {
                    $this->Session->setFlash('The promotion not updated.', 'default', array(), 'bad');
                    return $this->redirect(array(
                                'action' => 'index'
                    ));
                }
            } else {
                $this->Session->setFlash('The Level 1 promotion already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     *  making the treatment plan from global promotion and set as public.
     */
    public function setpropublic() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $optionsch['conditions'] = array(
                'LevelupPromotion.promotion_id' => trim($this->request->data['pro_id']),
                'LevelupPromotion.clinic_id' => $sessionstaff['clinic_id'],
                'LevelupPromotion.public' => 1
            );
            $checkpub = $this->LevelupPromotion->find('first', $optionsch);
            if (!empty($checkpub)) {

                $settings = $this->UpperLevelSetting->query('select * from upper_level_settings where clinic_id=' . $sessionstaff['clinic_id'] . ' and (global_promotion_ids like "%,' . trim($checkpub['LevelupPromotion']['id']) . '%" or global_promotion_ids like "%,' . trim($checkpub['LevelupPromotion']['id']) . ',%" or global_promotion_ids like "%' . trim($checkpub['LevelupPromotion']['id']) . ',%")');
            }
            if (empty($settings)) {
                $this->LevelupPromotion->create();
                $options['conditions'] = array(
                    'LevelupPromotion.promotion_id' => trim($this->request->data['pro_id']),
                    'LevelupPromotion.clinic_id' => $sessionstaff['clinic_id']
                );

                $ind = $this->LevelupPromotion->find('first', $options);
                if (empty($ind)) {
                    $optionspro['conditions'] = array(
                        'Promotion.id' => trim($this->request->data['pro_id'])
                    );
                    //creating levelup promotion for clinic
                    $pro = $this->Promotion->find('first', $optionspro);
                    $proarra['LevelupPromotion'] = array(
                        'promotion_id' => $pro['Promotion']['id'],
                        'public' => 1,
                        'operand' => $pro['Promotion']['operand'],
                        'value' => $pro['Promotion']['value'],
                        'display_name' => $pro['Promotion']['display_name'],
                        'promotion_display_name' => $pro['Promotion']['display_name'],
                        'description' => $pro['Promotion']['description'],
                        'clinic_id' => $sessionstaff['clinic_id']
                    );
                    $this->LevelupPromotion->save($proarra);
                    echo 1;
                } else {
                    if ($ind['LevelupPromotion']['public'] == 1) {
                        $public = 0;
                        echo 2;
                    } else {
                        $public = 1;
                        echo 1;
                    }
                    $proarra['LevelupPromotion'] = array(
                        'id' => $ind['LevelupPromotion']['id'],
                        'public' => $public,
                        'clinic_id' => $sessionstaff['clinic_id']
                    );
                    $this->LevelupPromotion->save($proarra);
                }
            } else {
                echo 0;
            }
        }
        die();
    }

    /**
     * Get published promotions of level up program.
     * 
     * @param int $promotionId optional, get promotion on basis of ID
     * 
     * @return Array promotion list
     */
    public function getPublishedPromotions($promotionId = null) {
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array(
            'LevelupPromotion.clinic_id' => $sessionstaff['clinic_id'],
            'LevelupPromotion.public' => 1
        );

        if ($promotionId) {
            $options6['conditions']['LevelupPromotion.id'] = $promotionId;
        }

        $options6['order'] = array(
            'LevelupPromotion.sort ASC'
        );
        $Promotion = $this->LevelupPromotion->find('all', $options6);
        return $Promotion;
    }

    /**
     * Get the details of treatment plan.
     * @param type $treatementId
     * @return type
     */
    public function getTreatments($treatementId = null) {
        $sessionstaff = $this->Session->read('staff');
        $optionslevelsetting['conditions'] = array('UpperLevelSetting.clinic_id' => $sessionstaff['clinic_id']);
        if ($treatementId) {
            $optionslevelsetting['conditions']['UpperLevelSetting.id'] = $treatementId;
        }
        $data = $this->UpperLevelSetting->find('all', $optionslevelsetting);
        return $data;
    }

    /**
     * Fetch phase distribution for level up promotion on basis of treatment id.
     * @param type $upper_level_setting_id
     * @param type $interval
     * @return type
     */
    public function getPhaseDistribution($upper_level_setting_id = null, $interval = null) {
        $response = $options = array();

        $sessionstaff = $this->Session->read('staff');
        $options['joins'] = array(
            array(
                'table' => 'phase_distributions',
                'alias' => 'phase_distributions',
                'type' => 'INNER',
                'conditions' => array(
                    'phase_distributions.upper_level_settings_id = UpperLevelSetting.id'
                )
            )
        );
        $options['conditions'] = array(
            'UpperLevelSetting.clinic_id' => $sessionstaff['clinic_id'],
            'UpperLevelSetting.status' => 1,
            'UpperLevelSetting.soft_delete' => 1
        );
        if ($upper_level_setting_id) {
            $options['conditions']['phase_distributions.upper_level_settings_id'] = $upper_level_setting_id;
        }
        if (isset($interval)) {
            $options['conditions']['UpperLevelSetting.interval'] = $interval;
        }
        $options['fields'] = array(
            'phase_distributions.*',
            'UpperLevelSetting.id',
            'UpperLevelSetting.treatment_name',
            'UpperLevelSetting.total_points',
            'UpperLevelSetting.total_visits',
            'UpperLevelSetting.global_promotion_ids',
            'UpperLevelSetting.bonus_points',
            'UpperLevelSetting.bonus_message'
        );
        $data = $this->UpperLevelSetting->find('all', $options);
        if (!empty($data)) {

            $Promotionlist = array();
            foreach ($data as $val) {
                $globalids = explode(',', $val['UpperLevelSetting']['global_promotion_ids']);
                $prolist = array();
                foreach ($globalids as $ids) {
                    $options6['conditions'] = array(
                        'LevelupPromotion.id' => $ids
                    );
                    $Promotion = $this->LevelupPromotion->find('first', $options6);
                    $prolist[] = $Promotion['LevelupPromotion']['promotion_display_name'];
                }

                $val['UpperLevelSetting']['promotion_names'] = $prolist;
                $response[$val['phase_distributions']['upper_level_settings_id']]['UpperLevelSetting'] = $val['UpperLevelSetting'];
                $response[$val['phase_distributions']['upper_level_settings_id']]['phase_distribution'][] = $val['phase_distributions'];
            }
        }
        return $response;
    }

    /**
     * Deletion of treatment plan who not in use.
     * @param type $id
     * @return type
     */
    public function deleteTreatment($id) {
        $sessionstaff = $this->Session->read('staff');
        $conditions['conditions'][] = array(
            'TreatmentSetting.clinic_id' => $sessionstaff['clinic_id'], 'TreatmentSetting.upper_level_setting_id' => $id
        );
        $data = $this->TreatmentSetting->find('all', $conditions);
        if (empty($data)) {
            $this->UpperLevelSetting->deleteAll(array('UpperLevelSetting.id' => $id));
            $this->PhaseDistribution->query("delete from phase_distributions where upper_level_settings_id=" . $id);
            $this->Session->setFlash('The treatment has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'levelupsettings'));
        } else {
            $this->Session->setFlash('UNABLE TO DELETE: Treatment plan already in progress.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'levelupsettings'));
        }
    }

    /**
     * Deletion of interval plan who not in use.
     * @param type $id
     * @return type
     */
    public function deleteinterval($id) {
        $sessionstaff = $this->Session->read('staff');
        $conditions['conditions'][] = array(
            'TreatmentSetting.clinic_id' => $sessionstaff['clinic_id'], 'TreatmentSetting.upper_level_setting_id' => $id
        );
        $data = $this->TreatmentSetting->find('all', $conditions);
        if (empty($data)) {
            $this->UpperLevelSetting->deleteAll(array('UpperLevelSetting.id' => $id));
            $this->PhaseDistribution->query("delete from phase_distributions where upper_level_settings_id=" . $id);
            $this->Session->setFlash('The interval plan has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'intervallevelupsettings'));
        } else {
            $this->Session->setFlash('UNABLE TO DELETE: Interval plan already in progress.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'intervallevelupsettings'));
        }
    }

}

// class end here
?>
