    <?php  $sessionstaff = $this->Session->read('admin');  ?>
<script type="text/javascript">
    <!--
$(document).ready(function() {
        $(".sorting").click(function() {
            var sortby = $(this).attr('sortby');
            var sort = $(this).attr('sort');
            var url = '<?php echo Router::url('/GlobalUserManagement/sort/', true); ?>' + sortby + '/' + sort;

            //console.log(url);
            window.location.href = url;
        });
    });
    //-->
</script>


<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-list-ul"></i> Global Users
        </h1>
    </div>
</div>

   	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
<form action="/GlobalUserManagement/updateuser" method="POST" name="myinfo_form" class="form-horizontal" id="myinfo_form">
    <input type="hidden" value="<?=$Users['User']['id']?>" name="id" id="id">
        <?php
            if (!empty($Users['User']['custom_date'])) {
                    $date_array = explode ('-', $Users['User']['custom_date']);
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
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Date Of Birth: </label>
        <div class="col-sm-9 col-xs-12">

            <select name="date_year" id="date_year" onchange="checkage();">
                <option value="">Year</option>
                            <?php $curyer=date('Y');
                            for ($y = 1900; $y <= $curyer; $y += 1) {
                                if ($y == $year) { ?>
                <option value="<?=$y?>" selected><?=$y?></option>
                                    <?php }else{ ?>
                <option value="<?=$y?>"><?=$y?></option>
                                    <?php } } ?>
            </select>
            <span class="dateIcon"></span>
                        <?php  $months = array(
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
                            12 => 'Dec'); ?>
            <select name="date_month" id="date_month" onchange="checkage();">
                <option value="">Select Month</option>
                                <?php foreach($months as $mon=>$val){  ?>
                <option value="<?=$mon?>" <?php if ($mon == $month) { echo "selected"; } ?>><?php echo $val;?></option>
                                                        <?php } ?>
            </select>
            <span class="dateIcon"></span>

            <select name="date_day" id="date_day" onchange="checkage();">
                <option value="">Day</option>
                            <?php for ($d = 1; $d <= 31; $d++) {
                                                if ($d == $day) { ?>
                <option value="<?=$d?>" selected><?=$d?></option>
                            <?php }else{ ?>
                <option value="<?=$d?>"><?=$d?></option>
                            <?php } } ?>
            </select>
            <span class="dateIcon"></span>

        </div>
    </div>


          <?php 
            $date1_chd=$Users['User']['custom_date'];
            $date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
            $date2_chd = date('Y-m-d');
            $diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
            $years_chd = floor($diff_chd / (365*60*60*24));
					
	?>

    <div class="form-group Clearfix" id="pemail">
            <?php
             if(isset($years_chd) && $years_chd<18){
                   $pemail=$Users['User']['email']; ?>
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Email-Id:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="email" name="parents_email" id="parents_email" value="<?=$pemail?>" maxlength="50" class="col-xs-12 col-sm-5">
        </div>
          <?php } ?>
    </div>

        <?php
          if(isset($years_chd) && $years_chd<18){
                $email = $Users['User']['parents_email']; ?>
    <div class="form-group Clearfix"  id="email_field">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Username:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" name="aemail" value="<?=$email?>" maxlength="50" id='aemail' class="col-xs-12 col-sm-5">
        </div>
    </div>
        <?php }else {
            $email = $Users['User']['email']; ?>

    <div class="form-group Clearfix"  id="email_field">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Email:<span id='edit_error_msg_email' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
        <div class="col-sm-9 col-xs-12">
            <input class="col-xs-12 col-sm-5" type="text" name="email" value="<?=$email?>" maxlength="50" id='email'>
        </div>
    </div>

   <?php } ?>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Change password:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="password" placeholder="Change password (Optional)" name="new_password" id="new_password" value="" size="16"	maxlength="50" class="col-xs-12 col-sm-5">
        </div>
    </div>


    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Verify password:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="password" placeholder="Verify changed password" name="new_password2" id="new_password2" value="" size="16"	maxlength="50" class="col-xs-12 col-sm-5">
        </div>
    </div>


        <?php $first_name = $Users['User']['first_name']; ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >First Name:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" placeholder="First Name" id="first_name" name="first_name" value="<?=$first_name?>" size="24" maxlength="20" class="col-xs-12 col-sm-5" >
        </div>
    </div>
        <?php $last_name = $Users['User']['last_name']; ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >Last Name:</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" placeholder="Last Name" id="last_name" name="last_name" value="<?=$last_name?>" size="30" maxlength="20" class="col-xs-12 col-sm-5" >
        </div>
    </div>
          <?php 
          foreach($sessionstaff['ProfileField'] as $field){ 
            if(($field['ProfileField']['type']=='BigInt' || $field['ProfileField']['type']=='Varchar' || $field['ProfileField']['type']=='Integer')){ 
                    $field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
                    $user_val1 = '';
                    foreach($Users['User']['profile'] as $pfield){
                        if(isset($pfield['ProfileField']['profile_field']) && $pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
                           $user_val1=$pfield['ProfileFieldUser']['value'];
                        }
                    } ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><?php if($field_val=='Street1'){
                    echo "Address:";
                }
                else if($field_val=='Street2'){
                    echo "&nbsp;";
                }else{
                    echo $field_val.':'; 
                } ?></label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" placeholder="<?php echo $field_val; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $user_val1;?>" maxlength="50" class="col-xs-12 col-sm-5">
        </div>
    </div>
          <?php } 
				
				if($field['ProfileField']['type']=='Text'){ 
					
					
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$user_val2 = '';
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val2=$pfield['ProfileFieldUser']['value'];
					}
					}
					
					
					 ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><?php echo $field_val;  ?>:</label>
        <div class="col-sm-9 col-xs-12">
            <textarea name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" rows="6" cols="30" ><?php echo $user_val2;?></textarea>
        </div>
    </div>
          <?php } 
					if($field['ProfileField']['type']=='MultiSelect'){ 
					
					
					$field_val=ucwords(str_replace('_',' ',$field['ProfileField']['profile_field']));
					$field_options_red=explode(',',$field['ProfileField']['options']);
					$user_val = array();
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=explode(',',$pfield['ProfileFieldUser']['value']);
					}
					}
					
					 ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><?php echo $field_val;  ?>:</label>
        <div class="col-sm-9 col-xs-12">
            <select class="col-xs-12 col-sm-5" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" id="<?php echo $field['ProfileField']['profile_field']; ?>"  multiple="multiple" size="4">
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
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=$pfield['ProfileFieldUser']['value'];
					}
					}
					
					 ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><?php echo $field_val;  ?>:</label>
        <div class="col-sm-9 col-xs-12">
            <select class="col-xs-12 col-sm-5" name="<?php echo $field['ProfileField']['profile_field']; ?>" id="<?php echo $field['ProfileField']['profile_field']; ?>" >
                <option value="">Please Select</option>
                        <?php foreach($field_options_red as $op){ ?>
                <option value="<?php echo $op;?>" <?php if($user_val==$op){ echo "selected"; } ?>><?php echo $op;?></option>
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
                                        $user_val = '';
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=$pfield['ProfileFieldUser']['value'];
					}
					}
					$otherrd=explode('###',$user_val);
                                        $othervalrd='';
                                        if(isset($otherrd[1])){
                                        $othervalrd=$otherrd[1];
                                        }
					 ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><?php echo $field_val;  ?>:</label>
        <div class="col-sm-9 col-xs-12">
            <?php foreach($field_options_red as $op){ ?>
            <div class="patientinfo_radio">
                <input type="radio" class="" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $op; ?>" <?php if( $user_val==$op){ echo "checked"; } ?> onclick="opt1('<?php echo $op; ?>', '<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>', '<?=$othervalrd?>')">
                <label class=" control-label"><?php echo $op; ?></label>
            </div>

            <?php }if($other==1){?>
            <div class="patientinfo_radio">
                <input type="radio" class="" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>" value="other" <?php if(count($otherrd)>1){ echo "checked"; } ?>  onclick="opt1('other', '<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>', '<?=$othervalrd?>')">
                <label class=" control-label">Other</label>
            </div> 

            <?php if(count($otherrd)>1){ ?>
            <div id="othertext_<?php echo $field['ProfileField']['profile_field']; ?>"  class="othertextbox">
                <input type="text" name="other_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" placeholder="Other" value="<?=$othervalrd?>" maxlength="30">     
            </div>
            <?php }else{ ?>
            <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" class="othertextbox"></div>
            <?php }} ?>

            <script>

                function opt1(val, val1, val2) {

                    if (val == 'other') {
                        $('#othertext_' + val1).html('<input type="text" name="other_' + val1 + '" id="other_' + val1 + '" placeholder="Other" value="' + val2 + '" maxlength="30" >');
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
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']==$field['ProfileField']['profile_field']){
							$user_val=explode(',',$pfield['ProfileFieldUser']['value']);
					}
					}
					$otherchek=explode('###',end($user_val));
                                        $otherval='';
                                        if(isset($otherchek[1])){
                                        $otherval=$otherchek[1];
                                        }
					 ?>
    <div class="form-group" style="overflow:hidden;">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php echo $field_val;  ?>:</label>
        <div class="col-sm-9">
           
                            <?php foreach($field_options_red as $op){ ?>
              
                <input type="checkbox" id="<?php echo $field['ProfileField']['profile_field']; ?>" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="<?php echo $op; ?>" <?php if (in_array($op, $user_val)) { echo "checked";} ?>>
                    <label class="patientinfo_checkbox">
					 <?php echo $op; ?>
					</label>
                            <?php }
                            if($other1==1){?>
                <input type="checkbox" name="<?php echo $field['ProfileField']['profile_field']; ?>[]" value="other" <?php if (count($otherchek)>1) { echo "checked";} ?>  id="getopt_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" onclick="opt('<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>','<?=$otherval?>')">
                   <label class="patientinfo_checkbox">
                    Other
                   </label>
                            <?php } ?>
            
                <?php 
                if (count($otherchek)>1) { ?>
            <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" class="othertextbox">
                  
                   <input class="col-xs-10 col-sm-5"  type="text" name="other_<?php echo $field['ProfileField']['profile_field']; ?>" id="other_<?php echo $field['ProfileField']['profile_field']; ?>" value="<?php echo $otherval;?>" maxlength="30" >    
		       
		   </div>
                <?php }else{ ?>
            <div id="othertext_<?php echo str_replace(array('?','#'),'',$field['ProfileField']['profile_field']); ?>" class="othertextbox">
                       
		   </div>
                <?php } ?>
            <script>

                function opt(val, val1) {

                    if ($('#getopt_' + val).is(":checked"))
                    {
                        $('#othertext_' + val).html('<input class="col-xs-10 col-sm-5" type="text" name="other_'+val+'" id="other_'+val+'" value="'+val1+'" maxlength="30" >');
                    } else {
                        $('#othertext_' + val).html('');
                    }

                }</script>
        </div>
    </div>







          <?php }
     
					if($field['ProfileField']['profile_field']=='state'){
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']=='state'){
							$state=$pfield['ProfileFieldUser']['value'];
					}
					}
					if(!isset($state)){
							$state = '';
					} ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" >State:</label>
        <div class="col-sm-9 col-xs-12">
            <select class="col-xs-12 col-sm-5" name="state" id="state" onchange="getcity();">
                <option value="">Select State</option>
                    <?php foreach($states as $st){ ?>
                <option value="<?=$st['State']['state']?>" <?php if($state==$st['State']['state']){ echo "selected"; } ?>><?=$st['State']['state']?></option>
                    <?php } ?>
            </select>
        </div>
    </div>
          <?php }
  
		
					if($field['ProfileField']['profile_field']=='city'){
					foreach($Users['User']['profile'] as $pfield){
					if($pfield['ProfileField']['profile_field']=='city'){
							$city_val=$pfield['ProfileFieldUser']['value'];
					}
					}
					if(!isset($city)){
							$city_val = '';
					} ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">City:</label>
        <div class="col-sm-9 col-xs-12">
            <select class="col-xs-12 col-sm-5" name="city" id="city">
                <option value="">Select City</option>
                <?php foreach($city as $ct){ ?>
                <option value="<?=$ct['City']['city']?>" <?php if($city_val==$ct['City']['city']){ echo "selected"; } ?>><?=$ct['City']['city']?></option>
                <?php } ?>
            </select>
        </div>
    </div>




          <?php }

	  } 
	  if($Users['User']['parents_email']=='' && $Users['User']['email']!=''){
		  $ademail='';
		  $memail=$Users['User']['email'];
	  }else{
		  $ademail=$Users['User']['parents_email'];
		  $memail=$Users['User']['email'];
	  }
          if($option==1){
	  ?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Unsubscribe From Emails:</label>
        <div class="col-sm-9 col-xs-12">
					            <div class="patientinfo_radio">
                <input type="radio" value="0" name="unsubscribe" id="unsubscribe" class="" <?php if($unsubscribe==0){ echo "checked"; } ?>>
                <label class=" control-label">Yes</label>
            </div>
                                 <div class="patientinfo_radio">
                <input type="radio" value="1" name="unsubscribe" id="unsubscribe" class="" <?php if($unsubscribe==1){ echo "checked"; } ?>>
                <label class=" control-label">No</label>
                                 </div>		


        </div></div>
          <?php } ?>
    <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Changes" class="btn btn-sm btn-primary" onclick="return checkemail();">
   </div>
