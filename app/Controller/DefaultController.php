<?php
/**
 *  This file for redirecting to staff,rewards and buzzydoc site after changing url from database.
 */
App::uses('AppController', 'Controller');
/**
 *  This is default controller for redirecting to staff,rewards and buzzydoc site after changing url from database.
 */
class DefaultController extends AppController {
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
    public $uses = array('Staff', 'Clinic', 'ClinicUser', 'User', 'CardNumber', 'CardLog');

    public function beforeFilter() {
        
    }
    /**
     * Using url we are featching the record from database and redirect accordingly.
     * @return type
     * @throws NotFoundException
     */
    public function index() {

        $this->Clinic->create();
        $host = explode('.', $_SERVER['HTTP_HOST']);
        //condition for redirect to buzzydoc site

        if ($host[1] == Buzzy || $host[0] == Buzzy) {
            return $this->redirect(array('controller' => 'buzzydoc', 'action' => 'login'));
        } else {
            $chkdomain = str_replace($host[0] . '.', '', $_SERVER['HTTP_HOST']);
            //if url is match with predefind domain name
            if ($chkdomain == Domain_Name) {
                $options['conditions'] = array('Clinic.api_user' => $host[0]);
                $patientcheck = $this->Clinic->find('first', $options);
                if (!empty($patientcheck)) {
                    if($patientcheck['Clinic']['is_lite']==1){
                        return $this->redirect(Buzzy_Name);
                    }else{
                    return $this->redirect(array('controller' => 'rewards', 'action' => 'login'));
                    }
                } else {
                    throw new NotFoundException(__('Invalid client'));
                }
            } else {
                //url chacking with patient url and staff url
                $options['conditions'] = array('Clinic.patient_url Like' => "%" . $_SERVER['HTTP_HOST'] . "%");
                $patientcheck = $this->Clinic->find('first', $options);
                $options1['conditions'] = array('Clinic.staff_url Like' => "%" . $_SERVER['HTTP_HOST'] . "%");
                $staffcheck = $this->Clinic->find('first', $options1);
                if (!empty($patientcheck)) {
                    if($patientcheck['Clinic']['is_lite']==1){
                        return $this->redirect(Buzzy_Name);
                    }else{
                    return $this->redirect(array('controller' => 'rewards', 'action' => 'login'));
                    }
                } else if (!empty($staffcheck)) {
                    return $this->redirect(array('controller' => 'staff', 'action' => 'login'));
                } else {
                    throw new NotFoundException(__('Invalid client'));
                }
            }
        }
    }

}

?>
