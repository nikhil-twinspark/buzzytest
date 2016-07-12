<?php
/**
 * @deprecated deprecated controller
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * @deprecated deprecated controller
 */
class BankAccountController extends AppController {

    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session','Api');
    public $uses = array('BankAccount', 'User', 'Clinic', 'Transaction', 'Refer','TrainingVideo','ClinicNotification');
    public $layout = 'staffLayout';

    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $trainingvideo = $this->TrainingVideo->find('all');
        $this->Session->write('staff.trainingvideo', $trainingvideo);
        if (isset($sessionstaff['clinic_id'])) {
            $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
            $options3['fields'] = array('Clinic.analytic_code', 'Clinic.log_time', 'Clinic.lead_log', 'Clinic.id');
            $ClientCredentials = $this->Clinic->find('first', $options3);

            if (isset($ClientCredentials)) {

                $this->Session->write('staff.analytic_code', $ClientCredentials['Clinic']['analytic_code']);
            }
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }

    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('BankAccount.clinic_id' => $sessionstaff['clinic_id']);
        $ProductServicelist = $this->BankAccount->find('all', $options6);

        $this->set('BankAccount', $ProductServicelist);
        
        
       if(DEBIT_FROM_BANK==0){
            $this->render('/Elements/access');
        }
    }

    public function add() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $this->BankAccount->create(); 
          
            $options['conditions'] = array('BankAccount.clinic_id'=>$sessionstaff['clinic_id']);
            $ind = $this->BankAccount->find('first', $options);
            if(empty($ind)){
            $proarra['BankAccount'] = array('customer_name' => $this->request->data['customer_name'],'account_number' => $this->request->data['account_number'], 'transit_number' => $this->request->data['transit_number'], 'clinic_id' => $sessionstaff['clinic_id'],'account_type'=>$this->request->data['account_type'],'created_at'=>date('Y-m-d H:i:s'));
            if ($this->BankAccount->save($proarra)) {
                $this->Session->setFlash('Account successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add Account', 'default', array(), 'bad');
            }
            }else{
            $this->Session->setFlash('Account already exists.', 'default', array(), 'bad');
            }
        }
    }

    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $ProductAndService = $this->BankAccount->find('first',array('conditions' => array('BankAccount.id' => $id)));
     
        $this->set('data', $ProductAndService);
       
        if (isset($this->request->data['productandservices']['action']) && $this->request->data['productandservices']['action'] == 'update') {
            $proarra['BankAccount'] = array('id' => $this->request->data['id'], 'customer_name' => $this->request->data['customer_name'], 'account_number' => $this->request->data['account_number'], 'transit_number' => $this->request->data['transit_number'], 'account_type' => $this->request->data['account_type']);
            if ($this->BankAccount->save($proarra)) {
                $this->Session->setFlash('The Account has been updated.', 'default', array(), 'good');
                $this->set('data', $this->request->data);
                $this->redirect(array('action' => "edit/$id"));
            } else {
                $this->Session->setFlash('The Account not updated.', 'default', array(), 'bad');
                return $this->redirect(array('action' => 'index'));
            }
       
        }
        
    }


}

?>
