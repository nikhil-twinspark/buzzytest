<?php
/**
 *  This file is for lead level setting for local clinic
 * 
 * 
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This Controller is for lead level setting for local clinic
 * 
 * 
 */
class AdminSettingController extends AppController {
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
    public $uses = array('Promotion', 'AdminSetting', 'IndustryType', 'LeadLevel', 'Clinic', 'Transaction', 'Refer','User','RateReview','ClinicNotification');
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
     *  fetch all lead setting related to clinic industry.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $clinic_set = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $sessionstaff['clinic_id'])));

        $lead = $this->IndustryType->find('all', array(
            'joins' => array(
                array(
                    'table' => 'lead_levels',
                    'alias' => 'lead_levels',
                    'type' => 'INNER',
                    'conditions' => array(
                        'lead_levels.industryId = IndustryType.id'
                    )
                )),
            'conditions' => array(
                'lead_levels.industryId ' => $clinic_set['Clinic']['industry_type']
            ),
            'fields' => array('IndustryType.*', 'lead_levels.*')
        ));
        $this->set('leads', $lead);
        $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $sessionstaff['clinic_id'], 'AdminSetting.setting_type' => 'leadpoints')));
        $this->set('admin_settings', $admin_set);
        //checking access control
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Admin Setting');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }

    /**
     *  change the lead level setting for local clinic.
     */
    public function changesetting() {
        $sessionstaff = $this->Session->read('staff');
        $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $sessionstaff['clinic_id'], 'AdminSetting.setting_type' => 'leadpoints')));
        $data = json_encode($_POST);
        if (empty($admin_set)) {
            $set_array['AdminSetting'] = array('setting_type' => 'leadpoints', 'clinic_id' => $sessionstaff['clinic_id'], 'setting_data' => $data);
            if ($this->AdminSetting->save($set_array)) {
                echo "Successfully Set the Local Lead points";
            } else {
                echo "Unable Set the Local Lead points";
            }
        } else {
            $set_array['AdminSetting'] = array('id' => $admin_set['AdminSetting']['id'], 'setting_data' => $data);
            if ($this->AdminSetting->save($set_array)) {

                echo json_encode(array('msg' => 'Local lead points successfully modified', 'val' => $_POST));
            } else {

                echo json_encode(array('msg' => 'Unable Set the Local Lead points', 'val' => ''));
            }
        }

        exit;
    }

}

?>
