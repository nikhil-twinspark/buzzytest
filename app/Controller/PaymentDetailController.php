<?php
/**
 *  This file for show all payment details transaction while patient redeem and deduct from buzzydoc bank.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for show all payment details transaction while patient redeem and deduct from buzzydoc bank.
 */
class PaymentDetailController extends AppController {

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
    public $uses = array('Promotion', 'User', 'Clinic', 'RateReview', 'Refer', 'Transaction', 'Doctor', 'Invoice','TrainingVideo','ClinicNotification');
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
     *  get the list of all payment details for clinic.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $options3['conditions'] = array('Invoice.clinic_id' => $sessionstaff['clinic_id']);
        $options3['order'] = array('Invoice.payed_on desc');
        $Invoice = $this->Invoice->find('all', $options3);
        $i=0;
        foreach ($Invoice as $inv) {
            if($inv['Invoice']['user_id']!=0){
            $optionsuser['conditions'] = array('User.id' => $inv['Invoice']['user_id']);
            $user = $this->User->find('first', $optionsuser);
            $Invoice[$i]['Invoice']['username']=$user['User']['first_name'].' '.$user['User']['last_name']; 
            $Invoice[$i]['Invoice']['email']=$user['User']['email']; 
            }else{
              $Invoice[$i]['Invoice']['username']='NA'; 
              $Invoice[$i]['Invoice']['email']='NA'; 
            }
            $i++;
        }
        $this->set('Invoices', $Invoice);
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'PaymentDetail');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }

}

?>
