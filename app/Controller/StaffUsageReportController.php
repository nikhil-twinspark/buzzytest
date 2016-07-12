<?php
/**
 * This file for get report (staff given the points to patient how many time this week and overall in given date range) for staff. 
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for get report (staff given the points to patient how many time this week and overall in given date range) for staff. 
 */
class StaffUsageReportController extends AppController {
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
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField', 'ClinicUser', 'Transaction','transaction','GlobalRedeem', 'Refer','Doctor','Staff','TrainingVideo','RateReview','ClinicNotification');
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
     * This is default index page for this module.
     */
    public function index() {
         $sessionstaff = $this->Session->read('staff');

         if($sessionstaff['staff_role']=='Doctor'  && $sessionstaff['staffaccess']['AccessStaff']['staff_reporting']==1 ){
             
         }else{
             $this->render('/Elements/access');
         }
    } 
    /**
     * Get the report according to search parameter passed.
     */
    public function getreport(){
        $sessionstaff = $this->Session->read('staff');
        $data = array();
        $this->layout = "";
        if($_POST){
            $type = $_POST['type'];
            $range =  $_POST['date_range'];
            $duration = explode(' - ',$range);
            //get the overall staff transaction report.
            $data = $this->transaction->getStaffTransactionReport('d',$duration,$sessionstaff['clinic_id']);
            $total=0;
            foreach($data as $overall){
                $total=$total+$overall[0]['user_count'];
            }
            $weekly = $this->_getWeeklyReport('d', $duration, $sessionstaff['clinic_id']);
            $success=1;
            if(empty($data) && empty($weekly)){
                $success = 0;
            }
            echo json_encode(array('data'=>array('overall'=>$data,'weekly'=>$weekly,'total'=>$total),'success'=>$success));
        }
        die();
    }
    /**
     * function to get weekly staff transaction report.
     * @param type $type
     * @param type $duration
     * @param type $clinicId
     * @return type
     */
    protected function _getWeeklyReport($type,$duration,$clinicId){
        if ($duration != null) {
            $from = new DateTime($duration[0]);
            $from = $from->format('Y-m-d');
        
            $to = new DateTime($duration[1]);
            $to = $to->format('Y-m-d');
        }
        
        $date1 = new DateTime($to);
        $date2 = new DateTime($from);
        
        $diff = $date2->diff($date1)->format("%a");
        
        if($diff>90){
            $date = new DateTime($to);
            $date->sub(new DateInterval('P3M'));
            $from = $date->format('Y-m-d');
        }
        
        $start_date = date('Y-m-d', strtotime($from));
        $end_date = date('Y-m-d', strtotime($to));
        $end_date1 = date('Y-m-d', strtotime($to.' + 6 days'));
        
        $weekDistribution = $dataSet = $weekRange = $list = $clientList = $resultSet = array();
        for($date = $start_date; $date <= $end_date1; $date = date('Y-m-d', strtotime($date. ' + 7 days')))
        {
            //create the date range for week.
            $dateRange = ApiComponent::getWeekDates($date, $start_date, $end_date);
            $duration = explode(' - ',$dateRange); 
            //get the weekly staff transaction report.
            $weekly = $this->transaction->getStaffWeeklyTransactionReport($type,$duration,$clinicId);
            $dataSet[$dateRange] = $weekly['dataSet'];
            $list[]=implode(',',$weekly['clients']);
            $weekRange[] = $dateRange;
            
        }
        // Steps to get uniqe clients
        $clients = array_unique($list);
        $clients =  implode(',',$clients);
        $clients = explode(',',$clients); 
        $clients = array_unique($clients);
        foreach($clients as $val){
            if(!empty($val)){
                $clientList[]= $val;
            }
        }
        
        foreach($clientList as $key=>$val){
            foreach($dataSet as $index=>$elem){
                if(empty($elem)){
                    $resultSet[$val][$index] = 0 ;
                    continue;
                }
                if(is_array($elem) && isset($dataSet[$index][$val][0])){
                    $resultSet[$val][$index] = $dataSet[$index][$val][0];
                }else{
                    $resultSet[$val][$index] = 0;
                }
            }
        }
        unset($dataSet);
        return array('dataSet'=>$resultSet,'weekRanges'=>$weekRange);
    }
}

?>
