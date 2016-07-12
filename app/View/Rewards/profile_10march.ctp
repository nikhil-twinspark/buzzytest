<style type="text/css">
.mobilebanner {padding-bottom: 20px; }
@media (max-width: 768px) and (min-width: 100px){
	.leftcont { display:none;}	
}
</style>
<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
         <div id="logo"><a href="#">
         <img src="<?=$sessionpatient['patient_logo']?>" width="246" height="88" alt="Pure Smiles" title="Pure Smiles" />
        </a></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image('reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

       
        
          </div>
      <div class=" clearfix">
       
        
        <div class="col-lg-9 col-xs-12 rightcont">
        
          <div class="settingArea clearfix">
            
            <ul class="col-md-12">
               
               <li class="col-md-7 col-xs-12" id="liprofile">
               <h2>YOUR profile</h2>
               <form action="<?=Staff_Name?>rewards/editprofile/" method="POST" name="new_account_form" class="side_padding">
	<?php if (!empty($sessionpatient['preferences']['customer_fields_order'])) {
		foreach ($sessionpatient['preferences']['customer_fields_order'] as $field_sorted) {
			if (in_array($field_sorted, $sessionpatient['preferences']['fields_to_show'])
				|| $field_sorted == 'card_number'
				|| $field_sorted == 'customer_password'){
						// Normal fields:
				if (strpos($field_sorted, 'custom_field_') === false) {
					if ($field_sorted == 'card_number') { ?>
                <p> <strong>CARD NUMBER:</strong> <?=$sessionpatient['customer_info']['customer'][0]['card_number']?></p>
                <?php 
					}
					elseif ($field_sorted == 'first_name' && $sessionpatient['customer_info']['customer'][0]['first_name']!='') { ?>
                 <p> <strong>NAME:</strong> <?=$sessionpatient['customer_info']['customer'][0]['first_name']?> <?=$sessionpatient['customer_info']['customer'][0]['last_name']?></p>
                 <?php }
					elseif ($field_sorted == 'phone' && $sessionpatient['customer_info']['customer'][0]['phone']!='') { ?>
                 <p> <strong>PHONE:</strong> <?=$sessionpatient['customer_info']['customer'][0]['phone']?> </p>
                 <?php
					}
					elseif ($field_sorted == 'email' && $sessionpatient['customer_info']['customer'][0]['email']!='') { ?>
					<p> <strong>EMAIL:</strong> <?=$sessionpatient['customer_info']['customer'][0]['email']?></p>
					<?php
					}
					elseif ($field_sorted == 'custom_date' && $sessionpatient['customer_info']['customer'][0]['custom_date']!='0000-00-00') {
					?>
					<p> <strong>Date Of Birth:</strong> <?=$sessionpatient['customer_info']['customer'][0]['custom_date']?></p>
				<?php
					}
					elseif (($field_sorted == 'address1' && $sessionpatient['customer_info']['customer'][0]['address1']!='') || ($field_sorted == 'street1' && $sessionpatient['customer_info']['customer'][0]['street1']!='' )) {
						?>		
					
                 <p><strong>ADDRESS:</strong> <?=$sessionpatient['customer_info']['customer'][0]['street1']?></p>
                <?php }
					elseif (($field_sorted == 'address2' && $sessionpatient['customer_info']['customer'][0]['address2']!='')|| ($field_sorted == 'street2' && $sessionpatient['customer_info']['customer'][0]['street2']!='')) {
						?>
				<p><strong>ADDRESS 1:</strong> <?=$sessionpatient['customer_info']['customer'][0]['street2']?></p>
				<?php
					}
					elseif ($field_sorted == 'city' && $sessionpatient['customer_info']['customer'][0]['city']!='') {
						?>
						<p><strong>CITY:</strong> <?=$sessionpatient['customer_info']['customer'][0]['city']?></p>
							<?php
					}
					elseif ($field_sorted == 'state' && $sessionpatient['customer_info']['customer'][0]['state']!='') {
						?>
						<p><strong>STATE:</strong> <?=$sessionpatient['customer_info']['customer'][0]['state']?></p>
						<?php
					}
					elseif ($field_sorted == 'zip' && $sessionpatient['customer_info']['customer'][0]['postal_code']!='') {
						?>
						<p><strong>ZIP:</strong> <?=$sessionpatient['customer_info']['customer'][0]['postal_code']?></p>
						<?php
					}
					elseif ($field_sorted == 'country' && $sessionpatient['customer_info']['customer'][0]['country']!='') {
						?>
						<p><strong>COUNTRY:</strong> <?=$sessionpatient['customer_info']['customer'][0]['country']?></p>
						<?php
					}
				}
						else { // Custom fields:
						//echo "<pre>";print_r($sessionpatient);
							if ($sessionpatient['customer_info']['customer'][0][$field_sorted][0]['type'] == 'Text' && $sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data']!='') {
								?>
								<p><strong><?=$sessionpatient['customer_info']['customer'][0][$field_sorted][0]['label']?>:</strong> 
								<?=$sessionpatient['customer_info']['customer'][0][$field_sorted][0]['data']?></p>
									<?php	}
					}
				} } } ?>
                <input class="btn btn-primary buttondflt"	type="submit" value="Edit Profile" name="myinfo_submit">
                </form>
               </li>
               
               
               
               <li class="col-md-7 col-xs-12" id="lichangepass">
               <h2>CHANGE PASSWORD</h2><?php echo $this->Session->flash(); ?>
                <form action="<?=Staff_Name?>rewards/profile/" method="POST" name="pass_form" onsubmit="return match()">
							<input type="hidden" name="card_number" value="<?=$sessionpatient['customer_info']['customer'][0]['card_number']?>">
								<input type="hidden" name="action" value="passet">
								
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">CURRENT PASSWORD:</label>
                   <input class="form-control" type="password" name="password" required id="password" value="">
                   </div>
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">NEW PASSWORD:</label>
                    <input class="form-control" type="password" name="new_password" required id="new_password" value="">
                   </div>
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">CONFIRM NEW PASSWORD:</label>
                    <input class="form-control" type="password" name="new_password2" id="new_password2" value="">
                   </div>
             
                <input type="submit" value="SUBMIT" class="btn btn-primary buttondflt" name="login_submit">
                </form>
               </li>
               <li class="col-md-7 col-xs-12" id="linotification">
               <h2>NOTIFICATIONS</h2>
               <form action="<?=Staff_Name?>rewards/profile/" method="POST" name="notification_form" >
               <input type="hidden" name="action" value="notification">
               <input type="hidden" name="id" value="<?php if(isset($Notifications['Notifications']['id'])){ echo $Notifications['Notifications']['id']; }else{ echo '';} ?>">
                <input type="hidden" name="chkemail" id="chkemail" value="<?=$sessionpatient['customer_info']['customer'][0]['email']?>">
                 <div class="form-group notification clearfix">
                      <input type="checkbox" class="form-control" id="reward_challenges" name="reward_challenges" placeholder="Email" <?php if(isset($Notifications['Notifications']['reward_challenges']) && $Notifications['Notifications']['reward_challenges']==1){ echo "checked";} ?>>
                      <label class="col-sm-2 control-label">INFORM ME ABOUT NEW REWARDS AND CHALLENGES
                                           </label>
                   </div>
                     <div class="form-group notification clearfix">
                      <input type="checkbox" class="form-control" id="order_status" name="order_status" placeholder="Email" <?php if(isset($Notifications['Notifications']['order_status']) && $Notifications['Notifications']['order_status']==1){ echo "checked";} ?>>
                      <label class="col-sm-2 control-label">INFORM ME WHEN MY ORDER STATUS CHANGES
                                           </label>
                   </div>
                     <div class="form-group notification clearfix">
                      <input type="checkbox" class="form-control" id="earn_points" name="earn_points" placeholder="Email" <?php if(isset($Notifications['Notifications']['earn_points']) && $Notifications['Notifications']['earn_points']==1){ echo "checked";} ?>>
                      <label class="col-sm-2 control-label">INFORM ME WHEN I EARN POINTS
                                           </label>
                   </div>
                <input type="button" value="UPDATE" class="btn btn-primary buttondflt" name="login_submit" onclick="checkemail();">
                </form>
               </li>
               <li class="col-md-7 col-xs-12" id="liorderstatus">
            <div class="orderstatus  clearfix">
               <h2>YOUR ORDER STATUS</h2>
               <?php
         function monthDropdown($name="month", $selected=null)
{
        $dd = '<select name="'.$name.'" id="'.$name.'" onchange="this.form.submit()">';

        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;
		
        for ($i = 1; $i <= 12; $i++)
        {
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected)
                {
                        $dd .= ' selected';
                }
                /*** get the month ***/
                $mon = date("F", mktime(0, 0, 0, $i+1, 0, 0, 0));
                $dd .= '>'.$mon.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}
?>
               <form class="form_size Feeddropdown" action="<?=Staff_Name?>rewards/profile/#liorderstatus" method="POST" id="dateform">
               <input type="hidden" name="action" value="month_change">
              <span class="dropIcon"></span>
             <?php
             
              echo monthDropdown('my_dropdown', $selectedmonth); ?>
             </form>
              </div>
              <div class="row clearfix">
              <?php
              if(count($RedeemRewards)>0){
               foreach($RedeemRewards as $redeem){ ?>
                  <div class="col-md-6">
                <p> <strong>ORDER NUMBER:</strong> <?=$redeem['RedeemRewards']['TransId']?></p>
                 <p> <strong>DATE REDEEMED:</strong> <?=$redeem['RedeemRewards']['TransDate']?></p>
                 <p><strong>DESCRIPTION:</strong> <?=$redeem['RedeemRewards']['Reward']?></p>
                <p> <strong>STATUS:</strong> <?php if($redeem['RedeemRewards']['Status']=='New'){ echo "In Office";}elseif($redeem['RedeemRewards']['Status']=='Ordered_Shipped'){ echo "Ordered/Shipped";}else{ echo "Redeemed"; } ?></p>
                   <div><br></div>
                   </div>
                   
                 <?php }}else{ ?>
                  <div class="col-md-6">
                <p>No Reward Redeemed!!</p>
                
                   </div>
                   <?php } ?>
                </div>
               
               </li>
               <li class="col-md-7 col-xs-12" id="lirefer">
               <h2>INVITE MORE FRIENDS</h2>
                 <table class="table clearfix">
                  <tr>
                     <td><strong>INVITED</strong></td>
                     <td><strong>DATE</strong></td>
                     <td><strong>STATUS</strong></td>
                  </tr>
                  <?php if(count($Refers)>0){
					  foreach($Refers as $ref){ ?>
                   <tr>
                     <td><?=$ref['Refers']['email']?></td>
                     <td><?=$ref['Refers']['refdate']?></td>
                     <td><?php if($ref['Refers']['status']==0){ echo "PENDING"; }else{ echo "ACCEPTED"; } ?></td>
                  </tr>
                 <?php } }else{ ?>
                 <tr>
                     <td colspan="3">No Refferal Found!!</td>
                     
                  </tr>
                  <?php } ?>
                 </table>
                <a href="<?=Staff_Name?>rewards/refer/"><button class="btn btn-primary buttondflt">REFER TO FRIEND ></button></a>
               </li>
               
               
               
               
               <li class="col-md-6 col-xs-12" id="liwish">
               <h2>YOUR WISHLIST</h2>
               <div class="row">
               
               <?php
				
				
				 if(isset($sessionpatient['customer_info']['campaigns'])){ $current_balance=$sessionpatient['customer_info']['campaigns'][0]['campaign'][0]['balance']; }else{ $current_balance='0'; }
						if(!empty($WishLists)){
						foreach($WishLists as $wishlist){
						?>
                        <div style="position:relative; overflow:hidden;" class="col-lg-4 col-md-4 col-sm-6 col-xs-6 profile">
						<div class="remove" onClick="document.remove_form_<?=$wishlist['id']?>.submit();"><?php echo $this->html->image('reward_imges/remove_btn.png',array('class'=>'hand-icon'));?></div>
						<?php
						$need_more=$wishlist['level']-$current_balance;
							if (intval($sessionpatient['current_campaign_balance']) >= intval($wishlist['level'])) {
								?>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 settinproducBox hand-icon" onClick="document.reward_form_<?=$wishlist['id']?>.submit();">
								
								<?php
							} else {
								?>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 settinproducBox">
									<?php
							}
						
						?>
						
              
						<?php
					    $uploadFolder = "rewards/".$sessionpatient['api_user'];
                        $uploadPath = WWW_ROOT .'img/'. $uploadFolder;
						if ($sessionpatient['current_campaign_type'] == 'points') {
							?>
                            <p><?=$wishlist['description']?></p>
                            <?php
                            if (file_exists($uploadPath.'/'.$wishlist['description'])) {
							
								?>
								<img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user'].'/'.$wishlist['description']?>" width="131" height="88">
								<?php
							}else{
								if (file_exists($uploadPath."/noimage.jpg")) {
									?><img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user']?>/noimage.jpg" width="131" height="88">
									<?php
								}else{
									echo '<img src="http://redeem.integr8ideas.com/wp-admin/rewardimages/'.$wishlist['description'].'.jpg" alt="" width="131" height="88">';
								}
							} ?>
                            <h3><?=$wishlist['level']?> points<br>
                            <?php if($need_more>0){ ?>
                            <span>You need <?=$need_more?> more points.</span>
                            <?php } ?>
                             <span class="headTopCorner"></span>
                             <span class="headrightcorner"></span>
                             </h3>
                             
                          </div>
                           </div>
                 <form action="<?=Staff_Name?>rewards/redeemreward/<?=$wishlist['id']?>" method="POST" name="reward_form_<?=$wishlist['id']?>">
						<input type="hidden" name="action" value="confirm_redeem">
						<input type="hidden" name="which_reward_id" value="<?=$wishlist['id']?>">
						<input type="hidden" name="which_reward_description" value="<?=urlencode($wishlist['description'])?>">
						<input type="hidden" name="which_reward_level" value="<?=$wishlist['level']?>">
						<input type="hidden" name="which_campaign" value="<?=$sessionpatient['preferences']['campaigns_to_show'][0]?>">
						<input type="hidden" name="which_campaign_type" value="<?=$sessionpatient['campaign_rewards']['campaign'][0]['type']?>">
						 </form>
                 <form action="<?=Staff_Name?>rewards/profile/#liwish" method="POST" name="remove_form_<?=$wishlist['id']?>">
						<input type="hidden" name="action" value="remove_wishlist">
						<input type="hidden" name="which_reward_id" value="<?=$wishlist['id']?>">
						</form>
                 <?php } ?>
                 
						<?php }}else{ ?>
						<div class="col-lg-12 settinproducBox">
							No Item In Wishlist
							</div>
							<?php } ?>
                     
                         
            </div>
               </li>
              </ul>
       
      </div>
        </div>
         <?php echo $this->element('left_sidebar'); ?>
      </div>
      	<script>

function match(){
		var len=$('#new_password').val().length;
		var curpass=$("#password").val();
		var cur_pass='<?=$sessionpatient['var']['patient_password']?>';
		
		if(cur_pass!=curpass){
		alert('Current Password Invalid');
		return false;
		}
		if(len>0){
	    if ($("#new_password").val() === $("#new_password2").val()){
			if(len<5 && $("#new_password").val()!=''){
			$('#paserr').text('password atleast 6 characters long.');
			}
			else{
			return true;
			}
    } else {
        alert('Password do not match.');
        return false;
    }
    }
    else{
    alert('Plz Enter Password.');
    return false;
    }
    
}



function checkemail(){
	var emailchk=$("#chkemail").val();
	if(emailchk==''){
		alert('Before Notification Setting Insert Email ID in your profile.');
		return false;
	}else{
	document.notification_form.submit();
	}
}
	</script>
   
