     <?php $sessionpatient = $this->Session->read('patient');?>
      <div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } 
       
       
       ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>
<form action="<?=Staff_Name?>rewards/contestdetail/" method="POST" name="challenge" id="challengeForm">
       <div class="mob_banner">
       	 <?php if(isset($challenges[0]['c']['contest_image'])){ ?>
        <img src="<?=$challenges[0]['c']['contest_image']?>" width='730' height='300' class="img-responsive"/>
        <?php }else{ ?>
        <?php echo DEF_CHALLENGE_HEADER_IMAGE;?>
        <?php }if(isset($challenges[0]['c']['contest_name'])){ ?>
        <div class="challenges"><?=$challenges[0]['c']['contest_name']?></div>
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
        <?php if(isset($challenges[0]['c']['contest_image'])){ ?>
        <img src="<?=$challenges[0]['c']['contest_image']?>" width='730px' height='300px' class="img-responsive"/>
        <?php }else{ ?>
        <?php echo DEF_CHALLENGE_HEADER_IMAGE;?>
        <?php }if(isset($challenges[0]['c']['contest_name'])){ ?>
        <div class="challenges"><?=$challenges[0]['c']['contest_name']?></div>
         <?php }else{ ?>
         <div class="challenges"><?=DEF_CHALLENGE_NAME?></div>
         <?php } ?>
      
            <a class="btn btn-primary buttondflt enter" onclick="lightbox1();">ENTER NOW</a>
         
         
          </div>
          
         
      
			
 

          <div class="loginArea ">
                 <div class="contBox">
					 <P><?php if(isset($challenges[0]['c']['contest_description'])){ echo $challenges[0]['c']['contest_description']; }else {
						 echo DEF_CHALLENGE_DESC;
						} ?></P>
					
			

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
		  <?php if(isset($challenges[0]['c']['contest_area'])){
			  echo $challenges[0]['c']['contest_area'];
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





























	
		

					
		

		
		
