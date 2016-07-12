
<section class="relevant-details">
    <div class="row">
        <div class="relevant-adjust">

                            <?php if($identifier!=''){ if($alreadyRateReview==0){ ?>
            <div class="details-wrap cf">
                <header class="relevant-header">Rate & Review</header>
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div>
                            <div class="col-sm-5">

<?php 
                                    if(isset($ClinicDetails['patient_logo_url']) && $ClinicDetails['patient_logo_url']!=''){                              
 ?>
                                <a href="javascript:void(0)"><img src="<?php echo S3Path.$ClinicDetails['patient_logo_url'];?>" alt="<?=$ClinicDetails['api_user'];?>" title="<?=$ClinicDetails['api_user'];?>" /></a>
<?php
}else{
    echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$ClinicDetails['api_user'],'alt'=>$ClinicDetails['api_user'],'class'=>'thumb-picture'));
}

?>
                            </div>

                            <div class="col-sm-5">
                                <h4 class="thumb-name"><?php if($ClinicDetails['display_name']==''){ echo $clinicname=$ClinicDetails['api_user'];}else{ echo $clinicname=$ClinicDetails['display_name']; } ?></h4>
                                <?php if(!empty($ClinicLocation)){ ?>
                                <p class="address-detials1"><?php echo $ClinicLocation['ClinicLocation']['address'].', '.$ClinicLocation['ClinicLocation']['city'].', '.$ClinicLocation['ClinicLocation']['state']; ?></p>
                                <?php } ?>
                            </div>

                        </div><!-- .detials-info-->
                    </div><!-- .details-all-info-wrap-->
                </div><!-- .main-details-->
                <input type="hidden" id="identifier" name="identifier" value="<?php echo $identifier; ?>">
                <div class="row" style="height:480px;">
                    <div class="col-sm-12">
                        <div class="row">
                            <form action="" method="POST" name="notification_form" onsubmit="return false;" class="form-horizontal">
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">&nbsp;</label>

                                    <div class="col-sm-9">
                                        <h3><?php echo $RateReview['Rate']; ?></h3>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Rate</label>

                                    <div class="col-sm-9">
                                        <div id="rateYo"></div>
                                        <!--<span id="rating-2" data-stars="3" style="font-size:35px;"></span>-->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">&nbsp;</label>

                                    <div class="col-sm-9">
                                        <h3><?php echo $RateReview['Review']; ?></h3>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Review</label>

                                    <div class="col-sm-9">
                                        <textarea placeholder="Review" class="col-xs-10 col-sm-5 valid" cols="30" rows="6" id="review" name="review"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-offset-3 col-md-9">
                                    <span id="notification_load" class="notifi-reloader" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                                    <input type="submit" value="Submit" class="btn btn-info btn-lg" onclick="ratereview();" id="rateButton">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                    <?php }else if($alreadyRateReview==2){ ?>
            <div class="details-wrap cf" style="padding: 0;">
                <header class="relevant-header">Rate & Review</header>
                <div class="row" >
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div>
                            <div class="col-sm-5">

<?php 
                                    if(isset($ClinicDetails['patient_logo_url']) && $ClinicDetails['patient_logo_url']!=''){                              
 ?>
                                <a href="javascript:void(0)"><img src="<?php echo S3Path.$ClinicDetails['patient_logo_url'];?>" alt="<?=$ClinicDetails['api_user'];?>" title="<?=$ClinicDetails['api_user'];?>" /></a>
<?php
}else{
    echo $this->html->image(CDN.'img/images_buzzy/clinic.png',array('title'=>$ClinicDetails['api_user'],'alt'=>$ClinicDetails['api_user'],'class'=>'thumb-picture'));
}

?>
                            </div>

                            <div class="col-sm-5">
                                <h4 class="thumb-name"><?php if($ClinicDetails['display_name']==''){ echo $clinicname=$ClinicDetails['api_user'];}else{ echo $clinicname=$ClinicDetails['display_name']; } ?></h4>
                                <?php if(!empty($ClinicLocation)){ ?>
                                <p class="address-detials1"><?php echo $ClinicLocation['ClinicLocation']['address'].', '.$ClinicLocation['ClinicLocation']['city'].', '.$ClinicLocation['ClinicLocation']['state']; ?></p>
                                <?php } ?>
                            </div>

                        </div><!-- .detials-info-->
                    </div><!-- .details-all-info-wrap-->
                </div><!-- .main-details-->
                <input type="hidden" id="identifier" name="identifier" value="<?php echo $identifier; ?>">
            </div>
            <?php
                    }else{ ?>
            <div class="details-wrap cf" style="height: 690px;">
                <header class="relevant-header">Rate & Review</header>
                <div class="form-group" style="text-align: center;font-size: 20px;margin-top: 160px;">

                    You have successfully rated and reviewed <?php echo $ClinicDetails['display_name']; ?>.

                </div>
            </div>
                <?php }}else{ ?>
            <div class="details-wrap cf" style="height: 690px;">
                <header class="relevant-header">Rate & Review</header>
                <div class="form-group" style="text-align: center;font-size: 20px;margin-top: 160px;">

                    Not a valid request.

                </div>
            </div>
                <?php } ?>

        </div><!-- .top-docs-container  -->
    </div>
    <?php if($alreadyRateReview==2){  ?>
    <div class="two-cols-wrap">
        <div class="row cf">
            <section class="left-module" style="width: 100% !important;max-width: 88% !important;">
                <header class="left-module-heading">
                    <div class="modified-border-top"></div>
                    <h4 class="left-module-title-heading" style="margin-bottom: 0px !important;margin-top: 0 !important;">Thank you for telling us about your experience.</h4>
                    <div class="modified-border-bottom clear"></div>
                </header>
                <ul class="left-module-listing ratereview">
                    <li>
                        <div class="data-container">
                            <span class="doc-place-name">Your Rating and Review</span>
                            <p class="listing-description">
                                <span class="rating rating-adjust" id="ratedstar1" style="max-width: 185px !important;">
                                                    <?php 
                                                         $grey1=5-$currentRateReview['RateReview']['rate'];
                                                         for($i2=0;$i2<$currentRateReview['RateReview']['rate'];$i2++){ ?>
                                    <span class="fullstar"></span>
                                                    <?php }
                                                    for($i3=0;$i3<$grey1;$i3++){ ?>
                                    <span class="greystar"></span>
                                                    <?php }  ?>
                                </span></p>
                            <div>&nbsp;</div>
                            <p id="copyTarget"><?php echo $currentRateReview['RateReview']['review']; ?></p>
                        </div>
                    </li>
                </ul>
            </section><!-- .left-module-->

        </div>
    </div><!-- .two-cols-wrap-->
    <div class="two-cols-wrap">
        <div class="row cf">
            <section class="left-module" style="width: 100% !important;max-width: 88% !important;">
                <header class="left-module-heading">
                    <div class="modified-border-top"></div>
                    <h4 class="left-module-title-heading" style="margin-bottom: 0px !important;margin-top: 0 !important;">Wow! What a great review!</h4>
                    <div class="modified-border-bottom clear"></div>
                </header>
                <div class="tab-container reviews-tab r-tabs-panel r-tabs-state-active" id="tab-4" style="display: block;">
                    <h4 class="point-heading">
                        We have some more points we would love to give you...
                    </h4>
                    <ul style="list-style: initial; list-style-position: inside;">
                        <li> <?php echo $RateReview['Facebook Share']; ?></li>
                    <?php $inc=2; if($GoogleUrl!=''){ ?>
                        <li><?php echo $RateReview['Google Share']; ?></li>
                    <?php $inc++; } ?>
                    <?php if($YahooUrl!=''){ ?>
                        <li><?php echo $RateReview['Yahoo Share']; ?></li>
                    <?php $inc++; } ?>
                    <?php if($YelpUrl!=''){ ?>
                        <li><?php echo $RateReview['Yelp Share']; ?></li>
                    <?php $inc++; } ?>
                    <?php if($HealthgradesUrl!=''){ ?>
                        <li><?php echo $RateReview['Healthgrades Share']; ?></li>
                    <?php } ?></ul>
                    <?php if($HealthgradesUrl!='' || $GoogleUrl!='' || $YelpUrl!='' || $YahooUrl!=''){ ?>
                    (A staff member from your practice will verify your post and issue points automatically)
                    <?php } ?>
                    <ul>
                        <li>
                            <div class="review-wrap">
                                <h4 class="reviewer-heading">Step 1: Share On Facebook</h4>
                                <div class="review-txt-n-img-wrap cf">
                                    <div class="review-txt-wrap">
                                        <?php if($facebook==1){ ?>
                                        <div id="bodyarea">
                                            <input type="submit" id="rateButton" class="btn btn-info share_fb btn-lg" value="Share on Facebook" onclick="postToFeed();">
                                        </div>
                                        <?php }else{ ?>

                                        <input type="submit" class="btn btn-info share_fb btn-lg" value="Share on Facebook" onclick="alert('You have already shared review on facebook.');">

                                        <?php } ?>
                                    </div>
                                    <div class="reviewer-picture-wrap">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php if($HealthgradesUrl!='' || $GoogleUrl!='' || $YelpUrl!='' || $YahooUrl!=''){ ?>
                        <li>
                            <div class="review-wrap">
                                <h4 class="reviewer-heading">Step 2: Copy Your Review</h4>
                                <div class="review-txt-n-img-wrap cf">
                                    <div class="review-txt-wrap">
                                        <button id="copyButton" class="btn btn-info btn-lg">Copy Review</button>
                                        <span style="font-size:12px;margin-top: 8x;display: block;color: red;">Note :  iOS/Safari User's will need to manually copy and paste the review</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="review-wrap">
                                <h4 class="reviewer-heading">Step 3: Paste Your Copied Review To The Following Site<?php if($inc>1){ echo "s"; } ?></h4>
                                <div class="review-txt-n-img-wrap cf">
                                    <div class="review-txt-wrap">  
                                        <?php 
                                        $socialcount=0;
                                        if($GoogleUrl!=''){ ?>
                                        <a href="<?php echo $GoogleUrl;?>" target="_blank" class="btn btn-info btn-lg" style="margin-left: 4px;margin-top: 4px;margin-bottom: 4px;">Google + </a>
                                        <?php $socialcount++; $socialtype=1;} ?>
                                        <?php if($YahooUrl!=''){ ?>
                                        <a href="<?php echo $YahooUrl;?>" target="_blank" class="btn btn-info btn-lg" style="margin-left: 4px;margin-top: 4px;margin-bottom: 4px;">Yahoo </a>
                                        <?php $socialcount++; $socialtype=2;} ?>
                                        <?php if($YelpUrl!=''){ ?>
                                        <a href="<?php echo $YelpUrl;?>" target="_blank" class="btn btn-info btn-lg" style="margin-left: 4px;margin-top: 4px;margin-bottom: 4px;">Yelp </a>
                                        <?php $socialcount++; $socialtype=3;} ?>
                                        <?php if($HealthgradesUrl!=''){ ?>
                                        <a href="<?php echo $HealthgradesUrl;?>" target="_blank" class="btn btn-info btn-lg" style="margin-left: 4px;margin-top: 4px;margin-bottom: 4px;">Healthgrades </a>
                                        <?php $socialcount++; $socialtype=4;} ?>
                                        <span style="font-size:12px;margin-bottom: 20px;display: block;color: red;">Tip: Click the 'Write a Review' button once on the review page then (⌘V) or (CTRL-V)</span>
                                    </div>

                                </div>
                        </li>
                        <li>
                            <div class="review-wrap">
                                <h4 class="reviewer-heading">Step 4: Let Your Office Know That You Have Successfully Left Reviews</h4>
                                <div class="review-txt-n-img-wrap cf">
                                    <div class="review-txt-wrap"> 
                                        <?php if($socialcount>1){ 
                                            if($currentRateReview['RateReview']['yahoo_notify']==1 && $currentRateReview['RateReview']['notify_staff']==1 && $currentRateReview['RateReview']['yelp_notify']==1 && $currentRateReview['RateReview']['healthgrades_notify']==1){
                                            ?>
                                        <button type="button" class="btn btn-info btn-lg" onclick="alert('You have already notifed the staff member.');">Notify Clinic</button>
                                            <?php }else{ ?>
                                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Notify Clinic</button>
                                            <?php } }else{ ?>
                                        <a href="javascript:void(0);" class="btn btn-info btn-lg" onclick="notifyStaff(<?php echo $socialtype; ?>);">Notify Clinic</a><span id="google_load" class="notifi-reloader" style="display: none;"><img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif"></span>
                                        <?php } ?>
                                    </div>
                                </div>
                        </li>
                        <?php } ?>

                    </ul>
                    <!-- <div class="view-more-wrap"><a href="#" class="view-more" title="view more">View More</a></div>-->
                </div>
            </section><!-- .left-module-->

        </div>
    </div><!-- .two-cols-wrap-->
    <?php } ?>
