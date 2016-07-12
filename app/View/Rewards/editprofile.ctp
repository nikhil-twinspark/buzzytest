<style type="text/css">
.form-group .col-xs-4 { padding-left:0;}
.detail {padding: 15px 15px;
border-top: 1px solid #333;
margin-top: 10px;}
.form-control { height:34px;}

@media  (min-width: 100px) and (max-width: 767px){
.leftcont { display:none;}
.settingArea { margin: -10px 0 0px 0; padding-top: 60px;
}
}
</style>
<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
         <div id="logo">
        <?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?>
         
        </div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

       
        
          </div>
<div class=" clearfix">
        <div class="col-lg-9 col-xs-12 rightcont">
          <div class="settingArea clearfix">
<?php 
		
			if (isset($sessionpatient['customer_info'])) {
?>
				<div class="grid_100 left content_selected">
			
				<form action="<?=Staff_Name?>rewards/profile/" method="POST" name="new_account_form" id="new_account_form" enctype="multipart/form-data" >
				<div class="content_left breathing_room">
				<div class="right content_center myinfo_right">
				<div class="content_center">
				 <div class="row">
						<?php
						$profilePath = CDN .'img/profile/'.$sessionpatient['api_user'].'/'. $sessionpatient['customer_info']['ClinicUser']['card_number'];
						$profilePath1 = AWS_server.AWS_BUCKET.'/img/profile/'.$sessionpatient['api_user'].'/'. $sessionpatient['customer_info']['ClinicUser']['card_number'];	
                                            if (file_exists($profilePath)) { ?>
								
                                	<div style="margin-left:15px;"><?php echo $this->Session->flash(); ?></div>
                                    <div class="profile_image col-lg-4 col-sm-4 col-md-4 col-xs-12">
                                   
                                        <span class="font-bold">Profile Image</span>
                                        <img src="<?=$profilePath1?>" class='img-responsive' width="200px" height="200px"></div>
								
							<?php }else if (!empty($sessionpatient['customer_info']['user']['profile_img_url'])) {
$profilePath2 = S3Path .$sessionpatient['customer_info']['user']['profile_img_url'];
 ?>
								
                                	<div style="margin-left:15px;"><?php echo $this->Session->flash(); ?></div>
                                    <div class="profile_image col-lg-4 col-sm-4 col-md-4 col-xs-12">
                                   
                                        <span class="font-bold">Profile Image</span>
                                        <img src="<?=$profilePath2?>" class='img-responsive' width="200px" height="200px"></div>
								
							<?php }else{ ?>
							  <div class="profile_image col-lg-4 col-sm-12 col-md-4 col-xs-12">
							  <?php echo $this->Session->flash(); ?>
                                        <span class="font-bold">Profile Image</span>
                                        <img src="<?php echo CDN; ?>img/notfound_pic.jpg" class='img-responsive' width="200px" height="200px"></div>
								
							<?php } ?>
					<div align="center" class="col-lg-8 col-sm-12 col-md-8 col-xs-12 choose_file">
					<table>
						<tr>
							<td>
							<?php echo $this->Form->input('profile_image', array('type' => 'file','label'=>false,'div'=>false, 'class'=>'hand-icon',"onchange"=>"checkimg('profile_image','plu');"));  ?>
							<a onclick="removeimg('profile_image','plu');" class="" id="plu"></a>	
							</td>
						</tr>
						
					</table>
				</div>
                </div>
				</div>
				</div>
				<input type="hidden" name="action" value="record_myinfo">
				<?php //print_r($sessionpatient['customer_info']['ClinicUser']);die; ?>
				<input type="hidden" name="id" id="id" value="<?=$sessionpatient['customer_info']['ClinicUser']['user_id']?>">
				<input type="hidden" name="card_number" id="card_number" value="<?=$sessionpatient['customer_info']['ClinicUser']['card_number']?>">
				<input type="hidden" name="selfcheck" id="selfcheck" value="<?=$selfcheckin?>"> 
                                <div class="detail ">
				<?php 
			
					
									?>
									<div class="form-group">
               <label >Card Number:</label>
              <?=$sessionpatient['customer_info']['ClinicUser']['card_number']?>
								<input	class="form-control" type="hidden" name="card_number" value="<?=$sessionpatient['customer_info']['ClinicUser']['card_number']?>" maxlength="255">
              </div>
									
									<?php
								
							
									if (!empty($sessionpatient['customer_info']['user']['custom_date'])) {
										$date_array = explode ('-', $sessionpatient['customer_info']['user']['custom_date']);
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
				 <div class="form-group" style="margin-bottom:0;">
               <label><span style="color:red;">*</span>Date Of Birth: <span id='edit_error_msg_date_year' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
				
				</div>				
				<div class="form-group clearfix last-child">
				 <div class="col-xs-4 selctboxstate clearfix"> 
            
				<select name="date_year" id="date_year" size="1" class="form-control"  onchange="return checkage();">
									<option value="">Select Year</option>
									<?php
									$curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) {
										if ($y == $year) {
											echo'
											<option value="'.$y.'" selected="selected">'.$y.'</option>';
										} else {
											echo'
											<option value="'.$y.'">'.$y.'</option>';
										}
									} ?>
									
				</select>
				</div>				
				 <div class="col-xs-4 selctboxstate clearfix"> 
             
				<select name="date_month" id="date_month" size="1"  class="form-control"  onchange="return checkage();">
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
             
				<select name="date_day" id="date_day" size="1" class="form-control"  onchange="return checkage();">
									<option value="">Select Day</option>
									<?php
									for ($d = 1; $d <= 31; $d += 1) {
										if ($d == $day) {
											echo'
											<option value="'.$d.'" selected="selected">'.$d.'</option>';
										} else {
											echo'
											<option value="'.$d.'">'.$d.'</option>';
										}
									}
									?>
									</select>
				</div>	
				</div>	
				<?php 
				$date1_chd=$sessionpatient['customer_info']['user']['custom_date'];
				
						$date2_chd = date('Y-m-d');
						
						$date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
						$diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
						$years_chd = floor($diff_chd / (365*60*60*24));
				?>
				
				<div class="form-group" id="pemail">
				<?php if($years_chd<18){ ?>
				
               <label ><span style="color:red;">*</span>Email-Id:<span id='edit_error_msg_parents_email' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
	<input	class="form-control" type="text" name="parents_email" id="parents_email" value="<?=$sessionpatient['customer_info']['user']['email']?>" maxlength="50">
             
              <?php } ?>
               </div>	

                            <div class="form-group" id="email_field">
<label>
                   <?php

                   if($years_chd<18){
                           $email = $sessionpatient['customer_info']['user']['parents_email'];
                           ?>
                           <span style="color:red;">*</span>Username:</label>
                           <input class="form-control" type="text" name="aemail" value="<?=$email?>" maxlength="50" id='aemail'>
                           <?php
                   }
                   else {
                           $email = $sessionpatient['customer_info']['user']['email'];
                           ?>
                           <span style="color:red;">*</span>Email:</label><span id='edit_error_msg_email' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
                           <input class="form-control" type="text" name="email" value="<?=$email?>" maxlength="50" id='email'>
                           <?php
                   }
                   ?>

</div>
                           <?php


                           ?>
                            <div class="form-group">
                           <label ><span style="color:red;">*</span>First Name:<span id='edit_error_msg_first_name' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
                           <?php 
                           $first_name = $sessionpatient['customer_info']['user']['first_name'];

                   ?>
                           <input	class="form-control" type="text" name="first_name" id='first_name' value="<?=$first_name?>" maxlength="20" >
                           </div>

                            <div class="form-group">
                           <label ><span style="color:red;">*</span>Last Name:<span id='edit_error_msg_last_name' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
                           <?php

                           $last_name = $sessionpatient['customer_info']['user']['last_name'];

                   ?>
                           <input 	class="form-control" type="text" name="last_name" id='last_name' value="<?=$last_name?>" maxlength="20" >
                           </div>
                           <?php
                                   foreach($sessionpatient['ProfileField'] as $field){ 
                   if(($field['ProfileField']['type']=='BigInt' || $field['ProfileField']['type']=='Varchar' || $field['ProfileField']['type']=='Integer')){ 


                   $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                   $user_val = '';
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                   if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                           $user_val=$pfield['ProfileFieldUser']['value'];
                   }
                   }

                   ?>
                   <div class="form-group">
                   <label ><?php if($field_val=='Street1'){
                                                           echo "Address:";
                                                   }
                                                   else if($field_val=='Street2'){
                                                           echo "&nbsp;";
                                                   }else{
                                                           echo $field_val.':'; 
                                                   } ?><span id='edit_error_msg_<?php echo $field['ProfileField']['profile_field']; ?>' style='color:red;font-style:italic;padding-left:50px;' ></span></label>

                   <input 	class="form-control"  type="text" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $user_val;?>" maxlength="50" >
                   </div>
                   <?php }
                   if($field['ProfileField']['type']=='Text'){ 


                   $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                   $user_val = '';
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                                           $user_val=$pfield['ProfileFieldUser']['value'];
                           }
                   }

                   ?>
                   <div class="form-group">
                   <label ><?php echo $field_val;  ?>:</label>
                   <textarea name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" rows="6" cols="30" ><?php echo $user_val;?></textarea>
                   </div>
                   <?php }
                   if($field['ProfileField']['type']=='MultiSelect'){ 


                   $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                   $field_options_red=explode(',',$field['ProfileField']['options']);
                   $user_val = array();
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                                   $user_val=explode(',',$pfield['ProfileFieldUser']['value']);
                           }
                   }

                   ?>
                   <div class="form-group">
                   <label ><?php echo $field_val;  ?>:</label>
                   <select class="form-control" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" id="<?php echo $field['ProfileField']['profile_field']; ?>"  multiple="multiple" size="4">
                   <option value="">Please Select</option>
                   <?php foreach($field_options_red as $op){ ?>
                   <option value="<?php echo $op;?>" <?php if (in_array($op, $user_val)) { echo "selected"; } ?>><?php echo $op;?></option>
                   <?php } ?>
                   </select>

                   </div>
                   <?php }
                   if($field['ProfileField']['type']=='Select' && ($field['ProfileField']['profile_field']!='state' && $field['ProfileField']['profile_field']!='city')){ 


                   $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                   $field_options_red=explode(',',$field['ProfileField']['options']);
                   $user_val = array();
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                                   $user_val=explode(',',$pfield['ProfileFieldUser']['value']);
                           }
                   }

                   ?>
                   <div class="form-group">
                   <label ><?php echo $field_val;  ?>:</label>
                   <select class="form-control" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>">
                   <option value="">Please Select</option>
                   <?php foreach($field_options_red as $op){ ?>
                   <option value="<?php echo $op;?>" <?php if($user_val==$op){ echo "selected"; } ?>><?php echo $op;?></option>
                   <?php } ?>
                   </select>

                   </div>
                   <?php }




