<?php
/**
 * This file for set the Social link settings for pratice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for set the Social link settings for pratice.
 */
class SettingsController extends AppController {
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
     * Edit the Practice social link settings.
     */
    public function edit() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $proarra['Clinic'] = array('id' => $this->request->data['id'], 'facebook_url' => $this->request->data['facebook_url'], 'pintrest_url' => $this->request->data['pintrest_url'], 'twitter_url' => $this->request->data['twitter_url'], 'instagram_url' => $this->request->data['instagram_url'], 'google_url' => $this->request->data['google_url'], 'yelp_url' => $this->request->data['yelp_url'], 'youtube_url' => $this->request->data['youtube_url'], 'healthgrade_url' => $this->request->data['healthgrade_url'], 'website_url' => $this->request->data['website_url']);

            if ($this->Clinic->save($proarra)) {
                $this->Session->setFlash('Updated successfully.', 'default', array(), 'good');
                $this->redirect(array('action' => 'edit'));
            } else {
                $this->Session->setFlash('Unable to update', 'default', array(), 'bad');
            }
        }

            $options4['conditions'] = array('Clinic.id'=>$sessionstaff['clinic_id']);
            $clinic = $this->Clinic->find('first', $options4);
            $this->set('Clinics', $clinic);
    }



}

?>
