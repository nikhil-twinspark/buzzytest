<?php

/**
 * This file for common use function throughout the project.
 * Get the Clinic detail like promotion,transaction,campaign from steekystreet.
 * Get the email template details.
 * Get weekly details for staff usage report.
 * Get and set tier level for patient who have access for accelerated reward program.
 */
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This Controller for common use function throughout the project.
 * Get the Clinic detail like promotion,transaction,campaign from steekystreet.
 * Get the email template details.
 * Get weekly details for staff usage report.
 * Get and set tier level for patient who have access for accelerated reward program.
 */
class ApiComponent extends Component {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $components = array('Session', 'Api');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinic', 'Promotions', 'Notifications', 'User', 'ClinicUser', 'ClientCredentials', 'Transaction', 'AccessControl', 'ClinicNotification');

    /**
     * Curl hit with post data.
     * @param type $data
     * @param type $api_url
     * @return type
     */
    function submit_cURL($data, $api_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // turn off verification of SSL for testing:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // Send query to server:
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Curl hit with send Get data.
     * @param type $api_url
     * @return type
     */
    function submit_cURL_Get($api_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        // turn off verification of SSL for testing:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // Send query to server:
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Getting the list of promotion from steekystreet for Practice's patient.
     * @param type $cred
     * @param type $camp_id
     * @param type $client_id
     */
    function list_promos($cred, $camp_id, $client_id) {

        $data['user_id'] = $cred['ClientCredentials']['api_user'];
        $data['user_password'] = $cred['ClientCredentials']['api_key'];
        $data['type'] = 'campaign_promos';
        $data['account_id'] = $cred['ClientCredentials']['accountId'];
        $data['campaign_id'] = $camp_id;
        $result = $this->submit_cURL($data, $cred['ClientCredentials']['api_url']);
        try {
            $pro['promos'] = new SimpleXMLElement(str_replace('&', '&amp;', $result));
        } catch (Exception $e) {
            CakeLog::write('error', 'Data-Rohitortho' . print_r($data, true) . '-result-Rohitortho' . print_r($result, true));
        }
        if (isset($pro) && (string) $pro['promos']['status'] == 'success') {
            $promos = $this->xml2array($pro['promos']);
            foreach ($promos['promotions'][0]['promotion'] as $promotion) {
                $prget = ClassRegistry::init('Promotions');
                $options1['conditions'] = array('Promotions.clinic_id' => $client_id, 'Promotions.description' => $promotion['description'], 'Promotions.value' => $promotion['value']);

                $promo = $prget->find('first', $options1);
                if (empty($promo)) {
                    $promos_array['Promotions'] = array(
                        'operand' => $promotion['operand'],
                        'value' => $promotion['value'],
                        'description' => $promotion['description'],
                        'clinic_id' => $client_id
                    );
                    $Promotions = ClassRegistry::init('Promotions');
                    $Promotions->create();
                    $Promotions->save($promos_array);
                }
            }
        }
    }

    /**
     * Function to get all transaction from steekystreet for Patient.
     * @param type $cred
     * @param type $camp_id
     * @param type $client_id
     */
    function get_redeems($cred, $camp_id, $client_id) {

        $chktrans = ClassRegistry::init('Transaction');
        $ischktrans = $chktrans->query('select max(date) as dt from transactions where clinic_id=' . $client_id);
        if (isset($ischktrans[0][0]['dt']) && $ischktrans[0][0]['dt'] != '0000-00-00 00:00:00') {
            $checkdate = explode(' ', $ischktrans[0][0]['dt']);

            $date13age = date('Y-m-d', strtotime('+1 days', strtotime($checkdate[0])));
            $url_history = 'https://api.clienttoolbox.com/?user_id=' . $cred['ClientCredentials']['api_user'] . '&user_password=' . $cred['ClientCredentials']['api_key'] . '&account_id=' . $cred['ClientCredentials']['accountId'] . '&type=reports&report=transactions_all&date_start=' . $date13age . '&selected_campaigns=' . $camp_id;
            $result_history = file_get_contents($url_history);
            try {
                $red['redeem_list'] = new SimpleXMLElement(str_replace('&', '&amp;', $result_history));
            } catch (Exception $e) {
                CakeLog::write('error', 'Data-Rohitortho' . print_r($url_history, true) . '-result-Rohitortho' . print_r($result_history, true));
            }
            if (isset($red) && (string) $red['redeem_list']['status'] == 'success') {
                $redeem = $this->xml2array($red['redeem_list']);
                if ($redeem['transactions_count'] > 0) {
                    foreach ($redeem['transaction'] as $rd) {
                        $chkuser = ClassRegistry::init('clinic_user');
                        $optionuser['conditions'] = array('clinic_user.clinic_id' => $client_id, 'clinic_user.card_number' => $rd['card_number']);
                        $ischkuser = $chkuser->find('first', $optionuser);
                        if (!empty($ischkuser)) {
                            if ($rd['redeemed'] == 'R') {
                                $st = 'Redeemed';
                                $type = 'Y';
                            } else {
                                $type = 'N';
                                $st = 'New';
                            }
                            $redeem_reward_array['Transaction'] = array(
                                'user_id' => $ischkuser['clinic_user']['user_id'],
                                'card_number' => $rd['card_number'],
                                'first_name' => $rd['first_name'],
                                'last_name' => $rd['last_name'],
                                'activity_type' => $type,
                                'authorization' => $rd['authorization'],
                                'amount' => $rd['amount_number'],
                                'clinic_id' => $client_id,
                                'date' => $rd['record_timestamp'],
                                'status' => $st
                            );
                            $redeemRwd = ClassRegistry::init('Transaction');
                            $redeemRwd->create();
                            $redeemRwd->save($redeem_reward_array);
                        }
                    }
                }
            }
        }
    }

    /**
     * Function to get campaign list from steekystreet for any Practice's Patient.
     * @param type $user
     * @param type $client_id
     */
    function get_campaign($user, $client_id) {
        $data = array();
        $data['user_id'] = $user['ClientCredentials']['api_user'];
        $data['user_password'] = $user['ClientCredentials']['api_key'];
        $data['type'] = 'campaigns_list';
        $data['account_id'] = $user['ClientCredentials']['accountId'];
        $data['type_restrict'] = 'points';
        $result = $this->submit_cURL($data, $user['ClientCredentials']['api_url']);
        try {
            $campaignls['campaigns_list'] = new SimpleXMLElement(str_replace('&', '&amp;', $result));
        } catch (Exception $e) {
            CakeLog::write('error', 'Data-Rohitortho' . print_r($data, true) . '-result-Rohitortho' . print_r($result, true));
        }

        $campaigns_list = $this->xml2array($campaignls['campaigns_list']);
        if (!empty($campaigns_list['campaigns'][0]['campaign'])) {
            if ($client_id != 1) {
                $this->get_redeems($user, $campaigns_list['campaigns'][0]['campaign'][0]['id'], $client_id);
            }
            $this->list_promos($user, $campaigns_list['campaigns'][0]['campaign'][0]['id'], $client_id);
        }
    }

    /**
     * Function to sync patient from stickystreet data.
     * @param type $client_id
     */
    function syncClient($client_id) {
        $cl = ClassRegistry::init('Clinic');
        $options['conditions'] = array('Clinic.id' => $client_id);
        $clnic = $cl->find('first', $options);
        $user = ClassRegistry::init('ClientCredentials');
        $options['conditions'] = array('ClientCredentials.api_user' => $clnic['Clinic']['api_user']);
        $userResult = $user->find('first', $options);
        if (!empty($userResult)) {
            $this->get_campaign($userResult, $client_id);
        }
    }

    /**
     * checking the access for clinic staff
     * @param type $client_id
     * @param type $controller
     * @return int
     */
    function accesscheck($client_id, $controller) {
        $access = ClassRegistry::init('AccessControl');
        $accessck = $access->getAccessForClinic($client_id, $controller);
        if (empty($accessck)) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Function to convert sml to array.
     * @param type $xml
     * @return type
     */
    function xml2array($xml) {

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
     * function to change the number to standard number format. 
     * @param int $n
     * @return int
     */
    public function custom_number_format($n) {
        if (is_numeric($n)) {
            $position = 0;
            $units = array('', 'K', 'M', 'B', 'T');
            while ($n >= 1000 && ( $n / 1000 ) >= 1) {
                $n /= 1000;
                $position++;
            }
            return number_format(floor($n), 0, '.', ',') . $units[$position];
        } else {
            return $n;
        }
    }

    /**
     * Function to check img is exist in directory or not.
     * @param type $img
     * @return type
     */
    public function is_exist_img($img) {
        $ch = curl_init($img);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        return $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }

    /**
     * Global Function to create week range.
     * @param type $date
     * @param type $start_date
     * @param type $end_date
     * @return type
     */
    static function getWeekDates($date, $start_date, $end_date) {
        $week = date('W', strtotime($date));
        $year = date('Y', strtotime($date));
        $from = date("Y-m-d", strtotime("{$year}-W{$week}+1")); // Returns the date of monday in week
        if ($from < $start_date)
            $from = $start_date;
        $to = date("Y-m-d", strtotime("{$year}-W{$week}-7")); // Returns the date of sunday in week
        if ($to > $end_date) {
            $to = $end_date;
        } else
        if ($to < $from) {
            $to = $from;
        }
        return $from . ' - ' . $to;
    }

    /**
     * Function to get email template from database.
     * @param type $template_name
     * @return type
     */
    function get_template($template_name) {
        $access = ClassRegistry::init('EmailTemplate');
        $clientData = $access->find('first', array(
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
                'EmailList.id' => $template_name),
            'fields' => array('EmailTemplate.subject', 'EmailTemplate.header_msg', 'EmailTemplate.content', 'EmailTemplate.sms_body')
        ));
        return $clientData['EmailTemplate'];
    }

    /**
     * Gets the chronologically first transaction of a patient user.
     * If Clinic have accelerated reward access then assign tier level to Patient.
     * @param type $user_id
     * @param type $clinic_id clinic id to check clinic have accelerated reward access or not.
     * @return int
     */
    function get_firsttransaction($user_id, $clinic_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $accessData = $checkaccess->getAccessForClinic($clinic_id);
        //condition to check Accelerated reward program access for Pratice.
        if ($accessData['AccessStaff']['tier_setting'] == 1) {
            $this->setTierForPatient($user_id, $clinic_id, $accessData['AccessStaff']['tier_start_date'], $accessData['AccessStaff']['tier_timeframe']);
        }
        $tran = ClassRegistry::init('Transaction');
        $traData = $tran->find('all', array(
            'conditions' => array(
                'Transaction.user_id' => $user_id, 'Transaction.amount >' => 0, 'Transaction.activity_type' => 'N'),
            'fields' => array('Transaction.id')
        ));
        if (count($traData) > 1) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Set the tier level for patient when he get points from staff.
     * @param type $user_id
     * @param type $clinic_id
     * @param type $tier_start_date
     * @param type $tier_timeframe
     */
    function setTierForPatient($user_id, $clinic_id, $tier_start_date, $tier_timeframe) {
        $gettierAchivForPatient = ClassRegistry::init('TierAchievement');
        $tran = ClassRegistry::init('Transaction');
        $getListOfTier = ClassRegistry::init('TierSetting');
        $updatetierAchivForPatient = ClassRegistry::init('TierAchievement');
        //getting the all tier level for Patient.
        $data = $gettierAchivForPatient->getAllTierForPatient($user_id, $clinic_id);
        //condition to check user already start the reward program.
        if (!empty($data)) {
            //getting the current active tier level for patient.
            $currentdata = $gettierAchivForPatient->getCurrentTierForPatient($user_id, $clinic_id);
            $currentdate = date('Y-m-d H:i:s');
            if ($currentdate > $currentdata['TierAchievement']['end_tier']) {
                $startdate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($currentdata['TierAchievement']['end_tier'])) . " +1minutes"));
                $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($startdate)) . " +" . $tier_timeframe . "days"));
                $cycle = count($data) + 1;
                //if patient in second year of reward program.
                if ($cycle == 2) {
                    $tier_level_id = $gettierAchivForPatient->setLevelForPatient($user_id, $clinic_id, $cycle, $currentdata['TierAchievement']['current_level'], $currentdata['TierAchievement']['tier_id'], $startdate, $enddate);
                } else {
                    $alltrans = $tran->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $user_id,
                            'Transaction.clinic_id' => $clinic_id,
                            'Transaction.activity_type' => 'N',
                            'Transaction.date >=' => $currentdata['TierAchievement']['start_tier'],
                            'Transaction.date <' => $currentdata['TierAchievement']['end_tier']
                        ),
                        'fields' => array(
                            'SUM(Transaction.amount) AS points'
                        ),
                        'group' => array(
                            'Transaction.user_id'
                        )
                    ));
                    $getListOfTier = ClassRegistry::init('TierSetting');
                    //getting the list of all tier setting dependce on point value.
                    $tier_list = $getListOfTier->getListOfTierByPoints($clinic_id, $alltrans[0]['points']);
                    $lastlevel = $currentdata['TierAchievement']['current_level'];
                    $nextlevel = count($tier_list);
                    $checklevel = $lastlevel - $nextlevel;
                    if ($checklevel > 1) {
                        $updated_level = $lastlevel - 2;
                        $update_tier = $this->getLastTierLevel($clinic_id, $updated_level);
                    } else {
                        $updated_level = $lastlevel;
                        $update_tier = $this->getLastTierLevel($clinic_id, $updated_level);
                    }
                    //if patient enter to any level of reward program.
                    $tier_level_id = $gettierAchivForPatient->setLevelForPatient($user_id, $clinic_id, $cycle, $updated_level, $update_tier, $startdate, $enddate);
                }
            } else {
                $alltrans = $tran->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $user_id,
                        'Transaction.clinic_id' => $clinic_id,
                        'Transaction.activity_type' => 'N',
                        'Transaction.date >=' => $currentdata['TierAchievement']['start_tier']
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                //getting the list of all tier setting dependce on point value.
                $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($currentdata['TierAchievement']['start_tier'])) . " +" . $tier_timeframe . "days"));
                $tier_list = $getListOfTier->getListOfTierByPoints($clinic_id, $alltrans[0]['points']);
                if (!empty($tier_list)) {
                    $level = count($tier_list);
                    $tier_id = $tier_list[0]['TierSetting']['id'];
                    if ($currentdata['TierAchievement']['current_level'] <= count($tier_list)) {
                        //if patient in same year and get the next level then update his current level.
                        $updatetierAchivForPatient->updateLevelForPatient($currentdata['TierAchievement']['id'], $user_id, $clinic_id, $currentdata['TierAchievement']['cycle'], $level, $tier_id, $enddate);
                    } else {
                        $getcurtier = $this->getCurTier($clinic_id, $currentdata['TierAchievement']['tier_id']);
                        $checnglevel = $getcurtier;
                        $updatetierAchivForPatient->updateLevelForPatient($currentdata['TierAchievement']['id'], $user_id, $clinic_id, $currentdata['TierAchievement']['cycle'], $checnglevel, $currentdata['TierAchievement']['tier_id'], $enddate);
                    }
                    //if patient achieve any level and got coupon belong to this level.
                    $this->gotCouponWhenAchievTier($tier_list, $user_id, $clinic_id, $currentdata['TierAchievement']['start_tier']);
                } else {
                    $getcurtier = $this->getCurTier($clinic_id, $currentdata['TierAchievement']['tier_id']);
                    $checnglevel = $getcurtier;
                    $updatetierAchivForPatient->updateLevelForPatient($currentdata['TierAchievement']['id'], $user_id, $clinic_id, $currentdata['TierAchievement']['cycle'], $checnglevel, $currentdata['TierAchievement']['tier_id'], $enddate);
                }
            }
        } else {

            $traData = $tran->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $user_id, 'Transaction.clinic_id' => $clinic_id, 'Transaction.amount >' => 0, 'Transaction.activity_type' => 'N', 'Transaction.date >=' => $tier_start_date),
                'fields' => array('Transaction.date'),
                'order' => array('Transaction.date asc')
            ));

            if (!empty($traData)) {
                $enddate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($traData['Transaction']['date'])) . " +" . $tier_timeframe . "days"));
                $tier_level_id = $gettierAchivForPatient->setLevelForPatient($user_id, $clinic_id, 1, 0, 0, $traData['Transaction']['date'], $enddate);
                //condition to check user achived the first level on first point get.

                $alltrans = $tran->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $user_id,
                        'Transaction.clinic_id' => $clinic_id,
                        'Transaction.activity_type' => 'N',
                        'Transaction.date >=' => $tier_start_date
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                $getListOfTier = ClassRegistry::init('TierSetting');
                $tier_list = $getListOfTier->getListOfTierByPoints($clinic_id, $alltrans[0]['points']);
                if (!empty($tier_list)) {
                    $level = count($tier_list);
                    $tier_id = $tier_list[0]['TierSetting']['id'];
                    $updatetierAchivForPatient = ClassRegistry::init('TierAchievement');
                    $updatetierAchivForPatient->updateLevelForPatient($tier_level_id, $user_id, $clinic_id, 1, $level, $tier_id, $enddate);
                    //if patient achieve any level and got coupon belong to this level.
                    $this->gotCouponWhenAchievTier($tier_list, $user_id, $clinic_id, $traData['Transaction']['date']);
                }
            }
        }
    }

    /**
     * User get coupon and send mail when achieve Tier level.
     * @param type $tier_list
     * @param type $user_id
     * @param type $clinic_id
     * @param type $lstdt
     */
    function gotCouponWhenAchievTier($tier_list, $user_id, $clinic_id, $lstdt) {

        foreach ($tier_list as $coupgive) {
            if ($coupgive['TierSetting']['coupon_id'] != '') {
                $getuser = ClassRegistry::init('User');
                $patientclinic = $getuser->find('first', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_users',
                            'alias' => 'ClinicUser',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicUser.user_id = User.id'
                            )
                        ),
                        array(
                            'table' => 'clinics',
                            'alias' => 'Clinic',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Clinic.id = ClinicUser.clinic_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'User.id' => $user_id,
                        'Clinic.id' => $clinic_id
                    ),
                    'fields' => array(
                        'User.first_name',
                        'User.last_name',
                        'User.email',
                        'Clinic.display_name',
                        'Clinic.api_user',
                        'ClinicUser.card_number'
                    )
                ));
                $getcoupon = ClassRegistry::init('ProductService');
                $coupondata = $getcoupon->find('first', array(
                    'conditions' => array(
                        'ProductService.id' => $coupgive['TierSetting']['coupon_id']
                    )
                ));
                $getcouponavailMd = ClassRegistry::init('CouponAvail');
                $getcouponavail = $getcouponavailMd->find('all', array(
                    'conditions' => array(
                        'CouponAvail.user_id' => $user_id,
                        'CouponAvail.clinic_id' => $clinic_id,
                        'CouponAvail.coupon_id' => $coupgive['TierSetting']['coupon_id'],
                        'CouponAvail.created_on >=' => $lstdt
                    )
                ));
                $transactionMd = ClassRegistry::init('Transaction');
                //condition to check patient already got the coupon in current cycle.
                if (empty($getcouponavail)) {
                    $transaction = array(
                        'user_id' => $user_id,
                        'card_number' => $patientclinic['ClinicUser']['card_number'],
                        'first_name' => $patientclinic['User']['first_name'],
                        'last_name' => $patientclinic['User']['last_name'],
                        'promotion_id' => 0,
                        'activity_type' => 'Y',
                        'authorization' => 'Got a coupon -' . $coupondata['ProductService']['title'] . ' (' . $coupondata['ProductService']['points'] . ')',
                        'product_service_id' => $coupgive['TierSetting']['coupon_id'],
                        'amount' => 0,
                        'clinic_id' => $clinic_id,
                        'staff_id' => 0,
                        'redeem_from' => 1,
                        'doctor_id' => 0,
                        'date' => date('Y-m-d H:i:s'),
                        'status' => 'Active',
                        'is_buzzydoc' => 1
                    );
                    $transactionMd->create();
                    $transactionMd->save($transaction);
                    $tr_id = $transactionMd->getLastInsertId();
                    if ($patientclinic['Clinic']['display_name'] == '') {
                        $clinicname = $patientclinic['Clinic']['api_user'];
                    } else {
                        $clinicname = $patientclinic['Clinic']['display_name'];
                    }
                    if ($patientclinic['User']['email'] != '') {
                        $orderdetail = array('Clinic Name' => $clinicname, 'Coupon' => $coupondata['ProductService']['title'], 'Description' => $coupondata['ProductService']['description'], 'Coupon Image' => '<img src="' . S3Path . $coupondata['ProductService']['coupon_image'] . '" height="136" width="200">');
                        $template_array = $this->Api->get_template(16);
                        $link = str_replace('[username]', $patientclinic['User']['first_name'], $template_array['content']);
                        $link1 = str_replace('[coupon]', $coupondata['ProductService']['title'], $link);
                        $sub = str_replace('[clinic_name]', $clinicname, $template_array['subject']);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                        $Email->to($patientclinic['User']['email']);
                        $Email->subject($sub)
                                ->template('buzzydocother')
                                ->emailFormat('html');
                        $Email->viewVars(array('msg' => $link1,
                            'orderdetails' => $orderdetail
                        ));
                        $Email->send();
                    }
                    $CouponAvail_array['CouponAvail'] = array(
                        'user_id' => $user_id,
                        'clinic_id' => $clinic_id,
                        'coupon_id' => $coupgive['TierSetting']['coupon_id'],
                        'transaction_id' => $tr_id,
                        'availed' => 1,
                        'created_on' => date('Y-m-d H:i:s')
                    );
                    $getcouponavailMd->create();
                    $getcouponavailMd->save($CouponAvail_array);
                }
            }
        }
    }

    /**
     * retrive the free card number with details for practice
     * @param type $clinic_id
     * @return int
     */
    function get_freecardDetails($clinic_id) {
        $cardnumber = ClassRegistry::init('CardNumber');
        $freecard = $cardnumber->find('first', array(
            'conditions' => array(
                'CardNumber.clinic_id' => $clinic_id, 'CardNumber.status' => 1),
            'fields' => array('CardNumber.card_number', 'CardNumber.text'),
            'order' => array('CardNumber.card_number asc')
        ));
        $data['card_number'] = $freecard['CardNumber']['card_number'];
        return $data;
    }

    /**
     * Function to get unique username for Patient when creating child account.
     * @param type $username
     * @return string
     */
    function get_username($username) {
        $usern = ClassRegistry::init('User');
        $user = $usern->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'User.email like ' => '%' . $username . '%',
                    'User.parents_email like ' => '%' . $username . '%'
                )
            ),
            'fields' => array(
                'User.parents_email'
            )
        ));
        if (empty($user)) {
            $data = $username;
        } else {
            $arraynum = array();
            foreach ($user as $us) {
                $getnum = explode(strtolower($username), strtolower($us['User']['parents_email']));
                if (isset($getnum[1]) && $getnum[1] != '') {
                    $arraynum[] = $getnum[1];
                }
            }
            $lastnum = max($arraynum);
            if ($lastnum != '') {
                $nextnum = max($arraynum) + 1;
                $data = $username . $nextnum;
            } else {
                $data = $username . '1';
            }
        }
        return $data;
    }

    /**
     * Getting the current level for patient and multiplier value.
     * @param type $clinic_id
     * @param type $user_id
     * @return int
     */
    function getPatientLevelForAcceleratedReward($clinic_id, $user_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $traData = $checkaccess->getAccessForClinic($clinic_id);
        //condition to check Accelerated reward program access for Pratice.
        if ($traData['AccessStaff']['tier_setting'] == 1) {
            $gettierAchivForPatient = ClassRegistry::init('TierAchievement');
            $currentdata = $gettierAchivForPatient->getCurrentTierForPatient($user_id, $clinic_id);
            if (!empty($currentdata) && $currentdata['TierAchievement']['tier_id'] != 0) {
                $getAcceleratedreward = ClassRegistry::init('TierSetting');
                $currentdata = $getAcceleratedreward->getRecordById($currentdata['TierAchievement']['tier_id']);
                $getlast = $getAcceleratedreward->getNextTier($clinic_id, $currentdata['TierSetting']['points']);
                if (!empty($getlast)) {
                    return $getlast['TierSetting']['multiplier_value'];
                } else {
                    return $currentdata['TierSetting']['multiplier_value'];
                }
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    }

    /**
     * Getting the patient all tier level achived.
     * @param type $clinic_id
     * @param type $user_id
     * @return int
     */
    function getPatientAllLevelAchieved($clinic_id, $user_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $tran = ClassRegistry::init('Transaction');
        $traData = $checkaccess->getAccessForClinic($clinic_id);
        $array_for_level_achive = array();
        //condition to check Accelerated reward program access for Pratice.
        if ($traData['AccessStaff']['tier_setting'] == 1) {
            $gettierAchivForPatient = ClassRegistry::init('TierAchievement');
            $currentdata = $gettierAchivForPatient->getCurrentTierForPatient($user_id, $clinic_id);
            if (!empty($currentdata)) {
                $alltrans = $tran->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $user_id,
                        'Transaction.clinic_id' => $clinic_id,
                        'Transaction.activity_type' => 'N',
                        'Transaction.date >=' => $currentdata['TierAchievement']['start_tier']
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                $allpoints = $alltrans[0]['points'];
            } else {
                $allpoints = 0;
            }
            $getListOfTier = ClassRegistry::init('TierSetting');
            $tier_list_belong_to_clinic = $getListOfTier->getListOfTierByClinic($clinic_id);
            $count_tier = 0;
            foreach ($tier_list_belong_to_clinic as $list) {
                if (!empty($currentdata)) {
                    $diff = strtotime($currentdata['TierAchievement']['end_tier']) - strtotime(date('Y-m-d H:i:s'));
                    $days = round($diff / (60 * 60 * 24));
                } else {
                    $days = $traData['AccessStaff']['tier_timeframe'];
                }
                if ($days > 1) {
                    $dayleft = $days . ' Days';
                } else {
                    $dayleft = $days . ' Day';
                }


                if ($currentdata['TierAchievement']['current_level'] > $count_tier) {
                    $earning = $this->getEarningInPersent($list['TierSetting']['multiplier_value'], $clinic_id);

                    if (($currentdata['TierAchievement']['current_level']) == $count_tier) {
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['dayLeft_MorePoint'] = 'Earning ' . $earning . '% Back in Points<br>Already achieved the level.';
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['status'] = 0;
                    } else {
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['dayLeft_MorePoint'] = 'Earning ' . $earning . '% Back in Points<br>Already achieved the level.';
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['status'] = 0;
                    }
                } else {

                    $morepoint = $list['TierSetting']['points'] - $alltrans[0]['points'];
                    $earning = $this->getEarningInPersent($list['TierSetting']['multiplier_value'], $clinic_id);
                    if ((count($tier_list_belong_to_clinic) - 1) == $count_tier) {
                        $strmore = '';
                    } else {
                        $strmore = '<br>' . $morepoint . ' More Points Until Next Member Status Earned';
                    }
                    if (($currentdata['TierAchievement']['tier_id'] == 0 && $count_tier == 0) || (($currentdata['TierAchievement']['current_level']) == $count_tier)) {
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['dayLeft_MorePoint'] = 'Earning ' . $earning . '% Back in Points<br>' . $dayleft . ' Left in Earning Year' . $strmore;
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['status'] = 1;
                    } else {
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['dayLeft_MorePoint'] = 'Earning ' . $earning . '% Back in Points' . $strmore;
                        $array_for_level_achive[$list['TierSetting']['tier_name']]['status'] = 0;
                    }
                }
                $count_tier++;
            }
        }
        return $array_for_level_achive;
    }

    /**
     * Getting the patient current points in accelerated reward year.
     * @param type $clinic_id
     * @param type $user_id
     * @return int
     */
    function getPatientAcceleratedPoints($clinic_id, $user_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $tran = ClassRegistry::init('Transaction');
        $traData = $checkaccess->getAccessForClinic($clinic_id);
        $array_for_level_achive = array();
        $allpoints = 0;
        $days = 0;
        //condition to check Accelerated reward program access for Pratice.
        if ($traData['AccessStaff']['tier_setting'] == 1) {
            $gettierAchivForPatient = ClassRegistry::init('TierAchievement');
            $currentdata = $gettierAchivForPatient->getCurrentTierForPatient($user_id, $clinic_id);
            if (!empty($currentdata)) {
                $diff = strtotime($currentdata['TierAchievement']['end_tier']) - strtotime(date('Y-m-d H:i:s'));
                $days = round($diff / (60 * 60 * 24));
            } else {
                $days = $traData['AccessStaff']['tier_timeframe'];
            }
            if (!empty($currentdata)) {
                $alltrans = $tran->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $user_id,
                        'Transaction.clinic_id' => $clinic_id,
                        'Transaction.activity_type' => 'N',
                        'Transaction.date >=' => $currentdata['TierAchievement']['start_tier'],
                        'Transaction.date <=' => $currentdata['TierAchievement']['end_tier']
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                if (isset($alltrans[0]['points']) && $alltrans[0]['points'] != '') {
                    $allpoints = $alltrans[0]['points'];
                } else {
                    $allpoints = 0;
                }
            } else {
                $allpoints = 0;
            }
        }
        return $array_sum = array('TotalPoints' => $allpoints, 'Days' => $days);
    }

    /**
     * Getting the total points from purticular pratice.
     * @param type $clinic_id
     * @param type $user_id
     * @param type $is_buzzydoc
     * @return type
     */
    function getPatientPointsFromClinic($clinic_id, $user_id, $is_buzzydoc) {
        $tran = ClassRegistry::init('Transaction');
        if ($is_buzzydoc == 0) {
            $alltrans = $tran->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $user_id,
                    'Transaction.clinic_id' => $clinic_id,
                    'Transaction.is_buzzydoc' => 0
                ),
                'fields' => array(
                    'SUM(Transaction.amount) AS points'
                ),
                'group' => array(
                    'Transaction.user_id'
                )
            ));
            $allpoints = $alltrans[0]['points'];
        } else {
            $alltrans = $tran->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $user_id,
                    'Transaction.clinic_id' => $clinic_id,
                    'Transaction.is_buzzydoc' => 1
                ),
                'fields' => array(
                    'SUM(Transaction.amount) AS points'
                ),
                'group' => array(
                    'Transaction.user_id'
                )
            ));
            $tran1 = ClassRegistry::init('GlobalRedeem');
            $alltrans1 = $tran1->find('first', array(
                'conditions' => array(
                    'GlobalRedeem.user_id' => $user_id,
                    'GlobalRedeem.clinic_id' => $clinic_id
                ),
                'fields' => array(
                    'SUM(GlobalRedeem.amount) AS points'
                ),
                'group' => array(
                    'GlobalRedeem.user_id'
                )
            ));
            $allpoints = $alltrans[0]['points'] + $alltrans1[0]['points'];
        }
        return $allpoints;
    }

    /**
     * Getting the last tier id after year completion with downgrade level.
     * @param type $clinic_id
     * @param type $lastlevel
     * @return type
     */
    function getLastTierLevel($clinic_id, $lastlevel) {
        $tierSetting = ClassRegistry::init('TierSetting');
        $alltier = $tierSetting->find('all', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id
            ),
            'fields' => array(
                'TierSetting.id'
            ),
            'limit' => $lastlevel,
            'order' => array('TierSetting.points asc')
        ));
        $getlasttier = end($alltier);
        return $getlasttier['TierSetting']['id'];
    }

    /**
     * Getting the all access for practice.
     * @param type $client_id
     * @return type
     */
    function accessstaff($client_id) {
        $access = ClassRegistry::init('AccessStaff');
        return $staffaceess = $access->getAccessForClinic($client_id);
    }

    function getCurTier($clinic_id, $tier_id) {
        $tierSetting = ClassRegistry::init('TierSetting');
        $tierpoints = $tierSetting->find('first', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id,
                'TierSetting.id' => $tier_id
            ),
            'fields' => array(
                'TierSetting.points'
            )
        ));
        if (!empty($tierpoints)) {
            $alltierpoints = $tierSetting->find('all', array(
                'conditions' => array(
                    'TierSetting.clinic_id' => $clinic_id,
                    'TierSetting.points <=' => $tierpoints['TierSetting']['points']
                ),
                'fields' => array(
                    'TierSetting.*'
                )
            ));
        } else {
            $alltierpoints = array();
        }
        return count($alltierpoints);
    }

    /**
     * Getting the patient all tier level achived.
     * @param type $clinic_id
     * @param type $user_id
     * @return int
     */
    function getPatientCurrentLevel($clinic_id, $user_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $tran = ClassRegistry::init('Transaction');
        $traData = $checkaccess->getAccessForClinic($clinic_id);
        $array_for_level_achive = array();
        //condition to check Accelerated reward program access for Pratice.
        if ($traData['AccessStaff']['tier_setting'] == 1) {
            $gettierAchivForPatient = ClassRegistry::init('TierAchievement');
            $currentdata = $gettierAchivForPatient->getCurrentTierForPatient($user_id, $clinic_id);
            if (!empty($currentdata)) {
                $alltrans = $tran->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $user_id,
                        'Transaction.clinic_id' => $clinic_id,
                        'Transaction.activity_type' => 'N',
                        'Transaction.date >=' => $currentdata['TierAchievement']['start_tier']
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                $allpoints = $alltrans[0]['points'];
            } else {
                $allpoints = 0;
            }
            $getListOfTier = ClassRegistry::init('TierSetting');
            $getcurrentpoint = $getListOfTier->getCurrentTierByPoints($clinic_id, $allpoints);
            if (isset($getcurrentpoint['TierSetting']['points'])) {
                $getlover = $getcurrentpoint['TierSetting']['points'];
            } else {
                $getlover = 0;
            }
            $currentpointinlevel = $allpoints - $getlover;
            $getnexttierpoints = $getListOfTier->getNextTier($clinic_id, $getlover);
            $levelpnt = $getnexttierpoints['TierSetting']['points'] - $getlover;
            $currentpersentlevel = number_format(($currentpointinlevel / $levelpnt) * 100, 2);

            if ($currentpersentlevel < 0) {
                $currentpersentlevel = 100;
            }
            if (empty($currentdata) || $currentdata['TierAchievement']['current_level'] == 0) {
                $firstTier = $getListOfTier->getFirstTier($clinic_id);
                $array_for_level_achive['tier_id'] = $firstTier['TierSetting']['id'];
                $array_for_level_achive['tier_name'] = $firstTier['TierSetting']['tier_name'];
                $array_for_level_achive['earning'] = $this->getEarningInPersent($firstTier['TierSetting']['multiplier_value'], $clinic_id);
                $nextTier = $getListOfTier->getNextTier($clinic_id, $firstTier['TierSetting']['points']);
                $array_for_level_achive['next_earning'] = $this->getEarningInPersent($nextTier['TierSetting']['multiplier_value'], $clinic_id);
                $array_for_level_achive['more_points'] = $firstTier['TierSetting']['points'] - $allpoints;
                $array_for_level_achive['persent_achive'] = $currentpersentlevel;
            } else {
                $tier_list_belong_to_clinic = $getListOfTier->getListOfTierByClinic($clinic_id);
                $count_tier = 1;
                foreach ($tier_list_belong_to_clinic as $list) {
                    if ($currentdata['TierAchievement']['current_level'] == $count_tier) {
                        $currentTier = $getListOfTier->getRecordById($list['TierSetting']['id']);
                        $firstTier = $getListOfTier->getNextTier($clinic_id, $currentTier['TierSetting']['points']);
                        if (empty($firstTier)) {
                            $array_for_level_achive['tier_id'] = $currentTier['TierSetting']['id'];
                            $array_for_level_achive['tier_name'] = $currentTier['TierSetting']['tier_name'];
                            $array_for_level_achive['earning'] = $this->getEarningInPersent($currentTier['TierSetting']['multiplier_value'], $clinic_id);
                        } else {
                            $array_for_level_achive['tier_id'] = $firstTier['TierSetting']['id'];
                            $array_for_level_achive['tier_name'] = $firstTier['TierSetting']['tier_name'];
                            $array_for_level_achive['earning'] = $this->getEarningInPersent($firstTier['TierSetting']['multiplier_value'], $clinic_id);
                        }
                        $nextTier = $getListOfTier->getNextTier($clinic_id, $firstTier['TierSetting']['points']);
                        if (!empty($nextTier)) {
                            $array_for_level_achive['next_earning'] = $this->getEarningInPersent($nextTier['TierSetting']['multiplier_value'], $clinic_id);
                            $array_for_level_achive['more_points'] = $firstTier['TierSetting']['points'] - $allpoints;
                        } else {
                            $array_for_level_achive['next_earning'] = '';
                            if (empty($firstTier['TierSetting']['points'])) {
                                $array_for_level_achive['more_points'] = 'achieved';
                            } else {
                                $array_for_level_achive['more_points'] = $firstTier['TierSetting']['points'] - $allpoints;
                            }
                        }
                        $array_for_level_achive['persent_achive'] = $currentpersentlevel;
                    }
                    $count_tier++;
                }
            }
        }
        return $array_for_level_achive;
    }

    /**
     * Getting the earning value in persent.
     * @param type $multiplier
     * @param type $clinic_id
     * @return int
     */
    function getEarningInPersent($multiplier, $clinic_id) {
        $checkaccess = ClassRegistry::init('AccessStaff');
        $tran = ClassRegistry::init('Transaction');
        $traData = $checkaccess->getAccessForClinic($clinic_id);
        return $persent = number_format(($multiplier * $traData['AccessStaff']['base_value']), 2);
    }

    /**
     * get the all performance report for all staff user and practice.
     * @param type $goal_week
     * @param type $clinic_id
     * @param type $staff_id
     * @param type $type
     * @return type
     */
    function getClinicReportingData($goal_week, $clinic_id, $staff_id = null, $type = 0) {
        $previous_week = strtotime("$goal_week week +1 day");
        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $start_week = date("Y-m-d H:i:s", $start_week);
        $end_week = date("Y-m-d", $end_week) . ' 23:59:59';
        $duration = $start_week . ' - ' . $end_week;
        $clinic = ClassRegistry::init('Clinic');
        $GoalSetting = ClassRegistry::init('GoalSetting');
        $transaction = ClassRegistry::init('transaction');
        $allclinic = $clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = Clinic.id'
                    )
                )
            ),
            'conditions' => array(
                'AccessStaff.staff_reward_program' => 1,
                'Clinic_id' => $clinic_id),
            'fields' => array('Clinic.*')
        ));
        $getdata_array = array();
        foreach ($allclinic as $aclinic) {
            $getgoalsettings = $GoalSetting->getAllSettings($clinic_id, $staff_id);
            foreach ($getgoalsettings as $glseting) {
                if ($glseting['Goal']['goal_type'] == 1) {
                    if ($glseting['GoalSetting']['staff_id'] > 0) {
                        $getdata['return'] = $transaction->getTransactionReportForClinic('d', $duration, $clinic_id, $glseting['GoalSetting']['staff_id']);

                        $getdata['duration'] = $duration;
                        $getdata['details_for'] = $glseting;
                        $getdata_array[] = $getdata;
                    } else {
                        $getdata['return'] = $transaction->getTransactionReportForClinic('d', $duration, $clinic_id);
                        $getdata['duration'] = $duration;
                        $getdata['details_for'] = $glseting;
                        $getdata_array[] = $getdata;
                    }
                } else if ($glseting['Goal']['goal_type'] == 2) {

                    if ($glseting['GoalSetting']['staff_id'] > 0) {
                        $getdata['return'] = $transaction->getTransactionReportForClinic('d', $duration, $clinic_id, $glseting['GoalSetting']['staff_id'], $glseting['Goal']['promotion_id']);
                        $getdata['duration'] = $duration;
                        $getdata['details_for'] = $glseting;
                        $getdata_array[] = $getdata;
                    } else {
                        $getdata['return'] = $transaction->getTransactionReportForClinic('d', $duration, $clinic_id, null, $glseting['Goal']['promotion_id']);
                        $getdata['duration'] = $duration;
                        $getdata['details_for'] = $glseting;
                        $getdata_array[] = $getdata;
                    }
                }


                if ($type == 0) {
                    $this->logReport($getdata, $duration, $glseting);
                }
            }
        }
        return $getdata_array;
    }

    /**
     * Save performance report in to db.
     * @param type $getdata
     * @param type $duration
     * @param type $glseting
     */
    function logReport($getdata, $duration, $glseting) {
        $start_date = explode(' - ', $duration);

        $year = explode('-', $start_date[0]);
        $goalAchievement = ClassRegistry::init('GoalAchievement');
        $count = 0;
        foreach ($getdata['return'] as $usrcnt) {
            $count = $count + $usrcnt[0]['user_count'];
        }
        $date = new DateTime($start_date[1]);
        $week = $date->format("W");
        $checkachieve = $goalAchievement->find('first', array(
            'conditions' => array(
                'GoalAchievement.goal_id' => $glseting['GoalSetting']['goal_id'],
                'GoalAchievement.clinic_id' => $glseting['GoalSetting']['clinic_id'],
                'GoalAchievement.staff_id' => $glseting['GoalSetting']['staff_id'],
                'GoalAchievement.week_number' => $week
            )
        ));
        if (empty($checkachieve)) {
            $goal_achieve_array['GoalAchievement'] = array(
                'goal_id' => $glseting['GoalSetting']['goal_id'],
                'clinic_id' => $glseting['GoalSetting']['clinic_id'],
                'staff_id' => $glseting['GoalSetting']['staff_id'],
                'target_value' => $glseting['GoalSetting']['target_value'],
                'actual_value' => $count,
                'week_number' => $week,
                'year' => $year[0],
                'goal_start_date' => $start_date[0]
            );
            $goalAchievement->create();
            $goalAchievement->save($goal_achieve_array);
        }
    }

    /**
     * save all notification like refer,review,redeem
     * @param type $details_array notification details
     * @param type $type notification type
     * @param type $id notification id
     * @return int
     */
    function save_notification($details_array, $type, $id) {
        $clinicNotification = ClassRegistry::init('ClinicNotification');
        if ($type == 1) {
            $details = json_encode(array(
                'first_name' => $details_array['GlobalRedeem']['first_name'],
                'last_name' => $details_array['GlobalRedeem']['last_name'],
                'authorization' => $details_array['GlobalRedeem']['authorization']
            ));
            $notification_array = array('clinic_id' => $details_array['GlobalRedeem']['clinic_id'], 'notification_id' => $details_array['GlobalRedeem']['transaction_id'], 'notification_type' => 1, 'details' => $details, 'status' => 0, 'date' => $details_array['GlobalRedeem']['date']);
        }
        if ($type == 2) {
            $details = json_encode(array(
                'first_name' => $details_array['first_name'],
                'last_name' => $details_array['last_name'],
                'authorization' => $details_array['authorization']
            ));
            $notification_array = array('clinic_id' => $details_array['clinic_id'], 'notification_id' => $id, 'notification_type' => 1, 'details' => $details, 'status' => 0, 'date' => $details_array['date']);
        }
        if ($type == 3) {
            $details = json_encode(array(
                'first_name' => $details_array['User']['first_name'],
                'last_name' => $details_array['User']['last_name'],
                'platform' => $details_array['RateReview']['platform']
            ));
            $notification_array = array('clinic_id' => $details_array['RateReview']['clinic_id'], 'notification_id' => $details_array['RateReview']['id'], 'notification_type' => 2, 'details' => $details, 'status' => 0, 'date' => $details_array['RateReview']['created_on']);
        }
        if ($type == 4) {
            $user = ClassRegistry::init('User');
            $userdetails = $user->find('first', array('conditions' => array('User.id' => $details_array['user_id'])));
            $details = json_encode(array(
                'first_name' => $details_array['first_name'],
                'last_name' => $details_array['last_name'],
                'referrer' => $userdetails['User']['first_name'] . ' ' . $userdetails['User']['last_name']
            ));
            $notification_array = array('clinic_id' => $details_array['clinic_id'], 'notification_id' => $id, 'notification_type' => 3, 'details' => $details, 'status' => 0, 'date' => $details_array['refdate']);
        }
        $clinicNotification->create();
        $clinicNotification->save($notification_array);
        if ($type == 1) {
            return $clinicNotification->getInsertID();
        }
    }

    public function getTotalPoints($data, $clinic_id) {
        $tran = ClassRegistry::init('Transaction');
        $globtran = ClassRegistry::init('GlobalRedeem');
        $dateRange = explode(' - ', $data['date_range_picker']);
        $curdate = $dateRange[1];
        $date = $dateRange[0];

        $getvip = $tran->find('first', array(
            'conditions' => array(
                'Transaction.activity_type' => $data['transaction_type'],
                'Transaction.clinic_id' => $clinic_id,
                'Transaction.date >=' => $date,
                'Transaction.date <=' => $curdate
            ),
            'fields' => array('sum(Transaction.amount) AS total')
        ));
        $getglobalred = 0;
        if ($data['transaction_type'] == 'Y') {
            $getvip1 = $globtran->find('first', array(
                'conditions' => array(
                    'GlobalRedeem.activity_type' => $data['transaction_type'],
                    'GlobalRedeem.clinic_id' => $clinic_id,
                    'GlobalRedeem.date >=' => $date,
                    'GlobalRedeem.date <=' => $curdate
                ),
                'fields' => array('sum(GlobalRedeem.amount) AS total')
            ));
            $getglobalred = $getvip1[0]['total'];
        }
        if ($data['transaction_type'] == 'Y') {
            $totalamount = ($getvip[0]['total'] + $getglobalred) * -1;
        } else {
            $totalamount = $getvip[0]['total'] + $getglobalred;
        }
        return $totalamount;
    }

    public function getPointhistory($data, $clinic_id) {
        $tran = ClassRegistry::init('Transaction');
        $globtran = ClassRegistry::init('GlobalRedeem');
        $dateRange = explode(' - ', $data['date_range_picker']);
        $curdate = $dateRange[1];
        $date = $dateRange[0];
        $Patients = $tran->find('all', array(
            'fields' => array('first_name', 'last_name', 'card_number', 'doctor_id', 'staff_id', 'authorization', 'amount', 'date'),
            'conditions' => array(
                'Transaction.clinic_id' => $clinic_id, 'Transaction.date >=' => $date, 'Transaction.date <=' => $curdate, 'Transaction.activity_type' => $data['transaction_type'], 'Transaction.amount !=' => 0
            ),
            'order' => array('Transaction.date desc')
        ));

        if ($Patients) {
            $Patients = array_column($Patients, 'Transaction');
        }
        if ($data['transaction_type'] == 'Y') {
            $Global_Redeem_Patients = $globtran->find('all', array(
                'fields' => array('first_name', 'last_name', 'card_number', 'doctor_id', 'staff_id', 'authorization', 'amount', 'date'),
                'conditions' => array(
                    'GlobalRedeem.clinic_id' => $clinic_id, 'GlobalRedeem.date >=' => $date, 'GlobalRedeem.date <=' => $curdate, 'GlobalRedeem.activity_type' => $data['transaction_type']
                ),
                'order' => array('GlobalRedeem.date desc')
            ));

            if ($Global_Redeem_Patients) {
                $Global_Redeem_Patients = array_column($Global_Redeem_Patients, 'GlobalRedeem');
            }
            $Patients = array_merge($Patients, $Global_Redeem_Patients);
            self::array_sort_by_column($Patients, 'date');
        }
        return $Patients;
    }

    /**
     *  function to short the array by column.
     * @param type $array
     * @param type $column
     * @param type $direction
     */
    function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
        $reference_array = array();

        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }

}

?>
