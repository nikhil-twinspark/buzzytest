<?php echo $this->Html->script('ckeditor/ckeditor'); ?>

    
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
    
 
		<?php echo $this->Form->create("LeadLevel",array('class'=>'form-horizontal'));
	
		?>
		
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Lead Name</label>
            <input type="hidden" name="industryId" value="<?php echo $industryid;?>">
            <input type="hidden" name="data[LeadLevel][id]" value="<?php echo $LeadLevel['LeadLevel']['id'];?>">
            <div class="col-sm-9 col-xs-12">
                <input type="text" id="leadname" class="col-xs-12 col-sm-5" name="data[LeadLevel][leadname]" value="<?php echo $LeadLevel['LeadLevel']['leadname'];?>" placeholder="Lead Name" required="required" maxlength="100">
            </div>
       </div>

      <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Lead point</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" class="col-xs-12 col-sm-5" id="leadpoints" name="data[LeadLevel][leadpoints]" value="<?php echo $LeadLevel['LeadLevel']['leadpoints'];?>" placeholder="Lead Point" required="required">
        </div>
       
       </div>

        <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Lead Level" class="btn btn-sm btn-primary">
        </div>

      
     </div>
     </form>
    

<script>
$(document).ready(function() { 
			
	
	 
			
        $('#LeadLevelEditleadlevelForm').validate({
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
				number: "Please enter a valid lead points"
				
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
