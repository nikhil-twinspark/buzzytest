<style type="text/css">
    #Style {
        position:absolute;

        /*border:solid 1px #CCC;*/
        padding: 5px;
        right: -143px;}
    .buttondflt {background-position: 78% 11px;}
    .form-group { margin-bottom:6px;}
</style>
<?php $sessionpatient = $this->Session->read('patient'); 
$clinicBaseChangeId = 5; // Facial plastics
?>
<section class="clearfix loginArea">
    <div class="col-md-6 col-sm-6 col-xs-12 userSign clearfix">
        <p>Existing/returning user Sign in</p>
          <?php echo $this->Session->flash(); ?>
       <?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
        <div class="form-group">
            <label>Card Number/Email</label>
             <?php echo $this->Form->input("patient_name",array('label'=>false,'div'=>false,'class'=>'form-control','required'));?>
        </div>
        <div class="form-group">
            <label>Password</label>
             <?php echo $this->Form->input("patient_password",array('type'=>'password','label'=>false,'div'=>false,'class'=>'form-control','required')); ?>
            <a href="<?=Staff_Name?>rewards/forgotpassword" >Forgot Password?</a>

        </div>

        <input class="btn btn-primary buttondflt col-md-4 col-sm-4 col-xs-4"  type="submit" value="Sign In" >
        <!-- onclick="return checkuser();" -->
        <span class="col-md-1 col-sm-1 col-xs-1 max_wid">OR</span>
        <div class="col-xs-12 fbloginspc">
            <a href="<?=Staff_Name?>rewards/facebooklogin" class="socialbtn">

            <?php echo $this->html->image(CDN.'img/reward_imges/facebook-sign-up.png',array('width'=>'171','height'=>'25','alt'=>'facebook','title'=>'facebook'));?>

            </a>
        </div>
        </form>

    </div>

    <div class="col-md-6 col-sm-6 col-xs-12 userSign tool_tip">
        <?php if($sessionpatient['Themes']['industry_type']==$clinicBaseChangeId) { ?>
        <p>New User Self-Registration</p>
	<?php } else { ?>
	<p>New user sign up</p>
	<?php } ?>
          <?php echo $this->Session->flash(); ?>
