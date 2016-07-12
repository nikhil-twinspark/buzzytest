
<?php $sessionpatient = $this->Session->read('patient'); ?>
    <section class="clearfix loginArea">
        <div class="col-md-6 col-sm-6 col-xs-12 userSign clearfix">
          <p>Staff Login</p>
          <?php echo $this->Session->flash(); ?>
       <?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
           <div class="form-group">
             <label>Staff UserName</label>
             <?php echo $this->Form->input("staff_name",array('label'=>false,'div'=>false,'class'=>'form-control','required'));?>
            </div>
           <div class="form-group">
             <label>Password</label>
             <?php echo $this->Form->input("staff_password",array('type'=>'password','label'=>false,'div'=>false,'class'=>'form-control','required')); ?>
       
            
            </div>
      
          <input class="btn btn-primary buttondflt col-md-4 col-sm-4 col-xs-4"  type="submit" value="Sign In" >
        
        
          </form>
          
         </div>
   

    </section><!--loginform-->

