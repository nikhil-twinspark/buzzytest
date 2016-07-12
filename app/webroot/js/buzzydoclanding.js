///////////////////////
var base_url = window.location.origin;
$(document).on("click", "#custom_date", function() {
    $("#date_pick").show();
    $("#date_pick").datepicker("destroy");
    var dateToday = new Date();
    $("#date_pick").datepicker({
        maxDate: 0,
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:' + dateToday.getFullYear(),
        onSelect: function(e) {
            $("#custom_date").val(e);
            $('#signup-error-msg').text('');
            $.ajax({
                type: "POST",
                url: base_url + "/buzzydoc/getcheckage",
                data: "custom_date=" + $('#custom_date').val(),
                success: function(msg) {
                    $('#email_field').html(msg);
                }
            });
            $(this).toggle();
        },
    });
});
$(".login-form").click(function(e) {

    var container = $("#custom_date");
    var container2 = $("#date_pick");
    if (!container.is(e.target) &&
            container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0) {

        if ($("#date_pick").css('display') == 'block') {
            $("#date_pick").toggle();
        }
    }

});
function currentDateUserDateDiff(enddate) {  //difference between current and user date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var st = dd + '-' + mm + '-' + yyyy; // this is current date(dd-mm-yyy)
    var st1 = st.split('-');
    var end = enddate; // put end date here(dd-mm-yyyy)
    var end1 = end.split('-');
    var date1 = st1[2] + "-" + st1[1] + "-" + st1[0]; // YYYY-mm-dd
    var date2 = end1[2] + "-" + end1[1] + "-" + end1[0];
    date1 = date1.split('-');
    date2 = date2.split('-');
    date1 = new Date(date1[0], date1[1], date1[2]);
    date2 = new Date(date2[0], date2[1], date2[2]);
    var date1_unixtime = parseInt(date1.getTime() / 1000);
    var date2_unixtime = parseInt(date2.getTime() / 1000);
    var timeDifference = date2_unixtime - date1_unixtime;
    var timeDifferenceInHours = timeDifference / 60 / 60;
    var timeDifferenceInDays = timeDifferenceInHours / 24;
    if (timeDifferenceInDays == 0) {
        return 0; // both same date
    } else if (timeDifferenceInDays > 0) {
        return 1; // user date big then current date
    } else {
        return -1; // user date small  then current date
    }

}


function gocursor() {

    setTimeout('$("#first_name").focus()', 30);
}




