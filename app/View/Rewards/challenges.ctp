     <?php $sessionpatient = $this->Session->read('patient');?>
      <div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } 
       
       
       ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>
<form action="<?=Staff_Name?>rewards/challenegdetail/" method="POST" name="challenge" id="challengeForm">
       <div class="mob_banner">
       	 <?php if(isset($sessionpatient['Themes']['challenge_header_image'])){ ?>
        <img src="<?=$sessionpatient['Themes']['challenge_header_image']?>" width='730' height='300' class="img-responsive"/>
        <?php }else{ ?>
        <?php echo DEF_CHALLENGE_HEADER_IMAGE;?>
        <?php }if(isset($sessionpatient['Themes']['challenge_name'])){ ?>
        <div class="challenges"><?=$sessionpatient['Themes']['challenge_name']?></div>
         <?php }else{ ?>
         <div class="challenges"><?=DEF_CHALLENGE_NAME?></div>
         <?php } ?>
         
            <button class="btn btn-primary buttondflt enter" onClick="document.challengeForm.submit();">ENTER NOW</button>
          
       </div>
          </form>
          </div>


       <div class=" clearfix">
       
		      <?php echo $this->element('left_sidebar'); ?>
        <div class="col-lg-9 rightcont">
			
		
			
        <div class="banner"> 
        <?php if(isset($sessionpatient['Themes']['challenge_header_image'])){ ?>
        <img src="<?=$sessionpatient['Themes']['challenge_header_image']?>" width='730' height='300' class="img-responsive"/>
        <?php }else{ ?>
        <?php echo DEF_CHALLENGE_HEADER_IMAGE;?>
        <?php }if(isset($sessionpatient['Themes']['challenge_name'])){ ?>
        <div class="challenges"><?=$sessionpatient['Themes']['challenge_name']?></div>
         <?php }else{ ?>
         <div class="challenges"><?=DEF_CHALLENGE_NAME?></div>
         <?php } ?>
      
            <a class="btn btn-primary buttondflt enter" onclick="lightbox1();">ENTER NOW</a>
         
         
          </div>
          
         
      
			
 

          <div class="loginArea ">
                 <div class="contBox">
					 <P><?php if(isset($challenges['challenge_description'])){ echo $challenges['challenge_description']; }else {
						 echo DEF_CHALLENGE_DESC;
						} ?></P>
					
			<?php
			 if(isset($sessionpatient['customer_info'])){
			  $current_balance=$sessionpatient['customer_info']['user']['points']; }else{ $current_balance='0';
			   }
			 if (isset($Reward) && sizeof($Reward) > 0) {
				foreach ($Reward as $reward_item => $discard) {
					$need_more=$discard[0]['points']-$current_balance;
					if (!empty($discard[0]['description']) && !empty($discard[0]['points'])) {  // ignore blank entries
						
							if (intval($current_balance) >= intval($discard[0]['points'])) {
								
					?>
           <!-- <div class="col-md-4 col-xs-12" onClick="document.reward_form_<?=$discard[0]['id']?>.submit();" onclick="lightbox();"> -->
            <?php if($sessionpatient['is_mobile']==0){ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >
            <?php }else{ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >
            <?php } ?>
            <?php }else{ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <?php } ?>
            <form action="<?=Staff_Name?>rewards/rewarddetail/" method="POST" name="reward_form_<?=$discard[0]['id']?>">
						<input type="hidden" name="action" value="confirm_redeem">
						<input type="hidden" name="which_reward_id" value="<?=$discard[0]['id']?>">
						<input type="hidden" name="which_reward_description" value="<?=urlencode($discard[0]['description'])?>">
						<input type="hidden" name="which_reward_level" value="<?=$discard[0]['points']?>">
					
						
						<?php
						$uploadFolder = "rewards/".$sessionpatient['api_user'];
                //full path to upload folder
                $uploadPath = WWW_ROOT .'img/'. $uploadFolder;
						 ?>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 productBoxSM">
                    <p><a title="<?=$discard[0]['description']?>" href=""><?php
                    if(strlen($discard[0]['description'])>40){
								echo substr($discard[0]['description'],0,40).'...';
							}else{
								echo $discard[0]['description'];
							}
                    ?></a></p>
                     <?php
                                                        echo '<img src="' . $discard[0]['imagepath'] . '" alt="" width="175" height="117">';
                                                        ?>
                    <h3><?=$discard[0]['points']?> points<br>
                    <?php if ($need_more > 0) { ?>
                                                                <span>You need <?= $need_more ?> more points.</span>
                    <?php }else{ ?>
                    <?php if ($sessionpatient['is_mobile'] == 0) { ?>
                <span>Bravo! <a class="hand-icon" onclick="lightbox(<?= $discard[0]['id'] ?>);">Click to redeem now</a></span>
                                            
                                                    <?php } else { ?>
                                                    <span>Bravo! <a class="hand-icon" onClick="document.reward_form_<?=$discard[0]['id']?>.submit();">Click to redeem now</a></span>
                                                
                                                    <?php } ?>  
                    <?php } ?>
                     <span class="headTopCorner"></span>
                     <span class="headrightcorner"></span>
                     </h3>
                  </div>
                  </form>
            </div>
           
           <?php  } }}else{ ?>
            <div class="col-md-4 col-xs-12">
              <div class="col-lg-12 productBoxSM">
                    <p>No Rewards Avalable!!</p>
                  </div>
            </div>
           <?php } ?>
 <div style="display:block; clear:both;" class="margin_20_bot">
  <a href="<?=Staff_Name?>rewards/reward/">
