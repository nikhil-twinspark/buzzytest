<?php
/**
 *  this file is for featching the clinic related all details (redemption and referrals).
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  this controller is for featching the clinic related all details (redemption and referrals).
 */
class BasicReportController extends AppController {
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
    public $uses = array('Transaction', 'Refer','GlobalRedeem', 'LeadLevel', 'CardNumber', 'ClinicUser', 'Clinic','User','TrainingVideo','ClinicNotification');
    /**
     * For Staff site we use the staffLayout
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
        $trainingvideo = $this->TrainingVideo->find('all');
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
     *  getting the all reports related to clinic like : (Points,Redemptions,Referrals).
     */
    public function index() {
        
        $sessionstaff = $this->Session->read('staff');

        
        $PointDisbursed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="N"');
        if (empty($PointDisbursed)) {
            $this->set('PointDisbursed', 0);
        } else {
            $this->set('PointDisbursed', $PointDisbursed[0][0]['total']);
        }
         $PointRedeemed = $this->Transaction->query('select sum(amount) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y"');
        $PointRedeemed1 = $this->GlobalRedeem->query('select sum(amount) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y"');
        $totpntred=$PointRedeemed[0][0]['total']+$PointRedeemed1[0][0]['total'];
        if ($totpntred==0) {
            $this->set('PointRedeemed', 0);
        } else {
            $this->set('PointRedeemed', round(ltrim($totpntred, '-'),2));
        }
        $OrderRedeemed = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and (status="New" or status="Redeemed") group by activity_type');
        $OrderRedeemed1 = $this->GlobalRedeem->query('select count(id) as total from global_redeems where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" group by activity_type');
        $totlred = $OrderRedeemed1[0][0]['total'] + $OrderRedeemed[0][0]['total'];

        if (empty($totlred)) {
            $this->set('OrderRedeemed', 0);
        } else {
            $this->set('OrderRedeemed', $totlred);
        }
        $OrderInoffice = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and status="In Office" group by activity_type');
        if (empty($OrderInoffice)) {
            $this->set('OrderInoffice', 0);
        } else {
            $this->set('OrderInoffice', $OrderInoffice[0][0]['total']);
        }
        $OrderShipped = $this->Transaction->query('select count(id) as total from transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and activity_type="Y" and status="Ordered/Shipped" group by activity_type');
        if (empty($OrderShipped)) {
            $this->set('OrderShipped', 0);
        } else {
            $this->set('OrderShipped', $OrderShipped[0][0]['total']);
        }
        $TotalRefer = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' group by clinic_id');
        if (empty($TotalRefer)) {
            $this->set('TotalRefer', 0);
        } else {
            $this->set('TotalRefer', $TotalRefer[0][0]['total']);
        }
        $TotalReferAccpeted = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Accepted" group by clinic_id');
        if (empty($TotalReferAccpeted)) {
            $this->set('TotalReferAccpeted', 0);
        } else {
            $this->set('TotalReferAccpeted', $TotalReferAccpeted[0][0]['total']);
        }
        $TotalReferFailed = $this->Refer->query('select count(refers.id) as total from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Failed" group by clinic_id');
        if (empty($TotalReferFailed)) {
            $this->set('TotalReferFailed', 0);
        } else {
            $this->set('TotalReferFailed', $TotalReferFailed[0][0]['total']);
        }
        $TotalReferCompleted = $this->Refer->query('select count(refers.id) as total,level from refers inner join users on users.id=refers.user_id where clinic_id=' . $sessionstaff['clinic_id'] . ' and refers.status="Completed" group by level');
        $completed = array();
        $i = 0;
        foreach ($TotalReferCompleted as $comp) {
            $completed[$i]['total'] = $comp[0]['total'];
            $lead = $this->LeadLevel->find('first', array('conditions' => array('LeadLevel.id' => $comp['refers']['level'])));
            $completed[$i]['leadname'] = $lead['LeadLevel']['leadname'];
            $i++;
        }
        $this->set('TotalReferCompleted', $completed);
        $Totalcardpurch = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' group by clinic_id');
        if (empty($Totalcardpurch)) {
            $this->set('Totalcardpurch', 0);
            $total1 = 0;
        } else {
            $this->set('Totalcardpurch', $Totalcardpurch[0][0]['total']);
            $total1 = $Totalcardpurch[0][0]['total'];
        }
        $Totalcardissue = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' and status=1 group by clinic_id');
        if (empty($Totalcardissue)) {
            $this->set('Totalcardissue', 0);
            $total2 = 0;
        } else {
            $this->set('Totalcardissue', $Totalcardissue[0][0]['total']);
            $total2 = $Totalcardissue[0][0]['total'];
        }
        $Totalcardreg = $this->CardNumber->query('select count(id) as total from card_numbers where clinic_id=' . $sessionstaff['clinic_id'] . ' and status=2 group by clinic_id');
        if (empty($Totalcardreg)) {
            $this->set('Totalcardreg', 0);
            $total3 = 0;
        } else {
            $this->set('Totalcardreg', $Totalcardreg[0][0]['total']);
            $total3 = $Totalcardreg[0][0]['total'];
        }
        $this->set('Totalcardbalance', $total1 - ($total2 + $total3));
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Basic Report');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }

}

?>
