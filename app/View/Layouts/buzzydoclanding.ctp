<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">

        <title>BuzzyDoc</title>

        <?php
        
            echo $this->Html->css(CDN.'css/stylebuzzy.css');
            echo $this->Html->css(CDN.'css/jquery.remodal.css');
            echo $this->Html->css(CDN.'css/owl.carousel.css');
            echo $this->Html->script(CDN.'js/jquery.min.js');
        ?>

        <?php
            echo $this->Html->script(CDN.'js/custom.js');
            echo $this->Html->script(CDN.'js/owl.carousel.js');
            echo $this->Html->script(CDN.'js/buzzydoclanding.js');
        ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            window.heap = window.heap || [], heap.load = function(e, t) {
                window.heap.appid = e, window.heap.config = t = t || {};
                var n = t.forceSSL || "https:" === document.location.protocol, a = document.createElement("script");
                a.type = "text/javascript", a.async = !0, a.src = (n ? "https:" : "http:") + "//cdn.heapanalytics.com/js/heap-" + e + ".js";
                var o = document.getElementsByTagName("script")[0];
                o.parentNode.insertBefore(a, o);
                for (var r = function(e) {
                    return function() {
                        heap.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                    }
                }, p = ["clearEventProperties", "identify", "setEventProperties", "track", "unsetEventProperty"], c = 0; c < p.length; c++)
                    heap[p[c]] = r(p[c])
            };
            heap.load("3420711851");</script>
    </head>
    <body>
        <section class="buzzy-doc">
            <div class="row cf">
                <header class="main-header cf">
                    <a href="javascript:void(0)" class="top-button" title="Claim Your Practice" data-target="#main-clame-practice" data-toggle="modal"  id="claimhear">Claim Your Practice</a>
                    <p class="buzzylearn">Are you a Doctor? <a href="http://mypractice.buzzydoc.com/" title="Learn More..." target="_blank" style="color:inherit;">Learn More...</a> </p>
                    <!--<a href="#log-in-form-home" class="top-button" title="Claim Your Practice">Claim Your Practice</a>-->
                    <a href="javascript:void(0)" class="logo" title="BuzzyDoc" style="cursor: default">
                        <?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_logo_with_tagline.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo')); ?>
                    </a>
                </header>
                <div class="slider-container">
                    <div id="owl-demo" class="owl-carousel">
                        <div class="slide-1 slide-adjust-1">DENTIST</div>
                        <div class="slide-2 slide-adjust-2">ORTHODONTIST</div>
                        <div class="slide-3 slide-adjust-3">OPTOMETRIST</div>
                        <div class="slide-4 slide-adjust-4">PEDIATRICIAN</div>
                        <div class="slide-5 slide-adjust-5">OB/GYN</div>
                        <div class="slide-6 slide-adjust-6">DERMATOLOGIST</div>
                        <div class="slide-7 slide-adjust-7">CHIROPRACTOR</div>
                        <div class="slide-8 slide-adjust-8">PLASTIC<br>SURGEON</div>
                        <div class="slide-9 slide-adjust-9">FAMILY<br>MEDICINE</div>
                    </div><!-- #owl-demo -->
                </div>



                <section class="connect-n-search">
                    <section class="signup-section">
                        <?php echo $this->Session->flash(); ?>
                        <div class="signup-container cf">

                            <a href="/buzzydoc/facebooklogin/" class="fb-signup-btn"></a>
                            <?php if($mobile_device==1){ ?>
                            <a href="/buzzydoc/mlogin" class="signup-btn" title="Sign in" id="home-login" >Returning User? Sign In</a>

                            <?php }else{ ?>
                            <a href="javascript:void(0)" class="signup-btn" title="Sign in" id="home-login" data-target="#main-sign-in" data-toggle="modal">Returning User? Sign In</a>

                            <?php } ?>
                            <span class="or">or</span>
                        </div>
                        <div class="signup-container cf">


                            <?php if($mobile_device==1){ ?>
                            <a href="/buzzydoc/selectpractice" class="signup-btn1" >Registering As A New User? </br> Sign Up Today To Start Earning!</a>

                            <?php }else{ ?>
                            <a href="javascript:void(0)" class="signup-btn1" data-target="#main-sign-up" data-toggle="modal">Registering As A New User? </br> Sign Up Today To Start Earning!</a>

                            <?php } ?>

                        </div>
                    </section>
                    <section class="search-doctors">
                        <div class="search-container">
                            <!--<form action="" method="post" name="search-doctor">-->
                            <?php echo $this->Form->create("searchdoc",array('class'=>'','action'=>''));?>
                            <div class="doctors-dropdown">
                                <div class="dropdown-wrap doctor-dropdown">

                                    <select class="listing" name="specialty" id="specialty" onchange="getdoctor();">
                                        <option value="">Search Doctor Type</option>

                                        <option value="Dentistry">Dentistry</option>
                                        <option value="Orthodontics">Orthodontics</option>
                                        <option value="Optometry">Optometry</option>
                                        <option value="Pediatrics">Pediatrics</option>
                                        <option value="OB/GYN">OB/GYN</option>
                                        <option value="Dermatology">Dermatology</option>
                                        <option value="Chiropractics">Chiropractics</option>
                                        <option value="Plastic Surgery">Plastic Surgery</option>
                                        <option value="Family Medicine">Family Medicine</option>

                                    </select>
                                    <span class="dropdown-value doctor-dropdown-value"></span>
                                </div>
                            </div>
                            <input type="text" placeholder="Your Zipcode" id="pincode" class="doctor-search-box" onkeypress="getdoctorviapincode(event);">
                            </form>
                        </div>
                        <div class="dr-list-container">
                            <ul class="doctors-list" id="doctor_list">

                                <?php if(count($Doctors)>0){
                                foreach ($Doctors as $toprated) {
                                    $totalrate = 0;
                                    foreach ($toprated as $trKey => $trVal) {

                                        if ($trKey == 0 && isset($trVal->totalrate)) {
                                            $totalrate = round($trVal->totalrate);
                                        }
                                    }
                                    ?>
                                <li>

                                    <a href="<?php echo '/doctor/' .$toprated->dc->first_name.' '.$toprated->dc->last_name.'/'.$toprated->dc->specialty; ?>" style="cursor: pointer">
                                        <div class="doctor-detials cf">
                                            <div class="doctor-image">
    <?php if ($toprated->dc->gender == 'Male') { ?>
                                                        <?php echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png', array('title' => $toprated->dc->first_name, 'alt' => 'doctor icon')); ?>
                                                    <?php } else { ?>
                                                        <?php echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png', array('title' => $toprated->dc->first_name, 'alt' => 'doctor icon')); ?>
                                                    <?php } ?>
                                            </div>
                                            <div class="doctor-name">
                                                <h3>Dr. <?php echo $toprated->dc->first_name . ' ' . $toprated->dc->last_name; ?>, <?php echo $toprated->dc->specialty; ?></h3>
                                                <div class="rating">
   <?php
                                
                                 $grey=5-$totalrate;
                                 for($i=0;$i<$totalrate;$i++){ ?>
                                                    <span class="fullstar"></span>
                            <?php }
                            for($i1=0;$i1<$grey;$i1++){ ?>
                                                    <span class="greystar"></span>
                            <?php }
                            ?>
                                                </div>
                                            </div>
                                            <div class="doctor-address">
                                                <h4><?php echo $toprated->dc->address; ?></h4>
                                                <p>
    <?php echo $toprated->dc->city . ', ' . $toprated->dc->state; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>



<?php }}else{ ?>
                                <li>
                                    <a href="#" style="cursor: default;">
                                        <div class="doctor-detials cf">

                                            No Doctor Found!

                                        </div>
                                    </a>
                                </li>
<?php } ?>

                            </ul>
                        </div>
                    </section>
                </section>
            </div>
        </section><!-- First section End -->

        <section class="overview-n-points">
            <div class="row cf">
                <div class="overview">
                    <div class="img-wrap">

