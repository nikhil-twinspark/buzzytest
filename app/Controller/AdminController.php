<?php
/**
 * This file for super admin login,logout and getting all clinic fund details.
 * 
 * 
 */


App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This is for super admin login,logout and getting all clinic fund details.
 * 
 * 
 */
class AdminController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    
    /**
     * We use the session and security componnents for this controller.
     * @var type 
     */
    public $components = array('Session','Security');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Staff', 'Clinic', 'ProfileField', 'Admin', 'TangoAccount', 'BeanstreamPayment', 'Invoice', 'Transaction', 'GlobalRedeem','TrainingVideo');

    /**
     * security check when login and redirect to login page when session expire.
     * 
     */
    public function beforeFilter() {
        
        $sessionadmin = $this->Session->read('Admin');
        $this->Security->validatePost = false;
        $this->Security->unlockedActions=array('logout','clinicfund','managebalance','changeredeemstatusxml');
        $this->Security->blackHoleCallback = 'blackhole';

        $sessionadmin = $this->Session->read('Admin');
        if (empty($sessionadmin) && $this->params['controller'] != 'Admin' && $this->params['action'] != 'login' && $this->params['action'] != 'clinicfund' && $this->params['action'] != 'managebalance') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }

    }
    /**
     * checking the any blackhole while login.
     * @param type $type
     * @return type
     */
    public function blackhole ($type)
  {
    $this->log ('Request has been blackholed: ' . $type, 'tests');
    $this->Session->setFlash(__('Looks like you attempted to pass that request incorrectly.Please refresh the page and try again.'));
    return $this->redirect(array('controller' => 'admin', 'action' => 'login')); 
  }

  /**
   * super admin login.
   * @return type
   */
    public function login() {
        if ($this->request->is('post')) {
            $options1['conditions'] = array('Admin.username' => $this->request->data['login']['admin_name'], 'BINARY (`Admin`.`password`) LIKE' => md5($this->request->data['login']['admin_password']), 'Admin.admin_role' => 'main');
            $AdminResult = $this->Admin->find('first', $options1);

            if (!empty($AdminResult)) {
                
                //fetch all default profile fields and store to session
                $ProField = $this->ProfileField->query('SELECT `ProfileField`.`id`, `ProfileField`.`profile_field`, `ProfileField`.`type`, `ProfileField`.`options`, `ProfileField`.`clinic_id` FROM `profile_fields` AS `ProfileField` WHERE `ProfileField`.`clinic_id` = 0');
                
                // fetch tango card detail and store to session
                $tango = $this->TangoAccount->find('first');

                $this->Session->write('admin.ProfileField', $ProField);
                $this->Session->write('Admin.loginName', $this->request->data['login']['admin_name']);
                $this->Session->write('Admin.loginPassword', $this->request->data['login']['admin_password']);
                $this->Session->write('Admin.aval_bal', $tango['TangoAccount']['available_balance']);
                setcookie('serachVal', '', time() + (86400 * 30), "/");
                return $this->redirect(array('controller' => 'ClientManagement', 'action' => 'index'));
            }
            $this->Session->setFlash(__('Invalid Credentials'));
        }
    }
    /**
     * logut super admin and destroy all session value.
     * @return type
     */
    public function logout() {
        
        $this->Session->destroy();
        $this->Session->delete('Admin');
        return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
    }

    /**
     *  get the all buzzydoc clinic fund (minimum deposit).
     */
    public function clinicfund() {
        
        $this->layout = "adminLayout";
        
        $clinic = $this->Clinic->find('all', array(
            'conditions' => array(
                'Clinic.is_buzzydoc' => 1,
                'Clinic.minimum_deposit >' => 0
            ),
            'fields' => array('Clinic.id', 'Clinic.api_user', 'Clinic.minimum_deposit', 'Clinic.display_name'),
            'order' => array('Clinic.api_user asc')
        ));
        $invoicear = array();
        $i = 0;
        foreach ($clinic as $cl) {
            if ($cl['Clinic']['display_name'] == '') {
                $invoicear[$i]['Invoice']['clinic_name'] = $cl['Clinic']['api_user'];
            } else {
                $invoicear[$i]['Invoice']['clinic_name'] = $cl['Clinic']['display_name'];
            }

            $getglbpoint = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.clinic_id' => $cl['Clinic']['id'],
                    'Transaction.is_buzzydoc' => 1
                ),
                'group' => array('Transaction.clinic_id'),
                'fields' => array('sum(Transaction.amount) AS total')
            ));

            $getglberedem = $this->GlobalRedeem->find('first', array(
                'conditions' => array(
                    'GlobalRedeem.clinic_id' => $cl['Clinic']['id'],
                    'GlobalRedeem.is_buzzydoc' => 1
                ),
                'group' => array('GlobalRedeem.clinic_id'),
                'fields' => array('sum(GlobalRedeem.amount) AS total,GlobalRedeem.clinic_id')
            ));

            if (!empty($getglbpoint)) {
                $totalred = $getglbpoint[0]['total'];
            } else {
                $totalred = 0;
            }
            if (!empty($getglberedem)) {
                $totalglbred = $getglberedem[0]['total'];
            } else {
                $totalglbred = 0;
            }
            $totalsum = ($totalred + $totalglbred);
            if ($totalsum > 0) {
                $invoicear[$i]['Invoice']['outstanding_points'] = $totalsum;
                $invoicear[$i]['Invoice']['outstanding_points_dol'] = $totalsum / 50;
                $invoicear[$i]['Invoice']['minimum_dep_per'] = '10 %';
                $invoicear[$i]['Invoice']['threshold_per'] = '50 %';
                $invoicear[$i]['Invoice']['minimum_dep_dol'] = ($totalsum / 50) / 10;
                $invoicear[$i]['Invoice']['threshold_dol'] = (($totalsum / 50) / 10) / 2;
                $invoicear[$i]['Invoice']['actual_minimum_dep_dol'] = $cl['Clinic']['minimum_deposit'];
                $invoicear[$i]['Invoice']['actual_threshold_dol'] = ($cl['Clinic']['minimum_deposit']) / 2;
            } else {
                $invoicear[$i]['Invoice']['outstanding_points'] = 0;
                $invoicear[$i]['Invoice']['outstanding_points_dol'] = 0;
                $invoicear[$i]['Invoice']['minimum_dep_per'] = '10 %';
                $invoicear[$i]['Invoice']['threshold_per'] = '50 %';
                $invoicear[$i]['Invoice']['minimum_dep_dol'] = 0;
                $invoicear[$i]['Invoice']['threshold_dol'] = 0;
                $invoicear[$i]['Invoice']['actual_minimum_dep_dol'] = $cl['Clinic']['minimum_deposit'];
                $invoicear[$i]['Invoice']['actual_threshold_dol'] = ($cl['Clinic']['minimum_deposit']) / 2;
            }
            $invoice = $this->Invoice->find('first', array(
                'conditions' => array(
                    'Invoice.clinic_id' => $cl['Clinic']['id']
                ),
                'order' => array('Invoice.payed_on desc')
            ));
            if (!empty($invoice)) {
                $invoicear[$i]['Invoice']['current_transaction'] = $cl['Clinic']['minimum_deposit'] - $invoice['Invoice']['current_balance'];
                $invoicear[$i]['Invoice']['live_min_deposit'] = $invoice['Invoice']['current_balance'];
            } else {
                $invoicear[$i]['Invoice']['current_transaction'] = 0;
                $invoicear[$i]['Invoice']['live_min_deposit'] = 0;
            }

            $date_chk1 = date("Y-m") . '-01';
            $date_chk2 = date("Y-m") . '-31';
            $totalthismonth = $this->Transaction->find('first', array('conditions' => array('Transaction.date BETWEEN ? AND ?' => array($date_chk1, $date_chk2), 'Transaction.clinic_id' => $cl['Clinic']['id'], 'Transaction.is_buzzydoc' => 1, 'Transaction.activity_type' => 'Y'),
                'fields' => array('count(Transaction.clinic_id) AS cnt')
            ));
            $invoicear[$i]['Invoice']['redemption_this_month'] = $totalthismonth[0]['cnt'];
            $getfirstred = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.clinic_id' => $cl['Clinic']['id'],
                    'Transaction.is_buzzydoc' => 1,
                    'Transaction.activity_type' => 'Y'
                ),
                'order' => array('Transaction.date asc')
            ));

            $date_chk11 = $getfirstred['Transaction']['date'];
            $date_chk21 = date("Y-m-d");
            $diffdef = strtotime(date('Y-m-d H:i:s')) - strtotime($date_chk11);
            $expdate = floor($diffdef / (60 * 60 * 24 * 30));
            $totalred = $this->Transaction->find('first', array('conditions' => array('Transaction.date BETWEEN ? AND ?' => array($date_chk11, $date_chk21), 'Transaction.clinic_id' => $cl['Clinic']['id'], 'Transaction.is_buzzydoc' => 1, 'Transaction.activity_type' => 'Y'),
                'fields' => array('count(Transaction.clinic_id) AS cnt', 'sum(Transaction.amount) AS ttl')
            ));

            $invoicear[$i]['Invoice']['redemption_this_month'] = $totalthismonth[0]['cnt'];
            $invoicear[$i]['Invoice']['avg_redemption_month'] = number_format($totalred[0]['cnt'] / $expdate, 2);
            $invoicear[$i]['Invoice']['avg_redemption'] = ltrim(number_format($totalred[0]['ttl'] / $expdate, 2), '-') / 50;
            $i++;
        }
        $this->set('invoices', $invoicear);
    }
    /**
     * This function is no longer in use.
     * @deprecated no longer use
     */
    public function managebalance() {
        
        $this->layout = "adminLayout";
        if(DEBIT_FROM_BANK==0){
            $this->render('/Elements/access');
        }
        $clinic = $this->BeanstreamPayment->find('all', array(
            'order' => array('BeanstreamPayment.date desc')
        ));

        $this->set('BeanstreamPayment', $clinic);
    }
    /**
     * This function is no longer in use.
     * @deprecated no longer use
     */

    public function changeredeemstatusxml() {

        $this->layout = null;
        $status_name = $this->request->data['status'];

        $redeem['BeanstreamPayment'] = array('id' => $this->request->data['id'], 'status' => $this->request->data['status']);
        if (($this->request->data['id'] != '') && ($this->request->data['status'] != '')) {
            if ($this->BeanstreamPayment->save($redeem)) {
                $options10['conditions'] = array('BeanstreamPayment.id' => $this->request->data['id']);
                $Staffid = $this->BeanstreamPayment->find('first', $options10);
                $options8['conditions'] = array('Staff.clinic_id' => $Staffid['BeanstreamPayment']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.redemption_mail' => 1);
                $Staff = $this->Staff->find('first', $options8);
                $stemail = '';
                $stname = '';
                if (!empty($Staff)) {
                    $stemail = $Staff['Staff']['staff_email'];
                    $stname = $Staff['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $Staffid['BeanstreamPayment']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.staff_role' => 'Doctor');
                    $Staff1 = $this->Staff->find('first', $options9);
                    $stemail = $Staff1['Staff']['staff_email'];
                    $stname = $Staff1['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $Staffid['BeanstreamPayment']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Administrator', 'Staff.staff_role' => 'A'));
                    $Staff2 = $this->Staff->find('first', $options9);
                    $stemail = $Staff2['Staff']['staff_email'];
                    $stname = $Staff2['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options10['conditions'] = array('Staff.clinic_id' => $Staffid['BeanstreamPayment']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Manager', 'Staff.staff_role' => 'M'));
                    $Staff3 = $this->Staff->find('first', $options10);
                    $stemail = $Staff3['Staff']['staff_email'];
                    $stname = $Staff3['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $stemail = SUPER_ADMIN_EMAIL_STAFF;
                }

                $Email1 = new CakeEmail(MAILTYPE);

                    $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                    $Email1->to($stemail);
                    $Email1->subject("Congratulations, Balance Amount paid")
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $reachthres = $current_bal - $threshold;
                    $Email1->viewVars(array('msg' => 'Hi ' . $stname . ', we just wanted to tell you know that Balance amount paid to your account.',
                        'subject' => 'Congratulations, Balance Amount paid :)'
                    ));
                    $Email1->send();


                if (($status_name == 'Pending')) {
                    echo 1;
                } elseif (($status_name == 'Completed')) {
                    echo 2;
                } else {
                    echo 4;
                }
            }
        }


        exit;
    }

}

?>
