<?php $sessionstaff = $this->Session->read('staff'); ?>
<div class="pull-left col-md-12 col-sm-12 col-xs-12 rightcol">
   <?php 

   if( ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || ($sessionstaff['staffaccess']['AccessStaff']['levelup']!=1 && $sessionstaff['staffaccess']['AccessStaff']['milestone_reward']!=1))  && $sessionstaff['is_buzzydoc']==1){ ?>
    <div class="balance_box_header">
    <?php echo 'Current Balance';  ?>
    </div>
    <?php } ?>
    <div class="balancebox">
		  <?php
                    $existingtretment=array();
          foreach($sessionstaff['customer_info']['treatment_over'] as $tret){
              $existingtretment[]=$tret['treatment_id'];
          }
                  $treatmentrunning=0;
                  foreach($sessionstaff['customer_info']['visitcheck'] as $treat){
                                            if(in_array($treat['UpperLevelSetting']['id'],$existingtretment)){
                                                $treatmentrunning++;
                                            }
                                            }
                            $exppoint=$sessionstaff['customer_info']['total_points'];
                           $pt=explode('(',$exppoint);
			  	if(count($pt)>1){                 
                                    $gp=rtrim($pt[1],')');
                    if( ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || ($sessionstaff['staffaccess']['AccessStaff']['levelup']!=1 && $sessionstaff['staffaccess']['AccessStaff']['milestone_reward']!=1))  && $sessionstaff['is_buzzydoc']==1){ ?>
        <div class="balance clearfix">
            <span class="balanceheading">BuzzyDoc Points</span>
            <span class="balancePoint">
           <?php if($gp==''){ echo 0; }else{ echo round($gp); }?>
            </span>
        </div>
        <?php }
        if($sessionstaff['staffaccess']['AccessStaff']['milestone_reward']==1 && $sessionstaff['is_buzzydoc']==1){ ?>
        <div class="balance clearfix">
            <span class="balanceheading">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="ace-icon fa fa-info-circle"></i>
                </a>
                <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                    <li class="dropdown-footer">
                    <?php echo $firstpoint; ?>
                    </li>
                </ul>Lifetime Points</span>
            <span class="balancePoint">
           <?php echo round($sessionstaff['customer_info']['lifetime_points']);?>
            </span>
        </div>
        <?php } if($sessionstaff['is_buzzydoc']==0){ ?>
        <div class="balance clearfix">
            <span class="balanceheading">Legacy Points</span>
            <span class="balancePoint">
           <?php if($pt[0]==''){ echo 0; }else{ echo $pt[0]; }?>
            </span>
        </div>
        <?php if($gp>0){ ?>
        <div class="balance clearfix">
            <span class="balanceheading">BuzzyDoc Points</span>
            <span class="balancePoint">
           <?php if($gp==''){ echo 0; }else{ echo round($gp); }?>
            </span>
        </div>
        <?php }} }else{ ?>
        <div class="balance clearfix">
            <span class="balanceheading">Balance</span>
            <span class="balancePoint">
           <?php if($sessionstaff['customer_info']['total_points']==''){ echo 0; }else{ echo $sessionstaff['customer_info']['total_points']; }?>
            </span>
        </div>
                                <?php }
                                if($sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['milestone_reward']==1){ ?>
        <div class="balance clearfix">
            <span class="balanceheading">VIP Points</span>
            <span class="balancePoint1">
           <?php echo $vip_points;?>
            </span>
        </div>
        <?php }

                                if($sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['tier_setting']==1 && $sessionstaff['customer_info']['User']['id']!=0){ 

                                ?>
        <div class="balance clearfix">
            <span class="balanceheading">
                Points Earned This Cycle</span>
            <span class="balancePoint2">
                <?php echo $AcceleratedPoints['TotalPoints'];?> points earned so far. <br />
<?php echo $AcceleratedPoints['Days'];?> days left in cycle.
            </span>
        </div>
                                <?php } ?>
    </div>
