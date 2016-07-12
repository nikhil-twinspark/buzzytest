<?php
/**
 *  This file is for add,edit,delete global badges
 * 
 * 
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 *  This controller is for add,edit,delete global badges
 * 
 * 
 */
class BadgesManagementController extends AppController {
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
    public $uses = array('Clinic', 'Badge');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }

    /**
     * Fetch global badges added by super admin.
     */
    public function index() {
        $this->layout = "adminLayout";
        $options['conditions'] = array('Badge.clinic_id' => 0);
        $charinsu = $this->Badge->find('all',$options);
        $this->set('Badge', $charinsu);
    }

    /**
     *  Add global badges.
     */
    public function add() {
        $this->layout = "adminLayout";
        if ($this->request->is('post')) {
            $options['conditions'] = array('Badge.name' => $this->request->data['name'],'Badge.clinic_id'=>0);
            $ind = $this->Badge->find('first', $options);
            if (empty($ind)) {
                $this->request->data['clinic_id']=0;
                $data['Badge']=$this->request->data;
                if ($this->Badge->save($data)) {

                    $this->Session->setFlash('The Badge has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The Badge could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Badge already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * edit global badges.
     * @param type $id
     * @throws NotFoundException
     */
    public function edit($id) {
        $this->layout = "adminLayout";
        $optionsid['conditions'] = array('Badge.id' => $id);
        $optionsid['fields'] = array('Badge.*');
        $indData = $this->Badge->find('first', $optionsid);
        $this->set('Badge', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $options['conditions'] = array('Badge.name' => $this->request->data['name'], 'Badge.id !=' => $this->request->data['id'],'Badge.clinic_id'=>0);
            $ind = $this->Badge->find('first', $options);

            if (empty($ind)) {
                $this->request->data['clinic_id']=0;
                $data['Badge']=$this->request->data;
                if ($this->Badge->save($data)) {

                    $this->Session->setFlash('The Badge has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The  Badge could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Badge already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     * delete global badge.
     * @param type $id
     * @return type
     */
    public function delete($id) {
        if ($this->Badge->deleteAll(array('Badge.id' => $id))) {
            $this->Session->setFlash('Badge has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Badge not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }

}

?>
