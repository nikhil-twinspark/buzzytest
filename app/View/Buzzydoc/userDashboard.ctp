<link href="/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="/fileinput/js/fileinput.min.js"></script>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div id="dialogUploadPic" class="user-dialogUpload" style="display:none;">
    <div id="tabs" style="height: 279px;">
        <ul>
            <li><a href="#tabs-1">Upload From Facebook</a></li>
            <li><a href="#tabs-2">Upload From PC</a></li>
            <li><a href="#tabs-3">Choose From Basket</a></li>
        </ul>

        <div id="tabs-1" class="tab-FB-button" style="margin:0 auto;width:100px;">
            <fb:login-button scope="public_profile,email"
                             onlogin="checkLoginState();">
            </fb:login-button>
        </div>

        <div id="tabs-2" class="tab-browse-button">
            <input id="fileUploadPc" type="file" multiple=true class="file-loading">
        </div>

        <div id="tabs-3" class="tab-images-button">
            <div class="user-images">
                <?php
                foreach ($userDashboard['userDetail']['stock_images'] as $val) {
                    ?>
                    <img src ="<?php echo S3Path.$val['url']; ?>" alt="user">
                    <?php
                }
                ?>
            </div>

        </div>

    </div>


</div>

<?php 
echo $this->Html->css(CDN.'css/assets/buzzydoc-user/select2.css');
echo $this->Html->css(CDN.'css/assets/buzzydoc-user/datepicker.css');
echo $this->Html->css(CDN.'css/assets/buzzydoc-user/bootstrap-editable.css');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.fileinput.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/fuelux/fuelux.spinner.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/date-time/bootstrap-datepicker.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.scroller.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.fileinput.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/ace/elements.spinner.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/date-time/moment.js');
echo $this->Html->script(CDN.'js/timeago.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/x-editable/bootstrap-editable.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/x-editable/ace-editable.js');
echo $this->Html->script(CDN.'js/assets/buzzydoc-user/select2.js');
echo $this->Html->script(CDN.'js/userDashboard.js');
echo $this->Html->script(CDN.'js/jssor.js');
echo $this->Html->script(CDN.'js/jssor.slider.js');
$sessionbuzzy = $this->Session->read('userdetail');
?>
<style>
    .panel {
        border: 1px solid #2fb889;
    }
    /* jssor slider thumbnail navigator skin 12 css */
    /*
    .jssort12 .p            (normal)
    .jssort12 .p:hover      (normal mouseover)
    .jssort12 .pav          (active)
    .jssort12 .pav:hover    (active mouseover)
    .jssort12 .pdn          (mousedown)
    */
    .jssort12 {
        position: absolute;
        width: 500px;
        height: 30px;
    }

    .jssort12 > div {
        left: 0 !important;
    }

    .jssort12 .w {
        cursor: pointer;
        position: absolute;
        WIDTH: 99px;
        HEIGHT: 28px;
        border: 0;
        top: 0px;
        left: -1px;
        background:#435464;
    }

    .jssort12 .p {
        position: absolute;
        width: 100px;
        height: 30px;
        top: 0;
        left: 0;
        padding: 0px;
    }

    .jssort12 .pav .w, .jssort12 .pdn .w {
        border-bottom: 1px solid #fff;
    }

    .jssort12 .c {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        line-height: 28px;
        text-align: center;
        color: #fff;
        font-size: 13px;
    }

    .jssort12 .p .c, .jssort12 .pav:hover .c {
        /* background:#2FB889;*/
    }

    .jssort12 .pav .c, .jssort12 .p:hover .c {
        background:#2FB889;
    }

    .redeemContainer {
    }
    .redeemBox {
        box-sizing:border-box;
        -webkit-box-sizing:border-box;
        -mox-box-sizing:border-box;
        -o-box-sizing:border-box;
        float:left;
        padding:15px;
        background: none repeat scroll 0 0 #F9F9F9;
        float: left;
        margin: 0.5%;
        padding: 10px;
        width: 31.2%;
    }
    .redeemClear {
        clear:both;
    }
#redeem_container{
    width:100% !important;
}
    .redeemBox .productPoints {
        color: #435464;
        display: block;
        font: 20px "FuturaW01-ExtraBoldCond_774896";
    }
    .needRedeemPoints {
        font-size: 16px;
        height: 44px;
        padding: 12px 0;
    }
    .redeemBox h3 {
        color: #A7A7A7;
        display: inline-block;
        font: 16px "FuturaW01-MediumCondens";

        line-height: 18px;
        min-height: 40px;
        overflow: hidden;
    }
    .redeemButtom button {
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        transition: background-color 0.15s ease 0s, border-color 0.15s ease 0s, opacity 0.15s ease 0s;
        vertical-align: middle;
        background-color: #87B87F !important;
        border: 5px solid #87B87F;
        color: #FFFFFF;
        cursor: pointer;
        width:100%;
        padding: 4px 9px;
        margin-top:7px;
    }
    .redeemButtom button:hover {
        background-color: #629B58 !important;
        border-color: #87B87F;
    }
