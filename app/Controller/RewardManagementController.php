<?php
/**
 * This file for add,edit and delete global custom and amazon rewards.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add,edit and delete global custom and amazon rewards.
 */
class RewardManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session and CakeS3 component for this controller.
     * @var type 
     */
    public $components = array('Session', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinics', 'Rewards', 'Notification', 'Category');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     * Getting the list of all global rewards added by super admin.
     */
    public function index() {
        $this->layout = "adminLayout";
        $rewards = $this->Rewards->find('all', array('conditions' => array('Rewards.points !=' => '', 'Rewards.description !=' => '', 'Rewards.clinic_id' => 0)));
        $this->set('rewards', $rewards);
    }
    /**
     * Add New global rewards
     */
    public function add() {/////////////////////////////////submit reward/////////////////////
        $this->layout = "adminLayout";
        $categoryresult = $this->Category->find('all', array('conditions' => array('Category.status' => 1)));
        $this->set('category', $categoryresult);

        if ($this->request->is('post')) {

            if (($this->request->data['reward_name'] != '') && ($this->request->data['reward_point'] != '') && ($this->request->data['reward_category'] != '')) {

                if ($this->request->data['product_type'] == 'normal') {
                    $image = $this->request->params['form']['reward_image'];
                }
                $img_dir = '';
                $img_fileName = '';
                $imagepath = '';
                $imazon_product_id = '';
                //condition for upload rewards image.
                if (isset($image) && ($image["size"] > 0) && ($this->request->data['product_type'] == 'normal')) {
                    $date = strtotime(date('m/d/Y h:i:s a', time()));
                    $uploadFolder = "Global";
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
                    $getimgpath = 'http://' . $_SERVER['HTTP_HOST'] . $imagepath;
                    if (in_array($extension, array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'JPG', 'JPEG', 'GIF', 'PNG', 'BMP'))) {
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

                        $this->redirect(array('action' => 'add'));
                    }
                } elseif ($this->request->data['product_type'] == 'amazon') {
                    $imagepath = $this->request->data['amazon_product_url'];
                    $imazon_product_id = $this->request->data['amazon_id'];
                    $getimgpath = $imagepath;
                }
                //////////////////insert///////////////////////////////
                $data = array();

                $data['points'] = $this->request->data['reward_point'];
                $data['description'] = $this->request->data['reward_name'];
                $data['category'] = $this->request->data['reward_category'];

                if ($imagepath != '' && $this->request->data['product_type'] == 'normal') {

                    //upload reward image on S3 server.
                    $response = $this->CakeS3->putObject(WWW_ROOT . $imagepath, 'img/rewards/' . $uploadFolder . '/' . $img_fileName, $this->CakeS3->permission('public_read_write'));
                    $sharingImageUrl = $response['url'];
                    @unlink($imagepath);
                    $data['imagepath'] = $sharingImageUrl;
                } else {
                    $data['imagepath'] = $imagepath;
                }

                $data['amazon_id'] = $imazon_product_id;
                $data['clinic_id'] = 0;

                $saveid = $this->Rewards->save($data);

                if ($saveid == false) {
                    unlink($imagepath);
                } else {
                    $this->Session->setFlash('Reward successfully added', 'default', array(), 'good');
                    $this->redirect('/RewardManagement/index');
                }
            } else {
                $this->Session->setFlash('Missing parameter', 'default', array(), 'bad');
                $this->redirect('/RewardManagement/add');
            }
        }
    }
    /**
     * Edit global reward added by super admin
     * @param type $id
     * @return type
     */
    public function edit($id = Null) {
        $this->layout = "adminLayout";
        $categoryresult = $this->Category->find('all', array('conditions' => array('Category.status' => 1)));
        $this->set('category', $categoryresult);
        if (isset($id)) {
            $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.id' => $id)));
            $this->set('rewardInfo', $rewardInfo);
        }

        if ($this->request->is('post')) {

            if (($this->request->data['reward_name'] != '') && ($this->request->data['reward_point'] != '') && ($this->request->data['reward_category'] != '')) {
                if ($this->request->data['product_type'] == 'normal' && isset($this->request->params['form']['reward_image'])) {
                    $image = $this->request->params['form']['reward_image'];
                }
                
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.id' => $id)));
                $img_dir = '';
                $img_fileName = '';
                $imagepath1 = $rewardInfo['Rewards']['imagepath'];
                $imagepath = '';
                $imazon_product_id = '';

                //condition to upload new reward image.
                if (isset($image) && ($image["size"] > 0) && ($this->request->data['product_type'] == 'normal')) {
                    $date = strtotime(date('m/d/Y h:i:s a', time()));
                    $uploadFolder = "Global";
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

                        return $this->redirect('/RewardManagement/edit/' . $id);
                    }
                } elseif ($this->request->data['product_type'] == 'amazon') {
                    $imagepath = $this->request->data['amazon_product_url'];
                    $imazon_product_id = $this->request->data['amazon_id'];
                }
                //////////////////insert///////////////////////////////
                $data = array();
                $data['id'] = $id;
                $data['points'] = $this->request->data['reward_point'];
                $data['description'] = $this->request->data['reward_name'];
                $data['category'] = $this->request->data['reward_category'];

                if ($imagepath != '' && $this->request->data['product_type'] == 'normal') {

                    //upload reward image on S3 server.
                    $response = $this->CakeS3->putObject(WWW_ROOT . $imagepath, 'img/rewards/' . $uploadFolder . '/' . $img_fileName, $this->CakeS3->permission('public_read_write'));
                    $sharingImageUrl = $response['url'];
                    @unlink($imagepath);
                    $data['imagepath'] = $sharingImageUrl;
                } else {
                    $data['imagepath'] = $imagepath1;
                }

                $data['amazon_id'] = $imazon_product_id;
                $data['clinic_id'] = 0;

                $saveid = $this->Rewards->save($data);

                if ($saveid == false) {
                    unlink($imagepath);
                } else {
                    $this->Session->setFlash('Reward successfully updated.', 'default', array(), 'good');
                    $this->redirect('/RewardManagement/index');
                }
            } else {
                $this->Session->setFlash('Missing parameter', 'default', array(), 'bad');
                return $this->redirect('/RewardManagement/edit/' . $id);
            }
        }
    }
    /**
     * Deletion of un used global reward.
     * @return type
     */
    public function delete() {

        if ($this->Rewards->deleteAll(array('Rewards.id' => $this->request->data['Rewards']['id']))) {
            $this->Session->setFlash('Reward successfully deleted', 'default', array(), 'good');
            if (isset($this->request->data['page_no']) && $this->request->data['page_no'] > 0) {
                return $this->redirect('/RewardManagement/index/page:' . $this->request->data['page_no'] . '?' . $this->request->data['search_field']);
            } else {
                return $this->redirect('/RewardManagement/?' . $this->request->data['search_field']);
            }
        } else {

            if(isset($this->request->data['page_no']) && $this->request->data['page_no'] > 0) {
                $this->Session->setFlash('The reward not deleted.', 'default', array(), 'bad');
                return $this->redirect('/RewardManagement/index/page:' . $this->request->data['page_no'] . '?' . $this->request->data['search_field']);
            } else {
                $this->Session->setFlash('The reward not deleted.', 'default', array(), 'bad');
                return $this->redirect('/RewardManagement/?' . $this->request->data['search_field']);
            }
        }
    }

    /**
     * function for convert xml to array.
     * @param type $xml
     * @return type
     */
    public function xml2array($xml) {
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
     * Function to getting a access from amazon to get rewards list.
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
     * Function for search reward list from amazon.
     * @param type $pageid
     */
    public function searchamazonproduct($pageid) {
        $this->layout = '';
        $keywords = $this->request->data['keywords'];

        $public_key = 'AKIAJ7LOWJKIJTX7JGRA';
        $private_key = 'Yx1/+1/5hKDgFJRgtouUNaEuhbPt1uBsP62ZnjPc';
        $associate_tag = 'httpwwwint097-20';
        //authonticate from amazon.
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
            $xml2array = $this->xml2array($pxml);
            //echo "<pre>";
            //print_r($xml2array);die;
            $responseData = array();

            if ($pxml === FALSE) {
                $responseData[0] = array(
                    'status_code' => 400,
                    'error' => "Response could not be parsed"
                );
            } else {
                //output here
                $pointConversionFactor = 0.5;
                if ($xml2array['Items'][0]['Request'][0]['IsValid'] == 'True') {
                    if (isset($xml2array['Items'][0]['Item'])) {
                        for ($a = 0; $a < count($xml2array['Items'][0]['Item']); $a++) {
                            $responseData[] = array(
                                'status_code' => 200,
                                'sku' => (isset($xml2array['Items'][0]['Item'][$a]['ASIN']) ? $xml2array['Items'][0]['Item'][$a]['ASIN'] : ''),
                                'title' => (isset($xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['Title']) ? $xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['Title'] : ''),
                                'url' => (isset($xml2array['Items'][0]['Item'][$a]['MediumImage'][0]['URL']) ? $xml2array['Items'][0]['Item'][$a]['MediumImage'][0]['URL'] : ''),
                                'price' => (isset($xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['ListPrice'][0]['Amount']) ? round($xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['ListPrice'][0]['Amount'] * $pointConversionFactor) : ''),
                                'category' => (isset($xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['Binding']) ? $xml2array['Items'][0]['Item'][$a]['ItemAttributes'][0]['Binding'] : '')
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
     * Function to check duplicate global reward in our system.
     */
    public function checkreward() {
        $this->layout = '';
        if (isset($this->request->data['amazon_id']) && $this->request->data['amazon_id'] != '') {
            if (isset($this->request->data['reward_id'])) {
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.amazon_id' => $this->request->data['amazon_id'], 'Rewards.id !=' => $this->request->data['reward_id'])));
            } else {
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.amazon_id' => $this->request->data['amazon_id'])));
            }
            if (!empty($rewardInfo)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            if (isset($this->request->data['reward_id'])) {
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.description' => $this->request->data['reward_name'], 'Rewards.id !=' => $this->request->data['reward_id'])));
            } else {
                $rewardInfo = $this->Rewards->find('first', array('conditions' => array('Rewards.description' => $this->request->data['reward_name'])));
            }
            if (!empty($rewardInfo)) {
                echo 1;
            } else {
                echo 0;
            }
        }

        die;
    }

///////////////////////////end here
}

//*******************class end here
?>
