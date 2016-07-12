<?php
$sessionpatient = $this->Session->read('patient');

$total=count($sessionpatient['ProfileField'])+3;
$m=0;
//echo "<pre>";print_r($sessionpatient['customer_info']['ProfileField']);die;
					foreach ($sessionpatient['customer_info']['ProfileField'] as $field_sorted) {
						
						
						
								if (isset($field_sorted['ProfileFieldUser']['value']) && $field_sorted['profile_field'] != 'street2' ) {
									
									if(isset($field_sorted['ProfileFieldUser']['value']) && $field_sorted['ProfileFieldUser']['value']!='' ){
									$m++;
                                                                        
									}
								}
					
					}
			
					
					if($sessionpatient['customer_info']['user']['email']!=''){
						$m++;
					}
					if($sessionpatient['customer_info']['user']['custom_date']!=''){
						$m++;
					}
					if($sessionpatient['customer_info']['user']['first_name']!=''){
						$m++;
						
					}
					if($sessionpatient['customer_info']['user']['last_name']!=''){
						$m++;
						
					}
                                     
		
$completed=$m;
$uncompleted=$total-$m;	
$complitionper= number_format(($m/$total)*100); 
//$this->Session->write('patient.profile_comp', $complitionper); 
$_SESSION['patient']['profile_comp']=$complitionper;
echo $this->Html->css(CDN.'css/kendo.common.min.css');
echo $this->Html->css(CDN.'css/kendo.default.min.css');
echo $this->Html->css(CDN.'css/kendo.dataviz.min.css');
echo $this->Html->script(CDN.'js/kendo.dataviz.min.js');
?>
 <script>

	 
        function createChart() {
            $("#donutchart").kendoChart({
               
                legend: {
                    visible: false
                },
                chartArea: {
                    background: ""
                },
                seriesDefaults: {
                    type: "donut",
                    startAngle: 90,
                    holeSize: 35,
                        size: 10,
                },
                
                series: [{
                  
                    data: [{
                        category: "<?=100-$complitionper?>%",
                        value: <?=$uncompleted?>,
                        color: "red"
                    },{
                        category: "<?=$complitionper?>%",
                        value: <?=$completed?>,
                        color: "grey"
                    }]
                }],
                tooltip: {
                    visible: true,
                    template: "#= category #"
                }
            });
            
        }
		
        $(document).ready(createChart);
        setTimeout("$('#centertext').text('<?=$complitionper?>%')",1000);
        $(document).bind("kendo:skinChange", createChart);
        
        
        
    </script>
   

  <div class="col-lg-3 leftcont">
          <div class="lefcontBox">
           <div class="socialIcon">
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
            <div class="welcomeBox clearfix">
            <p>Welcome <?php
            if($sessionpatient['customer_info']['user']['first_name']!=''){
            echo $sessionpatient['customer_info']['user']['first_name'];
            }else{
            echo $sessionpatient['customer_info']['ClinicUser']['card_number'];}?>,</p>
              <span>
               <span class="pointTopBG"></span>
                <p>you have
                 <strong id="total_point_div" > 
                     <?php 
                     if($sessionpatient['customer_info']['user']['points']>0){
                           $global= $current_balance=$sessionpatient['customer_info']['user']['points'];
                           }else{
                            $global=0;  
                           }
                           if($sessionpatient['customer_info']['ClinicUser']['local_points']>0){
                           $local= $current_balance=$sessionpatient['customer_info']['ClinicUser']['local_points'];
                           }else{
                            $local=0;  
                           }
                     //echo "<pre>";print_r($sessionpatient);die;
                           
                           echo "<span title='To redeem the global points go to buzzydoc.com and after clicking the link you would be redirected to the rewards site.'><a href=".Buzzy_Name."buzzydoc/login/".base64_encode($sessionpatient['customer_info']['user']['id'])." target='_blank' style='color:#fff;cusror:pointer;font-size: 20px;display: block;padding: 0 7px 0;text-align:left;'>".$this->html->image(CDN.'img/reward_imges/globe_small.png',array('width'=>'20','height'=>'20','alt'=>'global','style'=>'margin: -6px 5px 0 0;'))."".$global."</a></span>";
                     if($local==0 && $sessionpatient['Themes']['is_buzzydoc']==1){
                     
                     }else{
                         echo "<span title='Local Points' style='color:#fff;cusror:pointer;font-size: 20px;display: block;padding: 0 7px 0; text-align:left;'>".$this->html->image(CDN.'img/reward_imges/home_small.png',array('width'=>'20','height'=>'20','alt'=>'local','style'=>'margin: -6px 5px 0 0;'))."".$local.'</span>';
                     }
