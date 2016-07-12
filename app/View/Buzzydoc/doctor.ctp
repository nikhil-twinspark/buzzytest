<?php
$sessionbuzzy = $this->Session->read('userdetail');
//echo "<pre>";print_r($sessionbuzzy);die;
if(isset($sessionbuzzy->User->id)){
    $userid=$sessionbuzzy->User->id;
}else{
    $userid=0;
}
$uliked = 0;

if (!empty($sessionbuzzy->Saveddoc)) {
    foreach ($sessionbuzzy->Saveddoc as $saved) {
        if (isset($saved->Doctor->id) && $saved->Doctor->id == $Doctors->Doctor->id) {
            $uliked = 1;
        }
    }
}
$rated = 0;
foreach ($sessionbuzzy->givenRate as $rate) {
    if ($rate->rate_reviews->doctor_id == $Doctors->Doctor->id && $rate->rate_reviews->clinic_id == 0) {
        $rated = 1;
    }
}

?>


<section class="relevant-details">
    <div class="row">
        <div class="relevant-adjust">
            <div class="details-wrap cf">
                <header class="relevant-header cf">
                    DOCTOR PROFILE
                    <a href="<?php echo '/practice/' . str_replace(' ','',$Doctors->ClinicName); ?>" class="header-link right">View Practice Profile</a>
                </header>
                <div class="main-details">
                    <div class="details-all-info-wrap">
                        <div class="details-all-info cf">
                            <div class="detials-info">
                                <div class="main-thumb main-thumb-alt">

                                    <?php
                                    $docprofilePath = WWW_ROOT .'img/docprofile/'.$Doctors->ClinicName.'/'. $Doctors->Doctor->id;
                                    $docprofilePath1 = AWS_server . AWS_BUCKET . '/img/docprofile/' . $Doctors->ClinicName . '/' . $Doctors->Doctor->id;

                                    $ch = curl_init($docprofilePath1);

                                    curl_setopt($ch, CURLOPT_NOBODY, true);
                                    curl_exec($ch);
                                    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


                                    if (file_exists($docprofilePath)) {
                                        ?>
                                        <img src="<?= $docprofilePath1 ?>" class='thumb-picture' width="122px" height="132px">

                                        <?php
                                    } else {
                                        if ($Doctors->Doctor->gender == 'Male') {
                                            echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png', array('title' => 'doctor', 'alt' => 'doctor picture', 'class' => 'thumb-picture'));
                                        } else {
                                            echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png', array('title' => 'doctor', 'alt' => 'doctor picture', 'class' => 'thumb-picture'));
                                        }
                                    }

                                    $d1 = new DateTime(date('Y-m-d'));
                                    $d2 = new DateTime($Doctors->Doctor->dob);
                                    $diff = $d2->diff($d1);
                                    ?>



                                </div>
                                <div class="detials-info-all-text detials-info-all-text-alt">
                                    <h4 class="thumb-name">Dr. <?php echo $Doctors->Doctor->first_name; ?> <?php echo $Doctors->Doctor->last_name; ?>, <?php echo $Doctors->Doctor->degree; ?></h4>
                                    <ul class="thumb-info">
                                        <li>Specializes in <?php echo $Doctors->Doctor->specialty; ?></li>
                                        <li><?php echo ucfirst(strtolower($Doctors->Doctor->gender)); ?></li>
                                        <?php if($Doctors->Doctor->dob!='0000-00-00'){ ?><li>Age <?php echo $diff->y; ?></li><?php } ?>
                                    </ul>
                                    <h5 class="address-heading1"><?php echo $Doctors->Doctor->address; ?></h5>
                                    <p class="address-detials1">
                                        <?php echo $Doctors->Doctor->city; ?> , <?php echo $Doctors->Doctor->state; ?> , <?php echo $Doctors->Doctor->pincode; ?>
                                    </p>
                                </div>
                            </div><!-- .detials-info-->
                            
                            <div class="main-buttons">
                                
                                <?php if ($uliked == 0) {if($userid==0){ ?>
                                    <a href="javascript:void(0)" title="Sign in" id="home-login" data-target="#main-sign-in_login" data-toggle="modal">Save</a>
                                <?php }else{ ?>
                                    <a href="javascript:void(0)" class="main-btn-1" title="Save" onclick="savedoctor()" id="savedoc">Save</a>
                                <?php }} else { ?>
                                    <a class="main-btn-1 clicked" title="Save" id="savedoc">Save</a>
                                <?php } if($Doctors->is_buzzydoc==1){ if($userid==0){ ?>
                                <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="main-btn-2" title="Recommend">Recommend</a>
                                <?php }else{ ?>
                                <a href="javascript:void(0)" class="main-btn-2" title="Recommend" onclick="inviteFriend();">Recommend</a>
                                <?php }}else{ ?>
                                <a href="#recommend-box" class="main-btn-2 clicked" title="Recommend">Recommend</a>
                                <?php
                                }if ($rated == 0) {if($userid==0){ ?>
                                    <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="main-btn-3" title="Rate">Rate</a>
                                <?php }else{ ?>
                                    <a href="#rate-box" class="main-btn-3 inline" id="rate" title="Rate">Rate</a>
                                <?php }} else { ?>
                                    <a class="main-btn-3 clicked" id="rate" title="Rate">Rate</a>
                                <?php }if($userid==0){ ?>
                                <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="main-btn-4" title="Schedule an Appointment">Schedule an Appointment</a>
                                <?php }else{ ?>
                                <a href="#appointment-box" class="main-btn-4 inline" title="Schedule an Appointment">Schedule an Appointment</a>
                                <?php } ?>
                                <span id="savedocbtn-status"></span>
                            </div><!-- .main-buttons-->

                            <div class="sub-details">
                                <div class="sub-details-inner">
                                    <header class="top-5-chars">Top 5 Characteristics:</header>
                                    <ul class="chars-listing">
                                        <?php
                                        $i = 0;
