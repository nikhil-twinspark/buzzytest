<style type="text/css">
    .mobilebanner {padding-bottom: 20px; }
	.productBoxSM { margin-left:15px;}
	/*.productBoxSM img {width: 90%; height: 117px;}*/
    @media (max-width: 767px) and (min-width: 100px){
        .leftcont { display:none;}
		.productBoxSM { width:94%; margin-left:10px;}	
		.col-md-12.clearfix.pad-0{ padding-left:0; padding-right:0;}
    }
</style>
<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
    <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>

    <div id="navimob"><a href="#" id="pull">
            <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png', array('width' => '31', 'height' => '26')); ?>

        </a></div>

</div>
<div class=" clearfix">

    <div class="col-lg-9 col-xs-12 rightcont">

        <div class="settingArea clearfix upper_case bg_white">
			<div>
            <ul class="col-md-12 clearfix pad-0">

                <li class="col-md-7 col-xs-12" id="liprofile">
                    <h2>YOUR profile</h2>
                    <form action="<?= Staff_Name ?>rewards/editprofile/" method="POST" name="new_account_form" class="side_padding">
                        <?php
										if ($sessionpatient['customer_info']['ClinicUser']['card_number'] != '') {
                                            ?>
                                            <p> <strong>CARD NUMBER:</strong> <?= $sessionpatient['customer_info']['ClinicUser']['card_number'] ?></p>
                                            <?php
                                        }
                                        if ($sessionpatient['customer_info']['user']['first_name'] != '') {
                                            ?>
                                            <p> <strong>FIRST NAME:</strong> <?= $sessionpatient['customer_info']['user']['first_name'] ?></p>
                                        <?php }
                                        if ( $sessionpatient['customer_info']['user']['last_name'] != '') {
                                            ?>
                                            <p> <strong>LAST NAME:</strong> <?= $sessionpatient['customer_info']['user']['last_name'] ?></p>
                                        <?php }
                                        if ($sessionpatient['customer_info']['user']['email'] != '') {
                                            ?>
                                            <p> <strong>EMAIL:</strong> <span style="text-transform:none;"><?= $sessionpatient['customer_info']['user']['email'] ?></span></p>
                                            <?php
                                        } if ($sessionpatient['customer_info']['user']['custom_date'] != '0000-00-00' && $sessionpatient['customer_info']['user']['custom_date'] != '') {
                                            ?>
                                            <p> <strong>Date Of Birth:</strong> <?= $sessionpatient['customer_info']['user']['custom_date'] ?></p>
                                            <?php
                                        }
                                     
                            foreach ($sessionpatient['customer_info']['ProfileField'] as $field_sorted) {
                               
                                    // Normal fields:
                                  
                                        if ($field_sorted['profile_field'] == 'phone' && $field_sorted['ProfileFieldUser']['value']!='') {
                                            ?>
                                            <p> <strong>PHONE:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?> </p>
                                            <?php
                                        }  if ($field_sorted['profile_field'] == 'street1' && $field_sorted['ProfileFieldUser']['value']!='' ) {
                                            ?>		

                                            <p><strong>ADDRESS:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?></p>
										<?php
										} elseif ($field_sorted['profile_field'] == 'street2' && $field_sorted['ProfileFieldUser']['value']!='') {
										?>
                                            <p><strong>ADDRESS 1:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?></p>
                                            <?php
                                        } elseif ($field_sorted['profile_field'] == 'city' && $field_sorted['ProfileFieldUser']['value']!='') {
                                            ?>
                                            <p><strong>CITY:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?></p>
                                            <?php
                                        } elseif ($field_sorted['profile_field'] == 'state' && $field_sorted['ProfileFieldUser']['value']!='') {
                                            ?>
                                            <p><strong>STATE:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?></p>
                                            <?php
                                        } elseif ($field_sorted['profile_field'] == 'postal_code' && $field_sorted['ProfileFieldUser']['value']!='') {
                                            ?>
                                            <p><strong>ZIP:</strong> <?= $field_sorted['ProfileFieldUser']['value'] ?></p>
                                            <?php
                                        } 
                                   
                                
                            }
                        
                        ?>
                        <input class="btn btn-primary buttondflt btn_new"	type="submit" value="Edit Profile" name="myinfo_submit">
                    </form>
                </li>



                <li class="col-md-7 col-xs-12" id="lichangepass">
                    <h2>CHANGE PASSWORD</h2><?php echo $this->Session->flash(); ?>
                    <form action="<?= Staff_Name ?>rewards/profile/" method="POST" name="pass_form" onsubmit="return match()">
                        <input type="hidden" name="user_id" value="<?= $sessionpatient['customer_info']['user']['id'] ?>" >
                        <input type="hidden" name="action" value="passet">
 
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">CURRENT PASSWORD:</label><span id='error_msg_password' class='message' ></span>
                            <input class="form-control" type="password" autocomplete="off" name="password"  id="password" value="" onkeypress="changeErrorMessage(this.id)">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">NEW PASSWORD:</label><span id='error_msg_new_password' class='message'></span>
                            <input class="form-control" type="password" autocomplete="off" name="new_password"  id="new_password" value="" onkeypress="changeErrorMessage(this.id)">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">CONFIRM NEW PASSWORD:</label><span id='error_msg_new_password2' class='message' ></span>
                            <input class="form-control" type="password" autocomplete="off" name="new_password2" id="new_password2" value="" onkeypress="changeErrorMessage(this.id)">
                        </div>

                        <input type="submit" value="SUBMIT" class="btn btn-primary buttondflt btn_new" name="login_submit">
                    </form>
                        <script>
        function match() {
        var len = $('#new_password').val().length;
        var curpass = $("#password").val();
        var cur_pass = '<?= $sessionpatient['var']['patient_password'] ?>';
		if($("#password").val()==''){
                $("#password").css('background-color','#FF9966');
                $("#error_msg_password").html("Enter a current Password");
                $("#password").focus();
                return false;
            }
            else if (cur_pass != curpass) {
        
                $("#password").css('background-color','#FF9966');
                $("#error_msg_password").html("Current Password Invalid");
                $("#password").focus();
                return false;
            }
           else if($("#new_password").val()==''){
                $("#new_password").css('background-color','#FF9966');
                $("#error_msg_new_password").html("Enter a new Password");
                $("#new_password").focus();
                return false;
            }
            else if (len < 6) {

                $("#new_password").css('background-color','#FF9966');
                $("#error_msg_new_password").html("Password atleast 6 characters long.");
                $("#new_password").focus();
                return false;
            }
            else if($("#new_password2").val()==''){
                $("#new_password2").css('background-color','#FF9966');
                $("#error_msg_new_password2").html("Enter a confirm Password");
                $("#new_password2").focus();
                return false;
            }
            
            else if ($("#new_password").val() != $("#new_password2").val()) {
                $("#new_password2").css('background-color','#FF9966');
                $("#error_msg_new_password2").html("Passwords do not match.");
                $("#new_password2").focus();
                return false;
               
            } 
           
        }
        

    

        
         function changeErrorMessage(ptr){
                $("#"+ptr).css('background-color','');
				$("#error_msg_"+ptr).html("");

           }
    </script>
                </li>
                <li class="col-md-7 col-xs-12" id="linotification">
                    <h2>NOTIFICATIONS</h2>
                    <form action="<?= Staff_Name ?>rewards/profile/" method="POST" name="notification_form" >
                        <input type="hidden" name="action" value="notification">
                        <input type="hidden" name="id" value="<?php if (isset($Notifications['Notification']['id'])) {
                            echo $Notifications['Notification']['id'];
                        } else {
                            echo '';
                        } ?>">
                        <input type="hidden" name="chkemail" id="chkemail" value="<?= $sessionpatient['customer_info']['user']['email'] ?>">
                        <div class="form-group notification clearfix">
                            <input type="checkbox" class="form-control" id="reward_challenges" name="reward_challenges" placeholder="Email" <?php if (isset($Notifications['Notification']['reward_challenges']) && $Notifications['Notification']['reward_challenges'] == 1) {
                            echo "checked";
                        } ?>>
                            <label class="col-sm-2 control-label">INFORM ME ABOUT NEW REWARDS AND CHALLENGES
                            </label>
                        </div>
                        <div class="form-group notification clearfix">
                            <input type="checkbox" class="form-control" id="order_status" name="order_status" placeholder="Email" <?php if (isset($Notifications['Notification']['order_status']) && $Notifications['Notification']['order_status'] == 1) {
                            echo "checked";
                        } ?>>
                            <label class="col-sm-2 control-label">INFORM ME WHEN MY ORDER STATUS CHANGES
                            </label>
                        </div>
                        <div class="form-group notification clearfix">
                            <input type="checkbox" class="form-control" id="earn_points" name="earn_points" placeholder="Email" <?php if (isset($Notifications['Notification']['earn_points']) && $Notifications['Notification']['earn_points'] == 1) {
                            echo "checked";
                        } ?>>
                            <label class="col-sm-2 control-label">INFORM ME WHEN I EARN POINTS
                            </label>
                        </div>