<?php
	if($sessionpatient['is_mobile']==0){
 ?>
        <form id="signupLoginForm" method="post" class="loginBox" onsubmit="lightbox();
                return false;">
            <?php if($sessionpatient['staffaccess']['AccessStaff']['self_registration']==1 && $sessionpatient['staffaccess']['AccessStaff']['auto_assign']==1){ ?>
            <input class="btn btn-primary buttondflt"  type="button" value="Sign Up" onclick="getNextCard(<?php echo $sessionpatient['clinic_id'];?>);">
            <?php }else{ ?>
            <div class="form-group">
                <label>Sign up using the doctor provided number 
                    <span class="helpicon" style="position:relative;">
                        <a href="javascript:void(0)" class="showhim" id="clickme">?</a>
                        <div id="Style" style="display:none">
             <?php if(isset($sessionpatient['Themes']['patient_question_mark'])){ ?>
                            <img src="<?=S3Path.$sessionpatient['Themes']['patient_question_mark']?>" width="182" height="148"/>
             <?php }else{ ?>
             <?php echo $this->html->image(CDN.'img/reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
             <?php } ?>
                        </div> 
                    </span>
                </label>
             <?php echo $this->Form->input("card_number",array('type'=>'text','label'=>false,'div'=>false,'class'=>'form-control','required','value'=>'')); ?>
            </div>
            <input class="btn btn-primary buttondflt"  type="button" value="Submit" onclick="lightbox();">
            <?php } ?>
        </form>
       <?php if($sessionpatient['Themes']['industry_type']!=$clinicBaseChangeId) { ?>
        <label class="note">If you're logging in for the first time since April 1st, 2014, or for your first time since our upgrade, even if you previously had a password setup you will be considered a New User and will be required to setup a new one. Please use your card number and fill out the required fields on our registration screen to enter. You can create your own password or keep your existing one as long as it was a minimum of 6 digits.</label>
        
       <?php } ?>
         <?php }else{ ?>
        
        <form id="signupLoginForm1" name="signupLoginForm1" action="<?=Staff_Name?>rewards/signup/" method="post" class="loginBox">
            <?php if($sessionpatient['staffaccess']['AccessStaff']['self_registration']==1 && $sessionpatient['staffaccess']['AccessStaff']['auto_assign']==1){ ?>
            <input class="btn btn-primary buttondflt"  type="button" value="Sign Up" onclick="getNextCard(<?php echo $sessionpatient['clinic_id'];?>);">
            <?php }else{ ?>
            <div class="form-group">
                
                <label>Sign up using the doctor provided number 
                    <span class="helpicon" style="position:relative;">
                        <a href="javascript:void(0)" class="showhim"  id="clickme">?</a>
                        <div id="Style" style="display:none">

             <?php if(isset($sessionpatient['Themes']['patient_question_mark'])){ ?>
                            <img src="<?=S3Path.$sessionpatient['Themes']['patient_question_mark']?>" width="182" height="148"/>
             <?php }else{ ?>
             <?php echo $this->html->image(CDN.'img/reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
             <?php } ?>

                        </div> 

                    </span>
                </label>
             <?php echo $this->Form->input("card_number",array('type'=>'text','label'=>false,'div'=>false,'placeholder'=>'Card Number','class'=>'form-control','required','value'=>'')); ?>
            
            </div>
            <input class="btn btn-primary buttondflt"  type="button" value="Submit" onclick="lightbox1();">
            <?php } ?>
        </form>
        <label class="note">If you're logging in for the first time since April 1st, 2014, or for your first time since our upgrade, even if you previously had a password setup you will be considered a New User and will be required to setup a new one. Please use your card number and fill out the required fields on our registration screen to enter. You can create your own password or keep your existing one as long as it was a minimum of 6 digits.</label> 
          <?php } ?>
			<?php if(!empty($Documents)){ ?>
          <label class="note1">
                            <p class="doc_heading">Documents &amp; Forms</p>
                   
       <ul class="doc_listing">
                           <?php foreach($Documents as $doc){ ?> 
                       <li>
                           <a target="_blank" href="<?php echo $doc['Document']['document']; ?>">
                                   <?php echo $doc['Document']['title']; ?>                              </a>
                           
                       </li>
                <?php } ?>
                       
                                    </ul>
       </label>
       <?php } ?>
        <!-- data-toggle="modal" data-target="#myModal" -->
    </div>

</section><!--loginform-->

<!-- Modal for sign up popup-->

<div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">


    <div class="modal-dialog-sm" style="margin:auto; margin-top: 50px;">
        <div class="modal-content popup" style="border-radius:20px; border: 2px solid #bdbdbd;">
            <div class="row rowcont">
                <div class="modal-header col-md-7 col-sm-7 col-xs-12 pad-l-34" style="position:static;">
                    <a class="close closebtn" onclick="close_form();">&times;</a>
                    <h4 class="modal-title" id="myModalLabel">new user Sign up</h4>
                    <p>Complete the form below to start earning 
                        easy points and redeeming great rewards.</p>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 fb-signup">
                    <span style="font-family: arial; font-size: 13px; display: block; margin-bottom: 5px;"> OR sign up using Facebook</span>
                    <form action="<?=Staff_Name?>rewards/facebooklogin/" method="POST" name="new_account_form_fb" class="side_padding">
                        <input class="form-control" type="hidden" id="card1"	name="card1" value="" maxlength="255" readonly>
                        <a href="#" class=" socialbtn  socialPopup" onclick="document.new_account_form_fb.submit();"> <?php echo $this->html->image(CDN.'img/reward_imges/facebook-sign.png',array('width'=>'171','height'=>'25','alt'=>'facebook','title'=>'facebook'));?>
                        </a>
                    </form>
                </div>
            </div>
            <form action="<?=Staff_Name?>rewards/signup/" method="POST" name="new_account_form" id="new_account_form" class="popupform">

                <input type="hidden" name="action" value="record_new_account" id='action'>
                <div class="modal-body clearfix" style="margin-top: 85px;">
                    <div class="col-md-6 col-sm-6 error_tip">

          <?php 

		$totalfield=7+count($sessionpatient['ProFieldGlobal']);
		$breakonfield=round($totalfield/2);
		//changes in signup  done on 15 april 2014
				
			
					 ?>
                        <div class="form-group">
                            <label >Card Number</label><span class="helpicon" style="position:relative;">
                                <a href="#" class="showhim" id="clickme1" style="padding: 5px;">?</a>
                                <div id="Style1" style="position:absolute; padding:5px; display:none; right: -150px;">
             <?php if(isset($sessionpatient['Themes']['patient_question_mark'])){ ?>
                                    <img src="<?=S3Path.$sessionpatient['Themes']['patient_question_mark']?>" width="182" height="148"/>
             <?php }else{ ?>
             <?php echo $this->html->image(CDN.'img/reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
             <?php } ?>
                                </div> 
                            </span>
                            <input class="form-control" type="text" id="card" readonly="readonly" name="card" value="" maxlength="20" onblur="checkCardNumber();">
                        </div>
              <?php 
					
					 ?>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label ><span style="color:red;">*</span>Date Of Birth</label><span id="doberr" style="color:red;"></span>
                        </div>
                        <div class="form-group clearfix margin-bot">
                            <div class="selctboxmonth" >

                                <span class="dropIcon dropIcon_new"></span>
                                <select class="form-control" name="date_year" id="date_year" onchange="return checkage();">
                                    <option value="">Year</option>
						<?php 
						$curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) { ?>

                                    <option value="<?=$y?>" <?php if($y==1990){ echo "selected";} ?>><?=$y?></option>
						<?php }	?>
                                </select>
                            </div>
                            <div class="selctboxmonth" >

                                <span class="dropIcon dropIcon_new"></span>
                                <select class="form-control" name="date_month" id="date_month" onchange="return checkage();">
                                    <option value="">Month</option>
                  <?php 
                  $months = array(
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sept',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec');
						foreach($months as $mon=>$val){
						?>
                                    <option value="<?=$mon?>"><?php echo $val;?></option>
						<?php } ?>
                                </select>
                            </div>
                            <div class="selctboxmonth" >

                                <span class="dropIcon dropIcon_new"></span>
                                <select class="form-control" name="date_day" id="date_day"  onchange="return checkage();">
                                    <option value="">Day</option>
						<?php for ($d = 1; $d <= 31; $d += 1) { ?>
                                    <option value="<?=$d?>"><?=$d?></option>
						<?php	} ?>
                                </select>
                            </div>
                        </div>
                        <div id="emailprocess">
                            <label ><span style="color:red;">*</span>Email</label>
                            <input 	class="form-control" type="email" name="email" id="email" value="" maxlength="50" onblur="checkuserexist();">
                        </div>
                        <div id="msgboxcheckemail" class="form-group clearfix" style="display:none;">
                            <span>This email id exists with us. Click on the Link button to link your account.</span>
                            <input class="btn btn-primary clearfix buttondflt" type="submit" style="width: 60%; background-position: 78% 13px;" name="myinfo_submit" value="Link">  
                        </div>
                        <div class="form-group" id="fn">
                            <label ><span style="color:red;">*</span>First Name</label>
                            <input	class="form-control" type="text" name="first_name" id="first_name" value="" maxlength="20" >
                        </div>

                        <div class="form-group" id="ln">
                            <label ><span style="color:red;">*</span>Last Name</label>
                            <input 	class="form-control" type="text" name="last_name" id="last_name" value="" maxlength="20" >
                        </div>
                        <div class="form-group" id="pws">
                            <label ><span style="color:red;">*</span>Password (atleast 6 characters)</label>

                            <input	class="form-control" type="password"	name="new_password" id="new_password" 	value="" maxlength="50">
                        </div>
                        <div class="form-group" id="cp">
                            <label ><span style="color:red;">*</span>Confirm Password</label>
                            <input	class="form-control" type="password"	name="new_password2" id="new_password2" 	value="" maxlength="50">
                        </div>
              <?php 
					$s=0;
			        foreach($sessionpatient['ProFieldGlobal'] as $field){
			       
					if($breakonfield==$s+7){
					?>
                    </div>
                    <div class="col-md-6 col-sm-6 error_tip">
					<?php
					}
					if(($field['ProfileField']['type']=='BigInt' || $field['ProfileField']['type']=='Varchar' || $field['ProfileField']['type']=='Integer')){ 
			  $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
			  ?>
                        <div class="form-group" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label ><?php
					if($field_val=='Street1'){
					echo "Address";
                                        $maxlength='100';
					}
					else if($field_val=='Street2'){
					echo "&nbsp;";
                                        $maxlength='100';
					}
                                        else if($field_val=='postal_code'){
					
                                        $maxlength='5';
					}
                                        else{
					 echo $field_val; 
                                         $maxlength='20';
					 }
					 ?></label>
                            <input class="form-control" type="text" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" value="" maxlength="<?=$maxlength?>" >
                        </div>
              <?php } 
			      if($field['ProfileField']['type']=='Text'){ 
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
			  ?>
                        <div class="form-group">
                            <label ><?php echo $field_val; ?></label>
                            <textarea id="<?php echo $field['ProfileField']['profile_field']; ?>" rows="6" cols="30" name="<?php echo $field['ProfileField']['profile_field']; ?>"></textarea>
                        </div>
              <?php } 
				if($field['ProfileField']['type']=='MultiSelect'){ 
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
			  ?>
                        <div class="form-group  clearfix" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label ><?php echo $field_val; ?></label>
                            <div style="position:relative;" class=" state_drop">
                                <span class="dropIcon dropIcon_new state"></span>
                                <select class="form-control" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" id="<?php echo $field['ProfileField']['profile_field']; ?>" multiple="multiple" size="4">
                                    <option value="">Please Select</option>
									<?php 
									foreach($field_options_red as $op){ ?>
                                    <option value="<?=$op?>"><?=$op?></option>
									<?php } ?>
                                </select>
                            </div>
                        </div>
              <?php }
              if($field['ProfileField']['type']=='Select'  && ($field['ProfileField']['profile_field']!='state' && $field['ProfileField']['profile_field']!='city')){ 
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
			  ?>
                        <div class="form-group  clearfix" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label ><?php echo $field_val; ?></label>
                            <div style="position:relative;" class="selctboxmonth state_drop">
                                <span class="dropIcon dropIcon_new state"></span>
                                <select class="form-control" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>">
                                    <option value="">Please Select</option>
									<?php 
									foreach($field_options_red as $op){ ?>
                                    <option value="<?=$op?>"><?=$op?></option>
									<?php } ?>
                                </select>
                            </div>
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
                                       
			  ?>
                        <div class="clearfix" ></div>
                        <div class="form-group clearfix" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label style="display:block;">
					  <?php if($field['ProfileField']['profile_field']=='gender'){ ?>
                                <span style="color:red;">*</span>
					  <?php } ?>
					  <?php echo $field_val; ?></label>
					  <?php foreach($field_options_red as $opt1){ ?>
                            <div class="col-xs-6 pull-left radio_btn">
                                <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $opt1; ?>"  onclick="opt1('<?php echo $opt1; ?>', '<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')">
                                <label class=" control-label"><?php echo $opt1; ?></label>
                            </div>
                      <?php } if($other==1){?>
                            <div class="col-xs-6 pull-left radio_btn">
                                <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="other"  onclick="opt1('other', '<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')">
                                <label class=" control-label">Other</label>
                            </div>  
                            <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>"  class="clearfix">


                            </div>
                      <?php } ?>
                        </div>
                        <script>

                            function opt1(val, field_name) {

                                if (val == 'other') {
                                    $('#othertext_' + field_name).html('<input class="form-control" type="text" name="other_' + field_name + '" id="other_' + field_name + '" placeholder="Other" value="" maxlength="30" >');
                                } else {
                                    $('#othertext_' + field_name).html('');
                                }
                            }
                        </script>
              <?php }
              if($field['ProfileField']['type']=='CheckBox'){ 
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options=explode(',',$field['ProfileField']['options']);
                                        $oth_chk1=end($field_options);
                                        $other1=0;
                                        if($oth_chk1=='(other)'){
                                          $other1=1;
                                          $field_options= array_diff($field_options,array($oth_chk1)); 
                                        }
                                       
			  ?>
                        <div class="form-group rad_btn" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <span style="display:block; font-weight:bold;"><?php echo $field_val; ?></span>
                            <div>
                   <?php foreach($field_options as $opt){ ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="<?php echo $opt; ?>"> <?php echo $opt; ?>
                                </label>

              <?php }if($other1==1){?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="other" onclick="opt('<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')"  id="getopt_<?php echo $field['ProfileField']['profile_field']; ?>"> Other
                                </label> 
                                <div id="othertext1_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>">


                                </div>
                      <?php } ?>
                            </div>
                        </div>
                        <script>

                            function opt(val) {

                                if ($('#getopt_' + val).is(":checked"))
                                {
                                    $('#othertext1_' + val).html('<input class="form-control" type="text" name="other_' + val + '" id="other_' + val + '" placeholder="Other" value="" maxlength="30" >');
                                } else {
                                    $('#othertext1_' + val).html('');
                                }

                            }</script>
                <?php }
	    if($field['ProfileField']['profile_field']=='state'){
						?>

                        <div class="form-group  clearfix" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label >State</label>
                            <div style="position:relative;" class="selctboxmonth state_drop">
                                <span class="dropIcon dropIcon_new state"></span>
                                <select class="form-control" name="state" id="state" onchange="getcity();">
                                    <option value="">Select State</option>
               <?php 
               foreach($states as $st){ ?>
                                    <option value="<?=$st['State']['state']?>"><?=$st['State']['state']?></option>
                <?php } ?>
                                </select>
                            </div>
                        </div>



              <?php
	     }
	    if($field['ProfileField']['profile_field']=='city'){
						?>


                        <div class="form-group  clearfix" id="hid_<?=$field['ProfileField']['profile_field']?>">
                            <label >City</label>
                            <div style="position:relative;" class="selctboxmonth state_drop" id="city_drop">
                                <span class="dropIcon dropIcon_new"></span>
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
             <?php
					}
					
				
				
					$s++;
                   }
                   
                    ?>
                        <input type="submit" value="SUBMIT" name="myinfo_submit" class="btn btn-primary clearfix buttondflt" style="width:60%; background-position: 78% 13px;"  id='hid_submit'>

                    </div>
                </div>

        </div>
    </div>
</form>

</div>






<!-- Modal for sign up popup-->

<div class="modal fade popupBox" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-sm">
        <div class="modal-content popup ">
            <div class="row rowcont">
                <div class="modal-header col-md-12" style="position:relative;">
                    <a class="close closebtn" onclick="close_form_mul();">&times;</a>
                </div>
            </div>
            <div class="modal-body clearfix wid-100">
                <div class=" row">
                    <div class="col-xs-12 clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
                            <h3>This Username associated with these card number please select card and proceed to login: </h3>
                            <form action="/rewards/getmultilogin" method="POST" name="multilogin" id='multilogin' >
                                <table border='0' width='100%'>
                                    <tr><?php $sessionpatient = $this->Session->read('patient'); ?>
                                        <td>Card Number<span style='color:red;'>*</span>

                                            <input type="hidden" name='api_user' id='api_user' value="<?php echo $sessionpatient['api_user']; ?>"> </td>

                                        <td id="sel_card">
                                            <div class="relative">
                                                <select name="card_number_mul" id="card_number_mul"  onchange="changeErrorMessage(this.id)" >
                                                    <option value="">Select Card Number</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><input type="submit" value='Proceed' id='send_mail' class="btn btn-primary buttondflt back_icon"  onclick="return chmulConfirmation()"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>


                    </div>

                </div>
            </div>
            </form>
        </div>
    </div>
</div>   <!--popup--> 

<div class="modal fade popupBox" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">


    <div class="modal-dialog-sm" style="margin:auto; margin-top: 50px;">
        <div class="modal-content popup" style="border-radius:20px; border: 2px solid #bdbdbd;">
            <div class="row rowcont">
                <div class="modal-header col-md-7 col-sm-7 col-xs-12 pad-l-34" style="position:static;">
                    <a class="close closebtn" onclick="close_form_exist();">&times;</a>
                    <h4 class="modal-title" id="myModalLabel">Set Your Email/Password</h4>
                    <p>Complete the form below to start earning 
                        easy points and redeeming great rewards.</p>
                </div>

            </div>
            <form action="<?=Staff_Name?>rewards/signup/" method="POST" name="exist_account_form" id="exist_account_form" class="popupform" >

                <input type="hidden" name="action" value="record_exist_account" id='action'>
                <input type="hidden" name="id" id="id" value="">
                <div class="modal-body clearfix" style="margin-top: 85px;">
                    <div class="col-md-6 col-sm-6 error_tip">

                        <div class="form-group">
                            <label >Card Number</label>


                            </span>
                            <input class="form-control" type="text" id="cardexist"	name="cardexist" value="" maxlength="255" readonly>
                        </div>


                        <div class="form-group" id="fn">
                            <label ><span style="color:red;">*</span>First Name</label>
                            <input	class="form-control" type="text" name="first_name_exist" id="first_name_exist" value="" maxlength="20" >
                        </div>

                        <div class="form-group" id="ln">
                            <label ><span style="color:red;">*</span>Last Name</label>
                            <input 	class="form-control" type="text" name="last_name_exist" id="last_name_exist" value="" maxlength="20" >
                        </div>

                        <div class="form-group" id="email_field">
                            <label ><span style="color:red;">*</span>Email</label>
                            <input 	class="form-control" type="email" name="emailexist" id="emailexist" value="" maxlength="50"  onblur="checkemail();">
                        </div>
                        <div id="msgboxcheckemailexist" class="form-group clearfix" style="display:none;">
                            <span style="color:red;">This email id exists with us.</span>
                        </div>
                        <div class="form-group" id="pws">
                            <label ><span style="color:red;">*</span>Password (atleast 6 characters)</label>

                            <input	class="form-control" type="password"	name="new_password_exist" id="new_password_exist" 	value="" maxlength="50">
                        </div>
                        <div class="form-group" id="cp">
                            <label ><span style="color:red;">*</span>Confirm Password</label>
                            <input	class="form-control" type="password"	name="new_password2_exist" id="new_password2_exist" 	value="" maxlength="50">
                        </div>

                        <input type="submit" value="SUBMIT" name="myinfo_submit" class="btn btn-primary clearfix buttondflt" style="width:60%; background-position: 78% 13px;"  id='hid_submit' >

                    </div>
                </div>

        </div>
    </div>
</form>

</div>
<div class="" id="Mymodel1"></div>
<!--popup-->


<script language="Javascript">



    function lightbox() {

        var card_number = $('#loginCardNumber').val();
        if (card_number == '') {
            alert('Please fill the card number provided by orthodontist.');
            return false;
        } else {
            $.ajax({
                type: "POST",
                data: "card_number=" + card_number,
                dataType: "json",
                url: "<?=Staff_Name?>rewards/verifycard/",
                success: function(result) {
                    if (result.status == 1) {
                        alert('Card Number Already Exist');
                        return false;
                    }
                    if (result.status == 0) {
                        alert('Invalid Card Number.');
                        return false;
                    }
                    if (result.status == 4) {
                        alert('Card Not Issued.');
                        return false;
                    }
                    if (result.status == 3) {
                        alert('Clinic does not exist for this card number.');
                        return false;
                    }
                    if (result.status == 2) {
                        //$(this).closest('.abc').remove();
                        $('div.form-group').children('div.error_validate').remove();
                        $('#myModal').addClass("modal fade popupBox in");
                        $('#Mymodel1').addClass('modal-backdrop fade in');
                        $('#myModal').attr('aria-hidden', false);
                        $('#myModal').css('display', 'block');
                        $('#card').val(card_number);
                        $('#card1').val(card_number);
                    }
                    if (result.status == 5) {
                        //$(this).closest('.abc').remove();
                        $('div.form-group').children('div.error_validate').remove();
                        $('#myModal3').addClass("modal fade popupBox in");
                        $('#Mymodel1').addClass('modal-backdrop fade in');
                        $('#myModal3').attr('aria-hidden', false);
                        $('#myModal3').css('display', 'block');
                        $('#cardexist').val(result.card_number);
                        $('#first_name_exist').val(result.first_name);
                        $('#last_name_exist').val(result.last_name);
                        $('#id').val(result.id);

                    }
                }});



        }
    }

    function checkemail() {

        var user_id = $('#id').val();
        var datasrc = "user_id=" + user_id;

        var email = $('#emailexist').val();
        datasrc = datasrc + "&email=" + email;

        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>rewards/checkemail/",
            success: function(result) {
                if (result == 1) {

                    $('#msgboxcheckemailexist').css('display', 'block');
                    $('input[type="submit"]').attr('disabled', 'disabled');


                    return false;
                }
                else if (result == 2) {
                    $('#msgboxcheckemailexist').css('display', 'block');
                    $('input[type="submit"]').attr('disabled', 'disabled');


                    return false;

                }
                else if (result == 3) {
                    $('#msgboxcheckemailexist').css('display', 'block');
                    $('input[type="submit"]').attr('disabled', 'disabled');


                    return false;

                }
                else {
                    $('#msgboxcheckemailexist').css('display', 'none');
                    $('input[type="submit"]').removeAttr('disabled');
                }
            }
        });
        return false;
    }

    function close_form_exist() {

        $('#myModal3').addClass("modal fade popupBox");
        $('#myModal3').attr('aria-hidden', true);
        $('#myModal3').css('display', 'none');
        $('#Mymodel1').removeClass('modal-backdrop fade in');
        $('input[type="submit"]').removeAttr('disabled');
        $('#msgboxcheckemailexist').css('display', 'none');
        $('#cardexist').val('');
        $('#first_name_exist').val('');
        $('#last_name_exist').val('');
        $('#new_password_exist').val('');
        $('#new_password2_exist').val('');
        $('#emailexist').val('');



    }
    function checkCardNumber(){
        var card_number=$('#card').val();
        if(card_number!=''){
        datasrc = 'card_number=' + card_number ;
        $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>rewards/checkCardNumber/",
                success: function(result) {
                    if (result == 0) {
                       $('input[type="submit"]').removeAttr('disabled');
                       
                    } else if (result == 1) {
                        alert('Card Number Not Exist');
                       $('input[type="submit"]').attr('disabled', 'disabled'); 
                    }else{
                        alert('Card Number Already Registered');
                       $('input[type="submit"]').attr('disabled', 'disabled'); 
                    }
                }
            });
            }
    }
    function checkuserexist() {

        var datasrc = '';
        var yr = $('#date_year').val();
        var mn = $('#date_month').val();
        var dy = $('#date_day').val();

        datasrc = 'dob=' + yr + '-' + mn + '-' + dy;
        if ($('#email').val() == undefined) {
            var email = $('#parents_email').val();
        }
        else {
            var email = $('#email').val();
        }
        if (email != '') {
            datasrc = datasrc + "&email=" + email;
        }
        if ($('#aemail').val() != undefined && $('#aemail').val() != '') {
            var pemail = $('#aemail').val();
            datasrc = datasrc + "&parents_email=" + pemail;
        }
        if (datasrc != '') {
            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>rewards/checkuserexist/",
                success: function(result) {
                    if (result == 1) {
                        $('#fn').css('display', 'none');
                        $('#ln').css('display', 'none');
                        $('#pws').css('display', 'none');
                        $('#cp').css('display', 'none');
                <?php
               foreach($sessionpatient['ProFieldGlobal'] as $field){ ?>
                        $('#hid_' + '<?=$field['ProfileField']['profile_field']?>').css('display', 'none');
               <?php } ?>
                        $('#hid_submit').css('display', 'none');
                        $('#msgboxcheckemail').css('display', 'block');
                        $('#action').val('link');
                    } else {
                        $('#fn').css('display', 'block');
                        $('#ln').css('display', 'block');
                        $('#pws').css('display', 'block');
                        $('#cp').css('display', 'block');
                <?php
               foreach($sessionpatient['ProFieldGlobal'] as $field){ ?>
                        $('#hid_' + '<?=$field['ProfileField']['profile_field']?>').css('display', 'block');
               <?php } ?>
                        $('#hid_submit').css('display', 'block');
                        $('#msgboxcheckemail').css('display', 'none');
                        $('#action').val('record_new_account');
                    }
                }
            });
        }
        return false;
    }
    function close_form() {

        $('#myModal').addClass("modal fade popupBox");
        $('#myModal').attr('aria-hidden', true);
        $('#myModal').css('display', 'none');
        $('#Mymodel1').removeClass('modal-backdrop fade in');
        $('#hid_submit').css('display', 'none');
        $('#msgboxcheckemail').css('display', 'block');
        $('#action').val('link');

        $('#fn').css('display', 'block');
        $('#ln').css('display', 'block');
        $('#pws').css('display', 'block');
        $('#cp').css('display', 'block');
                <?php
               foreach($sessionpatient['ProFieldGlobal'] as $field){ ?>
        $('#hid_' + '<?=$field['ProfileField']['profile_field']?>').css('display', 'block');
               <?php } ?>
        $('#hid_submit').css('display', 'block');
        $('#msgboxcheckemail').css('display', 'none');
        $('#action').val('record_new_account');
        $('#card').val('');
        $('#first_name').val('');
        $('#last_name').val('');
        $('#new_password').val('');
        $('#new_password2').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#date_year').val('1990');
        $('#date_month').val('');
        $('#date_day').val('');
        $('#street1').val('');
        $('#state').val('');
        $('#city').val('');
        $('#postal_code').val('');
        $('#gender').prop('checked', false);
        $('#inlineCheckbox1').prop('checked', false);
        $('#inlineCheckbox2').prop('checked', false);
        $('#inlineCheckbox3').prop('checked', false);
        $('#inlineCheckbox4').prop('checked', false);
        $('#inlineCheckbox5').prop('checked', false);
        $('#inlineCheckbox6').prop('checked', false);
        $('#inlineCheckbox7').prop('checked', false);
        $('#inlineCheckbox8').prop('checked', false);
        $('#inlineCheckbox9').prop('checked', false);
        $('#loginCardNumber').val('');


    }

    function close_form_mul() {

        $('#Mymodel1').removeClass('modal-backdrop fade in');
        $('#myModal2').addClass("modal fade popupBox");
        $('#myModal2').attr('aria-hidden', true);
        $('#myModal2').css('display', 'none');
    }
    function checkuser() {

        var card_number = $('#loginPatientName').val();
        var password = $('#loginPatientPassword').val();


        $.ajax({
            type: "POST",
            data: "card_number=" + card_number + '&password=' + password,
            dataType: "json",
            url: "<?=Staff_Name?>rewards/checkuser/",
            success: function(result) {
                if (result == 0) {
                    $("#loginLoginForm").submit();
                } else {
                    $('#myModal2').addClass("modal fade popupBox in");
                    $('#Mymodel1').addClass('modal-backdrop fade in');
                    $('#myModal2').attr('aria-hidden', false);
                    $('#myModal2').css('display', 'block');
                    $('#sel_card').html(result);

                }
            }});
        return false;
    }
    $(document).ready(function() {
        $("#aemail").live('hover', function() {
            $("#dismsg").toggle();
        });

        $("#aemail").mouseleave(function() {
            $("#dismsg").css('display', 'none');
        });
        var window_wid = $(window).width();
        if (window_wid < 992) {

            $("#clickme").click(function() {
                $("#Style").toggle();
            });

            $("#clickme").mouseleave(function() {
                $("#Style").css('display', 'none');
            });
            $("#clickme1").click(function() {
                $("#Style1").toggle();
            });

            $("#clickme1").mouseleave(function() {
                $("#Style1").css('display', 'none');
            });

            $('.tool_tip .helpicon').appendTo('.tool_tip p');
        } else {
            $("#clickme").hover(function() {
                $("#Style").toggle();
            });
            $("#clickme1").hover(function() {
                $("#Style1").toggle();
            });
        }

        $.validator.addMethod("zipRegex", function(value, element) {
            return this.optional(element) || /^[a-z0-9]+$/i.test(value);
        }, "Zipcode must contain only alphanumeric.");

        $('#new_account_form').validate({
            rules: {
                card: {
                    required: true,number: true
                },
                first_name: "required",
                last_name: "required",
                new_password: {
                    required: true, minlength: 6
                },
                new_password2: {
                    required: true, equalTo: "#new_password", minlength: 6
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    number: true, minlength: 7, maxlength: 10
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
                    required: true,
                    email: true,
                    checkparentemail: true
                },
                aemail: {
                    required: true,
                    checkparentemail: true
                },
                postal_code: {
                    //minlength: 5 ,maxlength:5
                    zipRegex: true,
                    minlength: 4, maxlength: 6

                },
                gender: {
                    required: true
                }

            },
            // Specify the validation error messages
            messages: {
                card: {
                    required: "Please provide card number",
                    number: "Please enter a valid card number"
                },
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                new_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                new_password2: {
                    required: "Please provide a confirm password"
                },
                email: {
                    required: "Please enter a valid email address."
                },
                phone: {
                    number: "Please enter a valid phone number",
                    minlength: "Phone Number must be 7 to 10 characters long",
                    maxlength: "Phone Number must less then 11 characters"
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
                postal_code: {
                    //minlength: "Zip code must be 5 characters long"
                    zipRegex: "Please enter valid zipcode",
                    minlength: "Zip code must be greater then 3 characters",
                    maxlength: "Zip code must be less then 7 characters"

                },
                gender: "Please select gender",
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

        $('#exist_account_form').validate({
            rules: {
                first_name_exist: "required",
                last_name_exist: "required",
                new_password_exist: {
                    required: true, minlength: 6
                },
                new_password2_exist: {
                    required: true, equalTo: "#new_password_exist", minlength: 6
                },
                emailexist: {
                    required: true,
                    email: true
                }


            },
            // Specify the validation error messages
            messages: {
                first_name_exist: "Please enter your first name",
                last_name_exist: "Please enter your last name",
                new_password_exist: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                new_password2_exist: {
                    required: "Please provide a confirm password"
                },
                emailexist: {
                    required: "Please enter a valid email address."
                },
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
    });
    function ShowPicture(id, Source) {
        if (Source == "1") {
            if (document.layers)
                document.layers['' + id + ''].visibility = "show"
            else if (document.all)
                document.all['' + id + ''].style.visibility = "visible"
            else if (document.getElementById)
                document.getElementById('' + id + '').style.visibility = "visible"
        }
        else
        if (Source == "0") {
            if (document.layers)
                document.layers['' + id + ''].visibility = "hide"
            else if (document.all)
                document.all['' + id + ''].style.visibility = "hidden"
            else if (document.getElementById)
                document.getElementById('' + id + '').style.visibility = "hidden"
        }
    }

</script>
<script type="text/javascript">







    function lightbox1() {

        var card_number = $('#loginCardNumber').val();
        if (card_number == '') {
            alert('Please fill the card number provided by orthodontist.');
            return false;
        } else {
            $.ajax({
                type: "POST",
                data: "card_number=" + card_number,
                dataType: "json",
                url: "<?=Staff_Name?>rewards/verifycard/",
                success: function(result) {
                    if (result.status == 1) {
                        alert('Card Number Already Exist');
                        return false;
                    }
                    if (result.status == 0) {
                        alert('Invalid Card Number.');
                        return false;
                    }
                    if (result.status == 2) {
                        document.signupLoginForm1.submit();
                    }
                }});



        }
    }

    function getcity() {

        var state = $('#state').val();

        $.ajax({
            type: "POST",
            data: "state=" + state,
            url: "<?=Staff_Name?>rewards/getcity/",
            success: function(result) {
                $('#city').html(result);
            }});




    }
    function getemailcont() {

                    var emailprovide = $('input[name=emailprovide]:checked').val();
                    if (emailprovide == 'perent'){
                    $xml = '<div class="form-group clearfix" id="pemail"><label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value=""></div><div class="form-group" id="email_field"><label><span style="color:red;">*</span>Username</label><input class="form-control" type="text" name="aemail" id="aemail" value="" maxlength="255"  onblur="checkuserexist();"><div id="dismsg" style="display:none" class="note">Username has to be unique</div></div>';
                    
            }else{
                    $xml = '<div class="form-group clearfix" id="pemail"><label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value=""></div>';
            }
            $('#emailvalid').html($xml);
            }
    function checkage() {
        var yr = $("#date_year").val();
        var mn = $("#date_month").val();
        var dy = $("#date_day").val();
        var today = new Date()
        var past = new Date(yr, mn, dy)

        var diff = Math.floor(today.getTime() - past.getTime());
        var day = 1000 * 60 * 60 * 24;

        var days = Math.floor(diff / day);
        var months = Math.floor(days / 31);
        var years = Math.floor(months / 12);


        if ((days <= 4716) && (days >= 1)) {
            $('#emailprocess').html('<div class="form-group clearfix" id="pemail"><label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value=""></div><div class="form-group" id="email_field"><label><span style="color:red;">*</span>Username</label><input class="form-control" type="text" name="aemail" id="aemail" value="" maxlength="255"  onblur="checkuserexist();"><div id="dismsg" style="display:none" class="note">Username has to be unique</div></div>');
            return true;
        }else if ((days <= 6543) && (days >= 4716)) {
            $('#emailprocess').html('<div class="form-group clearfix" id="pemail"><div class="form-group clearfix"><label style="display:block;">Choose email</label><div class="col-xs-6 pull-left radio_btn"><input type="radio" value="own" name="emailprovide" id="emailprovide" class="form-control"><label class=" control-label">Own</label></div><div class="col-xs-6 pull-left radio_btn"><input type="radio" value="perent" name="emailprovide" id="emailprovide" class="form-control"><label class=" control-label">Parent</label></div></div></div><div id="emailvalid"><div class="form-group clearfix" id="pemail"><label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value=""></div><div class="form-group" id="email_field"><label><span style="color:red;">*</span>Username</label><input class="form-control" type="text" name="aemail" id="aemail" value="" maxlength="255"  onblur="checkuserexist();"><div id="dismsg" style="display:none" class="note">Username has to be unique</div></div></div>');

            return true;
        }
        else {
            $('#pemail').html('');
            $('#email_field').html('<label><span style="color:red;">*</span>Email</label><input class="form-control" type="email" name="email" id="email" value="" maxlength="255"   onblur="checkuserexist();">');
            return true;
        }

    }

    function chmulConfirmation() {

        if ($("#card_number_mul").val() == '') {
            $("#card_number_mul").css('background-color', '#FF9966');
            $("#error_div_msg_card_number_mul").html("Please select card number.");
            $("#card_number_mul").focus();
            return false;

        }

    }
    function changeErrorMessage(ptr) {
        $("#" + ptr).css('background-color', '');
        $("#error_div_msg_" + ptr).html("");

    }
    
    function getNextCard(id) {
            $.ajax({
                type: "POST",
                data: "clinic_id=" + id,
                url: "<?=Staff_Name?>rewards/getNextFreeCard/",
                success: function(result) {
                    if (result!='') {                     
                        $('div.form-group').children('div.error_validate').remove();
                        $('#myModal').addClass("modal fade popupBox in");
                        $('#Mymodel1').addClass('modal-backdrop fade in');
                        $('#myModal').attr('aria-hidden', false);
                        $('#myModal').css('display', 'block');
                        $('#card').val(result);
                        $('#card1').val(result);
                    }else{
                        alert('Card Not available please contact to clinic staff.');
                    }
                }});
    }
</script>
