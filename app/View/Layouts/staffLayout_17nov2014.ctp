
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
		<?php if($title_for_layout=='UserStaffManagement'){
      $title_for_layout= 'StaffManagement';
   }
   ?>
        <link rel="shortcut icon" href="/img/favicon.ico">

        <title><?php echo $title_for_layout; ?></title>

        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<?php   $sessionstaff = $this->Session->read('staff');
                //echo $this->Html->css(CDN.'css/style.css');
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
                
                ?>


    </head>

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
						));?>" class="active logobox" >

<?php echo $this->html->image('lamparski_staff_new.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo','class'=>'img-responsive'));?>
                        </a>
<?php }else{  ?>

                        <a href="<?=Staff_Name?>staff/login/" class="active logobox">
<?php echo $this->html->image('lamparski_staff_new.png',array('title'=>'BuzzyDoc','alt'=>'BuzzyDoc logo','class'=>'img-responsive'));?>
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
                        <!--	<li class="grey">
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                                <i class="ace-icon fa fa-tasks"></i>
                                                
                                        </a>

                                        <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                                

                                                
                                                <li class="">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "DocumentManagement",
							    "action" => "index"
							));?>" title="Documents" class="icon-1 info-tooltip Doc">
                                        <i class="menu-icon fa fa-book"></i>
                                        <span class="menu-text">Documents</span>
                                </a>
                        </li>


                        <li class="">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "PromotionManagement",
							    "action" => "index"
							));?>" title="Promotions" class="icon-1 info-tooltip Promo">
                                        <i class="menu-icon fa fa-magic"></i>
                                        <span class="menu-text">Promotions</span>
                                </a>
                        </li>

                        <li class="">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "UserManagement",
							    "action" => "index"
							));?>" title="Users" class="icon-1 info-tooltip User">
                                        <i class="menu-icon fa fa-user"></i>
                                        <span class="menu-text">Users</span>
                                </a>
                        </li>
                        
                        <li class="">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "ProfileFieldManagement",
							    "action" => "index"
							));?>" title="Profile Fields" class="icon-1 info-tooltip Field">
                                        <i class="menu-icon fa fa-list-ul"></i>
                                        <span class="menu-text">Profile Fields</span>
                                </a>
                        </li>
                        <li class="">
                                <a href="<?php echo $this->Html->url(array(
							    "controller" => "StaffRewardManagement",
							    "action" => "index"
							));?>" title="Rewards" class="icon-1 info-tooltip Reward">
                                        <i class="menu-icon fa fa-cubes"></i>
                                        <span class="menu-text">Rewards</span>
                                </a>
                        </li>
                        
                        <li>
                                <a href="<?php echo $this->Html->url(array(
                                            "controller" => "UserStaffManagement",
                                            "action" => "index"
                                            ));?>" title="Staffs" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-users"></i>
                            <span class="menu-text">Staffs</span>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array(
                                        "controller" => "StaffRedeem",
                                        "action" => "index"
                                        ));?>" title="Redeem" class="icon-1 info-tooltip Doc">
                        <i class="menu-icon fa fa-exchange"></i>
                        <span class="menu-text">Redeems </span><?php if($sessionstaff['new_redeem']==1 && $sessionstaff['redeemcnt']>0){ ?><span class="badge badge-important"><?php echo $sessionstaff['redeemcnt']; ?></span><?php }else{ ?><span class="badge badge-important"><?php echo 0; ?> </span><?php } ?>
                        </a>
                        </li>
                        <li>
                                <a href="<?php echo $this->Html->url(array(
                                            "controller" => "LeadManagement",
                                            "action" => "index"
                                            ));?>" title="Lead" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-crosshairs"></i>
                            <span class="menu-text">Leads </span><?php if($sessionstaff['new_lead']==1 && $sessionstaff['refcnt']>0){ ?><span class="badge badge-success"><?php echo $sessionstaff['refcnt']; ?> </span><?php }else{ ?><span class="badge badge-success"><?php echo 0; ?> </span> <?php } ?>
                            </a>
                        </li>
                                        <?php if($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator'){ ?>
                        <li>
                                <a href="<?php echo $this->Html->url(array(
                                            "controller" => "AdminSetting",
                                            "action" => "index"
                                            ));?>" title="Admin Setting" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-cog"></i>
                            <span class="menu-text">Admin Setting</span>
                            </a>
                        </li>
                                        <?php } ?>
                        <li>
                                <a href="<?php echo $this->Html->url(array(
                                            "controller" => "BasicReport",
                                            "action" => "index"
                                            ));?>" title="Basic Report" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon ace-icon fa fa-bar-chart-o"></i>
                            <span class="menu-text">Basic Report</span>
                            </a>
                        </li>
                        <li>
                                <a href="<?php echo $this->Html->url(array(
                                            "controller" => "StaffReferralPromotionManagement",
                                            "action" => "index"
                                            ));?>" title="Promotion" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-trophy"></i>
                            <span class="menu-text">Referral Promotions</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url(array(
                                        "controller" => "Staff",
                                        "action" => "instructions"
                                        ));?>" title="instructions" class="icon-1 info-tooltip Doc">
                        <i class="menu-icon fa fa-info-circle"></i>
                        <span class="menu-text">Instructions</span>
                        </a>
                    </li>
                     <li>
                                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "ClinicLocation",
                                                                    "action" => "index"
                                                                    ));?>" title="Clinic Location" class="icon-1 info-tooltip Doc">
                                                    <i class="menu-icon fa fa-home"></i>
                                                    <span class="menu-text">Clinic Location</span>
                                                    </a>
                                                </li>
                                                <li>
                                                        <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "Doctor",
                                                                    "action" => "index"
                                                                    ));?>" title="Doctor" class="icon-1 info-tooltip Doc">
                                                    <i class="menu-icon fa fa-flask"></i>
                                                    <span class="menu-text">Doctor</span>
                                                    </a>
                                                </li>
                                                
                                        </ul>
                                </li>
                        -->

                        <li class="purple">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                                                                <?php if($sessionstaff['new_redeem']==1 && $sessionstaff['redeemcnt']>0){ ?><i class="ace-icon fa fa-bell icon-animated-bell"></i><span class="badge badge-important"><?php echo $sessionstaff['redeemcnt']; ?></span><?php }else{ ?> <i class="ace-icon fa fa-bell"></i><span class="badge badge-important"><?php echo 0; ?> </span><?php } ?>
                            </a>

                            <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-exclamation-triangle"></i>
									<?php if($sessionstaff['new_redeem']==1 && $sessionstaff['redeemcnt']>0){ ?><?php echo $sessionstaff['redeemcnt']; ?><?php }else{ ?> <?php echo 'No'; ?> <?php } ?> Notifications
                                </li>

                                                                <?php if($sessionstaff['redeemcnt']>0){
                                                                   foreach($sessionstaff['newredeem'] as $newred){ 
                                                                       ?>
                                <li>
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRedeem",
                                                                    "action" => "index"
                                                                    ));?>" title="Redeem" class="icon-1 info-tooltip Doc">
										<?php echo $newred['Transaction']['first_name'].' '.$newred['Transaction']['last_name'].' redeemed '.$newred['Transaction']['authorization']; ?>
                                    </a>
                                </li>

                                                                   <?php } ?>

                                <li class="dropdown-footer">
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "StaffRedeem",
                                                                    "action" => "index"
                                                                    ));?>" title="Redeem" class="icon-1 info-tooltip Doc">
                                        See all notifications
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                                                <?php } ?>

                            </ul>
                        </li>

                        <li class="green">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                                                                <?php if($sessionstaff['new_lead']==1 && $sessionstaff['refcnt']>0){ ?> <i class="ace-icon fa fa-envelope icon-animated-vertical"></i><span class="badge badge-success"><?php echo $sessionstaff['refcnt']; ?> </span><?php }else{ ?><i class="ace-icon fa fa-envelope"></i><span class="badge badge-success"><?php echo 0; ?> </span> <?php } ?>

                            </a>

                            <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-envelope-o"></i>
									<?php if($sessionstaff['new_lead']==1 && $sessionstaff['refcnt']>0){ ?> <?php echo $sessionstaff['refcnt']; ?> <?php }else{ ?><?php echo 'No'; ?> <?php } ?> Messages
                                </li>
                                                                <?php 
                                                                //echo "<pre>";print_r($sessionstaff['newref']);die;
                                                                if($sessionstaff['refcnt']>0){ ?>
                                <li class="dropdown-content">
                                    <ul class="dropdown-menu dropdown-navbar">
                                                                            <?php foreach($sessionstaff['newref'] as $newref){ ?>
                                        <li>
                                            <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Lead" class="icon-1 info-tooltip Doc">

                                                <span class="msg-body">
                                                    <span class="msg-title">

                                                                                        <?php echo $newref['Refer']['referrer'].' refer to '.$newref['Refer']['first_name'].' '.$newref['Refer']['last_name']; ?>
                                                    </span>


                                                </span>
                                            </a>
                                        </li>

                                                                            <?php } ?>
                                    </ul>
                                </li>

                                <li class="dropdown-footer">
                                    <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "index"
                                                                    ));?>" title="Lead" class="icon-1 info-tooltip Doc">
                                        See all messages
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                                                <?php } ?>
                            </ul>
                        </li>

                        <!-- #section:basics/navbar.user_menu -->
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">

                                <span class="user-info">

									<?php if(isset($sessionstaff['var']['staff_name']) && $this->params['action']!='login' && $this->params['action']!='basicreport'){ ?>
		  <?php echo $this->html->image('userpics.png',array('width'=>'18','height'=>'16','alt'=>'user','title'=>'user'));?>
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
            <div id="sidebar" class="sidebar responsive">


                <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-success">
                            <i class="ace-icon fa fa-signal"></i>
                        </button>

                        <button class="btn btn-info">
                            <i class="ace-icon fa fa-pencil"></i>
                        </button>

                        <!-- #section:basics/sidebar.layout.shortcuts -->
                        <button class="btn btn-warning">
                            <i class="ace-icon fa fa-users"></i>
                        </button>

                        <button class="btn btn-danger">
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
                    
                    <?php foreach($sessionstaff['access'] as $access){ 
                        $redeem='';
                        $lead='';
                        if($access=='Dashboard'){
                            $controller='PatientManagement';
                            $action='index';
                            $icon='fa-tachometer';
                        }
                        if($access=='Documents'){
                            $controller='DocumentManagement';
                            $action='index';
                            $icon='fa-book';
                        }
                        if($access=='Promotions'){
                            $controller='PromotionManagement';
                            $action='index';
                            $icon='fa-magic';
                        }
                        if($access=='Users'){
                            $controller='UserManagement';
                            $action='index';
                            $icon='fa-user';
                        }
                        if($access=='Profile Fields'){
                            $controller='ProfileFieldManagement';
                            $action='index';
                            $icon='fa-list-ul';
                        }
                        if($access=='Rewards'){
                            $controller='StaffRewardManagement';
                            $action='index';
                            $icon='fa-cubes';
                        }
                        if($access=='Staffs'){
                            $controller='UserStaffManagement';
                            $action='index';
                            $icon='fa-users';
                        }
                        if($access=='Redeems'){
                            $controller='StaffRedeem';
                            $action='index';
                            $icon='fa-exchange';
                        if($sessionstaff['new_redeem']==1 && $sessionstaff['redeemcnt']>0){ 
                            $redeem .='<span class="badge badge-important">'.$sessionstaff['redeemcnt'].'</span>';  
                            
                        }else{
                            $redeem .= '<span class="badge badge-important">0</span>';
                            } 
                        }
                        if($access=='Leads'){
                            $controller='LeadManagement';
                            $action='index';
                            $icon='fa-crosshairs';
                        if($sessionstaff['new_lead']==1 && $sessionstaff['refcnt']>0){ 
                            $lead .='<span class="badge badge-success">'.$sessionstaff['refcnt'].'</span>';  
                            
                        }else{
                            $lead .= '<span class="badge badge-success">0</span>';
                            } 
                        }
                        
                        if($access=='Admin Setting' && ($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator')){
                            $controller='AdminSetting';
                            $action='index';
                            $icon='fa-cog';
                        }
                        if($access=='Basic Report' ){
                            $controller='BasicReport';
                            $action='index';
                            $icon='fa-bar-chart-o ace-icon';
                        }
                        if($access=='Referral Promotions' ){
                            $controller='StaffReferralPromotionManagement';
                            $action='index';
                            $icon='fa-trophy';
                        }
                        if($access=='Instructions' ){
                            $controller='Staff';
                            $action='instructions';
                            $icon='fa-info-circle';
                        }
                        if($access=='Clinic Locations' ){
                            $controller='ClinicLocation';
                            $action='index';
                            $icon='fa-home';
                        }
                        if($access=='Doctors' && ($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator' || $sessionstaff['staff_role']=='Doctor')){
                            $controller='Doctor';
                            $action='index';
                            $icon='fa-flask';
                        }
                        ?>
                    <li class="">
                        <a href="<?php echo $this->Html->url(array(
							    "controller" => "$controller",
							    "action" => "$action",
                                                    1
							));?>" title="<?=$access?>" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa <?=$icon?>"></i>
                            <span class="menu-text"><?=$access?></span>
                            <?=$redeem?>
                            <?=$lead?>
                        </a>
                    </li>
                    <?php } ?>                                                                     
                </ul><!-- /.nav-list -->

                <!-- #section:basics/sidebar.layout.minimize -->
                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                </div>

                <!-- /section:basics/sidebar.layout.minimize -->
                <script type="text/javascript">
                    try {
                        ace.settings.check('sidebar', 'collapsed')
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
						));?>" class="active">Home</a>
                        </li>
                        <li>

                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
                                                ));?>" class="active"><?php
                                                
                                                
                                                if($sessionstaff['landing']==1 && $dashboard==0){ ?> Search Patient <?php }else{ ?>Dashboard<?php } ?>
                            </a>
                        </li>
                                                <?php }elseif ($title_for_layout=='PatientManagement' && $this->params['action']!='index') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
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
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "documentManagement",
							"action"=>"index"
						));?>" class="active">Documents</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Document</li>
                                                <?php } ?>
                                                <?php }elseif ($title_for_layout=='PromotionManagement') { ?>
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
						));?>" class="active">Promotions</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Promotion</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Promotion</li>
                                                <?php } ?>
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
                                                <?php }elseif ($title_for_layout=='ProfileFieldManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"index"
						));?>" class="active">Home</a>
                        </li>

                        <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ProfileFieldManagement",
							"action"=>"index"
						));?>" class="active">Profile Fields</a></li>
                                                <?php if($this->params['action']=='add'){ ?>
                        <li class="active">Add Profile Field</li>
                                                <?php } ?>
                                                <?php if($this->params['action']=='edit'){ ?>
                        <li class="active">Edit Profile Field</li>
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

                                                <?php }elseif ($title_for_layout=='StaffManagement') { ?>
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
						));?>" class="active">Staffs</a></li>
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
						));?>" class="active">Admin Setting</a></li>

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
						));?>" class="active">Redeems</a></li>
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
						));?>" class="active">Leads</a></li>


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


                                                <?php } ?>
                    </ul><!-- /.breadcrumb -->


                    <!-- #section:basics/content.searchbox -->
                    <div class="nav-search" id="nav-search">

                        <span class="input-icon">
                            <form  class="form-horizontal" id="searchCustomer" action="<?=Staff_Name?>PatientManagement/recordpoint" method="post">
                                <div class="form-group"><div class="col-sm-12"><input type="text" placeholder="Scan Card or Find Patient using Card Number, Name or Email..." name="customer_card" id="find_customer_textbox" class="col-xs-10 col-sm-13" required></div></div>
                            </form>
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>

                    </div><!-- /.nav-search -->

                    <!-- /section:basics/content.searchbox -->
                </div>

                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">

                    <div class="page-content-area">
                        <div class="row">
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
    </body>
</html>
