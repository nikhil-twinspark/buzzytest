  
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-credit-card"></i> Tango Account
        </h1>
    </div>

     <?php 
       //echo $this->element('messagehelper'); 
       echo $this->Session->flash('good');
       echo $this->Session->flash('bad');
       
   ?>

        <?php echo $this->Form->create("AddFund",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Customer</label>
        <div class="col-sm-9 col-xs-12">
            <input type="hidden" id="id" name="id" value="<?php echo $tangodetail['TangoAccount']['id']; ?>">
            <input type="hidden" id="cc_tokan" name="cc_tokan" value="<?php echo $tangodetail['TangoAccount']['cc_tokan']; ?>">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-6" placeholder="Customer" id="customer" name="customer" value="<?php echo $tangodetail['TangoAccount']['customer']; ?>" readonly="readonly">
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Account Identifier</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-6" placeholder="Identifier" id="identifier" name="identifier" value="<?php echo $tangodetail['TangoAccount']['identifier']; ?>" readonly="readonly">

        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Amount (In $)</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="10" class="col-xs-10 col-sm-6" placeholder="Amount" id="amount" name="amount" value="" >

        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>CVV Number</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="3" class="col-xs-10 col-sm-6" placeholder="CVV Number" id="cvv" name="cvv" value="" >

        </div>
    </div>

    <div class="col-md-offset-3 col-md-9">
        <input type="submit" value="Fund Amount" class="btn btn-sm btn-primary">
    </div>
</form>

<script>

    $(document).ready(function() {




        $('#AddFundAddfundForm').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                amount: {
                    required: true, number: true
                },
                cvv: {
                    required: true, number: true
                },
            },
            // Specify the validation error messages
            messages: {
                amount: {
                    required: "Please enter a amount",
                    number: "Please enter a valid amount"
                },
                cvv: {
                    required: "Please enter a cvv number",
                    number: "Please enter a valid cvv number"
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
                var amt = $('#amount').val();
                if (amt > 0) {
                    form.submit();
                } else {
                    alert('Amount should be greater then 0.');
                    focus('#amount');
                    return false;
                }
            }

        });
    });
</script>