//for radio button
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
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                                   $user_val=$pfield['ProfileFieldUser']['value'];
                           }
                   }
                   $otherrd=explode('###',$user_val);
                    $othervalrd='';
                    if(isset($otherrd[1])){
                    $othervalrd=$otherrd[1];
                    }
                   ?>
                   <div class="form-group clearfix">
                   <label style="display: block;">
                   <?php if($field['ProfileField']['profile_field']=='gender'){ ?>
                   <span style="color:red;">*</span>
                   <?php } ?>
                   <?php echo $field_val;  ?>:<span id='edit_error_msg_<?php echo $field['ProfileField']['profile_field']; ?>' style='color:red;font-style:italic;padding-left:50px;' ></span></label>

                   <?php foreach($field_options_red as $op){ ?>
                   <div class="col-xs-6 pull-left radio_btn">
                   <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $op; ?>" <?php if( $user_val==$op){ echo "checked"; } ?>   onclick="opt1('<?php echo $op; ?>','<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')">
                   <label class=" control-label"><?php echo $op; ?></label>
                   </div>
                   <?php }if($other==1){?>
                   <div class="col-xs-6 pull-left radio_btn">
                   <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="other" <?php if(count($otherrd)>1){ echo "checked"; } ?>  onclick="opt1('other','<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>','<?=$othervalrd?>')">
                   <label class=" control-label">Other</label>
                   </div> 
                   <?php if(count($otherrd)>1){ ?>
                   <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" class="clearfix">
                  
                    <input class="form-control" type="text" name="other_<?php echo $field['ProfileField']['profile_field']; ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" placeholder="Other" value="<?=$othervalrd?>" maxlength="30" >     
		       
		   </div>
                   <?php }else{ ?>
                   <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>"  class="clearfix">
                       
		       
		   </div>
                      <?php }} ?>
                   <script>

                    function opt1(val,val1,val2){

                        if(val=='other'){
                            $('#othertext_'+val1).html('<input class="form-control" type="text" name="other_'+val1+'" id="other_'+val1+'" placeholder="Other" value="'+val2+'" maxlength="30" >');
                        }else{
                            $('#othertext_'+val1).html('');
                        }
                    }
                    </script>
                   </div>
                   <?php }


