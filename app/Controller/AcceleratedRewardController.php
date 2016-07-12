<?php

/**
 * This file for add,edit and delete Accelerated Reward settings.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for add,edit and delete Accelerated Reward settings.
 */
class AcceleratedRewardController extends AppController {
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
    public $uses = array('Promotion', 'User', 'Clinic', 'Transaction', 'ProductService', 'Refer', 'TierSetting', 'TrainingVideo', 'TierAchievment', 'AccessStaff','RateReview','ClinicNotification');
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
     * Getting the list of all Accelerated Reward for practice.
     */
    public function index() {

        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('TierSetting.clinic_id' => $sessionstaff['clinic_id']);
        $options6['fields'] = array('TierSetting.*');
        $options6['order'] = array('TierSetting.tier_name ASC');
        $AcceleratedRewardlist = $this->TierSetting->find('all', $options6);
        $this->set('AcceleratedReward', $AcceleratedRewardlist);
        if ($sessionstaff['staff_role'] == 'Doctor'  && $sessionstaff['staffaccess']['AccessStaff']['product_service'] == 1 && $sessionstaff['staffaccess']['AccessStaff']['tier_setting'] == 1) {
            
        } else {
            $this->render('/Elements/access');
        }
    }

    /**
     * Add new Accelerated Reward for practice
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        //fetch all coupon list of practice for link to Accelerated Reward
        $options6['conditions'] = array('ProductService.clinic_id' => $sessionstaff['clinic_id'], 'ProductService.type' => 3, 'ProductService.status' => 1);
        $options6['order'] = array('ProductService.title ASC');
        $ProductServicelist = $this->ProductService->find('all', $options6);
        $this->set('ProductService', $ProductServicelist);
        if ($this->request->is('post')) {
            $this->TierSetting->create();
            $options['conditions'] = array('TierSetting.tier_name' => trim($this->request->data['tier_name']), 'TierSetting.clinic_id' => $this->request->data['clinic_id']);
            $ind = $this->TierSetting->find('first', $options);
            $options['conditions'] = array('TierSetting.multiplier_value' => $this->request->data['multiplier_value'], 'TierSetting.clinic_id' => $this->request->data['clinic_id']);
            $ind1 = $this->TierSetting->find('first', $options);
            //condition to check duplicate Accelerated Reward for practice
            if (!empty($ind)) {
                $this->Session->setFlash('Accelerated Reward already exists.Please use different Tier Name.', 'default', array(), 'bad');
            }
            else if (!empty($ind1)) {
                $this->Session->setFlash('Accelerated Reward already exists.Please use different Multiplier Value.', 'default', array(), 'bad');
            }
            else {
                $proarra['TierSetting'] = array('tier_name' => $this->request->data['tier_name'], 'multiplier_value' => $this->request->data['multiplier_value'], 'points' => $this->request->data['points'], 'coupon_id' => $this->request->data['coupon_id'], 'clinic_id' => $this->request->data['clinic_id'], 'created_on' => date('Y-m-d H:i:s'), 'updated_on' => date('Y-m-d H:i:s'));
                if ($this->TierSetting->save($proarra)) {
                    $this->Session->setFlash('Accelerated Reward successfully added', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to add Accelerated Reward', 'default', array(), 'bad');
                }
            }
        }
    }

    /**
     * @description edit Accelerated Reward for practice
     * @param type $id Accelerated id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        //fetch all coupon list of practice for link to Accelerated Reward
        $options6['conditions'] = array('ProductService.clinic_id' => $sessionstaff['clinic_id'], 'ProductService.type' => 3, 'ProductService.status' => 1);
        $options6['order'] = array('ProductService.title ASC');
        $ProductServicelist = $this->ProductService->find('all', $options6);
        $this->set('ProductService', $ProductServicelist);
        $TierSettings = $this->TierSetting->find('first', array('conditions' => array('TierSetting.id' => $id)));
        $this->set('AcceleratedReward', $TierSettings);
        if (isset($this->request->data['AcceleratedReward']['action']) && $this->request->data['AcceleratedReward']['action'] == 'update') {

            $options['conditions'] = array('TierSetting.tier_name' => trim($this->request->data['tier_name']), 'TierSetting.id !=' => $this->request->data['id'], 'clinic_id' => $sessionstaff['clinic_id']);
            $ind = $this->TierSetting->find('first', $options);
            //condition to check duplicate accelerated reward for practice.
            if (empty($ind)) {
                $proarra['TierSetting'] = array('id' => $this->request->data['id'], 'coupon_id' => $this->request->data['coupon_id']);
                if ($this->TierSetting->save($proarra)) {
                    $this->Session->setFlash('The Accelerated Reward has been updated.', 'default', array(), 'good');
                    $this->set('AcceleratedReward', $this->request->data);
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The Accelerated Reward not updated.', 'default', array(), 'bad');
                    return $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Accelerated Reward already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * @description delete Accelerated Reward
     * @param type $id Accelerated Reward id
     * @return type
     */
    public function delete($id) {

        if ($this->TierSetting->deleteAll(array('TierSetting.id' => $id))) {
            $this->Session->setFlash('The Accelerated Reward has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('ERR:The Accelerated Reward not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Set the timeframe for accelerated Rewards.
     */
    public function changetimeframe() {
        $sessionstaff = $this->Session->read('staff');
        if ($_POST['tier_timeframe'] == '') {
            $_POST['tier_timeframe'] = 0;
        }
        $this->AccessStaff->query('update access_staffs set tier_timeframe=' . $_POST['tier_timeframe'] . ' where clinic_id=' . $sessionstaff['clinic_id']);
        $this->Session->write('staff.staffaccess.AccessStaff.tier_timeframe', $_POST['tier_timeframe']);
        echo 1;

        die;
    }
    /**
     * Set the base value for accelerated Rewards.
     */
    public function setBaseValue() {
        $sessionstaff = $this->Session->read('staff');
        if ($_POST['base_value'] == '') {
            $_POST['base_value'] = 0;
        }
        $this->AccessStaff->query('update access_staffs set base_value=' . $_POST['base_value'] . ' where clinic_id=' . $sessionstaff['clinic_id']);
        echo 1;

        die;
    }

}

?>