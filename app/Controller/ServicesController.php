<?php

/**
 *  This file is for create new practice ,edit ,delete and set the access control for staff.
 *  Deletion of existing treatment plan for clinic,login to staff site directolly.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller is for create new practice ,edit ,delete and set the access control for staff.
 *  Deletion of existing treatment plan for clinic,login to staff site directolly.
 */
class ServicesController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session, api and CakeS3 component for this controller.
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
    public $uses = array('Service', 'Model', 'AdminSetting', 'AccessControl', 'UpperLevelSetting', 'LevelupPromotion', 'PhaseDistribution', 'TreatmentSetting', 'AccessStaff', 'BeanstreamPayment', 'Appointment', 'Badge', 'BankAccount', 'ClinicLocation', 'ClinicPromotion', 'ContestClinic', 'CouponAvail', 'Doctor', 'Document', 'FacebookLike', 'GlobalRedeem', 'Invoice', 'LevelupPromotion', 'MilestoneReward', 'PaymentDetail', 'ProductService', 'RateReview', 'Refer', 'SaveLike', 'UnregTransaction', 'UserAssignedTreatment', 'UserPerfectVisit', 'WishList','EmailTemplate');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }

    
    /**
     * add new service for test.
     * @return boolean
     */
    public function add() {
        Configure::write('debug', 2);
        $this->layout = "adminLayout";

        if ($this->request->is('post')) {
        	if($this->Service->save($this->request->data)){
	        	$this->Session->setFlash('Service Saved!', 'default', array(), 'good');
	            return $this->redirect(array('controller' => 'services', 'action' => 'add'));
        	}
        	else{
        		$this->Session->setFlash('Service Not added!', 'default', array(), 'bad');
	            return $this->redirect(array('controller' => 'services', 'action' => 'add'));	
        	}
		}
    }

    /**
	* view the service list
	* @return boolean
    **/
    public function index(){
    	$this->layout = "adminLayout";
    	$data = $this->Service->find('all');
    	$this->set('data', $data);
    }

    public function delete($id) {
        if ($this->Services->deleteAll(array('Services.id' => $id))) {
            $this->Session->setFlash('Service has deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('there is an error'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    /*public function delete($id) {
    	echo $id; die;
        echo $this->Services->delete($this->request->data('Services.id'));

    }*/
    


}
?>