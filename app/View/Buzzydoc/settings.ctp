<?php

$sessionbuzzy = $this->Session->read('userdetail');
$sessionbuzzycheck = $this->Session->read('usercheck');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
?>

<section class="relevant-details setting-details">
    <div class="top-buzz-bg">
        <div class="relevant-adjust">
            <div class="top-docs-details-wrap clearfix">
                <header class="relevant-header">SETTINGS</header>

                <div class="setting-view col-md-12">
                    <div class="col-md-12 setting-cal notifaction">
                        <header class="left-module-heading">
                            <div class="modified-border-top"></div>
                            <h4 class="left-module-title-heading">Notification</h4>
                            <div class="modified-border-bottom clear"></div>
                        </header>
                        <?php if($HaveNotifications==1){ ?>
                        <div class="control-group">
                            
                            <form action="" method="POST" name="notification_form" onsubmit="return false;">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="ace" id="reward_challenges" name="reward_challenges" placeholder="reward_challenges" <?php if (isset($Notifications['reward_challenges']) && $Notifications['reward_challenges'] == 1) { echo "checked"; } ?>>
                                        <span class="lbl">INFORM ME ABOUT NEW REWARDS AND CHALLENGES</span>
                                    </label>
                                </div><!--/.checkbox-->
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="ace" id="order_status" name="order_status" placeholder="order_status" <?php if (isset($Notifications['order_status']) && $Notifications['order_status'] == 1) { echo "checked"; } ?>>
                                        <span class="lbl">INFORM ME WHEN MY ORDER STATUS CHANGES</span>
                                    </label>
                                </div><!--/.checkbox-->
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="ace" id="earn_points" name="earn_points" placeholder="earn_points" <?php if (isset($Notifications['earn_points']) && $Notifications['earn_points'] == 1) { echo "checked"; } ?>>
                                        <span class="lbl">INFORM ME WHEN I EARN POINTS</span>
                                    </label>
                                </div><!--/.checkbox-->
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="ace" id="points_expire" name="points_expire" placeholder="points_expire" <?php if (isset($Notifications['points_expire']) && $Notifications['points_expire'] == 1) { echo "checked"; } ?>>
                                        <span class="lbl">INFORM ME WHEN MY POINTS GOING TO EXPIRE</span>
                                    </label>
                                </div><!--/.checkbox-->

                                <div class="result-view-profile-btn-wrap update-notification clearfix">
                                    <span id="notification_load" class="notifi-reloader" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                                    <input type="button" value="UPDATE" class="result-view-profile-btn"  name="submit" onclick="setNotification();">
                                </div>
                            </form>
                            
                        </div><!--/.control-group-->
                        <?php }else{ ?>
                        <div class="control-group">No notification found!</div>
                        <?php } ?>
                    </div><!--/.notification-->



                    <div class="col-md-12 setting-cal refer-frnd">
                        <header class="left-module-heading">
                            <div class="modified-border-top"></div>
                            <h4 class="left-module-title-heading">Refer friends and family</h4>
                            <div class="modified-border-bottom clear"></div>
                        </header>
                        <div class="table-area">
                            <div class="table-responsive" style="overflow-y:hidden; overflow-x:hidden;">
                                <table id="example" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending">INVITED </th>
                                            <th width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending">DATE</th>
                                            
                                            <th width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending">STATUS</th>
                                            <th width="18%" class="client sorting" aria-label="Domain: activate to sort column ascending">ACTION</th>
                                            <th width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">PRACTICE</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                           <?php
                            foreach ($Refers as $ref) {
                                ?>
                                        <tr>
                                            <td width="20%" class="center"><?php echo $ref['Refer']['email'] ?></td>
                                            <td width="20%" class="center"><?php echo $ref['Refer']['refdate'] ?></td>
                                            
                                            <td width="20%" class="center"><?php echo $ref['Refer']['status']; ?></td>
                                            <td width="18%" class="center"><?php if($ref['Refer']['status']=='Pending'){ ?><span id="resend_load_<?=$ref['Refer']['id']?>" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span><a onclick="resendrefer(<?=$ref['Refer']['id']?>)" style="cursor:pointer">Resend</a><?php }else{ echo "NA"; } ?></td>
                                            <td width="20%" class="center"><?php if($ref['Clinic']['display_name']==''){ echo $ref['Clinic']['api_user']; }else{ echo $ref['Clinic']['display_name']; } ?></td>
                                        </tr>
                                  <?php }
                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12  load-more-btn">
                       <?php if(isset($ClinicList) && !empty($ClinicList)){ ?>
                            <button class="result-view-profile-btn" onclick="inviteFriend();">INVITE MORE FRIENDS</button>
                            <?php } ?>        </div>
                    </div><!--/.setting-cal-->



                    <div class="col-md-12 setting-cal doc-form">
                        <header class="left-module-heading">
                            <div class="modified-border-top"></div>
                            <h4 class="left-module-title-heading">Documents & Forms</h4>
                            <div class="modified-border-bottom clear"></div>
                        </header>
                        <ul class="doc-links">
                          <?php 
                            if(!empty($Documents)){
                            foreach ($Documents as $doc) {
                                ?>
                            <li><i></i><a href="<?php echo $doc['Document']['document'] ?>"  target="_blank"><?php echo $doc['Document']['title'] ?></a> - (<?php if($doc['Clinic']['display_name']==''){ echo $doc['Clinic']['api_user']; }else{ echo $doc['Clinic']['display_name']; } ?>)</li>
                            <?php 
                            }}else{ ?>
                            <li>There are no documents available right now. Please check back later.</li>
                            <?php } ?>
                        </ul>
                    </div><!--/.doc-form-->



    <?php

                            if(isset($sessionbuzzycheck['is_parent']) && $sessionbuzzycheck['is_parent']==1){
                             ?>
                    <div class="col-md-12 setting-cal refer-frnd switch-child">
                        <header class="left-module-heading">
                            <div class="modified-border-top"></div>
                            <h4 class="left-module-title-heading">Switch to child Account</h4>
                            <div class="modified-border-bottom clear"></div>
                        </header>
                        <div class="table-area">
                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>

                                        <th>NAME</th>
                                        <th>DATE OF BIRTH</th>
                                        <th>EMAIL</th>
                                        <th>ACTION</th>

                                    </tr>
                                </thead>
                                <tbody>
                            <?php

                            foreach ($ChildDetails as $chd) {

                                ?>
                                    <tr>
                                        <td class="center"><?php echo $chd['User']['first_name'].' '.$chd['User']['last_name']; ?></td>
                                        <td class="center"><?php echo $chd['User']['custom_date']; ?></td>
                                        <td class="center"><?php echo $chd['User']['email']; ?></td>
                                        <td class="center">
                                            <form action="buzzydoc/getmultilogin/" method="post">
                                                <input type="hidden" name="child_id" id="child_id" value="<?php echo $chd['User']['id']; ?>">

                                                <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $sessionbuzzy->User->id; ?>">
                                                <input type="hidden" name="parent_login" id="parent_login" value="1">
                                                <button class="switch-btn">SWITCH</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                            <?php } ?>


     <?php

                            if(isset($sessionbuzzycheck['is_parent']) && $sessionbuzzycheck['is_parent']==0 && isset($sessionbuzzycheck['parent_id']) && $sessionbuzzycheck['parent_id']!=''){
                             ?>
                    <div class="col-md-12 setting-cal refer-frnd switch-child">
                        <header class="left-module-heading">
                            <div class="modified-border-top"></div>
                            <h4 class="left-module-title-heading">Switch back to parent Account</h4>
                            <div class="modified-border-bottom clear"></div>
                        </header>
                        <div class="table-area">
                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>

                                        <th>NAME</th>
                                        <th>DATE OF BIRTH</th>
                                        <th>EMAIL</th>
                                        <th>ACTION</th>

                                    </tr>
                                </thead>
                                <tbody>
                             <?php

                            foreach ($ParentDetails as $chd) {

                                ?>
                                    <tr>
                                        <td class="center"><?php echo $chd['User']['first_name'].' '.$chd['User']['last_name']; ?></td>
                                        <td class="center"><?php echo $chd['User']['custom_date']; ?></td>
                                        <td class="center"><?php echo $chd['User']['email']; ?></td>
                                        <td class="center">
                                            <form action="buzzydoc/getmultilogin/" method="post">
                                                <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $chd['User']['id']; ?>">
                                                <input type="hidden" name="parent_login" id="parent_login" value="0">
                                                <button class="switch-btn">SWITCH BACK</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                            <?php } ?>
                    <!--/.refer-frnd-->
                </div><!--  filter-search-results-wrap  -->

            </div><!-- .top-docs-details-wrap  -->
        </div><!-- .top-docs-container  -->
    </div>


</section><!-- .relevant-details top-docs-bg -->

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
                                        <textarea class="form-control" name="message" id="message"><?php echo $refer_msg->reffralmessage1; ?></textarea>
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
<!-- jQuery (JavaScript plugins) -->


<script type="text/javascript">
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "oLanguage": {
                "sEmptyTable": "No referrals found!"
            },
            "columnDefs": [{"targets": 3, "orderable": false}],
            "aLengthMenu": [[5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]],
            "aaSorting": [[0, "asc"]],
            "sPaginationType": "full_numbers",
        });

    });
    function inviteFriend() {
        $('#notification-table').modal();
        //        $("#inviteFri").css("display", "block");
    }
    function setNotification(id) {
        $("#notification_load").css("display", "inline-block");
        if ($("#reward_challenges").prop('checked') == true) {
            var reward_challenges = 1;
        } else {
            var reward_challenges = 0;
        }
        if ($("#order_status").prop('checked') == true) {
            var order_status = 1;
        } else {
            var order_status = 0;
        }
        if ($("#earn_points").prop('checked') == true) {
            var earn_points = 1;
        } else {
            var earn_points = 0;
        }
        if ($("#points_expire").prop('checked') == true) {
            var points_expire = 1;
        } else {
            var points_expire = 0;
        }
        
        $.ajax({
            type: "POST",
            url: "<?php echo Buzzy_Name.'buzzydoc/setNotification' ?>",
            data: "reward_challenges=" + reward_challenges + "&order_status=" + order_status + "&earn_points=" + earn_points + "&points_expire=" + points_expire,
            success: function(msg) {
                if (msg == 1) {
                    alert('Notification updated successfully.');
                    $("#notification_load").css("display", "none");
                } else {
                    alert('Try agin leter.');
                    $("#notification_load").css("display", "none");
                }
            }
        });
        
    }
    function resendrefer(id) {
        $("#resend_load_" + id).css("display", "inline-block");
        $.ajax({
            type: "POST",
            url: "<?php echo Buzzy_Name.'buzzydoc/resendrefer' ?>",
            data: "ref_id=" + id,
            success: function(msg) {
                if (msg == 1) {
                    alert('Successfully resent email to referral');
                    $("#resend_load_" + id).css("display", "none");
                } else {
                    alert('Email not send to referral');
                    $("#resend_load_" + id).css("display", "inline-block");
                }
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
        var message = $("#message").val();


        var clinic_select = $('#clinic_list').val();
        var doctor_id = 0;
        if ($.trim(clinic_select) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please select clinic");
        }
        else if ($.trim(first_name) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please enter first name");
        } else if ($.trim(last_name) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please enter last name");
        } else if ($.trim(email) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please enter email");
        } else if (!regex.test(email)) {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
            $("#status_error_reco").html("Please enter valid email");
        } else if ($.trim(message) == '') {
//                $('input[type="button"]').removeAttr('disabled');
$("#refer_load").css("display", "none");
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
    function setmsg(id) {
        var indty = $('#indty').val();
        $.ajax({
            type: "POST",
            url: "/buzzydoc/getmsg/",
            data: "&id=" + id + "&indty=" + indty,
            success: function(msg) {
                $("#message").focus();
                $('#message').val(msg);

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
                $('#message').val(obj.message);
                $('#indty').val(obj.indty);
                $('#ref_clinic_id').val(obj.ref_clinic_id);
                $('#leadsplan').html(obj.leadsplan);
                $('#defaultmsg').html(obj.defaultmsg);

            }
        });
    }
</script>