<?php
/**
 *  this file is for add,edit,delete global profile fields 
 * 
 * 
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  this controller is for add,edit,delete global profile fields 
 * 
 * 
 */
class AdminProfileFieldManagementController extends AppController {
    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form', 'Session');
    /**
     * We use the session component for this controller.
     * @var type 
     */
    public $components = array('Session');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField');
    /**
     * For Admin site we use the default layout
     * @var type 
     */
    public $layout = 'default';
    
    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  fetch all global profile fields created by super admin
     * 
     * 
     */
    public function index() {
        $this->layout = "adminLayout";
        $ProField = $this->ProfileField->find('all', array('conditions' => array('ProfileField.clinic_id' => 0)));
        $this->set('ProfileFields', $ProField);
    
    }
    
    /**
     *  add global profile fields
     * 
     * 
     */
    public function add() {
        $this->layout = "adminLayout";
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            
            if (isset($this->request->data['ProfileField']['options'])) {
                $this->request->data['ProfileField']['options'] = implode(',', $this->request->data['ProfileField']['options']);
            
            }
            // this condition to add other option in profile fields
            if (isset($this->request->data['other'])) {
                $this->request->data['ProfileField']['options'] .=",(other)";
            }
            $this->request->data['ProfileField']['profile_field'] = strtolower(str_replace(' ', '_', $this->request->data['ProfileField']['profile_field']));
            $this->request->data['ProfileField']['clinic_id'] = 0;
            $ProfileFields = $this->ProfileField->find('first', array(
                'conditions' => array(
                    'ProfileField.profile_field' => $this->request->data['ProfileField']['profile_field'],
                )
            ));
            if(empty($ProfileFields) && $this->request->data['ProfileField']['profile_field']!='address1' && $this->request->data['ProfileField']['profile_field']!='address2'){
            $this->ProfileField->create();
            if ($this->ProfileField->save($this->request->data)) {
                $this->Session->setFlash('Profile Field successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash('ERR:Unable to add Profile Field', 'default', array(), 'bad');
            }
            }else{
               $this->Session->setFlash('ERR:Profile Field already exists.', 'default', array(), 'bad'); 
            }
        }
    }
    
    /**
     * edit profile fields.
     * @param type $id
     * @return type
     */
    public function edit($id) {
        $this->layout = "adminLayout";
        $ProfileFields = $this->ProfileField->find('first', array(
            'conditions' => array(
                'ProfileField.id' => $id,
            )
        ));
        $this->set('ProfileFields', $ProfileFields);
        if (isset($this->request->data['action']) && $this->request->data['action'] == 'update') {

            if (isset($this->request->data['ProfileField']['options'])) {
                $this->request->data['ProfileField']['options'] = implode(',', $this->request->data['ProfileField']['options']);
            } else {
                $this->request->data['ProfileField']['options'] = '';
            }
            if (isset($this->request->data['other'])) {
                $this->request->data['ProfileField']['options'] .=",(other)";
            }
            $this->request->data['ProfileField']['profile_field'] = strtolower(str_replace(' ', '_', $this->request->data['ProfileField']['profile_field']));
            $ProfileFieldscheck = $this->ProfileField->find('first', array(
                'conditions' => array(
                    'ProfileField.profile_field' => $this->request->data['ProfileField']['profile_field'],
                    'ProfileField.id !='=>$this->request->data['ProfileField']['id']
                )
            ));
            if(empty($ProfileFieldscheck) && $this->request->data['ProfileField']['profile_field']!='address1' && $this->request->data['ProfileField']['profile_field']!='address2'){
            if ($this->ProfileField->save($this->request->data)) {
                $this->Session->setFlash('The Profile Field has been updated.', 'default', array(), 'good');
                return $this->redirect('/AdminProfileFieldManagement/edit/' . $id);
            } else {
                $this->Session->setFlash(__('ERR:The Profile Field not updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            }else{
                $this->Session->setFlash('ERR:Profile Field already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * delete profile fields.
     * @param type $id
     * @return type
     */
    public function delete($id) {
        if ($this->ProfileField->deleteAll(array('ProfileField.id' => $id))) {
            $this->Session->setFlash('The Profile Field has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('ERR:The Profile Field not deleted.'));
            return $this->redirect(array('action' => 'index'));
        }
    }
    /**
     *  preview of profile fields created by super admin
     * 
     * 
     */
    public function getpreview() {
        $this->layout = "";
        $html = '';
        //html for checkbox

        if ($_POST['data']['ProfileField']['type'] == 'CheckBox') {

            $html .='<div class="form-group><span style="display:block; font-weight:bold;">' . $_POST['data']['ProfileField']['profile_field'] . ':</span><div>';
            foreach ($_POST['data']['ProfileField']['options'] as $option) {
                $html .='<label class="checkbox-inline"><input type="checkbox">' . $option . '</label>';
            } if (isset($_POST['other'])) {
                $html .='<label class="checkbox-inline"><input type="checkbox" onclick="opt()" id="getopt">Other</label>';
            }

            $html .='<div id="othertext" class="prevhtml clearfix"></div></div></div>';
        }

        //html for multiselect
        if ($_POST['data']['ProfileField']['type'] == 'MultiSelect') {
            $html .='<div class="form-group"><span style="display:block; font-weight:bold;">' . $_POST['data']['ProfileField']['profile_field'] . ':</span><select size="4" multiple="multiple" class="form-control select-info" style="height:80px"><option>Please Select</option>';
            foreach ($_POST['data']['ProfileField']['options'] as $option) {
                $html .='<option>' . $option . '</option>';
            }
            $html .='</select></div>';
        }

        //html for radio
        if ($_POST['data']['ProfileField']['type'] == 'RadioButton') {
            $html .='<div class="form-group"><span style="display:block; font-weight:bold;">' . $_POST['data']['ProfileField']['profile_field'] . ':</span><div class="radio_prev">';
            foreach ($_POST['data']['ProfileField']['options'] as $option) {
                $html .='<div class="col-xs-6 pull-left"><input type="radio" class="form-control"  name="radiobox" onclick="opt1(\'' . $option . '\')"><label class=" control-label">' . $option . '</label></div>';
            }
            if (isset($_POST['other'])) {
                $html .='<div class="col-xs-6 pull-left"><input type="radio" class="form-control" onclick="opt1(\'other\')" name="radiobox"><label class=" control-label">Other</label></div>';
            }
            $html .='</div></div>';
        }
        $html .='<div id="othertext" class="prevhtml clearfix"></div>';
        echo $html;
        exit;
    }

}

?>
