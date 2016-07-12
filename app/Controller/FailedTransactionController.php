<?php
/**
 *  This file for getting the list of failed transaction when payemnet detail not found or CC expire.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for getting the list of failed transaction when payemnet detail not found or CC expire.
 */
class FailedTransactionController extends AppController {
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
    public $uses = array('Clinic', 'FailedPayment','User');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  list of failed transaction.
     */
    public function index() {
        $this->layout = "adminLayout";
        $clinic = $this->FailedPayment->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinic',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinic.id = FailedPayment.clinic_id'
                        )
                    )
                ),
                
                'fields' => array('Clinic.api_user', 'FailedPayment.*')
            ));

        $this->set('FailedTransaction', $clinic);
    }

}

?>