//for check box
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
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']==$field['ProfileField']['profile_field']){
                                   $user_val=explode(',',$pfield['ProfileFieldUser']['value']);
                           }
                   }

                    $otherchek=explode('###',end($user_val));
                    $otherval='';
                    if(isset($otherchek[1])){
                    $otherval=$otherchek[1];
                    }
                   ?>
                   <div class="form-group rad_btn main-int" style="margin-top:10px;">
                   <span style="display:block; font-weight:bold;"><?php echo $field_val;  ?>:</span>


                   <div>
                   <?php foreach($field_options_red as $op){ ?>

                   <label class="checkbox-inline">
                   <input type="checkbox" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="<?php echo $op; ?>" <?php if (in_array($op, $user_val)) { echo "checked";} ?>> <?php echo $op; ?>
                   </label>
                   <?php } 
                    if($other1==1){?>
                   <label class="checkbox-inline">
                   <input type="checkbox" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="other" <?php if (count($otherchek)>1) { echo "checked";} ?>  id="getopt_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" onclick="opt('<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>','<?=$otherval?>')"> Other
                   </label>
                   <?php if (count($otherchek)>1) { ?>
                   <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>">
                  
                   <input class="form-control"  type="text" name="other_<?php echo $field['ProfileField']['profile_field']; ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $otherval;?>" maxlength="30" >    
		       
		   </div>
                   <?php }else{ ?>
                   <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>">
                       
		   </div>
                   <?php }} ?>
                   <script>

                     function opt(val,val1){
                     
                    if ($('#getopt_'+val).is(":checked"))
                    {
                    $('#othertext_'+val).html('<input class="form-control" type="text" name="other_'+val+'" id="other_'+val+'" value="'+val1+'" maxlength="30" >');
                    }else{
                    $('#othertext_'+val).html('');
                    }

                    }</script>
                   </div>
                   </div>
                    
                   <?php }





                           if($field['ProfileField']['profile_field']=='state'){
                           foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                           if($pfield['profile_field']=='state'){
                                   $state=$pfield['ProfileFieldUser']['value'];
                                           }
                           }
                           if(!isset($state)){
                                   $state = '';
                           }
                           ?>
                           <div class="form-group">
                           <label>State:<span id='edit_error_msg_state' style='color:red;font-style:italic;padding-left:50px;' ></span></label>

                           <select class="form-control" name="state" id="state" onchange="getcity();" >
                           <option value="">Select State</option>
                           <?php foreach($states as $st){ ?>
                           <option value="<?=$st['State']['state']?>" <?php if($state==$st['State']['state']){ echo "selected"; } ?>><?=$st['State']['state']?></option>
                           <?php } ?>
                           </select>
                           </div>	
                           <?php
                   }
                   if($field['ProfileField']['profile_field']=='city'){
                   foreach($sessionpatient['customer_info']['ProfileField'] as $pfield){
                   if($pfield['profile_field']=='city'){
                                   $city_val=$pfield['ProfileFieldUser']['value'];
                                           }
                           }
                           if(!isset($city_val)){
                                   $city_val = '';
                           }
                           ?>
                            <div class="form-group">
                           <label>City:<span id='edit_error_msg_city' style='color:red;font-style:italic;padding-left:50px;' ></span></label>


                           <select class="form-control" name="city" id="city" >
                           <option value="">Select City</option>
                           <?php foreach($city as $ct){ ?>
                           <option value="<?=$ct['City']['city']?>" <?php if($city_val==$ct['City']['city']){ echo "selected"; } ?>><?=$ct['City']['city']?></option>
                                   <?php } ?>
                           </select>


                           </div>		
                           <?php
                   }




}
                  ?> 
                   <div class="form-group">
                   <input type="submit" value="Save Profile" class="btn btn-primary buttondflt" name="myinfo_submit" style="margin:15px 0px" onclick="return checkemail();">
                   </div>	
                   </form>
                   </div>
