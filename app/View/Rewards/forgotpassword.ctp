
<section class="clearfix loginArea">
        <div class="col-md-5 userSign clearfix">
          <p>Reset Password</p>
          <?php echo $this->Session->flash(); ?>
          <form action="<?=Staff_Name?>rewards/forgotpassword/" method="POST" name="pass_form" id="pass_form" onsubmit="return checkForgotPassword()">
           <div class="form-group">
             <label>Card Number<span style="color:red;">*</span><span id='error_msg_card_number' style='color:red;font-style:italic;padding-left:50px;' ></span></label>
             <input class="form-control" type="text" name="card_number" id="card_number" size="24" maxlength="20" border="0" value="" onkeypress="changeErrorMessage()">
            </div>
            <input type="submit" value="Reset Password" name="login_submit" class="btn btn-primary buttondflt forgot_pass">
         </form>
         </div>
    </section><!--loginform-->

    <script>
        function checkForgotPassword(){
            if($("#card_number").val()==''){
                 $("#card_number").css('background-color','#FF9966');
                $("#error_msg_card_number").html("Please enter a card number");
                $("#card_number").focus();
                return false;
            }
            return true;
        }
        
         function changeErrorMessage(){
                $("#card_number").css('background-color','');
                $("#error_msg_card_number").html("");

           }
    </script>
