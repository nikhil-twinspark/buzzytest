        <?php //echo $this->Html->script('ckeditor/ckeditor'); ?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-tachometer"></i> Staff Access Control
        </h1>
    </div>
</div>
<div style="color:red;font-size: 12px;">
    If you fill number of treatment plan and custom promotion as 0 that means clinic staff can add unlimited treatment plans and custom promotions.

</div>
         <?php 
         //echo $this->element('messagehelper'); 
         echo $this->Session->flash('good');
         echo $this->Session->flash('bad');

         ?>

<form name="addcard" id="addcard" action="/ClientManagement/staff_access/<?php echo $staff_access['AccessStaff']['clinic_id']; ?>" method="post" class="form-horizontal">
    <input type="hidden" id="clinic_id" name="id" value="<?php echo $staff_access['AccessStaff']['id']; ?>">
    <input type="hidden" id="error_dis" name="error_dis" value="">

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Upper Level Rewards Program :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="levelup" id="levelup" onchange="make_readonly();" class="" <?php if( $staff_access['AccessStaff']['levelup']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="levelup" id="levelup" onchange="make_readonly();" class="" <?php if( $staff_access['AccessStaff']['levelup']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Interval Rewards Program :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="interval" id="interval" onchange="make_interval_readonly();" class="" <?php if( $staff_access['AccessStaff']['interval']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="interval" id="interval" onchange="make_interval_readonly();" class="" <?php if( $staff_access['AccessStaff']['interval']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
<?php if( $staff_access['AccessStaff']['levelup']==1){ $red=""; }else{ $red="readonly"; } ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Number Of Treatment Plans :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  name="no_of_plan" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" id="no_of_plan" class="" value="<?php echo $staff_access['AccessStaff']['no_of_plan']; ?>" <?php echo $red; ?>>

        </div>

    </div>
    <?php if( $staff_access['AccessStaff']['interval']==1){ $red_int=""; }else{ $red_int="readonly"; } ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Number Of Interval Plans :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  name="no_of_interval" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" id="no_of_interval" class="" value="<?php echo $staff_access['AccessStaff']['no_of_interval']; ?>" <?php echo $red_int; ?>>

        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Staff Rewards Program :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="staff_reward_program" id="staff_reward_program"  class="" <?php if( $staff_access['AccessStaff']['staff_reward_program']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="staff_reward_program" id="staff_reward_program" class="" <?php if( $staff_access['AccessStaff']['staff_reward_program']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Patient Rewards Reporting Feature :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="reward_reporting" id="reward_reporting" disabled="" class="" <?php if( $staff_access['AccessStaff']['reward_reporting']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="reward_reporting" id="reward_reporting" disabled="" class="" <?php if( $staff_access['AccessStaff']['reward_reporting']==0){ echo "checked"; } ?> > Off
        </div>

    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Staff Rewards (Input) :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="staff_input" id="staff_input" class="" <?php if( $staff_access['AccessStaff']['staff_input']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="staff_input" id="staff_input" class="" <?php if( $staff_access['AccessStaff']['staff_input']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Staff Rewards (Output/Reporting) :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="staff_reporting" id="staff_reporting" class="" <?php if( $staff_access['AccessStaff']['staff_reporting']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="staff_reporting" id="staff_reporting" class="" <?php if( $staff_access['AccessStaff']['staff_reporting']==0){ echo "checked"; } ?> > Off
        </div>

    </div>


    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Can Offer Products/Services :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="product_service" id="product_service" class="" <?php if( $staff_access['AccessStaff']['product_service']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="product_service" id="product_service" class="" <?php if( $staff_access['AccessStaff']['product_service']==0){ echo "checked"; } ?> > Off
        </div>

    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Can Offer Milestone Rewards :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="milestone_reward" id="milestone_reward"  class="" <?php if( $staff_access['AccessStaff']['milestone_reward']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="milestone_reward" id="milestone_reward"  class="" <?php if( $staff_access['AccessStaff']['milestone_reward']==0){ echo "checked"; } ?> > Off
        </div>

    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Staff to Redeem For Patient :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="staff_to_redeem" id="staff_to_redeem" class="" <?php if( $staff_access['AccessStaff']['staff_to_redeem']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="staff_to_redeem" id="staff_to_redeem" class="" <?php if( $staff_access['AccessStaff']['staff_to_redeem']==0){ echo "checked"; } ?> > Off
        </div>

    </div>


    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Document Show At Rewards Front Page :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="show_documents" id="show_documents" class="" <?php if( $staff_access['AccessStaff']['show_documents']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="show_documents" id="show_documents" class="" <?php if( $staff_access['AccessStaff']['show_documents']==0){ echo "checked"; } ?> > Off
        </div>

    </div>


    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Assign card without email :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="with_email" id="with_email" class="" <?php if( $staff_access['AccessStaff']['with_email']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="with_email" id="with_email" class="" <?php if( $staff_access['AccessStaff']['with_email']==0){ echo "checked"; } ?> > Off


        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Patient Self Registration :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="self_registration" id="self_registration" onchange="auto_readonly();" class="" <?php if( $staff_access['AccessStaff']['self_registration']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="self_registration" id="self_registration" onchange="auto_readonly();" class="" <?php if( $staff_access['AccessStaff']['self_registration']==0){ echo "checked"; } ?> > Off


        </div>

    </div>
    <?php if( $staff_access['AccessStaff']['self_registration']==1){ $dis="display:block;"; }else{ $dis="display:none;"; } ?>
    <div class="form-group Clearfix" style="<?php echo $dis; ?>" id="autoassign_display">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Auto Assign :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="auto_assign" id="auto_assign"  class="" <?php if( $staff_access['AccessStaff']['auto_assign']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="auto_assign" id="auto_assign"  class="" <?php if( $staff_access['AccessStaff']['auto_assign']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Number Of Custom Promotion :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  name="no_of_promotion" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" id="no_of_promotion" class="" value="<?php echo $staff_access['AccessStaff']['no_of_promotion']; ?>">

        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Accelerated Rewards :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="tier_setting" id="tier_setting" onchange="maketier_readonly();" class="" <?php if( $staff_access['AccessStaff']['tier_setting']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="tier_setting" id="tier_setting" onchange="maketier_readonly();" class="" <?php if( $staff_access['AccessStaff']['tier_setting']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <?php if( $staff_access['AccessStaff']['tier_setting']==1 && $staff_access['AccessStaff']['levelup']==1){ $dis="display:block;"; }else{ $dis="display:none;"; } ?>
    <div class="form-group Clearfix" style="<?php echo $dis; ?>" id="independent_display">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Independent earning from Levelup Program :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="0" name="independent_earning" id="independent_earning"  class="" <?php if( $staff_access['AccessStaff']['independent_earning']==0){ echo "checked"; } ?> > On
            <input type="radio" value="1" name="independent_earning" id="independent_earning"  class="" <?php if( $staff_access['AccessStaff']['independent_earning']==1){ echo "checked"; } ?> > Off
        </div>

    </div>

<?php  if( $staff_access['AccessStaff']['tier_setting']==1){ $red1=""; }else{ $red1="readonly"; } ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Number Of Tier :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  name="no_of_tier" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" id="no_of_tier" class="" value="<?php echo $staff_access['AccessStaff']['no_of_tier']; ?>" <?php echo $red1; ?>>

        </div>

    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Show Training Video :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="0" name="show_training_video" id="show_training_video" class="" <?php if( $staff_access['AccessStaff']['show_training_video']==0){ echo "checked"; } ?> > On
            <input type="radio" value="1" name="show_training_video" id="show_training_video" class="" <?php if( $staff_access['AccessStaff']['show_training_video']==1){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Allow Redemption From Tango/Amazon :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="0" name="amazon_redemption" id="amazon_redemption" class="" <?php if( $staff_access['AccessStaff']['amazon_redemption']==0){ echo "checked"; } ?> > On
            <input type="radio" value="1" name="amazon_redemption" id="amazon_redemption" class="" <?php if( $staff_access['AccessStaff']['amazon_redemption']==1){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Request a Review :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="rate_review" id="rate_review" onchange="request_readonly();" class="" <?php if( $staff_access['AccessStaff']['rate_review']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="rate_review" id="rate_review" onchange="request_readonly();" class="" <?php if( $staff_access['AccessStaff']['rate_review']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <?php if( $staff_access['AccessStaff']['rate_review']==1){ $dis_req="display:block;"; }else{ $dis_req="display:none;"; } ?>
    <div class="form-group Clearfix" style="<?php echo $dis_req; ?>" id="request_sms">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">SMS Alert :</label>
        <div class="col-sm-9 col-xs-12">

            <input type="radio" value="1" name="sms" id="sms" <?php if($email['sms_body']==''){ echo 'onchange="check_readonly();"'; }else{ echo ""; } ?> class="" <?php if( $staff_access['AccessStaff']['sms']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="sms" id="sms" <?php if($email['sms_body']==''){ echo 'onchange="check_readonly();"'; }else{ echo ""; } ?> class="" <?php if( $staff_access['AccessStaff']['sms']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Refer Friends & Family :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="refer" id="refer" class="" <?php if( $staff_access['AccessStaff']['refer']==1){ echo "checked"; } ?> > On
            <input type="radio" value="0" name="refer" id="refer" class="" <?php if( $staff_access['AccessStaff']['refer']==0){ echo "checked"; } ?> > Off
        </div>

    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Receive Report In :</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  name="report" id="report" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" class="" value="<?php echo $staff_access['AccessStaff']['report']; ?>" style="width:50px;"> Days

        </div>

    </div>

    <div class="col-md-offset-3 col-md-9">
        <input type="submit" id="add_card" value="Update Access" class="btn btn-sm btn-primary">

    </div>


</form>
<script>

    function make_readonly() {
        var accelerated = $('input[name=tier_setting]:checked', '#addcard').val();
        var level = $('input[name=levelup]:checked', '#addcard').val();
        var interval = $('input[name=interval]:checked', '#addcard').val();
        if (level == 0 && interval == 0) {
            $('#independent_display').hide();
        }
        if (level == 0) {
            $('#no_of_plan').val(0);

            $("#no_of_plan").attr("readonly", "readonly");
        } else {
            if (accelerated == 1) {
                $('#independent_display').show();
            } else {
                $('#independent_display').hide();
            }
            $("#no_of_plan").removeAttr("readonly");
        }
    }
    function make_interval_readonly() {
        var accelerated = $('input[name=tier_setting]:checked', '#addcard').val();
        var level = $('input[name=levelup]:checked', '#addcard').val();
        var interval = $('input[name=interval]:checked', '#addcard').val();
        if (level == 0 && interval == 0) {
            $('#independent_display').hide();
        }
        if (interval == 0) {

            $('#no_of_interval').val(0);
            $("#no_of_interval").attr("readonly", "readonly");
        } else {
            if (accelerated == 1) {
                $('#independent_display').show();
            } else {
                $('#independent_display').hide();
            }
            $("#no_of_interval").removeAttr("readonly");
        }
    }
    function maketier_readonly() {
        var level = $('input[name=tier_setting]:checked', '#addcard').val();
        if (level == 0) {
            $('#no_of_tier').val(0);
            $('#independent_display').hide();
            $("#no_of_tier").attr("readonly", "readonly");
        } else {
            var levelup = $('input[name=levelup]:checked', '#addcard').val();
            if (levelup == 1) {
                $('#independent_display').show();
            } else {
                $('#independent_display').hide();
            }
            $("#no_of_tier").removeAttr("readonly");
        }
    }
    function request_readonly() {
        var level = $('input[name=rate_review]:checked', '#addcard').val();
        if (level == 0) {
            $('#request_sms').hide();
        } else {
            $('#request_sms').show();
        }
    }
    function check_readonly() {
        var level = $('input[name=sms]:checked', '#addcard').val();
        if (level == 1) {
            var r = confirm("You don't have sms body for send sms alert.Do you want to add sms body?");
            if (r == true)
            {
                $( "#sms" ).prop( "checked", false );
                window.location.href = "/EmailManagement/edit/42";
            } else
            {
                return false;
            }
        }
    }


    function auto_readonly() {
        var level = $('input[name=self_registration]:checked', '#addcard').val();
        if (level == 0) {
            $('#autoassign_display').hide();
        } else {
            $('#autoassign_display').show();
        }
    }
    $(document).ready(function () {

        $('#addcard').validate({
            submitHandler: function (form) {
                var milestone = $('input[name=milestone_reward]:checked', '#addcard').val();
                var tier = $('input[name=tier_setting]:checked', '#addcard').val();
                var levelup = $('input[name=levelup]:checked', '#addcard').val();
                var interval = $('input[name=interval]:checked', '#addcard').val();
                if (milestone == 1 && tier == 1) {
                    $("#milestone_reward").focus();
                    alert('You can grant access for either Milestone Rewards or Accelerated Rewards');
                    return false;
                } else if (levelup == 1 && interval == 1) {
                    $("#levelup").focus();
                    alert('You can grant access for either Levelup Rewards Program or Interval Rewards Program');
                    return false;
                } else if ($('#report').val() < 1) {
                    $("#report").focus();
                    alert('Received Report days should be greater then 0.');
                    return false;
                } else {
                    form.submit();
                }
            }
        });
    });
</script>
