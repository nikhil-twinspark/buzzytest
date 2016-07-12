
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
    
  
      <?php echo $this->Form->create("productandservices",array('class'=>'form-horizontal'));
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));
 ?>
        <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Customer Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Customer Name" id="customer_name" name="customer_name" value="<?php echo $data['BankAccount']['customer_name']; ?>">
        </div>
        </div>
         <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Account Type</label>

<div class="col-sm-9">
	<select id="account_type" class="col-xs-10 col-sm-5" name="data[account_type]">
        <option value="PC" <?php if($data['BankAccount']['account_type']=='PC'){ echo "selected";} ?>>Personal Checking</option>
	<option value="PS" <?php if($data['BankAccount']['account_type']=='PS'){ echo "selected";} ?>>Personal Savings</option>
        <option value="CC" <?php if($data['BankAccount']['account_type']=='CC'){ echo "selected";} ?>>Corporate Checking</option>
        <option value="CS" <?php if($data['BankAccount']['account_type']=='CS'){ echo "selected";} ?>>Corporate Savings</option>
	</select>
</div>
 </div>
            <input type="hidden"  id="id" name="id" value="<?php echo $data['BankAccount']['id']; ?>">
     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Account Number</label>

<div class="col-sm-9">

    <input type="number"  maxlength="15" class="col-xs-10 col-sm-5" placeholder="Account Number" id="account_number" name="account_number" value="<?php echo $data['BankAccount']['account_number']; ?>">
</div>
 </div>
            
             <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Transit Number</label>

<div class="col-sm-9">

    <input type="number"  maxlength="9" class="col-xs-10 col-sm-5" placeholder="Transit Number" id="transit_number" name="transit_number" value="<?php echo $data['BankAccount']['transit_number']; ?>">
</div>
 </div>
    
<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save" class="btn btn-info">
       
									</div>  
      </form>
 
    
     
   </div>
 

<script language="Javascript">
$(document).ready(function() { 

	$('#productandservicesEditForm').validate({
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




