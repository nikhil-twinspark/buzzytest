<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
		<?php
                if($title_for_layout=='UserStaffManagement'){
      $title_for_layout1= 'StaffManagement';
   }
   if($title_for_layout=='PatientManagement' && $this->params['action']=='recordpoint'){
      $title_for_layout1= 'Patient Management';
   }
   if($title_for_layout=='Staff' && $this->params['action']=='notifications'){
      $title_for_layout1= 'Notifications';
   }
   if($title_for_layout=='PatientManagement' && $this->params['action']=='index' && $dashboard==0){
      $title_for_layout1= 'Search Patients';
   }
  
   if($title_for_layout=='PatientManagement' && $this->params['action']=='index' && $dashboard==1){
      $title_for_layout1= 'Dashboard';
   }
   if($title_for_layout=='ProductAndService'){
      $title_for_layout1= 'Other Rewards';
   }
   if($title_for_layout=='manageOrders'){
      $title_for_layout1= 'Manage Orders';
   }
   if($title_for_layout=='BankAccount'){
      $title_for_layout1= 'Bank Account';
   }
   if($title_for_layout=='DocumentManagement'){
      $title_for_layout1= 'Documents';
   }
   if($title_for_layout=='PromotionManagement'){
      $title_for_layout1= 'Ways To Earn';
   }
   if($title_for_layout=='UserManagement'){
      $title_for_layout1= 'Users';
   }
   if($title_for_layout=='StaffRewardManagement'){
      $title_for_layout1= 'Rewards';
   }
   if($title_for_layout=='StaffRedeem'){
      $title_for_layout1= 'Redemptions';
   }
   if($title_for_layout=='LeadManagement'){
      $title_for_layout1= 'Referrals';
   }
   if($title_for_layout=='AdminSetting'){
      $title_for_layout1= 'Referral Levels';
   }
   if($title_for_layout=='BasicReport'){
      $title_for_layout1= 'Basic Report';
   }
   if($title_for_layout=='StaffReferralPromotionManagement'){
      $title_for_layout1= 'Referral Promotion';
   }
   if($title_for_layout=='Staff' && $this->params['action']=='instructions'){
      $title_for_layout1= 'Instructions';
   }
   if($title_for_layout=='ClinicLocation'){
      $title_for_layout1= 'Clinic Locations';
   }
   if($title_for_layout=='Doctor'){
      $title_for_layout1= 'Doctors';
   }
   if($title_for_layout=='StaffProceInsureChareManagement'){
      $title_for_layout1= 'Practice Profile Extras';
   }
   if($title_for_layout=='ReviewManagement'){
      $title_for_layout1= 'Doctor-to-Doctor Reviews';
   }
   if($title_for_layout=='PaymentDetail'){
      $title_for_layout1= 'Payment History';
   }
   if($title_for_layout=='Settings'){
      $title_for_layout1= 'Social Links';
   }
   if($title_for_layout=='PointsHistory'){
      $title_for_layout1= 'Points History';
   }

   if($title_for_layout=='StaffUsageReport'){
      $title_for_layout1= 'Staff Usage Report';
   }
   
   if($title_for_layout=='LevelupPromotions'){
      $title_for_layout1= 'LevelUp Promotions';
   }

   if($title_for_layout=='MilestoneReward'){
      $title_for_layout1= 'Milestone Rewards';
   }
   if($title_for_layout=='AcceleratedReward'){
      $title_for_layout1= 'Accelerated Rewards';
   }
   if($title_for_layout=='StaffRewardProgram'){
       $title_for_layout1='Staff Rewards Program';
   }
   if($title_for_layout=='PatientRateReview'){
      $title_for_layout1= 'Patient\'s Ratings/Reviews';
   }
   if($title_for_layout=='Staff' && $this->params['action']=='reviewreport'){
      $title_for_layout1= 'Ratings/Reviews';
   }
   if($title_for_layout=='Staff' && $this->params['action']=='basicreport'){
      $title_for_layout1= 'Report';
   }
   ?>
        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
        <title><?php echo $title_for_layout1; ?></title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<?php           $sessionstaff = $this->Session->read('staff');
                $access = $sessionstaff['staffaccess']['AccessStaff'];
                echo $this->Html->css(CDN.'css/assets/css/bootstrap.min.css');
                echo $this->Html->css(CDN.'css/assets/css/custom.css');
                echo $this->Html->css(CDN.'css/assets/css/font-awesome.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-fonts.css');
                echo $this->Html->css(CDN.'css/assets/css/jquery-ui.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-skins.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-rtl.min.css');
                echo $this->Html->script(CDN.'js/assets/js/ace-extra.min.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery.min.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery-ui.min.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery.easypiechart.min.js');
                echo $this->Html->script(CDN.'js/jquery.validate.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery.mobile.custom.min.js');
                echo $this->Html->script(CDN.'js/assets/js/bootstrap.min.js');
                echo $this->Html->script(CDN.'js/assets/js/ace-elements.min.js');
                echo $this->Html->script(CDN.'js/assets/js/ace.min.js');
                echo $this->Html->css(CDN.'css/assets/css/ace.onpage-help.css');
                echo $this->Html->css(CDN.'css/docs/assets/js/themes/sunburst.css');
                echo $this->Html->script(CDN.'js/assets/js/ace/elements.onpage-help.js');
                echo $this->Html->script(CDN.'js/assets/js/ace/ace.onpage-help.js');
                echo $this->Html->script(CDN.'js/docs/assets/js/rainbow.js');
                echo $this->Html->script(CDN.'js/docs/assets/js/language/generic.js');
                echo $this->Html->script(CDN.'js/docs/assets/js/language/html.js');
                echo $this->Html->script(CDN.'js/docs/assets/js/language/css.js');
                echo $this->Html->script(CDN.'js/docs/assets/js/language/javascript.js');
                echo $this->Html->script(CDN.'js/assets/js/jquery.ui.touch-punch.min.js');
                echo $this->Html->script(CDN.'js/buzzydoc.js');
                echo $this->Html->script(CDN.'js/jquery.cookie.js');
                ?>
        <script type="text/javascript">
            window.heap = window.heap || [], heap.load = function (e, t) {
                window.heap.appid = e, window.heap.config = t = t || {};
                var n = t.forceSSL || "https:" === document.location.protocol, a = document.createElement("script");
                a.type = "text/javascript", a.async = !0, a.src = (n ? "https:" : "http:") + "//cdn.heapanalytics.com/js/heap-" + e + ".js";
                var o = document.getElementsByTagName("script")[0];
                o.parentNode.insertBefore(a, o);
                for (var r = function (e) {
                    return function () {
                        heap.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                    }
                }, p = ["clearEventProperties", "identify", "setEventProperties", "track", "unsetEventProperty"], c = 0; c < p.length; c++)
                    heap[p[c]] = r(p[c])
            };
            heap.load("3420711851");
        </script>
    </head>
    <script>
        var isBuzzyDoc = "<?php echo $sessionstaff['is_buzzydoc'];?>";
        var clinicId = "<?php echo $sessionstaff['clinic_id'];?>";
        // Include the UserVoice JavaScript SDK (only needed once on a page)
        UserVoice = window.UserVoice || [];
        (function () {
            var uv = document.createElement('script');
            uv.type = 'text/javascript';
            uv.async = true;
            uv.src = '//widget.uservoice.com/cnp5IPlBWychXH4CnQFzbA.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(uv, s)
        })();

        //
        // UserVoice Javascript SDK developer documentation:
        // https://www.uservoice.com/o/javascript-sdk
        //
        // Set colors
        UserVoice.push(['set', {
                accent_color: '#448dd6',
                trigger_color: 'white',
                trigger_background_color: '#020202'
            }]);

        // Identify the user and pass traits
        // To enable, replace sample data with actual user traits and uncomment the line
        UserVoice.push(['identify', {
            }]);

        // Add default trigger to the bottom-right corner of the window:
        UserVoice.push(['addTrigger', {mode: 'contact', trigger_position: 'bottom-right'}]);

        // Or, use your own custom trigger:
        //UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);
        // Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
        UserVoice.push(['autoprompt', {}]);
    </script>
    <style>
        #patient-loading-div{
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.2);
            display: block;
            width: 100%;
            height: 100%;
            text-align:center;
            z-index:999999;
        }
        #patient-loading-div img{
            width:65px;
        }
    </style>
    <body class="no-skin">
        <!-- #section:basics/navbar.layout -->
        <div id="navbar" class="navbar navbar-default">
            <script type="text/javascript">
                try {
                    ace.settings.check('navbar', 'fixed')
                } catch (e) {
                }
            </script>
            <div class="navbar-container" id="navbar-container">
                <!-- #section:basics/sidebar.mobile.toggle -->
                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
                    <span class="sr-only">Toggle sidebar</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- /section:basics/sidebar.mobile.toggle -->
                <div class="navbar-header pull-left">
                    <!-- #section:basics/navbar.layout.brand -->

                    <small>
							<?php if(isset($sessionstaff['var']) && $this->params['action']!='login' && $this->params['action']!='basicreport'){ ?>
                        <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
                            ));?>" class="active logobox" onclick="setSearch();">

