<?php
/**
 *  This file for running cron on every 30 min to check completion of treatment phase and send a mail to patient.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for running cron on every 30 min to check completion of treatment phase and send a mail to patient.
 */
class CrontortreatmentcompletionController extends AppController {
    /**
     * We use the session and api component for this controller.
     * @var type 
     */
    public $components = array('Session', 'Api');
    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('User', 'ClinicUser', 'CardNumber', 'Transaction', 'Clinic', 'UserAssignedTreatment');
    /**
     * Cron for run on every 30 min to find out tretment phase completion for all patients.
     */
    public function index() {

        $date3 = date("Y-m-d H:i:s", strtotime("-30 minutes"));
        //find out the phase completion transaction
        $options['conditions'] = array(
            'Transaction.treatment_id >' => 0,
            'Transaction.amount !=' => 0,
            'Transaction.date <' => $date3,
            'Transaction.mail_send' => 0
        );

        $data = $this->Transaction->find('all', $options);

        foreach ($data as $dt) {

            $optiongetuser['conditions'] = array(
                'User.id' => $dt['Transaction']['user_id'], 'User.email !=' => '');

            $getuser = $this->User->find('first', $optiongetuser);
            $records_pf_vl = array(
                "Transaction" => array(
                    "id" => $dt['Transaction']['id'],
                    "mail_send" =>1 
                )
            );
            $this->Transaction->save($records_pf_vl);

            if (!empty($getuser)) {
                $template_array = $this->Api->get_template(38);
                $link = str_replace('[username]', $getuser['User']['first_name'], $template_array['content']);

                $link1 = str_replace('[points]', $dt['Transaction']['amount'], $link);

                $Email = new CakeEmail(MAILTYPE);
                $Email->from(array(SUPER_ADMIN_EMAIL => 'BuzzyDoc'));

                $Email->to($getuser['User']['email']);
                $Email->subject($template_array['subject'])
                        ->template('buzzydocother')
                        ->emailFormat('html');

                $Email->viewVars(array('msg' => $link1
                ));
                $Email->send();
            }
        }

        die;

        ////////
    }

}

?>
