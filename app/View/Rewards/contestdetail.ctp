<style type="text/css">
    .detail h2.challengeDetail { margin-top:0;}
</style>
<?php $sessionpatient = $this->Session->read('patient'); 
    
?>
<div class="mobilebanner"> 
    <div id="logo"><?php if (isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url'] != '') { ?>
            <a href="<?= Staff_Name ?>rewards/home"><img src="<?= S3Path.$sessionpatient['Themes']['patient_logo_url'] ?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
        <?php } else { ?>
            <a href="<?= Staff_Name ?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg', array('width' => '246', 'height' => '88', 'alt' => 'Pure Smiles')); ?></a>
        <?php } ?></div>

    <div id="navimob"><a href="#" id="pull">
            <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png', array('width' => '31', 'height' => '26')); ?>

        </a></div>

    <?php //echo $this->html->image(CDN.'img/reward_imges/reward_mobile.jpg'); ?>

</div>


      <div class=" clearfix">
        <div class="col-lg-9 rightcont">
        <div class="banner"> 
         <?php echo $this->html->image(CDN.'img/reward_imges/banner2.png',array('width'=>'730','height'=>'250','class'=>'img-responsive'));?>
          </div>
          <div class="loginArea ">
			  
            <div class="contBox">
				 <?php if(isset($challenges[0]['c']['contest_area'])){
			  echo $challenges[0]['c']['contest_area'];
		  }else{
			   echo DEF_CHALLENGE_AREA; }?>
     </div>
</div>
		 </div>
        
      <?php echo $this->element('left_sidebar'); ?></div>

