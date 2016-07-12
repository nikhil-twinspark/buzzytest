    <div class="contArea Clearfix">
      <div class="tabBox">
        	<?php if(isset($sessionstaff['customer_search_results'])){ ?>
      <ul>
		
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"recordpoint"
						));?>" >Record Points</a></li>
							
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"patienthistory"
						));?>">Patient History</a></li>
						
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "PatientManagement",
							"action"=>"patientinfo"
						));?>">Patient Info</a></li>
						
     </ul>
     
     <?php } 
     ?>
     </div>
     <div class="breadcrumb_staff"><a href="<?php echo $this->Html->url(array(
						    "controller" => "StaffRedeem",
							"action"=>"index"
						));?>" class="active">Staff Redeem Management</a> >> <b>View</b> </div>
     <?php echo $this->Session->flash(); ?>
     
     <div class="adminsuper form_box">
      <?php echo $this->Form->create("Transaction",array('class'=>'admin'));
      echo $this->Form->input("action",array('type' => 'hidden','value'=>'update'));
      echo $this->Form->input("id",array('type' => 'hidden','value'=>$red['Transaction']['id']));
       ?>
       <div class="groupAdmin">
        <label>Transaction Id</label>
		<?php echo $this->Form->input("Transaction Id",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['id'],'class'=>'form-control')); ?>
        </div>
       <div class="groupAdmin">
        <label>Transaction Date</label>
		<?php echo $this->Form->input("Transaction Date",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['date'],'class'=>'form-control')); ?>
        </div>
	<div class="groupAdmin">
        <label>First name</label>
		<?php echo $this->Form->input("first name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['first_name'],'class'=>'form-control')); ?>
		
        </div>
        <div class="groupAdmin">
        <label>Last name</label>
		<?php echo $this->Form->input("last name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['last_name'],'class'=>'form-control')); ?>
		
        </div>
        <div class="groupAdmin">
        <label>Clinic</label>
		<?php echo $this->Form->input("clinic",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Clinics']['api_user'],'class'=>'form-control')); ?>
		
        </div>
         <div class="groupAdmin">
        <label>Reward</label>
		<?php echo $this->Form->input("Reward",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['authorization'],'class'=>'form-control')); ?>
        </div>
        <div class="groupAdmin">
        <label>Card number</label>
		<?php echo $this->Form->input("Card number",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['card_number'],'class'=>'form-control')); ?>
        </div>
       
       
        <div class="groupAdmin">
        <label>Redeemed Amount</label>
		<?php echo $this->Form->input("Redeemed Amount",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['amount'],'class'=>'form-control')); ?>
        </div>
       
        <div class="groupAdmin">
        <label>Status</label>
		<select name="data[Transaction][status]" required>
                        <option value="">Select Status</option>
                        <option value="Redeemed" <?php if($red['Transaction']['status']=='Redeemed'){ ?> selected="selected" <?php } ?>>Redeemed</option>
                        <option value="In Office" <?php if($red['Transaction']['status']=='In Office'){ ?> selected="selected" <?php } ?>>In Office</option>
                        <option value="Ordered/Shipped" <?php if($red['Transaction']['status']=='Ordered/Shipped'){ ?> selected="selected" <?php } ?>>Ordered/Shipped</option>
                </select>
        </div>
        <div class="submit"><input type="submit" value="Update" style="cursor: pointer;"></div>
        </form>
     </div>
     

    
    
    
    </div>
     
   </div>
   </div><!-- container -->