</style>
<?php
$userClinics = $this->Session->read('userClinics');
?>
<div class="main-content main-content-inner margin-top-small">
    <div class="main-content-inner">
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">

            <div class="page-header">
                <h1>
                    <?= $userDashboard['userDetail']['name'] . '\'s Profile'; ?>
                    <small> <i class="ace-icon fa fa-angle-double-right"></i>
                        Profile <span id="breadcom_home"> <i
                                class="ace-icon fa fa-angle-double-right"></i>View Profile
                        </span>

                    </small>
                </h1>
            </div>
            <!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="clearfix">
                        <div class="alert alert-success no-margin">
                            <!--  <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>-->
                            <i class="ace-icon fa fa-pencil-square-o blue"></i> Click on the
                            image below or edit profile to edit each field.
                        </div>
                    </div>

                    <div class="hr dotted"></div>

                    <div class="">
                        <div class="col-xs-12 col-sm-3 center">
                            <div>
                                <!-- #section:pages/profile.picture -->
                                <span class="profile-picture" id="choose_pic">
                                    <?php
                                    if (!empty($userDashboard['userDetail']['profile_img_url'])) {
                                    $profile_img=explode('graph.facebook.com',$userDashboard['userDetail']['profile_img_url']);
                                        if(count($profile_img)>1){
                                        $primg=$userDashboard['userDetail']['profile_img_url'];
                                        }else{
                                        $primg=S3Path.$userDashboard['userDetail']['profile_img_url'];
                                        }
                                        ?>
                                        <img
                                            width="200px" height="200px"
                                            class="editable img-responsive editable-click editable-empty"
                                            alt="Photo" title="BuzzyDoc"
                                            src="<?php echo $primg; ?>"
                                            style="display: block;"> </img>
                                        <?php } else { ?>
                                       <img
                                            width="200px" height="200px"
                                            class="editable img-responsive editable-click editable-empty"
                                            alt="Photo" title="BuzzyDoc"
                                            src="<?php echo CDN; ?>img/buzzydoc-user/avatars/profile-pic.jpg"
                                            style="display: block;">
                                    <?php }
                                    ?></span>
                                <input type="hidden" id="user-email"
                                       value="<?= $userDashboard['userDetail']['email']; ?>"> <input
                                       type="hidden" id="user-id"
                                       value="<?= $userDashboard['userDetail']['id']; ?>"> <input
                                       type="hidden" id="ren"
                                       value="<?= $userDashboard['userDetail']['ren']; ?>"> <input
                                       type="hidden" id="user-state"
                                       value="<?= $userDashboard['userDetail']['state']; ?>"> <input
                                       type="hidden" id="user-city"
                                       value="<?= $userDashboard['userDetail']['city']; ?>">
                                <!-- /section:pages/profile.picture -->
                                <div class="space-4"></div>

                                <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                    <div class="inline position-relative">
                                        <a href="javascript:void(0)"
                                           class="user-title-label dropdown-toggle"
                                           data-toggle="dropdown"> &nbsp; <span class="white"
                                                                             id="maintextname"><?php echo ((strlen($userDashboard['userDetail']['fName']) > 15) ? substr($userDashboard['userDetail']['fName'], 0, 15) . '...' : $userDashboard['userDetail']['fName']); ?> </span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="space-6"></div>

                            <!-- #section:pages/profile.contact -->
                            <div class="profile-contact-info">
                                <div class="profile-contact-links align-center">
                                    <span id="edit-profile" class="btn btn-link"> Edit Profile </span>
                                </div>
                            </div>
                            <div>&nbsp;</div>
                            <div class="profile-contact-info">
                                <div class="profile-contact-links align-center">
                                    <span> Treatment in-progress </span>
                                </div>
                            </div>
                            <?php if (!empty($visithistory)) { ?>


                                <!-- #section:elements.tab.position -->
                                <div class="tabbable tabs-left">


                                    <div class="tab-content">
                                        <?php
                                        $nv = 1;
                                        $phase1 = '';
                                        $phase2 = '';
                                        $phase3 = '';
                                        $vi1 = 0;
                                        foreach ($visithistory as $data => $vhistory) {
                                            ?>
                                            <div id="clinic_graph_<?php echo $vhistory['clinic_id']; ?>" style="border-bottom:1px solid #009926;">

                                                <div class="page-header">
                                                    <h1 style="font-size: 14px !important;"> <b><?php echo $vhistory['clinic_name']; ?> - <?php echo $data; ?></b></h1>
                                                </div>

                                                <?php
                                                $levecomp = 0;
                                                foreach ($vhistory['record'] as $vs) {
                                                    if ($vs['perfect'] == 'Perfect')
                                                        $levecomp++;
                                                }
                                                $totalcomp = $levecomp;
                                               
                                                //calculation for interval reward plan.
                            if($vhistory['treatment_details']['interval']==1){
                             $totalvisitcomp=($vhistory['interval_details']['Visit']*100)/$vhistory['treatment_details']['total_visits'];
                            }else{
                            $totalvisitcomp=($totalcomp*100)/$vhistory['treatment_details']['total_visits'];
                            }
                                                ?>
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="sample-table-1" class="table table-striped table-bordered table-hover" >
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <!-- #section:elements.progressbar -->
                                                                <?php
                                                                $p = 0;
                                                                $totalremainvisit = 0;
                                                                foreach ($vhistory['treatment_details']['phase_distribution'] as $phaseset) {
                                                                    if ($p == 0)
                                                                        $css = ' progress-bar-success';
                                                                    if ($p == 1)
                                                                        $css = ' progress-bar-warning';
                                                                    if ($p == 2)
                                                                        $css = ' progress-bar-purple';
                                                                    if ($p == 3)
                                                                        $css = ' progress-danger';
                                                                    if ($p == 4)
                                                                        $css = ' progress-bar-pink';
                                                                        
                                                                    if ($levecomp > $phaseset['PhaseDistribution']['visits'] && $levecomp > 0) {
                                                                        $phase = 100;
                                                                        $levecomp = $levecomp - $phaseset['PhaseDistribution']['visits'];
                                                                        $mesg = 'You got ' . $phaseset['PhaseDistribution']['points'] . ' points and badge - ' . $phaseset['PhaseDistribution']['badge_name'] . ' on completion of this phase';
                                                                    } else if ($levecomp == $phaseset['PhaseDistribution']['visits'] && $levecomp > 0) {
                                                                        $phase = 100;
                                                                        $levecomp = $levecomp - $phaseset['PhaseDistribution']['visits'];
                                                                        $mesg = 'You got ' . $phaseset['PhaseDistribution']['points'] . ' points and badge - ' . $phaseset['PhaseDistribution']['badge_name'] . ' on completion of this phase';
                                                                    } else if ($levecomp > 0) {
                                                                        $phase = ($levecomp * 100) / $phaseset['PhaseDistribution']['visits'];
                                                                        $levecomp = $levecomp - $phaseset['PhaseDistribution']['visits'];
                                                                        $remainvisit = explode('-', $levecomp);
                                                                        $totalremainvisit = $totalremainvisit + (int) $remainvisit;
                                                                        
                                                                            $mesg = 'Receive ' . $phaseset['PhaseDistribution']['points'] . ' points and ' . $phaseset['PhaseDistribution']['badge_name'] . ' after ' . $totalremainvisit . ' more Perfect Visits!';
                                                                        
                                                                    }else {
                                                                        $phase = 0;
                                                                        $levecomp = 0;
                                                                        $totalremainvisit = $totalremainvisit + $phaseset['PhaseDistribution']['visits'];
                                                                        $mesg = 'Receive ' . $phaseset['PhaseDistribution']['points'] . ' points and ' . $phaseset['PhaseDistribution']['badge_name'] . ' after ' . $totalremainvisit . ' more Perfect Visits!';
                                                                    }
                                                                    
                                                                    if($vhistory['treatment_details']['interval']==1){
                                $phase=$totalvisitcomp;
                                $phase_name=$vhistory['interval_details']['Phase'];
                                $mesg = 'Receive ' . $phaseset['PhaseDistribution']['points'] . ' points and ' . $phaseset['PhaseDistribution']['badge_name'] . ' after ' .($vhistory['treatment_details']['total_visits']-$vhistory['interval_details']['Visit']). ' more Perfect Visits!';
                            }else{
                                $phase_name=$phaseset['PhaseDistribution']['phase_name'];
                            }
                                                                    ?>
                                                                    <?php echo $mesg; ?>
                                                                    <div class="progress pos-rel" data-percent="<?php echo $phase_name; ?> (<?php echo round($phase, 1); ?>%) ">

                                                                        <div class="progress-bar<?php echo $css; ?>" style="width:<?php echo $phase; ?>%;"></div>

                                                                    </div>
                                                                    <?php
                                                                    if ($p == 4)
                                                                        $p = 0;
                                                                    $p++;
                                                                }$p = 0;
                                                                ?>
                                                            </div><!-- /.col -->
                                                        </div>
                                                    </div>
                                                            </table>

                                                            <?php $nv++; ?></div>


                                                        <?php $vi1++;
                                                    }
                                                    ?>
                                                </div>
                                        </div>

                                        <!-- /section:elements.tab.position -->




                                    <?php }else { ?>
                                        <div class="tab-content" style="color:red;">No Treatment In Progress</div>
<?php } ?>



                                    <!-- /section:pages/profile.contact -->


                                    <!-- /section:custom/extra.grid -->
                                    <div class="hr hr16 dotted"></div>
                                </div>
                                <div class="tabbable">
                                    <div class="col-xs-12 col-sm-9">
                                        <div class="center">
                                            <a data-toggle="tab" href="#user-profile" id="tab-profile" onclick="{
                                                $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>View Profile');
                                                $('#vprofile').addClass('highlight');
                                                $('#bpoint').removeClass('highlight');
                                                $('#ubadge').removeClass('highlight');
                                                $('#ureward').removeClass('highlight');
                                                $('#uearn').removeClass('highlight');
                                                $('#ucheckins').removeClass('highlight');
                                                $('#usaved').removeClass('highlight');
                                            }" style="display:none;">
                                        <span class="btn btn-app btn-sm btn-purple no-hover" id="vprofile">
                                            <span class="line-height-1 bigger-130"> View </span>
                                            <br />
                                            <span class="line-height-1 smaller-90"> Profile </span>
                                        </span>
                                    </a>

                                            <a data-toggle="tab" href="#buzzy-points" id="tab-points" title="<?= $userDashboard['userDetail']['totalPointsShort']; ?>" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Redeem');
                                                        $('#bpoint').addClass('highlight');
                                                        $('#ubadge').removeClass('highlight');
                                                        $('#ureward').removeClass('highlight');
                                                        $('#uearn').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm btn-success no-hover" id="bpoint" >
                                                    <span class="line-height-1 bigger-70" > <?= $userDashboard['userDetail']['totalPointsShort']; ?> Points</span>

                                                    <br />
                                                    <input type="hidden" id="getpointcnt" name="getpointcnt" value="0">
                                                    <span class="line-height-1 smaller-90" id="midpoint"> Redeem </span>
                                                </span>
                                            </a>

                                            <a data-toggle="tab" href="#badges" id="tab-badges" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Badges');
                                                        $('#ubadge').addClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ureward').removeClass('highlight');
                                                        $('#uearn').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm no-hover" id="ubadge">
                                                    <span class="line-height-1 bigger-130"> <?php if($userDashboard['userDetail']['totalBadges']>5){ echo '5'; }else{ echo $userDashboard['userDetail']['totalBadges']; } ?> </span>

                                                    <br />
                                                    <span class="line-height-1 smaller-90"> Badges </span>
                                                </span>
                                            </a>
                                            <a data-toggle="tab" href="#buzzy_rewards" id="tab-reward" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Rewards');
                                                        $('#ureward').addClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ubadge').removeClass('highlight');
                                                        $('#uearn').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm no-hover" id="ureward" >
                                                    <span class="line-height-1 bigger-130 spanimg">
                                                    <img class="clinic-image" src="<?php echo CDN; ?>img/icons2_new.png">
 </span>

                                                    <br />
                                                    <span class="line-height-1 smaller-90"> Rewards </span>
                                                    <br />
                                                </span>
                                            </a>
                                            <a data-toggle="tab" href="#earn" id="tab-earn" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Earn');
                                                        $('#uearn').addClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ubadge').removeClass('highlight');
                                                        $('#ureward').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm no-hover" id="uearn" >


                                                    <span class="line-height-1 bigger-130 spanimg"> <img class="clinic-image" src="<?php echo CDN; ?>img/icons1_new.png"> </span>
                                                    <br />
                                                    <span class="line-height-1 smaller-90"> Earn </span>


                                                </span>
                                            </a>






                                            <a data-toggle="tab" href="#checkins"  id="tab-checkins" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Check-ins');
                                                        $('#ucheckins').addClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ubadge').removeClass('highlight');
                                                        $('#ureward').removeClass('highlight');
                                                        $('#uearn').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm btn-pink no-hover" id="ucheckins">
                                                    <span class="line-height-1 bigger-130"><?= $userDashboard['userDetail']['totalCheckIns']; ?> </span>

                                                    <br />
                                                    <span class="line-height-1 smaller-90"> Check-ins </span>
                                                </span>
                                            </a>

                                            <a data-toggle="tab" href="#saved" id="tab-saved" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>My Practices');
                                                        $('#usaved').addClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ubadge').removeClass('highlight');
                                                        $('#ureward').removeClass('highlight');
                                                        $('#uearn').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#vprofile').removeClass('highlight'); 
                                                    }">
                                                <span class="btn btn-app btn-sm btn-grey no-hover" id="usaved">
                                                    <span class="line-height-1 bigger-130" id="svdoc"> <?= $userDashboard['userDetail']['totalSaved']; ?> </span>

                                                    <br />
                                                    <span class="line-height-1 smaller-90 check-ins-heading"> My Practices

                                                    </span>
                                                </span>
                                            </a>

                                        

                                        </div>
                                        <div class="hr hr-8 dotted"></div>
                                        <div class="tab-content no-border">
                                            <div id="user-profile" class="tab-pane active">
                                                <div class="user-profile-data-container">
                                                    <form id="user_profile_id">
                                                        <div class="row">
                                                            <div class="col-lg-3 user-profile-headings">
                                                                <span class="heading">User Name</span>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>First Name</span>
                                                                    </div>
                                                                    <div class="col-lg-9 fname-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['fName']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Last Name</span>
                                                                    </div>
                                                                    <div class="col-lg-9 lname-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['lName']; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="hr hr-8 dotted"></div>
                                                        <div class="row">
                                                            <div class="col-lg-3 user-profile-headings">
                                                                <span class="heading">Location</span>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Address1</span>
                                                                    </div>
                                                                    <div class="col-lg-9 add1-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['address1']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span>&nbsp;&nbsp;</span>Address2</span>
                                                                    </div>
                                                                    <div class="col-lg-9 add2-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['address2']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>State</span>
                                                                    </div>
                                                                    <div class="col-lg-9 state-container">
                                                                        <span class="span-value-medium" id="statedd"><?= (!empty($userDashboard['userDetail']['state'])) ? $userDashboard['userDetail']['state'] : '&nbsp;&nbsp;&nbsp;&nbsp;'; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>City</span>
                                                                    </div>
                                                                    <div class="col-lg-9 city-container">
                                                                        <span class="span-value-medium" id="city"><?= (!empty($userDashboard['userDetail']['city'])) ? $userDashboard['userDetail']['city'] : '&nbsp;&nbsp;&nbsp;&nbsp;'; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Zip/Postal</span>
                                                                    </div>
                                                                    <div class="col-lg-9 zip-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['zip']; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="hr hr-8 dotted"></div>
                                                        <div class="row">
                                                            <div class="col-lg-3 user-profile-headings">
                                                                <span class="heading">Basic Info</span>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Contact Number</span>
                                                                    </div>
                                                                    <div class="col-lg-9 contact-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['contactNumber']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row dob-outer-container">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Date of Birth</span>
                                                                    </div>
                                                                    <div class="col-lg-9 dob-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['dob']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Age</span>
                                                                    </div>
                                                                    <div class="col-lg-9 age-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['age']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class='gender-container'>
                                                                        <div class="col-lg-3">
                                                                            <span class="span-heading-medium"><span
                                                                                    style="color: #FF0000;">*</span>Gender</span>
                                                                        </div>
                                                                        <div class="col-lg-9 gender-container">
                                                                            <span class="span-value-medium"><?= $userDashboard['userDetail']['gender']; ?></span>
                                                                            <div class="editGen hide">
                                                                                <div class="radio">
                                                                                    <label><input type="radio" name="genradio"
                                                                                                  value="male"
