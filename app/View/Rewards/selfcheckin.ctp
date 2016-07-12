
<?php $sessionpatient = $this->Session->read('patient'); ?>
    <section class="clearfix loginArea">
        <div class="col-md-6 col-sm-6 col-xs-12 userSign clearfix">
          <p>Self Check-in</p>
          <?php echo $this->Session->flash(); ?>
      
          <form action="/rewards/login" method="post" name="selfcheckin" class="loginBox">
           <div class="form-group">
             <label>Patient UserName</label>
             <input type="text" id="loginPatientName" required="required" class="form-control" name="data[login][patient_name]">
             <input type="hidden" id="loginselfcheckin" name="data[login][selfcheckin]" value="1">
            </div>
           <div class="form-group">
             <label>Patient Password</label>
             <input type="password" id="loginPatientPassword" required="required" class="form-control" name="data[login][patient_password]">
       
            
            </div>
      
          <input class="btn btn-primary buttondflt col-md-4 col-sm-4 col-xs-4"  type="submit" value="Sign In" >
        
        
          </form>
          
         </div>
   

    </section><!--loginform-->

