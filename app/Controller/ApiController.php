<?php

/**
 * This is api file.
 * Auto assign new card to patient when clinic upgrade and staff tag and given point to any free card.
 * Getting the details of buzzydoc patient.
 * Getting the Pratice points for patient if belong to multiple pratice.
 * Getting the list of unique transaction from clinic.
 * Getting the list of saved doctor and liked pratice for Patient.
 * Getting the list of all activty for patient and buzz list of buzzydoc.
 * Getting overall clinic list with details.
 * Getting overall doctor list with details.
 * Search doctor and clinic by type,specility,most point,most like and co-ordinate.
 * Patient sign-in ,signup and forgot password.
 * Activity save,like and rate by Patient to doctor and pratice.
 * Place an order by Patient from tango and amazon.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for Auto assign new card to patient when clinic upgrade and staff tag and given point to any free card.
 * Getting the details of buzzydoc patient.
 * Getting the Pratice points for patient if belong to multiple pratice.
 * Getting the list of unique transaction from clinic.
 * Getting the list of saved doctor and liked pratice for Patient.
 * Getting the list of all activty for patient and buzz list of buzzydoc.
 * Getting overall clinic list with details.
 * Getting overall doctor list with details.
 * Search doctor and clinic by type,specility,most point,most like and co-ordinate.
 * Patient sign-in ,signup and forgot password.
 * Activity save,like and rate by Patient to doctor and pratice.
 * Place an order by Patient from tango and amazon.
 */
class ApiController extends AppController {

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinic', 'ClinicUser', 'ProfileFieldUser', 'ProfileField', 'CharacteristicInsuranceLike', 'CharacteristicInsurance', 'Doctor', 'ClinicLocation', 'Transaction', 'RateReview', 'User', 'IndustryType', 'SaveLike', 'Appointment', 'Promotion', 'Refpromotion', 'Refer', 'Staff', 'Badge', 'UsersBadge', 'TangoAccount', 'CharacteristicInsurance', 'State', 'Invoice', 'PaymentDetail', 'FacebookLike', 'GlobalRedeem', 'CardNumber', 'UnregTransaction', 'ProductService', 'FailedPayment', 'AccessStaff', 'Goal', 'GoalAchievement', 'transaction', 'ClinicNotification');

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form');