//echo "<pre>";print_r($Doctors->Characteristics);
                                        foreach ($Doctors->Characteristics as $chracter) {
                                            /* $liked=0;
                                              foreach($sessionbuzzy->CharacteristicLike as $userlike){
                                              if($chracter->CharacteristicInsurance->id==$userlike->characteristic_insurance_likes->characteristic_insurance_id && $userlike->characteristic_insurance_likes->doctor_id==$Doctors->Doctor->id){
                                              $liked=1;
                                              break;
                                              }
                                              } */
                                            if($userid!=0){
                                            ?>
                                            <li style="cursor: pointer;" id="character_<?php echo $chracter->CharacteristicInsurance->id; ?>" ><div onclick="characteristiclike(<?php echo $chracter->CharacteristicInsurance->id; ?>,<?php echo $chracter->totallike; ?>, '<?php echo $chracter->CharacteristicInsurance->name; ?>');"><span class="relevant-point" ><?php echo $chracter->totallike; ?></span><?php echo $chracter->CharacteristicInsurance->name; ?></div></li>
                                            <?php
                                            $i++;
                                            if ($i == 5)
                                                break;
                                            }else{ ?>
                                            <li style="cursor: pointer;" ><div id="home-login" data-target="#main-sign-in_login" data-toggle="modal"><span class="relevant-point" ><?php echo $chracter->totallike; ?></span><?php echo $chracter->CharacteristicInsurance->name; ?></div></li>
                                            <?php
                                            $i++;
                                            if ($i == 5)
                                                break;
                                            }
                                            }
                                        ?>

                                    </ul>
                                </div>
                            </div><!-- .sub-details-->

                            <div class="all-tabs-wrap">
                                <div id="horizontalTab" class="all-tabs-container cf">
                                    <ul class="tab-menu-listing">
                                        <li>
                                            <a href="#tab-1" class="alternate-tab">
                                                <div class="tab-rating-wrap">
                                                    <div class="rating tab-rating" id="ratedstar">
                                                        <?php
                                                        $grey = 5 - $Doctors->Rate;
                                                        for ($i = 0; $i < $Doctors->Rate; $i++) {
                                                            ?>
                                                            <span class="fullstar"></span>
                                                        <?php
                                                        }
                                                        for ($i1 = 0; $i1 < $grey; $i1++) {
                                                            ?>
                                                            <span class="greystar"></span>
<?php } ?>
                                                    </div>
                                                    <span class="tab-sub-txt" id="rateshow">(<?php echo number_format((float) $Doctors->Rate, 1, '.', ''); ?>)</span>
                                                </div>
                                                <span class="alternate-txt">Patient Satisfaction</span>
                                            </a>
                                        </li>
                                        <li><a href="#tab-2">Overview</a></li>
                                        <li><a href="#tab-3">Other Characteristics</a></li>
                                        <li><a href="#tab-4">Promotions</a></li>
                                        <li><a href="#tab-5">Offices</a></li>
                                    </ul>

                                    <div id="tab-1" class="tab-container rating-tab">
                                        <p class="rating-overview">
                                            Patients’ likelihood of recommending Dr. <?php echo $Doctors->Doctor->first_name; ?> <?php echo $Doctors->Doctor->last_name; ?> to family and friends is <span class="rate-out-of"><span id="rateshow1"><?php echo number_format((float) $Doctors->Rate, 1, '.', '');