<?= ($userDashboard['userDetail']['gender'] == "male") ? 'checked' : ''; ?>>Male</label>
                                                                                    <label class="margin-left-small"><input type="radio"
                                                                                                                            name="genradio" value="female"
<?= ($userDashboard['userDetail']['gender'] == "female") ? 'checked' : ''; ?>>Female</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row email-outer-container">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span><?php
                                                                            //print_r($userDashboard);
                                                                            if ($userDashboard['userDetail']['age'] >= 18 || $userDashboard['userDetail']['dob'] == '') {
                                                                                ?>Email <?php } else { ?>Email <?php } ?></span>
                                                                    </div>
                                                                    <div class="col-lg-9 email-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['email']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                if ($userDashboard['userDetail']['age'] >= 18 || $userDashboard['userDetail']['dob'] == '') {
                                                                    $dis = 'none;';
                                                                } else {
                                                                    $dis = 'block;';
                                                                }
                                                                ?>
                                                                <div class="row add-email-outer-container" style="display:<?= $dis ?>">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium"><span
                                                                                style="color: #FF0000;">*</span>Username</span>
                                                                    </div>
                                                                    <div class="col-lg-9 add-email-container">
                                                                        <span class="span-value-medium"><?= $userDashboard['userDetail']['parentsEmail']; ?></span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="hr hr-8 dotted"></div>
                                                        <div class="row">
                                                            <div class="col-lg-3 user-profile-headings">
                                                                <span class="heading">Change Password</span>
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <div class="row">
                                                                    <input type="hidden" name="present_pass"
                                                                           id="present_pass"
                                                                           value="<?= $userDashboard['userDetail']['password']; ?>">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium">Current Password</span>
                                                                    </div>
                                                                    <div class="col-lg-9 currentpass-container">
                                                                        <span class="span-value-medium">******</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium">New Password</span>
                                                                    </div>
                                                                    <div class="col-lg-9 newpass-container">
                                                                        <span class="span-value-medium"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3">
                                                                        <span class="span-heading-medium">Confirm Password</span>
                                                                    </div>
                                                                    <div class="col-lg-9 confpass-container">
                                                                        <span class="span-value-medium"></span>
                                                                    </div>
                                                                </div>



                                                            </div>
                                                        </div>
                                                        <div class="hr hr-8 dotted"></div>
                                                    </form>

                                                </div><!-- /#user-profile -->
                                            </div>


                                            <div id="buzzy-points" class="tab-pane">

                                                <div class="panel-group" id="Rdeempoint" role="tablist" aria-multiselectable="true">
                                                    <div class="row">
                                                       <div class="col-md-12">
                                                          <h3><b><a>REDEEM</a></b></h3>
                                                          <div class="hr hr-8 dotted"></div>
                                                        </div>
                                                    <div class="space-6"></div>
                                                    <div class="col-md-8">
                                                     <span>
                                                    <h3>How It Works</h3>
                                                </span>
                                                
                                                <span>
                                                    <h4><b>Step 1: Visit Your Office</b></h4>
                                                </span>
<span>
                                                    <h4><b>Step 2: Earn Points</b></h4>
                                                </span>
<span>
                                                    <h4><b>Step 3: Redeem Instantly For Prizes</b></h4>
                                                </span>
<span>
                                                    <h4><b>Step 4: Tell Everyone About How Great Your Office Is</b></h4>
                                                </span>
                                                </div>
                                                <div class="col-md-4 redeem-earn-more">
<a data-toggle="tab" href="#earn" id="tab-earn" onclick="{
                                                        $('#breadcom_home').html('<i class=\'ace-icon fa fa-angle-double-right\'></i>Earn');
                                                        $('#uearn').addClass('highlight');
                                                        $('#vprofile').removeClass('highlight');
                                                        $('#bpoint').removeClass('highlight');
                                                        $('#ucheckins').removeClass('highlight');
                                                        $('#usaved').removeClass('highlight');
                                                        $('#uliked').removeClass('highlight');
                                                    }">
                                                
                                                    <button class="result-view-profile-btn">Earn More Now!</button>
                                                    </a>

</div>
                                                <div class="clearfix col-md-12">
                                                <div class="hr hr-8 dotted"></div>
                                                When you redeem your points for any of our available gift cards, the code will instantly be sent to your email address so you can use it that day. No more waiting for prizes to be mailed and picked-up in office!<br \>
                                                <b>Remember: 50 BuzzyDoc Points = $1</b>
                                                <div class="hr hr-8 dotted"></div>
                                               </div>
                                                </div>
                                                    <div class="panel panel-default">

                                                        <div class="panel-heading" role="tab" id="buzzyDoc-global1" data-toggle="collapse" data-parent="#buzzy-points" href="#buzzyDoc-global-points" aria-expanded="true" aria-controls="rdeem-doc-panel1">
                                                            <h4 class="panel-title clearfix">
                                                                <span>
                                                                    BuzzyDoc Points
                                                                </span>
                                                                <span class="pull-right clinicpoint" id="shgbpoint"><?= (!empty($userDashboard['userDetail']['globalPoints'])) ? $userDashboard['userDetail']['globalPoints'] : '0'; ?></span>
                                                            </h4>
                                                        </div>
                                                        <input type="hidden" id="point_type" value=""/>
                                                        <input type="hidden" id="clinic_id" value=""/>
                                                        <input type="hidden" id="buzz_point" value=""/>
                                                        <div id="buzzyDoc-global-points" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="buzzyDoc-global">
                                                            <div class="panel-body">
                                                                <div class="row text-center">
                                                                    <div class="col-xs-6  col-sm-3 col-md-3 col-lg-3">
