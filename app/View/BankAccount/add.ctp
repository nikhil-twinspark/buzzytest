<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-credit-card"></i>
Bank Account
</h1>
</div>

    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    <form name="productandservices" id="productandservices" class="form-horizontal" action="" method="post">
      <input type="hidden" id="clinic_id" name="clinic_id" value="<?=$sessionstaff['clinic_id']?>">
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Customer Name</label>

<div class="col-sm-9">

    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Customer Name" id="customer_name" name="customer_name" value="">
</div>
 </div>
       <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Account Type</label>

<div class="col-sm-9">

    <?php echo $this->Form->input("account_type",array('options'=>array('PC'=>'Personal Checking','PS'=>'Personal Savings','CC'=>'Corporate Checking','CS'=>'Corporate Savings'),'label'=>false,'div'=>false,'class'=>'col-xs-10 col-sm-5')); ?>
</div>
 </div>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Account Number</label>

<div class="col-sm-9">

    <input type="text" class="col-xs-10 col-sm-5" maxlength="15" placeholder="Account Number" id="account_number" name="account_number" value="">
</div>
 </div>
       <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Transit Number</label>

<div class="col-sm-9">

    <input type="text" class="col-xs-10 col-sm-5" maxlength="9" placeholder="Transit Number" id="transit_number" name="transit_number" value="">
</div>
 </div>

<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save" class="btn btn-info">
       
									</div>  

      </form>
  
 
     
   </div>
  


<script language="Javascript">


$(document).ready(function() { 
			
	
	 
			
        $('#productandservices').validate({
		rules: {
                        customer_name: "required",
			account_number: { 
				required: true,
				number: true,
                                minlength: 5
			},
                        transit_number: { 
				required: true,
				number: true,
                                minlength: 9
			}
		},
        
        // Specify the validation error messages
		messages: {
                        title: "Please enter customer name",
			account_number: {
				required: "Please enter account number",
				number: "Please enter a valid account number",
                                minlength: "Account Number must be 5 to 15 characters long"
			},
                        transit_number: {
				required: "Please enter transit number",
				number: "Please enter a valid transit number",
                                minlength: "Transit Number must be 9 characters long"
			}
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

