
    
   <div class="contArea Clearfix">
      <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-pencil-square-o"></i> Training Video
        </h1>
    </div>
     <?php 
     //echo $this->element('messagehelper'); 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     
     ?>
 
        <?php echo $this->Form->create("TrainingVideo",array('class'=>'form-horizontal'));?>
       <input type="hidden"  id="id" name="id" value="<?php echo $TrainingVideo['TrainingVideo']['id']; ?>">
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Video Title</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Video Title" id="title" name="title" value="<?php echo $TrainingVideo['TrainingVideo']['title']; ?>" >
                </div>
            </div>

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Video Embedded Source</label>
                <div class="col-sm-9 col-xs-12">
                    <textarea rows="4" cols="50" id="video_embed" name="video_embed" placeholder="Video Embedded Source"><?php echo $TrainingVideo['TrainingVideo']['video_embed']; ?>
</textarea> 
                
                </div>
            </div>

            
            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Training Video" class="btn btn-sm btn-primary">
             </div>
            
        </form>
</div>
<script>

$(document).ready(function() { 
	
	 
			
        $('#TrainingVideoEditForm').validate({
		rules: {
			title: "required",
                     
                video_embed: "required"
			
			
		},
        
        // Specify the validation error messages
		messages: {
			title: "Please enter video title.",
                        
                video_embed: "Please enter video embedded source."
			
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

