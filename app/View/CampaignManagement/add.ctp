    <div class="contArea Clearfix">
     <div class="tabBox">
          <ul>
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "ClientManagement",
							"action"=>"index"
						));?>">Client Management</a></li>
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "CampaignManagement",
							"action"=>"index"
						));?>" class="active">Campaign Management</a></li>
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "RewardManagement",
							"action"=>"index"
						));?>">Rewards Management</a></li>
      <li><a href="<?php echo $this->Html->url(array(
						    "controller" => "Redeem",
							"action"=>"index"
						));?>">Redemption</a></li>
     </ul>
     </div>
     <?php echo $this->Session->flash(); ?>
     <div class="adminsuper">
      <?php echo $this->Form->create("Campaigns",array('class'=>'admin'));
      echo $this->Form->input('type', array('type' => 'hidden','value'=>'points'));
echo $this->Form->input('points_ratio', array('type' => 'hidden','value'=>'1'));
echo $this->Form->input('reward_ratio', array('type' => 'hidden','value'=>'0'));
echo $this->Form->input('currency', array('type' => 'hidden','value'=>'USD'));
echo $this->Form->input('glyph', array('type' => 'hidden','value'=>'$')); ?>
        <div class="groupAdmin">
        <label><span class="star">*</span>Client</label>
         <select size="4" name="data[Campaigns][api_user]" id="api_user" required >
								
							<?php 
							if(!empty($clients))
							{
								foreach ($clients as $cl)
								{
							?>
								
								<option value="<?php echo $cl['ClientCredentials']['api_user'];?>"><?php echo $cl['ClientCredentials']['api_user'];?></option>
							<?php 
								}//Endforeach
							}
							else 
							{
							?>
								<option value="">No Client Found</option>							
							<?php 
							}//Endif
							?>
							</select> 
        </div>
       
       <div class="groupAdmin">
        <label><span class="star">*</span>Name</label>
        <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'placeholder'=>'Name','required','class'=>'editable','maxlength'=>'50')); ?>
       </div>
       <div class="submit"><input type="submit" value="Save Camapaign" style="cursor: pointer;"></div>
     </div>
     </form>
     </div>
     
   </div>
   </div><!-- container -->
