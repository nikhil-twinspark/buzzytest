<style type="text/css">
.banner-topPoints {padding-top: 50px; padding-left: 25px;
padding-right: 25px;}


@media  (max-width:767px){
.lefcontBox .proflie .profile_mob{font-weight: 200; line-height: 14px; font-size: 14px; color: #fff; text-transform: uppercase; font-family: 'Montserrat', sans-serif; margin-bottom: 10px;}
.col-md-7.col-sm-7.col-xs-7{float: right; width: 68%;}
.col-md-5.col-sm-5.chart-h {max-height: 90px; max-width: 80px; float: left; }
.lefcontBox .proflie {display:block; clear: both; padding: 20px;}
	.proflie_mob { display: block;
width: 100%;
float: none;
clear: both;
padding: 25px;
border-top: 4px solid #fff;

}
.proflie_mob p{
	text-align: center;	
}	
.leftcont { height:auto !important;}
.learn_more { bottom:229px;}
.lefcontBox .proflie p +p{max-width:100%; margin-top: 3px;}
.lefcontBox .proflie p.profilelink a{ color:white;}
}

@media  (min-width: 345px) and (max-width: 405px){
.earnpoint .learn_more{margin-top: 29px;}
}
@media  (max-width: 345px){
.earnpoint .learn_more{margin-top: 17px;}
}
</style>
<?php $sessionpatient = $this->Session->read('patient');
?>
<div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

       
        
          </div>
  <div class=" clearfix">
       
        <?php
         function monthDropdown($name="month", $selected=null)
{
        $dd = '<select name="'.$name.'" id="'.$name.'" onchange="this.form.submit()">';

        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;
		
        for ($i = 1; $i <= 12; $i++)
        {
                $dd .= '<option value="'.$i.'"';
                if ($i == $selected)
                {
                        $dd .= ' selected';
                }
                /*** get the month ***/
                $mon = date("F", mktime(0, 0, 0, $i+1, 0, 0, 0));
                $dd .= '>'.$mon.'</option>';
        }
        $dd .= '</select>';
        return $dd;
}
?>
       
        <div class="col-lg-9 rightcont">
          <div class="row  banner-topPoints">
           <div class="col-md-4 pointsSM">
			   <?php echo $this->html->image(CDN.'img/reward_imges/pointssm.png');?>
 
            <h2>Points</h2>
            <p>Earn points on every visit for being a great patient. Earn bonus points for participating in challenges, getting good grades, doing community service and much more!</p>
          </div>
           <div class="col-md-4 pointsSM">
			      <?php echo $this->html->image(CDN.'img/reward_imges/rewardsm.png');?>

            <h2>rewards</h2>
            <p>Redeem your points for electronics, video games, iPods, toys, books, T-shirts, gift cards, or use them to play challenges, contests and win more fun stuff.</p>
          </div>
           <div class="col-md-4 pointsSM">
			    <?php echo $this->html->image(CDN.'img/reward_imges/challenge.png');?>
            <h2>challenges</h2>
            <p>Enter our challenges and win big prizes. We work on updating challenges regularly to make this experience fun for you and your family.</p>
          </div>
          </div>
          
          
          <?php if($sessionpatient['is_mobile']==1){ echo $this->element('left_sidebar');}?>
          
           
          <div class="ActivityFeed ">
            <div class="ActivityFeedTittle col-md-4" id="activityfeed">
<?php echo $this->Session->flash(); ?>
            <h2>YOUR Activity Feed</h2>
             <form class="form_size Feeddropdown" action="<?=Staff_Name?>rewards/home/#activityfeed" method="POST" id="dateform">
              <span class="dropIcon"></span>
                    <?php echo monthDropdown('my_dropdown', $selectedmonth); ?>
             </form>
              </div>
             <table class="table table-striped tablefeed">
              <tr >
               <td class="feedhead">Date</td>
                <td class="feedhead">Points</td>
                 <td class="feedhead">ACTIVITY</td>
              </tr>
              <?php 	
          //echo "<pre>";print_r($Transaction);die;
                        $count_record=True;
			if(isset($Transaction)){
			foreach ($Transaction as $discard => $transaction_info) {


				$curyear=date('Y');
				$curmonth=date('n');
				if($selectedmonth>$curmonth){
					$curyear--;
				}
				
				$start=strtotime($curyear.'-'.$selectedmonth.'-01');
				$end=strtotime($curyear.'-'.$selectedmonth.'-31');
				$tdate=explode(' ',$transaction_info['Transaction']['date']);
				if($start<=strtotime($tdate[0]) && $end>=strtotime($tdate[0])){
                                    $count_record=False;
				?>
             <tr>
               <td><?=$tdate[0]?>:</td>
               
               <?php
					$amount_to_show = $transaction_info['Transaction']['amount'];
				if ($transaction_info['Transaction']['amount'] < 0) {
					?>
					<td><?=$amount_to_show?></td>
					<?php
				} elseif (!empty($transaction_info['Transaction']['amount'])) {
					?>
					<td>+<?=$amount_to_show?></td>
					<?php
				} else {
					?>
					<td>&nbsp;</td>
					<?php
				}
				?>
               <td>	<?php
				
					if (!empty($transaction_info['Transaction']['authorization'])) {
						echo $transaction_info['Transaction']['authorization'];
					} else {
						echo '&nbsp;';
					}
				 ?></td>
             </tr>
                    <?php
                            } //if end here  ?>

               <?php   } // foreach end here
                    }else{
			?>
			 <tr>
                            <td colspan="3">There are no activities listed for the selected month</td>
                        </tr>
             <?php } ?>
             <?php if($count_record){ ?>
                    <tr><td colspan="3">There are no activities listed for the selected month</td></tr>
             <?php } ?>
             </table>
            
      </div>
        </div>
        <div class="desktop_content">
       <?php if($sessionpatient['is_mobile']==0){ echo $this->element('left_sidebar');}?>
       </div>
        </div>
      
   





































	
		

					
		

		
		
