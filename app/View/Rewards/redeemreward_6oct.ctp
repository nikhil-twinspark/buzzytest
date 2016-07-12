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
<?php $sessionpatient = $this->Session->read('patient');


 ?>
<div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
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
			<form class=" form_size" action="<?=Staff_Name?>rewards/redeem/" method="POST" name="confirm_form" id="confirm_form">
			<input type="hidden" name="action" value="redeem_reward">
			<input type="hidden" name="id" value="<?=$sessionpatient['customer_info']['user']['id']?>">
			<input type="hidden" name="which_reward_id" value="<?=$rewards['Reward']['id']?>">
			<input type="hidden" name="which_reward_description" value="<?=$rewards['Reward']['description']?>">
			<input type="hidden" name="which_reward_level" value="<?=$rewards['Reward']['points']?>">
			
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
				
			
				?>
				<div class="content_left social_buttons">
				<div class="g-plusone" data-annotation="inline" data-width="280"></div><br>
				<div class="fb-like" data-send="true" data-width="280" data-show-faces="false"></div><br>
				</div>
				<img src="<?=$rewards['Reward']['imagepath']?>" width="240px" height="160px">
				<?php
			
			?></div>
			</td>
			</tr>
			<tr>
				<td itemprop="name" align="left" valign="top" class="reward_title test_point"><?=$rewards['Reward']['description']?></td>
			</tr>
			<tr>
			<td align="right" valign="top" class="reward_amount">
				<div class="points">
				<?php
				if ($rewards['Reward']['points'] > 0) { ?>
					<span class="large">-<?=number_format($rewards['Reward']['points'])?></span>
					<?php
				} elseif ($rewards['Reward']['points'] == 0) {
					?>
					<span class="large"><?=number_format($rewards['Reward']['points'])?></span>
					<?php
				} else {
				?>
					<span class="large">+<?=number_format($rewards['Reward']['points'])?></span>
					<?php
				}
				echo (intval($rewards['Reward']['points']) == 1) ? 'point' : 'points';
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
			
			<?php 
               
                    if($sessionpatient['is_buzzydoc']==1){
                        if($sessionpatient['customer_info']['ClinicUser']['local_points']>=$sessionpatient['customer_info']['user']['points']){
                    $current_balance = $sessionpatient['customer_info']['ClinicUser']['local_points'];
                        }else{
                        $current_balance = $sessionpatient['customer_info']['user']['points'];    
                        }
                    }else{
                     $current_balance = $sessionpatient['customer_info']['ClinicUser']['local_points'];   
                    }
                
