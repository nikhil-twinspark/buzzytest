<?php
/**
 * This file for add fund to tango account and refresh the balance of tango.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add fund to tango account and refresh the balance of tango.
 */
class TangoAccountManagementController extends AppController {
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
    public $uses = array('Clinic', 'TangoAccount');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');
        //condition to check admin have loged in or not.
        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     * This is default index file for this module.
     */
    public function index() {
        $this->layout = "adminLayout";
        //get the tango account fund detail.
        $TangoAccount = $this->TangoAccount->find('all');
        $this->set('TangoAccount', $TangoAccount);
    }
    /**
     * Adding fund to tango account by super admin.
     * @param type $id
     */
    public function addfund($id) {
        $this->layout = "adminLayout";
        $tangodetails = $this->TangoAccount->find('first', array('conditions' => array('TangoAccount.id' => $id)));
        $this->set('tangodetail', $tangodetails);
        if ($this->request->is('post')) {
            $tangocard = new Sourcefuse\TangoCard(PLATFORM_ID, PLATFORM_KEY);
            $tangocard->setAppMode(TANGO_MODE);
            //add a fund to tango account using tango api.
            $response = $tangocard->fundAccount($this->request->data['customer'], $this->request->data['identifier'], (float) $this->request->data['amount'] * 100, $this->request->data['cc_tokan'], $this->request->data['cvv'], '0');
            //if fund have succssfully added then mail goes to inna.kuperman@tangocard.com.
            if ($response->success == '1') {

                $template_array = $this->Api->get_template(30);
                $link = str_replace('[amount]', $this->request->data['amount'], $template_array['content']);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                $Email->to('inna.kuperman@tangocard.com');
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array('msg' => $link
                ));
                $Email->send();
                $getbalance = $tangocard->getAccountInfo($this->request->data['customer'], $this->request->data['identifier']);
                $this->TangoAccount->query('update tango_accounts set available_balance=' . ($getbalance->account->available_balance / 100) . ' where customer="' . $this->request->data['customer'] . '"');
                $this->Session->write('Admin.aval_bal', $getbalance->account->available_balance / 100);
                $this->Session->setFlash('Payment Send to Tango.', 'default', array(), 'good');
                $this->redirect(array('action' => "index"));
            } else {
                $this->Session->setFlash($response->denial_message, 'default', array(), 'bad');
            }
        }
    }
    /**
     * Function to update tango fund to our system.
     */
    public function updatefund() {
        $this->layout = "adminLayout";

        if ($this->request->is('post')) {

            $tangocard = new Sourcefuse\TangoCard(PLATFORM_ID, PLATFORM_KEY);

            $tangocard->setAppMode(TANGO_MODE);

            //fetch the tango fund using tango api.
            $response = $tangocard->getAccountInfo($_POST['customer'], $_POST['identifier']);
            if ($response->success == '1') {
                $av_bal=$response->account->available_balance / 100;
                $this->TangoAccount->query('update tango_accounts set available_balance=' . $av_bal . ' where customer="' . $_POST['customer'] . '"');
                echo $av_bal;
            } else {
                echo 0;
            }
        }
        die;
    }

}

?>
