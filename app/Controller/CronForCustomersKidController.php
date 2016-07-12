<?php

/**
 * This file for run cron on daily basis.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller running the cron on daily basis for many functionality.
 * Patient Get coupon when reached the milestone.
 * Send reminder when pratice downgrade or upgrade.
 * Mail when point expire when user account inactive for 2 year.
 * When charge minimum depoise for buzzydoc clinic.
 * Mail send to patient when child become adult.
 * Birthday wish to staff user.
 * Reminder to new reward added by Staff user for pratice.
 * daily update of stock image from s3. 
 * weekly generate report for staff and clinic
 */
class CronForCustomersKidController extends AppController {

    /**
     * We use the session,api and CakeS3 component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));

    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $uses = array('User', 'ClinicUser', 'CardNumber', 'Document', 'Reward', 'patients', 'ProfileField', 'ProfileFieldUser', 'Transaction', 'Clinic', 'Notification', 'Staff', 'UsersBadge', 'Badge', 'GlobalRedeem', 'TangoAccount', 'Invoice', 'PaymentDetail', 'StockImage', 'ProductService', 'CouponAvail', 'MilestoneReward', 'FailedPayment', 'RssFeed', 'ClinicNotification');
    public $rss_item = array();
    protected $_feed = "http://blog.buzzydoc.com/feed/";

    /**
     * This cron is run daily for reminder to Patient,staff user and super admin for deffrent activity.
     */
    public function index() {


        //11 -send email to staff for report
        $Clinic_rep = $this->Clinic->find('all');
        foreach ($Clinic_rep as $cl) {
            $staffaceess = $this->Api->accessstaff($cl['Clinic']['id']);
            $enddate = date('Y-m-d', date(strtotime("+" . $staffaceess['AccessStaff']['report'] . " day", strtotime($cl['Clinic']['log_time']))));
            if (date('Y-m-d') > $enddate) {
                $options2['conditions'] = array('Staff.clinic_id' => $cl['Clinic']['id'], 'Staff.report_mail' => 1);
                $staff = $this->Staff->find('all', $options2);
                foreach ($staff as $stf) {
                    $template_array = $this->Api->get_template(32);
                    $link = str_replace('[staff_name]', $stf['Staff']['staff_id'], $template_array['content']);
                    $link1 = str_replace('[click_here]', '<a href="' . rtrim($cl['Clinic']['staff_url'], '/') . '/staff/basicreport/' . base64_encode($stf['Staff']['id']) . '">Click Here</a>', $link);
                    $sub = str_replace('[clinic_name]', $cl['Clinic']['api_user'], $template_array['subject']);
                    $Email = new CakeEmail(MAILTYPE);
                    $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                    $Email->to($stf['Staff']['staff_email']);
                    $Email->subject($sub)
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array(
                        'msg' => $link1
                    ));
                    try {
                        $Email->send();
                        echo "mail send to " . $stf['Staff']['staff_email'] . " for weekly basic reporting";
                    } catch (Exception $e) {
                        CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
                    }
                    $loc['Clinic'] = array('id' => $cl['Clinic']['id'], 'log_time' => date('Y-m-d'));
                    $this->Clinic->save($loc);
                }
            }
        }
        //10 -store rss feeds to table

        $rss = simplexml_load_file($this->_feed);
        $rss_split = array();
        foreach ($rss->channel->item as $item) {
            $title = $item->title; // Title
            $link = $item->link; // Url Link
            $description = $item->description; // Description
            $rss_data[] = array(
                'link' => $link,
                'title' => $title,
                'description' => $description
            );
        }

        if (!empty($rss_data)) {
            $this->RssFeed->query('TRUNCATE TABLE rss_feeds;');
            $this->RssFeed->saveAll($rss_data);
        }

        $clinicfornot = $this->Clinic->find('all');
        foreach ($clinicfornot as $clnot) {
            $feeds = $this->RssFeed->find('all');
            foreach ($feeds as $fd) {
                $getnotfeed = $this->ClinicNotification->find('first', array(
                    'conditions' => array(
                        'ClinicNotification.notification_id' => $fd['RssFeed']['id'],
                        'ClinicNotification.notification_type' => 4,
                        'ClinicNotification.clinic_id' => $clnot['Clinic']['id']
                    )
                ));
                if (empty($getnotfeed)) {
                    $details = json_encode(array(
                        'link' => $fd['RssFeed']['link'],
                        'title' => $fd['RssFeed']['title']
                    ));
                    $notification_array = array('clinic_id' => $clnot['Clinic']['id'], 'notification_id' => $fd['RssFeed']['id'], 'notification_type' => 4, 'details' => $details, 'status' => 0, 'date' => date('Y-m-d H:i:s'));
                    $this->ClinicNotification->create();
                    $this->ClinicNotification->save($notification_array);
                }
            }
        }
        //0:-weekly generate report for staff and clinic