<?php echo $this->html->image(CDN.'img/lamparski_staff_new.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo','class'=>'img-responsive'));?>
                        </a>
<?php }else{  ?>

                        <a href="<?=Staff_Name?>staff/login/" class="active logobox" onclick="setSearch();">
<?php echo $this->html->image(CDN.'img/lamparski_staff_new.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo','class'=>'img-responsive'));?>
                        </a>
<?php } ?>

                    </small>

                    <!-- /section:basics/navbar.layout.brand -->
                    <!-- #section:basics/navbar.toggle -->
                    <!-- /section:basics/navbar.toggle -->
                </div>

                <!-- #section:basics/navbar.dropdown -->
                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">

                        <li class="purple">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                                                                <?php if(count($sessionstaff['AllNotifications'])>0){ ?><i class="ace-icon fa fa-bell icon-animated-bell"></i><span class="badge badge-important"><?php echo count($sessionstaff['AllNotifications']); ?></span><?php }else{ ?> <i class="ace-icon fa fa-bell"></i><span class="badge badge-important"><?php echo 0; ?> </span><?php } ?>
                            </a>

                            <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-exclamation-triangle"></i>
                                <?php if(count($sessionstaff['AllNotifications'])>1){ ?><?php echo count($sessionstaff['AllNotifications']).' New Notifications'; ?><?php }else if(count($sessionstaff['AllNotifications'])==1){ ?> <?php echo count($sessionstaff['AllNotifications']).' New Notification'; ?> <?php }else{ ?> <?php echo 'No New Notifications'; ?> <?php } ?>
                                </li>


                                <?php if(count($sessionstaff['AllNotifications'])>0){
                                foreach($sessionstaff['AllNotifications'] as $allnot){ 
                                    $detailnot=json_decode($allnot['ClinicNotification']['details']);
                                    if($allnot['ClinicNotification']['notification_type']==1){

                                                                       ?>
                                <li>
                                    <a href="<?php echo $this->Html->url(array("controller" => "StaffRedeem","action" => "index"));?>" title="Redeem" class="icon-1 info-tooltip Doc">
				<?php echo $detailnot->first_name.' '.$detailnot->last_name.' redeemed '.$detailnot->authorization; ?>
                                    </a>
                                </li>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==2){
                                        
                                                                       ?>
                                <li>
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "PatientRateReview",
                                                                    "action" => "index"
                                                                    ));?>" title="Reviews" class="icon-1 info-tooltip Doc">
										<?php echo $detailnot->first_name.' '.$detailnot->last_name.' has just reviewed the clinic on ';
                                                                                if(isset($detailnot->platform) && $detailnot->platform!=''){ echo $detailnot->platform; }else{ echo 'different social platform.'; } ?>
                                    </a>
                                </li>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==3){
                                        
                                                                       ?>
                                <li>
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Leads" class="icon-1 info-tooltip Doc">

                                        <span class="msg-body">
                                            <span class="msg-title">

                                                                                        <?php echo $detailnot->referrer.' referred '.$detailnot->first_name.' '.$detailnot->last_name; ?>
                                            </span>


                                        </span>
                                    </a>
                                </li>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==4){
                                        
                                                                       ?>
                                <li>
                                    <a href="<?php echo $detailnot->link;?>" target="_blank" onclick="readnotification(<?php echo $allnot['ClinicNotification']['id']; ?>);" class="icon-1 info-tooltip Doc">

                                        <span class="msg-body">
                                            <span class="msg-title">

                                                                                       <?php echo $detailnot->title; ?>
                                            </span>


                                        </span>
                                    </a>
                                </li>

                                    <?php }}  } ?>
                                <li class="dropdown-footer">
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Staff",
                                                                    "action" => "notifications"
                                                                    ));?>" title="Notifications" class="icon-1 info-tooltip Doc">
                                        See all notifications
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if($sessionstaff['staffaccess']['AccessStaff']['show_training_video']==0 ){ ?>
                        <li class="light-green">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">

                                <span class="user-info traing-name">
                                    <small>Training Videos</small>

                                </span>
                                <span class="badge badge-important"><?php echo count($sessionstaff['trainingvideo']); ?></span>
                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <?php 
                                $tv=1;
                                if(!empty($sessionstaff['trainingvideo'])){ 
                                foreach($sessionstaff['trainingvideo'] as $tvideo){ ?>
                                <li id="training-video" class="training-video" data-points="<?php echo $tvideo['TrainingVideo']['id']; ?>" data-type="global">

                                    <a href="javascript:void(0);"><i class="ace-icon fa fa-video-camera"></i><?php echo $tvideo['TrainingVideo']['title']; ?></a>

                                </li>
                                <?php if($tv!=count($sessionstaff['trainingvideo'])){ ?>
                                <li class="divider"></li>
                                <?php } $tv++;}}else{ ?>
                                <li class="training-video" >

                                    <a href="javascript:void(0);"><i class="ace-icon fa fa-video-camera"></i>No Video Found!</a>

                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <!-- #section:basics/navbar.user_menu -->
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">

                                <span class="user-info">

									<?php if(isset($sessionstaff['var']['staff_name']) && $this->params['action']!='login' && $this->params['action']!='basicreport'){ ?>
		  <?php echo $this->html->image(CDN.'img/userpics.png',array('width'=>'18','height'=>'16','alt'=>'user','title'=>'user'));?>
                                    <small>Welcome,</small> <?php if($sessionstaff['var']['staff_fname']!=''){ echo $sessionstaff['var']['staff_fname']; }else{ echo $sessionstaff['var']['staff_name']; }?><?php } ?>
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">


                                <li>
                                    <a href="/UserStaffManagement/edit/<?php echo $sessionstaff['staff_id']; ?>">
                                        <i class="ace-icon fa fa-user"></i>
                                        Profile
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="<?=Staff_Name?>staff/logout/" id="logout" title="Logout">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- /section:basics/navbar.user_menu -->
                    </ul>
                </div>

                <!-- /section:basics/navbar.dropdown -->
            </div><!-- /.navbar-container -->
        </div>
        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
            <!-- #section:basics/sidebar -->
            <div id="sidebar" class="sidebar responsive menu-min">
                <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-success" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "PatientManagement",
							    "action" => "index",
                                                    1
                            ));?>';setSearch();" title="Dashboard">
                            <i class="ace-icon fa fa-signal"></i>
                        </button>

                        <button class="btn btn-info" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "PatientManagement",
							    "action" => "index",
                                                            0
							));?>';setSearch();" title="Search Patient">
                            <i class="ace-icon glyphicon glyphicon-search"></i>
                        </button>

                        <!-- #section:basics/sidebar.layout.shortcuts -->
                        <button class="btn btn-warning" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "UserStaffManagement",
							    "action" => "index"
							));?>'" title="Staff">
                            <i class="ace-icon fa fa-users"></i>
                        </button>

                        <button class="btn btn-danger" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "PaymentDetail",
							    "action" => "index"
							));?>'" title="Payment History">
                            <i class="ace-icon fa fa-gift"></i>
                        </button>
                        <button class="btn btn-purple" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "PointsHistory",
							    "action" => "index"
							));?>'" title="Reports">
                            <i class="ace-icon fa fa-cogs"></i>
                        </button>

                        <!-- /section:basics/sidebar.layout.shortcuts -->
                    </div>

                    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                        <span class="btn btn-success"></span>

                        <span class="btn btn-info"></span>

                        <span class="btn btn-warning"></span>

                        <span class="btn btn-danger"></span>
                    </div>
                </div><!-- /.sidebar-shortcuts -->

                <ul class="nav nav-list">
                    <li class="<?php if($title_for_layout=='PatientManagement' && $dashboard==1){ echo "active"; }?>">
                        <a href="<?php echo $this->Html->url(array(
							    "controller" => "PatientManagement",
							    "action" => "index",
                                                    1
                            ));?>" title="Dashboard" class="icon-1 info-tooltip Doc" onclick="setSearch();">
                            <i class="menu-icon fa fa-tachometer"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="<?php if($title_for_layout=='LevelupPromotions' || $title_for_layout=='PromotionManagement' || $title_for_layout=='MilestoneReward' || $title_for_layout=='AcceleratedReward' || $title_for_layout=='PointsHistory' || $title_for_layout=='StaffRedeem'  || $title_for_layout=='StaffUsageReport'){ echo "active"; }?>">

                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-desktop"></i>
                            <span class="menu-text"> Patient Rewards </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu">
                            <?php 

                            if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['is_buzzydoc']==1  && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1)){  ?>


                            <li class="<?php if($title_for_layout=='LevelupPromotions'){ echo "active"; }?>">
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    <span class=""> Levelup Promotions </span>

                                    <b class="arrow fa fa-angle-down"></b>
                                </a>

                                <b class="arrow"></b>

                                <ul class="submenu" >
                                    <li class="<?php if($title_for_layout=='LevelupPromotions' && ($this->params['action']=='index' || $this->params['action']=='edit')){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LevelupPromotions",
                                                                    "action" => "index"
                                                                    ));?>" title="Editor">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Editor
                                        </a>

                                        <b class="arrow"></b>
                                    </li>

