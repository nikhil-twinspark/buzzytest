<?php

$sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-flask"></i>
            Doctors
            <!--
           <small>
              
           <i class="ace-icon fa fa-angle-double-right"></i>
           Draggabble Widget Boxes & Containers
           </small>
            -->
        </h1>
    </div>
     <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    
    ?>



      <?php echo $this->Form->create("Doctor",array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
       ?>




    <div class="tab-content profile-edit-tab-content">
        <div id="edit-basic" class="tab-pane in active">
            <h4 class="header blue bolder smaller">General</h4>

            <div class="row">
                <div class="col-xs-12 col-sm-4">

                    <input type="file" id="profile_image" onchange="checkimg('profile_image', 'plu');"  name="profile_image">
                    <a onclick="removeimg('profile_image', 'plu');" class="" id="plu"></a>
                </div>

                <div class="vspace-12-sm"></div>

                <div class="col-xs-12 col-sm-8">

                    <input type="hidden" id="clinic_id" name="clinic_id" value="<?=$sessionstaff['clinic_id']?>">



                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>First Name:</label>
                        <div class="col-sm-9">
                            <input type="text"  maxlength="20" class="col-xs-10 col-sm-5" placeholder="First Name" id="first_name" name="first_name" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Last Name:</label>
                        <div class="col-sm-9">
                            <input type="text"  maxlength="20" class="col-xs-10 col-sm-5" placeholder="Last Name" id="last_name" name="last_name" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Degree:</label>
                        <div class="col-sm-9">

                            <select class="col-xs-10 col-sm-5" name="degree" id="degree">
                                <option value="">Select Degree</option>

                                <option value="DMD">DMD</option>
                                <option value="DDS">DDS</option>
                                <option value="MD">MD</option>
                                <option value="DO">DO</option>
                                <option value="PA-C">PA-C</option>


                            </select>
                        </div>
                    </div>															</div>
            </div>

            


            <h4 class="header blue bolder smaller">Contact</h4>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1" title="Selecting clinic location autofills the form">Select clinic location:</label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-5" name="location_default" id="location_default" onchange="selectdefault();">
                                <option value="">Select Location</option>
	<?php foreach($Locations as $loc){ ?>
                                <option value="<?=$loc['ClinicLocation']['id']?>" ><?=$loc['ClinicLocation']['address']?></option>
<?php } ?>
                            </select>
      
           
                        </div>
      
                    </div>

            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Phone:</label>
                <div class="col-sm-9">
                    <input type="text"  maxlength="11" class="col-xs-10 col-sm-5" placeholder="Phone" id="phone" name="phone" value="">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Email:</label>
                <div class="col-sm-9">
                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Email" id="email" name="email" value="">
                </div>
            </div>

            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Address:</label>
                <div class="col-sm-9">
                    <textarea name="address" id="address" rows="6" cols="30" class="col-xs-10 col-sm-5" placeholder="Address"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>State:</label>
                <div class="col-sm-9">
                    <select class="col-xs-10 col-sm-5" name="state" id="state" onchange="getcity();" >
                        <option value="">Select State</option>
	<?php foreach($states as $st){ ?>
                        
                        <option value="<?=$st['State']['state']?>"><?=$st['State']['state']?></option>
<?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>City:</label>
                <div class="col-sm-9">
                    <select class="col-xs-10 col-sm-5" name="city" id="city" >
                        <option value="">Select City</option>
         
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Zipcode:</label>
                <div class="col-sm-9">
                    <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Zipcode" id="pincode" name="pincode" value="">
                </div>
            </div> 
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Description:</label>
                <div class="col-sm-9">
                    <textarea name="description" id="description" rows="6" cols="30" placeholder="Description" class="col-xs-10 col-sm-5"></textarea>
                </div>
            </div>


            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" >Date Of Birth:</label>

                <div class="col-sm-9">

                    <select name="date_year" id="date_year" onchange="checkage();" class="col-xs-10 col-sm-2">
                        <option value="">Year</option>
            <?php $curyer=date('Y');
						for ($y = 1900; $y <= $curyer; $y += 1) {
				?>
                        <option value="<?=$y?>"><?=$y?></option>
            <?php  } ?>
                    </select>
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
                    <select name="date_month" id="date_month" onchange="checkage();" class="col-xs-10 col-sm-2">
                        <option value="">Select Month</option>
									<?php 
                 
						foreach($months as $mon=>$val){
						?>
                        <option value="<?=$mon?>" ><?php echo $val;?></option>
						<?php } ?>
                    </select>
                    <select name="date_day" id="date_day" onchange="checkage();" class="col-xs-10 col-sm-2">
                        <option value="">Day</option>
            <?php for ($d = 1; $d <= 31; $d++) {
			?>

                        <option value="<?=$d?>"><?=$d?></option>
            <?php  } ?>
                    </select>
                </div>
            </div>
            <h4 class="header blue bolder smaller">Personal Details</h4>
                <hr />
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Speciality:</label>
                <div class="col-sm-9">

                    <select class="col-xs-10 col-sm-5" name="specialty" id="specialty">
                        <option value="">Select Speciality</option>

                        <option value="Pediatric Dentistry">Pediatric Dentistry</option>
                        <option value="Orthodontics" >Orthodontics</option>
                        <option value="Optometry" >Optometry</option>
                        <option value="OB/GYN" >OB/GYN</option>
                        <option value="Dermatology" >Dermatology</option>
                        <option value="Chiropractics" >Chiropractics</option>
                        <option value="Plastic Surgery" >Plastic Surgery</option>
                        <option value="Family Medicine" >Family Medicine</option>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <div id="gendererror" style="padding-left:26%;color: red;"></div>
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Gender:</label>
                <div class="col-sm-9">
                    <div class="patientinfo_radio">
                        <input type="radio"  value="Male" name="gender" id="gender" class="" onclick="clearError()">
                        <label class=" control-label">Male</label>
                    </div>
                    <div class="patientinfo_radio">
                        <input type="radio"  value="Female" name="gender" id="gender" class="" onclick="clearError()">
                        <label class=" control-label">Female</label>
                    </div>
                </div>
            </div>
            <h4 class="header blue bolder smaller">Location Details</h4>
            <div  id="locationdiv">
                <input type="hidden" id="cnt" name="cnt" value="1">
                <div  id="locationno_1">




                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Location:</label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-5" name="location_1" id="location_1" onchange="checkloc('location_1', '1');">
                                <option value="">Select Location</option>
	<?php foreach($Locations as $loc){ ?>
                                <option value="<?=$loc['ClinicLocation']['id']?>" ><?=$loc['ClinicLocation']['address']?></option>
<?php } ?>
                            </select>
        <?php if(count($Locations)>1){ ?>
                            <div class="add_profile icon-1" onclick="addoptionmore();" id="addloc" title="Add Location"> &nbsp;Add</div>
                        </div>
        <?php } ?>
                    </div>




                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Choose Days:</label>
                        <div class="col-sm-9">
                            <strong>
                                <input type="checkbox" id="days_1_1" name="days_1[]" value="1" onclick="selcheck('days_1_1');">
                                <label>Monday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_2" name="days_1[]" value="2" onclick="selcheck('days_1_2');">
                                <label>Tuesday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_3" name="days_1[]" value="3" onclick="selcheck('days_1_3');">
                                <label>Wednesday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_4" name="days_1[]" value="4" onclick="selcheck('days_1_4');">
                                <label>Thursday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_5" name="days_1[]" value="5" onclick="selcheck('days_1_5');">
                                <label>Friday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_6" name="days_1[]" value="6" onclick="selcheck('days_1_6');">
                                <label>Saturday</label></strong>  <strong>
                                <input type="checkbox" id="days_1_7" name="days_1[]" value="7" onclick="selcheck('days_1_7');">
                                <label>Sunday</label></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <h4 class="header blue bolder smaller">Procedure Follow</h4>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Choose Procedure:</label>
                        <div class="col-sm-9">
                            <strong>
                                <?php if(!empty($Procedures)){ foreach($Procedures as $pro){ ?>
                                <input type="checkbox" id="procedures" name="procedures[]" value="<?php echo $pro['CharacteristicInsurance']['id']; ?>" >
                                <label><?php echo $pro['CharacteristicInsurance']['name']; ?></label>
                                <?php }}else{ ?>
                                <input type="hidden" >
                                <label>Add a procedure to proceed. <?php } ?>
                                </strong>
                        </div>
                    </div>
                
          
        </div>									</div>


    <div class="clearfix form-actions">

        <div class="col-md-offset-3 col-md-9">

            <input type="submit" value="Save Doctor" class="btn btn-info">
        </div></div>
</form>






<script language="Javascript">

function clearError(){
                        $("#gendererror").text('');
                    }
    $(document).ready(function() {


$.validator.addMethod("zipRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Zipcode must contain only alphanumeric.");

        $('#DoctorAddForm').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                first_name: "required",
                last_name: "required",
                degree:"required",
                specialty: "required",
                phone: {
                    required:true,number: true, minlength: 7, maxlength: 10
                },
                email: {
                    required: true, email: true
                },
                //gender: "required",
                address: "required",
                state: "required",
                city: "required",
                pincode: {
                    required: true,
                    zipRegex:true,
                                 minlength: 4 ,maxlength:6
                },
                description: "required"
            },
            // Specify the validation error messages
            messages: {
                first_name: "Please enter first name",
                last_name: "Please enter last name",
                degree:"Please select degree",
                specialty: "Please enter speciality",
                phone: {
                    required: "Please enter phone number",
                    number: "Please enter a valid phone number",
                    minlength: "Phone Number must be 7 to 10 characters long"
                },
                email: "Please enter a valid email address",
                //gender: "Please select gender",
                address: "Please enter a address",
                state: "Please select state",
                city: "Please select city",
                pincode: {
                    required: "Please enter zipcode",
                    zipRegex:"Please enter valid zipcode",
                    minlength: "Zipcode must be 4 to 6 characters long"
                },
                description: "Please enter description"
            },
            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
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
                
                var gender = $('input[name="gender"]:checked').length;
                if(gender){
                    
                }else{
                    $('#gendererror').text('Please select gender');
                    return false;
                }
                var cnt = $('#cnt').val();
                for (var i = 1; i <= cnt; i++) {
                    var loc_id_other = $('#location_' + i).val();

                    if (loc_id_other == '') {
                        alert('Please select location.');
                        $('#location_' + i).focus();
                        return false;
                    }
                    var i2 = 0;
                    for (var i1 = 1; i1 <= 7; i1++) {
                        if ($('#days_' + i + '_' + i1).is(":checked")) {

                        } else {
                            i2 = i2 + 1;
                        }
                    }
                    if (i2 == 7) {
                        alert('Please select atleast 1 day.');
                        $('#days_' + i + '_1').focus();
                        return false;
                    }
                }
//                var email = $('#email').val();
//                $.ajax({
//                    type: "POST",
//                    data: "email=" + email,
//                    url: "<?=Staff_Name?>Doctor/checkemail/",
//                    success: function(result) {
//                        if (result == 1) {
//
//                            alert('Doctor email already exist.');
//                            $('#email').focus();
//                            return false;
//                        } 
//                    }});
//                
                var checked = $('input[name="procedures[]"]:checked').length;
                if (!checked) {
                   alert('Please select atleast 1 Procedure.');
                   return false;
                }
                 $.ajax({
            type: "POST",
            data: "zip=" + $('#pincode').val()+"&state=" + $('#state').val(),
            url: "<?=Staff_Name?>ClinicLocation/checkzip/",
            success: function(result1) {
                if(result1==1){
                    form.submit();
                }else{
                    $("#pincode").focus();
                    alert('Pincode entered does not match the state');
                    return false;
                }
                
            }});
            }

        });
    });
    function getcity() {

        var state = $('#state').val();

        $.ajax({
            type: "POST",
            data: "state=" + state,
            url: "<?=Staff_Name?>PatientManagement/getcity/",
            success: function(result) {
                $('#city').html(result);
            }});




    }
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


        if ((days <= 4717) && (days >= 1)) {

            alert('Age should be greater then 13 year.');
            return false;
        }
        else {

            return true;
        }

    }
    function addoptionmore() {
        var cnt = $('#cnt').val();
        var inccnt = parseInt(cnt) + 1;


        $.ajax({
            type: "POST",
            data: "cnt=" + inccnt,
            url: "<?=Staff_Name?>Doctor/getlocation/",
            success: function(result) {
                $('#locationdiv').append(result);
                $('#cnt').val(inccnt);
                if (<?php echo count($Locations); ?> == inccnt) {
                    $("#addloc").css("display", "none");
                }
            }});

    }
    function removeoption(id) {

        var cnt = $('#cnt').val();
        var deccnt = parseInt(cnt) - 1;
        $('#' + id).remove();
        $('#cnt').val(deccnt);
        $("#addloc").css("display", "block");
    }
    function checkloc(id, id1) {
        var cnt = $('#cnt').val();
        var loc_id = $('#' + id).val();

        for (var i = 1; i <= cnt; i++) {
            var loc_id_other = $('#location_' + i).val();

            if (loc_id == loc_id_other && id1 != i) {
                alert('You have already selected the location. Please select other location');

                $('#' + id).find('option').prop('selected', false);
                return false;
            }
        }

    }
    function selectdefault(){
           var loc_id = $('#location_default').val();

        $.ajax({
            type: "POST",
            data: "location_id=" + loc_id,
            url: "<?=Staff_Name?>Doctor/getprimlocation/",
            success: function(msg) {
                obj = JSON.parse(msg);
                $('#phone').val(obj.phone);
                $('#email').val(obj.email);
                $('#address').val(obj.address);
                $('#state').empty();
                $('#state').append(obj.state);
                $('#city').empty().append('<option value="">Select City</option>');
                $('#city').append(obj.city);
                $('#pincode').val(obj.zipcode);
               
               
            }});

    }
    function selcheck(id) {
        var res = id.split("_");
        var cnt = $('#cnt').val();
        for (var i = 1; i <= cnt; i++) {


            if ($('#days_' + i + '_' + res[2]).prop('checked') && i != res[1]) {
                alert('You have already selected the day.Please select other day');

                $('#' + id).prop('checked', false);
                return false;
            }
        }
    }
    function removeimg(filename, aname) {
        $('#' + filename).val('');
        $('#' + aname).text('');
        $('#' + aname).removeClass('icon-top hand-icon');
    }
    function checkimg(filename, aname) {
        var sluval = $('#' + filename).val();
        if (sluval != '') {
            $('#' + aname).text('x');
            $('#' + aname).addClass('icon-top hand-icon');
        }
    }
</script>