</section><!-- .relevant-details top-docs-bg -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Notify Clinic</h4>
            </div>

            <form action="" method="post" id="rating-submit" name="rating-submit" class="rating-submit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                           <?php if($GoogleUrl!=''){ ?>
                            <div class="checkbox">
                                <label><input type="checkbox" id="google" name="google" <?php if($currentRateReview['RateReview']['notify_staff']==1){ echo "checked='checked' disabled='disabled' value='0'";}else{ echo "echo value='1'"; } ?>>Google+</label>
                            </div>
                           <?php } ?>
                           <?php if($YahooUrl!=''){ ?>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" id="yahoo" name="yahoo" <?php if($currentRateReview['RateReview']['yahoo_notify']==1){ echo "checked='checked' disabled='disabled' value='0'";}else{ echo "echo value='1'"; } ?>>Yahoo</label>
                            </div>
                           <?php } ?>
                           <?php if($YelpUrl!=''){ ?>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" id="yelp" name="yelp" <?php if($currentRateReview['RateReview']['yelp_notify']==1){ echo "checked='checked' disabled='disabled' value='0'";}else{ echo "echo value='1'"; } ?>>Yelp</label>
                            </div>
                           <?php } ?>
                           <?php if($HealthgradesUrl!=''){ ?>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" id="healthgrades" name="healthgrades" <?php if($currentRateReview['RateReview']['healthgrades_notify']==1){ echo "checked='checked' disabled='disabled' value='0'";}else{ echo "echo value='1'"; } ?>>Healthgrades</label>
                            </div>

                           <?php } ?>                   
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" value="Notify" id='recommen_btn' class="btn btn-info btn-lg">&nbsp;
                        <span id="reco-status-bar" style="position: absolute; z-index: 5; left: 100px; top: 115px;"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery (JavaScript plugins) -->
