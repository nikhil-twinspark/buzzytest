
 <div class="top-buzz-bg">
      <div class="row cf">
        <section class="left-module">
          <header class="left-module-heading">
            <div class="modified-border-top"></div>
            <h4 class="left-module-title-heading">Activity Feed</h4>
            <div class="modified-border-bottom clear"></div>
          </header><!--  .left-module-heading-->
<input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>">
<input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>">
          <ul class="left-module-listing" id="thebuzzlist">
              <?php foreach($activitydetails as $promo){ 
if($promo->Transaction->activity_type=='like clinic'){
?>
                                                
              <li>

                <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                <div class="data-container">
                  <div class="listing-point">&nbsp;</div>
                  <div class="doc-small-img">
                  
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                  </div>
                  <span class="doc-place-name">
                        <?php $givenName = $promo->Transaction->first_name; ?>
                        <?php echo ((strlen($givenName) > 30) ? substr($givenName,0,30).'...' : $givenName) ?></span>
                  <p class="listing-description">Liked the <?php echo ((strlen($promo->Transaction->given_name) > 30) ? substr($promo->Transaction->given_name, 0,30).'...' : $promo->Transaction->given_name); ?> practice </p>
                </div>
              </li>
               <?php } else if($promo->Transaction->activity_type=='save doctor'){
?>
                                                
              <li>
                <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                <div class="data-container">
                  <div class="listing-point">&nbsp;</div>
                  <div class="doc-small-img">
                  
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                  </div>
                  <span class="doc-place-name">
                        <?php $givenName = $promo->Transaction->first_name; ?>
                        <?php echo ((strlen($givenName) > 30) ? substr($givenName,0,30).'...' : $givenName) ?></span>
                  <p class="listing-description">saved the Dr. <?php echo ((strlen($promo->Transaction->given_name) > 30) ? substr($promo->Transaction->given_name,0, 30).'...' : $promo->Transaction->given_name); ?>  </p>
                </div>
              </li>
               <?php }else if($promo->Transaction->activity_type=='earn badge'){
?>
                                                
              <li>
              <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
              <div class="data-container">
                <div class="listing-point">
                  <div class="badgeImg">
                   
<?php if($promo->Transaction->buzzy_name=='Newbie'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point1.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 1'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point2.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 2'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point3.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 3'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point4.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 4'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point5.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 5'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point6.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 6'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point7.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 7'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point8.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 8'){ echo $this->html->image(CDN.'img/images_buzzy/badge_point9.png',array('title'=>'','alt'=>'','class'=>''));}
if($promo->Transaction->buzzy_name=='Level 9'){ echo $this->html->image(CDN.'img/images_buzzy/badge10_big.png',array('title'=>'','alt'=>'','class'=>''));}
?>
                  </div>
                  <p class="point-num"><?php echo $promo->Transaction->buzzy_value; ?></p>
                  <p class="point-word">Points</p>
                </div>
                <div class="doc-small-img">
                  <?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                </div>
                <span class="doc-place-name">
                    <?php $givenName = $promo->Transaction->first_name; ?>
                    <?php echo ((strlen($givenName) > 30) ? substr($givenName,0,30).'...' : $givenName) ?></span>
                <p class="listing-description"><?php echo ((strlen($promo->Transaction->authorization) > 30) ? substr($promo->Transaction->authorization,0,30).'...' : $promo->Transaction->authorization); ?></p>
              </div>
            </li>
               <?php }else if($promo->Transaction->activity_type=='redeemed'){
?>
                                                
              <li>
                <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                <div class="data-container">
                  <div class="listing-point"><?php if($promo->Transaction->amount<0){ echo $promo->Transaction->amount;}else{ echo  '+'.$promo->Transaction->amount;} ?></div>
                  <div class="doc-small-img">
                  
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                  </div>
                  <span class="doc-place-name">
                        <?php $givenName = $promo->Transaction->first_name; ?>
                        <?php echo ((strlen($givenName) > 30) ? substr($givenName,0,30).'...' : $givenName) ?>
                    </span>
<?php if($promo->Transaction->authorization=='global points expired'){ ?>
                  <p class="listing-description" title="<?php echo $promo->Transaction->authorization; ?>"><?php echo ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization,0,20).'...' : $promo->Transaction->authorization); ?>  </p>
<?php }else if($promo->Transaction->product_service_id>0){ ?>
<p class="listing-description" title="<?php echo $promo->Transaction->authorization; ?>"><?php echo ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization,0,20).'...' : $promo->Transaction->authorization); ?>  </p>
<?php }else{ ?>
<p class="listing-description" title="Have redeemed the reward <?php echo $promo->Transaction->authorization; ?>">Have redeemed the reward <?php echo ((strlen($promo->Transaction->authorization) > 20) ? substr($promo->Transaction->authorization,0,20).'...' : $promo->Transaction->authorization); ?>  </p>
<?php } ?>
                </div>
              </li>
               <?php }else if($promo->Transaction->activity_type=='get point'){
?>
                                                
              <li>
                <span class="date-detail"><?php echo date("M d,Y", strtotime($promo->Transaction->date)); ?></span>
                <div class="data-container">
                  <div class="listing-point"><?php if($promo->Transaction->amount<0){ echo $promo->Transaction->amount;}else{ echo  '+'.$promo->Transaction->amount;} ?></div>
                  <div class="doc-small-img">
                  
<?php echo $this->html->image(CDN.'img/images_buzzy/user_small_pic.png',array('title'=>'','alt'=>'user picture','class'=>''));?>
                  </div>
                  <span class="doc-place-name" title="<?=$promo->Transaction->given_name?>">
<?php echo ((strlen($promo->Transaction->given_name) > 22) ? substr($promo->Transaction->given_name,0,22)."..." : $promo->Transaction->given_name); ?></span>

<?php $gavepoint = str_replace(array('-','+','*'), "", $promo->Transaction->authorization)." to ".$promo->Transaction->first_name; ?>                  
<p class="listing-description" title="Gave points for <?php echo $gavepoint; ?>">Gave points for 
                  
                    <?php echo ((strlen($gavepoint) > 30) ? substr($gavepoint, 0, 30)."..." : $gavepoint) ?>
                    </p>
                </div>
              </li>
               <?php }} ?>
         
            
           
          
          </ul><!--  .left-module-listing-->
            <div id="moreact" class="more-activity-expand-btn-wrap">
                    <a href="javascript:void(0)" id='change_redeem_btn' class="more-activity-expand-btn" >More Activity</a>
                <!--<input type="button" value="More Activity" id='change_redeem_btn' class="more-activity-expand-btn">-->
                <span id="status-bar"></span>
            </div>
        </section><!--  .left-module-->


                    <!-- <aside class="right-module">
            <div class="right-module-inner">
              <header class="right-module-main-heading">Practice’s Giving the Most Points in Your Area</header>
              <ul class="right-module-listing">

