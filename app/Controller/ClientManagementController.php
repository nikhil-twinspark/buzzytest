<?php

/**
 *  This file is for create new practice ,edit ,delete and set the access control for staff.
 *  Deletion of existing treatment plan for clinic,login to staff site directolly.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller is for create new practice ,edit ,delete and set the access control for staff.
 *  Deletion of existing treatment plan for clinic,login to staff site directolly.
 */
class ClientManagementController extends AppController {

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');

    /**
     * We use the session, api and CakeS3 component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinic', 'Theme', 'Notification', 'CharacteristicInsurance', 'IndustryType', 'User', 'Model', 'Staff', 'CardLog', 'CardNumber', 'AdminSetting', 'IndustryPromotion', 'Transaction', 'ClinicUser', 'Promotion', 'AccessControl', 'Clinic_reward', 'Reward', 'UpperLevelSetting', 'LevelupPromotion', 'PhaseDistribution', 'TreatmentSetting', 'AccessStaff', 'BeanstreamPayment', 'Appointment', 'Badge', 'BankAccount', 'ClinicLocation', 'ClinicPromotion', 'ContestClinic', 'CouponAvail', 'Doctor', 'Document', 'FacebookLike', 'GlobalRedeem', 'Invoice', 'LevelupPromotion', 'MilestoneReward', 'PaymentDetail', 'ProductService', 'RateReview', 'Refer', 'SaveLike', 'UnregTransaction', 'UserAssignedTreatment', 'UserPerfectVisit', 'WishList','EmailTemplate');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }

    /**
     * 
     *  Function to get all clinic list.
     */
    public function index() {
        $this->layout = "adminLayout";
        $clients = $this->Clinic->find('all');

        $i1 = 0;
        foreach ($clients as $client) {


            $gettreatment = $this->UpperLevelSetting->find('all', array('conditions' => array('UpperLevelSetting.clinic_id' => $client['Clinic']['id'])));
            if (!empty($gettreatment)) {
                $clients[$i1]['Clinic']['treatment_avail'] = 1;
            } else {
                $clients[$i1]['Clinic']['treatment_avail'] = 0;
            }

            $allaccess = $this->AccessStaff->getAccessForClinic($client['Clinic']['id']);
            if (empty($allaccess)) {
                $AccessStaffdata['AccessStaff'] = array('clinic_id' => $client['Clinic']['id'], 'levelup' => 0, 'reward_reporting' => 0, 'staff_input' => 0, 'staff_reporting' => 0, 'no_of_plan' => 0, 'no_of_interval' => 0, 'product_service' => 0, 'staff_to_redeem' => 0, 'milestone_reward' => 0, 'with_email' => 0, 'show_documents' => 0, 'self_registration' => 0, 'no_of_promotion' => 0, 'tier_setting' => 0);
                $this->AccessStaff->create();
                $this->AccessStaff->save($AccessStaffdata);
            }
            $staff = $this->Staff->query('select * from staffs where clinic_id=' . $client['Clinic']['id'] . ' and is_superstaff=1');
            if (empty($staff)) {
                $staff = $this->Staff->query('select * from staffs where clinic_id=' . $client['Clinic']['id'] . ' and (staff_role="A" OR staff_role="Administrator")');
                if (empty($staff)) {
                    $staff = $this->Staff->query('select * from staffs where clinic_id=' . $client['Clinic']['id'] . ' and (staff_role="M" OR staff_role="Manager")');
                }
            }
            if (!empty($staff)) {
                if ($staff[0]['staffs']['staff_role'] == 'A' || $staff[0]['staffs']['staff_role'] == 'Administrator') {
                    $clients[$i1]['Clinic']['staff_id'] = $staff[0]['staffs']['id'];
                } else {
                    $staffad = $this->Staff->query('select * from staffs where clinic_id=' . $client['Clinic']['id'] . ' and (staff_role="A" OR staff_role="Administrator")');
                    if (empty($staffad)) {
                        $clients[$i1]['Clinic']['staff_id'] = $staff[0]['staffs']['id'];
                    } else {
                        $clients[$i1]['Clinic']['staff_id'] = $staffad[0]['staffs']['id'];
                        $this->Staff->query('update staffs set is_superstaff=1 where id=' . $staffad[0]['staffs']['id']);
                        $this->Staff->query('update staffs set is_superstaff=0 where id=' . $staff[0]['staffs']['id']);
                    }
                }
            }
            $i1++;
        }
        $this->set('clients', $clients);
    }

