
    <div class="contArea">
     <div class="grid100 pull-left ">
      <div id="login">
      <div class="headBox">
       <h1>Admin Login</h1>
     </div>
     <?php echo $this->Session->flash(); ?>
       <?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
         <div class="formGroup">
        <label >Username:</label>
        <?php echo $this->Form->input("admin_name",array('label'=>false,'div'=>false,'placeholder'=>'Name')); ?>
          </div>
      
             <div class="formGroup">
        <label >Password:</label>
        <?php echo $this->Form->input("admin_password",array('type'=>'password','label'=>false,'div'=>false,'placeholder'=>'Password')); ?>
          </div>

       <div class="submit"><input type="submit" value="Admin Login" class="record hand-icon"></div>
      </form>
      </div><!-- formarea-->
     
     </div>
     
   </div>
   </div><!-- container -->
   <div class="Clearfix"></div>
