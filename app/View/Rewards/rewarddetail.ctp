<style type="text/css">
.loginArea input { width:50%; border-radius: 0; -moz-border-radius:0; -webkit-border-radius:0; color: #fff;}
#wishlist_div input { width:100%; text-transform: uppercase; color: #fff; background: url(<?php echo CDN; ?>img/reward_imges/button-arrow.png) no-repeat 85% 9px #3f7ae8;}
@media (min-width: 100px) and (max-width: 599px) {
.col-lg-3.leftcont { display:none;}
}
</style>
<?php $sessionpatient = $this->Session->read('patient'); 

?>
<div class="mobilebanner"> 
         <div id="logo">
        <?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?>
        </div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

       
        
          </div>


      <div class=" clearfix m-top-20n">
        <div class="col-lg-9 rightcont">
        <div class="banner"> 
         <?php echo $this->html->image(CDN.'img/reward_imges/banner2.png',array('width'=>'730','height'=>'250','class'=>'img-responsive'));?>
          </div>
          <div class="loginArea ">
			  
            <div class="contBox">
				 <div class="mobilebanner"> 
        <form action="<?=Staff_Name?>rewards/redeemreward/<?php echo $rewards['rewards']['Reward']['id'];?>" method="POST" name="reward" id="RewardForm">
						<input type="hidden" name="action" value="confirm_redeem">
						<input type="hidden" name="reward_id" id="reward_id" value="<?=$rewards['rewards']['Reward']['id']?>">
         <div class="rewardslider">
                  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                     <div class="item active rewardItem ">
                          <?php echo $rewards['rewards']['Reward']['imagepath'];?>
                         </div>
                        
                    </div>
                    
      
                   </div>
               </div>
          </div><!--mobile banner--->
      <div class=" row  detail">
        <div class="col-xs-12 clearfix m-top-20p">
         <h2 class="challengeDetail">
            <div id="rewardImg"><?php echo $rewards['rewards']['Reward']['imagepath'];?></div>
           <span><?php echo $rewards['rewards']['Reward']['points'];?> Points</span>
          </h2>
         
       <div style="display:block;" class="clearfix	">
    <?php   if ($sessionpatient['profile_comp'] < 100) { ?>
    <span class="complete">
                                            <?php
                                        echo 'Click the link below to complete your profile, then you can redeem!';
                                        ?>
                                        </span>
     <a href="<?= Staff_Name ?>rewards/editprofile/" class="btn btn-primary buttondflt pull-righ profile_comp">COMPLETE YOUR PROFILE</a>
<?php }else{ ?>
     <input type="submit" class="btn redeem col-xs-6" value="REDEEM NOW" >
<?php } ?>
     <div id="wishlist_div" class="col-xs-6">
     <?php if($rewards['WishLists']==1){ ?>
     <input type="button" class="btn pull-righ" value="Added To WishList">
     <?php }else{ ?>
     <input type="button" class="btn pull-righ" value="Add To WishList" onclick="wish();">
     <?php } ?>
     </div>
      </div>
      <div class="socialIcon">
          <p>SHARE</p>
           <a href="https://twitter.com/intent/tweet?url=<?php echo $_SERVER['HTTP_HOST'];?>/rewards/redeemreward/<?php echo $rewards['rewards']['Reward']['id'];?>&amp;text=Share On twitter&amp;via=buzzydoc" id='twlink' target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png',array('width'=>'23','height'=>'22','alt'=>'twitter','title'=>'twitter'));?></a>
            <a href="http://www.facebook.com/sharer.php?u=<?php echo $_SERVER['HTTP_HOST'];?>/rewards/redeemreward/<?php echo $rewards['rewards']['Reward']['id'];?>&t=Share on Facebook" id="fblink" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png',array('width'=>'23','height'=>'22','alt'=>'facebook','title'=>'facebook'));?></a>
			<a href="https://plus.google.com/share?url={<?php echo $_SERVER['HTTP_HOST'];?>/rewards/redeemreward/<?php echo $rewards['rewards']['Reward']['id'];?>}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" id="gplink"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus','title'=>'googleplus'));?></a>
            </div>
         <p class="description">
         <strong>Details/Instructions:</strong>
        <?php echo $rewards['rewards']['Reward']['description'];?>
         </p>
      </div>
      </form>
     </div>
</div>
		 </div>
        </div>
      <?php echo $this->element('left_sidebar'); ?></div>

<script>

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
