<?php
/**
 * This file for show all global Procedure/Charecterstice/insurence and published for Practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for show all global Procedure/Charecterstice/insurence and published for Practice.
 */
class StaffProceInsureChareManagementController extends AppController {
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
    public $components = array('Session', 'Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'User', 'CharacteristicInsurance', 'ClinicCharInsuProce', 'Clinic_reward', 'Notification', 'Clinic', 'Transaction', 'Refer','Staff','TrainingVideo','ClinicNotification');
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
     * This is default index file for this module.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'staffprocedure');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }
    /**
     * Getting the list of all global Procedure/Charecterstice/insurence
     */
    public function getProfileExtra() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('name', 'type');
        $sIndexColumn = "id";
        $sTable = 'characteristic_insurances';

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


        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder $sLimit";
        $rResult = $this->CharacteristicInsurance->query($sQuery);
        

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder";
        $aResultFilterTotal = $this->CharacteristicInsurance->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);


        $sQuery = "select * from $sTable ";
        $aResultTotal = $this->CharacteristicInsurance->query($sQuery);
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
            $pQuery = "select * from clinic_char_insu_proces where char_insue_proce_id='" . $val['characteristic_insurances']['id'] . "' and clinic_id=" . $sessionstaff['clinic_id'];
            $pResult = $this->ClinicCharInsuProce->query($pQuery);
            $pCount = count($pResult);
            $row = array();
            if ($pCount > 0) {
                $checked = 'checked="checked"';
            }

            $row[] = $val['characteristic_insurances']['name'];
            $row[] = $val['characteristic_insurances']['type'];

            $row[] = "<span id='pub_" . $val['characteristic_insurances']['id'] . "' class='public_span'><input type='checkbox' name='' id='publish_" . $val['characteristic_insurances']['id'] . "' value='" . $val['characteristic_insurances']['id'] . "' $checked onclick='setPublished(" . $val['characteristic_insurances']['id'] . ")'></span>";
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
        
    }
    /**
     * set publish Procedure/Charecterstice/insurence for practice.
     */
    public function setPublishProfileextra() {

        $this->layout = '';
        $xml = '';
        $sessionstaff = $this->Session->read('staff');

        $pResult = $this->ClinicCharInsuProce->find('first', array('conditions' => array('char_insue_proce_id' => $this->request->data['reward_id'], 'clinic_id' => $sessionstaff['clinic_id'])));
        if (empty($pResult)) {
            $data = array();
            $data['clinic_id'] = $sessionstaff['clinic_id'];
            $data['char_insue_proce_id'] = $this->request->data['reward_id'];
            $saveid = $this->ClinicCharInsuProce->save($data);
            if ($saveid) {
                echo '1';
                exit;
            } else {
                echo '2';
                exit;
            }
        } else {
            $pResult = $this->ClinicCharInsuProce->query("delete from clinic_char_insu_proces where clinic_id='" . $sessionstaff['clinic_id'] . "' and char_insue_proce_id='" . $this->request->data['reward_id'] . "'");
            echo '3';
            exit;
        }
    }
    /**
     * Function for request mail send to super admin to add new Procedure/Charecterstice/insurence.
     */
    public function requestnew() {

        $this->layout = '';
        $xml = '';
        $sessionstaff = $this->Session->read('staff');
        $staffdet = $this->Staff->find('first', array('conditions' => array('id' => $sessionstaff['staff_id'])));
        if ($staffdet['Staff']['staff_email'] != '') {
            $template_array = $this->Api->get_template(29);
            if ($this->request->data['request_type'] == 'Insurance') {
                $tx = 'an';
            } else {
                $tx = 'a';
            }
            $link = str_replace('[staff_name]', $sessionstaff['var']['staff_name'], $template_array['content']);
            $link1 = str_replace('[text]', $tx, $link);
            $link2 = str_replace('[clinic_name]', $sessionstaff['api_user'], $link1);
            $link3 = str_replace('[request_type]', $this->request->data['request_type'], $link2);
            $link4 = str_replace('[description]', $this->request->data['description'], $link3);
          
            $Email = new CakeEmail(MAILTYPE);

            $Email->from($staffdet['Staff']['staff_email']);
            $Email->to(SUPER_ADMIN_EMAIL);
            $Email->subject($template_array['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');

            $Email->viewVars(array(
                'msg' => $link4,
            ));
            try {
                $Email->send();
            } catch (Exception $e) {
                CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
            }
            echo 'Add request sent to buzzydoc admin successfully.';
            exit;
        } else {
            echo 'Update your staff email id to proceed with send request.';
            exit;
        }
    }

}

//class end here
?>
