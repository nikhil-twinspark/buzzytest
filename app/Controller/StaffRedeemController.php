<?php

/**
 * This file for getting the list of all practice's redemption list and option to change status for that.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for getting the list of all practice's redemption list and option to change status for that.
 */
class StaffRedeemController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session,api and apiAmazon component for this controller.
     * @var type 
     */
    public $components = array('Session', 'ApiAmazon', 'Api');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Transaction', 'Clinic', 'Notification', 'User', 'Staffs', 'Rewards', 'Refer', 'GlobalRedeem', 'TrainingVideo', 'RateReview','ClinicNotification');

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
     * This is default index page for this module and update the notification to new redemption is done.
     */
    public function index($id) {
        $sessionstaff = $this->Session->read('staff');
        $redecnt = $this->ClinicNotification->query('update clinic_notifications set status="1" where clinic_id=' . $sessionstaff['clinic_id'].' and notification_type=1');
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Redeems');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }

    /**
     * Getting the list of redemption for legacy practice.
     */
    public function getRedeem() {
        $sessionstaff = $this->Session->read('staff');

        $this->layout = '';
        $aColumns = array('', 'transactions.card_number', 'transactions.first_name', 'transactions.authorization', 'transactions.amount', 'transactions.date', 'transactions.status');
        $sIndexColumn = "id";
        $sTable = 'transactions';

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
            $sWhere = "WHERE (CONCAT(transactions.first_name, ' ', transactions.last_name) LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.card_number LIKE '%" . $_GET['sSearch'] . "%'   OR transactions.id LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.amount LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.date LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.status LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.authorization LIKE '%" . $_GET['sSearch'] . "%')";
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
            $sWhere .=" WHERE transactions.clinic_id=" . $sessionstaff['clinic_id'] . " and transactions.activity_type='Y' and transactions.status!='New' and transactions.redeem_from=0";
        } else {
            $sWhere .=" AND transactions.clinic_id=" . $sessionstaff['clinic_id'] . " and transactions.activity_type='Y' and transactions.status!='New' and transactions.redeem_from=0";
        }
        if ($sessionstaff['is_buzzydoc'] == 1) {
            $sWhere .=" AND transactions.status !='Ordered/Shipped'";
        }
        $sQuery = "SELECT transactions.id,transactions.card_number,transactions.reward_id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,transactions.authorization,transactions.date,transactions.amount,transactions.status FROM   $sTable inner join users on users.id=$sTable.user_id $sWhere $sOrder $sLimit";

        $rResult = $this->Transaction->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT transactions.id,transactions.card_number,transactions.reward_id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,transactions.authorization,transactions.date,transactions.amount,transactions.status FROM   $sTable inner join users on users.id=$sTable.user_id $sWhere $sOrder";
        $aResultFilterTotal = $this->Transaction->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);



        $sQuery = "select transactions.id,transactions.card_number,transactions.reward_id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,transactions.authorization,transactions.date,transactions.amount,transactions.status from $sTable inner join users on users.id=$sTable.user_id where clinic_id=" . $sessionstaff['clinic_id'] . " and activity_type='Y'and transactions.status !='Ordered/Shipped' and transactions.redeem_from=0";
        $aResultTotal = $this->Transaction->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        foreach ($rResult as $val) {
            $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.id' => $val['transactions']['reward_id'])));
            $amazon_id = '';
            if (isset($rewardInfo['Rewards']['amazon_id']) && ($rewardInfo['Rewards']['amazon_id'] != Null)) {
                $amazon_id = $rewardInfo['Rewards']['amazon_id'];
            }

            $row = array();
            if ($amazon_id != '') {
                $amazon = 'amazon';
                $row[] = "<input type='checkbox' class='ajax-amazon'  value='" . $val['transactions']['id'] . "' >";
            } else {
                $normal = 'normal';
                $row[] = "<input type='checkbox' class='ajax-normal'  value='" . $val['transactions']['id'] . "' >";
            }
            $redstatus = "<select name='redeem_status_" . $val['transactions']['id'] . "' id='redeem_status_" . $val['transactions']['id'] . "' onchange='changestatus(" . $val['transactions']['id'] . ")'>
					<option value=''>Select Status</option>
					<option value='Redeemed' ";
            if ($val['transactions']['status'] == 'Redeemed') {
                $redstatus .=" selected='selected'";
            }
            $redstatus .=" >Redeemed</option>
					<option value='In Office' ";
            if ($val['transactions']['status'] == 'In Office') {
                $redstatus .=" selected='selected'";
            }
            $redstatus .=" >In Office</option>
					<option value='Ordered/Shipped' ";
            if ($val['transactions']['status'] == 'Ordered/Shipped') {
                $redstatus .=" selected='selected'";
            }
            $redstatus .=" >Ordered/Shipped</option>
				</select>";
            $row[] = $val['transactions']['card_number'];
            $row[] = "<p style='word-wrap: break-word;'>" . $val['0']['first_name'] . "</p>";
            $row[] = $val['transactions']['authorization'];
            $row[] = (ltrim($val['transactions']['amount'], '-'));
            if ($val['transactions']['date'] != '0000-00-00 00:00:00') {
                $row[] = date('Y-m-d', strtotime($val['transactions']['date']));
            } else {
                $row[] = 'NA';
            }
            $row[] = $redstatus;
            $row[] = "<a title='View Redeem' href='/StaffRedeem/view/" . $val['transactions']['id'] . "'   class='btn btn-xs btn-info'><i class='ace-icon fa fa-search-plus bigger-110'></i></a>";
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }

    /**
     * @deprecated
     */
    public function amazoncartcreate() {
        $this->layout = '';
        $amazon_prd_sku = $this->request->data['amazon_prd_sku'];
        $amazonProducts = explode(',', $amazon_prd_sku);
        $orderItems = array('Operation' => 'CartCreate');

        $aid = array();
        foreach ($amazonProducts as $amazonProductSku) {
            $sQuery = "select rewards.amazon_id from rewards inner join transactions where  rewards.id=transactions.reward_id and transactions.id='" . $amazonProductSku . "'";
            $amazon_prd_id = $this->Transaction->query($sQuery);
            $aid[] = $amazon_prd_id[0]['rewards']['amazon_id'];
        }
        $amazon_array = array_count_values($aid);
        $i = 1;
        foreach ($amazon_array as $amazonProductSku => $cnt) {


            $orderItems["Item.$i.ASIN"] = $amazonProductSku;
            $orderItems["Item.$i.Quantity"] = $cnt;
            $i++;
        }
        $request = $this->ApiAmazon->aws_signed_request('com', $orderItems, PUBLIC_KEY, PRIVATE_KEY, ASSOCIATE_TAG);
        $amazonResponse = array();
        $response = @file_get_contents($request);
        if ($response === FALSE) {
            $amazonResponse['success'] = 'false';
        } else {
            $pxml = simplexml_load_string($response);
            $xml2array = $this->xml2array($pxml);
        }
        if ($xml2array['Cart'][0]['Request'][0]['IsValid'] == 'True') {
            $amazonResponse['success'] = 'true';
            $amazonResponse['PurchaseURL'] = $xml2array['Cart'][0]['PurchaseURL'];
            $amazonResponse['FormattedPrice'] = $xml2array['Cart'][0]['SubTotal'][0]['FormattedPrice'];
        } else {
            $amazonResponse['success'] = 'false';
        }
        echo json_encode($amazonResponse);
        exit;
    }

    /**
     * @deprecated
     */
    public function bulkupdate() {
        $transaction_id = $this->request->data['transaction_id'];
        $transaction_status = $this->request->data['transaction_status'];

        if (($transaction_id != '') && ($transaction_status != '')) {

            $transactionId = explode(",", $transaction_id);
            $update_response = 'false';
            foreach ($transactionId as $transval) {
                $redeem = array();
                $redeem['Transaction'] = array('id' => $transval, 'status' => $transaction_status);

                if ($this->Transaction->save($redeem)) {
                    $update_response = 'true';

                    $redinfo = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.id' => $transval
                    )));

                    $patients = $this->Notification->find('all', array(
                        'joins' => array(
                            array(
                                'table' => 'users',
                                'alias' => 'Users',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'Users.id = Notification.user_id'
                                )
                            ),
                            array(
                                'table' => 'clinic_users',
                                'alias' => 'clinic_users',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'clinic_users.user_id = Users.id'
                                )
                            ),
                            array(
                                'table' => 'clinics',
                                'alias' => 'Clinics',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'Clinics.id = clinic_users.clinic_id'
                                )
                            )
                        ),
                        'conditions' => array(
                            'Notification.order_status' => 1, 'Clinics.id' => $redinfo['Transaction']['clinic_id'], 'Users.id' => $redinfo['Transaction']['user_id']
                        ),
                        'fields' => array('Users.*', 'Clinics.*', 'clinic_users.card_number'),
                        'group' => array('clinic_users.user_id')
                    ));

                    foreach ($patients as $pat) {
                        $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($redinfo['Transaction']['user_id']) . "/Unsubscribe";

                        $template_array = $this->Api->get_template(24);
                        $link = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $template_array['content']);
                        $link1 = str_replace('[order_number]', $transval, $link);
                        $link2 = str_replace('[username]', $pat['Users']['first_name'], $link1);
                        $link3 = str_replace('[status]', $transaction_status, $link2);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                        $Email->to($pat['Users']['email']);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array('msg' => $link3
                        ));
                        $Email->send();
                    }
                }
            }//for end here

            if ($update_response == 'true') {
                echo '1';
                exit;
            } else {
                echo '2';
                exit;
            }
        }
    }

    /**
     * Function for convert xml to array.
     * @param type $xml
     * @return type
     */
    public function xml2array($xml) {

        $arr = array();
        foreach ($xml->children() as $r) {
            $t = array();
            if (count($r->children()) == 0) {
                $arr[$r->getName()] = strval($r);
            } else {
                $arr[$r->getName()][] = $this->xml2array($r);
            }
        }
        return $arr;
    }

    /**
     * This action view the redemption details and can change status.
     * @param type $id
     * @throws NotFoundException
     */
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid redemption'));
        } else {
            $sessionstaff = $this->Session->read('staff');
            $clinic_id = $this->passedArgs[0];
            $id = $this->passedArgs[1];
            $table = 'Transaction';
            if (isset($this->request->data[$table]['action']) && $this->request->data[$table]['action'] == 'update' && $this->request->data[$table]['clinic_id']) {
                $redeem[$table] = array('id' => $this->request->data[$table]['id'], 'status' => $this->request->data[$table]['status']);

                $this->$table->save($redeem);
                unset($redeem[$table]);
                $globalRedeemData = $this->GlobalRedeem->find('first', array(
                    'conditions' => array('GlobalRedeem.transaction_id' => $this->request->data[$table]['id'], 'GlobalRedeem.clinic_id' => $this->request->data[$table]['clinic_id'])
                ));

                if ($globalRedeemData) {
                    $redeem['GlobalRedeem'] = array('id' => $globalRedeemData['GlobalRedeem']['id'], 'status' => $this->request->data[$table]['status']);
                    $this->GlobalRedeem->save($redeem);
                }

                $redinfo = $this->$table->find('first', array(
                    'conditions' => array(
                        $table . '.id' => $this->request->data[$table]['id']
                )));
                //checking the notification setting for patient for order status change.
                $patients = $this->Notification->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'Users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Users.id = Notification.user_id'
                            )
                        ),
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = Users.id'
                            )
                        ),
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinics',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinics.id = clinic_users.clinic_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'Notification.order_status' => 1, 'Clinics.id' => $this->request->data[$table]['clinic_id'], 'Users.id' => $redinfo[$table]['user_id']
                    ),
                    'fields' => array('Users.*', 'Clinics.*', 'clinic_users.card_number'),
                    'group' => array('clinic_users.user_id')
                ));

                foreach ($patients as $pat) {
                    $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($redinfo[$table]['user_id']) . "/Unsubscribe";
                    $template_array = $this->Api->get_template(24);
                    $link = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $template_array['content']);
                    $link1 = str_replace('[order_number]', $this->request->data[$table]['id'], $link);
                    $link2 = str_replace('[username]', $pat['Users']['first_name'], $link1);
                    $link3 = str_replace('[status]', $this->request->data[$table]['status'], $link2);
                    
                    $Email = new CakeEmail(MAILTYPE);
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                    $Email->to($pat['Users']['email']);
                    $Email->subject($template_array['subject'])
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array('msg' => $link3
                    ));
                    $Email->send();
                }


                $this->Session->setFlash('The redeem has been updated.', 'default', array(), 'good');
            }
            $redeem = $this->$table->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'global_redeems',
                        'alias' => 'global_redeems',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'global_redeems.transaction_id = ' . $table . '.id'
                        )
                    ),
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinics',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinics.id = ' . $table . '.clinic_id'
                        )
                    ),
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id =  ' . $table . '.user_id'
                        )
                    ),
                    array(
                        'table' => 'staffs',
                        'alias' => 'Staffs',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Staffs.id = ' . $table . '.staff_id'
                        )
                    )
                ),
                'conditions' => array(
                    $table . '.id' => $id,
                    'clinic_users.clinic_id' => $clinic_id
                ),
                'fields' => array($table . '.*', 'Clinics.api_user', 'Staffs.staff_id as staff_name', 'clinic_users.card_number'),
                'order' => array($table . '.date desc')
            ));

            if (empty($redeem)) {
                $redeem = $this->$table->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'global_redeems',
                            'alias' => 'global_redeems',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'global_redeems.transaction_id = ' . $table . '.id'
                            )
                        ),
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinics',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinics.id = global_redeems.clinic_id'
                            )
                        ),
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id =  ' . $table . '.user_id'
                            )
                        ),
                        array(
                            'table' => 'staffs',
                            'alias' => 'Staffs',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Staffs.id = ' . $table . '.staff_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        $table . '.id' => $id,
                        'clinic_users.clinic_id' => $clinic_id
                    ),
                    'fields' => array($table . '.*', 'Clinics.api_user', 'Staffs.staff_id as staff_name', 'clinic_users.card_number'),
                    'order' => array($table . '.date desc')
                ));
            }
            if (!$redeem) {
                throw new NotFoundException(__('Invalid redemption'));
            }
            $redeem = array_merge($redeem[$table], $redeem['Clinics'], $redeem['Staffs'], $redeem['clinic_users']);
            $redeem['clinic_id'] = $clinic_id;
            $this->set('red', $redeem);
        }
    }

    /**
     * Change redemption status from list page.
     */
    public function changeredeemstatusxml() {
        $this->layout = null;
        $status_name = $this->request->data['status'];

        if (($this->request->data['id'] != '') && ($this->request->data['status'] != '')) {

            $table = 'Transaction';
            $redeem[$table] = array('id' => $this->request->data['id'], 'status' => $this->request->data['status']);

            if ($this->$table->save($redeem)) {
                unset($redeem[$table]);
                $globalRedeemData = $this->GlobalRedeem->find('first', array(
                    'conditions' => array('GlobalRedeem.transaction_id' => $this->request->data['id'], 'GlobalRedeem.clinic_id' => $this->request->data['clinic_id'])
                ));

                if ($globalRedeemData) {
                    $redeem['GlobalRedeem'] = array('id' => $globalRedeemData['GlobalRedeem']['id'], 'status' => $this->request->data['status']);
                    $this->GlobalRedeem->save($redeem);
                }

                $redinfo = $this->$table->find('first', array(
                    'conditions' => array(
                        $table . '.id' => $this->request->data['id']
                )));
                //checking the notification setting for patient for order status change.
                $patients = $this->Notification->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'Users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Users.id = Notification.user_id'
                            )
                        ),
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'clinic_users',
                            'type' => 'INNER',
                            'conditions' => array(
                                'clinic_users.user_id = Users.id'
                            )
                        ),
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinics',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinics.id = clinic_users.clinic_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'Notification.order_status' => 1, 'Clinics.id' => $redinfo[$table]['clinic_id'], 'Users.id' => $redinfo[$table]['user_id']
                    ),
                    'fields' => array('Users.*', 'Clinics.*', 'clinic_users.card_number'),
                    'group' => array('clinic_users.user_id')
                ));

                foreach ($patients as $pat) {
                    $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($redinfo[$table]['user_id']) . "/Unsubscribe";

                    $template_array = $this->Api->get_template(24);
                    $link = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $template_array['content']);
                    $link1 = str_replace('[order_number]', $this->request->data['id'], $link);
                    $link2 = str_replace('[username]', $pat['Users']['first_name'], $link1);
                    $link3 = str_replace('[status]', $this->request->data['status'], $link2);
                    $Email = new CakeEmail(MAILTYPE);
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                    $Email->to($pat['Users']['email']);
                    $Email->subject($template_array['subject'])
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array('msg' => $link3
                    ));
                    $Email->send();
                }

                if (($status_name == 'Redeemed')) {
                    echo 1;
                } elseif (($status_name == 'In Office')) {
                    echo 2;
                } elseif (($status_name == 'Ordered/Shipped')) {
                    echo 3;
                } else {
                    echo 4;
                }
            }
        }


        exit;
    }

    /**
     * Fetch non legacy redemptions of clinic.
     */
    public function getnonlegacyredemptions() {
        $sessionstaff = $this->Session->read('staff');
        $data = $_GET;
        $this->layout = $iTotal = $iFilteredTotal = '';
        $sIndexColumn = "id";

        $transactionsTableData = $this->_getTransactions($data, 'transactions', $sessionstaff);
        $iTotal = $transactionsTableData['iTotal'] + $globalRedeemsTableData['iTotal'];
        $iFilteredTotal = $transactionsTableData['iFilteredTotal'] + $globalRedeemsTableData['iFilteredTotal'];
        //Output
        $output = array(
            "sEcho" => intval($data['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => $transactionsTableData['aaData']
        );

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }

    /**
     * function to get all non legacy redemption list.
     * @param array $data
     * @param string $sTable
     * @param type $sessionstaff
     * @return type
     */
    protected function _getTransactions($data, $sTable, $sessionstaff) {
        $sTable = 'transactions';
        $aColumns = array('', $sTable . '.card_number', $sTable . '.first_name', $sTable . '.authorization', $sTable . '.amount', $sTable . '.date', $sTable . '.status');
        $aaData = array();
        //Ordering
        if (isset($data['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($data['iSortingCols']); $i++) {
                if ($data['bSortable_' . intval($data['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($data['iSortCol_' . $i])] . "
				 	" . $data['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $data['sSearch'] = str_replace('%', '#$@19', $data['sSearch']);
        //Filtering
        $sWhere = "";
        if ($data['sSearch'] != "") {
            $sWhere = "WHERE (CONCAT(" . $sTable . ".first_name, ' ', " . $sTable . ".last_name) LIKE '%" . $data['sSearch'] . "%'  OR " . $sTable . ".card_number LIKE '%" . $data['sSearch'] . "%'   OR " . $sTable . ".id LIKE '%" . $data['sSearch'] . "%'  OR " . $sTable . ".amount LIKE '%" . $data['sSearch'] . "%'  OR " . $sTable . ".date LIKE '%" . $data['sSearch'] . "%'  OR " . $sTable . ".status LIKE '%" . $data['sSearch'] . "%'  OR " . $sTable . ".authorization LIKE '%" . $data['sSearch'] . "%')";
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($data['bSearchable_' . $i] == "true" && $data['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $data['sSearch_' . $i] . "%' ";
            }
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE (transactions.`clinic_id`=" . $sessionstaff['clinic_id'] . " OR global_redeems.`clinic_id`=" . $sessionstaff['clinic_id'] . ")   and clinic_users.clinic_id=" . $sessionstaff['clinic_id'] . " and " . $sTable . ".activity_type='Y' and " . $sTable . ".status!='New'";
        } else {
            $sWhere .=" AND (transactions.`clinic_id`=" . $sessionstaff['clinic_id'] . " OR global_redeems.`clinic_id`=" . $sessionstaff['clinic_id'] . ")  and clinic_users.clinic_id=" . $sessionstaff['clinic_id'] . " and " . $sTable . ".activity_type='Y' and " . $sTable . ".status!='New'";
        }
        $sWhere .=" AND " . $sTable . ".status !='Ordered/Shipped' and ".$sTable.".amount != 0";

        $sQuery = "SELECT
    `transactions`.id,
    IF(transactions.`clinic_id`=0,global_redeems.`clinic_id`,transactions.`clinic_id`) AS clinic_used,
      
  IF(transactions.`clinic_id` = 0, ROUND(global_redeems.`amount`), ROUND(transactions.`amount`) ) AS amount,
    transactions.product_service_id,
  product_services.type,
  clinic_users.card_number,
   transactions.reward_id,
    `transactions`.staff_id,
    transactions.`authorization`
    ,transactions.`date`,
    CONCAT(transactions.`first_name`,' ',transactions.`last_name`) AS first_name,
    transactions.`activity_type`,
    transactions.`redeem_notes_by_staff`,
    transactions.`status`
    
FROM `transactions`
    LEFT JOIN `global_redeems` 
        ON (`global_redeems`.`transaction_id` = `transactions`.`id`)
        
         INNER JOIN clinic_users 
    ON clinic_users.user_id = transactions.user_id 

  LEFT JOIN product_services 
    ON product_services.id = transactions.product_service_id  $sWhere and $sTable.amount != 0 ORDER by transactions.id desc";

        $rResult = $this->Transaction->query($sQuery);
        $iFilteredTotal = count($rResult);
        $iTotal = count($rResult);

        foreach ($rResult as $val) {
            $amazon_id = '';
            if ($val[$sTable]['reward_id']) {
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.id' => $val[$sTable]['reward_id'])));
                if (isset($rewardInfo['Rewards']['amazon_id']) && ($rewardInfo['Rewards']['amazon_id'] != Null)) {
                    $amazon_id = $rewardInfo['Rewards']['amazon_id'];
                }
            }


            $row = array();
            if ($amazon_id != '') {
                $amazon = 'amazon';
                $row[] = "<input type='checkbox' class='ajax-amazon'  value='" . $val[$sTable]['id'] . "' >";
            } else {
                $normal = 'normal';
                $row[] = "<input type='checkbox' class='ajax-normal'  value='" . $val[$sTable]['id'] . "' >";
            }
            if ($val['product_services']['type'] == 3 && $val['transactions']['status'] != 'Redeemed') {
                $redstatus = "<select name='redeem_status_" . $val[$sTable]['id'] . "' id='redeem_status_" . $val[$sTable]['id'] . "' onchange=changestatus(" . $val[$sTable]['id'] . ",'" . $sessionstaff['clinic_id'] . "')>
					<option value='Active' selected>Active </option>
                    <option value='Redeemed'>Redeemed </option>
				</select>";
            } else {
                $redstatus = "<select disabled name='redeem_status_" . $val[$sTable]['id'] . "' id='redeem_status_" . $val[$sTable]['id'] . "' onchange=changestatus(" . $val[$sTable]['id'] . ",'" . $sessionstaff['clinic_id'] . "')>
					               <option value='Redeemed'>Redeemed </option>
				            </select>";
            }

            $row[] = $val['clinic_users']['card_number'];
            $row[] = "<p style='word-wrap: break-word;'>" . $val['0']['first_name'] . "</p>";
            $row[] = $val[$sTable]['authorization'];
            $row[] = (ltrim($val['0']['amount'], '-'));
            if ($val[$sTable]['date'] != '0000-00-00 00:00:00') {
                $row[] = date('Y-m-d', strtotime($val[$sTable]['date']));
            } else {
                $row[] = 'NA';
            }
            $row[] = $redstatus;
            $row[] = "<a title='View Redeem' href='/StaffRedeem/view/" . $val[0]['clinic_used'] . '/' . $val[$sTable]['id'] . "'   class='btn btn-xs btn-info'><i class='ace-icon fa fa-search-plus bigger-110'></i></a>";
            $aaData[] = $row;
        }

        return array('iTotal' => $iTotal, 'iFilteredTotal' => $iFilteredTotal, 'rResult' => $rResult, 'aaData' => $aaData);
    }

}

?>
