     <style type="text/css">
     	.detail h2.challengeDetail { margin-top:0;}
     </style>
	 <?php $sessionpatient = $this->Session->read('patient');  ?>
     <div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['patient_logo']) && $sessionpatient['patient_logo']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=$sessionpatient['patient_logo']?>" width="246" height="88" alt="Pure Smiles" title="Pure Smiles" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image('reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image('reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

        <?php echo $this->html->image('reward_imges/reward_mobile.jpg');?>
        
          </div>
<?php 
if (isset($sessionpatient['campaign_rewards']) && sizeof($sessionpatient['campaign_rewards']['rewards'][0]['reward']) > 0) {
				// Sort the rewards list:
			$reward_sorting_counter = 0;
			foreach ($sessionpatient['campaign_rewards']['rewards'][0]['reward'] as $discard => $reward_info_for_sort) {
				$reward_array_to_sort[$reward_sorting_counter] = $reward_info_for_sort['level'];
				$reward_sorting_counter ++;
			}
			asort($reward_array_to_sort);
			$category_sorting_counter = 0;
			foreach ($sessionpatient['campaign_rewards']['rewards'][0]['reward'] as $discard => $category_info_for_sort) {
				if(isset($category_info_for_sort['reward_id'])){
				$category_array_to_sort[$category_sorting_counter] = $category_info_for_sort['reward_id'];
				$category_sorting_counter ++;
			}
			}
			$category = (array_unique($category_array_to_sort));
			
		}
				$reward_array=array();
		$i=0;
		foreach ($reward_array_to_sort as $reward_item => $discard) {
			$reward_info = $sessionpatient['campaign_rewards']['rewards'][0]['reward'][$reward_item];
			if (!empty($reward_info['description']) && !empty($reward_info['level'])) {
			
		
			$reward_array[$i]['id']=$reward_info['id'];
			$reward_array[$i]['level']=$reward_info['level'];
			$reward_array[$i]['description']=$reward_info['description'];
			if(isset($reward_info['reward_id'])){
			$reward_array[$i]['reward_id']=$reward_info['reward_id'];
		}
		$i++;
	}
		}
		
		//print_r($reward_array);die;
	$thispage = Staff_Name.'rewards/reward'  ;
    $num = count($reward_array); // number of items in list
    $per_page = 6; // Number of items to show per page
    $showeachside = 5; //  Number of items to show either side of selected page
	$pg=floor($num/$per_page);
	$end_page=$pg*6;
    if (!isset($_GET['start'])) {
    $start=0;  // Current start position
	}else{
	$start=$_GET['start'];
	}
    $max_pages = ceil($num / $per_page); // Number of pages
    $cur = ceil($start / $per_page)+1; // Current page number
		$reward_array_final=array();
		$k=0;
		for($x=$start;$x<min($num,($start+$per_page));$x++){
			$reward_array_final[$k]=$reward_array[$x];
			$k++;
			 };