$(".top-button").click(function() {
    $("#username").val('');
    $("#password").val('');
    $("#error-msg").text('');
});
function validateZip(email) {
    var re = /^([a-zA-Z0-9])+$/;
    return re.test(email);
}
$(document).on("click", "#signUpBtn", function() {

    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $("#email").val();
    var actionType = $("#actionType").val();
    var dob_status = currentDateUserDateDiff($("#custom_date").val());
    if ($("#card_number").val() == '') {
        $("#card_number").focus();
        $("#signup-error-msg").text("Card Number cannot be blank");
    }
    else if ($("#first_name").val() == '') {
        $("#first_name").focus();
        $("#signup-error-msg").text("First name cannot be blank");
    } else if ($("#last_name").val() == '') {
        $("#last_name").focus();
        $("#signup-error-msg").text("Last name cannot be blank");
    } else if ($("#signup-password").val() == '') {
        $("#signup-password").focus();
        $("#signup-error-msg").text("Password cannot be blank");
    } else if ($("#signup-password").val().length < 6) {
        $("#signup-password").focus();
        $("#signup-error-msg").text("Password should be atleast six characters");
    } else if ($("#signup-confirm-password").val() == '') {
        $("#signup-confirm-password").focus();
        $("#signup-error-msg").text("Confirm password cannot be blank");
    } else if ($("#signup-password").val() != $("#signup-confirm-password").val()) {
        $("#signup-confirm-password").focus();
        $("#signup-error-msg").text("Password do not match");
    } else if ($('input[name=gender]:checked').length <= 0) {
        $("#gender").focus();
        $("#signup-error-msg").text("Please select gender");
    } else if ($("#custom_date").val() == '') {
        $("#custom_date").focus();
        $("#signup-error-msg").text("Date of birth cannot be blank");
    } else if (dob_status >= 0) {
        $("#custom_date").focus();
        $("#signup-error-msg").text("Invalid date of birth");
    } else if ($("#email").val() == '') {
        $("#email").focus();
        $("#signup-error-msg").text("Email cannot be blank");
    } else if (!regex.test(email)) {
        $("#email").focus();
        $("#signup-error-msg").text("Invalid email.");
    }
    else if ($("#parents_email").val() == '') {

        $("#parents_email").focus();
        $("#signup-error-msg").text("Username cannot be blank.");
    }
    else if ($("#parents_email").val() == $("#email").val() && $("#parents_email").val() != '') {
        $("#parents_email").focus();
        $("#signup-error-msg").text("Email and Username should be different.");
    }
    else if ($("#street1").val() == '' && actionType == 'record_new_account') {
        $("#street1").focus();
        $("#signup-error-msg").text("Address cannot be blank");
    } else if ($("#state").val() == '' && actionType == 'record_new_account') {
        $("#state").focus();
        $("#signup-error-msg").text("State cannot be blank");
    } else if ($("#city").val() == '' && actionType == 'record_new_account') {
        $("#city").focus();
        $("#signup-error-msg").text("City cannot be blank");
    } else if ($("#postal_code").val() == '' && actionType == 'record_new_account') {
        $("#postal_code").focus();
        $("#signup-error-msg").text("Zip code cannot be blank");
    } else if ($("#postal_code").val().length < 4 && actionType == 'record_new_account') {
        $("#postal_code").focus();
        $("#signup-error-msg").text("Zip code should be 4 to 6 digits");
    } else if (!validateZip($("#postal_code").val()) && actionType == 'record_new_account') {
        $("#postal_code").focus();
        $("#signup-error-msg").text("Please enter valid Zip code.");
    }
    else if ($("#phone").val() == '' && actionType == 'record_new_account') {
        $("#phone").focus();
        $("#signup-error-msg").text("Phone number cannot be blank");
    } else if ($("#phone").val().length < 7 && actionType == 'record_new_account') {
        $("#phone").focus();
        $("#signup-error-msg").text("Phone number should be 7 to 10 digits");
    } else {
        $("#signup-progress").html('<img alt="" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
        $("#signup-progress1").html('<img alt="" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
        var form_element_data = new Array();
        form_element_data[0] = $('#first_name').val();
        form_element_data[1] = $('#last_name').val();
        form_element_data[2] = $('#custom_date').val();
        form_element_data[3] = $('#email').val();
        if ($('#parents_email').length > 0) {
            form_element_data[4] = $('#parents_email').val();
            form_element_data[15] = 'child';
        } else {
            form_element_data[4] = '';
            form_element_data[15] = 'parent';
        }
        if ($('input[name=emailprovide]:checked').val() != '') {
            form_element_data[16] = $('input[name=emailprovide]:checked').val();
        }

        form_element_data[5] = $('#phone').val();
        form_element_data[6] = $('#street1').val();
        form_element_data[7] = $('#street2').val();
        form_element_data[8] = $('#state').val();
        form_element_data[9] = $('#city').val();
        form_element_data[10] = $('#postal_code').val();
        form_element_data[11] = $('input[name=gender]:checked').val();
        form_element_data[12] = $('#signup-password').val();
        form_element_data[17] = $('#clinic_id').val();
        form_element_data[18] = $('#card_number').val();
        form_element_data[19] = actionType;
        $.ajax({
            type: "POST",
            url: base_url + "/buzzydoc/buzzysignup",
            data: "jsonData=" + JSON.stringify(form_element_data),
            dataType: "json",
            success: function(msg) {
                $("#signup-progress").html('');
                $("#signup-progress1").html('');
                if (msg.data == 'Sign Up completed, use your credentials for login.') {
                    $('#signUpBtn').attr('disabled', true);
                    $("#signup-error-msg").text(msg.data);
                    window.location.href = "/dashboard";
                } else {
                    $('#signUpBtn').attr('disabled', false);
                    if (msg.data == null) {
                        $("#signup-error-msg").text("Form submitted successfully. An email is sent to the parent\'s account for approval.");
                    } else {
                        $("#signup-error-msg").text(msg.data);
                    }
                }

            }
        });
    }
});
function clearError() {
    $("#signup-error-msg").text('');
}
function clearMsg(ptr) {
    $("#" + ptr).text('');
}


$(document).ready(function() {

    $("#password").keypress(function(e) {
        if (e.which == 13) {
// var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var email = $("#username").val();
            if ($("#username").val() == '') {
                $("#username").focus();
                $("#error-msg").text("Username cannot be blank");
            }
//            else if (!regex.test(email)) {
//                $("#username").focus();
//                $("#error-msg").text("Invalid Username!");
//            }
            else if ($("#password").val() == '') {
                $("#password").focus();
                $("#error-msg").text("Password cannot be blank");
            } else {
                $("#sign-progress").html('<img alt="Please wait" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
                $.ajax({
                    type: "POST",
                    url: base_url + "/buzzydoc/buzzysignin",
                    data: "username=" + $('#username').val() + "&password=" + $('#password').val() + "&data[_Token][key]=" + $("input[name='data[_Token][key]']").val(),
                    success: function(msg) {
                        $("#sign-progress").html('');
                        if ($.trim(msg) == 1) {
                            window.location = base_url + "/buzzydoc/dashboard/";
                        } else if ($.trim(msg) == 3) {
                            $("#error-msg").text("Waiting on parent\'s email confirmation.!");
                        } else if ($.trim(msg) == 4) {
                            $("#error-msg").text("Your Account has been blocked.Please contact to buzzydoc admin.");
                        } else if ($.trim(msg) == 2) {
                            $("#error-msg").text("Incorrect Password!");
                        } else {
                            $("#error-msg").text("Looks like you attempted to pass that request incorrectly. Please refresh the page and try again.");
                        }

                    }
                });
            }
        }

    });
});
//$(document).ready(function(){
//         $("#email").keypress(function(e) {
//        if(e.which == 13) {
//       if ($("#email").val() == '') {
//        $("#email").focus();
//        $("#error-msg-forgot").text("Email Id or Username cannot be blank");
//    }
//     else {
//        $("#forgot-progress").html('<img alt="Please wait" title="BuzzyDoc" src="/img/images_buzzy/loading.gif">');
//        $.ajax({
//            type: "POST",
//            url: base_url + "/buzzydoc/forgotpassword",
//            data: "email=" + $('#email').val()+"&cardnumber="+$("#cardcheck").val(),
//            success: function(msg) {
//                $("#forgot-progress").html('');
//                $("#error-msg-forgot").text(msg);
//            }
//        });
//    }
//    }
//
//  });
//});

$(document).on("click", "#submitBtn", function() {

    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $("#username").val();
    if ($("#username").val() == '') {
        $("#username").focus();
        $("#error-msg").text("Username cannot be blank");
    }
//            else if (!regex.test(email)) {
//                $("#username").focus();
//                $("#error-msg").text("Invalid Username!");
//            }
    else if ($("#password").val() == '') {
        $("#password").focus();
        $("#error-msg").text("Password cannot be blank");
    } else {
        $("#sign-progress").html('<img alt="Please wait" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
        $.ajax({
            type: "POST",
            url: base_url + "/buzzydoc/buzzysignin",
            data: "username=" + $('#username').val() + "&password=" + $('#password').val() + "&data[_Token][key]=" + $("input[name='data[_Token][key]']").val(),
            success: function(msg) {
                //alert(msg);return false;
                $("#sign-progress").html('');
                if ($.trim(msg) == 1) {
                    window.location = base_url + "/buzzydoc/dashboard/";
                } else if ($.trim(msg) == 3) {
                    $("#error-msg").text("Waiting on parent\'s email confirmation.!");
                } else if ($.trim(msg) == 4) {
                    $("#error-msg").text("Your Account has been blocked.Please contact to buzzydoc admin.");
                } else if ($.trim(msg) == 2) {
                    $("#error-msg").text("Incorrect Password!");
                } else {
                    $("#error-msg").text("Looks like you attempted to pass that request incorrectly. Please refresh the page and try again.");
                }

            }
        });
    }
});
function checkuserexist() {
    $('#forgotBtn').attr('disabled');
    var email = $('#femail').val();
    if ($.trim(email) != '') {
        $.ajax({
            type: "POST",
            data: "email=" + email,
            url: "/buzzydoc/checkuser/",
            success: function(result) {
                $('#forgotBtn').removeAttr("disabled");
                $('#cardnumber').html(result);
            }
        });
    }
}

$(document).on("click", "#forgotBtn", function() {
    checkuserexist();
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $("#femail").val();
    var card = $("#cardcheck").val();
    if ($.trim($("#femail").val()) == '') {
        $("#femail").focus();
        $("#error-msg-forgot").text("Email Id cannot be blank");
    }
    else if (!regex.test(email)) {
        $("#femail").focus();
        $("#error-msg-forgot").text("Invalid Email Id.");
    }
    else if (card != undefined && card == '') {
        $("#cardcheck").focus();
        $("#error-msg-forgot").text("Select User.");
    }
    else {
        $("#forgot-progress").html('<img alt="Please wait" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
        $.ajax({
            type: "POST",
            url: base_url + "/buzzydoc/forgotpassword",
            data: "email=" + $('#femail').val() + "&cardnumber=" + $("#cardcheck").val(),
            success: function(msg) {
                $("#forgot-progress").html('');
                $("#error-msg-forgot").text(msg);
            }
        });
    }
});
function getCity(ptr) {
    if (ptr != '') {
        $("#state-progress").html('<img alt="Please wait" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
        $.ajax({
            type: "POST",
            url: base_url + "/buzzydoc/getcity",
            data: "state_code=" + $('#state').val(),
            success: function(msg) {
                $("#state-progress").html('');
                $("#city").html(msg);
            }
        });
    }

}
function searchClinic() {

    var type = $('#search_type').val();
    $.ajax({
        type: "POST",
        url: "/Buzzydoc/getSearchPractice",
        data: {"type": type},
        success: function(data) {
            $('#search_practice').html(data);
        }
    });
    return false;
}
function selectClinic() {

    var practice = $('#search_practice').val();
    $('#clinic_id').val(practice);
    $.ajax({
        type: "POST",
        url: "/Buzzydoc/getPractice",
        data: {"practice": practice},
        success: function(data) {
            $('#send_card_number').val(data);
        }
    });
    return false;
}
$(document).on("click", "#proceedBtn", function() {
    if ($("#search_type").val() == '') {
        $("#search_type").focus();
        $("#card-error-msg").text("Please select practice type");
    } else if ($("#search_practice").val() == '') {
        $("#search_practice").focus();
        $("#card-error-msg").text("Please select practice name");
    } else {
        $('#clinic_id').val($('#search_practice').val());
        $('#card_number').val($('#send_card_number').val());
        if ($('#send_card_number').val() != '') {
            $("#card_number").attr("readonly", "readonly");
        }
        $.ajax({
            type: "POST",
            url: "/Buzzydoc/getQuestionCard",
            data: {"practice": $('#search_practice').val()},
            success: function(data) {
                if (data != '') {
                    $("#card_number").removeAttr("readonly");
                    $('.helpicon').css('display', 'inline-block');
                    $('#Style').html(data);
                } else {
                    $("#card_number").attr("readonly", "readonly");
                    $('.helpicon').css('display', 'none');
                }
            }
        });
        $('#search_type').val('');
        $('#search_practice').val('');
        $('#main-sign-up').modal("hide");
        $('#main-sign-up-form').modal("show");
    }
});
function checkpatientexist() {

    var datasrc = '';
    var dob = $('#custom_date').val();
    datasrc = 'dob=' + dob + '&clinic_id=' + $('#clinic_id').val();
    var email = $('#email').val();
    if (email != '') {
        datasrc = datasrc + "&email=" + email;
    }
    if ($('#parents_email').val() != undefined && $('#parents_email').val() != '') {
        var pemail = $('#parents_email').val();
        datasrc = datasrc + "&parents_email=" + pemail;
    }
    if (datasrc != '') {
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "/Buzzydoc/checkuserexist/",
            success: function(result) {
                if (result == 1) {
                    $('#forLink').css('display', 'none');
                    $('#hid_submit').css('display', 'none');
                    $('#emailexistlink').css('display', 'block');
                    $('#actionType').val('link');
                } else {
                    $('#forLink').css('display', 'block');
                    $('#hid_submit').css('display', 'block');
                    $('#emailexistlink').css('display', 'none');
                    $('#actionType').val('record_new_account');
                }
            }
        });
    }
    return false;
}

$(document).on("click", "#signFbUpBtn", function() {

    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $("#email").val();
    var actionType = $("#actionType").val();
    var dob_status = currentDateUserDateDiff($("#custom_date").val());
    $("#fb-signup-progress").html('<img alt="" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
    $("#fb-signup-progress1").html('<img alt="" title="BuzzyDoc" src="https://dvpizcali3m6.cloudfront.net/img/images_buzzy/loading.gif">');
    var form_element_data = new Array();
    form_element_data[0] = $('#first_name').val();
    form_element_data[1] = $('#last_name').val();
    form_element_data[2] = $('#custom_date').val();
    form_element_data[3] = $('#email').val();
    form_element_data[4] = '';
    form_element_data[15] = 'parent';
    form_element_data[16] = '';
    form_element_data[5] = '';
    form_element_data[6] = '';
    form_element_data[7] = '';
    form_element_data[8] = '';
    form_element_data[9] = '';
    form_element_data[10] = '';
    form_element_data[11] = $('#gender').val();
    form_element_data[12] = $('#fb_password').val();
    form_element_data[17] = $('#clinic_id').val();
    form_element_data[18] = $('#send_card_number').val();
    form_element_data[19] = actionType;
    form_element_data[20] = $('#facebook_id').val();
    form_element_data[21] = $('#is_facebook').val();
    $.ajax({
        type: "POST",
        url: base_url + "/buzzydoc/buzzysignup",
        data: "jsonData=" + JSON.stringify(form_element_data),
        dataType: "json",
        success: function(msg) {
            $("#fb-signup-progress").html('');
            $("#fb-signup-progress1").html('');
            if (msg.data == 'Sign Up completed, use your credentials for login.') {
                $('#signFbUpBtn').attr('disabled', true);
                $("#fb-signup-error-msg").text(msg.data);
                window.location.href = "/dashboard";
            } else {
                $('#signFbUpBtn').attr('disabled', false);
                if (msg.data == null) {
                    $("#fb-signup-error-msg").text("Form submitted successfully. An email is sent to the parent\'s account for approval.");
                } else {
                    $("#fb-signup-error-msg").text(msg.data);
                }
            }

        }
    });
});
