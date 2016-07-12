<?php

/**
 *  This file for add,edit,delete goal.
 *  Super doctor set the goals for staff user and pratice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This file for add,edit,delete goal.
 *  Super doctor set the goals for staff user and pratice.
 */
class StaffRewardProgramController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session,api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'Clinic', 'Doctor', 'ClinicLocation', 'Transaction', 'State', 'City', 'TrainingVideo', 'Refer', 'Goal', 'GoalSetting', 'GoalAchievement', 'Promotion', 'Staff','ClinicNotification');

    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $layout = 'staffLayout';
    protected $_session = null;

    protected function getSession() {
        return $this->_session;
    }

    protected function setSession($session) {
        $this->_session = $session;
        return;
    }

    /**
     * fetching default value for clinic and store it to session.
     * @return type
     */
    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        //set next free card number for default search
        $this->set('FreeCardDetails', $getfreecard);
        $trainingvideo = $this->TrainingVideo->find('all');
        //fetch all training video and set it to session to show at top of the page
        $this->Session->write('staff.trainingvideo', $trainingvideo);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }
        $sessionstaffget = $this->Session->read('staff');
        $this->setSession($sessionstaffget);
        $this->layout = $this->layout;
    }

    /**
     *  get all goal list for pratice..
     */
    public function index() {

        $sessionstaff = $this->getSession();

        if ($sessionstaff['staff_role'] != 'Doctor' && $sessionstaff['staffaccess']['AccessStaff']['staff_reward_program'] != 1) {
            $this->render('/Elements/access');
        }
        $Goals = $this->Goal->getAllRecord($sessionstaff['clinic_id']);
        $this->set('Goals', $Goals);
        $usedPromo = $this->Goal->getAllRecordWithPromo($sessionstaff['clinic_id']);
        $uspro = array();
        foreach ($usedPromo as $uspr) {
            $uspro[] = $uspr['Goal']['promotion_id'];
        }

        $getpro['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_lite' => 0);
        $getpromo = $this->Promotion->find('all', $getpro);
        $pro_array = array();
        foreach ($getpromo as $pro) {
            if (!in_array($pro['Promotion']['id'], $uspro)) {
                $pro_array[] = $pro;
            }
        }

        $goalassignment = $this->Goal->getGoalForAssignment($sessionstaff['clinic_id']);
        if (empty($goalassignment) && empty($pro_array)) {
            $this->set('CanAdd', 1);
        } else {
            $this->set('CanAdd', 0);
        }
    }

    /**
     * Create new goal for staff user.
     */
    public function add() {
        $sessionstaff = $this->getSession();
        $usedPromo = $this->Goal->getAllRecordWithPromo($sessionstaff['clinic_id']);
        $uspro = array();
        foreach ($usedPromo as $uspr) {
            $uspro[] = $uspr['Goal']['promotion_id'];
        }
        $getpro['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_lite' => 0, 'Promotion.public' => 1);
        $getpromo = $this->Promotion->find('all', $getpro);
        $pro_array = array();
        foreach ($getpromo as $pro) {
            if (!in_array($pro['Promotion']['id'], $uspro)) {
                $pro_array[] = $pro;
            }
        }
        $this->set('Promotion', $pro_array);
        $goalassignment = $this->Goal->getGoalForAssignment($sessionstaff['clinic_id']);
        if (empty($goalassignment)) {
            $this->set('CanAdd', 1);
        } else {
            $this->set('CanAdd', 0);
        }
        if ($this->request->is('post')) {
            $this->Goal->create();
            $options['conditions'] = array('Goal.goal_name' => trim($this->request->data['goal_name']), 'Goal.clinic_id' => $sessionstaff['clinic_id'], 'Goal.status' => 0);
            $ind = $this->Goal->find('first', $options);
            //condition to check duplicate goal for practice
            if (!empty($ind)) {
                $this->Session->setFlash('Goal already exists.Please use different Goal Name.', 'default', array(), 'bad');
            } else {
                $proarra['Goal']['clinic_id'] = $sessionstaff['clinic_id'];
                $proarra['Goal']['goal_name'] = $this->request->data['goal_name'];
                $proarra['Goal']['goal_type'] = $this->request->data['goal_type'];
                if (isset($this->request->data['promotion_id']) && $proarra['Goal']['goal_type'] == 2) {
                    $proarra['Goal']['promotion_id'] = $this->request->data['promotion_id'];
                } else {
                    $proarra['Goal']['promotion_id'] = 0;
                }
                $proarra['Goal']['created_on'] = date('Y-m-d H:i:s');
                if ($this->Goal->save($proarra)) {
                    $this->Session->setFlash('Goal successfully created', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to create goal', 'default', array(), 'bad');
                }
            }
        }
    }

    /**
     * @description edit goal for practice
     * @param type $id goal id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->getSession();

        $Goalvalue = $this->Goal->find('first', array('conditions' => array('Goal.id' => $id)));
        $this->set('Goal', $Goalvalue['Goal']);
        if ($Goalvalue['Goal']['promotion_id'] > 0) {
            $getpro['conditions'] = array('Promotion.id' => $Goalvalue['Goal']['promotion_id']);
            $getpromo = $this->Promotion->find('first', $getpro);
            $this->set('Promotion', $getpromo['Promotion']);
        }
        if (isset($this->request->data['Goal']['action']) && $this->request->data['Goal']['action'] == 'update') {

            $options['conditions'] = array('Goal.goal_name' => trim($this->request->data['goal_name']), 'Goal.id !=' => $this->request->data['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'Goal.status' => 0);
            $goalcheck = $this->Goal->find('first', $options);
            //condition to check duplicate accelerated reward for practice.
            if (empty($goalcheck)) {
                $goalarra['Goal'] = array('id' => $this->request->data['id'], 'goal_name' => $this->request->data['goal_name']);
                if ($this->Goal->save($goalarra)) {
                    $this->Session->setFlash('The Goal has been updated.', 'default', array(), 'good');
                    $this->set('Goal', $this->request->data);
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The Goal not updated.', 'default', array(), 'bad');
                    return $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash('Goal already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * @description delete Goal
     * @param type $id goal id
     * @return type
     */
    public function delete($id) {
        $sessionstaff = $this->getSession();
        $goalarra['Goal'] = array('id' => $id, 'clinic_id' => $sessionstaff['clinic_id'], 'status' => 1);
        if ($this->Goal->save($goalarra)) {
            $this->Session->setFlash('The goal has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('ERR:The goal not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

    /**
     *  get all goal setting list for pratice.
     */
    public function goalsettings() {
        $sessionstaff = $this->getSession();
        $Goalsettings = $this->GoalSetting->getAllSettings($sessionstaff['clinic_id']);
        $this->set('GoalSettings', $Goalsettings);
    }

    /**
     *  add new settings for pratice and staff user.
     */
    public function addsetting() {
        $sessionstaff = $this->getSession();

        $getgoal['conditions'] = array('Goal.clinic_id' => $sessionstaff['clinic_id'], 'Goal.status' => 0);
        $getgl = $this->Goal->find('all', $getgoal);
        $this->set('Goal', $getgl);
        $getstaff['conditions'] = array('Staff.clinic_id' => $sessionstaff['clinic_id']);
        $getstaffuser = $this->Staff->find('all', $getstaff);
        $this->set('Staff', $getstaffuser);
        if ($this->request->is('post')) {
            $data = $this->request->data;
            if (isset($data['staff_user']) && $data['staff_user'] != '') {
                $getst['conditions'] = array('Staff.clinic_id' => $sessionstaff['clinic_id'], 'Staff.id' => $data['staff_user']);
                $getst['fields'] = array('Staff.id');
                $getstaffdt = $this->Staff->find('all', $getst);
            } else if (isset($data['set_for_all']) && $data['set_for_all'] == 1) {
                $getst['conditions'] = array('Staff.clinic_id' => $sessionstaff['clinic_id']);
                $getst['fields'] = array('Staff.id');
                $getstaffdt = $this->Staff->find('all', $getst);
            } else {
                $getstaffdt[0]['Staff']['id'] = 0;
            }
            unset($data['staff_user']);
            unset($data['set_for_all']);
            unset($data['set_for_practice']);
            $i = $j = $k = 1;
            foreach ($data as $key => $val) {
                if ($i == $j) {
                    $keyVal = explode('_', $key);
                    $getgoalid = 'goal_name_' . $keyVal[2];
                    $target_value = 'target_value_' . $keyVal[2];

                    foreach ($getstaffdt as $staffid) {
                        $checksetting['conditions'] = array('GoalSetting.clinic_id' => $sessionstaff['clinic_id'], 'GoalSetting.staff_id' => $staffid['Staff']['id'], 'GoalSetting.goal_id' => $data[$getgoalid]);
                        $checksetting['fields'] = array('GoalSetting.*');
                        $checkset = $this->GoalSetting->find('first', $checksetting);
                        if (empty($checkset)) {
                            $goalsettingArray = array('clinic_id' => $sessionstaff['clinic_id'], 'staff_id' => $staffid['Staff']['id'], 'goal_id' => $data[$getgoalid], 'target_value' => $data[$target_value], 'goal_start_date' => date('Y-m-d H:i:s'), 'status' => 1);
                            $this->GoalSetting->create();
                            $this->GoalSetting->save($goalsettingArray);
                        } else {
                            $goalsettingArray = array('id' => $checkset['GoalSetting']['id'], 'target_value' => $data[$target_value], 'status' => 1);
                            $this->GoalSetting->save($goalsettingArray);
                        }
                    }
                    unset($data[$getgoalid]);
                    unset($data[$target_value]);
                    $j +=2;
                    $k++;
                }
                $i++;
            }
            $this->Session->setFlash('Goal Set successfully', 'default', array(), 'good');
            $this->redirect(array('action' => 'goalsettings'));
        }
    }
    /**
     * Check goal setting is already set for any staff user or clinic.
     */
    public function checkgoalsetting() {
        $this->layout = "";
        $sessionstaff = $this->getSession();
        if (!empty($_POST['goal_id'])) {
            if ($_POST['for_all'] == 1) {
                $getgoal['conditions'] = array('GoalSetting.clinic_id' => $sessionstaff['clinic_id'], 'GoalSetting.staff_id >' => 0, 'GoalSetting.goal_id' => $_POST['goal_id'], 'GoalSetting.status' => 1);
                $getgl = $this->GoalSetting->find('first', $getgoal);
                if (!empty($getgl)) {
                    echo 1;
                }
            } else if ($_POST['staff_user'] != '') {
                $getst['conditions'] = array('Staff.clinic_id' => $sessionstaff['clinic_id'], 'Staff.id' => $_POST['staff_user']);
                $getstaff = $this->Staff->find('first', $getst);
                $getgoal['conditions'] = array('GoalSetting.clinic_id' => $sessionstaff['clinic_id'], 'GoalSetting.staff_id' => $getstaff['Staff']['id'], 'GoalSetting.goal_id' => $_POST['goal_id'], 'GoalSetting.status' => 1);
                $getgl = $this->GoalSetting->find('first', $getgoal);
                if (!empty($getgl)) {
                    echo 2;
                }
            } else {
                $getgoal['conditions'] = array('GoalSetting.clinic_id' => $sessionstaff['clinic_id'], 'GoalSetting.staff_id' => 0, 'GoalSetting.goal_id' => $_POST['goal_id'], 'GoalSetting.status' => 1);
                $getgl = $this->GoalSetting->find('first', $getgoal);
                if (!empty($getgl)) {
                    echo 3;
                }
            }
        } else {
            echo 4;
        }

        exit;
    }
    /**
     * delete goal setting.
     * @param type $id
     * @return type
     */
    public function deletesetting($id) {
        $sessionstaff = $this->getSession();
        $goalarra['GoalSetting'] = array('id' => $id, 'clinic_id' => $sessionstaff['clinic_id'], 'status' => 0);
        if ($this->GoalSetting->save($goalarra)) {
            $this->Session->setFlash('The goal setting has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'goalsettings'));
        } else {
            $this->Session->setFlash('ERR:The goal setting not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'goalsettings'));
        }
    }
    /**
     * update target value for goal setting.
     */
    public function updatesetting() {
        $this->layout = "";
        $sessionstaff = $this->getSession();
        $goalsettingArray = array('id' => $_POST['id'], 'target_value' => $_POST['target_value'], 'status' => 1);
        $this->GoalSetting->save($goalsettingArray);
        echo 1;
        exit;
    }

    /**
     * getting the performance report for staff user and pratice.
     */
    public function performancereport() {

        $sessionstaff = $this->getSession();
        $getreport = $this->GoalAchievement->getAllreport($sessionstaff['clinic_id']);
        $this->set('Reports', $getreport);
    }
    /**
     * getting the currunt performance report for staff user and clinic.
     */
    public function currentweekreport() {
        $sessionstaff = $this->getSession();
        $reports = $this->Api->getClinicReportingData(0, $sessionstaff['clinic_id'], null, 1);
        $goal_achieve_array = array();
        foreach ($reports as $report) {
            $start_date = explode(' - ', $report['duration']);
            $year = explode('-', $start_date[0]);
            $count = 0;
            foreach ($report['return'] as $usrcnt) {
                $count = $count + $usrcnt[0]['user_count'];
            }
            $date = new DateTime($start_date[1]);
            $week = $date->format("W");

            if ($report['details_for']['Staff']['staff_id'] == '') {
                $goal_for = $report['details_for']['Clinic']['api_user'] . ' (Practice)';
            } else {
                $goal_for = $report['details_for']['Staff']['staff_id'] . ' (Staff User)';
            }

            $goal_achieve_array[] = array(
                'goal_name' => $report['details_for']['Goal']['goal_name'],
                'goal_for' => $goal_for,
                'target_value' => $report['details_for']['GoalSetting']['target_value'],
                'actual_value' => $count,
                'week' => $year[0] . ' (' . $week . ')',
                'date_range' => $report['duration']
            );
        }
        $this->set('Reports', $goal_achieve_array);
    }
    /**
     * export performance report history.
     */
    public function exportReport() {
        $sessionstaff = $this->getSession();
        $getreport = $this->GoalAchievement->getAllreport($sessionstaff['clinic_id']);
        $output = ob_clean();
        $csv_terminated = "\n";
        $csv_separator = ",";
        $csv_enclosed = '"';
        $csv_escaped = "\\";
        $schema_insert = "";
        $out = '';
        $field_name = array();
        $field_id = array();

        $field_name[] = "Goal Name";
        $field_name[] = "Goal For";
        $field_name[] = "Target (Weekly)";
        $field_name[] = "Target Achieved";
        $field_name[] = "Week Number";
        $field_name[] = "Date Range";
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';


        for ($a = 0; $a < count($field_name); $a++) {
            $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes($field_name[$a])) . $csv_enclosed;
            $schema_insert .= $l;
            $schema_insert .= $csv_separator;
        }
        $out .= $schema_insert;
        $out .= $csv_terminated;

        foreach ($getreport as $val) {
            $schema_insert = '';
            $answer = '';
            $customerName = $val['Goal']['goal_name'];
            if ($val['Staff']['staff_id'] != '') {
                $card_number = $val['Staff']['staff_id'] . " (Staff User)";
            } else {
                $card_number = $val['Clinic']['api_user'] . " (Practice)";
            }

            $dob = $val['GoalAchievement']['target_value'];
            $email = $val['GoalAchievement']['actual_value'];
            $points = $val['GoalAchievement']['year'] . ' (' . $val['GoalAchievement']['week_number'] . ')';
            $date = strtotime("+6 day", strtotime($val['GoalAchievement']['goal_start_date']));
            $start_date = explode(' ', $val['GoalAchievement']['goal_start_date']);
            $daterange = $start_date[0] . ' - ' . date('Y-m-d', $date);
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $customerName) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $card_number) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $dob) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $email) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $points) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $daterange) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_separator;
            $out .= $schema_insert;
            $out .= $csv_terminated;
        }
        $output = $out;
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=PerformanceReport" . date("Y-m-d-H-i-s") . ".csv;");
        header("Content-Transfer-Encoding: binary");
        echo $output;
        die;
    }
    /**
     * export current performance report for staff user and clinic. 
     */
    public function exportCurrentReport() {
        $sessionstaff = $this->getSession();
        $reports = $this->Api->getClinicReportingData(0, $sessionstaff['clinic_id'], null, 1);
        $getreport = array();
        foreach ($reports as $report) {
            $start_date = explode(' - ', $report['duration']);
            $year = explode('-', $start_date[0]);
            $count = 0;
            foreach ($report['return'] as $usrcnt) {
                $count = $count + $usrcnt[0]['user_count'];
            }
            $date = new DateTime($start_date[1]);
            $week = $date->format("W");

            if ($report['details_for']['Staff']['staff_id'] == '') {
                $goal_for = $report['details_for']['Clinic']['api_user'] . ' (Practice)';
            } else {
                $goal_for = $report['details_for']['Staff']['staff_id'] . ' (Staff User)';
            }
            $daterange = explode(' - ', $report['duration']);
            $start_date = explode(' ', $daterange[0]);
            $end_date = explode(' ', $daterange[1]);
            $getreport[] = array(
                'goal_name' => $report['details_for']['Goal']['goal_name'],
                'goal_for' => $goal_for,
                'target_value' => $report['details_for']['GoalSetting']['target_value'],
                'actual_value' => $count,
                'week' => $year[0] . ' (' . $week . ')',
                'date_range' => $start_date[0] . ' - ' . $end_date[0]
            );
        }
        $output = ob_clean();
        $csv_terminated = "\n";
        $csv_separator = ",";
        $csv_enclosed = '"';
        $csv_escaped = "\\";
        $schema_insert = "";
        $out = '';
        $field_name = array();
        $field_id = array();

        $field_name[] = "Goal Name";
        $field_name[] = "Goal For";
        $field_name[] = "Target (Weekly)";
        $field_name[] = "Target Achieved";
        $field_name[] = "Week Number";
        $field_name[] = "Date Range";
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';
        $field_id[] = '';


        for ($a = 0; $a < count($field_name); $a++) {
            $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes($field_name[$a])) . $csv_enclosed;
            $schema_insert .= $l;
            $schema_insert .= $csv_separator;
        }
        $out .= $schema_insert;
        $out .= $csv_terminated;

        foreach ($getreport as $val) {
            $schema_insert = '';
            $answer = '';
            $customerName = $val['goal_name'];
            $card_number = $val['goal_for'];

            $dob = $val['target_value'];
            $email = $val['actual_value'];
            $points = $val['week'];
            ;
            $daterange = $val['date_range'];
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $customerName) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $card_number) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $dob) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $email) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $points) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $daterange) . $csv_enclosed;
            $schema_insert .= $csv_separator;
            $schema_insert .= $csv_separator;
            $out .= $schema_insert;
            $out .= $csv_terminated;
        }
        $output = $out;
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=CurrentWeekPerformanceReport" . date("Y-m-d-H-i-s") . ".csv;");
        header("Content-Transfer-Encoding: binary");
        echo $output;
        die;
    }
    /**
     * check goal setting is already exist for all staff user.
     */
    public function checkforallstaff() {
        $this->layout = "";
        $sessionstaff = $this->getSession();
        $s=0;
        if(isset($_POST['set_for_all']) && $_POST['set_for_all']==1){
        $getst['conditions'] = array('Staff.clinic_id' => $sessionstaff['clinic_id']);
        $getst['fields'] = array('Staff.id');
        $getstaffdt = $this->Staff->find('all', $getst);

        unset($_POST['_method']);
        unset($_POST['set_for_all']);
 
        $i = $j = $k = 1;
        
        foreach ($_POST as $key => $val) {
            if ($i == $j) {
                $keyVal = explode('_', $key);
                $getgoalid = 'goal_name_' . $keyVal[2];
                $target_value = 'target_value_' . $keyVal[2];
                foreach ($getstaffdt as $staffid) {
                    $checksetting['conditions'] = array('GoalSetting.clinic_id' => $sessionstaff['clinic_id'], 'GoalSetting.staff_id' => $staffid['Staff']['id'], 'GoalSetting.goal_id' => $_POST[$getgoalid]);
                    $checksetting['fields'] = array('GoalSetting.*');
                    $checkset = $this->GoalSetting->find('first', $checksetting);
                    if(!empty($checkset)){
                        $s++;
                    }
                }
                unset($_POST[$getgoalid]);
                unset($_POST[$target_value]);
                $j +=2;
                $k++;
            }
            $i++;
        }
        }
        echo $s;
        exit;
    }

}

?>
