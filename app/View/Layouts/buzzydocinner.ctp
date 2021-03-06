<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
        <title>BuzzyDoc | <?php echo ucwords($this->params['action'])?></title>


    <?php 
 
        if($this->params['action']=='settings' || $this->params['action']=='doctor' || $this->params['action']=='practice'){ 
            
            echo $this->Html->css(CDN.'css/assets/css/ace.min.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/bootstrap.css');
            echo $this->Html->css(CDN.'css/assets/css/font-awesome.min.css');
        }
        echo $this->Html->css(CDN.'css/main.css'); 
        echo $this->Html->css(CDN.'css/jquery-ui_new.css'); 

        echo $this->Html->script(CDN.'js/jquery.min.js');
        echo $this->Html->script(CDN.'js/custom.js');
        echo $this->Html->script(CDN.'js/owl.carousel.js');
        echo $this->Html->script(CDN.'js/jquery.responsiveTabs.min.js');
        echo $this->Html->script(CDN.'js/jquery-ui.min.js');
        echo $this->Html->script(CDN.'js/jquery.colorbox-min.js');
        $sessionbuzzy = $this->Session->read('userdetail');
     echo $this->Html->script(CDN.'js/jquery.remodal-min.js');
    ?>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   <script type="text/javascript">
      window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var n=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(n?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(a,o);for(var r=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["clearEventProperties","identify","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=r(p[c])};
      heap.load("3420711851");
    </script>
  </head>
  <body>
    <header class="main-header">
      <div class="row cf">
        <a href="/dashboard" class="main-logo" title="BuzzyDoc">


<?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo'));?>
                </a>
                <div class="menu-btn"></div>
                <nav class="top-navigation">
                    <ul>
                        <li>
                            <a class="<?php echo (($this->params['action']=="thebuzz") ? "active" : "") ?>" href="/thebuzz" title="The Buzz">The Buzz</a>
                        </li>

<!--<li><a href="<?php //echo $this->Html->url(array("controller"=>"Buzzydoc","action"=>"doctor"));?>" title="Top Docs">Top Docs</a></li>-->
<!--                        <li>
                            <a class="<?php echo (($this->params['action']=="practice") ? "active" : "") ?>" href="/practice" title="Top Practices">Top Practices</a>
                        </li>-->
                <?php if (!empty($sessionbuzzy)){ ?>
                        <li>
                            <a class="<?php echo (($this->params['action']=="dashboard") ? "active" : "") ?>" href="/dashboard" title="My Profile">My Profile</a>
                        </li>
                        <li>
                            <a class="<?php echo (($this->params['action'] == "settings") ? "active" : "") ?>" href="/settings" title="Settings">Settings</a></li>
                <?php } ?>
                        <li><a href="http://blog.buzzydoc.com/" title="Blog" target="_blank">Blog</a></li>
                        <li><a href="http://helpme.buzzydoc.com" title="Help" target="_blank">Help</a></li>
                <?php if (!empty($sessionbuzzy)){ ?>
                        <li>
                            <a href="<?php echo $this->Html->url(array("controller"=>"Buzzydoc","action"=>"dashboard/#buzzy-points"));?>" onclick="{
                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Points');
                                        $('#bpoint').addClass('highlight');
                                        $('#vprofile').removeClass('highlight');
                                        $('#ubadge').removeClass('highlight');
                                        $('#ucheckins').removeClass('highlight');
                                        $('#usaved').removeClass('highlight');
                                        $('#uliked').removeClass('highlight');
                                        $('#buzzy-points').addClass('active');
                                        $('#user-profile').removeClass('active');
                                        $('#badges').removeClass('active');
                                        $('#checkins').removeClass('active');
                                        $('#saved').removeClass('active');
                                        $('#liked').removeClass('active');
                                    }" id="tab-points" title="My BuzzyDoc Points" style="cursor: pointer;">
                        <?php echo $this->html->image(CDN.'img/images_buzzy/point_header_n_footer2x.png',array('title'=>'point','alt'=>'point icon','class'=>'header-point-icon'));?>
                                <span class="point-header"><?php echo $sessionbuzzy->totalPointsShort; ?></span>
                                Points
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller"=>"Buzzydoc","action"=>"logout"));?>" title="Help">logout</a></li>
                <?php }else{ ?>
                        <li>
                            <a href="/login" title="Help">Sign In</a></li>
                <?php } ?>
                    </ul>
                </nav><!-- .top-navigation-->
                <form action="/searchresult" method="post" name="search-doctor-header">
                    <input type="search" id="key" name="key" title="Find a doctor or practice" placeholder="Find a doctor or practice..." class="search-box">
                </form>
            </div>
        </header><!-- .main-header-->

<?php echo $this->fetch('content'); ?>
        <footer class="main-footer">
            <div class="row cf">
                <a href="/dashboard" class="footer-logo" title="BuzzyDoc">

<?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc Logo','class'=>''));?>
                </a>
                <nav class="bottom-navigation">
                    <ul>
                        <li><a href="/thebuzz" title="The Buzz">The Buzz</a></li>
                        <li><a href="/practice" title="Top Practices">Top Practices</a></li>
            <?php if (!empty($sessionbuzzy)){ ?>
                        <li><a href="/dashboard" title="My Profile">My Profile</a></li>
            <?php } ?>
                        <li><a href="http://blog.buzzydoc.com/" title="Blog" target="_blank">Blog</a></li>
                        <li><a href="http://helpme.buzzydoc.com" title="Help" target="_blank">Help</a></li>
            <?php if (!empty($sessionbuzzy)){ ?><li>
                            <a href="<?php echo $this->Html->url(array("controller"=>"Buzzydoc","action"=>"dashboard/#buzzy-points"));?>" onclick="{
                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Points');
                                        $('#bpoint').addClass('highlight');
                                        $('#vprofile').removeClass('highlight');
                                        $('#ubadge').removeClass('highlight');
                                        $('#ucheckins').removeClass('highlight');
                                        $('#usaved').removeClass('highlight');
                                        $('#uliked').removeClass('highlight');
                                        $('#buzzy-points').addClass('active');
                                        $('#user-profile').removeClass('active');
                                        $('#badges').removeClass('active');
                                        $('#checkins').removeClass('active');
                                        $('#saved').removeClass('active');
                                        $('#liked').removeClass('active');
                                    }" id="tab-points" title="My BuzzyDoc Points" style="cursor: pointer;">

<?php echo $this->html->image(CDN.'img/images_buzzy/point_header_n_footer2x.png',array('title'=>'point','alt'=>'point icon','class'=>'footer-point-icon'));?>
                                <span class="point-footer"><?php echo $sessionbuzzy->totalPointsShort; ?></span>
                                Points
                            </a>
                        </li>
            <?php } ?>
                    </ul>
                </nav><!-- .bottom-navigation-->

                <div class="terms-n-conditions">
        <!--          <p>&copy; BuzzyDoc.com. All rights reserved <a href="/buzzydoc/termcondition/">Terms and conditions</a></p>-->
                    <p>&copy; BuzzyDoc.com. All rights reserved <a href="javascript:void(0)">Terms and conditions</a></p>
                </div>
            </div>
        </footer><!-- .main-footer-->




    </body>
</html>
