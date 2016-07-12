<?php
/**
 *  This file for add,edit global promotion for treatment plan in staff site.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit global promotion for treatment plan in staff site.
 */
class GlobalPromotionManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session component for this controller.
     * @var type 
     */
    public $components = array('Session');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinic','Badge', 'Theme', 'Promotion', 'LeadLevel', 'IndustryPromotion', 'Refpromotion', 'ClinicPromotion');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');
        
        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  list of all global promotion added by super admin.
     */
    public function index() {
        $this->layout = "adminLayout";
        $litePromotion = $this->Promotion->find('all', array('conditions' => array('Promotion.is_global' => 1, 'Promotion.clinic_id' => 0)));
        $this->set('Promotion', $litePromotion);
    }
    /**
     *  add new global promotion by super admin.
     */
    public function addpromotion() {
        $this->layout = "adminLayout";
        
        if ($this->request->is('post')) {
            // condition to check duplicate global promotion
            $options['conditions'] = array('OR' => array('Promotion.display_name' => trim($this->request->data['Promotion']['display_name']), 'Promotion.description' => trim($this->request->data['Promotion']['description'])), 'Promotion.is_global' => 1, 'Promotion.clinic_id' => 0);
            $ind = $this->Promotion->find('first', $options);
            if (empty($ind)) {


                $proarra['Promotion'] = array(
                    'description' => trim($this->request->data['Promotion']['description']),
                    'display_name' => trim($this->request->data['Promotion']['display_name']),
                    'is_global' => 1,
                    'clinic_id' => 0,
                    'is_lite'=>0);
                $this->Promotion->create();
                if ($this->Promotion->save($proarra)) {
                    $this->Session->setFlash('The Global Promotion has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The Global Promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Global Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     * Edit existing global promotion.
     * @param type $ledid
     * @throws NotFoundException
     */
    public function editpromotion($ledid) {
        $this->layout = "adminLayout";

        $indData = $this->Promotion->findById($ledid);
        $this->set('Promotion', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            //condition for duplicate global promotion 
            $options['conditions'] = array('Promotion.display_name' => str_replace(" ", "", $this->request->data['Promotion']['display_name']), 'Promotion.id !=' => $this->request->data['Promotion']['id'], 'Promotion.is_global' => 1, 'Promotion.clinic_id' => 0);
            $ind = $this->Promotion->find('first', $options);

            if (empty($ind)) {
              
                $this->request->data['Promotion']['display_name']=  trim($this->request->data['Promotion']['display_name']);
                
                if ($this->Promotion->save($this->request->data)) {
                  
                    $this->Session->setFlash('The Global Promotion has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "editpromotion/$ledid"));
                } else {
                    $this->Session->setFlash('The  Global Promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Global Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }

}

?>
