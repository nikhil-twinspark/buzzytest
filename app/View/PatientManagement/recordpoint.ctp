<?php

echo $this->Html->script(CDN.'js/jssor.js');
echo $this->Html->script(CDN.'js/jssor.slider.js'); ?>

<style>
    .blur2 {
        text-decoration: line-through;
    }
    .panel {
        border: 1px solid #2fb889;
    }
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
        font-family: 'open sans';
        font-size: 18px;
        font-weight: bold;
    }
    .needRedeemPoints {
        font-size: 16px;
        height: 44px;
        padding: 12px 0;
    }
    .redeemBox h3 {
        color: #A7A7A7;
        display: inline-block;
        font-family:'Open Sans';
        line-height: 18px;
        min-height: 40px;
        overflow: hidden;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        display: block;
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
    .reedem-modalbox .showOnclick, .reedem-modalbox .showOnclick1{
        width: 50%;
        display: block; 
    }
    .reedem-modalbox .showOnclick .dropdown-toggle, .reedem-modalbox .showOnclick1 .dropdown-toggle{
        display: block;
        margin-left: 154px;
        margin-top: 99px;
    }
</style>
<?php
$months = array(1 => 'Jan',2 => 'Feb',3 => 'Mar',4 => 'Apr',5 => 'May',6 => 'Jun',7 => 'Jul',8 => 'Aug',9 => 'Sept',10 => 'Oct',11 => 'Nov',12 => 'Dec');
$treatmentAssigned = 0;
$sessionstaff = $this->Session->read('staff');
$bonusTreatmentDetails = $sessionstaff['bonus_treatment_details'];
$bonusTreatments = $sessionstaff['bonus_treatment'];
if($treatmentFinished){
	$treatmentFinished = array_column($treatmentFinished,'treatment_id');
}
function mask_email( $email, $mask_char, $percent=50 )
{
        list( $user, $domain ) = preg_split("/@/", $email );
        $len = strlen( $user );
        $mask_count = floor( $len * $percent /100 );
        $offset = floor( ( $len - $mask_count ) / 2 );
        $masked = substr( $user, 0, $offset )
                .str_repeat( $mask_char, $mask_count )
                .substr( $user, $mask_count+$offset );
        $masked1 = mask_other($domain);
        if($masked1!=''){
         return( $masked.'@'.$masked1 );   
        }else{
            return( $masked);
        }
}
function mask_other( $email )
{
        $len = strlen($email);
$showLen = floor($len/2);
$str_arr = str_split($email);
for($ii=$showLen;$ii<$len;$ii++){
    $str_arr[$ii] = '*';
}
$em[0] = implode('',$str_arr); 
$new_name = implode('@',$em);
        return( $new_name);
}
?>
<div class="page-header">
    <h1>
        Patient Management
    </h1>
</div>
    <?php 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
                                    $pathis="";
                                     $pathrecord="";
                                     $pathinfo="";
                                     $pathhistory="";
                                     $pathbadge="";
                                     $more="";
                                if(isset($this->request->pass[0]) && $this->request->pass[0]==2){
                                     $pathis="active";
                                     $pathrecord="";
                                     $pathinfo="";
                                     $pathhistory="";
                                     $pathbadge="";
                                     $more="";
                                     
                                 }
                                 else if(isset($this->request->pass[0]) && $this->request->pass[0]==1){
                                     $pathrecord="active";
                                     $pathis="";
                                     $pathinfo="";
                                     $pathhistory="";
                                     $pathbadge="";
                                     $more="";
                                     
                                 }
                                 else if(isset($this->request->pass[0]) && $this->request->pass[0]==3){
                                     $pathinfo="active";
                                     $pathrecord="";
                                     $pathis="";
                                     $pathhistory="";
                                     $pathbadge="";
                                     $more="";
                                 }
                                 else if(isset($this->request->pass[0]) && $this->request->pass[0]==4){
                                     $pathinfo="";
                                     $pathrecord="";
                                     $pathis="";
                                     $pathbadge="";
                                     $pathhistory="active";
                                     $more="";
                                 }else if(isset($this->request->pass[0]) && $this->request->pass[0]==5){
                                     $pathinfo="";
                                     $pathrecord="";
                                     $pathis="";
                                     $pathhistory="";
                                     $pathbadge="active";
                                     $more="";
                                 }else if(isset($this->request->pass[0]) && $this->request->pass[0]==6){
                                     $pathinfo="";
                                     $pathrecord="";
                                     $pathis="";
                                     $pathhistory="";
                                     $pathbadge="";
                                     $more="active";
                                 }else{
                                    $pathrecord="active"; 
                                 }
?>
<div class="tabbable">
    <div class="patientname clearfix">Patient:  <b><?php
					$fname='';
					$lname='';
					if($sessionstaff['customer_info']['first_name']!=''){
					echo $fname=$sessionstaff['customer_info']['first_name'].' ';
					}
					if($sessionstaff['customer_info']['last_name']!=''){
					echo $lname=$sessionstaff['customer_info']['last_name'];
					}
					if((isset($fname) && $fname=='') && (isset($lname) && $lname=='')){
					echo $sessionstaff['customer_info']['card_number'];
					}
					?></b></div>
    <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
        <li class="<?=$pathrecord?>">
            <a data-toggle="tab" href="#home4">Record Points</a>
        </li>
        <li class="<?=$pathis?>">
            <a data-toggle="tab" href="#profile4">Points History</a>
        </li>
        <li class="<?=$pathinfo?>">
            <a data-toggle="tab" href="#dropdown14">Patient Info</a>
        </li>
        <?php 
if(!empty($sessionstaff['customer_info']['visithistory']) && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1)){  ?>
        <li class="<?php echo $pathhistory; ?>"><a data-toggle="tab" href="#history14">Progress Tracker</a></li><?php } ?>
                <?php 
if(isset($sessionstaff['customer_info']['usersBadge']) && !empty($sessionstaff['customer_info']['usersBadge'])){ ?>
        <li class="<?=$pathbadge?>">
            <a data-toggle="tab" href="#mybadges">Badges</a>
        </li><?php } ?>