if($current_balance>=$rewards['Reward']['points']){ ?>
			<span class="normal content_left"><strong>Rewards will be shipped to your office. Please make sure this is your correct information. Rewards are shipped monthly.</span>
			<?php }else{ ?>
			<span class="normal content_left" style="color:red">Insufficient balance.You can't redeem this reward.<strong></span>
			<?php } ?>
			</td>
			</tr>
			</table>
			<?php if($current_balance>=$rewards['Reward']['points']){ ?>
			         <div class="detail ">
         <?php
			
			
							
							if($sessionpatient['is_buzzydoc']==1){	?>
 <div class="form-group">
              <label>Redeem From:</label>
									
										<select class="form-control" name="set_account_type" id="set_account_type" >
									<option value="1">Global Points (<?=$sessionpatient['customer_info']['user']['points']?>)</option>
									
                                                                        <option value="0">Local Points (<?=$sessionpatient['customer_info']['ClinicUser']['local_points']?>)</option>

  				</select>
				</div>	
<?php }else{ ?>
<input type="hidden" id="set_account_type" name="set_account_type" value="0">
<?php } ?>

               <div class="form-group">
               <label >Card Number:</label>
              <?=$sessionpatient['customer_info']['ClinicUser']['card_number']?>
								<input	class="form-control" type="hidden" name="card_number" id="card_number" value="<?=$sessionpatient['customer_info']['ClinicUser']['card_number']?>" maxlength="255">
              </div>
            
				 <div class="form-group" id="email_field">
               <label >
        <?php
								
							
									$email = $sessionpatient['customer_info']['user']['email'];
									?>
									Email<span style="color:red;">*</span>:</label>
									<input 	class="form-control" type="text" name="email" value="<?=$email?>" maxlength="255" id='email'>

          </div>
			
               <div class="form-group">
               <label >First Name<span style="color:red;">*</span></label>
             <?php
									$first_name = $sessionpatient['customer_info']['user']['first_name'];
								
							
								
								?>
	<input	class="form-control" type="text" name="first_name" value="<?=$first_name?>" maxlength="255" id='first_name' >
				</div>
          
              <div class="form-group">
               <label >Last Name<span style="color:red;">*</span></label>
             <?php
							
									$last_name = $sessionpatient['customer_info']['user']['last_name'];
								
								?>
<input 	class="form-control" type="text" name="last_name" value="<?=$last_name?>" maxlength="255" id="last_name" >
				</div>
            <?php 
            foreach($sessionpatient['customer_info']['ProfileField'] as $field){
				if($field['profile_field']=='phone'){
					$phone=$field['ProfileFieldUser']['value']; ?>
              <div class="form-group">
               <label >Phone:</label>
				<input 	class="form-control"  type="text" id='phone' name="phone" value="<?=$phone?>" maxlength="10" >
				</div>
				<?php 
				}
				
				 ?>		
				
									<?php if($field['profile_field']=='street1'){
					$street1=$field['ProfileFieldUser']['value'];
									?>
									 <div class="form-group">
               <label>Address:</label>
									<input 	class="form-control" type="text"	name="street1" value="<?=$street1?>" maxlength="255" >
				</div>		
				<?php }
				if($field['profile_field']=='street2'){
					$street2=$field['ProfileFieldUser']['value'];
				 ?>		
				 <div class="form-group">
         
									<input 	class="form-control" type="text" name="street2" value="<?=$street2?>" maxlength="255">
				</div>		
				<?php }
				
				
				if($field['profile_field']=='state'){
					$state=$field['ProfileFieldUser']['value'];
				 ?>		
				 <div class="form-group">
              <label>State:</label>
									
										<select class="form-control" name="state" id="state" onchange="getcity();">
									<option value="">Select State</option>
									<?php foreach($states as $st){ ?>
<option value="<?=$st['State']['state']?>" <?php if($state==$st['State']['state']){ echo "selected"; } ?>><?=$st['State']['state']?></option>
<?php } ?>
  				</select>
				</div>				
				<?php }
				if($field['profile_field']=='city'){
					$city_val=$field['ProfileFieldUser']['value']; ?>		
				 <div class="form-group">
              <label>City:</label>
              
									<select class="form-control" name="city" id="city">
									<option value="">Select City</option>
<?php foreach($city as $ct){ ?>
<option value="<?=$ct['City']['city']?>" <?php if($city_val==$ct['City']['city']){ echo "selected"; } ?>><?=$ct['City']['city']?></option>
<?php } ?>
  				</select>
									
				</div>		
				<?php }
				if($field['profile_field']=='postal_code'){
					$postal_code=$field['ProfileFieldUser']['value']; ?>
				 <div class="form-group">
               <label >Zip:</label><span style='color:red;font-style:italic;padding-left:50px;' id="error_div_msg_postal_code" ></span>
									
									<input 	class="form-control" type="text" name="postal_code" id="postal_code" value="<?=$postal_code?>" maxlength="5">
									
				</div>
				<?php }
				}
				
			 ?>
					<div class="form-group">
 <input type="submit" value="Submit order" class="btn btn-primary buttondflt" name="myinfo_submit" style="margin:15px 0px"><div id="progress_red_prd_div_page"></div>

				</div>	
				
		</form>
</div>
<?php } ?>
</div></div>
	<?php	}
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
				$uploadFolder = "rewards/".$rewards['Reward']['api_user'];
				$uploadPath = WWW_ROOT .'img/'. $uploadFolder;
			if (file_exists($uploadPath.'/'.$rewards['Reward']['description'])) {
				?>

				<img src="<?=$this->webroot.'img/rewards/'.$rewards['Reward']['api_user'].'/'.$rewards['Reward']['description']?>" width="240px" height="160px">
				<?php
			}else{
				if (file_exists($uploadPath."/noimage.jpg")) {
				?>

				<img src="<?=$this->webroot.'img/rewards/'.$rewards['Reward']['api_user'].'/noimage.jpg';?>" width="240px" height="160px">
				<?php
				}else{
					?>
					<img src="http://redeem.integr8ideas.com/wp-admin/rewardimages/<?=$rewards['Reward']['description']?>" alt="" width="240px" height="160px">
					<?php
				}
			}
			?></div>
			</td>
			</tr>
			<tr>
				<td itemprop="name" align="left" valign="top" class="reward_title test_point"><?=$rewards['Reward']['description']?></td>
			</tr>
			<tr>
			<td align="right" valign="top" class="reward_amount points">
				<div class="points">
				<?php
				if ($rewards['Reward']['points'] > 0) { ?>
					<span class="large">-<?=number_format($rewards['Reward']['points'])?></span>
					<?php
				} elseif ($rewards['Reward']['points'] == 0) {
					?>
					<span class="large"><?=number_format($rewards['Reward']['points'])?></span>
					<?php
				} else {
				?>
					<span class="large">+<?=number_format($rewards['Reward']['points'])?></span>
					<?php
				}
				echo (intval($rewards['Reward']['points']) == 1) ? 'point' : 'points';
			 ?>
             </div>
			</td>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			<td align="center">
                            <input type="submit" value="Redeem Now" class="btn btn-primary buttondflt" name="myinfo_submit" style="margin:15px 0px" >
			</td>
			</tr>
			</table>
			</form>
			</div>
			</div>
			<?php }?>
	       </div>
      
        </div>
      <?php if (!empty($sessionpatient['customer_info'])) { echo $this->element('left_sidebar'); }else{ ?><div style="padding: 176px;background-color:#000">
           
               </div> <?php } ?></div>
   	<script>
		