<script>
    $("#recommen_btn").click(function () {

        var rateid = '<?php echo $currentRateReview['RateReview']['id']; ?>';
        var google = 0;
        var yahoo = 0;
        var yelp = 0;
        var healthgrades = 0;
        var type = '';
        if ($('#google').is(":checked") && $('#google').val() == 1)
        {
            google = 1;
            type = 'Google+, ';
        }
        if ($('#yahoo').is(":checked") && $('#yahoo').val() == 1)
        {
            yahoo = 1;
            type += 'Yahoo, ';
        }
        if ($('#yelp').is(":checked") && $('#yelp').val() == 1)
        {
            yelp = 1;
            type += 'Yelp, ';
        }
        if ($('#healthgrades').is(":checked") && $('#healthgrades').val() == 1)
        {
            healthgrades = 1;
            type += 'Healthgrades, ';
        }
        type = type.slice(0, -2);
        if (google == 0 && yahoo == 0 && yelp == 0 && healthgrades == 0) {
            alert('Please check any social platform.');
            $('input[type="button"]').removeAttr('disabled');
            return false;
        }
        var r = confirm("Would you like to confirm that you have submitted a " + type + " review?");
        if (r == true)
        {
            $('input[type="button"]').attr('disabled', 'disabled');
            $("#reco-status-bar").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $.ajax({
                type: "POST",
                url: "/buzzydoc/notifyStaff/",
                data: "identifer=" + $("#identifier").val() + "&rate_id=" + rateid + "&google=" + google + "&yahoo=" + yahoo + "&yelp=" + yelp + "&healthgrades=" + healthgrades,
                success: function (msg) {
                    $("#reco-status-bar").html('');
                    $('input[type="button"]').removeAttr('disabled');
                    if (msg == 1) {
                        alert('You have successfully notifed the staff member.');
                        location.reload();
                    } else {
                        alert('You have already notifed the staff member.');
                    }
                }
            });
        } else
        {
            return false;
        }

    });
    function notifyStaff(social_type) {
        var google = 0;
        var yahoo = 0;
        var yelp = 0;
        var healthgrades = 0;
        var type = '';
        if (social_type == 1)
        {
            google = 1;
            type = 'Google+';
        }
        if (social_type == 2)
        {
            yahoo = 1;
            type = 'Yahoo';
        }
        if (social_type == 3)
        {
            yelp = 1;
            type = 'Yelp';
        }
        if (social_type == 4)
        {
            healthgrades = 1;
            type = 'Healthgrades';
        }
        var r = confirm("Would you like to confirm that you have submitted a " + type + " review?");
        if (r == true)
        {
            $("#google_load").css("display", "inline-block");
            var rateid = '<?php echo $currentRateReview['RateReview']['id']; ?>';

            $.ajax({
                type: "POST",
                url: "<?php echo Buzzy_Name.'buzzydoc/notifyStaff' ?>",
                data: "identifer=" + $("#identifier").val() + "&rate_id=" + rateid + "&google=" + google + "&yahoo=" + yahoo + "&yelp=" + yelp + "&healthgrades=" + healthgrades,
                success: function (msg) {
                    if (msg == 1) {
                        alert('You have successfully notifed the staff member.');
                        $("#google_load").css("display", "none");
                        location.reload();
                    } else {
                        $("#google_load").css("display", "none");
                        alert('You have already notifed the staff member.');
                    }
                }
            });

        } else
        {
            return false;
        }
    }
    $(function () {

        $("#rateYo").rateYo({
            rating: 0,
            fullStar: true
        });
    });


    window.fbAsyncInit = function () {
        FB.init({
            appId: '<?php echo Facebook_APP_ID; ?>', status: true, cookie: true, xfbml: true});
    };
    (function (d, debug) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
        ref.parentNode.insertBefore(js, ref);
    }(document, /*debug*/ false));
    function postToFeed(title, desc, url, image) {
        var obj = {method: 'feed', link: '<?php echo $shareUrl;?>', name: "<?php echo $ClinicDetails['display_name'];?>"};
        function callback(response) {
            if(response.error_code==4201){
                
            }else{
                Share(1);
            }
        }
        FB.ui(obj, callback);
    }


