<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Doctor-to-Doctor Reviews
</h1>
</div>

    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
   
      <?php echo $this->Form->create("Review",array('class'=>'form-horizontal'));
       ?>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Review For</label>

<div class="col-sm-9">

     <select id="clinic_id" class="col-xs-10 col-sm-5 valid" name="clinic_id">
         <option value="">Select Clinic</option>
        <?php foreach($Clinics as $Clinic){ ?>
<option value="<?=$Clinic['Clinic']['id']?>"><?=$Clinic['Clinic']['api_user']?></option>
        <?php } ?>
</select>
</div>
 </div>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Reviewed By</label>

<div class="col-sm-9">

     <select id="doctor_id" class="col-xs-10 col-sm-5 valid" name="doctor_id">
         <option value="">Select Doctor</option>
        <?php foreach($Doctors as $doctor){ ?>
<option value="<?=$doctor['Doctor']['id']?>"><?=$doctor['Doctor']['first_name']?> <?=$doctor['Doctor']['last_name']?></option>
        <?php } ?>
</select>
</div>
 </div>
      
     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Review</label>

<div class="col-sm-9">

   <textarea placeholder="Review" class="col-xs-10 col-sm-5" cols="30" rows="6" id="review" name="review"></textarea>
</div>
 </div>

<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Review" class="btn btn-info">
       
									</div>  

      </form>
  
 
     
   </div>
  


<script language="Javascript">


$(document).ready(function() { 
			
	
	 
			
        $('#ReviewAddForm').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
		rules: {
                        clinic_id: "required",
                        doctor_id: "required",
			review: "required",
                        

		},
        
        // Specify the validation error messages
		messages: {
                        clinic_id: "Please select clinic",
                        doctor_id: "Please select doctor",
			review: "Please enter reviews",
			
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
                highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },
           
        submitHandler: function(form) {
            form.submit();
        }  
            
         });
});
</script>

