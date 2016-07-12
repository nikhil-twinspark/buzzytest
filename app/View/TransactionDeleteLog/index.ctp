<?php $sessiontrans = $this->Session->read('transactionlog'); ?>
 <div class="contArea">
     <div class="grid100 pull-left ">
     
     <?php if(isset($sessiontrans['loginName']) && isset($sessiontrans['loginPassword'])){
    ?>
    <div class="breadcrumb_staff"><b>Transaction Deleted Log</b> </div>
     <div class="adminsuper">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" > 
                <thead>
                        <tr> 
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Client</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Staff</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Reward</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Activity</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Amount</td>
                            <td width="12%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Clinic</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Date</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Status</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
                      
                 <?php 
					if(!empty($transactiondel)){
                 foreach ($transactiondel as $tdet){
                
                                                  
                     ?>
                         <tr> 
                              
                           
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['first_name'].' '.$tdet['TransactionDeleteLog']['last_name']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['staff_id']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['card_number']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['authorization']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['activity_type']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['amount']; ?></td>
                            <td width="12%"><?php echo $tdet['TransactionDeleteLog']['clinic_id']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['date']; ?></td>
                            <td width="10%"><?php echo $tdet['TransactionDeleteLog']['status']; ?></td>
                            
                         </tr> 
                         
                 <?php }}else{  ?> 
                 <tr> 
                              
                           
                            <td colspan="9">No Record Found!</td>
                            
                            
                         </tr> 
                         <?php } ?> 
                </tbody>
               
                
         </table>

     

    </div>
     <?php
     }else{ ?>
      <div id="login" class="login_staff">
      <div class="headBox">
       <h1>Login</h1>
     </div>
     <?php echo $this->Session->flash(); ?>
       <?php echo $this->Form->create("login",array('class'=>'loginBox')); ?>
       <div class="formGroup">
        <label >Username:</label>
        <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'placeholder'=>'User Name','required'));?>
          </div>
      
             <div class="formGroup">
        <label >Password:</label>
        <?php echo $this->Form->input("password",array('type'=>'password','label'=>false,'div'=>false,'placeholder'=>'Password','required')); ?>
          </div>
      

      <div class="submit"><input type="submit" value="Login" class="record hand-icon" style="margin-left:34px;"></div>
      </form>
      </div><!-- formarea-->
     <?php } ?>
     
     
     </div>
     
   </div>
   </div><!-- container -->
   <div class="Clearfix"></div>


