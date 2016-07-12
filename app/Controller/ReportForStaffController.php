<?php

/**
 * integrateortho2.sourcefuse.com/ReportForStaff/index/1 run this for save log in database at qa site
 * rewards.lamparski.com/ReportForStaff/index/1 run this for save log in database at qa site
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller for store report in database.

 */
class ReportForStaffController extends AppController {

    /**
     * We use the session,api and CakeS3 component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api', 'CakeS3.CakeS3' => array(
            's3Key' => AWS_KEY,
            's3Secret' => AWS_SECRET,
            'bucket' => AWS_BUCKET
    ));

    /**
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $uses = array('Clinic');

    /**
     * This controller for store report in database.
     */
    public function index() {

        if(isset($this->request->pass[0])){
        $clinicreporting = $this->Clinic->find('all', array(
            'joins' => array(
                array(
                    'table' => 'access_staffs',
                    'alias' => 'AccessStaff',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AccessStaff.clinic_id = Clinic.id'
                    )
                )
            ),
            'conditions' => array(
                'AccessStaff.staff_reward_program' => 1),
            'fields' => array('Clinic.id', 'Clinic.api_user', 'Clinic.display_name')
        ));
        foreach ($clinicreporting as $clinicreport) {
            $i = -1;
            while ($i < 0) {
                $reports = $this->Api->getClinicReportingData($i, $clinicreport['Clinic']['id'], null, 0);
                
                if ($i == -4*$this->request->pass[0])
                    die;
                $i--;
            }
        }
        }
        die;
    }

}

?>
