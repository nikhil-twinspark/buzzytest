<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $title_for_layout; ?></title>
<?php
		$sessionpatient = $this->Session->read('patient');
		echo $this->Html->css(CDN.'css/bootstrap.css');
		echo $this->Html->css(CDN.'css/style_rewards.css');
		echo $this->Html->script(CDN.'js/jquery.min.js');
		echo $this->Html->script(CDN.'js/jquery.validate.js');
		echo $this->Html->script(CDN.'js/bootstrap.js');
	?>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
</head>

<body>
 <div class="pagewrap container">
     <div id="logo"><a href="#">
     <?php //echo $this->html->image('reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles','title'=>'Pure Smiles'));?>
     <img src="<?=$sessionpatient['patient_logo']?>" width="246" height="88" alt="Pure Smiles" title="Pure Smiles" />
     </a></div>
     <section id="sliderArea">
         <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
           <ol class="carousel-indicators controller">
             <li data-target="#carousel-example-generic" data-slide-to="0" class="active"> </li>
             <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
             <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
          </ol><!--indicators-->
             <div class="carousel-inner">
               <div class="item active ">
               <?php echo $this->html->image(CDN.'img/reward_imges/sliderfirst.jpg',array('alt'=>'980px'));?>
              <div class="carousel-caption carouseloption">
               <h2>SAY CHEESE!</h2>
               <p>We are lucky enough to fill our practice with the best patients! 
We have created our Rewards program to recognize your accomplishments 
and congratulate your winning achievements. You set the bar every visit 
is an opportunity to earn points and cool prizes.</p>
                <p class="textbold">Ask the front desk how you can join our new rewards program today.</p>
             </div>
        </div>
        <div class="item ">
        <?php echo $this->html->image(CDN.'img/reward_imges/slidersec.jpg',array('alt'=>'980px'));?>

                <div class="carousel-caption carouseloption">
                 <h2>SAY CHEESE!</h2>
               <p>We are lucky enough to fill our practice with the best patients! 
We have created our Rewards program to recognize your accomplishments 
and congratulate your winning achievements. You set the bar every visit 
is an opportunity to earn points and cool prizes.</p>
                <p class="textbold">Ask the front desk how you can join our new rewards program today.</p>
             </div>
        </div>
        
        <div class="item ">
                  <?php echo $this->html->image(CDN.'img/reward_imges/sliderfirst.jpg');?>
               <div class="carousel-caption carouseloption">
                <h2>SAY CHEESE!</h2>
               <p>We are lucky enough to fill our practice with the best patients! 
We have created our Rewards program to recognize your accomplishments 
and congratulate your winning achievements. You set the bar every visit 
is an opportunity to earn points and cool prizes.</p>
                <p class="textbold">Ask the front desk how you can join our new rewards program today.</p>
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
       <div class="col-md-4 col-xs-12 pointArea">
         <div class="rewards">
            <span class="rewardsIcon"></span>
            <h2>POINTS</h2>
            <p>Earn points for initial consultation visits, liking us on Facebook, being on time for appointments, participating in challenges, referring us to your friends and many more easy ways.</p>
          </div>
        <span class="pointBG"></span> 
       </div>
      <div class="col-md-4 col-xs-12 pointArea">
        <div class="rewards">
        <span class="rewardsIcon"></span>
         <h2>rewards</h2>
            <p>Earn points for initial consultation visits, liking us on Facebook, being on time for appointments, participating in challenges, referring us to your friends and many more easy ways.</p>
         </div>
         <span class="pointBG"></span>  
      </div>
     <div class="col-md-4 col-xs-12 pointArea ">
      <div class="challenge">
      <span class="challengeIcon"></span>
        <h2>challenges</h2>
            <p>Earn points for initial consultation visits, liking us on Facebook, being on time for appointments, participating in challenges, referring us to your friends and many more easy ways.</p>
      </div>
         <span class="pointBG"></span> 
     </div>
     
     </section><!--workArea-->
		 <footer class="clearfix">
      <p><span></span>integrate ortho</p>
     </footer><!--footer-->
 </div>
</body>
</html>