</div>
<div class="">&nbsp;</div>
<?php if(!empty($coupon_list)){ ?>
<div class="balance_box_header">
                                        <?php echo 'Current Certificates';  ?>
</div>
<div class="">&nbsp;</div>
    <?php 
}
    foreach($coupon_list as $coupon){ ?>
<div class="well well-sm clearfix" id="coupon_show_<?php echo $coupon['ProductService']['tid'];  ?>">
    <h4 class="lighter no-margin-bottom coupon_display">
        <img class="img-responsive" alt="Coupon yet to be redeemed." title="Coupon yet to be redeemed." src="<?php echo S3Path.$coupon['ProductService']['coupon_image'];  ?>" height="25px" width="50px">
        <span onclick="showredeempop(<?php echo $coupon['ProductService']['tid'];  ?>)" title="Coupon yet to be redeemed." style="font-size:20px;color: #4C718A;font-weight: 700; display:block; text-align:center;">
        <?php echo $coupon['ProductService']['title'];  ?></span>
        <span onclick="showredeempop(<?php echo $coupon['ProductService']['tid'];  ?>)" title="Coupon yet to be redeemed." style="font-size:11px; font-style:italic; display:block;color: #62A8D1; text-align:center;cursor: pointer;margin-top: 5px;">
            (Click to Redeem)</span>
    </h4>
</div>
    <?php } ?>


<?php if($sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['tier_setting']==1 && $sessionstaff['customer_info']['User']['id']!=0){  ?>

<div class="tabbable tabs-left">
    <div class="tab-content" style="overflow:visible;">
        <div class="page-header">
            <h1> <?php echo $current_tier['tier_name']; ?></h1>
            <div class="Accelerated1">( Earning at <?php echo $current_tier['earning']; ?>% )</div>
        </div>
        <?php if($current_tier['next_earning']!=''){ ?>
        <div class="Accelerated">Earn <?php echo $current_tier['more_points']; ?> more points to next level</div>
        <?php }else{ if($current_tier['more_points']=='achieved'){?>
        <div class="Accelerated">Congrats You have achieved highest level.</div>
        <?php }else{ ?>
        <div class="Accelerated">Earn <?php echo $current_tier['more_points']; ?> more points to achieve your certificate</div> 
       <?php }} if($current_tier['more_points']=='achieved'){ }else{ ?>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $current_tier['persent_achive']; ?>%">
            </div>
        </div>
       <?php } ?>
        <div class="Accelerated">
            <?php if($current_tier['next_earning']!=''){ ?>Earn at <?php echo $current_tier['next_earning']; ?>% on next level.<?php }else{  } ?>
            <a data-toggle="dropdown" class="dropdown-toggle" href="#" style="float:right;">
                View Details
            </a>
            <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav" style="right: 40px;top: 209px;">
                <li class="dropdown-footer">
                        <?php  
       foreach($tier_achived as $tier=>$tierval){  
       ?>
                    <h4 class="lighter no-margin-bottom coupon_display">
                        <b><?php echo $tier; ?>:</b>
                        <span style="font-size:14px; display:block; text-align:left;margin-top: 5px;">
        <?php echo $tierval['dayLeft_MorePoint']; ?></span>
                    </h4>

           <?php } ?>

                </li>
            </ul>
        </div>
    </div>
</div>
<?php } ?>
<div class="">&nbsp;</div>
<div class="pull-left col-md-12 col-sm-12 col-xs-12 rightcol margin-top">
    <?php 
    if($treatmentrunning>0 && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1) && $sessionstaff['is_buzzydoc']==1){ ?>
    <div class="balance_box_header btn btn-sm btn-info" id="id-btn-dialog-end" style="cursor:pointer;"><?php echo 'End of Plan';  ?></div>
    <?php } ?>
