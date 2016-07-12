<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title_for_layout; ?></title>
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes, minimum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="no">
        <meta name="format-detection" content="telephone=no">
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
<?php
           $sessionpatient = $this->Session->read('patient');
		echo $this->Html->css(CDN.'css/bootstrap.css');
		echo $this->Html->css(CDN.'css/style_rewards.css');
                echo $this->Html->css(CDN.'css/form_error.css');
		echo $this->Html->script(CDN.'js/jquery.min.js');
		echo $this->Html->script(CDN.'js/bootstrap.js');
                echo $this->Html->script(CDN.'js/jquery.js');
                echo $this->Html->script(CDN.'js/jquery.validate.js');
                echo $this->Html->script(CDN.'js/common.js');
	?>
        <link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
        <script>
            // Include the UserVoice JavaScript SDK (only needed once on a page)
            UserVoice = window.UserVoice || [];
            (function() {
                var uv = document.createElement('script');
                uv.type = 'text/javascript';
                uv.async = true;
                uv.src = '//widget.uservoice.com/6XYrIT7FBM0Nb5RIR3GeQ.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(uv, s)
            })();
            var my_options = {mode: 'full', primary_color: '#cc6d00', link_color: '#007dbf', default_mode: 'support', forum_id: 34190, tab_label: 'Feedback & Support', tab_color: '#cc6d00', tab_position: 'middle-right', tab_inverted: false}
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
            heap.load("3420711851");
        </script>
    </head>

    <body>
        <div class="aboutUs"><a href="javascript:void(0)" data-uv-lightbox="classic_widget" data-uv-mode="full" data-uv-primary-color="#cc6d00" data-uv-link-color="#007dbf" data-uv-default-mode="support" data-uv-forum-id="34190">Ask us</a></div>
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

                    <?php 
                    if($sessionpatient['ClinicDetails']['patient_mobile_image']!=''){
                    echo $this->html->image(CDN.$sessionpatient['ClinicDetails']['patient_mobile_image'],array('class'=>'img-responsive','height'=>'409px','width'=>'638px'));
                    }else{
                    echo $this->html->image(CDN.'img/reward_imges/mobile-slider.jpg',array('class'=>'img-responsive','height'=>'409px','width'=>'638px'));   
                    }
                    ?>
                    <div class="carousel-caption carouseloption">
                        <?php if($sessionpatient['ClinicDetails']['reward_site_mobile_content']!=''){ echo $sessionpatient['ClinicDetails']['reward_site_mobile_content']; } ?>
                    </div>
                </div>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!--its for slider-->
                    <?php if($sessionpatient['ClinicDetails']['patient_slider_image_1']!='' && $sessionpatient['ClinicDetails']['patient_slider_image_2']!=''){ ?>
                    <ol class="carousel-indicators controller">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"> </li>
                        <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
                    </ol><!--indicators-->
                    <?php } ?>
                    <div class="carousel-inner">
                        <div class="item active ">

               <?php 
               if($sessionpatient['ClinicDetails']['patient_slider_image_1']!=''){
                   echo $this->html->image(CDN.$sessionpatient['ClinicDetails']['patient_slider_image_1'],array('height'=>'341px','width'=>'960px')); 
               }else{
                 echo $this->html->image(CDN.'img/reward_imges/sliderfirst.png',array('height'=>'341px','width'=>'960px'));   
               }
               ?>
                            <div class="carousel-caption carouseloption">
                            <?php if($sessionpatient['ClinicDetails']['reward_site_content']!=''){ echo $sessionpatient['ClinicDetails']['reward_site_content']; } ?>
                            </div>

                        </div>
                        <?php if($sessionpatient['ClinicDetails']['patient_slider_image_2']!=''){ ?>
                        <div class="item ">
        <?php echo $this->html->image(CDN.$sessionpatient['ClinicDetails']['patient_slider_image_2'],array('height'=>'341px','width'=>'960px'));  ?>

                            <div class="carousel-caption carouseloption">
                            <?php echo $sessionpatient['ClinicDetails']['reward_site_content']; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div><!--wrapper for slide-->
            </section><!--sliderTop--> 
            <!--content -->
			<?php echo $this->fetch('content'); ?>
            <!--content end -->
            
            <?php if($sessionpatient['ClinicDetails']['how_it_works']!=''){ ?>

            <section class="workArea clearfix">
                <?php echo $sessionpatient['ClinicDetails']['how_it_works']; ?>

            </section><!--workArea-->

            <?php } ?>

            <footer class="clearfix">

                <div class="clearfix">
                    <div class="col-sm-6 col-md-6 col-xs-6">
                        <a href="/rewards/login"><img src="<?php echo CDN; ?>img/lamparski/lamparski_footer_image" alt="logo" title="logo" height="23px" width="111px"/></a>

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
