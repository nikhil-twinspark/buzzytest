<?php

$sessionbuzzy = $this->Session->read('userdetail');
if(isset($sessionbuzzy->User->id)){
    $userid=$sessionbuzzy->User->id;
}else{
    $userid=0;
}
$zip='';
if(isset($sessionbuzzy->Profilefield)){
foreach($sessionbuzzy->Profilefield as $field){
    if($field->ProfileField->profile_field=='postal_code'){
        $zip=$field->ProfileFieldUser->value;
    }
}   
}
$uliked=0;
if(!empty($sessionbuzzy->Likeclinic)){
foreach($sessionbuzzy->Likeclinic as $saved){
    if($saved->Clinic->id==$Clinics->Clinic->id){
        $uliked=1;
    }
}
}
$fbliked=0;
if(!empty($sessionbuzzy->Fblikes)){
foreach($sessionbuzzy->Fblikes as $flike){
    if($flike->FacebookLike->clinic_id==$Clinics->Clinic->id && $flike->FacebookLike->like_status==1){
        $fbliked=1;
    }
}
}
$rated=0;
foreach($sessionbuzzy->givenRate as $rate){
    if($rate->rate_reviews->clinic_id==$Clinics->Clinic->id && $rate->rate_reviews->doctor_id==0){
        $rated=1;
    }
}
?>
<section class="relevant-details">
    <div class="row">
        <div class="relevant-adjust">
            <div class="details-wrap cf">
                <header class="relevant-header">PRACTICE PROFILE</header>
                <div class="main-details">
                    <div class="details-all-info-wrap">
                        <div class="details-all-info cf">
                            <div class="detials-info">
                                <div class="main-thumb">
                                    <input type="hidden" id="indty" name="indty" value="<?php echo $Clinics->Clinic->industry_type; ?>" >