<?php 

                            if($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 ){  ?>
                                    <li class="<?php if($title_for_layout=='LevelupPromotions' && ($this->params['action']=='levelupsettings' || $this->params['action']=='addsettings')){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LevelupPromotions",
                                                                    "action" => "levelupsettings"
                                                                    ));?>" title="Treatment Rewards Plans">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Treatment Rewards Plans
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                            <?php } 
                            if($sessionstaff['staffaccess']['AccessStaff']['interval']==1 ){
                            ?>
                                    <li class="<?php if($title_for_layout=='LevelupPromotions' && ($this->params['action']=='addinterval' || $this->params['action']=='intervallevelupsettings')){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LevelupPromotions",
                                                                    "action" => "intervallevelupsettings"
                                                                    ));?>" title="Interval Rewards Plans">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Interval Rewards Plans
                                        </a>

                                        <b class="arrow"></b>
                                    </li>
                            <?php } ?>
                                </ul>
                            </li>
                    <?php } ?>

                            <li class="<?php if($title_for_layout=='PromotionManagement' && ($this->params['action']=='index' || $this->params['action']=='edit' || $this->params['action']=='add')){ echo "active"; }?>">

                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "PromotionManagement",
							    "action" => "index"
							));?>" title="Ways To Earn" class="icon-1 info-tooltip Promo">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Ways To Earn


                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['rate_review']==1){  ?>
                            <li class="<?php if($title_for_layout=='PromotionManagement' && ($this->params['action']=='ratereviewpromotion' || $this->params['action']=='editratereviewpromotion' )){ echo "active"; }?>">

                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "PromotionManagement",
							    "action" => "ratereviewpromotion"
							));?>" title="Rating/Reviews Promotion" class="icon-1 info-tooltip Promo">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Rating/Reviews Promotion


                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php } ?>
                            <?php if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['milestone_reward']==1){  ?>
                            <li class="<?php if($title_for_layout=='MilestoneReward'){ echo "active"; }?>">

                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "MilestoneReward",
							    "action" => "index"
							));?>" title="Milestone Reward" class="icon-1 info-tooltip Promo">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Milestone Rewards


                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php } ?>
                            <?php if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['tier_setting']==1){  ?>
                            <li class="<?php if($title_for_layout=='AcceleratedReward'){ echo "active"; }?>">

                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "AcceleratedReward",
							    "action" => "index"
							));?>" title="Accelerated Rewards" class="icon-1 info-tooltip Promo">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Accelerated Rewards


                                </a>
                                <b class="arrow"></b>
                            </li>
                            <?php } ?>
                            <li class="<?php if($title_for_layout=='PointsHistory' || $title_for_layout=='StaffRedeem' || $title_for_layout=='StaffUsageReport'){ echo "active"; }?>">
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>

                                    Reports
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>

                                <b class="arrow"></b>

                                <ul class="submenu">
                                    <li class="<?php if($title_for_layout=='PointsHistory'){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "PointsHistory",
                                                                    "action" => "index"
                                                                    ));?>" title="Points History">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Points History
                                        </a>

                                        <b class="arrow"></b>
                                    </li> 

                                    <li class="<?php if($title_for_layout=='StaffRedeem'){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRedeem",
                                                                    "action" => "index"
                                                                    ));?>" title="Redemptions" class="icon-1 info-tooltip Doc">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            <span>Redemptions </span>
                       <?php 
                       $rednotcnt==0;
                       foreach($sessionstaff['AllNotifications'] as $rednot){
                           if($rednot['ClinicNotification']['notification_type']==1){
                             $rednotcnt++;  
                           }
                       }
                       if($rednotcnt>0){ ?><span class="badge badge-important"><?php echo $rednotcnt; ?></span><?php }else{ ?><span class="badge badge-important"><?php echo 0; ?> </span><?php } ?>
                                        </a>
                                        <b class="arrow"></b>
                                    </li>

                                    <?php 
                            if($sessionstaff['staff_role']=='Doctor'  && $sessionstaff['staffaccess']['AccessStaff']['staff_reporting']==1 ){  ?>
                                    <li class="<?php if($title_for_layout=='StaffUsageReport'){ echo "active"; }?>">
                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffUsageReport",
                                                                    "action" => "index"
                                                                    ));?>" title="Staff Usage Report">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Staff Usage Report
                                        </a>

                                        <b class="arrow"></b>
                                    </li> 
                                    <?php } ?>



                                </ul>
                            </li>
                        </ul>
                    </li>


                    <?php if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['staff_reward_program']==1){  ?>

                    <li class="<?php if($title_for_layout=='StaffRewardProgram' ){ echo "active"; }?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-gift"></i>
                            <span class="menu-text"> Staff Rewards Program </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="<?php if($title_for_layout=='StaffRewardProgram'){ echo "submenu nav-show"; }else{ echo "submenu nav-hide"; }?>" style="<?php if($title_for_layout=='StaffRewardProgram' ){ echo "display: block;"; }else{ echo "display: none;"; }?>">
                            <li class="<?php if($title_for_layout=='StaffRewardProgram' && ($this->params['action']=='index' ||  $this->params['action']=='add' || $this->params['action']=='edit')){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRewardProgram",
                                                                    "action" => "index"
                                                                    ));?>" title="Goals" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    <span class="">Goals</span>
                                </a>
                            </li>
                            <li class="<?php if($title_for_layout=='StaffRewardProgram' && ($this->params['action']=='goalsettings' || $this->params['action']=='addsetting' )){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRewardProgram",
                                                                    "action" => "goalsettings"
                                                                    ));?>" title="Set Goal" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Goal Settings
                                </a>
                            </li>
                            <li class="<?php if($title_for_layout=='StaffRewardProgram' && $this->params['action']=='performancereport'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRewardProgram",
                                                                    "action" => "performancereport"
                                                                    ));?>" title="Performance Report" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Performance Report
                                </a>
                            </li>
                            <li class="<?php if($title_for_layout=='StaffRewardProgram' && $this->params['action']=='currentweekreport'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRewardProgram",
                                                                    "action" => "currentweekreport"
                                                                    ));?>" title="Current Week Report" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Current Week Report
                                </a>
                            </li>


                        </ul>
                    </li>

                    <?php } ?>








                    <li class="<?php if($title_for_layout=='LeadManagement' || $title_for_layout=='AdminSetting' || $title_for_layout=='StaffReferralPromotionManagement'){ echo "active"; }?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-list"></i>
                            <span class="menu-text"> Referral Settings </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="<?php if($title_for_layout=='LeadManagement' || $title_for_layout=='AdminSetting' || $title_for_layout=='StaffReferralPromotionManagement'){ echo "submenu nav-show"; }else{ echo "submenu nav-hide"; }?>" style="<?php if($title_for_layout=='StaffReferralPromotionManagement' || $title_for_layout=='LeadManagement' || $title_for_layout=='AdminSetting' ){ echo "display: block;"; }else{ echo "display: none;"; }?>">
                            <li class="<?php if($title_for_layout=='LeadManagement'  && $this->params['action']=='index'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Referrals" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    <span class="">Referrals </span>
                                   <?php 
                                   $refnotcnt==0;
                       foreach($sessionstaff['AllNotifications'] as $rednot){
                           if($rednot['ClinicNotification']['notification_type']==3){
                             $refnotcnt++;  
                           }
                       }
                       if($refnotcnt>0){ ?><span class="badge badge-success"><?php echo $refnotcnt; ?> </span><?php }else{ ?><span class="badge badge-success"><?php echo 0; ?> </span> <?php } ?>
                                </a>
                            </li>
                            <li class="<?php if($title_for_layout=='LeadManagement'  && $this->params['action']=='referralsreport'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "referralsreport"
                                                                    ));?>" title="Referrals Report" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Referrals Report
                                </a>
                            </li>
                              <?php if($sessionstaff['staff_role']!='Manager' && $sessionstaff['staff_role']!='M' ){ ?>
                            <li class="<?php if($title_for_layout=='AdminSetting'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "AdminSetting",
                                                                    "action" => "index"
                                                                    ));?>" title="Referral Levels" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Referral Levels
                                </a>
                            </li>
                                                                <?php } ?>
                            <li class="<?php if($title_for_layout=='StaffReferralPromotionManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffReferralPromotionManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Referral Promotion" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Referral Promotions
                                </a>
                            </li>


                        </ul>
                    </li>






                    <?php if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['is_buzzydoc']=='1' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && DEBIT_FROM_BANK==1){  ?>


                    <li class="<?php if(($title_for_layout=='ProductAndService' && $this->params['action']=='manageOrders') || $title_for_layout=='BankAccount'){ echo "active"; }?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-gift"></i>
                            <span class="menu-text"> Other Rewards </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="<?php if(($title_for_layout=='ProductAndService' && $this->params['action']=='manageOrders') || $title_for_layout=='BankAccount'  || ($title_for_layout=='ProductAndService' && $this->params['action']=='add') || ($title_for_layout=='ProductAndService' && $this->params['action']=='edit')){ echo "submenu nav-show"; }else{ echo "submenu nav-hide"; }?>" style="<?php if(($title_for_layout=='ProductAndService' && $this->params['action']=='manageOrders')  || $title_for_layout=='BankAccount' || ($title_for_layout=='ProductAndService' && $this->params['action']=='add') || ($title_for_layout=='ProductAndService' && $this->params['action']=='edit')){ echo "display: block;"; }else{ echo "display: none;"; }?>">

                            <li class="<?php if($title_for_layout=='ProductAndService' && $this->params['action']=='manageOrders'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ProductAndService",
                                                                    "action" => "manageOrders"
                                                                    ));?>" title="Manage Orders">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Manage Orders
                                </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="<?php if($title_for_layout=='ProductAndService' && $this->params['action']=='index'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ProductAndService",
                                                                    "action" => "index"
                                                                    ));?>" title="Manage Other Rewards">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Manage Other Rewards
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="<?php if($title_for_layout=='BankAccount'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "BankAccount",
                                                                    "action" => "index"
                                                                    ));?>" title="Manage Bank Account">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Manage Bank Account
                                </a>

                                <b class="arrow"></b>
                            </li>

                        </ul>
                    </li>

                    <?php } ?>







                    <li class="<?php if($title_for_layout=='UserStaffManagement' || $title_for_layout=='UserManagement' || $title_for_layout=='DocumentManagement' || $title_for_layout=='StaffRewardManagement'  || $title_for_layout=='BasicReport' || ($title_for_layout=='ProductAndService' && $this->params['action']=='BalanceStatus') || ($title_for_layout=='ProductAndService' && $this->params['action']=='index')){ echo "active"; }?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-pencil-square-o"></i>
                            <span class="menu-text"> Settings </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="<?php if($title_for_layout=='UserStaffManagement' || $title_for_layout=='UserManagement' || $title_for_layout=='DocumentManagement' || $title_for_layout=='StaffRewardManagement'  || $title_for_layout=='BasicReport' || ($title_for_layout=='ProductAndService' && $this->params['action']=='BalanceStatus')){ echo "submenu nav-show"; }else{ echo "submenu nav-hide"; }?>" style="<?php if($title_for_layout=='UserStaffManagement' || $title_for_layout=='UserManagement' || $title_for_layout=='DocumentManagement' || $title_for_layout=='StaffRewardManagement' || $title_for_layout=='BasicReport' || ($title_for_layout=='ProductAndService' && $this->params['action']=='BalanceStatus') || ($title_for_layout=='ProductAndService' && $this->params['action']=='index')){ echo "display: block;"; }else{ echo "display: none;"; }?>">
                            <li class="<?php if($title_for_layout=='UserStaffManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "UserStaffManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Staff" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Staff
                                </a>

                            </li>
                            <?php if($sessionstaff['is_buzzydoc']==0){ ?>
                            <li class="<?php if($title_for_layout=='UserManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "UserManagement",
							    "action" => "index"
							));?>" title="Users" class="icon-1 info-tooltip User">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Users
                                </a>
                            </li>
                            <?php } ?>
                            <li class="<?php if($title_for_layout=='DocumentManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "DocumentManagement",
							    "action" => "index"
							));?>" title="Documents" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Documents
                                </a>
                            </li>
                            <?php if($sessionstaff['is_buzzydoc']==0){ ?>
                            <li class="<?php if($title_for_layout=='StaffRewardManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "StaffRewardManagement",
							    "action" => "index"
							));?>" title="Rewards" class="icon-1 info-tooltip Reward">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Rewards
                                </a>
                            </li>
                            <?php } ?>

                            <li class="<?php if($title_for_layout=='BasicReport'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "BasicReport",
                                                                    "action" => "index"
                                                                    ));?>" title="Basic Report" class="icon-1 info-tooltip Doc">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Basic Report
                                </a>
                            </li>

                            <?php if($sessionstaff['staff_role']=='Doctor'  && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && DEBIT_FROM_BANK==1){  ?>

                            <li class="<?php if($title_for_layout=='ProductAndService' && $this->params['action']=='BalanceStatus'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ProductAndService",
                                                                    "action" => "BalanceStatus"
                                                                    ));?>" title="Balance Status">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Balance Status
                                </a>

                                <b class="arrow"></b>
                            </li>
                            <?php } ?>
                            <?php if($sessionstaff['is_buzzydoc']==1){ ?>
                            <li class="<?php if($title_for_layout=='UserStaffManagement' && $this->params['action']=='paymentdetails'){ echo "active"; }?>">
                                <a href="javascript:void(0);" title="Payment Details" class="icon-1 info-tooltip Doc" onclick="checkdoc();">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payment Details
                                </a>

                            </li>
                            <?php } if($sessionstaff['staff_role']=='Doctor' && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1){ ?>
                            <li class="<?php if($title_for_layout=='ProductAndService' && $this->params['action']=='index'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ProductAndService",
                                                                    "action" => "index"
                                                                    ));?>" title="Manage Other Rewards">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Manage Other Rewards
                                </a>

                                <b class="arrow"></b>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>








                    <li class="<?php if($title_for_layout=='ClinicLocation' || $title_for_layout=='Doctor' || $title_for_layout=='StaffProceInsureChareManagement' || $title_for_layout=='ReviewManagement' || $title_for_layout=='PaymentDetail' || $title_for_layout=='PatientRateReview'){ echo "active"; }?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-tag"></i>
                            <span class="menu-text"> Directory </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="<?php if($title_for_layout=='ClinicLocation' || $title_for_layout=='Doctor' || $title_for_layout=='StaffProceInsureChareManagement' || $title_for_layout=='ReviewManagement' || $title_for_layout=='PaymentDetail' || $title_for_layout=='Settings'|| $title_for_layout=='PatientRateReview'){ echo "submenu nav-show"; }else{ echo "submenu nav-hide"; }?>" style="<?php if($title_for_layout=='ClinicLocation' || $title_for_layout=='Doctor' || $title_for_layout=='StaffProceInsureChareManagement' || $title_for_layout=='ReviewManagement' || $title_for_layout=='PaymentDetail' || $title_for_layout=='Settings'|| $title_for_layout=='PatientRateReview'){ echo "display: block;"; }else{ echo "display: none;"; }?>">
                            <li class="<?php if($title_for_layout=='ClinicLocation'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ClinicLocation",
                                                                    "action" => "index"
                                                                    ));?>" title="Clinic Locations">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Clinic Locations
                                </a>

                                <b class="arrow"></b>
                            </li>