        $previous_week = strtotime("0 week +1 day");
        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);
        $end_week = date("Y-m-d", $end_week) . ' 23:59:59';
        $todaycheck = date('Y-m-d :h:i:s');
        if ($todaycheck > $end_week) {
            $clinicreporting = $this->Clinic->find('all', array(
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
                    'AccessStaff.staff_reward_program' => 1),
                'fields' => array('Clinic.id', 'Clinic.api_user', 'Clinic.display_name')
            ));
            foreach ($clinicreporting as $clinicreport) {
                $reports = $this->Api->getClinicReportingData(-1, $clinicreport['Clinic']['id'], null, 0);
            }
        }


        //1:-cron for patient get coupon when reached the milestone.
        //getting the clinic list who have milestone functionality active.
        $clinicforcoupon = $this->Clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'product_services',
                    'alias' => 'product_services',
                    'type' => 'INNER',
                    'conditions' => array(
                        'product_services.clinic_id = Clinic.id'
                    )
                ), array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = product_services.clinic_id'
                    )
                )
            ),
            'conditions' => array(
                'product_services.type' => 3, 'AccessStaff.product_service' => 1, 'AccessStaff.milestone_reward' => 1),
            'group' => array('product_services.clinic_id'),
            'fields' => array('Clinic.id', 'Clinic.api_user', 'Clinic.display_name')
        ));
        foreach ($clinicforcoupon as $cln) {


            $usersget = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'ClinicUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ClinicUser.user_id = User.id'
                        )
                    )),
                'conditions' => array(
                    'ClinicUser.clinic_id' => $cln['Clinic']['id'],
                    'User.points >' => 0,
                    'User.email !=' => ''
                ),
                'fields' => array('User.*', 'ClinicUser.*')
            ));

            foreach ($usersget as $userdt) {
                $getfirsttransaction = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $userdt['User']['id'],
                        'Transaction.activity_type' => 'N',
                        'Transaction.clinic_id' => $cln['Clinic']['id']
                    ),
                    'fields' => array('Transaction.date'),
                    'order' => array('Transaction.date asc')
                ));
                $getlastdate = explode('-', $getfirsttransaction['Transaction']['date']);
                $lstdt = date('Y') . '-' . $getlastdate[1] . '-' . $getlastdate[2];
                $diffdefcheck = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
                $expdatechek = floor($diffdefcheck / (60 * 60 * 24));
                if ($expdatechek < 0) {
                    $lstdt = (date('Y') - 1) . '-' . $getlastdate[1] . '-' . $getlastdate[2];
                }
                $diffdef = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
                $expdate = floor($diffdef / (60 * 60 * 24));
                $getglb = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $userdt['User']['id'],
                        'Transaction.activity_type' => 'N',
                        'Transaction.clinic_id' => $cln['Clinic']['id'],
                        'Transaction.date >=' => $lstdt,
                        'Transaction.date <=' => date('Y-m-d H:i:s')
                    ),
                    'fields' => array('sum(Transaction.amount) AS total')
                ));


                $coupondet = $this->MilestoneReward->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'product_services',
                            'alias' => 'ProductService',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ProductService.id = MilestoneReward.coupon_id'
                            )
                        )
                    ),
                    'conditions' => array(
                        'MilestoneReward.clinic_id' => $cln['Clinic']['id'], 'MilestoneReward.points <=' => $getglb[0]['total']),
                    'order' => array('MilestoneReward.points asc'),
                    'fields' => array('ProductService.*', 'MilestoneReward.*')
                ));
                foreach ($coupondet as $coupgive) {
                    $getcouponavail = $this->CouponAvail->find('all', array(
                        'conditions' => array(
                            'CouponAvail.user_id' => $userdt['User']['id'],
                            'CouponAvail.clinic_id' => $cln['Clinic']['id'],
                            'CouponAvail.coupon_id' => $coupgive['ProductService']['id'],
                            'CouponAvail.created_on >=' => $lstdt
                        )
                    ));
                    //condition to check patient already get the coupon or not.
                    if (!empty($coupgive) && empty($getcouponavail)) {
                        $transaction = array(
                            'user_id' => $userdt['User']['id'],
                            'card_number' => $userdt['ClinicUser']['card_number'],
                            'first_name' => $userdt['User']['first_name'],
                            'last_name' => $userdt['User']['last_name'],
                            'promotion_id' => 0,
                            'activity_type' => 'Y',
                            'authorization' => 'Got a coupon -' . $coupgive['ProductService']['title'] . ' (' . $coupgive['ProductService']['points'] . ')',
                            'product_service_id' => $coupgive['ProductService']['id'],
                            'amount' => 0,
                            'clinic_id' => $cln['Clinic']['id'],
                            'staff_id' => 0,
                            'redeem_from' => 1,
                            'doctor_id' => 0,
                            'date' => date('Y-m-d H:i:s'),
                            'status' => 'Active',
                            'is_buzzydoc' => $cln['Clinic']['is_buzzydoc']
                        );
                        $this->Transaction->create();
                        $this->Transaction->save($transaction);
                        $tr_id = $this->Transaction->getLastInsertId();
                        $template_array_red = $this->Api->save_notification($transaction, 2, $tr_id);
                        if ($cln['Clinic']['display_name'] == '') {
                            $clinicname = $cln['Clinic']['api_user'];
                        } else {
                            $clinicname = $cln['Clinic']['display_name'];
                        }

                        $orderdetail = array('Clinic Name' => $clinicname, 'Coupon' => $coupgive['ProductService']['title'], 'Description' => $coupgive['ProductService']['description'], 'Coupon Image' => '<img src="' . S3Path . $coupgive['ProductService']['coupon_image'] . '" height="136" width="200">');
                        $template_array = $this->Api->get_template(16);
                        $link = str_replace('[username]', $userdt['User']['first_name'], $template_array['content']);
                        $link1 = str_replace('[coupon]', $coupgive['ProductService']['title'], $link);
                        $sub = str_replace('[clinic_name]', $clinicname, $template_array['subject']);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                        $Email->to($userdt['User']['email']);
                        $Email->subject($sub)
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email->viewVars(array('msg' => $link1,
                            'orderdetails' => $orderdetail
                        ));
                        $Email->send();
                        $CouponAvail_array['CouponAvail'] = array(
                            'user_id' => $userdt['User']['id'],
                            'clinic_id' => $cln['Clinic']['id'],
                            'coupon_id' => $coupgive['ProductService']['id'],
                            'transaction_id' => $tr_id,
                            'availed' => 1,
                            'created_on' => date('Y-m-d H:i:s')
                        );
                        $this->CouponAvail->create();
                        $this->CouponAvail->save($CouponAvail_array);
                    }
                }
                echo $userdt['User']['email'] . '<br>' . $userdt['User']['id'];
            }
        }

        //2:-cron for send reminder for downgrading from Buzzydoc full to BuzzyDoc legacy 
        $downoption['conditions'] = array('Clinic.is_buzzydoc' => 1, 'Clinic.down_date !=' => '0000-00-00');
        $downclinic = $this->Clinic->find('all', $downoption);
        $sheduledate = 60;
        $date21 = date("Y-m-d");
        foreach ($downclinic as $down) {

            $usersget = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_users',
                        'alias' => 'clinic_users',
                        'type' => 'INNER',
                        'conditions' => array(
                            'clinic_users.user_id = User.id'
                        )
                    )),
                'conditions' => array(
                    'clinic_users.clinic_id' => $down['Clinic']['id'],
                    'User.points >' => 0
                )
            ));

            $date11 = $down['Clinic']['down_date'];
            $diff1 = strtotime($date21) - strtotime($date11);
            $days1 = floor($diff1 / (60 * 60 * 24));

            $remainday = $sheduledate - $days1;
            //condition to check expire date (60 days) reached or not.
            if ($remainday > 0) {
                foreach ($usersget as $userem) {
                    if ($userem['User']['email'] != '') {
                        $getlastglbtansaction = $this->Transaction->find('first', array(
                            'conditions' => array(
                                'Transaction.user_id' => $userem['User']['id'],
                                'Transaction.is_buzzydoc' => 1,
                                'Transaction.clinic_id' => 0,
                                'Transaction.activity_type' => 'Y'
                            ),
                            'order' => array('Transaction.date desc'),
                            'fields' => array('Transaction.date')
                        ));

                        if (empty($getlastglbtansaction)) {
                            $getglb = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $userem['User']['id'],
                                    'Transaction.is_buzzydoc' => 1,
                                    'Transaction.clinic_id' => $down['Clinic']['id']
                                ),
                                'group' => array('Transaction.clinic_id'),
                                'fields' => array('sum(Transaction.amount) AS total')
                            ));
                        } else {
                            $getglb = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $userem['User']['id'],
                                    'Transaction.is_buzzydoc' => 1,
                                    'Transaction.clinic_id' => $down['Clinic']['id'],
                                    'Transaction.date >' => $getlastglbtansaction['Transaction']['date']
                                ),
                                'group' => array('Transaction.clinic_id'),
                                'fields' => array('sum(Transaction.amount) AS total')
                            ));
                        }

                        if ($userem['User']['first_name'] != '' || $userem['User']['last_name'] != '') {
                            $username = $userem['User']['first_name'] . ' ' . $userem['User']['last_name'];
                        } else {
                            $username = $userem['User']['email'];
                        }

                        if ($getglb[0]['total'] > 0) {

                            $template_array = $this->Api->get_template(20);
                            $link = str_replace('[username]', $username, $template_array['content']);
                            $link1 = str_replace('[clinic_name]', $down['Clinic']['api_user'], $link);
                            $link2 = str_replace('[points]', $getglb[0]['total'], $link1);
                            $link3 = str_replace('[days]', $remainday, $link2);
                            $Email = new CakeEmail(MAILTYPE);

                            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                            $Email->to($userem['User']['email']);
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email->viewVars(array('msg' => $link3
                            ));
                            $Email->send();
                        }
                    }
                    echo $username . ' redeem  point with in ' . $remainday . ' days <br>';
                }
            } else {
                foreach ($usersget as $userem) {
                    $getlastglbtansaction = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $userem['User']['id'],
                            'Transaction.is_buzzydoc' => 1,
                            'Transaction.clinic_id' => 0,
                            'Transaction.activity_type' => 'Y'
                        ),
                        'order' => array('Transaction.date desc'),
                        'fields' => array('Transaction.date')
                    ));
                    if (empty($getlastglbtansaction)) {
                        $getglb = $this->Transaction->find('first', array(
                            'conditions' => array(
                                'Transaction.user_id' => $userem['User']['id'],
                                'Transaction.is_buzzydoc' => 1,
                                'Transaction.clinic_id' => $down['Clinic']['id']
                            ),
                            'group' => array('Transaction.clinic_id'),
                            'fields' => array('sum(Transaction.amount) AS total')
                        ));
                    } else {
                        $getglb = $this->Transaction->find('first', array(
                            'conditions' => array(
                                'Transaction.user_id' => $userem['User']['id'],
                                'Transaction.is_buzzydoc' => 1,
                                'Transaction.clinic_id' => $down['Clinic']['id'],
                                'Transaction.date >' => $getlastglbtansaction['Transaction']['date']
                            ),
                            'group' => array('Transaction.clinic_id'),
                            'fields' => array('sum(Transaction.amount) AS total')
                        ));
                    }
                    $remainpt = $userem['User']['points'] - $getglb[0]['total'];
                    if ($getglb[0]['total'] > 0) {
                        $ratereview_array['Transaction'] = array(
                            'user_id' => $userem['User']['id'],
                            'clinic_id' => $down['Clinic']['id'],
                            'first_name' => $userem['User']['first_name'],
                            'last_name' => $userem['User']['last_name'],
                            'activity_type' => 'N',
                            'authorization' => 'global points lapsed',
                            'amount' => '-' . $getglb[0]['total'],
                            'status' => 'New',
                            'date' => date('Y-m-d H:i:s'),
                            'is_buzzydoc' => 1
                        );
                        $this->Transaction->create();
                        $this->Transaction->save($ratereview_array);
                        $this->User->query("update users set points=" . $remainpt . " where id=" . $userem['User']['id']);
                    }
                    echo $username . ' global points lapsed <br>';
                }
                $this->Clinic->query("update clinics set down_date='0000-00-00',is_buzzydoc=0,minimum_deposit=0 where id=" . $down['Clinic']['id']);
            }
        }


        //3:-cron for expire a points for buzzydoc user inactive till 2 years.
        //check the notification setting for patient.
        $usersgetex = $this->Notification->find('all', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Notification.user_id'
                    )
                ),
            ),
            'conditions' => array(
                'Notification.points_expire' => 1,
                'User.email !=' => '',
                'User.points >' => 0
            ),
            'fields' => array('User.*'),
            'group' => array('Notification.user_id')
        ));

        foreach ($usersgetex as $useremex) {
            $buzzylogin = Buzzy_Name . 'buzzydoc/login/' . base64_encode($useremex['User']['id']) . '/Unsubscribe';
            $getlastglbtansactionex = $this->Transaction->find('first', array(
                'conditions' => array(
                    'Transaction.user_id' => $useremex['User']['id'],
                    'Transaction.is_buzzydoc' => 1
                ),
                'order' => array('Transaction.date desc'),
                'fields' => array('Transaction.date')
            ));
            if (!empty($getlastglbtansactionex)) {
                $lstdt = $getlastglbtansactionex['Transaction']['date'];
                $diffdef = strtotime(date('Y-m-d H:i:s')) - strtotime($lstdt);
                $expdate = floor($diffdef / (60 * 60 * 24));
                //condition to check user account inactive till 2 years.
                if ($expdate == 730) {
                    $getlasttansaction = $this->Transaction->find('first', array(
                        'conditions' => array(
                            'Transaction.user_id' => $useremex['User']['id'],
                            'Transaction.is_buzzydoc' => 1,
                            'Transaction.clinic_id' => 0,
                            'Transaction.activity_type' => 'Y'
                        ),
                        'order' => array('Transaction.date desc'),
                        'fields' => array('Transaction.date')
                    ));

                    if (empty($getlasttansaction)) {
                        $getglb = $this->Transaction->find('all', array(
                            'conditions' => array(
                                'Transaction.user_id' => $useremex['User']['id'],
                                'Transaction.is_buzzydoc' => 1
                            ),
                            'group' => array('Transaction.clinic_id'),
                            'fields' => array('sum(Transaction.amount) AS total', 'Transaction.clinic_id')
                        ));
                    } else {
                        $getglb = $this->Transaction->find('all', array(
                            'conditions' => array(
                                'Transaction.user_id' => $useremex['User']['id'],
                                'Transaction.is_buzzydoc' => 1,
                                'Transaction.date >' => $getlasttansaction['Transaction']['date']
                            ),
                            'group' => array('Transaction.clinic_id'),
                            'fields' => array('sum(Transaction.amount) AS total', 'Transaction.clinic_id')
                        ));
                    }
                    foreach ($getglb as $glbpt) {
                        $globalredeem_array['GlobalRedeem'] = array(
                            'user_id' => $useremex['User']['id'],
                            'clinic_id' => $glbpt['Transaction']['clinic_id'],
                            'first_name' => $useremex['User']['first_name'],
                            'last_name' => $useremex['User']['last_name'],
                            'activity_type' => 'Y',
                            'authorization' => 'global points expired',
                            'amount' => '-' . $glbpt[0]['total'],
                            'status' => 'New',
                            'date' => date('Y-m-d H:i:s'),
                            'is_buzzydoc' => 1
                        );
                        $this->GlobalRedeem->create();
                        $this->GlobalRedeem->save($globalredeem_array);
                    }

                    $ratereview_arrayex['Transaction'] = array(
                        'user_id' => $useremex['User']['id'],
                        'clinic_id' => 0,
                        'first_name' => $useremex['User']['first_name'],
                        'last_name' => $useremex['User']['last_name'],
                        'activity_type' => 'Y',
                        'authorization' => 'global points expired',
                        'amount' => '-' . $useremex['User']['points'],
                        'status' => 'New',
                        'date' => date('Y-m-d H:i:s'),
                        'is_buzzydoc' => 1
                    );
                    $this->Transaction->create();
                    $this->Transaction->save($ratereview_arrayex);
                    $this->User->query("update users set points=0 where id=" . $useremex['User']['id']);
                    if ($useremex['User']['first_name'] != '' || $useremex['User']['last_name'] != '') {
                        $username = $useremex['User']['first_name'] . ' ' . $useremex['User']['last_name'];
                    } else {
                        $username = $useremex['User']['email'];
                    }

                    $template_array = $this->Api->get_template(17);
                    $link = str_replace('[username]', $username, $template_array['content']);
                    $link1 = str_replace('[points]', $useremex['User']['points'], $link);
                    $Email = new CakeEmail(MAILTYPE);

                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                    $Email->to($useremex['User']['email']);
                    $Email->subject($template_array['subject'])
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array('msg' => $link1
                    ));
                    $Email->send();
                } else if ($expdate == 365 || $expdate == 395 || $expdate == 426 || $expdate == 456 || $expdate == 487 || $expdate == 517 || $expdate == 548 || $expdate == 578 || $expdate == 609 || $expdate == 639 || $expdate == 670 || $expdate == 700) {
                    if ($useremex['User']['first_name'] != '' || $useremex['User']['last_name'] != '') {
                        $username = $useremex['User']['first_name'] . ' ' . $useremex['User']['last_name'];
                    } else {
                        $username = $useremex['User']['email'];
                    }
                    $rmtm = round((730 - $expdate) / 30);
                    $rmtm1 = round(($expdate) / 30);
                    $template_array = $this->Api->get_template(18);
                    $link = str_replace('[username]', $username, $template_array['content']);
                    $link1 = str_replace('[last_month]', $rmtm1, $link);
                    $link2 = str_replace('[remain_month]', $rmtm, $link1);
//                    $link3 = str_replace('[amount]', round($useremex['User']['points'] / 50, 2), $link2);
                    $Email = new CakeEmail(MAILTYPE);

                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                    $Email->to($useremex['User']['email']);
                    $Email->subject($template_array['subject'])
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array('msg' => $link2
                    ));
                    $Email->send();
                    echo $username . ' point will expire. <br>';
                }
            }
        }

        //4:-cron for get minimum deposite from all buzzydoc clinic
        //list of all buzzydoc practice.
        $clinicall = $this->Clinic->find('all', array(
            'conditions' => array(
                'Clinic.is_buzzydoc' => 1, 'Clinic.minimum_deposit >' => 0
            )
        ));
        foreach ($clinicall as $cln) {
            $options4['conditions'] = array('Invoice.clinic_id' => $cln['Clinic']['id']);
            $findlastpay = $this->Invoice->find('first', $options4);
            $paydet['conditions'] = array('PaymentDetail.clinic_id' => $cln['Clinic']['id']);
            $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
            //condition to check clinic already pay minimum deposite,payment details is set for clinic or not
            if (empty($findlastpay) && !empty($getpayemntdetails) && $getpayemntdetails['PaymentDetail']['customer_account_profile_id'] > 0) {
                $optionsstaff['conditions'] = array('Staff.clinic_id' => $cln['Clinic']['id'], 'Staff.staff_role' => 'Doctor');
                $staffdoc = $this->Staff->find('first', $optionsstaff);
                //transaction fees .35 $ + .75 % of minimum deposit.
                $transactionFee = .35 + $cln['Clinic']['minimum_deposit'] * (.75 / 100);
                $totalcredit1 = $cln['Clinic']['minimum_deposit'] + $transactionFee;
                $totalcredit = number_format($totalcredit1, 2, '.', '');
                $transaction = new AuthorizeNetTransaction;
                $transaction->amount = $totalcredit;
                $transaction->customerProfileId = $getpayemntdetails['PaymentDetail']['customer_account_id'];
                $transaction->customerPaymentProfileId = $getpayemntdetails['PaymentDetail']['customer_account_profile_id'];

                $lineItem = new AuthorizeNetLineItem;
                $item_id = mt_rand(10000, 999999);
                $lineItem->itemId = $item_id;
                $lineItem->name = 'Minimum deposit';
                $lineItem->description = "Credit for payments";
                $lineItem->quantity = "1";
                $lineItem->unitPrice = $cln['Clinic']['minimum_deposit'];
                $lineItem->taxable = "true";
                $transaction->lineItems[] = $lineItem;
                //charge the minimum deposit using authorize.net
                $request = new AuthorizeNetCIM;
                $response = $request->createCustomerProfileTransaction("AuthCapture", $transaction);
                //condition to check success response.
                if ($response->xml->messages->message->code == 'I00001') {
                    //echo $response->xml->messages->message->text . '<br>';

                    $transactionResponse = $response->getTransactionResponse();
                    $trnsid = $transactionResponse->transaction_id;

                    $Invoice_array['Invoice'] = array(
                        'clinic_id' => $cln['Clinic']['id'],
                        'amount' => $cln['Clinic']['minimum_deposit'],
                        'transaction_fee' => $transactionFee,
                        'invoice_id' => $trnsid,
                        'mode' => 'Credit',
                        'current_balance' => $cln['Clinic']['minimum_deposit'],
                        'payed_on' => date('Y-m-d H:i:s'),
                        'status' => 1
                    );
                    $this->Invoice->create();
                    $this->Invoice->save($Invoice_array);

                    if (!empty($staffdoc)) {
                        $template_array = $this->Api->get_template(33);
                        $link = str_replace('[staff_name]', $staffdoc['Staff']['staff_id'], $template_array['content']);
                        $link1 = str_replace('[credit_amount]', $cln['Clinic']['minimum_deposit'], $link);
                        $link2 = str_replace('[current_balance]', $cln['Clinic']['minimum_deposit'], $link1);
                        $Email1 = new CakeEmail(MAILTYPE);

                        $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                        $Email1->to($staffdoc['Staff']['staff_email']);
                        $Email1->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email1->viewVars(array('msg' => $link2
                        ));
                        $Email1->send();
                        echo 'credit amount to clinic- ' . $cln['Clinic']['api_user'] . '<br>';
                    }
                    $update_payment['PaymentDetail'] = array(
                        'id' => $getpayemntdetails['PaymentDetail']['id'],
                        'reminder_date' => '0000-00-00',
                        'reminder_count' => 0
                    );
                    $this->PaymentDetail->save($update_payment);
                } else {
                    $failed_payment['FailedPayment'] = array(
                        'clinic_id' => $cln['Clinic']['id'],
                        'user_id' => 0,
                        'subject' => $response->xml->messages->message->text,
                        'description' => 'Get minimum deposite from practice.',
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
                        $Email1->subject($response->xml->messages->message->text)
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email1->viewVars(array('msg' => 'Hi BuzzyDoc, Staff - "' . $staffdoc['Staff']['staff_id'] . '" from ' . $cln['Clinic']['api_user'] . ' have some issue related to credit amount to account.<br> Error Message Details are "' . $response->xml->messages->message->text . '"',
                            'subject' => $response->xml->messages->message->text
                        ));
                        $Email1->send();
                        echo 'Subject:- "' . $response->xml->messages->message->text . '".Error Message Details are "' . $response->xml->messages->message->description . '"';
                    }
                }
            }
        }



        //5:-cron for become a adult of all clinics


        $date2 = date("Y-m-d", strtotime("-18 year"));
        //getting the list of all legacy child Patient.
        $patients = $this->User->find('all', array('conditions' => array('User.custom_date >=' => $date2, 'User.is_buzzydoc' => 0, 'User.email !=' => '')));

        foreach ($patients as $pat) {

            $clinic = $this->ClinicUser->find('first', array(
                'joins' => array(
                    array(
                        'table' => 'clinics',
                        'alias' => 'Clinics',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Clinics.id = ClinicUser.clinic_id'
                        )
                    )
                ),
                'conditions' => array(
                    'ClinicUser.user_id' => $pat['User']['id']
                ),
                'fields' => array('Clinics.*', 'ClinicUser.*')
            ));
            $date1 = $pat['User']['custom_date'];
            $diff = strtotime($date1) - strtotime($date2);
            $days = floor($diff / (60 * 60 * 24));

            //condition for adult reminder
            if ($days == 1) {
                $template_array = $this->Api->get_template(35);
                $link = str_replace('[link_url]', '<a href="' . rtrim($clinic['Clinics']['patient_url'], '/') . '">Click here</a>', $template_array['content']);
                $link1 = str_replace('[username]', $pat['User']['first_name'], $link);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                $Email->to($pat['User']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array('msg' => $link1
                ));
                $Email->send();
            }
        }

        //6:-cron for become adult buzzydoc user
        //getting the list of all buzzydoc child Patient.
        $patientsbuzzy = $this->User->find('all', array('conditions' => array('User.custom_date >=' => $date2, 'User.is_buzzydoc' => 1, 'User.email !=' => '')));
        foreach ($patientsbuzzy as $patbuz) {

            $datebuzzy = $patbuz['User']['custom_date'];
            $diffbuzz = strtotime($datebuzzy) - strtotime($date2);
            $daysbuzz = floor($diffbuzz / (60 * 60 * 24));

            //condition for adult
            if ($daysbuzz == 0) {

                $template_array = $this->Api->get_template(35);
                $link = str_replace('[username]', $patbuz['User']['first_name'], $template_array['content']);
                $link1 = str_replace('[link_url]', '<a href="' . Buzzy_Name . '">Click here</a>', $link);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array($patbuz['User']['email'] => 'BuzzyDoc'));
                $Email->to($patbuz['User']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array(
                    'msg' => $link1
                ));
                $Email->send();
            }
        }









        //7:-cron to send birthday wish mail to staff admin
        $currentday = date('d');
        $currentmonth = date('m');
        //getting the list of all staff user who have email id.
        $staff = $this->Staff->query("SELECT  `Staff`.`staff_id` , `Staff`.`staff_first_name` , `Staff`.`staff_last_name` , `Staff`.`staff_email`  FROM `staffs` AS `Staff` WHERE `Staff`.`dob` like '%" . '-' . $currentmonth . '-' . $currentday . "%' and Staff.staff_email!=''");

        foreach ($staff as $stf) {

            $template_array = $this->Api->get_template(21);
            $link = str_replace('[staff_name]', $stf['Staff']['staff_id'], $template_array['content']);
            $sub = str_replace('[staff_name]', $stf['Staff']['staff_id'], $template_array['subject']);
            $Email = new CakeEmail(MAILTYPE);
            $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
            $Email->to($stf['Staff']['staff_email']);
            $Email->subject($sub)
                    ->template('buzzydocother')
                    ->emailFormat('html');

            $Email->viewVars(array(
                'msg' => $link,
            ));
            try {
                $Email->send();
                echo "mail send to " . $stf['Staff']['staff_email'] . " for wishing happy birthday";
            } catch (Exception $e) {
                CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
            }
        }

        //8:-cron for auto mail for new reward added by staff admin
        $clinicchk = $this->Clinic->find('all');
        foreach ($clinicchk as $cck) {
            $date_chk1 = date("Y-m-d") . ' 00:00:00';
            $date_chk2 = date("Y-m-d") . ' 23:59:59';
            $rewardadmin = $this->Reward->find('all', array('conditions' => array('Reward.created BETWEEN ? AND ?' => array($date_chk1, $date_chk2), 'Reward.clinic_id' => $cck['Clinic']['id'])));

            $reward_array = array();
            $i = 0;
            foreach ($rewardadmin as $rewards) {
                $reward_array[$i]['reward_name'] = $rewards['Reward']['description'];
                $reward_array[$i]['reward_image'] = "<img src='" . $rewards['Reward']['imagepath'] . "' height='140' width='160'>";
                $i++;
            }

            if (!empty($reward_array)) {
                //checking the notification setting for Patient.
                $patients1 = $this->Notification->find('all', array(
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
                        'Notification.reward_challenges' => 1,
                        'Clinics.id' => $cck['Clinic']['id'],
                        'Users.email !=' => ''
                    ),
                    'fields' => array('Users.*', 'Clinics.*', 'clinic_users.card_number'),
                    'group' => array('clinic_users.user_id')
                ));
                foreach ($patients1 as $pat) {
                    $template_array = $this->Api->get_template(22);
                    $link = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinics']['patient_url'], '/') . '">Click Here</a>', $template_array['header_msg']);
                    $msg = str_replace('[username]', $pat['Users']['first_name'], $template_array['content']);
                    $rewardlogin = rtrim($pat['Clinics']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($pat['clinic_users']['card_number']) . "/" . base64_encode($pat['Users']['id']) . "/Unsubscribe";

                    $Email = new CakeEmail(MAILTYPE);
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                    $Email->to($pat['Users']['email']);
                    $Email->subject($template_array['subject'])
                            ->template('rewardmail')
                            ->emailFormat('html');

                    $Email->viewVars(array('msg' => $msg,
                        'reward_detail' => $reward_array,
                        'link' => $link
                    ));
                    try {
                        $Email->send();
                        echo "mail send to " . $pat['Users']['email'] . " for new reward :-" . print_r($reward_array) . '  added';
                    } catch (Exception $e) {
                        CakeLog::write('error', 'Data-Rohitortho' . print_r($pat, true) . '-result-Rohitortho' . print_r($reward_array, true));
                    }
                }
            }
        }
        //9:-daily update of stock image from s3.
        $stockImages = $this->CakeS3->list_s3_bucket('stock_images');
        if (!empty($stockImages)) {
            $this->StockImage->query('TRUNCATE TABLE stock_images;');
            $this->StockImage->saveAll($stockImages);
        }



        die('end here.');
    }

}

?>