<div class="form-group notification clearfix">
                            <input type="checkbox" class="form-control" id="points_expire" name="points_expire"  <?php if (isset($Notifications['Notification']['points_expire']) && $Notifications['Notification']['points_expire'] == 1) {
                            echo "checked";
                        } ?>>
                            <label class="col-sm-2 control-label">INFORM ME WHEN MY POINTS GOING TO EXPIRE
                            </label>
                        </div>
                        <input type="button" value="UPDATE" class="btn btn-primary buttondflt btn_new" name="login_submit" onclick="checkemail();">
                    </form>
                </li>
                <li class="col-md-7 col-xs-12" id="liorderstatus">
                    <div class="orderstatus  clearfix">
                        <h2>YOUR ORDER STATUS</h2>
                        <?php

                        function monthDropdown($name = "month", $selected = null) {
                            $dd = '<select name="' . $name . '" id="' . $name . '" onchange="this.form.submit()">';

                            /*                             * * the current month ** */
                            $selected = is_null($selected) ? date('n', time()) : $selected;

                            for ($i = 1; $i <= 12; $i++) {
                                $dd .= '<option value="' . $i . '"';
                                if ($i == $selected) {
                                    $dd .= ' selected';
                                }
                                /*                                 * * get the month ** */
                                $mon = date("F", mktime(0, 0, 0, $i + 1, 0, 0, 0));
                                $dd .= '>' . $mon . '</option>';
                            }
                            $dd .= '</select>';
                            return $dd;
                        }
                        ?>
                        <form class="form_size Feeddropdown" action="<?= Staff_Name ?>rewards/profile/#liorderstatus" method="POST" id="dateform">
                            <input type="hidden" name="action" value="month_change">
                            <span class="dropIcon height_new"></span>
                        <?php echo monthDropdown('my_dropdown', $selectedmonth); ?>
                        </form>
                    </div>
                    <div class="clearfix">
