<?php
/**
 * This file for get the all global referral promotion and set as published for practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for get the all global referral promotion and set as published for practice.
 */
class StaffReferralPromotionManagementController extends AppController {
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
    public $components = array('Session','Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'User', 'Category', 'Reward', 'Clinic_reward', 'Notification', 'Clinic', 'Transaction', 'Refpromotion', 'ClinicPromotion', 'Refer','TrainingVideo','ClinicNotification');
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
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }
    /**
     * This is default index file for this controller.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        //checking the access for the clinic.
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Referral Promotions');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }
    /**
     * Getting the list of all global referral promotion list.
     */
    public function getGlobalReferralPromotion() {
        $sessionstaff = $this->Session->read('staff');
        $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
        $options3['fields'] = array('Clinic.industry_type', 'Clinic.id');
        //getting the industry for current practice.
        $Clientindustry = $this->Clinic->find('first', $options3);
        
        $this->layout = '';
        $aColumns = array('promotion_name');
        $sIndexColumn = "id";
        $sTable = 'refpromotions';

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }
        //Ordering
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $_GET['sSearch'] = str_replace('%', '#$@19', $_GET['sSearch']);
        //Filtering
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE industry_id=" . $Clientindustry['Clinic']['industry_type'];
        } else {
            $sWhere .=" AND industry_id=" . $Clientindustry['Clinic']['industry_type'];
        }

        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder $sLimit";
        $rResult = $this->Refpromotion->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder";
        $aResultFilterTotal = $this->Refpromotion->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);



        $sQuery = "select * from $sTable where industry_id=" . $Clientindustry['Clinic']['industry_type'];
        $aResultTotal = $this->Refpromotion->query($sQuery);
 
        foreach($aResultTotal as $refpro){
          
            if($refpro['refpromotions']['dafault']==1){
            $options13['conditions'] = array('ClinicPromotion.clinic_id'=>$sessionstaff['clinic_id']);
        $find = $this->ClinicPromotion->find('first', $options13);
        if(empty($find)){
            $data = array();
            $data['clinic_id'] = $sessionstaff['clinic_id'];
            $data['promotion_id'] = $refpro['refpromotions']['id'];
           
            $saveid = $this->ClinicPromotion->save($data);
        }
            }
        }
        
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $val) {
            $pCount = '';
            $checked = '';
            $pQuery = "select * from clinic_promotions where promotion_id='" . $val['refpromotions']['id'] . "' and clinic_id='".$sessionstaff['clinic_id']."'";
            $pResult = $this->ClinicPromotion->query($pQuery);
            $pCount = count($pResult);
            $row = array();
            if ($pCount > 0) {
                $checked = 'checked';
            }

            $row[] = $val['refpromotions']['promotion_name'];

            $row[] = "<span id='pub_" . $val['refpromotions']['id'] . "' class='public_span'><input type='checkbox' name='' id='publish_" . $val['refpromotions']['id'] . "' value='" . $val['refpromotions']['id'] . "' $checked onclick='setPublished(" . $val['refpromotions']['id'] . ")'></span>";
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     * Set the referral promotion as publish for local pratice use.
     */
    public function setPublishGlobalReferralPromotion() {

        $this->layout = '';
        $xml = '';
        $sessionstaff = $this->Session->read('staff');

        $pResult = $this->ClinicPromotion->find('all', array('conditions' => array('promotion_id' => $this->request->data['promotion_id'], 'clinic_id' => $sessionstaff['clinic_id'])));
        $pCount = count($pResult);

        if ($pCount == 0) {
            $data = array();
            $data['clinic_id'] = $sessionstaff['clinic_id'];
            $data['promotion_id'] = $this->request->data['promotion_id'];
            $saveid = $this->ClinicPromotion->save($data);
            if ($saveid) {
                echo '1';
                exit;
            } else {
                echo '2';
                exit;
            }
        } else {
            $pResult = $this->ClinicPromotion->query("delete from clinic_promotions where clinic_id='" . $sessionstaff['clinic_id'] . "' and promotion_id='" . $this->request->data['promotion_id'] . "'");
            echo '3';
            exit;
        }
    }

}

//class end here
?>
