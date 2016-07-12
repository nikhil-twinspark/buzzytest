<?php
/**
 * This file for getting the list of all local pratice patient and update the record.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for getting the list of all local pratice patient and update the record.
 */
class UserManagementController extends AppController {
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
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField', 'ClinicUser', 'Transaction', 'Refer','TrainingVideo','ClinicNotification');
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
     * This is default index file for this module.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Users');
        //checking the access for this module.
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }
    /**
     * View patient details for practice.
     * @param type $id
     */
    public function edit($id) {
        $this->loadModel('user');
        $Patients_getchild = $this->user->find('first', array(
            'joins' => array(
                array(
                    'table' => 'clinic_users',
                    'alias' => 'ClinicUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClinicUser.user_id = user.id'
                    )
                )),
            'conditions' => array(
                'ClinicUser.user_id' => $id,
            ),
            'fields' => array('ClinicUser.*', 'user.*')
        ));
        $this->set('Users', $Patients_getchild);

        if (isset($Patients_getchild) && !empty($Patients_getchild)) {
            foreach ($Patients_getchild['ProfileField'] as $customer) {
                if ($customer['profile_field'] == 'state') {
                    $state = $this->State->find('all');
                    $this->set('states', $state);
                    $options['joins'] = array(
                        array('table' => 'states',
                            'alias' => 'States',
                            'type' => 'INNER',
                            'conditions' => array(
                                'States.state_code = City.state_code',
                                'States.state = "' . $customer['ProfileFieldUser']['value'] . '"'
                            )
                        )
                    );
                    $options['fields'] = array('City.city');
                    $cityresult = $this->City->find('all', $options);
                    $this->set('city', $cityresult);
                }
                $state = $this->State->find('all');
                $this->set('states', $state);
            }
        } else {
            $state = $this->State->find('all');
            $this->set('states', $state);
            $this->set('city', array());
        }
    }
    /**
     * Getting the list of all local pratice patient list.
     */
    public function getuser() {

        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('users.first_name', 'clinic_users.card_number', 'users.email');
        $sIndexColumn = "user_id";
        $sTable = 'users';

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
        //Filtering
        $_GET['sSearch'] = str_replace('%', '#$@19', $_GET['sSearch']);
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (CONCAT(users.first_name, ' ', users.last_name) LIKE '%" . $_GET['sSearch'] . "%' OR clinic_users.card_number LIKE '%" . $_GET['sSearch'] . "%'  OR users.email LIKE '%" . $_GET['sSearch'] . "%' and users.first_name!='')";
        }

        if ($sWhere == '') {
            $sWhere .=" WHERE clinic_users.clinic_id =" . $sessionstaff['clinic_id'] . "  and users.first_name!=''";
        } else {
            $sWhere .=" AND clinic_users.clinic_id=" . $sessionstaff['clinic_id'] . "";
        }
        $sQuery = "SELECT users.id,CONCAT(users.first_name, ' ', users.last_name) AS first_name,users.custom_date,users.email,clinic_users.card_number FROM   $sTable inner join clinic_users on clinic_users.user_id=$sTable.id  $sWhere $sOrder $sLimit";
        $rResult = $this->ClinicUser->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT count(clinic_users.clinic_id) as cnt,CONCAT(users.first_name, ' ', users.last_name) AS first_name FROM   $sTable inner join clinic_users on clinic_users.user_id=$sTable.id $sWhere group by clinic_users.clinic_id";
        $aResultFilterTotal = $this->ClinicUser->query($sQuery);
        if (!empty($aResultFilterTotal)) {
            $iFilteredTotal = $aResultFilterTotal[0][0]['cnt'];
        } else {
            $iFilteredTotal = 0;
        }
        $sQuery = "SELECT count(clinic_users.clinic_id) as cnt,CONCAT(users.first_name, ' ', users.last_name) AS first_name FROM   $sTable inner join clinic_users on clinic_users.user_id=$sTable.id where clinic_users.clinic_id=" . $sessionstaff['clinic_id'] . " and users.first_name!='' group by clinic_users.clinic_id";

        $aResultTotal = $this->ClinicUser->query($sQuery);
        if (!empty($aResultTotal)) {
            $iTotal = $aResultTotal[0][0]['cnt'];
        } else {
            $iTotal = 0;
        }
        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $val) {
            if ($val['users']['email'] != '') {
                $email = $val['users']['email'];
            } else {
                $email = 'NA';
            }
            if ($val[0]['first_name'] != '') {
                $name = $val[0]['first_name'];
            } else {
                $name = 'NA';
            }
            $row = array();
            $row[] = $name;
            $row[] = $val['clinic_users']['card_number'];
            $row[] = "<span class='emailClass'>" . $email . "</span>";
            $row[] = "<a title='Edit' href='/UserManagement/edit/" . $val['users']['id'] . "'   class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil'></i></a>";
            $output['aaData'][] = $row;
        }
        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     * Update the details for patient by staff user.
     * @return type
     */
    public function updatecustomer() {
        $sessionpatient = $this->Session->read('staff');
        //getting the filled profile field details by staff user.
        foreach ($this->request->data as $allfield1 => $allfieldval1) {
            $checkfield = explode('_', $allfield1);
            if ($checkfield[0] == 'other') {

                $findfield = str_replace('other_', '', $allfield1);
                $newarray[$findfield] = $allfieldval1;
                unset($this->request->data[$allfield1]);
            }
        }

        foreach ($this->request->data as $allfield => $allfieldval) {
            if (is_array($allfieldval)) {
                $this->request->data[$allfield] = implode(',', $allfieldval);
            } else {
                $this->request->data[$allfield] = $allfieldval;
            }

            if (isset($newarray[$allfield])) {

                $this->request->data[$allfield] = $this->request->data[$allfield] . '###' . $newarray[$allfield];
            }
        }
        $this->request->data['custom_date'] = $this->request->data['date_year'] . '-' . $this->request->data['date_month'] . '-' . $this->request->data['date_day'];
        if (isset($this->request->data['parents_email'])) {
            $this->request->data['email'] = $this->request->data['parents_email'];
            $this->request->data['parents_email'] = $this->request->data['aemail'];
        } else {
            $this->request->data['parents_email'] = '';
        }

        if ($this->request->data['new_password'] != '') {
            $fl_array['User']['id'] = $this->request->data['id'];
            $fl_array['User']['customer_password'] = md5($this->request->data['new_password']);
            $fl_array['User']['password'] = $this->request->data['new_password'];
            $fl_array['User']['parents_email'] = $this->request->data['parents_email'];
            $fl_array['User']['email'] = strtolower($this->request->data['email']);
            $fl_array['User']['custom_date'] = $this->request->data['custom_date'];
            $fl_array['User']['first_name'] = $this->request->data['first_name'];
            $fl_array['User']['last_name'] = $this->request->data['last_name'];
            $this->User->save($fl_array);
        } else {
            $fl_array['User']['id'] = $this->request->data['id'];
            $fl_array['User']['parents_email'] = $this->request->data['parents_email'];
            $fl_array['User']['email'] = strtolower($this->request->data['email']);
            $fl_array['User']['custom_date'] = $this->request->data['custom_date'];
            $fl_array['User']['first_name'] = $this->request->data['first_name'];
            $fl_array['User']['last_name'] = $this->request->data['last_name'];
            $this->User->save($fl_array);
        }

        foreach ($sessionpatient['ProfileField'] as $val) {

            if (isset($this->request->data[$val['ProfileField']['profile_field']])) {
                $pr_val = $this->request->data[$val['ProfileField']['profile_field']];
            } else {
                $pr_val = '';
            }
            $records_pf_vl = array("ProfileFieldUser" => array("user_id" => $this->request->data['id'], "profilefield_id" => $val['ProfileField']['id'], "value" => $pr_val));
            $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id=" . $sessionpatient['clinic_id'] . " or clinic_id='0' or clinic_id='') and user_id=" . $this->request->data['id'] . " and profilefield_id=" . $val['ProfileField']['id']);
            //updateing the all profile field details for patient.
            if (empty($ProfileField_val)) {
                $this->ProfileFieldUser->create();
                $this->ProfileFieldUser->save($records_pf_vl);
            } else {
                $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $pr_val . "' WHERE `profilefield_id` = " . $val['ProfileField']['id'] . " AND `user_id` =" . $this->request->data['id'] . " AND clinic_id=" . $val['ProfileField']['clinic_id']);
            }
        }
        $users = $this->User->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinic_users',
                    'alias' => 'clinic_users',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinic_users.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinic',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinic.id = clinic_users.clinic_id'
                    )
                ),
                array(
                    'table' => 'profile_field_users',
                    'alias' => 'PFU',
                    'type' => 'INNER',
                    'conditions' => array(
                        'PFU.user_id = User.id'
                    )
                ),
                array(
                    'table' => 'profile_fields',
                    'alias' => 'PF',
                    'type' => 'INNER',
                    'conditions' => array(
                        'PF.id = PFU.profilefield_id'
                    )
                )
            ),
            'conditions' => array(
                'User.id' => $this->request->data['id']),
            'fields' => array('clinic_users.*', 'User.*')
        ));
        $data = array();
        foreach ($users as $key => $value) {
            $users_field = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    ),
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinic',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinic.id = clinic_users.clinic_id'
                        )
                    ),
                    array(
                        'table' => 'profile_field_users',
                        'alias' => 'PFU',
                        'type' => 'INNER',
                        'conditions' => array(
                            'PFU.user_id = User.id'
                        )
                    ),
                    array(
                        'table' => 'profile_fields',
                        'alias' => 'PF',
                        'type' => 'INNER',
                        'conditions' => array(
                            'PF.id = PFU.profilefield_id'
                        )
                    )
                ),
                'conditions' => array(
                    'clinic_users.user_id' => $value['clinic_users']['user_id']
                ),
                'fields' => array('PF.*', 'PFU.*')
            ));

            $data[$value['clinic_users']['user_id']] = $users_field;
            $data[$value['clinic_users']['user_id']]['card_number'] = $value['clinic_users']['card_number'];
            $data[$value['clinic_users']['user_id']]['custom_date'] = $value['User']['custom_date'];
            $data[$value['clinic_users']['user_id']]['email'] = $value['User']['email'];
            $data[$value['clinic_users']['user_id']]['parents_email'] = $value['User']['parents_email'];
            $data[$value['clinic_users']['user_id']]['first_name'] = $value['User']['first_name'];
            $data[$value['clinic_users']['user_id']]['last_name'] = $value['User']['last_name'];
            $data[$value['clinic_users']['user_id']]['total_points'] = $value['User']['points'];
            $data[$value['clinic_users']['user_id']]['User'] = $value['User'];
        }
        foreach ($data as $dt) {
            $this->Session->write('staff.customer_info', $dt);
        }
        $this->Session->setFlash('Selected record updated successfully', 'default', array(), 'good');
        return $this->redirect('/UserManagement/edit/' . $this->request->data['id']);
        $this->redirect(array('action' => 'patientinfo'));
    }

}

?>
