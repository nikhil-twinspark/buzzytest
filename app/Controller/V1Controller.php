<?php

class V1Controller extends AppController
{

    public $helpers = array(
        'Html',
        'Form',
        'Session'
    );

    public $components = array(
        'Session',
        'Api',
        'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
        )
    );

    public $uses = array(
        'Promotion',
        'clinic',
        'ApiModel',
        'Reward',
        'Staff',
        'user',
        'ClinicUser',
        'ProfileFieldUser',
        'Category',
        'CardNumber',
        'ProfileField',
        'Document',
        'Doctor',
        'ClinicLocation',
        'DoctorLocation',
        'RateReview',
        'IndustryType',
        'Clinic',
        'State',
        'City',
        'WishList',
        'Transaction',
        'Notification',
        'Refer',
        'UnregTransaction',
        'ContestClinic',
        'ClinicPromotion',
        'Refpromotion',
        'LeadLevel',
        'IndustryType',
        'CharacteristicInsurance',
        'Badge',
        'FacebookLike',
        'PaymentDetail',
        'UsersBadge',
        'Badge',
        'AdminSetting',
        'GlobalRedeem',
        'Invoice',
        'ProductService',
        'LevelupPromotion',
        'UserPerfectVisit',
        'UserAssignedTreatment',
        'UpperLevelSetting',
        'PhaseDistribution',
        'StockImage'
    );

    public function beforeFilter()
    {}

    /**
     * Get patient list on search
     *
     * @author Maninder Bali
     * @since 2015/11/06
     */
    public function getpatients()
    {
        $inputData = $this->request->input("json_decode");
        $searchString = str_replace(array(
            '\\',
            '$',
            '#',
            '^',
            '/'
        ), '', $inputData->searchString);
        $cardchk1 = explode(';', $searchString);
        $cardchk2 = explode('?', $searchString);
        if (count($cardchk1) == 1 && count($cardchk2) == 1) {
            $searchString = $searchString;
        } else 
            if (count($cardchk1) == 2 && count($cardchk2) == 2) {
                $getcard = explode('?', $cardchk1[1]);
                $searchString = $getcard[0];
            } else {
                $searchString = '#$%^';
            }
        if (isset($inputData->ownClinic) && ($inputData->ownClinic == 1 || $inputData->ownClinic == 'on')) {
            $ownclinic = 1;
        } else {
            $ownclinic = 0;
        }
        if ($inputData->is_buzzydoc == 1 && $ownclinic == 0) {
            $users = $this->user->getUsers($searchString);
        } else {
            $users = $this->user->getUsers($searchString, $inputData->clinic_id);
        }
        $forfnln = explode(' ', $searchString);
        $queryl = '';
        foreach ($forfnln as $flname) {
            $queryl .= '(text like "%' . $flname . '%") and ';
        }
        $queryl = rtrim($queryl, ' and ');
        
        $cardnumber = $this->CardNumber->query('select * from card_numbers as CardNumber where (card_number like "%' . $searchString . '%" or ' . $queryl . ') and clinic_id=' . $inputData->clinic_id . ' and status=1');
        
        $total = count($users) + count($cardnumber);
        if ($total != 0) {
            if ($total > 1) {
                $records = [];
                foreach ($users as $val) {
                    $records[] = array_merge($val['clinic_users'], $val[0], $val['user']);
                }
                echo json_encode(array(
                    'customer_search_results' => $records,
                    'unreg_customer_search_results' => $cardnumber
                ));
            }
        }
        die();
    }
    
    /**
     * Get patient details
     */
    public function getpatientinfo(){
        $inputData = $this->request->input("json_decode");
        $userClinicId = $inputData->user_clinic_id;
        $clinicId = $inputData->clinic_id;
        $isBuzzyDoc = $inputData->is_buzzydoc;
        $userId = $inputData->user_id;
        $clinicId = $inputData->clinic_id;
                           if ($userId) {
    
                        $fsusers = $this->User->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = User.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'User.id' => $userId, 'clinic_users.clinic_id' => $userClinicId),
                            'fields' => array('clinic_users.*', 'User.*')
                        ));
    
                        if (!empty($fsusers)) {
                            $data = array();
                            $fromclinic = 0;
                            foreach ($fsusers as $key => $value) {
    
                                if ($value['clinic_users']['clinic_id'] == $clinicId) {
                                    $fromclinic = 1;
                                }
                                $sQuery = "SELECT * FROM  profile_field_users as PFU inner join profile_fields as PF on PF.id=PFU.profilefield_id where PFU.user_id=" . $value['clinic_users']['user_id'];
                                $users_field = $this->ProfileFieldUser->query($sQuery);
    
                                $data[$value['clinic_users']['user_id']] = $users_field;
                                $data[$value['clinic_users']['user_id']]['card_number'] = $value['clinic_users']['card_number'];
                                $data[$value['clinic_users']['user_id']]['custom_date'] = $value['User']['custom_date'];
                                $data[$value['clinic_users']['user_id']]['email'] = $value['User']['email'];
                                $data[$value['clinic_users']['user_id']]['parents_email'] = $value['User']['parents_email'];
                                $data[$value['clinic_users']['user_id']]['first_name'] = $value['User']['first_name'];
                                $data[$value['clinic_users']['user_id']]['last_name'] = $value['User']['last_name'];
                                $data[$value['clinic_users']['user_id']]['customer_password'] = $value['User']['password'];
                                $data[$value['clinic_users']['user_id']]['clinic_id'] = $value['clinic_users']['clinic_id'];
                                $localpoint = $this->ClinicUser->find('first', array(
                                    'conditions' => array(
                                        'ClinicUser.user_id' => $value['clinic_users']['user_id'],
                                        'ClinicUser.clinic_id' => $clinicId
                                    )
                                ));
    
                                if ($isBuzzyDoc == 1) {
                                    $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                                } else {
    
                                    $data[$value['clinic_users']['user_id']]['total_points'] = $localpoint['ClinicUser']['local_points'] . '(' . $value['User']['points'] . ')';
                                }
    
                                $data[$value['clinic_users']['user_id']]['User'] = $value['User'];
                            }
    
                            foreach ($data as $dt) {
                                $this->Session->write('staff.customer_info', $dt);
                            }
                            
                            $this->Session->write('staff.fromclinic', $fromclinic);
                            $this->Session->write('staff.customer_search_results', array());
                            $this->Session->delete('staff.customer_search_results');
                            $this->Session->write('staff.unreg_customer_search_results', array());
                            $this->Session->delete('staff.unreg_customer_search_results');
                        }
                    } else {
    
                        $unreguserdata = json_decode($cardnumber[0]['CardNumber']['text']);
                        $data[0]['card_number'] = $cardnumber[0]['CardNumber']['card_number'];
                        if (isset($unreguserdata) && !empty($unreguserdata)) {
    
                            $data[0]['first_name'] = $unreguserdata->first_name;
                            $data[0]['last_name'] = $unreguserdata->last_name;
                            $data[0]['email'] = $unreguserdata->email;
                            $data[0]['parents_email'] = $unreguserdata->parents_email;
                            $data[0]['custom_date'] = $unreguserdata->custom_date;
                            $data[0]['parents_email'] = $unreguserdata->parents_email;
                        } else {
    
    
                            $data[0]['first_name'] = '';
                            $data[0]['last_name'] = '';
                            $data[0]['email'] = '';
                            $data[0]['custom_date'] = '0000-00-00';
                            $data[0]['parents_email'] = '';
                        }
                        $unrgtrans = $this->UnregTransaction->query('select sum(amount) as total from unreg_transactions where clinic_id=' . $sessionstaff['clinic_id'] . ' and card_number="' . $cardnumber[0]['CardNumber']['card_number'] . '"');
                        if ($unrgtrans[0][0]['total'] != '') {
                            $totalpt = $unrgtrans[0][0]['total'];
                        } else {
                            $totalpt = 0;
                        }
                        $data[0]['total_points'] = $totalpt;
                        $data[0]['User']['id'] = 0;
                        $data[0]['User']['status'] = 1;
    
                        foreach ($data as $dt) {
                            $this->Session->write('staff.customer_info', $dt);
                        }
    
                        $this->Session->write('staff.customer_search_results', array());
                        $this->Session->delete('staff.customer_search_results');
                        $this->Session->write('staff.unreg_customer_search_results', array());
                        $this->Session->delete('staff.unreg_customer_search_results');
                    }
                    $this->Session->write('staff.permission', 1);
                    $sessionstaffnew = $this->Session->read('staff');
                    $localpoint = $this->Staff->find('first', array(
                        'conditions' => array(
                            'Staff.staff_email' => $sessionstaffnew['customer_info']['email'],
                            'Staff.staff_email !=' => '',
                            'Staff.clinic_id' => $sessionstaff['clinic_id']
                        )
                    ));
    }
}

?>
