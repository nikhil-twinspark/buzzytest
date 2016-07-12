<?php
/**
 *  This file for manage network promotion added by super admin.
 *  add,edit,delete custom promotion for practice.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for manage network promotion added by super admin.
 *  add,edit,delete custom promotion for practice.
 */
class PromotionManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Promotion', 'User', 'Clinic', 'Transaction', 'Refer', 'TrainingVideo','RateReview','ClinicNotification');
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
     * Default page for controller.
     */
    public function index() {

        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0,'Promotion.default'=>0,'Promotion.is_lite'=>0);
        $Promotionlist = $this->Promotion->find('all', $options6);
        $this->set('promotionlist', $Promotionlist);
        //function to check access control for practice staff
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Promotions');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');

        }
    }
    /**
     *  get the list of all network promotion for practice.
     */
    public function getdata() {

        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        //temperary merging the list of custom and netwotk promotion
        $options6['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default !=' => 2);
        $options6['order'] = array('Promotion.sort ASC');
        $Promotionlist = $this->Promotion->find('all', $options6);
        $Promotion = array();
        if ($sessionstaff['is_lite'] == 1) {
            foreach ($Promotionlist as $plist) {
                if ($plist['Promotion']['is_lite'] == 1) {
                    $Promotion[] = $plist;
                }
            }
        }
        if ($sessionstaff['is_lite'] != 1) {
            foreach ($Promotionlist as $plist) {
                if ($plist['Promotion']['is_lite'] != 1) {
                    $Promotion[] = $plist;
                }
            }
        }
        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Promotions');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
            exit;
        }

        $response = array('aaData' => array());
        $i = 0;

        foreach ($Promotion as $value) {


            $editDeleteString = "<a title='Edit' m_id='" . $value['Promotion']['id'] . "' href='" . Staff_Name . "PromotionManagement/edit/" . $value['Promotion']['id'] . "'  class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;";
            if ($value['Promotion']['default'] == 1) {
                    $editDeleteString .= "<a title='Delete' href='javascript:void(0)'  class='btn btn-xs btn-danger'><i class='ace-icon glyphicon glyphicon-trash grey'></i></a>";
                } else {
                    $editDeleteString .="<a title='Delete' href='" . Staff_Name . "PromotionManagement/delete/" . $value['Promotion']['id'] . "'  class='btn btn-xs btn-danger'><i class='ace-icon glyphicon glyphicon-trash'></i></a>";
                }

            $checked = '';
            if ($value['Promotion']['public'] == 1) {
                $checked = "checked";

            }
            $editDeleteString .= "&nbsp;Active <input type='checkbox' $checked name='" . $value['Promotion']['id'] . "' id='" . $value['Promotion']['id'] . "'  onclick='setpublic(" . $value['Promotion']['id'] . ");'>";
            if ($value['Promotion']['display_name'] == '') {
                $pdn = $value['Promotion']['description'];
            } else {
                $pdn = $value['Promotion']['display_name'];
            }
            //condition to check promotion type and display the promotion name
            if($value['Promotion']['default']==1){
               $category='Network Promotions'; 
            }else{
               $category='Custom Promotions';  
            }
            $response['aaData'][$i] = array($pdn,
                $category
                , $value['Promotion']['value']
                , $editDeleteString
            );
            $i++;
        }
        echo json_encode($response);
        exit;
    }
    /**
     *  add new custom promotion for practice.
     */
    public function add() {

        if ($this->request->is('post')) {

            $this->Promotion->create();
            $options['conditions'] = array('OR' => array('Promotion.display_name' => trim($this->request->data['display_name']), 'Promotion.description' => trim($this->request->data['description'])), 'Promotion.clinic_id' => $this->request->data['clinic_id'], 'is_lite' => 0);
            $ind = $this->Promotion->find('first', $options);
            //get the promotion count for not add more then 3 promotion
            $optionspr['conditions'] = array('Promotion.clinic_id' => $this->request->data['clinic_id'], 'is_lite' => 0,'is_global'=>0);
            $cntpro = $this->Promotion->find('all', $optionspr);
            $nextcnt = count($cntpro) + 1;
            //condition to check duplicate custom promotion for practice
            if (empty($ind)) {
                $proarra['Promotion'] = array('description' => $this->request->data['description'], 'display_name' => $this->request->data['display_name'], 'value' => $this->request->data['value'], 'operand' => $this->request->data['Promotion']['operand'], 'clinic_id' => $this->request->data['clinic_id'], 'is_lite' => 0, 'sort' => $nextcnt,'is_global'=>0,'public'=>1,'default'=>0);
                if ($this->Promotion->save($proarra)) {
                    $this->Session->setFlash('Custom Promotion successfully added', 'default', array(), 'good');
                    $this->redirect(array('action' => 'index'));
                } else {


                    $this->Session->setFlash('Unable to add custom promotion', 'default', array(), 'bad');

                }
            } else {
                $this->Session->setFlash('Custom Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit network promotion for practice.
     * @param type $id
     * @return type
     */
    public function edit($id) {
        $sessionstaff = $this->Session->read('staff');
        $Promotions = $this->Promotion->find('first', array('conditions' => array('Promotion.id' => $id)));
        $this->set('promotion', $Promotions);
        if (isset($this->request->data['Promotion']['action']) && $this->request->data['Promotion']['action'] == 'update') {
            if ($sessionstaff['is_lite'] == 1) {
                $lite = 1;
            } else {
                $lite = 0;
            }
          
            $proarra['Promotion']['id']=$this->request->data['id'];
            $proarra['Promotion']['display_name']=$this->request->data['display_name'];
            $proarra['Promotion']['value']=$this->request->data['value'];
            if(isset($this->request->data['description'])){
            $proarra['Promotion']['description']=$this->request->data['description'];
            }
            if(isset($this->request->data['operand'])){
            $proarra['Promotion']['operand']=$this->request->data['operand'];
            }
            if(isset($this->request->data['category'])){
            $proarra['Promotion']['default']=$this->request->data['category'];
            }
            $proarra['Promotion']['is_lite']=$lite;
            $proarra['Promotion']['is_global']=0;
           
            if ($this->Promotion->save($proarra)) {
                $this->Session->setFlash('The promotion has been updated.', 'default', array(), 'good');
                $this->set('promotion', $this->request->data);
                $this->redirect(array('action' => "edit/$id"));
            } else {
                $this->Session->setFlash('The promotion not updated.', 'default', array(), 'bad');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }
    /**
     *  set custom promotion as public to show at record point screen and bussydoc site.
     */
    public function setpublic() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Promotion->find('first', array('conditions' => array('Promotion.id' => $_POST['promotion_id'])));


        if ($Locations['Promotion']['public'] == 1) {
            $like = 0;
        } else {
            $like = 1;
        }

        $this->Promotion->query('UPDATE promotions set public="' . $like . '" where id=' . $Locations['Promotion']['id']);

        exit;
    }
    /**
     *  delete custom promotion.
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->Promotion->deleteAll(array('Promotion.id' => $id))) {
            $this->Session->setFlash('The promotion has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('ERR:The promotion not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }
    /**
     *  change the ordering for network and custom promotions.
     */
    public function sortpromotions() {
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        if (isset($_POST['data'])) {
            $data = json_decode($_POST['data'], true);
            if (!empty($data)) {
                foreach ($data as $value) {
                    $this->Promotion->query('UPDATE promotions set sort="' . $value['position'] . '" where id=' . $value['id']);
                }
                $status = 'Grid sorted successfully';
                echo $status;
            }
        }
        exit;
    }
    /**
     *  get the count of custom promotion.
     */
    public function custompromotion() {

        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.default' => 0, 'Promotion.is_lite' => 0,'Promotion.is_global'=>0);
        $Promotionlist = $this->Promotion->find('all', $options6);
        $this->set('Promotions', $Promotionlist);

        $checkaccess = $this->Api->accesscheck($sessionstaff['clinic_id'], 'Promotions');
        if ($checkaccess == 0) {
            $this->render('/Elements/access');
        }
    }
    /**
     *  get the list of all custom promotion.
     */
    public function getdatacustom() {

        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0, 'Promotion.default' => 0);
        $options6['order'] = array('Promotion.sort ASC');
        $Promotionlist = $this->Promotion->find('all', $options6);
        $Promotion = array();
        if ($sessionstaff['is_lite'] == 1) {
            foreach ($Promotionlist as $plist) {
                if ($plist['Promotion']['is_lite'] == 1) {
                    $Promotion[] = $plist;
                }
            }
        }
        if ($sessionstaff['is_lite'] != 1) {
            foreach ($Promotionlist as $plist) {
                if ($plist['Promotion']['is_lite'] != 1) {
                    $Promotion[] = $plist;
                }
            }
        }
        $response = array('aaData' => array());
        $i = 0;

        foreach ($Promotion as $value) {


            $editDeleteString = "<a title='Edit' m_id='" . $value['Promotion']['id'] . "' href='" . Staff_Name . "PromotionManagement/editcustom/" . $value['Promotion']['id'] . "'  class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;";

            if ($sessionstaff['is_lite'] != 1) {
                if ($value['Promotion']['is_global'] == 1) {
                    $editDeleteString .= "<a title='Delete' href='javascript:void(0)'  class='btn btn-xs btn-danger'><i class='ace-icon glyphicon glyphicon-trash grey'></i></a>";
                } else {
                    $editDeleteString .="<a title='Delete' href='" . Staff_Name . "PromotionManagement/delete/" . $value['Promotion']['id'] . "'  class='btn btn-xs btn-danger'><i class='ace-icon glyphicon glyphicon-trash'></i></a>";
                }
            }
            $checked = '';
            if ($value['Promotion']['public'] == 1) {
                $checked = "checked";
            }
            $editDeleteString .= "&nbsp;Active <input type='checkbox' $checked name='" . $value['Promotion']['id'] . "' id='" . $value['Promotion']['id'] . "'  onclick='setpublic(" . $value['Promotion']['id'] . ");'>";
            if ($value['Promotion']['display_name'] == '') {
                $pdn = $value['Promotion']['description'];
            } else {
                $pdn = $value['Promotion']['display_name'];
            }

            $response['aaData'][$i] = array($pdn
                , $value['Promotion']['value']
                , $editDeleteString
            );
            $i++;
        }
        echo json_encode($response);
        exit;
    }
    /**
     *  edit custom promotion.
     * @param type $id
     * @return type
     */
    public function editcustom($id) {
        $sessionstaff = $this->Session->read('staff');
        $Promotions = $this->Promotion->find('first', array('conditions' => array('Promotion.id' => $id)));
        $this->set('promotion', $Promotions);
        if (isset($this->request->data['Promotion']['action']) && $this->request->data['Promotion']['action'] == 'update') {
            if ($sessionstaff['is_lite'] == 1) {
                $lite = 1;
            } else {
                $lite = 0;
            }
            $options['conditions'] = array('OR' => array('Promotion.display_name' => trim($this->request->data['display_name']), 'Promotion.description' => trim($this->request->data['description'])), 'Promotion.id !=' => $this->request->data['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'is_lite' => $lite);
            $ind = $this->Promotion->find('first', $options);
            //condition to check duplicate custom promotion for practice
            if (empty($ind)) {
                $proarra['Promotion'] = array('id' => $this->request->data['id'], 'display_name' => $this->request->data['display_name'], 'description' => $this->request->data['description'], 'category' => $this->request->data['category'], 'value' => $this->request->data['value'], 'operand' => $this->request->data['operand'], 'is_lite' => $lite);

                if ($this->Promotion->save($proarra)) {
                    $this->Session->setFlash('The promotion has been updated.', 'default', array(), 'good');
                    $this->set('promotion', $this->request->data);
                    $this->redirect(array('action' => "editcustom/$id"));
                } else {
                    $this->Session->setFlash('The promotion not updated.', 'default', array(), 'bad');
                    return $this->redirect(array('action' => 'custompromotion'));
                }
            } else {
                $this->Session->setFlash('Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     * rate review promotion listing
     */
    public function ratereviewpromotion() {

        $sessionstaff = $this->Session->read('staff');
        $options6['conditions'] = array('Promotion.clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0,'Promotion.default'=>2,'Promotion.is_lite'=>0);
        $Promotionlist = $this->Promotion->find('all', $options6);
        $this->set('promotionlist', $Promotionlist);
        //function to check access control for practice staff
        if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['rate_review']==1){
           
        }else{
            $this->render('/Elements/access');
        }
    }
     /**
     *  edit custom promotion.
     * @param type $id
     * @return type
     */
    public function editratereviewpromotion($id) {
        $sessionstaff = $this->Session->read('staff');
        $Promotions = $this->Promotion->find('first', array('conditions' => array('Promotion.id' => $id)));
        $this->set('promotion', $Promotions);
        if (isset($this->request->data['Promotion']['action']) && $this->request->data['Promotion']['action'] == 'update') {

            $options['conditions'] = array('OR' => array('Promotion.display_name' => trim($this->request->data['display_name']), 'Promotion.description' => trim($this->request->data['description'])), 'Promotion.id !=' => $this->request->data['id'], 'clinic_id' => $sessionstaff['clinic_id'], 'Promotion.is_global' => 0,'Promotion.default'=>2);
            $ind = $this->Promotion->find('first', $options);
            //condition to check duplicate custom promotion for practice
            if (empty($ind)) {
                $proarra['Promotion'] = array('id' => $this->request->data['id'], 'display_name' => $this->request->data['display_name'], 'value' => $this->request->data['value']);

                if ($this->Promotion->save($proarra)) {
                    $this->Session->setFlash('The rate review promotion has been updated.', 'default', array(), 'good');
                    $this->set('promotion', $this->request->data);
                    $this->redirect(array('action' => "editratereviewpromotion/$id"));
                } else {
                    $this->Session->setFlash('The rate review promotion not updated.', 'default', array(), 'bad');
                    return $this->redirect(array('action' => 'ratereviewpromotion'));
                }
            } else {
                $this->Session->setFlash('Rate review promotion already exists.', 'default', array(), 'bad');
            }
        }
    }


}

?>
