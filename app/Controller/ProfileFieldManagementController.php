<?php
/**
 * @deprecated Not manage from staff its working from only super admin site
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * @deprecated Not manage from staff its working from only super admin site
 */
class ProfileFieldManagementController extends AppController {

    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session','Api');
    public $uses = array('User', 'Clinic', 'State', 'City', 'ProfileFieldUser', 'ProfileField', 'Transaction', 'Refer');
    public $layout = 'staffLayout';

    public function beforeFilter() {
        $sessionstaff = $this->Session->read('staff');
        if (empty($sessionstaff['var']) && $this->params['action'] != 'login') {

            return $this->redirect('/staff/login/');
        }

        $this->layout = $this->layout;
    }

    public function index() {
        $sessionstaff = $this->Session->read('staff');
        $ProField = $this->ProfileField->find('all', array('conditions' => array('ProfileField.clinic_id' => $sessionstaff['clinic_id'])));
        $this->set('ProfileFields', $ProField);
        $checkaccess=$this->Api->accesscheck($sessionstaff['clinic_id'],'Profile Fields');
        if($checkaccess==0){
        $this->render('/Elements/access');
        }
    }

    public function add() {
        $sessionstaff = $this->Session->read('staff');
        if ($this->request->is('post')) {
            if (isset($this->request->data['ProfileField']['options'])) {
                $this->request->data['ProfileField']['options'] = implode(',', $this->request->data['ProfileField']['options']);
            }
            if (isset($this->request->data['other'])) {
                $this->request->data['ProfileField']['options'] .=",(other)";
            }
            $this->request->data['ProfileField']['profile_field'] = strtolower(str_replace(' ', '_', $this->request->data['ProfileField']['profile_field']));
            $this->request->data['ProfileField']['clinic_id'] = $sessionstaff['clinic_id'];
            $this->ProfileField->create();
            if ($this->ProfileField->save($this->request->data)) {
                $this->Session->setFlash('Profile Field successfully added', 'default', array(), 'good');
                $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash('Unable to add Profile Field', 'default', array(), 'bad');
            }
        }
    }

    public function edit($id) {
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

            if ($this->ProfileField->save($this->request->data)) {
                $this->Session->setFlash('The Profile Field has been updated.', 'default', array(), 'good');
                return $this->redirect('/ProfileFieldManagement/edit/' . $id);
            } else {
                $this->Session->setFlash('ERR:The Profile Field not updated.', 'default', array(), 'bad');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    public function delete($id) {

        if ($this->ProfileField->deleteAll(array('ProfileField.id' => $id))) {
            $this->Session->setFlash(__('The Profile Field has been deleted.'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('ERR:The Profile Field not deleted.'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function getpreview() {
        $this->layout = "";


        $html = '';
        //html for checkbox

        if ($_POST['data']['ProfileField']['type'] == 'CheckBox') {

            $html .='<div class="form-group><span style="display:block; font-weight:bold;">' . $_POST['data']['ProfileField']['profile_field'] . ':</span><div class="check_prev">';
            foreach ($_POST['data']['ProfileField']['options'] as $option) {
                $html .='<label class="checkbox-inline"><input type="checkbox">' . $option . '</label>';
            } if (isset($_POST['other'])) {
                $html .='<label class="checkbox-inline"><input type="checkbox" onclick="opt()" id="getopt">Other</label>';
            }

            $html .='<div id="othertext"  class="prevhtml clearfix"></div></div></div>';
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
        $html .='<div id="othertext"  class="prevhtml clearfix"></div>';
        echo $html;
        exit;
    }

}

?>
