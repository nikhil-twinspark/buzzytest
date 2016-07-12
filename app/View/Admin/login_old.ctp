

 <div class="contArea Clearfix">
    
      <div id="login" class="login_staff">
      <div class="headBox">
       <h1>Admin Login</h1>
     </div>
     <?php echo $this->Session->flash(); ?>
       <?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
       <div class="formGroup">
        <label >Admin Username:</label>
        <?php echo $this->Form->input("admin_name",array('label'=>false,'div'=>false,'placeholder'=>'User Name','required'));?>
          </div>
      
             <div class="formGroup">
        <label >Admin Password:</label>
        <?php echo $this->Form->input("admin_password",array('type'=>'password','label'=>false,'div'=>false,'placeholder'=>'Password','required')); ?>
          </div>
          <div class="submit">
<input type="submit" value="Admin Login" style="cursor: pointer;">
</div>
</form>
      
      </div><!-- formarea-->
     
     </div>
     

   </div><!-- container -->
