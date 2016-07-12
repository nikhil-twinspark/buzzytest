
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />

        <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
	<?php if($title_for_layout=='Redeem'){
      $title_for_layout1= 'Redemption';
   }
   if($title_for_layout=='AdminProfileFieldManagement'){
      $title_for_layout1= 'Profile field';
   }
   if($title_for_layout=='ClientManagement'){
      $title_for_layout1= 'Clients';
   }
   if($title_for_layout=='RewardManagement'){
      $title_for_layout1= 'Rewards';
   }
   if($title_for_layout=='GlobalUserManagement'){
      $title_for_layout1= 'Global User';
   }
   if($title_for_layout=='IndustryManagement'){
      $title_for_layout1= 'Industries';
   }
   if($title_for_layout=='ContestManagement'){
      $title_for_layout1= 'Contest';
   }
   if($title_for_layout=='LitePromotionManagement'){
      $title_for_layout1= 'Lite Promotions';
   }
   if($title_for_layout=='GlobalPromotionManagement'){
      $title_for_layout1= 'Global Promotions';
   }
   if($title_for_layout=='CharacteristicsInsurancesManagement'){
      $title_for_layout1= 'Characteristics / Insurances / Procedures';
   }
   if($title_for_layout=='BadgesManagement'){
      $title_for_layout1= 'Badges';
   }
   if($title_for_layout=='TangoAccountManagement'){
      $title_for_layout1= 'Tango Account';
   }
   if($title_for_layout=='EmailManagement'){
      $title_for_layout1= 'Email Management';
   }
   if($title_for_layout=='Admin' && $this->params['action']=='clinicfund'){
      $title_for_layout1= 'BuzzyDoc Bank';
   }
   if($title_for_layout=='Admin' && $this->params['action']=='managebalance'){
      $title_for_layout1= 'Manage Balance Status';
   }
   if($title_for_layout=='TrainingVideo'){
      $title_for_layout1= 'Training Video';
   }
   if($title_for_layout=='FailedTransaction'){
      $title_for_layout1= 'Failed Transaction';
   }
   if($title_for_layout=='Services'){
      $title_for_layout1 = 'Service Management';
   }
   ?>
        <title><?php echo $title_for_layout1; ?></title>

        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <?php
         $sessionAdmin = $this->Session->read('Admin');
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
        echo $this->Html->script(CDN.'js/jquery.cookie.js');
        ?>

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
                           <?php if (isset($sessionAdmin['loginName']) && $this->params['action'] != 'login' && $this->params['action'] != 'basicreport') { ?>
                        <a href="<?php
                                echo $this->Html->url(array(
                                    "controller" => "ClientManagement",
                                    "action" => "index"
                                ));
                                ?>" class="active logobox" onclick="setSearch();">

                            <?php echo $this->html->image(CDN.'img/lamparski_staff_new.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo', 'class' => 'img-responsive','height'=>'30px','width'=>'131px')); ?>
                        </a>
<?php } else { ?>

                        <a href="<?= Staff_Name ?>admin/login/" class="active logobox">
    <?php echo $this->html->image(CDN.'img/lamparski_staff_new.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo', 'class' => 'img-responsive','height'=>'30px','width'=>'131px')); ?>
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

                        <!-- #section:basics/navbar.user_menu -->
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">

                                <span class="user-info">