?>
<!--
<div>Filter By-</div>
			<div class=" grid_66 right categories content_center">
			<a id="showall">Category</a>
			<a class="showSingle" target="1">Recentlly Added</a>
			<a class="showSingle" target="2">Popular</a>
			<a class="showSingle" target="3">points</a>
			</div>
		<div>
		-->
      <div class=" clearfix">
      <?php echo $this->element('left_sidebar'); ?>
        <div class="col-lg-9 rightcont">
        <div class="banner"> 
         <?php echo $this->html->image('reward_imges/banner2.png',array('width'=>'730','height'=>'250', 'class' => 'img-responsive'));?>
          </div>
          <div class="loginArea">
         
          	<div class="row page_main">
            	<div class="col-lg-12 page_sort">
                	SORT BY <span> &#62 </span>
                </div>
                </div>
            
            <div class="contBox pad-top-13">
			<?php
			 if(isset($sessionpatient['customer_info']['campaigns'])){ $current_balance=$sessionpatient['customer_info']['campaigns'][0]['campaign'][0]['balance']; }else{ $current_balance='0'; }
			 if (isset($sessionpatient['campaign_rewards']) && sizeof($sessionpatient['campaign_rewards']['rewards'][0]['reward']) > 0) {
				foreach ($reward_array_final as $reward_item => $discard) {
					$need_more=$discard['level']-$current_balance;
					if (!empty($discard['description']) && !empty($discard['level'])) {  // ignore blank entries
						if ($sessionpatient['current_campaign_type'] == 'points') {
							if (intval($sessionpatient['current_campaign_balance']) >= intval($discard['level'])) {
								
					?>
           <!-- <div class="col-md-4 col-xs-12" onClick="document.reward_form_<?=$discard['id']?>.submit();" onclick="lightbox();"> -->
           <?php if($sessionpatient['is_mobile']==0){ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 hand-icon" onclick="lightbox(<?=$discard['id']?>);">
            <?php }else{ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 hand-icon" onClick="document.reward_form_<?=$discard['id']?>.submit();">
            <?php } ?>
            <?php }else{ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <?php } ?>
            <form action="<?=Staff_Name?>rewards/rewarddetail/" method="POST" name="reward_form_<?=$discard['id']?>">
						
						<input type="hidden" name="which_reward_id" value="<?=$discard['id']?>">
						<input type="hidden" name="which_reward_description" value="<?=urlencode($discard['description'])?>">
						<input type="hidden" name="which_reward_level" value="<?=$discard['level']?>">
						<input type="hidden" name="which_campaign" value="<?=$sessionpatient['preferences']['campaigns_to_show']?>">
						<input type="hidden" name="which_campaign_type" value="<?=$sessionpatient['campaign_rewards']['campaign'][0]['type']?>">
						
						<?php
						$uploadFolder = "rewards/".$sessionpatient['api_user'];
                //full path to upload folder
                $uploadPath = WWW_ROOT .'img/'. $uploadFolder;
						if ($sessionpatient['current_campaign_type'] == 'points') { ?>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 productBoxSM">
                    <p><a title="<?=$discard['description']?>" href=""><?php
                    if(strlen($discard['description'])>40){
								echo substr($discard['description'],0,40).'...';
							}else{
								echo $discard['description'];
							}
                    ?></a></p>
                    <?php
                    if (file_exists($uploadPath.'/'.$discard['description'])) {
							
								?>
								<img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user'].'/'.$discard['description']?>" width="175" height="117">
								<?php
							}else{
								if (file_exists($uploadPath."/noimage.jpg")) {
									?><img src="<?=$this->webroot.'img/rewards/'.$sessionpatient['api_user']?>/noimage.jpg" width="175" height="117">
									<?php
								}else{
									echo '<img src="http://redeem.integr8ideas.com/wp-admin/rewardimages/'.$discard['description'].'.jpg" alt="" width="175" height="117">';
								}
							}
                    ?>
                    <h3><?=$discard['level']?> points
                    <?php if($need_more>0){ ?>
                    <span>You need <?=$need_more?> more points.</span>
                    <?php } ?>
                     <span class="headTopCorner"></span>
                     <span class="headrightcorner"></span>
                     </h3>
                  </div>
                  </form>
            </div>
           
           <?php }} } }}else{ ?>
            <div class="col-md-4 col-xs-6">
              <div class="col-lg-12 productBoxSM">
                    <p>No Rewards Avalable!!</p>
                  </div>
            </div>
           <?php } ?>
            <span class="pagination"> 
<?php 
$eitherside = ($showeachside * $per_page);
if($start+1 > $eitherside){ ?>
<a class="" href="<?php print("$thispage");?>"><<</a>
<?php }
$pg=1;
for($y=0;$y<$num;$y+=$per_page)
{
    $class=($y==$start)?"pageselected":"";
    if(($y > ($start - $eitherside)) && ($y < ($start + $eitherside)))
    {
?>
&nbsp;<a class="<?php print($class);?>" href="<?php print("$thispage".($y>0?("?start=").$y:""));?>"><?php print($pg);?></a>&nbsp; 
<?php
    }
    $pg++;
}
if(($start+$eitherside)<$num){ ?>
<a class="" href="<?php print("$thispage".($end_page>0?("?start=").$end_page:""));?>">>></a>
<?php }
?>
</span>
       </div>
      </div>
        </div>
      </div>
 
 
 
 
 
 
                  
 <!-- Modal -->
