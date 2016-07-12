<style>
    @media (max-width:767px){
        .help-block{
            clear: both;
        }
    }
</style>
<?php

$sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-users"></i>
            Staff
        </h1>
    </div>
     <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>

    <form accept-charset="utf-8" method="post" id="UserStaff" class="form-horizontal" action="/UserStaffManagement/add/">
        <div id="edit-basic" class="tab-pane in active">
            <h4 class="header blue bolder smaller">General</h4>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>First Name</label>

                <div class="col-sm-9">

                    <input type="text"  maxlength="20" class="col-xs-10 col-sm-5" placeholder="First Name" required="required" id="first_name" name="first_name" value="">

                    <input type="hidden" id="clinic_id"  name="clinic_id" value="<?php echo $sessionstaff['clinic_id']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Last Name</label>

                <div class="col-sm-9">

                    <input type="text"  maxlength="20" class="col-xs-10 col-sm-5" placeholder="Last Name" required="required" id="last_name" name="last_name" value="">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Email</label>

                <div class="col-sm-9">

                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Email" required="required" id="staff_email" name="staff_email" value="">
                </div>
            </div>


            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" >Date Of Birth:</label>

                <div class="col-sm-9">

                    <select name="date_year" id="date_year" class="col-xs-10 col-sm-2">
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
                    <select name="date_month" id="date_month"  class="col-xs-10 col-sm-2">
                        <option value="">Select Month</option>
									<?php 
                 
						foreach($months as $mon=>$val){
						?>
                        <option value="<?=$mon?>" ><?php echo $val;?></option>
						<?php } ?>
                    </select>
                    <select name="date_day" id="date_day" class="col-xs-10 col-sm-2">
                        <option value="">Day</option>
            <?php for ($d = 1; $d <= 31; $d++) {
				 ?>
                        <option value="<?=$d?>"><?=$d?></option>
            <?php }  ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>UserName</label>

                <div class="col-sm-9">
                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="UserName" required="required" id="staff_id" name="staff_id" value="">
                </div>
            </div>  
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Staff Role</label>

                <div class="col-sm-9">
                    <?php if($sessionstaff['is_buzzydoc']==1){ ?>
                    <select name="staff_role" id="staff_role" class="col-xs-10 col-sm-5" onchange="getpaydiv()">
                    <?php }else{ ?>
                        <select name="staff_role" id="staff_role" class="col-xs-10 col-sm-5">
                    <?php } ?>
                            <option value="Administrator" >Administrator</option>
                            <option value="Manager" >Manager</option>
                            <option value="Doctor" <?php if(isset($this->request->pass[0]) && $this->request->pass[0]==1){ echo "selected"; } ?>>Super Doctor</option>
                        </select>
                </div>
            </div> 
            <h4 class="header blue bolder smaller">Create Password</h4>  
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>New Password</label>

                <div class="col-sm-9">
                    <input type="password"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="New Password" id="new_password" name="new_password" value=""  required="required">
                </div>
            </div>     
            <div class="form-group">
                <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Verify Password</label>

                <div class="col-sm-9">
                    <input type="password"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Verify Password" id="new_password2" name="new_password2" value=""  required="required">
                </div>
             
            </div>  
        </div>
        <div class="clearfix form-actions" >
            <div class="col-md-offset-3 col-md-9" id="ajax-super-dr-id">

                <input type="submit" value="Save" class="btn btn-info" onclick="return checkcurrentpassword();">

            </div>     </div>

    </form>


</div>
<input type="hidden" value="0" id="checkpayment" name="checkpayment" > 


