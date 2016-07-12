<?php
/**
 *  This file is for add,edit,delete clinic's contest.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller is for add,edit,delete clinic's contest.
 */
class ContestManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session ,api and CakeS3 component for this controller.
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
    public $uses = array('Clinic', 'Contest', 'ContestClinic');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  Getting the all clinic list for assign contest.
     */
    public function index() { 
        $this->layout="adminLayout";
        $credResult = $this->Clinic->find('all', array('order' => 'api_user asc'));
        $this->set('clinics', $credResult);
    }
    /**
     * 
     *  fetch all contest list.
     */
    public function getContest() {
        $sessionstaff = $this->Session->read('staff');
        $this->layout = '';
        $aColumns = array('contests.contest_name');
        $sIndexColumn = "id";
        $sTable = 'contests';

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
            $sWhere = "WHERE  contests.contest_name LIKE '%" . $_GET['sSearch'] . "%')";
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

        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder $sLimit";
        $rResult = $this->Contest->query($sQuery);

        /* Data set length after filtering */
        $sQuery = "SELECT * FROM   $sTable $sWhere $sOrder";
        $aResultFilterTotal = $this->Contest->query($sQuery);
        $iFilteredTotal = count($aResultFilterTotal);
        $sQuery = "select * from $sTable";
        $aResultTotal = $this->Contest->query($sQuery);
        $iTotal = count($aResultTotal);

        //Output
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        //print_r($rResult);die;
        foreach ($rResult as $val) {

            $row = array();
            $row[] = $val['contests']['contest_name'];
            $str="<a class='btn btn-xs btn-info' title='Edit Contest' href='/ContestManagement/edit/" . $val['contests']['id'] . "'  ><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;";
            
            $str .= "<a class='btn btn-xs btn-danger' title='Delete Contest' href='/ContestManagement/delete/" . $val['contests']['id'] . "'  ><i class='ace-icon glyphicon glyphicon-trash'></i></a>&nbsp;";
            $str .="<a title='Assign To Practice' onclick='assignclinic(" . $val['contests']['id'] . ");' class='btn btn-xs btn-info' ><i class='ace-icon fa fa-external-link'></i></a>";
            $row[] = $str;
            $output['aaData'][] = $row;
        }

        header("Content-type:application/json");
        echo json_encode($output);
        die;
    }
    /**
     * 
     *  add new global contest.
     */
    public function add() {
        $this->layout="adminLayout";
        if ($this->request->is('post')) {
            $options['conditions'] = array('Contest.contest_name' => $this->request->data['Contest']['contest_name']);
            $credResult = $this->Contest->find('all', $options);

            if (empty($credResult)) {

                if ($this->data['Contest']) {

                    $challenge_header_image = $this->data['Contest']['contest_image'];

                    //allowed image types
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = 'Contest';

                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {

                        if ($type == $challenge_header_image['type']) {

                            //check if there wasn't errors uploading file on serwer
                            if ($challenge_header_image['error'] == 0) {
                                //image file name
                                $date = strtotime(date('m/d/Y h:i:s a', time()));
                                $img_dir = $uploadPath;
                                $img = explode('.', $challenge_header_image["name"]);
                                $image_filePath = $challenge_header_image['tmp_name'];
                                $img_fileName = $date . "." . $img[1];
                                $challenge_header_image_name = $img_fileName;
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $challenge_header_image_name)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $challenge_header_image_name);
                                }
                                //create full path with image name
                                $challenge_header_image_full_image_path = $uploadPath . '/' . $challenge_header_image_name;

                                //upload image to upload folder
                                if (move_uploaded_file($challenge_header_image['tmp_name'], $challenge_header_image_full_image_path)) {
                                    $response = $this->CakeS3->putObject($challenge_header_image_full_image_path, 'img/' . $uploadFolder . '/' . $challenge_header_image_name, $this->CakeS3->permission('public_read_write'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($challenge_header_image_full_image_path);
                                    $this->request->data['Contest']['contest_image'] = $sharingImageUrl;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading staff logo image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {

                                $this->Session->setFlash('Error uploading staff logo image.', 'default', array(), 'bad');
                            }
                        }
                    }
                }



                if (isset($this->request->data['Contest']['contest_image']['name']) && empty($this->request->data['Contest']['contest_image']['name'])) {
                    unset($this->request->data['Contest']['contest_image']);
                }
                if ($this->Contest->save($this->request->data)) {
                    $this->Session->setFlash('The contest has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The contest could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Contest Already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     * edit global contest.
     * @param type $id
     * @return boolean
     * @throws NotFoundException
     */
    public function edit($id = null) {
         $this->layout="adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $clientData = $this->Contest->findById($id);

        if (!$clientData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $urlResult = array();
            $options1['conditions'] = array('Contest.contest_name' => $this->request->data['Contest']['contest_name'], 'Contest.id !=' => $this->request->data['Contest']['id']);
            $urlResult = $this->Contest->find('all', $options1);

            if (empty($urlResult)) {

                if ($this->data['Contest']) {

                    $challenge_header_image = $this->data['Contest']['contest_image'];
                    //allowed image types
                    $imageTypes = array("image/gif", "image/jpeg", "image/png", "image/GIF", "image/JPEG", "image/PNG");
                    //upload folder - make sure to create one in webroot
                    $uploadFolder = 'Contest';

                    //full path to upload folder

                    $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                        chmod($uploadPath, 0777);
                    }

                    //check if image type fits one of allowed types
                    foreach ($imageTypes as $type) {

                        if ($type == $challenge_header_image['type']) {

                            //check if there wasn't errors uploading file on serwer
                            if ($challenge_header_image['error'] == 0) {
                                //image file name
                                $date = strtotime(date('m/d/Y h:i:s a', time()));
                                $img_dir = $uploadPath;
                                $img = explode('.', $challenge_header_image["name"]);
                                $image_filePath = $challenge_header_image['tmp_name'];
                                $img_fileName = $date . "." . $img[1];

                                $challenge_header_image_name = $img_fileName;
                                //check if file exists in upload folder
                                if (file_exists($uploadPath . '/' . $challenge_header_image_name)) {
                                    //create full filename with timestamp
                                    unlink($uploadPath . '/' . $challenge_header_image_name);
                                }
                                //create full path with image name
                                $challenge_header_image_full_image_path = $uploadPath . '/' . $challenge_header_image_name;

                                //upload image to upload folder
                                if (move_uploaded_file($challenge_header_image['tmp_name'], $challenge_header_image_full_image_path)) {
                                    $response = $this->CakeS3->putObject($challenge_header_image_full_image_path, 'img/' . $uploadFolder . '/' . $challenge_header_image_name, $this->CakeS3->permission('public_read_write'));
                                    $sharingImageUrl = $response['url'];
                                    @unlink($challenge_header_image_full_image_path);
                                    $this->request->data['Contest']['contest_image'] = $sharingImageUrl;
                                } else {
                                    $this->Session->setFlash('There was a problem uploading staff logo image. Please try again.', 'default', array(), 'bad');
                                }
                            } else {

                                $this->Session->setFlash('Error uploading staff logo image.', 'default', array(), 'bad');
                            }
                        }
                    }
                }


                if (isset($this->request->data['Contest']['contest_image']['name']) && empty($this->request->data['Contest']['contest_image']['name'])) {
                    unset($this->request->data['Contest']['contest_image']);
                }


                if ($this->Contest->save($this->request->data)) {

                    $this->Session->setFlash('The contest has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The client could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Contest Name Already exists.', 'default', array(), 'bad');
                return false;
            }
        } else {

            $this->request->data = $clientData;
        }
    }
    /**
     * delete global contest.
     * @param type $id
     * @return type
     * @throws MethodNotAllowedException
     */
    public function delete($id) {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $options1['conditions'] = array('Contest.id' => $id);
        $contest = $this->Contest->find('first', $options1);
        $this->Contest->deleteAll(array('Contest.id' => $id));
        $this->ContestClinic->query('delete from contest_clinics where contest_id=' . $id);

        $this->Session->setFlash('The contest: %s has been deleted.', h($contest['Contest']['contest_name']), 'default', array(), 'good');
        return $this->redirect(array('action' => 'index'));
    }
    /**
     * 
     *  getting list of contest.
     */
    public function getlist() {

        $options1['conditions'] = array('ContestClinic.contest_id' => $this->request->data['contest_id']);
        $contest = $this->ContestClinic->find('all', $options1);
        $clinic = array();
        foreach ($contest as $cnt) {
            $clinic[]['clinic_id'] = $cnt['ContestClinic']['clinic_id'];
        }
        echo json_encode($clinic);
        die;
    }
    /**
     * 
     *  assign contest to clinic.
     */
    public function assignclinic() {
        $this->layout = null;

        $this->ContestClinic->query('delete from contest_clinics where contest_id=' . $this->request->data['cid']);

        if (isset($this->request->data['clinicid']) && !empty($this->request->data['clinicid'])) {
            foreach ($this->request->data['clinicid'] as $clinicid) {
                $this->ContestClinic->query('delete from contest_clinics where clinic_id=' . $clinicid);
            }

            foreach ($this->request->data['clinicid'] as $clinicid) {
                $redeem['ContestClinic'] = array('contest_id' => $this->request->data['cid'], 'clinic_id' => $clinicid);
                $this->ContestClinic->save($redeem);
            }
        }
        exit;
    }


}

?>
