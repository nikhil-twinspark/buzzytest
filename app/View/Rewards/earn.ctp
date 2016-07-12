     <?php 
//echo $_SERVER['HTTP_USER_AGENT'];
$sessionpatient = $this->Session->read('patient');?>
      <div class="mobilebanner"> 
         <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>
        
            <div id="navimob"><a href="#" id="pull">
        <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png',array('width'=>'31','height'=>'26'));?>
 
        </a></div>

        <?php echo $this->html->image(CDN.'img/reward_imges/mobilebanner2.png',array('width'=>'31','height'=>'26'));?>
        
          </div>

	
       <div class=" clearfix">
       <?php echo $this->element('left_sidebar'); ?>
        <div class="col-lg-9 rightcont">
        <div class="banner"> 
   
        <?php 
     
        if($sessionpatient['clinic_id']==57){
        echo $this->html->image(CDN.'img/reward_imges/EARNbg.jpg',array('width'=>'730','height'=>'250', 'class' => 'img-responsive'));
        }else{
        echo $this->html->image(CDN.'img/reward_imges/banner.png',array('width'=>'730','height'=>'250', 'class' => 'img-responsive'));    
        }
        ?>
          
        </div>
            <div class="documentlink">
                <a href="#documentdiv" id="document">View our documents</a>    
            </div>
          <div class="loginArea ">
            <div class="contBox ln_height">
                <P>Here are a few ways you can earn points on your <strong><?php //echo $campaign_name ?></strong> card:</P>
       <!--<P>Up to <strong>50</strong> points per visit for performing tasks (10 points for each) recommended by Dr. Dykes. A few good examples are: good oral hygiene, showing up for the visit on time, wearing your Lakeway T-shirt to your appointment, no broken appliaces or brackets, good elastic/headgear wear and checking in on Facebook.</P>-->
       <ul>
             <?php foreach($Promotions as $row){ ?>
              <li><p>Earn <strong><?php echo $row['Promotion']['value'] ?></strong> 
                      points for <?php echo $row['Promotion']['description'] ?></p></li>
              
             
             <?php } ?>
       </ul>
       </div>
         <div class="contBox ln_height" id="documentdiv">
            <?php if(count($Documents) > 0){ ?>
                <P class='doc_heading'>Documents & Forms</P>
            <?php }else{ ?>
                <P>There are no documents available right now. Please check back later.</P>
            <?php } ?>
       <!--<P>Up to <strong>50</strong> points per visit for performing tasks (10 points for each) recommended by Dr. Dykes. A few good examples are: good oral hygiene, showing up for the visit on time, wearing your Lakeway T-shirt to your appointment, no broken appliaces or brackets, good elastic/headgear wear and checking in on Facebook.</P>-->
       <ul class="doc_listing">
            <?php if(count($Documents) > 0){ ?>
                <?php foreach($Documents as $key){ ?>

                       <li>
                           <a href="<?php echo $key['Document']['document']; ?>" target="_blank">
                                   <?php echo $key['Document']['title'];?>
                               </a>
                           
                       </li>
                       
                <?php } ?>
             <?php } ?>
       </ul>
       </div>
      </div>
        </div>
       
        </div>
      
   





































	
		

					
		

		
		
