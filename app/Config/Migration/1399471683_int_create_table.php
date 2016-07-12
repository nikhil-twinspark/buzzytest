<?php

class IntCreateTable extends CakeMigration {

    /**
     * Migration description
     *
     * @var string
     */
    public $description = '';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
        ),
        'down' => array(
        ),
    );

    /**
     * Before migration callback
     *
     * @param string $direction, up or down direction of migration process
     * @return boolean Should process continue
     */
    public function before($direction) {
        if ($direction === 'up') {
            $db = ConnectionManager::getDataSource($this->connection);
            $db->cacheSources = false;
            //insert card number ,update user table and update transaction table
            $clinic = ClassRegistry::init('clinics');
            $clinic_to_migrate = 3;
            $clinics = $clinic->find('all', array('conditions' => array('clinics.id' => $clinic_to_migrate))); //condition need to remove after test ,array('conditions'=>array('clinics.id'=>1))
            foreach ($clinics as $cln) {
                $getcardfordelete = ClassRegistry::init('card_numbers');
                $gcarddelete = $getcardfordelete->find('all', array(
                    'conditions' => array(
                        'card_numbers.clinic_id' => $cln['clinics']['id'],
                )));

                foreach ($gcarddelete as $gcarddel) {
                    $guserdelete = ClassRegistry::init('clinic_users');
                    $getuserdel = $guserdelete->find('first', array(
                        'conditions' => array(
                            'clinic_users.card_number' => $gcarddel['card_numbers']['card_number'],
                            'clinic_users.clinic_id' => $cln['clinics']['id']
                    )));

                    if (!empty($getuserdel)) {
                        $deletetransaction = ClassRegistry::init('transactions');
                        $deletetransaction->query("delete from transactions where user_id=" . $getuserdel['clinic_users']['user_id']);
                        $deletetprofilefield = ClassRegistry::init('profile_field_users');
                        $deletetprofilefield->query("delete from profile_field_users where user_id=" . $getuserdel['clinic_users']['user_id']);
                        $deletetuser = ClassRegistry::init('users');
                        $deletetuser->query("delete from users where id=" . $getuserdel['clinic_users']['user_id']);
                        $deletetclinicuser = ClassRegistry::init('clinic_users');
                        $deletetclinicuser->query("delete from clinic_users where user_id=" . $getuserdel['clinic_users']['user_id']);
                    }
                    $deletetcard = ClassRegistry::init('card_numbers');
                    $deletetcard->query("delete from card_numbers where id=" . $gcarddel['card_numbers']['id']);
                }


                $deletetransaction1 = ClassRegistry::init('transactions');
                $deletetransaction1->query("delete from transactions where clinic_id=" . $clinic_to_migrate);

                $deletetclinicuser1 = ClassRegistry::init('clinic_users');
                $deletetclinicuser1->query("delete from clinic_users where clinic_id=" . $clinic_to_migrate);
                $cc = ClassRegistry::init('client_credentials');
                $cc_det = $cc->find('first', array(
                    'conditions' => array(
                        'client_credentials.api_user' => $cln['clinics']['api_user']
                )));



                $accesscontrol = ClassRegistry::init('access_controls');
                $accesscontrolar = array(
                    "access_controls" => array(
                        "clinic_id" => $cln['clinics']['id'],
                        'clinic_type' => 0,
                        'access' => 'Documents,Promotions,Users,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail'
                    )
                );
                $accesscontrol->save($accesscontrolar);
                $cg = ClassRegistry::init('campaigns');
                $campg = $cg->find('first', array(
                    'conditions' => array(
                        'campaigns.client_id' => $cln['clinics']['api_user']
                )));

                $url = 'https://api.clienttoolbox.com/?user_id=' . $cc_det['client_credentials']['api_user'] . '&user_password=' . $cc_det['client_credentials']['api_key'] . '&account_id=' . $cc_det['client_credentials']['accountId'] . '&type=reports&report=customers_all';
                $result = file_get_contents($url);

                try {
                    $allcust = new SimpleXMLElement(str_replace('&', '&amp;', $result));
                } catch (Exception $e) {
                    CakeLog::write('error', 'Data-Rohitortho-' . $url . '-resul-Rohitortho' . print_r($allcust, true));
                }

                if (isset($allcust) && (string) $allcust->attributes()->status == 'success') {
                    foreach ($allcust->customer as $cus) {

                        $cus->card_number = stripslashes($cus->card_number);
                        if ($cus->card_number != '') {
                            $getcard = ClassRegistry::init('card_numbers');
                            $gcard = $getcard->find('first', array(
                                'conditions' => array(
                                    'card_numbers.card_number' => $cus->card_number,
                                    'card_numbers.clinic_id' => $cln['clinics']['id'],
                            )));
                            if (empty($gcard)) {
                                $card_number = $this->generateModel('card_numbers');
                                $rd_card_number = array(
                                    "card_numbers" => array(
                                        "clinic_id" => $cln['clinics']['id'],
                                        'card_number' => $cus->card_number,
                                        'status' => 1
                                    )
                                );

                                $card_number->save($rd_card_number);
                                $this->callback->out($cus->card_number . '-card number table has been initialized');
                            }
                            $url_history = 'https://api.clienttoolbox.com/?user_id=' . $cc_det['client_credentials']['api_user'] . '&user_password=' . $cc_det['client_credentials']['api_key'] . '&account_id=' . $cc_det['client_credentials']['accountId'] . '&type=balance&campaign_id=' . $campg['campaigns']['campaign_id'] . '&code=' . $cus->customer_code;
                            $result_history = file_get_contents($url_history);

                            try {
                                $allhistory = new SimpleXMLElement(str_replace('&', '&amp;', $result_history));
                            } catch (Exception $e) {
                                CakeLog::write('error', 'Data-Rohitortho-' . $url_history . '-resul-Rohitortho' . print_r($allhistory, true));
                            }

                            $ap = array();
                            if (isset($allhistory) && (string) $allhistory->attributes()->status == 'success') {
                                $ap['patients']['card_number'] = (string) $cus->card_number;
                                $ap['patients']['enrollment_stamp'] = (string) $cus->record_timestamp;
                                $ap['patients']['first_name'] = (string) $cus->first_name;
                                $ap['patients']['last_name'] = (string) $cus->last_name;
                                $ap['patients']['customer_password'] = '';
                                $ap['patients']['phone'] = (string) $cus->phone;
                                $ap['patients']['email'] = (string) $cus->email;
                                $ap['patients']['street1'] = (string) $cus->address1;
                                $ap['patients']['street2'] = (string) $cus->address2;
                                $ap['patients']['city'] = (string) $cus->city;
                                $ap['patients']['state'] = (string) $cus->state;
                                $ap['patients']['postal_code'] = (string) $cus->zip;
                                $ap['patients']['gender'] = '';
                                $ap['patients']['main_interest'] = '';
                                if ($cus->custom_date != '0000-00-00') {
                                    $ap['patients']['custom_date'] = (string) $cus->custom_date;
                                } else {
                                    $ap['patients']['custom_date'] = '';
                                }
                                $ap['patients']['parents_email'] = '';
                                $ap['patients']['facebook_id'] = 0;
                                $ap['patients']['is_facebook'] = 0;
                                $ap['patients']['status'] = 1;
                                $ap['patients']['is_varified'] = 1;
                                $ap['patients']['blocked'] = 0;
                                $ap['patients']['is_buzzydoc'] = 0;
                            }

                            if (!empty($ap) && $ap['patients']['first_name'] != '' && $ap['patients']['last_name'] != '') {

                                $guser = ClassRegistry::init('clinic_users');
                                $getuser = $guser->find('first', array(
                                    'conditions' => array(
                                        'clinic_users.card_number' => $ap['patients']['card_number'],
                                        'clinic_users.clinic_id' => $cln['clinics']['id']
                                )));
                                if (empty($getuser)) {
                                    $users = $this->generateModel('users');
                                    $records = array(
                                        "users" => array(
                                            "custom_date" => $ap['patients']['custom_date'],
                                            "email" => $ap['patients']['email'],
                                            "parents_email" => $ap['patients']['parents_email'],
                                            "first_name" => $ap['patients']['first_name'],
                                            "last_name" => $ap['patients']['last_name'],
                                            'customer_password' => $ap['patients']['customer_password'],
                                            'points' => 0,
                                            "enrollment_stamp" => $ap['patients']['enrollment_stamp'],
                                            'facebook_id' => $ap['patients']['facebook_id'],
                                            'is_facebook' => $ap['patients']['is_facebook'],
                                            'status' => $ap['patients']['status'],
                                            'is_verified' => $ap['patients']['is_varified'],
                                            'blocked' => $ap['patients']['blocked']
                                        )
                                    );


                                    if ($users->save($records)) {
                                        $updtcardnumber = ClassRegistry::init('card_numbers');
                                        $updtcardnumber->query("UPDATE `card_numbers` SET `status` = 2  WHERE `clinic_id` =" . $cln['clinics']['id'] . " and card_number='" . $ap['patients']['card_number'] . "'");
                                        $this->callback->out('users table has been initialized');
                                    }
                                    $user_id = $users->getLastInsertId();
                                    $clinic_users = $this->generateModel('clinic_users');
                                    $clinicusers_vl = array(
                                        "clinic_users" => array(
                                            "clinic_id" => $cln['clinics']['id'],
                                            "user_id" => $user_id,
                                            "card_number" => $ap['patients']['card_number'],
                                            'facebook_like_status' => 0
                                        )
                                    );

                                    $clinic_users->save($clinicusers_vl);

                                    foreach ($ap['patients'] as $val => $index) {

                                        $prfield = ClassRegistry::init('profile_fields');
                                        $profile_id = $prfield->find('first', array('conditions' => array('profile_fields.profile_field' => $val)));
                                        if (!empty($profile_id)) {
                                            $profile_field_users = $this->generateModel('profile_field_users');
                                            $records_pf_vl = array(
                                                "profile_field_users" => array(
                                                    "user_id" => $user_id,
                                                    "profilefield_id" => $profile_id['profile_fields']['id'],
                                                    "value" => $index,
                                                    "clinic_id" => 0
                                                )
                                            );

                                            if ($profile_field_users->save($records_pf_vl)) {
                                                $this->callback->out('profile_field_users table has been initialized');
                                            }
                                        }
                                    }
                                } else {
                                    $user_id = $getuser['clinic_users']['user_id'];
                                }


                                if (isset($allhistory) && (string) $allhistory->attributes()->status == 'success') {

                                    if (isset($allhistory->campaign->customer->transactions)) {
                                        foreach ($allhistory->campaign->customer->transactions->transaction as $trans) {
                                            $rds = ClassRegistry::init('rewards');
                                            $rddet = $rds->find('first', array('conditions' => array('rewards.description LIKE' => '%' . $trans->authorization . '%')));
                                            if (empty($rddet)) {
                                                $rdid = '';
                                            } else {
                                                $rdid = $rddet['rewards']['id'];
                                            }

                                            $gettrans = ClassRegistry::init('transactions');
                                            $trsndate = explode(' ', $trans->date);
                                            $rddet = $gettrans->find('first', array('conditions' => array('transactions.authorization LIKE' => '%' . $trans->authorization . '%', 'transactions.date LIKE' => '%' . $trsndate[0] . '%', 'transactions.clinic_id' => $cln['clinics']['id'], 'transactions.user_id' => $user_id)));

                                            if (empty($rddet)) {
                                                $transaction = $this->generateModel('transactions');
                                                if ($trans->redeemed == 'Y') {
                                                    $st = 'Redeemed';
                                                } else {
                                                    $st = 'New';
                                                }
                                                $rd_transaction = array(
                                                    "transactions" => array(
                                                        'user_id' => $user_id,
                                                        'card_number' => $allhistory->campaign->customer->card_number,
                                                        'first_name' => $allhistory->campaign->customer->first_name,
                                                        'last_name' => $allhistory->campaign->customer->last_name,
                                                        'reward_id' => $rdid,
                                                        'activity_type' => $trans->redeemed,
                                                        'authorization' => $trans->authorization,
                                                        'amount' => $trans->amount / 2,
                                                        'clinic_id' => $cln['clinics']['id'],
                                                        'date' => $trans->date,
                                                        'status' => $st,
                                                        'redeem_from' => 0,
                                                        'is_buzzydoc' => 0
                                                    )
                                                );

                                                if ($transaction->save($rd_transaction)) {
                                                    $this->callback->out('transactions table has been initialized');
                                                }
                                            }
                                        }
                                    }
                                }


                                $trans = ClassRegistry::init('transactions');
                                $alltrans = $trans->find('first', array(
                                    'conditions' => array(
                                        'transactions.user_id' => $user_id,
                                        'transactions.clinic_id' => $cln['clinics']['id']
                                    ),
                                    'fields' => array(
                                        'SUM(transactions.amount) AS points'
                                    ),
                                    'group' => array(
                                        'transactions.user_id'
                                )));
                                if (empty($alltrans)) {
                                    $points = 0;
                                } else {
                                    $points = $alltrans[0]['points'];
                                }
                                if ($points > 200) {
                                    $updtcardnumber1 = ClassRegistry::init('clinic_users');
                                    $updtcardnumber1->query("UPDATE `clinic_users` SET local_points=" . $points . "  WHERE `clinic_id` =" . $cln['clinics']['id'] . " and card_number='" . $ap['patients']['card_number'] . "'");
                                    ///hear put the mail for change your password
                                    $this->callback->out($ap['patients']['card_number'] . '-clinic_users table has been initialized');
                                } else {
                                    $updtcardnumber1 = ClassRegistry::init('clinic_users');
                                    $updtcardnumber1->query("UPDATE `clinic_users` SET local_points=0  WHERE `clinic_id` =" . $cln['clinics']['id'] . " and card_number='" . $ap['patients']['card_number'] . "'");
                                    $deletetransactiondel = ClassRegistry::init('transactions');
                                    $deletetransactiondel->query("delete from transactions where user_id=" . $user_id);
                                    ///hear put the mail for change your password
                                    $this->callback->out($ap['patients']['card_number'] . '-clinic_users table has been initialized');
                                }
                            } else {

                                $updtcardnumber = ClassRegistry::init('card_numbers');
                                $updtcardnumber->query("UPDATE `card_numbers` SET `status` = 1  WHERE `clinic_id` =" . $cln['clinics']['id'] . " and card_number='" . $ap['patients']['card_number'] . "'");



                                if (isset($allhistory) && (string) $allhistory->attributes()->status == 'success') {

                                    if (isset($allhistory->campaign->customer->transactions)) {
                                        foreach ($allhistory->campaign->customer->transactions->transaction as $trans) {
                                            $rds = ClassRegistry::init('rewards');
                                            $rddet = $rds->find('first', array('conditions' => array('rewards.description LIKE' => '%' . $trans->authorization . '%')));
                                            if (empty($rddet)) {
                                                $rdid = '';
                                            } else {
                                                $rdid = $rddet['rewards']['id'];
                                            }
                                            $gettrans = ClassRegistry::init('unreg_transactions');
                                            $trsndate = explode(' ', $trans->date);
                                            $rddet = $gettrans->find('first', array('conditions' => array('unreg_transactions.authorization LIKE' => '%' . $trans->authorization . '%', 'unreg_transactions.date LIKE' => '%' . $trsndate[0] . '%', 'unreg_transactions.clinic_id' => $cln['clinics']['id'], 'unreg_transactions.card_number' => $allhistory->campaign->customer->card_number)));
                                            if (empty($rddet)) {

                                                $transaction_unreg = $this->generateModel('unreg_transactions');
                                                if ($trans->redeemed == 'Y') {
                                                    $st = 'Redeemed';
                                                } else {
                                                    $st = 'New';
                                                }
                                                $rd_transaction_unreg = array(
                                                    "unreg_transactions" => array(
                                                        'user_id' => 0,
                                                        'card_number' => $allhistory->campaign->customer->card_number,
                                                        'first_name' => $allhistory->campaign->customer->first_name,
                                                        'last_name' => $allhistory->campaign->customer->last_name,
                                                        'reward_id' => $rdid,
                                                        'activity_type' => $trans->redeemed,
                                                        'authorization' => $trans->authorization,
                                                        'amount' => $trans->amount / 2,
                                                        'clinic_id' => $cln['clinics']['id'],
                                                        'date' => $trans->date,
                                                        'status' => $st
                                                    )
                                                );

                                                if ($transaction_unreg->save($rd_transaction_unreg)) {
                                                    $this->callback->out('unreg transactions table has been initialized');
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            /* -------------------------- */
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * After migration callback
     *
     * @param string $direction, up or down direction of migration process
     * @return boolean Should process continue
     */
    public function after($direction) {
        return true;
    }

}