;
?></span> out of <span>5.0</span></span></p>
                                        <div class="rating-n-rate-wrap cf">
                                            <div class="rating rating-adjust" id="ratedstar1">


                                                <?php
                                                $grey = 5 - $Doctors->Rate;
                                                for ($i = 0; $i < $Doctors->Rate; $i++) {
                                                    ?>
                                                    <span class="fullstar"></span>
                                                <?php
                                                }
                                                for ($i1 = 0; $i1 < $grey; $i1++) {
                                                    ?>
                                                    <span class="greystar"></span>
                                            <?php }
                                            ?>
                                            </div>


                                            <?php if ($rated == 0) { if($userid==0){?>
                                                <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="rate-doctor" title="Rate this Doctor" >Rate this Doctor</a>
                                            <?php }else{ ?>
                                                <a href="#rate-box" class="rate-doctor inline" title="Rate this Doctor" id="rate1">Rate this Doctor</a>
                                            <?php } } else { ?>
                                                <a class="rate-doctor clicked" title="Rate this Doctor" id="rate1">Rate this Doctor</a>
<?php } ?>
                                        </div>
                                    </div><!-- .rating-tab-->

                                    <div id="tab-2" class="tab-container overview-tab">
                                        <p class="main-text">
                                            <?php echo $Doctors->Doctor->description; ?>
                                        </p>
                                        <h4 class="point-heading">Procedures Dr. <?php echo $Doctors->Doctor->first_name; ?> 
                                            <?php echo $Doctors->Doctor->last_name; ?> performs:
                                        </h4>
                                        <ul class="point-listing">
                                            <?php 
                                                if(count($Doctors->Procedures) > 0){
                                                    foreach ($Doctors->Procedures as $proce) {
                                                        if($proce->ci->name!=''){
                                                        ?>
                                                        <li><?php echo $proce->ci->name; ?></li>
                                                    <?php }} ?>
                                            <?php }else{ ?>
                                                        <li>No Procedure Found</li> 
                                            <?php } ?>
                                        </ul>
                                    </div><!-- .overview-tab-->

                                    <div id="tab-3" class="tab-container other-chars-tab">
                                        <ul class="other-chars-listing">
                                            <?php
                                                $i = 0;
                                                if(count($Doctors->Characteristics) > 0){
                                                foreach ($Doctors->Characteristics as $chracter) {
                                                    if ($i > 4) {if($userid!=0){ ?>
                                                        <li style="cursor: pointer;" id="character_<?php echo $chracter->CharacteristicInsurance->id; ?>" >
                                                            <div onclick="characteristiclike(<?php echo $chracter->CharacteristicInsurance->id; ?>,<?php echo $chracter->totallike; ?>, '<?php echo $chracter->CharacteristicInsurance->name; ?>');">
                                                                <span class="relevant-point" ><?php echo $chracter->totallike; ?></span><?php echo $chracter->CharacteristicInsurance->name; ?>
                                                            </div>
                                                        </li>
                                                    <?php }else{ ?>
                                                        <li style="cursor: pointer;" >
                                                            <div id="home-login" data-target="#main-sign-in_login" data-toggle="modal">
                                                                <span class="relevant-point" ><?php echo $chracter->totallike; ?></span><?php echo $chracter->CharacteristicInsurance->name; ?>
                                                            </div>
                                                        </li>
                                                        
                                                    <?php
                                                    }}$i++;
                                                    } 
                                                }else{ ?>
                                                        <li>No Characteristics Found</li>   
                                                <?php } ?>
                                        </ul>
                                    </div><!-- .other-chars-tab-->

                                    <div id="tab-4" class="tab-container overview-tab">

                                        <?php
                                            if(count($Doctors->Promotions) > 0){
                                                foreach ($Doctors->Promotions as $promo) {
                                                    if ($promo->Promotion->display_name == '') {
                                                        $promoname = $promo->Promotion->description;
                                                    } else {
                                                        $promoname = $promo->Promotion->display_name;
                                                    } ?>
                                                <ul class="point-listing">
                                                    <li>
                                                        <?php echo $promoname . ' (' . $promo->Promotion->operand . $promo->Promotion->value . ' )'; ?>
                                                    </li>
                                                </ul>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                No Promotions Found
                                            <?php } ?>
                                    </div><!-- .promotions-tab-->


                                    <div id="tab-5" class="tab-container offices-tab">
                                        <h4 class="offices-tab-heading">Office Locations</h4>
                                        <div class="location-info-n-map cf">

                                            <?php
                                            $clinic_locations = array();
                                            $i = 0;

                                            foreach ($Doctors->Offices as $office) {
                                                $b = array();
                                                $b['LatitudeLongitude'] = $office->ClinicLocation->latitude . ',' . $office->ClinicLocation->longitude;
                                                $b['DisplayText'] = $office->ClinicLocation->address . ', ' . $office->ClinicLocation->city;
                                                $clinic_locations[$i++] = $b;
                                                ?>
                                                <div class="location-info">

    <?php echo $this->html->image(CDN.'img/images_buzzy/gmap_pointer2x.png', array('title' => '', 'alt' => '', 'class' => 'map-pointer')); ?>

                                                    <div class="location-info-text">
                                                        <h5 class="location-name"><?php echo $office->ClinicLocation->address; ?></h5>
                                                        <p class="location-address"><?php echo $office->ClinicLocation->city; ?> <?php echo $office->ClinicLocation->state; ?> <?php echo $office->ClinicLocation->pincode; ?></p>
                                                        <p class="location-phone"><span class="phone-legend">P:</span> <?php echo $office->ClinicLocation->phone; ?></p>
                                                        <p class="location-fax"><span class="fax-legend">F:</span> <?php echo $office->ClinicLocation->fax; ?></p>
                                                        <p class="location-direction"><a title="Directions" target="_blank" class="address-direction" href="/buzzydoc/map/<?= base64_encode($office->ClinicLocation->id) ?>">Directions</a></p>
                                                    </div>
                                                </div>
<?php } ?>
                                            <div id="map-canvas" style="width:600px;height: 200px;">
                                                <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6530.7450047434795!2d-83.96845932524774!3d35.072424371025065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0xd8cf6354876da126!2sMurphy+Medical+Center!5e0!3m2!1sen!2sin!4v1418045841865" width="100%" height="100%" frameborder="0" style="border:0"></iframe>-->
                                            </div>
                                        </div>
                                    </div><!-- .offices-tab-->
                                </div><!-- .all-tabs-container-->
                            </div><!-- .all-tabs-wrap-->
                        </div><!-- .details-all-info-->
                    </div><!-- .details-all-info-wrap-->
                </div><!-- .main-details-->
            </div><!-- .details-wrap-->
        </div><!-- .relevant-adjust-->
    </div>



    <div class="two-cols-wrap">
        <div class="row cf">
            <section class="left-module">
                <header class="left-module-heading">
                    <div class="modified-border-top"></div>
                    <h4 class="left-module-title-heading">Activity Feed</h4>
                    <div class="modified-border-bottom clear"></div>
                </header>
                <ul class="left-module-listing">
<?php
if (!empty($Doctors->Activities)) {
    foreach ($Doctors->Activities as $promo) {
        ?>

                            <li>
                                <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                                <div class="data-container">
                                    <div class="listing-point"><?php
                                        if ($promo->Transaction->amount < 0) {
                                            echo $promo->Transaction->amount;
                                        } else {
                                            echo '+' . $promo->Transaction->amount;
                                        }
                                        ?></div>
                                    <div class="doc-small-img">

                            <?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png', array('title' => '', 'alt' => 'user picture', 'class' => '')); ?>
                                    </div>
                                    <span class="doc-place-name">
                                        <?php $givenName = $Doctors->Doctor->first_name." ".$Doctors->Doctor->last_name; ?>
                                        <?php echo ((strlen($givenName) > 30) ? substr($givenName,0,30).'...' : $givenName) ?>
                                    </span>
                                    <p class="listing-description">Gave points for 
                                   <?php $gavepoint = str_replace(array('-', '+', '*'), "", $promo->Transaction->authorization)." to ".$promo->Transaction->first_name; ?>
                                    <?php echo ((strlen($gavepoint) > 30) ? substr($gavepoint,0,30).'...' : $gavepoint) ?>
                                    </p>
                                </div>
                            </li>
    <?php }
} else {
    ?>
                        <li>
                            No activity feeds available
                        </li>
                        <?php } ?>
                </ul>
            </section><!-- .left-module-->

<!--
            <aside class="right-module">
                <div class="right-module-inner">
                    <header class="right-module-main-heading">Practice’s Giving the Most Points in Your Area</header>
                    <ul class="right-module-listing">

                                    <?php
                                    if (!empty($topclinic)) {
                                        foreach ($topclinic as $mostpop) {
                                            ?>
                                <li>
                                    <div class="clinic-info-wrap cf">
                                        <div class="view-profile-box">
                                            <a href="<?php echo '/practice/' . str_replace(' ','',$mostpop->Clinic->api_user); ?>" class="profile-btn" title="View Profile">View Profile</a>

                                                <?php echo $this->html->image(CDN.'img/images_buzzy/dollar_thumb.png', array('title' => 'dollar', 'alt' => 'dollar thumb', 'class' => 'currency-thumb')); ?>
                                            <span class="clinic-points">+<?php echo $mostpop->Pointshare; ?></span>
                                        </div>
                                        <div class="clinic-img">
                                                                          <?php 
if(isset($mostpop->Clinic->buzzydoc_logo_url) && $mostpop->Clinic->buzzydoc_logo_url!=''){ ?>
                                    <img src="<?php echo $mostpop->Clinic->buzzydoc_logo_url;?>" alt="<?=$mostpop->Clinic->api_user;?>" title="<?=$mostpop->Clinic->api_user;?>" />
<?php
}else{
echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$mostpop->Clinic->api_user,'alt'=>$mostpop->Clinic->api_user,'class'=>'thumb-picture'));
}
?></div>
                                        <div class="clinic-info">
                                            <h4 class="clinic-name"><?php
                                                if ($mostpop->Clinic->display_name == '') {
                                                    echo $mostpop->Clinic->api_user;
                                                } else {
                                                    echo $mostpop->Clinic->display_name;
                                                }
                                                ?></h4>
                                                <?php if (isset($mostpop->PrimeOffices)) { ?>
                                                <h5 class="clinic-address"><?php echo $mostpop->PrimeOffices->ClinicLocation->address; ?> , <?php echo $mostpop->PrimeOffices->ClinicLocation->city; ?> , <?php echo $mostpop->PrimeOffices->ClinicLocation->state; ?></h5>
                                                <?php } ?>
                                            <div class="rating">

        <?php
        $grey = 5 - $mostpop->Rate;
        for ($i = 0; $i < $mostpop->Rate; $i++) {
            ?>
                                                    <span class="fullstar"></span>
        <?php
        }
        for ($i1 = 0; $i1 < $grey; $i1++) {
            ?>
                                                    <span class="greystar"></span>
                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
    <?php
    }
} else {
    ?>
                            <li>
                                <div class="clinic-info-wrap cf">
                                    <?php if($userid>0){ ?>
                                No Practice Found!
                                <?php }else{ ?>
                                <h4 class="signin-heading">
                                <a title="Sign in" href="/login">Sign in</a> to Find a Practice
                                </h4>
                                <?php } ?>
                                </div>
                            </li>
<?php }
?>

                    </ul>
                </div>
            </aside> .right-module-->
        </div>
    </div><!-- .two-cols-wrap-->
