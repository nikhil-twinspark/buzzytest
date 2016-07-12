<?php

$sessionstaff = $this->Session->read('staff'); 
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->script(CDN.'js/fnReloadAjax.js');
    echo $this->Html->script(CDN.'js/fnpaginginfo.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
 


$sessionstaff = $this->Session->read('staff');
?>
<style>
    .head_label {
        float: left;
        width: 184px;
        font-weight: bold;
    }
    body {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

</style>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Add Treatment Plan
        </h1>
    </div>
    <div style="display:none;" id="badMessage" class="message"></div>
<?php 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    <form accept-charset="utf-8" method="post" id="LevelupSettingsForm" class="form-horizontal" action="/LevelupPromotions/addsettings">
        <h4 class="header blue bolder smaller">General</h4>
        <div class="form-group">
            <label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Treatment Plan Name</label>
            <div class="col-sm-9">
                <input type="text" maxlength="50" value="" id="treatment_name" name="treatment_name" placeholder="Treatment Name" class="col-xs-12 col-sm-7">
            </div>
        </div>

        <div class="form-group">
            <label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Select Promotions:</label>
            <div class="col-sm-9">
            <?php 
            	if($promotions){
            		$promotions = array_column($promotions,'LevelupPromotion');
            		foreach($promotions as $key=>$val){
                        //pr($val);
            			?>
                                    <!--<option value="<?php echo $val['id'];?>"><?php // echo $val['display_name'];?></option>-->
                <div class="promo_check">
                    <label ><input type="checkbox" class="col-xs-1 col-sm-1" id="global_promotion_ids" name="global_promotion_ids[]" value="<?php echo $val['id']; ?>"><?php echo $val['display_name']; ?></label >
                </div>
            			<?php
            		}
            	}
            ?>
                <!--</select>-->
            </div>
        </div>


        <h4 class="header blue bolder smaller">Phase Distribution</h4>

        <div class="form-group">
            <div class="col-xs-offset-0 col-sm-9 col-sm-offset-3">
                <div class="head_label">Visits</div>
                <div class="head_label">Points</div>
                <div class="head_label">Badge</div>
            </div>
        </div>
        <input type="hidden" id="phase_cnt" id="phase_cnt" value="3">

        <div id="phase_div">
            <div class="form-group" id="phase_1">
                <label for="form-field-1" class="col-sm-3 control-label no-padding-right" id="phase_name_1"><span class="star">*</span>Phase 1</label>
                <div class="col-sm-9">
                    <input type="text"  value="" name="visits_1" id="visits_1" placeholder="No of visits" class="" maxlength="5" onblur="getvalVisit(1);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="points_1" id="points_1" placeholder="No of points" class="" maxlength="5"  onblur="getvalPoint(1);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="badge_1" id="badge_1" placeholder="Badge Name" class="" maxlength="50" onblur="checkSameBadge(1)">
                    &nbsp;<input type="button" value="Add Phase" class="btn btn-xs" onclick="addphase();">
                </div>
            </div>

            <div class="form-group" id="phase_2">
                <label for="form-field-1" class="col-sm-3 control-label no-padding-right" id="phase_name_2"><span class="star">*</span>Phase 2</label>
                <div class="col-sm-9">
                    <input type="text"  value="" name="visits_2" id="visits_2" placeholder="No of visits" class="" maxlength="5" onblur="getvalVisit(2);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="points_2" id="points_2" placeholder="No of points" class="" maxlength="5"  onblur="getvalPoint(2);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="badge_2" id="badge_2" placeholder="Badge Name" class="" maxlength="50" onblur="checkSameBadge(2)">
                    &nbsp;
                </div>
            </div>

            <div class="form-group" id="phase_3">
                <label for="form-field-1" class="col-sm-3 control-label no-padding-right" id="phase_name_3"><span class="star">*</span>Phase 3</label>
                <div class="col-sm-9">
                    <input type="text"  value="" name="visits_3" id="visits_3" placeholder="No of visits" class="" maxlength="5" onblur="getvalVisit(3);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="points_3" id="points_3" placeholder="No of points" class="" maxlength="5"  onblur="getvalPoint(3);" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

                    <input type="text"  value="" name="badge_3" id="badge_3" placeholder="Badge Name" class="" maxlength="50" onblur="checkSameBadge(3)">
                    &nbsp;<input type="button" value="X" class="btn btn-xs" onclick="deletephase(3);">
                </div>
            </div>
        </div>

        <h4 class="header blue bolder smaller">Treatment Plan Summary</h4>
        <div class="form-group">

            <label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Total Visits</label>

            <div class="col-sm-9">
                <input type="text" readonly="readonly"
                       value="0" name="total_visits" id="total_visits" placeholder="Total Visits" class="col-xs-10 col-sm-5" maxlength="5">
            </div>
        </div>

        <div class="form-group">
            <label for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Total Points</label>

            <div class="col-sm-9">
                <input type="text" readonly="readonly" value="0" name="total_points" id="total_points" placeholder="Total Points" class="col-xs-10 col-sm-5" maxlength="100">
            </div>
        </div>

        <h4 class="header blue bolder smaller">Bonus Settings</h4>
        <div class="form-group">
            <label for="form-field-1" class="col-sm-3 control-label no-padding-right">Bonus Points</label>
            <div class="col-sm-9">
                <input type="text" maxlength="10" value="" id="bonus_points" name="bonus_points" placeholder="Bonus Points" class="col-xs-12 col-sm-7" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">
            </div>
        </div>

        <div class="form-group">
            <label for="form-field-1" class="col-sm-3 control-label no-padding-right">Bonus Message</label>
            <div class="col-sm-9">
                <input type="text" maxlength="100" value="" id="bonus_message" name="bonus_message" placeholder="Bonus Message" class="col-xs-12 col-sm-7">
            </div>
        </div>

        <div class="col-md-offset-3 col-md-9">

            <input type="submit" value="Save" class="btn btn-info">

        </div>  
    </form>

    <script type="text/javascript">
        $('#phase_cnt').val(3);
        var badgeArray = [];
        function checkSameBadge(id) {

            var badgeval = $('#badge_' + id).val().toLowerCase();
            if (badgeArray.indexOf(badgeval) && badgeArray.indexOf(badgeval) !== id && badgeArray.indexOf(badgeval) !== -1) {
                $('#badge_' + id).val('');
                alert('Badge cannot be same');
            } else if (badgeval !== undefined && badgeval !== "") {
                badgeArray[id] = badgeval.toLowerCase();
            }

        }
        var getVisitTotal = function() {
            var total = 0;
            $.each($('input[id^="visits_"]'), function(i, v) {
                if ($(v).val() != '' && $(v).val() != 0 && $(v).val() != undefined) {
                    total = parseInt(total) + parseInt($(v).val());
                    return total;
                }
            });
            $('#total_visits').val(parseInt(total));
        };
        var getPointTotal = function() {
            var total = 0;
            $.each($('input[id^="points_"]'), function(i, v) {
                if ($(v).val() != '' && $(v).val() != 0 && $(v).val() != undefined) {
                    total = parseInt(total) + parseInt($(v).val());
                    return total;
                }
            });
            $('#total_points').val(parseInt(total));
        };
        function getvalVisit(id) {
            var visit = $('#visits_' + id).val();
            var total = parseInt($('#total_visits').val());
            if (visit == '' || visit == 0 || visit == undefined) {
                $('#total_visits').val(total);
                $('#goodMessage').fadeOut(1000);
                $('#badMessage').text('Visits cannot be zero or blank');
                $("#badMessage").fadeIn(1000);
                $("#badMessage").fadeOut(3000);
                return false;
            } else {
                getVisitTotal();
            }
        }
        function getvalPoint(id) {
            var point = $('#points_' + id).val();
            var total = parseInt($('#total_points').val());
            if (point == '' || point == 0 || point == undefined) {

                var val = parseInt(total);
                $('#total_points').val(val);
                $('#goodMessage').fadeOut(1000);
                $('#badMessage').text('Points cannot be zero or blank');
                $("#badMessage").fadeIn(1000);
                $("#badMessage").fadeOut(3000);
                return false;
            } else {
                getPointTotal();

            }
        }
        function addphase() {
            var number_phase = parseInt($('#phase_cnt').val()) + 1;

            var str = '<div class="form-group" id="phase_' + number_phase + '"><label for="form-field-1" class="col-sm-3 control-label no-padding-right" id="phase_name_' + number_phase + '"><span class="star">*</span>Phase ' + number_phase + '</label><div class="col-sm-9"><input type="text"  value="" name="visits_' + number_phase + '" id="visits_' + number_phase + '" placeholder="No of visits" class="" maxlength="5" onblur="getvalVisit(' + number_phase + ');" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, \'\')"> <input type="text"  value="" name="points_' + number_phase + '" id="points_' + number_phase + '" placeholder="No of points" class="" maxlength="5" onblur="getvalPoint(' + number_phase + ');" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, \'\')"> <input type="text"  value="" name="badge_' + number_phase + '" id="badge_' + number_phase + '" placeholder="Badge Name" class="" maxlength="50" onblur="checkSameBadge(' + number_phase + ');">&nbsp;&nbsp;<input type="button" value="X" class="btn btn-xs" onclick="deletephase(' + number_phase + ');"></div></div>';
            $('#phase_cnt').val(number_phase);
            $('#phase_div').append(str);
        }

        function deletephase(div_id) {
            var tlvisit = parseInt($('#total_visits').val());
            var tlpoint = parseInt($('#total_points').val());

            delete badgeArray[div_id];

            if ($('#visits_' + div_id).val() > 0) {
                var getvisit = parseInt($('#visits_' + div_id).val());
            } else {
                var getvisit = 0;
            }
            if ($('#points_' + div_id).val() > 0) {
                var getpoint = parseInt($('#points_' + div_id).val());
            } else {
                var getpoint = 0;
            }
            $('#total_visits').val(tlvisit - getvisit);
            $('#total_points').val(tlpoint - getpoint);
            $('#phase_' + div_id).remove();
            var number_phase = parseInt($('#phase_cnt').val()) - 1;
            $.each($('label[id^=phase_name_]'), function(i, v) {
                var cnt = parseInt(i) + 1;
                $(v).html('<span class="star">*</span>Phase ' + cnt);
            });
            $('#phase_cnt').val(number_phase);

        }
        $(document).ready(function() {
            getVisitTotal();
            getPointTotal();

            var checkEmptyVisits = function() {
                var isEmpty = false;
                $.each($('input[id^="visits_"]'), function(i, v) {
                    if ($(v).val() == 0) {
                        isEmpty = true;
                        return isEmpty;
                    }
                });
                return isEmpty;
            };
            var checkEmptyPoints = function() {
                var isEmpty = false;
                $.each($('input[id^="points_"]'), function(i, v) {
                    if ($(v).val() == 0) {
                        isEmpty = true;
                        return isEmpty;
                    }
                });
                return isEmpty;
            };
            var checkEmptyBadge = function() {
                var isEmpty = false;
                $.each($('input[id^="badge_"]'), function(i, v) {
                    if ($(v).val() == '') {
                        isEmpty = true;
                        return isEmpty;
                    }
                });
                return isEmpty;
            };




            $('#LevelupSettingsForm').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    treatment_name: "required"
                },
// Specify the validation error messages
                messages: {
                    treatment_name: "Please enter treatment name"
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function(e) {
                    $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                    $(e).remove();
                },
                showErrors: function(errorMap, errorList) {
                    if (errorList.length) {
                        var s = errorList.shift();
                        var n = [];
                        n.push(s);
                        this.errorList = n;
                    }
                    this.defaultShowErrors();
                },
                submitHandler: function(form) {
                    if (checkEmptyPoints() == true) {
                        alert('Points cannot be zero');
                    }
                    else if (checkEmptyVisits() == true) {
                        alert('Visits cannot be zero');
                    } else if (checkEmptyBadge() == true) {
                        alert('Badge cannot be blank');
                    }
                    else if ($('#bonus_points').val() != '' && $('#bonus_message').val() == '') {
                        alert('Please enter bonus message');
                    }
                    else if ($('#bonus_points').val() == '' && $('#bonus_message').val() != '') {
                        alert('Please enter bonus points');
                    }
//                    else if (checkSameBadge1() == true) {
//                        alert('Badge cannot be same');
//                    }
                    else if ($('#total_points').val() == 0) {
                        alert('Points should be greater than zero');
                    } else if ($('#total_visits').val() == 0) {
                        alert('Visits should be greater than zero');
                    } else {
                        form.submit();
                    }
                    return false;
                }

            });

            $("#global_promotion_ids").rules("add", {
                required: true,
                minlength: 1,
                messages: {
                    required: "Please select at least 1 promotion",
                    minlength: jQuery.validator.format("Please select at least 1 promotion")
                }
            });
        });
    </script>
