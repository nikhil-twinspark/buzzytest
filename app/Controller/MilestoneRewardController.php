<?php
/**
 *  This file for add,edit and delete Milestone rewards.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit and delete Milestone rewards.
 */
class MilestoneRewardController extends AppController {
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
    public $components = array('Session','Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'User', 'Clinic', 'Transaction', 'ProductService','Refer','MilestoneReward','TrainingVideo','RateReview','ClinicNotification');
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
     *  get the list of all milestone rewards for practice.
     */
    public function index() {
        
                $sessionstaff = $this->Session->read('staff');
                $options6['conditions'] = array('MilestoneReward.clinic_id' => $sessionstaff['clinic_id']);
                $options6['order'] = array('MilestoneReward.name ASC');
                $MilestoneBadgelist = $this->MilestoneReward->find('all', $options6);

                $this->set('MilestoneReward', $MilestoneBadgelist);
                

        if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['milestone_reward']==1){
           
        }else{
            $this->render('/Elements/access');
        }
    }

    /**
     *  add new milestone rewards for practice.
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        //fetch all coupon list of practice for link to milestone
        $options6['conditions'] = array('ProductService.clinic_id' => $sessionstaff['clinic_id'],'ProductService.type'=>3,'ProductService.status'=>1);
        $options6['order'] = array('ProductService.title ASC');
        $ProductServicelist = $this->ProductService->find('all', $options6);
        $this->set('ProductService', $ProductServicelist);
        if ($this->request->is('post')) {
            $this->MilestoneReward->create();
            $options['conditions'] = array('MilestoneReward.name' => trim($this->request->data['name']),'MilestoneReward.clinic_id'=>$this->request->data['clinic_id']);
            $ind = $this->MilestoneReward->find('first', $options);
            //condition to check duplicate milestone reward for practice
            if(empty($ind)){
            $proarra['MilestoneReward'] = array('description' => $this->request->data['description'],'name' => $this->request->data['name'], 'points' => $this->request->data['points'], 'coupon_id' => $this->request->data['coupon_id'], 'clinic_id' => $this->request->data['clinic_id'],'created_on'=>date('Y-m-d H:i:s'));
            if ($this->MilestoneReward->save($proarra)) {
                $this->Session->setFlash('Milestone Reward successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add Milestone Reward', 'default', array(), 'bad');
            }
            }else{
            $this->Session->setFlash('Milestone Reward already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit milestone reward for practice.
     * @param type $id milestone id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        //fetch all coupon list of practice for link to milestone
        $options6['conditions'] = array('ProductService.clinic_id' => $sessionstaff['clinic_id'],'ProductService.type'=>3,'ProductService.status'=>1);
        $options6['order'] = array('ProductService.title ASC');
        $ProductServicelist = $this->ProductService->find('all', $options6);
        $this->set('ProductService', $ProductServicelist);
        $Promotions = $this->MilestoneReward->find('first', array('conditions' => array('MilestoneReward.id' => $id)));
        $this->set('MilestoneReward', $Promotions);
        //get current used coupon for calculate earning %
        $optionscu['conditions'] = array('ProductService.id' => $Promotions['MilestoneReward']['coupon_id']);
        $ProductServicecur = $this->ProductService->find('first', $optionscu);
        $this->set('ProductServicecur', $ProductServicecur);
        
        if (isset($this->request->data['MilestoneReward']['action']) && $this->request->data['MilestoneReward']['action'] == 'update') {
          
            $options['conditions'] = array('MilestoneReward.name' => trim($this->request->data['display_name']),'MilestoneReward.id !='=>$this->request->data['id'],'clinic_id'=>$sessionstaff['clinic_id']);
            $ind = $this->MilestoneReward->find('first', $options);
            //condition to check duplicate milestone reward for practice
            if(empty($ind)){
            $proarra['MilestoneReward'] = array('id' => $this->request->data['id'], 'name' => $this->request->data['name'], 'description' => $this->request->data['description'], 'coupon_id' => $this->request->data['coupon_id'], 'points' => $this->request->data['points']);
            if ($this->MilestoneReward->save($proarra)) {
                $this->Session->setFlash('The Milestone Reward has been updated.', 'default', array(), 'good');
                $this->set('MilestoneReward', $this->request->data);
                $this->redirect(array('action' => "edit/$id"));
            } else {
                $this->Session->setFlash('The Milestone Reward not updated.', 'default', array(), 'bad');
                return $this->redirect(array('action' => 'index'));
            }
        }else{
            $this->Session->setFlash('Milestone Reward already exists.', 'default', array(), 'bad');    
            }
        }
        
        
    }
    /**
     *  delete milestone reward.
     * @param type $id milestone id
     * @return type
     */
    public function delete($id) {

        if ($this->MilestoneReward->deleteAll(array('MilestoneReward.id' => $id))) {
            $this->Session->setFlash('The Milestone Reward has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('ERR:The Milestone Reward not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }
    
}

?>