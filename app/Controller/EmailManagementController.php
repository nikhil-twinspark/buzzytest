<?php

/**
 *  This file for edit, manage email template and send preview email.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'php-bandwidth-master/source/Catapult');

/**
 *  This controller for edit, manage email template and send preview email.
 */
class EmailManagementController extends AppController {

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
    public $uses = array('Clinic', 'EmailTemplate', 'EmailList');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }

    /**
     * default page for this module.
     */
    public function index() {
        $this->layout = "adminLayout";
    }

    /**
     *  get the email template list.
     */
    public function getEmail() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('email_templates.email_for');
        $sIndexColumn = "id";
        $sTable = 'email_templates';

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
            $sWhere = "WHERE  email_lists.name LIKE '%" . $_GET['sSearch'] . "%'";
        }


        $sQuery = "SELECT * FROM $sTable left join email_lists on email_lists.id=$sTable.email_for  $sWhere $sOrder $sLimit";
        $rResult = $this->EmailTemplate->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT * FROM $sTable left join email_lists on email_lists.id=$sTable.email_for $sWhere $sOrder";
        $aResultFilterTotal = $this->EmailTemplate->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);
        $sQuery = "SELECT * FROM $sTable left join email_lists on email_lists.id=$sTable.email_for";
        $aResultTotal = $this->EmailTemplate->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        //print_r($rResult);die;
        foreach ($rResult as $val) {

            $row = array();
            $row[] = $val['email_lists']['name'];
            $str = "<a class='btn btn-xs btn-info' title='Edit Email Template' href='/EmailManagement/edit/" . $val['email_templates']['id'] . "'  ><i class='ace-icon glyphicon glyphicon-pencil'></i></a>";
            //&nbsp;<a title='Edit Email For' href='/EmailManagement/edit_email_for/" . $val['email_lists']['id'] . "'  >Change Email For</a>

            $row[] = $str;
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }

    /**
     *  add new email template.
     */
    public function add() {
        $this->layout = "adminLayout";
        $email_get = $this->EmailTemplate->find('all', array(
            'fields' => array('EmailTemplate.email_for')
        ));
        foreach ($email_get as $get) {
            $earr[] = $get['EmailTemplate']['email_for'];
        }
        $commaList = implode(', ', $earr);
        $emaillist = $this->EmailList->query('SELECT * FROM email_lists WHERE id NOT IN (' . $commaList . ')');
        $this->set('email_list', $emaillist);
        if ($this->request->is('post')) {
            if ($this->EmailTemplate->save($this->request->data)) {
                $this->Session->setFlash('The email template has been added', 'default', array(), 'good');
                $this->redirect(array('action' => "index"));
            } else {
                $this->Session->setFlash('The email template could not be saved. Please, try again.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * Edit existing email template.
     * @param type $id
     * @throws NotFoundException
     */
    public function edit($id = null) {
        $this->layout = "adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $clientData = $this->EmailTemplate->find('first', array(
            'joins' => array(
                array(
                    'table' => 'email_lists',
                    'alias' => 'EmailList',
                    'type' => 'INNER',
                    'conditions' => array(
                        'EmailList.id = EmailTemplate.email_for'
                    )
                )
            ),
            'conditions' => array(
                'EmailTemplate.id' => $id),
            'fields' => array('EmailTemplate.*', 'EmailList.*')
        ));

        if (!$clientData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            unset($this->request->data['EmailTemplate']['textname']);
            $this->request->data['EmailTemplate']['updated_on'] = date('Y-m-d H:i:s');
            if ($this->EmailTemplate->save($this->request->data)) {

                $this->Session->setFlash('The email template has been saved', 'default', array(), 'good');
                $this->redirect(array('action' => "edit/$id"));
            } else {
                $this->Session->setFlash('The email template could not be saved. Please, try again.', 'default', array(), 'bad');
            }
        } else {

            $this->request->data = $clientData;
        }
    }

    /**
     * @deprecated its only when want to change email for 
     * @param type $id
     * @throws NotFoundException
     */
    public function edit_email_for($id = null) {
        $this->layout = "adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $clientData = $this->EmailList->find('first', array(
            'conditions' => array(
                'EmailList.id' => $id)
        ));

        if (!$clientData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->EmailList->save($this->request->data)) {

                $this->Session->setFlash('The email for has been saved', 'default', array(), 'good');
                $this->redirect(array('action' => "index"));
            } else {
                $this->Session->setFlash('The email for could not be saved. Please, try again.', 'default', array(), 'bad');
            }
        } else {

            $this->request->data = $clientData;
        }
    }

    /**
     *  send email template preview to defind email id.
     */
    public function emailPreview() {
        $this->layout = null;
        //condition to send particular email template
        if ($_POST['data']['EmailTemplate']['email_for'] == 22) {
            $reward_array[0]['reward_name'] = '[Reward Name]';
            $reward_array[0]['reward_image'] = "<img src='" . CDN . "img/$25 Gift Coupon.png' height='140' width='160'>";
            $Email = new CakeEmail(MAILTYPE);
            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
            $Email->to(trim($_POST['emailTo']));
            $Email->subject($_POST['data']['EmailTemplate']['subject'])
                    ->template('rewardmail')
                    ->emailFormat('html');

            $Email->viewVars(array('msg' => $_POST['data']['EmailTemplate']['content'],
                'reward_detail' => $reward_array,
                'link' => $_POST['data']['EmailTemplate']['header_msg'],
            ));
            $Email->send();
        } else {
            $orderdetail = array();

            $Emailadmin = new CakeEmail(MAILTYPE);
            $Emailadmin->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
            $Emailadmin->to(trim($_POST['emailTo']));
            $Emailadmin->subject($_POST['data']['EmailTemplate']['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');
            if ($_POST['data']['EmailTemplate']['email_for'] == 16) {
                $orderdetail = array('Clinic Name' => '[Clinic Name]', 'Coupon' => '[Coupon]', 'Description' => '[Coupon Description]', 'Coupon Image' => '<img src="' . CDN . 'img/$25 Gift Coupon.png" height="136" width="200">');
                $Emailadmin->viewVars(array('msg' => $_POST['data']['EmailTemplate']['content'],
                    'orderdetails' => $orderdetail
                ));
            } else if ($_POST['data']['EmailTemplate']['email_for'] == 13) {
                $orderdetail = array('Order Number' => '[Order Number]', 'Redeemed From' => '[Redeemed From]', 'Product/Service' => '[Product/Service]', 'Points Redeemed' => '[Points Redeemed]');
                $Emailadmin->viewVars(array('msg' => $_POST['data']['EmailTemplate']['content'],
                    'orderdetails' => $orderdetail
                ));
            } else {
                $Emailadmin->viewVars(array('msg' => $_POST['data']['EmailTemplate']['content']
                ));
            }

            $Emailadmin->send();
        }


        echo 1;
        exit;
    }

    public function smsPreview() {
        $this->layout = null;
        $cred = new Catapult\Credentials(BANDWIDTH_USER_ID, BANDWIDTH_API_TOKEN, BANDWIDTH_API_SECRET);
        $client = new Catapult\Client($cred);
        try {
            $messageSend = new Catapult\Message(array(
                "from" => new Catapult\PhoneNumber('+12054199750'),
                "to" => new Catapult\PhoneNumber(COUNTRY_CODE . $_POST['smsTo']),
                "text" => new Catapult\TextMessage($_POST['data']['EmailTemplate']['sms_body'])
            ));
        } catch (\CatapultApiException $e) {
        }
        echo 1;
        exit;
    }

}

?>
