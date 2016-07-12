<?php
/**
 *  This file for buzzydoc landing page where show default top 3 doctors using pincode and searching using doctor type and zipcode.
 */
App::uses('AppController', 'Controller');
App::import('Vendor', 'facebook/facebook');
App::uses('CakeEmail', 'Network/Email');
/**
 *  This controller for buzzydoc landing page where show default top 3 doctors using pincode and searching using doctor type and zipcode.
 */
class DashboardController extends AppController {
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
     * For Staff site we use the staffLayout layout
     * @var type 
     */
    public $uses = array('Promotion', 'Reward', 'Staff', 'User', 'ClinicUser', 'ProfileFieldUser', 'Category', 'CardNumber', 'ProfileField', 'Document', 'Doctor', 'ClinicLocation', 'DoctorLocation', 'RateReview', 'IndustryType', 'Clinic', 'State', 'City', 'WishList', 'Transaction', 'Notification', 'Refer', 'UnregTransaction', 'ContestClinic', 'ClinicPromotion', 'Refpromotion', 'LeadLevel');

    public function beforeFilter() {
        
    }
    /**
     *  on landing page show default top 3 doctors.
     */
    public function login() {

        $this->layout = "buzzydoclanding";
        //api to search top 3 doctor
        $doctor = $this->Api->submit_cURL_Get($_SERVER['HTTP_HOST'] . '/api/gettop3doctor.json');

        $doctors = json_decode($doctor);
        if ($doctors->top3doc->success == 1) {

            $this->set('Doctors', $doctors->top3doc->data);
        } else {
            $this->set('Doctors', array());
        }
    }
    /**
     *  searching doctor via pincode and doctor type.
     */
    public function getdoctor() {
        $this->layout = "";
        $data = array('specialty' => $_POST['specialty'],'pincode' => $_POST['pincode']);
        //api to search top doctor via pincode and doctor type
        $doctor = $this->Api->submit_cURL(json_encode($data), $_SERVER['HTTP_HOST'] . '/api/getspecialitydoc.json');
        $alldoctor = json_decode($doctor);
        
        $html = '';
        if ($alldoctor->specilitydoc->success == 1) {
            if (count($alldoctor->specilitydoc->data) > 0) {
                foreach ($alldoctor->specilitydoc->data as $toprated) {
                    $totalrate = 0;
                    foreach ($toprated as $trKey => $trVal) {

                        if ($trKey == 0 && isset($trVal->totalrate)) {
                            $totalrate = round($trVal->totalrate);
                        }
                    }
                    $html .='<li>
                  <a href="#">
                    <div class="doctor-detials cf">
                      <div class="doctor-image">';
                    if ($toprated->dc->gender == 'Male') {
                        $html .='<img alt="buzzydoc overview" title="buzzydoc overview" src="'.CDN.'img/images_buzzy/doctor-male.png">';
                    } else {
                        $html .='<img alt="buzzydoc overview" title="buzzydoc overview" src="'.CDN.'img/images_buzzy/doctor-female.png">';
                    }
                    $html .='</div>
                      <div class="doctor-name">
                      <h3>Dr. ' . $toprated->dc->first_name . ' ' . $toprated->dc->last_name . ',' . $toprated->dc->specialty . '</h2>
                        <div class="rating">';
                    for ($i = 0; $i < $totalrate; $i++) {
                        $html .='<span class="fullstar"></span>';
                    }

                    $html .='</div>
                      </div>
                      <div class="doctor-address">
                        <h4>' . $toprated->dc->address . '</h4>
                        <p>
                          ' . $toprated->dc->city . ', ' . $toprated->dc->state . '</p>
                      </div>
                    </div>
                  </a>
                </li>';
                }
            } else {
                $html = '<li>
                    <div class="doctor-detials cf">
                      No Doctor Found!
                    </div>
                </li>';
            }
        } else {
            $html = '<li>
                    <div class="doctor-detials cf">
                      No Doctor Found!
                    </div>
                </li>';
        }
        echo $html;
        exit;
    }
    /**
     *  searching doctor via pincode and doctor type.
     */
    public function getdoctorviapincode() {
        $this->layout = "";
     
        $data = array('specialty' => $_POST['specialty'],'pincode' => $_POST['pincode']);
        //api to search top doctor via pincode and doctor type
        $doctor = $this->Api->submit_cURL(json_encode($data), $_SERVER['HTTP_HOST'] . '/api/getspecialitydoc.json');
        $alldoctorid = json_decode($doctor);
        
        $html = '';
        if ($alldoctorid->specilitydoc->success == 1) {
            if (count($alldoctorid->specilitydoc->data) > 0) {

                foreach ($alldoctorid->specilitydoc->data as $toprated) {
                    $totalrate = 0;
                    foreach ($toprated as $trKey => $trVal) {

                        if ($trKey == 0 && isset($trVal->totalrate)) {
                            $totalrate = round($trVal->totalrate);
                        }
                    }

                    $html .='<li>
                  <a href="#">
                    <div class="doctor-detials cf">
                      <div class="doctor-image">';
                    if ($toprated->dc->gender == 'Male') {
                        $html .='<img alt="buzzydoc overview" title="buzzydoc overview" src="'.CDN.'img/images_buzzy/doctor-male.png">';
                    } else {
                        $html .='<img alt="buzzydoc overview" title="buzzydoc overview" src="'.CDN.'img/images_buzzy/doctor-female.png">';
                    }
                    $html .='</div>
                      <div class="doctor-name">
                        <h3>Dr. ' . $toprated->dc->first_name . ' ' . $toprated->dc->last_name . ',' . $toprated->dc->specialty . '</h2>
                        <div class="rating">';
                    for ($i = 0; $i < $totalrate; $i++) {
                        $html .='<span class="fullstar"></span>';
                    }

                    $html .='</div>
                      </div>
                      <div class="doctor-address">
                        <h4>' . $toprated->dc->address . '</h4>
                        <p>
                          ' . $toprated->dc->city . ', ' . $toprated->dc->state . '</p>
                      </div>
                    </div>
                  </a>
                </li>';
                }
            } else {
                $html = '<li>
                  <a href="#">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
            }
        } else {
            $html = '<li>
                  <a href="#">
                    <div class="doctor-detials cf">
                     
                      No Doctor Found!
                      
                    </div>
                  </a>
                </li>';
        }
        echo $html;
        exit;
    }

}

?>
