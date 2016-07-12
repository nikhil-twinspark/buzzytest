<style>
    @media (max-width:767px){
        .help-block{
            clear: both;
        }
    }
</style>
<?php
echo $this->Html->css(CDN.'css/jquery.remodal.css');
$sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-users"></i>
            Payment Details
        </h1>
    </div>
    <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    
    ?>
<?php echo $this->Html->script(CDN.'js/jquery.remodal-min.js'); ?>




    <form accept-charset="utf-8" method="post" id="UserStaff" class="form-horizontal" action="/UserStaffManagement/paymentdetails">
        <div id="edit-basic" class="tab-pane in active">

 <input type="hidden"  id="staff_id" name="staff_id" value="<?php echo $staff_id; ?>">


            <div id="docpay" style="display: block;">

        <?php 

         $cntpayemnt=0;

            ?>


            <?php if($PaymentDetails['PaymentDetail']['id']==''){ 
                $cntpayemnt=0;
                 ?>

                <div id="paymentdiv">


                    <input type="hidden"  id="customer_account_id" name="customer_account_id" value="<?php echo mt_rand(100000, 999999); ?>">


                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Account Description</label>
                        <div class="col-sm-9">
                            <input type="text" value="" maxlength="50" class="col-xs-10 col-sm-5" placeholder="Account Description" id="account_description" name="account_description" >
                        </div>
                    </div> 

                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Account Email-Id</label>
                        <div class="col-sm-9">
                            <input type="text" value="" maxlength="50" class="col-xs-10 col-sm-5" placeholder="Account Email-Id" id="account_email" name="account_email">
                        </div>
                    </div> 
                </div>
            <?php }else{ ?>


                    <?php
                 $requestfetch = new AuthorizeNetCIM;

//$response=$requestfetch->deleteCustomerProfile('31601301');die;
$responsefetch = $requestfetch->getCustomerProfile($PaymentDetails['PaymentDetail']['customer_account_id']);

$cntpayemnt=count($responsefetch->xml->profile->paymentProfiles);

if($cntpayemnt>0){ ?>

                <div class="form-group Clearfix">
                    <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Choose Default Account</label>
                    <div class="col-sm-9 col-xs-12"> <?php
 foreach($responsefetch->xml->profile->paymentProfiles as $paymentoptions){
     if(isset($paymentoptions->payment->bankAccount)){
         $acndet=$paymentoptions->payment->bankAccount->accountNumber;
         $paymentid=$paymentoptions->customerPaymentProfileId;
     }else{
         $acndet=$paymentoptions->payment->creditCard->cardNumber;
         $paymentid=$paymentoptions->customerPaymentProfileId;
     }
?>
                        <input type="hidden" value="<?php echo $PaymentDetails['PaymentDetail']['id']; ?>" id="payment_id" name="payment_id" >
                        <input type="radio" value="<?php echo $paymentid; ?>" name="customer_account_profile_id" id="customer_account_profile_id" <?php if( $PaymentDetails['PaymentDetail']['customer_account_profile_id']==$paymentid){ echo "checked"; } ?>> <?php echo $acndet; ?>


            <?php } ?>
                    </div>
                </div><div class="form-group">
                    &nbsp;</br>
                    </br>
                    </br>
                    </div> <?php 
}else{
    ?>
                <div class="form-group">
                    &nbsp;</br>
                    </br>
                    </br>
                    </div>
                <script>
                    $('#checkpayment').val(0);

                </script>
                    <?php
}}  ?>
            </div>
        </div>
        <?php //} ?>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9" id="ajax-super-dr-id">
                <?php if( ($PaymentDetails['PaymentDetail']['id']=="") && ($sessionstaff['is_buzzydoc']==1)){ ?>
                <a href='javascript:void(0)' class='top-button btn btn-info' title='Proceed' id='proceed-id' onclick='proceed();'> Proceed</a>

                <?php }else{ ?>
                <input type="submit" value="Save" class="btn btn-info" onclick="return checkcurrentpassword();" >
                <?php } ?>
            </div>
        </div>


</div>
</form>
</div>
<input type="hidden" value="0" id="checkpayment" name="checkpayment" >
<div id="payemtopt" style="position: relative;">

<?php

        if(isset($top)){
            $mrgtop=$top;
        }else{
            $mrgtop='76';
        }