<script>
    
    var checkref1 = $('#checkpayment').val();
        var staff_role1 = $('#staff_role').val();

 
    function validatenumber(number) {
        var re = /^([0-9])+$/;
        return re.test(number);
    }
    function validateEmail(email) {
        var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return re.test(email);
    }
 

    $("#agree").click(function() {
        if ($("#agree:checked").length > 0) {
            $("#agreeBtn").removeClass("primary-btn submit-btn disable-btn");
            $("#agreeBtn").addClass("primary-btn submit-btn")

        } else {
            $("#agreeBtn").removeClass("primary-btn submit-btn");
            $("#agreeBtn").addClass("primary-btn submit-btn disable-btn")

        }
    });


    $("#agreeBtn").click(function() {
        if ($("#agree:checked").length > 0) {
            $(".remodal-close").trigger('click');
            $('form#UserStaff').submit();


        } else {
            $("#ajax-super-dr-id").html("<a href='javascript:void(0)' class='top-button btn btn-info' title='Proceed' id='proceed-id' onclick='proceed();'> Proceed</a>");
        }
    });


    var staff_role = $('#staff_role').val();
 
    function getpaydiv() {
        var staff_id = $('#staff_id').val();
        var staff_email = $('#staff_email').val();
        var staff_role = $('#staff_role').val();
        datasrc = 'staff_id=' + staff_id + '&staff_email=' + staff_email + '&staff_role=' + staff_role;
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>UserStaffManagement/checkapiuser/",
            success: function(result) {

                if (result == 1) {
                    alert('Staff already exist.');
                    return false;
                }
                else if (result == 2) {
                    alert('Email already exist.');
                    return false;
                }
                else if (result == 3) {
                    alert('Super doctor already exist.');
                    return false;
                }
                else {
                    
                }
            }
        });

    }
    function checkcurrentpassword() {

        var staff_role = $('#staff_role').val();


        var staff_id = $('#staff_id').val();
        var staff_email = $('#staff_email').val();
        if(staff_email!=''){
        datasrc = 'staff_id=' + staff_id + '&staff_email=' + staff_email + '&staff_role=' + staff_role;
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>UserStaffManagement/checkapiuser/",
            success: function(result) {

                if (result == 1) {
                    alert('Staff already exist.');
                    return false;
                }
                else if (result == 2) {
                    alert('Email already exist.');
                    return false;
                }
                else if (result == 3) {
                    alert('Super doctor already exist.');
                    return false;
                }
                else {
                    window.onbeforeunload = null;
                    $("#UserStaff").submit();
                    return true;
                }
            }
        });
        return false;

}
    }
    $(document).ready(function() {
        $('#UserStaff').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                first_name: "required",
                last_name: "required",
                staff_id: "required",
                staff_email: {
                    required: true,
                    email: true
                },
               
                new_password: {
                    minlength: 6
                },
                new_password2: {
                    equalTo: "#new_password", minlength: 6
                },
                routing_number: {
                    required: true
                },
                account_number: {
                    required: true,
                    number: true,
                    minlength: 12,
                    maxlength: 16
                },
                confirm_account_number: {
                    equalTo: "#account_number"
                },
                bank_name: {
                    required: true,
                },
                customer_name: {
                    required: true,
                }


            },
            // Specify the validation error messages
            messages: {
                new_password: {
                    minlength: "Your password must be at least 6 characters long"
                },
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                staff_email: {
                    required: "Please enter your email",
                    email: "Please enter your valid email"
                },
               
                staff_id: "Please enter your username",
                routing_number: {
                    required: "Please enter routing number"
                },
                account_number: {
                    required: "Please enter account number",
                    number: "Invalid account number",
                    minlength: "account number should be 12 charecter long"
                },
                bank_name: {
                    required: "Please enter bank name",
                },
                customer_name: {
                    required: "Please enter customer name",
                }

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
                var checkref = $('#checkpayment').val();
                var staff_role = $('#staff_role').val();
                if (<?php echo $cntpayemnt; ?> == 0 && checkref == 0 && staff_role == 'Doctor' && <?php echo $sessionstaff['is_buzzydoc']; ?> == 1) {
                    alert('Please add payment and shipping info before submit');
                    return false;
                } else if (<?php echo $cntpayemnt; ?> == 0 && checkref == 1 && staff_role == 'Doctor' && <?php echo $sessionstaff['is_buzzydoc']; ?> == 1) {
                    alert('Please refresh the page');
                    return false;
                } else {
                    var acntprfid = $('input:radio[name=customer_account_profile_id]').filter(":checked").val();
                    if (acntprfid === undefined && staff_role == 'Doctor' && <?php echo $sessionstaff['is_buzzydoc']; ?> == 1) {
                        alert('Please Choose Default Account');
                        return false;
                    } else {
                        form.submit();
                    }
                }

            }


        });
    });

</script>