<?php if($sessionstaff['fromclinic']==1 && $sessionstaff['is_buzzydoc']==1 && $sessionstaff['staffaccess']['AccessStaff']['staff_to_redeem']==1 && $sessionstaff['customer_info']['User']['id']>0){ ?>
        <li class="">

            <input type="hidden" id="global-point-value" name="global-point-value" value="<?php echo $PointsFromClinic; ?>" >
            <input type="hidden" id="point_type" value=""/>
            <input type="hidden" id="clinic_id" value=""/>
            <input type="hidden" id="buzz_point" value=""/>
            <div class="redeem-points-button-main-container" data-points="<?= (!empty($PointsFromClinic)) ? $PointsFromClinic : '0'; ?>" data-type="global">
                <a style="color: #fff;" class="btn btn-purple btn-sm">Redeem Points</a>
            </div>
        </li>
<?php } if(($sessionstaff['customer_info']['User']['email']!='' && $sessionstaff['fromclinic']==1) || ($sessionstaff['customer_info']['User']['id']>0 && $sessionstaff['fromclinic']==1) || ($sessionstaff['customer_info']['User']['blocked']==1) || (isset($sessionstaff['customer_info']['User']['is_verified']) && $sessionstaff['customer_info']['User']['is_verified']==0)){ ?>
        <li class="<?=$more?>" style="margin-left: 3px;">
            <a data-toggle="tab" href="#moreTab">More</a>
        </li>
<?php } ?>
    </ul>
    <div class="tab-content clearfix">
        <div id="home4" class="tab-pane patientHistory pull-left col-md-8 col-sm-12 col-xs-12 leftCol <?=$pathrecord?>">

            <?php 
            $manual_text="";
            if($sessionstaff['staffaccess']['AccessStaff']['product_service']==1 && $sessionstaff['staffaccess']['AccessStaff']['tier_setting']==1){ $manual_text="readonly='readonly'"; ?>

            <div class="form-group" id="amounttextfield">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Dollar Amount Spent:<span style="font-size:11px;"><i>(Aesthetic Procedures)</i></span></label>
                <div class="col-sm-9">
                    <input type="text" class="col-xs-12 col-sm-7" placeholder="Dollar Amount Spent" id="dollar_amount" maxlength="8" name="dollar_amount" onkeyup="getPointVal();">
                </div>
            </div>

                        <?php } ?>
            <div class="form-group clearfix"></div>

            <div id="goodMessage" class="message" style="display:none;"></div>
            <?php if($sessionstaff['is_buzzydoc']==1 && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1) && $sessionstaff['customer_info']['User']['id']>0 && $sessionstaff['customer_info']['clinic_id']==$sessionstaff['clinic_id']){
                $currenttreatment=array();
                foreach($sessionstaff['customer_info']['visitcheck'] as $treat){
                   $currenttreatment[]=$treat['UpperLevelSetting']['id'];
                }
                $remaintreatment=array();
                foreach($global_promotions as $key=>$glopromo){
              $id_name=explode('#-',$key);
              $tretment_name=str_replace('_', ' ', $id_name[1]);
              if(!in_array($id_name[0], $currenttreatment)){
                 $remaintreatment[]= $id_name[0];
               }}
                ?>
            <div class="col-xs-3 col-sm-5 strt-treat-btn">
                <input type="button" class="btn btn-primary btn-xs" id="start_treatment_btn" value="Start New Plan">
                <div id="new-treatment-dialog" title="Start New Plan" style="display:none;">
                    <style>
                        .start-treat-form { 
                            margin-top: 20px;
                        }
                        .start-treat-form .col-md-12 .checkbox label { 
                            padding-left: 0px;
                        }
                        .start-treat-form .col-md-12.start-treat-chck .row { 
                            margin-left: -12px;
                            margin-right: -12px;
                        }
                        .strt-treat-btn input { 
                            padding:10px;
                        }
                    </style>
                    <div class="col-md-12 start-treat-form">
                        <select id="gettreatment" name="gettreatment" class="col-md-12" onchange="getbonusarea();">
                            <option value="">Select a New Plan</option>
                    <?php foreach($global_promotions as $key=>$glopromo){
              $id_name=explode('#-',$key);
             
              $tretment_name=str_replace('_', ' ', $id_name[1]);
              if(!in_array($id_name[0], $userAssignedTreatments) && !in_array($id_name[0],$treatmentFinished) && $id_name[2]==1){
              $treatmentAssigned = 1;
              ?>
                            <option value="<?php echo $id_name[0];?>"><?php echo $tretment_name;?></option>
              <?php }} ?>
                        </select>
                        <div class="col-md-12 start-treat-chck">
                            <div class="row" id="bonusarea">
                            </div>
                        </div>
                    </div>
                </div>
                <!--  ------------------------------------------------  -->
            </div>
            <?php }?>

            <div style="display:block; clear:both; overflow:hidden;" class="clearfix">
                <div class="pull-left col-md-12 col-sm-12 col-xs-12 leftCol">
                    <form  class="form-horizo" id="add_transaction_form" name="add_transaction_form" method="POST" action="/PatientManagement/pointallocation">
         <?php
         if($sessionstaff['is_buzzydoc']==1 && $paymentcheck==0){ ?>
                        <div class="form-group" id="">
                            <a style="color: #000000;margin-left: 0;margin-top: 0; padding: 0 7px 2px;cursor: pointer;" href="<?php echo $this->Html->url(array(
							    "controller" => "UserStaffManagement",
							    "action" => "index"
        ));?>">Create super doctor and manage payment details before point allocation </a>
                        </div>
         <?php } ?>
                        <input type="hidden"  id="user_id" value="<?php echo $sessionstaff['customer_info']['User']['id']; ?>" name="user_id">
                        <input type="hidden"  id="card_number" value="<?php echo $sessionstaff['customer_info']['card_number']; ?>" name="card_number">
                        <input type="hidden"  id="first_name1" value="<?php if(isset($fname)){ echo $fname;}else{ echo ''; } ?>" name="first_name1">
                        <input type="hidden"  id="last_name1" value="<?php if(isset($lname)){ echo $lname;}else{ echo ''; } ?>" name="last_name1">
                        <input type="hidden"  id="searchclinic" value="<?php echo $sessionstaff['customer_info']['clinic_id']; ?>" name="searchclinic">
                        <input type="hidden"  id="calculate_amount" value="" name="calculate_amount">
                        <div class="form-group" id="accordion">
                            <?php if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['User']['id']!=0 && $sessionstaff['customer_info']['clinic_id']==$sessionstaff['clinic_id'] && ($sessionstaff['staffaccess']['AccessStaff']['levelup']==1 || $sessionstaff['staffaccess']['AccessStaff']['interval']==1)){ 
          $runningtretment=array();
          foreach ($userAssignedTreatments as $treatments) {
             $runningtretment[]=$treatments; 
          }
          $existingtretment=array();
          foreach($sessionstaff['customer_info']['treatment_over'] as $tret){
              $existingtretment[]=$tret['treatment_id'];
          }
          $glo=1;
          foreach($global_promotions_run as $key=>$glopromo){
              $id_name=explode('#-',$key);
              $tretment_name=str_replace('_', ' ', $id_name[1]);
              if(empty($glopromo['visits']) && $id_name[2]==0){
                  $checkdel=1;
              }else{
                  $checkdel=0;
              }
              if(in_array($id_name[0],$runningtretment) && !in_array($id_name[0],$treatmentFinished) && $checkdel==0 ){
              ?>
                            <h3 aria-selected="false" id="treatment_h3_<?php echo $id_name[0]; ?>" <?php if(in_array($id_name[0],$runningtretment)){ ?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?> ><?php echo $tretment_name ?>
                                <?php if(empty($glopromo['visits'])){ ?>
                                <a data-action="close" href="#"  onclick="removeTreatment('<?php echo $id_name[0]; ?>');" style="position: absolute; right: 5px; font-size: 14px; color: rgb(237, 50, 55);">
                                    <i class="ace-icon fa fa-times"></i>
                                </a>
                                <?php } unset($glopromo['visits']); ?>
                            </h3>
                            <div id="treatment_div_<?php echo $id_name[0]; ?>" style="height:auto;">
                                <div class="col-md-12" style="margin-bottom: 10px; color: #449d44;">
                                    <strong><?php if(!empty($bonusTreatmentDetails) && array_key_exists($id_name[0], $bonusTreatmentDetails) && in_array($id_name[0],$bonusTreatments)){
                            		echo "*Now that you've started the treatment, click the record button to save the transaction";
                            	}?></strong>
                                </div>
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">&nbsp;
                                </label>
                                <div class="col-sm-9">
                                    <?php foreach($glopromo as $gp){ ?>
                                    <div class="promo_check">
                                        <label id="label_<?php echo $id_name[0]; ?>_<?php echo $gp['id']; ?>"><input type="checkbox" class="col-xs-1 col-sm-1" id="global_promo_<?php echo $id_name[0]; ?>_<?php echo $gp['id']; ?>" name="global_promo[<?php echo $id_name[0]; ?>][]" value="<?php echo $gp['id']; ?>"><?php echo $gp['display_name']; ?></label >
                                        <i id="exempt_sign_<?php echo $id_name[0]; ?>_<?php echo $gp['id']; ?>" class="ace-icon fa fa-times" onclick="exmptPromotion(<?php echo $id_name[0]; ?>, <?php echo $gp['id']; ?>);"></i>
                                        <span id="exempt_span_<?php echo $id_name[0]; ?>_<?php echo $gp['id']; ?>" style="font-size: 9px;">Exempt</span>
                                    </div>
                                    <?php } ?>
                                    <input type="hidden" value="0" id="exempt_count_<?php echo $id_name[0]; ?>" name="exempt_count_<?php echo $id_name[0]; ?>">
                                </div>
                            </div>
                                <?php
              }
              $glo++;
                                    }  } 
                           if(!empty($promotions) ){ ?>

                            <h3 aria-selected="false">Ways To Earn:<br>
                            </h3>
                            <div  style="height:auto;"> 
                                 <?php 
                                 $m=0; 
                                 foreach($promotions as $promo){   ?>
                                <label class="col-sm-3 control-label align-right blue" for="form-field-1">


                                </label>
                                <div class="col-sm-9">


                                    <div class="promo_check">

                                        <label ><input type="checkbox" class="col-xs-1 col-sm-1" id="promo_<?=$promo['Promotion']['id']?>" name="promo_id[]" value="<?=$promo['Promotion']['id']?>"><?php if($promo['Promotion']['display_name']==''){ echo $promo['Promotion']['description']; }else{ echo $promo['Promotion']['display_name']; } ?>&nbsp;&nbsp;(<?php echo $promo['Promotion']['operand'].' '.$promo['Promotion']['value']; ?>)</label >
                                    </div>


                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>

                        </div>

                        <div class="form-group clearfix"></div>
                        <div class="form-group" id="amounttextfield">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Record Manual Points:</label>
                            <div class="col-sm-9">
                                <input type="text" class="col-xs-12 col-sm-7" placeholder="Amount" id="add_amount_textbox" maxlength="8" name="amount" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" <?php echo $manual_text; ?>>
                            </div>
                        </div>

                        <div class="form-group clearfix"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            <!--<span class="star">*</span> -->
        <?php if($sessionstaff['clinic_id']==5){ ?>Staff Initials:<?php }else { ?>Notes:<?php } ?></label>
                            <div class="col-sm-9"><textarea align="top" cols="20" rows="2" name="transaction_description" id="add_description_textbox" class="col-xs-10 col-sm-5"></textarea></div>
                        </div>
                        <div class="form-group">
                            &nbsp;
                        </div>
                        <?php if($sessionstaff['staffaccess']['AccessStaff']['staff_input']==1){ ?>
                        <div class="form-group clearfix">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Given By</label>
                            <div class="col-sm-9">
                                <select name="staff_id" id="staff_id" class="col-xs-10 col-sm-5" required>
                                    <option value=''>Select Staff</option>
                        <?php 
                        if(count($staffs)==1){
                            $autosel1='selected';
                        }else{
                            $autosel1='';
                        }
                        foreach($staffs as $staff){ ?>
                                    <option value="<?php echo $staff['Staff']['id']; ?>" <?php echo $autosel1; ?>><?php echo $staff['Staff']['staff_id']; ?></option>
                        <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <?php }else{
                            ?>
                        <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $sessionstaff['staff_id']; ?>" >
                        <?php
                        } ?>
                        <div class="form-group clearfix">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Treated By</label>
                            <div class="col-sm-9">
                                <select name="doctor_id" id="doctor_id" class="col-xs-10 col-sm-5" required>
                                    <option value=''>Select Doctor</option>
                        <?php 
                        if(count($doctors)==1){
                            $autosel='selected';
                        }else{
                            $autosel='';
                        }
                        foreach($doctors as $doc){ ?>
                                    <option value="<?php echo $doc['Doctor']['id']; ?>" <?php if($doc['Doctor']['default']==1){ echo "selected";}else{ echo $autosel;} ?>><?php echo $doc['Doctor']['first_name'].' '.$doc['Doctor']['last_name']; ?></option>
                        <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-offset-3 col-md-9 recordBtn">
       <?php
       if(($sessionstaff['is_buzzydoc']==1 && $paymentcheck==0) || $sessionstaff['permission']==0){ ?> <?php }else{ ?><button class="btn btn-info" id="recordButton">Record</button><?php } ?>
                        </div>   
                    </form>
                </div>
            </div>   
        </div>
        <div id="profile4" class="tab-pane pull-left col-md-8 col-sm-12 col-xs-12 <?=$pathis?>">
            <div class="pull-left col-md-12 col-sm-12 col-xs-12">
                <div class="patienthistory">
                    <div class="table-responsive">
						<?php if(count($transactions_type)>1){ ?>
                        <div id="transaction_type" class="pull-right clearfix">

                            <select name="transactionType" id="transactionType" style="color:#000;" onchange="transactionType();">
                                <option value="0">All Transactions</option> 
							<?php foreach($transactions_type as $ttype=>$tval){ ?>
                                <option value="<?php echo $ttype; ?>"><?php echo $tval; ?></option>
							<?php } ?>
                            </select>
                        </div>
							<?php } ?>
                        <div class="col-sm-12" style="padding:0; margin-top:15px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="allTransaction" class="table table-striped table-bordered table-hover" > 
                                <tr>
                                    <td width="10%" class="amountpositive">Delete</td>
                                    <td width="30%" class="amountpositive">Date</td>

                                    <td width="10%" class="amountpositive">Points</td>

                                    <td width="50%" class="amountpositive">For</td>
                                </tr>


       	<?php	if (!empty($transactions)) {
			foreach ($transactions as $transaction_info) { ?>
                                <tr>
                                    <td width="10%" class="firstCol">
                                    <?php if ($transaction_info['Transaction']['activity_type'] == 'Y' || $transaction_info['Transaction']['promotion_type'] == '1') { ?>
                                        <a title="Delete" href="javascript:void(0);"  class="btn btn-xs btn-danger" style="cursor:default;"><i class='ace-icon glyphicon glyphicon-trash grey'></i></a>
                                    <?php }else{ ?>
                                        <a title="Delete" href="javascript:void(0);"  class="btn btn-xs btn-danger" onclick="DeleteHistory(<?php echo $transaction_info['Transaction']['id'] ?>);"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
                                    <?php } ?>
                                    </td>
                                    <td width="30%"><?=$transaction_info['Transaction']['date']?>: </td>
         <?php	$amount_to_show = $transaction_info['Transaction']['amount'];
				
					if ($transaction_info['Transaction']['activity_type'] == 'Y') { ?>
                                    <td width="10%" class="amountpositive"><?php echo round($amount_to_show); ?></td>
         <?php } else { ?>
                                    <td width="10%" class="amountpositive">+<?php echo round($amount_to_show);?></td>
         <?php } ?>
                                    <td width="50%">
			 <?php
						echo ' &#8211; '.$transaction_info['Transaction']['authorization'];
						if($transaction_info['Transaction']['status']=='Active' && $transaction_info[Transaction]['activity_type']=='Y' && $transaction_info[Transaction]['amount']=='0'){
				  ?>

                                        <span id="coupon_<?php echo $transaction_info['Transaction']['id']; ?>">
                                            <select name="redeem_status_<?php echo $transaction_info['Transaction']['id']; ?>" id="redeem_status_<?php echo $transaction_info['Transaction']['id']; ?>" onchange="changestatus(<?php echo $transaction_info[Transaction][id]; ?>,<?php echo $sessionstaff['clinic_id']; ?>)">

    <?php if($transaction_info['Transaction']['status']!='Active'){
    	?>
                                                <option value="Redeemed" selected="selected">Redeemed</option>
    	<?php 
    }else{
    ?>

                                                <option value="Active" selected="selected">Active</option>
                                                <option value="Redeemed">Redeemed</option> 
   <?php 
   }
    ?>

                                            </select></span>
    <?php } ?>
                                    </td>

                                </tr>
        <?php }}else{ ?>
                                <tr>
                                    <td width="10%" colspan="4"> <?php if(!empty($sessionstaff['customer_info']['visithistory'])){ ?>
                                        No points have been earned yet,check progress bar to see the patient's status
                                    <?php }else{ ?>
                                        There are no activities!
                                    <?php } ?></td>

                                </tr>
        <?php } ?>
                            </table>
                        </div>
                    </div><!-- formarea-->
                </div>
            </div>
        </div>
        <div id="dropdown14" class="tab-pane col-md-8 col-sm-12 col-xs-12 pull-left <?=$pathinfo?>">    
<?php if (!empty($sessionstaff['customer_info'])) { ?>
            <div class="margin-top clearfix">
                <div class="col-md-12 col-sm-12 col-xs-12 pull-left ">
		 <?php if($sessionstaff['customer_info']['User']['status']!=1){ ?>
                    <p class="message_box">We've made some changes to make registering patients more efficient. The user 
		 <?php
					if($sessionstaff['customer_info']['first_name']!=''){
					echo $fname=$sessionstaff['customer_info']['first_name'].' ';
					}
					if($sessionstaff['customer_info']['last_name']!=''){
					echo $lname=$sessionstaff['customer_info']['last_name'];
					}
					if((isset($fname) && $fname=='') || (isset($lname) && $lname=='')){
					echo $card_number=$sessionstaff['customer_info']['first_name'];
					}
					?> has not registered on the upgraded rewards website yet. Now that you've handed them their rewards card, please ask the patient to sign up on <a href='<?php echo $sessionstaff['patient_url']; ?>' target='_blank'><?php echo $sessionstaff['patient_url']; ?></a> with their existing card number as a "New User" to give them full access to the program.  You can still administer points from your end on their visits, however, they will need to load their basic contact information into their profile from the rewards site to ensure proper login credentials for future visits.</p>
		<?php }
                 if($sessionstaff['customer_info']['User']['id']!=0){
                     ?>	
                    <form action="/PatientManagement/updatecustomer" method="POST" name="myinfo_form" class="form-horizontal" id="myinfo_form">
                        <input type="hidden" value="<?=$sessionstaff['customer_info']['card_number']?>" name="customer_card" id="customer_card">
                        <input type="hidden" value="<?=$sessionstaff['customer_info']['User']['id']?>" name="id" id="id">
<?php		$card_number = $sessionstaff['customer_info']['card_number']; ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Card Number:</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Amount" type="text" class="col-xs-12 col-sm-7"  name="first_name" value="<?=$card_number?>" size="24" maxlength="20" disabled>
                            </div>
                        </div>
          <?php
							if (!empty($sessionstaff['customer_info']['custom_date'])) {
								$date_array = explode ('-', $sessionstaff['customer_info']['custom_date']);
							}
							if (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}
							if (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}
							if (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							} 
?>
                        <div class="form-group DateofBirthBox">
                            <label  class="col-sm-3 control-label no-padding-right" >Date Of Birth:</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if($sessionstaff['is_buzzydoc']==1 && $year!='' && $year!='0000' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redyr='disabled';
                                }else{
                                    $redyr='';
                                } ?>
                                <select name="date_year" id="date_year" onchange="checkage();" class="col-xs-12 col-sm-2 selectDate" <?php echo $redyr; ?>>
                                    <option value="">Year</option>
            <?php $curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) {
				if ($y == $year) { ?>
                                    <option value="<?=$y?>" selected><?=$y?></option>
            <?php }else{ ?>
                                    <option value="<?=$y?>"><?=$y?></option>
            <?php } } ?>
                                </select>
                                <?php if($sessionstaff['is_buzzydoc']==1 && $month!='' && $month!='00' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redmn='disabled';
                                }else{
                                    $redmn='';
                                } ?>
                                <select name="date_month" id="date_month" onchange="checkage();" class="col-xs-12 col-sm-2 selectMonth" <?php echo $redmn; ?>>
                                    <option value="">Select Month</option>
									<?php 
						foreach($months as $mon=>$val){
						?>
                                    <option value="<?=$mon?>" <?php if ($mon == $month) { echo "selected"; } ?>><?php echo $val;?></option>
						<?php } ?>
                                </select>
                                <?php if($sessionstaff['is_buzzydoc']==1 && $day!='' && $day!='00' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $reddy='disabled';
                                }else{
                                    $reddy='';
                                } ?>
                                <select name="date_day" id="date_day" onchange="checkage();" class="col-xs-12 col-sm-2 selectYear" <?php echo $reddy; ?>>
                                    <option value="">Day</option>
            <?php for ($d = 1; $d <= 31; $d++) {
				if ($d == $day) { ?>
                                    <option value="<?=$d?>" selected><?=$d?></option>
            <?php }else{ ?>
                                    <option value="<?=$d?>"><?=$d?></option>
            <?php } } ?>
                                </select>
                            </div>
                        </div>
          <?php 
						
						$date1_chd=$sessionstaff['customer_info']['custom_date'];
						$date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
						$date2_chd = date('Y-m-d');
						$diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
						$years_chd = floor($diff_chd / (365*60*60*24));
			if(isset($years_chd) && $years_chd<18){
                            $ds='block';
                        }else{
                            $ds='none';
                        }		
			?>
                        <div class="form-group" id="pemail" style="display:<?=$ds?>">
			<?php
         	 if(isset($years_chd) && $years_chd<18){
				
		  if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['email']!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redem='disabled';
                                    $pemail=mask_email( $sessionstaff['customer_info']['email'], '*', 70 );
                                }else{
                                    $redem='';
                                    $pemail=$sessionstaff['customer_info']['email'];
                                }	
					 ?>

                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Email-Id:</label>
                            <div class="col-sm-9"><input type="email" name="parents_email" class="col-xs-12 col-sm-7" id="parents_email" value="<?=$pemail?>" <?php echo $redem; ?>></div>

              <?php } 
              ?>
                        </div>
               <?php
          if(isset($years_chd) && $years_chd<18){
									
		 if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['parents_email']!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redpem='disabled';
                                    $email = mask_email( $sessionstaff['customer_info']['parents_email'], '*', 70 );
                                }else{
                                    $redpem='';
                                    $email=$sessionstaff['customer_info']['parents_email'];
                                } 
				?>
                        <div class="form-group"  id="email_field">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                Username:</label><div class="col-sm-9">
                                <input 	class="col-xs-12 col-sm-7" type="text" name="aemail" value="<?=$email?>" maxlength="50" id='aemail' <?php echo $redpem; ?>></div>
                        </div>
				<?php
								}
								else {
								
		 if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['email']!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redpem='disabled';
                                    $email = mask_email( $sessionstaff['customer_info']['email'], '*', 70 );
                                }else{
                                    $redpem='';
                                    $email=$sessionstaff['customer_info']['email'];
                                } 
				?>
                        <div class="form-group"  id="email_field">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                Email:<span id='edit_error_msg_email' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
                            <div class="col-sm-9">
                                <input 	class="col-xs-12 col-sm-7" type="text" name="email" value="<?=$email?>" maxlength="50" id='email' <?php echo $redpem; ?>></div>
                        </div>
			<?php	}     	?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Change password:</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="Change password (Optional)" name="new_password" class="col-xs-12 col-sm-7" id="new_password" value="" size="16" maxlength="50" autocomplete="off">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Verify password:</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="Verify changed password" name="new_password2" id="new_password2" value="" size="16"	maxlength="50" class="col-xs-12 col-sm-7" autocomplete="off">
                            </div>
                        </div> 
          <?php 
				$first_name = $sessionstaff['customer_info']['first_name'];
                                if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['first_name']!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redfst='disabled';
                                   
                                }else{
                                    $redfst='';
                                   
                                }  ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">First Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="col-xs-12 col-sm-7" placeholder="First Name" id="first_name" name="first_name" value="<?=$first_name?>" size="24" maxlength="20" <?php echo $redfst; ?>>
                            </div>
                        </div> 
          <?php 
                    $last_name = $sessionstaff['customer_info']['last_name'];
                    if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['last_name']!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redlst='disabled';    
                                }else{
                                    $redlst='';
                                } ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Last Name:</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Last Name" class="col-xs-12 col-sm-7" id="last_name" name="last_name" value="<?=$last_name?>" size="30"	maxlength="20" <?php echo $redlst; ?>>
                            </div>
                        </div>   
          <?php 
          $profileField=array();
          if($sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
             foreach($sessionstaff['ProfileField'] as $field){ 
                 if($field['ProfileField']['clinic_id']<1){
                 $profileField[]=$field;
                 }
             } 
          }else{
             $profileField=$sessionstaff['ProfileField']; 
          }

          foreach($profileField as $field){ 
                            if(($field['ProfileField']['type']=='BigInt' || $field['ProfileField']['type']=='Varchar' || $field['ProfileField']['type']=='Integer') ){ 
                                    $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                                    $user_val = '';
                                    foreach($sessionstaff['customer_info'] as $pfield){ 
                                    if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
                                        if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id'] && $pfield['PFU']['value']!='' && ($field['ProfileField']['profile_field']=='street1' || $field['ProfileField']['profile_field']=='street2' || $field['ProfileField']['profile_field']=='phone')){
                                                    $user_val=mask_other( $pfield['PFU']['value'], '*', 50 );
                                        }else{
                                          $user_val=$pfield['PFU']['value'];  
                                        }
                                    }
                                    }
                                    if(!isset($user_val)){
                                                    $user_val = '';
                                    }
                                    if($sessionstaff['is_buzzydoc']==1 && $user_val!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                $redoth='disabled';

                            }else{
                                $redoth='';

                            } 
                                     ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php if($field_val=='Street1'){
                                    echo "Address:";
                                  
                                    }
                                    else if($field_val=='Street2'){
                                    echo "&nbsp;";
                                  
                                    }else{

                                     echo $field_val.':'; 

                                     } ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="col-xs-12 col-sm-7" placeholder="<?php echo $field_val; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $user_val;?>"  maxlength="50" <?php echo $redoth; ?>></div>
                        </div>
      <?php } 
				if($field['ProfileField']['type']=='Text'){ 			
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$user_val = '';
					foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=$pfield['PFU']['value'];
					}
					}
				if($sessionstaff['is_buzzydoc']==1 && $user_val!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redoth1='disabled';
                                   
                                }else{
                                    $redoth1='';
                                   
                                } 
					 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>
                            <div class="col-sm-9">
                                <textarea class="col-xs-12 col-sm-7" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" rows="6" cols="30" <?php echo $redoth1; ?>><?php echo $user_val;?></textarea></div>
                        </div>
          <?php } 
					if($field['ProfileField']['type']=='MultiSelect'){ 
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
					$user_val = array();
					foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
                                            if($pfield['PFU']['value']!=''){
							$user_val=explode(',',$pfield['PFU']['value']);
                                            }else{
                                                $user_val=array();
                                            }
					}
					}
								                                                                       if($sessionstaff['is_buzzydoc']==1 && !empty($user_val) && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redoth2='disabled';
                                   
                                }else{
                                    $redoth2='';
                                   
                                } 
					 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>
                            <div class="col-sm-9">
                                <select class="col-xs-12 col-sm-7" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" id="<?php echo $field['ProfileField']['profile_field']; ?>"  multiple="multiple" size="4" <?php echo $redoth2; ?>>
                                    <option value="">Please Select</option>
						<?php foreach($field_options_red as $op){ ?>
                                    <option value="<?php echo $op;?>" <?php if (in_array($op, $user_val)) { echo "selected"; } ?>><?php echo $op;?></option>
						<?php } ?>
                                </select>
                            </div>
                        </div>
          <?php }
          if($field['ProfileField']['type']=='Select' && ($field['ProfileField']['profile_field']!='state' && $field['ProfileField']['profile_field']!='city')){ 
					
					
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
					$user_val = '';
					foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=$pfield['PFU']['value'];
					}
					}
					  if($sessionstaff['is_buzzydoc']==1 && $user_val!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redoth3='disabled';
                                   
                                }else{
                                    $redoth3='';
                                }
                                 
					 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>
                            <div class="col-sm-9"><select class="col-xs-12 col-sm-7" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" <?php echo $redoth3; ?>>
                                    <option value="">Please Select</option>
						<?php foreach($field_options_red as $op){ ?>
                                    <option value="<?php echo $op;?>" <?php if($user_val==$op){ echo "selected"; } ?>><?php echo $op;?></option>
						<?php } ?>
                                </select></div>

                        </div>
          <?php }
           if($field['ProfileField']['type']=='RadioButton'){ 
					
					
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
					$oth_chk=end($field_options_red);
                                        
                                        $other=0;
                                        if($oth_chk=='(other)'){
                                        $other=1;
                                        $field_options_red= array_diff($field_options_red,array($oth_chk)); 
                                        }
                                        $user_val = '';
					foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=$pfield['PFU']['value'];
					}
					}
					$otherrd=explode('###',$user_val);
                                        $othervalrd='';
                                        if(isset($otherrd[1])){
                                        $othervalrd=$otherrd[1];
                                        }
                                        	  if($sessionstaff['is_buzzydoc']==1 && $user_val!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redoth4='disabled';
                                    $redothertext='disabled';
                                }else{
                                    $redoth4='';
                                    $redothertext='';
                                }
					 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>


                            <div class="col-sm-9">
					<?php foreach($field_options_red as $op){ ?>
                                <div class="patientinfo_radio">
                                    <input type="radio" class="" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $op; ?>" <?php if( strtolower($user_val)==strtolower($op)){ echo "checked"; } ?>  onclick="opt1('<?php echo $op; ?>', '<?php echo $field['ProfileField']['profile_field']; ?>', '<?=$othervalrd?>')" <?php echo $redoth4; ?>>
                                    <label class=" control-label"><?php echo $op; ?></label>
                                </div>
                     <?php }if($other==1){ ?>
                                <div class="patientinfo_radio">
                                    <input type="radio" class="" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="other" <?php if(count($otherrd)>1){ echo "checked"; } ?>  onclick="opt1('other', '<?php echo $field['ProfileField']['profile_field']; ?>', '<?=$othervalrd?>')" <?php echo $redoth4; ?>>
                                    <label class=" control-label">Other</label>
                                </div> 
                   <?php if(count($otherrd)>1){ ?>
                                <div id="othertext_<?php echo $field['ProfileField']['profile_field']; ?>" class="othertextbox">

                                    <input type="text" name="other_<?php echo $field['ProfileField']['profile_field']; ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" placeholder="Other" value="<?=$othervalrd?>" maxlength="30" class="form-control" <?php echo $redothertext; ?>>     

                                </div>
                   <?php }else{ ?>
                                <div id="othertext_<?php echo $field['ProfileField']['profile_field']; ?>"  class="othertextbox">


                                </div>
                      <?php }} ?>

                                <script>

                                    function opt1(val, val1, val2) {

                                        if (val == 'other') {
                                            $('#othertext_' + val1).html('<input type="text" class="form-control" name="other_' + val1 + '" id="other_' + val1 + '" placeholder="Other" value="' + val2 + '" maxlength="30" >');
                                        } else {
                                            $('#othertext_' + val1).html('');
                                        }
                                    }
                                </script>		

                            </div>
                        </div>
          <?php }
          if($field['ProfileField']['type']=='CheckBox'){ 
					
					
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
					$oth_chk1=end($field_options_red);
                                        $other1=0;
                                        if($oth_chk1=='(other)'){
                                        $other1=1;
                                        $field_options_red= array_diff($field_options_red,array($oth_chk1)); 
                                        }
                                        $user_val = array();
					foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']==$field['ProfileField']['profile_field']){
                                                        if($pfield['PFU']['value']!=''){
							$user_val=explode(',',$pfield['PFU']['value']);
                                                        }else{
                                                         $user_val=array();   
                                                        }
					}
					}
                                        
                                        $otherchek=explode('###',end($user_val));
                                        $otherval='';
                                        if(isset($otherchek[1])){
                                        $otherval=$otherchek[1];
                                        }
                                              	  if($sessionstaff['is_buzzydoc']==1 && !empty($user_val) && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    $redoth5='disabled';
                                    $redothertext1='disabled';
                                }else{
                                    $redoth5='';
                                    $redothertext1='';
                                }
					 ?>
                        <div class="form-group" style="overflow:hidden;">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>


                            <div class="col-sm-9">

					<?php foreach($field_options_red as $op){ ?>

                                <input type="checkbox" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="<?php echo $op; ?>" <?php if (in_array($op, $user_val)) { echo "checked";} ?> <?php echo $redoth5; ?>>
                                <label class="patientinfo_checkbox">
					 <?php echo $op; ?>
                                </label>

                     <?php }if($other1==1){?>

                                <input type="checkbox" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="other" <?php if (count($otherchek)>1) { echo "checked";} ?>  id="getopt_<?php echo $field['ProfileField']['profile_field']; ?>" onclick="opt('<?php echo $field['ProfileField']['profile_field']; ?>', '<?=$otherval?>')" <?php echo $redoth5; ?>>
                                <label class="patientinfo_checkbox">
                                    Other
                                </label>

                     <?php } ?>

                     <?php 
                     if (count($otherchek)>1) { ?>
                                <div id="othertext_<?php echo $field['ProfileField']['profile_field']; ?>"   class="othertextbox">

                                    <input class="form-control"  type="text" name="other_<?php echo $field['ProfileField']['profile_field']; ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $otherval;?>" maxlength="30" <?php echo $redothertext1; ?>>    

                                </div>
                   <?php }else{ ?>
                                <div id="othertext_<?php echo $field['ProfileField']['profile_field']; ?>"   class="othertextbox">

                                </div>
                   <?php } ?>
                                <script>

                                    function opt(val, val1) {

                                        if ($('#getopt_' + val).is(":checked"))
                                        {
                                            $('#othertext_' + val).html('<input class="form-control" type="text" name="other_' + val + '" id="other_' + val + '" value="' + val1 + '" maxlength="30" >');
                                        } else {
                                            $('#othertext_' + val).html('');
                                        }

                                    }</script>
                            </div>
                        </div>
          <?php }

                                        if(isset($field['ProfileField']['profile_field']) && $field['ProfileField']['profile_field']=='state'){
							
                                        foreach($sessionstaff['customer_info'] as $pfield){ 
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']=='state'){
							$state = $pfield['PFU']['value'];
					}
					}
                                                 	  if($sessionstaff['is_buzzydoc']==1 && $state!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    
                                    $redoth7='disabled';
                                }else{
                                    $redoth7='';
                                    
                                }
                                                     ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">State:</label>
                            <div class="col-sm-9">
                                <select class="col-xs-12 col-sm-7" name="state" id="state" onchange="getcity();" <?php echo $redoth7; ?>>
                                    <option value="">Select State</option>
	<?php foreach($states as $st){ ?>
                                    <option value="<?=$st['State']['state']?>" <?php if($state==$st['State']['state']){ echo "selected"; } ?>><?=$st['State']['state']?></option>
<?php } ?>
                                </select>
                            </div>
                        </div>
          <?php }
  
		
					if(isset($field['ProfileField']['profile_field']) && $field['ProfileField']['profile_field']=='city'){
							
                                        foreach($sessionstaff['customer_info'] as $pfield){ 
                                            
					if(isset($pfield['PF']['profile_field']) && $pfield['PF']['profile_field']=='city'){
                                          
							$city_val = $pfield['PFU']['value'];
                                                        
					}
					} 
                                              	  if($sessionstaff['is_buzzydoc']==1 && $city_val!='' && $sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']){
                                    
                                    $redoth6='disabled';
                                }else{
                                    $redoth6='';
                                    
                                }
                                        
                                        ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">City:</label>
                            <div class="col-sm-9">
                                <select class="col-xs-12 col-sm-7" name="city" id="city" <?php echo $redoth6; ?>>
                                    <option value="">Select City</option>
<?php foreach($city as $ct){ ?>
                                    <option value="<?=$ct['City']['city']?>" <?php if($city_val==$ct['City']['city']){ echo "selected"; } ?>><?=$ct['City']['city']?></option>
<?php } ?>
                                </select></div>
                        </div>
          <?php }
	  } if(($sessionstaff['clinic_id']==79 || $sessionstaff['clinic_id']==73)  && $sessionstaff['customer_info']['clinic_id']==$sessionstaff['clinic_id']){
         ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Internal ID:</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Internal ID" name="internal_id" class="col-xs-12 col-sm-7" id="internal_id" value="<?php echo $sessionstaff['customer_info']['internal_id']; ?>"  maxlength="9" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">
                            </div>
                        </div>
          <?php } ?>
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-sm btn-primary" onclick="return checkemail();">Save Changes</button>

                        </div> 
                    </form>

                 <?php }else{ 
                     if($sessionstaff['is_buzzydoc']==1){ ?>
                    <form action="<?=Staff_Name?>PatientManagement/assigncard" method="POST" name="myinfo_form_assign" class="form-horizontal" id="myinfo_form_assign">

                        <input type="hidden" value="<?=$sessionstaff['customer_info']['User']['id']?>" name="id" id="id">
                        <input type="hidden" value="0" name="type" id="type">
                        <input type="hidden" value="0" name="uid" id="uid">

<?php	$card_number = $sessionstaff['customer_info']['card_number'];	 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Card Number:</label><div class="col-sm-9">
                                <input type="text" placeholder="Card Number" class="col-xs-12 col-sm-7" type="text"  name="card_number" id="card_number" value="<?=$card_number?>" size="24" maxlength="20" readonly></div>
                        </div>
                        <?php
          
			  		
							if (!empty($sessionstaff['customer_info']['custom_date'])) {
								$date_array = explode ('-', $sessionstaff['customer_info']['custom_date']);
							}
							if (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}
							if (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}
							if (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							} 
?>
                        <div class="form-group DateofBirthBox">
                            <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Date Of Birth:</label>

                            <div class="col-sm-9 col-xs-12">

                                <select name="date_year" id="date_year" onchange="checkage1();" class="col-xs-12 col-sm-2 selectDate" required="">
                                    <option value="">Year</option>
            <?php $curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) {
				if ($y == $year) { ?>
                                    <option value="<?=$y?>" selected><?=$y?></option>
            <?php }else{ ?>
                                    <option value="<?=$y?>"><?=$y?></option>
            <?php } } ?>
                                </select>
                                <select name="date_month" id="date_month" onchange="checkage1();" class="col-xs-12 col-sm-2 selectMonth" required="">
                                    <option value="">Select Month</option>
									<?php 
                 
						foreach($months as $mon=>$val){
						?>
                                    <option value="<?=$mon?>" <?php if ($mon == $month) { echo "selected"; } ?>><?php echo $val;?></option>
						<?php } ?>
                                </select>

                                <select name="date_day" id="date_day" onchange="checkage1();" class="col-xs-12 col-sm-2 selectYear" required="">
                                    <option value="">Day</option>
            <?php for ($d = 1; $d <= 31; $d++) {
				if ($d == $day) { ?>
                                    <option value="<?=$d?>" selected><?=$d?></option>
            <?php }else{ ?>
                                    <option value="<?=$d?>"><?=$d?></option>
            <?php } } ?>
                                </select>
                            </div>
                        </div>

          <?php 
	$first_name = $sessionstaff['customer_info']['first_name'];
							 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>First Name:</label><div class="col-sm-9">

                                <input type="text" placeholder="First Name" id="first_name" required="required" class="col-xs-12 col-sm-7" name="first_name" value="<?=$first_name?>" size="24" maxlength="20" onblur="checkusername();"></div>
                        </div>

                        <?php 				
                        $last_name = $sessionstaff['customer_info']['last_name']; ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Last Name:</label><div class="col-sm-9">


                                <input type="text" placeholder="Last Name" class="col-xs-12 col-sm-7" id="last_name" required="required" name="last_name" value="<?=$last_name?>" size="30" maxlength="20" onblur="checkusername();"></div>
                        </div>
                        <?php if($sessionstaff['staffaccess']['AccessStaff']['with_email']!=1){  ?><input type="hidden" value="0" name="with_email" id="with_email"><?php }else{ ?><input type="hidden" value="1" name="with_email" id="with_email"> <?php } ?>
                        <?php 
			$date1_chd=$sessionstaff['customer_info']['custom_date'];
			$date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
			$date2_chd = date('Y-m-d');
			$diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
			$years_chd = floor($diff_chd / (365*60*60*24));
			if(isset($years_chd) && $years_chd<18){
                            $ds='block';
                        }else{
                            $ds='none';
                        }		
			?>
                        <div id="perenttype">
                            <div class="form-group" id="pemail" style="display:<?=$ds?>">
			<?php
         	 if(isset($years_chd) && $years_chd<18){
                         $pemail=$sessionstaff['customer_info']['email'];	
					 ?>
                                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php if($sessionstaff['staffaccess']['AccessStaff']['with_email']!=1){ $req='required="required"'; ?><span class="star">*</span><?php }else{ $req="";?> <?php } ?>Email:</label>
                                <div class="col-sm-9"><input type="email" name="parents_email" class="col-xs-12 col-sm-7" id="parents_email" value="<?=$pemail?>" onblur="checkuserexist();
                                        checkusername();" onmouseout="checkuserexist();" <?php echo $req; ?> placeholder="Email"></div>

              <?php } 
              ?>
                            </div>
               <?php
          if(isset($years_chd) && $years_chd<18){
                                    $email=$sessionstaff['customer_info']['parents_email'];
									
									?>
                            <div class="form-group"  id="email_field">
                                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    <span class="star">*</span>Username:</label><div class="col-sm-9">
                                    <input class="col-xs-12 col-sm-7" type="text" name="aemail" value="<?=$email?>" maxlength="50" id='aemail'></div>
                            </div>
									<?php
								}
								else {
                                    $email=$sessionstaff['customer_info']['email'];
				
									?>
                            <div class="form-group"  id="email_field">
                                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                    <?php if($sessionstaff['staffaccess']['AccessStaff']['with_email']!=1){ $req='required="required"';?><span class="star">*</span><?php }else{ $req=''; } ?>Email:</label>
                                <div class="col-sm-9">
                                    <input class="col-xs-12 col-sm-7" type="text" name="email" value="<?=$email?>" maxlength="50" id='email' onblur="checkuserexist();
                                            checkusername();" onmouseout="checkuserexist();" <?php echo $req; ?> placeholder="Email"></div>
                            </div>
			<?php }       	?>
                        </div>
                        <div class="col-md-offset-3 col-md-9">

                            <button class="btn btn-sm btn-primary" id="asgnlink" name="asgnlink">Assign Card</button>

                        </div> 
                    </form>
                 <?php        
                     }else{
                     ?>
                    <form action="<?=Staff_Name?>PatientManagement/updateunregcustomer" method="POST" name="myinfo_form2" class="form-horizontal" id="myinfo_form2">

                        <input type="hidden" value="<?=$sessionstaff['customer_info']['User']['id']?>" name="id" id="id">

<?php	$card_number = $sessionstaff['customer_info']['card_number'];	 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Card Number:</label><div class="col-sm-9">
                                <input type="text" placeholder="Card Number" class="col-xs-12 col-sm-7" type="text"  name="card_number" id="card_number" value="<?=$card_number?>" size="24" maxlength="20" readonly></div>
                        </div>
                        <?php
          
			  		
							if (!empty($sessionstaff['customer_info']['custom_date'])) {
								$date_array = explode ('-', $sessionstaff['customer_info']['custom_date']);
							}
							if (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}
							if (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}
							if (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							} 
?>
                        <div class="form-group DateofBirthBox">
                            <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Date Of Birth:</label>

                            <div class="col-sm-9 col-xs-12">

                                <select name="date_year" id="date_year" onchange="checkage2();" class="col-xs-12 col-sm-2 selectDate">
                                    <option value="">Year</option>
            <?php $curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) {
				if ($y == $year) { ?>
                                    <option value="<?=$y?>" selected><?=$y?></option>
            <?php }else{ ?>
                                    <option value="<?=$y?>"><?=$y?></option>
            <?php } } ?>
                                </select>
                                <select name="date_month" id="date_month" onchange="checkage2();" class="col-xs-12 col-sm-2 selectMonth">
                                    <option value="">Select Month</option>
									<?php 
                 
						foreach($months as $mon=>$val){
						?>
                                    <option value="<?=$mon?>" <?php if ($mon == $month) { echo "selected"; } ?>><?php echo $val;?></option>
						<?php } ?>
                                </select>

                                <select name="date_day" id="date_day" onchange="checkage2();" class="col-xs-12 col-sm-2 selectYear">
                                    <option value="">Day</option>
            <?php for ($d = 1; $d <= 31; $d++) {
				if ($d == $day) { ?>
                                    <option value="<?=$d?>" selected><?=$d?></option>
            <?php }else{ ?>
                                    <option value="<?=$d?>"><?=$d?></option>
            <?php } } ?>
                                </select>
                            </div>
                        </div>


          <?php 
	$first_name = $sessionstaff['customer_info']['first_name'];
							 ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">First Name:</label><div class="col-sm-9">

                                <input type="text" placeholder="First Name" id="first_name" class="col-xs-12 col-sm-7" name="first_name" value="<?=$first_name?>" size="24" maxlength="20" onblur="checkusername();"></div>
                        </div>

          <?php 				
	$last_name = $sessionstaff['customer_info']['last_name']; ?>
                        <div class="form-group">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Last Name:</label><div class="col-sm-9">


                                <input type="text" placeholder="Last Name" class="col-xs-12 col-sm-7" id="last_name"  name="last_name" value="<?=$last_name?>" size="30"	maxlength="20" onblur="checkusername();"></div>
                        </div>
                         <?php 
						
						$date1_chd=$sessionstaff['customer_info']['custom_date'];
						$date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
						$date2_chd = date('Y-m-d');
						$diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
						$years_chd = floor($diff_chd / (365*60*60*24));
			if(isset($years_chd) && $years_chd<18){
                            $ds='block';
                        }else{
                            $ds='none';
                        }		
			?>

                        <div class="form-group" id="pemail" style="display:<?=$ds?>">
			<?php
         	 if(isset($years_chd) && $years_chd<18){
				
		
                                    $pemail=$sessionstaff['customer_info']['email'];
                                
				
					
						
					 ?>

                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Email-Id:</label>
                            <div class="col-sm-9"><input type="email" name="parents_email" class="col-xs-12 col-sm-7" id="parents_email" value="<?=$pemail?>"></div>

              <?php } 
              ?>
                        </div>
               <?php
          if(isset($years_chd) && $years_chd<18){
			    $email=$sessionstaff['customer_info']['parents_email'];
                                ?>
                        <div class="form-group"  id="email_field">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                Username:</label><div class="col-sm-9">
                                <input 	class="col-xs-12 col-sm-7" type="text" name="aemail" value="<?=$email?>" maxlength="50" id='aemail'></div>
                        </div>
									<?php
								}
								else {
                                    $email=$sessionstaff['customer_info']['email'];
                                ?>
                        <div class="form-group"  id="email_field">
                            <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                Email:</label>
                            <div class="col-sm-9">
                                <input 	class="col-xs-12 col-sm-7" type="text" name="email" value="<?=$email?>" maxlength="50" id='email'></div>
                        </div>
		<?php }
          	?>
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-sm btn-primary">Save Changes</button>       

                        </div> 
                    </form>

      <?php
                     }
                 } ?>
                </div>
     	<?php }
	  if($sessionstaff['customer_info']['parents_email']=='' && $sessionstaff['customer_info']['email']!=''){
		  $ademail='';
		  $memail=$sessionstaff['customer_info']['email'];
	  }else{
		  $ademail=$sessionstaff['customer_info']['parents_email'];
		  $memail=$sessionstaff['customer_info']['email'];
	  }
     
		  ?>
            </div>
        </div>
        <div id="history14" class="tab-pane pull-left col-md-8 col-sm-12 col-xs-12 <?php echo $pathhistory; ?>">
            <?php 
            if(!empty($sessionstaff['customer_info']['visithistory'])){ ?>
            <div class="col-sm-12">
                <!-- #section:elements.tab.position -->
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs" id="myTab3">
                        <?php 
                        $vi=0;
                        foreach ($sessionstaff['customer_info']['visithistory'] as $data=>$vhistory) {
                            if($vi==0){
                                $ac='active';
                            }else{
                                $ac='';
                            }
                            ?>
                        <li class="<?php echo $ac; ?>">
                            <a data-toggle="tab" href="#visit<?php echo $vhistory['treatment_details']['id']; ?>">
                                <?php if(isset($vhistory['status']) && $vhistory['status']==1){ echo '<b>'.$data." <br>(Treatment Over)</b>"; }else{ echo $data; }?>
                            </a>
                        </li>
                        <?php $vi++;} ?>
                    </ul>

                    <div class="tab-content">
                         <?php 
                        $nv=1;
                        $phase1='';
                        $phase2='';
                        $phase3='';
                        $totalvisit='';
                        $totalpoint='';
                        $vi1=0;
                        
                        foreach ($sessionstaff['customer_info']['visithistory'] as $data=>$vhistory) {
                            if($vi1==0){
                                $ac1='active';
                            }else{
                                $ac1='';
                            }
                            ?>
                        <div id="visit<?php echo $vhistory['treatment_details']['id']; ?>" class="tab-pane in <?php echo $ac1; ?>">
                            <?php if($vhistory['treatment_details']['interval']==1){
                            ?>
                            <div class="col-sm-12 center grey "><b>Interval Reward Plan</b></div>
                            <div class="col-sm-12">&nbsp;</div>
                            <?php }else{ ?>
                            <div class="col-sm-12 center grey"><b>Treatment Reward Plan</b></div>
                            <div class="col-sm-12">&nbsp;</div>
                            <?php } ?>
                            <p>
                                <?php                             
                                $levecomp=0;
                                foreach($vhistory['record'] as $vs){
                                if($vs['perfect']=='Perfect')
                                   $levecomp++; 
                            }
                            $totalcomp=$levecomp;
                            //condition to show phase details for interval reward plan.
                            if($vhistory['treatment_details']['interval']==1){
                                //condition when interval reward plan is over.
                                if($vhistory['interval_details']['Phase']!='Over'){
                                $totalvisitcomp=($vhistory['interval_details']['Visit']*100)/$vhistory['treatment_details']['total_visits']; ?>
                            <div class="col-sm-12"> <?php echo $vhistory['treatment_details']['total_visits']-$vhistory['interval_details']['Visit']; ?> more perfect visit to achieve <?php echo $vhistory['interval_details']['Phase']; ?></div>
                                <?php
                                }}else{
                                // levelup treatment plan phase details.
                                $totalvisitcomp=($totalcomp*100)/$vhistory['treatment_details']['total_visits'];
                          ?>
                            <div class="col-sm-12"> Total Visits in Plan: <?php echo $vhistory['treatment_details']['total_visits']; ?></div>
                            <div class="col-sm-12">Possible Earnings in Plan: $<?php echo number_format($vhistory['treatment_details']['total_points']/50, 2, '.', ' '); ?></div>
                            <div class="col-sm-12">Perfect Visits Earned During Plan: <?php echo $totalcomp; ?> out of <?php echo count($vhistory['record']); ?> </div>
                            <?php }
                            if(isset($vhistory['interval_details']['Phase']) && $vhistory['interval_details']['Phase']=='Over'){
                                ?>
                            <div class="col-sm-12">This Interval reward plan is completed.<?php if($vhistory['interval_details']['CurrentPhase']!=''){ ?> User Achieved <?php echo $vhistory['interval_details']['CurrentPhase']; ?>.<?php } ?></div>
                            <div class="col-sm-12">&nbsp;</div>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="sample-table-1" class="table table-striped table-bordered table-hover" ></table>
                            <?php
                            }else{
                            ?>
                            <div class="col-sm-12">&nbsp;</div>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="sample-table-1" class="table table-striped table-bordered table-hover" >
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <?php 
                                            $p=0;
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
                            $levelname=explode(' ',$phaseset['PhaseDistribution']['phase_name']);
                            //calculation for interval reward plan.
                            if($vhistory['treatment_details']['interval']==1){
                                $phase=$totalvisitcomp;
                                $phase_name=$vhistory['interval_details']['Phase'];
                            }else{
                                $phase=$phase;
                                $phase_name=$phaseset['PhaseDistribution']['phase_name'];
                                ?>
                                            Level <?php echo $levelname[1]; ?> Visits: <?php echo $phaseset['PhaseDistribution']['visits']; ?> | Level <?php echo $levelname[1]; ?> Points: <?php echo $phaseset['PhaseDistribution']['points']; }?>
                                            <!-- #section:elements.progressbar -->
                                            <div class="progress pos-rel" data-percent="<?php echo $phase_name; ?> (<?php echo round($phase,1); ?>%) ">
                                                <div class="progress-bar<?php echo $css; ?>" style="width:<?php echo $phase; ?>%;"></div>

                                            </div>
                                            <?php 
                                            if($p==4)
                                                $p=0;
                           $p++;  }$p=0; ?>

                                        </div><!-- /.col -->

                                        <div class="col-xs-3 center">
                                            <!-- #section:plugins/charts.easypiechart -->
                                            <div class="easy-pie-chart percentage" data-percent="<?php echo $totalvisitcomp; ?>" data-color="#D15B47">
                                                <span class="percent"><?php echo round($totalvisitcomp); ?></span>%
                                            </div>
                                            </br>
                                        <?php if($vhistory['treatment_details']['interval']==1){ echo $vhistory['interval_details']['Visit']."/".$vhistory['treatment_details']['total_visits'];}else{ echo $totalcomp."/".$vhistory['treatment_details']['total_visits']; }?>
                                        </div><!-- /.col -->
                                    </div><!-- /.row -->
                                </div><!-- /.col -->
                            </table>
                            <?php } ?>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="sample-table-1" class="table table-striped table-bordered table-hover" > 
                                <tr>
                                    <td width="10%" style="background: none repeat scroll 0 0 #62A8D1;color: #FFFFFF;padding-bottom: 12px; padding-top: 12px; text-align: center;">Visit Date</td>
                                    <td width="30%" style="background: none repeat scroll 0 0 #62A8D1;color: #FFFFFF;padding-bottom: 12px; padding-top: 12px; text-align: center;">Visit Type</td>

                                    <td width="10%" style="background: none repeat scroll 0 0 #62A8D1;color: #FFFFFF;padding-bottom: 12px; padding-top: 12px; text-align: center;">Level Status</td>

                                    <td width="50%" style="background: none repeat scroll 0 0 #62A8D1;color: #FFFFFF;padding-bottom: 12px; padding-top: 12px; text-align: center;">Plan Status</td>
                                </tr>
				<?php
                           foreach($vhistory['record'] as $vs){
                               
                            ?>
                                <tr>
                                    <td width="25%" class="firstCol"><?php echo $vs['date']; ?></td>
                                    <td width="25%"><?php echo $vs['perfect']; ?> </td>
                                    <td width="25%" class="amountpositive"><?php  echo str_replace('0', '--', $vs['level_status']); ?></td>
                                    <td width="25%" class="amountpositive"><?php echo $vs['status']; ?></td>
                                </tr>
                            <?php } ?>
                            </table>
                            <?php
        $nv++; ?></p>

                        </div>
<?php $vi1++;} ?>
                    </div>
                </div>

                <!-- /section:elements.tab.position -->
            </div><!-- /.col -->
