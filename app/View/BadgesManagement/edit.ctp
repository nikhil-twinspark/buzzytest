
    
   <div class="contArea Clearfix">
      <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-key"></i> Badges
        </h1>
    </div>
     <?php 
     //echo $this->element('messagehelper'); 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     
     ?>
 
        <?php echo $this->Form->create("Badge",array('class'=>'form-horizontal'));?>
       <input type="hidden"  id="id" name="id" value="<?php echo $Badge['Badge']['id']; ?>">
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Badge Name</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="name" name="name" value="<?php echo $Badge['Badge']['name']; ?>" >
                </div>
            </div>

            

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Badge Point</label>
                <div class="col-sm-9 col-xs-12">
                    
                    
                    <input type="number"  maxlength="6" class="col-xs-10 col-sm-5" placeholder="Badge Point" id="value" name="value" value="<?php echo $Badge['Badge']['value']; ?>" onkeyup="this.value = this.value.replace(/\D/g, '')">
             
                </div>
            </div>
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Badge Description</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" id="description" maxlength="100" class="col-xs-12 col-sm-5" placeholder="Badge Description" name="description" value="<?php echo $Badge['Badge']['description']; ?>">
                </div>
            </div>
            <input type="hidden" id="type"  name="type" value="0">
<!--            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right">Type</label>
                <div class="col-sm-9 col-xs-12">
                    <select name="type" id="type">
                    	<option value="0">Normal</option>
                    	<option value="1">Global Promotion</option>
                    	<option value="2">Combo</option>
                    </select>
                </div>
            </div>-->
            
            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Badge" class="btn btn-sm btn-primary">
             </div>
            
        </form>
</div>
<script>
var typeValue="<?php echo $Badge['Badge']['type']; ?>";
$(document).ready(function() { 
			$('#type option[value='+typeValue+']').attr('selected','selected').trigger('change');
	 
			
        $('#BadgeEditForm').validate({
		rules: {
			name: "required",
                        value: {
                    required: true,
                    number: true
                },
                description: "required"
			
			
		},
        
        // Specify the validation error messages
		messages: {
			name: "Please enter badge name.",
                        value: {
                    required: "Please enter badge point",
                    number: "Invalid badge point"
                },
                description: "Please enter badge description."
			
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

