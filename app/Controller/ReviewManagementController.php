<?php
/**
 * This file for add and delete review for other practices.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add and delete review for other practices.
 */
class ReviewManagementController extends AppController {
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
    public $uses = array('Promotion', 'User', 'Clinic', 'RateReview', 'Refer','Transaction','Doctor','TrainingVideo','ClinicNotification');
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
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
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
     * Getting the list off all review added by staff for Practice.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        //list of doctor for assign as reviewer when add review.
        $options3['conditions'] = array('Doctor.clinic_id'=>$sessionstaff['clinic_id']);
            $Doctor = $this->Doctor->find('all', $options3);
            $docarray = array();
            foreach ($Doctor as $adoc) {
                $docarray[] = $adoc['Doctor']['id'];
            }
            $docarr = array_unique($docarray);
        //list of added review.
        $Promotions = $this->RateReview->find('all', array(
            'joins' => array(
                array(
                    'table' => 'doctors',
                    'alias' => 'doctors',
                    'type' => 'INNER',
                    'conditions' => array(
                        'doctors.id = RateReview.doctor_id'
                    )
                ),
                array(
                    'table' => 'clinics',
                    'alias' => 'clinics',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinics.id = RateReview.clinic_id'
                    )
                )),
            'conditions' => array(
                'RateReview.doctor_id' => $docarr,'RateReview.review !='=>''
            ),
            'fields' => array('RateReview.id','RateReview.review', 'doctors.first_name', 'doctors.last_name','clinics.api_user')
        ));
        $this->set('Reviews', $Promotions);
        //checking the access control.
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Review');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }
    /**
     * Review add by staff for other pratice.
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $this->RateReview->create();

            $proarra['RateReview'] = array('clinic_id' => $this->request->data['clinic_id'], 'doctor_id' => $this->request->data['doctor_id'], 'review' => $this->request->data['review'],'created_on'=>date('Y-m-d H:i:s'));
         
            if ($this->RateReview->save($proarra)) {
                $this->Session->setFlash('Review successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add review', 'default', array(), 'bad');
            }
        }
            $options3['conditions'] = array('Doctor.clinic_id'=>$sessionstaff['clinic_id']);
            $options3['order'] = array('Doctor.first_name');
            $Doctor = $this->Doctor->find('all', $options3);
            $this->set('Doctors', $Doctor);
            $options4['conditions'] = array('Clinic.id !='=>$sessionstaff['clinic_id']);
            $options4['order'] = array('Clinic.api_user');
            $clinic = $this->Clinic->find('all', $options4);
            $this->set('Clinics', $clinic);
    }

 
    /**
     * Delete the review given by staff for other practice..
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->RateReview->deleteAll(array('RateReview.id' => $id))) {
            $this->Session->setFlash('The review has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('ERR:The review not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

}

?>
