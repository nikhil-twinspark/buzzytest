<?php
/**
 * This file for get the details of deleted transaction history by staff user.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for get the details of deleted transaction history by staff user.
 */
class TransactionDeleteLogController extends AppController {
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
    public $uses = array('Clinic', 'User', 'Transaction', 'TransactionDeleteLog', 'Staff', 'Reward', 'Admin');
    /**
     * For Staff site we use the transactionlog layout
     * @var type 
     */
    public $layout = 'transactionlog';

    public function beforeFilter() {
        
    }
    /**
     * Getting the list of all delete transaction list by staff user.
     */
    public function index() {
        $transdet = $this->TransactionDeleteLog->find('all');
        $i = 0;
        foreach ($transdet as $tras) {
            $stf = $this->Staff->find('first', array('conditions' => array('Staff.id' => $tras['TransactionDeleteLog']['staff_id'])));
            $transdet[$i]['TransactionDeleteLog']['staff_id'] = $stf['Staff']['staff_id'];
            $rd = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $tras['TransactionDeleteLog']['clinic_id'])));
            $transdet[$i]['TransactionDeleteLog']['clinic_id'] = $rd['Clinic']['api_user'];
            if ($tras['TransactionDeleteLog']['activity_type'] == 'Y') {
                $transdet[$i]['TransactionDeleteLog']['activity_type'] = 'Redeemed';
            } else {
                $transdet[$i]['TransactionDeleteLog']['activity_type'] = 'Allocated';
            }
            $i++;
        }
        $this->set('transactiondel', $transdet);
        if ($this->request->is('post')) {
            $options1['conditions'] = array('Admin.username' => $this->request->data['login']['name'], 'BINARY (`Admin`.`password`) LIKE' => $this->request->data['login']['password'], 'Admin.admin_role' => 'logchk');
            $AdminResult = $this->Admin->find('first', $options1);
            //check the login credential of admin.
            if (!empty($AdminResult)) {

                $this->Session->write('transactionlog.loginName', $this->request->data['login']['name']);
                $this->Session->write('transactionlog.loginPassword', $this->request->data['login']['password']);
            }
            $this->Session->setFlash(__('Invalid Credentials'));
        }
    }
    /**
     * @depricated
     * @param type $data
     * @return boolean
     */
    function verify_trascred($data) {
        $result = FALSE;
        if (isset($data['login']['name']) && isset($data['login']['password']) && isset($GLOBALS['trans_users']) && isset($GLOBALS['trans_users'][$data['login']['name']])) {
            if ($GLOBALS['trans_users'][$data['login']['name']] == $data['login']['password']) {
                $result = TRUE;
            }
        }
        return $result;
    }
    /**
     * Deleting the transaction by staff user.
     */
    public function deletehistory() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->data['c'] != 0) {
            $transdet = $this->Transaction->find('first', array('conditions' => array('Transaction.id' => $this->request->data['tid'])));

            $transedel['TransactionDeleteLog'] = array('user_id' => $transdet['Transaction']['user_id'], 'staff_id' => $sessionstaff['staff_id'], 'card_number' => $transdet['Transaction']['card_number'], 'first_name' => $transdet['Transaction']['first_name'], 'last_name' => $transdet['Transaction']['last_name'], 'activity_type' => $transdet['Transaction']['activity_type'], 'promotion_id' => $transdet['Transaction']['promotion_id'], 'reward_id' => $transdet['Transaction']['reward_id'], 'authorization' => $transdet['Transaction']['authorization'], 'amount' => $transdet['Transaction']['amount'], 'clinic_id' => $transdet['Transaction']['clinic_id'], 'date' => date('Y-m-d H:i:s'), 'status' => $transdet['Transaction']['status']);
            $this->TransactionDeleteLog->create();
            $this->TransactionDeleteLog->save($transedel['TransactionDeleteLog']);
            if ($this->Transaction->deleteAll(array('Transaction.id' => $this->request->data['tid'], 'Transaction.user_id' => $this->request->data['c']))) {
                $alltrans = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $this->request->data['c']
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                )));
                if (empty($alltrans)) {
                    $points = 0;
                } else {
                    $points = $alltrans[0]['points'];
                }
                $this->User->updateAll(
                        array('User.points' => $points), array(
                    'User.id' => $this->request->data['c']
                        )
                );
                $this->Session->write('staff.customer_info.total_points', $points);
                $this->Session->setFlash('Transaction deleted successfuly.');
                $this->redirect(array('action' => 'patienthistory'));
            } else {
                $this->Session->setFlash('ERR:Transaction not deleted.');
                $this->redirect(array('action' => 'patienthistory'));
            }
        } else {
            if ($this->UnregTransaction->deleteAll(array('UnregTransaction.id' => $this->request->data['tid'], 'UnregTransaction.user_id' => $this->request->data['c'], 'UnregTransaction.card_number' => $this->request->data['card_number']))) {
                $alltrans = $this->UnregTransaction->find('first', array(
                    'conditions' => array(
                        'UnregTransaction.user_id' => $this->request->data['c'],
                        'UnregTransaction.card_number' => $this->request->data['card_number']
                    ),
                    'fields' => array(
                        'SUM(UnregTransaction.amount) AS points'
                    ),
                    'group' => array(
                        'UnregTransaction.card_number'
                )));
                if (empty($alltrans)) {
                    $points = 0;
                } else {
                    $points = $alltrans[0]['points'];
                }

                $this->Session->write('staff.customer_info.total_points', $points);
                $this->Session->setFlash('Transaction deleted successfuly.');
                $this->redirect(array('action' => 'patienthistory'));
            } else {
                $this->Session->setFlash('ERR:Transaction not deleted.');
                $this->redirect(array('action' => 'patienthistory'));
            }
        }
    }

}

?>
