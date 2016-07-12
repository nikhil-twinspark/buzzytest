<script type="text/javascript">
<!--
$(document).ready(function(){
	$(".sorting").click(function(){
		var sortby = $(this).attr('sortby');
		var sort = $(this).attr('sort');
		var url = '<?php echo Router::url('/CampaignManagement/sort/', true); ?>'+sortby+'/'+sort;
		
		//console.log(url);
		window.location.href = url;
	});
});
//-->
</script>
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
     <div class="adminsuper">
		 <table border="0" cellpadding="0" cellspacing="0" id="paging-table" class='addOption'>
		 <tr>
			
			<td align="right">
			<form id="Campaigns" action="<?=Staff_Name?>CampaignManagement/index" method="get" class="text-right">
							<input class="border-1" type="text" name="search" id="Search" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; }?>"><input type="submit" value="Search" style="cursor:pointer;">
							</form>
			</td>
			</tr>
			<tr>
			<td>
			<?php if(!empty($clients)){ ?>
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "CampaignManagement",
							    "action" => "add"
							));?>" title="Edit" class="icon-1 info-tooltip">Add Campaign</a>
							<?php } ?>
			</td>
			</tr>
			</table>
			<?php echo $this->Session->flash(); ?>
       <table width="100%" border="0" cellpadding="0" cellspacing="0" > 
       <tr> 
         <td width="40%" class="client"><?php 
					$order_name = 'desc';
					$class_name = 'up_arrow';
					if(isset($sortname) && ($sortname=='client_id'))
					{
						if($sort=='desc')
						{
							$order_name = 'asc';
							$class_name = '';
						}else{
							$order_name = 'desc';							
							$class_name = 'up_arrow';
						}
					}
					?>
						<a class="sorting <?php echo $class_name;?>" href="javascript:void(0);" sortby="client_id" sort="<?php echo $order_name;?>">Client</a>
         </td>
         <td width="40%" class="campaign"><?php 
					$order_name = 'desc';
					$class_name = 'up_arrow';
					if(isset($sortname) && ($sortname=='name'))
					{
						if($sort=='desc')
						{
							$order_name = 'asc';
							$class_name = '';
						}else{
							$order_name = 'desc';							
							$class_name = 'up_arrow';
						}
					}
					?>
						<a class="sorting <?php echo $class_name;?>" href="javascript:void(0);" sortby="name" sort="<?php echo $order_name;?>">Campaign Name</a>
         </td>
         <td width="10%" colspan="2" class="aptn">Options</td>
        </tr>
        <?php 
				//categories data
				if(!empty($campaigns))
				{
					
					foreach ($campaigns as $campaign)
					{
					
					?>
       <tr> 
         <td width="40%"><?=$campaign['ClientCredentials']['api_user']?></td>
         <td width="40%" ><?=$campaign['Campaigns']['name'];?></td>
         <td width="10%" class="editbtn"><form id="Campaigns" action="<?=Staff_Name?>CampaignManagement/edit" method="post">
							<input type="hidden" name="data[Campaigns][id]" value="<?=$campaign['Campaigns']['id']?>">
							<input type="hidden" name="data[Campaigns][campaign_id]" value="<?=$campaign['Campaigns']['campaign_id']?>">
							<input type="hidden" name="data[Campaigns][name]" value="<?=$campaign['Campaigns']['name']?>">
							<input type="hidden" name="data[Campaigns][account_id]" value="<?=$campaign['ClientCredentials']['accountId']?>">
							<input type="hidden" name="data[Campaigns][api_user]" value="<?=$campaign['ClientCredentials']['api_user']?>">
							<input type="hidden" name="data[Campaigns][api_key]" value="<?=$campaign['ClientCredentials']['api_key']?>">
							<input type="hidden" name="data[Campaigns][api_url]" value="<?=$campaign['ClientCredentials']['api_url']?>">
							<input type="submit" value="Edit">
							</form>
			<!--					</td>
         <td width="10%" class="editbtn"><form id="Campaigns" action="<?=Staff_Name?>CampaignManagement/delete" method="post">
							<input type="hidden" name="data[Campaigns][id]" value="<?=$campaign['Campaigns']['id']?>">
							<input type="hidden" name="data[Campaigns][campaign_id]" value="<?=$campaign['Campaigns']['campaign_id']?>">
							<input type="hidden" name="data[Campaigns][account_id]" value="<?=$campaign['ClientCredentials']['accountId']?>">
							<input type="hidden" name="data[Campaigns][api_user]" value="<?=$campaign['ClientCredentials']['api_user']?>">
							<input type="hidden" name="data[Campaigns][api_key]" value="<?=$campaign['ClientCredentials']['api_key']?>">
							<input type="hidden" name="data[Campaigns][api_url]" value="<?=$campaign['ClientCredentials']['api_url']?>">
							<input type="submit" value="Delete">
							</form></td> -->
        </tr>
      <?php 	
					}//Endforeach
				}else{
				//Endif?>
        <tr> 
         <td colspan="3">No matches found</td>
        </tr>
        <?php } ?>
       </table>

       <table id="paging-table">
			<tr>
			<td>
				<?php echo $this->Paginator->first(__('<< First', true), array('class' => 'number-first'));?>
     <?php echo $this->Paginator->numbers();?>
  
<?php echo $this->Paginator->last(__('>> Last', true), array('class' => 'number-end'));?>
			</td>
			
			</tr>
			</table>
			
     </div>
     
   </div>
   </div><!-- container -->
   
   

















