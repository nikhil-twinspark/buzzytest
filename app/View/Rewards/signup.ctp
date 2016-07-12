<style type="text/css">
.pagewrap.container { background:#e2e2e2;}
#Style {
position:absolute;

padding: 5px;
right: -143px;
z-index: 101;}
.form-group {position:relative;}
div.error_validate{top:-7px; width:auto;}
.m_box{width: 29% !important;}
.selctboxmonth:last-child{ margin-right:0;}
.detail form .gender label{margin: 2px 0px 0px 0px;}

.selctboxmonth div.error_validate {top: -78px; width: 76px;}
.gender div.error_validate{top: -29px; width: auto;}
.radio_btn input{width: 15px !important;}
</style>
      <form action="<?=Staff_Name?>rewards/signup/" method="POST" name="new_account_form" id="new_account_form" class="popupform">
      
<input type="hidden" name="action" value="record_new_account">

      <div class="modal-body clearfix">
        <div class="row">
		<div class="col-md-6 col-xs-12">

          <?php $sessionpatient = $this->Session->read('patient');


		
			
				 ?>
               <div class="form-group">
               <label >Card Number</label>
               <span class="helpicon" style="position:relative;">
             <a href="#" class="showhim" id="clickme" >?</a>
             <div id="Style1" style="position:absolute; padding:5px;display:none; z-index:101; top: 12px; right: -151px;">
             <?php if(isset($sessionpatient['Themes']['patient_question_mark'])){ ?>
             <img src="<?=S3Path.$sessionpatient['Themes']['patient_question_mark']?>" width="182" height="148"/>
             <?php }else{ ?>
             <?php echo $this->html->image(CDN.'img/reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
             <?php } ?>
             </div> 
             </span>
              <input class="form-control" type="text" id="card"	name="card" value="<?=$card_number?>" maxlength="50" readonly>
              </div>
              <script>
              
                
              $(document).ready(function() {  
	var window_wid = $(window).width();
	if(window_wid<992){
		
			$( "#clickme" ).click(function() {
			$( "#Style1" ).toggle();
			});
			
			$( "#clickme" ).mouseleave(function(){
					$( "#Style1" ).css('display', 'none');
			});
	
		
		$('.tool_tip .helpicon').appendTo('.tool_tip p');
	} else{
	$( "#clickme" ).hover(function() {
	$( "#Style1" ).toggle();
	});
	}
	
	});
	
	</script>
             
			  <div class="form-group" style='margin-bottom:0;'>
               <label ><span style="color:red;">*</span>Date Of Birth</label>
              </div>
               <div class="form-group clearfix">
                <div class="selctboxmonth m_box" >
              
               <span class="dropIcon"></span>
                <select class="form-control" name="date_year" id="date_year" onchange="return checkage();">
                  <option value="">Year</option>
						<?php 
						$curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) { ?>
							
						<option value="<?=$y?>" <?php if($y==1990){ echo "selected";} ?>><?=$y?></option>
						<?php }	?>
               </select>
               </div>
               <div class="selctboxmonth m_box_2">
          
            
               <span class="dropIcon"></span>
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
                <div class="selctboxmonth m_box" >
       
               <span class="dropIcon"></span>
               <select class="form-control" name="date_day" id="date_day" onchange="return checkage();">
                  <option value="">Day</option>
						<?php for ($d = 1; $d <= 31; $d += 1) { ?>
						<option value="<?=$d?>"><?=$d?></option>
						<?php	} ?>
               </select>
               </div>
              </div>
              <div class="form-group clearfix" id="pemail" style="display:none";>
               </div>
              	
              <div class="form-group" id="email_field">
               <label ><span style="color:red;">*</span>Email</label>
              <input 	class="form-control" type="email" name="email" value="" maxlength="50" >
              </div>
            
					
              <div class="form-group">
               <label ><span style="color:red;">*</span>First Name</label>
               <input	class="form-control" type="text" name="first_name" id="first_name" value="" maxlength="20" >
              </div>
             
              <div class="form-group">
               <label ><span style="color:red;">*</span>Last Name</label>
               <input 	class="form-control" type="text" name="last_name" id="last_name" value="" maxlength="20" >
              </div>
              <div class="form-group">
               <label ><span style="color:red;">*</span>Password (atleast 6 characters)</label>
              
              <input	class="form-control" type="password"	name="new_password" id="new_password" value="" maxlength="20">
              </div>
              <div class="form-group" >
               <label ><span style="color:red;">*</span>Confirm Password</label>
              <input	class="form-control" type="password"	name="new_password2" id="new_password2"	value="" maxlength="20">
              </div>
              <?php 
              $s=0;
			        foreach($sessionpatient['ProFieldGlobal'] as $field){
			       
					
					if(($field['ProfileField']['type']=='BigInt' || $field['ProfileField']['type']=='Varchar' || $field['ProfileField']['type']=='Integer')){ 
			  $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
			  ?>
			        <div class="form-group">
					<label ><?php
					if($field_val=='Street1'){
					echo "Address";
					}
					else if($field_val=='Street2'){
					echo "&nbsp;";
					}else{
					 echo $field_val; 
					 }
					 ?></label>
					<input class="form-control" type="text" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" value="" maxlength="10" >
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
			        <div class="form-group">
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
			        <div class="form-group">
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
			         <div class="clearfix"></div>
					<div class="form-group clearfix">
					  <label style="display:block;">
					  <?php if($field['ProfileField']['profile_field']=='gender'){ ?>
					  <span style="color:red;">*</span>
					  <?php } ?>
					  <?php echo $field_val; ?></label>
					  <?php foreach($field_options_red as $opt1){ ?>
                      <div class="col-xs-6 pull-left radio_btn">
                      <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $opt1; ?>"  onclick="opt1('<?php echo $opt1; ?>','<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')">
                      <label class=" control-label"><?php echo $opt1; ?></label>
                      </div>
                      <?php } if($other==1){?>
                      <div class="col-xs-6 pull-left radio_btn">
                      <input type="radio" class="form-control" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="other"  onclick="opt1('other','<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>')">
                      <label class=" control-label">Other</label>
                      </div>  
                      <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" class="clearfix">
                       
		       
		      </div>
                      <?php } ?>
                   </div>
                    <script>

                    function opt1(val,field_name){

                        if(val=='other'){
                            $('#othertext_'+field_name).html('<input class="form-control" type="text" name="other_'+field_name+'" id="other_'+field_name+'" placeholder="Other" value="" maxlength="30" >');
                        }else{
                            $('#othertext_'+field_name).html('');
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
			         <div class="form-group rad_btn">
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
                    
                    function opt(val){

        if ($('#getopt_'+val).is(":checked"))
{
 $('#othertext1_'+val).html('<input class="form-control" type="text" name="other_'+val+'" id="other_'+val+'" placeholder="Other" value="" maxlength="30" >');
}else{
     $('#othertext1_'+val).html('');
    }
       
    }</script>
                <?php }
					if($field['ProfileField']['profile_field']=='state'){
						?>
              
              <div class="form-group">
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
                     
              
               <div class="form-group">
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
                   } ?>
              <input type="submit" value="Submit" name="myinfo_submit" class="btn btn-primary clearfix buttondflt">

         </div>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>-->
    </div>
  </div>
  </div>
  </form>
  <script type="text/javascript">
$(document).ready(function() {  
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
			required:"Please enter a valid email address",
			checkparentemail:"Email and Username should be different."
			},
                        aemail: {
//		
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
            $('#pemail').html('<label><span style="color:red;">*</span>Email-Id:</label><span id="emailerr" style="color:red;"></span><input	class="form-control" type="email" name="parents_email" id="parents_email" value="" required>');
			$('#email_field').html('<label><span style="color:red;">*</span>Username</label><input class="form-control" type="text" name="aemail" id="aemail" value="" maxlength="255" >');
            return true;
        }
        else{
            $('#pemail').html('');
			$('#email_field').html('<label><span style="color:red;">*</span>Email</label><input class="form-control" type="email" name="email" id="email" value="" maxlength="255" >');
            return true;
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
function ShowPicture(id,Source) {
if (Source=="1"){
if (document.layers) document.layers[''+id+''].visibility = "show"
else if (document.all) document.all[''+id+''].style.visibility = "visible"
else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "visible"
}
else
if (Source=="0"){
if (document.layers) document.layers[''+id+''].visibility = "hide"
else if (document.all) document.all[''+id+''].style.visibility = "hidden"
else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "hidden"
}
}
</script>