<?php if (isset($sessionAdmin['loginName']) && $this->params['action'] != 'login' && $this->params['action'] != 'basicreport') { ?>
    <?php echo $this->html->image(CDN.'img/userpics.png', array('width' => '18', 'height' => '16', 'alt' => 'user', 'title' => 'user')); ?>
                                    <small>Welcome,</small> <?php echo $sessionAdmin['loginName']; ?><?php } ?>
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                                <li>
                                    <a href="<?= Staff_Name ?>admin/logout/" id="logout" title="Logout">
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
                        <button class="btn btn-success" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "BadgesManagement",
							    "action" => "index",
                                                    1
                            ));?>'" title="Badges">
                            <i class="ace-icon fa fa-signal"></i>
                        </button>

                        <button class="btn btn-info" onclick="window.location.href = '<?php echo $this->Html->url(array("controller" => "ClientManagement","action" => "index"));?>';setSearch();" title="Clients">
                            <i class="ace-icon glyphicon glyphicon-search"></i>
                        </button>

                        <!-- #section:basics/sidebar.layout.shortcuts -->
                        <button class="btn btn-warning" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "GlobalUserManagement",
							    "action" => "index"
							));?>'" title="Global Users">
                            <i class="ace-icon fa fa-users"></i>
                        </button>

                        <button class="btn btn-danger" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "TangoAccountManagement",
							    "action" => "index"
							));?>'" title="Tango Account">
                            <i class="ace-icon fa fa-cogs"></i>
                        </button>
                        <button class="btn btn-danger" onclick="window.location.href = '<?php echo $this->Html->url(array(
							    "controller" => "Admin",
							    "action" => "clinicfund"
							));?>'" title="BuzzyDoc Bank">
                            <i class="ace-icon fa fa-briefcase"></i>
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
                <!--------------------------------Add by sahoo---------------------------------------------->
                <ul class="nav nav-list">
                    <li class="">
                        <a href="<?php  echo $this->Html->url(array("controller" => "ClientManagement","action" => "index")); ?>" title="Clients" class="icon-1 info-tooltip Doc" onclick="setSearch();">
                            <i class="menu-icon fa fa-tachometer"></i>
                            <span class="menu-text">Clients</span>
                        </a>
                    </li>



                    <li class="">
                        <a href="<?php echo $this->Html->url(array("controller" => "RewardManagement","action" => "index" )); ?>" title="Rewards" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-book"></i>
                            <span class="menu-text">Rewards</span>
                        </a>
                    </li>


                    <li class="">
                        <a href="<?php echo $this->Html->url(array("controller" => "Redeem", "action" => "index" )); ?>" title="Redemption" class="icon-1 info-tooltip Promo">
                            <i class="menu-icon fa fa-magic"></i>
                            <span class="menu-text">Redemption</span>
                        </a>
                    </li>

                    <li class="">
                        <a href="<?php echo $this->Html->url(array("controller" => "AdminProfileFieldManagement","action" => "index" )); ?>" title="Profile Fields" class="icon-1 info-tooltip User">
                            <i class="menu-icon fa fa-user"></i>
                            <span class="menu-text">Profile Fields</span>
                        </a>
                    </li>

                    <li class="">
                        <a href="<?php echo $this->Html->url(array("controller" => "GlobalUserManagement","action" => "index")); ?>" title="Global Users" class="icon-1 info-tooltip Field">
                            <i class="menu-icon fa fa-users"></i>
                            <span class="menu-text">Global Users</span>
                        </a>
                    </li>


                    <li class="">
                        <a href="<?php
           echo $this->Html->url(array("controller" => "IndustryManagement","action" => "index")); ?>" title="Industries" class="icon-1 info-tooltip Reward">
                            <i class="menu-icon fa fa-cubes"></i>
                            <span class="menu-text">Industries</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php  echo $this->Html->url(array("controller" => "ContestManagement","action" => "index" )); ?>" title="Contest" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-list-ul"></i>
                            <span class="menu-text">Contest</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "LitePromotionManagement","action" => "index")); ?>" title="Lite Promotions" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-exchange"></i>
                            <span class="menu-text">Lite Promotions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "GlobalPromotionManagement","action" => "index")); ?>" title="Global Promotions" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-exchange"></i>
                            <span class="menu-text">Global Promotions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "CharacteristicsInsurancesManagement","action" => "index")); ?>" title="Characteristics / Insurances / Procedures" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-asterisk"></i>
                            <span class="menu-text">Characteristics / Insurances / Procedures</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "BadgesManagement","action" => "index")); ?>" title="Badges" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-key"></i>
                            <span class="menu-text">Badges</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "TangoAccountManagement","action" => "index")); ?>" title="Tango Account" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-credit-card"></i>
                            <span class="menu-text">Tango Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "Admin","action" => "clinicfund")); ?>" title="BuzzyDoc Bank" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-briefcase"></i>
                            <span class="menu-text">BuzzyDoc Bank</span>
                        </a>
                    </li>
                    <?php if(DEBIT_FROM_BANK==1){ ?>
                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "Admin","action" => "managebalance")); ?>" title="BuzzyDoc Bank" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-briefcase"></i>
                            <span class="menu-text">Manage Balance Status</span>
                        </a>
                    </li>



                    <?php } ?>



                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "TrainingVideo","action" => "index")); ?>" title="Training Video" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-pencil-square-o"></i>
                            <span class="menu-text">Training Video</span>
                        </a>
                    </li>


                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "EmailManagement","action" => "index")); ?>" title="BuzzyDoc Bank" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-envelope"></i>
                            <span class="menu-text">Email Management</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "FailedTransaction","action" => "index")); ?>" title="Training Video" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-ban"></i>
                            <span class="menu-text">Failed Transaction</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $this->Html->url(array("controller" => "Services","action" => "add")); ?>" title="Service Test CRUD" class="icon-1 info-tooltip Doc">
                            <i class="menu-icon fa fa-ban"></i>
                            <span class="menu-text">Service Test CRUD</span>
                        </a>
                    </li>



                </ul>
                <!-------------------------------------------- /.nav-list -------------------------------------------------->

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
<?php //echo "dddddddddd : ".$title_for_layout ?>
<?php //echo "Action : ".$this->params['action'] ?>                
                    <ul class="breadcrumb">
    <?php if ($title_for_layout == 'ClientManagement' ) { ?>    
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array("controller" => "ClientManagement","action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "index" )); ?>" class="active" onclick="setSearch();">Clients
                            </a>
                        </li>
    <?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Client</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Client</li>
    <?php } ?>

   <?php if ($this->params['action'] == 'assigncard') { ?>
                        <li class="active">Assign card</li>
    <?php }if ($this->params['action'] == 'treatmentplans') { ?>
                        <li class="active">Level Up Treatment Plans</li>
    <?php } ?>
      <?php if ($this->params['action'] == 'addcard') {
          
          ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "assigncard",$this->request->pass[0] )); ?>" class="active">Assign card</a></li>
                        <li class="active">Add Card</li>
    <?php } ?>  

    <?php }   elseif ($title_for_layout == 'RewardManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array("controller" => "RewardManagement","action" => "index")); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array( "controller" => "RewardManagement","action" => "index")); ?>" class="active">Rewards</a>
                        </li>

    <?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Rewards</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') {
        if((isset($rewardInfo['Rewards']['amazon_id']) && ($rewardInfo['Rewards']['amazon_id']!=''))){?>
                        <li class="active">View Rewards</li>
    <?php }else{
        ?>
                        <li class="active">Edit Rewards</li>
    <?php
    }} ?>
    <?php } elseif ($title_for_layout == 'Redeem') { ?>

                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array("controller" => "Redeem","action" => "index")); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "Redeem","action" => "index" )); ?>" class="active">Redemption</a>
                        </li>
 <?php if ($this->params['action'] == 'view') { ?>
                        <li class="active">View</li>
    <?php } ?>