<div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-sm">
    <div class="modal-content popup ">
      <div class="row rowcont">
      <div class="modal-header col-md-7">
        <a class="close closebtn" onclick="close_form();">&times;</a>
      </div>
      </div>
      <form action="" method="POST" name="reward" id="RewardForm">
						<input type="hidden" name="action" value="confirm_redeem">
						<input type="hidden" name="reward_id" id="reward_id" value="">
      <div class="modal-body clearfix">
         <div class=" row  detail">
        <div class="col-xs-12 clearfix">
         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div id="rewardImg"></div>
        
      <?php  
    if($sessionpatient['profile_comp']<100){
		echo 'You can redeem on 100% Profile completion';
		?>
		<a href="<?=Staff_Name?>rewards/editprofile/">COMPLETE YOUR PROFILE</a>
		<?php
		}else{
		?>
     <input type="submit" class="btn btn-primary buttondflt redeem" value="REDEEM NOW" >
     <?php } ?>
     <div id="wishlist_div">
      </div>
    
      <div class="socialIcon">
          <p class="pull-left">SHARE</p>
			

			<a href="" id='twlink' target="_blank"><?php echo $this->html->image('reward_imges/twitter.png',array('width'=>'23','height'=>'22','alt'=>'twitter','title'=>'twitter'));?></a>
            <a href="" id="fblink" target="_blank"><?php echo $this->html->image('reward_imges/facebook.png',array('width'=>'23','height'=>'22','alt'=>'facebook','title'=>'facebook'));?></a>
			<a href="" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" id="gplink"><?php echo $this->html->image('reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus','title'=>'googleplus'));?></a>
           </div>
           </div>
           
           <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h2 class="challengeDetail">
            
          
           <span id="reward_name"></span>
          </h2>
         <p class="description">
         <strong>Details/Instructions:</strong>
			<div id="desc"></div>
         </p>
         </div>
      </div>
      
     </div>
      </div>
      </form>
    </div>
  </div>
</div>   <!--popup-->     
<div class="" id="Mymodel1"></div>

<script>

	
function lightbox(reward_id){

 $('#myModal').addClass("modal fade popupBox in");
 $('#myModal').attr('aria-hidden',false);
 $('#myModal').css('display','block');
 $('#Mymodel1').addClass('modal-backdrop fade in');
  $.ajax({
	  type:"POST",
	  data:"reward_id="+reward_id,
	  dataType: "json",
	  url:"<?=Staff_Name?>rewards/getreward/",
	  success:function(result){
		$('#reward_name').text(result.rewards['level']+' Points');  
		$('#desc').text(result.rewards['description']); 
		$('#rewardImg').html(result.rewards['imagepath']); 
		$('#reward_id').val(result.rewards['id']);
		$("a#fblink").attr("href", "http://www.facebook.com/sharer.php?u=<?=Patient_Url?>redeemreward/"+result.rewards['id']+"&t=Share on Facebook");
		$("a#twlink").attr("href", "https://twitter.com/intent/tweet?url=<?=Patient_Url?>redeemreward/"+result.rewards['id']+"&amp;text=Share On twitter&amp;via=IntegrateOrtho");
		$("a#gplink").attr("href", "https://plus.google.com/share?url={<?=Patient_Url?>redeemreward/"+result.rewards['id']+"}");
		$("#RewardForm").attr("action", "<?=Staff_Name?>rewards/redeemreward/"+result.rewards['id'])
    if(result.WishLists==1){
		$('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Added To WishList">');
	}
	else{
		$('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Add To WishList" onclick="wish();">');
	}
  }});

 
}
function close_form(){

 $('#myModal').addClass("modal fade popupBox");
 $('#myModal').attr('aria-hidden',true);
 $('#myModal').css('display','none');
  $('#Mymodel1').removeClass('modal-backdrop fade in');
 }



function wish(){
	var reward_id=$('#reward_id').val();
  $.ajax({
	   
	  type:"POST",
	  data:"reward_id="+reward_id,
	  dataType: "json",
	  url:"<?=Staff_Name?>rewards/addwishlist/",
	  success:function(result){
    if(result==1){
		$('#wishlist_div').html('<input type="button" class="btn buttondflt pull-righ" value="Added To WishList">');
	}
  }});
} 
</script>