<?php echo $this->html->image(CDN.'img/images_buzzy/infographic_1.jpg', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                    </div>
                </div>
                <div class="overview-points">
                    <ul class="main-points cf">
                        <li>
                            <div><?php echo $this->html->image(CDN.'img/images_buzzy/front_badge1.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?><span>1. </span><h4>Schedule an appointment</h4></div>
                        </li>
                        <li>
                            <div><?php echo $this->html->image(CDN.'img/images_buzzy/front_badge2.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?><span>2. </span><h4>Be a great patient</h4></div>
                        </li>
                        <li>
                            <div><?php echo $this->html->image(CDN.'img/images_buzzy/front_badge3.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?><span>3. </span><h4>Earn points</h4></div>
                        </li>
                        <li>
                            <div><?php echo $this->html->image(CDN.'img/images_buzzy/front_badge4.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?><span>4. </span><h4>Redeem your points</h4></div>
                        </li>
                    </ul>
                </div>
            </div>
        </section><!-- Second section End -->
        <section class="doctor-in-area">
            <h1>Look for doctors in your area with the most rewards</h1>
            <div class="row cf">
                <div class="doctor-info-wrap">
                    <div class="doctor-info cf">
                        <div class="doctor-img">
                            <div class="img-wrap">
<?php echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                            </div>
                        </div>
                        <div class="doctor-content">
                            <h4>Dr. Will B. Healthy, DMD</h4>
                            <div class="address-n-point cf">
                                <div class="place-name">
                                    <h5>Miles of Smiles</h5>
                                    <h6>111 Medical Lane</h6>
                                </div>
                                <div class="points-of-doctor">
                                    <p class="number-points">400</p>
                                    <p class="point-text">Points</p>
                                </div>
                            </div>
                        </div>
                        <div class="rating clear">
                            <span class="fullstar"></span>
                            <span class="fullstar"></span>
                            <span class="fullstar"></span>
                            <span class="fullstar"></span>
                            <span class="fullstar"></span>
                        </div>
                    </div>
                </div>
                <div class="map-info-warp">
                    <div class="map-info">

<?php echo $this->html->image(CDN.'img/images_buzzy/map-fake.png', array('title' => '', 'alt' => '', 'class' => 'map-fake')); ?>
                        <div class="map-pointers">

                            <a href="javascript:void(0);" class="pointer1"><?php echo $this->html->image(CDN.'img/images_buzzy/map_tp_pointer1.png'); ?></a>
                            <a href="javascript:void(0);" class="pointer2"><?php echo $this->html->image(CDN.'img/images_buzzy/map_tp_pointer2.png'); ?></a>
                            <a href="javascript:void(0);" class="pointer3"><?php echo $this->html->image(CDN.'img/images_buzzy/map_tp_pointer3.png'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- Third section End -->


        <section class="for-great-patients">
            <div class="row cf">
                <div class="earn-points">
                    <div class="highlights">
                        <h1>
                            Earn points for being a great patient
                        </h1>
                        <ul>
                            <li>
                                <p>Keeping your appointments</p>
                                <p>Being on time for appointments</p>
                                <p>Referring family &amp; friends</p>
                            </li><li>
                                <p>Social media engagement</p>
                                <p>Appointment check-in</p>
                                <p>Reviews</p>
                            </li>
                        </ul>
                        <div class="badges">
                            <h5>Badges:</h5>
                            <div class="badgeImg">
                                <ul>
                                    <li>

<?php echo $this->html->image(CDN.'img/images_buzzy/badge1_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge2_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge3_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge4_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge5_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge6_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge7_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge8_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge9_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li><li>
                                        <?php echo $this->html->image(CDN.'img/images_buzzy/badge10_big.png', array('title' => 'buzzydoc overview', 'alt' => 'buzzydoc overview')); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-activity-feeds">
                    <section class="activityWrap">
                        <div class="activity">
                            <header class="activity-feed-header">
                                <div class="modified-border-top"></div>
                                <h4>My Activity Feed</h4>
                                <div class="modified-border-bottom clear"></div>
                            </header>
                            <div class="activity-feeds">
                                <ul>
                                    <li>
                                        <div class="points">+100</div>
                                        <div class="user-small-img">

<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png'); ?>
                                        </div>
                                        <span class="userName">Joe</span>
                                        <p>Earned points for appointment check-in</p>
                                    </li>
                                    <li>
                                        <div class="points">+100</div>
                                        <div class="user-small-img">
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png'); ?>
                                        </div>
                                        <span class="userName">Joe</span>
                                        <p>Earned points for reviewing your doctor</p>
                                    </li>
                                    <li>
                                        <div class="points">

<?php echo $this->html->image(CDN.'img/images_buzzy/badge5_big.png'); ?>
                                            <p class="point-num">250</p>
                                            <p class="point-word">Points</p>
                                        </div>
                                        <div class="user-small-img">
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png'); ?>

                                        </div>
                                        <span class="userName">Joe</span>
                                        <p>Earned the Good Patient Badge - Great job!</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="redeem">
                            <a href="javascript:void(0)"  title="amazon" style="cursor: default">


<?php echo $this->html->image(CDN.'img/buzzydoc-user/images/gift_card.png',array('style'=>'max-width:265px')); ?>
                                <h5 class="heading">Earn and redeem points for Amazon.com Gift Cards<span class="asterisk-disclaimer">*</span>. Amazon.com Gift Cards never expire and can be redeemed towards millions of items at www.amazon.com.</h5>
                                <br>
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </section><!-- Fourth section End -->

        <section class="connect-us">
            <div class="row">
                <div class="connect">
                    <div class="connect-links cf">
                        <a href="/buzzydoc/facebooklogin/" title="connect with facebook" class="fbConnect">Sign up</a>
                        <?php if($mobile_device==1){ ?>
                        <a href="/buzzydoc/mlogin" class="signUp" title="Sign in" id="home-login1" >Returning User? Sign In</a>
                        <?php }else{ ?>
                        <a href="javascript:void(0)" class="signUp" title="Sign in" id="home-login1" data-target="#main-sign-in" data-toggle="modal">Returning User? Sign In</a>
                        <?php } ?>
                        <span class="mid">or</span>
                    </div>
                    <div class="connect-links cf">
                         <?php if($mobile_device==1){ ?>
                        <a href="/buzzydoc/selectpractice" class="signUp1" >Registering As A New User?
                            <br>
                            Sign Up Today To Start Earning!</a>

                         <?php }else{ ?>
                        <a href="javascript:void(0)" title="Sign up" class="signUp1"  data-target="#main-sign-up" data-toggle="modal" onclick="gocursor();" >Registering As A New User?
                            <br>
                            Sign Up Today To Start Earning!</a>

                         <?php } ?>

                    </div>
                </div>
            </div>
        </section><!-- Fifth section End -->

        <section class="analysis-n-points">
            <div class="row cf">
                <div class="doctor-advise-points">
                    <div class="doctors-points">
                        <div class="headings-container">
                            <h4>Are you a doctor?</h4>
                            <!--<a href="#" title="Claim your practice here">Claim your practice here.</a>-->
                            <a href="javascript:void(0)"  id="claimhear" title="Claim your practice here" data-target="#main-clame-practice" data-toggle="modal">Claim your practice here</a>

                            <h6>Give patients points for:</h6>
                        </div>
                        <ul>
                            <li>Starting with your practice</li>
                            <li>Keeping appointments</li>
                            <li>Referring family &amp; friends</li>
                            <li>Growing your social media</li>
                            <li>Reviewing your practice</li>
                        </ul>
                    </div>
                </div>
                <div class="analysis-imaging">
                    <div class="img-wrap">

<?php echo $this->html->image(CDN.'img/images_buzzy/imac.png', array('title' => 'Buzzydoc analysis', 'alt' => 'Buzzydoc doctor analysis')); ?>
                        <?php echo $this->html->image(CDN.'img/images_buzzy/imac_cropped.png', array('title' => 'Buzzydoc analysis', 'alt' => 'Buzzydoc doctor analysis', 'class' => 'cropped-img')); ?>

                    </div>
                </div>
            </div>
        </section><!-- Sixth section End -->

        <footer class="main-footer-section">
            <div class="row">
                <div class="main-footer">
                    <a href="#" class="footer-logo" title="BuzzyDoc">

<?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_logo_small.png', array('title' => 'Buzzydoc', 'alt' => 'BuzzyDoc logo')); ?>
                    </a>
                    <ul>
                        <li><a href="/thebuzz" title="Newsfeed">Newsfeed</a></li>
                        <li><a href="http://blog.buzzydoc.com/" title="Blog" target="_blank">Blog</a></li>
                        <li><a href="http://helpme.buzzydoc.com" title="Help" target="_blank">Help</a></li>
                    </ul>
                </div>
            </div>
        </footer><!-- Main Footer section End -->

        <!------------------------------login sahoo form end------------------------->
        <div class="modal fade" id="main-sign-in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="log-in-form" >
                            <?php echo $this->Form->create("sign-form",array('class'=>'login-form','action'=>''));?>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="header">
                                <h1>Sign In</h1>
                            </div>

                            <div class="content">
                                <input name="username" id="username" type="text" class="input username" placeholder="Email Id or Username" onkeypress="clearMsg('error-msg')"/>
                                <input name="password" id="password" type="password" class="input password" placeholder="Password" onkeypress="clearMsg('error-msg')" maxlength="15"/>
                                <p id="error-msg" class="err-msg"><?php echo $this->Session->flash('expire'); ?></p>
                            </div>

                            <div class="footer">
                                <div class="content">
                                    <p style="text-align: left; margin-bottom: 10px; "><a href="javascript:void(0)" title="Forgot Password" id="home-login2" data-target="#main-sign-in1" data-toggle="modal" style="color: #666;" >Forgot Password ?</a></p>
                                    <span id="sign-progress"></span>
                                    <p>
                                        <input type="button" name="submitBtn" id="submitBtn" value="Login" class="button" /></p>
                                </div>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="main-sign-in1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="log-in-form" >

                                <?php echo $this->Form->create("forgot-form",array('class'=>'login-form','action'=>''));?>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="header">
                                <h1>Forgot Password</h1>
                            </div>


                            <div class="content">
                                <input name="femail" id="femail" type="text" class="input username" placeholder="Email Id" onkeypress="clearMsg('error-msg-forgot')" onblur="checkuserexist();" onmouseout="checkuserexist();"/>
                                <div id="cardnumber">&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">
                                </div>

                                <p id="error-msg-forgot" class="err-msg"></p>
                            </div>

                            <div class="footer">
                                <div>&nbsp;
                                </div>
                                <div>&nbsp;
                                </div>
                                <span id="forgot-progress"></span>

                                <input type="button" name="forgotBtn" id="forgotBtn" value="Submit" class="button" onclick="checkuserexist();"/>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------------- signup model---------------------------->
<?php $sessionfbuser = $this->Session->read('fbuserdetail'); ?>
        <div class="modal fade" id="main-sign-up" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="sign-up-form">
                            <form class="login-form">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="header">
                                    <h1>Sign Up</h1>
                                </div>
                                <div class="header">
                                    <p class="asterisk-info">[*] Asterisk fields are mandatory.</p>
                                </div>
                                <div class="content cf">
                                    <div class="content-col-new">
                                        <select name="search_type" id="search_type" onchange="searchClinic();">
                                            <option value="">Select Practice Type [*]</option>
                                            <?php foreach ($industryType as $indType) { ?>
                                            <option value="<?php echo $indType['IndustryType']['id'] ?>"><?php echo $indType['IndustryType']['name'] ?></option>
                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="content cf">
                                    <div class="content-col-new">
                                        <?php if(isset($sessionfbuser) && !empty($sessionfbuser)){ ?>
                                        <select name="search_practice" id="search_practice" onchange="selectClinic(), checkpatientexist();">
                                            <option value="">Select Practice Name [*]</option>
                                        </select>
                                        <?php }else{ ?>
                                        <select name="search_practice" id="search_practice" onchange="selectClinic();">
                                            <option value="">Select Practice Name [*]</option>
                                        </select>
                                        <?php } ?>
                                    </div>
                                    <input name="send_card_number" id="send_card_number" type="hidden" maxlength="30" value=""/>

                                    <p class="err-msg clear" id="card-error-msg"></p>
                                    <p class="err-msg clear" id="fb-signup-error-msg"></p>
                                </div>
                                <div class="footer">
                                    <?php if(isset($sessionfbuser) && !empty($sessionfbuser)){ ?>
                                    <input name="clinic_id" id="clinic_id" type="hidden" value=""/>
                                    <input name="custom_date" id="custom_date" type="hidden" value="<?php echo $sessionfbuser['custom_date']; ?>"/>
                                    <input name="email" id="email" type="hidden" value="<?php echo $sessionfbuser['email']; ?>"/>
                                    <input name="parents_email" id="parents_email" type="hidden" value=""/>
                                    <input name="first_name" id="first_name" type="hidden" value="<?php echo $sessionfbuser['first_name']; ?>"/>
                                    <input name="last_name" id="last_name" type="hidden" value="<?php echo $sessionfbuser['last_name']; ?>"/>
                                    <input name="facebook_id" id="facebook_id" type="hidden" value="<?php echo $sessionfbuser['facebook_id']; ?>"/>
                                    <input name="gender" id="gender" type="hidden" value="<?php echo $sessionfbuser['gender']; ?>"/>
                                    <input name="fb_password" id="fb_password" type="hidden" value="<?php echo $sessionfbuser['password']; ?>"/>
                                    <input name="is_facebook" id="is_facebook" type="hidden" value="1"/>
                                    <input type="hidden" name="actionType" value="record_new_account" id='actionType'>
                                    <div id="hid_submit">
                                        <input type="button" name="submit" id="signFbUpBtn" value="Sign Up" class="button" />&nbsp;<span id="fb-signup-progress"></span>
                                    </div>
                                    <div id="emailexistlink" style="display:none;">
                                        <span>This email id exists with us. Click on the Link button to link your account.</span>
                                        <input type="button" name="submit" id="signFbUpBtn" value="Link" class="button" />&nbsp;
                                        <span id="fb-signup-progress1"></span>
                                    </div>
                                    <?php }else{ ?>
                                    <input type="button" name="submit" id="proceedBtn" value="PROCEED" class="button" />
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="main-sign-up-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="sign-up-form">
                            <?php echo $this->Form->create("sign-up-form",array('class'=>'login-form','action'=>''));?>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="header">
                                <h1>Sign Up</h1>
                                <p class="asterisk-info">[*] Asterisk fields are mandatory.</p>
                            </div>
                            <div class="content cf">
                                <div class="content-col-form">
                                    <p class="radio-btn-wrap">
                                        Card Number : <input name="card_number" id="card_number" type="text" class="input username" placeholder="Card Number [*]" onkeypress="clearError()" maxlength="30" value="" onkeyup="this.value = this.value.replace(/\D/g, '')"/>
                                        <span class="helpicon" style="position:relative;background: none repeat scroll 0 0 #D1DFF9;border: 1px solid #B7B7B7;border-radius: 10px 10px 10px 10px;display: inline-block;font-size: 12px;margin-left: 5px;padding: 0;text-align: center;width: 20px;" >
                                            <a href="javascript:void(0)" class="showhim" id="clickme">?</a>
                                            <span id="Style" style="position: absolute; padding: 5px; right: -142px;top:12px; display: none;">
             <?php echo $this->html->image('reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
                                            </span> 
                                        </span></p>
                                    <input name="clinic_id" id="clinic_id" type="hidden" maxlength="30" value=""/>
                                    <input type="hidden" name="actionType" value="record_new_account" id='actionType'>
                                </div>
                                <div class="content-col-1">
                                    <input name="first_name" id="first_name" type="text" class="input username" placeholder="First Name [*]" onkeypress="clearError()" maxlength="30"/>
                                </div>

                                <div class="content-col-2">
                                    <input name="last_name" id="last_name" type="text" class="input username" placeholder="Last Name [*]" onkeypress="clearError()" maxlength="20"/>
                                </div>

                                <div class="content-col-1">
                                    <input name="signup-password" id="signup-password" type="password" class="input password" placeholder="Password [*]" onkeypress="clearError()" maxlength="15"/>
                                </div>

                                <div class="content-col-2">
                                    <input name="signup-confirm-password" id="signup-confirm-password" type="password" class="input password" placeholder="Confirm Password [*]" onkeypress="clearError()" maxlength="15"/>
                                </div>

                                <div class="content-col-1">
                                    <p class="radio-btn-wrap">
                                        Gender [*]
                                        <input type="radio" name="gender" value="Male" > <label for="male">Male</label>
                                        <input type="radio" name="gender" value="Female"> <label for="female">Female</label>
                                    </p>
                                </div>

                                <div class="content-col-2">
                                    <input name="custom_date" id="custom_date" type="text" class="input username" placeholder="Date of Birth [*]" readonly=""/>
                                    <div id="date_pick" style="height: 0px; position: relative; top: -10px; z-index:9999" ></div>
                                </div>

                                <span id="email_field">
                                    <div class="content-col-1">
                                        <input name="email" id="email" type="email" class="input email" placeholder="Email [*]" onblur="checkpatientexist();" maxlength="50"/>
                                    </div>

                                    <div class="content-col-2" id="pemail"></div>
                                </span>
                                <div id="forLink">
                                    <div class="content-col-1">
                                        <input name="street1" id="street1" type="text" class="input username" placeholder="Address Line 1 [*]" onkeypress="clearError()" maxlength="200"/>
                                    </div>

                                    <div class="content-col-2">
                                        <input name="street2" id="street2" type="text" class="input username" placeholder="Address Line 2" onkeypress="clearError()" maxlength="200"/>
                                    </div>

                                    <div class="content-col-1">
                                        <select name="state" id="state" onchange="clearError(), getCity(this.value)">
                                            <option value="">Select State [*]</option>
                            <?php foreach ($states as $st) { ?>
                                            <option value="<?php echo $st['State']['state'] ?>"><?php echo $st['State']['state'] ?></option>
                            <?php } ?>
                                        </select>
                                        <span id="state-progress"></span>
                                    </div>

                                    <div class="content-col-2">
                                        <select name="city" id="city" onchange="clearError()">
                                            <option value="">Select City [*]</option>
                                        </select>
                                    </div>
                                    <div class="content-col-1">
                                        <input name="postal_code" id="postal_code" type="text" class="input username" placeholder="Zip Code [*]" onkeypress="clearError()" maxlength="6">
                                    </div>
                                    <div class="content-col-2">
                                        <input name="phone" id="phone" type="tel" class="input username" placeholder="Phone [*]" onkeypress="clearError()" maxlength="10" onkeyup="this.value = this.value.replace(/\D/g, '')" />
                                    </div>
                                </div>
                                <p class="err-msg clear" id="signup-error-msg"></p>
                            </div>

                            <div class="footer" id="hid_submit">
                                <input type="button" name="submit" id="signUpBtn" value="Sign Up" class="button" />&nbsp;
                                <span id="signup-progress"></span>
                            </div>
                            <div class="footer" id="emailexistlink" style="display:none;">
                                <span>This email id exists with us. Click on the Link button to link your account.</span>
                                <input type="button" name="submit" id="signUpBtn" value="Link" class="button" />&nbsp;
                                <span id="signup-progress1"></span>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <!-----code for clame your parctice ------>


        <div class="modal fade" id="main-clame-practice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="clmwdth">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="log-in-form" >
                            <form accept-charset="UTF-8" action="https://dl191.infusionsoft.com/app/form/process/e8c09f0b03156b90a6791f10324f7c8a" class="login-form" id="claimform" name="claimform" method="POST" target="_blank" onsubmit="return claimpractice();">

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="header">
                                    <h1>Claim Your Practice</h1>
                                </div>
                                <input name="inf_form_xid" type="hidden" value="e8c09f0b03156b90a6791f10324f7c8a" />
                                <input name="inf_form_name" type="hidden" value="Prospect Web Form" />
                                <input name="infusionsoft_version" type="hidden" value="1.38.0.37" />
                                <div class="content cf">
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">Practice Name *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <input name="inf_field_Company" id="inf_field_Company" type="text" class="input username" placeholder="Practice Name [*]"  maxlength="30"/>
                                    </div>
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">Practice Type *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <select name="inf_custom_PracticeName" id="inf_custom_PracticeName">
                                            <option selected="selected" value="">Select Practice Type</option>
                                            <option value="Orthodontics">Orthodontics</option><option value="Pediatric Dentistry">Pediatric Dentistry</option><option value="OrthoPedo Only">OrthoPedo Only</option><option value="General Dentistry">General Dentistry</option><option value="Multi Specialty">Multi Specialty</option>

                                        </select>

                                    </div>
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">First Name *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <input name="inf_field_FirstName" id="inf_field_FirstName" type="text" class="input password" placeholder="First Name [*]" maxlength="15"/>
                                    </div>
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">Last Name *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <input name="inf_field_LastName" id="inf_field_LastName" type="text" class="input password" placeholder="Last Name [*]"  maxlength="15"/>
                                    </div>
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">Job Title *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <input name="inf_field_JobTitle" id="inf_field_JobTitle" type="text" class="input password" placeholder="Job Title [*]"  maxlength="15"/>
                                    </div>
                                    <div class="content-col-1">
                                        <label for="inf_field_LastName">Phone *</label>
                                    </div>
                                    <div class="content-col-2">
                                        <input name="inf_field_Phone1" id="inf_field_Phone1" type="text" class="input password" placeholder="Phone [*]"  maxlength="15" onkeyup="this.value = this.value.replace(/\D/g, '')"/>
                                    </div>
                                    <p class="err-msg clear" id="claim-error-msg"></p>
                                </div>

                                <div class="footer">
                                    <input type="submit" name="submit" id="claimUpBtn" value="Claim" class="button" />&nbsp;
                                    <span id="signup-progress"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!---end code----->
        <!------------------------------- end testing model---------------------------->

<?php echo $this->Html->script(CDN.'js/jquery.remodal-min.js'); ?>
<?php //echo $this->Html->script(CDN.'js/jquery-ui.js'); ?>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script>
                                            $(function() {
                                                $('.popup-modal').magnificPopup({
                                                    type: 'inline',
                                                    preloader: false,
                                                    focus: '#username',
                                                    modal: true
                                                });
                                            });</script>

        <!--------------END BY SAHOO COLOR BOX----------------------------->
    </body>

</html>

<script>
    $("#clickme").hover(function() {
                $("#Style").toggle();
            });
    <?php if(isset($sessionfbuser) && !empty($sessionfbuser)){ ?>
    $('#main-sign-up').modal("show");
    <?php } ?>
    function getemailcont() {

        var emailprovide = $('input[name=emailprovide]:checked').val();
        if (emailprovide == 'perent') {
            $xml = "<div class='content-col-1'>";
            $xml += "<input name='email' id='email' type='email' class='input email' placeholder='Email [*]' onkeypress='clearError()' maxlength='50'/>";
            $xml += "</div>";
            $xml += "<div class='content-col-2'>";
            $xml += "<input name='parents_email' id='parents_email' type='email' class='input email' placeholder='Username [*]' maxlength='50'/>";
            $xml += "</div>";
        } else {
            $xml = "<div class='content-col-1'>";
            $xml += "<input name='email' id='email' type='email' class='input email' placeholder='Email [*]' onkeypress='clearError()' maxlength='50'/>";
            $xml += "</div>";
        }
        $('#emailvalid').html($xml);
    }

    $("#claimhear").click(function() {
        $("#inf_field_Company").val('');
        $("#inf_custom_PracticeName").val('');
        $("#inf_field_FirstName").val('');
        $("#inf_field_LastName").val('');
        $("#inf_field_JobTitle").val('');
        $("#inf_field_Phone1").val('');
    });
    function claimpractice() {

        if ($("#inf_field_Company").val() == '') {
            $("#inf_field_Company").focus();
            $("#claim-error-msg").text("Practice name cannot be blank");
            return false;
        } else if ($("#inf_custom_PracticeName").val() == '') {
            $("#inf_custom_PracticeName").focus();
            $("#claim-error-msg").text("Select practice type");
            return false;
        } else if ($("#inf_field_FirstName").val() == '') {
            $("#inf_field_FirstName").focus();
            $("#claim-error-msg").text("First name cannot be blank");
            return false;
        } else if ($("#inf_field_LastName").val() == '') {
            $("#inf_field_LastName").focus();
            $("#claim-error-msg").text("Last name cannot be blank");
            return false;
        } else if ($("#inf_field_JobTitle").val() == '') {
            $("#inf_field_JobTitle").focus();
            $("#claim-error-msg").text("Job title cannot be blank");
            return false;
        } else if ($("#inf_field_Phone1").val() == '') {
            $("#inf_field_Phone1").focus();
            $("#claim-error-msg").text("Phone cannot be blank");
            return false;
        }
        else {
            document.getElementById("claimform").submit();
            return true;
        }
    }
    $('#specialty').val('');
    $('#pincode').val('');
    function getdoctor() {
        var specialty = $('#specialty').val();
        var pincode = $('#pincode').val();
        $.ajax({
            type: "POST",
            data: "specialty=" + specialty + "&pincode=" + pincode + "&data[_Token][key]=" + $("input[name='data[_Token][key]']").val(),
            url: "/buzzydoc/getdoctor/",
            success: function(result) {
                $('#doctor_list').html(result);
            }});
    }

    function getdoctorviapincode(e) {
        var key = e.keyCode || e.which;
        if (key == 13) {
            var specialty = $('#specialty').val();
            var pincode = $('#pincode').val();
            $.ajax({
                type: "POST",
                data: "specialty=" + specialty + "&pincode=" + pincode,
                url: "/buzzydoc/getdoctor/",
                success: function(result) {
                    $('#doctor_list').html(result);
                }
            });
        }
    }
    $("#home-login").click(function() {
        $("#username").val('');
        $("#password").val('');
        $("#error-msg").text('');
        $("#femail").val('');
        $("#forgot-progress").text('');
        $("#cardnumber").html('&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">');
    });
    $("#home-login1").click(function() {
        $("#username").val('');
        $("#password").val('');
        $("#error-msg").text('');
        $("#femail").val('');
        $("#error-msg-forgot").text('');
        $("#cardnumber").html('&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">');
    });
    $("#home-login2").click(function() {
        $("#username").val('');
        $("#password").val('');
        $("#error-msg").text('');
        $("#femail").val('');
        $("#error-msg-forgot").text('');
        $("#cardnumber").html('&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">');
    });
    $("#home-login3").click(function() {
        $("#username").val('');
        $("#password").val('');
        $("#error-msg").text('');
        $("#femail").val('');
        $("#error-msg-forgot").text('');
        $("#cardnumber").html('&nbsp;<input type="hidden"  id="cardcheck" name="cardcheck" value="No">');
    });
    $(".signUp").click(function() {
        if ($('#myModal').css('display') == "block") {
            $('html').addClass('scroll-none');
            $('body').addClass('scroll-none');
        } else {
            $('html').removeClass('scroll-none');
            $('body').removeClass('scroll-none');
        }
        $("#date_pick").datepicker("destroy");
        if ($('#date_pick').css("display") == "block") {
            $('#date_pick').hide();
        }

        $('#signUpBtn').attr('disabled', false);
        $("#first_name").val('');
        $("#last_name").val('');
        setTimeout('$("#first_name").focus()', 30);
        $("#signup-password").val('');
        $("#signup-confirm-password").val('');
        $("#custom_date").val('');
        $("#email").val('');
        $("#street1").val('');
        $("#street2").val('');
        $("#state").val('');
        $("#city").val('');
        $("#postal_code").val('');
        $("#phone").val('');
        $("#parents_email").val('');
        $("#email_field").text('');
        $("#signup-error-msg").text('');
    });
    $(".signup-btn1").click(function(e) {
        if ($('#myModal').css('display') == "block") {
            $('html').addClass('scroll-none');
            $('body').addClass('scroll-none');
        } else {
            $('html').removeClass('scroll-none');
            $('body').removeClass('scroll-none');
        }
        $("#date_pick").datepicker("destroy");
        if ($('#date_pick').css("display") == "block") {
            $('#date_pick').hide();
        }

        $('#signUpBtn').attr('disabled', false);
        $("#first_name").val('');
        setTimeout('$("#first_name").focus()', 30);
        $("#last_name").val('');
        $("#signup-password").val('');
        $("#signup-confirm-password").val('');
        $("#custom_date").val('');
        $("#email").val('');
        $("#street1").val('');
        $("#street2").val('');
        $("#state").val('');
        $("#city").val('');
        $("#postal_code").val('');
        $("#email_field").text('');
        $("#phone").val('');
        $("#parents_email").val('');
        $("#signup-error-msg").text('');
        $('#search_type').val('');
        $('#search_practice').val('');
        $('#main-sign-up').modal("hide");
    });



</script>