<?php } ?>
        </div>

        <div id="mybadges" class="tab-pane pull-left col-md-8 col-sm-12 col-xs-12 <?=$pathbadge?>">
            <div class="pull-left col-md-12 col-sm-12 col-xs-12">
                <div class="patienthistory">
                    <div class="profile-users clearfix">
                                                    <?php
                                                    $i = 1;
                                                    foreach ($sessionstaff['customer_info']['usersBadge'] as $sbage) {
                                                        $opacity_class = 'opacity-midium';
                                                        ?>
                        <div
                            class="badges <?= $opacity_class ?>">
    <?php 
    	echo $this->html->image(CDN.'img/images_buzzy/badge_point' . $i . '.png', array('title' => $sbage['Badge']['name'], 'alt' => $sbage['Badge']['name'], 'id' => 'avatar1'));
     ?>
                            <header
                                class="check-ins-heading clearfix"><?php echo $sbage['Badge']['name']; ?>
                                <div
                                    class="check-ins-tooltip"><?php echo $sbage['Badge']['description']; ?></div>
                            </header>
                        </div>

    <?php $i++;
}
?>
                    </div>
                </div>
            </div>
        </div>
        <div id="moreTab" class="tab-pane pull-left col-md-8 col-sm-12 col-xs-12 <?=$more?>">
            <div class="pull-left col-md-12 col-sm-12 col-xs-12">
                <div class="patienthistory">
                    <div class="profile-users clearfix">

                        <?php
                        if($sessionstaff['customer_info']['User']['email']!='' && $sessionstaff['fromclinic']==1){ ?> 
                        <div class="badges opacity-midium" style="height:70px !important;">
                            <a href="javascript:void(0)" onclick="sendAccountInfo();" class="btn btn-purple btn-sm">Send Account Info</a>
                        </div>
                        <?php } if($sessionstaff['customer_info']['User']['id']>0 && $sessionstaff['fromclinic']==1){ ?>
                        <div class="badges opacity-midium" style="height:70px !important;">
                            <a href="#" id="id-btn-dialog1" class="btn btn-purple btn-sm">Lost Card</a>
                        </div>
                        <?php } if($sessionstaff['customer_info']['User']['blocked']==1){ ?>
                        <div class="badges opacity-midium" style="height:70px !important;" id="unblockdiv">
                            <a href="#" id="id-btn-dialog12" class="btn btn-purple btn-sm">Unblock</a>
                        </div>
                        <?php } if(isset($sessionstaff['customer_info']['User']['is_verified']) && $sessionstaff['customer_info']['User']['is_verified']==0){ ?>
                        <div class="badges opacity-midium" style="height:70px !important;" id="unblockdiv">
                            <a href="javascript:void(0)" onclick="verfiy();" style="cursor:pointer" class="btn btn-purple btn-sm">Send Verification Email</a>
                        </div>
                        <?php }if($sessionstaff['customer_info']['User']['email']!='' && $sessionstaff['fromclinic']==1 && $sessionstaff['staffaccess']['AccessStaff']['rate_review']==1){ ?> 
                        <div class="badges opacity-midium" style="height:70px !important;">
                            <input type="button" onclick="javascript:void(0);" class="btn btn-purple btn-sm requestReview" id="request-review" value="Request a Review">
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-left col-md-4 col-sm-12 col-xs-12">
         <?php echo $this->element('right_sidebar'); ?>
        </div>
    </div>
</div>
<div class="Clearfix"></div>
<div id="dialog-message" class="hide">
    <div class="innermsg"><p>If a patient has lost a card, you can change their card number to a number or card assigned to the office and loaded to your account.</p></div>
    <div class="hr hr-12 hr-double"></div>
    <input type="text" id='new_card' name="new_card" placeholder="New Card Number" class="leadinput">
    <input type="hidden" id='old_user_id' name="old_user_id" value="<?=$sessionstaff['customer_info']['User']['id']?>">
    <input type="hidden" id='old_card' name="old_card" value="<?=$sessionstaff['customer_info']['card_number']?>">
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
            <input type="button" value="Assign To New Card" id='assgn_new_btn' class="btn btn-primary btn-xs">
        </div></div>
    <div id='status_error' style="color: #FF0000;"></div>
</div><!-- #dialog-message -->

<div id="dialog-message2" class="hide">
    <div class="innermsg"><p>Please fill in email id to unblock the account.</p></div>
    <div class="hr hr-12 hr-double"></div>
    <input type="text" id='unblock_email' name="unblock_email" placeholder="Email-Id" class="leadinput">
    <input type="hidden" id='unblock_user_id' name="unblock_user_id" value="<?=$sessionstaff['customer_info']['User']['id']?>">
    <input type="hidden" id='unblock_card' name="unblock_card" value="<?=$sessionstaff['customer_info']['card_number']?>">
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
            <input type="button" value="Unblock Account" id='unblock_new_btn' class="btn btn-primary btn-xs">
        </div></div>
    <div id='status_error_unblock' style="color: #FF0000;"></div>
</div><!-- #dialog-message -->

<div id="staffPatientRedeem" class="hide">
    <div class="alert alert-info bigger-110" id="staffPatientRedeemText">
    </div>
    <select name="redeem_staff_id" id="redeem_staff_id" required>
        <option value=''>Redeemed By</option>
                        <?php 
                        if(count($staffs)==1){
                            $autosel1='selected';
                        }else{
                            $autosel1='';
                        }
                        foreach($staffs as $staff){ ?>
        <option value="<?php echo $staff['Staff']['id']; ?>" <?php echo $autosel1; ?>><?php echo $staff['Staff']['staff_id']; ?></option>
                        <?php } ?>

    </select>
    <div for="staff_id" generated="true"  style="display:none;color:#FF0000;" id="staffRedeemError">Please select staff</div>
</div>
<div id="staffPatientRedeemTangAmazon" class="hide">
    <div class="alert alert-info bigger-110" id="staffPatientRedeemTangAmazonText">
    </div>
    <select name="redeem_staff_idTangAmazon" id="redeem_staff_idTangAmazon" required>
        <option value=''>Redeemed By</option>
                        <?php 
                        if(count($staffs)==1){
                            $autosel1='selected';
                        }else{
                            $autosel1='';
                        }
                        foreach($staffs as $staff){ ?>
        <option value="<?php echo $staff['Staff']['id']; ?>" <?php echo $autosel1; ?>><?php echo $staff['Staff']['staff_id']; ?></option>
                        <?php } ?>

    </select>
    <div for="staff_id" generated="true"  style="display:none;color:#FF0000;" id="staffRedeemErrorTangAmazon">Please select staff</div>
</div>

<div class="modal fade" id="redeemModalMain" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog reedem-modalbox">
        <div class="modal-content">
            <div class="modal-header text-center">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="redeemclose();"><span aria-hidden="true" id="closeredeem" >&times;</span></button>

                <h4 class="modal-title" id="redeemPointsLabel">Redeem Points for <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="text-center points-value-span-container1">
                    <span id="points_value_span1" class=""></span>
                </div>

                <?php if (!empty($productandservice)) {
                                 
                                    ?>
                <div id="redeem_container" style="position: relative; top: 0px; left: 0px; width: 560px; height: 331px; background: #fff; overflow: hidden; ">

                    <!-- Slides Container -->
                    <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 29px; width: 560px; height: 278px; border: 1px solid gray; -webkit-filter: blur(0px); background-color: #fff; overflow: hidden;">
                                            <?php foreach ($productandservice as $key => $val) { ?>
                        <div>
                            <div u="thumb"><?php echo $key; ?></div>
                            <div style="margin: 10px; height: 265px; overflow: auto; color: #000;">
                                                <?php
                                                foreach ($val as $index => $elem) {
                                                   
                                                    ?>
                                <div class="redeemBox">
                                    <h3><?php echo $elem['title']; ?></h3>
                                    <div class="productPoints"><?php echo $elem['points']; ?> Points</div>
                                    <?php if($PointsFromClinic>0){ ?>
                                    <div class="redeemButtom"><button type="button" id="submit-redeem-<?php echo $elem['id']; ?>" prodId="<?php echo $elem['id']; ?>" userId="<?php echo $sessionstaff['customer_info']['User']['id']; ?>" prodPoints="<?php echo $elem['points']; ?>" prodTitle="<?php echo $elem['title']; ?>">Redeem</button></div>
                                    <?php }else{ ?>
                                    <div class="redeemButtom" style="cursor:auto;"><button type="button" id="submit-redeem" title="You do not have sufficient balance" class="btn disabled">Redeem</button></div>
                                    <?php } ?>
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
<?php }  if($sessionstaff['staffaccess']['AccessStaff']['amazon_redemption']==0 ){ ?>
                <?php $totalpt=$sessionstaff['customer_info']['total_points'];
                           $pt=explode('(',$totalpt); 
                           if(count($pt)>1){
                                    
                            $gp=rtrim($pt[1],')');
                            $totalpoints=$gp;
                           }else{
                             $totalpoints=$sessionstaff['customer_info']['total_points'];   
                           }
                           ?>
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
                    <div class="col-sm-6 ">
                      <?php if($totalpoints>0){ ?>


                        <button type="button" class="btn btn-success " style=" margin-top: 30px;" id="submit-redeem1" onclick="submitRedeemPoints('TNGO-E-V-STD');">Redeem</button>
                                    <?php }else{ ?>
                        <button type="button" class="btn disabled " style=" margin-top: 30px;cursor:auto;" id="submit-redeem1" onclick="submitRedeemPoints('TNGO-E-V-STD');">Redeem</button>

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
                    <div class="col-sm-6">
                    <?php if($totalpoints>0){ ?>
                        <button type="button" class="btn btn-success " style=" margin-top: 30px;" id="submit-redeem" onclick="submitRedeemPoints('AMZN-E-V-STD');">Redeem</button>
                                    <?php }else{ ?>
                        <button type="button" class="btn disabled " style=" margin-top: 30px;" id="submit-redeem" onclick="submitRedeemPoints('AMZN-E-V-STD');">Redeem</button>
                                    <?php } ?>

                        <span id="load-status1" style="display:none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span></div>
                </div>
<?php } ?>
            </div>
            <!--#endregion ThumbnailNavigator Skin End -->
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
                <h4 class="modal-title" id="redeemPointsLabel">Redeem Product/Service</h4>
            </div>
            <div class="modal-body">
                <div class="text-center points-value-span-container">
                    <span id="points_value_span" class=""></span>
                </div>
                <div class="clearfix" style="font-size: 9px;">
                    <input type="hidden" name="red_user_id" id="red_user_id">
                    <input type="hidden" name="red_product_id" id="red_product_id">
                    <input type="hidden" name="red_product_points" id="red_product_points">
                    <input type="hidden" name="red_product_name" id="red_product_name">
                    <input type="hidden" name="red_extra_point" id="red_extra_point">
                    <textarea id="notes" name="notes" class="form-control" rows="4" cols="50"></textarea>
                </div>
            </div>
            <div class="modal-body">
                <select name="redeem_staff_id1" id="redeem_staff_id1" required>
                    <option value=''>Redeemed By</option>
                        <?php 
                        if(count($staffs)==1){
                            $autosel1='selected';
                        }else{
                            $autosel1='';
                        }
                        foreach($staffs as $staff){ ?>
                    <option value="<?php echo $staff['Staff']['id']; ?>" <?php echo $autosel1; ?>><?php echo $staff['Staff']['staff_id']; ?></option>
                        <?php } ?>

                </select>
                <div for="staff_id" generated="true"  style="display:none;color:#FF0000;" id="staffRedeemError1">Please select staff</div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success " id="submit-redeem2"
                        onclick="redeemedByStaff();">Redeem</button>
                <span id="load-status2" style="display: none;"><img
                        alt="Please wait" title="BuzzyDoc"
                        src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="requestreviewModalMain" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog reedem-modalbox">
        <div class="modal-content">
            <div class="modal-header text-center">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true" id="closeredeem" >&times;</span></button>

                <h4 class="modal-title" id="redeemPointsLabel">Request a Review</h4>
            </div>
            <div class="modal-body">
                <div class="text-center points-value-span-container1">
                    <span id="review_message" style="font-size:17px;margin-bottom: 20px;display: block;color: red;">
                        Request a review from patients for the following locations. Select the ‘Send Request’ button next to the office you are currently at.
                    </span>
                    <?php
                   
                                if(!empty($clinicLocation)){
                                    foreach($clinicLocation as $loc){
                                        $socail_site = '';
                                        if($loc['ClinicLocation']['google_business_page_url']==''){
                                            $socail_site .='Google+, ';
                                        }
                                        if($loc['ClinicLocation']['yahoo_business_page_url']==''){
                                            $socail_site .='Yahoo, ';
                                        }
                                        if($loc['ClinicLocation']['yelp_business_page_url']==''){
                                            $socail_site .='Yelp, ';
                                        }
                                        if($loc['ClinicLocation']['healthgrades_business_page_url']==''){
                                            $socail_site .='Healthgrades, ';
                                        }
                                        $socail_site=rtrim($socail_site, ", ");
                                    ?>
                    <div class="row clearfix">
                        <div class="col-sm-6 showOnclick"><p><strong><span class="glyphicon glyphicon-pushpin"></span><?php echo $loc['ClinicLocation']['address'].', '.$loc['ClinicLocation']['city'].', '.$loc['ClinicLocation']['state']; ?></strong></p>
                        </div>
                        <div class="col-sm-6 ">
                            <?php if($loc['ClinicLocation']['google_business_page_url']=='' && $loc['ClinicLocation']['yahoo_business_page_url']=='' && $loc['ClinicLocation']['yelp_business_page_url']=='' && $loc['ClinicLocation']['healthgrades_business_page_url']==''){ ?>
                            <p><input type="button" onclick="sendRequestReview(0,0);" class="btn btn-minier btn-success" id="request-review_0_0" value="Send Request"></p>
                            <p>This location not have any social business page. <a href='/ClinicLocation/edit/<?php echo $loc['ClinicLocation']['id']; ?>' target="_blank">Click Here</a> to add the URL now or proceed with sending the request.</p>
                            <?php }else{ if(in_array($loc['ClinicLocation']['id'], $alreadyrate)){ ?>
                            <p><input type="button" class="btn btn-minier btn-success" disabled="disabled"  value="Send Request"></p>
                            <span style="font-size:8px;margin-bottom: 20px;display: block;color: red;" id="already_request_<?php echo $loc['ClinicLocation']['id']; ?>">Patient has already received points for reviewing this office</span>
                            <?php }else{ ?>
                            <p><input type="button" onclick="sendRequestReview(<?php echo $loc['ClinicLocation']['id']; ?>,<?php echo $loc['ClinicLocation']['id']; ?>);" class="btn btn-minier btn-success" id="request-review_<?php echo $loc['ClinicLocation']['id']; ?>_<?php echo $loc['ClinicLocation']['id']; ?>" value="Send Request"></p>
                            <span style="font-size:8px;margin-bottom: 20px;display: block;color: red;" id="already_request_<?php echo $loc['ClinicLocation']['id']; ?>"></span>
                            <?php if($loc['ClinicLocation']['google_business_page_url']=='' || $loc['ClinicLocation']['yahoo_business_page_url']=='' || $loc['ClinicLocation']['yelp_business_page_url']=='' || $loc['ClinicLocation']['healthgrades_business_page_url']==''){ ?>
                            <p>A <?php echo $socail_site; ?> page has not been added for this location yet. <a href='/ClinicLocation/edit/<?php echo $loc['ClinicLocation']['id']; ?>' target="_blank">Click Here</a> to add the URL now or proceed with sending the request.</p>
                            <?php } ?>
                            <?php }} ?>
                        </div>
                    </div>
                    
                            <?php
                                    } } ?>
                </div>
            </div>
            <!--#endregion ThumbnailNavigator Skin End -->
        </div>
    </div>
</div>
<?php if ($sessionstaff['customer_info']['clinic_id']!=$sessionstaff['clinic_id']) {
                    $fn = $sessionstaff['customer_info']['User']['first_name'];
                    $ln = $sessionstaff['customer_info']['User']['last_name'];
                    $em =$sessionstaff['customer_info']['User']['email'];
                    if ($fn != '') {
                        $displnm = $fn.' '.$ln;
                    } else {
                        $displnm = $em;
                    }
                    if($sessionstaff['customer_info']['User']['custom_date']=='00-00-0000' || $sessionstaff['customer_info']['User']['custom_date']==''){
                    $alertmsg="Hi! this is just a friendly reminder that you are about to issue points to a patient that was not initially setup at your office but is on buzzydoc network.The patient is ".$displnm.". Please confirm that this is the correct patient information before proceeding."; 
                    }else{
                    $dob = date("m-d-Y", strtotime($sessionstaff['customer_info']['User']['custom_date']));
                    $alertmsg="Hi! this is just a friendly reminder that you are about to issue points to a patient that was not initially setup at your office but is on buzzydoc network.The patient is ".$displnm." with a birthdate of ".$dob.". Please confirm that this is the correct patient information before proceeding.";
                    }
}
                    ?>
<script>
    $('#myinfo_form_assign').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            first_name: "required",
            last_name: "required",
            email: {
                email: true
            },
            date_year: {
                required: true,
                lessThanCurrentDate: true
            },
            date_month: {
                required: true,
                lessThanCurrentDate: true
            },
            date_day: {
                required: true,
                lessThanCurrentDate: true
            },
            parents_email: {
                email: true,
                checkparentemail: true
            },
            aemail: {
                required: true,
                checkparentemail: true
            },
        },
        // Specify the validation error messages
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your last name",
            email: {
                required: "Please enter a email address.",
                email: "Please enter a valid email address"
            },
            date_year: {
                required: "Please select date of year",
                lessThanCurrentDate: "You can not select future date."
            },
            date_month: {
                required: "Please select date of month",
                lessThanCurrentDate: "You can not select future date."
            },
            date_day: {
                required: "Please select date of day",
                lessThanCurrentDate: "You can not select future date."
            },
            parents_email: {
                required: "Please enter a valid email address.",
                checkparentemail: "Email and Username should be different."
            },
            aemail: {
                required: "Please enter username.",
                checkparentemail: "Email and Username should be different."
            },
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
            $(e).remove();
        },
        showErrors: function(errorMap, errorList) {
            if (errorList.length) {
                var s = errorList.shift();
                var n = [];
                n.push(s);
                this.errorList = n;
            }
            this.defaultShowErrors();
        },
        submitHandler: function(form) {
            if ($('#email').val() == '' || $('#parents_email').val() == '') {
                document.getElementById("myinfo_form_assign").action = '<?=Staff_Name?>PatientManagement/updateunregcustomer';
            }
            form.submit();
        }

    });
    $('#myinfo_form2').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            email: {
                required: false,
                email: true
            },
            date_year: {
                required: true,
                lessThanCurrentDate: true
            },
            date_month: {
                required: true,
                lessThanCurrentDate: true
            },
            date_day: {
                required: true,
                lessThanCurrentDate: true
            },
            parents_email: {
                required: false,
                email: true,
                checkparentemail: true
            },
            aemail: {
                required: false,
                checkparentemail: true
            },
        },
        // Specify the validation error messages
        messages: {
            email: {
                required: "Please enter a email address.",
                email: "Please enter a valid email address"
            },
            date_year: {
                required: "Please select date of year",
                lessThanCurrentDate: "You can not select future date."
            },
            date_month: {
                required: "Please select date of month",
                lessThanCurrentDate: "You can not select future date."
            },
            date_day: {
                required: "Please select date of day",
                lessThanCurrentDate: "You can not select future date."
            },
            parents_email: {
                required: "Please enter a valid email address.",
                checkparentemail: "Email and Username should be different."
            },
            aemail: {
                required: "Please enter username.",
                checkparentemail: "Email and Username should be different."
            },
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
            $(e).remove();
        },
        showErrors: function(errorMap, errorList) {
            if (errorList.length) {
                var s = errorList.shift();
                var n = [];
                n.push(s);
                this.errorList = n;
            }
            this.defaultShowErrors();
        },
        submitHandler: function(form) {
            form.submit();
        }

    });

    $(document).ready(function() {
//override dialog's title function to allow for HTML titles
        $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
            _title: function(title) {
                var $title = this.options.title || '&nbsp;'
                if (("title_html" in this.options) && this.options.title_html == true)
                    title.html($title);
                else
                    title.text($title);
            }
        }));
        $('button[id^="submit-redeem-"]').on('click', function(e) {
            redeemed($(this).attr('userid'), $(this).attr('prodid'), $(this).attr('prodpoints'), $(this).attr('prodtitle'));
        });

        $('#redeem_staff_id').on('change', function() {
            if ($(this).val() != "") {
                $('#staffRedeemError').hide();
            } else {
                $('#staffRedeemError').show();
            }
        });

        $('#redeem_staff_id1').on('change', function() {
            if ($(this).val() != "") {
                $('#staffRedeemError1').hide();
            } else {
                $('#staffRedeemError1').show();
            }
        });

        $('#redeem_staff_idTangAmazon').on('change', function() {
            if ($(this).val() != "") {
                $('#staffRedeemErrorTangAmazon').hide();
            } else {
                $('#staffRedeemErrorTangAmazon').show();
            }
        });

        function redeemed(user_id, product_id, redpoints, product_name) {

            var email = '<?php echo $sessionstaff['customer_info']['User']['email']; ?>';
            var r = true;
            if (email == '') {
                var r = confirm("That user doesn't have an email on record. Are you sure you want to proceed?");
            }


            if (r == true)
            {
                var totalGlPoint = $('#global-point-value').val();
                if (parseInt(totalGlPoint) >= parseInt(redpoints)) {
                    $('#staffPatientRedeemText').text("You have selected to redeem " + redpoints + " points towards " + product_name + " for patient <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?>. You will be responsible for issuing this prize to the user in-office.  Would you like to proceed?");
                    $("#staffPatientRedeem").removeClass('hide').dialog({
                        resizable: false,
                        modal: true,
                        title: "<div class='widget-header'><h4 class='smaller'> Redeem Points</h4></div>",
                        title_html: true,
                        buttons: [
                            {
                                text: "Cancel",
                                "class": "btn btn-xs",
                                click: function() {
                                    $(this).dialog("close");
                                }
                            },
                            {
                                text: "OK",
                                "class": "btn btn-primary btn-xs",
                                click: function() {
                                    if ($('#redeem_staff_id').val() == "") {
                                        $('#staffRedeemError').show();
                                    } else {
                                        $("#redeemload").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
                                        $.ajax({
                                            type: "POST",
                                            data: "user_id=" + user_id + '&product_id=' + product_id + '&points=' + redpoints + '&staff_id=' + $('#redeem_staff_id').val(),
                                            dataType: "json",
                                            url: "<?php echo Staff_Name.'PatientManagement/redeemlocproduct' ?>",
                                            success: function(result) {
                                                if (result == 1) {
                                                    alert('You have redeemed product successfully.');
                                                    window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/2' ?>';
                                                } else if (result == 2) {
                                                    alert('You do not have sufficient balance.');
                                                } else {
                                                    alert('Unable to redeem. Please contact buzzydoc admin.');
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        ]
                    });
                } else {
                    $('#redeemModalMain').modal().fadeOut(100);
                    $('.modal-backdrop').hide();
                    $('#red_user_id').val(user_id);
                    $('#red_product_id').val(product_id);
                    $('#red_product_points').val(redpoints);
                    $('#red_product_name').val(product_name);
                    $('#red_extra_point').val(totalGlPoint);
                    var balance = redpoints - totalGlPoint;
                    var pointsindol = balance / 50;
                    var num = parseFloat(pointsindol);
                    var new_num = num.toFixed(2);
                    $('#notes').val("Product of " + redpoints + " points is being redeemed against " + totalGlPoint + " points from the patients wallet and the balance of " + balance + " points or USD " + new_num + " is being collected from the patient by <?php echo $sessionstaff['var']['staff_name']; ?>");
                    $('#points_value_span').html("User have " + totalGlPoint + " Buzzydoc Points. You have selected to redeem " + redpoints + " points towards " + product_name + " for patient <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?>.");
                    $('#redeemModal').modal().fadeIn(100);

                }
            }
            else
            {
                return false;
            }
        }

    });

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

    if ($('#redeem_container').length != 0) {
        var jssor_slider2 = sliderObject('redeem_container', options);
        ScaleSlider(jssor_slider2);
    }

    function jsssss1() {
        if ($('#redeem_container1').length != 0) {
            var jsssss1 = sliderObject('redeem_container1', options);
            ScaleSlider(jsssss1);
        }
        return true;
    }


    $(window).bind("load", function() {
        if ($('#redeem_container').length != 0) {
            ScaleSlider(jssor_slider2);
        }
        jsssss1();
    });
    $(window).bind("resize", function() {
        if ($('#redeem_container').length != 0) {
            ScaleSlider(jssor_slider2);
        }
        jsssss1();
    });
    $(window).bind("orientationchange", function() {
        if ($('#redeem_container').length != 0) {
            ScaleSlider(jssor_slider2);
        }
        jsssss1();
    });
    var bonusTreatmentIds = <?php echo json_encode($sessionstaff['bonus_treatment']); ?>;

    if (bonusTreatmentIds.length > 0) {
        $.each(bonusTreatmentIds, function(i, v) {
            exemptBonus(v);
        });
    }
    function submitRedeemPoints(sku) {
        var email = '<?php echo $sessionstaff['customer_info']['User']['email']; ?>';
        if (email == '') {
            alert("NOTICE: <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?> does not have an Email-Id associated with his/her account yet. Please provide this field under Patient Info in order to complete the redemption.");
            return false;
        }

        var buzzPoints = parseInt($('#buzz_point').val());
        var arr = sku.split('-');
        if (arr[0] == 'AMZN') {
            var card = 'an Amazon';
        } else {
            var card = 'a Tango';
        }


        $('#staffPatientRedeemTangAmazonText').text("You have selected to redeem " + buzzPoints + " points towards " + card + " gift card for patient <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?>. This prize will automatically be sent to the user\'s email address, <?php echo $sessionstaff['customer_info']['User']['email']; ?>. Please ensure all information is correct before clicking \'OK\' to proceed.");

        $("#staffPatientRedeemTangAmazon").removeClass('hide').dialog({
            resizable: false,
            modal: true,
            title: "<div class='widget-header'><h4 class='smaller'> Redeem Points</h4></div>",
            title_html: true,
            buttons: [
                {
                    text: "Cancel",
                    "class": "btn btn-xs",
                    click: function() {
                        $(this).dialog("close");
                    }
                },
                {
                    text: "OK",
                    "class": "btn btn-primary btn-xs",
                    click: function() {
                        if ($('#redeem_staff_idTangAmazon').val() == "") {
                            $('#staffRedeemErrorTangAmazon').show();
                        } else {
                            $(this).dialog("close");
                            $("#redeemload").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
                            if (sku == 'AMZN-E-V-STD') {

                                $('#load-status1').show();
                                $('#submit-redeem').attr('disabled', 'disabled');
                                $('#submit-redeem1').attr('disabled', 'disabled');
                            } else {

                                $('#load-status').show();
                                $('#submit-redeem').attr('disabled', 'disabled');
                                $('#submit-redeem1').attr('disabled', 'disabled');
                            }
                            var clinic_id = 0;
                            if (buzzPoints > 0) {
                                $('.input-error').remove();

                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo Staff_Name.'PatientManagement/placeorder' ?>',
                                    dataType: 'json',
                                    data: {
                                        sku: sku, //$('#recent-adcitivies a').length,
                                        clinic_id: clinic_id,
                                        staff_id: $('#redeem_staff_idTangAmazon').val(),
                                        amount: buzzPoints,
                                        user_id: '<?php echo $sessionstaff['customer_info']['User']['id']; ?>'
                                    },
                                    success: function(r) {
                                        if (r.success == true) {
                                            if (r.error != '') {
                                                var errcl = 'Partial redemption successful. Check your e-mail. Transaction for ' + r.error + ' has failed. Your points are safe in your account.'
                                            } else {
                                                var errcl = 'Redemption successful. Check your e-mail.';
                                            }
                                            $('#load-status').hide();
                                            $('#load-status1').hide();
                                            var total = $('#toppoint').text();
                                            $('.input-error').remove();
                                            var remindol = r.pointremain / 50;
                                            $('.theme-bold-text').text(r.pointremain);
                                            $('.theme-bold-text1').text(remindol);
                                            var $newDataContainer = $('<div class="input-error alert alert-danger alert-dismissible" role="alert"></div>');
                                            $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                            $newDataContainer.append('<span>' + errcl + '</span>');
                                            if (sku == 'AMZN-E-V-STD') {
                                                $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
                                            } else {
                                                $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
                                            }
                                            location.reload();
                                        } else {
                                            if (r.error != '') {
                                                var errcl = 'Check your e-mail. Transaction for ' + r.error + ' has failed. Your points are safe in your account.'
                                            } else {
                                                var errcl = '';
                                            }
                                            $('#load-status').hide();
                                            $('#load-status1').hide();
                                            $('#submit-redeem').removeAttr('disabled');
                                            $('#submit-redeem1').removeAttr('disabled');
                                            alert('Unable to redeem. Please contact buzzydoc admin.' + errcl);
                                        }
                                    }
                                });

                            } else {
                                $('.input-error').remove();
                                $('#load-status').hide();
                                $('#load-status1').hide();

                                var $newDataContainer = $('<div class="input-error alert alert-danger alert-dismissible" role="alert"></div>');
                                $newDataContainer.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                $newDataContainer.append('<span>You do not have sufficient balance</span>');
                                if (sku == 'AMZN-E-V-STD') {
                                    $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
                                } else {
                                    $('#redeemModalMain .modal-body .points-value-span-container1').before($newDataContainer);
                                }
                            }
                        }
                    }
                }
            ]
        });
    }
    function redeemclose() {
        $('#load-status').hide();
        $('#load-status1').hide();
        $('#submit-redeem').removeAttr('disabled');
        $('#submit-redeem1').removeAttr('disabled');
        $('#submit-redeem2').removeAttr('disabled');
        $('#redeemModalMain .modal-body .points-value-span-container1').before('');
    }
    function redeemedByStaff() {
        if ($("#redeem_staff_id1").val() == "") {
            $("#staffRedeemError1").show();
            return false;
        } else {
            $("#staffRedeemError1").hide();
        }
        var user_id = $('#red_user_id').val();
        var product_id = $('#red_product_id').val();
        var rdpoints = $('#red_product_points').val();
        var product_name = $('#red_product_name').val();
        var redeem_notes = $('#notes').val();
        var extra = rdpoints - $('#red_extra_point').val();
        if (redeem_notes != '') {
            var r = confirm("You have selected to redeem " + rdpoints + " points towards " + product_name + " for patient <?php echo $sessionstaff['customer_info']['User']['first_name']; ?> <?php echo $sessionstaff['customer_info']['User']['last_name']; ?>. You will be responsible for issuing this prize to the user in-office.  Would you like to proceed?");
            if (r == true)
            {

                $('#load-status2').show();
                $.ajax({
                    type: "POST",
                    data: "user_id=" + user_id + '&product_id=' + product_id + '&points=' + $('#red_extra_point').val() + '&redeem_notes=' + redeem_notes + '&extra_point=' + extra + '&staff_id=' + $("#redeem_staff_id1").val(),
                    dataType: "json",
                    url: "<?php echo Staff_Name.'PatientManagement/redeemlocproduct' ?>",
                    success: function(result) {
                        if (result == 1) {
                            alert('You have redeemed product successfully.');
                            window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/2' ?>';
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
        } else {
            alert('Please enter notes.');
            return false;
        }
    }

    $(document).on("click", ".redeem-points-button-main-container", function() {
        $('.modal-backdrop').show();
        $('#load-status').hide();
        $('#submit-redeem').removeAttr('disabled');
        $('#submit-redeem1').removeAttr('disabled');
        var tlpoints = $('#global-point-value').val();
        var point_type = $(this).attr('data-type');
        var clinic_id = '<?php echo $sessionstaff['clinic_id']; ?>';
        $('#point_type').val(point_type);
        $('#buzz_point').val(tlpoints);
        $('#clinic_id').val(clinic_id);
        var pointsindol = tlpoints / 50;
        var num = parseFloat(pointsindol);
        var new_num = num.toFixed(2);
        $('#points_value_span1').html("<?php echo $sessionstaff['customer_info']['User']['first_name']; ?> has <span class='theme-bold-text'>" + tlpoints + "</span> points,<br />or $<span class='theme-bold-text1'>" + new_num + "</span>, to redeem for rewards today!");

        $('.input-error').remove();
        $('#redeemModalMain').modal().fadeIn(100);
        $('#points_value').empty();
    });

    function exemptBonus(treatmentId) {
        console.log($('#treatment_div_' + treatmentId).find('i[id^=exempt_sign_]'));
        $('#treatment_div_' + treatmentId).find('i[id^=exempt_sign_]').each(function(i, v) {
            $(v).trigger('click');
            $(v).hide();
        });
    }

    function exmptPromotion(treatmentId, promotionId) {
        var exempt_count = $('#exempt_count_' + treatmentId).val();
        var count = 0;
        if ($('#label_' + treatmentId + '_' + promotionId).hasClass("blur2")) {
            count = parseInt(exempt_count) - 1;
            $('#label_' + treatmentId + '_' + promotionId).removeClass('blur2');
            $('#global_promo_' + treatmentId + '_' + promotionId).removeAttr('disabled', 'disabled');
            $('#exempt_count_' + treatmentId).val(count);
            $('#exempt_span_' + treatmentId + '_' + promotionId).text('Exempt');
        } else {
            count = parseInt(exempt_count) + 1;
            $('#label_' + treatmentId + '_' + promotionId).addClass('blur2');
            $('#global_promo_' + treatmentId + '_' + promotionId).attr('disabled', 'disabled');
            $('#exempt_count_' + treatmentId).val(count);
            $('#exempt_span_' + treatmentId + '_' + promotionId).text('Exempted');
        }
        return true;
    }

    function removeTreatment(treatmentId) {
        if (treatmentId) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "<?php echo Staff_Name.'PatientManagement/savetreatmentsettings' ?>",
                data: {upper_level_setting_id: treatmentId, remove: 1},
                success: function(msg) {
                    if (msg.success == 0) {
                        $("#goodMessage").text(msg.message);
                        $("#goodMessage").fadeIn(1000);
                        $("#goodMessage").fadeOut(3000);
                    } else {
                        $("#goodMessage").text(msg.message);
                        $("#goodMessage").fadeIn(1000);
                        $("#goodMessage").fadeOut(4000);
                        $('#treatment_h3_' + treatmentId).remove();
                        $('#treatment_div_' + treatmentId).remove();
                        window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/1' ?>';

                    }
                }
            });
        }
    }
    $(document).ready(function() {
        var isTreatMentAssigned = "<?php echo $treatmentAssigned; ?>";


        $(document).on("click", '#bonus_available', function(event) {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });


        $("#accordion").accordion({
            collapsible: false,
            heightStyle: "content"
        });

        $("#accordion1").accordion({
            collapsible: false,
            heightStyle: "content"
        });
        var newTreatmentDialog = $("#new-treatment-dialog").dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true,
            buttons: {
                "Cancel": {
                    text: 'Cancel',
                    'class': 'btn ',
                    click: function() {
                        $(this).dialog("close");
                    }
                },
                "Start": {
                    text: 'Start',
                    'class': 'btn btn-info',
                    click: function() {
                        var treatmentVal = $('#gettreatment').val();
                        var bonus = $('#bonus_available').val();

                        if (treatmentVal == '') {
                            alert('Please select treatment');
                            return false;
                        }

                        $.ajax({
                            type: "POST",
                            url: "<?php echo Staff_Name.'PatientManagement/savetreatmentsettings' ?>",
                            data: {upper_level_setting_id: treatmentVal, bonus: bonus},
                            success: function(msg) {
                                window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/1' ?>';
                            }
                        });
                        //$( this ).dialog( "close" );
                    }
                }
            },
            close: function() {
            }
        });


        $("#start_treatment_btn").button().on("click", function() {
            if (isTreatMentAssigned == 0) {
                alert('No treatment to start');
                return false;
            }
            $('#gettreatment').find('option:eq(0)').prop('selected', true);
            $('#first_visit').prop("checked", false);
            $('#bonusarea').html('');
            $('#perfect_visit').prop("checked", false).attr('disabled', 'disabled');
            $("#new-treatment-dialog").show();
            newTreatmentDialog.dialog("open");
        });

    });

    jQuery(function($) {
        var oldie = /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase());
        $('.easy-pie-chart.percentage').each(function() {
            $(this).easyPieChart({
                barColor: $(this).data('color'),
                trackColor: '#EEEEEE',
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: 8,
                animate: oldie ? false : 1000,
                size: 75
            }).css('color', $(this).data('color'));
        });
    });



    function getbonusarea() {
        var treatment_id = $('#gettreatment').val();
        datasrc = 'treatment_id=' + treatment_id;
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>PatientManagement/getbonus/",
            success: function(result) {
                $('#bonusarea').html(result);
            }
        });
    }
    function checkuserexist() {
        $('#asgnlink').attr('disabled');
        var datasrc = '';
        var yr = $('#date_year').val();
        var mn = $('#date_month').val();
        var dy = $('#date_day').val();

        if ($('#email').val() == undefined) {
            var email = $('#parents_email').val();
        }
        else {
            var email = $('#email').val();
        }
        if ($.trim(email) != '') {
            datasrc = datasrc + "&email=" + email + '&dob=' + yr + '-' + mn + '-' + dy;
        }
        if ($.trim($('#aemail').val()) != undefined && $.trim($('#aemail').val()) != '' && $.trim(email) != '') {
            var pemail = $('#aemail').val();
            datasrc = datasrc + "&parents_email=" + pemail + '&dob=' + yr + '-' + mn + '-' + dy;
        }
        if (datasrc != '') {
            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>PatientManagement/checkuserexist/",
                success: function(result) {
                    if (result == 0) {
                        $('#asgnlink').text('Assign Card');
                        $('#type').val(0);
                        $('#uid').val(0);
                        $('#asgnlink').removeAttr("disabled");
                    } else {
                        $('#uid').val(result);
                        $('#asgnlink').text('Link Card');
                        $('#type').val(1);
                        $('#asgnlink').removeAttr("disabled");
                    }
                }
            });
        }

    }
    function checkusername() {
        var fname = $('#first_name').val();
        var lname = $('#last_name').val();

        datasrc = 'fname=' + fname + '&lname=' + lname;
        if ($('#email').val() == undefined && fname != '' && lname != '') {

            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>PatientManagement/checkusername/",
                success: function(result) {
                    $('#aemail').val(result);
                }
            });
        }
    }
    $("#unblock_new_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var email = $("#unblock_email").val();
        var user_id = $("#unblock_user_id").val();
        var card = $('#unblock_card').val();
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (email == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_unblock").html("Please enter email-id");
        } else if (!regex.test(email)) {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error_unblock").html("Please enter valid email-id");

        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>PatientManagement/unblock/",
                data: "&user_id=" + user_id + "&email=" + email + "&card=" + card,
                success: function(msg) {

                    if (msg == 1) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#status_error_unblock").html("Email id already exists.");
                    } else if (msg == 0) {
                        $("#status_error_unblock").html("Account Unblock successfully.");
                        $('input[type="button"]').attr('disabled', 'disabled');
                        $('#unblockdiv').css('display', 'none');
                    } else {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#status_error_unblock").html("Error found try again");
                    }

                }
            });
        }

    });


    function cunfdelete() {
        var r = confirm("Are you sure to delete activity?");
        if (r == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function verfiy() {
        $.ajax({
            type: "POST",
            url: "<?php echo Staff_Name.'PatientManagement/sendVerify' ?>",
            success: function(msg) {
                if (msg == 1) {
                    alert('Verification Email sent successfuly.');
                } else {
                    alert('Verification Email not sent.');
                }
            }
        });
    }

    function changeStatusRedeem() {
        $('#redeem_status').val('');
        $("#dialog").dialog();
    }
    $("#assgn_new_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var new_card = $("#new_card").val();
        var old_user_id = $("#old_user_id").val();
        var old_card = $("#old_card").val();
        if (new_card == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error").html("Please enter new card number");
        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>PatientManagement/checkcardnumber/",
                data: "&user_id=" + old_user_id + "&new_card=" + new_card + "&old_card=" + old_card,
                success: function(msg) {

                    if (msg == 1) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#status_error").html("Invalid card number.");
                    } else if (msg == 2) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#status_error").html("Card already assigned to other patient.");
                    } else if (msg == 3) {
                        $("#status_error").html("New card successfully assign to patient.");
                        $('input[type="button"]').attr('disabled', 'disabled');
                    } else {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#status_error").html("Error found try again");
                    }

                }
            });
        }

    });
    $("#id-btn-dialog1").on('click', function(e) {
        e.preventDefault();

        var dialog = $("#dialog-message").removeClass('hide').dialog({
            modal: true,
            title: "Assign To New Card",
            title_html: true,
        });

    });
    $("#id-btn-dialog12").on('click', function(e) {
        e.preventDefault();

        var dialog = $("#dialog-message2").removeClass('hide').dialog({
            modal: true,
            title: "Unblock Account",
            title_html: true,
        });

    });
    function frmsubmit(id) {
        var myfrom = 'choose_' + id + '_form';
        document.forms["choose_" + id + "_form"].submit();

    }
     <?php if($sessionstaff['clinic_id']==5){ ?>
    $(document).ready(function() {
        $.each($('div[id^="ui-id-"]'), function(i, v) {
            $(v).css('height', 'auto');
        });
        $.each($('div[id^="treatment_div_"]'), function(i, v) {
            $(v).css('height', 'auto');
        });
        $('#add_transaction_form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                amount: {
                    number: true
                },
                staff_id: "required",
                doctor_id: "required"
                ,
                transaction_description: "required"

            },
            // Specify the validation error messages
            messages: {
                amount: {
                    number: "Please enter a valid amount"
                },
                staff_id: "Please select staff",
                doctor_id: "Please select doctor"
                ,
                transaction_description: {
                    required: "Please enter Staff Member Initials"
                }
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },
            showErrors: function(errorMap, errorList) {
                if (errorList.length) {
                    var s = errorList.shift();
                    var n = [];
                    n.push(s);
                    this.errorList = n;
                }
                this.defaultShowErrors();
            },
            submitHandler: function(form) {
                $("#recordButton").prop("disabled", true);
                var clid = $('#searchclinic').val();
                if (clid !=<?php echo $sessionstaff['clinic_id']; ?> && clid != '') {

                    var r = confirm("<?php echo $alertmsg; ?>");
                    if (r == true)
                    {
                        form.submit();
                    }
                    else
                    {
                        $("#recordButton").prop("disabled", false);
                        return false;
                    }
                } else {
                    form.submit();
                }
            }

        });
    });
     <?php }else{ ?>
    $(document).ready(function() {
        $('#add_transaction_form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                amount: {
                    number: true
                },
                staff_id: "required",
                doctor_id: "required"


            },
            // Specify the validation error messages
            messages: {
                amount: {
                    number: "Please enter a valid amount"
                },
                staff_id: "Please select staff",
                doctor_id: "Please select doctor"
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },
            showErrors: function(errorMap, errorList) {
                if (errorList.length) {
                    var s = errorList.shift();
                    var n = [];
                    n.push(s);
                    this.errorList = n;
                }
                this.defaultShowErrors();
            },
            submitHandler: function(form) {
                $("#recordButton").prop("disabled", true);
                var clid = $('#searchclinic').val();

                if (clid !=<?php echo $sessionstaff['clinic_id']; ?> && clid != '') {

                    var r = confirm("<?php echo $alertmsg; ?>");
                    if (r == true)
                    {
                        form.submit();
                    }
                    else
                    {
                        $("#recordButton").prop("disabled", false);
                        return false;
                    }
                } else {
                    form.submit();
                }

            }
        });
    });
     <?php } ?>
    function manualamount() {
        $('#amounttextfield').css("display", "block");
        $("#amounttext").css("display", "none");
    }
    function checkage2() {
        var yr = $("#date_year").val();
        var mn = $("#date_month").val();
        var dy = $("#date_day").val();
        var today = new Date()
        var past = new Date(yr, mn, dy);
        var diff = Math.floor(today.getTime() - past.getTime());
        var day = 1000 * 60 * 60 * 24;

        var days = Math.floor(diff / day);
        var months = Math.floor(days / 31);
        var years = Math.floor(months / 12);

        if ((days <= 6542) && (days >= 1)) {
            $('#pemail').css('display', 'block');
            $('#email_field').html('<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Username:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="text" name="aemail" id="aemail" value="<?=str_replace("'", "\'", $ademail)?>" maxlength="50"></div>');

            return true;
        }
        else {
            $('#pemail').html('');
            $('#email_field').html('<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Email:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="email" id="email" value="<?=$sessionstaff['customer_info']['email']?>" maxlength="50"></div>');
            return true;
        }

    }

    function getemailcont() {

        var emailprovide = $('input[name=emailprovide]:checked').val();
        if (emailprovide == 'perent') {
            $xml = '<div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Email:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" required maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();checkusername();"></div></div><div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Username:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="text" name="aemail" id="aemail" value="<?=str_replace("'", "\'", $ademail)?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();"></div></div>';

        } else {
            $xml = '<div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Email:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();" required></div></div>';
        }
        $('#emailvalid').html($xml);
    }
    function checkage1() {
        var yr = $("#date_year").val();
        var mn = $("#date_month").val();
        var dy = $("#date_day").val();
        var today = new Date();
        var past = new Date(yr, mn, dy);
        var diff = Math.floor(today.getTime() - past.getTime());
        var day = 1000 * 60 * 60 * 24;
        var with_email = $("#with_email").val();
        var days = Math.floor(diff / day);
        var months = Math.floor(days / 31);
        var years = Math.floor(months / 12);
        if (with_email == 0) {
            var requ = '<span class="star">*</span>';
            var requ1 = 'required="required"';
        } else {
            var requ = '';
            var requ1 = '';
        }
        if ((days <= 4716) && (days >= 1)) {

            $('#perenttype').html('<div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right">' + requ + 'Email:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();checkusername();" placeholder="Email" ' + requ1 + '></div></div><div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Username:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="text" name="aemail" id="aemail" value="<?=str_replace("'", "\'", $ademail)?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();"></div></div>');
            return true;
        }
        else if ((days <= 6543) && (days >= 4716)) {
            $('#perenttype').html('<div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right">Choose email:</label><div class="col-sm-9"><div class="patientinfo_radio"><input type="radio" value="own" name="emailprovide" id="gender" checked class=""><label class=" control-label">Own</label></div><div class="patientinfo_radio"><input type="radio" value="perent" name="emailprovide" id="gender" class=""><label class=" control-label">Parent</label><div></div></div></div></div><div id="emailvalid"><div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right">' + requ + 'Email:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" maxlength="50"  onblur="checkuserexist();checkusername();" onmouseout="checkuserexist();" ' + requ1 + ' placeholder="Email"></div></div></div></div><div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Username:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="text" name="aemail" id="aemail" value="<?=str_replace("'", "\'", $ademail)?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();"></div></div>');
            return true;
        }
        else {
            $('#perenttype').html('<div class="form-group"><label for="form-field-1" class="col-sm-3 control-label no-padding-right">' + requ + 'Email:</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="email" id="email" value="<?=$sessionstaff['customer_info']['email']?>" maxlength="50"  onblur="checkuserexist();" onmouseout="checkuserexist();" ' + requ1 + ' placeholder="Email"></div></div>');
            return true;
        }

    }


    function checkage() {
        var yr = $("#date_year").val();
        var mn = $("#date_month").val();
        var dy = $("#date_day").val();
        var today = new Date()
        var past = new Date(yr, mn, dy);
        var diff = Math.floor(today.getTime() - past.getTime());
        var day = 1000 * 60 * 60 * 24;

        var days = Math.floor(diff / day);
        var months = Math.floor(days / 31);
        var years = Math.floor(months / 12);

        if ((days <= 6542) && (days >= 1)) {
            $('#pemail').css('display', 'block');
            $('#pemail').html('<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Email-Id:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" maxlength="50"></div>');

            $('#email_field').html('<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Username</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="text" name="aemail" id="aemail" value="<?=str_replace("'", "\'", $ademail)?>" maxlength="50" ></div>');

            return true;
        }
        else {
            $('#pemail').html('');
            $('#email_field').html('<label for="form-field-1" class="col-sm-3 control-label no-padding-right">Email</label><div class="col-sm-9"><input class="col-xs-12 col-sm-7" type="email" name="email" id="email" value="<?=$sessionstaff['customer_info']['email']?>" maxlength="50" ></div>');
            return true;
        }

    }
    function checkemail() {

        var id = $('#id').val();
        var datasrc = "id=" + id;
        if ($('#email').val() == undefined) {
            var email = $('#parents_email').val();
            var parents_email = $('#aemail').val();
            datasrc = datasrc + "&email=" + email + "&parents_email=" + parents_email;
        }
        else {
            var email = $('#email').val();
            datasrc = datasrc + "&email=" + email;
        }
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>PatientManagement/checkemail/",
            success: function(result) {

                if (result == 1) {

                    $("#email").focus();
                    alert('Email and Username already exists.');
                    return false;
                }
                else if (result == 2) {
                    $("#parents_email").focus();
                    alert('Email Id already exists.');
                    return false;

                }
                else if (result == 3) {
                    $("#email").focus();
                    alert('Email Id already exists.');
                    return false;

                }
                else if (result == 4) {
                    $("#aemail").focus();
                    alert('Username already exists.');
                    return false;

                }
                else {
                    $("#myinfo_form").submit();
                    return true;
                }
            }
        });
        return false;
    }

    $(document).ready(function() {

//        $('h3#ui-id-1').trigger('click');
        $.validator.addMethod("zipRegex", function(value, element) {
            return this.optional(element) || /^[a-z0-9]+$/i.test(value);
        }, "Zipcode must contain only alphanumeric.");

        $('#myinfo_form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                new_password: {
                    minlength: 6
                },
                new_password2: {
                    equalTo: "#new_password", minlength: 6
                },
                email: {
                    email: true
                },
                phone: {
                    number: true, minlength: 7, maxlength: 10
                },
                date_year: {
                    lessThanCurrentDate: true
                },
                date_month: {
                    lessThanCurrentDate: true
                },
                date_day: {
                    lessThanCurrentDate: true
                },
                parents_email: {
                    email: true,
                    checkparentemail: true
                },
                aemail: {
                    checkparentemail: true
                },
                postal_code: {
                    zipRegex: true,
                    minlength: 4, maxlength: 6
                }

            },
            // Specify the validation error messages
            messages: {
                new_password: {
                    minlength: "Your password must be at least 6 characters long"
                },
                email: "Please enter a valid email address",
                phone: {
                    number: "Please enter a valid phone number",
                    minlength: "Phone Number must be 7 to 10 characters long",
                    maxlength: "Phone Number must less then 11 characters"
                },
                date_year: {
                    lessThanCurrentDate: "You can not select future date."
                },
                date_month: {
                    lessThanCurrentDate: "You can not select future date."
                },
                date_day: {
                    lessThanCurrentDate: "You can not select future date."
                },
                parents_email: {
                    checkparentemail: "Email and Username should be different."
                },
                aemail: {
                    checkparentemail: "Email and Username should be different."
                },
                postal_code: {
                    zipRegex: "Please enter valid zipcode",
                    minlength: "Zip code must be greater then 3 characters",
                    maxlength: "Zip code must be less then 7 characters"
                },
                gender: "Please select gender",
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },
            showErrors: function(errorMap, errorList) {
                if (errorList.length) {
                    var s = errorList.shift();
                    var n = [];
                    n.push(s);
                    this.errorList = n;
                }
                this.defaultShowErrors();
            },
            submitHandler: function(form) {
                if ($('#internal_id').val() != undefined && $('#internal_id').val() != '') {
                    if ($('#internal_id').val() > 0) {
                        datasrc = "internal_id=" + $('#internal_id').val() + "&user_id=" + $('#id').val();
                        ;
                        $.ajax({
                            type: "POST",
                            data: datasrc,
                            url: "<?=Staff_Name?>PatientManagement/checkInternalId/",
                            success: function(result) {
                                if (result == 1) {
                                    $("#internal_id").focus();
                                    alert('Internal Id already exists.');
                                    return false;
                                }
                                else {
                                    form.submit();
                                }
                            }
                        });
                    } else {
                        $("#internal_id").focus();
                        alert('Internal Id should be greater then 0.');
                        return false;
                    }
                } else {
                    form.submit();
                }

            }

        });
    });

    function getcity() {

        var state = $('#state').val();

        $.ajax({
            type: "POST",
            data: "state=" + state,
            url: "<?=Staff_Name?>PatientManagement/getcity/",
            success: function(result) {
                $('#city').html(result);
            }});
    }


    function changestatus(tid, clinic_id) {
        var status_name = $("#redeem_status_" + tid).val();
        var id = tid;
        var r = confirm("Are you sure to change status ?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                url: "/StaffRedeem/changeredeemstatusxml/",
                data: "&id=" + tid + "&status=" + status_name + "&clinic_id=" + clinic_id,
                success: function(msg) {
                    if (msg == 1) {
                        $('#redeem_status_' + tid).attr('disabled', 'disabled');
                    }
                    alert('Status changed successfully.');
                    $("#coupon_" + tid).html('');


                    $('#coupon_show_' + tid).hide();


                }
            });
        } else
        {
            return false;
        }
    }
    function transactionType() {
        var transactionType = $("#transactionType").val();

        $.ajax({
            type: "POST",
            url: "/PatientManagement/transactionTypeDetails/",
            data: "transactionType=" + transactionType,
            success: function(msg) {
                $('#allTransaction').html(msg);

            }
        });

    }
    function transactionTypeReminder() {
        var transactionType = 'R';

        $.ajax({
            type: "POST",
            url: "/PatientManagement/transactionTypeDetails/",
            data: "transactionType=" + transactionType,
            success: function(msg) {
                $('#allTransaction').html(msg);

            }
        });

    }

    function getPointVal() {
        var dollar_val = $('#dollar_amount').val();
        $('#calculate_amount').val(dollar_val);
        $.ajax({
            type: "POST",
            url: "/PatientManagement/getPointVal/",
            data: "dollar_val=" + dollar_val,
            success: function(msg) {
                $('#add_amount_textbox').val(msg);

            }
        });
    }
    function sendAccountInfo() {
        $.ajax({
            type: "POST",
            url: "<?php echo Staff_Name.'PatientManagement/sendAccountInfo' ?>",
            success: function(msg) {
                if (msg == 1) {
                    alert('Account information successfully sent.');
                } else {
                    alert('Account Info not sent.Try again later.');
                }
            }
        });
    }


    function DeleteHistory(tid) {
        var user_id = <?php echo $sessionstaff['customer_info']['User']['id'] ?>;
        var card_number = <?=$sessionstaff['customer_info']['card_number']?>;
        var product_service = '<?=$sessionstaff['staffaccess']['AccessStaff']['product_service']?>';
        var accelerated = '<?=$sessionstaff['staffaccess']['AccessStaff']['tier_setting']?>';
        if (product_service == 1 && accelerated == 1 && user_id > 0) {
            var r = confirm("If enough points are given for the patient to qualify for the next tier, deleting points WILL NOT result in the patient being brought down a tier.  Would you like to proceed?");
            if (r == true)
            {
                $.ajax({
                    type: "POST",
                    data: "user_id=" + user_id + '&card_number=' + card_number + '&tid=' + tid,
                    dataType: "json",
                    url: "<?php echo Staff_Name.'PatientManagement/deletehistory' ?>",
                    success: function(result) {
                        if (result == 1) {
                            alert('Transaction deleted successfuly.');
                            window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/2' ?>';
                        } else if (result == 2) {
                            alert('Current Balance is low.Transaction not deleted.');
                        } else {
                            alert('Transaction not deleted.');
                        }
                    }
                });
            }
            else
            {
                return false;
            }
        } else {
            $.ajax({
                type: "POST",
                data: "user_id=" + user_id + '&card_number=' + card_number + '&tid=' + tid,
                dataType: "json",
                url: "<?php echo Staff_Name.'PatientManagement/deletehistory' ?>",
                success: function(result) {
                    if (result == 1) {
                        alert('Transaction deleted successfuly.');
                        window.location.href = '<?php echo Staff_Name.'PatientManagement/recordpoint/2' ?>';
                    } else if (result == 2) {
                        alert('Current Balance is low.Transaction not deleted.');
                    } else {
                        alert('Transaction not deleted.');
                    }
                }
            });
        }
    }
    function sendRequestReview(location_id,id) {

        $('#request-review_'+location_id+'_'+id).attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            data: "location_id=" + location_id,
            url: "<?php echo Staff_Name.'PatientManagement/sendRequestReview' ?>",
            success: function(msg) {
                if (msg == 1) {

                    $('#request-review_'+location_id+'_'+id).removeAttr('disabled');
                    alert('Request has been successfully sent.');
                }else if (msg == 2) {

                    $('#request-review_'+location_id+'_'+id).attr('disabled', 'disabled');
                    $('#already_request_'+location_id).text('Patient has already received points for reviewing this office');
                } else {

                    $('#request-review_'+location_id+'_'+id).removeAttr('disabled');
                    alert('Request mail not sent.Try again later.');
                }
            }
        });
    }
    $(document).on("click", ".requestReview", function() {
        $('#requestreviewModalMain').modal().fadeIn(100);
    });


</script>

