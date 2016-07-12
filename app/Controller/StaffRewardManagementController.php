<?php
/**
 * This file for add,edit,delete publish new local rewards using amazon and custom.
 * this is basically use for only legacy pratice because buzzyodc practice not have reward program.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add,edit,delete local rewards using amazon and custom.
 * There is the option to publish global rewards for local use.
 */
class StaffRewardManagementController extends AppController {
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
    public $components = array('Session','Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'User', 'Category', 'Reward', 'Clinic_reward', 'Notification', 'Clinic', 'Transaction', 'Refer','TrainingVideo','ClinicNotification');
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
        $getfreecard = $this->Api->get_freecardDetails($sessionstaff['clinic_id']);
        $this->set('FreeCardDetails', $getfreecard);
        $getallnotifications = $this->ClinicNotification->getNotification($sessionstaff['clinic_id']);
        $this->Session->write('staff.AllNotifications', $getallnotifications);
        if (isset($sessionstaff['clinic_id'])) {
            $options3['conditions'] = array('Clinic.id' => $sessionstaff['clinic_id']);
            $options3['fields'] = array('Clinic.analytic_code', 'Clinic.log_time', 'Clinic.lead_log', 'Clinic.id');
            $ClientCredentials = $this->Clinic->find('first', $options3);
        }
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }
    /**
     * This is default index page for this module.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Rewards');
        //checking the access for pratice.
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }
    /**
     * Action for add new local reward page.
     * @param type $id
     */
    public function addlocalreward($id = null) {
        //$this->layout='';
        if (isset($id)) {
            //fetch the local reward for display info.
            $rewardInfo = $this->Reward->find('first', array('conditions' => array('Reward.id' => $id)));
            $this->set('rewardInfo', $rewardInfo);
        }
        //get the list of all category for rewards.
        $categoryresult = $this->Category->find('all', array('conditions' => array('Category.status' => 1)));
        $this->set('category', $categoryresult);
    }
    /**
     * Add new local reward after form submit.
     * @return type
     */
    public function postlocalreward() { //submit add local reward form
        $this->layout = '';
        $sessionstaff = $this->Session->read('staff');

        if ($this->request->is('post')) {

            if (($this->request->data['reward_name'] != '') && ($this->request->data['reward_point'] != '') && ($this->request->data['reward_category'] != '')) {
                if ($this->request->data['product_type'] == 'in-office') {
                    $image = $this->request->params['form']['reward_image'];
                }
                $img_dir = '';
                $img_fileName = '';
                $imagepath = '';
                $imazon_product_id = '';

                //condition to upload reward image.
                if (isset($image) && ($image["size"] > 0) && ($this->request->data['product_type'] == 'in-office')) {
                    $date = strtotime(date('m/d/Y h:i:s a', time()));
                    $uploadFolder = $sessionstaff['api_user'];
                    $uploadPath = WWW_ROOT . 'img/rewards/' . $uploadFolder . '/';
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    $img_dir = $uploadPath;
                    $img = explode('.', $image["name"]);

                    $image_filePath = $image['tmp_name'];
                    $img_fileName = $date . "." . end($img);
                    $img_thumb = $img_dir . $img_fileName;
                    $extension = strtolower(end($img));
                    $imagepath = $this->webroot . 'img/rewards/' . $uploadFolder . '/' . $img_fileName;
                    //checking the extension for image file and validate.
                    if (in_array($extension, array('jpg', 'jpeg', 'gif', 'png', 'bmp'))) {

                        list($gotwidth, $gotheight, $gottype, $gotattr) = getimagesize($image_filePath);
                        //---------- To create thumbnail of image---------------
                        if ($extension == "jpg" || $extension == "jpeg") {
                            $src = imagecreatefromjpeg($image['tmp_name']);
                        } else if ($extension == "png") {
                            $src = imagecreatefrompng($image['tmp_name']);
                        } else {
                            $src = imagecreatefromgif($image['tmp_name']);
                        }
                        list($width, $height) = getimagesize($image['tmp_name']);
                        if ($gotwidth >= 150) {
                            $newwidth = 150;
                        } else {
                            $newwidth = $gotwidth;
                        }
                        $newheight = 200;
                        //$newheight=round(($gotheight*$newwidth)/$gotwidth);
                        $tmp = imagecreatetruecolor($newwidth, $newheight);
                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                        $img_result = $createImageSave = imagejpeg($tmp, $img_thumb, 100);
                    } else {
                        $this->Session->setFlash('Unacceptable file type', 'default', array(), 'bad');
                        if (isset($this->request->data['edit_id'])) {
                            return $this->redirect('/StaffRewardManagement/addlocalreward/' . $this->request->data['edit_id']);
                        } else {
                            $this->redirect(array('action' => 'addlocalreward'));
                        }
                    }
                } elseif ($this->request->data['product_type'] == 'amazon') {
                    $imagepath = $this->request->data['amazon_product_url'];
                    $imazon_product_id = $this->request->data['amazon_id'];
                }
                //insert
                $data = array();
                if ($this->request->data['edit_id'] != '') {
                    $data['id'] = $this->request->data['edit_id'];
                }

                $data['points'] = $this->request->data['reward_point'];
                $data['description'] = $this->request->data['reward_name'];
                $data['category'] = $this->request->data['reward_category'];

                if ($imagepath != '' && $this->request->data['product_type'] == 'in-office') {

                    //uploading the reward image on S3 server.
                    $response = $this->CakeS3->putObject(WWW_ROOT . $imagepath, 'img/rewards/' . $uploadFolder . '/' . $img_fileName, $this->CakeS3->permission('public_read_write'));
                    $sharingImageUrl = $response['url'];
                    @unlink($imagepath);
                    $data['imagepath'] = $sharingImageUrl;
                } else {
                    $data['imagepath'] = $imagepath;
                }

                $data['amazon_id'] = $imazon_product_id;
                $data['clinic_id'] = $sessionstaff['clinic_id'];

                $saveid = $this->Reward->save($data);

                if ($saveid == false) {
                    unlink($imagepath);
                } else {
                    if ($this->request->data['edit_id'] != '') {

                        $this->Session->setFlash('Reward successfully updated', 'default', array(), 'good');
                        $this->redirect('/StaffRewardManagement/addlocalreward/' . $this->request->data['edit_id']);
                    } else {
                      
                        $this->Session->setFlash('Reward successfully added', 'default', array(), 'good');
                        $this->redirect('/StaffRewardManagement/addlocalreward');
                    }
                }
            } else {
                $this->Session->setFlash('Missing parameter', 'default', array(), 'bad');
                $this->redirect('/StaffRewardManagement#tab2');
            }
        }
    }
    /**
     * Get the list of all global rewards added by super amdin.
     */
    public function getGlobalRewards() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('description', 'points', 'category');
        $sIndexColumn = "id";
        $sTable = 'rewards';

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }
        //Ordering
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $_GET['sSearch'] = str_replace('%', '#$@19', $_GET['sSearch']);
        //Filtering
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE clinic_id='0'";
        } else {
            $sWhere .=" AND clinic_id='0'";
        }

        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder $sLimit";
        $rResult = $this->Reward->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder";
        $aResultFilterTotal = $this->Reward->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);



        $sQuery = "select * from $sTable where clinic_id='0' ";
        $aResultTotal = $this->Reward->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $val) {
            $pCount = '';
            $checked = '';
            $pQuery = "select * from clinic_rewards where reward_id='" . $val['rewards']['id'] . "' and clinic_id=" . $sessionstaff['clinic_id'];
            $pResult = $this->Reward->query($pQuery);
            $pCount = count($pResult);
            $row = array();
            if ($pCount > 0) {
                $checked = 'checked="checked"';
            }
            $cat = explode(';', $val['rewards']['category']);
            $row[] = $val['rewards']['description'];
            $row[] = $val['rewards']['points'];
            $row[] = $cat[0];
            $row[] = "<span id='pub_" . $val['rewards']['id'] . "' class='public_span'><input type='checkbox' name='' id='publish_" . $val['rewards']['id'] . "' value='" . $val['rewards']['id'] . "' $checked onclick='setPublished(" . $val['rewards']['id'] . ")'></span>";
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     * Publish the global rewards for local use.
     */
    public function setPublishGlobalReward() {

        $this->layout = '';
        $xml = '';
        $sessionstaff = $this->Session->read('staff');
        $pResult = $this->Clinic_reward->find('all', array('conditions' => array('reward_id' => $this->request->data['reward_id'], 'clinic_id' => $sessionstaff['clinic_id'])));
        $pCount = count($pResult[0]['Clinic_reward']['reward_id']);

        if ($pCount == 0) {
            $data = array();
            $data['clinic_id'] = $sessionstaff['clinic_id'];
            $data['reward_id'] = $this->request->data['reward_id'];
            $saveid = $this->Clinic_reward->save($data);
            if ($saveid) {
                echo '1';
                exit;
            } else {
                echo '2';
                exit;
            }
        } else {
            $pResult = $this->Clinic_reward->query("delete from clinic_rewards where clinic_id='" . $sessionstaff['clinic_id'] . "' and reward_id='" . $this->request->data['reward_id'] . "'");
            echo '3';
            exit;
        }
    }
    /**
     * Getting the list of all local rewards.
     */
    public function getLocalRewards() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('description', 'points', 'category');
        $sIndexColumn = "id";
        $sTable = 'rewards';

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " . $_GET['iDisplayLength'];
        }
        //Ordering
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
						" . $_GET['sSortDir_' . $i] . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $_GET['sSearch'] = str_replace('%', '#$@19', $_GET['sSearch']);
        //Filtering
        $sWhere = "";
        if ($_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $_GET['sSearch_' . $i] . "%' ";
            }
        }
        if ($sWhere == '') {
            $sWhere .=" WHERE clinic_id =" . $sessionstaff['clinic_id'] . " and description!='' and points!=''";
        } else {
            $sWhere .=" AND clinic_id=" . $sessionstaff['clinic_id'] . "";
        }

        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder $sLimit";
        $rResult = $this->Reward->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder";
        $aResultFilterTotal = $this->Reward->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);



        $sQuery = "select * from $sTable where clinic_id=" . $sessionstaff['clinic_id'];
        $aResultTotal = $this->Reward->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $val) {
            if ($val['rewards']['amazon_id'] == '' && $val['rewards']['amazon_id'] == Null) {
                $action = "ace-icon glyphicon glyphicon-pencil";
                //$action="edit_icon";
                $over = "Edit";
            } else {
                $action = "ace-icon fa fa-search-plus bigger-110";
                //$action="view_icon";
                $over = "View";
            }
            $cat = explode(';', $val['rewards']['category']);
            $row = array();
            $row[] = $val['rewards']['description'];
            $row[] = $val['rewards']['points'];
            $row[] = $cat[0];
            $row[] = "<span id='pub_" . $val['rewards']['id'] . "' ><a href='/StaffRewardManagement/addlocalreward/" . $val['rewards']['id'] . "' title='" . $over . "' rel='facebox'  class='btn btn-xs btn-info'><i class='" . $action . "'></i></a> <a href='/StaffRewardManagement/delete/" . $val['rewards']['id'] . "' title='Delete' rel='facebox'  class='btn btn-xs btn-danger'><i class='ace-icon glyphicon glyphicon-trash'></i></a></span>";
          
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     * Function to validate credential for amazon rewards search through api.
     * @param type $region
     * @param type $params
     * @param type $public_key
     * @param type $private_key
     * @param type $associate_tag
     * @param type $version
     * @return string
     */
    public function aws_signed_request($region, $params, $public_key, $private_key, $associate_tag = NULL, $version = '2011-08-01') {
        /*
          Copyright (c) 2009-2012 Ulrich Mierendorff

          Permission is hereby granted, free of charge, to any person obtaining a
          copy of this software and associated documentation files (the "Software"),
          to deal in the Software without restriction, including without limitation
          the rights to use, copy, modify, merge, publish, distribute, sublicense,
          and/or sell copies of the Software, and to permit persons to whom the
          Software is furnished to do so, subject to the following conditions:

          The above copyright notice and this permission notice shall be included in
          all copies or substantial portions of the Software.

          THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
          IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
          FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
          THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
          LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
          FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
          DEALINGS IN THE SOFTWARE.
         */

        /*
          Parameters:
          $region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
          $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
          "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
          $public_key - your "Access Key ID"
          $private_key - your "Secret Access Key"
          $version (optional)
         */

        // some paramters
        $method = 'GET';
        $host = 'webservices.amazon.' . $region;
        $uri = '/onca/xml';

        // additional parameters
        $params['Service'] = 'AWSECommerceService';
        $params['AWSAccessKeyId'] = $public_key;
        // GMT timestamp
        $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        // API version
        $params['Version'] = $version;
        if ($associate_tag !== NULL) {
            $params['AssociateTag'] = $associate_tag;
        }

        // sort the parameters
        ksort($params);

        // create the canonicalized query
        $canonicalized_query = array();
        foreach ($params as $param => $value) {
            $param = str_replace('%7E', '~', rawurlencode($param));
            $value = str_replace('%7E', '~', rawurlencode($value));
            $canonicalized_query[] = $param . '=' . $value;
        }
        $canonicalized_query = implode('&', $canonicalized_query);

        // create the string to sign
        $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;

        // calculate HMAC with SHA256 and base64-encoding
        $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $private_key, TRUE));

        // encode the signature for the request
        $signature = str_replace('%7E', '~', rawurlencode($signature));

        // create request
        $request = 'http://' . $host . $uri . '?' . $canonicalized_query . '&Signature=' . $signature;

        return $request;
    }
    /**
     * Search the amazon product using keywards.
     * @param type $pageid
     */
    public function searchamazonproduct($pageid) {
        $this->layout = '';
        $keywords = $this->request->data['keywords'];

        $public_key = 'AKIAJ7LOWJKIJTX7JGRA';
        $private_key = 'Yx1/+1/5hKDgFJRgtouUNaEuhbPt1uBsP62ZnjPc';
        $associate_tag = 'httpwwwint097-20';

        $request = $this->aws_signed_request('com', array(
            'Operation' => 'ItemSearch',
            'Keywords' => $keywords,
            'SearchIndex' => 'All',
            'ItemPage' => $pageid,
            'ResponseGroup' => 'Small,Images,ItemIds,ItemAttributes'), $public_key, $private_key, $associate_tag);

        // do request (you could also use curl etc.)
        $response = @file_get_contents($request);



        if ($response === FALSE) {
            $responseData[0] = array(
                'status_code' => 400,
                'error' => "Request failed"
            );
        } else {
            // parse XML
            $pxml = simplexml_load_string($response);

            //$xml2array=$this->xml2array($pxml);
            $responseData = array();

            if ($pxml === FALSE) {
                $responseData[0] = array(
                    'status_code' => 400,
                    'error' => "Response could not be parsed"
                );
            } else {
                //output here
                $pointConversionFactor = 0.5;

                if ($pxml->Items->Request->IsValid == 'True') {
                    if (count($pxml->Items->Item) > 0) {
                        for ($a = 0; $a < count($pxml->Items->Item); $a++) {
                            $responseData[] = array(
                                'status_code' => 200,
                                'sku' => (isset($pxml->Items->Item[$a]->ASIN) ? $pxml->Items->Item[$a]->ASIN : ''),
                                'title' => (isset($pxml->Items->Item[$a]->ItemAttributes->Title) ? $pxml->Items->Item[$a]->ItemAttributes->Title : ''),
                                'url' => (isset($pxml->Items->Item[$a]->MediumImage->URL) ? $pxml->Items->Item[$a]->MediumImage->URL : ''),
                                'price' => (isset($pxml->Items->Item[$a]->ItemAttributes->ListPrice->Amount) ? round($pxml->Items->Item[$a]->ItemAttributes->ListPrice->Amount * $pointConversionFactor) : ''),
                                'category' => (isset($pxml->Items->Item[$a]->ItemAttributes->Binding) ? $pxml->Items->Item[$a]->ItemAttributes->Binding : '')
                            );
                        }
                    } else {
                        $responseData[0] = array(
                            'status_code' => 400,
                            'error' => "No record found..."
                        );
                    }
                } else {
                    $responseData[0] = array(
                        'status_code' => 400,
                        'error' => "No record found..."
                    );
                }
            }
        }
        $this->set('responseData', $responseData);
        $this->set('current_page', $pageid);
        $this->set('total_pages', $pxml->Items->TotalPages);
    }

    /**
     * @deprecated
     */
    public function setamazonproductxml() {
        $this->layout = '';
        $sku = $this->request->data['sku'];
        $url = $this->request->data['url'];
        $title = $this->request->data['title'];
        $price = $this->request->data['price'];
    }
    /**
     * Function to check duplicate local rewards for practice.
     */
    public function checkreward() {
        $this->layout = '';
        $sessionstaff = $this->Session->read('staff');
        if (isset($this->request->data['amazon_id']) && $this->request->data['amazon_id'] != '') {
            if (isset($this->request->data['reward_id'])) {
                $rewardInfo = $this->Reward->find('first', array('conditions' => array('OR' => array(array('Reward.clinic_id' => $sessionstaff['clinic_id']), array('Reward.clinic_id' => 0)), 'Reward.amazon_id' => $this->request->data['amazon_id'], 'Reward.id !=' => $this->request->data['reward_id'])));
            } else {
                $rewardInfo = $this->Reward->find('first', array('conditions' => array('OR' => array(array('Reward.clinic_id' => $sessionstaff['clinic_id']), array('Reward.clinic_id' => 0)), 'Reward.amazon_id' => $this->request->data['amazon_id'])));
            }
            //check the reward already exist or not.
            if (!empty($rewardInfo)) {
                echo 1;
            } else {
                echo 0;
            }
        } else if (isset($this->request->data['reward_name']) && $this->request->data['reward_name'] != '') {
            if (isset($this->request->data['reward_id'])) {
                $rewardInfo = $this->Reward->find('first', array('conditions' => array('OR' => array(array('Reward.clinic_id' => $sessionstaff['clinic_id']), array('Reward.clinic_id' => 0)), 'Reward.description' => $this->request->data['reward_name'], 'Reward.id !=' => $this->request->data['reward_id'])));
            } else {
                $rewardInfo = $this->Reward->find('first', array('conditions' => array('OR' => array(array('Reward.clinic_id' => $sessionstaff['clinic_id']), array('Reward.clinic_id' => 0)), 'Reward.description' => $this->request->data['reward_name'])));
            }
            //check the reward already exist or not.
            if (!empty($rewardInfo)) {
                echo 1;
            } else {
                echo 0;
            }
        }

        die;
    }
    /**
     * Delete the un used local reward.
     * @param type $id
     */
    public function delete($id = null) {

        if ($this->Reward->deleteAll(array('Reward.id' => $id))) {
            $this->Session->setFlash('Reward successfully deleted', 'default', array(), 'good');
            $this->redirect('/StaffRewardManagement#tab2');
        } else {

            $this->Session->setFlash('The reward not deleted.', 'default', array(), 'bad');
            $this->redirect('/StaffRewardManagement#tab2');
        }
    }

}

//class end here
?>
