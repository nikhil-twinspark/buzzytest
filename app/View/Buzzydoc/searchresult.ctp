<?php 
$sessionbuzzy = $this->Session->read('userdetail');
//echo "<pre>";print_r($Doctorslist);
?>
    <section class="top-docs-bg">
      <div class="row">
        <div class="top-docs-container">
          <div class="top-docs-details-wrap">
            <header class="top-docs-heading">Search Result</header>
           
            <div class="filter-search-results-wrap">
                <?php 
            
                if(count($SearchResult->doctorlist)<1 && count($SearchResult->clinicslist)<1){ ?>
                <p class="doctor-search-result-helper">
               0 results for "<?php echo $searchkey; ?>" 
                </p>
                <?php } ?>
                <?php if(count($SearchResult->doctorlist)>0){ ?>
                <p class="doctor-search-result-helper">
               <?php  echo count($SearchResult->doctorlist); ?> doctors results for "<?php echo $searchkey; ?>" 
                </p>
                <?php } ?>
                <?php 
                if(count($SearchResult->doctorlist)>0){
                foreach($SearchResult->doctorlist as $Doctors){ ?>
              <div class="filter-search-results cf">
                <div class="result-img-wrap">
                <?php 
						$docprofilePath = WWW_ROOT .'img/docprofile/'.$Doctors->ClinicName.'/'. $Doctors->Doctor->id;
						$docprofilePath1 = AWS_server.AWS_BUCKET.'/img/docprofile/'.$Doctors->ClinicName.'/'. $Doctors->Doctor->id;	
                                            if (file_exists($docprofilePath)) { ?>
                                        <img src="<?=$docprofilePath1?>" class='' width="76px" height="128px">
								
							<?php }else{
if($Doctors->Doctor->gender=='Male'){
 echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png',array('title'=>'doctor','width'=>'76px','height'=>'128px','alt'=>'doctor picture','class'=>'thumb-picture'));
								
							 }else{ 
echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png',array('title'=>'doctor','width'=>'76px','height'=>'128px','alt'=>'doctor picture','class'=>'thumb-picture'));
}} 

$d1 = new DateTime(date('Y-m-d'));
$d2 = new DateTime($Doctors->Doctor->dob);
$diff = $d2->diff($d1);
?>

                </div>
                <div class="result-detail">
                  <div class="result-detail-top-row">
                    <h4 class="result-heading">Dr. <?php echo $Doctors->Doctor->first_name; ?> <?php echo $Doctors->Doctor->last_name; ?>, <?php echo $Doctors->Doctor->degree; ?></h4>
                   
                    <div class="result-view-profile-btn-wrap">
                        
                      <a href="<?php echo '/doctor/' .$Doctors->Doctor->first_name.' '.$Doctors->Doctor->last_name.'/'.$Doctors->Doctor->specialty; ?>" class="result-view-profile-btn" title="View Full Profile">View Full Profile</a>
                    </div>
                  </div>
                  <h5 class="address-heading"><?php echo $Doctors->Doctor->address; ?></h5>
                      <p class="address-detials">
                        <?php echo $Doctors->Doctor->city; ?> , <?php echo $Doctors->Doctor->state; ?> , <?php echo $Doctors->Doctor->pincode; ?>
                      </p>
                  <ul class="result-doctor-list">
                    <li>Specializes in <?php echo $Doctors->Doctor->specialty; ?></li>
                        <li><?php echo $Doctors->Doctor->gender; ?></li>
                        <li>Age <?php echo $diff->y; ?></li>
                  </ul>
                  <ul class="result-main-btn">
                    <li>
                      <a >
                        <div class="rating tab-rating">
                         
                           <?php 
                                
                                 $grey=5-$Doctors->Rate;
                                 for($i=0;$i<$Doctors->Rate;$i++){ ?>
                                                        <span class="fullstar"></span>
                            <?php }
                            for($i1=0;$i1<$grey;$i1++){ ?>
                                                        <span class="greystar"></span>
                            <?php }
                            ?>
                        </div>
                        <span class="sub-txt">(<?php echo number_format((float)$Doctors->Rate, 1, '.', ''); ?>)</span>
                      </a>
                    </li>
                    <li><a >Save <span class="sub-txt">(<?php echo $Doctors->Save; ?>)</span></a></li>
                  
                  </ul>
                </div>
              </div>
              
                <?php }} ?>
                
                
                
            </div><!--  filter-search-results-wrap  -->
            
            <!--for clinic list -->
            
             <div class="filter-search-results-wrap">
                 <?php if(count($SearchResult->clinicslist)>0){ ?>
                 <p class="practice-search-result-helper">
                <?php  echo count($SearchResult->clinicslist); ?> practice results for "<?php echo $searchkey; ?>" 
                 </p>
                 <?php } ?>
                <?php 
                
               //echo "<pre>";print_r($SearchResult->clinicslist);
                if(count($SearchResult->clinicslist)>0){
                foreach($SearchResult->clinicslist as $Clinics){ ?>
              <div class="filter-search-results cf">
                <div class="result-img-wrap">
  <?php 
if(isset($Clinics->Clinic->buzzydoc_logo_url) && $Clinics->Clinic->buzzydoc_logo_url!=''){ ?>
                                    <img src="<?php echo S3Path.$Clinics->Clinic->buzzydoc_logo_url;?>" alt="<?=$Clinics->Clinic->api_user;?>" title="<?=$Clinics->Clinic->api_user;?>" />
<?php
}else{
echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>'Miles of Smiles','alt'=>'building picture','class'=>'thumb-picture'));
}
?>

                </div>
                <div class="result-detail">
                  <div class="result-detail-top-row">
                    <h4 class="result-heading"><?php if ($Clinics->Clinic->display_name == '') {
                                        echo $Clinics->Clinic->api_user;
                                    } else {
                                        echo $Clinics->Clinic->display_name;
                                        } ?></h4>
                 
                    <div class="result-view-profile-btn-wrap">
                      <a href="<?php echo '/practice/'.str_replace(' ','',$Clinics->Clinic->api_user);?>" class="result-view-profile-btn" title="View Full Profile">View Full Profile</a>
                    </div>
                  </div>
                  <h5 class="address-heading"><?php if(isset($Clinics->PrimeOffices)){ echo $Clinics->PrimeOffices->ClinicLocation->address; } ?></h5>
                      <p class="address-detials">
                        <?php if(isset($Clinics->PrimeOffices)){ echo $Clinics->PrimeOffices->ClinicLocation->city.' ,'.$Clinics->PrimeOffices->ClinicLocation->state.' ,'.$Clinics->PrimeOffices->ClinicLocation->pincode; } ?>
                      </p>
                      <ul class="result-doctor-list">
                          <?php $d=count($Clinics->Doctors);
                          $d1=1;foreach($Clinics->Doctors as $doc){ ?>
                    <li><?php echo $doc->Doctor->first_name; ?> <?php echo $doc->Doctor->last_name; ?><?php if($d!=$d1){ echo ","; }?></li>
                          <?php $d1++;} ?>
                  </ul>
                  
                  <ul class="result-main-btn">
                    <li>
                      <a>
                        <div class="rating tab-rating">
                           <?php 
                                
                                 $grey=5-$Clinics->Rate;
                                 for($i=0;$i<$Clinics->Rate;$i++){ ?>
                                                        <span class="fullstar"></span>
                            <?php }
                            for($i1=0;$i1<$grey;$i1++){ ?>
                                                        <span class="greystar"></span>
                            <?php }
                            ?>
                        </div>
                        <span class="sub-txt">(<?php echo number_format((float)$Clinics->Rate, 1, '.', ''); ?>)</span>
                      </a>
                    </li>
                    <li><a>Likes <span class="sub-txt">(<?php echo $Clinics->Likes; ?>)</span></a></li>
                    <li><a>Reviews <span class="sub-txt">(<?php echo $Clinics->TotalReview; ?>)</span></a></li>
                    <li><a>Check-ins <span class="sub-txt">(<?php echo $Clinics->TotalCheckin; ?>)</span></a></li>
                  
                  </ul>
                </div>
              </div>
              
                <?php }} ?>
                
                
                
            </div><!--  filter-search-results-wrap  -->
          </div><!-- .top-docs-details-wrap  -->
        </div><!-- .top-docs-container  -->
      </div>


    </section><!-- .relevant-details top-docs-bg -->



    <!-- jQuery (JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript">
      if (typeof jQuery == 'undefined') {
        document.write(unescape("%3Cscript src='js/jquery-1.11.1.min.js' type='text/javascript'%3E%3C/script%3E"));
      }
    </script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/jquery.responsiveTabs.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/custom.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
        // Tab text change function for first tab
        $('.alternate-tab').on('click', function(){
          $('.alternate-txt').show();
          $('.tab-rating-wrap').hide();
          $('.alternate-tab').removeClass('alternate-tab-class');
          $('#tab-1').show();
        }); // Tab text change function for first tab End

        $('#horizontalTab').responsiveTabs({
          rotate: false,
          startCollapsed: 'accordion',
          collapsible: 'accordion',
          setHash: false,
          disabled: [],
          activate: function(e, tab) {
            $('.info').html('Tab <strong>' + tab.id + '</strong> activated!');
          },
          activateState: function(e, state) {
            //console.log(state);
            $('.info').html('Switched from <strong>' + state.oldState + '</strong> state to <strong>' + state.newState + '</strong> state!');
          }
        });

        $('#horizontalTab').responsiveTabs('deactivate', 0);

        $('#start-rotation').on('click', function() {
          $('#horizontalTab').responsiveTabs('startRotation', 1000);
        });
        $('#stop-rotation').on('click', function() {
          $('#horizontalTab').responsiveTabs('stopRotation');
        });
        $('#start-rotation').on('click', function() {
          $('#horizontalTab').responsiveTabs('active');
        });
        $('.select-tab').on('click', function() {
          $('#horizontalTab').responsiveTabs('activate', $(this).val());
        });

      });


    </script>
    <script type="text/javascript">
      $( "#select-doctor-type" ).selectmenu();

      $( "#choose-insurance" ).selectmenu({
        disabled: true
      });

    </script>
 