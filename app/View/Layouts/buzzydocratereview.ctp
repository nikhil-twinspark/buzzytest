<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
        <title>BuzzyDoc | Rate & Review</title>
    <?php 
        echo $this->Html->css(CDN.'css/assets/buzzydoc-user/bootstrap.css');
        echo $this->Html->css('/css/main.css');
        echo $this->Html->css(CDN.'css/jquery-ui.css');
        echo $this->Html->script(CDN.'js/assets/js/bootstrap.min.js');
        echo $this->Html->script(CDN.'js/jquery.min.js');
        echo $this->Html->script(CDN.'js/custom.js');
        echo $this->Html->script(CDN.'js/owl.carousel.js');
        echo $this->Html->script(CDN.'js/jquery-ui.js');
        echo $this->Html->script(CDN.'js/jquery.responsiveTabs.min.js');
        echo $this->Html->script(CDN.'js/jquery.remodal-min.js');
        $sessionbuzzy = $this->Session->read('userdetail');
    ?>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.0.1/jquery.rateyo.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.0.1/jquery.rateyo.min.js"></script>
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
