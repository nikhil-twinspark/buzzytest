<?php
/**
 *  This file for add,delete documents for clinic.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,delete documents for clinic.
 */
class DocumentManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session,api and cakes3 component for this controller.
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
    public $uses = array('Document', 'User', 'Clinic', 'Transaction', 'Refer','TrainingVideo','RateReview','ClinicNotification');
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
     *  fetch list of documents for clinic.
     */
    public function index() {
        $sessionstaff = $this->Session->read('staff');

        $Documents = $this->Document->find('all', array('conditions' => array('Document.clinic_id' => $sessionstaff['clinic_id'])));

        $this->set('Documents', $Documents);
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Documents');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }
    /**
     * Sorting for documents.
     * @param type $sort_by
     * @param type $sort
     */
    public function sort($sort_by, $sort) {
        $sessionstaff = $this->Session->read('staff');
        $table = 'Document';


        $this->paginate = array(
            'fields' => array('Document.id', 'Document.document', 'Document.title'),
            'conditions' => array('Document.clinic_id' => $sessionstaff['clinic_id']),
            'limit' => 10,
            'order' => array($table . '.' . $sort_by => $sort)
        );
        if (!empty($this->params['pass'])) {
            $this->set('sortname', $this->params['pass']['0']);
            $this->set('sort', $this->params['pass']['1']);
        }
        $clients = $this->paginate('Document');
        $this->set('Documents', $clients);
        $this->render('/DocumentManagement/index');
    }
    /**
     *  add documents for clinic.
     */
    public function add() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            if ($this->data['Document']) {
                $image = $this->data['Document']['document'];

                //allowed image types
                $imageTypes = array("pdf", "doc", "docx");
                //upload folder - make sure to create one in webroot

                $uploadFolder = "rewards/" . $sessionstaff['api_user'] . '/doc';
                //full path to upload folder
                $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                    chmod($uploadPath, 0777);
                }

                //check if image type fits one of allowed types
                $imgty = explode(".", $image["name"]);

                $extension = end($imgty);
                if (in_array($extension, $imageTypes)) {
                    //check if there wasn't errors uploading file on serwer
                    if ($image['error'] == 0) {
                        //image file name

                        $imageName = $this->data['Document']['document'];
                        $imageNamesize = $imageName['size'] / 1000;
                        if ($imageNamesize < 2048) {
                            $docimageName = time().'_'.$imageName['name'];
                            //check if file exists in upload folder
                            if (file_exists($uploadPath . '/' . $docimageName)) {
                                //create full filename with timestamp
                                unlink($uploadPath . '/' . $docimageName);
                            }
                            //create full path with image name
                            $full_image_path = $uploadPath . '/' . $docimageName;


                            //upload image to upload folder
                            if (move_uploaded_file($image['tmp_name'], $full_image_path)) {
                                $response = $this->CakeS3->putObject($full_image_path, 'img/rewards/' . $sessionstaff['api_user'] . '/doc/' . $docimageName, $this->CakeS3->permission('public_read_write'));
                                $sharingImageUrl = $response['url'];
                                @unlink($full_image_path);
                                $this->request->data['Document']['document'] = $sharingImageUrl;
                                if ($this->Document->save($this->request->data)) {

                                    $this->Session->setFlash('Document successfully added', 'default', array(), 'good');
                                    $this->redirect(array('action' => 'index'));
                                } else {

                                    $this->Session->setFlash('Unable to add document.', 'default', array(), 'bad');
                                }
                            } else {

                                $this->Session->setFlash('There was a problem uploading file. Please try again.', 'default', array(), 'bad');
                            }
                        } else {

                            $this->Session->setFlash('File Size should be less then 2 MB.', 'default', array(), 'bad');
                        }
                    } else {

                        $this->Session->setFlash('Error uploading file.', 'default', array(), 'bad');
                    }
                } else {
                    $this->Session->setFlash('Unacceptable file type', 'default', array(), 'bad');
                }
            }
        }
    }
    /**
     * Delete clinic's documents.
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->Document->deleteAll(array('Document.id' => $id))) {
            $this->Session->setFlash('Document has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Document not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

}

?>