<?php
}
else {
	?>
	<div class="grid_100 content_center">
	<br><br><br><br><br>
	<div class="error">Error accessing your information</div><br>
	<br><br><br><br><br><br><br><br>
	</div>
	<?php
} ?>
</div></div></div></div>
 <?php echo $this->element('left_sidebar'); ?>
      </div>


<script>
function removeimg(filename,aname){
	$('#'+filename).val('');
	$('#'+aname).text('');
	$('#'+aname).removeClass('icon-top hand-icon');
}
function checkimg(filename,aname){
	var sluval=$('#'+filename).val();
	if(sluval!=''){
		$('#'+aname).text('x');
		$('#'+aname).addClass('icon-top hand-icon');
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
	}
  });



 
}
function checkemail(){

	var user_id=$('#id').val();
	var datasrc="user_id="+user_id;
	if($('#email').val()==undefined){
		var email=$('#parents_email').val();
		var parents_email=$('#aemail').val();
		datasrc=datasrc+"&email="+email+"&parents_email="+parents_email;
	}
	else{
		var email=$('#email').val();
		datasrc=datasrc+"&email="+email;
	}
  $.ajax({
	  type:"POST",
	  data:datasrc,
	  url:"<?=Staff_Name?>rewards/checkemail/",
	  success:function(result){
		if(result==1){
		
			$("#email").focus();
			alert('Email and Username already exists.');
			return false;
		}
		else if(result==2){
		$("#parents_email").focus();
			alert('Email Id already exists.');
			return false;
		
		}
		else if(result==3){
		$("#email").focus();
			alert('Email Id already exists.');
			return false;
		
		}
		else if(result==4){
		$("#aemail").focus();
			alert('Username already exists.');
			return false;
		
		}else{
		$( "#new_account_form" ).submit();
		}
	}
  });
 return false;
}