</div>
<div class="">&nbsp;</div>
       <?php if(!empty($sessionstaff['customer_info']['visithistory']) && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1) && $sessionstaff['is_buzzydoc']==1){ 
           $run=0;
           foreach ($sessionstaff['customer_info']['visithistory'] as $data=>$vhistory) {
                        if($vhistory['status']==0){
                            $run++;  
                        }
                        }
                        if($run>0){
           ?>
<!-- #section:elements.tab.position -->
<div class="tabbable tabs-left">
    <div class="tab-content">
                         <?php 
                        $nv=1;
                        $phase1='';
                        $phase2='';
                        $phase3='';
                        $vi1=0;
                        foreach ($sessionstaff['customer_info']['visithistory'] as $data=>$vhistory) {
                        if($vhistory['status']==0){
                            ?>
        <p>
        <div class="page-header">
            <h1> <?php echo $data; ?> </h1>
        </div>
                                <?php
                            $levecomp=0;
                                foreach($vhistory['record'] as $vs){
                                if($vs['perfect']=='Perfect')
                                   $levecomp++; 
                            }
                            $totalcomp=$levecomp;
                            //calculation for interval reward plan.
                            if($vhistory['treatment_details']['interval']==1){
                             $totalvisitcomp=($vhistory['interval_details']['Visit']*100)/$vhistory['treatment_details']['total_visits'];
                            }else{
                            $totalvisitcomp=($totalcomp*100)/$vhistory['treatment_details']['total_visits'];
                            }
        if($vhistory['treatment_details']['interval']==1){
                            $totalvisitcomp=($vhistory['interval_details']['Visit']*100)/$vhistory['treatment_details']['total_visits']; ?>
        <div class="col-sm-12"> <?php echo $vhistory['treatment_details']['total_visits']-$vhistory['interval_details']['Visit']; ?> more perfect visit to achieve <?php echo $vhistory['interval_details']['Phase']; ?></div>
        <div class="col-sm-12">&nbsp;</div>
                                <?php
                                } ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="sample-table-1" class="table table-striped table-bordered table-hover" >
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xs-8">
                        <!-- #section:elements.progressbar -->
                             <?php 
                             $p==0;
                             foreach($vhistory['treatment_details']['phase_distribution'] as $phaseset){
                                 if($p==0)
                                                    $css=' progress-bar-success';
                                                if($p==1)
                                                    $css=' progress-bar-warning';
                                                if($p==2)
                                                    $css=' progress-bar-purple';
                                                if($p==3)
                                                    $css=' progress-danger';
                                                if($p==4)
                                                    $css=' progress-bar-pink';
                                                 if($levecomp>$phaseset['PhaseDistribution']['visits'] && $levecomp>0){
                            $phase=100;
                            $levecomp=$levecomp-$phaseset['PhaseDistribution']['visits'];
                            }else if($levecomp>0){
                            $phase=($levecomp*100)/$phaseset['PhaseDistribution']['visits'];
                            $levecomp=$levecomp-$phaseset['PhaseDistribution']['visits'];   
                            }else{
                             $phase=0;
                            $levecomp=0;      
                            }
                            if($vhistory['treatment_details']['interval']==1){
                                $phase=$totalvisitcomp;
                                $phase_name=$vhistory['interval_details']['Phase'];
                            }else{
                                $phase_name=$phaseset['PhaseDistribution']['phase_name'];
                            }
                                                ?>
                        <div class="progress pos-rel" data-percent="<?php echo $phase_name; ?> (<?php echo round($phase,1); ?>%) ">
                            <div class="progress-bar<?php echo $css; ?>" style="width:<?php echo $phase; ?>%;"></div>
                        </div>
                                            <?php
                                            if($p==4)
                                                $p=0;
                                 $p++;             
                            }$p=0; ?>
                    </div><!-- /.col -->
                    <div class="col-xs-4 center">
                        <!-- #section:plugins/charts.easypiechart -->
                        <div class="easy-pie-chart percentage" data-percent="<?php echo $totalvisitcomp; ?>" data-color="#D15B47">
                            <span class="percent"><?php echo round($totalvisitcomp); ?></span>%
                        </div>
                                        <?php if($vhistory['treatment_details']['interval']==1){ echo $vhistory['interval_details']['Visit']."/".$vhistory['treatment_details']['total_visits'];}else{ echo $totalcomp."/".$vhistory['treatment_details']['total_visits']; }?>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.col -->
        </table>
                <?php $nv++; ?></p>
                        <?php $vi1++;}} ?>
    </div>
</div>
<!-- /section:elements.tab.position -->
       <?php }} ?>
<div id="dialog-message_endtreatment" class="hide">
    <div class="row inquerybox1">
        <!--<div class="col-xs-12 clearfix inquerybox2">-->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
            <form action="" method="POST" name="end_treatment_form" id='end_treatment_form' >
                <table border='0' width='100%'>
                    <tr>
                        <td>Treatment<span style='color:red;'>*</span> </td>
                        <td>
                            <div class="relative">
                                <input type="hidden" id="treatment_user_id" name="treatment_user_id" value="<?php echo $sessionstaff['customer_info']['User']['id'] ?>">
                                <select id="active_treatment_id" name="active_treatment_id">
                                        <?php 
                                        foreach($sessionstaff['customer_info']['visitcheck'] as $treat){
                                            if(in_array($treat['UpperLevelSetting']['id'],$existingtretment)){
                                            ?>
                                    <option value="<?php echo $treat['UpperLevelSetting']['id']; ?>"><?php echo $treat['UpperLevelSetting']['treatment_name']; ?></option>
                                            <?php }} ?>
                                </select>
                                <div class="fix"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value='End Of Plan' id='end_treatment' class="btn btn-primary buttondflt back_icon btn-sm"  onclick="return activatetreatment();"></td>
                    </tr>
                    <tr>
                        <td  colspan='2'><span id='treatment_status_div' style="display:none"><?php echo $this->html->image(CDN.'img/loading.gif');?> &nbsp;Please wait...</span></td>
                    </tr>
                    <tr>
                        <td colspan='2' id='treatment_error' style='color:green;margin-left:100px;'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>   <!--popup--> 
