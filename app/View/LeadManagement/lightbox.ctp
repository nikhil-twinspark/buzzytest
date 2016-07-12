<?php echo $this->Session->flash(); ?>
<div class="adminsuper">
      <?php echo $this->Form->create("Redeem",array('class'=>'admin'));
      echo $this->Form->input("action",array('type' => 'hidden','value'=>'update'));
      echo $this->Form->input("ID",array('type' => 'hidden','value'=>$red['RedeemRewards']['id']));
       ?>
        <div class="groupAdmin">
        <label>Transaction Id</label>
		<?php echo $this->Form->input("Transaction Id",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['TransId'],'class'=>'form-control')); ?>
        </div>
       <div class="groupAdmin">
        <label>Transaction Date</label>
		<?php echo $this->Form->input("Transaction Date",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['TransDate'],'class'=>'form-control')); ?>
        </div>
	<div class="groupAdmin">
        <label>Customer name</label>
		<?php echo $this->Form->input("Customer name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['CustomerFirstName'],'class'=>'form-control')); ?>
		
        </div>
        <div class="groupAdmin">
        <label>Client</label>
		<?php echo $this->Form->input("Client",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['UserId'],'class'=>'form-control')); ?>
		<?php echo $this->Form->input("Client",array('type' => 'hidden','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['UserId'])); ?>
        </div>
         <div class="groupAdmin">
        <label>Reward</label>
		<?php echo $this->Form->input("Reward",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['Reward'],'class'=>'form-control')); ?>
        </div>
        <div class="groupAdmin">
        <label>Card number</label>
		<?php echo $this->Form->input("Card number",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['CardNumber'],'class'=>'form-control')); ?>
        </div>
        <div class="groupAdmin">
        <label>Customer Email</label>
		<?php echo $this->Form->input("Customer Email",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['CustomerEmail'],'class'=>'form-control')); ?>
        </div>
       
        <div class="groupAdmin">
        <label>Redeemed Amount</label>
		<?php echo $this->Form->input("Redeemed Amount",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['RedeemAmount'],'class'=>'form-control')); ?>
        </div>
        <div class="groupAdmin">
        <label>Email To</label>
		<?php echo $this->Form->input("Email To",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['RedeemRewards']['EmailTo'],'class'=>'form-control')); ?>
        </div>
        <div class="groupAdmin">
        <label>Status</label>
		<select name="data[Redeem][Status]" required>
                        <option value="">Select Status</option>
                        <option value="New" <?php if($red['RedeemRewards']['Status']=='New'){ ?> selected="selected" <?php } ?>>Redeemed</option>
                        <option value="Cunfirm" <?php if($red['RedeemRewards']['Status']=='Cunfirm'){ ?> selected="selected" <?php } ?>>In Office</option>
                        <option value="Ordered_Shipped" <?php if($red['RedeemRewards']['Status']=='Ordered_Shipped'){ ?> selected="selected" <?php } ?>>Ordered/Shipped</option>
                </select>
        </div>
        <div class="submit"><input type="submit" value="Update" style="cursor: pointer;"></div>
        </form>
     </div>