//                     if($sessionpatient['is_buzzydoc']==1){
//                     if(isset($sessionpatient['customer_info']['user']['points'])){ echo $current_balance=$sessionpatient['customer_info']['ClinicUser']['local_points'].'('.$sessionpatient['customer_info']['user']['points'].')'; }else{ echo $current_balance='0'; }    
//                     }else{
//                       if(isset($sessionpatient['customer_info']['ClinicUser']['local_points'])){ 
//                           if($sessionpatient['customer_info']['user']['points']>0){
//                           echo $current_balance=$sessionpatient['customer_info']['ClinicUser']['local_points'];
//                           }else{
//                            echo $current_balance=$sessionpatient['customer_info']['ClinicUser']['local_points'];  
//                           }
//                           }else{ echo $current_balance='0'; }  
//                     }
                      ?></strong>
                 Points</p>
                <span class="pointbottomBG"></span>
              </span>
            <?php if($local==0 && $sessionpatient['Themes']['is_buzzydoc']==1){
                     
                     }else{ ?>
                       <a href="<?=Staff_Name?>rewards/reward"><button class="btn no-bg">Start redeeming </button></a>
                   <?php  }
                     ?>
              
              
            </div>
            <div class="earnpoint visible-xs">
            <h2 class="visible-xs">HOW DO I EARN MORE POINTS</h2>
            <p class="visible-xs">Earn Points by Participating in Challenges, referring a friend and many ways.</p>
            <a href="<?=Staff_Name?>rewards/earn" class="btn btn-primary clearfix buttondflt visible-xs learn_more">Learn More</a>
            
              <p class="hidden-xs"><strong>Earn points</strong> at your visit</p>
               <p class="hidden-xs"><strong>Earn bonus points</strong> 
                     at home</p>
                   
                <p class="hidden-xs"><strong>Earn points for</strong> completing challenges</strong></p>
               <p class="profilelink hidden-xs">
               <a href="<?=Staff_Name?>rewards/earn" id="logout">Click here to know more <span class="ssc_code">&#62</span></a>
               </p>   
            </div>
             <div class="proflie clearfix">
             <div class="col-md-7 col-sm-7 col-xs-7" style="padding:0;">
				 <?php if($sessionpatient['is_mobile']==0){ ?>
              <p >YOUR PROFILE IS <?=$complitionper?>% DONE</p>
              <?php if($_SESSION['patient']['profile_comp']<100){ ?>
              <p class="profilelink"><a href="<?=Staff_Name?>rewards/editprofile/" id="logout">COMPLETE YOUR PROFILE <span class="ssc_code">&#62</span></a> </p>
              <?php } }else{ ?>
              <p class='profile_mob'>YOUR PROFILE</p>
              <p><?php
            if($sessionpatient['customer_info']['ClinicUser']['first_name']!=''){
            echo $sessionpatient['customer_info']['ClinicUser']['first_name'];
            }else{
            echo $sessionpatient['customer_info']['ClinicUser']['card_number']; }?>,YOUR PROFILE IS <?=$complitionper?>% DONE,</p>
            
              <?php if($_SESSION['patient']['profile_comp']<100){ ?>
              <p>COMPLETE IT TO EARN POINTS.</p>
              <p class="profilelink"><a href="<?=Staff_Name?>rewards/editprofile/" id="logout" class="btn btn-primary clearfix buttondflt visible-xs learn_more">EDIT PROFILE </a> </p>
              
				  <?php }} ?>
              </div>
              
              <div class="col-md-5 col-sm-5 col-xs-5 chart-h" style="padding:0;">
              <div id='chart' style="position:relative;">
			  <div id="centertext"></div>
    		  <div id="donutchart" style="width:97px; height: 120px;"></div>
              </div>
              </div>
                  
            </div>
              <?php 
              //clinic=5 is off
              if($sessionpatient['staffaccess']['AccessStaff']['refer']==1){ ?>
              <div class="clearfix">
                <div class="refertofriend" id='login_facebook_div'>  <a href='/rewards/refer/'>REFER FRIENDS & FAMILY</a></div>
            </div>
              <?php } ?>
            <div class="earnpoint hidden-xs">
            <h2 class="visible-xs">HOW DO I EARN MORE POINTS</h2>
            <p class="visible-xs">Earn Points by Participating in Challenges, referring a friend and many ways.</p>
            <a href="<?=Staff_Name?>rewards/earn" class="btn btn-primary clearfix buttondflt visible-xs learn_more">Learn More</a>
            
              <p class="hidden-xs"><strong>Earn points</strong> at your visit</p>
               <p class="hidden-xs"><strong>Earn bonus points</strong> 
                     at home</p>
                   
                <p class="hidden-xs"><strong>Earn points for</strong> completing challenges</strong></p>
               <p class="profilelink hidden-xs">
               <a href="<?=Staff_Name?>rewards/earn" id="logout">Click here to know more <span class="ssc_code">&#62</span></a>
               </p>
               
               <!-----------------------add by sahoo----------->
             <?php  $session_patient = $this->Session->read('patient'); ?>
                   <div id="fb-root"></div>
                    <script src="//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $session_patient['Themes']['fb_app_id'] ?>"></script>
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
                            url: "<?php echo Staff_Name ?>rewards/facebookpointallocation",
                            data: "fb_status=like",
                            success: function(msg) {
                                $("#fb_progress_div").hide();
                                
                                if(msg==1){
				      
                                      var cur_point=$("#total_point_div").text();
                                      cur_point=parseInt(cur_point) + parseInt(100);
                                      $("#total_point_div").text(cur_point);
                                      $('#fb_like_div').remove();
                                      $("#login_facebook_div").remove();
                                      alert("We've credited 100 points to you as we found that you've already liked our Facebook page. Thanks!");
                                      location.reload();
                                  }else{
                                      alert("Please click on login with facebook button to gain reward");
                                  }
                            }
                        });

                    });
                    
                    
                  </script>
                   <!--<div class="fb-like" data-href="https://www.facebook.com/LamparskiOrthodontics" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>-->
                   <?php  
                   
                        if($session_patient['customer_info']['ClinicUser']['facebook_like_status']!=1){
                             $config = array(
                                'appId' => $session_patient['Themes']['fb_app_id'],
                                'secret' =>$session_patient['Themes']['fb_app_key'],
                                'allowSignedRequest' => false
                            );

                            $facebook = new Facebook($config);
                            $user = $facebook->getUser();
                            $loginUrl='';
                            ?>
                             <div id="fb_like_div" class="fb_new">
                                 
                                  <div class="fb-like-box" data-href="<?php echo $session_patient['Themes']['facebook_url'] ?>" data-colorscheme="light" 
                                       data-show-faces="false" data-header="true" data-stream="false" data-show-border="false"></div>
                                  
                                  <p style="display:none;" id="fb_progress_div" >
                                      <?php echo $this->html->image(CDN.'img/loading.gif',array('alt'=>'fb like'));?>Please wait...
                                  </p>
<?php
                           
                            if($user){ ?>
                                  <p class="profilelink hidden-xs">
                                      <a href="javascript:void(0)" style="cursor:default;">Get 100 points instantly for clicking "like"</a>
                                  </p>
                            <?php }else{ ?>
                                  <p class="profilelink hidden-xs">
                                      <a href="javascript:void(0)" style="cursor:default;">PLEASE LOGIN TO FACEBOOK TO EARN 100 INSTANT POINTS</a>
                                  </p>
                            <?php } ?>
                             </div>
                   
                   
                   <div class="login_facebook" id='login_facebook_div'> 
                       <?php
                           
                            if(!$user){
                                $loginUrl = $facebook->getLoginUrl(); ?>
                                <a href='<?php echo $loginUrl ?>'>Login with Facebook</a>
                                
                     <?php }  ?>
                       
                       
                   </div>
                   
                       <?php  } ?>
               <!----------------------------end by sh---------------------------->
              
            </div>
            
            
             
          </div>
         </div>