<div id="dialog-message_redeem-coupon" class="hide">
    <div class="row inquerybox1">
        <!--<div class="col-xs-12 clearfix inquerybox2">-->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
            <form action="" method="POST" name="coupon_redeem_form" id='coupon_redeem_form' >
                <table border='0' width='100%'>
                    <tr><td colspan=2>Update the status of the patient's coupon once it has been successfully redeemed in office</td></tr>
                    <tr>
                        <td>Status<span style='color:red;'>*</span> </td>
                        <td>
                            <div class="relative">
                                <input type="hidden" id="coupon_id" name="coupon_id" value="">
                                <select name="redeem_status_coupon" id="redeem_status_coupon" >
                                    <option value="Active" selected="selected">Active</option>
                                    <option value="Redeemed">Redeemed</option> 
                                </select>
                                <div class="fix"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value='Redeem' class="btn btn-primary buttondflt back_icon btn-sm"  onclick="return changecouponstatus(<?php echo $sessionstaff['clinic_id']; ?>);"></td>
                    </tr>
                    <tr>
                        <td  colspan='2'><span id='coupon_status_div' style="display:none"><?php echo $this->html->image(CDN.'img/loading.gif');?> &nbsp;Please wait...</span></td>
                    </tr>
                    <tr>
                        <td colspan='2' id='coupon_redeem_success' style='color:green;margin-left:100px;'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>   <!--popup--> 
<script>
    function changecouponstatus(clinic_id) {
        var tid = $('#coupon_id').val();
        var status_name = $("#redeem_status_coupon").val();
        var id = tid;
        var r = confirm("Are you sure to change status ?");
        if (r == true)
        {
            $('#coupon_status_div').show();
            $.ajax({
                type: "POST",
                url: "/StaffRedeem/changeredeemstatusxml/",
                data: "&id=" + tid + "&status=" + status_name + "&clinic_id=" + clinic_id,
                success: function(msg) {
                    if (msg == 1) {
                        $('#redeem_status_' + tid).attr('disabled', 'disabled');
                    }
                    $('#coupon_status_div').hide();
                    $('#coupon_redeem_success').html('Status changed successfully.');
                    $("#coupon_" + tid).html('');
                    $('#coupon_show_' + tid).hide();
                }
            });
        } else
        {
            return false;
        }
    }

    function activatetreatment() {

        var r = confirm("are you sure to end treatment successfully?");

        if (r == true)
        {
            var treatment_id = $('#active_treatment_id').val();
            var user_id = $('#treatment_user_id').val();
            $("#treatment_status_div").show();
            var datasrc = "treatment_id=" + treatment_id + '&user_id=' + user_id;

            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>PatientManagement/deactivatetreatment/",
                success: function(result) {
                    if (result == '') {
                        $("#treatment_status_div").hide();
                        alert('End of treatment successfully');
                        location.reload();
                    } else {
                        $("#treatment_status_div").hide();
                        alert('End of treatment successfully');
                        location.reload();
                    }
                }
            });
        } else {
            return false;
        }


    }

    $("#id-btn-dialog-end").on('click', function(e) {
        e.preventDefault();
        var dialog = $("#dialog-message_endtreatment").removeClass('hide').dialog({
            modal: true,
            title: "End Of Plan:",
            title_html: true,
        });

    });
    function showredeempop(id) {
        //e.preventDefault();
        $('#coupon_id').val(id);
        $('#coupon_status_div').hide();
        $('#coupon_redeem_success').html('');
        $('#redeem_status_coupon').val();
        var dialog = $("#dialog-message_redeem-coupon").removeClass('hide').dialog({
            modal: true,
            title: "Redeem Coupon:",
            title_html: true,
        });
    }
</script>



