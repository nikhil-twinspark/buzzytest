<?php
/**
 *  This file is for add,edit,delete clinic locations.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller is for add,edit,delete clinic locations.
 */
class ClinicLocationController extends AppController {
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
    public $uses = array('DoctorLocation', 'User', 'Clinic', 'Doctor', 'ClinicLocation', 'Transaction', 'Refer', 'State', 'City','TrainingVideo','RateReview','ClinicNotification');
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
     * 
     *  featch all clinic locations.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));

        $this->set('Locations', $Locations);
        //checking the access for this clinic
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Clinic Locations');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }

    /**
     * 
     *  add clinic location.
     */
    public function add() {

        if ($this->request->is('post')) {

            $address = $this->request->data['address'] . ' ' . $this->request->data['state'] . ' ' . $this->request->data['city'] . ' ' . $this->request->data['pincode']; // Google HQ
            $prepAddr = str_replace(' ', '+', $address);
            //find out the lattitude and longitude for clinic location from address
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $Locationcheck = $this->ClinicLocation->find('first', array('conditions' => array('ClinicLocation.clinic_id' => $this->request->data['clinic_id'])));
            if(empty($Locationcheck)){
                $ispm=1;
            }else{
                $ispm=0;
            }
            $this->ClinicLocation->create();
            
            $loc['ClinicLocation'] = array('address' => $this->request->data['address'],'email' => $this->request->data['email'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'clinic_id' => $this->request->data['clinic_id'], 'pincode' => $this->request->data['pincode'],'phone' => $this->request->data['phone'],'fax' => $this->request->data['fax'],'google_business_page_url' => $this->request->data['google_business_page_url'],'yahoo_business_page_url' => $this->request->data['yahoo_business_page_url'],'yelp_business_page_url' => $this->request->data['yelp_business_page_url'],'healthgrades_business_page_url' => $this->request->data['healthgrades_business_page_url'], 'latitude' => $latitude, 'longitude' => $longitude, 'status' => 1, 'created_on' => date('Y-m-d H:i:s'),'is_prime'=>$ispm);
            if ($this->ClinicLocation->save($loc)) {

                $this->Session->setFlash('Location successfully added', 'default', array(), 'goog');
                $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash('Unable to add location', 'default', array(), 'bad');
            }
        }
        $state = $this->State->find('all');
        $this->set('states', $state);
        $this->set('city', array());
    }
     /**
      * 
      *  fetching the right location for address.
      */
    public function checkzip(){
            $address = $_POST['zip']; // Google HQ
            $prepAddr = str_replace(' ', '+', $address);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $addressdetail = $output->results[0]->address_components[0]->long_name;
            $addressdetail1 = $output->results[0]->address_components[1]->long_name;
            $addressdetail2 = $output->results[0]->address_components[2]->long_name; 
            $addressdetail3 = $output->results[0]->address_components[3]->long_name; 
            $addressdetail4 = $output->results[0]->address_components[4]->long_name; 
            if(strtolower($addressdetail)==strtolower($_POST['state']) || strtolower($addressdetail1)==strtolower($_POST['state']) || strtolower($addressdetail2)==strtolower($_POST['state']) || strtolower($addressdetail3)==strtolower($_POST['state']) || strtolower($addressdetail4)==strtolower($_POST['state'])){
                echo 1;
            }else{
                echo 0;
            }
            die;
    }
    /**
     * edit clinic location.
     * @param type $id
     */
    public function edit($id) {
        if ($this->request->is('post')) {
            $this->ClinicLocation->create();
            $address = $this->request->data['address'] . ' ' . $this->request->data['state'] . ' ' . $this->request->data['city'] . ' ' . $this->request->data['pincode']; // Google HQ
            $prepAddr = str_replace(' ', '+', $address);
            //find out the lattitude and longitude for clinic location from address
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $loca['ClinicLocation'] = array('id' => $id, 'address' => $this->request->data['address'],'email' => $this->request->data['email'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'pincode' => $this->request->data['pincode'],'phone' => $this->request->data['phone'],'fax' => $this->request->data['fax'],'google_business_page_url' => $this->request->data['google_business_page_url'],'yahoo_business_page_url' => $this->request->data['yahoo_business_page_url'],'yelp_business_page_url' => $this->request->data['yelp_business_page_url'],'healthgrades_business_page_url' => $this->request->data['healthgrades_business_page_url'], 'latitude' => $latitude, 'longitude' => $longitude);


            if ($this->ClinicLocation->save($loca)) {

                $this->Session->setFlash('Location successfully updated', 'default', array(), 'good');
            } else {

                $this->Session->setFlash('Unable to edit Location', 'default', array(), 'bad');
            }
        }

        $getlocation = $this->ClinicLocation->find('first', array(
            'conditions' => array(
                'ClinicLocation.id' => $id,
            )
        ));

        $this->set('ClinicLocations', $getlocation);
        $state = $this->State->find('all');
        $this->set('states', $state);
        $options['joins'] = array(
            array('table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $getlocation['ClinicLocation']['state'] . '"'
                )
            )
        );
        $options['fields'] = array('City.city');
        $cityresult = $this->City->find('all', $options);
        $this->set('city', $cityresult);
    }
    
    /**
     * delete clinic location.
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->ClinicLocation->deleteAll(array('ClinicLocation.id' => $id))) {

            $this->Session->setFlash('Location has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {

            $this->Session->setFlash('Unable to delete Location', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }
    /**
     * 
     *  set clinic location as a prime location.
     */
    public function setprime(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));
        foreach($Locations as $loc){
            $like=0;
            if($loc['ClinicLocation']['id']==$_POST['location_id']){
                $like=1;
            }
            $loca['ClinicLocation'] = array('id' => $loc['ClinicLocation']['id'],'is_prime'=>$like);
            $this->ClinicLocation->save($loca);
        }
        exit;
    }

}

?>
