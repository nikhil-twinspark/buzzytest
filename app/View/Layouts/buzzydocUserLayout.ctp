<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
        <title>BuzzyDoc | My Profile</title>

        <meta name="description" content="3 styles with inline editable feature" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <?php
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/bootstrap.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/font-awesome.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/jquery-ui.custom.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/ace.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/jquery.gritter.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/ace-fonts.css');
            echo $this->Html->css('/css/assets/buzzydoc-user/custom.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/select2.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/datepicker.css');
            echo $this->Html->css(CDN.'css/assets/buzzydoc-user/bootstrap-editable.css');
        
            echo $this->Html->script(CDN.'js/assets/js/ace-extra.min.js');
            echo $this->Html->script(CDN.'js/assets/js/jquery.min.js');
            echo $this->Html->script(CDN.'js/assets/js/jquery-ui.min.js');
            echo $this->Html->script(CDN.'js/assets/js/jquery.easypiechart.min.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/bootstrap.js');
//            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace-extra.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.fileinput.js');
//            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/fuelux/fuelux.spinner.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/date-time/bootstrap-datepicker.js');
//            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.scroller.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/date-time/moment.js');
            echo $this->Html->script(CDN.'js/timeago.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/x-editable/bootstrap-editable.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/x-editable/ace-editable.js');
            echo $this->Html->script(CDN.'js/assets/buzzydoc-user/select2.js');
            echo $this->Html->script(CDN.'js/userDashboard.js');
        $sessionbuzzy = $this->Session->read('userdetail');
        ?>


        <!-- ace styles -->

        <!--[if lte IE 9]>
                <link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
        <![endif]-->

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="../assets/css/ace-ie.css" />
        <![endif]-->

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
        <script src="../assets/js/html5shiv.js"></script>
        <script src="../assets/js/respond.js"></script>
        <![endif]-->
        <style>
            .profile-picture{
                width:auto;
            }
       @media (min-width: 768px) and (max-width: 992px) {     
            .profile-picture{
                width:100%;
            }
       }
       
       @media (min-width: 320px) and (max-width: 767px) {     
            .profile-picture{
                width:auto;
            }
       }
        </style>
        <script type="text/javascript">
      window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var n=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(n?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(a,o);for(var r=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["clearEventProperties","identify","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=r(p[c])};
      heap.load("3420711851");
    </script>
    </head>

    <body class="no-skin">
        <!-- #section:basics/navbar.layout -->
        <nav id="navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle menu-top-btn collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/dashboard"><?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo')); ?></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse wrap-dd" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a class="<?php echo (($this->params['action'] == "thebuzz") ? "active" : "") ?>" href="/thebuzz" title="The Buzz">The Buzz</a></li>

<!--                        <li>
                            <a class="<?php echo (($this->params['action'] == "practice") ? "active" : "") ?>" href="/practice" title="Top Practices">Top Practices</a></li>-->

                        <li>
                            <a class="<?php echo (($this->params['action'] == "dashboard") ? "active" : "") ?>" href="/dashboard" title="My Profile">My Profile</a></li>
                        <li>
                            <a class="<?php echo (($this->params['action'] == "settings") ? "active" : "") ?>" href="/settings" title="Settings">Settings</a></li>
                        <li><a href="http://blog.buzzydoc.com/" title="Blog" target="_blank">Blog</a></li>
                        <li><a href="http://helpme.buzzydoc.com" title="Help" target="_blank">Help</a></li>
                        <li>
                            <a href="#buzzy-points" onclick="{$('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Points'); $('#bpoint').addClass('highlight');$('#vprofile').removeClass('highlight');$('#ubadge').removeClass('highlight');$('#ucheckins').removeClass('highlight');$('#usaved').removeClass('highlight');$('#uliked').removeClass('highlight');$('#buzzy-points').addClass('active');$('#user-profile').removeClass('active');$('#badges').removeClass('active');$('#checkins').removeClass('active');$('#saved').removeClass('active');$('#liked').removeClass('active');}" id="tab-points1" title="My BuzzyDoc Points" style="cursor: pointer;">
                                <?php echo $this->html->image(CDN.'img/images_buzzy/point_header_n_footer2x.png', array('title' => 'point', 'alt' => 'point icon', 'class' => 'header-point-icon')); ?>

                                <span class="point-header" id="toppoint"><?= $sessionbuzzy->totalPointsShort; ?></span>
                                <span>Points</span>
                            </a>
                        </li>
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle user-menu-link">
                                <?php if(!$sessionbuzzy->User->profile_img_url) {
                                	$imgUrl = CDN.'img/buzzydoc-user/avatars/avatar5.png' ; 
                                } else{
                                	$imgUrl = S3Path.$sessionbuzzy->User->profile_img_url ; 
                                }
                            
                              echo  $this->html->image($imgUrl, array('title' => ucwords($sessionbuzzy->User->first_name), 'alt' => 'User\'s Photo', 'class' => 'nav-user-photo')); 
                                ?>
                                <!--<img class="nav-user-photo" src="../assets/avatars/user.jpg" alt="Jason's Photo" />-->
                                <span class="user-info" id="usernametext">
                                    <?= ucwords($sessionbuzzy->User->first_name); ?>
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
  

                                <li>
                                    <a href="/logout">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <form action="/searchresult" method="post" name="search-doctor-header">
                        <input type="search" id="key" name="key" title="Find a doctor or practice" placeholder="Find a doctor or practice..." class="search-box">
                    </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
            <!-- /section:basics/sidebar -->
            <!-- /section: content-->

            <?php echo $this->fetch('content'); ?>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script type="text/javascript">
//            window.jQuery || document.write("<script src='../assets/js/jquery.js'>" + "<" + "/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='assets/buzzydoc-user/jquery.mobile.custom.js'>" + "<" + "/script>");
        </script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="../assets/js/excanvas.js"></script>
        <![endif]-->
        <?php
        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/jquery-ui.custom.js');
        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/jquery.ui.touch-punch.js');
        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/jquery.gritter.js');
//        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/jquery.hotkeys.js');
        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/ace.js');
        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/ace.ajax-content.js');
//        echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/ace.touch-drag.js');
        ?>

    </body>
</html>