    /**
     * We use the session, api and CakeS3 component for this controller.
     * @var type 
     */
    public $components = array('RequestHandler', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));

    /**
     * This function for auto assign new card number to Patient when clinic upgrad from legecy to buzzydoc clinic and that card number is already tag by staff with some points.
     * @param type $id
     */
    public function autoassign($id) {
        $alltagcards = $this->CardNumber->find('all', array('conditions' => array('CardNumber.clinic_id' => $id, 'CardNumber.text !=' => '', 'CardNumber.status' => 1)));
        foreach ($alltagcards as $tagcard) {
            $details = json_decode($tagcard['CardNumber']['text']);
            if ($details->email != '') {
                //condition to check card number already assigned to any other email id.
                if (isset($details->parents_email) && $details->parents_email != '') {
                    $users_field_check = $this->User->find('first', array(
                        'joins' => array(
                            array(
                                'table' => 'clinic_users',
                                'alias' => 'clinic_users',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'clinic_users.user_id = User.id'
                                )
                            )
                        ),
                        'conditions' => array(
                            'clinic_users.clinic_id' => $id,
                            'User.email' => $details->email,
                            'User.parents_email' => $details->parents_email
                        ),
                        'fields' => array('User.id')
                    ));
                } else {
                    $date13age = date("Y-m-d", strtotime("-18 year"));
                    $users_field_check = $this->User->find('first', array(
                        'joins' => array(
                            array(
                                'table' => 'clinic_users',
                                'alias' => 'clinic_users',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'clinic_users.user_id = User.id'
                                )
                            )
                        ),
                        'conditions' => array(
                            'clinic_users.clinic_id' => $id,
                            'User.email' => $details->email,
                            'User.custom_date <=' => $date13age
                        ),
                        'fields' => array('User.id')
                    ));
                }

                if (empty($users_field_check)) {
                    if (isset($details->parents_email) && $details->parents_email != '') {
                        $users_field = $this->User->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = User.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'clinic_users.clinic_id !=' => $id,
                                'User.email' => $details->email,
                                'User.parents_email' => $details->parents_email
                            ),
                            'fields' => array('User.email', 'User.id', 'clinic_users.clinic_id')
                        ));
                    } else {
                        $date13age = date("Y-m-d", strtotime("-18 year"));
                        $users_field = $this->User->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = User.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'clinic_users.clinic_id !=' => $id,
                                'User.email' => $details->email,
                                'User.custom_date <=' => $date13age
                            ),
                            'fields' => array('User.email', 'User.id', 'clinic_users.clinic_id')
                        ));
                    }
                    if (count($users_field) > 0) {
                        $linkid = $users_field['User']['id'];
                    } else {
                        $linkid = 0;
                    }
                } else {
                    $linkid = 0;
                }
                //condition to check email id which is taged in card number belong to any other clinic if yes then link that card to other clinic card.
                if ($linkid > 0) {

                    $options['conditions'] = array('User.id' => $linkid);
                    $user1 = $this->User->find('first', $options);
                    if (!empty($user1)) {
                        $optionscl['conditions'] = array('ClinicUser.user_id' => $user1['User']['id'], 'ClinicUser.card_number' => $tagcard['CardNumber']['card_number']);
                        $userclinic = $this->ClinicUser->find('first', $optionscl);
                        if (empty($userclinic)) {
                            $alltrans = $this->UnregTransaction->find('all', array(
                                'conditions' => array(
                                    'UnregTransaction.user_id' => 0,
                                    'UnregTransaction.card_number' => $tagcard['CardNumber']['card_number'],
                                    'UnregTransaction.clinic_id' => $id
                                )
                            ));

                            foreach ($alltrans as $newtran) {
                                $datatrans['user_id'] = $user1['User']['id'];
                                $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                                $datatrans['card_number'] = $tagcard['CardNumber']['card_number'];
                                $datatrans['first_name'] = $user1['User']['first_name'];
                                $datatrans['last_name'] = $user1['User']['last_name'];
                                $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                                $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                                $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                                $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                                $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                                $datatrans['date'] = $newtran['UnregTransaction']['date'];
                                $datatrans['status'] = $newtran['UnregTransaction']['status'];
                                $datatrans['is_buzzydoc'] = 1;
                                $this->Transaction->create();
                                $this->Transaction->save($datatrans);
                                $this->UnregTransaction->deleteAll(array('UnregTransaction.id' => $newtran['UnregTransaction']['id'], false));
                            }

                            $allpoints = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $user1['User']['id'],
                                    'Transaction.clinic_id' => $id,
                                    'Transaction.is_buzzydoc' => 1
                                ),
                                'fields' => array(
                                    'SUM(Transaction.amount) AS points'
                                ),
                                'group' => array(
                                    'Transaction.card_number'
                            )));

                            if ($allpoints[0]['points'] > 0) {
                                $newpoints = $allpoints[0]['points'] + $user1['points'];
                            } else {
                                $newpoints = 0 + $allpoints[0]['points'];
                            }
                            $Patients_array['ClinicUser'] = array(
                                'clinic_id' => $id,
                                'user_id' => $user1['User']['id'],
                                'card_number' => $tagcard['CardNumber']['card_number'],
                                'local_points' => 0
                            );
                            $this->ClinicUser->create();
                            $this->ClinicUser->save($Patients_array);
                            $query = 'update card_numbers set status=2,text="" where clinic_id=' . $id . ' and card_number=' . $tagcard['CardNumber']['card_number'];
                            $unrescard = $this->CardNumber->query($query);
                            $queryuser = 'update users set points=' . $newpoints . ' where id=' . $user1['User']['id'];
                            $usersave = $this->User->query($queryuser);
                            $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $id)));
                            if ($user1['User']['email'] != '') {
                                $template_array = $this->Api->get_template(2);
                                $link = str_replace('[username]', $user1['User']['first_name'], $template_array['content']);
                                $link1 = str_replace('[link_url]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">' . rtrim($clinic['Clinic']['patient_url'], '/') . '</a>', $link);
                                $link2 = str_replace('[card_number]', $tagcard['CardNumber']['card_number'], $link1);
                                $link3 = str_replace('[password]', $user1['User']['password'], $link2);
                                $Email = new CakeEmail(MAILTYPE);
                                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                                $Email->to($user1['User']['email']);
                                $Email->subject($template_array['subject'])
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email->viewVars(array('msg' => $link3
                                ));
                                $Email->send();
                            }
                        }
                    }
                } else {

                    if (isset($details->parents_email) && $details->parents_email != '') {
                        $options['conditions'] = array('User.email' => $details->email, 'User.parents_email' => $details->parents_email);
                        $user1 = $this->User->find('first', $options);
                        if (empty($user1)) {
                            $options['conditions'] = array('User.parents_email' => $details->parents_email);
                            $user1 = $this->User->find('first', $options);
                        }
                    } else {
                        $date13age = date("Y-m-d", strtotime("-18 year"));
                        $options['conditions'] = array('User.email' => $details->email, 'User.custom_date <=' => $date13age);
                        $user1 = $this->User->find('first', $options);
                    }

                    if (!empty($user1)) {
                        $optionscl['conditions'] = array('ClinicUser.user_id' => $user1['User']['id']);

                        $userclinic = $this->ClinicUser->find('first', $optionscl);

                        if (empty($userclinic)) {
                            $alltrans = $this->UnregTransaction->find('all', array(
                                'conditions' => array(
                                    'UnregTransaction.user_id' => 0,
                                    'UnregTransaction.card_number' => $tagcard['CardNumber']['card_number'],
                                    'UnregTransaction.clinic_id' => $id
                                )
                            ));

                            foreach ($alltrans as $newtran) {
                                $datatrans['user_id'] = $user1['User']['id'];
                                $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                                $datatrans['card_number'] = $tagcard['CardNumber']['card_number'];
                                $datatrans['first_name'] = $user1['User']['first_name'];
                                $datatrans['last_name'] = $user1['User']['last_name'];
                                $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                                $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                                $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                                $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                                $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                                $datatrans['date'] = $newtran['UnregTransaction']['date'];
                                $datatrans['status'] = $newtran['UnregTransaction']['status'];
                                $datatrans['is_buzzydoc'] = 1;
                                $this->Transaction->create();
                                $this->Transaction->save($datatrans);
                                $this->UnregTransaction->deleteAll(array('UnregTransaction.id' => $newtran['UnregTransaction']['id'], false));
                            }

                            $allpoints = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $user1['User']['id'],
                                    'Transaction.clinic_id' => $id,
                                    'Transaction.is_buzzydoc' => 1
                                ),
                                'fields' => array(
                                    'SUM(Transaction.amount) AS points'
                                ),
                                'group' => array(
                                    'Transaction.card_number'
                            )));

                            if ($allpoints[0]['points'] > 0) {
                                $newpoints = $allpoints[0]['points'] + $user1['points'];
                            } else {
                                $newpoints = 0 + $allpoints[0]['points'] + $user1['points'];
                            }
                            $Patients_array['ClinicUser'] = array(
                                'clinic_id' => $id,
                                'user_id' => $user1['User']['id'],
                                'card_number' => $tagcard['CardNumber']['card_number'],
                                'local_points' => 0
                            );
                            $this->ClinicUser->create();
                            $this->ClinicUser->save($Patients_array);
                            $query = 'update card_numbers set status=2,text="" where clinic_id=' . $id . ' and card_number=' . $tagcard['CardNumber']['card_number'];
                            $unrescard = $this->CardNumber->query($query);
                            $queryuser = 'update users set is_buzzydoc=0,points=' . $newpoints . ' where id=' . $user1['User']['id'];
                            $usersave = $this->User->query($queryuser);
                            $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $id)));
                            if ($user1['User']['email'] != '') {
                                $template_array = $this->Api->get_template(3);
                                $link = str_replace('[username]', $user1['User']['first_name'], $template_array['content']);
                                $link1 = str_replace('[click_here]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">Click Here</a>', $link);
                                $link2 = str_replace('[card_number]', $tagcard['CardNumber']['card_number'], $link1);
                                $link3 = str_replace('[password]', $user1['User']['password'], $link2);
                                $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
                                $Email = new CakeEmail(MAILTYPE);
                                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                                $Email->to($user1['User']['email']);
                                $Email->subject($sub)
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email->viewVars(array('msg' => $link4
                                ));
                                $Email->send();
                            }
                        }
                    } else {
                        $optionscl['conditions'] = array('ClinicUser.card_number' => $tagcard['CardNumber']['card_number'], 'ClinicUser.clinic_id' => $id);

                        $userclinic = $this->ClinicUser->find('first', $optionscl);

                        if (empty($userclinic)) {
                            if (isset($details->parents_email) && $details->parents_email != '') {
                                $email = $details->email;
                                $username = $details->parents_email;
                            } else {
                                $email = $details->email;
                                $username = '';
                            }
                            if (isset($details->custom_date) && $details->custom_date != '') {
                                $dob = $details->custom_date;
                            } else {
                                $dob = '0000-00-00';
                            }
                            $password = mt_rand(100000, 999999);
                            $newPatients_array['User'] = array('email' => $email, 'parents_email' => $username, 'custom_date' => $dob, 'first_name' => $details->first_name, 'last_name' => $details->last_name, 'enrollment_stamp' => date('Y-m-d H:i:s'), 'customer_password' => md5($password), 'password' => $password, 'status' => 1, 'is_verified' => 1, 'is_buzzydoc' => 0, 'blocked' => 0, 'points' => 0);
                            $this->User->create();
                            $this->User->save($newPatients_array);
                            $user_id = $this->User->getLastInsertId();
                            $optionsusdet['conditions'] = array('User.id' => $user_id);
                            $userdetail = $this->User->find('first', $optionsusdet);
                            $Patients_array['ClinicUser'] = array(
                                'clinic_id' => $id,
                                'user_id' => $user_id,
                                'card_number' => $tagcard['CardNumber']['card_number'],
                                'local_points' => 0
                            );
                            $this->ClinicUser->create();
                            $this->ClinicUser->save($Patients_array);
                            $query = 'update card_numbers set status=2,text="" where clinic_id=' . $id . ' and card_number=' . $tagcard['CardNumber']['card_number'];
                            $unrescard = $this->CardNumber->query($query);
                            $alltrans = $this->UnregTransaction->find('all', array(
                                'conditions' => array(
                                    'UnregTransaction.user_id' => 0,
                                    'UnregTransaction.card_number' => $tagcard['CardNumber']['card_number'],
                                    'UnregTransaction.clinic_id' => $id
                                )
                            ));
                            foreach ($alltrans as $newtran) {
                                $datatrans['user_id'] = $user_id;
                                $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                                $datatrans['card_number'] = $tagcard['CardNumber']['card_number'];
                                $datatrans['first_name'] = $details->first_name;
                                $datatrans['last_name'] = $details->last_name;
                                $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                                $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                                $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                                $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                                $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                                $datatrans['date'] = $newtran['UnregTransaction']['date'];
                                $datatrans['status'] = $newtran['UnregTransaction']['status'];
                                $datatrans['is_buzzydoc'] = 1;
                                $this->Transaction->create();
                                $this->Transaction->save($datatrans);
                                $this->UnregTransaction->deleteAll(array('UnregTransaction.id' => $newtran['UnregTransaction']['id'], false));
                            }

                            $allpoints = $this->Transaction->find('first', array(
                                'conditions' => array(
                                    'Transaction.user_id' => $user_id,
                                    'Transaction.clinic_id' => $id,
                                    'Transaction.is_buzzydoc' => 1
                                ),
                                'fields' => array(
                                    'SUM(Transaction.amount) AS points'
                                ),
                                'group' => array(
                                    'Transaction.card_number'
                            )));

                            if ($allpoints[0]['points'] > 0) {
                                $newpoints = $allpoints[0]['points'];
                            } else {
                                $newpoints = 0;
                            }
                            $queryuser = 'update users set points=' . $newpoints . ' where id=' . $user_id;
                            $usersave = $this->User->query($queryuser);
                            $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $id)));
                            if ($userdetail['User']['email'] != '') {
                                $template_array = $this->Api->get_template(3);
                                $link = str_replace('[username]', $userdetail['User']['first_name'], $template_array['content']);
                                $link1 = str_replace('[click_here]', '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . '">Click Here</a>', $link);
                                $link2 = str_replace('[card_number]', $tagcard['CardNumber']['card_number'], $link1);
                                $link3 = str_replace('[password]', $userdetail['User']['password'], $link2);
                                $sub = str_replace('[clinic_name]', $clinic['Clinic']['api_user'], $template_array['subject']);
                                $Email = new CakeEmail(MAILTYPE);
                                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                                $Email->to($userdetail['User']['email']);
                                $Email->subject($sub)
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email->viewVars(array('msg' => $link3
                                ));
                                $Email->send();
                            }
                        }
                    }
                }
            }
        }
        $response = array('success' => false, 'data' => 'true');

        $this->set(array(
            'assign' => $response,
            '_serialize' => array('assign')
        ));
    }

    /**
     * 
     * @param type $idGetting the all details for Patient like (Total Points from all clinics,likes,saved doctor,profile field details,all activity,total checkins,badge list etc.)
     */
    public function userdetail($id) {
        $options['fields'] = array('User.id', 'User.custom_date', 'User.email', 'User.parents_email', 'User.first_name', 'User.last_name', 'User.profile_img_url', 'User.points', 'User.customer_password', 'User.password');
        $options['conditions'] = array('User.id' => $id);
        $user = $this->User->find('first', $options);

        if (!empty($user)) {
            $profilefield = $this->ProfileFieldUser->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'profile_fields',
                        'alias' => 'ProfileField',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ProfileField.id = ProfileFieldUser.profilefield_id'
                        )
                    )),
                'conditions' => array(
                    'ProfileFieldUser.user_id' => $id,
                    'ProfileFieldUser.clinic_id' => 0
                ),
                'fields' => array('ProfileFieldUser.*,ProfileField.*')
            ));
            //getting the detail of profile field.
            if (!empty($profilefield)) {

                $user['Profilefield'] = $profilefield;
            } else {
                $user['Profilefield'] = array();
            }
            $totalpoints = $user['User']['points'];
            $options8['conditions'] = array('ClinicUser.user_id' => $id);
            $fromclinic = $this->ClinicUser->find('all', $options8);
            foreach ($fromclinic as $allc) {
                $totalpoints = $totalpoints + $allc['ClinicUser']['local_points'];
            }


            $optionsPnt['conditions'] = array('Transaction.user_id' => $id, 'Transaction.activity_type' => 'N', 'Transaction.amount >' => 0);
            $optionsPnt['group'] = array('Transaction.clinic_id');
            $fromclpnt = $this->Transaction->find('all', $optionsPnt);
            //getting the list of pratice who given the point to patient.
            if (!empty($fromclpnt)) {
                $user['Save'] = count($fromclpnt);
            } else {
                $user['Save'] = 0;
            }
            $uniquecheckin = $this->Transaction->query("select * from transactions as rr where rr.user_id=" . $id . " and activity_type='N' and amount>0  group by date(rr.date),rr.clinic_id");

            if (!empty($uniquecheckin)) {
                $user['Checkin'] = count($uniquecheckin);
            } else {

                $user['Checkin'] = 0;
            }
            $options2['conditions'] = array('SaveLike.user_id' => $id, 'SaveLike.doctor_id' => 0, 'SaveLike.clinic_id !=' => 0);
            $options2['fields'] = array('count(SaveLike.user_id) as likeclinic');
            $Like = $this->SaveLike->find('first', $options2);
            if (isset($Like[0]['likeclinic'])) {
                $user['Like'] = $Like[0]['likeclinic'];
            } else {
                $user['Like'] = 0;
            }
            $optionssl['conditions'] = array('SaveLike.user_id' => $id);
            $Likesave = $this->SaveLike->find('all', $optionssl);
            $saveddoc = array();
            $likedclinic = array();
            //getting saved doctor list.
            if (!empty($Likesave)) {
                foreach ($Likesave as $ls) {
                    if ($ls['SaveLike']['doctor_id'] != 0) {
                        $options6['conditions'] = array('Doctor.id' => $ls['SaveLike']['doctor_id']);
                        $Doctor = $this->Doctor->find('first', $options6);
                        if (!empty($Doctor)) {
                            $saveddoc[] = $Doctor;
                        }
                    }
                    if ($ls['SaveLike']['clinic_id'] != 0) {
                        $options7['conditions'] = array('Clinic.id' => $ls['SaveLike']['clinic_id']);
                        $options7['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.industry_type', 'Clinic.staff_url', 'Clinic.patient_url', 'Clinic.is_buzzydoc', 'Clinic.is_lite');
                        $Clinic = $this->Clinic->find('first', $options7);
                        $likedclinic[] = $Clinic;
                    }
                }
            }
            $user['Saveddoc'] = $saveddoc;
            $user['Likeclinic'] = $likedclinic;
            //getting all badge doctor have.
            $Badge = $this->UsersBadge->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'badges',
                        'alias' => 'Badge',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Badge.id = UsersBadge.badge_id'
                        )
                    )),
                'conditions' => array(
                    'UsersBadge.user_id' => $id,
                    'Badge.clinic_id' => 0
                ),
                'fields' => array('UsersBadge.*'),
                'group' => array('UsersBadge.badge_id')
            ));
            if (!empty($Badge)) {
                $user['Badge'] = $Badge;
            } else {
                $user['Badge'] = array();
            }
            $optionsfblike['conditions'] = array('FacebookLike.user_id' => $id);
            $fblikes = $this->FacebookLike->find('all', $optionsfblike);
            if (!empty($fblikes)) {
                $user['Fblikes'] = $fblikes;
            } else {
                $user['Fblikes'] = array();
            }
            $options4['conditions'] = array('Transaction.user_id' => $id, 'Transaction.activity_type' => 'Y');
            $options4['order'] = array('Transaction.date' => 'desc');
            $totalredeem = $this->Transaction->find('all', $options4);

            $user['OtherClinicPoint'] = $clinicuser;
            $user['totalpoints'] = $totalpoints;
            $user['totalPointsShort'] = $totalpoints;
            //get cheractristic/insurance liked by patient.
            $Characteristiclike = $this->CharacteristicInsuranceLike->query("select * from characteristic_insurance_likes where user_id=" . $id);
            if (!empty($Characteristiclike)) {
                $user['CharacteristicLike'] = $Characteristiclike;
            } else {
                $user['CharacteristicLike'] = array();
            }
            $rate = $this->RateReview->query("select id,doctor_id,clinic_id,user_id from rate_reviews where user_id=" . $id);
            if (!empty($rate)) {
                $user['givenRate'] = $rate;
            } else {
                $user['givenRate'] = array();
            }
            $user['profileimage'] = AWS_server . AWS_BUCKET . '/img/profile/' . $user['User']['id'];
            $chkimg = $this->Api->is_exist_img($user['profileimage']);
            if ($chkimg == 200) {
                $user['profileimage'] = AWS_server . AWS_BUCKET . '/img/profile/' . $user['User']['id'];
            } else {
                $user['profileimage'] = 'buzzydoc-user/avatars/profile-pic.jpg';
            }

            $response = array('success' => true, 'data' => $user);
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'userdetail' => $response,
            '_serialize' => array('userdetail')
        ));
    }

    /**
     * List of points from all practice.
     */
    public function userspoints() {

        if ($this->request->is('post')) {
            $userId = $this->request->data('user_id');
            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');

            if (!empty($userId)) {
                $options8['conditions'] = array('ClinicUser.user_id' => $userId);
                $options8['limit'] = $limit;
                $options8['offset'] = $offset;
                $clinicuser = $this->ClinicUser->find('all', $options8);
                $i = 0;

                foreach ($clinicuser as $clinicu) {
                    $options['conditions'] = array('Clinic.id' => $clinicu['ClinicUser']['clinic_id']);
                    $clinic = $this->Clinic->find('first', $options);
                    if ($clinic['Clinic']['display_name'] == '') {
                        $clinicuser[$i]['clinicname'] = ucwords($clinic['Clinic']['api_user']);
                    } else {
                        $clinicuser[$i]['clinicname'] = $clinic['Clinic']['display_name'];
                    }
                    $clinicuser[$i]['cliniclogo'] = S3Path . $clinic['Clinic']['buzzydoc_logo_url'];

                    $clinicuser[$i]['clinicurl'] = rtrim($clinic['Clinic']['patient_url'], '/') . '/rewards/login/redeem/' . base64_encode($clinicu['ClinicUser']['card_number']) . '/' . base64_encode($userId);
                    $i++;
                }

                $response = array('success' => true, 'data' => $clinicuser);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'userspoints' => $response,
            '_serialize' => array('userspoints')
        ));
    }

    /**
     * List of all unique checkins (Point Earn Transaction per day.)
     */
    public function checkins() {
        if ($this->request->is('post')) {
            $userId = $this->request->data('user_id');
            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');
            $timestamp = $this->request->data('timestamp');
            if (!empty($userId)) {
                $options['conditions'] = array('Transaction.user_id' => $userId, 'Transaction.amount >' => 0, 'Transaction.activity_type' => 'N', 'Transaction.date <=' => $timestamp);
                $options['limit'] = $limit;
                $options['offset'] = $offset;
                $options['order'] = array('Transaction.date' => 'desc');
                $options['group'] = array('date(Transaction.date),Transaction.clinic_id');
                $activities = $this->Transaction->find('all', $options);
                $i = 0;
                foreach ($activities as $act) {
                    $optionsdoc['conditions'] = array('Clinic.id' => $act['Transaction']['clinic_id']);
                    $clinic = $this->Clinic->find('first', $optionsdoc);
                    if ($act['Transaction']['doctor_id'] > 0) {
                        $optionsdoc['conditions'] = array('Doctor.id' => $act['Transaction']['doctor_id']);
                        $doc = $this->Doctor->find('first', $optionsdoc);

                        $docprofilePath = AWS_server . AWS_BUCKET . '/img/docprofile/' . $clinic['Clinic']['api_user'] . '/' . $doc['Doctor']['id'];

                        $chkimg = $this->Api->is_exist_img($docprofilePath);
                        if ($chkimg == 200) {
                            $activities[$i]['doctor_image'] = $docprofilePath;
                        } else {
                            if ($doc['Doctor']['gender'] == 'Male') {
                                $activities[$i]['doctor_image'] = CDN . 'img/images_buzzy/doctor-male.png';
                            } else {
                                $activities[$i]['doctor_image'] = CDN . 'img/images_buzzy/doctor-female.png';
                            }
                        }

                        $activities[$i]['doctor_name'] = $doc['Doctor']['first_name'] . ' ' . $doc['Doctor']['last_name'];
                    } else {
                        if ($clinic['Clinic']['display_name'] == '') {
                            $activities[$i]['doctor_name'] = $clinic['Clinic']['api_user'];
                        } else {
                            $activities[$i]['doctor_name'] = $clinic['Clinic']['display_name'];
                        }
                        $chkimg = $this->Api->is_exist_img(S3Path . $clinic['Clinic']['buzzydoc_logo_url']);
                        if ($chkimg == 200) {
                            $activities[$i]['doctor_image'] = S3Path . $clinic['Clinic']['buzzydoc_logo_url'];
                        } else {

                            $activities[$i]['doctor_image'] = CDN . 'img/images_buzzy/clinic.png';
                        }
                    }
                    $i++;
                }

                $response = array('success' => true, 'data' => $activities);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'checkins' => $response,
            '_serialize' => array('checkins')
        ));
    }

    /**
     * List of all saved doctor by patient.
     */
    public function saveddoctors() {
        if ($this->request->is('post')) {
            $userId = $this->request->data('user_id');
            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');
            $timestamp = $this->request->data('timestamp');
            if (!empty($userId)) {
                $options5['conditions'] = array('SaveLike.user_id' => $userId, 'SaveLike.clinic_id' => 0, 'SaveLike.doctor_id !=' => 0, 'SaveLike.date <=' => $timestamp);
                $options5['limit'] = $limit;
                $options5['offset'] = $offset;
                $Likesave = $this->SaveLike->find('all', $options5);
                $saveddoc = array();
                if (!empty($Likesave)) {
                    foreach ($Likesave as $ls) {
                        if ($ls['SaveLike']['doctor_id'] != '') {
                            $options6['conditions'] = array('Doctor.id' => $ls['SaveLike']['doctor_id']);
                            $Doctor = $this->Doctor->find('first', $options6);
                            if (!empty($Doctor)) {
                                $optionsdoc['conditions'] = array('Clinic.id' => $Doctor['Doctor']['clinic_id']);
                                $clinic = $this->Clinic->find('first', $optionsdoc);
                                $docprofilePath = AWS_server . AWS_BUCKET . '/img/docprofile/' . $clinic['Clinic']['api_user'] . '/' . $Doctor['Doctor']['id'];
                                $chkimg = $this->Api->is_exist_img($docprofilePath);
                                if ($chkimg == 200) {
                                    $Doctor['Doctor']['docimg'] = $docprofilePath;
                                } else {
                                    if ($Doctor['Doctor']['gender'] == 'Male') {
                                        $Doctor['Doctor']['docimg'] = CDN . 'img/images_buzzy/doctor-male.png';
                                    } else {
                                        $Doctor['Doctor']['docimg'] = CDN . 'img/images_buzzy/doctor-female.png';
                                    }
                                }
                                if ($Doctor['Doctor']['gender'] == 'FeMale') {
                                    $Doctor['Doctor']['gender'] = 'Female';
                                }
                                $Doctor['Doctor']['buzzyurl'] = '/doctor/' . $Doctor['Doctor']['first_name'] . ' ' . $Doctor['Doctor']['last_name'] . '/' . $Doctor['Doctor']['specialty'];
                                $saveddoc[] = $Doctor;
                            }
                        }
                    }
                }
                $response = array('success' => true, 'data' => $saveddoc);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'saveddoctors' => $response,
            '_serialize' => array('saveddoctors')
        ));
    }

    /**
     * List of all liked Pratice by Patient.
     */
    public function likedclinic() {
        if ($this->request->is('post')) {
            $userId = $this->request->data('user_id');
            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');
            $timestamp = $this->request->data('timestamp');
            if (!empty($userId)) {
                $options5['conditions'] = array('SaveLike.user_id' => $userId, 'SaveLike.clinic_id !=' => 0, 'SaveLike.doctor_id' => 0, 'SaveLike.date <=' => $timestamp);
                $options5['limit'] = $limit;
                $options5['offset'] = $offset;
                $Likesave = $this->SaveLike->find('all', $options5);

                $likedclinic = array();
                if (!empty($Likesave)) {
                    foreach ($Likesave as $ls) {

                        if ($ls['SaveLike']['clinic_id'] != '') {
                            $options7['conditions'] = array('Clinic.id' => $ls['SaveLike']['clinic_id']);
                            $options7['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.industry_type', 'Clinic.staff_url', 'Clinic.patient_url', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url', 'Clinic.is_buzzydoc', 'Clinic.is_lite');
                            $Clinic = $this->Clinic->find('first', $options7);
                            if (!empty($Clinic)) {
                                $chkimg = $this->Api->is_exist_img(S3Path . $Clinic['Clinic']['buzzydoc_logo_url']);
                                if (isset($Clinic['Clinic']['buzzydoc_logo_url']) && $Clinic['Clinic']['buzzydoc_logo_url'] != '') {
                                    $Clinic['Clinic']['clinicimg'] = S3Path . $Clinic['Clinic']['buzzydoc_logo_url'];
                                } else {

                                    $Clinic['Clinic']['clinicimg'] = CDN . 'img/images_buzzy/clinic.png';
                                }
                                $Clinic['Clinic']['buzzyclinicurl'] = '/practice/' . $Clinic['Clinic']['api_user'];

                                $likedclinic[] = $Clinic;
                            }
                        }
                    }
                }
                $response = array('success' => true, 'data' => $likedclinic);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'likedclinic' => $response,
            '_serialize' => array('likedclinic')
        ));
    }

    /**
     * Getting the list of all activty by Patient like (doctor saved,earned point,redeemed point,like clinic etc.)
     */
    public function usersactivity() {
        if ($this->request->is('post')) {

            $userId = $this->request->data('user_id');
            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');
            $timestamp = $this->request->data('timestamp');

            if (!empty($userId)) {
                $options['conditions'] = array('Transaction.user_id' => $userId, 'Transaction.date <=' => $timestamp, 'Transaction.amount !=' => 0, 'Transaction.activity_type' => 'N');
                $options['limit'] = 80;
                $options['order'] = array('Transaction.id' => 'desc');
                $activitiesN = $this->Transaction->find('all', $options);

                $optionsY['conditions'] = array('Transaction.user_id' => $userId, 'Transaction.date <=' => $timestamp, 'Transaction.activity_type' => 'Y');
                $optionsY['limit'] = 10;
                $optionsY['order'] = array('Transaction.id' => 'desc');
                $activitiesY = $this->Transaction->find('all', $optionsY);
                $activities = array_merge_recursive($activitiesN, $activitiesY);
                $counter = 0;
                $optionssave['conditions'] = array('SaveLike.user_id' => $userId, 'SaveLike.date <=' => $timestamp);
                $saveLikeDetails = $this->SaveLike->find('all', $optionssave);
                $clinicDetails = array();
                $doctorDetails = array();

                foreach ($saveLikeDetails as $svdtl) {

                    if (($svdtl['SaveLike']['clinic_id'] != 0) && ($svdtl['SaveLike']['clinic_id'] != '')) {
                        $userName = '';
                        $clinic_options['conditions'] = array('Clinic.id' => $svdtl['SaveLike']['clinic_id']);
                        $clinic_options['fields'] = array('Clinic.api_user', 'Clinic.display_name');
                        $clinic = $this->Clinic->find('first', $clinic_options);
                        if (count($clinic) > 0) {
                            $usr_options['conditions'] = array('User.id' => $svdtl['SaveLike']['user_id']);
                            $usr_options['fields'] = array('User.id', 'User.first_name', 'User.last_name');
                            $user = $this->User->find('first', $usr_options);
                            if (count($user) > 0) {
                                $userName = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                            }
                            if ($clinic['Clinic']['display_name'] == '') {
                                $clinicname = $clinic['Clinic']['api_user'];
                            } else {
                                $clinicname = $clinic['Clinic']['display_name'];
                            }
                            $authorization = $userName . ' liked the ' . $clinicname . ' practice';
                            $clinicDetails[] = array(
                                'user_id' => $user['User']['id'],
                                'first_name' => $user['User']['first_name'],
                                'last_name' => $user['User']['last_name'],
                                'given_name' => $clinicname,
                                'authorization' => $authorization,
                                'date' => $svdtl['SaveLike']['date'],
                                'activity_type' => 'like clinic');
                        }
                    }


                    if (($svdtl['SaveLike']['doctor_id'] != 0) && ($svdtl['SaveLike']['doctor_id'] != '')) {
                        $userName = '';

                        $dr_options['conditions'] = array('Doctor.id' => $svdtl['SaveLike']['doctor_id']);
                        $dr_options['fields'] = array('Doctor.first_name', 'Doctor.last_name');
                        $doctor = $this->Doctor->find('first', $dr_options);
                        if (count($doctor) > 0) {
                            $usr_options['conditions'] = array('User.id' => $svdtl['SaveLike']['user_id']);
                            $usr_options['fields'] = array('User.id', 'User.first_name', 'User.last_name');
                            $user = $this->User->find('first', $usr_options);

                            if (count($user) > 0) {
                                $userName = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                            }

                            $authorization = $userName . ' saved the Dr ' . $doctor['Doctor']['first_name'];
                            $doctorDetails[] = array(
                                'user_id' => $user['User']['id'],
                                'first_name' => $user['User']['first_name'],
                                'last_name' => $user['User']['last_name'],
                                'given_name' => $doctor['Doctor']['first_name'] . ' ' . $doctor['Doctor']['last_name'],
                                'authorization' => $authorization,
                                'date' => $svdtl['SaveLike']['date'],
                                'activity_type' => 'save doctor'
                            );
                        }
                    }
                }

                $buzzyDetails = $this->User->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'users_badges',
                            'type' => 'INNER',
                            'conditions' => array(
                                'User.id = users_badges.user_id'
                            )
                        ),
                        array(
                            'table' => 'badges',
                            'type' => 'INNER',
                            'conditions' => array(
                                'users_badges.badge_id = badges.id'
                            )
                        )),
                    'fields' => array('User.id', 'User.first_name', 'User.last_name', 'badges.name', 'badges.value', 'users_badges.created_on'),
                    'conditions' => array('User.id' => $userId, 'users_badges.created_on <=' => $timestamp, 'badges.clinic_id' => 0),
                ));


                foreach ($activities as $trns) {

                    if ($trns['Transaction']['activity_type'] == 'N') {
                        $activities[$counter]['Transaction']['activity_type'] = 'get point';
                    } else if ($trns['Transaction']['activity_type'] == 'Y') {
                        $activities[$counter]['Transaction']['activity_type'] = 'redeemed';
                    }



                    $clinic_options['conditions'] = array('Clinic.id' => $trns['Transaction']['clinic_id']);
                    $clinic_options['fields'] = array('Clinic.api_user', 'Clinic.display_name');
                    $staffDetails = $this->Clinic->find('first', $clinic_options);

                    if ($staffDetails['Clinic']['display_name'] == '') {
                        $clinicname = $staffDetails['Clinic']['api_user'];
                    } else {
                        $clinicname = $staffDetails['Clinic']['display_name'];
                    }
                    $activities[$counter]['Transaction']['given_name'] = $clinicname;
                    $activities[$counter]['Transaction']['buzzy_name'] = '';
                    $activities[$counter]['Transaction']['buzzy_value'] = '';

                    $counter++;
                }
                foreach ($buzzyDetails as $bz) {
                    $transaction = array(
                        'id' => '',
                        'user_id' => $bz['User']['id'],
                        'staff_id' => '',
                        'doctor_id' => '',
                        'card_number' => '',
                        'first_name' => $bz['User']['first_name'],
                        'last_name' => $bz['User']['last_name'],
                        'promotion_id' => '',
                        'reward_id' => '',
                        'activity_type' => 'earn badge',
                        'authorization' => 'got the new badge',
                        'amount' => '',
                        'clinic_id' => '',
                        'date' => $bz['users_badges']['created_on'],
                        'status' => '',
                        'is_buzzydoc' => '',
                        'given_name' => '',
                        'buzzy_name' => $bz['badges']['name'],
                        'buzzy_value' => $bz['badges']['value']
                    );
                    $activities[]['Transaction'] = $transaction;
                }

                foreach ($clinicDetails as $bz) {
                    $transaction = array(
                        'id' => '',
                        'user_id' => $bz['user_id'],
                        'staff_id' => '',
                        'doctor_id' => '',
                        'card_number' => '',
                        'first_name' => $bz['first_name'],
                        'last_name' => $bz['last_name'],
                        'promotion_id' => '',
                        'reward_id' => '',
                        'activity_type' => $bz['activity_type'],
                        'authorization' => $bz['authorization'],
                        'amount' => '',
                        'clinic_id' => '',
                        'date' => $bz['date'],
                        'status' => '',
                        'is_buzzydoc' => '',
                        'given_name' => $bz['given_name'],
                        'buzzy_name' => '',
                        'buzzy_value' => ''
                    );
                    $activities[]['Transaction'] = $transaction;
                }

                foreach ($doctorDetails as $bz) {
                    $transaction = array(
                        'id' => '',
                        'user_id' => $bz['user_id'],
                        'staff_id' => '',
                        'doctor_id' => '',
                        'card_number' => '',
                        'first_name' => $bz['first_name'],
                        'last_name' => $bz['last_name'],
                        'promotion_id' => '',
                        'reward_id' => '',
                        'activity_type' => $bz['activity_type'],
                        'authorization' => $bz['authorization'],
                        'amount' => '',
                        'clinic_id' => '',
                        'date' => $bz['date'],
                        'status' => '',
                        'is_buzzydoc' => '',
                        'given_name' => $bz['given_name'],
                        'buzzy_name' => '',
                        'buzzy_value' => ''
                    );
                    $activities[]['Transaction'] = $transaction;
                }

                function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {
                    foreach ($array as $subarray) {
                        $keys[] = $subarray['Transaction'][$subkey];
                    }
                    array_multisort($keys, $sortType, $array);
                }

                sortBySubkey($activities, 'date');
                $actcnt = count($activities);
                $activitiesdetail = array();
                if ($actcnt > ($limit + $offset)) {
                    for ($i = $offset; $i < $limit + $offset; $i++) {
                        $activitiesdetail[] = $activities[$i];
                    }
                } else {
                    $activitiesdetail = $activities;
                }
                $response = array('success' => true, 'data' => $activitiesdetail);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'usersactivity' => $response,
            '_serialize' => array('usersactivity')
        ));
    }

    /**
     * Buzz list for buzzydoc like (Pratice given point to patient,badge earn by patient,points redeemed patient,patient saved the doctor etc.)
     */
    public function activity() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->offset > 0) {
            $off = $request_data->offset + 10;
        } else {
            $off = 10;
        }
        $options['limit'] = $off;
        $options['conditions'] = array('Transaction.amount !=' => 0, 'Transaction.activity_type' => 'N');
        $options['order'] = array('Transaction.date' => 'desc');
        $activitiesN = $this->Transaction->find('all', $options);
        $optionsy['limit'] = $off;
        $optionsy['conditions'] = array('Transaction.activity_type' => 'Y');
        $optionsy['order'] = array('Transaction.date' => 'desc');
        $activitiesY = $this->Transaction->find('all', $optionsy);
        $activities = array_merge_recursive($activitiesN, $activitiesY);
        $counter = 0;
        $options_svlk['limit'] = $off;
        $options_svlk['order'] = array('SaveLike.date' => 'desc');
        $saveLikeDetails = $this->SaveLike->find('all', $options_svlk);
        $clinicDetails = array();
        $doctorDetails = array();

        foreach ($saveLikeDetails as $svdtl) {

            if (($svdtl['SaveLike']['clinic_id'] != 0) && ($svdtl['SaveLike']['clinic_id'] != '')) {
                $userName = '';
                $clinic_options['conditions'] = array('Clinic.id' => $svdtl['SaveLike']['clinic_id']);
                $clinic_options['fields'] = array('Clinic.api_user', 'Clinic.display_name');
                $clinic = $this->Clinic->find('first', $clinic_options);
                if (count($clinic) > 0) {
                    $usr_options['conditions'] = array('User.id' => $svdtl['SaveLike']['user_id']);
                    $usr_options['fields'] = array('User.id', 'User.first_name', 'User.last_name');
                    $user = $this->User->find('first', $usr_options);
                    if (count($user) > 0) {
                        $userName = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                    }
                    if ($clinic['Clinic']['display_name'] == '') {
                        $clinicname = $clinic['Clinic']['api_user'];
                    } else {
                        $clinicname = $clinic['Clinic']['display_name'];
                    }

                    $authorization = $userName . ' liked the ' . $clinicname . ' practice';
                    $clinicDetails[] = array(
                        'user_id' => $user['User']['id'],
                        'first_name' => $user['User']['first_name'],
                        'last_name' => $user['User']['last_name'],
                        'given_name' => $clinicname,
                        'authorization' => $authorization,
                        'date' => $svdtl['SaveLike']['date'],
                        'activity_type' => 'like clinic');
                }
            }


            if (($svdtl['SaveLike']['doctor_id'] != 0) && ($svdtl['SaveLike']['doctor_id'] != '')) {
                $userName = '';
                $dr_options['conditions'] = array('Doctor.id' => $svdtl['SaveLike']['doctor_id']);
                $dr_options['fields'] = array('Doctor.first_name', 'Doctor.last_name');
                $doctor = $this->Doctor->find('first', $dr_options);
                if (count($doctor) > 0) {
                    $usr_options['conditions'] = array('User.id' => $svdtl['SaveLike']['user_id']);
                    $usr_options['fields'] = array('User.id', 'User.first_name', 'User.last_name');
                    $user = $this->User->find('first', $usr_options);
                    if (count($user) > 0) {
                        $userName = $user['User']['first_name'] . ' ' . $user['User']['last_name'];
                    }
                    $authorization = $userName . ' saved the Dr ' . $doctor['Doctor']['first_name'];
                    $doctorDetails[] = array(
                        'user_id' => $user['User']['id'],
                        'first_name' => $user['User']['first_name'],
                        'last_name' => $user['User']['last_name'],
                        'given_name' => $doctor['Doctor']['first_name'] . ' ' . $doctor['Doctor']['last_name'],
                        'authorization' => $authorization,
                        'date' => $svdtl['SaveLike']['date'],
                        'activity_type' => 'save doctor'
                    );
                }
            }
        }
        $buzzyDetails = $this->User->find('all', array(
            'joins' => array(
                array(
                    'table' => 'users_badges',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = users_badges.user_id'
                    )
                ),
                array(
                    'table' => 'badges',
                    'type' => 'INNER',
                    'conditions' => array(
                        'users_badges.badge_id = badges.id',
                        'badges.clinic_id' => 0
                    )
                )),
            'fields' => array('User.id', 'User.first_name', 'User.last_name', 'badges.name', 'badges.value', 'users_badges.created_on'),
            'limit' => $off,
            'order' => array('users_badges.created_on' => 'desc')
        ));

        foreach ($activities as $trns) {
            $activities[$counter]['Transaction']['amount'] = $this->Api->custom_number_format($trns['Transaction']['amount']);
            if ($trns['Transaction']['activity_type'] == 'N') {
                $activities[$counter]['Transaction']['activity_type'] = 'get point';
            } else if ($trns['Transaction']['activity_type'] == 'Y') {
                $activities[$counter]['Transaction']['activity_type'] = 'redeemed';
            }
            $clinic_options['conditions'] = array('Clinic.id' => $trns['Transaction']['clinic_id']);
            $clinic_options['fields'] = array('Clinic.api_user', 'Clinic.display_name');
            $staffDetails = $this->Clinic->find('first', $clinic_options);
            if ($staffDetails['Clinic']['display_name'] == '') {
                $clinicname = $staffDetails['Clinic']['api_user'];
            } else {
                $clinicname = $staffDetails['Clinic']['display_name'];
            }
            $activities[$counter]['Transaction']['given_name'] = $clinicname;
            $activities[$counter]['Transaction']['buzzy_name'] = '';
            $activities[$counter]['Transaction']['buzzy_value'] = '';

            $counter++;
        }

        foreach ($buzzyDetails as $bz) {
            $transaction = array(
                'id' => '',
                'user_id' => $bz['User']['id'],
                'staff_id' => '',
                'doctor_id' => '',
                'card_number' => '',
                'first_name' => $bz['User']['first_name'],
                'last_name' => $bz['User']['last_name'],
                'promotion_id' => '',
                'reward_id' => '',
                'activity_type' => 'earn badge',
                'authorization' => 'got the new badge of ' . $bz['badges']['name'],
                'amount' => '',
                'clinic_id' => '',
                'date' => $bz['users_badges']['created_on'],
                'status' => '',
                'is_buzzydoc' => '',
                'given_name' => '',
                'buzzy_name' => $bz['badges']['name'],
                'buzzy_value' => $this->Api->custom_number_format($bz['badges']['value'])
            );
            $activities[]['Transaction'] = $transaction;
        }

        foreach ($clinicDetails as $bz) {
            $transaction = array(
                'id' => '',
                'user_id' => $bz['user_id'],
                'staff_id' => '',
                'doctor_id' => '',
                'card_number' => '',
                'first_name' => $bz['first_name'],
                'last_name' => $bz['last_name'],
                'promotion_id' => '',
                'reward_id' => '',
                'activity_type' => $bz['activity_type'],
                'authorization' => $bz['authorization'],
                'amount' => '',
                'clinic_id' => '',
                'date' => $bz['date'],
                'status' => '',
                'is_buzzydoc' => '',
                'given_name' => $bz['given_name'],
                'buzzy_name' => '',
                'buzzy_value' => ''
            );
            $activities[]['Transaction'] = $transaction;
        }

        foreach ($doctorDetails as $bz) {
            $transaction = array(
                'id' => '',
                'user_id' => $bz['user_id'],
                'staff_id' => '',
                'doctor_id' => '',
                'card_number' => '',
                'first_name' => $bz['first_name'],
                'last_name' => $bz['last_name'],
                'promotion_id' => '',
                'reward_id' => '',
                'activity_type' => $bz['activity_type'],
                'authorization' => $bz['authorization'],
                'amount' => '',
                'clinic_id' => '',
                'date' => $bz['date'],
                'status' => '',
                'is_buzzydoc' => '',
                'given_name' => $bz['given_name'],
                'buzzy_name' => '',
                'buzzy_value' => ''
            );
            $activities[]['Transaction'] = $transaction;
        }

        function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {
            foreach ($array as $subarray) {
                $keys[] = $subarray['Transaction'][$subkey];
            }
            array_multisort($keys, $sortType, $array);
        }

        sortBySubkey($activities, 'date');
        $tlact = count($activities);

        $activitiesdetail = array();

        if (($request_data->limit + $request_data->offset) < $tlact) {
            for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                $activitiesdetail[] = $activities[$i];
            }
        }
        $response = array('success' => true, 'data' => $activitiesdetail);

        $this->set(array(
            'usersactivity' => $response,
            '_serialize' => array('usersactivity')
        ));
    }

    /**
     * Getting the all Pratice list.
     */
    public function cliniclist() {
        $request_data = $this->request->input('json_decode');
        $options['conditions'] = array('Clinic.status' => 1);
        $options['limit'] = $request_data->limit;
        $options['offset'] = $request_data->offset;
        $options['order'] = array('Clinic.api_user asc');
        $cliniclist = $this->Clinic->find('all', $options);
        $i = 0;
        $allclinic = array();
        foreach ($cliniclist as $clinic) {
            $response = $this->cliniclist_helper($clinic['Clinic']['api_user'], 1);
            $allclinic[$i] = $response;
            $i++;
        }
        $response = array('success' => true, 'data' => $allclinic);
        $this->set(array(
            'cliniclists' => $response,
            '_serialize' => array('cliniclists')
        ));
    }

    /**
     * Get the pratice all details like (Number of patient,)
     */
    public function clinic($id) {
        $request_data = $this->request->input('json_decode');
        if ($id != '') {
            $result = $this->clinicdetail_helper($id, 0, $request_data->user_id);
            $response = array('success' => true, 'data' => $result);
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'clinics' => $response,
            '_serialize' => array('clinics')
                )
        );
    }

    /**
     * Helper function to get list of practice.
     * @param type $id
     * @param type $check
     * @return int
     */
    public function cliniclist_helper($id, $check = 0) {
        $clinics = array();
        $options['conditions'] = array('Clinic.api_user' => $id);
        $options['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.patient_url', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url', 'is_lite', 'is_buzzydoc');
        $clinics = $this->Clinic->find('first', $options);

        if (!empty($clinics)) {
            $totalpointshare = $this->Promotion->query("SELECT `Promotion`.`id`, `Promotion`.`operand`, `Promotion`.`value`, `Promotion`.`display_name`, `Promotion`.`description`, `Promotion`.`clinic_id`, `Promotion`.`is_lite` FROM `promotions` AS `Promotion`   WHERE (`Promotion`.`description`='For Check In') AND `Promotion`.`clinic_id` = '" . $clinics['Clinic']['id'] . "' AND `Promotion`.`is_lite`= '1' order by `Promotion`.`value` asc");
            if ($totalpointshare[0]['Promotion']['value'] > 0 && $clinics['Clinic']['is_lite'] == 1 && $clinics['Clinic']['is_buzzydoc'] == 1) {
                $clinics['Pointshare'] = $totalpointshare[0]['Promotion']['value'];
                $clinics['Pointsharesrt'] = $totalpointshare[0]['Promotion']['value'];
            } else {

                $totalpointshareoth = $this->Promotion->query("SELECT `Promotion`.`id`, `Promotion`.`operand`, `Promotion`.`value`, `Promotion`.`display_name`, `Promotion`.`description`, `Promotion`.`clinic_id`, `Promotion`.`is_lite` FROM `promotions` AS `Promotion`   WHERE (`Promotion`.`description`='Perfect Score Check-in') AND `Promotion`.`clinic_id` = '" . $clinics['Clinic']['id'] . "' AND `Promotion`.`is_lite` != '1' order by `Promotion`.`value` asc");

                if ($totalpointshareoth[0]['Promotion']['value'] > 0) {
                    $clinics['Pointshare'] = $totalpointshareoth[0]['Promotion']['value'];
                    $clinics['Pointsharesrt'] = $totalpointshareoth[0]['Promotion']['value'];
                } else {
                    $clinics['Pointshare'] = 0;
                    $clinics['Pointsharesrt'] = 0;
                }
            }

            $rateclinic = $this->RateReview->query("select avg(rr.rate) as totalrate from rate_reviews as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and rr.doctor_id=0 group by rr.clinic_id");
            if (isset($rateclinic[0][0]['totalrate'])) {
                $clinics['Rate'] = round($rateclinic[0][0]['totalrate']);
            } else {
                $clinics['Rate'] = 0;
            }

            $reviewclinic = $this->RateReview->query("select * from rate_reviews as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and rr.review!='' order by created_on desc");

            if (!empty($reviewclinic)) {

                $clinics['TotalReview'] = count($reviewclinic);
            } else {

                $clinics['TotalReview'] = 0;
            }

            $checkinclinic = $this->Transaction->query("select count(*) as total from transactions as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and activity_type='N'  group by rr.clinic_id");

            if (isset($checkinclinic[0][0]['total'])) {
                $clinics['TotalCheckin'] = $this->Api->custom_number_format($checkinclinic[0][0]['total']);
            } else {
                $clinics['TotalCheckin'] = 0;
            }
            $options5['conditions'] = array('SaveLike.clinic_id' => $clinics['Clinic']['id']);
            $options5['fields'] = array('count(*) as totallike');
            $totallike = $this->SaveLike->find('first', $options5);
            if (isset($totallike[0]['totallike'])) {
                $clinics['Likes'] = $totallike[0]['totallike'];
            } else {
                $clinics['Likes'] = 0;
            }
        }
        return $clinics;
    }

    /**
     * Hel

     * @param type $id
     * @param type $check
     * @param type $user_id
     * @return \intper|int * @param type $id
     * @param type $check
     * @param type $user_id
     * @return intper function to get all details for practice.
     */
    public function clinicdetail_helper($id, $check = 0, $user_id) {
        $clinics = array();
        $options['conditions'] = array('OR' => array('Clinic.api_user' => $id, 'Clinic.patient_url LIKE ' => "%" . $id . "%"));
        $options['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.industry_type', 'Clinic.staff_url', 'Clinic.patient_url', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.about', 'Clinic.facebook_url', 'Clinic.instagram_url', 'Clinic.google_url', 'Clinic.yelp_url', 'Clinic.youtube_url', 'Clinic.healthgrade_url', 'Clinic.website_url', 'Clinic.pintrest_url', 'Clinic.twitter_url', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url', 'Clinic.fb_app_id', 'Clinic.fb_app_key', 'Clinic.minimum_deposit');
        $clinics = $this->Clinic->find('first', $options);

        if (!empty($clinics)) {

            if ($check == 0) {
                $optionslk['conditions'] = array('SaveLike.clinic_id' => $clinics['Clinic']['id']);

                $likeclinic = $this->SaveLike->find('all', $optionslk);
                if (!empty($likeclinic)) {
                    $clinics['LikesClinic'] = $likeclinic;
                } else {
                    $clinics['LikesClinic'] = array();
                }
                if ($clinics['Clinic']['is_lite'] == 1) {
                    $options11['conditions'] = array('Promotion.is_lite' => 1, 'Promotion.public' => 1, 'Promotion.clinic_id' => $clinics['Clinic']['id']);
                    $options11['order'] = array('Promotion.sort' => 'asc');
                    $promotion11 = $this->Promotion->find('all', $options11);
                } else {
                    $optionscat['conditions'] = array('Promotion.public' => 1, 'Promotion.is_lite !=' => 1, 'Promotion.clinic_id' => $clinics['Clinic']['id']);
                    $optionscat['order'] = array('Promotion.sort' => 'asc');
                    $promotion11 = $this->Promotion->find('all', $optionscat);
                }
                if (!empty($promotion11)) {
                    $clinics['Promotions'] = $promotion11;
                } else {
                    $clinics['Promotions'] = array();
                }

                $insurence = $this->CharacteristicInsurance->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_char_insu_proces',
                            'alias' => 'ClinicCharInsuProce',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicCharInsuProce.clinic_id' => $clinics['Clinic']['id'],
                        'CharacteristicInsurance.type' => 'Insurance'
                    ),
                    'fields' => array('CharacteristicInsurance.*')
                ));
                if (!empty($insurence)) {
                    $clinics['insurence_provider'] = $insurence;
                } else {
                    $clinics['insurence_provider'] = array();
                }

                $charact = $this->CharacteristicInsurance->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_char_insu_proces',
                            'alias' => 'ClinicCharInsuProce',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicCharInsuProce.clinic_id' => $clinics['Clinic']['id'],
                        'CharacteristicInsurance.type' => 'Characteristic'
                    ),
                    'fields' => array('CharacteristicInsurance.*')
                ));
                if (!empty($charact)) {
                    $clinics['Characteristic'] = $charact;
                } else {
                    $clinics['Characteristic'] = array();
                }
                $Procedure = $this->CharacteristicInsurance->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_char_insu_proces',
                            'alias' => 'ClinicCharInsuProce',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicCharInsuProce.clinic_id' => $clinics['Clinic']['id'],
                        'CharacteristicInsurance.type' => 'Procedure'
                    ),
                    'fields' => array('CharacteristicInsurance.*')
                ));
                if (!empty($Procedure)) {
                    $clinics['Procedure'] = $Procedure;
                } else {
                    $clinics['Procedure'] = array();
                }

                if ($user_id > 0) {
                    $checkin_clinic = $this->Transaction->query("select count(*) as total from transactions as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and activity_type='N' and user_id=" . $user_id);

                    $clinics['selfCheckin'] = $checkin_clinic[0][0]['total'];
                } else {
                    $clinics['selfCheckin'] = 0;
                }


                $options3['conditions'] = array('Transaction.clinic_id' => $clinics['Clinic']['id'], 'Transaction.activity_type' => 'N');
                $options3['limit'] = 3;
                $options3['order'] = array('Transaction.date' => 'desc');
                $activities = $this->Transaction->find('all', $options3);
                if (!empty($activities)) {
                    $clinics['Activities'] = $activities;
                } else {
                    $clinics['Activities'] = array();
                }
            }
            $options1['conditions'] = array('ClinicLocation.clinic_id' => $clinics['Clinic']['id']);

            $cliniclocation = $this->ClinicLocation->find('all', $options1);
            if (!empty($cliniclocation)) {
                $prime = array();
                foreach ($cliniclocation as $loc) {
                    if ($loc['ClinicLocation']['is_prime'] == 1) {

                        $clinics['PrimeOffices'] = $loc;
                        break;
                    }
                }
                $clinics['Offices'] = $cliniclocation;
            } else {
                $clinics['Offices'] = array();
            }

            $optionsproduct['conditions'] = array('ProductService.clinic_id' => $clinics['Clinic']['id'], 'ProductService.status' => 1, 'ProductService.type !=' => 3);
            $optionsproduct['order'] = array('ProductService.points asc');

            $ProductService = $this->ProductService->find('all', $optionsproduct);
            if (!empty($ProductService)) {
                $clinics['ProductService'] = $ProductService;
            } else {
                $clinics['ProductService'] = array();
            }

            $options2['conditions'] = array('Doctor.clinic_id' => $clinics['Clinic']['id']);
            $doctors = $this->Doctor->find('all', $options2);
            if (!empty($doctors)) {
                $clinics['Doctors'] = $doctors;
            } else {
                $clinics['Doctors'] = array();
            }

            $totalpointshare = $this->Promotion->query("SELECT `Promotion`.`id`, `Promotion`.`operand`, `Promotion`.`value`, `Promotion`.`display_name`, `Promotion`.`description`, `Promotion`.`clinic_id`, `Promotion`.`is_lite` FROM `promotions` AS `Promotion`   WHERE (`Promotion`.`description`='For Check In') AND `Promotion`.`clinic_id` = '" . $clinics['Clinic']['id'] . "' AND `Promotion`.`is_lite`= '1' order by `Promotion`.`value` asc");
            if ($totalpointshare[0]['Promotion']['value'] > 0 && $clinics['Clinic']['is_lite'] == 1 && $clinics['Clinic']['is_buzzydoc'] == 1) {
                $clinics['Pointshare'] = $totalpointshare[0]['Promotion']['value'];
                $clinics['Pointsharesrt'] = $totalpointshare[0]['Promotion']['value'];
            } else {

                $totalpointshareoth = $this->Promotion->query("SELECT `Promotion`.`id`, `Promotion`.`operand`, `Promotion`.`value`, `Promotion`.`display_name`, `Promotion`.`description`, `Promotion`.`clinic_id`, `Promotion`.`is_lite` FROM `promotions` AS `Promotion`   WHERE (`Promotion`.`description`='Perfect Score Check-in') AND `Promotion`.`clinic_id` = '" . $clinics['Clinic']['id'] . "' AND `Promotion`.`is_lite` != '1' order by `Promotion`.`value` asc");
                if ($totalpointshareoth[0]['Promotion']['value'] > 0) {
                    $clinics['Pointshare'] = $totalpointshareoth[0]['Promotion']['value'];
                    $clinics['Pointsharesrt'] = $totalpointshareoth[0]['Promotion']['value'];
                } else {
                    $clinics['Pointshare'] = 0;
                    $clinics['Pointsharesrt'] = 0;
                }
            }

            $rateclinic = $this->RateReview->query("select avg(rr.rate) as totalrate from rate_reviews as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and rr.doctor_id=0 group by rr.clinic_id");
            if (isset($rateclinic[0][0]['totalrate'])) {
                $clinics['Rate'] = round($rateclinic[0][0]['totalrate']);
            } else {
                $clinics['Rate'] = 0;
            }

            $reviewclinic = $this->RateReview->query("select * from rate_reviews as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and rr.review!='' order by created_on desc");
            $s = 0;

            foreach ($reviewclinic as $rclinic) {
                $options212['conditions'] = array('Doctor.id' => $rclinic['rr']['doctor_id']);
                $doctorname = $this->Doctor->find('first', $options212);
                if (!empty($doctorname)) {
                    $reviewclinic[$s]['rr']['doctorname'] = $doctorname['Doctor']['first_name'] . ' ' . $doctorname['Doctor']['last_name'];
                    $reviewclinic[$s]['rr']['gender'] = $doctorname['Doctor']['gender'];
                    $s++;
                }
            }
            if (!empty($reviewclinic)) {
                $clinics['Reviews'] = $reviewclinic;
                $clinics['TotalReview'] = count($reviewclinic);
            } else {
                $clinics['Reviews'] = array();
                $clinics['TotalReview'] = 0;
            }

            $checkinclinic = $this->Transaction->query("select count(*) as total from transactions as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and activity_type='N'  group by rr.clinic_id");

            if (isset($checkinclinic[0][0]['total'])) {
                $clinics['TotalCheckin'] = $this->Api->custom_number_format($checkinclinic[0][0]['total']);
            } else {
                $clinics['TotalCheckin'] = 0;
            }

            $uniquecheckin = $this->Transaction->query("select count(*) as total from transactions as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and activity_type='N'  group by rr.user_id");

            if (!empty($uniquecheckin)) {
                $clinics['UniqueCheckin'] = count($uniquecheckin);
            } else {

                $clinics['UniqueCheckin'] = 0;
            }
            $start = date('Y-m-d H:i:s', strtotime('last month'));
            $end = date("Y-m-d H:i:s");
            $checkinmonthly = $this->Transaction->query("select count(*) as total from transactions as rr where rr.clinic_id=" . $clinics['Clinic']['id'] . " and activity_type='N' and rr.date between '" . $start . "' and '" . $end . "'  group by rr.clinic_id");

            if (isset($checkinmonthly[0][0]['total'])) {
                $clinics['MonthlyCheckin'] = $checkinmonthly[0][0]['total'];
            } else {
                $clinics['MonthlyCheckin'] = 0;
            }
            $options5['conditions'] = array('SaveLike.clinic_id' => $clinics['Clinic']['id']);
            $options5['fields'] = array('count(*) as totallike');
            $totallike = $this->SaveLike->find('first', $options5);
            if (isset($totallike[0]['totallike'])) {
                $clinics['Likes'] = $totallike[0]['totallike'];
            } else {
                $clinics['Likes'] = 0;
            }
        }
        return $clinics;
    }

    /**
     * Function to get all list of doctor.
     */
    public function doctorlist() {
        $request_data = $this->request->input('json_decode');
        $options3['limit'] = $request_data->limit;
        $options3['offset'] = $request_data->offset;
        $options3['order'] = array('Doctor.first_name' => 'asc');
        $doctorlists = $this->Doctor->find('all', $options3);
        $i = 0;
        $alldoc = array();
        foreach ($doctorlists as $doclist) {
            $response = $this->doctorlist_helper($doclist['Doctor']['id'], 1);
            $alldoc[$i] = $response;
            $i++;
        }
        $response = array('success' => true, 'data' => $alldoc);
        $this->set(array(
            'doctorslist' => $response,
            '_serialize' => array('doctorslist')
        ));
    }

    /**
     * Helper function to get list of all doctors.
     */
    public function doctorlist_helper($id, $check = 0) {
        $Doctors = array();
        $options['conditions'] = array('Doctor.id' => $id);
        $Doctors = $this->Doctor->find('first', $options);
        if (!empty($Doctors)) {
            $options5['conditions'] = array('Clinic.id' => $Doctors['Doctor']['clinic_id']);
            $options5['fields'] = array('Clinic.is_lite,Clinic.api_user,Clinic.id,Clinic.display_name', 'Clinic.buzzydoc_logo_url');
            $Clinics = $this->Clinic->find('first', $options5);
            $options4['conditions'] = array('Transaction.doctor_id' => $id, 'Transaction.activity_type' => 'N');
            $options4['order'] = array('Transaction.date' => 'desc');
            $options4['fields'] = array('sum(Transaction.amount) as share');
            $totalpointshare = $this->Transaction->find('first', $options4);
            if (isset($totalpointshare[0]['share'])) {
                $Doctors['Pointshare'] = $this->Api->custom_number_format($totalpointshare[0]['share']);
                $Doctors['Pointsharesrt'] = $totalpointshare[0]['share'];
            } else {
                $Doctors['Pointshare'] = 0;
                $Doctors['Pointsharesrt'] = 0;
            }

            $ratedoctor = $this->RateReview->query("select avg(rr.rate) as totalrate from rate_reviews as rr where rr.doctor_id=" . $id . " and rr.clinic_id=0 group by rr.doctor_id");

            if (isset($ratedoctor[0][0]['totalrate'])) {
                $Doctors['Rate'] = round($ratedoctor[0][0]['totalrate']);
            } else {
                $Doctors['Rate'] = 0;
            }
            $savedoctor = $this->SaveLike->query("select count(*) as tl from save_likes as sl where sl.doctor_id=" . $id . " group by sl.doctor_id");
            if (isset($savedoctor[0][0]['tl'])) {
                $Doctors['Save'] = round($savedoctor[0][0]['tl']);
            } else {
                $Doctors['Save'] = 0;
            }

            $Doctors['ClinicName'] = $Clinics['Clinic']['api_user'];
        }
        return $Doctors;
    }

    /**
     * Main function to get doctor.
     */
    public function doctor($id) {
        if ($id != '') {
            $result = $this->doctordetail_helper($id, 0);
            $response = array('success' => true, 'data' => $result);
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'Doctors' => $response,
            '_serialize' => array('Doctors')
                )
        );
    }

    /**
     * Helper function to get details of doctor.
     */
    public function doctordetail_helper($id, $check = 0) {
        $Doctors = array();
        $options['conditions'] = array('Doctor.id' => $id);
        $Doctors = $this->Doctor->find('first', $options);
        if (!empty($Doctors)) {
            $options5['conditions'] = array('Clinic.id' => $Doctors['Doctor']['clinic_id']);
            $options5['fields'] = array('Clinic.is_lite', 'Clinic.api_user', 'Clinic.id', 'Clinic.display_name', 'Clinic.is_buzzydoc');
            $Clinics = $this->Clinic->find('first', $options5);
            if ($check == 0) {
                if ($Clinics['Clinic']['is_lite'] == 1) {
                    $options11['conditions'] = array('Promotion.is_lite' => 1, 'Promotion.public' => 1, 'Promotion.clinic_id' => $Clinics['Clinic']['id']);
                    $options11['order'] = array('Promotion.sort' => 'asc');
                    $promotion11 = $this->Promotion->find('all', $options11);
                } else {
                    $optionscat['conditions'] = array('Promotion.public' => 1, 'Promotion.is_lite !=' => 1, 'Promotion.clinic_id' => $Clinics['Clinic']['id']);

                    $optionscat['order'] = array('Promotion.sort' => 'asc');
                    $promotion11 = $this->Promotion->find('all', $optionscat);
                }
                if (!empty($promotion11)) {
                    $Doctors['Promotions'] = $promotion11;
                } else {
                    $Doctors['Promotions'] = array();
                }

                $options3['conditions'] = array('Transaction.doctor_id' => $id);
                $options3['limit'] = 3;
                $options3['order'] = array('Transaction.date' => 'desc');
                $activities = $this->Transaction->find('all', $options3);
                if (!empty($activities)) {
                    $Doctors['Activities'] = $activities;
                } else {
                    $Doctors['Activities'] = array();
                }

                $charact = $this->CharacteristicInsurance->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_char_insu_proces',
                            'alias' => 'ClinicCharInsuProce',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicCharInsuProce.char_insue_proce_id = CharacteristicInsurance.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicCharInsuProce.clinic_id' => $Doctors['Doctor']['clinic_id'],
                        'CharacteristicInsurance.type' => 'Characteristic'
                    )
                    ,
                    'order' => array(
                        'CharacteristicInsurance.id'
                    ),
                    'fields' => array('CharacteristicInsurance.*')
                ));


                if (!empty($charact)) {
                    $c = 0;
                    foreach ($charact as $char) {

                        $Characteristiclike = $this->CharacteristicInsuranceLike->query("select count(*) as total,characteristic_insurance_id from characteristic_insurance_likes where doctor_id=" . $id . " and characteristic_insurance_id=" . $char['CharacteristicInsurance']['id'] . " group by characteristic_insurance_id");

                        if (isset($Characteristiclike[0][0]['total'])) {
                            $charact[$c]['totallike'] = $Characteristiclike[0][0]['total'];
                        } else {
                            $charact[$c]['totallike'] = 0;
                        }
                        $c++;
                    }
                }
                if (!empty($charact)) {
                    $Doctors['Characteristics'] = $charact;
                } else {
                    $Doctors['Characteristics'] = array();
                }
                if ($Doctors['Doctor']['procedures'] != '') {
                    $procedure = $this->CharacteristicInsurance->query('SELECT * FROM characteristic_insurances as ci join clinic_char_insu_proces as cip on cip.char_insue_proce_id=ci.id WHERE id IN (' . $Doctors['Doctor']['procedures'] . ') and cip.clinic_id=' . $Clinics['Clinic']['id']);
                    if (!empty($procedure)) {
                        $Doctors['Procedures'] = $procedure;
                    } else {
                        $Doctors['Procedures'] = array();
                    }
                } else {
                    $Doctors['Procedures'] = array();
                }

                $DoctorLocations = $this->ClinicLocation->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'doctor_locations',
                            'alias' => 'DL',
                            'type' => 'INNER',
                            'conditions' => array(
                                'DL.location_id = ClinicLocation.id'
                            )
                        )),
                    'conditions' => array(
                        'DL.doctor_id' => $id
                    ),
                    'fields' => array('ClinicLocation.*')
                ));
                if (!empty($DoctorLocations)) {
                    $Doctors['Offices'] = $DoctorLocations;
                } else {
                    $Doctors['Offices'] = array();
                }
            }
            $options4['conditions'] = array('Transaction.doctor_id' => $id, 'Transaction.activity_type' => 'N');
            $options4['order'] = array('Transaction.date' => 'desc');
            $options4['fields'] = array('sum(Transaction.amount) as share');
            $totalpointshare = $this->Transaction->find('first', $options4);
            if (isset($totalpointshare[0]['share'])) {
                $Doctors['Pointshare'] = $this->Api->custom_number_format($totalpointshare[0]['share']);
                $Doctors['Pointsharesrt'] = $totalpointshare[0]['share'];
            } else {
                $Doctors['Pointshare'] = 0;
                $Doctors['Pointsharesrt'] = 0;
            }

            $ratedoctor = $this->RateReview->query("select avg(rr.rate) as totalrate from rate_reviews as rr where rr.doctor_id=" . $id . " and rr.clinic_id=0 group by rr.doctor_id");

            if (isset($ratedoctor[0][0]['totalrate'])) {
                $Doctors['Rate'] = round($ratedoctor[0][0]['totalrate']);
            } else {
                $Doctors['Rate'] = 0;
            }
            $savedoctor = $this->SaveLike->query("select count(*) as tl from save_likes as sl where sl.doctor_id=" . $id . " group by sl.doctor_id");
            if (isset($savedoctor[0][0]['tl'])) {
                $Doctors['Save'] = round($savedoctor[0][0]['tl']);
            } else {
                $Doctors['Save'] = 0;
            }

            $Doctors['ClinicName'] = $Clinics['Clinic']['api_user'];
            $Doctors['is_buzzydoc'] = $Clinics['Clinic']['is_buzzydoc'];
        }
        return $Doctors;
    }

    /**
     * Search doctor or pratice using same parameters.
     */
    public function serarchdocorclinic() {
        $request_data = $this->request->input('json_decode');

        if (isset($request_data->key) && ($request_data->key != '') && (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $request_data->key) == false)) {
            $cliniclist = $this->Clinic->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'Clinic.api_user LIKE' => '%' . $request_data->key . '%', 'Clinic.display_name LIKE' => '%' . $request_data->key . '%'
                    ), 'Clinic.status' => 1
                ),
                'fields' => array('Clinic.api_user')
            ));

            $clinicarray = array();
            foreach ($cliniclist as $adoc) {
                $clinicarray[] = $adoc['Clinic']['api_user'];
            }
            $clinicarr = array_unique($clinicarray);

            $i = 0;
            $allclinic = array();
            foreach ($clinicarr as $clinicid) {

                $response = $this->cliniclist_helper($clinicid, 1);
                $allclinic[$i] = $response;
                $i++;
            }
            foreach ($allclinic as $key => $row) {
                $timeArr[$key] = $row['Rate']; //strtotime(str_replace('T',' ',$row['date']));
            }
            if (!empty($allclinic)) {
                array_multisort($timeArr, SORT_DESC, $allclinic);
            }

            $doctorlist = $this->Doctor->find('all', array(
                'conditions' => array(
                    'CONCAT(Doctor.first_name, " ", Doctor.last_name) LIKE' => '%' . $request_data->key . '%'
                ),
                'fields' => array('Doctor.id')
            ));
            $docarray = array();
            foreach ($doctorlist as $adoc) {
                $docarray[] = $adoc['Doctor']['id'];
            }
            $doctorarr = array_unique($docarray);

            $i = 0;
            $alldoc = array();
            foreach ($doctorarr as $docid) {

                $response = $this->doctorlist_helper($docid, 1);
                $alldoc[$i] = $response;
                $i++;
            }
            foreach ($alldoc as $key => $row) {
                $timeArr[$key] = $row['Rate']; //strtotime(str_replace('T',' ',$row['date']));
            }
            if (!empty($alldoc)) {
                array_multisort($timeArr, SORT_DESC, $alldoc);
            }
            $result = array('clinicslist' => $allclinic, 'doctorlist' => $alldoc);
            $response = array('success' => true, 'data' => $result);
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'serachresult' => $response,
            '_serialize' => array('serachresult')
        ));
    }

    /**
     * Search pratice by insurence/procedure,type,zip code,rating and points.
     */
    public function searchclinic() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->type == '' && $request_data->insurance != '') {
            $cliniclists = $this->Clinic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_char_insu_proces',
                        'alias' => 'ccip',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ccip.clinic_id = Clinic.id'
                        )
                    )),
                'conditions' => array(
                    'ccip.char_insue_proce_id' => $request_data->insurance
                ),
                'fields' => array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url')
            ));
        } else if ($request_data->type != '' && $request_data->insurance == '') {
            $options['conditions'] = array('Clinic.industry_type' => $request_data->type);
            $options['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url');

            $cliniclists = $this->Clinic->find('all', $options);
        } else if ($request_data->type == '' && $request_data->insurance == '') {
            $options['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url');
            $options['order'] = array('Clinic.api_user asc');

            $cliniclists = $this->Clinic->find('all', $options);
        } else if ($request_data->type != '' && $request_data->insurance != '') {

            $cliniclists = $this->Clinic->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'clinic_char_insu_proces',
                        'alias' => 'ccip',
                        'type' => 'INNER',
                        'conditions' => array(
                            'ccip.clinic_id = Clinic.id'
                        )
                    )),
                'conditions' => array(
                    'ccip.char_insue_proce_id' => $request_data->insurance, 'Clinic.industry_type' => $request_data->type
                ),
                'fields' => array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url')
            ));
        }
        //condition for search Pratice by zip code.
        if ($request_data->zip != '') {
            $pdocarray = array();
            foreach ($cliniclists as $adoc) {
                $pdocarray[] = $adoc['Clinic']['id'];
            }
            $prepAddr = str_replace(' ', '+', $request_data->zip);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;
            $sql = "SELECT cl.clinic_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl where cl.clinic_id IN (" . implode(',', array_unique($pdocarray)) . ")  HAVING distance <= {$radius}  order by distance asc";
            $allclinicid = $this->ClinicLocation->query($sql);

            $cliniclists = array();
            if (count($allclinicid) > 0) {
                $clinicarray = array();
                foreach ($allclinicid as $adoc) {
                    $clinicarray[] = $adoc['cl']['clinic_id'];
                }
                $optionse['conditions'] = array('Clinic.id' => array_unique($clinicarray));
                $optionse['fields'] = array('Clinic.id', 'Clinic.display_name', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.is_lite', 'Clinic.patient_logo_url', 'Clinic.buzzydoc_logo_url');

                $cliniclists = $this->Clinic->find('all', $optionse);
            }
        }
        //condition for search Pratice by rating.
        if (isset($request_data->check) && $request_data->check == 'rating') {

            $i = 0;
            $allclinic = array();
            foreach ($cliniclists as $cliniclist) {
                $response = $this->cliniclist_helper($cliniclist['Clinic']['api_user'], 1);
                $allclinic[$i] = $response;
                $i++;
            }
            foreach ($allclinic as $key => $row) {
                $timeArr[$key] = $row['Rate']; //strtotime(str_replace('T',' ',$row['date']));
            }
            array_multisort($timeArr, SORT_DESC, $allclinic);


            $actcnt = count($allclinic);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $allclinic[$i];
                }
            } else {
                $activitiesdetail = $allclinic;
            }

            $response = array('success' => true, 'data' => $activitiesdetail);
            //condition for search Pratice by points.
        } else if (isset($request_data->check) && $request_data->check == 'point') {

            $i = 0;
            $allclinic = array();
            foreach ($cliniclists as $cliniclist) {
                $response = $this->cliniclist_helper($cliniclist['Clinic']['api_user'], 1);
                $allclinic[$i] = $response;
                $i++;
            }

            foreach ($allclinic as $key => $row) {
                $timeArr[$key] = $row['Pointsharesrt']; //strtotime(str_replace('T',' ',$row['date']));
            }
            array_multisort($timeArr, SORT_DESC, $allclinic);

            $actcnt = count($allclinic);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $allclinic[$i];
                }
            } else {
                $activitiesdetail = $allclinic;
            }

            $response = array('success' => true, 'data' => $activitiesdetail);
        } else {

            $i = 0;
            $allclinic = array();
            foreach ($cliniclists as $cliniclist) {
                $response = $this->cliniclist_helper($cliniclist['Clinic']['api_user'], 1);
                $allclinic[$i] = $response;
                $i++;
            }
            $actcnt = count($allclinic);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $allclinic[$i];
                }
            } else {
                $activitiesdetail = $allclinic;
            }

            $response = array('success' => true, 'data' => $activitiesdetail);
        }
        $this->set(array(
            'clinicslist' => $response,
            '_serialize' => array('clinicslist')
        ));
    }

    /**
     * Search doctor by insurence/procedure,type,zip code,rating and points.
     */
    public function serarchdoc() {
        $request_data = $this->request->input('json_decode');
        //condition for search Pratice by type.
        if ($request_data->zip == '' && $request_data->type != '') {
            $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.specialty="' . $request_data->type . '" group by dc.id order by totalrate desc ');
            //condition for search Pratice by zip code.
        } else if ($request_data->type == '' && $request_data->zip != '') {
            $prepAddr = str_replace(' ', '+', $request_data->zip);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;


            $sql = "SELECT dl.doctor_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl inner join doctor_locations as dl on dl.location_id=cl.id HAVING distance <= {$radius}  order by distance asc";
            $alldoctorid = $this->ClinicLocation->query($sql);
            $alldoctor = array();
            if (count($alldoctorid) > 0) {
                $docarray = array();
                foreach ($alldoctorid as $adoc) {
                    $docarray[] = $adoc['dl']['doctor_id'];
                }

                $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.id IN (' . implode(',', array_unique($docarray)) . ') group by dc.id order by totalrate desc');
            }
        } else if ($request_data->type == '' && $request_data->zip == '') {
            $alldoctor = $this->Doctor->query('select dc.* from doctors as dc order by dc.first_name desc limit ' . $request_data->offset . ',' . $request_data->limit);
        } else if ($request_data->type != '' && $request_data->zip != '') {
            $alldoctor1 = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.specialty="' . $request_data->type . '" group by dc.id order by totalrate desc');

            $pdocarray = array();
            foreach ($alldoctor1 as $adoc) {
                $pdocarray[] = $adoc['dc']['id'];
            }

            $prepAddr = str_replace(' ', '+', $request_data->zip);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;
            $sql = "SELECT dl.doctor_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl inner join doctor_locations as dl on dl.location_id=cl.id where dl.doctor_id IN (" . implode(',', array_unique($pdocarray)) . ") HAVING distance <= {$radius}  order by distance asc";
            $alldoctorid = $this->ClinicLocation->query($sql);
            $alldoctor = array();
            if (count($alldoctorid) > 0) {
                $docarray = array();
                foreach ($alldoctorid as $adoc) {
                    $docarray[] = $adoc['dl']['doctor_id'];
                }

                $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.id IN (' . implode(',', array_unique($docarray)) . ') group by dc.id order by totalrate desc');
            }
        }

        if (isset($request_data->check) && $request_data->check == 'rating') {

            $i = 0;
            $alldoc = array();
            foreach ($alldoctor as $doclist) {
                $response = $this->doctorlist_helper($doclist['dc']['id'], 1);
                $alldoc[$i] = $response;
                $i++;
            }
            foreach ($alldoc as $key => $row) {
                $timeArr[$key] = $row['Rate']; //strtotime(str_replace('T',' ',$row['date']));
            }
            array_multisort($timeArr, SORT_DESC, $alldoc);
            $actcnt = count($alldoc);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $alldoc[$i];
                }
            } else {
                $activitiesdetail = $alldoc;
            }

            $response = array('success' => true, 'data' => $activitiesdetail);
        } else if (isset($request_data->check) && $request_data->check == 'point') {


            $i = 0;
            $alldoc = array();
            foreach ($alldoctor as $doclist) {
                $response = $this->doctorlist_helper($doclist['dc']['id'], 1);
                $alldoc[$i] = $response;
                $i++;
            }

            foreach ($alldoc as $key => $row) {
                $timeArr[$key] = $row['Pointsharesrt']; //strtotime(str_replace('T',' ',$row['date']));
            }
            array_multisort($timeArr, SORT_DESC, $alldoc);

            $actcnt = count($alldoc);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $alldoc[$i];
                }
            } else {
                $activitiesdetail = $alldoc;
            }
            $response = array('success' => true, 'data' => $activitiesdetail);
        } else {

            $i = 0;
            $alldoc = array();
            foreach ($alldoctor as $doclist) {
                $response = $this->doctorlist_helper($doclist['dc']['id'], 1);
                $alldoc[$i] = $response;
                $i++;
            }
            $actcnt = count($alldoc);
            $activitiesdetail = array();
            if ($actcnt > ($request_data->limit + $request_data->offset)) {
                for ($i = $request_data->offset; $i < $request_data->limit + $request_data->offset; $i++) {
                    $activitiesdetail[] = $alldoc[$i];
                }
            } else {
                $activitiesdetail = $alldoc;
            }
            $response = array('success' => true, 'data' => $activitiesdetail);
        }
        $this->set(array(
            'doctorslist' => $response,
            '_serialize' => array('doctorslist')
        ));
    }

    /**
     * get top 3 doctor by rateing.
     */
    public function gettop3doctor() {
        $doctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id group by dc.id order by totalrate desc limit 3');
        $response = array('success' => true, 'data' => $doctor);
        $this->set(array(
            'top3doc' => $response,
            '_serialize' => array('top3doc')
        ));
    }

    /**
     * Get doctor speciality vies.
     */
    public function getspecialitydoc() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->pincode == '' && $request_data->specialty != '') {
            $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.specialty="' . $request_data->specialty . '" group by dc.id order by totalrate desc limit 3');
            $response = array('success' => true, 'data' => $alldoctor);
            $this->set(array(
                'specilitydoc' => $response,
                '_serialize' => array('specilitydoc')
            ));
        } else if ($request_data->specialty == '' && $request_data->pincode != '') {
            $prepAddr = str_replace(' ', '+', $request_data->pincode);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;


            $sql = "SELECT dl.doctor_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl inner join doctor_locations as dl on dl.location_id=cl.id HAVING distance <= {$radius} order by distance asc";
            $alldoctorid = $this->ClinicLocation->query($sql);


            if (count($alldoctorid) > 0) {
                $alldoctor = array();
                $i = 0;
                foreach ($alldoctorid as $adoc) {

                    $docarray1 = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.id=' . $adoc['dl']['doctor_id'] . ' group by dc.id');
                    $alldoctor[] = $docarray1[0];
                    $i++;
                    if ($i == 3) {
                        break;
                    }
                }
                $response = array('success' => true, 'data' => $alldoctor);
            } else {
                $response = array('success' => false, 'data' => 'No Record Found!');
            }
            $this->set(array(
                'specilitydoc' => $response,
                '_serialize' => array('specilitydoc')
            ));
        } else if ($request_data->specialty == '' && $request_data->pincode == '') {
            $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id group by dc.id order by totalrate desc limit 3');

            $response = array('success' => true, 'data' => $alldoctor);
            $this->set(array(
                'specilitydoc' => $response,
                '_serialize' => array('specilitydoc')
            ));
        } else if ($request_data->specialty != '' && $request_data->pincode != '') {
            $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.specialty="' . $request_data->specialty . '" group by dc.id');

            $pdocarray = array();
            foreach ($alldoctor as $adoc) {
                $pdocarray[] = $adoc['dc']['id'];
            }

            $prepAddr = str_replace(' ', '+', $request_data->pincode);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;


            $sql = "SELECT dl.doctor_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl inner join doctor_locations as dl on dl.location_id=cl.id where dl.doctor_id IN (" . implode(',', array_unique($pdocarray)) . ") HAVING distance <= {$radius}  order by distance asc";
            $alldoctorid = $this->ClinicLocation->query($sql);
            if (count($alldoctorid) > 0) {
                $alldoctor = array();
                $i = 0;
                foreach ($alldoctorid as $adoc) {

                    $docarray1 = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.id=' . $adoc['dl']['doctor_id'] . ' group by dc.id');
                    $alldoctor[] = $docarray1[0];
                    $i++;
                    if ($i == 3) {
                        break;
                    }
                }
                $response = array('success' => true, 'data' => $alldoctor);
            } else {
                $response = array('success' => false, 'data' => 'No Record Found!');
            }
            $this->set(array(
                'specilitydoc' => $response,
                '_serialize' => array('specilitydoc')
            ));
        }
    }

    /**
     * Patient signup from byzzdoc site.
     */
    public function patientsignup() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->action == 'record_new_account') {
            $CardNumber = $this->CardNumber->find('first', array(
                'conditions' => array(
                    'CardNumber.card_number' => $request_data->card_number,
                    'CardNumber.clinic_id' => $request_data->clinic_id
                )
            ));
            if (!empty($CardNumber) && $CardNumber['CardNumber']['status'] == 1) {
                $Patient = $this->ClinicUser->find('first', array(
                    'conditions' => array(
                        'ClinicUser.card_number' => $request_data->card_number,
                        'ClinicUser.clinic_id' => $request_data->clinic_id
                    )
                ));
                if (empty($Patient)) {
                    $useralert = '';
                    $isverf = 1;
                    if (isset($request_data->parents_email)) {

                        $email = $request_data->email;
                        $parents_email = $request_data->parents_email;
                        if ($request_data->perent_check == 'own') {
                            $isverf = 1;
                        } else {
                            $isverf = 0;
                        }

                        $options['conditions'] = array('User.email' => $email, 'User.parents_email' => $parents_email, 'User.custom_date' => $request_data->dob);

                        $user1 = $this->User->find('first', $options);

                        if ($parents_email != '') {

                            $options_user2['conditions'] = array('User.email' => $email, 'User.parents_email' => $parents_email);
                            $user2 = $this->User->find('first', $options_user2);

                            $options_userch['conditions'] = array('User.email' => $parents_email, 'User.id !=' => $request_data->user_id);
                            $userch = $this->User->find('first', $options_userch);

                            $options_userchmore['conditions'] = array('OR' => array('User.email' => $parents_email, 'User.parents_email' => $parents_email), 'User.email !=' => $email, 'User.id !=' => $request_data->user_id);
                            $userchmore = $this->User->find('first', $options_userchmore);

                            if (empty($user2) && empty($userch) && empty($userchmore)) {

                                $useralert = '';
                            } else {
                                $useralert = 'aemail';
                            }
                        } else {

                            $options_user2['conditions'] = array('User.email' => $email, 'User.parents_email' => $parents_email, 'User.custom_date' => $request_data->dob);
                            $user2 = $this->User->find('first', $options_user2);

                            if (empty($user2)) {
                                $useralert = '';
                            } else {
                                $useralert = 'aemail';
                            }
                        }
                    } else {
                        $email = $request_data->email;
                        $parents_email = '';
                        $isverf = 1;

                        $options['conditions'] = array('User.email' => $email, 'User.custom_date' => $request_data->dob, 'User.blocked' => 1);
                        $user1 = $this->User->find('first', $options);
                        $date13year = date("Y-m-d H:i:s", strtotime("-18 year"));
                        $options_user['conditions'] = array('User.email' => $email, 'User.custom_date <' => $date13year);
                        $user = $this->User->find('first', $options_user);
                        if (empty($user)) {
                            $useralert = '';
                        } else {
                            $useralert = 'email';
                        }
                    }

                    if ($useralert == '') {

                        $Patients_array['User'] = array(
                            'custom_date' => $request_data->dob,
                            'email' => strtolower($email),
                            'parents_email' => strtolower($parents_email),
                            'first_name' => $request_data->first_name,
                            'last_name' => $request_data->last_name,
                            'customer_password' => md5($request_data->password),
                            'password' => $request_data->password,
                            'points' => 0,
                            'enrollment_stamp' => date('Y-m-d H:i:s'),
                            'facebook_id' => $request_data->facebook_id,
                            'is_facebook' => $request_data->is_facebook,
                            'status' => 1,
                            'is_verified' => $isverf,
                            'is_buzzydoc' => 1
                        );

                        $this->User->create();
                        $this->User->save($Patients_array);
                        $user_id = $this->User->getLastInsertId();
                        $clinicdetails = $this->Clinic->find('first', array(
                            'conditions' => array(
                                'Clinic.id' => $request_data->clinic_id
                            )
                        ));
                        $Patients_CU_array['ClinicUser'] = array('clinic_id' => $request_data->clinic_id,
                            'user_id' => $user_id,
                            'card_number' => $request_data->card_number,
                            'facebook_like_status' => 0
                        );
                        $this->ClinicUser->create();
                        $this->ClinicUser->save($Patients_CU_array);

                        $this->CardNumber->query("UPDATE `card_numbers` SET `status` = 2  WHERE `clinic_id` =" . $request_data->clinic_id . " and card_number='" . $request_data->card_number . "'");
                        $alltrans = $this->UnregTransaction->find('all', array(
                            'conditions' => array(
                                'UnregTransaction.user_id' => 0,
                                'UnregTransaction.card_number' => $request_data->card_number,
                                'UnregTransaction.clinic_id' => $request_data->clinic_id
                            )
                        ));
                        $firstamount = 0;
                        foreach ($alltrans as $newtran) {
                            $datatrans['user_id'] = $user_id;
                            $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                            $datatrans['card_number'] = $request_data->card_number;
                            $datatrans['first_name'] = $request_data->first_name;
                            $datatrans['last_name'] = $request_data->last_name;
                            $datatrans['promotion_id'] = $newtran['UnregTransaction']['promotion_id'];
                            $datatrans['amount'] = $newtran['UnregTransaction']['amount'];
                            $datatrans['activity_type'] = $newtran['UnregTransaction']['activity_type'];
                            $datatrans['authorization'] = $newtran['UnregTransaction']['authorization'];
                            $datatrans['clinic_id'] = $newtran['UnregTransaction']['clinic_id'];
                            $datatrans['date'] = $newtran['UnregTransaction']['date'];
                            $datatrans['status'] = $newtran['UnregTransaction']['status'];
                            $datatrans['is_buzzydoc'] = $clinicdetails['Clinic']['is_buzzydoc'];
                            $this->Transaction->create();
                            $this->Transaction->save($datatrans);
                            $this->UnregTransaction->deleteAll(array(
                                'UnregTransaction.id' => $newtran['UnregTransaction']['id'],
                                false
                            ));
                            if ($firstamount < 1) {
                                $firstamount = $newtran['UnregTransaction']['amount'];
                            }
                        }
                        $staff_access = $this->AccessStaff->getAccessForClinic($request_data->clinic_id);
                        if ($staff_access['AccessStaff']['self_registration'] == 1) {
                            $optionsdef['conditions'] = array('Promotion.clinic_id' => $clinicdetails['Clinic']['id'], 'Promotion.description' => 'Self Registration Bonus', 'Promotion.public' => 1);
                            $getdefaultpro = $this->Promotion->find('first', $optionsdef);
                            $datatransself['user_id'] = $user_id;
                            $datatransself['staff_id'] = 0;
                            $datatransself['card_number'] = $request_data->card_number;
                            $datatransself['first_name'] = $request_data->first_name;
                            $datatransself['last_name'] = $request_data->last_name;
                            $datatransself['promotion_id'] = $getdefaultpro['Promotion']['id'];
                            $datatransself['amount'] = $getdefaultpro['Promotion']['value'];
                            $datatransself['activity_type'] = 'N';
                            $datatransself['authorization'] = $getdefaultpro['Promotion']['description'];
                            $datatransself['clinic_id'] = $clinicdetails['Clinic']['id'];
                            $datatransself['date'] = date('Y-m-d H:i:s');
                            $datatransself['status'] = 'New';
                            $datatransself['is_buzzydoc'] = $clinicdetails['Clinic']['is_buzzydoc'];
                            $this->Transaction->create();
                            $this->Transaction->save($datatransself);
                            if ($firstamount < 1) {
                                $firstamount = $getdefaultpro['Promotion']['value'];
                            }
                        }
                        $getfirstTransaction = $this->Api->get_firsttransaction($user_id, $request_data->clinic_id);
                        if ($getfirstTransaction == 1 && $firstamount>0) {

                            $template_array = $this->Api->get_template(39);
                            $link1 = str_replace('[username]', $request_data->first_name, $template_array['content']);
                            $link = str_replace('[points]', $firstamount, $link1);
                            $link2 = str_replace('[clinic_name]', $clinicdetails['Clinic']['api_user'], $link);
                            $Emailnew = new CakeEmail(MAILTYPE);
                            $Emailnew->from(array(
                                SUPER_ADMIN_EMAIL => 'BuzzyDoc'
                            ));

                            $Emailnew->to($email);
                            $Emailnew->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Emailnew->viewVars(array(
                                'msg' => $link2
                            ));
                            $Emailnew->send();
                        }


                        $allpoints = $this->Transaction->find('first', array(
                            'conditions' => array(
                                'Transaction.user_id' => $user_id,
                                'Transaction.clinic_id' => $request_data->clinic_id,
                                'Transaction.is_buzzydoc' => $clinicdetails['Clinic']['is_buzzydoc']
                            ),
                            'fields' => array(
                                'SUM(Transaction.amount) AS points'
                            ),
                            'group' => array(
                                'Transaction.card_number'
                            )
                        ));

                        if ($allpoints[0]['points'] > 0) {
                            $newpoints = $allpoints[0]['points'];
                        } else {
                            $newpoints = 0;
                        }
                        if ($clinicdetails['Clinic']['is_buzzydoc'] == 1) {
                            $queryuser = 'update users set points=' . $newpoints . ' where id=' . $user_id;
                            $usersave = $this->User->query($queryuser);
                        } else {
                            $queryuser = 'update clinic_users set local_points=' . $newpoints . ' where user_id=' . $user_id . ' and clinic_id=' . $request_data->clinic_id;
                            $usersave = $this->ClinicUser->query($queryuser);
                        }
                        if ($user_id > 0) {
                            $Pfields = $this->ProfileField->find('all', array(
                                'conditions' => array('ProfileField.profile_field' => array('gender', 'phone', 'street1', 'street2', 'city', 'state', 'postal_code')
                                )
                            ));
                            $value = '';
                            foreach ($Pfields as $field) {
                                $records_pf_vl = array(
                                    "ProfileFieldUser" => array(
                                        "user_id" => $user_id,
                                        "profilefield_id" => $field['ProfileField']['id'],
                                        "value" => $request_data->$field['ProfileField']['profile_field'],
                                        "clinic_id" => 0
                                    )
                                );
                                $this->ProfileFieldUser->create();
                                $this->ProfileFieldUser->save($records_pf_vl);
                            }
                            if ($isverf == 1) { //user
                                $template_array = $this->Api->get_template(4);
                                $Email = new CakeEmail(MAILTYPE);
                                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                                $Email->to($email);
                                $link = str_replace('[first_name]', $request_data->first_name, $template_array['content']);
                                $link1 = str_replace('[username]', $email, $link);
                                $link2 = str_replace('[password]', $request_data->password, $link1);
                                $link3 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link2);
                                $sub = str_replace('[clinic_name]', $clinicdetails['Clinic']['api_user'], $template_array['subject']);
                                $Email->subject($sub)
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email->viewVars(array('msg' => $link3
                                ));
                                $Email->send();
                                $response = array('success' => true, 'data' => 'Sign Up completed, use your credentials for login.', 'user_id' => $user_id);
                            } else { //parent
                                $template_array = $this->Api->get_template(36);
                                $link = str_replace('[card_number]', $request_data->card_number, $template_array['content']);
                                $link1 = str_replace('[click_here]', '<a href="' . Buzzy_Name . "buzzydoc/login/verify/" . base64_encode($user_id) . '">Click Here</a>', $link);
                                $link2 = str_replace('[first_name]', $request_data->first_name, $link1);
                                $link3 = str_replace('[last_name]', $request_data->last_name, $link2);
                                $sub = str_replace('[clinic_name]', $clinicdetails['Clinic']['api_user'], $template_array['subject']);
                                $Email2 = new CakeEmail(MAILTYPE);
                                $Email2->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                                $Email2->to($email);
                                $Email2->subject($sub)
                                        ->template('buzzydocother')
                                        ->emailFormat('html');
                                $Email2->viewVars(array('msg' => $link3
                                ));
                                $Email2->send();
                                $response = array('success' => true, 'data' => 'Form submitted successfully. An email is sent to the parent\'s account for approval.');
                            }
                        } else {
                            $response = array('success' => false, 'data' => 'Due to some reason we cant proceed.Please Try agian later.');
                        }
                    } else if ($useralert == 'aemail') {
                        $response = array('success' => false, 'data' => 'Email or Username Already Exist');
                    } else {

                        $response = array('success' => false, 'data' => 'Email Already Exist');
                    }
                } else {
                    $response = array('success' => false, 'data' => 'Card Number Already Registered');
                }
            } else if (!empty($CardNumber) && $CardNumber['CardNumber']['status'] == 2) {
                $response = array('success' => false, 'data' => 'Card Number Already Registered');
            } else {
                $response = array('success' => false, 'data' => 'Card Number Not Exist');
            }
        } else {
            $clinic = $this->Clinic->find('first', array(
                'conditions' => array('Clinic.id' => $request_data->clinic_id
                )
            ));
            if (isset($request_data->parents_email)) {
                $email = $request_data->email;
                $pemail = $request_data->parents_email;
            } else {
                $email = $request_data->email;
            }
            $linkstr = "";
            if (isset($pemail)) {

                $link_str = '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . "/rewards/linkwithcard/" . base64_encode($email) . "/" . base64_encode($request_data->card_number) . "/" . base64_encode($pemail) . '">Link Url</a>';
            } else {
                $link_str = '<a href="' . rtrim($clinic['Clinic']['patient_url'], '/') . "/rewards/linkwithcard/" . base64_encode($email) . "/" . base64_encode($request_data->card_number) . '">Link Url</a>';
            }
            $link = str_replace('[link_url]', $link_str, $template_array['content']);
            $link1 = str_replace('[username]', $request_data->first_name, $link);
            $template_array = $this->Api->get_template(25);
            $subject = str_replace('[card_number]', $request_data->card_number, $template_array['subject']);
            $Email = new CakeEmail(MAILTYPE);
            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
            $Email->to($email);
            $Email->subject($subject)
                    ->template('buzzydocother')
                    ->emailFormat('html');
            $Email->viewVars(array('msg' => $link1
            ));
            $Email->send();
            $response = array('success' => true, 'data' => 'Check your email for linking.');
        }
        $this->set(array(
            'signupcheck' => $response,
            '_serialize' => array('signupcheck')
        ));
    }

    /**
     * Update Patient profile and change password from buzzdoc site.
     */
    public function editprofile() {

        $request_data = $this->request->input('json_decode');
        if ($this->request->is('post')) {
            $useralert = '';
            if (isset($request_data->parents_email)) {

                $email = $request_data->email;
                $parents_email = $request_data->parents_email;
                $isverf = 0;
                if ($parents_email != '') {

                    $options_user2['conditions'] = array('User.email' => $email, 'User.parents_email' => $parents_email, 'User.id !=' => $request_data->user_id);
                    $user2 = $this->User->find('first', $options_user2);
                    $options_userch['conditions'] = array('User.email' => $parents_email, 'User.id !=' => $request_data->user_id);
                    $userch = $this->User->find('first', $options_userch);

                    $options_userchmore['conditions'] = array('OR' => array('User.email' => $parents_email, 'User.parents_email' => $parents_email), 'User.email !=' => $email, 'User.id !=' => $request_data->user_id);
                    $userchmore = $this->User->find('first', $options_userchmore);

                    if (empty($user2) && empty($userch) && empty($userchmore)) {
                        $useralert = '';
                    } else {
                        $useralert = 'aemail';
                    }
                } else {
                    $options_user2['conditions'] = array('User.email' => $email, 'User.parents_email' => $parents_email, 'User.custom_date' => $request_data->dob, 'User.id !=' => $request_data->user_id);
                    $user2 = $this->User->find('first', $options_user2);

                    if (empty($user2)) {
                        $useralert = '';
                    } else {
                        $useralert = 'aemail';
                    }
                }
            } else {
                $email = $request_data->email;
                $parents_email = '';
                $isverf = 1;

                $date13year = date("Y-m-d H:i:s", strtotime("-18 year"));

                $options_user['conditions'] = array('User.email' => $email, 'User.custom_date <' => $date13year, 'User.id !=' => $request_data->user_id);
                $user = $this->User->find('first', $options_user);
                if (empty($user)) {
                    $useralert = '';
                } else {
                    $useralert = 'email';
                }
            }
            if ($useralert == '') {
                if ($request_data->new_password == '') {
                    $Patients_array['User'] = array(
                        'id' => $request_data->user_id,
                        'custom_date' => $request_data->dob,
                        'email' => strtolower($email),
                        'parents_email' => strtolower($parents_email),
                        'first_name' => $request_data->first_name,
                        'last_name' => $request_data->last_name,
                    );
                } else {
                    $Patients_array['User'] = array(
                        'id' => $request_data->user_id,
                        'custom_date' => $request_data->dob,
                        'email' => strtolower($email),
                        'parents_email' => strtolower($parents_email),
                        'first_name' => $request_data->first_name,
                        'last_name' => $request_data->last_name,
                        'customer_password' => md5($request_data->new_password),
                        'password' => $request_data->new_password
                    );
                }

                $this->User->create();
                $this->User->save($Patients_array);
                $user_id = $request_data->user_id;
                $Pfields = $this->ProfileField->find('all', array(
                    'conditions' => array('ProfileField.profile_field' => array('gender', 'phone', 'street1', 'street2', 'city', 'state', 'postal_code')
                    )
                ));
                foreach ($Pfields as $field) {
                    $ProfileField_val = $this->ProfileFieldUser->query("select * from  `profile_field_users` where (clinic_id='0' or clinic_id='') and user_id=" . $user_id . " and profilefield_id=" . $field['ProfileField']['id']);

                    $records_pf_vl = array(
                        "ProfileFieldUser" => array(
                            "user_id" => $user_id,
                            "profilefield_id" => $field['ProfileField']['id'],
                            "value" => $request_data->$field['ProfileField']['profile_field'],
                            "clinic_id" => 0
                        )
                    );

                    if (empty($ProfileField_val)) {
                        $this->ProfileFieldUser->create();
                        $this->ProfileFieldUser->save($records_pf_vl);
                    } else {
                        $this->ProfileFieldUser->query("UPDATE `profile_field_users` SET `value` = '" . $request_data->$field['ProfileField']['profile_field'] . "' WHERE `profilefield_id` = " . $field['ProfileField']['id'] . " AND `user_id` =" . $user_id . " AND clinic_id=0");
                    }
                }


                $response = array('success' => true, 'data' => 'Profile saved successfully', 'state' => $request_data->state, 'city' => $request_data->city);
            } else if ($useralert == 'aemail') {
                $response = array('success' => false, 'data' => 'Email or Username Already Exist');
            } else {

                $response = array('success' => false, 'data' => 'Email Already Exist');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }

        $this->set(array(
            'edit' => $response,
            '_serialize' => array('edit')
        ));
    }

    /**
     * Change profile image for patient.
     */
    public function editprofileimage() {

        if ($this->request->is('post')) {
            if (isset($this->request->data['user_id'])) {
                $image = $this->request->params['form']['file_data'];
                if ($image['name'] != '') {
                    //allowed image types
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = "profile";
                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    if (in_array($image['type'], $imageTypes)) {
                        //check if there wasn't errors uploading file on serwer
                        if ($image['error'] == 0) {
                            //image file name
                            $imageName = time() . '_' . $image['name'];
                            //check if file exists in upload folder
                            if (file_exists($uploadPath . '/' . $imageName)) {
                                //create full filename with timestamp
                                unlink($uploadPath . '/' . $imageName);
                            }
                            //create full path with image name
                            $full_image_path = $uploadPath . '/' . $imageName;
                            //upload image to upload folder

                            if (move_uploaded_file($image['tmp_name'], $full_image_path)) {
                                $responsePath = $this->CakeS3->putObject($full_image_path, 'img/' . $uploadFolder . '/' . $imageName, $this->CakeS3->permission('public_read_write'));

                                $userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/userdetail/' . $this->request->data['user_id'] . '.json'));
                                if ($userdetails->userdetail->success == 1) {
                                    $this->Session->write('userdetail', $userdetails->userdetail->data);
                                }
                                $this->User->save(array('id' => $this->request->data['user_id'], 'profile_img_url' => 'img/' . $uploadFolder . '/' . $imageName));
                                $response = array('success' => true, 'data' => $responsePath['url']);
                            } else {

                                $response = array('success' => false, 'data' => 'There was a problem uploading file. Please try again.');
                            }
                        } else {

                            $response = array('success' => false, 'data' => 'Error uploading file.');
                        }
                    } else {

                        $response = array('success' => false, 'data' => 'Unacceptable file type');
                    }
                }
            } else {
                $response = array('success' => false, 'data' => 'Bad Request');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'editimage' => $response,
            '_serialize' => array('editimage')
        ));
    }

    /**
     * Patient login from buzzydoc site.
     */
    public function patientlogin() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->username != '' && $request_data->password != '') {

            $user = $this->User->query('SELECT * FROM users WHERE (email = "' . $request_data->username . '" or parents_email = "' . $request_data->username . '") AND BINARY customer_password = md5("' . $request_data->password . '")');
            if (!empty($user)) {
                if ($user[0]['users']['is_verified'] == 1 && $user[0]['users']['blocked'] != 1) {
                    $response = array('success' => true, 'data' => $user[0]['users']['id']);
                } else if ($user[0]['users']['is_verified'] == 1 && $user[0]['users']['blocked'] == 1) {
                    $response = array('success' => false, 'data' => 'Your Account has been blocked.Please contact to buzzydoc admin.');
                } else {
                    $response = array('success' => false, 'data' => 'Waiting on parent\'s email confirmation.');
                }
            } else {

                $response = array('success' => false, 'data' => 'Invalid Credentials');
            }
        } else {

            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'logincheck' => $response,
            '_serialize' => array('logincheck')
        ));
    }

    /**
     * Function to forgot password for patient at buzzydoc site.
     */
    public function forgotpassword() {
        $request_data = $this->request->input('json_decode');
        $check = 1;
        if ($request_data->email != '') {

            if ($request_data->cardnumber == 'No') {

                $user = $this->User->find('first', array(
                    'conditions' => array('User.email' => $request_data->email)
                ));
                $userch = $this->User->find('all', array(
                    'conditions' => array('User.email' => $request_data->email)
                ));
                if (count($userch) == 1) {
                    $check = 1;
                } else {
                    $check = 0;
                }
            } else {
                if ($request_data->cardnumber != '' && $request_data->cardnumber != 'undefined') {
                    $crdnum = explode('_', $request_data->cardnumber);

                    if (count($crdnum) > 1) {

                        $user = $this->User->find('first', array(
                            'conditions' => array('User.email' => $request_data->email, 'is_buzzydoc' => 1, 'User.id' => $crdnum[1])
                        ));
                    } else {
                        $user = $this->User->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'clinic_users',
                                    'alias' => 'clinic_users',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'clinic_users.user_id = User.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'clinic_users.card_number' => $request_data->cardnumber,
                                'User.email' => $request_data->email
                            ),
                            'fields' => array('User.*')
                        ));
                    }
                    $check = 1;
                } else {
                    $check = 0;
                }
            }
            if ($check == 1) {
                if (!empty($user)) {
                    if ($user['User']['first_name'] != '' || $user['User']['last_name'] != '') {
                        $username = $user['User']['first_name'];
                    } else {
                        $username = $user['User']['email'];
                    }

                    $password = mt_rand(100000, 999999);
                    $query = 'update users set customer_password=md5(' . $password . '),password=' . $password . ' where id=' . $user['User']['id'];
                    $unrpass = $this->User->query($query);

                    $template_array = $this->Api->get_template(6);
                    $link = str_replace('[first_name]', $username, $template_array['content']);
                    $link1 = str_replace('[username]', $request_data->email, $link);
                    $link2 = str_replace('[password]', $password, $link1);
                    $link3 = str_replace('[click_here]', '<a href="' . Buzzy_Name . '">Click Here</a>', $link2);
                    $sub = str_replace('[clinic_name]', '', $template_array['subject']);
                    $Email = new CakeEmail(MAILTYPE);
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                    $Email->to($user['User']['email']);
                    $Email->subject($sub)
                            ->template('buzzydocother')
                            ->emailFormat('html');
                    $Email->viewVars(array('msg' => $link3
                    ));
                    $Email->send();
                    $response = array('success' => true, 'data' => 'Password was successfully sent to your email. Please use the new credentials for login.');
                } else {

                    $response = array('success' => true, 'data' => 'Email does not exist.');
                }
            } else {

                $response = array('success' => true, 'data' => 'Select User.');
            }
        } else {

            $response = array('success' => true, 'data' => 'Invalid Credentials');
        }
        $this->set(array(
            'logincheck' => $response,
            '_serialize' => array('logincheck')
        ));
    }

    /**
     * Characteristic like by patient for any pratice and doctor.
     */
    public function patientcharacteristiclike() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->user_id != '' && $request_data->characteristic_id != '' && $request_data->doctor_id != '') {
            $options['conditions'] = array('CharacteristicInsuranceLike.user_id' => $request_data->user_id, 'CharacteristicInsuranceLike.characteristic_insurance_id' => $request_data->characteristic_id, 'CharacteristicInsuranceLike.doctor_id' => $request_data->doctor_id);

            $CharacteristicInsurance = $this->CharacteristicInsuranceLike->find('first', $options);
            if (empty($CharacteristicInsurance)) {

                $characteristiclike_array['CharacteristicInsuranceLike'] = array(
                    'user_id' => $request_data->user_id,
                    'doctor_id' => $request_data->doctor_id,
                    'characteristic_insurance_id' => $request_data->characteristic_id
                );

                $this->CharacteristicInsuranceLike->create();
                $this->CharacteristicInsuranceLike->save($characteristiclike_array);
                $response = array('success' => true, 'data' => 'Like Successfully');
            } else {
                $this->CharacteristicInsuranceLike->query('DELETE FROM `characteristic_insurance_likes` WHERE `user_id` = ' . $request_data->user_id . ' and characteristic_insurance_id=' . $request_data->characteristic_id . ' and doctor_id=' . $request_data->doctor_id);
                $response = array('success' => true, 'data' => 'UnLike Successfully');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'characteristiclike' => $response,
            '_serialize' => array('characteristiclike')
        ));
    }

    /**
     * Activity to like clinic by patient.
     */
    public function activitylike() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->user_id != '' && $request_data->clinic_id != '') {
            $options['conditions'] = array('SaveLike.user_id' => $request_data->user_id, 'SaveLike.clinic_id' => $request_data->clinic_id);

            $Like = $this->SaveLike->find('first', $options);
            if (empty($Like)) {

                $like_array['SaveLike'] = array(
                    'user_id' => $request_data->user_id,
                    'clinic_id' => $request_data->clinic_id,
                    'doctor_id' => 0,
                    'date' => date('Y-m-d H:i:s')
                );

                $this->SaveLike->create();
                $this->SaveLike->save($like_array);
                $response = array('success' => true, 'data' => 'Like Successfully');
            } else {
                $this->SaveLike->query('DELETE FROM `save_likes` WHERE `user_id` = ' . $request_data->user_id . ' and clinic_id=' . $request_data->clinic_id);
                $response = array('success' => true, 'data' => 'UnLike Successfully');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'like' => $response,
            '_serialize' => array('like')
        ));
    }

    /**
     * Activity to save doctor by patient.
     */
    public function activitysavedoc() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->user_id != '' && $request_data->doctor_id != '') {
            $options['conditions'] = array('SaveLike.user_id' => $request_data->user_id, 'SaveLike.doctor_id' => $request_data->doctor_id);

            $Like = $this->SaveLike->find('first', $options);
            if (empty($Like)) {

                $like_array['SaveLike'] = array(
                    'user_id' => $request_data->user_id,
                    'doctor_id' => $request_data->doctor_id,
                    'clinic_id' => 0,
                    'date' => date('Y-m-d H:i:s')
                );

                $this->SaveLike->create();
                $this->SaveLike->save($like_array);
                $response = array('success' => true, 'data' => 'Save Successfully');
            } else {
                $this->SaveLike->query('DELETE FROM `save_likes` WHERE `user_id` = ' . $request_data->user_id . ' and doctor_id=' . $request_data->doctor_id);
                $response = array('success' => true, 'data' => 'UnSave Successfully');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'save' => $response,
            '_serialize' => array('save')
        ));
    }

    /**
     * Rated and review a doctor and pratice by patient.
     */
    public function rateReview() {
        $request_data = $this->request->input('json_decode');
        if (isset($request_data->doctor_id) && $request_data->user_id != '' && $request_data->rate != '' && $request_data->doctor_id != '') {
            $options['conditions'] = array('RateReview.user_id' => $request_data->user_id, 'RateReview.doctor_id' => $request_data->doctor_id, 'RateReview.rate !=' => 0);

            $rate = $this->RateReview->find('first', $options);

            if (empty($rate)) {
                $ratereview_array['RateReview'] = array(
                    'user_id' => $request_data->user_id,
                    'clinic_id' => 0,
                    'doctor_id' => $request_data->doctor_id,
                    'rate' => $request_data->rate,
                    'created_on' => date('Y-m-d H:i:s')
                );

                $this->RateReview->create();
                $this->RateReview->save($ratereview_array);
                $response = array('success' => true, 'data' => 'Rated Successfully');
            } else {
                $response = array('success' => false, 'data' => 'Allready rated.');
            }
        } else if (isset($request_data->clinic_id) && $request_data->user_id != '' && $request_data->rate != '' && $request_data->clinic_id != '') {
            $options['conditions'] = array('RateReview.user_id' => $request_data->user_id, 'RateReview.clinic_id' => $request_data->clinic_id, 'RateReview.rate !=' => 0);

            $rate = $this->RateReview->find('first', $options);
            if (empty($rate)) {
                $ratereview_array['RateReview'] = array(
                    'user_id' => $request_data->user_id,
                    'clinic_id' => $request_data->clinic_id,
                    'doctor_id' => 0,
                    'rate' => $request_data->rate,
                    'created_on' => date('Y-m-d H:i:s')
                );

                $this->RateReview->create();
                $this->RateReview->save($ratereview_array);
                $response = array('success' => true, 'data' => 'Rated Successfully');
            } else {
                $response = array('success' => false, 'data' => 'Allready rate.');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'rate' => $response,
            '_serialize' => array('rate')
        ));
    }

    /**
     * Shedule an appointment for visit pratice by patient.
     */
    public function appointment() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->user_id != '') {
            $options7['conditions'] = array('User.id' => $request_data->user_id);
            $user = $this->User->find('first', $options7);

            if (isset($request_data->doctor_id) && $request_data->doctor_id != 0) {

                $options9['conditions'] = array('Doctor.id' => $request_data->doctor_id);
                $Doctors = $this->Doctor->find('first', $options9);

                $options5['conditions'] = array('Clinic.id' => $Doctors['Doctor']['clinic_id']);
                $options5['fields'] = array('Clinic.id,Clinic.display_name,Clinic.patient_url,Clinic.staff_url,Clinic.facebook_url,Clinic.pintrest_url,Clinic.twitter_url,Clinic.instagram_url,Clinic.patient_logo_url,Clinic.buzzydoc_logo_url,Clinic.google_url,Clinic.yelp_url,Clinic.youtube_url,Clinic.healthgrade_url,Clinic.api_user');
                $Clinics = $this->Clinic->find('first', $options5);
                $options8['conditions'] = array('Staff.clinic_id' => $Doctors['Doctor']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.is_prime' => 1);
                $Staff = $this->Staff->find('first', $options8);
                $stemail = '';
                $stname = '';
                if (!empty($Staff)) {
                    $stemail = $Staff['Staff']['staff_email'];
                    $stname = $Staff['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $Doctors['Doctor']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.staff_role' => 'Doctor');
                    $Staff1 = $this->Staff->find('first', $options9);
                    $stemail = $Staff1['Staff']['staff_email'];
                    $stname = $Staff1['Staff']['staff_id'];
                }

                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $Doctors['Doctor']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Administrator', 'Staff.staff_role' => 'A'));
                    $Staff2 = $this->Staff->find('first', $options9);
                    $stemail = $Staff2['Staff']['staff_email'];
                    $stname = $Staff2['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options10['conditions'] = array('Staff.clinic_id' => $Doctors['Doctor']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Manager', 'Staff.staff_role' => 'M'));
                    $Staff3 = $this->Staff->find('first', $options10);
                    $stemail = $Staff3['Staff']['staff_email'];
                    $stname = $Staff3['Staff']['staff_id'];
                }
            } else {


                $options5['conditions'] = array('Clinic.id' => $request_data->clinic_id);
                $options5['fields'] = array('Clinic.id,Clinic.display_name,Clinic.patient_url,Clinic.staff_url,Clinic.facebook_url,Clinic.pintrest_url,Clinic.twitter_url,Clinic.instagram_url,Clinic.patient_logo_url,Clinic.buzzydoc_logo_url,Clinic.google_url,Clinic.yelp_url,Clinic.youtube_url,Clinic.healthgrade_url,Clinic.api_user');
                $Clinics = $this->Clinic->find('first', $options5);
                $options8['conditions'] = array('Staff.clinic_id' => $request_data->clinic_id, 'Staff.staff_email !=' => '', 'Staff.is_prime' => 1);
                $Staff = $this->Staff->find('first', $options8);

                $stemail = '';
                $stname = '';
                if (!empty($Staff)) {
                    $stemail = $Staff['Staff']['staff_email'];
                    $stname = $Staff['Staff']['staff_id'];
                }

                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $request_data->clinic_id, 'Staff.staff_email !=' => '', 'Staff.staff_role' => 'Doctor');
                    $Staff1 = $this->Staff->find('first', $options9);
                    $stemail = $Staff1['Staff']['staff_email'];
                    $stname = $Staff1['Staff']['staff_id'];
                }

                if ($stemail == '') {
                    $options9['conditions'] = array('Staff.clinic_id' => $request_data->clinic_id, 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Administrator', 'Staff.staff_role' => 'A'));
                    $Staff2 = $this->Staff->find('first', $options9);
                    $stemail = $Staff2['Staff']['staff_email'];
                    $stname = $Staff2['Staff']['staff_id'];
                }
                if ($stemail == '') {
                    $options10['conditions'] = array('Staff.clinic_id' => $request_data->clinic_id, 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Manager', 'Staff.staff_role' => 'M'));
                    $Staff3 = $this->Staff->find('first', $options10);
                    $stemail = $Staff3['Staff']['staff_email'];
                    $stname = $Staff3['Staff']['staff_id'];
                }
            }

            if ($stemail == '') {
                $stemail = SUPER_ADMIN_EMAIL_STAFF;
            }

            $ratereview_array['Appointment'] = array(
                'user_id' => $request_data->user_id,
                'clinic_id' => $request_data->clinic_id,
                'doctor_id' => $request_data->doctor_id,
                'reason' => $request_data->reason,
                'appointment_date' => date('Y-m-d H:i:s'),
                'status' => 0,
                'created_on' => date('Y-m-d H:i:s')
            );
            $this->Appointment->create();
            $this->Appointment->save($ratereview_array);

            $template_array = $this->Api->get_template(7);
            $link = str_replace('[staff_name]', $stname, $template_array['content']);
            $link1 = str_replace('[username]', $user['User']['first_name'] . ' ' . $user['User']['last_name'], $link);
            $link2 = str_replace('[emailid]', $user['User']['email'], $link1);
            $Email = new CakeEmail(MAILTYPE);

            $Email->from(array($user['User']['email'] => 'BuzzyDoc'));

            $Email->to($stemail);
            $Email->subject($template_array['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');

            $Email->viewVars(array(
                'msg' => $link2
            ));
            $Email->send();
            $template_array1 = $this->Api->get_template(8);
            $link_new = str_replace('[username]', $user['User']['first_name'] . ' ' . $user['User']['last_name'], $template_array1['content']);
            $Email1 = new CakeEmail(MAILTYPE);

            $Email1->from(array($stemail => 'BuzzyDoc'));

            $Email1->to($user['User']['email']);
            $Email1->subject($template_array1['subject'])
                    ->template('buzzydocother')
                    ->emailFormat('html');

            $Email1->viewVars(array(
                'msg' => $link_new
            ));
            $Email1->send();


            $response = array('success' => true, 'data' => 'Appointment Schedule Successfully');
        } else {
            $response = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'appointment' => $response,
            '_serialize' => array('appointment')
        ));
    }

    /**
     * Refer a clinic to friend by buzzydoc patient.
     */
    public function recommend() {
        $request_data = $this->request->input('json_decode');
        if ($request_data->user_id != '' && $request_data->user_email != '' && $request_data->first_name != '' && $request_data->last_name != '' && $request_data->message != '' && $request_data->email != '' && ($request_data->clinic_id != '' || $request_data->doctor_id != '')) {

            if ($request_data->clinic_id == 0) {
                $options['conditions'] = array('Doctor.id' => $request_data->doctor_id);
                $Doctors = $this->Doctor->find('first', $options);
                $options5['conditions'] = array('Clinic.id' => $Doctors['Doctor']['clinic_id']);

                $Clinics = $this->Clinic->find('first', $options5);
                $clinicid = $Clinics['Clinic']['id'];
            } else {
                $clinicid = $request_data->clinic_id;
                $options5['conditions'] = array('Clinic.id' => $clinicid);

                $Clinics = $this->Clinic->find('first', $options5);
            }

            $users_field = $this->User->find('first', array('conditions' => array('OR' => array('User.email' => $request_data->email, 'User.parents_email' => $request_data->email))));
            if (empty($users_field)) {

                $Refers_array['Refer'] = array('card_number' => '', 'first_name' => $request_data->first_name, 'last_name' => $request_data->last_name, 'email' => $request_data->email, 'message' => $request_data->message, 'user_id' => $request_data->user_id, 'clinic_id' => $clinicid, 'status' => 'Pending', 'doctor_id' => $request_data->doctor_id, 'refdate' => date('Y-m-d H:i:s'));
                $this->Refer->create();
                $this->Refer->save($Refers_array);
                $ref_id = $this->Refer->getLastInsertId();
                $template_array_red = $this->Api->save_notification($Refers_array['Refer'], 4, $ref_id);
                $refpromotion = $this->Refpromotion->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'clinic_promotions',
                            'alias' => 'ClinicPromotion',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ClinicPromotion.promotion_id = Refpromotion.id'
                            )
                        )),
                    'conditions' => array(
                        'ClinicPromotion.clinic_id' => $clinicid
                    )
                ));
                $template_array = $this->Api->get_template(9);

                $Email = new CakeEmail(MAILTYPE);
                if (empty($request_data->user_email)) {
                    $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                } else {
                    $Email->from(array($request_data->user_email => 'BuzzyDoc'));
                }
                $Email->to($request_data->email);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $promotion = '<br>';
                if (!empty($refpromotion)) {
                    foreach ($refpromotion as $refp) {
                        $promotion.= $refp['Refpromotion']['promotion_area'] . '<br>';
                    }
                }
                $link = str_replace('[accept_link]', "<a href='" . rtrim($Clinics['Clinic']['patient_url'], '/') . "/rewards/lead/" . base64_encode($ref_id) . "' style='background: none repeat scroll 0 0 #2FB888;color: #FFFFFF;display: block;margin: 10px 0 0;padding: 10px;text-decoration: none;width: 42%;'>SURE I'LL ACCEPT THIS REFERRAL!</a>" . $promotion, $template_array['content']);
                $link1 = str_replace('[description]', $request_data->message, $link);
                $link2 = str_replace('[username]', $request_data->first_name, $link1);
                $link3 = str_replace('[clinic_name]', $Clinics['Clinic']['api_user'], $link2);
                $Email->viewVars(array('msg' => $link3
                ));
                $Email->send();

                $response = array('success' => true, 'data' => 'Recommended Successfully');
            } else {
                $response = array('success' => true, 'data' => 'Already Recommended');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bed Request');
        }

        $this->set(array(
            'Recommend' => $response,
            '_serialize' => array('Recommend')
        ));
    }

    /**
     * Place an order to amazon and tango by patient.
     */
    public function placeAnOrder() {
        $request_data = $this->request->input('json_decode');

        $user_id = $request_data->user_id;
        $sku = $request_data->sku;
        $amount = $request_data->amount / 50;
        $clinic = $request_data->clinic_id;
        if (!empty($user_id) && !empty($sku) && !empty($amount)) {
            $Accountdetail = $this->TangoAccount->find('first');
            if ($Accountdetail['TangoAccount']['available_balance'] > $amount) {
                if ($clinic > 0) {
                    $clinic_id = $clinic;
                    $user = $this->User->find('first', array(
                        'joins' => array(
                            array(
                                'table' => 'clinic_users',
                                'alias' => 'ClinicUser',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'ClinicUser.user_id = User.id'
                                )
                            )
                        ),
                        'conditions' => array(
                            'User.id' => $user_id,
                            'ClinicUser.clinic_id' => $clinic
                        ),
                        'fields' => array('ClinicUser.*', 'User.*')
                    ));
                } else {
                    $clinic_id = 0;
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $user_id,
                        )
                    ));
                }
                if ($clinic > 0) {
                    $response_data = array('success' => true, 'data' => 'Place an order Successfully');
                } else {
                    $staffId = null;
                    if ($request_data->staff_id) {
                        $staffId = $request_data->staff_id;
                    }
                    $ratereview_array['Transaction'] = array(
                        'user_id' => $user_id,
                        'staff_id' => $staffId,
                        'clinic_id' => $clinic_id,
                        'first_name' => $user['User']['first_name'],
                        'last_name' => $user['User']['last_name'],
                        'activity_type' => 'Y',
                        'authorization' => $sku,
                        'status' => 'Redeemed',
                        'date' => date('Y-m-d H:i:s'),
                        'is_buzzydoc' => 1
                    );
                    $this->Transaction->create();
                    $this->Transaction->save($ratereview_array);
                    $transId = $this->Transaction->getInsertID();

                    $getglbpoint = $this->Transaction->find('all', array(
                        'conditions' => array(
                            'Transaction.user_id' => $user_id,
                            'Transaction.is_buzzydoc' => 1,
                            'Transaction.clinic_id !=' => 0
                        ),
                        'group' => array('Transaction.clinic_id'),
                        'fields' => array('sum(Transaction.amount) AS total', 'Transaction.clinic_id', 'Transaction.user_id')
                    ));

                    $totalbaltopay = 0;
                    $clinicfautid = '';
                    foreach ($getglbpoint as $glbpt) {

                        $getglberedem = $this->GlobalRedeem->find('first', array(
                            'conditions' => array(
                                'GlobalRedeem.clinic_id' => $glbpt['Transaction']['clinic_id'],
                                'GlobalRedeem.user_id' => $glbpt['Transaction']['user_id']
                            ),
                            'fields' => array('sum(GlobalRedeem.amount) AS total,GlobalRedeem.clinic_id')
                        ));

                        $paytoclinic = $glbpt[0]['total'];
                        if ($getglberedem[0]['total'] != '') {
                            $paytoclinic = $paytoclinic + $getglberedem[0]['total'];
                        }
                        if ($paytoclinic > 0) {
                            $options8['conditions'] = array('Staff.clinic_id' => $glbpt['Transaction']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.redemption_mail' => 1);
                            $Staff = $this->Staff->find('first', $options8);

                            $stemail = '';
                            $stname = '';
                            if (!empty($Staff)) {
                                $stemail = $Staff['Staff']['staff_email'];
                                $stname = $Staff['Staff']['staff_id'];
                            }

                            if ($stemail == '') {
                                $options9['conditions'] = array('Staff.clinic_id' => $glbpt['Transaction']['clinic_id'], 'Staff.staff_email !=' => '', 'Staff.staff_role' => 'Doctor');
                                $Staff1 = $this->Staff->find('first', $options9);
                                $stemail = $Staff1['Staff']['staff_email'];
                                $stname = $Staff1['Staff']['staff_id'];
                            }

                            if ($stemail == '') {
                                $options9['conditions'] = array('Staff.clinic_id' => $glbpt['Transaction']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Administrator', 'Staff.staff_role' => 'A'));
                                $Staff2 = $this->Staff->find('first', $options9);
                                $stemail = $Staff2['Staff']['staff_email'];
                                $stname = $Staff2['Staff']['staff_id'];
                            }
                            if ($stemail == '') {
                                $options10['conditions'] = array('Staff.clinic_id' => $glbpt['Transaction']['clinic_id'], 'Staff.staff_email !=' => '', 'OR' => array('Staff.staff_role' => 'Manager', 'Staff.staff_role' => 'M'));
                                $Staff3 = $this->Staff->find('first', $options10);
                                $stemail = $Staff3['Staff']['staff_email'];
                                $stname = $Staff3['Staff']['staff_id'];
                            }


                            if ($stemail == '') {
                                $stemail = SUPER_ADMIN_EMAIL_STAFF;
                            }


                            $options['conditions'] = array('Clinic.id' => $glbpt['Transaction']['clinic_id']);
                            $options['fields'] = array('Clinic.minimum_deposit', 'Clinic.api_user', 'Clinic.is_buzzydoc', 'Clinic.id');
                            $minimumdeposit = $this->Clinic->find('first', $options);
                            $threshold = $minimumdeposit['Clinic']['minimum_deposit'] / 2;
                            $options4['conditions'] = array('Invoice.clinic_id' => $glbpt['Transaction']['clinic_id']);
                            $options4['order'] = array('Invoice.payed_on desc');
                            $findlastpay = $this->Invoice->find('first', $options4);
                            $current_pay = $paytoclinic / 50;
                            $current_bal = $findlastpay['Invoice']['current_balance'] - $current_pay;
                            $creditsuccess = 1;

                            if ($threshold >= $current_bal && $minimumdeposit['Clinic']['is_buzzydoc'] == 1) {
                                if ($current_bal <= 0) {
                                    $cb = explode('-', $current_bal);
                                    $amountpay = $cb[1] + $threshold + 1;
                                    $curnbal = $threshold + 1;
                                } else {
                                    $amountpay = $threshold;
                                    $curnbal = $threshold + $current_bal;
                                }
                                $transactionFee = .35 + $amountpay * (.75 / 100);

                                $totalcredit1 = $amountpay + $transactionFee;
                                $totalcredit = number_format($totalcredit1, 2, '.', '');
                                $paydet['conditions'] = array('PaymentDetail.clinic_id' => $glbpt['Transaction']['clinic_id']);
                                $getpayemntdetails = $this->PaymentDetail->find('first', $paydet);
                                $transaction = new AuthorizeNetTransaction;
                                $transaction->amount = $totalcredit;
                                $transaction->customerProfileId = $getpayemntdetails['PaymentDetail']['customer_account_id'];
                                $transaction->customerPaymentProfileId = $getpayemntdetails['PaymentDetail']['customer_account_profile_id'];

                                $transaction_id = mt_rand(100000, 999999);
                                $lineItem = new AuthorizeNetLineItem;
                                $lineItem->itemId = $transaction_id;
                                $lineItem->name = $sku;
                                $lineItem->description = "Amazon gift card";
                                $lineItem->quantity = "1";
                                $lineItem->unitPrice = $amountpay;
                                $lineItem->taxable = "true";
                                $transaction->lineItems[] = $lineItem;
                                $request = new AuthorizeNetCIM;
                                $response = $request->createCustomerProfileTransaction("AuthCapture", $transaction);


                                if ($response->xml->messages->message->code == 'I00001') {
                                    $transactionResponse = $response->getTransactionResponse();
                                    $trnsid = $transactionResponse->transaction_id;
                                    $date2 = date("Y-m-d H:i:s", strtotime("+20 second"));
                                    $Invoice_array['Invoice'] = array(
                                        'clinic_id' => $glbpt['Transaction']['clinic_id'],
                                        'amount' => $amountpay,
                                        'transaction_fee' => $transactionFee,
                                        'invoice_id' => $trnsid,
                                        'mode' => 'Credit',
                                        'current_balance' => $curnbal,
                                        'payed_on' => $date2,
                                        'status' => 1
                                    );
                                    $this->Invoice->create();
                                    $this->Invoice->save($Invoice_array);
                                    $template_array = $this->Api->get_template(10);
                                    $link = str_replace('[staff_name]', $stname, $template_array['content']);
                                    $link1 = str_replace('[credit_amount]', $amountpay, $link);
                                    $link2 = str_replace('[current_balance]', $curnbal, $link1);
                                    $Email2 = new CakeEmail(MAILTYPE);

                                    $Email2->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                                    $Email2->to($stemail);
                                    $Email2->subject($template_array['subject'])
                                            ->template('buzzydocother')
                                            ->emailFormat('html');

                                    $Email2->viewVars(array('msg' => $link2
                                    ));
                                    $Email2->send();

                                    $creditsuccess = 1;
                                    $update_payment['PaymentDetail'] = array(
                                        'id' => $getpayemntdetails['PaymentDetail']['id'],
                                        'reminder_date' => '0000-00-00',
                                        'reminder_count' => 0
                                    );
                                    $this->PaymentDetail->save($update_payment);
                                } else {

                                    $failed_payment['FailedPayment'] = array(
                                        'clinic_id' => $minimumdeposit['Clinic']['id'],
                                        'user_id' => $user_id,
                                        'subject' => $response->xml->messages->message->text,
                                        'description' => 'Practice balance reached the threshold amount.',
                                        'date' => date('Y-m-d H:i:s')
                                    );
                                    $this->FailedPayment->create();
                                    $this->FailedPayment->save($failed_payment);

                                    if ($current_bal < 0) {
                                        $clinicfautid.=$minimumdeposit['Clinic']['api_user'] . ',';
                                        $creditsuccess = 0;
                                    } else {
                                        $creditsuccess = 1;
                                    }
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

                                        $send_mail = 1;
                                    }
                                    if ($send_mail == 1) {
                                        $template_array = $this->Api->get_template(11);
                                        $link = str_replace('[staff_name]', $stname, $template_array['content']);
                                        $Email2 = new CakeEmail(MAILTYPE);

                                        $Email2->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                                        $Email2->to($stemail);
                                        $Email2->subject($template_array['subject'])
                                                ->template('buzzydocother')
                                                ->emailFormat('html');

                                        $Email2->viewVars(array('msg' => $link
                                        ));
                                        $Email2->send();
                                        $Email3 = new CakeEmail(MAILTYPE);

                                        $Email3->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                                        $Email3->to(SUPER_ADMIN_EMAIL);
                                        $Email3->subject($response->xml->messages->message->text)
                                                ->template('buzzydocother')
                                                ->emailFormat('html');

                                        $Email3->viewVars(array('msg' => 'Hi BuzzyDoc, Practice - ' . $minimumdeposit['Clinic']['api_user'] . ' have some issue related to credit amount to account.<br> Error Message Details are "' . $response->xml->messages->message->text . '"',
                                            'subject' => $response->xml->messages->message->text
                                        ));
                                        $Email3->send();
                                    }
                                }
//                            }
                            }

                            if ($creditsuccess == 1 && $minimumdeposit['Clinic']['is_buzzydoc'] == 1) {
                                $ord_id = mt_rand(100000, 999999);
                                $globalredeem_array['GlobalRedeem'] = array(
                                    'user_id' => $user_id,
                                    'staff_id' => $staffId,
                                    'transaction_id' => $transId,
                                    'clinic_id' => $glbpt['Transaction']['clinic_id'],
                                    'first_name' => $user['User']['first_name'],
                                    'last_name' => $user['User']['last_name'],
                                    'activity_type' => 'Y',
                                    'authorization' => $sku,
                                    'amount' => '-' . $paytoclinic,
                                    'status' => 'Redeemed',
                                    'date' => date('Y-m-d H:i:s'),
                                    'order_id' => $ord_id,
                                    'is_buzzydoc' => 1
                                );
                                $this->GlobalRedeem->create();
                                $this->GlobalRedeem->save($globalredeem_array);
                                $notificationid = $this->Api->save_notification($globalredeem_array, 1);

                                $GltransId = $this->GlobalRedeem->getInsertID();
                                $totalbaltopay = $totalbaltopay + $paytoclinic;
                                $Invoice_array['Invoice'] = array(
                                    'clinic_id' => $glbpt['Transaction']['clinic_id'],
                                    'user_id' => $user_id,
                                    'amount' => $current_pay,
                                    'invoice_id' => $ord_id,
                                    'mode' => 'Debit',
                                    'current_balance' => $current_bal,
                                    'payed_on' => date('Y-m-d H:i:s'),
                                    'status' => 1
                                );
                                $this->Invoice->create();
                                $this->Invoice->save($Invoice_array);
                                $IntransId = $this->Invoice->getInsertID();
                            }
                        }
                    }

                    if ($totalbaltopay > 0) {
                        $tangocard = new Sourcefuse\TangoCard(PLATFORM_ID, PLATFORM_KEY);
                        $tangocard->setAppMode(TANGO_MODE);
                        $perchaseamout = $amount * 100;
                        $response_tango = $tangocard->placeOrder($Accountdetail['TangoAccount']['customer'], $Accountdetail['TangoAccount']['identifier'], 'Seatle', 'BuzzyDoc Team', 'Thanks for redeeming the points', 'Redeem Points, Thank you...', $sku, $perchaseamout, $user['User']['first_name'] . ' ' . $user['User']['last_name'], $user['User']['email'], true);


                        if ($response_tango->success == 1) {
                            $reachthres = $current_bal - $threshold;
                            $template_array_red = $this->Api->get_template(12);
                            $link = str_replace('[staff_name]', $stname, $template_array_red['content']);
                            $link1 = str_replace('[reduced_amount]', $current_pay, $link);
                            $link2 = str_replace('[current_balance]', $current_bal, $link1);
                            $link3 = str_replace('[away_amount]', $reachthres, $link2);
                            $Email1 = new CakeEmail(MAILTYPE);

                            $Email1->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));

                            $Email1->to($stemail);
                            $Email1->subject($template_array_red['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');
                            $Email1->viewVars(array('msg' => $link3
                            ));
                            $Email1->send();
                            $getbalance = $tangocard->getAccountInfo($Accountdetail['TangoAccount']['customer'], $Accountdetail['TangoAccount']['identifier']);

                            if ($getbalance->success == '1') {
                                $this->TangoAccount->query('update tango_accounts set available_balance=' . $getbalance->account->available_balance / 100 . ' where customer="' . $Accountdetail['TangoAccount']['customer'] . '"');
                            }
                            $totalpoint = $user['User']['points'] - $totalbaltopay;
                            $this->User->query("UPDATE `users` SET `points` = '" . $totalpoint . "' WHERE `id` =" . $user_id);

                            $template_array = $this->Api->get_template(13);
                            $link = str_replace('[username]', $user['User']['first_name'], $template_array['content']);
                            $Email = new CakeEmail(MAILTYPE);

                            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                            $Email->to($user['User']['email']);
                            $Email->subject($template_array['subject'])
                                    ->template('buzzydocother')
                                    ->emailFormat('html');

                            $Email->viewVars(array('msg' => $link, 'orderdetails' => array()
                            ));
                            $Email->send();
//demo code
                            $ordernumber = $response_tango->order->order_id;
                            $ratereview_array['Transaction'] = array(
                                'id' => $transId,
                                'amount' => '-' . $totalbaltopay,
                                'order_id' => $ordernumber,
                                'date' => date('Y-m-d H:i:s'),
                            );
                            $this->Transaction->create();
                            $this->Transaction->save($ratereview_array);

                            $response_data = array('success' => true, 'data' => 'Place an order Successfully', 'errorid' => $clinicfautid, 'pointremain' => number_format($totalpoint, 0, '.', ''));
                        } else {
                            $this->Transaction->deleteAll(array('Transaction.id' => $transId));
                            $this->GlobalRedeem->deleteAll(array('GlobalRedeem.id' => $GltransId));
                            $this->ClinicNotification->deleteAll(array('ClinicNotification.id' => $notificationid));
                            $this->Invoice->deleteAll(array('Invoice.id' => $IntransId));
                            $response_data = array('success' => false, 'data' => 'Bad Request');
                        }
                    } else {

                        $response_data = array('success' => false, 'data' => 'Bad Request', 'errorid' => $clinicfautid);
                    }
                }
            } else {
                $template_array = $this->Api->get_template(31);

                $Emailadmin = new CakeEmail(MAILTYPE);

                $Emailadmin->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                $Emailadmin->to(SUPER_ADMIN_EMAIL);
                $Emailadmin->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');

                $Emailadmin->viewVars(array('msg' => $template_array['content']
                ));
                $Emailadmin->send();
                $response_data = array('success' => false, 'data' => 'Bad Request');
            }
        } else {
            $response_data = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'placeanorder' => $response_data,
            '_serialize' => array('placeanorder')
        ));
    }

    /**
     * getting the pratice list by most point given by.
     */
    public function practicebymostpoint() {

        $request_data = $this->request->input('json_decode');
        if ($request_data->pincode != '') {
            $prepAddr = str_replace(' ', '+', $request_data->pincode);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);

            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $radius = 10;
            $sql = "SELECT c.api_user, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl join clinics as c on c.id=cl.clinic_id HAVING distance <= {$radius}  order by distance asc";
            $allclinicid = $this->ClinicLocation->query($sql);

            if (count($allclinicid) > 0) {
                $clinicarray = array();
                foreach ($allclinicid as $adoc) {
                    $clinicarray[] = $adoc['c']['api_user'];
                }
                $clinicarray = array_unique($clinicarray);
                $i = 0;
                $allclinic = array();
                foreach ($clinicarray as $cliniclist) {
                    $response = $this->clinicdetail_helper($cliniclist, 1);
                    $allclinic[$i] = $response;
                    $i++;
                }
                foreach ($allclinic as $key => $row) {
                    $timeArr[$key] = $this->Api->custom_number_format($row['Pointsharesrt']); //strtotime(str_replace('T',' ',$row['date']));
                }
                array_multisort($timeArr, SORT_DESC, $allclinic);
                $alltopclinic = array();
                $i = 0;
                foreach ($allclinic as $all) {
                    $alltopclinic[$i] = $all;
                    $i++;
                    if ($i == 3)
                        break;
                }
                $response = array('success' => true, 'data' => $alltopclinic);
            }else {
                $response = array('success' => false, 'data' => 'No record');
            }
        } else {
            $response = array('success' => false, 'data' => 'No record');
        }
        $this->set(array(
            'topclinic' => $response,
            '_serialize' => array('topclinic')
        ));
    }

    /**
     * Get the doctor list by pincode.
     */
    public function getdoctorviapincode() {
        $request_data = $this->request->input('json_decode');

        $prepAddr = str_replace(' ', '+', $request_data->pincode);
        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
        $output = json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        $radius = 10;


        $sql = "SELECT dl.doctor_id, ( 6371 * acos( cos( radians( {$latitude} ) ) * cos( radians( cl.latitude ) ) * cos( radians( cl.longitude ) - radians( {$longitude} ) ) + sin( radians( {$latitude} ) ) * sin( radians( cl.latitude ) ) ) ) AS distance FROM clinic_locations as cl inner join doctor_locations as dl on dl.location_id=cl.id HAVING distance <= {$radius}  order by distance asc";
        $alldoctorid = $this->ClinicLocation->query($sql);

        if (count($alldoctorid) > 0) {
            $docarray = array();
            foreach ($alldoctorid as $adoc) {
                $docarray[] = $adoc['dl']['doctor_id'];
            }

            $alldoctor = $this->Doctor->query('select dc.*, avg(rr.rate) as totalrate from doctors as dc left join rate_reviews as rr on rr.doctor_id=dc.id where dc.id IN (' . implode(',', array_unique($docarray)) . ') group by dc.id order by totalrate desc limit 3');
            $response = array('success' => true, 'data' => $alldoctor);
        } else {
            $response = array('success' => false, 'data' => 'No Record Found!');
        }
        $this->set(array(
            'doctorviapin' => $response,
            '_serialize' => array('doctorviapin')
        ));
    }

    /**
     * getting the all list of pratice for patient.
     */
    public function myclinic() {
        if ($this->request->is('post')) {
            $userId = $this->request->data('user_id');

            $offset = $this->request->data('offset');
            $limit = $this->request->data('limit');
            if (!empty($userId)) {
                $allpoints = $this->Transaction->find('all', array(
                    'conditions' => array(
                        'Transaction.user_id' => $userId,
                        'Transaction.activity_type' => 'N',
                        'Transaction.amount >' => 0
                    ),
                    'fields' => array(
                        'Transaction.clinic_id'
                    ),
                    'group' => array(
                        'Transaction.clinic_id'
                )));

                $clarray = array();
                foreach ($allpoints as $alp) {
                    $clarray[] = $alp['Transaction']['clinic_id'];
                }

                $clinic = $this->Clinic->find('all', array(
                    'conditions' => array(
                        'Clinic.id' => $clarray
                    ),
                    'fields' => array(
                        'Clinic.buzzydoc_logo_url,Clinic.api_user,Clinic.id'
                    )
                ));

                $likedclinic = array();
                if (!empty($clinic)) {
                    foreach ($clinic as $ls) {


                        $chkimg = $this->Api->is_exist_img($ls['Clinic']['buzzydoc_logo_url']);
                        if (isset($ls['Clinic']['buzzydoc_logo_url']) && $ls['Clinic']['buzzydoc_logo_url'] != '') {

                            $clinic['Clinic']['clinicimg'] = S3Path . $ls['Clinic']['buzzydoc_logo_url'];
                        } else {

                            $clinic['Clinic']['clinicimg'] = CDN . 'img/images_buzzy/clinic.png';
                        }
                        $clinic['Clinic']['buzzyclinicurl'] = '/practice/' . $ls['Clinic']['api_user'];
                        $clinic['Clinic']['api_user'] = $ls['Clinic']['api_user'];
                        $clinic['Clinic']['id'] = $ls['Clinic']['id'];

                        $likedclinic[] = $clinic;
                    }
                }
                $response = array('success' => true, 'data' => $likedclinic);
            } else {
                $response = array('success' => false, 'data' => 'Bad Request!');
            }
        } else {
            $response = array('success' => false, 'data' => 'Bad Request!');
        }
        $this->set(array(
            'myclinic' => $response,
            '_serialize' => array('myclinic')
        ));
    }

    public function goalSetForStaff() {
        $request_data = $this->request->input('json_decode');
        $staff_id = $request_data->staff_id;
        $clinic_id = $request_data->clinic_id;
        if ($staff_id != '' && $clinic_id != '') {
            $getgoalAchiv = $this->GoalAchievement->find('first', array(
                'conditions' => array(
                    'GoalAchievement.staff_id' => $staff_id,
                    'GoalAchievement.clinic_id' => $clinic_id
                ),
                'order' => array(
                    'GoalAchievement.goal_start_date desc'
                )
            ));

            $getgoal = $this->Goal->find('first', array(
                'conditions' => array(
                    'Goal.staff_id' => $staff_id,
                    'Goal.clinic_id' => $clinic_id
                ),
                'order' => array(
                    'Goal.goal_start_date desc'
                )
            ));
            if (!empty($getgoalAchiv)) {
                $start_date = date('Y-m-d H:i:s', strtotime($getgoalAchiv['GoalAchievement']['goal_start_date']));
            } else {
                $start_date = date('Y-m-d H:i:s', strtotime($getgoal['Goal']['goal_start_date']));
            }
            $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' + 7 days'));
            $duration = array($start_date, $end_date);

            //get the weekly staff transaction report.
            $weekly = $this->transaction->getStaffWeeklyTransactionReport('d', $duration, $clinic_id, $staff_id);
            print_r($weekly);
            die;
        } else {
            $response_data = array('success' => false, 'data' => 'Bad Request');
        }
        $this->set(array(
            'goalSet' => $response_data,
            '_serialize' => array('goalSet')
        ));
    }
}

?>
