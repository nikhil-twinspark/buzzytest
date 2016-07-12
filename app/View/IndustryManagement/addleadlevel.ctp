    <?php echo $this->Html->script('ckeditor/ckeditor'); ?>
    <div class="contArea Clearfix">
      <div class="page-header">
            <h1>
                <i class="menu-icon fa fa-cubes"></i> Industries
            </h1>
        </div>

     <div class="adminsuper">
 
    <?php echo $this->Form->create("LeadLevel",array('class'=>'form-horizontal'));?>
         
        <div class="form-group Clearfix">
             <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Lead Name</label>
             <input type="hidden" name="industryId" value="<?php echo $industryid;?>">
             <div class="col-sm-9 col-xs-12">
                <input type="text" id="leadname" maxlength="100" class="col-xs-12 col-sm-5" required="required" placeholder="Lead Name" name="leadname">
            </div>
        </div>
         
         <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Lead Point</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" id="leadpoints" class="col-xs-12 col-sm-5" required="required" placeholder="Lead Point" name="leadpoints">
            </div>
            </div>
   
         <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Lead Level" class="btn btn-sm btn-primary" onclick="return validateURL();" >
        </div>
         
     </div>
     </form>
   </div>
   </div><!-- container -->
<script>
$(document).ready(function() { 
			
	
	 
			
        $('#LeadLevelAddleadlevelForm').validate({
		rules: {
			leadname: "required",
			
			leadpoints: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
			leadname: "Please enter lead name",
			
			leadpoints: {
				required: "Please enter lead points",
				number: "Only numeric values are accepted"
				
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

