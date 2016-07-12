<?php

/**
 *  This file for show all redemption done by legacy patient and buzzydoc patient and option to redeem through amazon and change status of redeem.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller for show all redemption done by legacy patient and buzzydoc patient and option to redeem through amazon and change status of redeem.
 */
class RedeemController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session and apiAmozon component for this controller.
     * @var type 
     */
    public $components = array('Session', 'ApiAmazon');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Transaction', 'Clinic', 'Notification', 'User', 'Staffs', 'Rewards');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }

    /**
     * This is default index page for this module.
     */
    public function index() {
        $this->layout = "adminLayout";
    }

    /**
     *  get the list of all redeemed transaction.
     */
    public function getRedeem() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('', 'transactions.id', 'transactions.first_name', 'clinics.api_user', 'transactions.authorization', 'transactions.amount', 'transactions.date', 'transactions.status', '');
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
            $sWhere = "WHERE (CONCAT(transactions.first_name, ' ', transactions.last_name) LIKE '%" . $_GET['sSearch'] . "%' OR clinics.api_user LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.id LIKE '%" . $_GET['sSearch'] . "%'   OR transactions.id LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.amount LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.date LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.status LIKE '%" . $_GET['sSearch'] . "%'  OR transactions.authorization LIKE '%" . $_GET['sSearch'] . "%')";
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
            $sWhere .=" WHERE activity_type='Y' and redeem_from=0";
        } else {
            $sWhere .=" AND activity_type='Y' and redeem_from=0";
        }

        $sQuery = "SELECT transactions.id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,clinics.api_user,transactions.authorization,transactions.date,transactions.amount,transactions.reward_id,transactions.status FROM   $sTable inner join users on users.id=$sTable.user_id inner join clinics on clinics.id=$sTable.clinic_id $sWhere $sOrder $sLimit";

        $rResult = $this->Transaction->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT transactions.id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,clinics.api_user,transactions.authorization,transactions.reward_id,transactions.date,transactions.amount,transactions.status FROM   $sTable inner join users on users.id=$sTable.user_id inner join clinics on clinics.id=$sTable.clinic_id $sWhere $sOrder";
        $aResultFilterTotal = $this->Transaction->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);


        $sQuery = "select transactions.id,CONCAT(transactions.first_name, ' ', transactions.last_name) AS first_name,clinics.api_user,transactions.authorization,transactions.date,transactions.reward_id,transactions.amount,transactions.status from $sTable inner join users on users.id=$sTable.user_id inner join clinics on clinics.id=$sTable.clinic_id where activity_type='Y' and redeem_from=0";

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
            $row = array();
            if (isset($rewardInfo['Rewards']['amazon_id']) && ($rewardInfo['Rewards']['amazon_id'] != Null)) {
                $amazon_id = $rewardInfo['Rewards']['amazon_id'];
            }

            $row = array();
            if ($amazon_id != '') {
                $amazon = 'amazon';
                $row[] = "<input type='checkbox' class='ajax-amazon'  value='" . $val['transactions']['id'] . "' >&nbsp;<img src='" . CDN . "img/amazon-logo.jpg' >";
            } else {
                $normal = 'normal';
                $row[] = "<input type='checkbox' class='ajax-normal'  value='" . $val['transactions']['id'] . "' >";
            }


            $row[] = $val['transactions']['id'];
            $row[] = $val['0']['first_name'];
            $row[] = $val['clinics']['api_user'];
            $row[] = $val['transactions']['authorization'];
            $row[] = ltrim($val['transactions']['amount'], '-') / 50;
            if ($val['transactions']['date'] != '0000-00-00 00:00:00') {
                $row[] = date('Y-m-d', strtotime($val['transactions']['date']));
            } else {
                $row[] = 'NA';
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
            $row[] = $redstatus;
            $row[] = "<a class='btn btn-xs btn-info' title='View Redeem' href='/Redeem/view/" . $val['transactions']['id'] . "'  ><i class='ace-icon fa fa-search-plus bigger-110'></i></a>";
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }

    /**
     * @deprecated
     * @param type $sort_by
     * @param type $sort
     */
    public function sort($sort_by, $sort) {
        $this->paginate = array(
            'limit' => 10,
            'order' => array('Transaction.' . $sort_by => $sort)
        );
        if (!empty($this->params['pass'])) {
            $this->set('sortname', $this->params['pass']['0']);
            $this->set('sort', $this->params['pass']['1']);
        }
        $clients = $this->paginate('Transaction');
        $this->set('redeem', $clients);
        $this->render('/Redeem/index');
    }

    /**
     *  show the detail of redeemed transaction and update the status of redemption.
     * @param type $id
     * @throws NotFoundException
     */
    public function view($id = null) {
        $this->layout = "adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid redemption'));
        } else {
            if (isset($this->request->data['Transaction']['action']) && $this->request->data['Transaction']['action'] == 'update') {

                $redeem['Transaction'] = array('id' => $this->request->data['Transaction']['id'], 'status' => $this->request->data['Transaction']['status']);
                $this->Transaction->save($redeem);
                $redinfo = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.id' => $this->request->data['Transaction']['id']
                )));
                //fetching the detail of notification setting for earn point mail
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
                    $link1 = str_replace('[order_number]', $this->request->data['Transaction']['id'], $link);
                    $link2 = str_replace('[username]', $pat['User']['first_name'], $link1);
                    $link3 = str_replace('[status]', $this->request->data['Transaction']['status'], $link2);
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


                $this->Session->setFlash('Redemption status is successfully updated.', 'default', array(), 'good');
                $redeem = $this->Transaction->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinics',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinics.id = Transaction.clinic_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'Transaction.id' => $id
                    ),
                    'fields' => array('Transaction.*', 'Clinics.api_user'),
                    'order' => array('Transaction.date desc')
                ));
                $this->set('red', $redeem);
            } else {

                $redeem = $this->Transaction->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinics',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinics.id = Transaction.clinic_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'Transaction.id' => $id
                    ),
                    'fields' => array('Transaction.*', 'Clinics.api_user'),
                    'order' => array('Transaction.date desc')
                ));
                if (!$redeem) {
                    throw new NotFoundException(__('Invalid redemption'));
                }
                $this->set('red', $redeem);
            }
        }
    }

    /**
     * Function to change bulk status for redemed transactions.
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
                    //check the notification for order status for patient is on or not.
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
     * This function is use for purchase rewards from amazon cart.
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
     * Function to change status for redemed transactions.
     */
    public function changeredeemstatusxml() {
        $this->layout = null;
        $status_name = $this->request->data['status'];

        $redeem['Transaction'] = array('id' => $this->request->data['id'], 'status' => $this->request->data['status']);
        
        if (($this->request->data['id'] != '') && ($this->request->data['status'] != '')) {


            if ($this->Transaction->save($redeem)) {

                $redinfo = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.id' => $this->request->data['id']
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
     * Function for change xml to array.
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

}

?>
