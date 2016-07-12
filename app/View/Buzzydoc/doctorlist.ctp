<?php
$sessionbuzzy = $this->Session->read('userdetail');

//echo "<pre>";print_r($Doctorslist);
?>
<section class="top-docs-bg">
    <div class="row">
        <div class="top-docs-container">
            <div class="top-docs-details-wrap">
                <header class="top-docs-heading">
                    TOP DOCS
                    <a href="/practice" class="header-link right">Switch to search Top Practices</a>
                </header>
                <div class="form-wrap cf">
                    
                    <div class="form-left-section">
                        <h4 class="form-heading">Filter Search By:</h4>
                        <div class="select-box-left">
                            <select name="type" id="type" class="select-doctor-type">
                                <option value="">Select Doctor Type</option>
                                <option value="">Search all</option>
                                <option value="Pediatric Dentistry">Pediatric Dentistry</option>
                                <option value="Orthodontics" >Orthodontics</option>
                                <option value="Optometry" >Optometry</option>
                                
                                <option value="OB/GYN" >OB/GYN</option>
                                <option value="Dermatology" >Dermatology</option>
                                <option value="Chiropractics" >Chiropractics</option>
                                <option value="Plastic Surgery" >Plastic Surgery</option>
                                <option value="Family Medicine" >Family Medicine</option>
                                
                            </select>
                        </div>
                        
                        
                        <div class="radio-btn-wrap">
                            <div class="radio-btn-inner">
                                <span class="radio-box-1">
                                    <input type="radio" id="check" name="check" value="rating"  onclick="search('srch');">
                                Rating
                                </span>
                             
                            </div>
                        </div>
                        <p style="position: relative;"><span id="status-bar-srch" style="position: absolute; bottom: -10; z-index: 5;margin-left: -150px"></span></p>
                    </div>

                    <div class="form-right-section">
                        <input type="text" class="change-zipcode" id="zip" name="zip" placeholder="Zipcode Search" onkeypress="search(event)">
                    </div><!--  form-right-section  -->

                </div>

                <input type="hidden" name="limit" id="limit" value="<?php echo $limit; ?>">
                <input type="hidden" name="offset" id="offset" value="<?php echo $offset; ?>">
                <div class="filter-search-results-wrap" id="doclist">



                    <?php if(count($Doctorslist)>0){ foreach ($Doctorslist as $Doctors) { ?>
                        <div class="filter-search-results cf">
                            <div class="result-img-wrap">
                                <?php
                                $docprofilePath1 = AWS_server . AWS_BUCKET . '/img/docprofile/' . $Doctors->ClinicName . '/' . $Doctors->Doctor->id;
                                $ch = curl_init($docprofilePath1);

                                curl_setopt($ch, CURLOPT_NOBODY, true);
                                curl_exec($ch);
                                $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                if ($retcode == 200) {
                                    ?>
                                    <img src="<?= $docprofilePath1 ?>" class='thumb-picture' width="62px" height="72px">

                                <?php
                                } else {
                                    if ($Doctors->Doctor->gender == 'Male') {
                                        echo $this->html->image(CDN.'img/images_buzzy/doctor-male.png', array('title' => 'doctor', 'width' => '62px', 'height' => '72px', 'alt' => 'doctor picture', 'class' => 'thumb-picture'));
                                    } else {
                                        echo $this->html->image(CDN.'img/images_buzzy/doctor-female.png', array('title' => 'doctor', 'width' => '62px', 'height' => '72px', 'alt' => 'doctor picture', 'class' => 'thumb-picture'));
                                    }
                                }

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
                                    <li><?php echo ucfirst(strtolower($Doctors->Doctor->gender)); ?></li>
                                    <li>Age <?php echo $diff->y; ?></li>
                                </ul>
                                <ul class="result-main-btn">
                                    <li>
                                        <a>
                                            <div class="rating tab-rating">
                                                <?php
                                                if (isset($Doctors->Rate) && $Doctors->Rate > 0) {
                                                    $rate = $Doctors->Rate;
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
                                    <li><a>Save <span class="sub-txt">(<?php echo $Doctors->Save; ?>)</span></a></li>

                                </ul>
                            </div>
                        </div>

<?php }}else{ ?>
<div class="filter-search-results cf">
                        <div class="result-img-wrap">
                     
                      No Doctor Found!
                      
                    </div>
                  </div>
<?php } ?>


                </div><!--  filter-search-results-wrap  -->
<?php if (count($Doctorslist) > 9) { ?>
                    <div id="moreact" class="more-activity-expand-btn-wrap">
                        <!--<input type="button" value="More Doctor" id='more_practice' class="more-activity-expand-btn">-->
                        <a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Doctors</a>
                        <span id="status-bar"></span>
                    </div>
<?php } ?>
            </div><!-- .top-docs-details-wrap  -->
        </div><!-- .top-docs-container  -->
    </div>


</section><!-- .relevant-details top-docs-bg -->



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

    $(document).on('click', "#more_practice", function() {
        $("#status-bar").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
        var limit = $('#limit').val();
        var offset = $('#offset').val();
        var moreoff = parseInt(offset) + 10;
        var type = $('#type').val();
        var check = $('input[name=check]:checked').val();
        var zip = $('#zip').val();
        $.ajax({
            type: "POST",
            data: "type=" + type + "&check=" + check + "&zip=" + zip + "&limit=" + limit + "&offset=" + moreoff,
            url: "/buzzydoc/searchdoc/",
            success: function(result) {
                $("#status-bar").html('');
                obj = JSON.parse(result);
                $('#offset').val(moreoff);
                if (obj.cnt < 10) {
                    $('#moreact').html('');
                } else {
                    $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Doctors</a><span id="status-bar"></span>');
                }
                $("#doclist").append(obj.data);
            }});


    });
</script>
<script type="text/javascript">
    $("#type").selectmenu({
        change: function() {
            $("#status-bar-srch").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            var limit = $('#limit').val();
            var offset = $('#offset').val();
            var type = $('#type').val();
            var check = $('input[name=check]:checked').val();
            var zip = $('#zip').val();

            $.ajax({
                type: "POST",
                data: "type=" + type + "&check=" + check + "&zip=" + zip + "&limit=" + limit + "&offset=" + offset,
                url: "/buzzydoc/searchdoc/",
                success: function(result) {
                    $("#status-bar-srch").html('');
                    obj = JSON.parse(result);
                    if (obj.cnt < 10) {
                        $('#moreact').html('');
                    } else {
                        $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Doctors</a><span id="status-bar"></span>');
                    }
                    $('#doclist').html(obj.data);
                }
            });
        }
    });
    $("#choose-insurance").selectmenu();
    function search(e) {
        var key = e.keyCode || e.which;
        if ((key == 13) || e == 'srch') {
            $("#status-bar-srch").html('<img alt="" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            var limit = $('#limit').val();
            var offset = $('#offset').val();
            var type = $('#type').val();
            var check = $('input[name=check]:checked').val();
            var zip = $('#zip').val();

            $.ajax({
                type: "POST",
                data: "type=" + type + "&check=" + check + "&zip=" + zip + "&limit=" + limit + "&offset=" + offset,
                url: "/buzzydoc/searchdoc/",
                success: function(result) {
                    $("#status-bar-srch").html('');
                    obj = JSON.parse(result);
                    if (obj.cnt < 10) {
                        $('#moreact').html('');
                    } else {
                        $('#moreact').html('<a href="javascript:void(0)" id="more_practice" class="more-activity-expand-btn" >More Doctors</a><span id="status-bar"></span>');
                    }
                    $('#doclist').html(obj.data);
                }
            });
        }
    }
</script>
