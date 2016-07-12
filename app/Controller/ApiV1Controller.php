<?php

/**
 * This file is for third party login and patient search through api.
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * This controller is for third party login and patient search through api.
 */
class ApiV1Controller extends AppController {

    /**
     * These All database model in use.
     * @var type 
     */
    public $uses = array('Clinic', 'Staff', 'ApiToken');

    /**
     * These all helper we use for this controller.
     * 
     * @var type 
     */
    public $helpers = array('Html', 'Form');

    /**
     * We use the session and api components for this controller.
     * @var type 
     */
    public $components = array('RequestHandler', 'Api', 'Session');

    /**
     * This function check the api_token for pratice and check staff for that pratice and redirect if success or give the error response code.
     * @return type
     */
    public function login() {
        $this->layout = "";
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = explode('.', $_SERVER['HTTP_HOST']);
            $options1['conditions'] = array('Clinic.staff_url Like' => "%" . $_SERVER['HTTP_HOST'] . "%");
            $options1['fields'] = array('Clinic.id');
            $credResult = $this->Clinic->find('first', $options1);
            if (empty($credResult)) {
                $chkdomain = str_replace($host[0] . '.', '', $_SERVER['HTTP_HOST']);
                if ($chkdomain == Domain_Name) {
                    $optionsfind['conditions'] = array('Clinic.staff_url LIKE ' => "%" . $host[0] . "%");
                    $optionsfind['fields'] = array('Clinic.id');
                    $credResult = $this->Clinic->find('first', $optionsfind);
                }
            }
        }

        //echo $key = md5(microtime().rand());
        $options['conditions'] = array('ApiToken.api_token' => $this->request->data['api_token'], 'ApiToken.clinic_id' => $credResult['Clinic']['id']);
        //condition to check search parameter is pass by third party or not.
        if (isset($this->request->data['search_param']) && $this->request->data['search_param'] != '') {
            $serachtext = '/' . base64_encode($this->request->data['search_param']);
        } else {
            $serachtext = '';
        }
        $checktoken = $this->ApiToken->find('first', $options);
        //condition to check api_token is valid.
        if (empty($checktoken)) {
            $response = array('success' => 401, 'message' => 'Authentication Failure (wrong api_token)');
            $this->set('Response', $response);
            $this->render('/Elements/apiFailResponse');
        } else {
            $clinic = $this->Clinic->find('first', array('conditions' => array('Clinic.id' => $checktoken['ApiToken']['clinic_id'])));
            //condition to check staff id provided by third party.
            if (isset($this->request->data['staff_id']) && $this->request->data['staff_id'] != '') {
                $optionsstaff['conditions'] = array('Staff.staff_id' => $this->request->data['staff_id'], 'Staff.clinic_id' => $checktoken['ApiToken']['clinic_id']);
                $checkstaffid = $this->Staff->find('first', $optionsstaff);
                //validate staff id for pratice.
                if (empty($checkstaffid)) {
                    $response = array('success' => 400, 'message' => 'Invalid Staff User Name.');
                    $this->set('Response', $response);
                    $this->render('/Elements/apiFailResponse');
                } else {

                    //condition for those pratice who not have web url.
                    if (Domain_Name == 'integratestg.sourcefuse.com' && $checktoken['ApiToken']['clinic_id'] != 52 && $checktoken['ApiToken']['clinic_id'] != 67 && $checktoken['ApiToken']['clinic_id'] != 70) {
                        $dname = 'http://' . str_replace(' ', '', $clinic['Clinic']['api_user']) . '.' . Domain_Name . '/staff/login/' . base64_encode($checkstaffid['Staff']['id']) . $serachtext;
                    } else {
                        $dname = rtrim($clinic['Clinic']['staff_url'], '/') . '/staff/login/' . base64_encode($checkstaffid['Staff']['id']) . $serachtext;
                    }
                    //redirect to practice staff site.
                    return $this->redirect($dname);
                }
            } else {
                //condition to check practice setup for default staff login.
                if ($checktoken['ApiToken']['is_superstaff'] == 1) {
                    $optionsstaff['conditions'] = array('Staff.is_superstaff' => 1, 'Staff.clinic_id' => $checktoken['ApiToken']['clinic_id']);
                    $checkstaffid = $this->Staff->find('first', $optionsstaff);
                    //condition to check practice have default staff user.
                    if (empty($checkstaffid)) {
                        $response = array('success' => 404, 'message' => 'Please provide staff user name.');
                        $this->set('Response', $response);
                        $this->render('/Elements/apiFailResponse');
                    } else {
                        //condition for those pratice who not have web url.
                        if (Domain_Name == 'integratestg.sourcefuse.com' && $checktoken['ApiToken']['clinic_id'] != 52 && $checktoken['ApiToken']['clinic_id'] != 67 && $checktoken['ApiToken']['clinic_id'] != 70) {
                            $dname = 'http://' . str_replace(' ', '', $clinic['Clinic']['api_user']) . '.' . Domain_Name . '/staff/login/' . base64_encode($checkstaffid['Staff']['id']) . $serachtext;
                        } else {
                            $dname = rtrim($clinic['Clinic']['staff_url'], '/') . '/staff/login/' . base64_encode($checkstaffid['Staff']['id']) . $serachtext;
                        }

                        return $this->redirect($dname);
                    }
                } else {
                    $response = array('success' => 404, 'message' => 'Please provide staff user name.');
                    $this->set('Response', $response);
                    $this->render('/Elements/apiFailResponse');
                }
            }
        }
    }

}

?>
