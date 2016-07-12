<?php
/**
 *  this file is for add,edit,delete Characteristics / Insurances / Procedures.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  this controller is for add,edit,delete Characteristics / Insurances / Procedures.
 */
class CharacteristicsInsurancesManagementController extends AppController {
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
    public $uses = array('Clinic', 'Theme', 'Promotion', 'LeadLevel', 'CharacteristicInsurance', 'Refpromotion', 'ClinicPromotion');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  fetching all Characteristics / Insurances / Procedures.
     */
    public function index() {
        $this->layout = "adminLayout";
        $charinsu = $this->CharacteristicInsurance->find('all');
        $this->set('charinsu', $charinsu);
    }

    /**
     *  adding Characteristics / Insurances / Procedures.
     */
    public function add() {
        $this->layout = "adminLayout";
        if ($this->request->is('post')) {
            $options['conditions'] = array('CharacteristicInsurance.name' => trim($this->request->data['CharacteristicInsurance']['name']),'CharacteristicInsurance.type' => $this->request->data['CharacteristicInsurance']['type']);
            $ind = $this->CharacteristicInsurance->find('first', $options);
            if (empty($ind)) {
                if ($this->CharacteristicInsurance->save($this->request->data)) {

                    $this->Session->setFlash('The Characteristic/Insurance/Procedure has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The Characteristic/Insurance/Procedure could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Characteristic/Insurance/Procedure already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * edit Characteristics / Insurances / Procedures.
     * @param type $id
     * @throws NotFoundException
     */
    public function edit($id) {
        $this->layout = "adminLayout";
        $indData = $this->CharacteristicInsurance->findById($id);
        $this->set('CharacteristicInsurance', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            //checking the duplicate entry in db
            $options['conditions'] = array('CharacteristicInsurance.name' => trim($this->request->data['CharacteristicInsurance']['name']),'CharacteristicInsurance.type' => $this->request->data['CharacteristicInsurance']['type'], 'CharacteristicInsurance.id !=' => $this->request->data['CharacteristicInsurance']['id']);
            $ind = $this->CharacteristicInsurance->find('first', $options);

            if (empty($ind)) {

                if ($this->CharacteristicInsurance->save($this->request->data)) {

                    $this->Session->setFlash('The Characteristic/Insurance/Procedure has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The  Characteristic/Insurance/Procedure could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Characteristic/Insurance/Procedure already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * 
     *  delete Characteristics / Insurances / Procedures.
     */
    public function delete($id) {
        if ($this->CharacteristicInsurance->deleteAll(array('CharacteristicInsurance.id' => $id))) {
            $this->Session->setFlash('Characteristic/Insurance/Procedure has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Characteristic/Insurance/Procedure not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

}

?>
