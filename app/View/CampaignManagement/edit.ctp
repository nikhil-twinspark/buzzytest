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
  echo $this->Form->input("id",array('type' => 'hidden'));
echo $this->Form->input("api_user",array('type' => 'hidden'));
echo $this->Form->input("api_key",array('type' => 'hidden'));
echo $this->Form->input("campaign_id",array('type' => 'hidden'));
echo $this->Form->input("account_id",array('type' => 'hidden'));
echo $this->Form->input("api_url",array('type' => 'hidden'));
echo $this->Form->input("action",array('type' => 'hidden','value'=>'update'));
 ?>
        <div class="groupAdmin">
        <label>Client</label>
       <?php echo $this->Form->input("api_user",array('label'=>false,'div'=>false,'disabled' => 'disabled')); ?>
        </div>
       
       <div class="groupAdmin">
        <label><span class="star">*</span>Name</label>
        <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'placeholder'=>'Name','required','class'=>'editable','maxlength'=>'50')); ?>
       </div>
       <div class="submit"><input type="submit" value="Edit Camapaign" style="cursor: pointer;"></div>
     </div>
     </form>
     </div>
     
   </div>
   </div><!-- container -->