if(isset($PaymentDetails['PaymentDetail']['id']) && ($PaymentDetails['PaymentDetail']['id']!='') && ($sessionstaff['is_buzzydoc']==1)){ 
               
                $request = new AuthorizeNetCIM;
                $setarray=array('hostedProfileReturnUrl'=>'http://' . $_SERVER['HTTP_HOST'].'/UserStaffManagement/paymentdetails','hostedProfileReturnUrlText'=>'Return Back to Payment details page');
        $response = $request->getHostedProfilePageRequest($PaymentDetails['PaymentDetail']['customer_account_id'],$setarray);
        echo '<form method="post" action="'.AUTHORIZENET_URL.'" id="formAuthorizeNetPage"><input type="hidden" name="token" value="' . $response->xml->token . '"></form>';
       
        ?>
    <button type="button" id="payadd" onclick= "steref(), document.getElementById('formAuthorizeNetPage').submit();" style="cursor: pointer;display: inline-block;position: absolute;left: 156px;top: <?php echo $mrgtop; ?>px !important;">Manage payment and shipping info.</button>
    <?php
            }
            ?>

</div>
<script>
    var checkref1 = $('#checkpayment').val();
    var cntPayment = "<?php echo $cntpayemnt; ?>";
    var is_buzzydoc = "<?php echo $sessionstaff['is_buzzydoc']; ?>";
    if (cntPayment == 0 && checkref1 == 0 && is_buzzydoc == 1) {

        $('#payadd').focus();
        window.onbeforeunload = function(event) {
            var message = 'Important: Please add payment and shipping info before leaving this page.';
            if (typeof event == 'undefined') {
                event = window.event;
            }
            if (event) {
                event.returnValue = message;
            }
            return message;
        };

        $(function() {
            $("a").not('#lnkLogOut').click(function() {
                window.onbeforeunload = null;
            });

        });

    } else {
        $('#customer_account_profile_id').focus();
        var acntprfid1 = $('input:radio[name=customer_account_profile_id]').filter(":checked").val();
        if (acntprfid1 === undefined && is_buzzydoc == 1) {


            window.onbeforeunload = function(event) {
                var message = 'Important: Please Choose Default Account and click on \'Save\' button before leaving this page.';
                if (typeof event == 'undefined') {
                    event = window.event;
                }
                if (event) {
                    event.returnValue = message;
                }
                return message;
            };

            $(function() {
                $("a").not('#lnkLogOut').click(function() {
                    window.onbeforeunload = null;
                });

            });
        }

    }





    function steref() {
        $('#checkpayment').val(1);
    }
    function validateEmail(email) {
        var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return re.test(email);
    }
    function proceed() {
        if ($("#account_description").val() == '') {
            alert('Please enter account description.');
            $("#account_description").focus();
        } else if ($("#account_email").val() == '') {
            alert('Please enter account email-id.');
            $("#account_email").focus();
        } else if (!validateEmail($("#account_email").val())) {
            alert('Please enter valid account email-id.');
            $("#account_email").focus();
        } else {
            var id = $('#staff_id').val();
            datasrc = 'id=' + id + '&acnt_id=' + $("#customer_account_id").val() + '&acn_desc=' + $("#account_description").val() + '&acn_email=' + $("#account_email").val() + '&st=1';
            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>UserStaffManagement/paymentsave/",
                success: function(result) {
                    if (result != '') {
                        $('#paymentdiv').html('<h4 class="header blue bolder smaller">Payment Details</h4><div class="form-group Clearfix">&nbsp;</div>');
                        $('#payemtopt').html(result);
                        $("#ajax-super-dr-id").html("<input type='submit' value='Save' class='btn btn-info' onclick='return checkcurrentpassword();'>");
                    } else {
                        alert('Already exist');
                    }

                }
            });

        }
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



    function checkcurrentpassword() {
        var checkref = $('#checkpayment').val();

        if (cntPayment == 0 && checkref == 0 && is_buzzydoc == 1) {
            alert('Please add payment and shipping info before submit');
            return false;
        } else if (<?php echo $cntpayemnt; ?> == 0 && checkref == 1 && is_buzzydoc == 1) {
            var r = confirm("Click on OK to refresh the page.");
            if (r == true) {
                location.reload();
            } else {
                return false;
            }
        } else {
            var acntprfid = $('input:radio[name=customer_account_profile_id]').filter(":checked").val();
            if (acntprfid === undefined && is_buzzydoc == 1) {
                alert('Please Choose Default Account');
                return false;
            }
        }
    }


</script>


