<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Patient's Ratings and Reviews
        </h1>
    </div>
    <span class="add_rewards" style="float:right;">
        <?php if(empty($ClinicLocations)){ ?>
        <a href="javascript:void(0);" title="Add" class="icon-1 info-tooltip grey" onclick="alert('You don\'t have Any Buisness Url for clinic.')">See All Reviews</a>
        <?php }else{ ?>
        <a href="javascript:void(0);" title="Add" class="icon-1 info-tooltip requestReview">See All Reviews</a>
        <?php } ?>
    </span>
    <div class="adminsuper">
         <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="10%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Requested By</td>
                        <td width="10%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Reviewed By</td>
                        <td width="5%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Rating</td>
                        <td width="7%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Identifier</td>
                        <td width="7%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                        <td width="30%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Review</td>
                        <td width="17%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date</td>

                        <td width="14%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Verify and Reward Points</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($Reviews as $Review)
					{
					?>
                    <tr> 
                        <td width="10%"><?php if($Review['Staff']['staff_id']!=''){ echo $Review['Staff']['staff_id']; }else{ echo 'Itself'; }?></td>
                        <td width="10%"><?php echo $Review['User']['first_name'].' '.$Review['User']['last_name'];?></td>
                        <td width="5%" ><?php echo $Review['RateReview']['rate'];?></td>
                        <td width="7%" ><?php if($Review['RateReview']['identifier']>0){ echo $Review['RateReview']['identifier']; }else{ echo 'NA'; }?></td>
                        <td width="7%" ><?php echo $Review['ClinicUser']['card_number'];?></td>
                        <td width="30%" ><?php echo $Review['RateReview']['review'];?></td>
                        <td width="17%" ><?php echo $Review['RateReview']['created_on'];?></td>

                        <td width="14%" class="editbtn response_btn"> 
                        <?php 
                        if($Review['RateReview']['identifier']<1 && $Review['RateReview']['clinic_location_id']<1){
                        ?>
                            <a class="" title="Not Allowed" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                            <a class="" title="Not Allowed" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                            <a class="" title="Not Allowed" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                            <a class="" title="Not Allowed" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                        <?php
                        }else if($Review['RateReview']['rate']<3){ ?>
                            <a class="" title="Not Eligible" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                            <a class="" title="Not Eligible" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                            <a class="" title="Not Eligible" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                            <a class="" title="Not Eligible" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                        <?php
                        }else if(($sessionstaff['staff_role'] == 'M' || $sessionstaff['staff_role'] == 'Manager') && $Review['Staff']['id']!=$sessionstaff['staff_id']){
                             ?>
                            <a class="" title="You Are Not Authorised" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                            <a class="" title="You Are Not Authorised" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                            <a class="" title="You Are Not Authorised" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                            <a class="" title="You Are Not Authorised" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                        <?php
                        }else{
                            $totalverify=0;
                            $verifytype='';
                        //condition for google review
                        if($Review['RateReview']['google_share']!=1 && $Review['RateReview']['notify_staff']==1 ){
                            $totalverify++;
                            $verifytype .='1,';
                        ?>
                            <span id="google_<?php echo $Review['RateReview']['id']; ?>"><a class="" title="Verify and reward points" href="javascript:void(0);" onclick="rewardpoint(<?php echo $Review['RateReview']['id'] ?>, 1);"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a></span>
                        <?php }else{
                            if($Review['ClinicLocation']['google_business_page_url']!=''){
                            if($Review['RateReview']['notify_staff']!=1){ ?>
                            <a class="" title="Not Notified Yet" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                            <?php }else{ ?>
                            <a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php
                            }}else{
                             ?>
                            <a class="" title="No Review as Business URL is missing" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php   
                            }
                        }  
                        //condition for yahoo review
                        if($Review['RateReview']['yahoo_share']!=1 && $Review['RateReview']['yahoo_notify']==1 ){  
                            $totalverify++;
                            $verifytype .='2,';
                        ?>
                            <span id="yahoo_<?php echo $Review['RateReview']['id']; ?>"><a class="" title="Verify and reward points" href="javascript:void(0);" onclick="rewardpoint(<?php echo $Review['RateReview']['id'] ?>, 2);"><?php echo $this->html->image(CDN.'img/reward_imges/images.jpeg',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a></span>
                        <?php }else{
                            if($Review['ClinicLocation']['yahoo_business_page_url']!=''){
                            if($Review['RateReview']['yahoo_notify']!=1){ ?>
                            <a class="" title="Not Notified Yet" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                            <?php }else{ ?>
                            <a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                        <?php
                        }}else{
                             ?>
                            <a class="" title="No Review as Business URL is missing" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php   
                            }
                        }
                        //condition for yelp review
                        if($Review['RateReview']['yelp_share']!=1 && $Review['RateReview']['yelp_notify']==1 ){
                            $totalverify++;
                            $verifytype .='3,';
                        ?>
                            <span id="yelp_<?php echo $Review['RateReview']['id']; ?>"><a class="" title="Verify and reward points" href="javascript:void(0);" onclick="rewardpoint(<?php echo $Review['RateReview']['id'] ?>, 3);"><?php echo $this->html->image(CDN.'img/reward_imges/yelp.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a></span>
                        <?php }else{
                            if($Review['ClinicLocation']['yelp_business_page_url']!=''){
                            if($Review['RateReview']['yelp_notify']!=1){ ?>
                            <a class="" title="Not Notified Yet" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                            <?php }else{ ?>
                            <a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                        <?php
                            }}else{
                             ?>
                            <a class="" title="No Review as Business URL is missing" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php   
                            }
                        }
                        //condition for healthgrades review
                        if($Review['RateReview']['healthgrades_share']!=1 && $Review['RateReview']['healthgrades_notify']==1 ){ 
                            $totalverify++;
                            $verifytype .='4,';
                        ?>
                            <span id="healthgrades_<?php echo $Review['RateReview']['id']; ?>"><a class="" title="Verify and reward points" href="javascript:void(0);" onclick="rewardpoint(<?php echo $Review['RateReview']['id'] ?>, 4);"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a></span>
                        <?php }else{
                            if($Review['ClinicLocation']['healthgrades_business_page_url']!=''){
                            if($Review['RateReview']['healthgrades_notify']!=1){ ?>
                            <a class="" title="Not Notified Yet" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                            <?php }else{ ?>
                            <a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                        <?php
                            }}else{
                             ?>
                            <a class="" title="No Review as Business URL is missing" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php   
                            }
                        }
                        $verifytype = rtrim($verifytype, ",");
                        if($totalverify>0){
                            ?>
                            <span id="All_<?php echo $Review['RateReview']['id']; ?>"><a class="btn btn-xs btn-danger" title="Reward points for all verified sites" href="javascript:void(0);" onclick="rewardpointAll(<?php echo $Review['RateReview']['id'] ?>, '<?php echo $verifytype; ?>');"><i class="ace-icon fa fa-external-link"></i></a></span>
                            <?php
                        }
                        
                        //end here
                        } ?>

                        </td>

                    </tr>
      <?php 	
					}//Endforeach
				 ?>
                </tbody>
            </table>

        </div>		
    </div>

</div>
<div class="modal fade" id="requestreviewModalMain" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog reedem-modalbox">
        <div class="modal-content">
            <div class="modal-header text-center">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true" id="closeredeem" >&times;</span></button>

                <h4 class="modal-title" id="redeemPointsLabel">See All Reviews</h4>
            </div>
            <div class="modal-body">
                <div class="text-center points-value-span-container1">

                    <?php foreach($ClinicLocations as $loc){ ?>
                    <div class="row clearfix">
                        <div class="col-sm-6"><p><strong><span class="glyphicon glyphicon-pushpin"></span><?php echo $loc['ClinicLocation']['address'].', '.$loc['ClinicLocation']['city'].', '.$loc['ClinicLocation']['state']; ?></strong></p>
                        </div>
                        <div class="col-sm-6 ">
                        <?php 
                        $socail_site = '';
                        $socialcount=0;
                        if($loc['ClinicLocation']['google_business_page_url']==''){
                            $socail_site .='google+, ';
                            $socialcount++;
                        }
                        if($loc['ClinicLocation']['yahoo_business_page_url']==''){
                            $socail_site .='yahoo, ';
                            $socialcount++;
                        }
                        if($loc['ClinicLocation']['yelp_business_page_url']==''){
                            $socail_site .='yelp, ';
                            $socialcount++;
                        }
                        if($loc['ClinicLocation']['healthgrades_business_page_url']==''){
                            $socail_site .='healthgrades, ';
                            $socialcount++;
                        }
                        $socail_site=rtrim($socail_site, ", ");
                        if($loc['ClinicLocation']['google_business_page_url']=='' || $loc['ClinicLocation']['yelp_business_page_url']=='' || $loc['ClinicLocation']['yahoo_business_page_url']=='' || $loc['ClinicLocation']['healthgrades_business_page_url']==''){ ?>
                            <p>You don't have a <?php echo $socail_site; ?> reviews page set up for this location yet. <a href='/ClinicLocation/edit/<?php echo $loc['ClinicLocation']['id']; ?>' target="_blank">Click Here</a> to add the URL now.</p>
                        <?php } 
                        ?>
                            <p>
                            <?php
                        if($loc['ClinicLocation']['google_business_page_url']!=''){ ?>
                                <a href="<?php echo $loc['ClinicLocation']['google_business_page_url']; ?>" target='_blank' class=""><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>
                        <?php }
                        if($loc['ClinicLocation']['yahoo_business_page_url']!=''){ ?>
                                <a href="<?php echo $loc['ClinicLocation']['yahoo_business_page_url']; ?>" target='_blank' class="" style="margin-left:3px;"><?php echo $this->html->image(CDN.'img/reward_imges/images.jpeg',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>
                        <?php }
                        if($loc['ClinicLocation']['yelp_business_page_url']!=''){ ?>
                                <a href="<?php echo $loc['ClinicLocation']['yelp_business_page_url']; ?>" target='_blank' class="" style="margin-left:3px;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>
                        <?php }
                        if($loc['ClinicLocation']['healthgrades_business_page_url']!=''){ ?>
                                <a href="<?php echo $loc['ClinicLocation']['healthgrades_business_page_url']; ?>" target='_blank' class="" style="margin-left:3px;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>
                        <?php }?>
                            </p>
                        </div>
                    </div>
                    <?php }  ?>
                </div>
            </div>

            <!--#endregion ThumbnailNavigator Skin End -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aaSorting": [[6, "desc"]],
            "sPaginationType": "full_numbers",
        });
        $('#example').dataTable().columnFilter({
            aoColumns: [{type: "text"},
                {type: "select"},
                {type: "text"},
                {type: "number"},
                null,
                {type: "text"},
                null
            ]

        });
    });

    function rewardpoint(tid, type) {
        var type_name = '';
        if (type == 1) {
            type_name = 'Google+';
        }
        if (type == 2) {
            type_name = 'Yahoo';
        }
        if (type == 3) {
            type_name = 'Yelp';
        }
        if (type == 4) {
            type_name = 'Healthgrades';
        }
        var r = confirm("Would you like to give points to this patient for " + type_name + " review?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                data: 'tid=' + tid + "&type=" + type,
                url: "<?php echo Staff_Name.'PatientRateReview/awardpoint' ?>",
                success: function (result) {
                    alert(result);
                    if (type == 1) {
                        $('#google_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>');
                    }
                    if (type == 2) {
                        $('#yahoo_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>');
                    }
                    if (type == 3) {
                        $('#yelp_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>');
                    }
                    if (type == 4) {
                        $('#healthgrades_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>');
                    }
                }
            });
        } else
        {
            return false;
        }

    }
    function rewardpointAll(tid, type) {
        var r = confirm("Would you like to give points to this patient for All Social Platform review?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                data: 'tid=' + tid + "&type=" + type,
                url: "<?php echo Staff_Name.'PatientRateReview/awardpointAll' ?>",
                success: function (result) {
                    alert(result);
                    typearray = type.split(',');
                    for (i = 0; i < typearray.length; i++) {
                        if (typearray[i] == 1) {
                            $('#google_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus-gray.png',array('width'=>'23','height'=>'22','alt'=>'googleplus'));?></a>');
                        }
                        if (typearray[i] == 2) {
                            $('#yahoo_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yahoo-gray.png',array('width'=>'23','height'=>'22','alt'=>'yahoo'));?></a>');
                        }
                        if (typearray[i] == 3) {
                            $('#yelp_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/yelp-gray.png',array('width'=>'23','height'=>'22','alt'=>'yelp'));?></a>');
                        }
                        if (typearray[i] == 4) {
                            $('#healthgrades_' + tid).html('<a class="" title="Points Already Rewarded" href="javascript:void(0);" style="cursor:default;"><?php echo $this->html->image(CDN.'img/reward_imges/HealthGrades-gray.png',array('width'=>'23','height'=>'22','alt'=>'healthgrades'));?></a>');
                        }
                    }
                    $('#All_'+tid).html('');

                }
            });
        } else
        {
            return false;
        }

    }
    $(document).on("click", ".requestReview", function () {
        $('#requestreviewModalMain').modal().fadeIn(100);
    });
</script>
















