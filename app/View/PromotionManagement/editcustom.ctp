
<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
 <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Custom Promotions
</h1>
</div>
    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    
  
      <?php echo $this->Form->create("Promotion",array('class'=>'form-horizontal'));
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));
if ($sessionstaff['is_lite'] == 1 || $promotion['Promotion']['is_global']==1) {
    $red='readonly';
}else{
    $red='';
}
 ?>
        <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Promotion Display Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Display Name" id="display_name" name="display_name" value="<?php echo $promotion['Promotion']['display_name']; ?>">
        </div>
        </div>
         <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Category</label>

<div class="col-sm-9">

    <select id="category" class="col-xs-10 col-sm-5" name="category">
<option value="">Select Category</option>
<option value="Regular Visits" <?php if($promotion['Promotion']['category']=='Regular Visits'){ echo "selected"; } ?>>Regular Visits</option>
<option value="Bonus Points" <?php if($promotion['Promotion']['category']=='Bonus Points'){ echo "selected"; } ?>>Bonus Points</option>
</select>
</div>
 </div>
        <div class="form-group">
            <input type="hidden"  id="id" name="id" value="<?php echo $promotion['Promotion']['id']; ?>">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Description</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="<?php echo $promotion['Promotion']['description']; ?>" <?=$red?>>
        </div>
        </div>
     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Value</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Value" id="value" name="value" value="<?php echo $promotion['Promotion']['value']; ?>">
</div>
 </div>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Operand</label>

        <div class="col-sm-9">

            <select id="operand" class="col-xs-10 col-sm-5 valid" name="operand">
<option value="+" <?php if($promotion['Promotion']['operand']=='+'){ echo "selected"; } ?>>+</option>
<option value="x" <?php if($promotion['Promotion']['operand']=='x'){ echo "selected"; } ?>>x</option>
</select>
        </div>
     </div>
    
<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Promotion" class="btn btn-info">
       
									</div>  
      </form>
 
    
     
   </div>
 

<script language="Javascript">


$(document).ready(function() { 
			
	
	 
			
        $('#PromotionEditForm').validate({
		rules: {
                        display_name: "required",
                        category: "required",
			description: "required",

			value: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        display_name: "Please enter display name",
                        category:"Please select category",
			description: "Please enter promotion description",
			
			value: {
				required: "Please enter promotion value",
				number: "Please enter a valid promotion value"
				
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