<?php if($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator' || $sessionstaff['staff_role']=='Doctor'){ ?>
                            <li class="<?php if($title_for_layout=='Doctor'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Doctor",
                                                                    "action" => "index"
                                                                    ));?>" title="Doctors">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Doctors
                                </a>

                                <b class="arrow"></b>
                            </li> 
                            <?php } ?>
                            <li class="<?php if($title_for_layout=='StaffProceInsureChareManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffProceInsureChareManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Practice Profile Extras">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Practice Profile Extras
                                </a>

                                <b class="arrow"></b>
                            </li> 
                            <li class="<?php if($title_for_layout=='ReviewManagement'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ReviewManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Doctor-to-Doctor Reviews">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Doctor-to-Doctor Reviews
                                </a>

                                <b class="arrow"></b>
                            </li> 
                            <li class="<?php if($title_for_layout=='PaymentDetail'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "PaymentDetail",
                                                                    "action" => "index"
                                                                    ));?>" title="Payment History">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payment History
                                </a>

                                <b class="arrow"></b>
                            </li> 

                            <li class="<?php if($title_for_layout=='Settings'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Settings",
                                                                    "action" => "edit"
                                                                    ));?>" title="Social Links">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Social Links
                                </a>

                                <b class="arrow"></b>
                            </li> 
                            <?php if($sessionstaff['staffaccess']['AccessStaff']['rate_review']==1){ ?>
                            <li class="<?php if($title_for_layout=='PatientRateReview'){ echo "active"; }?>">
                                <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "PatientRateReview",
                                                                    "action" => "index"
                                                                    ));?>" title="Patient's Ratings/Reviews">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Patient's Ratings/Reviews
                                </a>

                                <b class="arrow"></b>
                            </li> 
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="<?php if($title_for_layout=='Staff' && $this->params['action']=='instructions'){ echo "active"; }?>">
                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Staff",
                                                                    "action" => "instructions"
                                                                    ));?>" title="instructions" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-info-circle"></i>
                            <span class="menu-text">Instructions</span>
                        </a>
                    </li>
