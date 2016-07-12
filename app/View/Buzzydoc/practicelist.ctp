<?php
$sessionbuzzy = $this->Session->read('userdetail');

//echo "<pre>";print_r($Doctorslist);
?>
<section class="top-docs-bg">
    <div class="row">
        <div class="top-docs-container">
            <div class="top-docs-details-wrap">
                <header class="top-docs-heading cf">
                    TOP Practices
                    <a href="/doctor" class="header-link right">Switch to search Top Docs</a>
                </header>
                
                <div class="form-wrap cf">
                    <div class="form-left-section">
                        <h4 class="form-heading">Filter Search By:</h4>
                        <div class="select-box-left">
                            <select name="type" id="type" class="select-doctor-type">
                                <option value="">Select Practice Type</option>
                                <option value="">Search all</option>
                                <?php foreach ($industry as $ind) { ?>
                                    <option value="<?= $ind['IndustryType']['id'] ?>"><?= $ind['IndustryType']['name'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="radio-btn-wrap">
                            <div class="radio-btn-inner">
                                <span class="radio-box-1">
                                    <input type="radio" id="check_1" name="check" value="rating" onclick="search('srch');">
                                    Rating
                                </span>
                                
                            </div>
                        </div>
                        <div class="select-box-right">
                            <select name="insurance" id="insurance" class="select-doctor-type">
                                <option value="">Choose an Insurance</option>
                                <option value="">Search all</option>
                                <?php foreach ($insurence as $ins) { ?>
                                    <option value="<?= $ins['CharacteristicInsurance']['id'] ?>"><?= $ins['CharacteristicInsurance']['name'] ?></option>
                                <?php } ?>
                              
                            </select>
                        </div>
                        <p style="position: relative;"><span id="status-bar-srch" style="position: absolute; bottom: -10; z-index: 5; margin-left: -80px;"></span></p>
                    </div><!--  form-left-section  -->

                    <div class="form-right-section">
                        <input type="text" class="change-zipcode" id="zip" name="zip" placeholder="Zipcode Search" onkeypress="search(event);">
                    </div><!--  form-right-section  -->
                </div>
                <input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>">
                <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>">
                <div class="filter-search-results-wrap" id="practicelist">

                    <?php
                    //echo "<pre>";print_r($cliniclists);
                    if (count($cliniclists) > 0) {
                        foreach ($cliniclists as $Clinics) {
                            ?>
                            <div class="filter-search-results cf">
                                <div class="result-img-wrap">
                                    <?php
                                    $ch = curl_init(S3Path.$Clinics->Clinic->buzzydoc_logo_url);

                                    curl_setopt($ch, CURLOPT_NOBODY, true);
                                    curl_exec($ch);
                                    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    if(isset($Clinics->Clinic->buzzydoc_logo_url) && $Clinics->Clinic->buzzydoc_logo_url!=''){
                                        ?>
                                    <img src="<?php echo S3Path.$Clinics->Clinic->buzzydoc_logo_url; ?>"  alt="<?= $Clinics->Clinic->api_user; ?>" title="<?= $Clinics->Clinic->api_user; ?>" class="thumb-picture"/>
                                        <?php
                                    } else {
                                        echo $this->html->image(CDN.'img/images_buzzy/clinic.png', array('title' => $Clinics->Clinic->api_user, 'alt' => $Clinics->Clinic->api_user, 'class' => 'thumb-picture'));
                                    }
                                    ?>

                                </div>
                                <div class="result-detail">
                                    <div class="result-detail-top-row">
                                        <h4 class="result-heading"><a href="<?php echo '/practice/' . str_replace(' ','',$Clinics->Clinic->api_user); ?>" title="View Full Profile"><?php if ($Clinics->Clinic->display_name == '') {
                                        echo $Clinics->Clinic->api_user;
                                    } else {
                                        echo $Clinics->Clinic->display_name;
                                        } ?></a></h4>
                                       
                                        <div class="result-view-profile-btn-wrap">
                                            <a href="<?php echo '/practice/' . str_replace(' ','',$Clinics->Clinic->api_user); ?>" class="result-view-profile-btn" title="View Full Profile">View Full Profile</a>
                                        </div>
                                    </div>
                                    <h5 class="address-heading"><?php if (isset($Clinics->PrimeOffices)) {
            echo $Clinics->PrimeOffices->ClinicLocation->address;
        } ?></h5>
                                    <p class="address-detials">
                                                    <?php if (isset($Clinics->PrimeOffices)) {
                                                        echo $Clinics->PrimeOffices->ClinicLocation->city . ' ,' . $Clinics->PrimeOffices->ClinicLocation->state . ' ,' . $Clinics->PrimeOffices->ClinicLocation->pincode;
                                                    } ?>
                                    </p>
                                    <ul class="result-doctor-list">
                                                    <?php $d=count($Clinics->Doctors);
                                                    $d1=1;foreach ($Clinics->Doctors as $doc) { ?>
                                            <li><?php echo $doc->Doctor->first_name; ?> <?php echo $doc->Doctor->last_name; ?><?php if($d!=$d1){ echo ","; }?></li>
                                                    <?php $d1++;} ?>
                                    </ul>

                                    <ul class="result-main-btn">
                                        <li>
                                            <a>
                                                <div class="rating tab-rating">
                                                    <?php
                                                    if (isset($Clinics->Rate) && $Clinics->Rate > 0) {
                                                        $rate = $Clinics->Rate;
                                                    } else {
                                                        $rate = 0;
                                                    }
                                                    $grey = 5 - $rate;
                                                    for ($i = 0; $i < $rate; $i++) {
                                                        ?>
                                                        <span class="fullstar"></span>
        <?php }
        for ($i1 = 0; $i1 < $grey; $i1++) {
            ?>
                                                        <span class="greystar"></span>
        <?php }
        ?>
                                                </div>
                                                <span class="sub-txt">(<?php echo number_format((float) $rate, 1, '.', ''); ?>)</span>
                                            </a>
                                        </li>
                                        <li><a>Likes <span class="sub-txt">(<?php echo $Clinics->Likes; ?>)</span></a></li>
                                        <li><a>Reviews <span class="sub-txt">(<?php echo $Clinics->TotalReview; ?>)</span></a></li>
                                        <li><a>Check-ins <span class="sub-txt">(<?php echo $Clinics->TotalCheckin; ?>)</span></a></li>

                                    </ul>
                                </div>
                            </div>

    <?php }
} ?>



                </div><!--  filter-search-results-wrap  -->
<?php if (count($cliniclists) > 9) { ?>

                    <div id="moreact" class="more-activity-expand-btn-wrap" >
                        <!--<input type="button" value="More Practice" id='more_practice' class="more-activity-expand-btn" >-->
                        <a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Practices</a>
                        <span id="status-bar"></span>
                    </div>
<?php } ?>
            </div><!-- .top-docs-details-wrap  -->
        </div><!-- .top-docs-container  -->
    </div>


</section><!-- .relevant-details top-docs-bg -->



<!-- jQuery (JavaScript plugins) -->


<script type="text/javascript">
    $(document).ready(function() {
        // Tab text change function for first tab
        $('.alternate-tab').on('click', function() {
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
    $('#type').val('');

    $("input:radio").attr("checked", false);
    $('#zip').val('');
    $('#insurance').val('');
    $('#limit').val(10);
    $('#offset').val(0);

    $(document).on('click', "#more_practice", function() {
        $("#status-bar").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
        var limit = $('#limit').val();
        var offset = $('#offset').val();
        var moreoff = parseInt(offset) + 10;
        var type = $('#type').val();
        var check = $('input[name=check]:checked').val();
        var zip = $('#zip').val();
        var insurance = $('#insurance').val();
        $.ajax({
            type: "POST",
            data: "type=" + type + "&check=" + check + "&zip=" + zip + "&insurance=" + insurance + "&limit=" + limit + "&offset=" + moreoff,
            url: "/buzzydoc/searchpractice/",
            success: function(result) {
                $("#status-bar").html('');
                obj = JSON.parse(result);
                $('#offset').val(moreoff);
                if (obj.cnt < 10) {
                    $('#moreact').html('');
                } else {
                    $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Practices</a><span id="status-bar"></span>');
                }
                $("#practicelist").append(obj.data);
            }});


    });


    $("#type").selectmenu({
        change: function() {
            $("#status-bar-srch").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            var limit = $('#limit').val();
            var offset = $('#offset').val();
            var type = $('#type').val();
            var check = $('input[name=check]:checked').val();
            var zip = $('#zip').val();
            var insurance = $('#insurance').val();
            $.ajax({
                type: "POST",
                data: "type=" + type + "&check=" + check + "&zip=" + zip + "&insurance=" + insurance + "&limit=" + limit + "&offset=" + offset,
                url: "/buzzydoc/searchpractice/",
                success: function(result) {
                    $("#status-bar-srch").html('');
                    obj = JSON.parse(result);
                    if (obj.cnt < 10) {
                        $('#moreact').html('');
                    } else {
                        $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Practices</a><span id="status-bar"></span>');
                    }
                    $('#practicelist').html(obj.data);
                }
            });
        }
    });
    $("#insurance").selectmenu({
      position: { my: "right top", at: "right bottom", collision: "none" },
        change: function() {
            $("#status-bar-srch").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            var limit = $('#limit').val();
            var offset = $('#offset').val();
            var type = $('#type').val();
            var check = $('input[name=check]:checked').val();
            var zip = $('#zip').val();
            var insurance = $('#insurance').val();
            $.ajax({
                type: "POST",
                data: "type=" + type + "&check=" + check + "&zip=" + zip + "&insurance=" + insurance + "&limit=" + limit + "&offset=" + offset,
                url: "/buzzydoc/searchpractice/",
                success: function(result) {
                    $("#status-bar-srch").html('');
                    obj = JSON.parse(result);
                    if (obj.cnt < 10) {
                        $('#moreact').html('');
                    } else {
                        $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Practices</a><span id="status-bar"></span>');
                    }
                    $('#practicelist').html(obj.data);
                }
            });
        }
    });

    function search(e) {
        var key = e.keyCode || e.which;
        if ((key == 13) || e == 'srch') {
            $("#status-bar-srch").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            var limit = $('#limit').val();
            var offset = $('#offset').val();
            var type = $('#type').val();
            var check = $('input[name=check]:checked').val();
            var zip = $('#zip').val();
            var insurance = $('#insurance').val();
            $.ajax({
                type: "POST",
                data: "type=" + type + "&check=" + check + "&zip=" + zip + "&insurance=" + insurance + "&limit=" + limit + "&offset=" + offset,
                url: "/buzzydoc/searchpractice/",
                success: function(result) {
                    $("#status-bar-srch").html('');
                    obj = JSON.parse(result);
                    if (obj.cnt < 10) {
                        $('#moreact').html('');
                    } else {
                        $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Practices</a><span id="status-bar"></span>');
                    }
                    $('#practicelist').html(obj.data);
                }
            });
        }
    }

</script>