<?php } elseif ($title_for_layout == 'AdminProfileFieldManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "AdminProfileFieldManagement","action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "AdminProfileFieldManagement","action" => "index" )); ?>" class="active">Profile Fields</a>
                        </li>

    <?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Profile Field</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Profile Field</li>
    <?php } ?>
<?php } elseif ($title_for_layout == 'GlobalUserManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "GlobalUserManagement", "action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "GlobalUserManagement","action" => "index" )); ?>" class="active">Global Users</a>
                        </li>

        <?php if ($this->params['action'] == 'view') { ?>
                        <li class="active">Edit Profile</li>
       <?php } ?>

       <?php if ($this->params['action'] == 'cardinfo') { ?>
                        <li class="active">Card Info</li>
       <?php } ?>
            <?php if ($this->params['action'] == 'viewprofile') { ?>
                        <li class="active">User Profile</li>
    <?php } ?>



<?php } elseif ($title_for_layout == 'IndustryManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "IndustryManagement", "action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "index" )); ?>" class="active">Industries</a>
                        </li>

<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add New Industry</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Industry</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'manageleadlevel') { ?>
                        <li class="active">Manage Lead Level</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'addleadlevel') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "manageleadlevel",$this->request->pass[0] )); ?>" class="active">Manage Lead Level</a></li>
                        <li class="active">Add Lead Level</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'editleadlevel') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "manageleadlevel",$this->request->pass[0] )); ?>" class="active">Manage Lead Level</a></li>
                        <li class="active">Edit Lead Level</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'managepromotion') { ?>
                        <li class="active">Manage Promotion</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'addpromotion') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "managepromotion",$this->request->pass[0] )); ?>" class="active">Manage Promotion</a></li>
                        <li class="active">Add Promotion</li>
    <?php } ?>

      <?php if ($this->params['action'] == 'editpromotion') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "managepromotion",$this->request->pass[0] )); ?>" class="active">Manage Promotion</a></li>
                        <li class="active">Edit Promotion</li>
    <?php } ?>  
    <?php if ($this->params['action'] == 'referralpromotion') { ?>
                        <li class="active">Referral Promotion</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'addreferralpromotion') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "referralpromotion",$this->request->pass[0] )); ?>" class="active">Referral Promotion</a></li>
                        <li class="active">Add Referral Promotion</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'editreferralpromotion') { ?>
                        <li class="active"><a href="<?php echo $this->Html->url(array("controller" => "IndustryManagement", "action" => "referralpromotion",$this->request->pass[0] )); ?>" class="active">Referral Promotion</a></li>
                        <li class="active">Edit Referral Promotion</li>
    <?php } ?>

<?php } elseif ($title_for_layout == 'ContestManagement') { ?>

                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ContestManagement", "action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ContestManagement", "action" => "index" )); ?>" class="active">Contest</a>
                        </li>
 <?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Contest</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Contest</li>
    <?php } ?>
