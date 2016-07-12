
    
   <div class="contArea Clearfix">
      <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-asterisk"></i> Characteristics / Insurances / Procedures
        </h1>
    </div>
     <?php 
     //echo $this->element('messagehelper'); 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     ?>
 
        <?php echo $this->Form->create("CharacteristicInsurance",array('class'=>'form-horizontal'));?>
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Name</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'placeholder'=>'Description','required','class'=>'col-xs-12 col-sm-5')); ?>
                </div>
            </div>


            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Type</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("type",array('options'=>array('Characteristic'=>'Characteristic','Insurance'=>'Insurance','Procedure'=>'Procedure'),'label'=>false,'div'=>false)); ?>
                </div>
            </div>

            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Characteristics/Insurances/Procedures" class="btn btn-sm btn-primary">
             </div>
            
        </form>
</div>
<script>
$(document).ready(function() { 
			
	
	 
			
        $('#CharacteristicInsuranceAddForm').validate({
		rules: {
			name: "required"
			
			
		},
        
        // Specify the validation error messages
		messages: {
			name: "Please enter name.",
			
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