    /**
     * 
     *  get all staff related to clinic for login to staff site.
     */
    public function getstaff() {

        $this->layout = "";
        $staff_id = '';
        $staff = $this->Staff->query('select * from staffs where clinic_id=' . $_POST['clinic_id'] . ' and is_superstaff=1');
        if (empty($staff)) {
            $staff = $this->Staff->query('select * from staffs where clinic_id=' . $_POST['clinic_id'] . ' and (staff_role="A" OR staff_role="Administrator")');
            if (empty($staff)) {
                $staff = $this->Staff->query('select * from staffs where clinic_id=' . $_POST['clinic_id'] . ' and (staff_role="M" OR staff_role="Manager")');
            }
        }
        if (!empty($staff)) {
            if ($staff[0]['staffs']['staff_role'] == 'A' || $staff[0]['staffs']['staff_role'] == 'Administrator') {
                $staff_id = $staff[0]['staffs']['id'];
            } else {
                $staffad = $this->Staff->query('select * from staffs where clinic_id=' . $_POST['clinic_id'] . ' and (staff_role="A" OR staff_role="Administrator")');
                if (empty($staffad)) {
                    $staff_id = $staff[0]['staffs']['id'];
                } else {
                    $staff_id = $staffad[0]['staffs']['id'];
                }
            }
        }
        $allstaff = $this->Staff->find('all', array('conditions' => array('Staff.clinic_id' => $_POST['clinic_id'])));
        $client = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $_POST['clinic_id'])));
        $stringstaff = '<select name="staffchk" id="staffchk">';
        foreach ($allstaff as $astaff) {
            // this condition for demo clinic and who not have own url
            if (Domain_Name == 'integratestg.sourcefuse.com' && $_POST['clinic_id'] != 52 && $_POST['clinic_id'] != 67 && $_POST['clinic_id'] != 70) {
                $dname = 'http://' . str_replace(' ', '', $client['Clinic']['api_user']) . '.' . Domain_Name . '/staff/login/' . base64_encode($astaff['Staff']['id']);
            } else {
                $dname = rtrim($client['Clinic']['staff_url'], '/') . '/staff/login/' . base64_encode($astaff['Staff']['id']);
            }




            $stringstaff .='<option value="' . $dname . '"';
            if ($astaff['Staff']['id'] == $staff_id) {
                $stringstaff .=' selected ';
            }

            $stringstaff .='>' . $astaff['Staff']['staff_id'] . '</option>';
        }
        $stringstaff .='</select>';
        echo $stringstaff;
        exit;
    }

    /**
     * add new clinic with default promotion,rewards and access control.
     * @return boolean
     */
    public function add() {
        Configure::write('debug', 2);
        $this->layout = "adminLayout";
        $indsurty = $this->IndustryType->find('all');
        $this->set('indsurty', $indsurty);
        $footer_full_image_path = NULL;
        if ($this->request->is('post')) {
            

            $options['conditions'] = array('Clinic.api_user' => $this->request->data['Clinic']['api_user']);
            $credResult = $this->Clinic->find('all', $options);

            if (empty($credResult)) {
                $staffurl = explode('/', $this->request->data['Clinic']['staff_url']);
                $patienturl = explode('/', $this->request->data['Clinic']['patient_url']);

                $options1['conditions'] = array('OR' => array('Clinic.staff_url Like ' => '%' . $staffurl[2] . '%', 'Clinic.patient_url Like ' => '%' . $staffurl[2] . '%'));
                $urlResult = $this->Clinic->find('all', $options1);
                $options2['conditions'] = array('OR' => array('Clinic.staff_url Like ' => '%' . $patienturl[2] . '%', 'Clinic.patient_url Like ' => '%' . $patienturl[2] . '%'));
                $urlResult2 = $this->Clinic->find('all', $options2);

                if (empty($urlResult) && empty($urlResult2)) {

                    if ($this->data['Clinic']) {
                        //$staff_logo_url = $this->data['Clinic']['staff_logo_url'];
                        $patient_logo_url = $this->data['Clinic']['patient_logo_url'];
                        $buzzydoc_logo_url = $this->data['Clinic']['buzzydoc_logo_url'];
                        $backgroud_image_url = $this->data['Clinic']['backgroud_image_url'];
                        //$patient_footer_logo_url = $this->data['Clinic']['patient_footer_logo_url'];
                        $patient_question_mark = $this->data['Clinic']['patient_question_mark'];
                        $patient_slider_1 = $this->data['Clinic']['patient_slider_image_1'];
                        $patient_slider_2 = $this->data['Clinic']['patient_slider_image_2'];
                        $patient_mobile_image = $this->data['Clinic']['patient_mobile_image'];
                        if ($this->request->data['Clinic']['analytic_code'] == '') {
                            $ana = "<script>
  (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');

  ga(''create'', ''UA-49868721-1'', ''buzzydoc.com'');
  ga(''send'', ''pageview'');
</script>";

                            $this->request->data['Clinic']['analytic_code'] = $ana;
                        }


                        //allowed image types
                        $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                        //upload folder - make sure to create one in webroot
                        $uploadFolder = $this->data['Clinic']['api_user'];

                        //full path to upload folder

                        $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                            chmod($uploadPath, 0777);
                        }

                        //check if image type fits one of allowed types
                        foreach ($imageTypes as $type) {


                            if ($type == $patient_logo_url['type']) {

                                //check if there wasn't errors uploading file on serwer
                                if ($patient_logo_url['error'] == 0) {
                                    //image file name
                                    $patientimageName = $this->data['Clinic']['api_user'] . '_patient_logo';
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $patientimageName)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $patientimageName);
                                    }
                                    //create full path with image name
                                    $patient_full_image_path = $uploadPath . '/' . $patientimageName;

                                    //upload image to upload folder
                                    if (move_uploaded_file($patient_logo_url['tmp_name'], $patient_full_image_path)) {

                                        $response = $this->CakeS3->putObject($patient_full_image_path, 'img/' . $uploadFolder . '/' . $patientimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        //pr($response); die("response is here");
                                        $sharingImageUrl = $response['url'];
                                        @unlink($patient_full_image_path);
                                        $this->request->data['Clinic']['patient_logo_url'] = 'img/' . $uploadFolder . '/' . $patientimageName;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading patient logo image. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading patient logo image.', 'default', array(), 'bad');
                                }
                            }
                            if ($type == $buzzydoc_logo_url['type']) {
                                if ($buzzydoc_logo_url['error'] == 0) {
                                    //image file name
                                    $buzzydocimageName = $this->data['Clinic']['api_user'] . '_buzzydoc_logo';
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $buzzydocimageName)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $buzzydocimageName);
                                    }
                                    //create full path with image name
                                    $buzzydoc_full_image_path = $uploadPath . '/' . $buzzydocimageName;

                                    //upload image to upload folder
                                    if (move_uploaded_file($buzzydoc_logo_url['tmp_name'], $buzzydoc_full_image_path)) {

                                        $response = $this->CakeS3->putObject($buzzydoc_full_image_path, 'img/' . $uploadFolder . '/' . $buzzydocimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($buzzydoc_full_image_path);
                                        $this->request->data['Clinic']['buzzydoc_logo_url'] = 'img/' . $uploadFolder . '/' . $buzzydocimageName;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading buzzydoc logo image. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading buzzydoc logo image.', 'default', array(), 'bad');
                                }
                            }
                            if ($type == $backgroud_image_url['type']) {
                                //check if there wasn't errors uploading file on serwer
                                if ($backgroud_image_url['error'] == 0) {
                                    //image file name
                                    $backgroudimageName = $this->data['Clinic']['api_user'] . "_background_image";
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $backgroudimageName)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $backgroudimageName);
                                    }
                                    //create full path with image name
                                    $backgroud_full_image_path = $uploadPath . '/' . $backgroudimageName;

                                    //upload image to upload folder
                                    if (move_uploaded_file($backgroud_image_url['tmp_name'], $backgroud_full_image_path)) {
                                        $response = $this->CakeS3->putObject($backgroud_full_image_path, 'img/' . $uploadFolder . '/' . $backgroudimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($backgroud_full_image_path);
                                        $this->request->data['Clinic']['backgroud_image_url'] = 'img/' . $uploadFolder . '/' . $backgroudimageName;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading backgroud logo image. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading backgroud logo image.', 'default', array(), 'bad');
                                }
                            }

                            if ($type == $patient_question_mark['type']) {
                                //check if there wasn't errors uploading file on serwer
                                if ($patient_question_mark['error'] == 0) {
                                    //image file name
                                    $patientquestionmark = $this->data['Clinic']['api_user'] . "_question_image";
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $patientquestionmark)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $patientquestionmark);
                                    }
                                    //create full path with image name
                                    $question_full_image_path = $uploadPath . '/' . $patientquestionmark;

                                    //upload image to upload folder
                                    if (move_uploaded_file($patient_question_mark['tmp_name'], $question_full_image_path)) {
                                        $response = $this->CakeS3->putObject($footer_full_image_path, 'img/' . $uploadFolder . '/' . $patientquestionmark, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($question_full_image_path);
                                        $this->request->data['Clinic']['patient_question_mark'] = 'img/' . $uploadFolder . '/' . $patientquestionmark;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading question mark image. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading question mark image.', 'default', array(), 'bad');
                                }
                            }

                            if ($type == $patient_slider_1['type']) {

                                //check if there wasn't errors uploading file on serwer
                                if ($patient_slider_1['error'] == 0) {
                                    //image file name

                                    $patientslider1 = time() . '_' . $patient_slider_1['name'];
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $patientslider1)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $patientslider1);
                                    }
                                    //create full path with image name
                                    $slider1_full_image_path = $uploadPath . '/' . $patientslider1;

                                    //upload image to upload folder
                                    if (move_uploaded_file($patient_slider_1['tmp_name'], $slider1_full_image_path)) {
                                        $response = $this->CakeS3->putObject($slider1_full_image_path, 'img/' . $uploadFolder . '/' . $patientslider1, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($slider1_full_image_path);
                                        $this->request->data['Clinic']['patient_slider_image_1'] = 'img/' . $uploadFolder . '/' . $patientslider1;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading reward site slider image 1. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading reward site slider image 1.', 'default', array(), 'bad');
                                }
                            }

                            if ($type == $patient_slider_2['type']) {
                                //check if there wasn't errors uploading file on serwer
                                if ($patient_slider_2['error'] == 0) {
                                    //image file name
                                    $patientslider2 = time() . '_' . $patient_slider_2['name'];
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $patientslider2)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $patientslider2);
                                    }
                                    //create full path with image name
                                    $slider2_full_image_path = $uploadPath . '/' . $patientslider2;

                                    //upload image to upload folder
                                    if (move_uploaded_file($patient_slider_2['tmp_name'], $slider2_full_image_path)) {
                                        $response = $this->CakeS3->putObject($slider2_full_image_path, 'img/' . $uploadFolder . '/' . $patientslider2, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($slider2_full_image_path);
                                        $this->request->data['Clinic']['patient_slider_image_2'] = 'img/' . $uploadFolder . '/' . $patientslider2;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading reward site slider image 2. Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading reward site slider image 2.', 'default', array(), 'bad');
                                }
                            }

                            if ($type == $patient_mobile_image['type']) {
                                //check if there wasn't errors uploading file on serwer
                                if ($patient_mobile_image['error'] == 0) {
                                    //image file name
                                    $patientmobileimage = time() . '_' . $patient_mobile_image['name'];
                                    //check if file exists in upload folder
                                    if (file_exists($uploadPath . '/' . $patientmobileimage)) {
                                        //create full filename with timestamp
                                        unlink($uploadPath . '/' . $patientmobileimage);
                                    }
                                    //create full path with image name
                                    $mobile_full_image_path = $uploadPath . '/' . $patientmobileimage;
                                    //pr($mobile_full_image_path); die;
                                    //upload image to upload folder
                                    if (move_uploaded_file($patient_mobile_image['tmp_name'], $mobile_full_image_path)) {
                                        $response = $this->CakeS3->putObject($mobile_full_image_path, 'img/' . $uploadFolder . '/' . $patientmobileimage, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                        $sharingImageUrl = $response['url'];
                                        @unlink($mobile_full_image_path);
                                        $this->request->data['Clinic']['patient_mobile_image'] = 'img/' . $uploadFolder . '/' . $patientmobileimage;
                                    } else {
                                        $this->Session->setFlash('There was a problem uploading reward site image (mobile). Please try again.', 'default', array(), 'bad');
                                    }
                                } else {
                                    $this->Session->setFlash('Error uploading reward site image (mobile).', 'default', array(), 'bad');
                                }
                            }
                        }
                    }


                    if (isset($this->request->data['Clinic']['patient_logo_url']['name']) && empty($this->request->data['Clinic']['patient_logo_url']['name'])) {
                        unset($this->request->data['Clinic']['patient_logo_url']);
                    }
                    if (isset($this->request->data['Clinic']['buzzydoc_logo_url']['name']) && empty($this->request->data['Clinic']['buzzydoc_logo_url']['name'])) {
                        unset($this->request->data['Clinic']['buzzydoc_logo_url']);
                    }
                    if (isset($this->request->data['Clinic']['backgroud_image_url']['name']) && empty($this->request->data['Clinic']['backgroud_image_url']['name'])) {
                        unset($this->request->data['Clinic']['backgroud_image_url']);
                    }

                    if (isset($this->request->data['Clinic']['patient_question_mark']['name']) && empty($this->request->data['Clinic']['patient_question_mark']['name'])) {
                        unset($this->request->data['Clinic']['patient_question_mark']);
                    }
                    if (isset($this->request->data['error_patient_logo']) && empty($this->request->data['error_patient_logo'])) {
                        unset($this->request->data['error_patient_logo']);
                    }
                    if (isset($this->request->data['error_buzzdoc_logo']) && empty($this->request->data['error_buzzdoc_logo'])) {
                        unset($this->request->data['error_buzzdoc_logo']);
                    }
                    if (isset($this->request->data['error_patient_logo_ftr']) && empty($this->request->data['error_patient_logo_ftr'])) {
                        unset($this->request->data['error_patient_logo_ftr']);
                    }
                    if ($this->request->data['is_buzzydoc'] == 0 && $clientData['Clinic']['is_buzzydoc'] == 1) {
                        $this->request->data['Clinic']['down_date'] = date('Y-m-d');
                    }
                    $this->request->data['Clinic']['is_buzzydoc'] = $this->request->data['is_buzzydoc'];
                    if (isset($this->request->data['Clinic']['is_lite'])) {
                        $this->request->data['Clinic']['is_lite'] = $this->request->data['Clinic']['is_lite'];
                    } else {
                        $this->request->data['Clinic']['is_lite'] = 0;
                    }

                    //echo "<pre>";print_r($this->request->data);die;
                    $options['conditions'] = array('IndustryPromotion.industry_id' => $this->request->data['Clinic']['industry_type']);
                    $Promotion = $this->IndustryPromotion->find('all', $options);
                    $optionslite['conditions'] = array('Promotion.clinic_id' => 0, 'Promotion.is_lite' => 1);
                    $litePromotion = $this->Promotion->find('all', $optionslite);

                    $this->request->data['Clinic']['is_buzzydoc'] = $this->request->data['is_buzzydoc'];

                    if ($this->Clinic->save($this->request->data)) {

                        $clinic_id = $this->Clinic->getLastInsertId();
                        //getting the default rewards and adding to clinic
                        $unpublicreward = $this->Reward->find('all', array('conditions' => array('Reward.clinic_id' => 0)));
                        foreach ($unpublicreward as $unpreward) {
                            $datareward = array();
                            $datareward['clinic_id'] = $clinic_id;
                            $datareward['reward_id'] = $unpreward['Reward']['id'];
                            $saverewards = $this->Clinic_reward->save($datareward);
                        }
                        //this is for saving the access control for legacy and buzzydoc clinic
                        if ($this->request->data['Clinic']['is_buzzydoc'] == 0) {
                            $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Users,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 0, 'clinic_id' => $clinic_id);
                            $this->AccessControl->save($accessarray);
                        } else {
                            if ($this->request->data['Clinic']['is_lite'] == 0) {
                                $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 1, 'clinic_id' => $clinic_id);
                                $this->AccessControl->save($accessarray);
                            } else {
                                $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Staffs,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 2, 'clinic_id' => $clinic_id);
                                $this->AccessControl->save($accessarray);
                            }
                        }
                        $procnt = 1;
                        //saving default promotion for clinic
                        foreach ($Promotion as $pro) {
                            $this->Promotion->create();


                            $proarray['Promotion'] = array('description' => $pro['IndustryPromotion']['description'], 'value' => $pro['IndustryPromotion']['value'], 'operand' => $pro['IndustryPromotion']['operand'], 'display_name' => $pro['IndustryPromotion']['display_name'], 'clinic_id' => $clinic_id, 'is_lite' => 0, 'sort' => $procnt, 'default' => 1, 'public' => 1, 'is_global' => 0);

                            $this->Promotion->save($proarray);
                            $procnt++;
                        }
                        $pro1 = 1;
                        foreach ($litePromotion as $litepro) {
                            $this->Promotion->create();

                            $proarraylite['Promotion'] = array('description' => $litepro['Promotion']['description'], 'value' => $litepro['Promotion']['value'], 'operand' => $litepro['Promotion']['operand'], 'display_name' => $litepro['Promotion']['display_name'], 'clinic_id' => $clinic_id, 'is_lite' => 1, 'sort' => $pro1, 'public' => 1, 'is_global' => 0);
                            $this->Promotion->save($proarraylite);
                            $pro1++;
                        }
                        $this->Staff->create();
                        //saving the administratior staff for clinic
                        $staff['Staff'] = array('staff_id' => $this->request->data['staff_id'], 'clinic_id' => $clinic_id, 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'staff_password' => $this->request->data['new_password'], 'staff_role' => 'A', 'is_superstaff' => 1);
                        $this->Staff->save($staff);
                        $this->Session->setFlash('The client has been added', 'default', array(), 'good');
                        $this->redirect(array('action' => "index"));
                    } else {
                        $this->Session->setFlash('The client could not be saved. Please, try again.', 'default', array(), 'bad');
                    }
                } else {
                    $this->Session->setFlash('Unable to add your credentials.Client Already exists.', 'default', array(), 'bad');
                    return false;
                }
                /* } */
            }
            $this->Session->setFlash('Unable to add your credentials.Client Already exists.', 'default', array(), 'bad');
        }
    }

    /**
     * edit clinic with access control.
     * @return boolean
     */
    public function edit($id = null) {
        $this->layout = "adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $clientData = $this->Clinic->findById($id);
        $remainday = 0;
        $sheduledate = 60;
        if ($clientData['Clinic']['down_date'] != '0000-00-00') {
            $date11 = $clientData['Clinic']['down_date'];
            $diff1 = strtotime(date("Y-m-d")) - strtotime($date11);
            $days1 = floor($diff1 / (60 * 60 * 24));
            $remainday = $sheduledate - $days1;
        }
        $this->set('remainday', $remainday);
        $indsurty = $this->IndustryType->find('all');
        $this->set('indsurty', $indsurty);

        $staff = $this->Staff->query('select * from staffs where clinic_id=' . $clientData['Clinic']['id'] . ' and is_superstaff=1');
        if (empty($staff)) {
            $staff = $this->Staff->query('select * from staffs where clinic_id=' . $clientData['Clinic']['id'] . ' and (staff_role="A" OR staff_role="Administrator")');
            if (empty($staff)) {
                $staff = $this->Staff->query('select * from staffs where clinic_id=' . $clientData['Clinic']['id'] . ' and (staff_role="M" OR staff_role="Manager")');
            }
        }
        if (!empty($staff)) {
            $clientData['Clinic']['sid'] = $staff[0]['staffs']['id'];
            $clientData['Clinic']['staff_id'] = $staff[0]['staffs']['staff_id'];
            $clientData['Clinic']['staff_first_name'] = $staff[0]['staffs']['staff_first_name'];
            $clientData['Clinic']['staff_last_name'] = $staff[0]['staffs']['staff_last_name'];
            $clientData['Clinic']['staff_email'] = $staff[0]['staffs']['staff_email'];
            $clientData['Clinic']['staff_password'] = $staff[0]['staffs']['staff_password'];
        }


        if (!$clientData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Clinic->id = $id;
            $urlResult = array();
            $urlResult2 = array();
            $staffurl = explode('/', $this->request->data['Clinic']['staff_url']);
            $patienturl = explode('/', $this->request->data['Clinic']['patient_url']);

            if (isset($staffurl[2])) {
                $options1['conditions'] = array('OR' => array('Clinic.staff_url Like ' => '%' . $staffurl[2] . '%', 'Clinic.patient_url Like ' => '%' . $staffurl[2] . '%'), 'Clinic.id !=' => $id);
                $urlResult = $this->Clinic->find('all', $options1);
            }

            if (isset($patienturl[2])) {
                $options2['conditions'] = array('OR' => array('Clinic.staff_url Like ' => '%' . $patienturl[2] . '%', 'Clinic.patient_url Like ' => '%' . $patienturl[2] . '%'), 'Clinic.id !=' => $id);
                $urlResult2 = $this->Clinic->find('all', $options2);
            }
            if (empty($urlResult) && empty($urlResult2)) {

                if ($this->data['Clinic']) {

                    $patient_logo_url = $this->data['Clinic']['patient_logo_url'];
                    $buzzydoc_logo_url = $this->data['Clinic']['buzzydoc_logo_url'];
                    $backgroud_image_url = $this->data['Clinic']['backgroud_image_url'];
                    $patient_question_mark = $this->data['Clinic']['patient_question_mark'];
                    $patient_slider_1 = $this->data['Clinic']['patient_slider_image_1'];
                    $patient_slider_2 = $this->data['Clinic']['patient_slider_image_2'];
                    //$patient_slider_2 = 'hello';
                    $patient_mobile_image = $this->data['Clinic']['patient_mobile_image'];

                    //allowed image types
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = $this->data['site_name'];

                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {

                        if ($type == $patient_logo_url['type']) {

                            //check if there wasn't errors uploading file on serwer
                            if ($patient_logo_url['error'] == 0) {
                                //image file name
                                $patientimageName = time() . '_' . $patient_logo_url['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $patientimageName)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $patientimageName);
                                }
                                //create full path with image name
                                $patient_full_image_path = $uploadPath . '/' . $patientimageName;

                                //upload image to upload folder
                                if (move_uploaded_file($patient_logo_url['tmp_name'], $patient_full_image_path)) {
                                    $response = $this->CakeS3->putObject($patient_full_image_path, 'img/' . $uploadFolder . '/' . $patientimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($patient_full_image_path);
                                    $this->request->data['Clinic']['patient_logo_url'] = 'img/' . $uploadFolder . '/' . $patientimageName;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading patient logo image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading patient logo image.', 'default', array(), 'bad');
                            }
                        }
                        if ($type == $buzzydoc_logo_url['type']) {
                            if ($buzzydoc_logo_url['error'] == 0) {
                                //image file name
                                $buzzydocimageName = time() . '_' . $buzzydoc_logo_url['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $buzzydocimageName)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $buzzydocimageName);
                                }
                                //create full path with image name
                                $buzzydoc_full_image_path = $uploadPath . '/' . $buzzydocimageName;

                                //upload image to upload folder
                                if (move_uploaded_file($buzzydoc_logo_url['tmp_name'], $buzzydoc_full_image_path)) {
                                    $response = $this->CakeS3->putObject($buzzydoc_full_image_path, 'img/' . $uploadFolder . '/' . $buzzydocimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($patient_full_image_path);
                                    $this->request->data['Clinic']['buzzydoc_logo_url'] = 'img/' . $uploadFolder . '/' . $buzzydocimageName;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading buzzydoc logo image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading buzzydoc logo image.', 'default', array(), 'bad');
                            }
                        }
                        if ($type == $backgroud_image_url['type']) {
                            //check if there wasn't errors uploading file on serwer
                            if ($backgroud_image_url['error'] == 0) {
                                //image file name
                                $backgroudimageName = time() . '_' . $backgroud_image_url['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $backgroudimageName)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $backgroudimageName);
                                }
                                //create full path with image name
                                $backgroud_full_image_path = $uploadPath . '/' . $backgroudimageName;

                                //upload image to upload folder
                                if (move_uploaded_file($backgroud_image_url['tmp_name'], $backgroud_full_image_path)) {
                                    $response = $this->CakeS3->putObject($backgroud_full_image_path, 'img/' . $uploadFolder . '/' . $backgroudimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($backgroud_full_image_path);
                                    $this->request->data['Clinic']['backgroud_image_url'] = 'img/' . $uploadFolder . '/' . $backgroudimageName;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading backgroud logo image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading backgroud logo image.', 'default', array(), 'bad');
                            }
                        }


                        if ($type == $patient_question_mark['type']) {
                            //check if there wasn't errors uploading file on serwer
                            if ($patient_question_mark['error'] == 0) {
                                //image file name
                                $patientquestionmark = time() . '_' . $patient_question_mark['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $patientquestionmark)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $patientquestionmark);
                                }
                                //create full path with image name
                                $question_full_image_path = $uploadPath . '/' . $patientquestionmark;

                                //upload image to upload folder
                                if (move_uploaded_file($patient_question_mark['tmp_name'], $question_full_image_path)) {
                                    $response = $this->CakeS3->putObject($question_full_image_path, 'img/' . $uploadFolder . '/' . $patientquestionmark, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($question_full_image_path);
                                    $this->request->data['Clinic']['patient_question_mark'] = 'img/' . $uploadFolder . '/' . $patientquestionmark;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading question mark image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading question mark image.', 'default', array(), 'bad');
                            }
                        }

                        if ($type == $patient_slider_1['type']) {

                            //check if there wasn't errors uploading file on serwer
                            if ($patient_slider_1['error'] == 0) {
                                //image file name

                                $patientslider1 = time() . '_' . $patient_slider_1['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $patientslider1)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $patientslider1);
                                }
                                //create full path with image name
                                $slider1_full_image_path = $uploadPath . '/' . $patientslider1;

                                //upload image to upload folder
                                if (move_uploaded_file($patient_slider_1['tmp_name'], $slider1_full_image_path)) {
                                    $response = $this->CakeS3->putObject($slider1_full_image_path, 'img/' . $uploadFolder . '/' . $patientslider1, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($slider1_full_image_path);
                                    $this->request->data['Clinic']['patient_slider_image_1'] = 'img/' . $uploadFolder . '/' . $patientslider1;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading reward site slider image 1. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading reward site slider image 1.', 'default', array(), 'bad');
                            }
                        }

                        if ($type == $patient_slider_2['type']) {
                            //check if there wasn't errors uploading file on serwer
                            if ($patient_slider_2['error'] == 0) {
                                //image file name
                                $patientslider2 = time() . '_' . $patient_slider_2['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $patientslider2)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $patientslider2);
                                }
                                //create full path with image name
                                $slider2_full_image_path = $uploadPath . '/' . $patientslider2;

                                //upload image to upload folder
                                if (move_uploaded_file($patient_slider_2['tmp_name'], $slider2_full_image_path)) {
                                    $response = $this->CakeS3->putObject($slider2_full_image_path, 'img/' . $uploadFolder . '/' . $patientslider2, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($slider2_full_image_path);
                                    $this->request->data['Clinic']['patient_slider_image_2'] = 'img/' . $uploadFolder . '/' . $patientslider2;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading reward site slider image 2. Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading reward site slider image 2.', 'default', array(), 'bad');
                            }
                        }

                        if ($type == $patient_mobile_image['type']) {
                            //check if there wasn't errors uploading file on serwer
                            if ($patient_mobile_image['error'] == 0) {
                                //image file name
                                $patientmobileimage = time() . '_' . $patient_mobile_image['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $patientmobileimage)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $patientmobileimage);
                                }
                                //create full path with image name
                                $mobile_full_image_path = $uploadPath . '/' . $patientmobileimage;

                                //upload image to upload folder
                                if (move_uploaded_file($patient_mobile_image['tmp_name'], $mobile_full_image_path)) {
                                    $response = $this->CakeS3->putObject($mobile_full_image_path, 'img/' . $uploadFolder . '/' . $patientmobileimage, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($mobile_full_image_path);
                                    $this->request->data['Clinic']['patient_mobile_image'] = 'img/' . $uploadFolder . '/' . $patientmobileimage;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading reward site image (mobile). Please try again.', 'default', array(), 'bad');
                                }
                            } else {
                                $this->Session->setFlash('Error uploading reward site image (mobile).', 'default', array(), 'bad');
                            }
                        }
                    }
                }


                if (isset($this->request->data['Clinic']['patient_logo_url']['name']) && empty($this->request->data['Clinic']['patient_logo_url']['name'])) {
                    unset($this->request->data['Clinic']['patient_logo_url']);
                }
                if (isset($this->request->data['Clinic']['buzzydoc_logo_url']['name']) && empty($this->request->data['Clinic']['buzzydoc_logo_url']['name'])) {
                    unset($this->request->data['Clinic']['buzzydoc_logo_url']);
                }
                if (isset($this->request->data['Clinic']['backgroud_image_url']['name']) && empty($this->request->data['Clinic']['backgroud_image_url']['name'])) {
                    unset($this->request->data['Clinic']['backgroud_image_url']);
                }
                if (isset($this->request->data['Clinic']['patient_question_mark']['name']) && empty($this->request->data['Clinic']['patient_question_mark']['name'])) {
                    unset($this->request->data['Clinic']['patient_question_mark']);
                }
                if (isset($this->request->data['Clinic']['patient_slider_image_1']['name']) && empty($this->request->data['Clinic']['patient_slider_image_1']['name'])) {
                    unset($this->request->data['Clinic']['patient_slider_image_1']);
                }
                if (isset($this->request->data['Clinic']['patient_slider_image_2']['name']) && empty($this->request->data['Clinic']['patient_slider_image_2']['name'])) {
                    unset($this->request->data['Clinic']['patient_slider_image_2']);
                }
                if (isset($this->request->data['Clinic']['patient_mobile_image']['name']) && empty($this->request->data['Clinic']['patient_mobile_image']['name'])) {
                    unset($this->request->data['Clinic']['patient_mobile_image']);
                }
                if (isset($this->request->data['error_patient_logo']) && empty($this->request->data['error_patient_logo'])) {
                    unset($this->request->data['error_patient_logo']);
                }
                if (isset($this->request->data['error_buzzydoc_logo']) && empty($this->request->data['error_buzzydoc_logo'])) {
                    unset($this->request->data['error_buzzydoc_logo']);
                }
                if (isset($this->request->data['error_patient_logo_ftr']) && empty($this->request->data['error_patient_logo_ftr'])) {
                    unset($this->request->data['error_patient_logo_ftr']);
                }
                unset($this->request->data['site_name']);


                $checkindustry = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $id)));
                $modify = 0;
                if ($checkindustry['Clinic']['industry_type'] == $this->request->data['Clinic']['industry_type']) {
                    $modify = 0;
                } else {
                    $modify = 1;
                }
                if (isset($this->request->data['is_buzzydoc'])) {
                    if ($this->request->data['is_buzzydoc'] == 0 && $clientData['Clinic']['is_buzzydoc'] == 1) {
                        $this->request->data['Clinic']['is_buzzydoc'] = 1;
                        $this->request->data['Clinic']['down_date'] = date('Y-m-d');
                    } else {
                        $this->request->data['Clinic']['is_buzzydoc'] = $this->request->data['is_buzzydoc'];
                        $this->request->data['Clinic']['down_date'] = '0000-00-00';
                    }
                }
                if (isset($this->request->data['Clinic']['is_lite'])) {
                    $this->request->data['Clinic']['is_lite'] = $this->request->data['Clinic']['is_lite'];
                } else {
                    $this->request->data['Clinic']['is_lite'] = 0;
                }



                if ($this->Clinic->save($this->request->data)) {
                    $options8['conditions'] = array('Staff.clinic_id' => $id, 'Staff.staff_email !=' => '');
                    $Staff = $this->Staff->find('all', $options8);
                    $allemail = array();
                    foreach ($Staff as $st) {
                        $allemail[] = $st['Staff']['staff_email'];
                    }
                    //conditon to check downgrade clinic
                    if (isset($this->request->data['is_buzzydoc']) && $this->request->data['is_buzzydoc'] == 0 && $clientData['Clinic']['is_buzzydoc'] == 1) {

                        $this->request->data['Clinic']['down_date'] = date('Y-m-d');
                        //getting the email template
                        $template_array = $this->Api->get_template(14);
                        $link = str_replace('[clinic_name]', $checkindustry['Clinic']['api_user'], $template_array['content']);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                        $Email->to($allemail);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email->viewVars(array(
                            'msg' => $link,
                        ));
                        try {
                            $Email->send();
                            echo "mail send to " . $stf['Staff']['staff_email'] . " for downgrade clinic";
                        } catch (Exception $e) {
                            CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
                        }
                    }
                    //condition to check upgrade clinic
                    if (isset($this->request->data['is_buzzydoc']) && $this->request->data['is_buzzydoc'] == 1 && $clientData['Clinic']['is_buzzydoc'] == 0) {

                        if ($this->request->data['Clinic']['is_lite'] == 1) {
                            $ver = 'Lite Version';
                        } else {
                            $ver = 'Elite Version';
                        }
                        $this->request->data['Clinic']['down_date'] = '0000-00-00';

                        $template_array = $this->Api->get_template(15);
                        $link = str_replace('[clinic_name]', $checkindustry['Clinic']['api_user'], $template_array['content']);
                        $link1 = str_replace('[version]', $ver, $link);
                        $Email = new CakeEmail(MAILTYPE);
                        $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
                        $Email->to($allemail);
                        $Email->subject($template_array['subject'])
                                ->template('buzzydocother')
                                ->emailFormat('html');

                        $Email->viewVars(array(
                            'msg' => $link1,
                        ));
                        try {
                            $Email->send();
                            echo "mail send to " . $stf['Staff']['staff_email'] . " for upgrage clinic";
                        } catch (Exception $e) {
                            CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
                        }
                    }

                    $checkaccess = $this->AccessControl->find('first', array('conditions' => array('AccessControl.clinic_id' => $id)));
                    //saving access control
                    if (isset($this->request->data['is_buzzydoc'])) {
                        if (empty($checkaccess)) {

                            if ($this->request->data['Clinic']['is_buzzydoc'] == 0) {
                                $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Users,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 0, 'clinic_id' => $id);
                                $this->AccessControl->save($accessarray);
                            } else {

                                if ($this->request->data['Clinic']['is_lite'] == 0) {
                                    $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 1, 'clinic_id' => $id);
                                    $this->AccessControl->save($accessarray);
                                } else {
                                    $accessarray['AccessControl'] = array('access' => 'Documents,Promotions,Staffs,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 2, 'clinic_id' => $id);
                                    $this->AccessControl->save($accessarray);
                                }
                            }
                        } else {
                            if (isset($this->request->data['is_buzzydoc']) && $this->request->data['Clinic']['is_buzzydoc'] == 0) {
                                $accessarray['AccessControl'] = array('id' => $checkaccess['AccessControl']['id'], 'access' => 'Documents,Promotions,Users,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 0, 'clinic_id' => $id);
                                $this->AccessControl->save($accessarray);
                            } else {

                                if ($this->request->data['Clinic']['is_lite'] == 0) {
                                    $accessarray['AccessControl'] = array('id' => $checkaccess['AccessControl']['id'], 'access' => 'Documents,Promotions,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail', 'clinic_type' => 1, 'clinic_id' => $id);
                                    $this->AccessControl->save($accessarray);
                                } else {
                                    $accessarray['AccessControl'] = array('id' => $checkaccess['AccessControl']['id'], 'access' => 'Documents,Promotions,Staffs,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail,Users', 'clinic_type' => 2, 'clinic_id' => $id);
                                    $this->AccessControl->save($accessarray);
                                }
                            }
                        }
                    }

                    //condition when clinic upgarde and all local point convert to buzzydoc points
                    if (isset($this->request->data['is_buzzydoc']) && $this->request->data['is_buzzydoc'] == 1 && $clientData['Clinic']['is_buzzydoc'] == 0) {
                        $clinicus = $this->ClinicUser->find('all', array('conditions' => array('ClinicUser.clinic_id' => $id, 'ClinicUser.local_points >' => 0)));
                        foreach ($clinicus as $clus) {
                            $this->Transaction->query("UPDATE `transactions` SET `is_buzzydoc` = 1 WHERE `user_id` =" . $clus['ClinicUser']['user_id'] . ' and clinic_id=' . $clus['ClinicUser']['clinic_id']);
                            $userpoint = $this->User->find('first', array('conditions' => array('User.id' => $clus['ClinicUser']['user_id'])));
                            $ttpt = $userpoint['User']['points'] + $clus['ClinicUser']['local_points'];
                            $this->User->query("UPDATE `users` SET `points` = " . $ttpt . " WHERE `id` =" . $clus['ClinicUser']['user_id']);
                            $this->ClinicUser->query("UPDATE `clinic_users` SET `local_points` = 0 WHERE `user_id` =" . $clus['ClinicUser']['user_id'] . " and clinic_id=" . $clus['ClinicUser']['clinic_id']);
                        }
                        //$userdetails = json_decode($this->Api->submit_cURL_Get(Buzzy_Name . '/api/autoassign/' . $id . '.json'));
                    }
                    //if staff not exist for clinic then its creating new staff admin
                    $this->Staff->create();
                    if ($this->request->data['new_password'] != '') {
                        $staff['Staff'] = array('id' => $this->request->data['sid'], 'staff_id' => $this->request->data['staff_id'], 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'staff_password' => $this->request->data['new_password'], 'is_superstaff' => 1);
                    } else {
                        $staff['Staff'] = array('id' => $this->request->data['sid'], 'staff_id' => $this->request->data['staff_id'], 'staff_first_name' => $this->request->data['first_name'], 'staff_last_name' => $this->request->data['last_name'], 'staff_email' => $this->request->data['staff_email'], 'is_superstaff' => 1);
                    }
                    $this->Staff->save($staff);
                    $clientData = $this->Clinic->findById($this->request->data['Clinic']['id']);
                    $this->request->data = $clientData;
                    $admin_set = $this->AdminSetting->find('first', array('conditions' => array('AdminSetting.clinic_id' => $id, 'AdminSetting.setting_type' => 'leadpoints')));
                    if (!empty($admin_set) && $modify == 1) {
                        $set_array['AdminSetting'] = array('id' => $admin_set['AdminSetting']['id'], 'setting_data' => '');
                        $this->AdminSetting->save($set_array);
                    }

                    $this->Session->setFlash('The client has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The client could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Unable to update your credentials.Client Already exists.', 'default', array(), 'bad');
                return false;
            }
        } else {

            $this->request->data = $clientData;
        }
    }

    /**
     * function to find duplicate staff user.
     * @return boolean
     */
    public function checkapiuser() {
        $options1['conditions'] = array('Staff.staff_id' => $_POST['staff_id'], 'Staff.clinic_id ' => $_POST['clinic_id'], 'Staff.id !=' => $_POST['id']);
        $urlResult = $this->Staff->find('all', $options1);

        $options2['conditions'] = array('Staff.staff_email' => $_POST['staff_email'], 'Staff.clinic_id ' => $_POST['clinic_id'], 'Staff.id !=' => $_POST['id']);
        $urlResultemail = $this->Staff->find('all', $options2);
        if (empty($urlResultemail) && empty($urlResult)) {
            echo 0;
        } else if (!empty($urlResultemail) && empty($urlResult)) {
            echo 2;
        } else if (empty($urlResultemail) && !empty($urlResult)) {
            echo 1;
        }

        die;
    }

    /**
     * getting all assigned card for clinic.
     * @return boolean
     */
    public function assigncard($id) {
        $this->layout = "adminLayout";
        $options1['conditions'] = array('CardLog.clinic_id' => $id);
        $cardlog = $this->CardLog->find('all', $options1);
        $this->set('cardlogs', $cardlog);
        $this->set('clinic_id', $id);
    }

    /**
     * add new cards for clinic.
     * @return boolean
     */
    public function addcard($id) {
        $this->layout = "adminLayout";
        $options1['conditions'] = array('CardLog.clinic_id' => $id);
        $options1['order'] = array('CardLog.log_date desc');
        $cardlog = $this->CardLog->find('first', $options1);
        $this->set('clinic_id', $id);
        $this->set('cardlogs', $cardlog);
    }

    /**
     * adding the card log for clinic.
     * @return boolean
     */
    public function addcardlog() {

        $cardlogarr['CardLog']['clinic_id'] = $this->request->data['clinic_id'];
        $cardlogarr['CardLog']['no_of_card'] = $this->request->data['no_of_card'];
        $cardlogarr['CardLog']['range_from'] = $this->request->data['range_from'];
        $cardlogarr['CardLog']['range_to'] = $this->request->data['range_to'];
        $cardlogarr['CardLog']['log_date'] = date('Y-m-d H:i:s');
        $options2['conditions'] = array('CardLog.clinic_id' => $this->request->data['clinic_id'], 'CardLog.range_from' => $this->request->data['range_from'], 'CardLog.range_to' => $this->request->data['range_to']);
        $cardlogcheck = $this->CardLog->find('first', $options2);
        if (empty($cardlogcheck)) {
            $this->CardLog->create();
            if ($this->CardLog->save($cardlogarr)) {

                for ($i = $cardlogarr['CardLog']['range_from']; $i <= $cardlogarr['CardLog']['range_to']; $i++) {
                    $options1['conditions'] = array('CardNumber.clinic_id' => $this->request->data['clinic_id'], 'CardNumber.card_number' => $i);
                    $cardcheck = $this->CardNumber->find('first', $options1);
                    if (empty($cardcheck)) {
                        $this->CardNumber->create();
                        $carddata['CardNumber'] = array('clinic_id' => $this->request->data['clinic_id'], 'card_number' => $i, 'status' => 1);
                        $this->CardNumber->save($carddata);
                    }
                }

                $this->Session->setFlash('Card successfully added', 'default', array(), 'good');
                return $this->redirect('/ClientManagement/assigncard/' . $this->request->data['clinic_id']);
            } else {
                $this->Session->setFlash('ERR:Unable to add Card');
            }
        }
        die;
    }

    /**
     * getting the free card range for clinic.
     * @return boolean
     */
    public function checkcard() {
        $options1['conditions'] = array('CardLog.clinic_id' => $_POST['clinic_id']);
        $cardlog = $this->CardLog->find('all', $options1);
        $rangeto = $_POST['range_from'] + $_POST['no_of_card'] - 1;
        $check = 0;
        $check1 = 0;
        if (!empty($cardlog)) {
            foreach ($cardlog as $clog) {
                if ($_POST['range_from'] >= $clog['CardLog']['range_from'] && $_POST['range_from'] <= $clog['CardLog']['range_to']) {
                    $check = 1;
                }
                if ($rangeto >= $clog['CardLog']['range_from'] && $rangeto <= $clog['CardLog']['range_to']) {
                    $check1 = 1;
                }
            }
            if ($check == 1 || $check1 == 1) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }

        die;
    }

    /**
     * sync the clinic data from steekystreat.
     * @return boolean
     */
    public function sync() {
        $this->layout = "";
        $this->Api->syncClient($this->request->data['client_id']);
        $response = array('result' => 1);
        echo json_encode($response);
        exit;
    }

    /**
     * getting the all treatment plan list.
     * @return boolean
     */
    public function treatmentplans($id) {
        $this->layout = "adminLayout";
        $phaseDistribution = $this->getPhaseDistribution($id);
        $this->set('phaseDistribution', $phaseDistribution);
    }

    /**
     * getting the all treatment plan for clinic.
     * @return boolean
     */
    public function getPhaseDistribution($clinic_id = null) {
        $response = $options = array();

        $options['joins'] = array(
            array(
                'table' => 'phase_distributions',
                'alias' => 'phase_distributions',
                'type' => 'INNER',
                'conditions' => array(
                    'phase_distributions.upper_level_settings_id = UpperLevelSetting.id'
                )
            )
        );

        $options['conditions'] = array(
            'UpperLevelSetting.clinic_id' => $clinic_id,
            'UpperLevelSetting.status' => 1,
            'UpperLevelSetting.soft_delete' => 1
        );

        $options['fields'] = array(
            'phase_distributions.*',
            'UpperLevelSetting.id',
            'UpperLevelSetting.clinic_id',
            'UpperLevelSetting.treatment_name',
            'UpperLevelSetting.total_points',
            'UpperLevelSetting.total_visits',
            'UpperLevelSetting.global_promotion_ids',
            'UpperLevelSetting.bonus_points',
            'UpperLevelSetting.bonus_message'
        );
        $data = $this->UpperLevelSetting->find('all', $options);
        if (!empty($data)) {

            $Promotionlist = array();
            foreach ($data as $val) {
                $globalids = explode(',', $val['UpperLevelSetting']['global_promotion_ids']);
                $prolist = array();
                foreach ($globalids as $ids) {
                    $options6['conditions'] = array(
                        'LevelupPromotion.id' => $ids
                    );
                    $Promotion = $this->LevelupPromotion->find('first', $options6);
                    $prolist[] = $Promotion['LevelupPromotion']['promotion_display_name'];
                }

                $val['UpperLevelSetting']['promotion_names'] = $prolist;
                $response[$val['phase_distributions']['upper_level_settings_id']]['UpperLevelSetting'] = $val['UpperLevelSetting'];
                $response[$val['phase_distributions']['upper_level_settings_id']]['phase_distribution'][] = $val['phase_distributions'];
            }
        }
        return $response;
    }

    /**
     * delete treatment plan for clinic.
     * @return boolean
     */
    public function deleteTreatment() {
        $conditions['conditions'][] = array(
            'TreatmentSetting.upper_level_setting_id' => $_POST['treatment_id'],
            'TreatmentSetting.clinic_id' => $_POST['clinic_id']
        );

        $data = $this->TreatmentSetting->find('all', $conditions);
        if (empty($data)) {
            $this->UpperLevelSetting->deleteAll(array('UpperLevelSetting.id' => $_POST['treatment_id']));
            $this->PhaseDistribution->query("delete from phase_distributions where upper_level_settings_id=" . $_POST['treatment_id']);
        } else {
            $treat['UpperLevelSetting'] = array(
                'id' => $_POST['treatment_id'],
                'clinic_id' => $_POST['clinic_id'],
                'soft_delete' => 0
            );
            $this->UpperLevelSetting->save($treat);
        }
        exit;
    }

    /**
     * fetch access control and modify.
     * @return boolean
     */
    public function staff_access($id) {
        $options3['conditions'] = array(
            'AccessStaff.clinic_id' => $id
        );
        $clients = $this->AccessStaff->find('first', $options3);
        $options3_email['conditions'] = array(
            'EmailTemplate.id' => 42
        );
        $email = $this->EmailTemplate->find('first', $options3_email);
        $this->set('email', $email['EmailTemplate']);
        $this->set('staff_access', $clients);
        $this->layout = "adminLayout";
        if ($this->request->is('post')) {
            $AccessStaffdata['AccessStaff']['id'] = $this->request->data['id'];
            $AccessStaffdata['AccessStaff']['clinic_id'] = $id;
            $AccessStaffdata['AccessStaff']['levelup'] = $this->request->data['levelup'];
            $AccessStaffdata['AccessStaff']['interval'] = $this->request->data['interval'];
            $AccessStaffdata['AccessStaff']['staff_reporting'] = $this->request->data['staff_reporting'];
            $AccessStaffdata['AccessStaff']['staff_reward_program'] = $this->request->data['staff_reward_program'];
            $AccessStaffdata['AccessStaff']['staff_input'] = $this->request->data['staff_input'];
            $AccessStaffdata['AccessStaff']['no_of_plan'] = $this->request->data['no_of_plan'];
            $AccessStaffdata['AccessStaff']['no_of_interval'] = $this->request->data['no_of_interval'];
            $AccessStaffdata['AccessStaff']['product_service'] = $this->request->data['product_service'];
            $AccessStaffdata['AccessStaff']['milestone_reward'] = $this->request->data['milestone_reward'];
            $AccessStaffdata['AccessStaff']['staff_to_redeem'] = $this->request->data['staff_to_redeem'];
            $AccessStaffdata['AccessStaff']['show_documents'] = $this->request->data['show_documents'];
            $AccessStaffdata['AccessStaff']['with_email'] = $this->request->data['with_email'];
            $AccessStaffdata['AccessStaff']['self_registration'] = $this->request->data['self_registration'];
            $AccessStaffdata['AccessStaff']['report'] = $this->request->data['report'];
            if (isset($this->request->data['auto_assign'])) {
                $AccessStaffdata['AccessStaff']['auto_assign'] = $this->request->data['auto_assign'];
            }
            $AccessStaffdata['AccessStaff']['no_of_promotion'] = $this->request->data['no_of_promotion'];
            $AccessStaffdata['AccessStaff']['tier_setting'] = $this->request->data['tier_setting'];
            if ($this->request->data['tier_setting'] == 1 && $clients['AccessStaff']['tier_setting'] == 0 && ($clients['AccessStaff']['tier_start_date'] == '0000-00-00' || $clients['AccessStaff']['tier_start_date'] == '')) {
                $AccessStaffdata['AccessStaff']['tier_start_date'] = date('Y-m-d H:i:s');
                $AccessStaffdata['AccessStaff']['tier_timeframe'] = 365;
                $AccessStaffdata['AccessStaff']['base_value'] = 1;
            }
            if (isset($this->request->data['independent_earning'])) {
                $AccessStaffdata['AccessStaff']['independent_earning'] = $this->request->data['independent_earning'];
            }
            if (isset($this->request->data['rate_review'])) {
                $AccessStaffdata['AccessStaff']['rate_review'] = $this->request->data['rate_review'];
            }
            if (isset($this->request->data['refer'])) {
                $AccessStaffdata['AccessStaff']['refer'] = $this->request->data['refer'];
            }
            if (isset($this->request->data['sms'])) {
                $AccessStaffdata['AccessStaff']['sms'] = $this->request->data['sms'];
            }
            $AccessStaffdata['AccessStaff']['no_of_tier'] = $this->request->data['no_of_tier'];
            $AccessStaffdata['AccessStaff']['show_training_video'] = $this->request->data['show_training_video'];
            $AccessStaffdata['AccessStaff']['amazon_redemption'] = $this->request->data['amazon_redemption'];
            $this->AccessStaff->save($AccessStaffdata);
            //condition to check self registration bonus is set for clinic or not
            if ($this->request->data['self_registration'] == 1) {
                $options['conditions'] = array('Clinic.id' => $id);
                $clinicind = $this->Clinic->find('first', $options);
                $optionsind['conditions'] = array('IndustryPromotion.industry_id' => $clinicind['Clinic']['industry_type'], 'IndustryPromotion.description ' => 'Self Registration Bonus');
                $ind = $this->IndustryPromotion->find('first', $optionsind);
                $optionsdef['conditions'] = array('Promotion.clinic_id' => $id, 'Promotion.description' => 'Self Registration Bonus');
                $getdefaultpro = $this->Promotion->find('first', $optionsdef);
                if (empty($getdefaultpro)) {
                    $proarray['Promotion'] = array('description' => $ind['IndustryPromotion']['description'], 'value' => $ind['IndustryPromotion']['value'], 'operand' => $ind['IndustryPromotion']['operand'], 'display_name' => $ind['IndustryPromotion']['display_name'], 'clinic_id' => $id, 'is_lite' => 0, 'default' => 1, 'category' => 'Bonus Promotions', 'public' => 1, 'is_global' => 0);
                    $this->Promotion->create();
                    $this->Promotion->save($proarray);
                }
            }
            $rtproarray[1]['Promotion'] = array('description' => 'Rate', 'value' => 10, 'operand' => '+', 'display_name' => 'get +10 points for rating', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);
            $rtproarray[2]['Promotion'] = array('description' => 'Review', 'value' => 5, 'operand' => '+', 'display_name' => 'Review and get +5 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);
            $rtproarray[3]['Promotion'] = array('description' => 'Facebook Share', 'value' => 50, 'operand' => '+', 'display_name' => 'Share on Facebook and get +50 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);
            $rtproarray[4]['Promotion'] = array('description' => 'Google Share', 'value' => 50, 'operand' => '+', 'display_name' => 'Share on Google and get +50 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);         
            $rtproarray[5]['Promotion'] = array('description' => 'Yahoo Share', 'value' => 50, 'operand' => '+', 'display_name' => 'Share on Yahoo and get +50 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);         
            $rtproarray[6]['Promotion'] = array('description' => 'Yelp Share', 'value' => 50, 'operand' => '+', 'display_name' => 'Share on Yelp and get +50 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0);         
            $rtproarray[7]['Promotion'] = array('description' => 'Healthgrades Share', 'value' => 50, 'operand' => '+', 'display_name' => 'Share on Healthgrades and get +50 points', 'clinic_id' => $id, 'is_lite' => 0, 'default' => 2, 'category' => '', 'public' => 1, 'is_global' => 0); 
            if ($this->request->data['rate_review'] == 1) {
                $optioncheckrd['conditions'] = array('clinic_id' => $id, 'default' => 2);
                $checkratepromotion = $this->Promotion->find('first', $optioncheckrd);
                if (empty($checkratepromotion)) {
                    foreach ($rtproarray as $rtpro) {
                        $this->Promotion->create();
                        $this->Promotion->save($rtpro);
                    }
                }
            }
            $this->redirect(array('action' => "staff_access/$id"));
        }
    }

    /**
     * find out treatment plan in use or not.
     * @return boolean
     */
    public function checkdeleteTreatment() {
        $conditions['conditions'][] = array(
            'TreatmentSetting.upper_level_setting_id' => $_POST['treatment_id'],
            'TreatmentSetting.clinic_id' => $_POST['clinic_id']
        );

        $i = 0;
        $data = $this->TreatmentSetting->find('all', $conditions);
        if (empty($data)) {
            $i = 1;
        }
        echo $i;
        exit;
    }

    /**
     * delete clinic.
     * @return boolean
     */
    public function delete() {
        $this->layout = "";
        $allusers = $this->ClinicUser->find('all', array(
            'conditions' => array(
                'ClinicUser.clinic_id' => $_POST['client_id']
            )
        ));
        foreach ($allusers as $user) {
            $allusercl = $this->ClinicUser->find('all', array(
                'conditions' => array(
                    'ClinicUser.user_id' => $user['ClinicUser']['user_id']
                )
            ));
            $this->ClinicUser->query('DELETE FROM `clinic_users` WHERE `clinic_users`.`clinic_id` = ' . $_POST['client_id'] . ' and clinic_users.user_id=' . $user['ClinicUser']['user_id']);
            if (count($allusercl) == 1) {
                $this->User->deleteAll(array('User.id' => $user['ClinicUser']['user_id']));
            }
        }
        $this->CardLog->deleteAll(array('CardLog.clinic_id' => $_POST['client_id']));
        $this->CardNumber->deleteAll(array('CardNumber.clinic_id' => $_POST['client_id']));
        $this->AccessControl->deleteAll(array('AccessControl.clinic_id' => $_POST['client_id']));
        $this->AccessStaff->deleteAll(array('AccessStaff.clinic_id' => $_POST['client_id']));
        $this->AdminSetting->deleteAll(array('AdminSetting.clinic_id' => $_POST['client_id']));
        $this->Appointment->deleteAll(array('Appointment.clinic_id' => $_POST['client_id']));
        $this->Badge->deleteAll(array('Badge.clinic_id' => $_POST['client_id']));
        $this->BankAccount->deleteAll(array('BankAccount.clinic_id' => $_POST['client_id']));
        $this->BeanstreamPayment->deleteAll(array('BeanstreamPayment.clinic_id' => $_POST['client_id']));
        $this->ClinicLocation->deleteAll(array('ClinicLocation.clinic_id' => $_POST['client_id']));
        $this->ClinicPromotion->query('DELETE FROM `clinic_promotions` WHERE `clinic_promotions`.`clinic_id` = ' . $_POST['client_id']);
        $this->Clinic_reward->query('DELETE FROM `clinic_rewards` WHERE `clinic_rewards`.`clinic_id` = ' . $_POST['client_id']);
        $this->ContestClinic->query('DELETE FROM `contest_clinics` WHERE `contest_clinics`.`clinic_id` = ' . $_POST['client_id']);
        $this->CouponAvail->deleteAll(array('CouponAvail.clinic_id' => $_POST['client_id']));
        $this->Doctor->deleteAll(array('Doctor.clinic_id' => $_POST['client_id']));
        $this->Document->deleteAll(array('Document.clinic_id' => $_POST['client_id']));
        $this->FacebookLike->query('DELETE FROM `facebook_likes` WHERE `facebook_likes`.`clinic_id` = ' . $_POST['client_id']);
        $this->GlobalRedeem->deleteAll(array('GlobalRedeem.clinic_id' => $_POST['client_id']));
        $this->Invoice->deleteAll(array('Invoice.clinic_id' => $_POST['client_id']));
        $this->LevelupPromotion->deleteAll(array('LevelupPromotion.clinic_id' => $_POST['client_id']));
        $this->MilestoneReward->deleteAll(array('MilestoneReward.clinic_id' => $_POST['client_id']));
        $this->Notification->deleteAll(array('Notification.clinic_id' => $_POST['client_id']));
        $this->PaymentDetail->deleteAll(array('PaymentDetail.clinic_id' => $_POST['client_id']));
        $this->ProductService->deleteAll(array('ProductService.clinic_id' => $_POST['client_id']));
        $this->Promotion->deleteAll(array('Promotion.clinic_id' => $_POST['client_id']));
        $this->RateReview->deleteAll(array('RateReview.clinic_id' => $_POST['client_id']));
        $this->Refer->deleteAll(array('Refer.clinic_id' => $_POST['client_id']));
        $this->Reward->deleteAll(array('Reward.clinic_id' => $_POST['client_id']));
        $this->SaveLike->query('DELETE FROM `save_likes` WHERE `save_likes`.`clinic_id` = ' . $_POST['client_id']);
        $this->Transaction->deleteAll(array('Transaction.clinic_id' => $_POST['client_id']));
        $this->TreatmentSetting->deleteAll(array('TreatmentSetting.clinic_id' => $_POST['client_id']));
        $this->UnregTransaction->deleteAll(array('UnregTransaction.clinic_id' => $_POST['client_id']));
        $this->UpperLevelSetting->deleteAll(array('UpperLevelSetting.clinic_id' => $_POST['client_id']));
        $this->UserAssignedTreatment->deleteAll(array('UserAssignedTreatment.clinic_id' => $_POST['client_id']));
        $this->UserPerfectVisit->deleteAll(array('UserPerfectVisit.clinic_id' => $_POST['client_id']));
        $this->WishList->deleteAll(array('WishList.clinic_id' => $_POST['client_id']));
        $this->Clinic->deleteAll(array('Clinic.id' => $_POST['client_id']));
        $this->Staff->deleteAll(array('Staff.clinic_id' => $_POST['client_id']));

        echo 1;
        exit;
    }

    /**
     * cancelation of downgrade request.
     * @return boolean
     */
    public function cancelDowngrade() {
        $carddata['Clinic'] = array('id' => $_POST['clinic_id'], 'down_date' => '0000-00-00');
        $this->Clinic->save($carddata);

//        $options8['conditions'] = array('Staff.clinic_id' => $_POST['clinic_id'], 'Staff.staff_email !=' => '');
//        $Staff = $this->Staff->find('all', $options8);
//        $allemail = array();
//        foreach ($Staff as $st) {
//            $allemail[] = $st['Staff']['staff_email'];
//        }
//        $checkindustry = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $_POST['clinic_id'])));
//        if (!empty($allemail)) {
//
//            $template_array = $this->Api->get_template(40);
//            $link = str_replace('[clinic_name]', $checkindustry['Clinic']['api_user'], $template_array['content']);
//            $Email = new CakeEmail(MAILTYPE);
//            $Email->from(array(SUPER_ADMIN_EMAIL_STAFF => 'BuzzyDoc'));
//            $Email->to($allemail);
//            $Email->subject($template_array['subject'])
//                    ->template('buzzydocother')
//                    ->emailFormat('html');
//
//            $Email->viewVars(array(
//                'msg' => $link,
//            ));
//            try {
//                $Email->send();
//                echo "mail send to " . $stf['Staff']['staff_email'] . " for Downgrade cancellation";
//            } catch (Exception $e) {
//                CakeLog::write('error', 'Data-Rohitortho' . print_r($stf, true) . '-result-Rohitortho');
//            }
//        }
//
//        $usersget = $this->User->find('all', array(
//            'joins' => array(
//                array(
//                    'table' => 'clinic_users',
//                    'alias' => 'clinic_users',
//                    'type' => 'INNER',
//                    'conditions' => array(
//                        'clinic_users.user_id = User.id'
//                    )
//                )),
//            'conditions' => array(
//                'clinic_users.clinic_id' => $_POST['clinic_id'],
//                'User.points >' => 0,
//                'User.email !=' => ''
//            )
//        ));
//
//        foreach ($usersget as $userem) {
//            if ($userem['User']['first_name'] != '' || $userem['User']['last_name'] != '') {
//                $username = $userem['User']['first_name'] . ' ' . $userem['User']['last_name'];
//            } else {
//                $username = $userem['User']['email'];
//            }
//
//            $template_array = $this->Api->get_template(41);
//            $link = str_replace('[username]', $username, $template_array['content']);
//            $link1 = str_replace('[clinic_name]', $checkindustry['Clinic']['api_user'], $link);
//            $Email = new CakeEmail(MAILTYPE);
//
//            $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
//
//            $Email->to($userem['User']['email']);
//            $Email->subject($template_array['subject'])
//                    ->template('buzzydocother')
//                    ->emailFormat('html');
//            $Email->viewVars(array('msg' => $link1
//            ));
//            $Email->send();
//        }




        die;
    }

}

?>
