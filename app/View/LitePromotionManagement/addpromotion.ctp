    <?php echo $this->Html->script('ckeditor/ckeditor'); ?>
    
    <div class="contArea Clearfix">
          <div class="page-header">
             <h1>
                 <i class="menu-icon fa fa-exchange"></i> Lite Promotions
             </h1>
         </div>
 </div>
     <?php 
     //echo $this->element('messagehelper'); 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     ?>
 
        <?php echo $this->Form->create("Promotion",array('class'=>'form-horizontal'));?>
<div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Display Name</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Display Name" id="display_name" name="display_name" value="">
                </div>
            </div>
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Description</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="">
                </div>
            </div>

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Value</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Value" id="value" name="value" value="">
                </div>
            </div>

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Operand</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("operand",array('options'=>array('+'=>'+','x'=>'x'),'label'=>false,'div'=>false)); ?>
                </div>
            </div>

            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Promotion" class="btn btn-sm btn-primary">
             </div>
            
        </form>
   
<script>
$(document).ready(function() { 
			
	
	 
			
        $('#PromotionAddpromotionForm').validate({
		rules: {
                        display_name: "required",
			description: "required",
			
			value: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        display_name:"Please enter display name",
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