function checkage(){
	var yr=$("#date_year").val();
        var mn=$("#date_month").val();
        var dy=$("#date_day").val();
        var today = new Date()
        var past = new Date(yr,mn,dy)
        var diff = Math.floor(today.getTime() - past.getTime());
        var day = 1000* 60 * 60 * 24;

        var days = Math.floor(diff/day);
        var months = Math.floor(days/31);
        var years = Math.floor(months/12);


        if((days <= 6542) && (days >= 1)){
            $('#pemail').css('display','block');
            $('#pemail').html('<label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value="<?php echo $sessionpatient['customer_info']['user']['email']; ?>" required>');
			$('#email_field').html('<label><span style="color:red;">*</span>Username</label><input class="form-control" type="text" name="aemail" id="aemail" value="<?php echo $sessionpatient['customer_info']['user']['parents_email']; ?>" maxlength="255" >');
            return true;
        }
        else{
            $('#pemail').html('');
			$('#email_field').html('<label><span style="color:red;">*</span>Email</label><input class="form-control" type="email" name="email" id="email" value="<?php echo $sessionpatient['customer_info']['user']['email']; ?>" maxlength="255" >');
            return true;
        }
    
}
$(document).ready(function() {  
$.validator.addMethod("zipRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Zipcode must contain only alphanumeric.");
        $('#new_account_form').validate({
		rules: {
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
				number: true, minlength: 7 ,maxlength:10
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
				required: true ,
				email: true,
				checkparentemail:true
			},
                        aemail: { 
				required: true,
				checkparentemail:true
			},
			postal_code: {
				 zipRegex:true,
                                 minlength: 4 ,maxlength:6
			},
			gender: {
				required: true
			}
			
		},
        
        // Specify the validation error messages
		messages: {
			first_name: "Please enter your first name",
			last_name: "Please enter your last name",
			new_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 6 characters long"
			},
			new_password2: {
				required: "Please provide a confirm password"
			},
			email: "Please enter a valid email address",
			phone: {
				number: "Please enter a valid phone number",
				minlength: "Phone Number must be 7 to 10 characters long",
                                maxlength: "Phone Number must less then 11 characters"
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
			parents_email: {
			required:"Please enter a valid Email address",
			checkparentemail:"Email and Username should be different."
			},
                        aemail: {

                        required:"Please enter username.",
			checkparentemail:"Email and Username should be different."
			},
			postal_code: {
				
				zipRegex:"Please enter valid zipcode",
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
});
  

</script>