<?= $this->html->image(CDN.'img/buzzydoc-user/images/gift.png', array('title' => 'BuzzyDoc', 'alt' => 'Alexa\'s Photo', 'class' => 'img-responsive')); ?> 
                                                                    </div>
                                                                    <div class="col-xs-6 col-sm-5 col-md-5 col-lg-5 margin-top-small">
                                                                        <span class="global-point-value"><?= (!empty($userDashboard['userDetail']['globalPoints'])) ? $userDashboard['userDetail']['globalPoints'] : '0'; ?></span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 margin-top-small">
                                                                        <p>Click here to redeem your BuzzyDoc points</p>
                                                                        <?php if(empty($BuzzyClinicList)){ ?>
                                                                        <div class="btn btn-success">
                                                                            <span class="redeem-points-button-main"><a style="color: #fff;">Redeem Points</a></span>
                                                                        </div>
                                                                        <?php }else{ ?>
                                                                        <div class="btn btn-success redeem-points-button-main-container" data-points="<?= (!empty($userDashboard['userDetail']['globalPoints'])) ? $userDashboard['userDetail']['globalPoints'] : '0'; ?>" data-type="global">
                                                                            <span class="redeem-points-button-main"><a style="color: #fff;">Redeem Points</a></span>
                                                                        </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
<?php if ($userDashboard['userDetail']['netLocalPoints'] > 0) { ?>
                                                        <div
                                                            class="panel panel-default">
                                                            <div class="panel-heading collapsed" role="tab"
                                                                 id="buzzyDoc-local" data-toggle="collapse"
                                                                 data-parent="#accordion" href="#buzzyDoc-local-points"
                                                                 aria-expanded="true" aria-controls="buzzyDoc-local-points">
                                                                <h4 class="panel-title clearfix">
                                                                    <span> Local Practice Points </span> <span
                                                                        class="pull-right clinicpoint"><?= $userDashboard['userDetail']['netLocalPoints']; ?></span>
                                                                </h4>
                                                            </div>
                                                            <div id="buzzyDoc-local-points"
                                                                 class="panel-collapse collapse" role="tabpanel"
                                                                 aria-labelledby="buzzyDoc-local">
                                                                <div class="panel-body">
                                                                    <div class="panel-group" id="accordionLocal"
                                                                         role="tablist" aria-multiselectable="true"></div>
                                                                    <div class="loading">
    <?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
<?php } ?>

                                                    <div class="widget-box transparent">
                                                        <div class="widget-header widget-header-small">
                                                            <h4 class="widget-title blue smaller">
                                                                <i class="ace-icon fa fa-rss orange"></i>
                                                                Recent Activities
                                                            </h4>
                                                        </div>

                                                        <div class="widget-body response-container" id="recent-acitivies">

                                                        </div>
                                                        <div class="loading">
<?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                        </div>
                                                    </div>
                                                    <div class="center">
                                                        <button type="button" class="btn btn-sm btn-primary btn-white btn-round" id="vew-more-activities">
                                                            <i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
                                                            <span class="bigger-110">View more activities</span>

                                                            <i class="icon-on-right ace-icon fa fa-arrow-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /#buzzy-points -->


                                            <div id="badges" class="tab-pane">
                                                <!-- #section:pages/profile.friends -->
                                                <span>
                                                    <h3><b><a>BADGES</a></b></h3>
                                                </span>
<div class="hr hr-8 dotted"></div>
Here are the milestone badges you have earned and the ones you still need to unlock!
<div class="hr hr-8 dotted"></div>
                                                
                                                <div class="profile-users clearfix">

                                                    <?php
                                                    $i = 1;
                                                    foreach ($userDashboard['systembadges'] as $sbage) {
                                                        $opacity_class = 'opacity-midium';

                                                        foreach ($userDashboard['badges'] as $bg) {
                                                            if ($bg == $sbage['Badge']['id']) {
                                                                $opacity_class = '';
                                                            }
                                                        }
                                                        ?>
                                                        <div
                                                            class="badges <?= $opacity_class ?>">
    <?php $levelArray = array(1,2,3,4,5); 
    	if(in_array($i,$levelArray)) {
    	echo $this->html->image(CDN.'img/images_buzzy/badge_point' . $i . '.png', array('title' => $sbage['Badge']['name'], 'alt' => $sbage['Badge']['name'], 'id' => 'avatar1'));
     ?>
                                                            <header
                                                                class="check-ins-heading clearfix"><?php echo $sbage['Badge']['name']; ?>
                                                                <div
                                                                    class="check-ins-tooltip"><?php echo $sbage['Badge']['description']; ?></div>
                                                            </header>
                                                            <?php } ?>
                                                        </div>

    <?php $i++;
}
?>

                                                </div>
                                            </div><!-- /#badges -->



                                           <div id="buzzy_rewards" class="tab-pane">
                                            <span>
                                                <h3><b><a>REWARDS</a></b></h3>
                                            </span>
<div class="hr hr-8 dotted"></div>
                                            Take a look at these amazing prizes you can redeem your points for!
                                           <!-- <div class="hr hr-8 dotted"></div>
                                                <span>
                                                    <h3>How Redeeming Works:</h3>
                                                </span>
                                                When you redeem your points for any of our available gift cards, the code will instantly be sent to your email address so you can use it that day. No more waiting for prizes to be mailed and picked-up in office!
                                              -->
                                            <div class="hr hr-8 dotted"></div>

                                            <div class="panel-body reedm-panl">
                                                                <div class="row doc-intro">


                                                                    <div class="clearfix col-md-12 showOnclick2">



                                                                        <div class="row clearfix">
                                                                            <div class="col-sm-6 reedm-card text-center">
                                            <?= $this->html->image(CDN.'img/buzzydoc-user/images/tangocard.png', array('title' => 'Tango Card', 'class' => '', 'width' => '200px !important;')); ?>
                                                                                <div class="col-md-12"> <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                                                                    What is Tango?
                                                                                  </a>
                                                                                <ul class="dropdown-menu-right reedm-tooltip dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                                                                    <li class="dropdown-footer">
                                                                                        The Tango Card lets you to choose from tons of different retail and restaurant gift card options. It can all be spent on one gift card, or many, and the great thing is it never expires! To learn more about Tango, <a href="https://www.tangocard.com/the-tango-card/" target="_blank" >click here!</a>
                                                                                    </li>
                                                                                </ul>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6 reedm-card  text-center">
                                                                                <?= $this->html->image(CDN.'img/buzzydoc-user/images/gift_card.png', array('title' => 'Amagon', 'class' => '', 'width' => '200px !important')); ?>
                                                                                <div class="col-md-12">
                                                                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                                                                    What is Amazon?
                                                                                </a>
                                                                                <ul class="dropdown-menu-right reedm-tooltip dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                                                                    <li class="dropdown-footer">
                                                                                        The Amazon Card lets you choose from millions of items storewide so you can get exactly what you want. It can all be spent on one item, or many, and the great thing is it never expires. All you have to do is load the redemption code into your Amazon account and start filling your cart!
                                                                                    </li>
                                                                                </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>





                                                                </div>
                                                            </div>
                                            <div id="user-reward-list-container">
                                                <div aria-multiselectable="true" role="tablist" id="rewardList" class="panel-group">
                                                    
                                                    

                                                       
                                                  
                                                    <div class="panel panel-default  margin-bottom-medium odd" id="panel-reward-doc11">
                                                        <div aria-controls="reward-doc-panel02" aria-expanded="true" href="#reward-doc02" data-parent="#rewardList" data-toggle="collapse" id="reward-doc-panel02" role="tab" class="panel-heading  see-more-gift collapsed">
                                                            <h4 class="panel-title clearfix"><span>See More Gift Cards:</span></h4>
                                                             <span class="up-down-arrow">
                                                              <i class="arrow fa fa-angle-up"></i>
                                                              <i class="arrow fa fa-angle-down"></i>
                                                             </span>
                                                        </div>
                                                        <div aria-labelledby="reward-doc-panel02" role="tabpanel" class="panel-collapse collapse" id="reward-doc02">
                                                            <div class="panel-body">
                                                                <div class="row doc-intro">


                                                                    <div>

                                                                        <div style="margin: 10px; height: 265px; overflow: auto; color: #000;">
                                                                            <?php foreach ($tangorewards as $tango) { ?>
                                                                                <div class="redeemBox-card col-md-3 text-center">

                                                                                    <div class="productPoints">
                                                                                        <img  alt="<?php echo $tango->description; ?>" title="<?php echo $tango->description; ?>" src="<?php echo $tango->image_url; ?>">
                                                                                    </div>
                                                                                    <h3><?php echo $tango->description; ?></h3>
                                                                                </div>


                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
 <?php if (!empty($userClinics)) { ?>
                                                    <div class="panel panel-default margin-bottom-medium" id="panel-reward-doc12">
                                                        <div aria-controls="reward-doc-panel03" aria-expanded="true" href="#reward-doc03" data-parent="#rewardList" data-toggle="collapse" id="reward-doc-panel021" role="tab" class="panel-heading see-more-gift collapsed">
                                                            <h4 class="panel-title clearfix"><span>Products and Services:</span></h4>
                                                            <span class="up-down-arrow">
                                                              <i class="arrow fa fa-angle-up"></i>
                                                              <i class="arrow fa fa-angle-down"></i>
                                                             </span>
                                                        </div>
                                                        
                                                        <div aria-labelledby="reward-doc-panel03" role="tabpanel" class="panel-collapse collapse" id="reward-doc03">
                                                            <div class="panel-heading see-more-gift collapsed">
  In order to redeem your points for one of the products or services listed below, please visit your practice and let them know which reward you would like to select. Otherwise, you may instantly redeem for one of the gift cards available above.
</div>
                                                            <div class="panel-body">
                                                                <div class="row doc-intro">


                               
                            <div id="redeem_container1" style="position: relative; top: 0px; left: 0px; width: 881px ; height: 331px; background: #fff; overflow: hidden; ">
                                <!-- Slides Container -->
                                <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 29px; width: 881px; height: 278px; border:  -webkit-filter: blur(0px); background-color: #fff; overflow: hidden;">
                                            <?php foreach ($userClinics as $key => $val) { ?>
                                        <div>
                                            <div u="thumb"><?php echo $key; ?></div>
                                            <div style="margin: 10px; height: 265px; overflow: auto; color: #000;">
                                                <?php
                                                foreach ($val as $index => $elem) {
                                                 
                                                    ?>
                                                    <div class="redeemBox">
                                                        <h3><?php echo $elem['title']; ?></h3>
                                                        <div class="productPoints"><?php echo $elem['points']; ?> Points</div>


                                                    </div>

                                            <?php
                                        }
                                        ?>
                                            </div>
                                        </div>
    <?php } ?>
                                </div>
                                <!--#region ThumbnailNavigator Skin Begin -->
                                <div u="thumbnavigator" class="jssort12" style="left:0px; top: 0px;">
                                    <!-- Thumbnail Item Skin Begin -->
                                    <div u="slides" style="cursor: default; top: 0px; left: 0px; border-left: 1px solid gray;">
                                        <div u="prototype" class="p">
                                            <div class=w><div u="thumbnailtemplate" class="c"></div></div>
                                        </div>
                                    </div>
                                    <!-- Thumbnail Item Skin End -->
                                </div>
                            </div>



                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    
                      
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                </div>

                                            </div>
    </div>


                                            <div id="earn" class="tab-pane panel" style="border:0;">
                                                <span>
                                                    <h3><b><a>EARN</a></b></h3>
                                                </span>
                                                <div class="hr hr-8 dotted"></div>
                                                Here is a list of the promotions your practice offers you points for. How many can you get?!
                                                <div class="hr hr-8 dotted"></div>
                       <?php 
$sessionbuzzycheck = $this->Session->read('usercheck');
if(isset($ClinicList) && !empty($ClinicList)){ ?>
                            <button class="result-view-profile-btn" onclick="inviteFriend();">INVITE MORE FRIENDS</button>
                            <?php } ?>  

<?php if(count($fbAvailableClinic)>0){ ?>
                                                <div class="hr hr-8 dotted"></div>
                                                <div class="space-6"></div>
                                                <span>
                                                    <h3>Like on Facebook</h3>
                                                </span>
                                                <b>Like your practice on Facebook now to earn 100pts!</b>
                                   <div class="col-xs-12 col-sm-12">
																		<label style="padding-left: 25px;">Choose Practice:</label>
                                                                        <select id="user_clinic_fb" style="height:30px;margin:10px 0 0 10px" onchange="changeclinic();">
                                                                        <option val=""> Select </option>
                                                                        <?php foreach ($fbAvailableClinic as $val) { ?>
                                                                       		<option value="<?php echo $val['Clinic']['id'] ?>"><?php echo $val['Clinic']['display_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    </div>
 
<div class="space-6"></div>         

<span>&nbsp;</span>
                                <?php 
                                 $f=1;
                                foreach($fbAvailableClinic as $fblike){
                                ?>
<div id="fbclinic_<?php echo $fblike['Clinic']['id'] ?>" style="display:none;">
                                <div id="fb-root"></div>
                    <script src="//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $fblike['Clinic']['fb_app_id'] ?>"></script>
                    
                             <div id="fb_like_div" class="fb_new">
                                  <div class="fb-like-box" id="fb_like" data-href="<?php echo $fblike['Clinic']['facebook_url'] ?>" data-colorscheme="light" 
                                       data-show-faces="false" data-header="true" data-stream="false" data-show-border="false"></div>
                                  
                                  <p style="display:none;" id="fb_progress_div" >
                                      <?php echo $this->html->image(CDN.'img/images_buzzy/loading.gif',array('alt'=>'fb like'));?>Please wait...
                                  </p>

                                  <p class="profilelink hidden-xs">
                                      <a href="javascript:void(0)" style="cursor:default;">Get 100 points instantly for clicking "like"</a>
                                  </p>
                       
                             </div>
</div>
<?php $f++; } } ?>

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
                                                url: "<?php echo Staff_Name ?>buzzydoc/facebookpointallocationnew",
                                                data: "fb_status=like&clinic_id=" +href,
                                                success: function(msg) {
                                                    $("#fb_progress_div").hide();
                                                    obj = JSON.parse(msg);
                                                    if (obj.success == 1) {


                                                        alert("We've credited your account " + obj.data + " points for liking our Facebook page. Thanks!");
                                                        location.reload();
                                                    } else {
                                                        alert("You've already got points for Facebook like for this page.");
                                                        location.reload();
                                                    }
                                                }
                                            });

                                        });

                  </script>      
<div class="hr hr-8 dotted"></div>
<?php if (empty($userRegisteredClinics)) { ?>
                                                    <div role="alert" class="profile-update-error alert alert-warning alert-dismissible text-center"><button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button><span>The list is empty.</span></div>
<?php } else { ?>

                                                    <!-- #section:pages/profile.friends -->

                                                        <div id="m_container" style="position: relative; top: 0px; left: 0px; width: 868px; height: 454px; background: #fff; overflow: auto; border:1px solid #2FB889; ">
																		<div class="col-xs-12 col-sm-12">
																		<label style="padding-left: 25px;">Choose Practice:</label>
                                                                        <select id="user_clinic_promotions" style="height:30px;margin:10px 0 0 10px">
                                                                        <?php foreach ($userRegisteredClinics as $key => $val) { ?>
                                                                       		<option value="<?php echo $val['id']?>"><?php echo $val['display_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    </div>

														<div id="clinics_promotions" style="display:none;" class="col-xs-12 col-sm-6 common-bx">
															<h2>Network Promotions</h2>
															<ul id="clinic_items"></span>
														</div>
														<div id="clinics_level_up_promotions" style="display:none;" class="col-xs-12 col-sm-6 common-bx">
															<h2>Treatment Plan Promotions</h2>
															<ul id="level_up_items"></span>
														</div>
                                                                                                                <div id="clinics_cus_promotions" style="display:none;" class="col-xs-12 col-sm-6 common-bx">
															<h2>Extras</h2>
															<ul id="cus_items"></span>
														</div>
                                                     </div>
<?php } ?>

<div class="hr hr-8 dotted"></div>
<span>
                                                    <h4>Liked Practices</h4>
                                                </span>
<div class="hr hr-8 dotted"></div>
                                                <div id="user-liked-list-container">
                                                    <div class="margin-bottom-large" id="likedList"
                                                         role="tablist" aria-multiselectable="true"></div>
                                                    <div class="loading">
<?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                    </div>
                                                </div>
                                                <div class="space-6"></div>

                                                <div class="center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary btn-white btn-round"
                                                            id="more-liked">
                                                        <i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
                                                        <span class="bigger-110">View More</span> <i
                                                            class="icon-on-right ace-icon fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div><!-- /#badges -->

                                            <div id="checkins" class="tab-pane">
                                                <span>
                                                    <h3><b><a>CHECK-INS</a></b></h3>
                                                </span>
                                                <div class="hr hr-8 dotted"></div>
                                                A Check-in will occur each time you visit your practice and receive points. The check-ins are a great way to see how many other unique users are participating in the rewards program and how busy your practice is.
                                                <div class="hr hr-8 dotted"></div>
                                                <div id="user-checkins-points-container">
                                                    <div class="panel-group" id="checkinsPoints" role="tablist"
                                                         aria-multiselectable="true"></div>
                                                    <div class="loading">
<?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                    </div>
                                                </div>
                                                <div class="space-6"></div>

                                                <div class="center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary btn-white btn-round"
                                                            id="more-checkins">
                                                        <i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
                                                        <span class="bigger-110">View More</span> <i
                                                            class="icon-on-right ace-icon fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /#checkins -->
                                            <div id="saved" class="tab-pane">
                                                <span>
                                                    <h3><b><a>MY PRACTICES</a></b></h3>
                                                </span>
                                                <div class="hr hr-8 dotted"></div>
                                                For a quick reference, we save the practices you have earned points from.
                                                <div class="hr hr-8 dotted"></div>
                                                <div id="user-clinic-list-container">
                                                    <div class="panel-group" id="clinicList" role="tablist" aria-multiselectable="true">

                                                    </div>
                                                    <div class="loading1">
<?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                    </div>
                                                </div>
                                                <span>
                                                    <h3>Saved Doctors</h3>
                                                </span>
                                                <div class="hr hr-8 dotted"></div>
                                                <div id="user-saved-list-container">
                                                    <div class="panel-group" id="savedList" role="tablist"
                                                         aria-multiselectable="true"></div>
                                                    <div class="loading">
<?= $this->html->image(CDN.'img/loading-bar.gif', array('title' => 'Please wait', 'alt' => 'Please wait', 'class' => 'loading-img')); ?>
                                                    </div>
                                                </div>
                                                <div class="space-6"></div>

                                                <div class="center">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary btn-white btn-round"
                                                            id="more-saved">
                                                        <i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
                                                        <span class="bigger-110">View More</span> <i
                                                            class="icon-on-right ace-icon fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /#saved -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- PAGE CONTENT ENDS -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.page-content -->
            </div>
        </div>
        <!-- /.main-content -->
        <div class="modal fade" id="redeemModalMain" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="redeemclose();"><span aria-hidden="true" id="closeredeem" >&times;</span></button>

                        <h4 class="modal-title" id="redeemPointsLabel">Redeem Points</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center points-value-span-container1">
                            <span id="points_value_span1" class=""></span>
                        </div>
                        <?php if($AccessRedeem==0){ ?>
                        <div class="row clearfix">

                            <div class="col-sm-6 showOnclick"><?= $this->html->image(CDN.'img/buzzydoc-user/images/tangocard.png', array('title' => 'Tango Card', 'class' => '', 'width' => '150px !important;', 'style' => 'float:right')); ?>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    What is Tango?
                                </a>
                                <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                    <li class="dropdown-footer">
                                        The Tango Card lets you to choose from tons of different retail and restaurant gift card options. It can all be spent on one gift card, or many, and the great thing is it never expires! To learn more about Tango, <a href="https://www.tangocard.com/the-tango-card/" target="_blank">click here!</a>


                                    </li>
                                </ul>
                            </div>
                            <?php if($AccessRedeem>0){ ?>
                            <div class="col-sm-6 "><button type="button" class="btn btn-success " style=" margin-top: 30px;" id="submit-redeem" onclick="alert('Redemption not allowed.Please contact clinic administrator.');">Redeem</button>
                            <?php }else{ ?>
                            <div class="col-sm-6 "><button type="button" class="btn btn-success " style=" margin-top: 30px;" id="submit-redeem" onclick="submitRedeemPoints('TNGO-E-V-STD');">Redeem</button>
                            <?php } ?>
                            


                                <span id="load-status" style="display:none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span></div>


                        </div>
                        <hr>

                        <div class="row clearfix">

                            <div class="col-sm-6 showOnclick1"><?= $this->html->image(CDN.'img/buzzydoc-user/images/gift_card.png', array('title' => 'Amagon', 'class' => '', 'width' => '150px !important', 'style' => 'float:right')); ?>

                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    What is Amazon?
                                </a>
                                <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                    <li class="dropdown-footer">
                                        The Amazon Card lets you choose from millions of items storewide so you can get exactly what you want. It can all be spent on one item, or many, and the great thing is it never expires. All you have to do is load the redemption code into your Amazon account and start filling your cart!


                                    </li>
                                </ul>
                            </div>
                            <?php if($AccessRedeem>0){ ?>
                            <div class="col-sm-6"><button type="button" class="btn btn-success" style=" margin-top: 30px;" id="submit-redeem1" onclick="alert('Redemption not allowed.Please contact clinic administrator.');">Redeem</button>
                            </div>
                            <?php }else{ ?>
                            <div class="col-sm-6"><button type="button" class="btn btn-success" style=" margin-top: 30px;" id="submit-redeem1" onclick="submitRedeemPoints('AMZN-E-V-STD');">Redeem</button>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                                <?php if (!empty($userClinics)) { ?>
<div class="panel-heading see-more-gift collapsed">
  In order to redeem your points for one of the products or services listed below, please visit your practice and let them know which reward you would like to select. Otherwise, you may instantly redeem for one of the gift cards available above.
</div>
                            <div id="redeem_container" style="position: relative; top: 0px; left: 0px; width: 560px; height: 331px; background: #fff; overflow: hidden; ">
                            
                                <!-- Slides Container -->
                                <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 29px; width: 560px; height: 278px; border: 1px solid gray; -webkit-filter: blur(0px); background-color: #fff; overflow: hidden;">
                                            <?php foreach ($userClinics as $key => $val) { ?>
                                        <div>
                                            <div u="thumb"><?php echo $key; ?></div>
                                            <div style="margin: 10px; height: 265px; overflow: auto; color: #000;">
                                                <?php
                                                foreach ($val as $index => $elem) {
                                                    if (isset($perclinicbuzzpnt) && $elem['from_us'] == 1) {
                                                        $need = round($elem['points'] - $perclinicbuzzpnt[$elem['clinic_id']]);
                                                    } else {
                                                        $need = $elem['points'] - $userDashboard['userDetail']['totalPoints'];
                                                    }
                                                    ?>
                                                    <div class="redeemBox">
                                                        <h3><?php echo $elem['title']; ?></h3>
                                                        <div class="productPoints"><?php echo $elem['points']; ?> Points</div>

                                  

                                                    </div>

                                            <?php
                                        }
                                        ?>
                                            </div>
                                        </div>
    <?php } ?>
                                </div>

                                <!--#region ThumbnailNavigator Skin Begin -->

                                <div u="thumbnavigator" class="jssort12" style="left:0px; top: 0px;">
                                    <!-- Thumbnail Item Skin Begin -->
                                    <div u="slides" style="cursor: default; top: 0px; left: 0px; border-left: 1px solid gray;">
                                        <div u="prototype" class="p">
                                            <div class=w><div u="thumbnailtemplate" class="c"></div></div>
                                        </div>
                                    </div>
                                    <!-- Thumbnail Item Skin End -->
                                </div>
                            </div>
<?php } ?>
                        <!--#endregion ThumbnailNavigator Skin End -->


                    </div>
                </div>
            </div>
        </div>
            <div class="modal fade" id="redeemModal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="redeemPointsLabel">Redeem Points</h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center points-value-span-container">
                                <span id="points_value_span" class=""></span>
                            </div>
                            <div class="clearfix" style="font-size: 9px;">
                                <div style="float: left"> <?= $this->html->image(CDN.'img/buzzydoc-user/images/gift_card.png', array('title' => 'Amagon', 'class' => '', 'height' => '200px', 'width' => '343px !important')); ?>
                                </div>
                                <div style="float: right; max-width: 190px;">
<?= $this->html->image(CDN.'img/buzzydoc-user/images/tangocard.png', array('title' => 'Tango Card', 'class' => '', 'width' => '150px !important')); ?>
                                    <p>Tango Card: Redeem for iTunes®, Amazon.com,
                                        Starbucks, or your favorite gift card. Use online, in store, or
                                        on your mobile.</p>
                                </div>
                                <p style="clear: both;">*Amazon.com is not a sponsor of this
                                    promotion. Except as required by law, Amazon.com Gift Cards
                                    ("GCs") cannot be transferred for value or redeemed for cash. GCs
                                    may be used only for purchases of eligible goods on Amazon.com or
                                    certain of its affiliated websites. GCs cannot be redeemed for
                                    purchases of gift cards. Purchases are deducted from the GC
                                    balance. To redeem or view a GC balance, visit "Your Account" on
                                    Amazon.com. Amazon is not responsible if a GC is lost, stolen,
                                    destroyed or used without permission. For complete terms and
                                    conditions, see www.amazon.com/gc-legal. GCs are issued by ACI
                                    Gift Cards, Inc., a Washington corporation. All Amazon ®, ™ & ©
                                    are IP of Amazon.com, Inc. or its affiliates. No expiration date
                                    or service fees.</p>
                            </div>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success " id="submit-redeem2"
                                    onclick="submitRedeemPoints('AMZN-E-V-STD');">Redeem</button>
                            <span id="load-status1" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmDeleteModal">
                <div class="modal-dialog deleteConfirmationModal">
                    <div class="modal-content">
                        <div class="modal-header border-none">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h2 class="modal-title text-center pink-color">Are you absolutely
                                sure?</h2>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">

                                <button type="button" class="btn btn-success dontDelete"
                                        data-dismiss="modal">No, Don't Delete</button>
                                <span type="button" class="btn btn-danger" id="confirmDelete"
                                      data-type="" data-id="">Yes, Delete</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="confirmCancelModal">
                <div class="modal-dialog confirmModal">
                    <div class="modal-content">
                        <div class="modal-header border-none">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h2 class="modal-title text-center pink-color">Are you absolutely
                                sure?</h2>
                        </div>
                        <div class="modal-body">
                            <p class="text-center">Your changes will be lost</p>
                            <div class="text-center">
                                <a href="#" type="button" class="btn btn-danger "
                                   data-dismiss="modal" aria-hidden="true">No</a> <span type="button"
                                   class="btn btn-success cancel-edit-confirm">Yes</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="confirmUpdateModal">
                <div class="modal-dialog confirmModal">
                    <div class="modal-content">
                        <div class="modal-header border-none">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h2 class="modal-title text-center pink-color">Are you absolutely
                                sure?</h2>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <a href="#" type="button" class="btn btn-danger "
                                   data-dismiss="modal" aria-hidden="true">No,Don't Update</a> <span
                                   type="button" class="btn btn-success confirmUpdateProfile">Yes,
                                    Update Profile</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div id="status"></div>
<div class="modal fade" id="notification-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog notification-table" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">REFER A FRIEND</h4>
            </div>

            <form action="" method="post" id="rating-submit" name="rating-submit" class="rating-submit">
                <div class="modal-body">
                    <div class="row">
                        <table class="notification-data table">
                            <thead>
                                <tr>          <th colspan="2"> 
                            <div class="  center-block notification-head-select">

                                       <?php if(count($ClinicList)>1){ ?>
                                <div class="col-md-5">
                                    <select name="clinic_list" id="clinic_list" class="form-control" onchange="getclinicdetails();">
                                        <option value="">Select Clinic</option>
                                <?php foreach($ClinicList as $clinicls){ ?>
                                        <option value="<?php echo $clinicls['clinic_id']; ?>" <?php if($defaultclinic==$clinicls['clinic_id']){ echo "selected"; } ?>><?php echo $clinicls['clinic_name']; ?></option>
                                <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-7 text-left notification-head-text">Earn points when the friends and family you refer convert into:</div>
                            <?php }else{ ?><div class="col-md-5">
                                    <select name="clinic_list" id="clinic_list" class="form-control" onchange="getclinicdetails();">
                                <?php foreach($ClinicList as $clinicls){ ?>
                                        <option value="<?php echo $clinicls['clinic_id']; ?>" <?php if($defaultclinic==$clinicls['clinic_id']){ echo "selected"; } ?>><?php echo $clinicls['clinic_name']; ?></option>
                                <?php } ?>
                                    </select>
                                </div><input type="hidden" id="clinic_list" name="clinic_list" value="<?php echo $defaultclinic; ?>" ><div class="col-md-7 text-left notification-head-text">Earn points when the friends and family you refer convert into:</div><?php } ?></div></th>
                            </tr>
                            </thead>
                            <tbody id="leadsplan">
                                   <?php
     $settings=array();
    if(!empty($admin_settings)){
                                    if($admin_settings['AdminSetting']['setting_data']!=''){
                                      $settings=json_decode($admin_settings['AdminSetting']['setting_data']);
                                    }
                                }



    foreach($leads as $ld){
            $point1='';
                                    foreach($settings as $set =>$setval){

                                       if($set==$ld['LeadLevel']['id']){
                                         $point1=$setval;
                                       }
                                    }

        ?>
                                <tr>
                                    <td class="center col-md-6"><?php echo $ld['LeadLevel']['leadname']; ?></td>
                                    <td class="center col-md-6"><?php if($point1!=''){ echo $point1; }else{ echo $ld['LeadLevel']['leadpoints']; }?> points</td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table>

                        <div class="col-md-12 notification-form form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <input type="hidden" id="indty" name="indty" value="<?php echo $industry_id; ?>" >
                                    <input type="hidden" id="ref_clinic_id" name="ref_clinic_id" value="<?php echo $defaultclinic; ?>" >
                                    <div class="col-md-12"><input type="text"  id="first_name" name="first_name" placeholder="First Name:" class="col-md-12" ></div>
                                    <div class="col-md-12"><input type="text" id="last_name" name="last_name" placeholder="Last Name:" class="col-md-12" ></div>
                                    <div class="col-md-12"><input type="text" id="email" name="email" placeholder="Email:" class="col-md-12" ></div>                         </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if(empty($refer_msg)){ ?>
                                        <textarea class="form-control" id="message" name="message" placeholder=""></textarea>
                                     <?php }else{ ?>
                                        <textarea class="form-control" name="message" id="massage"><?php echo $refer_msg->reffralmessage1; ?></textarea>
                                     <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 notification-chbx">
                                <div class="row" id="defaultmsg">
                                     <?php

                                                if($refer_msg->cnt>1){ ?>
                                    <?php

                        for($k=1;$k<=$refer_msg->cnt;$k++){
                            $fname='reffralmessage'.$k;
                            ?>
                          <?php if($k==1){
                           ?>
                                    <label class="col-md-2 checkbox-heading">Quick Recommendations :</label>
                                     <?php }else{ ?>
                                    <label class="col-md-2 checkbox-heading">&nbsp;</label>

                       <?php } ?>
                                    <div class="col-md-10">
                                        <div class="radio clearfix">
                                            <?php if($k==1){
                           ?>

                                            <div class="co-md-12">
                                                <label >
                                                    <input class="ace" type="radio" id="msg" name="msg" checked="checked" onclick="setmsg(<?=$k?>);"><span class="lbl"><?=$refer_msg->$fname?></span>


                                                </label>
                                            </div>
                                          <?php }else{ ?>
                                            <div class="co-md-12">
                                                <label >
                                                    <input class="ace" type="radio" id="msg" name="msg" onclick="setmsg(<?=$k?>);"><span class="lbl"><?=$refer_msg->$fname?></span>
                                                </label>
                                            </div>
                                          <?php } ?>

                                        </div>
                                    </div>
                        <?php }} ?>

                                </div>

                                 <?php if(!empty($refer_msg)){ ?>
                                <div class="col-md-10">
                                    <div class="radio clearfix">
                                        <div class="co-md-12">

                                            <div id="setnext" style="display:none">


                                                <a onclick="setdefault();" style="cursor: pointer;" title="Change Recommendation"></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div id='status_error_reco' style="color: #FF0000; margin-bottom: 3px;">&nbsp;</div>
                    <div class="modal-footer">
                        <span id="refer_load" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                        <input type="button" value="Submit" id='recommen_btn' class="result-view-profile-btn">&nbsp;
                        <span id="reco-status-bar" style="position: absolute; z-index: 5; left: 40px; top: 5px;"></span>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

            <!-- inline scripts related to this page -->

            <script>
function changeclinic(){
var id=$('#user_clinic_fb').val();
<?php 
 foreach($fbAvailableClinic as $fblike){
                                ?>
if(id==<?php echo $fblike['Clinic']['id'];?>){
$("#fbclinic_"+<?php echo $fblike['Clinic']['id'];?>).css("display", "block");
}else{
$("#fbclinic_"+<?php echo $fblike['Clinic']['id'];?>).css("display", "none");
}
<?php } ?>
}
function inviteFriend() {
        $('#notification-table').modal();
        //        $("#inviteFri").css("display", "block");
    }
function setmsg(id) {
        var indty = $('#indty').val();
        $.ajax({
            type: "POST",
            url: "/buzzydoc/getmsg/",
            data: "&id=" + id + "&indty=" + indty,
            success: function(msg) {
                $("#massage").focus();
                $('#massage').val(msg);

                $("#defaultmsg").css("display", "none");
                $("#setnext").css("display", "block");
            }
        });
    }
    function setdefault() {
        $("#defaultmsg").css("display", "block");
        $("#setnext").css("display", "none");
    }
    function getclinicdetails() {
        var clinic_id = $('#clinic_list').val();
        $.ajax({
            type: "POST",
            url: "/buzzydoc/getClinicLead/",
            data: "&clinic_id=" + clinic_id,
            success: function(msg) {
                obj = JSON.parse(msg);
                $('#massage').val(obj.message);
                $('#indty').val(obj.indty);
                $('#ref_clinic_id').val(obj.ref_clinic_id);
                $('#leadsplan').html(obj.leadsplan);
                $('#defaultmsg').html(obj.defaultmsg);

            }
        });
    }
                function redeemclose() {
                    $('#load-status').hide();
                    $('#load-status1').hide();
                    $('#submit-redeem').removeAttr('disabled');
                    $('#submit-redeem1').removeAttr('disabled');
                    $('#submit-redeem2').removeAttr('disabled');
                    $('#redeemModalMain .modal-body .points-value-span-container1').before('');
                    //location.reload();
                }

                function redeemed(user_id, product_id, points) {
                    var r = confirm("Are you sure you want to redeem this product?");
                    if (r == true)
                    {
                        $("#redeemload").css("display", "block");
                        $.ajax({
                            type: "POST",
                            data: "user_id=" + user_id + '&product_id=' + product_id + '&points=' + points,
                            dataType: "json",
                            url: "<?= Staff_Name ?>buzzydoc/redeemlocproduct/",
                            success: function(result) {
                                if (result == 1) {
                                    alert('You have redeemed product successfully.');
                                    location.reload();
                                    $("#redeemload").css("display", "none");
                                } else if (result == 2) {
                                    alert('You do not have sufficient balance.');
                                } else {
                                    alert('Unable to redeem. Please contact buzzydoc admin.');
                                }
                            }
                        });

                    }
                    else
                    {
                        return false;
                    }


                }

                jQuery(document).ready(function($) {
                
                $('#user_clinic_promotions').on('change',function(){
           			$('#clinic_items').html('');
        			$('#clinics_promotions').hide();
        			$('#level_up_items').html('');
        			$('#clinics_level_up_promotions').hide();
                                $('#cus_items').html('');
        			$('#clinics_cus_promotions').hide();
	            if($(this).val()!='' && $(this).val()!='Select'){
	            	 $.ajax({
			            type: 'POST',
			            url: '/buzzydoc/getajaxuserpromotions',
			            dataType: 'json',
			            data: {
			                clinic_id: $(this).val(),
			                display_name: $("#user_clinic_promotions :selected").text()
			            },
	            		success: function(data) {
	            			$('#clinic_items').html(data.promotions);
	            			$('#clinics_promotions').show();
		            		if(data.levelUpPromotions && data.levelUpPromotions!=''){
		            			$('#level_up_items').html(data.levelUpPromotions);
		            			$('#clinics_level_up_promotions').show();
		            		}
                                        if(data.cusPromotions && data.cusPromotions!=''){
		            			$('#cus_items').html(data.cusPromotions);
		            			$('#clinics_cus_promotions').show();
		            		}
	            		}
	            	});
	             }			
             });
$('#user_clinic_promotions').find('option:eq(0)').prop('selected', true).trigger('change');
                    
                    var options = {
                        $AutoPlay: false, //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                        $AutoPlaySteps: 1, //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                        $AutoPlayInterval: 4000, //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                        $PauseOnHover: 1, //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                        $ArrowKeyNavigation: true, //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                        $SlideDuration: 500, //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                        $MinDragOffsetToSlide: 20, //[Optional] Minimum drag offset to trigger slide , default value is 20
                        //$SlideWidth: 1000,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                        //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                        $SlideSpacing: 6, //[Optional] Space between each slide in pixels, default value is 0
                        $DisplayPieces: 1, //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                        $ParkingPosition: 0, //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                        $UISearchMode: 1, //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                        $PlayOrientation: 1, //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                        $DragOrientation: 0, //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                        $ThumbnailNavigatorOptions: {
                            $Class: $JssorThumbnailNavigator$, //[Required] Class to create thumbnail navigator instance
                            $ChanceToShow: 2, //[Required] 0 Never, 1 Mouse Over, 2 Always

                            $ActionMode: 1, //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                            $AutoCenter: 3, //[Optional] Auto center thumbnail items in the thumbnail navigator container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 3
                            $Lanes: 1, //[Optional] Specify lanes to arrange thumbnails, default value is 1
                            $SpacingX: 0, //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
                            $SpacingY: 0, //[Optional] Vertical space between each thumbnail in pixel, default value is 0
                            $DisplayPieces: 9, //[Optional] Number of pieces to display, default value is 1
                            $ParkingPosition: 0, //[Optional] The offset position to park thumbnail
                            $Orientation: 1, //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
                            $DisableDrag: true                              //[Optional] Disable drag or not, default value is false
                        }, //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                        $ArrowNavigatorOptions: {
                            $Class: $JssorArrowNavigator$, //[Required] Class to create thumbnail navigator instance
                            $ChanceToShow: 2, //[Required] 0 Never, 1 Mouse Over, 2 Always
                            $Steps: 1
                        }
                    };

                    function sliderObject(container, options) {
                        return new $JssorSlider$(container, options);
                    }
                    
                    //you can remove responsive code if you don't want the slider scales while window resizes
                    function ScaleSlider(slider) {
                        var parentWidth = slider.$Elmt.parentNode.clientWidth;
                        if (parentWidth) {
                            var sliderWidth = parentWidth;

                            //keep the slider width no more than 602
                            sliderWidth = Math.min(sliderWidth, 560);

                            slider.$ScaleWidth(sliderWidth);
                        }
                        else
                            window.setTimeout(ScaleSlider, 30);
                    }
                    
                    if( $('#redeem_container').length!=0){
                      var jssor_slider2 = sliderObject('redeem_container', options);
                      ScaleSlider(jssor_slider2);
                    }
                    
                    function jsssss1(){
	                    if( $('#redeem_container1').length!=0){
	                     var jsssss1 = sliderObject('redeem_container1', options);
	                      ScaleSlider(jsssss1);
	                    }
	                    return true;
                    }
                    
                    
                    $(window).bind("load", function() {
                      if( $('#redeem_container').length!=0){
                      	ScaleSlider(jssor_slider2);
                      }
                      jsssss1();
                    });
                    $(window).bind("resize", function() {
                          if( $('#redeem_container').length!=0){
                      		ScaleSlider(jssor_slider2);
                    	  }
                    	  jsssss1();
                    });
                    $(window).bind("orientationchange", function() {
                          if( $('#redeem_container').length!=0){
                      		ScaleSlider(jssor_slider2);
                    	  }
                    	  jsssss1();
                    });
                    //responsive code end
                });

                function saveprofileImgUrl(url, user_id) {
                    $.ajax({
                        type: "POST",
                        data: {id: user_id, profile_img_url: url},
                        url: "/buzzydoc/saveuserprofileimage/",
                        success: function(result) {
                            window.location = location.href;
                        }});
                }

                $('#dialogUploadPic').hide();
                var user_id = "<?php echo $userDashboard['userDetail']['id']; ?>";
                var email = "<?php echo $userDashboard['userDetail']['email']; ?>";
                $(document).on('ready', function() {

                    $('#tabs-3').find('img').on('click', function() {
                        saveprofileImgUrl($(this).attr('src'), user_id);
                    });
                    $('#fileUploadPc').on('filepreupload', function(event, data, previewId, index, jqXHR) {
                        var form = data.form, files = data.files, extra = data.extra,
                                response = data.response, reader = data.reader;
                        console.log('File pre upload triggered');
                    });
                    $("#fileUploadPc")
                            .fileinput({
                                uploadUrl: "/api/editprofileimage.json", // server upload action
                                uploadAsync: true,
                                dropZoneEnabled: false,
                                showCaption: false,
                                showUpload: false,
                                allowedFileExtensions: ['jpg', 'gif', 'png'],
                                allowedFileTypes: ['image'],
                                uploadExtraData: {
                                    user_id: user_id,
                                    email: email,
                                },
                                maxFileCount: 1,
                                previewSettings: {
                                    image: {width: "120px", height: "120px"}}
                            });
                });
                
                $('#fileUploadPc').on('fileuploaded', function(event, data, previewId, index) {
                    var form = data.form, files = data.files, extra = data.extra,
                            response = data.response, reader = data.reader;
                    window.location = location.href;
                });
                
                $('#fileUploadPc').on('filebrowse', function(event) {
                	$('#fileUploadPc').fileinput('reset');
				    console.log("File browse triggered.");
				});
                
                
                $("#tabs").tabs();
                // This is called with the results from from FB.getLoginStatus().
                function statusChangeCallback(response) {
                    console.log('statusChangeCallback');
                    console.log(response);
                    // The response object is returned with a status field that lets the
                    // app know the current login status of the person.
                    // Full docs on the response object can be found in the documentation
                    // for FB.getLoginStatus().
                    if (response.status === 'connected') {
                        // Logged into your app and Facebook.
                        testAPI();
                    } else if (response.status === 'not_authorized') {
                        // The person is logged into Facebook, but not your app.
                        document.getElementById('status').innerHTML = 'Please log ' +
                                'into this app.';
                    } else {
                        // The person is not logged into Facebook, so we're not sure if
                        // they are logged into this app or not.
                        document.getElementById('status').innerHTML = 'Please log ' +
                                'into Facebook.';
                    }
                }


                // This function is called when someone finishes with the Login
                // Button.  See the onlogin handler attached to it in the sample
                // code below.
                function checkLoginState() {
                    FB.getLoginStatus(function(response) {
                        if (response.authResponse.userID) {
                            var fbUrl = "https://graph.facebook.com/" + response.authResponse.userID + "/picture?type=large";
                            saveprofileImgUrl(fbUrl, user_id);
                        }
                        statusChangeCallback(response);
                    });

                }


                window.fbAsyncInit = function() {
                    FB.init({
                        appId: '890250871038456',
                        cookie: true, // enable cookies to allow the server to access
                        // the session
                        xfbml: true, // parse social plugins on this page
                        version: 'v2.2' // use version 2.2
                    });
                    // Now that we've initialized the JavaScript SDK, we call
                    // FB.getLoginStatus().  This function gets the state of the
                    // person visiting this page and can return one of three states to
                    // the callback you provide.  They can be:
                    //
                    // 1. Logged into your app ('connected')
                    // 2. Logged into Facebook, but not your app ('not_authorized')
                    // 3. Not logged into Facebook and can't tell if they are logged into
                    //    your app or not.
                    //
                    // These three cases are handled in the callback function.

                    FB.getLoginStatus(function(response) {
                        statusChangeCallback(response);
                    });
                };
                // Load the SDK asynchronously
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id))
                        return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
                // Here we run a very simple test of the Graph API after login is
                // successful.  See statusChangeCallback() for when this call is made.
                function testAPI() {
                    console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me', function(response) {
                        console.log('Successful login for: ' + response.name);
                        //$('#choose_pic').html('');
                        //$('#choose_pic').append('<img src="https://graph.facebook.com/"+response.id+"/picture?type=large">');
                        console.log(response.id);
                    });
                }

                $(function() {
                    var dialog = $("#dialogUploadPic").dialog({
                        autoOpen: false,
                        draggable: true,
                        width: 590,
                        buttons: {
                            "Cancel": {
                                click: function() {
                                    $(this).dialog("close");
                                },
                                text: 'Cancel',
                                class: 'btn btn-xs btn-danger'
                            }

                        },
                        close: function() {
                        }
                    });
                    $("#choose_pic").on("click", function() {
                        $('.ui-dialog-titlebar').attr('style', 'background-color: #eff3f8');
                        $('.ui-dialog-titlebar').removeClass('ui-widget-header');

                        dialog.dialog("open");
                    });
                });
                $(document).on('click', '#edit-profile', function() {
                    $('#user-profile .profile-update-error').remove();
                    $('#user-profile .update-button-container').remove();
                    $("#tab-profile").trigger("click");
                    var fname = $('.fname-container .span-value-medium').html();
                    $('.fname-container .span-value-medium').remove();
                    $('.fname-container').append('<input type="text" maxlength="20" class="input" id="input-fname" value="' + fname + '"/>');
                    obj.push({key: 'fname', value: fname});
                    var lname = $('.lname-container .span-value-medium').html();
                    $('.lname-container .span-value-medium').remove();
                    $('.lname-container').append('<input type="text" maxlength="20" class="input" id="input-lname" value="' + lname + '"/>');
                    obj.push({key: 'lname', value: lname});
                    var add1 = $('.add1-container .span-value-medium').html();
                    $('.add1-container .span-value-medium').remove();
                    $('.add1-container').append('<textarea type="text" maxlength="100" class="input full-width" id="input-add1" >' + add1 + '</textarea>');
                    obj.push({key: 'add1', value: add1});
                    var add2 = $('.add2-container .span-value-medium').html();
                    $('.add2-container .span-value-medium').remove();
                    $('.add2-container').append('<textarea type="text" maxlength="100" class="input full-width" id="input-add2" >' + add2 + '</textarea>');
                    obj.push({key: 'add2', value: add2});
                    var stateval = $('#statedd').html();
                    var cityval = $('#city').html();
                    $.ajax({
                                            type: "POST",
                                            data: "state=" + stateval + '&city=' + cityval,
                                            dataType: "json",
                                            url: "<?php echo Staff_Name.'Buzzydoc/getcitystate' ?>",
                                            success: function(result) {
                                                $('#statedd').remove();
                                                $('.state-container').html(result.state);
                                                $('#city').remove();
                                                $('.city-container').html(result.city);
                                            }
                                        });
                    var zip = $('.zip-container .span-value-medium').html();
                    $('.zip-container .span-value-medium').remove();
                    $('.zip-container').append('<input type="text"  maxlength="6" class="input" id="input-zip" value="' + zip + '"/>');
                    obj.push({key: 'zip', value: zip});
                    //var contact = $('.contact-container .span-value-medium').html();
                    var contact = $('.contact-container .span-value-medium').text();
                    $('.contact-container .span-value-medium').remove();
                    $('.contact-container').html('<input type="text" minlength="7" maxlength="10" class="input" id="input-contact" value="' + contact + '">');
                    obj.push({key: 'contact', value: parseInt(contact)});
                    var currentpass = $('.currentpass-container .span-value-medium').text();
                    $('.currentpass-container .span-value-medium').remove();
                    $('.currentpass-container').html('<input type="password" maxlength="50" class="input" id="input-currentpass" value="">');
                    obj.push({key: 'currentpass', value: currentpass});
                    var newpass = $('.newpass-container .span-value-medium').text();
                    $('.newpass-container .span-value-medium').remove();
                    $('.newpass-container').html('<input type="password" maxlength="50" class="input" id="input-newpass" value="">');
                    obj.push({key: 'newpass', value: newpass});
                    var confpass = $('.confpass-container .span-value-medium').text();
                    $('.confpass-container .span-value-medium').remove();
                    $('.confpass-container').html('<input type="password" maxlength="50" class="input" id="input-confpass" value="">');
                    obj.push({key: 'confpass', value: confpass});
                    //custom date editable
                    var dob = $('.dob-container .span-value-medium').html();
                    $('.dob-container .span-value-medium').remove();
                    $('.dob-container').append('<span class="datepicker editable editable-click" data-format="yyyy-mm-dd" id="dob" >' + dob + '</span>');
                    obj.push({key: 'dob', value: dob});
                    var ToEndDate = new Date();
                    $('#dob').editable({
                        type: 'adate',
                        date: {
                            //datepicker plugin options
                            format: 'yyyy-mm-dd',
                            viewformat: 'yyyy-mm-dd',
                            weekStart: 1,
                            endDate: ToEndDate,
                            title: 'Enter Date of Birth*',
                            emptytext: '&nbsp;&nbsp;&nbsp;&nbsp;',
                            //,nativeUI: true//if true and browser support input[type=date], native browser control will be used
                        }
                    });
                    $('.gender-container .span-value-medium').addClass('hide');
                    $('.editGen').removeClass('hide');
                    obj.push({key: 'gender', value: $('.gender-container .span-value-medium').html()});
                    var email = $('.email-container .span-value-medium').html();
                    $('.email-container .span-value-medium').remove();
                    $('.email-container').append('<input type="text" class="input" id="input-email" value="' + email + '"/>');
                    obj.push({key: 'email', value: email});
                    if ($('.age-container .span-value-medium').html() >= 18 || $('.age-container .span-value-medium').html() == 0) {
                        $('.add-email-outer-container').addClass('disabled');
                        $('.add-email-outer-container').hide();
                    } else {
                        var addEmail = $('.add-email-outer-container .span-value-medium').html();
                        $('.add-email-container .span-value-medium').remove();
                        $('.add-email-container').append('<input type="text" class="input" id="input-add-email" value="' + addEmail + '"/>');
                        obj.push({key: 'addEmail', value: addEmail});
                        $('.add-email-outer-container').removeClass('disabled');
                        $('.add-email-outer-container').show();
                    }
                    $('#edit-profile').attr('id', 'edit-profile-edited');
                    $('#user-profile .user-profile-data-container').append('<div class="text-center update-button-container margin-small"><span class="btn btn-danger cancel-edit margin-right-small">Cancel</span><span class="btn btn-success confirm-update margin-left-small">Update</span></div>')
                });

    function getstate(){
                        $.post("/buzzydoc/getcity", {
                            state_code: $('#state').val()
                        }, function(r) {
                            if (r) {
                                $('input').blur();
                                $('.city-container').html('<select id="citydd" name="cityDd" style="width:180px;">' + r + '</select>');
                            }
                        });
                    }

    $("#recommen_btn").click(function() {
        $("#refer_load").css("display", "block");
//            $('input[type="button"]').attr('disabled', 'disabled');
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var email = $("#email").val();
        var clinic_id = $("#ref_clinic_id").val();
        var user_id =<?php echo $sessionbuzzy->User->id; ?>;
        var user_email = "<?php echo $sessionbuzzy->User->email; ?>";
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();
        var message = $("#massage").val();
        var clinic_select = $('#clinic_list').val();

        var doctor_id = 0;
        if ($.trim(clinic_select) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please select clinic");
        }
        else if ($.trim(first_name) == '') {
$("#refer_load").css("display", "none");
//                $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter first name");
        } else if ($.trim(last_name) == '') {
$("#refer_load").css("display", "none");
//                $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter last name");
        } else if ($.trim(email) == '') {
$("#refer_load").css("display", "none");
//                $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter email");
        } else if (!regex.test(email)) {
$("#refer_load").css("display", "none");
//                $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter valid email");
        } else if ($.trim(message) == '') {
$("#refer_load").css("display", "none");
//                $('input[type="button"]').removeAttr('disabled');
            $("#status_error_reco").html("Please enter recommendation");
        } else {
            $("#reco-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/recommend/",
                data: "&clinic_id=" + clinic_id + "&first_name=" + first_name + "&last_name=" + last_name + "&email=" + email + "&message=" + message + "&user_id=" + user_id + "&doctor_id=" + doctor_id + "&user_email=" + user_email,
                success: function(msg) {
                    $("#reco-status-bar").html('');
                    obj = JSON.parse(msg);

                    if (obj.success == 1 && obj.data == 'Recommended Successfully') {
//                            $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error_reco").html("Recommended Successfully");
                        $("#refer_load").css("display", "none");
                        location.reload();

                    }
                    if (obj.success == 1 && obj.data == 'Already Recommended') {
//                            $('input[type="button"]').attr('disabled', 'disabled');

                        $("#status_error_reco").html("Email Id already Registered.");
                        $("#refer_load").css("display", "none");

                    }
                    if (obj.success == 0 && obj.data == 'Bad Request') {

                        $("#status_error_reco").html("Try again leter.");
                        $("#refer_load").css("display", "none");
                    }

                }
            });
        }

    });
            </script>

