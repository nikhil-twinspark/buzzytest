<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta charset="utf-8">
            <meta property="og:locale" content="en_US" /> 
            <meta property="og:site_name" content="Buzzydoc"/>
            <meta property="og:url" content="<?php echo $shareUrl; ?>" />
            <meta property="og:type" content="website" />
            <meta property="og:description" content="<?php if($currentRateReview['review']!=''){ echo $currentRateReview['review']; }else{ "I just gave the rate and review for this clinic"; } ?>" />
            <meta property="og:image" content="<?php echo S3Path.$ClinicDetails['patient_logo_url'];?>" />
            <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
                <title>BuzzyDoc | <?php echo ucwords($this->params['action'])?></title>
    <?php 
        echo $this->Html->css('/css/main.css'); 
        echo $this->Html->css(CDN.'css/jquery-ui_new.css'); 
    ?>
                </head>
                <body>
                    <header class="main-header">
                        <div class="row cf">
                            <a href="/dashboard" class="main-logo" title="BuzzyDoc">
<?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo'));?>
                            </a>
                            <div class="menu-btn"></div>
                            <nav class="top-navigation">
                            </nav><!-- .top-navigation-->
                        </div>
                    </header><!-- .main-header-->

                    <section class="relevant-details">
                        <div class="row">
                            <div class="relevant-adjust">
                                <div class="details-wrap cf">
                                    <header class="relevant-header">PRACTICE PROFILE</header>
                                    <div class="main-details">
                                        <div class="details-all-info-wrap">
                                            <div class="details-all-info cf">
                                                <div class="detials-info" style="width: 100% !important">
                                                    <div class="main-thumb">

<?php 

                                    if(isset($ClinicDetails['patient_logo_url']) && $ClinicDetails['patient_logo_url']!=''){
                                    
 ?>
                                                        <a href="javascript:void(0)"><img src="<?php echo S3Path.$ClinicDetails['patient_logo_url'];?>" alt="<?=$ClinicDetails['api_user'];?>" title="<?=$ClinicDetails['api_user'];?>" /></a>
<?php
}else{
    echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$ClinicDetails['api_user'],'alt'=>$ClinicDetails['api_user'],'class'=>'thumb-picture'));
}

?>
                                                    </div>

                                                    <div class="detials-info-all-text">
                                                        <h4 class="thumb-name"><?php
                                    if($ClinicDetails['display_name']==''){ echo $clinicname=$ClinicDetails['api_user'];}else{ echo $clinicname=$ClinicDetails['display_name']; } ?></h4>
                                                    </div>

                                                </div><!-- .detials-info-->
                                                <div class="all-tabs-wrap">
                                                    <div class="all-tabs-container cf r-tabs" id="horizontalTab">
                                                        <div class="tab-container rating-tab r-tabs-panel r-tabs-state-active" id="tab-1" style="display: block;">

                                                            <div class="rating-n-rate-wrap cf">
                                                                <div class="rating rating-adjust" id="ratedstar1">
                                                    <?php 
                                                         $grey=5-$currentRateReview['rate'];
                                                         for($i=0;$i<$currentRateReview['rate'];$i++){ ?>
                                                                    <span class="fullstar"></span>
                                                    <?php }
                                                    for($i1=0;$i1<$grey;$i1++){ ?>
                                                                    <span class="greystar"></span>
                                                    <?php }  ?>
                                                                </div>

                                                            </div>
                                                            <p class="rating-overview">
                                            <?php echo $currentRateReview['review']; ?>
                                                            </p>
                                                        </div><!-- .rating-tab-->
                                                    </div><!-- .all-tabs-container-->
                                                </div>


                                            </div><!-- .details-all-info-->
                                        </div><!-- .details-all-info-wrap-->
                                    </div><!-- .main-details-->
                                </div><!-- .details-wrap-->
                            </div><!-- .relevant-adjust-->
                        </div>


                        <div class="two-cols-wrap">
                            <div class="row cf">
                                <section class="left-module" style="width: 100% !important;max-width: 88% !important;">
                                    <header class="left-module-heading">
                                        <div class="modified-border-top"></div>
                                        <h4 class="left-module-title-heading">Other Reviews</h4>
                                        <div class="modified-border-bottom clear"></div>
                                    </header>
                                    <ul class="left-module-listing ratereview">
                    <?php if(!empty($otherRateReview)){
                        foreach($otherRateReview as $ratereview){ ?>

                                        <li>
                                            <span class="date-detail"><?php echo date("M d,Y", strtotime($ratereview['RateReview']['created_on'])); ?></span>
                                            <div class="data-container">

                                                <div class="doc-small-img">
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                                                </div>
                                                <span class="doc-place-name"><?php echo $ratereview['User']['first_name'].' '.$ratereview['User']['last_name']; ?></span>
                                                <p class="listing-description">
                                                    <span class="rating rating-adjust" id="ratedstar1" style="max-width: 185px !important;">
                                                    <?php 
                                                         $grey1=5-$ratereview['RateReview']['rate'];
                                                         for($i2=0;$i2<$ratereview['RateReview']['rate'];$i2++){ ?>
                                                        <span class="fullstar"></span>
                                                    <?php }
                                                    for($i3=0;$i3<$grey1;$i3++){ ?>
                                                        <span class="greystar"></span>
                                                    <?php }  ?>
                                                    </span>
                            <?php echo $ratereview['RateReview']['review']; ?>
                                                </p>
                                            </div>
                                        </li>
                    <?php }}else{ ?>
                                        <li>
                                            No rate and review available
                                        </li>
                    <?php } ?>
                                    </ul>
                                </section><!-- .left-module-->

                            </div>
                        </div><!-- .two-cols-wrap-->
                    </section><!-- .relevant-details -->
                    <footer class="main-footer">
                        <div class="row cf">
                            <a href="/dashboard" class="footer-logo" title="BuzzyDoc">

<?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc Logo','class'=>''));?>
                            </a>
                            <nav class="bottom-navigation">

                            </nav><!-- .bottom-navigation-->

                            <div class="terms-n-conditions">
                    <!--          <p>&copy; BuzzyDoc.com. All rights reserved <a href="/buzzydoc/termcondition/">Terms and conditions</a></p>-->
                                <p>&copy; BuzzyDoc.com. All rights reserved <a href="javascript:void(0)">Terms and conditions</a></p>
                            </div>
                        </div>
                    </footer><!-- .main-footer-->




                </body>
                </html>
