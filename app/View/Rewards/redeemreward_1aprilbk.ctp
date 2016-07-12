<style type="text/css">
.form-group .col-xs-4 { padding-left:0;}
.detail {padding: 15px 15px;
border-top: 1px solid #333;
margin-top: 10px;}
.social_buttons { display:none;}
.rightcont { height:auto !important;}
.form-control { height:34px;}

.selctboxstate:last-child { padding-right:0;}


</style>
<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['patient_logo']) && $sessionpatient['patient_logo']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=$sessionpatient['patient_logo']?>" width="246" height="88" alt="Pure Smiles" title="Pure Smiles" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image('reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image('reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

       
        
          </div>
<div class=" clearfix">
       
        
        <div class="col-lg-9 col-xs-12 rightcont">
        
          <div class="settingArea clearfix mob_top">
<?php
 $sessionpatient = $this->Session->read('patient');
		if (!empty($sessionpatient['customer_info'])) { ?>
			<div>
			<?php echo $this->Session->flash(); ?>
			<div class="content_left breathing_room">
			<div class="section_header content_center">Please confirm your order details</div>
			<form class=" form_size" action="<?=Staff_Name?>rewards/redeem/" method="POST" name="redeemreward_form" id="redeemreward_form" >
			<input type="hidden" name="action" value="redeem_reward">
			<input type="hidden" name="id" value="<?=$Patients['Patients']['id']?>">
			<input type="hidden" name="which_reward_id" value="<?=$rewards['id']?>">
			<input type="hidden" name="which_reward_description" value="<?=$rewards['description']?>">
			<input type="hidden" name="which_reward_level" value="<?=$rewards['level']?>">
			<input type="hidden" name="which_campaign" value="<?=$sessionpatient['preferences']['campaigns_to_show']?>">
			<input type="hidden" name="which_campaign_type" value="<?=$sessionpatient['campaign_rewards']['campaign'][0]['type']?>">
			<table cellspacing="0" cellpadding="4" border="0" width="100%" style="width:100%;" class="pad-l-25">
			
			<tr>
			<td>
			<span class="normal content_left">This is the item you have selected as a reward.</span>
			</td>
			</tr>
			<tr>
			<td>
			<table cellpadding="0" cellspacing="10" border="0" class="reward_table" align="center" style="width:100%;" class="pad-l-25">
						<tr>
			<td>
			<div class="show_image_confirm">
				<?php
				$uploadFolder = "rewards/".$sessionpatient['api_user'];
				$uploadPath = WWW_ROOT .'img/'. $uploadFolder;
			if (file_exists($uploadPath.'/'.$rewards['description'])) {
				?>
				<div class="content_left social_buttons">
				<div class="g-plusone" data-annotation="inline" data-width="280"></div><br>
				<div class="fb-like" data-send="true" data-width="280" data-show-faces="false"></div><br>
				</div>
				<img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user'].'/'.$rewards['description']?>" width="240px" height="160px">
				<?php
			}else{
				if (file_exists($uploadPath."/noimage.jpg")) {
				?>
				<div class="content_left social_buttons">
				<div class="g-plusone" data-annotation="inline" data-width="280"></div><br>
				<div class="fb-like" data-send="true" data-width="280" data-show-faces="false"></div><br>
				</div>
				<img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user'].'/noimage.jpg';?>" width="240px" height="160px">
				<?php
				}else{
					?>
					<div class="content_left social_buttons">
					<div class="g-plusone" data-annotation="inline" data-width="280"></div><br>
					<div class="fb-like" data-send="true" data-width="280" data-show-faces="false"></div><br>
					</div>
					<img src="http://redeem.integr8ideas.com/wp-admin/rewardimages/<?=$rewards['description']?>" alt="" width="240px" height="160px">
					<?php
				}
			}
			?></div>
			</td>
			</tr>
			<tr>
				<td itemprop="name" align="left" valign="top" class="reward_title test_point"><?=$rewards['description']?></td>
			</tr>
			<tr>
			<td align="right" valign="top" class="reward_amount">
				<div class="points">
				<?php
				if ($rewards['level'] > 0) { ?>
					<span class="large">-<?=number_format($rewards['level'])?></span>
					<?php
				} elseif ($rewards['level'] == 0) {
					?>
					<span class="large"><?=number_format($rewards['level'])?></span>
					<?php
				} else {
				?>
					<span class="large">+<?=number_format($rewards['level'])?></span>
					<?php
				}
				echo (intval($rewards['level']) == 1) ? 'point' : 'points';
			 ?>
             </div>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			<td class="tiny">&nbsp;</td>
			</tr>
			<tr>
			<td>
			
			
			<span class="normal content_left"><strong>Rewards will be shipped to your office. Please make sure this is your correct information. Rewards are shipped monthly.</span>
			</td>
			</tr>
			</table>
			
			         <div class="detail ">
         <?php
			if (!empty($sessionpatient['preferences']['customer_fields_order'])) {
				foreach ($sessionpatient['preferences']['customer_fields_order'] as $field_sorted) {
					if (in_array($field_sorted, $sessionpatient['preferences']['fields_to_show'])
						|| $field_sorted == 'card_number'
						|| $field_sorted == 'customer_password') {
						// Normal fields:
						if (strpos($field_sorted, 'custom_field_') === false || $field_sorted == 'custom_field_1') {
							if ($field_sorted == 'card_number') {
								?>
               <div class="form-group">
               <label >Card Number:</label>
              <?=$sessionpatient['customer_info']['customer'][0]['card_number']?>
								<input	class="form-control" type="hidden" name="card_number" value="<?=$sessionpatient['customer_info']['customer'][0]['card_number']?>" maxlength="255">
              </div>
              <?php }
							elseif ($field_sorted == 'first_name') { ?>
               <div class="form-group">
               <label >First Name<span style="color:red;">*</span><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_first_name" ></span></label>
             <?php if (!empty($sessionpatient['customer_info']['customer'][0]['first_name'])) {
									$first_name = $sessionpatient['customer_info']['customer'][0]['first_name'];
								}
								else {
									$first_name = '';
								}
								?>
	<input	class="form-control" type="text" name="first_name" value="<?=$first_name?>" maxlength="100" id='first_name' >
				</div>
              <?php }
							elseif ($field_sorted == 'last_name') { ?>
              <div class="form-group">
               <label >Last Name<span style="color:red;">*</span><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_last_name" ></span></label>
             <?php
								if (!empty($sessionpatient['customer_info']['customer'][0]['last_name'])) {
									$last_name = $sessionpatient['customer_info']['customer'][0]['last_name'];
								}
								else {
									$last_name = '';
								}
								?>
<input 	class="form-control" type="text" name="last_name" value="<?=$last_name?>" maxlength="100" id="last_name" >
				</div>
            <?php }elseif ($field_sorted == 'phone') { ?>
              <div class="form-group">
               <label >Phone:</label><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_phone" ></span>
           
								<?php if (!empty($sessionpatient['customer_info']['customer'][0]['phone'])) {
									$phone = $sessionpatient['customer_info']['customer'][0]['phone'];
								}
								else {
									$phone = '';
								}
								?>
<input 	class="form-control"  type="text" id='phone' name="phone" value="<?=$phone?>" maxlength="10" >
				</div>
				<?php }elseif ($field_sorted == 'email') { ?>
				 <div class="form-group">
               <label>Email <span style="color:red;">*</span><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_email" ></span></label>
								<?php
								
								if (!empty($sessionpatient['customer_info']['customer'][0]['email'])) {
									$email = $sessionpatient['customer_info']['customer'][0]['email'];
								}
								else {
									$email = '';
								}
								?>
<input 	class="form-control" type="email" name="email" value="<?=$email?>" maxlength="100" id='email' >
				</div>
				<?php }elseif ($field_sorted == 'custom_date') {
									if (!empty($sessionpatient['customer_info']['customer'][0]['custom_date'])) {
										$date_array = explode ('-', $sessionpatient['customer_info']['customer'][0]['custom_date']);
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
									} ?>
				 <div class="form-group" style="margin-bottom:0;">
               <label>Date Of Birth <span style="color:red;">*</span><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_date_year" ></span></label>
				
				</div>				
				<div class="form-group clearfix">
				 <div class="col-xs-4 selctboxstate clearfix"> 
              
				<select name="date_year" size="1" class="form-control" id='date_year' >
									<option value="">Select Year</option>
									<?php
									for ($y = 1900; $y <= 2050; $y += 1) {
										if ($y == $year) {
											echo'
											<option value="'.$y.'" SELECTED>'.$y.'</option>';
										} else {
											echo'
											<option value="'.$y.'">'.$y.'</option>';
										}
									} ?>
									
				</select>
				</div>				
				 <div class="col-xs-4 selctboxstate clearfix"> 
              
				<select name="date_month" size="1"  class="form-control" id='date_month' >
									<option value="">Select Month</option>
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
						<option value="<?=$mon?>" <?php if ($mon == $month) { echo "selected"; } ?>><?php echo $val;?></option>
						<?php } ?>
									
									</select>
				</div>
				 <div class="col-xs-4 selctboxstate clearfix"> 
              
				<select name="date_day" size="1" class="form-control" id='date_day' >
									<option value="">Select Day</option>
									<?php
									for ($d = 1; $d <= 31; $d += 1) {
										if ($d == $day) {
											echo'
											<option value="'.$d.'" SELECTED>'.$d.'</option>';
										} else {
											echo'
											<option value="'.$d.'">'.$d.'</option>';
										}
									}
									?>
									</select>
				</div>	
				</div>		
				<?php }elseif ($field_sorted == 'address1' || $field_sorted == 'street1') { ?>		
				 <div class="form-group">
               <label>Address:</label>
									<?php if (!empty($sessionpatient['customer_info']['customer'][0]['street1'])) {
										$street1 = $sessionpatient['customer_info']['customer'][0]['street1'];
									}
									else {
										$street1 = '';
									}
									?>
									<input 	class="form-control" type="text"	name="street1" value="<?=$street1?>" maxlength="255" >
				</div>		
				<?php }elseif ($field_sorted == 'address2' || $field_sorted == 'street2') { ?>		
				 <div class="form-group">
              <?php
									if (!empty($sessionpatient['customer_info']['customer'][0]['street2'])) {
										$street2 = $sessionpatient['customer_info']['customer'][0]['street2'];
									}
									else {
										$street2 = '';
									}
									?>
									<input 	class="form-control" type="text" name="street2" value="<?=$street2?>" maxlength="255">
				</div>		
				<?php }elseif ($field_sorted == 'city') { ?>		
				 <div class="form-group">
              <label>City:</label>
              
									<?php
									if (!empty($sessionpatient['customer_info']['customer'][0]['city'])) {
										$city_val = $sessionpatient['customer_info']['customer'][0]['city'];
									}
									else {
										$city_val = '';
									}
									
									?>
									<select class="form-control" name="city" id="city">
									<option value="">Select City</option>
<?php foreach($city as $ct){ ?>
<option value="<?=$ct['Cities']['city']?>" <?php if($city_val==$ct['Cities']['city']){ echo "selected"; } ?>><?=$ct['Cities']['city']?></option>
<?php } ?>
  				</select>
									
				</div>		
				<?php }elseif ($field_sorted == 'state') { ?>		
				 <div class="form-group">
              <label>State:</label>
									<?php
									if (!empty($sessionpatient['customer_info']['customer'][0]['state'])) {
										$state = $sessionpatient['customer_info']['customer'][0]['state'];
									}
									else {
										$state = '';
									}
									?>
										<select class="form-control" name="state" id="state" onchange="getcity();">
									<option value="">Select State</option>
									<?php foreach($states as $st){ ?>
<option value="<?=$st['States']['state']?>" <?php if($state==$st['States']['state']){ echo "selected"; } ?>><?=$st['States']['state']?></option>
<?php } ?>
  				</select>
				</div>				
				<?php }elseif ($field_sorted == 'zip') { ?>
				 <div class="form-group">
               <label>Zip:</label>
									<?php
									if (!empty($sessionpatient['customer_info']['customer'][0]['postal_code'])) {
										$postal_code = $sessionpatient['customer_info']['customer'][0]['postal_code'];
									}
									else {
										$postal_code = '';
									}
									?>
									<input 	class="form-control" type="text" name="postal_code" id="postal_code" value="<?=$postal_code?>" maxlength="8">
									
				</div>
				<?php }elseif ($field_sorted == 'country') { ?>
				 <div class="form-group">
               <label>Country:</label>
									<?php
									if (!empty($sessionpatient['customer_info']['customer'][0]['country'])) {
										$country = $sessionpatient['customer_info']['customer'][0]['country'];
									}
									else {
										$country = '';
									}
									?>
									<input 	class="form-control" type="text" name="country" value="<?=$country?>" maxlength="255" >
									
				</div>
				<?php }elseif ($field_sorted == 'custom_field_1' ) {
								// get label:
									foreach ($sessionpatient['customer_fields']['account'][0]['fields'][0]['field'] as $ignore => $field_data) {
										if ($field_data->name == 'custom_field_1') {
											$which_field_name_label = $field_data['label'];
										}
									}
									if (!empty($which_field_name_label)) { ?>
				 <div class="form-group">
               <label><?=$which_field_name_label?>:</label>
               <?php
										if (!empty($sessionpatient['customer_info']['customer'][0]['custom_field'])) {
											$custom_field = $sessionpatient['customer_info']['customer'][0]['custom_field'];
										}
										else {
											$custom_field = '';
										}
										?>
										<input 	class="form-control" type="text" name="custom_field_1" value="<?=$custom_field?>" maxlength="255">
										
									
				</div>
					<?php
									}
								}
						}
						else { // Custom fields:
							if ($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['type'] == 'Text') {
								if (!empty($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data'])) {
									$text_data = $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data'];
								}
								else {
									$text_data = '';
								}
								?>
				<div class="form-group">
				<label><?=$sessionpatient['customer_info']['customer'][0][$field_sorted][0]['label']?>:</label>
								<input 	class="form-control" type="text" name="<?=$field_sorted?>" value="<?=$text_data?>" maxlength="255" >
					</div>			
				<?php }elseif ($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['type'] == 'Date') {
								if (!empty($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data'])) {
									$date_array = explode ('-', $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data']);
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
				 <div class="form-group">
               <label>Date Of Birth:</label>
				
				</div>				
				<div class="form-group clearfix">
               
				<select name="<?=$field_sorted?>_year" size="1"  class="form-control">
									<option value="">Select Year</option>
									<?php
									for ($y = 1900; $y <= 2050; $y += 1) {
										if ($y == $year) {
											echo'
											<option value="'.$y.'" SELECTED>'.$y.'</option>';
										} else {
											echo'
											<option value="'.$y.'">'.$y.'</option>';
										}
									} ?>
									
				</select>
				</div>				
				<div class="form-group">
              
				<select name="<?=$field_sorted?>_month" size="1" class="form-control">
									<option value="">Select Month</option>
									<?php
									for ($m = 1; $m <= 12; $m += 1) {
										if ($m == $month) {
											echo'
											<option value="'.$m.'" SELECTED>'.$m.'</option>';
										} else {
											echo'
											<option value="'.$m.'">'.$m.'</option>';
										}
									}?>
									
									</select>
				</div>
				<div class="form-group">
             
				<select name="<?=$field_sorted?>_day" size="1">
									<option value="">Select Day</option>
									<?php
									for ($d = 1; $d <= 31; $d += 1) {
										if ($d == $day) {
											echo'
											<option value="'.$d.'" SELECTED>'.$d.'</option>';
										} else {
											echo'
											<option value="'.$d.'">'.$d.'</option>';
										}
									}
									?>
									</select>
				</div>	
				<?php }elseif ($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['type'] == 'Pick') { ?>
				<div class="form-group">
				<label><?=$sessionpatient['customer_info']['customer'][0][$field_sorted][0]['label']?>:</label>
								<?php
								$choices_array = explode(',', $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['choices']);
								?>
								<select name="<?=$field_sorted?>">
								<?php
								foreach ($choices_array as $ignore => $choice_item) {
									echo '
									<option value="'.trim($choice_item).'"';
									if (isset($GLOBALS['vars'][$field_sorted]) && $GLOBALS['vars'][$field_sorted] == trim($choice_item)) {
										echo ' SELECTED';
									}
									elseif (isset($GLOBALS['customer_info']->customer->$field_sorted->data) && (string)$GLOBALS['customer_info']->customer->$field_sorted->data == trim($choice_item)) {
										echo ' SELECTED';
									}
									echo '>'.$choice_item.'</option>';
								}
								?>
								</select>
				</div>
				<?php }elseif ($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['type'] == 'List') { ?>
				<div class="form-group">
				<label><?=$sessionpatient['customer_info']['customer'][0][$field_sorted][0]['label']?>:</label>
								<?php
								$choices_array = explode(',', $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['choices']);
								if (!empty($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data'])) {
									$raw_returned_choices = explode(',', $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data']);
								}
								else {
									$raw_returned_choices = array();
								}
								foreach ($raw_returned_choices as $returned_item) {
									$returned_choices[] = trim($returned_item);
								}
								foreach ($choices_array as $ignore => $choice_item) {
									echo '
									<input type="checkbox" name="'.$field_sorted.'[]" value="'.trim($choice_item).'"';
									if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
										echo ' CHECKED';
									}
									echo ' border="0"> '.trim($choice_item).'<br>';
								}
								?>
				</div>
				<?php 		}
					}
				}
			} ?>
					<div class="form-group">
 <input type="button" value="Submit order" class="btn btn-primary buttondflt" name="myinfo_submit" style="margin:15px 0px" onclick="redeemPointsConfirmation()">

				</div>	
		</form>
</div></div></div>
	<?php	}}
		else { ?>
			<div>
			<div class="content_left breathing_room">
			<form class=" form_size" action="<?=Staff_Name?>rewards/redeem/" method="POST" name="confirm_form">
			<input type="hidden" name="action" value="redeem_reward">
			<table cellspacing="0" cellpadding="4" border="0" width="100%" style="width:100%;" class="pad-l-25">
			<tr>
			<td>
			
			<span class="normal content_left">This is the item you have selected as a reward.</span>
			</td>
			</tr>
			<tr>
			<td>
			<table cellpadding="0" cellspacing="10" border="0" class="reward_table" align="center" style="width:100%;" class="pad-l-25">
			
			<tr>
			<td>
			<div class="show_image_confirm">
				<?php
				$uploadFolder = "rewards/".$rewards['site_name'];
				$uploadPath = WWW_ROOT .'img/'. $uploadFolder;
			if (file_exists($uploadPath.'/'.$rewards['description'])) {
				?>

				<img src="<?=$this->webroot.'img/rewards/'.$rewards['site_name'].'/'.$rewards['description']?>" width="240px" height="160px">
				<?php
			}else{
				if (file_exists($uploadPath."/noimage.jpg")) {
				?>

				<img src="<?=$this->webroot.'img/rewards/'.$rewards['site_name'].'/noimage.jpg';?>" width="240px" height="160px">
				<?php
				}else{
					?>
					<img src="http://redeem.integr8ideas.com/wp-admin/rewardimages/<?=$rewards['description']?>" alt="" width="240px" height="160px">
					<?php
				}
			}
			?></div>
			</td>
			</tr>
			<tr>
				<td itemprop="name" align="left" valign="top" class="reward_title test_point"><?=$rewards['description']?></td>
			</tr>
			<tr>
			<td align="right" valign="top" class="reward_amount points">
				<div class="points">
				<?php
				if ($rewards['points'] > 0) { ?>
					<span class="large">-<?=number_format($rewards['points'])?></span>
					<?php
				} elseif ($rewards['points'] == 0) {
					?>
					<span class="large"><?=number_format($rewards['points'])?></span>
					<?php
				} else {
				?>
					<span class="large">+<?=number_format($rewards['points'])?></span>
					<?php
				}
				echo (intval($rewards['points']) == 1) ? 'point' : 'points';
			 ?>
             </div>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			<td align="center">
                            <input type="button" value="Redeem Now" class="btn btn-primary buttondflt" name="myinfo_submit" style="margin:15px 0px" onclick="redeemPointsConfirmation()">
			</td>
			</tr>
			</table>
			</form>
			</div>
			</div>
			<?php }?>
	       </div>
      
        </div>
      <?php if (!empty($sessionpatient['customer_info'])) { echo $this->element('left_sidebar'); } ?></div>

<script type="text/javascript">
$(document).ready(function() {  
        $('#redeemreward_form').validate({
		rules: {
			first_name: "required",
			last_name: "required",

			
			email: {
				required: true,
				email: true
			},
			phone: {
				number: true, minlength: 10 ,maxlength:10
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
		
			postal_code: {
				 minlength: 8 ,maxlength:8
			}
			
		},
        
        // Specify the validation error messages
		messages: {
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			
			email: "Please enter a valid email address",
			phone: {
				number: "Please enter a valid phone number",
				minlength: "Phone Number must be 10 characters long"
			},
			
			date_year: {
			required: "Please select date of year",
			lessThanCurrentDate : "You can not select future date."
			},
			date_month: {
			required: "Please select date of month",
			lessThanCurrentDate : "You can not select future date."
			},
			date_day: {
				required: "Please select date of day",
				lessThanCurrentDate : "You can not select future date."
			},
			
			postal_code: {
				
				minlength: "Zip code must be 8 characters long"
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



    function redeemPointsConfirmation(){
           
                var r=confirm("Do you want to proceed?.");
                if(r){
                        document.forms["confirm_form"].submit();
                } 
            
            

    }
    function getcity(){

var state=$('#state').val();

  $.ajax({
	  type:"POST",
	  data:"state="+state,
	  url:"<?=Staff_Name?>rewards/getcity/",
	  success:function(result){
	  $('#city').html(result);
  }});



 
}
</script>
