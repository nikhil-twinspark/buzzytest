<?php

/**
 *  This file for cron to send weekly basic report to clinic staff.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller for cron to send weekly basic report to clinic staff.
 */
class CronForBasicReportingController extends AppController {

    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Staff', 'Clinic', 'RateReview');

    /**
     * Cron for run basic weekly reporting for all clinic staff.
     */
    public function index() {

        Configure::write('debug', 1);
        //cron for review mail.
        $Clinic = $this->Clinic->find('all');
        foreach ($Clinic as $cl) {
            $date_chk1 = date('Y-m-d', strtotime('-7 days')) . ' 00:00:00';
            $date_chk2 = date("Y-m-d") . ' 00:00:00';
            $options2['conditions'] = array('Staff.clinic_id' => $cl['Clinic']['id'], 'Staff.review_mail' => 1);
            $staff = $this->Staff->find('all', $options2);
            foreach ($staff as $stf) {
                //list of added review.
                $query = "SELECT `RateReview`.*, `Staff`.`id`, `Staff`.`staff_id`, `clinics`.`api_user`, `User`.`first_name`, `User`.`last_name`, `ClinicUser`.`card_number` FROM `rate_reviews` AS `RateReview` left JOIN `staffs` AS `Staff` ON (`Staff`.`id` = `RateReview`.`staff_id`) INNER JOIN `users` AS `User` ON (`User`.`id` = `RateReview`.`user_id`) INNER JOIN `clinic_users` AS `ClinicUser` ON (`ClinicUser`.`user_id` = `User`.`id`) INNER JOIN `clinics` AS `clinics` ON (`clinics`.`id` = `RateReview`.`clinic_id`) WHERE ((`RateReview`.`notify_staff` = '1' AND `RateReview`.`google_share` = '0') OR (`RateReview`.`yahoo_notify` = '1' AND `RateReview`.`yahoo_share` = '0') OR (`RateReview`.`yelp_notify` = '1' AND `RateReview`.`yelp_share` = '0') OR (`RateReview`.`healthgrades_notify` = '1' AND `RateReview`.`healthgrades_share` = '0')) AND ";
                $query .= "`ClinicUser`.`clinic_id` = " . $stf['Staff']['clinic_id'] . " AND `RateReview`.`rate` > 0 AND `RateReview`.`clinic_id` = " . $stf['Staff']['clinic_id'] . " AND `RateReview`.`created_on` >= '" . $date_chk1 . "' AND `RateReview`.`created_on` <= '" . $date_chk2 . "' ";
                if ($stf['Staff']['staff_role'] == 'Manager' || $stf['Staff']['staff_role'] == 'M') {
                    $query .= "AND `RateReview`.`staff_id` = " . $stf['Staff']['id'] . "";
                }
                $ratereview = $this->RateReview->query($query);
                if (!empty($ratereview)) {
                    $template_array = $this->Api->get_template(45);
                    $link = str_replace('[staff_name]', $stf['Staff']['staff_id'], $template_array['content']);
                    $link1 = str_replace('[click_here]', '<a href="' . rtrim($cl['Clinic']['staff_url'], '/') . '/staff/reviewreport/' . base64_encode($stf['Staff']['id']) . '">Click Here</a>', $link);
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
                        echo "mail send to " . $stf['Staff']['staff_email'] . " for weekly review reporting";
                    } catch (Exception $e) {
                        CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
                    }
                }
            }
            $loc['Clinic'] = array('id' => $cl['Clinic']['id'], 'lead_log' => $date_chk1);
            $this->Clinic->save($loc);
        }
        die;
    }

}

?>
