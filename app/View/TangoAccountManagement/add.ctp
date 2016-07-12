  
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
 
        <?php echo $this->Form->create("TangoAccount",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Customer</label>
                <div class="col-sm-9 col-xs-12">
                   
                    <input type="text"  maxlength="100" class="col-xs-10 col-sm-6" placeholder="Customer" id="customer" name="customer" value="">
                </div>
            </div>
        
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Account Identifier</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="100" class="col-xs-10 col-sm-6" placeholder="Identifier" id="identifier" name="identifier" value="">
                   
                </div>
            </div>
     

            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Account" class="btn btn-sm btn-primary">
             </div>
        </form>
   
<script>

    $(document).ready(function() {




        $('#TangoAccountAddForm').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                customer: "required",
                identifier: "required"
            },
            // Specify the validation error messages
            messages: {
                customer: "Please enter customer name",
                identifier: "Please enter identifier"
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
                form.submit();
            }

        });
    });
</script>