</section><!-- .relevant-details-->
<div style="display:none">
    <div id="rate-box" class="rate-box">
        <form action="" method="post" id="rating-submit" name="rating-submit" class="rating-submit">
            <h4 class="rating-modal-heading">Rate</h4>
            <div id='status_error' style="color: #FF0000; margin-bottom: 3px;">&nbsp;</div>
            <div class="slider-wrap cf">
                <div class="slider-hr-ruler-warp">
                    <div id="slider" class="slider-hr-ruler"></div>
                </div>
                <div class="slider-num-wrap">
                    <div class="slider-num-inner">
                        <input type="text" id="amount" name="amount" class="rating-display-num" readonly>
                        <span class="rating-sub-text">Rating</span>
                    </div>
                </div>
            </div>
            <div class="slider-submit-btn-wrap">
                <input type="button" value="Submit" id='change_redeem_btn' class="slider-submit-btn">
                &nbsp; <span id="redm-status-bar" style="position: absolute; z-index: 5; left: 40px; top: 5px;"></span> 
            </div>

        </form>
    </div>
</div>














<div class="modal fade" id="notification-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog notification-table" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">REFER A FRIEND</h4>
            </div>

            <form action="" method="post" id="rating-submit" name="rating-submit" class="rating-submit">
                <div class="modal-body">
                    <div class="row">
                        <table class="notification-data table">
                            <thead>
                                <tr>          <th colspan="2"> 
                                        <div class="  center-block notification-head-select">
                                      <div class="col-md-12 text-left notification-head-text">Earn points when the friends and family you refer convert into:</div></div></th>
                                </tr>
                            </thead>
                            <tbody id="leadsplan">
                                   <?php
     $settings=array();
    if(!empty($admin_settings)){
                                    if($admin_settings['AdminSetting']['setting_data']!=''){
                                      $settings=json_decode($admin_settings['AdminSetting']['setting_data']);
                                    }
                                }



    foreach($leads as $ld){
            $point1='';
                                    foreach($settings as $set =>$setval){

                                       if($set==$ld['LeadLevel']['id']){
                                         $point1=$setval;
                                       }
                                    }

        ?>
                                <tr>
                                    <td class="center col-md-6"><?php echo $ld['LeadLevel']['leadname']; ?></td>
                                    <td class="center col-md-6"><?php if($point1!=''){ echo $point1; }else{ echo $ld['LeadLevel']['leadpoints']; }?> points</td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table>

                        <div class="col-md-12 notification-form form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <input type="hidden" id="indty" name="indty" value="<?php echo $industry_id; ?>" >
                         
                                    <div class="col-md-12"><input type="text"  id="first_name" name="first_name" placeholder="First Name:" class="col-md-12" ></div>
                                    <div class="col-md-12"><input type="text" id="last_name" name="last_name" placeholder="Last Name:" class="col-md-12" ></div>
                                    <div class="col-md-12"><input type="text" id="email" name="email" placeholder="Email:" class="col-md-12" ></div>                         </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if(empty($refer_msg)){ ?>
                                        <textarea class="form-control" id="message" name="message" placeholder=""></textarea>
                                     <?php }else{ ?>
                                        <textarea class="form-control" name="message" id="massage"><?php echo $refer_msg->reffralmessage1; ?></textarea>
                                     <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 notification-chbx">
                                <div class="row" id="defaultmsg">
                                     <?php

                                                if($refer_msg->cnt>1){ ?>
                                    <?php

                        for($k=1;$k<=$refer_msg->cnt;$k++){
                            $fname='reffralmessage'.$k;
                            ?>
                          <?php if($k==1){
                           ?>
                                    <label class="col-md-2 checkbox-heading">Quick Recommendations :</label>
                                     <?php }else{ ?>
                                    <label class="col-md-2 checkbox-heading">&nbsp;</label>

                       <?php } ?>
                                    <div class="col-md-10">
                                        <div class="radio clearfix">
                                            <?php if($k==1){
                           ?>

                                            <div class="co-md-12">
                                                <label >
                                                    <input class="ace" type="radio" id="msg" name="msg" checked="checked" onclick="setmsg(<?=$k?>);"><span class="lbl"><?=$refer_msg->$fname?></span>


                                                </label>
                                            </div>
                                          <?php }else{ ?>
                                            <div class="co-md-12">
                                                <label >
                                                    <input class="ace" type="radio" id="msg" name="msg" onclick="setmsg(<?=$k?>);"><span class="lbl"><?=$refer_msg->$fname?></span>
                                                </label>
                                            </div>
                                          <?php } ?>

                                        </div>
                                    </div>
                        <?php }} ?>

                                </div>

                                 <?php if(!empty($refer_msg)){ ?>
                                <div class="col-md-10">
                                    <div class="radio clearfix">
                                        <div class="co-md-12">
                                            
                                            <div id="setnext" style="display:none">


                                                <a onclick="setdefault();" style="cursor: pointer;" title="Change Recommendation"></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div id='status_error_reco' style="color: #FF0000; margin-bottom: 3px;">&nbsp;</div>
                    <div class="modal-footer">
                        <span id="refer_load" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                        <input type="button" value="Submit" id='recommen_btn' class="result-view-profile-btn">&nbsp;
                        <span id="reco-status-bar" style="position: absolute; z-index: 5; left: 40px; top: 5px;"></span>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
















<div style="display:none">
    <div id="appointment-box" class="rate-box">
        <form action="" method="post" id="rating-submit" name="rating-submit" class="rating-submit">
            <h4 class="rating-modal-heading">Appointments</h4>
            <h2 style="color:#000;font-size: 11px;">Note: Your name and email will be shared with the office in which you are scheduling an appointment.</h2>
            <div id='status_error_appoint' style="color: #FF0000; margin-bottom: 3px;">&nbsp;</div>

            <textarea name="reason" id="reason" cols="30" rows="10" class="rating-description" placeholder="Appointment Reason [*]"></textarea>

            <div class="slider-submit-btn-wrap">
                <input type="button" value="Submit" id='appointment_btn' class="slider-submit-btn">
                &nbsp; <span id="appo-status-bar" style="position: absolute; z-index: 5; left: 40px; top: 5px;"></span>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="main-sign-in_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <div id="log-in-form" >
                    <form name="sign-form" id="sign-form" class="login-form" action="" method="post" >
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    <div class="header">
                            <h1>Please sign in to access the page.</h1>
                    </div>
                        <div class="footer">

                            <span id="sign-progress"></span>
                            <a href="/login" class="button" />Ok</a>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY&sensor=true" type="text/javascript"></script>

<script>
    var data='';
</script>
<?php if(count($clinic_locations)){ ?>
    <script type="text/javascript">
        data = '<?php echo json_encode($clinic_locations); ?>';
    </script>
<?php } ?>
    
<script type="text/javascript">
    
            function inviteFriend() {
            $('#notification-table').modal();
            //        $("#inviteFri").css("display", "block");
        }
	   function setmsg(id){
		  var indty=$('#indty').val();
	   $.ajax({
                   type: "POST",
                   url: "/buzzydoc/getmsg/",
                   data: "&id="+id+"&indty="+indty,
                   success: function(msg) {
					   $("#massage").focus();
					   $('#massage').val(msg);
                                          
                                           $("#defaultmsg").css("display", "none");
                                           $("#setnext").css("display", "block");
                   }
               }); 
            } 
//            function emailpreview(){
//                  var clinic_id =<?php echo $Clinics->Clinic->id; ?>;
//		  var msges=$('#massage').val();
//	   $.ajax({
//                   type: "POST",
//                   url: "/buzzydoc/referpreview/",
//                   data: "&message="+msges+"&clinic_id="+clinic_id,
//                   success: function(msg) {
//                       $('#myModal').addClass("modal fade popupBox in");
//                       $('#myModal').attr('aria-hidden', false);
//                       $('#myModal').css('display', 'block');
//                       $('#Mymodel1').addClass('modal-backdrop fade in');
//		       $('#VIEWPREv').html(msg);
//                   }
//               }); 
//            } 
            function setdefault(){
            $("#defaultmsg").css("display", "block");
            $("#setnext").css("display", "none");
            }

    if(data!=''){
        
        var map;
            var geocoder;
            var marker;
            var people = new Array();
            var latlng;
            var infowindow;

            $(document).ready(function() {

                    people = JSON.parse(data);
                    if (people.length > 0) {
                        var latitude_longitude = people[0]['LatitudeLongitude'].split(",");
                        ViewCustInGoogleMap(latitude_longitude[0], latitude_longitude[1]);

                    } else {
                        $("#map-canvas").text("No Location Found");
                    }

            });
    
        function ViewCustInGoogleMap(first_lat, first_lng) {
            
            var mapOptions = {
                center: new google.maps.LatLng(first_lat, first_lng),
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            if (people.length > 0) {
                for (var i = 0; i < people.length; i++) {
                    setMarker(people[i]);
                }
            }

        }

        function setMarker(people) {
            geocoder = new google.maps.Geocoder();
            infowindow = new google.maps.InfoWindow();
            map.markers = map.markers || [];
            map.latlngbounds = map.latlngbounds || new google.maps.LatLngBounds();//Create a new latlngbounds object on the map

            var latlngStr = people["LatitudeLongitude"].split(",");
            var lat = parseFloat(latlngStr[0]);
            var lng = parseFloat(latlngStr[1]);
            
            var latlng = new google.maps.LatLng(lat, lng);
            map.latlngbounds.extend(latlng);//Add current markers latlng to latlng bounds
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                draggable: false,
                url: '/',
                title: people["DisplayText"]
            });
            map.markers.push(marker);
            map.setCenter(map.latlngbounds.getCenter());//Get the center of all lat lngs
           map.markers.length > 1?map.fitBounds(map.latlngbounds):map.setZoom(12);//Set Zoom level of map to fit lat lngs
            
            var url = 'https://maps.google.com/?q=' + lat + ',' + lng;

            google.maps.event.addListener(marker, 'click', function(event) {
                window.open(url);

            });
        }
    
        $("#horizontalTab").on("tabs-activate", //Whenver tab is activated, trigger reszie and reset center and zoom level
                function() {
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(map.latlngbounds.getCenter());
                    map.markers.length > 1?map.fitBounds(map.latlngbounds):map.setZoom(12);//Set Zoom level of map to fit lat lngs

                }
        )
    }else{
        $("#map-canvas").text("No Location Found");
    }
    
    /*************** Map End Here ************************/

    $("#appointment_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');

        var reason = $("#reason").val();
        var clinic_id = 0;
        var user_id =<?php echo $userid; ?>;
        var doctor_id =<?php echo $Doctors->Doctor->id; ?>;
        if ($.trim(reason) == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_appoint").html("Please enter appointment reason");
        } else {
            $("#appo-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/appointment/",
                data: "&clinic_id=" + clinic_id + "&reason=" + reason + "&user_id=" + user_id + "&doctor_id=" + doctor_id,
                success: function(msg) {
                    $("#appo-status-bar").html('');
                    obj = JSON.parse(msg);

                    if (obj.success == 1 && obj.data == 'Appointment Schedule Successfully') {
                        $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error_appoint").html("Appointment Scheduled Successfully");


                    }

                    if (obj.success == 0 && obj.data == 'Bad Request') {

                        $("#status_error_appoint").html("Try again leter.");
                    }

                }
            });
        }

    });


    $("#recommen_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var email = $("#email").val();

        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();
        var message = $("#massage").val();
        var clinic_id = <?php echo $Doctors->Doctor->clinic_id; ?>;
        var user_id =<?php echo $userid; ?>;
        var user_email = "<?php echo $sessionbuzzy->User->email; ?>";
        var doctor_id =<?php echo $Doctors->Doctor->id; ?>;
        if ($.trim(first_name) == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter first name");
        } else if ($.trim(last_name) == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter last name");
        } else if ($.trim(email) == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter email");
        } else if (!regex.test(email)) {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter valid email");
        } else if ($.trim(message) == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter recommendation");
        } else {
            $("#reco-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/recommend/",
                data: "&clinic_id=" + clinic_id + "&first_name=" + first_name + "&last_name=" + last_name + "&email=" + email + "&message=" + message + "&user_id=" + user_id + "&doctor_id=" + doctor_id + "&user_email=" + user_email,
                success: function(msg) {
                    $("#reco-status-bar").html('');
                    obj = JSON.parse(msg);

                    if (obj.success == 1 && obj.data == 'Recommended Successfully') {
                        $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error_reco").html("Recommended Successfully");


                    }
                    if (obj.success == 1 && obj.data == 'Already Recommended') {
                        $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error_reco").html("Email Id already Registered.");

                    }
                    if (obj.success == 0 && obj.data == 'Bad Request') {

                        $("#status_error_reco").html("Try again leter.");
                    }

                }
            });
        }

    });
    $("#change_redeem_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var rateval = $("#amount").val();
        var doctor_id =<?php echo $Doctors->Doctor->id; ?>;
        var user_id =<?php echo $userid; ?>;
        if (rateval == 0) {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error").html("Please select a rating");
        } else {
            $("#redm-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/rate/",
                data: "&doctor_id=" + doctor_id + "&rate=" + rateval + "&user_id=" + user_id,
                success: function(msg) {
                    $("#redm-status-bar").html('');
                    obj = JSON.parse(msg);

                    if (obj.success == 1 && obj.data == 'Rated Successfully') {
                        $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error").html("Rated Successfully");
                        $('#rate').addClass('clicked');
                        $('#ratedstar').html(obj.stars);
                        $('#ratedstar1').html(obj.stars);
                        $('#rateshow').html(obj.rateshow);
                        $('#rateshow1').html(obj.rateshow);
                        $('#rate').removeClass('inline');
                        $("#rate").removeAttr("href");
                        $('#rate').removeClass('cboxElement');
                        $('#rate1').addClass('clicked');
                        $('#rate1').removeClass('inline');
                        $("#rate1").removeAttr("href");
                        $('#rate1').removeClass('cboxElement');

                    }
                    if (obj.success == 1 && obj.data == 'Allready rate.') {
                        $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error").html("Allready rate.");

                    }
                    if (obj.success == 0 && obj.data == 'Bad Request') {

                        $("#status_error").html("Try again leter.");
                    }

                }
            });
        }

    });
    function characteristiclike(cid, tlike, name) {


        var characteristic = cid;
        var totallike = tlike;
        var doctor_id =<?php echo $Doctors->Doctor->id; ?>;
        var user_id =<?php echo $userid; ?>;

        $.ajax({
            type: "POST",
            url: "/buzzydoc/characteristiclike/",
            data: "&characteristic_id=" + characteristic + "&doctor_id=" + doctor_id + "&user_id=" + user_id,
            success: function(msg) {
                obj = JSON.parse(msg);
                if (obj.success == 1 && obj.data == 'UnLike Successfully') {
                    var likes = totallike - 1;

                    $('#character_' + characteristic).html('<div onclick="characteristiclike(' + characteristic + ',' + likes + ',\'' + name + '\');"><span class="relevant-point">' + likes + '</span>' + name + '</div>');

                }
                if (obj.success == 1 && obj.data == 'Like Successfully') {
                    var unlikes = totallike + 1;

                    $('#character_' + characteristic).html('<div onclick="characteristiclike(' + characteristic + ',' + unlikes + ',\'' + name + '\');"><span class="relevant-point">' + unlikes + '</span>' + name + '</div>');

                }
                if (obj.success == 0 && obj.data == 'Bad Request') {
                    alert('Try again leter.');
                }

            }
        });



    }



    function savedoctor() {
        $("#savedocbtn-status").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');

        var doctor_id =<?php echo $Doctors->Doctor->id; ?>;
        var user_id =<?php echo $userid; ?>;

        $.ajax({
            type: "POST",
            url: "/buzzydoc/savedoctor/",
            data: "&doctor_id=" + doctor_id + "&user_id=" + user_id,
            success: function(msg) {
                $("#savedocbtn-status").html("");
                obj = JSON.parse(msg);

                if (obj.success == 1 && obj.data == 'Save Successfully') {


                    $('#savedoc').html('Save');
                    $('#savedoc').addClass('clicked');


                }
                if (obj.success == 0 && obj.data == 'Bad Request') {
                    alert('Try again leter.');
                }

            }
        });



    }
    $(document).ready(function() {
        // Tab text change function for first tab
        $('.alternate-tab').on('click', function() {
            $('.alternate-txt').show();
            $('.tab-rating-wrap').hide();
            $('.alternate-tab').removeClass('alternate-tab-class');
            $('#tab-1').show();
        }); // Tab text change function for first tab End

        $('#horizontalTab').responsiveTabs({
            rotate: false,
            startCollapsed: 'accordion',
            collapsible: 'accordion',
            setHash: false,
            disabled: [],
            activate: function(e, tab) {
                $('.info').html('Tab <strong>' + tab.id + '</strong> activated!');
            },
            activateState: function(e, state) {
                //console.log(state);
                $('.info').html('Switched from <strong>' + state.oldState + '</strong> state to <strong>' + state.newState + '</strong> state!');
            }
        });

        $('#horizontalTab').responsiveTabs('deactivate', 0);

        $('#start-rotation').on('click', function() {
            $('#horizontalTab').responsiveTabs('startRotation', 1000);
        });
        $('#stop-rotation').on('click', function() {
            $('#horizontalTab').responsiveTabs('stopRotation');
        });
        $('#start-rotation').on('click', function() {
            $('#horizontalTab').responsiveTabs('active');
        });
        $('.select-tab').on('click', function() {
            $('#horizontalTab').responsiveTabs('activate', $(this).val());
        });



        //Examples of how to assign the Colorbox event to elements
        $(".inline").colorbox({inline: true, width: "330px"});
        $(".callbacks").colorbox({
            onOpen: function() {
                alert('onOpen: colorbox is about to open');
            },
            onLoad: function() {
                alert('onLoad: colorbox has started to load the targeted content');
            },
            onComplete: function() {
                alert('onComplete: colorbox has displayed the loaded content');
            },
            onCleanup: function() {
                alert('onCleanup: colorbox has begun the close process');
            },
            onClosed: function() {
                alert('onClosed: colorbox has completely closed');
            }
        });

        $('.non-retina').colorbox({rel: 'group5', transition: 'none'})
        $('.retina').colorbox({rel: 'group5', transition: 'none', retinaImage: true, retinaUrl: true});

        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function() {
            $('#click').css({"background-color": "#f00", "color": "#fff", "cursor": "inherit"}).text("Open this window again and this message will still be here.");
            return false;
        });



    });

    $(function() {
        $("#slider").slider({
            value: 0,
            min: 0,
            max: 5,
            step: .5,
            slide: function(event, ui) {
                $("#amount").val(ui.value);
            }
        });
        $("#amount").val($("#slider").slider("value"));
    });

    $('.main-btn-2').click(function() {
        $("#recommen_btn").attr('disabled', false);
        $("#first_name").val('');
        $("#last_name").val('');
        $("#email").val('');
        $("#message").val('');
        $("#status_error_reco").html('&nbsp;');
    });

    $('.main-btn-4').click(function() {
        $("#reason").val('');
        $("#status_error_appoint").html('&nbsp;');
        $("#appointment_btn").attr('disabled', false);

    });

    $('.main-btn-3').click(function() {
        $("#amount").val('');
        $("#status_error_appoint").html('&nbsp;');
        $("#change_redeem_btn").attr('disabled', false);
    });
    $("#rate").click(function(){
       $("#slider").slider('value',0);
   });
   
   $("#rate1").click(function(){
       $("#slider").slider('value',0);
       $("#amount").val('');
       $("#status_error_appoint").html('&nbsp;');
   });
   

</script>