<?php 

if(!empty($topclinic)){
foreach($topclinic as $mostpop){ ?>
                <li>
                  <div class="clinic-info-wrap cf">
                    <div class="view-profile-box">
                        <a href="<?php echo '/practice/' . str_replace(' ','',$mostpop->Clinic->api_user); ?>" class="profile-btn" title="View Profile">View Profile</a>
                      
<?php echo $this->html->image(CDN.'img/images_buzzy/dollar_thumb.png',array('title'=>'dollar','alt'=>'dollar thumb','class'=>'currency-thumb'));?>
                      <span class="clinic-points">+<?php echo $mostpop->Pointshare; ?></span>
                    </div>
                    <div class="clinic-img">
                              <?php 
if(isset($mostpop->Clinic->buzzydoc_logo_url) && $mostpop->Clinic->buzzydoc_logo_url!=''){ ?>
                                    <img src="<?php echo S3Path.$mostpop->Clinic->buzzydoc_logo_url;?>" alt="<?=$mostpop->Clinic->api_user;?>" title="<?=$mostpop->Clinic->api_user;?>" />
<?php
}else{
echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$mostpop->Clinic->api_user,'alt'=>$mostpop->Clinic->api_user,'class'=>'thumb-picture'));
}
?></div>
                    <div class="clinic-info">
                      <h4 class="clinic-name"><?php if($mostpop->Clinic->display_name==''){ echo $mostpop->Clinic->api_user;}else{ echo $mostpop->Clinic->display_name; } ?></h4>
<?php if(isset($mostpop->PrimeOffices)){ ?>
                      <h5 class="clinic-address"><?php echo $mostpop->PrimeOffices->ClinicLocation->address; ?> , <?php echo $mostpop->PrimeOffices->ClinicLocation->city; ?> , <?php echo $mostpop->PrimeOffices->ClinicLocation->state; ?></h5>
<?php } ?>
                      <div class="rating">
                        <?php $grey=5-$mostpop->Rate;
                                 for($i=0;$i<$mostpop->Rate;$i++){ ?>
                                                        <span class="fullstar"></span>
                            <?php }
                            for($i1=0;$i1<$grey;$i1++){ ?>
                                                        <span class="greystar"></span>
                            <?php } ?>
                      </div>
                    </div>
                  </div>
                </li>
<?php }}else{
    ?>
                <li>
                  <div class="clinic-info-wrap cf">
                   No Practice Found!
                  </div>
                </li>
                <?php
} ?>
             
              </ul>
            </div>
          </aside>
-->
<!-- .right-module--> 

      </div>
    </div><!--  .top-buzz-bg-->
<script>
$("#change_redeem_btn").click(function(){
       var limit=$('#limit').val();
       var offset=$('#offset').val();
       $("#status-bar").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                   type: "POST",
                   url: "/buzzydoc/morebuzz/",
                   data: "&limit="+limit+"&offset="+offset,
                   success: function(msg) {
                        $("#status-bar").html('');
                        if(msg!=''){
                        var likes=parseInt(offset)+10;
                        $('#offset').val(likes);
			$("#thebuzzlist").append(msg);
                       }else{
                       $('#moreact').html('');
                      
                      }
                   }
               }); 
           
    
    });
    
</script>