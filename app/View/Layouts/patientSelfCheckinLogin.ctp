<!doctype html>
<html>
<head>
<meta charset="utf-8">

<title><?php echo $title_for_layout; ?></title>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes, minimum-scale=1.0">
<link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
<?php
		$sessionpatient = $this->Session->read('patient');
		echo $this->Html->css(CDN.'css/bootstrap.css');
		echo $this->Html->css(CDN.'css/style_rewards.css');
		echo $this->Html->script(CDN.'js/jquery.min.js');
		echo $this->Html->script(CDN.'js/bootstrap.js');
                
                echo $this->Html->css(CDN.'css/form_error.css');
                echo $this->Html->script(CDN.'js/jquery.js');
                echo $this->Html->script(CDN.'js/jquery.validate.js');
                echo $this->Html->script(CDN.'js/common.js');
	?>

	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
</head>

<body>
 <div class="pagewrap container">
     <div id="logo">
    
     <?php 

     if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="/rewards/login"><img src="<?php echo S3Path.$sessionpatient['Themes']['patient_logo_url'];?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="/rewards/login"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>$sessionpatient['Themes']['api_user']));?></a>
       <?php } ?>
     </div>
    
     <section id="sliderArea">
      <div class="mobile_slider">
<?php echo $this->html->image(CDN.'img/reward_imges/mobile-slider.jpg',array('class'=>'img-responsive'));?>
<div class="carousel-caption carouseloption">
               <h2>SAY CHEESE!</h2>
 <p> Presenting our Rewards program 
exclusively for you. </p>
<br />
                <p > <span class="textbold">Ask the front desk</span> how you 
can join our new rewards 
program today.</p>
</div>
     </div>
         <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
           <ol class="carousel-indicators controller">
             <li data-target="#carousel-example-generic" data-slide-to="0" class="active"> </li>
             <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
            
          </ol><!--indicators-->
             <div class="carousel-inner">
               <div class="item active ">
               <?php echo $this->html->image(CDN.'img/reward_imges/sliderfirst.jpg',array('alt'=>'980px'));?>
              <div class="carousel-caption carouseloption">
               <h2>SAY CHEESE!</h2>
               
               <?php
$sessionpatient = $this->Session->read('patient');
	if($sessionpatient['is_mobile']==0){
 ?>
             <p>  We are lucky enough to fill our practice with the best patients! 
<span style="display:block; ">We have created our Rewards program to recognize your accomplishments 
and congratulate your winning achievements.  Every visit is an opportunity to earn points and win cool prizes.</span></p>
                <p class="margin-top"><span class="textbold">Ask the front desk</span> how you can join our new rewards program today.</p>
                <?php }else{ ?>
                <p> Presenting our Rewards program 
exclusively for you. </p>
                <p > <span class="textbold">Ask the front desk</span> how you 
can join our new rewards 
program today.</p>
                


                <?php } ?>
             </div>
        </div>
        <div class="item ">
        <?php echo $this->html->image(CDN.'img/reward_imges/slidersec.jpg',array('alt'=>'980px'));?>

                <div class="carousel-caption carouseloption">
                 <h2>SAY CHEESE!</h2>
                 <?php
$sessionpatient = $this->Session->read('patient');
	if($sessionpatient['is_mobile']==0){
 ?>
             <p>  We are lucky enough to fill our practice with the best patients! 
<span style="display:block; ">We have created our Rewards program to recognize your accomplishments 
and congratulate your winning achievements.  Every visit is an opportunity to earn points and win cool prizes.</span></p>
                <p class="margin-top"><span class="textbold">Ask the front desk</span> how you can join our new rewards program today.</p>
                <?php }else{ ?>
                <p> Presenting our Rewards program 
exclusively for you. </p>
                <p > <span class="textbold">Ask the front desk</span> how you 
can join our new rewards 
program today.</p>
                


                <?php } ?>
             </div>
        </div>
        
       
        </div>
        
            <!--a class="left carousel-control carouselscroll" href="#carousel-example-generic" data-slide="prev">
               <span class="preIcon "></span>
               </a>
             <a class="right carousel-control carouselscroll" href="#carousel-example-generic" data-slide="next">
             <span class="nextIcon"></span>
            </a><!-- Controls -->
             </div><!--wrapper for slide-->
             
          </section><!--sliderTop--> 
   
    
    <!--content -->
			<?php echo $this->fetch('content'); ?>
		<!--content end -->



