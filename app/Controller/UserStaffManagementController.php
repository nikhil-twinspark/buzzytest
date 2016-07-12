<?php
/**
 * This file for add,edit,delete staff user for practice.
 * Set the any staff user to recieve appointment mail and get the all redemption mail when redemption done.
 * Set as active to staff to use staff site.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add,edit,delete staff user for practice.
 * Set the any staff user to recieve appointment mail and get the all redemption mail when redemption done.
 * Set as active to staff to use staff site.
 */
class UserStaffManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session,api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Staff', 'Clinic', 'State', 'PaymentDetail', 'City', 'ProfileFieldUser', 'ProfileField', 'ClinicUser', 'Transaction', 'Refer','User','TrainingVideo','ClinicNotification');
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
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }
    /**
     * This is default index file for this module and get the list of all staff users.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $Staff = $this->Staff->find('all', array('conditions' => array('Staff.clinic_id' => $sessionstaff['clinic_id'])));
        $this->set('Staffs', $Staff);
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Staffs');
        //check the access for this module.
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }
    /**
     * Edit the staff user details and change password.
     * @param type $id
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $getstaff = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.id' => $id,'Staff.clinic_id'=>$sessionstaff['clinic_id']
            )
        ));
        $this->set('Staffs', $getstaff);
        if ($this->request->is('post')) {
            if(!empty($getstaff)){
            $this->Staff->create();
            if($this->request->data['date_year']!=''){
            $this->request->data['dob'] = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];
            }else{
                $this->request->data['dob']='';
            }
            //condition for change password.
            if ($this->request->data['new_password'] != '') {
                $staff['Staff'] = array('id' => $id, 'staff_id' => $this->request->data['staff_id'], 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'dob' => $this->request->data['dob'], 'staff_role' => $this->request->data['staff_role'], 'clinic_id' => $sessionstaff['clinic_id'], 'staff_password' => md5($this->request->data['new_password']),'redemption_mail'=>$getstaff['Staff']['redemption_mail'],'is_prime'=>$getstaff['Staff']['is_prime'],'report_mail'=>$getstaff['Staff']['report_mail'],'active'=>$getstaff['Staff']['active']);
            } else {
                $staff['Staff'] = array('id' => $id, 'staff_id' => $this->request->data['staff_id'], 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'dob' => $this->request->data['dob'], 'staff_role' => $this->request->data['staff_role'], 'clinic_id' => $sessionstaff['clinic_id'],'redemption_mail'=>$getstaff['Staff']['redemption_mail'],'is_prime'=>$getstaff['Staff']['is_prime'],'report_mail'=>$getstaff['Staff']['report_mail'],'active'=>$getstaff['Staff']['active']);
            }
         
            if ($this->Staff->save($staff)) {
                if($this->request->data['staff_role']=='Doctor'){
                  $this->Session->write('staff.haveDoc', 1);  
                }else{
                  $this->Session->write('staff.haveDoc', 0); 
                }

                $this->Session->write('staff.var.staff_fname', $this->request->data['first_name']);
                $this->Session->setFlash('User Staff successfully updated', 'default', array(), 'good');
            } else {
                $this->Session->setFlash('Unable to edit User Staff', 'default', array(), 'bad');
            }
            
            $this->set('top', '798');
        }else{
            $this->Session->setFlash('Unable to edit User Staff', 'default', array(), 'bad');
        }
        }
        $i = 0;
        if ($getstaff['Staff']['staff_id'] != '') {
            $i++;
        }
        if ($getstaff['Staff']['staff_first_name'] != '') {
            $i++;
        }
        if ($getstaff['Staff']['staff_last_name'] != '') {
            $i++;
        }
        if ($getstaff['Staff']['staff_email'] != '') {
            $i++;
        }
        if ($getstaff['Staff']['staff_role'] != '') {
            $i++;
        }
        if ($getstaff['Staff']['dob'] != '' && $getstaff['Staff']['dob'] != '0000-00-00') {
            $i++;
        }
        $profilecomp = ($i / 6) * 100;
        //getting the profile completion %.
        $this->set('profilecomp', round($profilecomp, 2));
        $getstaff1 = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.id' => $id,'Staff.clinic_id'=>$sessionstaff['clinic_id']
            )
        ));
        $this->set('Staffs', $getstaff1);
    
    }
    /**
     * Add new staff user for pratice.
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $this->Staff->create();

            $this->request->data['dob'] = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];
            $staff['Staff'] = array('staff_id' => $this->request->data['staff_id'], 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'staff_role' => $this->request->data['staff_role'], 'dob' => $this->request->data['dob'], 'clinic_id' => $sessionstaff['clinic_id'], 'staff_password' => md5($this->request->data['new_password']));
            if ($this->Staff->save($staff)) {
                if($this->request->data['staff_role']=='Doctor'){
                  $this->Session->write('staff.haveDoc', 1);  
                }else{
                  $this->Session->write('staff.haveDoc', 0); 
                }

                $this->Session->setFlash('User Staff successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Unable to add User Staff', 'default', array(), 'bad');
            }
          
        }
      
    }
    /**
     * Delete staff user.
     * @param type $id
     */
    public function delete($id) {

        $sessionstaff = $this->Session->read('staff');
        $getstaff = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.id' => $id,'Staff.clinic_id'=>$sessionstaff['clinic_id']
            )
        ));
        
        if(!empty($getstaff)){
        if ($this->Staff->deleteAll(array('Staff.id' => $id,'Staff.clinic_id'=>$sessionstaff['clinic_id']))) {
            $getpayemntdetails = $this->PaymentDetail->find('first', array(
                'conditions' => array(
                    'PaymentDetail.doctor_id' => $id,'PaymentDetail.clinic_id' => $sessionstaff['clinic_id'],
                )
            ));
            //if deleted account for doctor then payment detail will also delete.
            if (!empty($getpayemntdetails)) {
                $this->PaymentDetail->deleteAll(array('PaymentDetail.id' => $getpayemntdetails['PaymentDetail']['id']));
            }
            $this->Session->setFlash('User Staff successfully deleted', 'default', array(), 'good');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Unable to delete User Staff', 'default', array(), 'bad');
        }
        } else {
            $this->Session->setFlash('Unable to delete User Staff', 'default', array(), 'bad');
            $this->redirect(array('action' => 'index'));
        }
    }
    /**
     * function to check duplicate staff user for practice.
     */
    public function checkapiuser() {
        $sessionstaff = $this->Session->read('staff');
        $urlResult = array();
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $options1['conditions'] = array('Staff.staff_id' => $_POST['staff_id'], 'Staff.clinic_id ' => $sessionstaff['clinic_id'], 'Staff.id !=' => $_POST['id']);
            $urlResult = $this->Staff->find('all', $options1);
        } else if (isset($_POST['staff_id']) && $_POST['staff_id'] != '') {
            $options1['conditions'] = array('Staff.staff_id' => $_POST['staff_id'], 'Staff.clinic_id ' => $sessionstaff['clinic_id']);
            $urlResult = $this->Staff->find('all', $options1);
        }
        $emailResult = array();
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $options2['conditions'] = array('Staff.staff_email' => $_POST['staff_email'], 'Staff.clinic_id ' => $sessionstaff['clinic_id'], 'Staff.id !=' => $_POST['id']);
            $emailResult = $this->Staff->find('all', $options2);
        } else if (isset($_POST['staff_email']) && $_POST['staff_email'] != '') {
            $options2['conditions'] = array('Staff.staff_email' => $_POST['staff_email'], 'Staff.clinic_id ' => $sessionstaff['clinic_id']);
            $emailResult = $this->Staff->find('all', $options2);
        }
        $docResult = array();
        if ($_POST['staff_role'] == 'Doctor') {
            if (isset($_POST['id']) && $_POST['id'] != '') {
                $options2['conditions'] = array('Staff.staff_role' => $_POST['staff_role'], 'Staff.clinic_id ' => $sessionstaff['clinic_id'], 'Staff.id !=' => $_POST['id']);
                $docResult = $this->Staff->find('all', $options2);
            } else {
                $options2['conditions'] = array('Staff.staff_role' => $_POST['staff_role'], 'Staff.clinic_id ' => $sessionstaff['clinic_id']);
                $docResult = $this->Staff->find('all', $options2);
            }
        }
        if (!empty($urlResult)) {
            echo 1;
        } else if (!empty($emailResult)) {
            echo 2;
        } else if (!empty($docResult)) {
            echo 3;
        } else {
            echo 0;
        }
        die;
    }
    /**
     * Function to save super doctor credit card details or bank account details for minimum deposite charge.
     */
    public function paymentsave() {
        $sessionstaff = $this->Session->read('staff');
        $urlResult = array();
        $customerProfile = new AuthorizeNetCustomer;
        $customerProfile->description = $_POST['acn_desc'];
        $customerProfile->merchantCustomerId = $_POST['acnt_id'];
        $customerProfile->email = $_POST['acn_email'];
        $request = new AuthorizeNetCIM;
        $response = $request->createCustomerProfile($customerProfile);
        //checking the credential of CC info at authorize.net
        if ($response->xml->messages->message->code == 'I00001') {
            $checkpayemntdetails = $this->PaymentDetail->find('first', array(
                'conditions' => array(
                    'PaymentDetail.clinic_id' => $sessionstaff['clinic_id'],
                )
            ));
            if($_POST['st']==0){
            $paymentdetail['PaymentDetail'] = array('doctor_id' => $_POST['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'customer_account_id' => $response->xml->customerProfileId, 'created_on' => date('Y-m-d H:m:s'));
            $this->PaymentDetail->create();
            $this->PaymentDetail->save($paymentdetail);
            $setarray=array('hostedProfileReturnUrl'=>'http://' . $_SERVER['HTTP_HOST'].'/UserStaffManagement/add/1','hostedProfileReturnUrlText'=>'Return Back to Payment details page');
            }else{
              $paymentdetail['PaymentDetail'] = array('id'=>$checkpayemntdetails['PaymentDetail']['id'],'doctor_id' => $_POST['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'customer_account_id' => $response->xml->customerProfileId, 'created_on' => date('Y-m-d H:m:s'));
            $this->PaymentDetail->save($paymentdetail);  
            $setarray=array('hostedProfileReturnUrl'=>'http://' . $_SERVER['HTTP_HOST'].'/UserStaffManagement/edit/'.$_POST['id'],'hostedProfileReturnUrlText'=>'Return Back to Payment details page');
            }
            $request1 = new AuthorizeNetCIM;
            //getting the hosted page after authorization success.
            $response1 = $request1->getHostedProfilePageRequest($response->xml->customerProfileId,$setarray);
            if ($response1->xml->messages->message->code == 'I00001') {
                if ($_POST['id'] == 0) {
                    $wdt = '-165';
                } else {
                    $wdt = '95';
                }
                echo '<form method="post" action="'.AUTHORIZENET_URL.'" id="formAuthorizeNetPage"><input type="hidden" name="token" value="' . $response1->xml->token . '"/></form><button type=”button” onclick= "steref(),document.getElementById(\'formAuthorizeNetPage\').submit();" style="cursor: pointer;display: inline-block;position: absolute;left: 156px;top: ' . $wdt . 'px;">Manage payment and shipping info.</button>';
            } else {
                echo '';
            }
            
        } else {
            echo '';
        }
        die;
    }
    /**
     * Set as default staff who recieve appointment mail.
     */
    public function setprime(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Staff->find('all', array('conditions' => array('Staff.clinic_id' => $sessionstaff['clinic_id'])));
        foreach($Locations as $loc){
            $like=0;
            if($loc['Staff']['id']==$_POST['staff_id']){
                $like=1;
            }
            $loca['Staff'] = array('id' => $loc['Staff']['id'],'is_prime'=>$like);
            $this->Staff->save($loca);
        }
        exit;
    }
    
    /**
     * Set staff to receive redeem mails
     */
    public function setredeemnotification(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $Locations = $this->Staff->find('all', array('conditions' => array('Staff.clinic_id' => $sessionstaff['clinic_id'])));
        foreach($Locations as $loc){
            $status=0;
            if($loc['Staff']['id']==$_POST['staff_id']){
                $status=1;
            }
            $loca['Staff'] = array('id' => $loc['Staff']['id'],'redemption_mail'=>$status);
            $this->Staff->save($loca);
        }
        exit;
    }
    /**
     * Activate the staff user.
     */
    public function setactive(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Staff->find('first', array('conditions' => array('Staff.id' => $_POST['staff_id'])));
      
          
            if($Locations['Staff']['active']==1){
                $like=0;
            }else{
                $like=1;
            }
         
            $this->Staff->query('UPDATE staffs set active="'.$like.'" where id='.$Locations['Staff']['id']);
   
        exit;
    }
    /**
     * Getting the existing payment details for super doctor.
     * @param type $id
     */
    public function paymentdetails($id) {
        $sessionstaff = $this->Session->read('staff');
        $getstaff = $this->Staff->find('first', array(
            'conditions' => array(
                'Staff.staff_role' => 'Doctor','Staff.clinic_id'=>$sessionstaff['clinic_id']
            )
        ));
        if ($this->request->is('post')) {
            
            if(!empty($getstaff)){
             
                if (isset($this->request->data['customer_account_profile_id']) ) {
                    if ($sessionstaff['is_buzzydoc'] == 1) {

                        $paymentdetail['PaymentDetail'] = array('id' => $this->request->data['payment_id'], 'doctor_id' => $getstaff['Staff']['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'customer_account_profile_id' => $this->request->data['customer_account_profile_id']);
                        $this->PaymentDetail->save($paymentdetail);
                    }
                }
                $this->Session->setFlash('Payment details successfully updated', 'default', array(), 'good');
            
            
            $this->set('top', '163');
        }else{
            $this->Session->setFlash('Unable to update payment details', 'default', array(), 'bad');
        }
        }

      
        $getpayemntdetails = $this->PaymentDetail->find('first', array(
            'conditions' => array(
                'PaymentDetail.clinic_id' => $sessionstaff['clinic_id'],
            )
        ));
        $this->set('staff_id',$getstaff['Staff']['id']);
        $this->set('PaymentDetails', $getpayemntdetails);
    }
    /**
     * Set staff to receive redeem mails
     */
    public function setreportnotification(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Staff->find('first', array('conditions' => array('Staff.id' => $_POST['staff_id'])));
      
          
            if($Locations['Staff']['report_mail']==1){
                $like=0;
            }else{
                $like=1;
            }
         
            $this->Staff->query('UPDATE staffs set report_mail="'.$like.'" where id='.$Locations['Staff']['id']);
   
        exit;
    }
    /**
     * Set staff to receive review mails
     */
    public function setreviewnotification(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Staff->find('first', array('conditions' => array('Staff.id' => $_POST['staff_id'])));
      
          
            if($Locations['Staff']['review_mail']==1){
                $like=0;
            }else{
                $like=1;
            }
         
            $this->Staff->query('UPDATE staffs set review_mail="'.$like.'" where id='.$Locations['Staff']['id']);
   
        exit;
    }
}

?>