<!--                    <li class="<?php if($title_for_layout=='Staff'  && $this->params['action']=='notifications'){ echo "active"; }?>">
                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Staff",
                                                                    "action" => "notifications"
                                                                    ));?>" title="notifications" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-bell"></i>
                            <span class="menu-text">Notifications</span>
                        </a>
                    </li>-->



                </ul><!-- /.nav-list -->

                <!-- #section:basics/sidebar.layout.minimize -->
                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    <i class="ace-icon fa fa-angle-double-right" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right" id="minNavigate"></i>
                </div>

                <!-- /section:basics/sidebar.layout.minimize -->
                <script type="text/javascript">
                    try {
                        ace.settings.check('sidebar_new', 'collapsed')
                    } catch (e) {
                    }
                </script>
            </div>

            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try {
                            ace.settings.check('breadcrumbs', 'fixed')
                        } catch (e) {
                        }
                    </script>

                    <ul class="breadcrumb">
						<?php if($title_for_layout=='PatientManagement' && $this->params['action']=='index'){ ?>    
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>
                        <li>

                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
                                                ));?>" class="active"  onclick="setSearch();"><?php
                                                
                                                
                                                if($dashboard==0){ ?> Search Patient <?php }else{ ?>Dashboard<?php } ?>
                            </a>
                        </li>
                                                <?php }elseif ($title_for_layout=='PatientManagement' && $this->params['action']!='index') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"recordpoint"
						));?>" class="active">Patient Management</a></li>

                                                <?php }elseif ($title_for_layout=='DocumentManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "documentManagement",
							"action"=>"index"
						));?>" class="active">Documents</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Document</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='index') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"index"

						));?>" class="active">Ways To Earn</a></li>


                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='edit') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"index"
						));?>" class="active">Ways To Earn</a></li>


                        <li class="active">Edit Promotion</li>

                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='custompromotion') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active"  onclick="setSearch();">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"custompromotion"
						));?>" class="active">Custom Promotions</a></li>

                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='add') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"index"
						));?>" class="active">Ways To Earn</a></li>

                        <li class="active">Add Custom Promotion</li>

                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='editcustom') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"custompromotion"
						));?>" class="active">Custom Promotions</a></li>

                        <li class="active">Edit Custom Promotion</li>


                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='ratereviewpromotion') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"ratereviewpromotion"

						));?>" class="active">Rating/Reviews Promotion</a></li>


                                                <?php }elseif ($title_for_layout=='PromotionManagement' && $this->params['action']=='editratereviewpromotion') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PromotionManagement",
							"action"=>"ratereviewpromotion"

						));?>" class="active">Rating/Reviews Promotion</a></li>
                        <li class="active">Edit Rating/Reviews Promotion</li>


                                                <?php }elseif ($title_for_layout=='UserManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "UserManagement",
							"action"=>"index"
						));?>" class="active">Users</a></li>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit User</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='StaffRewardManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRewardManagement",
							"action"=>"index"
						));?>" class="active">Rewards</a></li>
                                                <?php
                                                
                                                if($this->params['action']=='addlocalreward'){ ?>
                        <li class="active"><?php if(isset($rewardInfo['Reward'])){ if(isset($rewardInfo['Reward']['amazon_id']) && $rewardInfo['Reward']['amazon_id']!='' && $rewardInfo['Reward']['amazon_id']!=Null){ echo "View"; }else{ echo "Edit"; } }else{ echo "Add"; } ?>  My Office Reward</li>
                                                <?php } ?>

                                                <?php }elseif ($title_for_layout=='StaffProceInsureChareManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffProceInsureChareManagement",
							"action"=>"index"
						));?>" class="active">Practice Profile Extras</a></li>


                                                <?php }elseif (($title_for_layout=='ProductAndService' && $this->params['action']=='index') || ($title_for_layout=='ProductAndService' && $this->params['action']=='manageOrders') || ($title_for_layout=='ProductAndService' && $this->params['action']=='add') || ($title_for_layout=='ProductAndService' && $this->params['action']=='edit')) { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ProductAndService",
							"action"=>"index"
						));?>" class="active">Other Rewards</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Product/Service<?php if($access['milestone_reward']==1 && $access['product_service']==1) {?>/Coupon <?php }?></li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Product/Service<?php if($access['milestone_reward']==1 && $access['product_service']==1) {?>/Coupon <?php }?></li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='ProductAndService' && $this->params['action']=='BalanceStatus') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ProductAndService",
							"action"=>"BalanceStatus"
						));?>" class="active">Settings</a></li>

                                                <?php }elseif ($title_for_layout=='BankAccount') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "BankAccount",
							"action"=>"index"
						));?>" class="active">Other Rewards</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Bank Account</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Bank Account</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='UserStaffManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "UserStaffManagement",
							"action"=>"index"
						));?>" class="active">Staff</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Staff</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Staff</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='AdminSetting') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "AdminSetting",
							"action"=>"index"
						));?>" class="active">Referral Levels</a></li>

                                                <?php }elseif ($title_for_layout=='StaffReferralPromotionManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffReferralPromotionManagement",
							"action"=>"index"
						));?>" class="active">Referral Promotions</a></li>

                                                <?php }elseif ($title_for_layout=='Staff' && $this->params['action']=='instructions') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "Staff",
							"action"=>"instructions"
						));?>" class="active">Instructions</a></li>

                                                <?php }elseif ($title_for_layout=='Staff' && $this->params['action']=='notifications') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "Staff",
							"action"=>"notifications"
						));?>" class="active">Notifications</a></li>

                                                <?php }elseif ($title_for_layout=='ClinicLocation') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ClinicLocation",
							"action"=>"index"
						));?>" class="active">Clinic Location</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Location</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Location</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='StaffRedeem') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRedeem",
							"action"=>"index"
						));?>" class="active">Redemptions</a></li>
                                                <?php if($this->params['action']=='view'){ ?>
                        <li class="active">View</li>
                                                <?php } ?>

                                                <?php }elseif ($title_for_layout=='Doctor') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "Doctor",
							"action"=>"index"
						));?>" class="active">Doctors</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Doctor</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Doctor</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='LeadManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "LeadManagement",
							"action"=>"index"
						));?>" class="active">Referrals</a></li>


                                                <?php }elseif ($title_for_layout=='BasicReport') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "BasicReport",
							"action"=>"index"
						));?>" class="active">Basic Report</a></li>


                                                <?php }elseif ($title_for_layout=='ReviewManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ReviewManagement",
							"action"=>"index"
						));?>" class="active">Doctor-to-Doctor Reviews</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Review</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Review</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='PaymentDetail') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PaymentDetail",
							"action"=>"index"
						));?>" class="active">Payment History</a></li>

                                                <?php }elseif ($title_for_layout=='PointsHistory') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PointsHistory",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PointsHistory",
							"action"=>"index"
						));?>" class="active">Points History</a></li>

                                                <?php }elseif ($title_for_layout=='Settings') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "Settings",
							"action"=>"edit"
						));?>" class="active">Social Links</a></li>


                                                <?php }elseif ($title_for_layout=='LevelupPromotions') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>
                        <li>
                            <i ></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "LevelupPromotions",
							"action"=>"index"
						));?>" class="active">LevelUp Promotions</a>
                        </li>
                        <?php if($this->params['action']=='index'){ ?>
                        <li class="active">Editor</li>
                                                <?php } ?>
                        <?php if($this->params['action']=='promotions'){ ?>
                        <li class="active">Edit Promotions</li>
                                                <?php } ?>
                        <?php if($this->params['action']=='levelupsettings'){ ?>
                        <li class="active">Treatment Rewards Plans</li>
                                                <?php } ?>
                        <?php if($this->params['action']=='intervallevelupsettings'){ ?>
                        <li class="active">Interval Rewards Plans</li>
                                                <?php } ?>

                        <?php if($this->params['action']=='addsettings'){ ?>
                        <li>
                            <i ></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "LevelupPromotions",
							"action"=>"levelupsettings"
						));?>" class="active">Treatment Rewards Plans</a>
                        </li>
                        <li class="active">Add Treatment Plan</li>
                                                <?php } ?>
                        <?php if($this->params['action']=='addinterval'){ ?>
                        <li>
                            <i ></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "LevelupPromotions",
							"action"=>"intervallevelupsettings"
						));?>" class="active">Interval Rewards Plans</a>
                        </li>
                        <li class="active">Add Interval Plan</li>
                                                <?php } ?>
                        <?php if($this->params['action']=='edit'){ ?>
                        <li>
                            <i ></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "LevelupPromotions",
							"action"=>"promotions"
						));?>" class="active">Edit Promotions</a>
                        </li>
                        <li class="active">Edit Promotion</li>
                                                <?php } ?>

                                                <?php }elseif ($title_for_layout=='MilestoneReward') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "MilestoneReward",
							"action"=>"index"
						));?>" class="active">Milestone Reward</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Milestone Reward</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Milestone Reward</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='AcceleratedReward') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "AcceleratedReward",
							"action"=>"index"
						));?>" class="active">Accelerated Reward</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Accelerated Reward</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Accelerated Reward</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='StaffRewardProgram') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>
                        <?php if($this->params['action']=='index'){ ?>
                        <li class="active">Goals</li>
                        <?php } ?>
                        <?php if($this->params['action']=='add'){ ?>
                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRewardProgram",
							"action"=>"index"
						));?>" class="active">Goals</a></li>
                        <li class="active">Create Goal</li>

                        <?php } ?>
                        <?php if($this->params['action']=='edit'){ ?>
                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRewardProgram",
							"action"=>"index"
						));?>" class="active">Goals</a></li>
                        <li class="active">Update Goal</li>

                        <?php } ?>
                        <?php if($this->params['action']=='goalsettings'){ ?>
                        <li class="active">Goal Settings</li>

                        <?php } ?>
                        <?php if($this->params['action']=='addsetting'){ ?>
                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRewardProgram",
							"action"=>"goalsettings"
						));?>" class="active">Goal Settings</a></li>
                        <li class="active">Set Goal</li>

                        <?php }if($this->params['action']=='performancereport'){ ?>
                        <li class="active">Performance Report</li>

                        <?php } ?>
                        <?php if($this->params['action']=='currentweekreport'){ ?>
                        <li class="active">Current Week Report</li>

                        <?php } ?>


                                                <?php }elseif ($title_for_layout=='PatientRateReview') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientRateReview",
							"action"=>"index"
						));?>" class="active">Patient's Ratings/Reviews</a></li>
                                                <?php } ?>
                    </ul><!-- /.breadcrumb -->

                    <!-- #section:basics/content.searchbox -->
                    <div class="nav-search nav-search-new" id="nav-search">
                        <div class="assignCard">
                        <?php if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['staffaccess']['AccessStaff']['self_registration']==1 && $sessionstaff['staffaccess']['AccessStaff']['auto_assign']==1){ ?>
                            <form action="/PatientManagement/recordpoint" method="POST" name="quick_search_patient_form" id="quick_search_patient_form"  class="quickSearch quickSearch-dt">
                                <input type="hidden" name="action" value="selected_customer">
                                <input type="hidden" name="card_number" id="card_number" value="<?php echo $FreeCardDetails['card_number'];?>">
                                <input type="hidden" name="user_id" id="user_id" value="0">
                                <input type="hidden" name="quick_assign" id="quick_assign" value="1">

                                <input type="submit" value="Assign New Card" class="btn btn-minier">
                            </form>
<?php } ?>
                            <form  class="form-horizontal quickSearch quickSearch-dt-2" id="searchCustomer" action="<?=Staff_Name?>PatientManagement/recordpoint" method="post">


                                <span class="input-icon cst-adj fxt " > 
                                    <div class="checkbox">
                                    <?php if($sessionstaff['is_buzzydoc']==1){ ?>
                                        <label style='padding-left:0;'>




                                            <input type="checkbox" class="ace" name="ownclinic" id="ownclinic" <?php if(isset($sessionstaff['ownclinic']) && $sessionstaff['ownclinic']==0){ echo ""; }else{ echo "checked"; } ?>>
                                            <span class="lbl">&nbsp;My clinic search</span>

                                        </label>
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                            <i class="ace-icon fa fa-info-circle"></i>
                                        </a>
                                        <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                            <li class="dropdown-footer">
                                                Check this option if you would like to search for BuzzyDoc members who were issued the card from your office only.


                                            </li>
                                        </ul>
                                    <?php } ?>
                                    </div>
                                </span>
                                <span class="input-icon cst-adj-2" >


                                    <div class="form-group nomarg-left"><div class="col-sm-12 Clearfix nopad-left"><input type="text" placeholder="Scan Card or Find Patient using Card Number or Email..." name="customer_card" id="find_customer_textbox" class="col-xs-10 col-sm-13" required></div></div>

                                    <i class="ace-icon fa fa-search nav-search-icon"></i>
                                </span>
                            </form>
                        </div>
                    </div><!-- /.nav-search -->

                    <!-- /section:basics/content.searchbox -->
                </div>

                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">

                    <div class="page-content-area">
                        <div class="row">
                            <div class="form-group panel-default" id="patientSearchResults" style="display:none">


                                <div class="headBox panel-heading"> 

                                    <h1>Choose Patient <i>New/unregistered cards are in a list below, please scroll down to see</i></h1>

                                </div>
                                <div id="registered_patients" class="choosePatient">
                                    <h1>Registered Patient</h1>
                                </div>
                                <div id="unregistered_patients" class="choosePatient">
                                    <h1>New/unregistered cards</h1>
                                </div>

                                <form action="/PatientManagement/recordpoint" method="POST" name="search_patient_form" id="search_patient_form">

                                    <input type="hidden" name="action" value="selected_customer">
                                    <input type="hidden" name="card_number" id="card_number" value="">
                                    <input type="hidden" name="user_id" id="user_id" value="">
                                    <input type="hidden" name="quick_assign" id="quick_assign" value="0">

                                </form>
                            </div>

                            <div class="col-xs-12 innerContentBox">
                                <!-- PAGE CONTENT BEGINS -->
