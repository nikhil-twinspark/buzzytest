   
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
 
		<?php echo $this->Form->create("IndustryTextLevel",array('class'=>'form-horizontal'));
	
		?>
		<div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Levelup Text</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Levelup Text" id="levelup_text" name="data[IndustryTextLevel][levelup_text]" value="<?php echo $IndustryTextLevel['IndustryTextLevel']['levelup_text']; ?>">
            </div>
      </div>
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Treatment Text</label>
            <input type="hidden" name="data[IndustryTextLevel][industry_id]" value="<?php echo $industryid;?>">
            <input type="hidden" name="data[IndustryTextLevel][id]" value="<?php echo $IndustryTextLevel['IndustryTextLevel']['id'];?>">
            <div class="col-sm-9 col-xs-12">
                <input type="text" id="treatment_text" name="data[IndustryTextLevel][treatment_text]" class="col-xs-12 col-sm-5" value="<?php echo $IndustryTextLevel['IndustryTextLevel']['treatment_text'];?>" placeholder="Treatment Text" required="required">
            </div>
       </div>
       
      
       
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Header Text</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Header Text" id="header_text" name="data[IndustryTextLevel][header_text]" value="<?php echo $IndustryTextLevel['IndustryTextLevel']['header_text']; ?>">
            </div>
      </div>

        <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Text Label" class="btn btn-sm btn-primary">
        </div>

     
     
     </form>
     

<script>
$(document).ready(function() { 
			
	
	 
			
        $('#IndustryTextLevelEdittextForm').validate({
		rules: {
                        levelup_text: "required",
			treatment_text: "required",
                        header_text: "required",
		},
        
        // Specify the validation error messages
		messages: {
                        levelup_text: "Please enter levelup text",
			treatment_text: "Please enter treatment text",
			header_text: "Please enter header text",
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
