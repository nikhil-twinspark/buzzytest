<?php
/**
 *  This file for add,edit and delete product/service/coupon for practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit and delete product/service/coupon for practice.
 */
class ProductAndServiceController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
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
     * These All database model in use.
     * @var type 
     */
    public $uses = array('ProductService', 'User', 'Clinic', 'Transaction', 'Refer', 'BankAccount', 'BeanstreamPayment','MilestoneReward','TrainingVideo','Notification','ClinicUser','RateReview','ClinicNotification');
    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $layout = 'staffLayout';

    /**
     * fetching default value for clinic and store it to session.
     * @return type
     */
    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        //set next free card number for default search
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        $trainingvideo = $this->TrainingVideo->find('all');
        //fetch all training video and set it to session to show at top of the page
        $this->Session->write('staff.trainingvideo', $trainingvideo);
        if (isset($sessionstaff['clinic_id'])) {
            $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
            $options3['fields'] = array('Clinic.analytic_code', 'Clinic.log_time', 'Clinic.lead_log', 'Clinic.id');
            $ClientCredentials = $this->Clinic->find('first', $options3);
            
            if (isset($ClientCredentials)) {
                $staffaceess = $this->Api->accessstaff($sessionstaff['clinic_id']);
                $this->Session->write('staff.staffaccess', $staffaceess);
                    //store analytic code to session
                $this->Session->write('staff.analytic_code', $ClientCredentials['Clinic']['analytic_code']);

            }
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }
    /**
     *  get the list of product/service/coupon.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('ProductService.clinic_id' => $sessionstaff['clinic_id']);
        $options6['order'] = array('ProductService.title ASC');
        $ProductServicelist = $this->ProductService->find('all', $options6);
        $data = array();
        foreach ($ProductServicelist as $plist) {
            $data[] = $plist;
        }

        $this->set('data', array_column($data, 'ProductService'));
        //depricated fetch bank details
        $options7['conditions'] = array('BankAccount.clinic_id' => $sessionstaff['clinic_id']);
        $banklist = $this->BankAccount->find('first', $options7);

        $this->set('BankAccount', $banklist);
        //check the access to staff can use the feature or not
        if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1){
            
        }else{
            return $this->redirect('/PatientManagement/index/');
        }
    }
    /**
     *  add new product/service/coupon for practice.
     */
    public function add() {

        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            $this->ProductService->create();
            $type1 = '';
            if (!empty($this->request->data) && isset($this->request->data['type'])) {
             
                switch ($this->request->data['type']) {
                    case 1:
                        $type1 = "Product";
                        break;
                    case 2:
                        $type1 = "Service";
                        break;
                    case 3:
                        $type1 = "Coupon";
                        break;
                }
            }
            $options['conditions'] = array('ProductService.title' => trim($this->request->data['title'])
                , 'ProductService.type' => $this->request->data['type']
                , 'ProductService.clinic_id' => $sessionstaff['clinic_id']
            );
            $ind = $this->ProductService->find('first', $options);
            //condition to check duplicate product/service/coupon for practice
            if (empty($ind)) {
                //coupon image uploading
                $coupon_image = $this->request->data['productandservices']['coupon_image'];
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = $sessionstaff['api_user'];

                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {

                        if ($type == $coupon_image['type']) {
                                //image file name
                                $couponimageName = $coupon_image['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $couponimageName)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $couponimageName);
                                }
                                //create full path with image name
                                $coupon_full_image_path = $uploadPath . '/' . $couponimageName;
                                //upload image to upload folder
                                if (move_uploaded_file($coupon_image['tmp_name'], $coupon_full_image_path)) {
                                    $response = $this->CakeS3->putObject($coupon_full_image_path, 'img/' . $uploadFolder . '/' . $couponimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($coupon_full_image_path);

                                    $this->request->data['coupon_image'] = 'img/' . $uploadFolder.'/'.$couponimageName;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading coupon image. Please try again.', 'default', array(), 'bad');
                                }
                            
                        }
                        
                    }
                
                    $fromus = 1;
                //condition for coupon image upload
                if (isset($this->request->data['coupon_image']) && !empty($this->request->data['coupon_image'])) {
                $proarra['ProductService'] = array('title' => $this->request->data['title'], 'points' => $this->request->data['points'], 'description' => $this->request->data['description'], 'coupon_image' => $this->request->data['coupon_image'], 'type' => $this->request->data['type'], 'clinic_id' => $sessionstaff['clinic_id'], 'from_us' => $fromus, 'created_at' => date('Y-m-d H:i:s'), 'updated_on' => date('Y-m-d H:i:s'));
                }else{
                 $proarra['ProductService'] = array('title' => $this->request->data['title'], 'points' => $this->request->data['points'], 'description' => $this->request->data['description'], 'type' => $this->request->data['type'], 'clinic_id' => $sessionstaff['clinic_id'], 'from_us' => $fromus, 'created_at' => date('Y-m-d H:i:s'), 'updated_on' => date('Y-m-d H:i:s'));   
                }
                if ($this->ProductService->save($proarra)) {
                    $this->Session->setFlash($type1 . ' successfully added', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to add ' . $type1, 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash($type1 . ' already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit product/service/coupon for practice.
     * @param type $id product/service/coupon id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $ProductAndService = $this->ProductService->find('first', array('conditions' => array('ProductService.id' => $id)));

        $this->set('data', $ProductAndService);
        if (isset($this->request->data['productandservices']['action']) && $this->request->data['productandservices']['action'] == 'update') {

            $options['conditions'] = array('ProductService.title' => trim($this->request->data['title']), 'ProductService.type' => $this->request->data['type'], 'ProductService.id !=' => $this->request->data['id'], 'clinic_id' => $sessionstaff['clinic_id']);
            $ind = $this->ProductService->find('first', $options);
            $type1 = "";
            if (!empty($this->request->data) && isset($this->request->data['type'])) {
                switch ($this->request->data['type']) {
                    case 1:
                        $type1 = "Product";
                        break;
                    case 2:
                        $type1 = "Service";
                        break;
                    case 3:
                        $type1 = "Coupon";
                        break;
                }
            }
            //condition to check duplicate product/service/coupon for practice
            if (empty($ind)) {
                
                    $coupon_image = $this->request->data['productandservices']['coupon_image'];
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = $sessionstaff['api_user'];

                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {

                        if ($type == $coupon_image['type']) {
                                //image file name
                                $couponimageName = time().'_'.$coupon_image['name'];
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $couponimageName)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $couponimageName);
                                }
                                //create full path with image name
                                $coupon_full_image_path = $uploadPath . '/' . $couponimageName;

                                //upload image to upload folder
                                if (move_uploaded_file($coupon_image['tmp_name'], $coupon_full_image_path)) {
                                    $response = $this->CakeS3->putObject($coupon_full_image_path, 'img/' . $uploadFolder . '/' . $couponimageName, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($coupon_full_image_path);

                                    $this->request->data['coupon_image'] = 'img/' . $uploadFolder.'/'.$couponimageName;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading coupon image. Please try again.', 'default', array(), 'bad');
                                }
                            
                        }
                        
                    }
                
               
                    $fromus = 1;
                    if (isset($this->request->data['coupon_image']) && !empty($this->request->data['coupon_image'])) {
                
                $proarra['ProductService'] = array('id' => $this->request->data['id'], 'title' => $this->request->data['title'], 'points' => $this->request->data['points'], 'description' => $this->request->data['description'],'coupon_image'=>$this->request->data['coupon_image'], 'type' => $this->request->data['type'], 'from_us' => $fromus); 
                }else{
                  $proarra['ProductService'] = array('id' => $this->request->data['id'], 'title' => $this->request->data['title'], 'points' => $this->request->data['points'], 'description' => $this->request->data['description'], 'type' => $this->request->data['type'], 'from_us' => $fromus); 
                }

                if ($this->ProductService->save($proarra)) {
                    $this->Session->setFlash('The ' . $type1 . ' has been updated.', 'default', array(), 'good');
                    $this->set('data', $this->request->data);
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The ' . $type1 . ' not updated.', 'default', array(), 'bad');
                    return $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash($type1 . ' already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     *  delete product/service/coupon from practice.
     * @param type $id product/service/coupon id
     * @return type
     */
    public function delete($id) {
      $result = $this->MilestoneReward->find('first', array('conditions' => array('MilestoneReward.coupon_id' => $id)) );
      if(!empty($result)){
          $this->Session->setFlash('The coupon is already in use', 'default', array(), 'bad');
          return $this->redirect(array('action' => 'index'));
      }else{
          if ($this->ProductService->deleteAll(array('ProductService.id' => $id))) {
              $this->Session->setFlash('Item has been deleted.', 'default', array(), 'good');
              return $this->redirect(array('action' => 'index'));
          } else {
              $this->Session->setFlash('ERR:The Item not deleted.', 'default', array(), 'bad');
              return $this->redirect(array('action' => 'index'));
          }
      }
    }

    /**
     * @deprecated
     */
    public function manageOrders() {
        if(DEBIT_FROM_BANK==0){
            $this->render('/Elements/access');
        }
    }
    /**
     * @deprecated
     */
    public function getorders() {
        $sessionstaff = $this->Session->read('staff');
        
        $this->layout = "";
        $response = array('aaData' => array());
        $orders = array_column($this->ProductService->query('SELECT * FROM vw_order_details where clinic_redeem=' . $sessionstaff['clinic_id']), 'vw_order_details');
        $i = 0;

        foreach ($orders as $value) {

            if ($value['STATUS'] == 'Completed') {
                $statusDdl = "<select id='orderStatus_" . $value['id'] . "' disabled><option value='2' selected>Completed</option><option value='1'>Received</option></select>";
            } else {
                $statusDdl = "<select id='orderStatus_" . $value['id'] . "'><option value='1'>Received</option><option value='2'>Completed</option></select>";
            }

            $checked = '';

            $response['aaData'][$i] = array(
                $value['id']
                , $value['name']
                , $value['authorization']
                , $value['amount']
                , number_format($value['other_balance'] / 50, 2, '.', '')
                , $value['order_date']
                , $statusDdl
            );
            $i++;
        }

        echo json_encode($response);
        exit;
    }
    /**
     * @deprecated
     */
    public function changeorderstatus() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = "";
        if (!empty($_POST) && isset($_POST['id']) && isset($_POST['status'])) {
            switch ($_POST['status']) {
                case 2:
                    $status = 'Completed';
                    break;

                default:
                    $status = 'Received';
                    break;
            }
            $_POST['status'] = $status;
            if ($this->Transaction->save($_POST)) {
                $transs = $this->Transaction->find('first', array('conditions' => array('Transaction.id' => $_POST['id'])));

                $users = $this->User->find('first', array('conditions' => array('User.id' => $transs['Transaction']['user_id'])));
                $usersclinic = $this->ClinicUser->find('first', array('conditions' => array('ClinicUser.user_id' => $transs['Transaction']['user_id'],'ClinicUser.clinic_id'=>$sessionstaff['clinic_id'])));

                $pat = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $sessionstaff['clinic_id'])));
                $options2['conditions'] = array('Notification.user_id' => $transs['Transaction']['user_id'], 'Notification.clinic_id' => $sessionstaff['clinic_id'],'Notification.order_status'=>1);
                $Notifications = $this->Notification->find('first', $options2);
                if(empty($Notifications)){
                    $rewardlogin = rtrim($pat['Clinic']['patient_url'], '/') . "/rewards/login/" . base64_encode('redeem') . "/" . base64_encode($usersclinic['ClinicUser']['card_number']) . "/" . base64_encode($transs['Transaction']['user_id'])."/Unsubscribe";
                $template_array = $this->Api->get_template(24);
                $link = str_replace('[click_here]', '<a href="' . rtrim($pat['Clinic']['patient_url'], '/') . '">Click Here</a>', $template_array['content']);
                $link1 = str_replace('[order_number]', $transs['Transaction']['id'], $link);
                $link2 = str_replace('[username]', $users['User']['first_name'], $link1);
                $link3 = str_replace('[status]', $status, $link2);
                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));
                $Email->to($users['User']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');
                $Email->viewVars(array('msg' => $link3
                ));
                $Email->send();
                }
                echo json_encode(array('success' => 1));
            }
        }
        exit;
    }
    /**
     *  This is to show product/service/coupon as public for all staff and on buzzydoc site.
     */
    public function setproductpublic() {
        $this->layout = "";
        $Locations = $this->ProductService->find('first', array('conditions' => array('ProductService.id' => $_POST['pro_id'])));


        if ($Locations['ProductService']['status'] == 1) {
            $like = 0;
        } else {
            $like = 1;
        }

        $this->ProductService->query('UPDATE product_services set status="' . $like . '" where id=' . $Locations['ProductService']['id']);

        exit;
    }
    /**
     * @deprecated
     */
    public function BalanceStatus() {
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('BeanstreamPayment.clinic_id' => $sessionstaff['clinic_id']);
        $options6['order'] = array('BeanstreamPayment.date desc');
        $BeanstreamPayment = $this->BeanstreamPayment->find('all', $options6);
        $this->set('BeanstreamPayment', $BeanstreamPayment);
    }

}

?>
