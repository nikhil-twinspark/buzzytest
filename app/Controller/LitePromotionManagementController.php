<?php
/**
 *  This file for add,edit global promotion for buzzydoc lite practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit global promotion for buzzydoc lite practice.
 */
class LitePromotionManagementController extends AppController {
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
    public $uses = array('Clinic', 'Theme', 'Promotion', 'LeadLevel', 'IndustryPromotion', 'Refpromotion', 'ClinicPromotion');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  get the list of all global lite promotions.
     */
    public function index() {
        $this->layout = "adminLayout";
        $litePromotion = $this->Promotion->find('all', array('conditions' => array('Promotion.is_lite' => 1, 'Promotion.clinic_id' => 0)));

        $this->set('Promotion', $litePromotion);
    }
    /**
     *  add new global lite promotions.
     */
    public function addpromotion() {
        $this->layout = "adminLayout";
        if ($this->request->is('post')) {


            $options['conditions'] = array('OR' => array('Promotion.display_name' => trim($this->request->data['display_name']), 'Promotion.description' => trim($this->request->data['description'])), 'Promotion.is_lite' => 1, 'Promotion.clinic_id' => 0);
            $ind = $this->Promotion->find('first', $options);
            //condition to check duplicate global lite promotion
            if (empty($ind)) {


                $proarra['Promotion'] = array('description' => $this->request->data['description'], 'display_name' => $this->request->data['display_name'], 'value' => $this->request->data['value'], 'operand' => $this->request->data['Promotion']['operand'], 'is_lite' => 1,'clinic_id'=>0);
                $this->Promotion->create();
                if ($this->Promotion->save($proarra)) {

                    $optionscl['fields'] = array('Clinic.id');
                    $Clientid = $this->Clinic->find('all', $optionscl);
                    //fetch all clinic and set lite promotion for all
                    foreach ($Clientid as $clid) {
                        $proall['Promotion'] = array('description' => $this->request->data['description'], 'display_name' => $this->request->data['display_name'], 'value' => $this->request->data['value'], 'operand' => $this->request->data['Promotion']['operand'], 'is_lite' => 1,'clinic_id'=>$clid['Clinic']['id']);
                        $this->Promotion->create();
                        $addalllite=$this->Promotion->save($proall);
                        
                    }
                    $this->Session->setFlash('The Lite Promotion has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The Lite Promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Lite Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit global lite promotion.
     * @param type $ledid lite promotion id
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

            $options['conditions'] = array('OR' => array('Promotion.description' => trim($this->request->data['Promotion']['description']), 'Promotion.display_name' => trim($this->request->data['Promotion']['display_name'])), 'Promotion.id !=' => $this->request->data['Promotion']['id'], 'Promotion.is_lite' => 1, 'Promotion.clinic_id' => 0);
            $ind = $this->Promotion->find('first', $options);
            //condition to check duplicate lite promotion
            if (empty($ind)) {

                if ($this->Promotion->save($this->request->data)) {

                    $this->Session->setFlash('The Lite Promotion has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "editpromotion/$ledid"));
                } else {
                    $this->Session->setFlash('The  LitePromotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Lite Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }

}

?>
