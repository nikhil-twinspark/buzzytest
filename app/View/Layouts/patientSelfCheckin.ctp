<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ucwords($this->params['action'])?></title>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes, minimum-scale=1.0">
<link rel="shortcut icon" href="<?php echo CDN; ?>/favicon.ico">
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

<script>
		$(function() {
			
			var window_wid = $(window).width();
			if(window_wid>767){
			var height_right = $(".rightcont").height();
			var height_left = $(".leftcont").height();
			//console.log(height_right);
			if(height_right<height_left){
				$(".rightcont ").css('height',height_left + "px");
			}else if(height_left<height_right){
				$(".leftcont ").css('height',height_right + "px");
			}	
			}
			
			
			/*if(height_right<800){
				var height = $(".leftcont").height();
			//console.log(height);
 				$(".rightcont ").css('height',height + "px");
				} else{
						//var height_r = $(".leftcont").height();
						$(".leftcont ").css('height',height_right + "px");
				}*/
			
			var pull 		= $('#pull');
				menu 		= $('nav');
				menuHeight	= menu.height();

			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});

			$(window).resize(function(){
        		var w = $(window).width();
        		if(w > 320 && menu.is(':hidden')) {
        			menu.removeAttr('style');
        		}
    		});
		});
	</script>
<body >
 <div class="pagewrap container">
     
     <nav class="clearfix">
     
      <div id="logo">
      <?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?php echo Staff_Name;?>rewards/home"><img src="<?php echo S3Path.$sessionpatient['Themes']['patient_logo_url'];?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?php echo Staff_Name;?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>$sessionpatient['Themes']['api_user']));?></a>
       <?php } ?>
       </div>
      <div id="naviBar" class="clearfix">
       <ul class="hidden-xs">
       <?php if(isset($sessionpatient['selfcheckin']['var']['patient_name'])){ 
       ?>
       <!--<li> <a href="<?php echo Staff_Name;?>rewards/stafflogout/">Staff Logout</a></li> -->
       <li> <a href="<?php echo Staff_Name;?>rewards/patientlogout/">Logout</a></li>
       
   
             <?php }?>
       </ul>
       
       <div class="visible-xs">
       <ul class="col-xs-6">
       <?php if(isset($sessionpatient['var']) && $this->params['action']!='login'){ 
    
       ?>
         <li> <a href="<?php echo Staff_Name;?>rewards/home" style="<?php if($this->params['action']=='home'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Home</a></li>
          
       <li> <a href="<?php echo Staff_Name;?>rewards/logout/">Logout</a></li>
             <?php }?>
       </ul>
       
     
      </div>
     </nav>
     

     
     <!--nav-->

			<?php echo $this->fetch('content'); ?>
		<!--content end -->
   <footer class="clearfix">
     
      <div class="clearfix">
      <div class="col-sm-6 col-md-6 col-xs-6">
      <a href="<?php echo Staff_Name;?>rewards/home"><img src="<?php echo CDN; ?>img/lamparski/lamparski_footer_image" alt="logo" title="logo"/></a>
     
   
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
           <?php } ?>
          </div>
          <div class="footer_info hidden-xs" >     
              Support: <span style="cursor: pointer" onclick="do_lightbox()">help@buzzydoc.com</span>
      <br/>
               <p>(888) 696-4753</p>
              Your information is safe
      </div>
          
           </div>
           </div>
           
           <div class="footer_info visible-xs"  >     
               Support: <span style="cursor: pointer" onclick="do_lightbox()">help@buzzydoc.com</span>
      <br/>
               <p>(888) 696-4753</p>
               Your information is safe
      </div>
           </div>
                 
    </footer><!--footer-->
       
</div> <!--pagewrape -->
 <?php echo $this->element('footer_lightbox1'); ?>
   <?php if(isset($sessionpatient['Themes']['analytic_code']) && $sessionpatient['Themes']['analytic_code']!=''){ echo $sessionpatient['Themes']['analytic_code']; } ?>

     
 </div>
</body>
</html>
<SCRIPT type="text/javascript">
 history.pushState({ page: 1 }, "title 1", "#no-back-button");
    window.onhashchange = function (event) {
        window.location.hash = "no-back-button";

    }
</SCRIPT> 