<?php } elseif ($title_for_layout == 'LitePromotionManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "LitePromotionManagement","action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php  echo $this->Html->url(array("controller" => "LitePromotionManagement", "action" => "index"));?>" class="active">Lite Promotions</a>
                        </li>
 <?php if ($this->params['action'] == 'addpromotion') { ?>
                        <li class="active">Add Lite Promotion</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'editpromotion') { ?>
                        <li class="active">Edit Lite Promotion</li>
    <?php } ?>
<?php }elseif ($title_for_layout == 'GlobalPromotionManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "GlobalPromotionManagement","action" => "index" )); ?>" class="active">Home</a>
                        </li>

                        <li>
                            <a href="<?php  echo $this->Html->url(array("controller" => "GlobalPromotionManagement", "action" => "index"));?>" class="active">Global Promotions</a>
                        </li>
 <?php if ($this->params['action'] == 'addpromotion') { ?>
                        <li class="active">Add Global Promotion</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'editpromotion') { ?>
                        <li class="active">Edit Global Promotion</li>
    <?php } ?>
<?php }elseif ($title_for_layout == 'CharacteristicsInsurancesManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "CharacteristicsInsurancesManagement","action" => "index" )); ?>" class="active">Home</a>
                        </li>
                        <li>
                            <a href="<?php  echo $this->Html->url(array("controller" => "CharacteristicsInsurancesManagement", "action" => "index"));?>" class="active">Characteristics / Insurances / Procedures</a>
                        </li>
<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add</li>
    <?php } ?>
    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit</li>
       <?php } ?>
<?php }elseif ($title_for_layout == 'BadgesManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement","action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>
                        <li>
                            <a href="<?php  echo $this->Html->url(array("controller" => "BadgesManagement", "action" => "index"));?>" class="active">Badges</a>
                        </li>
<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add</li>
    <?php } ?>
    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit</li>
       <?php } ?>
<?php }elseif ($title_for_layout == 'TangoAccountManagement') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "TangoAccountManagement", "action" => "index" )); ?>" class="active">Tango Account</a>
                        </li>

<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Tango Account</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Tango Account</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'addfund') { ?>
                        <li class="active">Add Fund</li>
    <?php } ?>





<?php }elseif ($title_for_layout=='Admin' && $this->params['action']=='clinicfund') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "Admin", "action" => "clinicfund" )); ?>" class="active">BuzzyDoc Bank</a>
                        </li>

                                                <?php }elseif ($title_for_layout=='Admin' && $this->params['action']=='managebalance') { ?>
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>

                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "Admin", "action" => "managebalance" )); ?>" class="active">Manage Balance Status</a>
                        </li>


                                                <?php }elseif ($title_for_layout == 'EmailManagement') { ?>

                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?php echo $this->Html->url(array( "controller" => "ClientManagement", "action" => "index" )); ?>" class="active" onclick="setSearch();">Home</a>
                        </li>

                        <li>

                            <a href="<?php echo $this->Html->url(array("controller" => "EmailManagement", "action" => "index" )); ?>" class="active">Email Management</a>
                        </li>

<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Email Template</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Email Template</li>
    <?php } ?>


<?php }elseif ($title_for_layout == 'TrainingVideo') { ?>
<li>
                            <a href="<?php echo $this->Html->url(array("controller" => "TrainingVideo", "action" => "index" )); ?>" class="active">Training Video</a>
                        </li>

<?php if ($this->params['action'] == 'add') { ?>
                        <li class="active">Add Training Video</li>
    <?php } ?>

    <?php if ($this->params['action'] == 'edit') { ?>
                        <li class="active">Edit Training Video</li>
    <?php } ?>
                        <?php if($this->params['action']=='watched'){ ?>
                        <li class="active">Watched List</li>
                                                <?php } ?>
<?php }elseif ($title_for_layout == 'FailedTransaction') { ?>
<li>
                            <a href="<?php echo $this->Html->url(array("controller" => "FailedTransaction", "action" => "index" )); ?>" class="active">Failed Transaction</a>
                        </li>

<?php } ?>



                    </ul><!-- /.breadcrumb -->
                    <?php if($sessionAdmin['aval_bal']<500){ ?>
                    <div style="background-color:transparent;display:inline-block;line-height:20px;margin:10px 0px 0 0px;padding:0;font-size:18px;color:#333;float: right;">
                        <a href="<?php echo $this->Html->url(array("controller" => "TangoAccountManagement", "action" => "index" )); ?>" class="active" style="color:red;">Your available balance is low.Your fund should be $500 and above.</a>
                    </div>
                    <?php } ?>

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
                        <div class="footer-content">
                            <div class="bigger-120">
                                <!--<span class="blue bolder">Contact Support:</span>
                                <span style="cursor: pointer" id="id-btn-dialog2"> help@buzzydoc.com ||</span>
                                <span>(888) 696-4753</span>-->
                            </div>
                        </div>


                    </div>
                </div>
            </div><!-- /.main-content -->



            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->
    </body>
</html>
<script>
function setSearch() {
        $.removeCookie('serachVal', {path: '/'});
    }
</script>