<?php

if(@$errorMsg != ""){ ?>
 <script language="javascript">
        function hideEr() {
                document.getElementById('errorBlock').style.display = 'none';
        }
	</script>
	<script language="javascript">
		(function($){
			$(document).ready(function() {
				$("[AutoHide]").each(function() {
					if (!isNaN($(this).attr("AutoHide"))) {
						eval("setTimeout(function() {jQuery('#" + this.id + "').hide();}, " + parseInt($(this).attr('AutoHide')) * 800 + ");");
					}
				});
			});
		})(jQuery);
	</script>
	<div id="errorBlock" AutoHide="5" class="errorBlockCF" ondblclick="javascript: document.getElementById('errorBlock').style.display = 'none';">
	  <div class="errorBlockCFMsg"><?php echo @$errorMsg; ?></div>
	 
	  <br clear="all" />
	</div>
<?php } ?>
<style>
    /*error message */
        .errorBlockCF {
    background-color: #FFFFFF;
    border: 2px solid #BDBDBD;
    border-radius: 10px;
    box-shadow: 0 0 98px 1px #000000;
    clear: both;
    color: #915900;
    display: block;
    font-size: 12px;
    font-weight: bold;
    height: 23%;
    left: 30%;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 40%;
    width: 45.83%;
    z-index: 1000;
}
        .errorBlockCFImg{float:left; text-align:center;padding:15px 0px 0px 10px;}
        .errorBlockCFMsg{float:left;line-height: 16px;padding: 56px 0 10px 10px;text-align: center;width: 90%; color:#000; font-size:13px; font-weight:bold;}
        .errorBlockCFBtn{float:left; margin: 9px 5px; width:31px;background:url(../images/icons/close-btn.png) no-repeat;height:17px;}
        .errorBlockCFBtn a{color:#000; margin-left:-11px;}
        .close {background:url(../images/icons/close-btn.png) no-repeat;width:17px;height:17px;display:block;}
    /* end error message */


</style>
	


