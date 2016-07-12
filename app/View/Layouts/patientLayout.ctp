<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ucwords($this->params['action'])?></title>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes, minimum-scale=1.0">
 <meta name="apple-mobile-web-app-capable" content="no">
 <meta name="format-detection" content="telephone=no">
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
       <script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/6XYrIT7FBM0Nb5RIR3GeQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();


var my_options = { mode: 'full', primary_color: '#cc6d00', link_color: '#007dbf', default_mode: 'support', forum_id: 34190, tab_label: 'Feedback & Support', tab_color: '#cc6d00', tab_position: 'middle-right', tab_inverted: false }
// Set colors
UserVoice.push(['set', {
  accent_color: '#448dd6',
  trigger_color: 'white',
  trigger_background_color: 'rgba(46, 49, 51, 0.6)'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {

}]);

UserVoice.push(['autoprompt', {}]);
</script> 
<script type="text/javascript">
      window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var n=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(n?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(a,o);for(var r=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["clearEventProperties","identify","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=r(p[c])};
      heap.load("3420711851");
    </script>
</head>

<script>
    
    $(window).load(function(){
        
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
    });
		$(function() {
			
			
			
			
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
<body>
     <div class="aboutUs"><a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support" data-uv-forum-id="34190">Ask us</a></div>
 <div class="pagewrap container">
     
     <nav class="clearfix">
     
      <div id="logo">
      <?php 
     
      if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?php echo Staff_Name;?>rewards/home"><img src="<?php echo S3Path.$sessionpatient['Themes']['patient_logo_url'];?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?php echo Staff_Name;?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>$sessionpatient['Themes']['api_user']));?></a>
       <?php } ?>
       </div>
      <div id="naviBar" class="clearfix">
       <ul class="hidden-xs">
       <?php if(isset($sessionpatient['var']) && $this->params['action']!='login'){ 
  
       ?>
         <li> <a href="<?php echo Staff_Name;?>rewards/home" style="<?php if($this->params['action']=='home'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Home</a></li>
         <li> <a href="<?php echo Buzzy_Name;?>buzzydoc/login/<?php echo base64_encode($sessionpatient['customer_info']['user']['id']); ?>" style="<?php if($this->params['action']=='home1'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>" target="_blank">Redeem</a>
         </li>
          <li><a href="<?php echo Staff_Name;?>rewards/earn" style="<?php if($this->params['action']=='earn'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">EARN</a></li>
       <?php if($sessionpatient['customer_info']['ClinicUser']['local_points']==0 && $sessionpatient['Themes']['is_buzzydoc']==1){ }else{ ?>
       <li> <a href="<?php echo Staff_Name;?>rewards/reward" style="<?php if($this->params['action']=='reward'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Rewards</a></li>
       <?php } 

       if($sessionpatient['Themes']['is_buzzydoc']==1 && $sessionpatient['product']==1 && $sessionpatient['staffaccess']['AccessStaff']['product_service']==1){

       ?>
       
       <li> <a href="<?php echo Staff_Name;?>rewards/productservice" style="<?php if($this->params['action']=='productservice'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Products & Services</a></li>
      
       <?php }if($sessionpatient['Contest']==1){ ?>
       <li> <a href="<?php echo Staff_Name;?>rewards/contest" style="<?php if($this->params['action']=='contest'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Contest</a></li>
       <?php } ?>
       
<!--       <li> <a href="<?php echo Buzzy_Name; ?>buzzydoc/login/<?=base64_encode($sessionpatient['customer_info']['user']['id'])?>" target="_blank" style="<?php if($this->params['action']=='home1'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Dashboard</a></li>-->
       <li> <a href="<?php echo Staff_Name;?>rewards/logout/">Logout</a></li>
          <li>
           <div class="dropdown selectNavi col-xs-12 ">
             <a href="#" class="logout" data-toggle="dropdown" > </a>
              
               <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                 <span class="selectnaviIcon"></span>
                  <li><a href="<?php echo Staff_Name;?>rewards/profile/#liprofile" id="logout">Profile</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#lichangepass" id="logout">Change Password</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#linotification" id="logout">Notification</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#liorderstatus" id="logout">Order Status</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#lirefer" id="logout">Referrals</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#liwish" id="logout">WishList</a></li>
			<li><a href="<?php echo Staff_Name;?>rewards/earn/#documentdiv" id="document">Documents & Forms</a></li>
			<?php if(isset($sessionpatient['is_parent'])){ ?>
			<li><a href="<?php echo Staff_Name;?>rewards/profile/#switch" id="document">Switch</a></li>
			<?php }
			?>
			<?php if(isset($sessionpatient['parent_login'])){ ?>
			<li><form action="<?php echo Staff_Name; ?>rewards/getmultilogin/" method="post">
                        <input type="hidden" name="child_id" id="child_id" value="<?php echo $sessionpatient['parent_id']; ?>">
                        <input type="hidden" name="api_user" id="api_user" value="<?php echo $sessionpatient['api_user']; ?>">
                        <input type="hidden" name="parent_back" id="parent_back" value="1">
                        <button class="gear_btn">SWITCH BACK</button>
                        </form></li>
			<?php }
			?>
			<li>
            
       </li>
                 
             </ul>
             </div>
             </li>
             <?php }?>
       </ul>
 
       <div class="visible-xs">
       <ul class="col-xs-6">
       <?php if(isset($sessionpatient['var']) && $this->params['action']!='login'){ 
    
       ?>
         <li> <a href="<?php echo Staff_Name;?>rewards/home" style="<?php if($this->params['action']=='home'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Home</a></li>
          <li><a href="<?php echo Staff_Name;?>rewards/earn" style="<?php if($this->params['action']=='earn'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Earn</a></li>
       <li>
             <a href="<?=Staff_Name?>rewards/earn/#documentdiv" style="<?php if($this->params['action']=='documents'){
                echo 'color: #3F7AE8;';
             }else{ echo ''; } ?>">Documents & Forms</a>
       </li>
        <?php if($sessionpatient['customer_info']['ClinicUser']['local_points']==0 && $sessionpatient['Themes']['is_buzzydoc']==1){ }else{ ?>
       <li> <a href="<?php echo Staff_Name;?>rewards/reward" style="<?php if($this->params['action']=='reward'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Rewards</a></li>
       <?php } ?>
       <?php if($sessionpatient['Contest']==1){ ?>
       <li> <a href="<?php echo Staff_Name;?>rewards/contest" style="<?php if($this->params['action']=='contest'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Contest</a></li>
       <?php } ?>
      
       <!--<li> <a href="<?php echo Staff_Name;?>rewards/challenges" style="<?php if($this->params['action']=='challenges'){
       echo 'color: #3F7AE8;';
       }else{ echo ''; } ?>">Challenges</a></li>-->
       <li><a href="<?php echo Staff_Name;?>rewards/profile/#liprofile" id="logout">Profile</a></li>
       <?php if(isset($sessionpatient['is_parent'])){ ?>
       <li><a href="<?php echo Staff_Name;?>rewards/profile/#switch" id="document">Switch</a></li>
       <?php } ?>
       <?php if(isset($sessionpatient['parent_login'])){ ?>
        <li><form action="<?php echo Staff_Name; ?>rewards/getmultilogin/" method="post">
        <input type="hidden" name="child_id" id="child_id" value="<?php echo $sessionpatient['parent_id']; ?>">
        <input type="hidden" name="api_user" id="api_user" value="<?php echo $sessionpatient['api_user']; ?>">
        <input type="hidden" name="parent_back" id="parent_back" value="1">
        <button class="gear_btn">SWITCH BACK</button>
        </form></li>
	<?php } 	?>
       
             <?php }?>
       </ul>
       
       <ul class="col-xs-6">
       <?php if(isset($sessionpatient['var']) && $this->params['action']!='login'){ 
    
       ?>
         <li><a href="<?php echo Staff_Name;?>rewards/profile/#lichangepass" id="logout">Change Password</a></li>
          <li><a href="<?php echo Staff_Name;?>rewards/profile/#linotification" id="logout">Notification</a></li>
      <li><a href="<?php echo Staff_Name;?>rewards/profile/#liorderstatus" id="logout">Order Status</a></li>
      <li><a href="<?php echo Staff_Name;?>rewards/profile/#lirefer" id="logout">Refferrals</a></li>
						<li><a href="<?php echo Staff_Name;?>rewards/profile/#liwish" id="logout">WishList</a></li>
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