<?php echo $this->fetch('content'); ?>
                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content-area -->
                </div><!-- /.page-content -->
                <div class="footer">
                    <div class="footer-inner">
                        <!-- #section:basics/footer -->
                        <div class="footer-content">
                            <div class="bigger-120">
                                <span class="blue bolder">Contact Support:</span>
                                <span style="cursor: pointer" id="id-btn-dialog2"> help@buzzydoc.com ||</span>
                                <span>(888) 696-4753</span>
                            </div>
                        </div>

                        <!-- /section:basics/footer -->
                    </div>
                </div>
            </div><!-- /.main-content -->



            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->
<?php echo $this->element('footer_lightbox'); ?>
                <?php if(isset($sessionstaff['analytic_code']) && $sessionstaff['analytic_code']!=''){ echo $sessionstaff['analytic_code']; } ?>

        <div id="patient-loading-div" style="display:none;">
        </div>

        <script>
            function checkdoc() {
                if ("<?php echo $sessionstaff['haveDoc']; ?>" == 1) {
                    window.location.href = "/UserStaffManagement/paymentdetails";
                } else {
                    var r = confirm("Add super doctor before adding payment details.Do you want to proceed?");
                    if (r == true)
                    {
                        window.location.href = "/UserStaffManagement/add";
                    } else
                    {
                        return false;
                    }
                }
            }
        </script>
    </body>
