<?php
/**
 *  This file for add,edit,delete doctor and find out prime location and set it for doctor.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit,delete doctor and find out prime location and set it for doctor.
 */
class DoctorController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session,api and cakes3 component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('DoctorLocation', 'Procedure', 'CharacteristicInsurance', 'ClinicCharInsuProce', 'User', 'Clinic', 'Doctor', 'ClinicLocation', 'Transaction', 'Refer', 'State', 'City', 'SaveLike','TrainingVideo','RateReview','ClinicNotification');
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
        //set next free card number for default search
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
     *  get all doctor's for clinic.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');


        $Doctors = $this->Doctor->find('all', array('conditions' => array('Doctor.clinic_id' => $sessionstaff['clinic_id'])));

        $this->set('Doctors', $Doctors);
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Doctors');
        //checking the access for clinic type
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }
    /**
     * add new doctor to clinic with clinic location and procedures doctor have.
     * @return type
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        //getting all clinic locations
        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));

        if ($this->request->is('post')) {


            $image = $_FILES['profile_image'];
            if ($image['name'] != '') {
                //allowed image types
                $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                //upload folder - make sure to create one in webroot
                $uploadFolder = "docprofile/" . $sessionstaff['api_user'];
                //full path to upload folder
                $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                    chmod($uploadPath, 0777);
                }

                //check if image type fits one of allowed types
                if (in_array($image['type'], $imageTypes)) {



                    //check if there wasn't errors uploading file on serwer
                    if ($image['error'] == 0) {
                        $maxdoc = $this->Doctor->find('first', array('order' => array('Doctor.id desc')));
                        $doc_id=$maxdoc['Doctor']['id']+1;
                        //image file name
                        $imageName = $doc_id;
                        //check if file exists in upload folder
                        if (file_exists($uploadPath . '/' . $imageName)) {
                            //create full filename with timestamp
                            unlink($uploadPath . '/' . $imageName);
                        }
                        //create full path with image name
                        $full_image_path = $uploadPath . '/' . $imageName;
                        //upload image to upload folder
                        if (move_uploaded_file($image['tmp_name'], $full_image_path)) {

                            $response = $this->CakeS3->putObject($full_image_path, 'img/' . $uploadFolder . '/' . $imageName, $this->CakeS3->permission('public_read_write'));


                            $this->Doctor->create();
                            $this->request->data['procedures'] = implode(',', $this->request->data['procedures']);
                            $loc['Doctor'] = array('first_name' => $this->request->data['first_name'], 'last_name' => $this->request->data['last_name'], 'degree' => $this->request->data['degree'], 'procedures' => $this->request->data['procedures'], 'specialty' => $this->request->data['specialty'], 'clinic_id' => $this->request->data['clinic_id'], 'phone' => $this->request->data['phone'], 'email' => $this->request->data['email'], 'gender' => $this->request->data['gender'], 'address' => $this->request->data['address'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'pincode' => $this->request->data['pincode'], 'description' => $this->request->data['description'], 'dob' => $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'], 'pincode' => $this->request->data['pincode'], 'status' => 1, 'created_on' => date('Y-m-d H:i:s'));
                            if ($this->Doctor->save($loc)) {
                                $this->request->data['doctor_id'] = $this->Doctor->getLastInsertId();
                                for ($i = 1; $i <= $this->request->data['cnt']; $i++) {
                                    //selected clinic location set for doctor
                                    $this->request->data['location_id'] = $this->request->data['location_' . $i];
                                    unset($this->request->data['location_' . $i]);
                                    $this->request->data['days'] = implode(',', $this->request->data['days_' . $i]);
                                    unset($this->request->data['days_' . $i]);

                                    $this->DoctorLocation->create();

                                    $dloc['DoctorLocation'] = array('location_id' => $this->request->data['location_id'], 'days' => $this->request->data['days'], 'doctor_id' => $this->request->data['doctor_id']);
                                    $this->DoctorLocation->save($dloc);
                                }
                                $this->Session->setFlash('Doctor successfully added', 'default', array(), 'good');
                                $this->redirect(array('action' => 'index'));
                            } else {
                                $this->Session->setFlash('Unable to add doctor', 'default', array(), 'bad');
                            }
                        } else {
                            $this->Session->setFlash('There was a problem uploading file. Please try again.', 'default', array(), 'bad');
                            return $this->redirect('/doctor/add');
                        }
                    } else {
                        $this->Session->setFlash('Error uploading file.', 'default', array(), 'bad');
                        return $this->redirect('/doctor/add/');
                    }
                } else {
                    $this->Session->setFlash('Unacceptable file type', 'default', array(), 'bad');
                    return $this->redirect('/doctor/add/');
                }
            } else {
                $this->request->data['procedures'] = implode(',', $this->request->data['procedures']);
                $this->Doctor->create();
                $loc['Doctor'] = array('first_name' => $this->request->data['first_name'], 'last_name' => $this->request->data['last_name'], 'degree' => $this->request->data['degree'], 'procedures' => $this->request->data['procedures'], 'specialty' => $this->request->data['specialty'], 'clinic_id' => $this->request->data['clinic_id'], 'phone' => $this->request->data['phone'], 'email' => $this->request->data['email'], 'gender' => $this->request->data['gender'], 'address' => $this->request->data['address'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'pincode' => $this->request->data['pincode'], 'description' => $this->request->data['description'], 'dob' => $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'], 'pincode' => $this->request->data['pincode'], 'status' => 1, 'created_on' => date('Y-m-d H:i:s'));
                if ($this->Doctor->save($loc)) {
                    $this->request->data['doctor_id'] = $this->Doctor->getLastInsertId();
                    for ($i = 1; $i <= $this->request->data['cnt']; $i++) {

                        $this->request->data['location_id'] = $this->request->data['location_' . $i];
                        unset($this->request->data['location_' . $i]);
                        $this->request->data['days'] = implode(',', $this->request->data['days_' . $i]);
                        unset($this->request->data['days_' . $i]);

                        $this->DoctorLocation->create();

                        $dloc['DoctorLocation'] = array('location_id' => $this->request->data['location_id'], 'days' => $this->request->data['days'], 'doctor_id' => $this->request->data['doctor_id']);
                        $this->DoctorLocation->save($dloc);
                    }
                    $this->Session->setFlash('Doctor successfully added', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to add doctor', 'default', array(), 'bad');
                }
            }
        }


        $this->set('Locations', $Locations);
        $state = $this->State->find('all');
        $this->set('states', $state);
        $options['joins'] = array(
            array('table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $st . '"'
                )
            )
        );
        $options['fields'] = array('City.city');
        $cityresult = $this->City->find('all', $options);
        $this->set('city', $cityresult);
        $optionsch['joins'] = array(
            array('table' => 'clinic_char_insu_proces',
                'alias' => 'ClinicCharInsuProce',
                'type' => 'INNER',
                'conditions' => array(
                    'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id',
                    'ClinicCharInsuProce.clinic_id = "' . $sessionstaff['clinic_id'] . '"',
                    'CharacteristicInsurance.type = "Procedure"'
                )
            )
        );
        $optionsch['fields'] = array('CharacteristicInsurance.id,CharacteristicInsurance.name');
        $CharacteristicInsurance = $this->CharacteristicInsurance->find('all', $optionsch);
        $this->set('Procedures', $CharacteristicInsurance);
    }
    /**
     * Edit doctor profile with procedures and location.
     * @param type $id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));
     
        if ($this->request->is('post')) {


            $image = $_FILES['profile_image'];
            if ($image['name'] != '') {
                //allowed image types
                $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                //upload folder - make sure to create one in webroot
                $uploadFolder = "docprofile/" . $sessionstaff['api_user'];
                //full path to upload folder
                $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                    chmod($uploadPath, 0777);
                }

                //check if image type fits one of allowed types
                if (in_array($image['type'], $imageTypes)) {



                    //check if there wasn't errors uploading file on serwer
                    if ($image['error'] == 0) {
                        //image file name
                        $imageName = $id;
                        //check if file exists in upload folder
                        if (file_exists($uploadPath . '/' . $imageName)) {
                            //create full filename with timestamp
                            unlink($uploadPath . '/' . $imageName);
                        }
                        //create full path with image name
                        $full_image_path = $uploadPath . '/' . $imageName;
                        //upload image to upload folder
                        if (move_uploaded_file($image['tmp_name'], $full_image_path)) {

                            $response = $this->CakeS3->putObject($full_image_path, 'img/' . $uploadFolder . '/' . $imageName, $this->CakeS3->permission('public_read_write'));

                            $this->request->data['procedures'] = implode(',', $this->request->data['procedures']);
                            $this->Doctor->create();
                            $loc['Doctor'] = array('id' => $id, 'first_name' => $this->request->data['first_name'], 'last_name' => $this->request->data['last_name'], 'degree' => $this->request->data['degree'], 'specialty' => $this->request->data['specialty'], 'clinic_id' => $this->request->data['clinic_id'], 'phone' => $this->request->data['phone'], 'email' => $this->request->data['email'], 'gender' => $this->request->data['gender'], 'procedures' => $this->request->data['procedures'], 'address' => $this->request->data['address'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'pincode' => $this->request->data['pincode'], 'description' => $this->request->data['description'], 'dob' => $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'], 'pincode' => $this->request->data['pincode'], 'status' => 1, 'created_on' => date('Y-m-d H:i:s'));
                            if ($this->Doctor->save($loc)) {
                                $this->DoctorLocation->query('delete from doctor_locations where doctor_id=' . $id);
                                $this->request->data['doctor_id'] = $id;
                                for ($i = 1; $i <= $this->request->data['cnt']; $i++) {

                                    $this->request->data['location_id'] = $this->request->data['location_' . $i];
                                    unset($this->request->data['location_' . $i]);
                                    $this->request->data['days'] = implode(',', $this->request->data['days_' . $i]);
                                    unset($this->request->data['days_' . $i]);

                                    $this->DoctorLocation->create();

                                    $dloc['DoctorLocation'] = array('location_id' => $this->request->data['location_id'], 'days' => $this->request->data['days'], 'doctor_id' => $this->request->data['doctor_id']);
                                    $this->DoctorLocation->save($dloc);
                                }
                                $this->Session->setFlash('Doctor successfully updated', 'default', array(), 'good');
                                $this->redirect(array('action' => 'index'));
                            } else {
                                $this->Session->setFlash('Unable to update doctor', 'default', array(), 'bad');
                            }
                        } else {
                            $this->Session->setFlash('There was a problem uploading file. Please try again.', 'default', array(), 'bad');
                            return $this->redirect('/doctor/edit/' . $id);
                        }
                    } else {
                        $this->Session->setFlash('Error uploading file.', 'default', array(), 'bad');
                        return $this->redirect('/doctor/edit/' . $id);
                    }
                } else {
                    $this->Session->setFlash('Unacceptable file type', 'default', array(), 'bad');
                    return $this->redirect('/doctor/edit/' . $id);
                }
            } else {
                $this->Doctor->create();
                $this->request->data['procedures'] = implode(',', $this->request->data['procedures']);
                $loc['Doctor'] = array('id' => $id, 'first_name' => $this->request->data['first_name'], 'last_name' => $this->request->data['last_name'], 'degree' => $this->request->data['degree'], 'specialty' => $this->request->data['specialty'], 'clinic_id' => $this->request->data['clinic_id'], 'procedures' => $this->request->data['procedures'], 'phone' => $this->request->data['phone'], 'email' => $this->request->data['email'], 'gender' => $this->request->data['gender'], 'address' => $this->request->data['address'], 'state' => $this->request->data['state'], 'city' => $this->request->data['city'], 'pincode' => $this->request->data['pincode'], 'description' => $this->request->data['description'], 'dob' => $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'], 'pincode' => $this->request->data['pincode'], 'status' => 1, 'created_on' => date('Y-m-d H:i:s'));

                if ($this->Doctor->save($loc)) {
                    $this->DoctorLocation->query('delete from doctor_locations where doctor_id=' . $id);
                    $this->request->data['doctor_id'] = $id;
                    for ($i = 1; $i <= $this->request->data['cnt']; $i++) {

                        $this->request->data['location_id'] = $this->request->data['location_' . $i];
                        unset($this->request->data['location_' . $i]);
                        $this->request->data['days'] = implode(',', $this->request->data['days_' . $i]);
                        unset($this->request->data['days_' . $i]);

                        $this->DoctorLocation->create();

                        $dloc['DoctorLocation'] = array('location_id' => $this->request->data['location_id'], 'days' => $this->request->data['days'], 'doctor_id' => $this->request->data['doctor_id']);
                        $this->DoctorLocation->save($dloc);
                    }
                    $this->Session->setFlash('Doctor successfully updated', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to update doctor', 'default', array(), 'bad');
                }
            }
        }

        $getdoctor = $this->Doctor->find('first', array(
            'conditions' => array(
                'Doctor.id' => $id,
            )
        ));

        $this->set('Doctors', $getdoctor);
        $getdoctorloc = $this->DoctorLocation->find('all', array(
            'conditions' => array(
                'DoctorLocation.doctor_id' => $id,
            )
        ));
        $this->set('DoctorLocations', $getdoctorloc);
        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));

        $this->set('Locations', $Locations);
        $state = $this->State->find('all');

        $this->set('Locations', $Locations);
        $this->set('states', $state);
        $options['joins'] = array(
            array('table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $getdoctor['Doctor']['state'] . '"'
                )
            )
        );
        $options['fields'] = array('City.city');
        $cityresult = $this->City->find('all', $options);
        $this->set('city', $cityresult);
        $optionsch['joins'] = array(
            array('table' => 'clinic_char_insu_proces',
                'alias' => 'ClinicCharInsuProce',
                'type' => 'INNER',
                'conditions' => array(
                    'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id',
                    'ClinicCharInsuProce.clinic_id = "' . $sessionstaff['clinic_id'] . '"',
                    'CharacteristicInsurance.type = "Procedure"'
                )
            )
        );
        $optionsch['fields'] = array('CharacteristicInsurance.id,CharacteristicInsurance.name');
        $CharacteristicInsurance = $this->CharacteristicInsurance->find('all', $optionsch);
        $this->set('Procedures', $CharacteristicInsurance);
    }
    /**
     *  get prime location for currunt clinic.
     */
    public function getprimlocation() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $Locations = $this->ClinicLocation->find('first', array('conditions' => array('ClinicLocation.id' => $_POST['location_id'])));
        $state = $this->State->find('all');
        $html2 = '<option value="">Select State</option>';

        foreach ($state as $st) {

            $html2 .='<option value="' . $st['State']['state'] . '"';
          
            $html2 .='>' . $st['State']['state'] . '</option>';
        }
        if(!empty($Locations)){
        
        $html = '<option value="">Select State</option>';

        foreach ($state as $st) {

            $html .='<option value="' . $st['State']['state'] . '"';
            if ($st['State']['state'] == $Locations['ClinicLocation']['state']) {
                $html .=' selected';
            }
            $html .='>' . $st['State']['state'] . '</option>';
        }
        $options['joins'] = array(
            array('table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $Locations['ClinicLocation']['state'] . '"'
                )
            )
        );
        $options['fields'] = array('City.city');
        $cityresult = $this->City->find('all', $options);
        $html1 = '';

        foreach ($cityresult as $ct) {

            $html1 .='<option value="' . $ct['City']['city'] . '"';
            if ($ct['City']['city'] == $Locations['ClinicLocation']['city']) {
                $html1 .=' selected';
            }
            $html1 .='>' . $ct['City']['city'] . '</option>';
        }
        
            $data = array('success' => 1, 'state' => $html,'city'=>$html1,'phone'=>$Locations['ClinicLocation']['phone'],'email'=>$Locations['ClinicLocation']['email'],'address'=>$Locations['ClinicLocation']['address'],'zipcode'=>$Locations['ClinicLocation']['pincode']);
        } else {
            $data = array('success' => 1, 'state' => $html2,'city'=>'','phone'=>'','email'=>'','address'=>'','zipcode'=>'');
        }
        echo json_encode($data);
        die;
    }
    /**
     * Delete doctor.
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->Doctor->deleteAll(array('Doctor.id' => $id))) {
            $this->DoctorLocation->query('delete from doctor_locations where doctor_id=' . $id);
            $this->SaveLike->query('delete from save_likes where doctor_id=' . $id);
            $this->Session->setFlash('Doctor has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Doctor not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }
    /**
     * 
     *  get all clinic locations.
     */
    public function getlocation() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $Locations = $this->ClinicLocation->find('all', array('conditions' => array('ClinicLocation.clinic_id' => $sessionstaff['clinic_id'])));
        $html = '<div  id="locationno_' . $_POST['cnt'] . '"><div class="form-group"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Location:</label><div class="col-sm-9"><select class="col-xs-10 col-sm-5" name="location_' . $_POST['cnt'] . '" id="location_' . $_POST['cnt'] . '" onchange="checkloc(\'location_' . $_POST["cnt"] . '\',\'' . $_POST["cnt"] . '\');"><option value="">Select Location</option>';
        foreach ($Locations as $loc) {
            $html .='<option value="' . $loc['ClinicLocation']['id'] . '" >' . $loc['ClinicLocation']['address'] . '</option>';
        }
        $html .='</select><div class="add_profile icon-1" onclick="removeoption(\'locationno_' . $_POST["cnt"] . '\');" id="removeloc">x</div></div></div><div  class="form-group"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Choose Days:</label><div class="col-sm-9"><strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_1" name="days_' . $_POST['cnt'] . '[]" value="1" onclick="selcheck(\'days_' . $_POST["cnt"] . '_1\');"><label>Monday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_2" name="days_' . $_POST['cnt'] . '[]" value="2" onclick="selcheck(\'days_' . $_POST["cnt"] . '_2\');"><label>Tuesday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_3" name="days_' . $_POST['cnt'] . '[]" value="3" onclick="selcheck(\'days_' . $_POST["cnt"] . '_3\');"><label>Wednesday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_4" name="days_' . $_POST['cnt'] . '[]" value="4" onclick="selcheck(\'days_' . $_POST["cnt"] . '_4\');"><label>Thursday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_5" name="days_' . $_POST['cnt'] . '[]" value="5" onclick="selcheck(\'days_' . $_POST["cnt"] . '_5\');"><label>Friday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_6" name="days_' . $_POST['cnt'] . '[]" value="6" onclick="selcheck(\'days_' . $_POST["cnt"] . '_6\');"><label>Saturday</label></strong>  <strong><input type="checkbox" id="days_' . $_POST['cnt'] . '_7" name="days_' . $_POST['cnt'] . '[]" value="7" onclick="selcheck(\'days_' . $_POST["cnt"] . '_7\');"><label>Sunday</label></strong></div></div></div>';
        echo $html;
        exit;
    }
    /**
     *  check for doctor email is duplicate for clinic.
     */
    public function checkemail() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if (isset($_POST['id'])) {
            $getdoctor = $this->Doctor->find('first', array(
                'conditions' => array(
                    'Doctor.id !=' => $_POST['id'],
                    'Doctor.email' => $_POST['email'],
                    'Doctor.clinic_id' => $sessionstaff['clinic_id']
                )
            ));
        } else {
            $getdoctor = $this->Doctor->find('first', array(
                'conditions' => array(
                    'Doctor.email' => $_POST['email'],
                    'Doctor.clinic_id' => $sessionstaff['clinic_id']
                )
            ));
        }
        if (empty($getdoctor)) {
            echo 0;
        } else {
            echo 1;
        }
        exit;
    }
    /**
     *  set a default doctor to assign point to patient.
     */
        public function setprime(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Doctor->find('all', array('conditions' => array('Doctor.clinic_id' => $sessionstaff['clinic_id'])));
        foreach($Locations as $loc){
            $like=0;
            if($loc['Doctor']['id']==$_POST['doctor_id']){
                $like=1;
            }
            $loca['Doctor'] = array('id' => $loc['Doctor']['id'],'default'=>$like);
            $this->Doctor->save($loca);
        }
        exit;
    }

}

?>