//    $("#bodyarea").on('click', '.share_fb', function (event) {
//        event.preventDefault();
//        var that = $(this);
//        var post = that.parents('article.post-area');
//        $.ajaxSetup({cache: true});
//        $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
//            FB.init({
//                appId: '<?php echo Facebook_APP_ID; ?>',
//                version: 'v2.3' // or v2.0, v2.1, v2.0
//            });
//            FB.ui({
//                method: 'share',
//                title: "<?php echo $ClinicDetails['display_name'];?>",
//                mobile_iframe: true,
//                href: '<?php echo $shareUrl;?>',
//            },
//                    function (response) {
//                        if (response && !response.error_code) {
//                            Share(1);
//                        } else {
//                        }
//                    });
//        });
//    });
    function ratereview() {
        $('#rateButton').attr('disabled', 'disabled');
        $("#notification_load").css("display", "inline-block");
        var $rateYo = $("#rateYo").rateYo();
        var ratingval = $rateYo.rateYo("rating");
        if (ratingval == 0) {
            alert('Please select star rating.');
            $('#rateButton').removeAttr('disabled');
            $("#notification_load").css("display", "none");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Buzzy_Name.'buzzydoc/postRateReview' ?>",
            data: "rate=" + ratingval + "&review=" + $("#review").val() + "&identifer=" + $("#identifier").val(),
            success: function (msg) {
                if (msg == 1) {
                    $('#rateButton').attr('disabled', 'disabled');
                    if (ratingval > 2) {
                        alert('You have successfully submitted a review to our practice! If you want to earn <?php echo $TotalMoreEarn; ?> more points, simply share it on a few of our review sites now!');
                        $("#notification_load").css("display", "none");
                        location.reload();
                    } else {
                        alert('You have successfully submitted a review to our practice!');
                        $("#notification_load").css("display", "none");
                        location.reload();
                    }
                } else {
                    $('#rateButton').removeAttr('disabled');
                    $("#notification_load").css("display", "none");
                    alert('Try agin later.');
                }
            }
        });

    }
    function Share(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo Buzzy_Name.'buzzydoc/postRateReview' ?>",
            data: "share=" + id + "&identifer=" + $("#identifier").val(),
            success: function (msg) {
                if (msg == 1) {
                    alert('Successfully shared on facebook.');
                    location.reload();
                } else if (msg == 2) {
                    alert('Goggle Share successfully.');
                } else {
                    alert('Try agin later.');
                }
            }
        });

    }
    document.getElementById("copyButton").addEventListener("click", function () {
        copyToClipboard(document.getElementById("copyTarget"));
    });

    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy", false, null);
            alert('Review Copied successfully.')
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }

</script>