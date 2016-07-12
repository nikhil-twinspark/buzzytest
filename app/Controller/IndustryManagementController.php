<?php
/**
 *  This file for add,edit global industry type.
 *  add,edit,delete indusrty wies global promotions.
 *  add,edit,delete industry wies referral promotions.
 *  add,edit industry wies lead levels.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for add,edit global industry type.
 *  add,edit,delete indusrty wies global promotions.
 *  add,edit,delete industry wies referral promotions.
 *  add,edit industry wies lead levels.
 */
class IndustryManagementController extends AppController {
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
    public $uses = array('Clinic', 'Theme', 'IndustryType', 'LeadLevel', 'IndustryPromotion', 'Refpromotion', 'ClinicPromotion','IndustryTextLevel');

    public function beforeFilter() {
        $sessionadmin = $this->Session->read('Admin');

        if (empty($sessionadmin) && $this->params['controller'] != 'Admin') {
            return $this->redirect(array('controller' => 'Admin', 'action' => 'login'));
        }
    }
    /**
     *  fetch list of global industry type.
     */
    public function index() {
        $this->layout="adminLayout";
        $indsurty = $this->IndustryType->find('all');

        $this->set('indsurty', $indsurty);
    }
    /**
     *  add new industry type.
     */
    public function add() {
        $this->layout="adminLayout";
        if ($this->request->is('post')) {
            //adding all default referral message in to string
            $msgarray = array();
            for ($i = 1; $i <= $this->request->data['cnt']; $i++) {
                $msgarray['reffralmessage' . $i] = $this->request->data['reffralmessage' . $i];
                $msgarray['cnt'] = $this->request->data['cnt'];
                unset($this->request->data['reffralmessage' . $i]);
            }
            $text = json_encode($msgarray);

            $options['conditions'] = array('IndustryType.name' => $this->request->data['IndustryType']['name']);
            $ind = $this->IndustryType->find('first', $options);
            if (empty($ind)) {
                unset($this->request->data['cnt']);
                $this->request->data['IndustryType']['reffralmessages'] = $text;
                if ($this->IndustryType->save($this->request->data)) {

                    $this->Session->setFlash('The industry has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "index"));
                } else {
                    $this->Session->setFlash('The industry could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Unable to add industry.Industry Already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit default industry type details.
     * @param type $id
     * @throws NotFoundException
     */
    public function edit($id = null) {
        $this->layout="adminLayout";
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $indData = $this->IndustryType->findById($id);



        $this->set('indsurty', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            //condition to check duplicate industry type
            $options['conditions'] = array('IndustryType.name' => $this->request->data['IndustryType']['name'], 'IndustryType.id !=' => $this->request->data['IndustryType']['id']);
            $ind = $this->IndustryType->find('first', $options);

            if (empty($ind)) {
                //adding all default referral message in to string
                $msgarray = array();
                for ($i = 1; $i <= $this->request->data['cnt']; $i++) {
                    $msgarray['reffralmessage' . $i] = $this->request->data['reffralmessage' . $i];
                    $msgarray['cnt'] = $this->request->data['cnt'];
                    unset($this->request->data['reffralmessage' . $i]);
                }

                $text = json_encode($msgarray);

                $this->request->data['IndustryType']['reffralmessages'] = $text;
                unset($this->request->data['cnt']);

                if ($this->IndustryType->save($this->request->data)) {

                    $this->Session->setFlash('The industry has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edit/$id"));
                } else {
                    $this->Session->setFlash('The industry could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Unable to save industry.Industry Already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  lead level list for particular industry type.
     * @param type $id
     */
    public function manageleadlevel($id) {
        $this->layout="adminLayout";
        $indsurty = $this->LeadLevel->find('all', array('conditions' => array('LeadLevel.industryId' => $id)));
        $this->set('industryid', $id);
        $this->set('LeadLevel', $indsurty);
    }
    /**
     *  adding new lead level for industry type.
     * @param type $id
     */
    public function addleadlevel($id) {
        $this->layout="adminLayout";
         
        $this->set('industryid', $id);
        if ($this->request->is('post')) {


            $options['conditions'] = array('LeadLevel.leadname' => $this->request->data['leadname'], 'LeadLevel.industryId' => $this->request->data['industryId']);
            $ind = $this->LeadLevel->find('first', $options);
            //condition to check duplicate lead level for industry type
            if (empty($ind)) {
                $indid = $this->request->data['industryId'];
                $data['LeadLevel']=$this->request->data;
                if ($this->LeadLevel->save($data)) {

                    $this->Session->setFlash('The Lead Level has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "manageleadlevel/$indid"));
                } else {
                    $this->Session->setFlash('The lead level could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Lead level already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit lead level for industry type.
     * @param type $indid
     * @param type $ledid
     * @throws NotFoundException
     */
    public function editleadlevel($indid = null, $ledid = null) {
        $this->layout="adminLayout";
        $this->set('industryid', $indid);
        if (!$ledid) {
            throw new NotFoundException(__('Invalid client'));
        }
        $indData = $this->LeadLevel->findById($ledid);



        $this->set('LeadLevel', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $options['conditions'] = array('LeadLevel.leadname' => $this->request->data['LeadLevel']['leadname'], 'LeadLevel.industryId' => $this->request->data['LeadLevel']['industryId'], 'LeadLevel.id !=' => $this->request->data['LeadLevel']['id']);
            $ind = $this->LeadLevel->find('first', $options);
            //condition to check duplicate lead level for industry type
            if (empty($ind)) {

                if ($this->LeadLevel->save($this->request->data)) {

                    $this->Session->setFlash('The Lead level has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "editleadlevel/$indid/$ledid"));
                } else {
                    $this->Session->setFlash('The Lead Level could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Lead level already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     *  list of default promotion for industry type.
     * @param type $id
     * 
     */
    public function managepromotion($id) {
        $this->layout="adminLayout";
        $indsurty = $this->IndustryPromotion->find('all', array('conditions' => array('IndustryPromotion.industry_id' => $id)));
        $this->set('industryid', $id);
        $this->set('Promotion', $indsurty);
    }
    /**
     *  add new default promotion for industry type.
     * @param type $id
     */
    public function addpromotion($id) {
        $this->layout="adminLayout";
        $this->set('industryid', $id);
        if ($this->request->is('post')) {


            $options['conditions'] = array('OR'=>array('IndustryPromotion.description' => trim($this->request->data['IndustryPromotion']['description']),'IndustryPromotion.display_name' => trim($this->request->data['IndustryPromotion']['display_name'])), 'IndustryPromotion.industry_id' => $this->request->data['IndustryPromotion']['industry_id']);
            $ind = $this->IndustryPromotion->find('first', $options);
            //condition to check duplicate promotion for industry
            if (empty($ind)) {
                $indid = $this->request->data['IndustryPromotion']['industry_id'];
                if ($this->IndustryPromotion->save($this->request->data)) {

                    $this->Session->setFlash('The Promotion has been added', 'default', array(), 'good');
                    $this->redirect(array('action' => "managepromotion/$indid"));
                } else {
                    $this->Session->setFlash('The Promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }

    /**
     *  edit default promotion for industry type.
     * @param type $indid
     * @param type $ledid
     * @throws NotFoundException
     */
    public function editpromotion($indid = null, $ledid = null) {
        $this->layout="adminLayout";
        $this->set('industryid', $indid);
        if (!$ledid) {
            throw new NotFoundException(__('Invalid client'));
        }
        $indData = $this->IndustryPromotion->findById($ledid);
        $this->set('Promotion', $indData);
        if (!$indData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $options['conditions'] = array('OR'=>array('IndustryPromotion.description' => trim($this->request->data['IndustryPromotion']['description']),'IndustryPromotion.display_name' => trim($this->request->data['IndustryPromotion']['display_name'])), 'IndustryPromotion.industry_id' => $this->request->data['IndustryPromotion']['industry_id'], 'IndustryPromotion.id !=' => $this->request->data['IndustryPromotion']['id']);
            $ind = $this->IndustryPromotion->find('first', $options);
            //condition to check duplicate promotion for industry
            if (empty($ind)) {

                if ($this->IndustryPromotion->save($this->request->data)) {

                    $this->Session->setFlash('The Promotion has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "editpromotion/$indid/$ledid"));
                } else {
                    $this->Session->setFlash('The Promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('Promotion already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  delete promotion for industry type.
     * @param type $indid
     * @param type $ledid
     * @param type $proname
     */
        public function deletepromotion($indid,$ledid,$proname) {
        
        $this->IndustryPromotion->deleteAll(array('IndustryPromotion.id' => $ledid));

        $this->Session->setFlash('Promotion "'.$proname.'" has been deleted.', 'default', array(), 'good');
        $this->redirect(array('action' => "managepromotion/$indid"));
    }
    /**
     *  list of all referral promotion for industry type.
     * @param type $id
     */
    public function referralpromotion($id) {
        $this->layout="adminLayout";
        $indsurty = $this->Refpromotion->find('all', array('conditions' => array('Refpromotion.industry_id' => $id)));
        $this->set('industryid', $id);

        $this->set('ReferralPromotion', $indsurty);
    }
    
    /**
     *  set as default referral promotion for industry type.
     */
        public function setprime(){
        $this->layout = "";
        $sessionstaff = $this->Session->read('staff');


        $Locations = $this->Refpromotion->find('all', array('conditions' => array('Refpromotion.industry_id' => $_POST['ind_id'])));
        foreach($Locations as $loc){
            $like=0;
            if($loc['Refpromotion']['id']==$_POST['refpro_id']){
                $like=1;
            }
            $loca['Refpromotion'] = array('id' => $loc['Refpromotion']['id'],'dafault'=>$like);
            $this->Refpromotion->save($loca);
        }
        exit;
    }
    /**
     *  add new referral promotion for industry type.
     * @param type $id
     */
    public function addreferralpromotion($id) {
        $this->layout="adminLayout";
        $this->set('industryid', $id);
        if ($this->request->is('post')) {


            $options['conditions'] = array('Refpromotion.promotion_name' => $this->request->data['Refpromotion']['promotion_name'],'Refpromotion.industry_id'=>$this->request->data['Refpromotion']['industry_id']);
            $credResult = $this->Refpromotion->find('all', $options);
            $induid = $this->request->data['Refpromotion']['industry_id'];
            //condition to check duplicate referral promotion for industry
            if (empty($credResult)) {


                if ($this->Refpromotion->save($this->request->data)) {

                    $this->Session->setFlash('The referral promotion has been added', 'default', array(), 'good');
                    
                    $this->redirect(array('action' => "referralpromotion/$induid"));
                } else {
                    $this->Session->setFlash('The referral promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('referral promotion Already exists.', 'default', array(), 'bad');
            }
        }
    }
    /**
     *  edit referral promotion for industry type.
     * @param type $indid
     * @param type $id
     * @return boolean
     * @throws NotFoundException
     */
    public function editreferralpromotion($indid = null, $id = null) {
        $this->layout="adminLayout";
        $this->set('industryid', $indid);
        if (!$id) {
            throw new NotFoundException(__('Invalid client'));
        }
        $clientData = $this->Refpromotion->findById($id);

        if (!$clientData) {
            throw new NotFoundException(__('Invalid post'));
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $urlResult = array();
            $options1['conditions'] = array('Refpromotion.promotion_name' => $this->request->data['Refpromotion']['promotion_name'], 'Refpromotion.id !=' => $this->request->data['Refpromotion']['id'],'Refpromotion.industry_id'=>$this->request->data['Refpromotion']['industry_id']);
            $urlResult = $this->Refpromotion->find('all', $options1);
            //condition to check duplicate referral promotion for industry
            if (empty($urlResult)) {

                if ($this->Refpromotion->save($this->request->data)) {

                    $this->Session->setFlash('The referral promotion has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "editreferralpromotion/$indid/$id"));
                } else {
                    $this->Session->setFlash('The referral promotion could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            } else {
                $this->Session->setFlash('referral promotion Name Already exists.', 'default', array(), 'bad');
                return false;
            }
        } else {

            $this->request->data = $clientData;
        }
    }
    /**
     *  delete referral promotion for industry type.
     * @param type $indid
     * @param type $id
     * @throws MethodNotAllowedException
     */
    public function deletereferralpromotion($indid, $id) {
        if ($this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $options1['conditions'] = array('Refpromotion.id' => $id);
        $contest = $this->Refpromotion->find('first', $options1);
        $this->Refpromotion->deleteAll(array('Refpromotion.id' => $id));
        $this->ClinicPromotion->query('delete from clinic_promotions where promotion_id=' . $id);

        $this->Session->setFlash('The referral promotion: '.h($contest['Refpromotion']['promotion_name']).' has been deleted.', 'default', array(), 'good');
        
        $this->redirect(array('action' => "referralpromotion/$indid/"));
    }

    public function edittext($indid = null) {
        $this->layout="adminLayout";
        $this->set('industryid', $indid);
        $indData = $this->IndustryTextLevel->find('first', array('conditions' => array('IndustryTextLevel.industry_id' => $indid)));

        $this->set('IndustryTextLevel', $indData);


        if ($this->request->is('post') || $this->request->is('put')) {

            

                if ($this->IndustryTextLevel->save($this->request->data)) {

                    $this->Session->setFlash('The text label has been saved', 'default', array(), 'good');
                    $this->redirect(array('action' => "edittext/$indid/$ledid"));
                } else {
                    $this->Session->setFlash('The text label could not be saved. Please, try again.', 'default', array(), 'bad');
                }
            
        }
    }

}

?>