$(document).ready(function() {  
        $('#confirm_form').validate({
		rules: {
			first_name: "required",
			last_name: "required",

			email: {
				required: true,
				email: true
			},
			phone: {
				number: true, minlength: 7 ,maxlength:10
			}, 
			
			
			postal_code: {
				 minlength: 5 ,maxlength:5
			},
			gender: {
				required: true
			}
			
		},
        
        // Specify the validation error messages
		messages: {
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
					
			email: "Please enter a valid email address",
			phone: {
				number: "Please enter a valid phone number",
				minlength: "Phone Number must be 7 to 10 characters long"
			},
			
			
			postal_code: {
				
				minlength: "Zip code must be 5 characters long"
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
            var acnttype=$('#set_account_type').val();
            if(acnttype==1){

            if(<?=$sessionpatient['customer_info']['user']['points']?>>=<?=$rewards['Reward']['points']?>){
            }else{
            alert('Insufficient balance.Try with your local points.');
            $("#set_account_type").focus();
            return false;
            }
            }else{
            if(<?=$sessionpatient['customer_info']['ClinicUser']['local_points']?>>=<?=$rewards['Reward']['points']?>){
            }else{
            alert('Insufficient balance.Try with your global points.');
            $("#set_account_type").focus();
            return false;
            }
            }
            form.submit();
            $('input[type="submit"]').attr('disabled','disabled');
            $('#progress_red_prd_div_page').html("<img src='/img/loading.gif' > wait...");
            
        }  
            
         });
});

	</script>
<script type="text/javascript">

    function changeErrorMessage(ptr){
         $("#"+ptr).css('background-color','');
         $("#error_div_msg_"+ptr).html("");

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
