<?php
/**
 * This file for add,edit,delete training video by super admin.
 * There is list of watched list of staff user.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * This controller for add,edit,delete training video by super admin.
 * There is list of watched list of staff user.
 */
class TrainingVideoController extends AppController {
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
    public $uses = array('Clinic', 'TrainingVideo','WatchList','Staff');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');
        //condition to check super admin is loged in or not.
        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     * Getting the list of all training video added by super admin.
     */
    public function index() {
        $this->layout = "adminLayout";
        $charinsu = $this->TrainingVideo->find('all');
        $this->set('TrainingVideo', $charinsu);
    }
    /**
     * Add new training video.
     */
    public function add() {
        $this->layout = "adminLayout";
        if ($this->request->is('post')) {
            $options['conditions'] = array('TrainingVideo.title' => $this->request->data['title']);
            $ind = $this->TrainingVideo->find('first', $options);
            //condition to check duplicate training video.
            if (empty($ind)) {
                $data['TrainingVideo'] = $this->request->data;
                if ($this->TrainingVideo->save($data)) {

                    $this->Session->setFlash('The Training Video has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The Training Video could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Training Video already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     * edit training video.
     * @param type $id
     * @throws NotFoundException
     */
    public function edit($id) {

        $this->layout = "adminLayout";
        $optionsid['conditions'] = array('TrainingVideo.id' => $id);
        $optionsid['fields'] = array('TrainingVideo.*');
        $indData = $this->TrainingVideo->find('first', $optionsid);
        $this->set('TrainingVideo', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $options['conditions'] = array('TrainingVideo.title' => $this->request->data['name'], 'TrainingVideo.id !=' => $this->request->data['id']);
            $ind = $this->TrainingVideo->find('first', $options);
            //condition to check duplicate training video.
            if (empty($ind)) {
                $data['TrainingVideo'] = $this->request->data;
                if ($this->TrainingVideo->save($data)) {

                    $this->Session->setFlash('The Training Video has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The  Training Video could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Training Video already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     * Delete training video.
     * @param type $id
     * @return type
     */
    public function delete($id) {

        if ($this->TrainingVideo->deleteAll(array('TrainingVideo.id' => $id))) {
            $this->Session->setFlash('Training Video has been deleted.', 'default', array(), 'good');
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Training Video not deleted.', 'default', array(), 'bad');
            return $this->redirect(array('action' => 'index'));
        }
    }
    /**
     * Getting the list of staff user who watched the video.
     * @param type $id
     */
    public function watched($id) {
        $this->layout = "adminLayout";
        $watchlist = $this->WatchList->find('all', array(
            'joins' => array(
                array(
                    'table' => 'staffs',
                    'alias' => 'Staff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Staff.id = WatchList.staff_id'
                    )
                ),
                array(
                    'table' => 'training_videos',
                    'alias' => 'TrainingVideo',
                    'type' => 'INNER',
                    'conditions' => array(
                        'TrainingVideo.id = WatchList.traiing_video_id'
                    )
                )
            ),
            'conditions' => array(
                'WatchList.traiing_video_id' => $id
            ),
            'fields' => array(
                'WatchList.watched_on', 'TrainingVideo.title','Staff.staff_id'
            ),
            'order' => array('WatchList.watched_on desc')
        ));
        $this->set('watchlist', $watchlist);
    }

}

?>