<button class="btn">See All Rewards</button>
</a>
</div>
       </div>
     
      </div>
       
      </div>
       
    
        </div>
        
      	
   




<!-- Modal -->
<div class="modal fade popupBox" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-sm">
    <div class="modal-content popup ">
      <div class="row rowcont">
      <div class="modal-header col-md-12">
        <a class="close closebtn" onclick="close1();">&times;</a>
      </div>
      </div>

      <div class="modal-body clearfix">
		  <?php if(isset($challenges['challenge_area'])){
			  echo $challenges['challenge_area'];
		  }else{
			   echo DEF_CHALLENGE_AREA; }?>

       </div> 
     
    </div>
  </div>
</div>   <!--popup-->     
 <!-- Modal -->
<div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-sm">
    <div class="modal-content popup ">
      <div class="row rowcont">
      <div class="modal-header col-md-12">
       <a class="close closebtn" onclick="close_rewards();">&times;</a>
      </div>
      </div>
      <form action="" method="POST" name="reward" id="RewardForm">
						<input type="hidden" name="action" value="confirm_redeem">
						<input type="hidden" name="reward_id" id="reward_id" value="">
      <div class="modal-body clearfix">
         <div class="detail">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div id="rewardImg"></div>
         <h2 class="challengeDetail">
            
           <span id="reward_name"></span>
          </h2>
        
     <div>
     <span class="complete">
                      <?php
                                        if ($sessionpatient['profile_comp'] < 100) {
                                            echo 'Click the link below to complete your profile, then you can redeem!';
                                            ?>
                                            </span>
                                            <a href="<?= Staff_Name ?>rewards/editprofile/" class="btn btn-primary buttondflt pull-righ profile_comp">COMPLETE YOUR PROFILE</a>
    <?php
} else {
    ?>
                                            <input type="submit" class="btn btn-primary buttondflt redeem" value="REDEEM NOW" >
<?php } ?>
     <div id="wishlist_div" class="wishlist_wid">
      </div>
      </div>
      <div class="socialIcon">
          <p class="pull-left">SHARE</p>
			

			<a href="" id='twlink' target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png',array('width'=>'23','height'=>'22','alt'=>'twitter','title'=>'twitter'));?></a>
            <a href="" id="fblink" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png',array('width'=>'23','height'=>'22','alt'=>'facebook','title'=>'facebook'));?></a>
			<a href="" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" id="gplink"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus','title'=>'googleplus'));?></a>
           </div>
         <p class="description">
         <strong>Details/Instructions:</strong>
			<div id="desc"></div>
         </p>
      </div>
      
     </div>
      </div>
      </form>
    </div>
  </div>
</div>   <!--popup-->  
<div class="" id="Mymodel1"></div>
<script>
function lightbox1(){
 $('#myModal1').addClass("modal fade popupBox in");
 $('#myModal1').attr('aria-hidden',false);
 $('#myModal1').css('display','block');
  $('#Mymodel1').addClass('modal-backdrop fade in');
}
function close1(){

 $('#myModal1').addClass("modal fade popupBox");
 $('#myModal1').attr('aria-hidden',true);
 $('#myModal1').css('display','none');
 $('#Mymodel1').removeClass('modal-backdrop fade in');
 }
 
 	
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
		$('#reward_name').text(result.rewards.Reward['points']+' Points');  
		$('#desc').text(result.rewards.Reward['description']); 
		$('#rewardImg').html(result.rewards.Reward['imagepath']);
		$('#reward_id').val(result.rewards.Reward['id']);  
		$("a#fblink").attr("href", "http://www.facebook.com/sharer.php?u=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&t=Share on Facebook");
                        $("a#twlink").attr("href", "https://twitter.com/intent/tweet?url=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&amp;text=Share On twitter&amp;via=buzzyDoc");
                        $("a#gplink").attr("href", "https://plus.google.com/share?url={<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "}");
                       $("#RewardForm").attr("action", "<?=Staff_Name?>rewards/redeemreward/"+result.rewards.Reward['id'])
    if(result.WishLists==1){
		$('#wishlist_div').html('<input type="button" class="btn pull-righ buttondflt" value="Added To WishList">');
	}
	else{
		$('#wishlist_div').html('<input type="button" class="btn pull-righ buttondflt" value="Add To WishList" onclick="wish();">');
	}
  }});

 
}
function close_rewards(){

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
		$('#wishlist_div').html('<input type="button" class="btn pull-righ" value="Added To WishList">');
	}
  }});
}
</script>





























	
		

					
		

		
		