<?php 
$ch = curl_init(S3Path.$Clinics->Clinic->buzzydoc_logo_url);
                                    curl_setopt($ch, CURLOPT_NOBODY, true);
                                    curl_exec($ch);
                                    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    if(isset($Clinics->Clinic->buzzydoc_logo_url) && $Clinics->Clinic->buzzydoc_logo_url!=''){
                                    
 ?>
                                    <a href="javascript:void(0)"><img src="<?php echo S3Path.$Clinics->Clinic->buzzydoc_logo_url;?>" alt="<?=$Clinics->Clinic->api_user;?>" title="<?=$Clinics->Clinic->api_user;?>" /></a>
<?php
}else{
    echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$Clinics->Clinic->api_user,'alt'=>$Clinics->Clinic->api_user,'class'=>'thumb-picture'));
}
$cliniczip='';
if(isset($Clinics->PrimeOffices->ClinicLocation->pincode)){
   $cliniczip =$Clinics->PrimeOffices->ClinicLocation->pincode;
}
?>
                                </div>
                                <div class="detials-info-all-text">
                                    <h4 class="thumb-name"><?php
                                    if($Clinics->Clinic->display_name==''){ echo $clinicname=$Clinics->Clinic->api_user;}else{ echo $clinicname=$Clinics->Clinic->display_name; } ?></h4>
                                    <p class="likes-toggle"><a class="thumb-likes">Likes <span class="tumb-likes-num" >(<?php echo $Clinics->Likes; ?>)</span></a></p>
                                    <p class="address-detials1"><?php if(isset($Clinics->PrimeOffices)){ echo $Clinics->PrimeOffices->ClinicLocation->address.' , '.$Clinics->PrimeOffices->ClinicLocation->city.' , '.$Clinics->PrimeOffices->ClinicLocation->state; } ?></p>
                                    <p class="address-details-phone"><span class="legend">P:</span> <?php if(isset($Clinics->PrimeOffices)){ echo $Clinics->PrimeOffices->ClinicLocation->phone; }else{ echo "NA"; } ?></p>
                                    <p class="address-details-fax"><span class="legend">F:</span> <?php if(isset($Clinics->PrimeOffices)){ echo $Clinics->PrimeOffices->ClinicLocation->fax; }else{ echo "NA"; } ?></p>
                                    <p>
                                        <?php if(isset($Clinics->PrimeOffices)){ ?>
                                        <a title="Directions" target="_blank" class="address-direction" href="/buzzydoc/map/<?=base64_encode($Clinics->PrimeOffices->ClinicLocation->id)?>">Directions</a>
                                        <?php }else{ ?>
                                        <a title="Directions" class="address-direction" >Directions</a>
                                           <?php
                                        }?>
                                    </p>
                                </div>
                                <div class="details-sub-info">
                                    <a class="thumb-likes">Likes <span class="tumb-likes-num" id="likes">(<?php echo $Clinics->Likes; ?>)</span></a>
                                    <ul class="btn-listing" style="margin-bottom: 10px;">
                                        <li><a href="#tab-2">Characteristics</a></li>
                                        <li><a href="#tab-2">Procedures Offered</a></li>
                                        <li><a href="#tab-2">Insurance</a></li>
                                    </ul>
                                    <?php 
                                    if(isset($sessionbuzzy->User->id) && $Clinics->Clinic->is_buzzydoc==1 && $Clinics->Clinic->minimum_deposit>0 && $paymentcheck==1 && $Clinics->Clinic->id!=73){
?>
                                    <div id="fb-root"></div>
                                    <script src="//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $Clinics->Clinic->fb_app_id ?>"></script>
                                    <script >
                                        FB.init({
                                            status: true,
                                            cookie: true,
                                            xfbml: true
                                        });
                                        FB.Event.subscribe('edge.create', function(href, widget) {
                                            $("#fb_progress_div").show();
                                            $.ajax({
                                                type: "POST",
                                                url: "<?php echo Staff_Name ?>buzzydoc/facebookpointallocation",
                                                data: "fb_status=like&clinic_id=" +<?php echo $Clinics->Clinic->id; ?>,
                                                success: function(msg) {
                                                    $("#fb_progress_div").hide();
                                                    obj = JSON.parse(msg);
                                                    if (obj.success == 1) {
                                                        var cur_point = $("#total_point_div").text();
                                                        cur_point = parseInt(cur_point) + parseInt(obj.data);
                                                        $("#total_point_div").text(cur_point);
                                                        $('#fb_like_div').remove();
                                                        $("#login_facebook_div").remove();
                                                        alert("We've credited your account " + obj.data + " points for liking our Facebook page. Thanks!");
                                                        location.reload();
                                                    } else {
                                                        alert("You've already got points for Facebook like for this page.");
                                                        location.reload();
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                   <?php  
                        if($fbliked==0){
                            ?>
                                    <div id="fb_like_div" class="fb_new">
                                        <div class="fb-like-box" id="fb_like" data-width="50" data-href="<?php echo $Clinics->Clinic->facebook_url; ?>" data-colorscheme="light" 
                                             data-show-faces="false" data-header="true" data-stream="false" data-show-border="false"></div>
                                        <p style="display:none;" id="fb_progress_div" >
                                      <?php echo $this->html->image(CDN.'img/loading.gif',array('alt'=>'fb like'));?>Please wait...
                                        </p>
                                        <p class="profilelink hidden-xs" style="color: #626374;">
                                            Get instant points for <br> clicking "like"
                                        </p>
                                    </div>
<?php  }} ?>
                                </div>
                            </div><!-- .detials-info-->
                            <div class="main-buttons">
                                <?php if($uliked==0){if($userid==0){ ?>
                                <a class="main-btn-1" title="Like" href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal">Like</a>
                                <?php }else{ ?>
                                <a href="javascript:void(0)" class="main-btn-1" title="Like" onclick="likeclinic(<?php echo $Clinics->Likes; ?>)" id="savedoc">Like</a>
                                <?php }}else{  ?>
                                <a class="main-btn-1 clicked" title="Like" id="savedoc">Like</a>

                                <?php } if($Clinics->Clinic->is_buzzydoc==1){if($userid==0){ ?>
                                <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="main-btn-2" title="Recommend">Recommend</a>
                                <?php }else{ ?>
                                <a href="javascript:void(0)" class="main-btn-2" title="Recommend" onclick="inviteFriend();">Recommend</a>
                                <?php }}else{ ?>
                                <a href="#recommend-box" class="main-btn-2 clicked" title="Recommend">Recommend</a>
                                <?php
                                } if($rated==0){if($userid==0){ ?>
                                <a class="main-btn-3" title="Rate" href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal">Rate</a>
                                <?php }else{?>
                                <a href="#rate-box" class="main-btn-3 inline" id="rate" title="Rate">Rate</a>
                                <?php }}else{ ?>
                                <a class="main-btn-3 clicked" id="rate" title="Rate">Rate</a>
                                <?php }if($userid==0){ ?>
                                <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="main-btn-4" title="Schedule an Appointment">Schedule an Appointment</a>
                                <?php }else{?>
                                <a href="#appointment-box" class="main-btn-4 inline" title="Schedule an Appointment">Schedule an Appointment</a>
                                <?php } if(!empty($Clinics->ProductService) && $Clinics->Clinic->is_buzzydoc==1 && $staffaccess['AccessStaff']['product_service']==1){?>
                                <a href="javascript:void(0)" class="main-btn-5 redeem-points-button-main-container" title="Products And Services">Products And Services</a>
                                <?php } ?>
                                <ul class="main-social-icons">
                               <?php 
                               if(isset($Clinics->Clinic->twitter_url) && $Clinics->Clinic->twitter_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->twitter_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png',array('alt'=>'twitter'));?></a></li>
                              <?php } ?>
                              <?php if(isset($Clinics->Clinic->facebook_url) && $Clinics->Clinic->facebook_url!=''){ ?>
                                    <li> <a href="<?php echo $Clinics->Clinic->facebook_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png',array('alt'=>'facebook'));?></a></li>
                              <?php } ?>
                              <?php if(isset($Clinics->Clinic->google_url) && $Clinics->Clinic->google_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->google_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('alt'=>'googleplus'));?></a></li>
                             <?php } ?>
                             <?php if(isset($Clinics->Clinic->instagram_url) && $Clinics->Clinic->instagram_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->instagram_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/instagram.png',array('alt'=>'instagram'));?></a></li>
                             <?php } ?>
                             <?php if(isset($Clinics->Clinic->pintrest_url) && $Clinics->Clinic->pintrest_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->pintrest_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/pinterest.png',array('alt'=>'pinterest'));?></a></li>
                             <?php } ?>
                             <?php if(isset($Clinics->Clinic->yelp_url) && $Clinics->Clinic->yelp_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->yelp_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/yelp.png',array('alt'=>'yelp'));?></a></li>
                             <?php } ?>
                             <?php if(isset($Clinics->Clinic->youtube_url) && $Clinics->Clinic->youtube_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->youtube_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/you-tube.png',array('alt'=>'youtube'));?></a></li>
                             <?php } ?>
                             <?php if(isset($Clinics->Clinic->healthgrade_url) && $Clinics->Clinic->healthgrade_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->healthgrade_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades.png',array('alt'=>'healthgrades'));?></a></li>
                             <?php } ?>
                                    <?php if(isset($Clinics->Clinic->website_url) && $Clinics->Clinic->website_url!=''){ ?>
                                    <li><a href="<?php echo $Clinics->Clinic->website_url;?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/global_buzzydoc_link_2.png',array('alt'=>$Clinics->Clinic->api_user));?></a></li>
                             <?php } ?>
                                </ul>
                                <span id="savedocbtn-status"></span>
                            </div><!-- .main-buttons-->

                            <div class="sub-details">
                                <div class="sub-details-inner">
                                    <header class="check-ins-heading">
                                        Check-ins
                                        <div class="check-ins-tooltip">Check-in at your next appointment</div>
                                    </header>
                                    <ul class="check-ins-listing">
                                        <li>Total<span class="relevant-point"><?php echo $Clinics->TotalCheckin; ?></span></li>
                                        <li>Unique<span class="relevant-point"><?php echo $Clinics->UniqueCheckin; ?></span></li>
                                        <li>Monthly<span class="relevant-point"><?php echo $Clinics->MonthlyCheckin; ?></span></li>
                                        <li>You<span class="relevant-point-2nd"><?php echo $Clinics->selfCheckin; ?></span></li>
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
                                
                                 $grey=5-$Clinics->Rate;
                                 for($i=0;$i<$Clinics->Rate;$i++){ ?>
                                                        <span class="fullstar"></span>
                            <?php }
                            for($i1=0;$i1<$grey;$i1++){ ?>
                                                        <span class="greystar"></span>
                            <?php }
                            ?>
                                                    </div>
                                                    <span class="tab-sub-txt" id="rateshow">(<?php echo number_format((float)$Clinics->Rate, 1, '.', ''); ; ?>)</span>
                                                </div>
                                                <span class="alternate-txt">Patient Satisfaction</span>
                                            </a>
                                        </li>
                                        <li><a href="#tab-2">Overview</a></li>
                                        <li><a href="#tab-3">Promotions</a></li>
                                        <li><a href="#tab-4">Reviews <span class="tab-sub-txt">(<?php echo $Clinics->TotalReview; ?>)</span></a></li>
                                        <li><a href="#tab-5">Doctors</a></li>
                                        <li><a href="#tab-6">Offices</a></li>
                                    </ul>

                                    <div id="tab-1" class="tab-container rating-tab">
                                        <p class="rating-overview">
                                            Patients’ likelihood of recommending <?php echo $clinicname; ?> to family and friends is 
                                            <span class="rate-out-of">
                                                <span id="rateshow1">
                                                        <?php echo number_format((float)$Clinics->Rate, 1, '.', ''); ?>
                                                </span> out of 
                                                <span>5.0</span>
                                            </span>
                                        </p>
                                        <div class="rating-n-rate-wrap cf">
                                            <div class="rating rating-adjust" id="ratedstar1">
                                                    <?php 
                                                         $grey=5-$Clinics->Rate;
                                                         for($i=0;$i<$Clinics->Rate;$i++){ ?>
                                                <span class="fullstar"></span>
                                                    <?php }
                                                    for($i1=0;$i1<$grey;$i1++){ ?>
                                                <span class="greystar"></span>
                                                    <?php }  ?>
                                            </div>
                                                <?php if($rated==0){if($userid==0){  ?>
                                            <a href="javascript:void(0)" id="home-login" data-target="#main-sign-in_login" data-toggle="modal" class="rate-doctor" title="Rate this Practice">Rate this Practice</a>
                                                <?php }else{ ?>
                                            <a href="#rate-box" class="rate-doctor inline" title="Rate this Practice" id="rate1">Rate this Practice</a>
                                                <?php } }else{ ?>
                                            <a class="rate-doctor clicked" title="Rate this Practice" id="rate1">Rate this Practice</a>
                                                <?php } ?>
                                        </div>
                                    </div><!-- .rating-tab-->

                                    <div id="tab-2" class="tab-container overview-tab">
                                        <p class="main-text">
                                            <?php echo $Clinics->Clinic->about;?>
                                        </p>
                                        <h4 class="point-heading">
                                            Procedures <?php echo $clinicname; ?> Performs:
                                        </h4>
                                        <ul class="point-listing">
                                            <?php 
                                                if(count($Clinics->Procedure) > 0){
                                                    foreach($Clinics->Procedure as $proce){
                                                        if($proce->CharacteristicInsurance->name!=''){
                                                        ?>
                                            <li><?php echo $proce->CharacteristicInsurance->name; ?></li>
                                                    <?php }} ?>
                                            <?php }else{ ?>
                                            <li>No  Procedure Found   </li>
                                            <?php } ?>
                                        </ul>
                                        <h4 class="point-heading">Insurance:</h4>
                                        <ul class="point-listing">
                                            <?php 
                                                if(count($Clinics->insurence_provider) > 0){
                                                    foreach($Clinics->insurence_provider as $proce){
                                                        if($proce->CharacteristicInsurance->name!=''){
                                                        ?>
                                            <li><?php echo $proce->CharacteristicInsurance->name; ?></li>
                                                    <?php }} ?>
                                            <?php }else{ ?>
                                            <li>No Insurance Found</li>    
                                            <?php } ?>
                                        </ul>
                                        <h4 class="point-heading">Characteristic:</h4>
                                        <ul class="point-listing">
                                             <?php 
                                                if(count($Clinics->Characteristic) > 0){
                                                    foreach($Clinics->Characteristic as $proce){ 
                                                        if($proce->CharacteristicInsurance->name!=''){
                                                        ?>
                                            <li><?php echo $proce->CharacteristicInsurance->name; ?></li>
                                                    <?php }} ?>
                                            <?php }else{ ?>
                                            <li>No Characteristic Found</li>  
                                            <?php } ?>
                                        </ul>
                                    </div><!-- .overview-tab-->
                                    <div id="tab-3" class="tab-container overview-tab">
                                        <ul class="point-listing">
                                            <?php 
                                                if(count($Clinics->Promotions) > 0){
                                                    foreach($Clinics->Promotions as $promo){
                                                        if($promo->Promotion->display_name==''){
                                                            $promoname=$promo->Promotion->description;
                                                        }else{
                                                        $promoname=$promo->Promotion->display_name;
                                                        }  ?>
                                            <ul class="point-listing">
                                                <li>
                                                                  <?php echo $promoname.' ('.$promo->Promotion->operand.$promo->Promotion->value.' )'; ?>
                                                </li>
                                            </ul>
                                                    <?php } ?>
                                              <?php }else{ ?>
                                            <li>No Promotion Found</li>
                                              <?php } ?>
                                        </ul>
                                    </div><!-- .promotions-tab-->
                                    <div id="tab-4" class="tab-container reviews-tab">
                                        <h4 class="point-heading">
                                             <?php if(count($Clinics->Reviews) > 0){ ?>
                                            These are reviews from doctors
                                             <?php } ?>
                                        </h4>
                                        <ul>
                                            <?php 
                                            $k=1;
                                            if(count($Clinics->Reviews) > 0){
                                            foreach($Clinics->Reviews as $review){ ?>
                                            <li>
                                                <div class="review-wrap">
                                                    <h4 class="reviewer-heading">Review <?php echo $k; ?> (<?php echo $review->rr->doctorname; ?>)</h4>
                                                    <div class="review-txt-n-img-wrap cf">
                                                        <div class="review-txt-wrap">
                                                            <p><?php echo $review->rr->review; ?>
                                                            </p>
                                                        </div>
                                                        <div class="reviewer-picture-wrap">
<?php if($review->rr->gender=='Male'){ echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png',array('title'=>$review->rr->doctorname,'alt'=>'reviewer picture','class'=>'reviewer-picture'));}else{
    echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png',array('title'=>$review->rr->doctorname,'alt'=>'reviewer picture','class'=>'reviewer-picture'));
}
?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php $k++;} ?>
                                            <?php }else{ ?>
                                            <li>No Review Found</li>
                                            <?php } ?>
                                        </ul>
                                    </div><!-- .review-tab-->

                                    <div id="tab-5" class="tab-container doctors-tab">
                                        <ul class="all-doctors-list">
                                            <?php 
                                                    if(count($Clinics->Doctors) > 0){
                                                    foreach($Clinics->Doctors as $doc){ 
                                                    $date1 = date_create($doc->Doctor->dob);
                                                    $date2 = date_create(date('Y-m-d'));
                                                    $diff12 = date_diff($date2, $date1);
                                                    $years = $diff12->y;
                                                ?>
                                            <li>
                                                <div class="doctor-profile-wrap cf">
                                                    <div class="doctor-img-wrap">
                                                            <?php 
                                                            $docprofilePath = WWW_ROOT .'img/docprofile/'.$Clinics->Clinic->api_user.'/'. $doc->Doctor->id;
                                                            $docprofilePath1 = AWS_server.AWS_BUCKET.'/img/docprofile/'.$Clinics->Clinic->api_user.'/'. $doc->Doctor->id;	
                                                            if (file_exists($docprofilePath)) { ?>
                                                        <img src="<?=$docprofilePath1?>" class='' width="77px" height="87px">
                                                            <?php }else{
                                                                    if($doc->Doctor->gender=='Male'){
                                                                        echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png',array('title'=>'doctor','alt'=>'doctor picture','class'=>'thumb-picture' ,'height'=>'87px','width'=>'77px'));
                                                                     }else{ 
                                                                        echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png',array('title'=>'doctor','alt'=>'doctor picture','class'=>'thumb-picture' ,'height'=>'87px','width'=>'77px'));
                                                                    }
                                                                } ?>
                                                    </div>
                                                    <div class="doctor-details-wrap">
                                                        <h4 class="doctor-name">Dr. <?php echo $doc->Doctor->first_name; ?> <?php echo $doc->Doctor->last_name; ?>, <?php echo $doc->Doctor->degree; ?></h4>
                                                        <ul class="doctor-short-info">
                                                            <li>Specializes in <?php echo $doc->Doctor->specialty; ?></li>
                                                            <li><?php echo ucfirst(strtolower($doc->Doctor->gender)); ?></li>
                                                           <?php if($doc->Doctor->dob!='0000-00-00'){ ?><li>Age <?=$years?></li><?php } ?>
                                                        </ul>

                                                        <a href="<?php echo '/doctor/' .$doc->Doctor->first_name.' '.$doc->Doctor->last_name.'/'.$doc->Doctor->specialty; ?>" class="view-full-profile" title="view full profile">View Full Profile</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                          <?php }else{ ?>
                                            <li>No Doctor Found</li>
                                          <?php } ?>
                                        </ul>
                                    </div><!-- .doctors-tab-->

                                    <div id="tab-6" class="tab-container offices-tab">
                                        <h4 class="offices-tab-heading">
                                            Office Locations
                                        </h4>
                                        <div class="location-info-n-map cf">

                                            <?php  
                                                $clinic_locations= array();
                                                $i=0;
                                                foreach($Clinics->Offices as $office){ 
                                                    $b=array();
                                                    $b['LatitudeLongitude']=$office->ClinicLocation->latitude.','.$office->ClinicLocation->longitude;
                                                    $b['DisplayText']=$office->ClinicLocation->address.', '.$office->ClinicLocation->city;        
                                                    $clinic_locations[$i++]=$b;
                                                ?>
                                            <div class="location-info">

<?php echo $this->html->image(CDN.'img/images_buzzy/gmap_pointer2x.png',array('title'=>'','alt'=>'','class'=>'map-pointer'));?>
                                                <div class="location-info-text">
                                                    <h5 class="location-name"><?php echo $office->ClinicLocation->address;?></h5>
                                                    <p class="location-address"><?php echo $office->ClinicLocation->city;?> , <?php echo $office->ClinicLocation->state;?> ,<?php echo $office->ClinicLocation->pincode;?></p>
                                                    <p class="location-phone"><span class="phone-legend">P:</span><?php echo $office->ClinicLocation->phone;?></p>
                                                    <p class="location-fax"><span class="fax-legend">F:</span><?php echo $office->ClinicLocation->fax;?></p>
                                                    <p class="location-direction"><a title="Directions" target="_blank" class="address-direction" href="/buzzydoc/map/<?=base64_encode($office->ClinicLocation->id)?>">Directions</a></p>

                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div id="map-canvas" style="width: 500px; height: 200px;">
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
                    <?php if(!empty($Clinics->Activities)){
                        foreach($Clinics->Activities as $promo){ ?>
                    <li>
                        <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                        <div class="data-container">
                            <div class="listing-point"><?php if($promo->Transaction->amount<0){ echo $promo->Transaction->amount;}else{ echo  '+'.$promo->Transaction->amount;} ?></div>
                            <div class="doc-small-img">
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                            </div>
                            <span class="doc-place-name" title="<?php echo $clinicname; ?>"><?php echo ((strlen($clinicname) > 22) ? substr($clinicname,0,22)."..." : $clinicname); ?></span>
                               <?php $gavepoint = str_replace(array('-', '+', '*'), "", $promo->Transaction->authorization)." to ".$promo->Transaction->first_name; ?>
                            <p class="listing-description" title="Gave points for <?php echo $gavepoint; ?>">Gave points for 
                  <?php echo ((strlen($gavepoint) > 30) ? substr($gavepoint,0,30).'...' : $gavepoint) ?>
                            </p>
                        </div>
                    </li>
                    <?php }}else{ ?>
                    <li>
                        No activity feeds available
                    </li>
                    <?php } ?>
                </ul>
            </section><!-- .left-module-->
        </div>
    </div><!-- .two-cols-wrap-->
</section><!-- .relevant-details -->
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
            <h4 class="rating-modal-heading">Appointment</h4>
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


<div class="modal fade" id="redeemModalMain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="redeemPointsLabel">Products And Services</h4>
            </div>
            <div class="modal-body">
                <div class="redeemContainer clearfix">
                <?php 
                foreach($Clinics->ProductService as $prodser){
                    if(isset($Perclinicbuzzpnt) && $prodser->ProductService->from_us==1){
                        $need=round($prodser->ProductService->points-$Perclinicbuzzpnt[$Clinics->Clinic->id]);   
                    }else{
                        $need=$prodser->ProductService->points-$sessionbuzzy->User->points;
                    }
                    ?>
                    <div class="redeemBox">
                        <h3><?php echo $prodser->ProductService->title; ?></h3>
                        <div class="productPoints"><?php echo $prodser->ProductService->points; ?> Points</div>
                    </div>
                <?php } ?>
                    <div class="redeemClear"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="redeemload" style="display: none;"><?php echo $this->html->image(CDN.'img/loading52.gif'); ?></div>
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
<script type="text/javascript">
                                                    var data = '';
<?php if(count($clinic_locations)){ ?>
    data = '<?php echo json_encode($clinic_locations); ?>';
<?php } ?>
    function inviteFriend() {
        $('#notification-table').modal();
        //        $("#inviteFri").css("display", "block");
    }
    function redemed(user_id, product_id, points) {
        var r = confirm("Are you sure you want to redeem this product?");
        if (r == true)
        {
            $("#redeemload").css("display", "block");
            $.ajax({
                type: "POST",
                data: "user_id=" + user_id + '&product_id=' + product_id + '&points=' + points,
                dataType: "json",
                url: "<?= Staff_Name ?>buzzydoc/redeemlocproduct/",
                success: function(result) {
                    if (result == 1) {
                        alert('You have redeemed product successfully.');
                        location.reload();
                        $("#redeemload").css("display", "none");
                    } else if (result == 2) {
                        alert('You do not have sufficient balance.');
                    } else {
                        alert('Unable to redeem. Please contact buzzydoc admin.');
                    }
                }
            });

        }
        else
        {
            return false;
        }
    }
    $(document).on("click", ".redeem-points-button-main-container", function() {
        $('#redeemModalMain').modal().fadeIn(100);
    });
    function setmsg(id) {
        var indty = $('#indty').val();
        $.ajax({
            type: "POST",
            url: "/buzzydoc/getmsg/",
            data: "&id=" + id + "&indty=" + indty,
            success: function(msg) {
                $("#massage").focus();
                $('#massage').val(msg);

                $("#defaultmsg").css("display", "none");
                $("#setnext").css("display", "block");
            }
        });
    }
    function setdefault() {
        $("#defaultmsg").css("display", "block");
        $("#setnext").css("display", "none");
    }
    if (data != '') {
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
                zoom: 7,
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
            map.markers.length > 1 ? map.fitBounds(map.latlngbounds) : map.setZoom(12);//Set Zoom level of map to fit lat lngs
            var url = 'https://maps.google.com/?q=' + lat + ',' + lng;
            google.maps.event.addListener(marker, 'click', function(event) {
                window.open(url);
            });
        }
        $("#horizontalTab").on("tabs-activate", //Whenver tab is activated, trigger reszie and reset center and zoom level
                function() {
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(map.latlngbounds.getCenter());
                    map.markers.length > 1 ? map.fitBounds(map.latlngbounds) : map.setZoom(12);//Set Zoom level of map to fit lat lngs
                }
        )
    } else {
        $("#map-canvas").text("No Location Found");
    }
    /*************** Map End Here ************************/

    $("#appointment_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var reason = $("#reason").val();
        var clinic_id =<?php echo $Clinics->Clinic->id; ?>;
        var user_id =<?php echo $userid; ?>;
        var doctor_id = 0;
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
        var clinic_id =<?php echo $Clinics->Clinic->id; ?>;
        var user_id =<?php echo $userid; ?>;
        var user_email = "<?php echo $sessionbuzzy->User->email; ?>";
        var doctor_id = 0;
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
        var clinic_id =<?=$Clinics->Clinic->id;?>;
        var clinic_name = "<?=$Clinics->Clinic->api_user;?>";
        var user_id =<?php echo $userid; ?>;
        if (rateval == 0) {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error").html("Please select a rating");
        } else {
            $("#redm-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/rate/",
                data: "&clinic_id=" + clinic_id + "&rate=" + rateval + "&user_id=" + user_id + "&clinic_name=" + clinic_name,
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
                        $('#rate').removeClass('cboxElement');

                        $("#rate").removeAttr("href");
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

    function likeclinic(totallike) {
        $("#savedocbtn-status").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');

        var clinic_id =<?php echo $Clinics->Clinic->id; ?>;
        var user_id =<?php echo $userid; ?>;

        $.ajax({
            type: "POST",
            url: "/buzzydoc/likeclinic/",
            data: "&clinic_id=" + clinic_id + "&user_id=" + user_id,
            success: function(msg) {
                $("#savedocbtn-status").html('');
                obj = JSON.parse(msg);

                if (obj.success == 1 && obj.data == 'Like Successfully') {

                    var likes = totallike + 1;
                    $('#likes').html('(' + likes + ')');
                    $('#savedoc').html('Like');
                    $('#savedoc').addClass('clicked');
                    $("#savedoc").removeAttr("onclick");


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

    $("#rate").click(function() {
        $("#slider").slider('value', 0);
    });

    $("#rate1").click(function() {
        $("#slider").slider('value', 0);
        $("#amount").val('');
        $("#status_error_appoint").html('&nbsp;');
    });
</script>
<?php
if(@$errorMsg != ""){ ?>
<script language="javascript">
    alert(<?php echo @$errorMsg; ?>);
    < style >
<?php } ?>