</form>



<script>
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

//4717
        if ((days <= 6542) && (days >= 1)) {
            $('#pemail').css('display', 'block');
            $('#pemail').html('<label class="col-sm-3 col-xs-12 control-label no-padding-right">Email-Id:</label><span id="emailerr" style="color:red;"></span><div class="col-sm-9 col-xs-12"><input	class="col-xs-12 col-sm-5 valid" type="email" name="parents_email" id="parents_email" value="<?=$memail?>" maxlength="50"></div>');

            $('#email_field').html('<label class="col-sm-3 col-xs-12 control-label no-padding-right">Username</label><div class="col-sm-9 col-xs-12"><input class="col-xs-12 col-sm-5 valid" type="text" name="aemail" id="aemail" value="<?=$ademail?>" maxlength="50" ></div>');

            return true;
        }
        else {
            $('#pemail').html('');
            $('#email_field').html('<label class="col-sm-3 col-xs-12 control-label no-padding-right">Email</label><div class="col-sm-9 col-xs-12"><input class="col-xs-12 col-sm-5" type="email" name="email" id="email" value="<?=$Users['User']['email']?>" maxlength="50" ></div>');
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
            url: "<?=Staff_Name?>GlobalUserManagement/checkemail/",
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
     $.validator.addMethod("zipRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Zipcode must contain only alphanumeric.");
		
        $('#myinfo_form').validate({
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
                    zipRegex:true,
                    minlength: 4 ,maxlength:6
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

    function getcity() {

        var state = $('#state').val();

        $.ajax({
            type: "POST",
            data: "state=" + state,
            url: "<?=Staff_Name?>GlobalUserManagement/getcity/",
            success: function(result) {
                $('#city').html(result);
            }});




    }

</script>











