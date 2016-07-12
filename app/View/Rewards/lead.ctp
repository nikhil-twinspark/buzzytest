
<section class="clearfix loginArea">
        <div class="col-md-5 userSign clearfix">
            <div class="form-group">
              Hello! You are being referred by <?=$referred_by?>
               </div>
          <p>We look forward to meet you!</p>
          <?php echo $this->Session->flash(); ?>
          <form action="<?=Staff_Name?>rewards/lead/<?php echo base64_encode($refers['Refer']['id']);?>" method="POST" name="lead_form" id="lead_form" class="form-horizontal">
			
			
              <div class="form-group">
               <label class="col-sm-3 control-label" ><span style="color:red;">*</span>First Name</label>
              <input class="form-control" type="hidden"	name="lead_add" id="lead_add" value="lead_add"> 
              <input class="form-control" type="hidden"	name="id" id="id" value="<?php echo $refers['Refer']['id'];?>"> 
              <input class="form-control" type="text"	name="first_name" id="first_name"	value="<?php echo $refers['Refer']['first_name'];?>" maxlength="20" required>
              </div>
              <div class="form-group">
               <label class="col-sm-3 control-label" ><span style="color:red;">*</span>Last Name</label>
              <input	class="form-control" type="text"	name="last_name" id="last_name"	value="<?php echo $refers['Refer']['last_name'];?>" maxlength="20" required>
              </div>
              <div class="form-group">
               <label class="col-sm-3 control-label" ><span style="color:red;">*</span>Email</label>
              <input	class="form-control" type="text"	name="email" id="email"	value="<?php echo $refers['Refer']['email'];?>"  required>
              </div>
              <div class="form-group">
               <label class="col-sm-3 control-label" ><span style="color:red;">*</span>Phone Number</label>
              <input class="form-control" type="text" name="phone" id="phone" value="<?php echo $refers['Refer']['phone'];?>" maxlength="10" required>
              </div>
              <div class="form-group">
              
               <label class="col-sm-3 control-label" >Preferred Time to Talk</label>
               <div class="selctboxmonth">
              
               <span class="dropIcon dropIcon_new"></span>
               <select  id="pref_time" name="pref_time" class="form-control valid">
                                <option val="10AM - 12 Noon">10AM - 12 Noon</option>
              <option val="12 Noon - 2PM">12 Noon - 2PM</option>
              <option val="2PM - 4PM">2PM - 4PM</option>
              <option val="4PM - 6PM">4PM - 6PM</option>
              <option val="6PM - 8PM">6PM - 8PM</option>
              
						
						               </select>
						              
              
               </div>
             
              </div>
             <div class="form-group leadagree" >
                 <input type="checkbox" name="agree" id="agree" class = "col-sm-offset-2 col-sm-10" ><label>I agree to be contacted on the number above.</label>
              </div>
              <input type="submit" value="Accept" name="myinfo_submit" class="col-sm-offset-2 col-sm-10 btn btn-primary clearfix buttondflt" style="width:60%; background-position: 78% 13px;">
              <div class="form-group leadtext">
               I'm part of BuzzyDoc Office! BuzzyDoc is fun patient rewards program where the doctor gives you points for being a good patient and continuing to come back!</div>

    
  </form>
         </div>
    </section><!--loginform-->

    <script>
        $('#lead_form').validate({
		rules: {
			first_name:"required",
			last_name:"required",
			email:{ 
			required:true,
			email:true
			},
			phone: {
				required:true,number: true, minlength: 7 ,maxlength:10
			},
			agree:"required"
			
			
		},
        
        // Specify the validation error messages
		messages: {
			first_name:"Please enter first name",
			last_name:"Please enter last name",
			email:{
				required:"Please enter email",
				email:"Please enter valid email"
			},
			phone: {
				required:"Please enter a phone number",
				number: "Please enter a valid phone number",
				minlength: "Phone Number must be 7 to 10 characters long",
                                maxlength: "Phone Number must less then 11 characters"
			},
			agree:"Please check condition before submit."
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
    </script>
