
    
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
       <input type="hidden"  id="id" name="data[CharacteristicInsurance][id]" value="<?php echo $CharacteristicInsurance['CharacteristicInsurance']['id']; ?>">
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Name</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Name" id="name" name="data[CharacteristicInsurance][name]" value="<?php echo $CharacteristicInsurance['CharacteristicInsurance']['name']; ?>" >
                </div>
            </div>

            

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Type</label>
                <div class="col-sm-9 col-xs-12">
                    
                    
                    <select name="data[CharacteristicInsurance][type]" id="type" >
	
<option value="Characteristic" <?php if($CharacteristicInsurance['CharacteristicInsurance']['type']=='Characteristic'){ echo "selected"; } ?>>Characteristic</option>
<option value="Insurance" <?php if($CharacteristicInsurance['CharacteristicInsurance']['type']=='Insurance'){ echo "selected"; } ?>>Insurance</option>
<option value="Procedure" <?php if($CharacteristicInsurance['CharacteristicInsurance']['type']=='Procedure'){ echo "selected"; } ?>>Procedure</option>

  				</select>
             
                </div>
            </div>

            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Characteristics/Insurances/Procedures" class="btn btn-sm btn-primary">
             </div>
            
        </form>
</div>
<script>
$(document).ready(function() { 
			
	
	 
			
        $('#CharacteristicInsuranceEditForm').validate({
		rules: {
			name: "required"
		},
        
        // Specify the validation error messages
		messages: {
			name: "Please enter name"
			
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

