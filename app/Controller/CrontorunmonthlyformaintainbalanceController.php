<?php
/**
 *  This file for running cron on every month for reminder to redeem points for patient who have more then 749 points and maintain 10% for outstanding of all balance for practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for running cron on every month for reminder to redeem points for patient who have more then 749 points and maintain 10% for outstanding of all balance for practice.
 */
class CrontorunmonthlyformaintainbalanceController extends AppController {
    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'ClinicUser', 'CardNumber', 'Document', 'Reward', 'patients', 'ProfileField', 'ProfileFieldUser', 'Transaction', 'Clinic', 'Notification', 'Staff', 'UsersBadge', 'Badge', 'GlobalRedeem', 'TangoAccount', 'Invoice', 'PaymentDetail','FailedPayment');
    /**
     * This cron run on every end of month to reminder for patient to redeem and charge 10% to maintain buzzydoc bank balance.
     */
    public function index() {

        
         //cron for reminder to redeem points
        //getting the list of patient who have more then 749 points.
        $useroption['conditions'] = array('User.points >'=> 749,'User.email !='=>'');
        $alluser = $this->User->find('all', $useroption);
        foreach ($alluser as $allu) {
                        $template_array = $this->Api->get_template(37);
                        $link = str_replace('[username]', $allu['User']['first_name'], $template_array['content']);
                        $link1 = str_replace('[points]', $allu['User']['points'], $link);
                        $link2 = str_replace('[amount]', $allu['User']['points']/50, $link1);

                        $Email1 = new CakeEmail(MAILTYPE);

                        $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                        $Email1->to($allu['User']['email']);
                        $Email1->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email1->viewVars(array('msg' => $link2
                        ));
                        $Email1->send();
            
        }

        //cron for maintain 10% of all outsantand balance

        //getting the list of all buzzydoc clinic.
        $outoption['conditions'] = array('Clinic.is_buzzydoc' => 1, 'Clinic.minimum_deposit >' => 0);
        $outstndclinic = $this->Clinic->find('all', $outoption);

        foreach ($outstndclinic as $outstand) {
            //fetching the last invoice details
            $currentbal = $this->Invoice->find('first', array(
                'conditions' => array(
                    'Invoice.clinic_id' => $outstand['Clinic']['id']
                ),
                'order' => array('Invoice.payed_on desc')
            ));
            //getting the balance point for patient
            $getglbexpt = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.clinic_id' => $outstand['Clinic']['id'],
                    'Transaction.is_buzzydoc' => 1
                ),
                'group' => array('Transaction.clinic_id'),
                'fields' => array('sum(Transaction.amount) AS total,Transaction.clinic_id')
            ));

            $getglberedem = $this->GlobalRedeem->find('first', array(
                'conditions' => array(
                    'GlobalRedeem.clinic_id' => $outstand['Clinic']['id'],
                    'GlobalRedeem.is_buzzydoc' => 1
                ),
                'group' => array('GlobalRedeem.clinic_id'),
                'fields' => array('sum(GlobalRedeem.amount) AS total,GlobalRedeem.clinic_id')
            ));

            if (!empty($getglbexpt)) {
                $totalred = $getglbexpt[0]['total'];
            } else {
                $totalred = 0;
            }
            if (!empty($getglberedem)) {
                $totalglbred = $getglberedem[0]['total'];
            } else {
                $totalglbred = 0;
            }
            //total sum of all points given by staff to his Pratice Patient.
            $totalsum = ($totalred + $totalglbred) / 500;


            $paydet['conditions'] = array('PaymentDetail.clinic_id' => $outstand['Clinic']['id']);
            $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
            //condition to check clinic have a payment detail and outstanding amount is less then current balance
            if ($currentbal['Invoice']['current_balance'] < $totalsum && !empty($getpayemntdetails) && $getpayemntdetails['PaymentDetail']['customer_account_profile_id'] != '' && !empty($currentbal)) {

                $debamt = $totalsum - $currentbal['Invoice']['current_balance'];
                $transactionFee = .35 + $debamt * (.75 / 100);
                $totalcredit1 = $debamt + $transactionFee;
                $totalcredit = number_format($totalcredit1, 2, '.', '');
                $transaction1 = new AuthorizeNetTransaction;
                $transaction1->amount = $totalcredit;
                $transaction1->customerProfileId = $getpayemntdetails['PaymentDetail']['customer_account_id'];
                $transaction1->customerPaymentProfileId = $getpayemntdetails['PaymentDetail']['customer_account_profile_id'];
                $lineItem1 = new AuthorizeNetLineItem;
                $lineItem1->itemId = mt_rand(100000, 999999);
                $lineItem1->name = 'Credit 10 percent';
                $lineItem1->description = "Credit 10 percent of outstanding points";
                $lineItem1->quantity = "1";
                $lineItem1->unitPrice = $debamt;
                $lineItem1->taxable = "true";
                $transaction1->lineItems[] = $lineItem1;
                $request1 = new AuthorizeNetCIM;
                //charge balance amount from clinic throght authorize.net
                $response1 = $request1->createCustomerProfileTransaction("AuthCapture", $transaction1);

                if ($response1->xml->messages->message->code == 'I00001') {
                    $transactionResponse1 = $response1->getTransactionResponse();

                    $trnsid = $transactionResponse1->transaction_id;
                    $Invoice_array['Invoice'] = array(
                        'clinic_id' => $outstand['Clinic']['id'],
                        'amount' => $debamt,
                        'transaction_fee' => $transactionFee,
                        'invoice_id' => $trnsid,
                        'mode' => 'Credit',
                        'current_balance' => $currentbal['Invoice']['current_balance'] + $debamt,
                        'payed_on' => date('Y-m-d H:i:s'),
                        'status' => 1
                    );
                    $this->Invoice->create();
                    $this->Invoice->save($Invoice_array);
                    $optionsstaff['conditions'] = array('Staff.clinic_id' => $outstand['Clinic']['id'], 'Staff.staff_role' => 'Doctor');
                    $staffdoc = $this->Staff->find('first', $optionsstaff);
                    if ($staffdoc['Staff']['staff_email'] != '') {
                        $ttl = $currentbal['Invoice']['current_balance'] + $debamt;
                        $template_array = $this->Api->get_template(33);
                        $link = str_replace('[staff_name]', $staffdoc['Staff']['staff_id'], $template_array['content']);
                        $link1 = str_replace('[credit_amount]', $debamt, $link);
                        $link2 = str_replace('[current_balance]', $ttl, $link1);
                        $Email1 = new CakeEmail(MAILTYPE);

                        $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                        $Email1->to($staffdoc['Staff']['staff_email']);
                        $Email1->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        
                        $Email1->viewVars(array('msg' => $link2
                        ));
                        $Email1->send();
                    }
                    $update_payment['PaymentDetail'] = array(
                        'id' => $getpayemntdetails['PaymentDetail']['id'],
                        'reminder_date' => '0000-00-00',
                        'reminder_count' => 0
                    );
                    $this->PaymentDetail->save($update_payment);
                }else {
                    //if payment detail not found or cc details are wrong then failed payment store in db and failed email send to super admin
                    $failed_payment['FailedPayment'] = array(
                        'clinic_id' => $outstand['Clinic']['id'],
                        'user_id' => 0,
                        'subject' => $response1->xml->messages->message->text,
                        'description' => 'Maintain 10% of all outsantand balance.',
                        'date' => date('Y-m-d H:i:s')
                    );
                    $this->FailedPayment->create();
                    $this->FailedPayment->save($failed_payment);
                    $lstdt = $getpayemntdetails['PaymentDetail']['reminder_date'];
                    $diffdef = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
                    $reminderdate = floor($diffdef / (60 * 60 * 24));
                    $send_mail = 0;
                    if ($lstdt == '0000-00-00') {
                        $update_payment['PaymentDetail'] = array(
                            'id' => $getpayemntdetails['PaymentDetail']['id'],
                            'reminder_date' => date('Y-m-d'),
                            'reminder_count' => ($getpayemntdetails['PaymentDetail']['reminder_count'] + 1)
                        );
                        $this->PaymentDetail->save($update_payment);
                        $send_mail = 1;
                    } else if ($lstdt != '0000-00-00' && $getpayemntdetails['PaymentDetail']['reminder_count'] < 2 && $reminderdate == 7) {
                        $update_payment['PaymentDetail'] = array(
                            'id' => $getpayemntdetails['PaymentDetail']['id'],
                            'reminder_count' => ($getpayemntdetails['PaymentDetail']['reminder_count'] + 1)
                        );
                        $this->PaymentDetail->save($update_payment);

                        $send_mail == 1;
                    }
                    if ($send_mail == 1) {
                    $Email1 = new CakeEmail(MAILTYPE);

                    $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                    $Email1->to(SUPER_ADMIN_EMAIL);
                    $Email1->subject($response1->xml->messages->message->text)
                            ->template('buzzydocother')
                            ->emailFormat('html');

                    $Email1->viewVars(array('msg' => 'Hi BuzzyDoc, Staff - "' . $staffdoc['Staff']['staff_id'] . '" from ' . $outstand['Clinic']['api_user'] . ' have some issue related to credit amount to account.<br> Error Message Details are "' . $response1->xml->messages->message->text . '"',
                        'subject' => $response1->xml->messages->message->text
                    ));
                    $Email1->send();
                    echo 'Subject:- "' . $response1->xml->messages->message->text . '".Error Message Details are "' . $response1->xml->messages->message->description . '"';
                    }
                }
            }
        }
        
        
        
        die;
        ////////
    }

}

?>
