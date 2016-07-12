<?php
/**
 *  This file for getting the details for all global patient with card number and update the details for patient.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for getting the details for all global patient with card number and update the details for patient.
 */
class GlobalUserManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session component for this controller.
     * @var type 
     */
    public $components = array('Session');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField', 'ClinicUser', 'Transaction', 'GlobalRedeem','Notification');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     * default page for this model.
     */
    public function index() {
        $this->layout = "adminLayout";
    }
    /**
     *  list of all global patient without card number.
     */
    public function getUser() {

        $this->layout = '';
        $aColumns = array('users.first_name', 'users.custom_date', 'users.email', 'clinic_users.card_number');
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
                    $sOrder .= '' . $aColumns[intval($_GET['iSortCol_' . $i])] . "
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
            $sWhere = "WHERE (CONCAT(users.first_name, ' ', users.last_name) LIKE '%" . $_GET['sSearch'] . "%' OR users.custom_date LIKE '%" . $_GET['sSearch'] . "%'  OR users.email LIKE '%" . $_GET['sSearch'] . "%'  OR clinic_users.card_number LIKE '%" . $_GET['sSearch'] . "%' and users.first_name!='')";
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE users.first_name!=''";
        } else {
            
        }
        $sQuery = "SELECT users.id,CONCAT(users.first_name, ' ', users.last_name) AS first_name,users.custom_date,users.email,clinic_users.card_number,clinic_users.clinic_id FROM   $sTable join clinic_users on clinic_users.user_id=users.id $sWhere $sOrder $sLimit";
        $rResult = $this->User->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT count(users.id) as cnt,CONCAT(users.first_name, ' ', users.last_name) AS first_name FROM   $sTable join clinic_users on clinic_users.user_id=users.id $sWhere";
        $aResultFilterTotal = $this->User->query($sQuery);
        if (!empty($aResultFilterTotal)) {
            $iFilteredTotal = $aResultFilterTotal[0][0]['cnt'];
        } else {
            $iFilteredTotal = 0;
        }
        $sQuery = "SELECT count(users.id) as cnt,CONCAT(users.first_name, ' ', users.last_name) AS first_name FROM   $sTable join clinic_users on clinic_users.user_id=users.id where users.first_name!=''";
        $aResultTotal = $this->User->query($sQuery);
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
            $newday = date('Y-m-d', strtotime('+3 day', strtotime($val['users']['custom_date'])));
            if ($val['users']['custom_date'] != '') {
                $date1 = $newday;
                $date2 = date('Y-m-d');
                $diff = abs(strtotime($date2) - strtotime($date1));
                $years = floor($diff / (365 * 60 * 60 * 24));

                $yr_dt = $val['users']['custom_date'] . ' (' . $years . ')';
            } else {
                $yr_dt = 'NA';
            }
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
            $row[] = $yr_dt;
            $row[] = $email;
            $row[] = $val['clinic_users']['card_number'];
            $str = "<a class='btn btn-xs btn-info' href='/GlobalUserManagement/view/" . $val['users']['id'] . "' title='View Profile'>";
            $str .="<i class='ace-icon fa fa-search-plus bigger-110'></i>";
            $str .="</a> &nbsp; ";

            $str .="<a title='Cards assigned' href='/GlobalUserManagement/cardinfo/" . $val['users']['id'] . "' class='btn btn-xs btn-danger'>";
            $str .="<i class='ace-icon fa fa-external-link'></i>";
            $str .="</a> &nbsp;";
            //condition to check patient belong to buzzydoc practice or not
            $userclinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $val['clinic_users']['clinic_id'])));

            if ($userclinic['Clinic']['is_buzzydoc'] == 1) {
                $str .="<a title='Login to BuzzyDoc site' href='" . Buzzy_Name . "buzzydoc/login/" . base64_encode($val['users']['id']) . "' target='_blank' class='btn btn-xs btn-info'><i class='ace-icon fa fa-fighter-jet'></i></a>";
            } else {

                $userid = rtrim($userclinic['Clinic']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($val['clinic_users']['card_number']) . "/" . base64_encode($val['users']['id']);
                $str .="<a title='Login to reward site' href='" . $userid . "' target='_blank' class='btn btn-xs btn-info'><i class='ace-icon fa fa-fighter-jet'></i></a>";
            }



            $row[] = $str;
            $output['aaData'][] = $row;
        }
        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     *  getting the list of all global user with card number.
     */
    public function getdata() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');

        $sQuery = "SELECT users.id,CONCAT(users.first_name, ' ', users.last_name) AS first_name,users.email,cu.card_number,cu.clinic_id,c.patient_url FROM   users as users left join clinic_users as cu on cu.user_id=users.id left join clinics as c on c.id=cu.clinic_id WHERE users.first_name!=''  UNION select NULL ,NULL,NULL,cu.card_number,NULL,NULL from card_numbers as cu";


        $rResult = $this->User->query($sQuery);


        $response = array(
            'aaData' => array()
        );
        foreach ($rResult as $val) {
            if ($val[0]['email'] != '') {
                $email = $val[0]['email'];
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

            $row[] = $email;
            $row[] = $val[0]['card_number'];

            $str = "<a class='btn btn-xs btn-info' href='/GlobalUserManagement/view/" . $val[0]['id'] . "' title='View Profile'>";
            $str .="<i class='ace-icon fa fa-search-plus bigger-110'></i>";
            $str .="</a> &nbsp; ";
            //condition to check patient belong to buzzydoc practice or not
            if ($val[0]['card_number'] != '') {
                $userid = rtrim($val[0]['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($val[0]['card_number']) . "/" . base64_encode($val[0]['id']);
                $str .="<a title='Login to reward site' href='" . $userid . "' target='_blank' class='btn btn-xs btn-info'>";
            } else {
                $str .="<a title='Login to BuzzyDoc site' href='" . Buzzy_Name . "buzzydoc/login/" . base64_encode($val[0]['id']) . "' target='_blank' class='btn btn-xs btn-info'>";
            }

            $str .="<i class='ace-icon fa fa-fighter-jet'></i>";
            $str .="</a>";

            $row[] = $str;
            $response['aaData'][] = $row;
        }

        echo json_encode($response);
        exit();
    }
    /**
     *  getting the patient profile detail with card number and view data.
     * @param type $id
     */
    public function view($id = null) {
        $this->layout = "adminLayout";
        $this->set('unsubscribe', 1); 
        $getclinic = $this->ClinicUser->find('all', array('conditions' => array('ClinicUser.user_id' => $id)));
        if(empty($getclinic)){
            $this->set('option', 0); 
        }else{
            $this->set('option', 1); 
        }
        $Notifications = $this->Notification->find('all', array('conditions' => array('Notification.user_id' => $id)));
        if(empty($Notifications)){
           $this->set('unsubscribe', 0); 
        }else{
            foreach($Notifications as $not){
                if($not['Notification']['order_status']==0 || $not['Notification']['earn_points']==0 || $not['Notification']['reward_challenges']==0 || $not['Notification']['points_expire']==0){
                $this->set('unsubscribe', 0);  
                }else{
                 $this->set('unsubscribe', 1);   
                }
            }
        }
        //fetching the all profile detail with profile fields
        $Patients_det = $this->User->find('first', array('conditions' => array('User.id' => $id)));
        $Patients_profile = $this->ProfileFieldUser->find('all', array(
            'joins' => array(
                array(
                    'table' => 'profile_fields',
                    'alias' => 'ProfileField',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProfileField.id = ProfileFieldUser.profilefield_id'
                    )
                )),
            'conditions' => array(
                'ProfileFieldUser.user_id' => $id, 'OR' => array('ProfileField.clinic_id' => '', 'ProfileField.clinic_id' => 0)
            ),
            'fields' => array('ProfileField.*', 'ProfileFieldUser.*')
        ));
        $Patients_det['User']['profile'] = $Patients_profile;
        $this->set('Users', $Patients_det);

        if (isset($Patients_det) && !empty($Patients_det)) {
            foreach ($Patients_det['User']['profile'] as $customer) {
                if ($customer['ProfileField']['profile_field'] == 'state') {
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
     *  getting the patient profile detail without card number and view data.
     * @param type $id
     */
    public function viewprofile($id = null) {
        $this->layout = "adminLayout";
        $id_clid = explode('-', $id);
        $Patients_det = $this->User->find('first', array('conditions' => array('User.id' => $id_clid[0])));
        $ProField = $this->ProfileField->query('SELECT `ProfileField`.`id`, `ProfileField`.`profile_field`, `ProfileField`.`type`, `ProfileField`.`options`, `ProfileField`.`clinic_id` FROM `profile_fields` AS `ProfileField` WHERE ((`ProfileField`.`clinic_id` = 0) OR (`ProfileField`.`clinic_id` = ' . $id_clid[1] . ')) ');
        $this->set('profile_field', $ProField);
        $Patients_profile = $this->ProfileFieldUser->query('SELECT `ProfileField`.*, `ProfileFieldUser`.* FROM `profile_field_users` AS `ProfileFieldUser` INNER JOIN `profile_fields` AS `ProfileField` ON (`ProfileField`.`id` = `ProfileFieldUser`.`profilefield_id`) WHERE `ProfileFieldUser`.`user_id` = ' . $id_clid[0] . ' AND ((`ProfileField`.`clinic_id` = 0) OR (`ProfileField`.`clinic_id` = ' . $id_clid[1] . ')) ');
        $Patients_det['User']['profile'] = $Patients_profile;
        $this->set('card_number', $id_clid[2]);
        $this->set('clinic_id', $id_clid[1]);
        $this->set('Users', $Patients_det);

        if (isset($Patients_det) && !empty($Patients_det)) {
            foreach ($Patients_det['User']['profile'] as $customer) {
                if ($customer['ProfileField']['profile_field'] == 'state') {
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
     *  getting the patient card details and belong to practices.
     * @param type $id
     */
    public function cardinfo($id = null) {
        $this->layout = "adminLayout";


        $user = $this->User->find('all', array(
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
                    'alias' => 'clinics',
                    'type' => 'INNER',
                    'conditions' => array(
                        'clinics.id = clinic_users.clinic_id'
                    )
                )
            ),
            'conditions' => array(
                'User.id' => $id
            ),
            'fields' => array('clinic_users.card_number', 'User.id', 'User.custom_date', 'User.first_name', 'User.last_name', 'User.email', 'User.points', 'clinic_users.clinic_id', 'clinics.api_user', 'clinics.patient_url'),
            'order' => array('User.first_name desc')
        ));
        $i = 0;
        foreach ($user as $use) {

            $redeemedpoint = $this->Transaction->query('select sum(amount) as totalamount from transactions where clinic_id=' . $use['clinic_users']['clinic_id'] . ' and user_id=' . $use['User']['id'] . ' and activity_type="Y"');
            $redeemedpointglo = $this->GlobalRedeem->query('select sum(amount) as totalamount from global_redeems where clinic_id=' . $use['clinic_users']['clinic_id'] . ' and user_id=' . $use['User']['id'] . '');
            $totalredeem = $redeemedpoint[0][0]['totalamount'] + $redeemedpointglo[0][0]['totalamount'];



            $allocatepoint = $this->Transaction->query('select sum(amount) as totalamount from transactions where clinic_id=' . $use['clinic_users']['clinic_id'] . ' and user_id=' . $use['User']['id'] . ' and activity_type="N"');
            $user[$i]['redeemedpoint'] = $totalredeem;
            $user[$i]['allocatepoint'] = $allocatepoint[0][0]['totalamount'];
            $i++;
        }
        $this->set('users', $user);
    }
    /**
     *  update patient profile details with card number.
     */
    public function updateuser() {
        $sessionpatient = $this->Session->read('admin');
        if(isset($this->request->data['unsubscribe'])){
        $getclinic = $this->ClinicUser->find('all', array('conditions' => array('ClinicUser.user_id' => $this->request->data['id'])));
        foreach($getclinic as $aclinic){
         $getnot = $this->Notification->find('first', array('conditions' => array('Notification.user_id' => $this->request->data['id'],'Notification.clinic_id'=>$aclinic['ClinicUser']['clinic_id']))); 
         //condition for update notification setting for patient
         if(empty($getnot)){
             
             if($this->request->data['unsubscribe']==0){
             $not_vl = array("Notification" => array("user_id" => $this->request->data['id'],'order_status'=>0,'earn_points'=>0,'reward_challenges'=>0,'points_expire'=>0, "clinic_id" => $aclinic['ClinicUser']['clinic_id']));
             }else{
              $not_vl = array("Notification" => array("user_id" => $this->request->data['id'],'order_status'=>1,'earn_points'=>1,'reward_challenges'=>1,'points_expire'=>1, "clinic_id" => $aclinic['ClinicUser']['clinic_id']));   
             }
                $this->Notification->create();
                $this->Notification->save($not_vl);
         }else{
             if($this->request->data['unsubscribe']==0){
             $this->Notification->query('UPDATE `notifications` SET `order_status` = 0,`earn_points` = 0,`reward_challenges` = 0,`points_expire`=0 WHERE  `user_id` =' . $this->request->data['id'].' and clinic_id='.$aclinic['ClinicUser']['clinic_id']);
             }else{
              $this->Notification->query('UPDATE `notifications` SET `order_status` = 1,`earn_points` = 1,`reward_challenges` = 1,`points_expire`=1 WHERE  `user_id` =' . $this->request->data['id'].' and clinic_id='.$aclinic['ClinicUser']['clinic_id']);   
             }
         }
        }
        
        }
        unset($this->request->data['unsubscribe']);
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

        if ($this->request->data['new_password'] != '') {
            $this->request->data['customer_password'] = $this->request->data['new_password'];
        }
        if (isset($this->request->data['parents_email'])) {
            $this->request->data['email'] = $this->request->data['parents_email'];
            $this->request->data['parents_email'] = $this->request->data['aemail'];
        } else {
            $this->request->data['parents_email'] = '';
        }
        //assign new password to patient
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
            $records_pf_vl = array("ProfileFieldUser" => array("user_id" => $this->request->data['id'], "profilefield_id" => $val['ProfileField']['id'], "value" => $pr_val, "clinic_id" => 0));
            $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id='0' or clinic_id='') and user_id=" . $this->request->data['id'] . " and profilefield_id=" . $val['ProfileField']['id']);
            //updating all profile details
            if (empty($ProfileField_val)) {
                $this->ProfileFieldUser->create();
                $this->ProfileFieldUser->save($records_pf_vl);
            } else {
                $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $pr_val . "' WHERE `profilefield_id` = " . $val['ProfileField']['id'] . " AND `user_id` =" . $this->request->data['id']);
            }
        }

        $this->Session->setFlash('User updated successfully', 'default', array(), 'good');
        return $this->redirect('/GlobalUserManagement/view/' . $this->request->data['id']);
    }
    /**
     *  update patient profile details without card number.
     */
    public function updateuserprofile() {
        $sessionpatient = $this->Session->read('admin');
        $ProField = $this->ProfileField->query('SELECT `ProfileField`.`id`, `ProfileField`.`profile_field`, `ProfileField`.`type`, `ProfileField`.`options`, `ProfileField`.`clinic_id` FROM `profile_fields` AS `ProfileField` WHERE ((`ProfileField`.`clinic_id` = 0) OR (`ProfileField`.`clinic_id` = ' . $this->request->data['clinic_id'] . ')) ');



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

        if ($this->request->data['new_password'] != '') {
            $this->request->data['customer_password'] = $this->request->data['new_password'];
        }
        if (isset($this->request->data['parents_email'])) {
            $this->request->data['email'] = $this->request->data['parents_email'];
            $this->request->data['parents_email'] = $this->request->data['aemail'];
        } else {
            $this->request->data['parents_email'] = '';
        }
        //assign new password to patient
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

        foreach ($ProField as $val) {

            if (isset($this->request->data[$val['ProfileField']['profile_field']])) {
                $pr_val = $this->request->data[$val['ProfileField']['profile_field']];
            } else {
                $pr_val = '';
            }
            $records_pf_vl = array(
                "ProfileFieldUser" => array(
                    "user_id" => $this->request->data['id'],
                    "profilefield_id" => $val['ProfileField']['id'],
                    "value" => $pr_val,
                    "clinic_id" => $val['ProfileField']['clinic_id']
                )
            );

            $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id=" . $val['ProfileField']['clinic_id'] . " or clinic_id='0' or clinic_id='') and user_id=" . $this->request->data['id'] . " and profilefield_id=" . $val['ProfileField']['id']);
            //update all profile fields detail
            if (empty($ProfileField_val)) {
                $this->ProfileFieldUser->create();
                $this->ProfileFieldUser->save($records_pf_vl);
            } else {
                $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $pr_val . "' WHERE `profilefield_id` = " . $val['ProfileField']['id'] . " AND `user_id` =" . $this->request->data['id']);
            }
        }

        $this->Session->setFlash('User updated successfully', 'default', array(), 'good');
        return $this->redirect('/GlobalUserManagement/viewprofile/' . $this->request->data['id'] . '-' . $this->request->data['clinic_id'] . '-' . $this->request->data['card_number']);
    }
    /**
     *  getting all city related to state.
     */
    public function getcity() {
        $this->layout = "";
        $options['joins'] = array(
            array('table' => 'states',
                'alias' => 'States',
                'type' => 'INNER',
                'conditions' => array(
                    'States.state_code = City.state_code',
                    'States.state = "' . $_POST['state'] . '"'
                )
            )
        );
        $options['fields'] = array('City.city');
        $options['order'] = array('City.city asc');
        $cityresult = $this->City->find('all', $options);
        $html = '<option value="">Select City</option>';
        foreach ($cityresult as $ct) {


            $html .='<option value="';
            $html .=$ct["City"]["city"];
            $html .='">';
            $html .=$ct["City"]["city"];
            $html .='</option>';
        }
        echo $html;
        exit;
    }
    /**
     *  function for checking the duplicate email for child and parent patient.
     */
    public function checkemail() {
        $this->layout = "";

        $users_field = $this->User->find('all', array('conditions' => array('User.id !=' => $_POST['id'], 'OR' => array('User.email !=' => '', 'User.parents_email !=' => ''))));

        if (isset($_POST['email']) && isset($_POST['parents_email'])) {
            if (isset($_POST['parents_email']) && $_POST['parents_email'] != '' && $_POST['email'] != '') {
                foreach ($users_field as $user) {
                    if ($user['User']['email'] == $_POST['email'] && $user['User']['parents_email'] == $_POST['parents_email']) {
                        $check = 1;
                        break;
                    } else {
                        $check = 0;
                    }
                }
                foreach ($users_field as $user) {
                    if ($user['User']['parents_email'] == $_POST['email']) {
                        $check1 = 1;
                        break;
                    } else {
                        $check1 = 0;
                    }
                }

                foreach ($users_field as $user) {
                    if ($user['User']['email'] == $_POST['parents_email']) {
                        $check2 = 1;
                        break;
                    } else {
                        $check2 = 0;
                    }
                }
                foreach ($users_field as $user) {
                    if ($user['User']['email'] != $_POST['email'] && $user['User']['parents_email'] == $_POST['parents_email'] && $user['User']['parents_email'] != '') {
                        $check3 = 1;
                        break;
                    } else {
                        $check3 = 0;
                    }
                }

                if ($check == 1) {
                    echo 1;
                } else if ($check1 == 1) {
                    echo 2;
                } else if ($check2 == 1) {
                    echo 4;
                } else if ($check3 == 1) {
                    echo 4;
                } else {
                    echo 0;
                }
            } else if (isset($_POST['parents_email']) && $_POST['parents_email'] == '' && $_POST['email'] != '') {

                foreach ($users_field as $user) {
                    if ($user['User']['parents_email'] == $_POST['email']) {
                        $check2 = 1;
                        break;
                    } else {
                        $check2 = 0;
                    }
                }



                if ($check2 == 1) {
                    echo 2;
                } else {
                    echo 0;
                }
            } else if (isset($_POST['parents_email']) && $_POST['parents_email'] != '' && $_POST['email'] == '') {

                foreach ($users_field as $user) {
                    if ($user['User']['email'] == $_POST['parents_email']) {
                        $check2 = 1;
                        break;
                    } else {
                        $check2 = 0;
                    }
                }
                foreach ($users_field as $user) {
                    if ($user['User']['parents_email'] == $_POST['parents_email']) {
                        $check1 = 1;
                        break;
                    } else {
                        $check1 = 0;
                    }
                }


                if ($check2 == 1 || $check1 == 1) {
                    echo 4;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {


            if ($_POST['email'] != '') {
                $date13age = date("Y-m-d", strtotime("-18 year"));
                foreach ($users_field as $user) {
                    if ($user['User']['parents_email'] == $_POST['email']) {
                        $check2 = 1;
                        break;
                    } else {
                        $check2 = 0;
                    }
                }

                foreach ($users_field as $user) {
                    if ($user['User']['email'] == $_POST['email'] && $user['User']['parents_email'] == '' && $user['User']['custom_date'] < $date13age) {
                        $check1 = 1;
                        break;
                    } else {
                        $check1 = 0;
                    }
                }
                if ($check1 == 1) {
                    echo 3;
                } else if ($check2 == 1) {
                    echo 2;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }


        exit;
    }

}

?>
