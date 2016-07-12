<?php

/**
 * 
 * @deprecated no longer in use
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * 
 * @deprecated no longer in use
 */
class CronForS3imageController extends AppController {

    public $components = array('Session', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));
    public $uses = array('User', 'ClinicUser', 'CardNumber', 'Transaction', 'Document', 'Reward', 'patients', 'ProfileField', 'ProfileFieldUser', 'Transaction', 'Clinic', 'Notification', 'Promotion', 'UnregTransaction', 'Staff', 'GoalSetting', 'ProductService', 'StockImage');

    public function index() {

        Configure::write('debug', 1);
        $clinic_detail = array();
        $clinic_detail1 = array();
        $clinic_detail2 = array();
        $i = 0;
        $i1 = 0;
        $i2 = 0;
        
        $prod = $this->Clinic->find('all', array(
                'conditions' => array(
                    'Clinic.id' => $this->request->pass[0])
            ));
        foreach ($prod as $cl) {
            $enddate = date('Y-m-d', strtotime('-6 months'));
            $allclinic = $this->Transaction->query("SELECT Transaction.clinic_id,Transaction.user_id FROM `transactions` AS `Transaction` where Transaction.`date` > '" . $enddate . "' and Transaction.activity_type='N' and Transaction.amount>0 and Transaction.clinic_id=" . $cl['Clinic']['id'] . " group by Transaction.user_id");
            if (count($allclinic) > 0) {
                $clinic_detail[$i]['Clinic'] = $cl['Clinic']['api_user'];
                $clinic_detail[$i]['User_Count'] = count($allclinic);
                $i++;
            }
        }

        foreach ($prod as $cl) {
            $enddate1 = date('Y-m-d', strtotime('-2 years'));
            $allclinic1 = $this->Transaction->query("SELECT Transaction.clinic_id,Transaction.user_id FROM `transactions` AS `Transaction` where Transaction.`date` > '" . $enddate1 . "' and Transaction.amount>0 and Transaction.activity_type='N' and Transaction.clinic_id=" . $cl['Clinic']['id'] . " group by Transaction.user_id");
            if (count($allclinic1) > 0) {
                $clinic_detail1[$i1]['Clinic'] = $cl['Clinic']['api_user'];
                $clinic_detail1[$i1]['User_Count'] = count($allclinic1);
                $i1++;
            }
        }
        $i4=0;
        $i5=0;
        $clinic_detail3=array();
        $clinic_detail4=array();
        foreach ($prod as $cl) {


            $enddate2 = date('Y-m-d', strtotime('-2 years'));
            $allclinic2 = $this->Transaction->query("SELECT Transaction.clinic_id,Transaction.user_id FROM `transactions` AS `Transaction` where Transaction.`date` > '" . $enddate2 . "' and Transaction.activity_type='N' and Transaction.amount>0 and Transaction.clinic_id=" . $cl['Clinic']['id'] . " group by Transaction.user_id");
            if (count($allclinic2) > 0) {
                foreach ($allclinic2 as $all) {
                    $clinic_detail2[$i2] = $all['Transaction']['user_id'];
                    $i2++;
                }
            }
            
            $allclinicuser = $this->ClinicUser->find('all', array(
                'conditions' => array(
                    'ClinicUser.clinic_id' => $cl['Clinic']['id']),
                'fields' => array('ClinicUser.user_id')
            ));
            foreach ($allclinicuser as $alluse){
            if (!in_array($alluse['ClinicUser']['user_id'], $clinic_detail2)) {
                $clinic_detail3[$i4] = $alluse['ClinicUser']['user_id'];
                $i4++;
            }
            }
            if (count($clinic_detail3) > 0) {
                $clinic_detail4[$i5]['Clinic'] = $cl['Clinic']['api_user'];
                $clinic_detail4[$i5]['User_Count'] = count($clinic_detail3);
                $i5++;
            }
        }
        
        echo "<pre>";
        print_r($clinic_detail);
        echo "<pre>";
        print_r($clinic_detail1);
        echo "<pre>";
        print_r($clinic_detail4);die;
        die;
//        $path = 'img/fullcolor-high-rez_logo.jpg'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
//        foreach ($files as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        die('dd');
//                $path = 'css/'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
//        foreach ($files as $fl) {
//            $check_extan=explode('.',$fl);
//            if(end($check_extan)=='css' || end($check_extan)=='less' || end($check_extan)=='scss'){
//                $type='text/css';
//                $expire='86400';
//            }
//            else if(end($check_extan)=='js'){
//                $type='application/x-javascript';
//                $expire='86400';
//            }
//            else if(end($check_extan)=='ttf'){
//                $type='application/x-font-truetype';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='eot'){
//                $type='application/vnd.ms-fontobject';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='woff'){
//                $type='application/font-woff';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='woff2'){
//                $type='application/font-woff2';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='svg'){
//                $type='image/svg+xml';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='otf'){
//                $type='application/x-font-opentype';
//                $expire='86400';
//            }
//            else{
//                $type='text/plan';
//                $expire='31536000';
//            }
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age='.$expire,'Content-Type'=>$type));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        die;
//                $path = 'fonts/'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
//        foreach ($files as $fl) {
//            $check_extan=explode('.',$fl);
//            if(end($check_extan)=='ttf'){
//                $type='application/x-font-truetype';
//            }
//            else if(end($check_extan)=='eot'){
//                $type='application/vnd.ms-fontobject';
//            }
//            else if(end($check_extan)=='woff'){
//                $type='application/font-woff';
//            }
//            else if(end($check_extan)=='woff2'){
//                $type='application/font-woff2';
//            }
//            else if(end($check_extan)=='svg'){
//                $type='image/svg+xml';
//            }
//            else if(end($check_extan)=='otf'){
//                $type='application/x-font-opentype';
//            }
//            else {
//                $type='text/plan';
//            }
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=86400','Content-Type'=>$type));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        
//        die;
        //upload static images to s3 server
//        $path = 'img/*.jpg'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
//        foreach ($files as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path1 = 'img/*.png'; // file filter, you could specify a extension using *.ext
//        $files1 = explode("\n", trim(`find -L $path1`)); // -L follows symlinks
//        foreach ($files1 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path2 = 'img/*.jpeg'; // file filter, you could specify a extension using *.ext
//        $files2 = explode("\n", trim(`find -L $path2`)); // -L follows symlinks
//        foreach ($files2 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path3 = 'img/*.gif'; // file filter, you could specify a extension using *.ext
//        $files3 = explode("\n", trim(`find -L $path3`)); // -L follows symlinks
//        foreach ($files3 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path4 = 'img/BuzzyDoc_emailers/'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path4`)); // -L follows symlinks
//        foreach ($files4 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path5 = 'img/buzzydoc-user/'; // file filter, you could specify a extension using *.ext
//        $files5 = explode("\n", trim(`find -L $path5`)); // -L follows symlinks
//        foreach ($files5 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path6 = 'img/images_buzzy/'; // file filter, you could specify a extension using *.ext
//        $files6 = explode("\n", trim(`find -L $path6`)); // -L follows symlinks
//        foreach ($files6 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        $path7 = 'img/reward_imges/'; // file filter, you could specify a extension using *.ext
//        $files7 = explode("\n", trim(`find -L $path7`)); // -L follows symlinks
//        foreach ($files7 as $fl) {
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age=31536000','Content-Type'=>'text/plan'));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
//        die;
        //upload static js to s3 server
//        $path = 'js/'; // file filter, you could specify a extension using *.ext
//        $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
//        foreach ($files as $fl) {
//            $check_extan=explode('.',$fl);
//            if(end($check_extan)=='css' || end($check_extan)=='less' || end($check_extan)=='scss'){
//                $type='text/css';
//                $expire='86400';
//            }
//            else if(end($check_extan)=='js'){
//                $type='application/x-javascript';
//                $expire='86400';
//            }
//            else if(end($check_extan)=='ttf'){
//                $type='application/x-font-truetype';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='eot'){
//                $type='application/vnd.ms-fontobject';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='woff'){
//                $type='application/font-woff';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='woff2'){
//                $type='application/font-woff2';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='svg'){
//                $type='image/svg+xml';
//                 $expire='86400';
//            }
//            else if(end($check_extan)=='otf'){
//                $type='application/x-font-opentype';
//                $expire='86400';
//            }
//            else{
//                $type='text/plan';
//                $expire='31536000';
//            }
//            $uploadPath = WWW_ROOT . $fl;
//            $response = $this->CakeS3->putObject($uploadPath, $fl, $this->CakeS3->permission('public_read_write'), array('Cache-Control' => 'max-age='.$expire,'Content-Type'=>$type));
//            $sharingImageUrl = $response['url'];
//            echo "<pre>";
//            print_r($response);
//        }
        //upload static css to s3 server

        die;
        //        $prod = $this->ProductService->find('all');
//        foreach ($prod as $pr) {
//            $coupon_img = explode('integrateortho_prod/', $pr['ProductService']['coupon_image']);
//            $coparray['id'] = $pr['ProductService']['id'];
//            if (isset($coupon_img[1])) {
//                $coparray['coupon_image'] = $coupon_img[1];
//            }
//            $this->ProductService->save($coparray);
//        }
//        die;
//        $allclinic = $this->Clinic->find('all');
//        foreach ($allclinic as $clinic) {
//            $staff_lg = explode('integrateortho_prod/', $clinic['Clinic']['staff_logo_url']);
//            $patient_lg = explode('integrateortho_prod/', $clinic['Clinic']['patient_logo_url']);
//            $buzzy_lg = explode('integrateortho_prod/', $clinic['Clinic']['buzzydoc_logo_url']);
//            $back_lg = explode('integrateortho_prod/', $clinic['Clinic']['backgroud_image_url']);
//            $question_lg = explode('integrateortho_prod/', $clinic['Clinic']['patient_question_mark']);
//            $datatrans['id'] = $clinic['Clinic']['id'];
//            if(isset($staff_lg[1])){
//            $datatrans['staff_logo_url'] = $staff_lg[1];
//            }
//            if(isset($patient_lg[1])){
//            $datatrans['patient_logo_url'] = $patient_lg[1];
//            }
//            if(isset($buzzy_lg[1])){
//            $datatrans['buzzydoc_logo_url'] = $buzzy_lg[1];
//            }
//            if(isset($back_lg[1])){
//            $datatrans['backgroud_image_url'] = $back_lg[1];
//            }
//            if(isset($question_lg[1])){
//            $datatrans['patient_question_mark'] = $question_lg[1];
//            }
//            $this->Clinic->save($datatrans);
//        }
//        die;
//        $allclinic = $this->ClinicUser->find('all', array(
//            'joins' => array(
//                array(
//                    'table' => 'clinics',
//                    'alias' => 'Clinic',
//                    'type' => 'INNER',
//                    'conditions' => array(
//                        'Clinic.id = ClinicUser.clinic_id'
//                    )
//                )
//            ),
//            'conditions' => array(
//                'Clinic.is_buzzydoc' => 1),
//            'fields' => array('ClinicUser.*')
//        ));
//        $find_array = array();
//        $n==0;
//        foreach ($allclinic as $alluser) {
//            $unreg['conditions'] = array('UnregTransaction.card_number' => $alluser['ClinicUser']['card_number'], 'UnregTransaction.clinic_id' => $alluser['ClinicUser']['clinic_id'], 'UnregTransaction.amount >' => 0);
//            $find = $this->UnregTransaction->find('first', $unreg);
//            if (!empty($find)) {
//                $find_array[$n]['user_id'] = $alluser['ClinicUser']['user_id'];
//                $find_array[$n]['clinic_id'] = $alluser['ClinicUser']['clinic_id'];
//                $n++;
//            }
//        }
//        echo "<pre>";
//        print_r($find_array);
//        die;

        $allclinic = $this->ClinicUser->find('all', array(
            'joins' => array(
                array(
                    'table' => 'clinics',
                    'alias' => 'Clinic',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Clinic.id = ClinicUser.clinic_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = ClinicUser.user_id'
                    )
                )
            ),
            'conditions' => array(
                'Clinic.is_buzzydoc' => 1),
            'fields' => array('ClinicUser.*', 'User.*')
        ));
        $find_array = array();
        $n == 0;
        foreach ($allclinic as $alluser) {
            $unreg['conditions'] = array('UnregTransaction.card_number' => $alluser['ClinicUser']['card_number'], 'UnregTransaction.clinic_id' => $alluser['ClinicUser']['clinic_id']);
            $alltrans = $this->UnregTransaction->find('all', $unreg);
            if (!empty($alltrans)) {
                //if card number have transaction then copy to new users account.
                foreach ($alltrans as $newtran) {
                    $datatrans['user_id'] = $alluser['ClinicUser']['user_id'];
                    $datatrans['staff_id'] = $newtran['UnregTransaction']['staff_id'];
                    $datatrans['card_number'] = $alluser['ClinicUser']['card_number'];
                    $datatrans['first_name'] = $alluser['User']['first_name'];
                    $datatrans['last_name'] = $alluser['User']['last_name'];
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
                    $this->UnregTransaction->deleteAll(array(
                        'UnregTransaction.id' => $newtran['UnregTransaction']['id'],
                        false
                    ));
                }
                $gettrans = $this->Transaction->find('first', array(
                    'conditions' => array(
                        'Transaction.user_id' => $alluser['ClinicUser']['user_id'],
                        'Transaction.is_buzzydoc' => 1
                    ),
                    'fields' => array(
                        'SUM(Transaction.amount) AS points'
                    ),
                    'group' => array(
                        'Transaction.user_id'
                    )
                ));
                if (empty($gettrans)) {
                    $points = 0;
                } else {
                    $points = $gettrans[0]['points'];
                }

                $this->User->updateAll(array(
                    'User.points' => $points
                        ), array(
                    'User.id' => $alluser['ClinicUser']['user_id']
                ));
            }
        }
        die;
        $optionscl['field'] = array('Clinic.id', 'Clinic.is_lite');
        $allclinic = $this->Clinic->find('all', $optionscl);
        foreach ($allclinic as $clinic) {

            $optionscat['conditions'] = array('Promotion.category !=' => '', 'Promotion.is_lite !=' => 1, 'Promotion.clinic_id' => $clinic['Clinic']['id']);
            $optionscat['order'] = array('Promotion.description' => 'asc', 'Promotion.category' => 'desc');
            $promotionwithcat = $this->Promotion->find('all', $optionscat);
            $prid = array();
            foreach ($promotionwithcat as $prct) {
                if ($prct['Promotion']['is_lite'] != 1) {
                    $prid[] = $prct['Promotion']['id'];
                }
                $options['conditions'] = array('NOT' => array('Promotion.id' => $prid), 'Promotion.is_lite !=' => 1, 'Promotion.description like' => '*%', 'Promotion.clinic_id' => $clinic['Clinic']['id']);
                $options['order'] = array('Promotion.description' => 'asc');
                $promotion = $this->Promotion->find('all', $options);


                foreach ($promotion as $pr) {
                    if ($pr['Promotion']['is_lite'] != 1) {
                        $prid[] = $pr['Promotion']['id'];
                    }
                }
                $options1['conditions'] = array('NOT' => array('Promotion.id' => $prid), 'Promotion.clinic_id' => $clinic['Clinic']['id']);
                $options1['order'] = array('Promotion.description' => 'asc');
                $promotion1 = $this->Promotion->find('all', $options1);
                $promotion2 = array();
                foreach ($promotion1 as $pr1) {
                    if ($pr1['Promotion']['is_lite'] != 1) {
                        $promotion2[] = $pr1;
                    }
                }

                $promotion22 = array_merge_recursive($promotionwithcat, $promotion, $promotion2);
                $promotion11 = array_unique($promotion22, SORT_REGULAR);
                $i = 1;
                foreach ($promotion11 as $value) {
                    $this->Promotion->query('UPDATE promotions set sort="' . $i . '" where id=' . $value['Promotion']['id']);
                    $i++;
                }
                echo "updated ordering <br>";
            }
        }

        die;
        $clinic = ClassRegistry::init('clinics');
        $cln = $clinic->find('all', array('fields' => array('id', 'api_user', 'patient_url', 'staff_logo_url', 'patient_logo_url', 'backgroud_image_url', 'patient_question_mark', 'challenge_header_image'), 'conditions' => array('clinics.id' => 1)));
        foreach ($cln as $clnc) {
            $uploadFolder = $clnc['clinics']['api_user'];

            //upload staff logo on s3
            $pos = strpos($clnc['clinics']['staff_logo_url'], 'integrateortho_prod');
            if ($pos === false) {
                $uploadfrom = WWW_ROOT . ltrim($clnc['clinics']['staff_logo_url'], '/');
                $slName = $clnc['clinics']['api_user'] . "_staff_logo";
                $response1 = $this->CakeS3->putObject($uploadfrom, 'img/' . $uploadFolder . '/' . $slName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl1 = $response1['url'];
                echo "<br>";
                //@unlink($uploadfrom);
                $this->Clinic->query("UPDATE `clinics` SET `staff_logo_url` = '" . $sharingImageUrl1 . "' WHERE `clinics`.`id` =" . $clnc['clinics']['id']);
            }

            //upload patien logo on s3
            $pos1 = strpos($clnc['clinics']['patient_logo_url'], 'integrateortho_prod');
            if ($pos1 === false) {
                $plName = $clnc['clinics']['api_user'] . "_patient_logo";
                $uploadfrom1 = WWW_ROOT . ltrim($clnc['clinics']['patient_logo_url'], '/');
                $response2 = $this->CakeS3->putObject($uploadfrom1, 'img/' . $uploadFolder . '/' . $plName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl2 = $response2['url'];
                echo "<br>";
                //@unlink($uploadfrom1);
                $this->Clinic->query("UPDATE `clinics` SET `patient_logo_url` = '" . $sharingImageUrl2 . "' WHERE `clinics`.`id` =" . $clnc['clinics']['id']);
            }

            //upload background image on s3
            $pos2 = strpos($clnc['clinics']['backgroud_image_url'], 'integrateortho_prod');
            if ($pos2 === false) {
                $backgroudimageName = $clnc['clinics']['api_user'] . "_background_image";
                $uploadfrom2 = WWW_ROOT . ltrim($clnc['clinics']['backgroud_image_url'], '/');
                $response = $this->CakeS3->putObject($uploadfrom2, 'img/' . $uploadFolder . '/' . $backgroudimageName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl = $response['url'];
                echo "<br>";
                //@unlink($uploadfrom2);
                $this->Clinic->query("UPDATE `clinics` SET `backgroud_image_url` = '" . $sharingImageUrl . "' WHERE `clinics`.`id` =" . $clnc['clinics']['id']);
            }

            //upload question mark image on s3
            $pos3 = strpos($clnc['clinics']['patient_question_mark'], 'integrateortho_prod');
            if ($pos3 === false) {
                $pqName = $clnc['clinics']['api_user'] . "_question_image";
                $uploadfrom3 = WWW_ROOT . ltrim($clnc['clinics']['patient_question_mark'], '/');
                $response3 = $this->CakeS3->putObject($uploadfrom3, 'img/' . $uploadFolder . '/' . $pqName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl3 = $response3['url'];
                echo "<br>";
                //@unlink($uploadfrom3);
                $this->Clinic->query("UPDATE `clinics` SET `patient_question_mark` = '" . $sharingImageUrl3 . "' WHERE `clinics`.`id` =" . $clnc['clinics']['id']);
            }

            //upload challenge header image on s3
            $pos4 = strpos($clnc['clinics']['challenge_header_image'], 'integrateortho_prod');
            if ($pos4 === false) {
                $chName = $clnc['clinics']['api_user'] . "_challenge_header_image";
                $uploadfrom4 = WWW_ROOT . ltrim($clnc['clinics']['challenge_header_image'], '/');
                $response4 = $this->CakeS3->putObject($uploadfrom4, 'img/' . $uploadFolder . '/' . $chName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl4 = $response4['url'];
                echo "<br>";
                //@unlink($uploadfrom4);
                $this->Clinic->query("UPDATE `clinics` SET `challenge_header_image` = '" . $sharingImageUrl4 . "' WHERE `clinics`.`id` =" . $clnc['clinics']['id']);
            }
            echo "<br>";
            echo 'image uploaded for ' . $uploadFolder . ' on s3';
            echo "<br>";
        }



        $dc = ClassRegistry::init('documents');
        $doc = $dc->find('all', array('fields' => array('id', 'document', 'clinic_id'), 'conditions' => array('documents.clinic_id' => 1)));

        foreach ($doc as $docs) {
            $clinic = ClassRegistry::init('clinics');
            $cln = $clinic->find('first', array('fields' => array('api_user'), 'conditions' => array('id' => $docs['documents']['clinic_id'])));
            $uploadFolder = 'img/rewards/' . $cln['clinics']['api_user'] . '/doc';
            $uploadPath = WWW_ROOT . $uploadFolder;
            $pos4 = strpos($docs['documents']['document'], 'integrateortho_prod');
            if ($pos4 === false) {
                $imgn = explode('/', $docs['documents']['document']);
                $rdName = end($imgn);

                $ch_path = $uploadPath . '/' . $rdName;
                $response4 = $this->CakeS3->putObject($ch_path, 'img/rewards/' . $cln['clinics']['api_user'] . '/doc/' . $rdName, $this->CakeS3->permission('public_read_write'));
                echo $sharingImageUrl4 = $response4['url'];
                echo "<br>";
                //@unlink($ch_path);
                $this->Document->query("UPDATE `documents` SET `document` = '" . $sharingImageUrl4 . "' WHERE `documents`.`id` =" . $docs['documents']['id']);
            }

            echo "<br>";
            echo 'doc uploaded for ' . $cln['clinics']['api_user'] . ' on s3';
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
        }
        die;
        /*

         * 
          $rd = ClassRegistry::init('rewards');
          $rewards=$rd->find('all',array('fields'=>array('id','clinic_id','imagepath'),'conditions'=>array('imagepath LIKE'=>'%/img/%')));

          foreach($rewards as $reward){

          if ($reward['rewards']['clinic_id']==0) {

          $imgn=explode('/',$reward['rewards']['imagepath']);
          $rdName = str_replace(' ','_',end($imgn));
          $rd_path = WWW_ROOT. ltrim($reward['rewards']['imagepath'],'/');

          $response5 = $this->CakeS3->putObject($rd_path, 'img/rewards/Global/' . $rdName, $this->CakeS3->permission('public_read_write'));
          $sharingImageUrl5 = $response5['url'];
          @unlink($rd_path);
          $this->Reward->query("UPDATE `rewards` SET `imagepath` = '".$sharingImageUrl5."' WHERE `rewards`.`id` =".$reward['rewards']['id']);

          }
          }
         * 
         * 
         * 
          $path = 'img/*'; // file filter, you could specify a extension using *.ext
          $files = explode("\n", trim(`find -L $path`)); // -L follows symlinks
          echo "<pre>";
          print_r($files);
          die;
          $response = $this->CakeS3->putObject(WWW_ROOT.$imagepath, 'img/rewards/'.$uploadFolder. '/' . $img_fileName, $this->CakeS3->permission('public_read_write'));
          $sharingImageUrl = $response['url'];
          @unlink($imagepath);
          $data['imagepath'] = $sharingImageUrl;


          $trans=  ClassRegistry::init('transactions');
          $transdeta=$trans->query('SELECT t.user_id, SUM( t.amount ) - u.points AS difference FROM `transactions` AS t INNER JOIN users AS u ON u.id = t.user_id WHERE 1 GROUP BY user_id ORDER BY difference DESC LIMIT 0 , 470');
          foreach($transdeta as $trn){
          $cln=$this->User->find('first', array(
          'conditions' => array(
          'User.id' => $trn['t']['user_id']
          )));

          $points=$cln['User']['points']+$trn[0]['difference'];
          $users_pnt = array(
          "User" => array(

          "id"=>$trn['t']['user_id'],
          "points"=>$points
          )

          );
          $this->User->save($users_pnt);
          }
          die;

          $cardno = ClassRegistry::init('card_numbers');
          $cdno=$cardno->find('all', array(
          'conditions' => array(
          'card_number.status' =>1
          )));

          foreach($cdno as $alcard){


          $records = array(
          "User" => array(
          'status'=>1,
          'is_verified'=>1

          )

          );

          $this->User->create();
          $this->User->save($records);
          $user_id=$this->User->getLastInsertId();


          $this->ClinicUser->query("INSERT INTO `clinic_users` (`clinic_id`, `user_id`, `card_number`, `facebook_like_status`, `facebook_email`) VALUES ('".$alcard['card_numbers']['clinic_id']."', '".$user_id."', '".$alcard['card_numbers']['card_number']."', NULL, NULL)");


          $profile_id=$this->ProfileField->find('all',array('conditions'=>array('ProfileField.clinic_id'=>0)));
          foreach($profile_id as $pfield){


          $records_pf_vl = array(
          "ProfileFieldUser" => array(
          "user_id" => $user_id,
          "profilefield_id"=>$pfield['ProfileField']['id'],
          "value"=>'',
          "clinic_id"=>0
          )

          );

          $this->ProfileFieldUser->save($records_pf_vl);

          }




          }
          die;

          //for new patients
          $patients = ClassRegistry::init('patients');
          $ap=$patients->find('all');
          foreach($ap as $pat){
          $cc = ClassRegistry::init('client_credentials');
          $cc_det=$cc->find('first', array(
          'conditions' => array(
          'client_credentials.api_user' => $pat['patients']['client_id']
          )));
          $cg = ClassRegistry::init('campaigns');
          $campg=$cg->find('first', array(
          'conditions' => array(
          'campaigns.client_id' => $pat['patients']['client_id']
          )));
          $url_history='https://api.clienttoolbox.com/?user_id='.$cc_det['client_credentials']['api_user'].'&user_password='.$cc_det['client_credentials']['api_key'].'&account_id='.$cc_det['client_credentials']['accountId'].'&type=balance&campaign_id='.$campg['campaigns']['campaign_id'].'&code='.$pat['patients']['code'];
          $result_history=file_get_contents($url_history);
          try {
          $allhistory 	= new SimpleXMLElement(str_replace('&','&amp;',$result_history));
          }
          catch(Exception $e) {
          CakeLog::write('error', 'Data-Rohitortho-'.$url_history.'-resul-Rohitortho'.print_r($allhistory, true) );

          }



          $records = array(
          "User" => array(
          "custom_date"=>$pat['patients']['custom_date'],
          "email"=>$pat['patients']['email'],
          "parents_email"=>$pat['patients']['parents_email'],
          "first_name"=>$pat['patients']['first_name'],
          "last_name"=>$pat['patients']['last_name'],
          'customer_password'=>$pat['patients']['customer_password'],
          'points'=>0,
          "enrollment_stamp"=>$pat['patients']['enrollment_stamp'],
          'facebook_id'=>$pat['patients']['facebook_id'],
          'is_facebook'=>$pat['patients']['is_facebook'],

          'status'=>$pat['patients']['status'],
          'is_verified'=>$pat['patients']['is_varified'],
          'blocked'=>$pat['patients']['blocked']
          )

          );
          $this->User->create();
          if ($this->User->save($records)){
          $cln=$this->Clinic->find('first', array(
          'conditions' => array(
          'Clinic.api_user' => $pat['patients']['client_id']
          )));
          $this->CardNumber->query("UPDATE `card_numbers` SET `status` = 2  WHERE `clinic_id` =".$cln['Clinic']['id']." and card_number='".$pat['patients']['card_number']."'");
          echo "User inserted -".$pat['patients']['email'].'<br>';
          }
          $user_id=$this->User->getLastInsertId();


          if (isset($allhistory) && (string)$allhistory->attributes()->status == 'success') {

          if(isset($allhistory->campaign->customer->transactions)){
          foreach($allhistory->campaign->customer->transactions->transaction as $trans){




          if($trans->redeemed=='Y'){
          $st='Redeemed';
          }else{
          $st='New';
          }
          $rd_transaction = array(
          "Transaction" => array(
          'user_id'=>$user_id,
          'card_number'=>$allhistory->campaign->customer->card_number,
          'first_name'=>$allhistory->campaign->customer->first_name,
          'last_name'=>$allhistory->campaign->customer->last_name,

          'activity_type'=>$trans->redeemed,
          'authorization'=>$trans->authorization,
          'amount'=>$trans->amount,
          'clinic_id'=>$cln['Clinic']['id'],
          'date'=>$trans->record_timestamp,

          'status'=>$st
          )

          );
          $this->Transaction->create();
          if ($this->Transaction->save($rd_transaction)){
          echo "transaction inserted for ".$user_id.'<br>';
          }

          }
          }
          }

          $alltrans=$this->Transaction->find('first',
          array(
          'conditions'=>array(
          'Transaction.user_id'=>$user_id,
          'Transaction.clinic_id'=>$cln['Clinic']['id']
          ),
          'fields'=>array(
          'SUM(Transaction.amount) AS points'
          ),
          'group' => array(
          'Transaction.user_id'
          )));
          if(empty($alltrans)){
          $points=0;
          }else{
          $points=$alltrans[0]['points'];
          }

          $clinicusers_vl = array(
          "ClinicUser" => array(
          "clinic_id" => $cln['Clinic']['id'],
          "user_id"=>$user_id,
          "card_number"=>$pat['patients']['card_number'],
          'facebook_like_status'=>0
          )

          );

          if ($this->ClinicUser->save($clinicusers_vl)){

          $users_pnt = array(
          "User" => array(

          "id"=>$user_id,
          "points"=>$points
          )

          );
          $this->User->save($users_pnt);
          ///hear put the mail for change your password
          echo $pat['patients']['card_number'].'-clinic_users table has been initialized'.'<br>';
          }

          foreach($pat['patients'] as $val=>$index){


          $profile_id=$this->ProfileField->find('first',array('conditions'=>array('ProfileField.profile_field'=>$val)));
          if(!empty($profile_id)){

          $records_pf_vl = array(
          "ProfileFieldUser" => array(
          "user_id" => $user_id,
          "profilefield_id"=>$profile_id['ProfileField']['id'],
          "value"=>$index,
          "clinic_id"=>0
          )

          );

          if ($this->ProfileFieldUser->save($records_pf_vl)){
          echo 'profile_field_users table has been initialized'.'<br>';
          }

          }
          }
          echo '<br><br><br><br><br><br>';
          $patientsdel = ClassRegistry::init('patients');
          $patientsdel->deleteAll(array('patients.id'=>$pat['patients']['id']));
          }
          die;
         * 
         */
        /*
          $allpatients = $this->User->find('all');
          foreach($allpatients as $apat){
          $ClinicUser = $this->ClinicUser->find('all',array('conditions'=>array('ClinicUser.user_id'=>$apat['User']['id'])));
          foreach($ClinicUser as $CU){
          if($apat['User']['first_name']=='' && $apat['User']['last_name']==''){
          $this->CardNumber->query("UPDATE `card_numbers` SET `status` = 1 WHERE `clinic_id` =".$CU['ClinicUser']['clinic_id']." and card_number='".$CU['ClinicUser']['card_number']."'");
          $this->User->deleteAll(array('User.id'=>$apat['User']['id']));
          $this->ClinicUser->query("delete from clinic_users where user_id=".$apat['User']['id']);

          }else{
          $this->CardNumber->query("UPDATE `card_numbers` SET `status` = 2 WHERE `clinic_id` =".$CU['ClinicUser']['clinic_id']." and card_number='".$CU['ClinicUser']['card_number']."'");
          }
          }

          }


          die;

          $file = fopen("Admin Credentials Admin 6_14_14 - Sheet1.csv","r");
          $staffarray=array();
          while(! feof($file))
          {
          $staffarray[]=fgetcsv($file);
          }
          $i=0;
          foreach($staffarray as $stf){
          if($i>0){

          $cl = ClassRegistry::init('Clinic');
          $options['conditions'] = array('Clinic.api_user'=>$stf[0]);
          $clnic = $cl->find('first',$options);
          if(!empty($clnic)){
          $ind = ClassRegistry::init('industry_type');
          $options1['conditions'] = array('industry_type.name'=>$stf[1]);
          $industry_type = $ind->find('first',$options1);
          $staffs_array['clinics']=array(
          'id'=>$clnic['Clinic']['id'],
          'industry_type'=>$industry_type['industry_type']['id']
          );

          $stf = ClassRegistry::init('clinics');
          $stf->save($staffs_array);
          }
          }
          $i++;
          }
          fclose($file);
          die; */
    }

}

?>