<?php
if (count($RedeemRewards) > 0) {
    foreach ($RedeemRewards as $redeem) {
        ?>
                                <div class="col-md-6" style="min-height:116px;">
                                    <p> <strong>ORDER NUMBER:</strong> <?= $redeem['Transaction']['id'] ?></p>
                                    <p> <strong>DATE REDEEMED:</strong> <?php $dt=explode(' ',$redeem['Transaction']['date']); echo $dt[0]; ?></p>
                                    <p><strong>DESCRIPTION:</strong> <?= $redeem['Transaction']['authorization'] ?></p>
                                    <p> <strong>STATUS:</strong> <?php if ($redeem['Transaction']['status'] == 'New' || $redeem['Transaction']['status'] == 'Redeemed') {
            echo "Redeemed";
        } elseif ($redeem['Transaction']['status'] == 'Ordered/Shipped') {
            echo "Ordered/Shipped";
        }
elseif ($redeem['Transaction']['status'] == 'Received') {
            echo "Received";
        }
elseif ($redeem['Transaction']['status'] == 'Completed') {
            echo "Completed";
        } else {
            echo "In Office";
        } ?></p>
                                    <div><br></div>
                                </div>

    <?php }
} else { ?>
                            <div class="col-md-6">
                                <p>No Reward Redeemed!!</p>

                            </div>
                        <?php } ?>
                    </div>

                </li>
                <li class="col-md-7 col-xs-12" id="lirefer">
                    <h2>YOUR REFERRALS</h2>
                    <table class="table clearfix">
                        <tr>
                            <td><strong>INVITED</strong></td>
                            <td><strong>DATE</strong></td>
                            <td><strong>STATUS</strong></td>
                            <td><strong>ACTION</strong></td>
                        </tr>
                        <?php if (count($Refers) > 0) {
                            foreach ($Refers as $ref) {
                                ?>
                                <tr>
                                    <td><?php echo $ref['Refer']['email'] ?></td>
                                    <td><?php echo $ref['Refer']['refdate'] ?></td>
                                    <td><?php echo $ref['Refer']['status']; ?></td>
                                    <td><?php if($ref['Refer']['status']=='Pending'){ ?><span onclick="resendrefer(<?=$ref['Refer']['id']?>)" style="cursor:pointer">Resend</span><?php }else{ echo "NA"; } ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="3">No Referral Found!!</td>

                            </tr>
                        <?php } ?>
                    </table>
 <?php if($sessionpatient['staffaccess']['AccessStaff']['refer']==1){ ?>
                    <a href="<?= Staff_Name ?>rewards/refer/"><button class="btn btn-primary buttondflt btn_new">INVITE MORE FRIENDS</button></a>
<?php } ?>               
 </li>




                <li class="col-md-6 col-xs-12" id="liwish">
                    <h2>YOUR WISHLIST</h2>
                    <div class="row" id="ajax_wishlist_id_div">

                                    <?php
                                    if (isset($sessionpatient['customer_info']['user']['points'])) {
                    if($sessionpatient['is_buzzydoc']==1){
                        if($sessionpatient['customer_info']['ClinicUser']['local_points']>$sessionpatient['customer_info']['user']['points']){
                    $current_balance = $sessionpatient['customer_info']['ClinicUser']['local_points'];
                        }else{
                        $current_balance = $sessionpatient['customer_info']['user']['points'];    
                        }
                    }else{
                     $current_balance = $sessionpatient['customer_info']['ClinicUser']['local_points'];   
                    }
                } else {
                    $current_balance = '0';
                }
                                    if (!empty($WishLists)) {
                                        foreach ($WishLists as $wishlist) {
                                            ?>
                                <div style="position:relative;" class="col-lg-4 col-md-4 col-sm-6 col-xs-6 profile clearfix">
                                    <div class="remove" onClick="removeWishList(<?php echo $wishlist['Reward']['id'] ?>)"><?php echo $this->html->image(CDN.'img/reward_imges/remove_btn.png', array('class' => 'hand-icon')); ?></div>
                                                <?php
                                                $need_more = $wishlist['Reward']['points'] - $current_balance;
                                                if (intval($current_balance) >= intval($wishlist['Reward']['points'])) {
                                                    ?>
                                                    <?php if ($sessionpatient['is_mobile'] == 0) { ?>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12  productBoxSM l-margin box_wid" >
                                                    <?php } else { ?>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12  productBoxSM l-margin" >
                                                    <?php } ?>
                                                <?php } else { ?>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12  productBoxSM l-margin">
                                                <?php } ?>


                                                <?php
                                                $uploadFolder = "rewards/" . $sessionpatient['api_user'];
                                                $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                                               
                                                    ?>
                                                    <p><?php
                            
                            if(strlen($wishlist['Reward']['description'])>40){
								echo substr($wishlist['Reward']['description'],0,40).'...';
							}else{
								echo $wishlist['Reward']['description'];
							}
                            ?></p>
             <?php
                                                        echo '<img src="' . $wishlist['Reward']['imagepath'] . '" alt="" width="175" height="117">';
                                                        ?>
                                                    <h3><?= $wishlist['Reward']['points'] ?> points<br>
          <?php if ($need_more > 0) { ?>
                                                                <span>You need <?= $need_more ?> more points.</span>
                    <?php }else{ ?>
                    <?php if ($sessionpatient['is_mobile'] == 0) { ?>
                <span>Bravo! <a class="hand-icon" onclick="lightbox(<?= $wishlist['Reward']['id'] ?>);">Click to redeem now</a></span>
                                            
                                                    <?php } else { ?>
                                                    <span>Bravo! <a class="hand-icon" onClick="document.reward_form_<?= $wishlist['Reward']['id'] ?>.submit();">Click to redeem now</a></span>
                                                
                                                    <?php } ?>  
                    <?php } ?>
                                                        <span class="headTopCorner"></span>
                                                        <span class="headrightcorner"></span>
                                                    </h3>

                                                </div>
                                            </div>
                                            <form action="<?= Staff_Name ?>rewards/rewarddetail/<?= $wishlist['Reward']['id'] ?>" method="POST" name="reward_form_<?= $wishlist['Reward']['id'] ?>">
                                                <input type="hidden" name="action" value="confirm_redeem">
                                                <input type="hidden" name="which_reward_id" value="<?= $wishlist['Reward']['id'] ?>">
                                                <input type="hidden" name="which_reward_description" value="<?= urlencode($wishlist['Reward']['description']) ?>">
                                                <input type="hidden" name="which_reward_level" value="<?= $wishlist['Reward']['points'] ?>">
                                                
                                            </form>
                                            <form action="<?= Staff_Name ?>rewards/profile/#liwish" method="POST" name="remove_form_<?= $wishlist['Reward']['id'] ?>">
                                                <input type="hidden" name="action" value="remove_wishlist">
                                                <input type="hidden" name="which_reward_id" value="<?= $wishlist['Reward']['id'] ?>">
                                            </form>
    

    <?php }
} else { ?>
                                    <div class="col-lg-12 settinproducBox l-margin no-item">
                                        <p>No items in wishlist</p>
                                    </div>
<?php } ?>


                            </div>
                            </li>
                            <?php 
                            
                            if(isset($sessionpatient['is_parent'])){
                             ?>
                            <li class="col-md-7 col-xs-12" id="switch">
                    <h2>SWITCH TO CHILD ACCOUNT</h2>
                    <table class="table clearfix child_ac">
                        <tr>
                            <td><strong>Card Number</strong></td>	
                            <td><strong>Name</strong></td>
                            <td><strong>Date Of Birth</strong></td>
                            <td><strong>Action</strong></td>
                        </tr>
                        <?php 
                            foreach ($sessionpatient['child_detail'] as $chd) {
            
                                ?>
                                <tr>
                                    <td><?php echo $chd['ClinicUser']['card_number']; ?></td>
                                    <td><?php echo $chd['user']['first_name'].' '.$chd['user']['last_name']; ?></td>
                                    <td><?php echo $chd['user']['custom_date']; ?></td>
                                    <td>
                                    <form action="<?php echo Staff_Name; ?>rewards/getmultilogin/" method="post">
                                    <input type="hidden" name="child_id" id="child_id" value="<?php echo $chd['ClinicUser']['user_id']; ?>">
                                    <input type="hidden" name="api_user" id="api_user" value="<?php echo $sessionpatient['api_user']; ?>">
                                    <input type="hidden" name="parent_login" id="parent_login" value="1">
                                    <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $sessionpatient['customer_info']['user']['id']; ?>">
                                    <button class="btn btn-primary buttondflt btn_new">SWITCH</button>
                                    </form>
                                    </td>
                                </tr>
                            <?php }
                       ?>
                    </table>
                    
                </li>
                <?php } ?>
                            </ul>
			</div>
                        </div>
                    </div>
<?php echo $this->element('left_sidebar'); ?>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-sm">
                            <div class="modal-content popup ">
                                <div class="row rowcont">
                                    <div class="modal-header col-md-12">
                                        <a class="close closebtn" onclick="close_form();">&times;</a>
                                    </div>
                                </div>
                                <form action="" method="POST" name="reward" id="RewardForm">
                                    <input type="hidden" name="action" value="confirm_redeem">
                                    <input type="hidden" name="reward_id" id="reward_id" value="">
                                    <div class="modal-body clearfix">
                                        <div class=" row  detail">
                                            <div class="col-xs-12 clearfix">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div id="rewardImg"></div>
													<span class="complete">


                                                                     <?php
                                        if ($sessionpatient['profile_comp'] < 100) {
                                            echo 'Click the link below to complete your profile, then you can redeem!';
                                            ?>
                                            </span>
                                            <a href="<?= Staff_Name ?>rewards/editprofile/" class="btn btn-primary buttondflt pull-righ profile_comp">COMPLETE YOUR PROFILE</a>
    <?php
} else {
    ?>
                                            <input type="submit" class="btn btn-primary buttondflt redeem" value="REDEEM NOW" >
<?php } ?>
                                                    <div id="wishlist_div">
                                                    </div>

                                                    <div class="socialIcon">
                                                        <p class="pull-left">SHARE</p>


                                                        <a href="" id='twlink' target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png', array('width' => '23', 'height' => '22', 'alt' => 'twitter', 'title' => 'twitter')); ?></a>
                                                        <a href="" id="fblink" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png', array('width' => '23', 'height' => '22', 'alt' => 'facebook', 'title' => 'facebook')); ?></a>
                                                        <a href="" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                                                return false;" id="gplink"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png', array('width' => '23', 'height' => '22', 'alt' => 'googleplus', 'title' => 'googleplus')); ?></a>
                                                    </div>
                                                </div>

                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                    <h2 class="challengeDetail">


                                                        <span id="reward_name"></span>
                                                    </h2>
                                                    <p class="description">
                                                        <strong>Details/Instructions:</strong>
                                                    <div id="desc"></div>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>   <!--popup-->     
                    <div class="" id="Mymodel1"></div>
<script>
    function close_form() {

        $('#myModal').addClass("modal fade popupBox");
        $('#myModal').attr('aria-hidden', true);
        $('#myModal').css('display', 'none');
        $('#Mymodel1').removeClass('modal-backdrop fade in');
    }


    function checkemail() {
        var emailchk = $("#chkemail").val();
        if (emailchk == '') {
            alert('Before Notification Setting Insert Email ID in your profile.');
            return false;
        } else {
            document.notification_form.submit();
        }
    }
    function lightbox(reward_id) {
	
        $('#myModal').addClass("modal fade popupBox in");
        $('#myModal').attr('aria-hidden', false);
        $('#myModal').css('display', 'block');
        $('#Mymodel1').addClass('modal-backdrop fade in');
        $.ajax({
            type: "POST",
            data: "reward_id=" + reward_id,
            dataType: "json",
            url: "<?= Staff_Name ?>rewards/getreward/",
            success: function(result) {
                $('#reward_name').text(result.rewards.Reward['points'] + ' Points');
                $('#desc').text(result.rewards.Reward['description']);
                $('#rewardImg').html(result.rewards.Reward['imagepath']);
                $('#reward_id').val(result.rewards.Reward['id']);
                $("a#fblink").attr("href", "http://www.facebook.com/sharer.php?u=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&t=Share on Facebook");
                $("a#twlink").attr("href", "https://twitter.com/intent/tweet?url=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&amp;text=Share On twitter&amp;via=buzzyDoc");
                $("a#gplink").attr("href", "https://plus.google.com/share?url={<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "}");
                $("#RewardForm").attr("action", "<?= Staff_Name ?>rewards/redeemreward/" + result.rewards.Reward['id'])
                if (result.WishLists == 1) {
                    $('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Added To WishList">');
                }
                else {
                    $('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Add To WishList" onclick="wish();">');
                }
            }});


    }
    function removeWishList(wishlist_id){
        var r=confirm("Are you sure to remove?");
        if(r){
			
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name.'rewards/removewishList' ?>",
                data: "wishlist_id=" + wishlist_id,
                success: function(msg) {
                   // alert(msg);
                    document.getElementById('ajax_wishlist_id_div').innerHTML=msg;
					var height_right = $(".rightcont").height();
					var height_left = $(".leftcont").height();
			     	$(".leftcont ").css('height',height_right + "px");
				    }
            });
            
        }
    }
    function resendrefer(id){
			$.ajax({
                type: "POST",
                url: "<?php echo Staff_Name.'rewards/resendrefer' ?>",
                data: "ref_id=" + id,
                success: function(msg) {
					if(msg==1){
                     alert('Successfully resent email to referral');
                    }else{
                    alert('Email not send to referral');
                    }
				    }
            });
    }
</script>

