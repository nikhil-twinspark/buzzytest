<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>

Ways To Earn

</h1>
</div>

    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
   
      <?php echo $this->Form->create("Promotion",array('class'=>'form-horizontal'));
       ?>
      <input type="hidden" id="clinic_id" name="clinic_id" value="<?=$sessionstaff['clinic_id']?>">
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Promotion Display Name</label>

<div class="col-sm-9">

    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Display Name" id="display_name" name="display_name" value="">
</div>
 </div>
<!--       <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Category</label>

<div class="col-sm-9">

    <?php echo $this->Form->input("category",array('options'=>array(''=>'Select Category','Regular Visits'=>'Regular Visits','Bonus Points'=>'Bonus Points'),'label'=>false,'div'=>false,'class'=>'col-xs-10 col-sm-5')); ?>
</div>
 </div>-->
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Description</label>

<div class="col-sm-9">

    <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="">
</div>
 </div>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Value</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Value" id="value" name="value" value="">
</div>
 </div>
     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Operand</label>

<div class="col-sm-9">

    <?php echo $this->Form->input("operand",array('options'=>array('+'=>'+','x'=>'x'),'label'=>false,'div'=>false,'class'=>'col-xs-10 col-sm-5')); ?>
</div>
 </div>

<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Custom Promotion" class="btn btn-info">
       
									</div>  

      </form>
  
 
     
   </div>
  


<script language="Javascript">


$(document).ready(function() { 
			
	
	 
			
        $('#PromotionAddForm').validate({
		rules: {
                        display_name: "required",
//                        category: "required",
			description: "required",

			value: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        display_name: "Please enter display name",
//                        category:"Please select category",
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