<section class="workArea clearfix">
      <h2>how it works</h2>
       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pointArea">
         <div class="rewards">
            <span class="pointIcon"></span>
            <h2>POINTS</h2>
            <p>Earn points for initial  consultation visits, liking us on Facebook, being on time for appointments, participating in challenges, referring us to your friends and many more easy ways.</p>
          </div>
        <span class="pointBG"></span> 
       </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pointArea">
        <div class="rewards">
        <span class="rewardsIcon"></span>
         <h2>rewards</h2>
            <p>Redeem your points for electronics, video games, iPods, toys, books, T-shirts, gift cards, or use them to play challenges, contests and win more fun stuff.</p>
         </div>
         <span class="pointBG"></span>  
      </div>
     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pointArea ">
      <div class="challenge">
      <span class="challengeIcon"></span>
        <h2>challenges</h2>
            <p>Enter our challenges and win big prizes. We work on updating challenges regularly to make this experience fun for you and your family.</p>
      </div>
         <span class="pointBG"></span> 
     </div>
     
     </section><!--workArea-->
     
     
     
        <footer class="clearfix">
   
      <div class="clearfix">
      <div class="col-sm-6 col-md-6 col-xs-6">
      <a href="/rewards/login"><img src="<?php echo CDN; ?>img/lamparski/lamparski_footer_image" alt="logo" title="logo"/></a>
     
      </div>
      <div class="socialIcon pull-right col-sm-6 col-md-6 col-xs-6">
      		<div style="float:right;">
      		<span>Follow Us</span>
             <?php if(isset($sessionpatient['Themes']['twitter_url']) && $sessionpatient['Themes']['twitter_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['twitter_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png',array('width'=>'23','height'=>'22','alt'=>'twitter'));?></a>
            <?php } ?>
            <?php if(isset($sessionpatient['Themes']['facebook_url']) && $sessionpatient['Themes']['facebook_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['facebook_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png',array('width'=>'23','height'=>'22','alt'=>'facebook'));?></a>
            <?php } ?>
            <?php if(isset($sessionpatient['Themes']['google_url']) && $sessionpatient['Themes']['google_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['google_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
           <?php } ?>
           <?php if(isset($sessionpatient['Themes']['instagram_url']) && $sessionpatient['Themes']['instagram_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['instagram_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/instagram.png',array('width'=>'23','height'=>'22','alt'=>'instagram'));?></a>
           <?php } ?>
           <?php if(isset($sessionpatient['Themes']['pintrest_url']) && $sessionpatient['Themes']['pintrest_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['pintrest_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/pinterest.png',array('width'=>'23','height'=>'22','alt'=>'pinterest'));?></a>
           <?php } ?>
           <?php if(isset($sessionpatient['Themes']['yelp_url']) && $sessionpatient['Themes']['yelp_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['yelp_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/yelp.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
           <?php } ?>
           <?php if(isset($sessionpatient['Themes']['youtube_url']) && $sessionpatient['Themes']['youtube_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['youtube_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/you-tube.png',array('width'=>'23','height'=>'22','alt'=>'youtube'));?></a>
           <?php } ?>
           <?php if(isset($sessionpatient['Themes']['healthgrade_url']) && $sessionpatient['Themes']['healthgrade_url']!=''){ ?>
            <a href="<?php echo $sessionpatient['Themes']['healthgrade_url'];?>" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
           <?php } ?> </div>
          <div class="footer_info hidden-xs" >     
              Support: <span style="cursor: pointer" onclick="do_lightbox()">help@buzzydoc.com</span>
      <br/>
               <p>(888) 696-4753</p>
              Your information is safe
      </div>
          
           </div>
           </div>
           
           <div class="footer_info visible-xs">     
               Support: <span style="cursor: pointer" onclick="do_lightbox()">help@buzzydoc.com</span>
      <br/>
               <p>(888) 696-4753</p>
               Your information is safe
      </div>
           
           </div>
      
    </footer><!--footer-->
     <?php echo $this->element('footer_lightbox1'); ?>
     <?php 

     if(isset($sessionpatient['Themes']['analytic_code']) && $sessionpatient['Themes']['analytic_code']!=''){ echo $sessionpatient['Themes']['analytic_code']; } ?>
 </div>
</body>
</html>
<SCRIPT type="text/javascript">
 history.pushState({ page: 1 }, "title 1", "#no-back-button");
    window.onhashchange = function (event) {
        window.location.hash = "no-back-button";

    }
</SCRIPT>