</html>
<div class="modal fade" id="trainingModalMain" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog reedem-modalbox">
        <div class="modal-content">
            <div class="modal-header text-center">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="redeemclose();"><span aria-hidden="true" id="closeredeem" >&times;</span></button>

            </div>
            <div class="modal-body">
                <div class="text-center points-value-span-container1" id="embedtraining">

                </div>


            </div>



            <!--#endregion ThumbnailNavigator Skin End -->


        </div>
    </div>
</div>
<script>
    function readnotification(id) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Staff_Name.'PatientManagement/readnotification' ?>",
            data: {notification_id: id},
            success: function (msg) {
                if (msg == 1) {
                    location.reload();
                }
            }
        });
    }
    $("#trainingModalMain").on("hidden.bs.modal", function () {

        var _this = this,
                youtubeSrc = $(_this).find("iframe").attr("src");

        if ($(_this).find("iframe").length > 0) {                     // checking if there is iframe only then it will go to next level
            $(_this).find("iframe").attr("src", "");                // removing src on runtime to stop video
            $(_this).find("iframe").attr("src", youtubeSrc);        // again passing youtube src value to iframe
        }
    });

    jQuery(document).ready(function ($) {
         <?php if(isset($sessionstaff['search_from_api']) && $sessionstaff['search_from_api']!=''){ ?>
        self_search('<?php echo $sessionstaff['search_from_api']; ?>');
        function self_search(search_val) {
            $('#find_customer_textbox').val(search_val);
            if ($('#ownclinic').is(":checked") == true) {
                ownclinic = 1;
            } else {
                ownclinic = 0;
            }
            searchPatients(search_val, ownclinic, isBuzzyDoc, clinicId);
            return false;
        }
    <?php } ?>

        $(document).on("click", "#training-video", function () {
            var training_id = $(this).attr('data-points');
            var staff_id = '<?php echo $sessionstaff['staff_id']; ?>';
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "<?php echo Staff_Name.'PatientManagement/gettrainingvideo' ?>",
                data: {training_id: training_id, staff_id: staff_id},
                success: function (msg) {
                    if (msg.success == 0) {
                        alert('No Video Found!');
                    } else {
                        $("#embedtraining").html(msg.embed_code);
                        $('#trainingModalMain').modal().fadeIn(100);

                    }
                }
            });
        });
        $('#logout').on('click', function () {
            $.cookie('navigationOn', 0, {path: '/'});
            $.removeCookie('searchCust', {path: '/'});
        });
        if ($.cookie('navigationOn') == undefined || $.cookie('navigationOn') == 0) {
            $("#minNavigate").attr('class', 'ace-icon fa fa-angle-double-right');
            $("#sidebar").attr('class', 'sidebar responsive menu-min');
        } else if ($.cookie('navigationOn') == 1) {
            $("#minNavigate").attr('class', 'ace-icon fa fa-angle-double-left');
            $("#sidebar").attr('class', 'sidebar responsive');
        }
        $('#minNavigate').on('click', function () {
            getperformancereport();
            if ($.cookie('navigationOn') == undefined || $.cookie('navigationOn') == 0) {
                $('#minNavigate').removeClass('ace-icon fa fa-angle-double-right').addClass('ace-icon fa fa-angle-double-left');
                $('#sidebar').removeClass('sidebar responsive menu-min').addClass('sidebar responsive');
                //$.cookie('navigationOn', 1, {path: '/'});
            } else {
                $("#minNavigate").attr('class', 'ace-icon fa fa-angle-double-right');
                $("#sidebar").attr('class', 'sidebar responsive menu-min');
                //$.cookie('navigationOn', 0, {path: '/'});
            }
        });
    });
    $('#searchCustomer').on('submit', function () {
        var searchvalue = $('#find_customer_textbox').val();
        $.removeCookie('searchCust', {path: '/'});
        if (searchvalue != '') {
            $.cookie('searchCust', searchvalue, {path: '/'});
        }
        if ($('#ownclinic').is(":checked") == true) {
            ownclinic = 1;
        } else {
            ownclinic = 0;
        }
        searchPatients($('#find_customer_textbox').val(), ownclinic, isBuzzyDoc, clinicId);
        return false;
    });

    $('#find_customer_textbox').on('keyup', function () {
        return false;
    });

    function setsession(session) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Staff_Name.'PatientManagement/setsession' ?>",
            data: {session: session},
            success: function (msg) {
            }
        });
    }

    function setSearch() {
        $.removeCookie('searchCust', {path: '/'});
    }
</script>

