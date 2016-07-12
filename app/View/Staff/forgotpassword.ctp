<style>
    
    div.error_validate {
    background: none repeat scroll 0 0 #202020;
    border: 1px solid #000000;
    border-radius: 2px 2px 2px 2px;
    color: #FFFFFF;
    font-family: arial;
    font-size: 11px;
    padding: 3px 10px;
    position: absolute;
    right: 0;
    top: 207px;
    width: 47%;
    z-index: 5001;
}
</style>
 <div class="contArea">
     <div class="grid100 pull-left ">
      <div id="login" class="login_staff">
      <div class="headBox">
       <h1>Forgot Password</h1>
     </div>
     <?php echo $this->Session->flash(); ?>
       <form action="<?=Staff_Name?>staff/forgotpassword/" method="POST" name="pass_form" id="pass_form" onsubmit="return checkForgotPassword()" class="loginBox">
       <div class="formGroup">
        <label ><span class="star">*</span>Staff Email:</label>
        <input type="text"  maxlength="50" class="editable" placeholder="Email" required="required" id="staff_email" name="staff_email" value="">
        </div>
      

      <div class="submit"><input type="submit" value="Submit" class="record hand-icon" style="margin-left:34px;"></div>
      </form>
      </div><!-- formarea-->
     
     </div>
     
   </div>
   </div><!-- container -->
   <div class="Clearfix"></div>


   <script>
       $(document).ready(function() {  
        $('#pass_form').validate({
		rules: {

			
		
                        staff_email: {
                            required:true,
                            email:true
                        }
			
			
			
		},
        
        // Specify the validation error messages
		messages: {
			
			
			
			staff_email: {
                            required:"Please enter your email",
                            email:"Please enter your valid email"
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