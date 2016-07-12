    <?php echo $this->Html->script('ckeditor/ckeditor'); ?>
    <div class="contArea Clearfix">
        <div class="page-header">
            <h1>
                <i class="menu-icon fa fa-cubes"></i> Industries
            </h1>
        </div>
<?php 
       //echo $this->element('messagehelper'); 
       echo $this->Session->flash('good');
       echo $this->Session->flash('bad');
   ?>
     
     <div class="adminsuper">
 
		<?php echo $this->Form->create("IndustryPromotion",array('class'=>'form-horizontal'));?>
                <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Display Name</label>
      <div class="col-sm-9 col-xs-12">
        <?php echo $this->Form->input("display_name",array('label'=>false,'div'=>false,'placeholder'=>'Display Name','required','class'=>'col-xs-12 col-sm-5')); ?>
       </div>
       </div>
                <div class="form-group Clearfix">
                     <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Description</label>
                    <input type="hidden" name="data[IndustryPromotion][industry_id]" value="<?php echo $industryid;?>">
                    <div class="col-sm-9 col-xs-12">
                        <?php echo $this->Form->input("description",array('label'=>false,'div'=>false,'placeholder'=>'Description','required','class'=>'col-xs-12 col-sm-5')); ?>
                    </div>
                </div>
         
         <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Value</label>
      <div class="col-sm-9 col-xs-12">
        <?php echo $this->Form->input("value",array('label'=>false,'div'=>false,'placeholder'=>'Value','required','class'=>'col-xs-12 col-sm-5')); ?>
       </div>
       </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Operand</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("operand",array('options'=>array('+'=>'+','x'=>'x'),'label'=>false,'div'=>false)); ?>
        </div>
       </div>
         
         <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Promotion" class="btn btn-sm btn-primary" >
        </div>
         
     </div>
     </form>
   </div>
   </div><!-- container -->
<script>
$(document).ready(function() { 
			
	
	 
			
        $('#IndustryPromotionAddpromotionForm').validate({
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
                        display_name: "Please enter display name",
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

