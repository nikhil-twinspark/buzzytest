<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-pencil-square-o"></i> Set Goal
        </h1>
    </div>
     <?php 
     echo $this->Html->css(CDN.'css/assets/css/chosen.css');
     echo $this->Html->script(CDN.'js/assets/js/chosen.jquery.min.js');
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     ?>

        <?php echo $this->Form->create("GoalSetting",array('class'=>'form-horizontal'));?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Set for Practice</label>
        <div class="col-sm-9 col-xs-12">
            <input type="checkbox" value="1" name="set_for_practice" id="set_for_practice" class="col-xs-1 col-sm-1" onclick="setforclinic();">
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Set for all Staff User</label>
        <div class="col-sm-9 col-xs-12">
            <input type="checkbox" value="1" name="set_for_all" id="set_for_all" class="col-xs-1 col-sm-1" onclick="setforall();">
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Staff User</label>
        <div class="col-sm-3 col-xs-12">
            <select class="form-control" id="staff_user" name="staff_user" data-placeholder="Choose a Staff user" onchange="checkstaffuser();">
                <option value="">Choose a Staff user</option>
<?php foreach($Staff as $stf){ ?>
                <option value="<?php echo $stf['Staff']['id']; ?>"><?php echo $stf['Staff']['staff_id']; ?></option>
<?php } ?>
            </select>
        </div>
    </div>
    <div id="setting_div" style="display:none;">
        <h4 class="header blue bolder smaller">Goal Setting</h4>
        <input type="hidden" id="goal_cnt" id="goal_cnt" value="1">
        <div id="goal_div">
            <div class="form-group" id="goal_1">
                <label for="form-field-1" class="col-sm-3 control-label no-padding-right">&nbsp;</label>
                <div class="col-sm-3">
                    <!--chosen-select-->
                    <select class="form-control" id="goal_name_1" name="goal_name_1" data-placeholder="Choose a Goal" onchange="checkSameGoal(1);">
                        <option value="">Choose a Goal</option>
            <?php foreach($Goal as $gl){ ?>
                        <option value="<?php echo $gl['Goal']['id']; ?>"><?php echo $gl['Goal']['goal_name']; ?></option>
            <?php } ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <input type="text" id="target_value_1" class="form-control" maxlength="50" placeholder="Target Value" name="target_value_1" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">
                </div>
                <div class="col-sm-3">
                    <input type="button" value="Add More Setting" class="btn btn-default btn-xs" onclick="addsetting();">
                </div>
            </div>
        </div>


        <div class="col-md-offset-3 col-md-9">
            <input type="submit" value="Set Goal" class="btn btn-sm btn-primary">
        </div>
    </div>
</form>
</div>
<script>
    $('#goal_cnt').val(1);

    var goalArray = [];
    function checkSameGoal(id) {

        var goalval = $('#goal_name_' + id).val();
        if ($.trim(goalval) != '') {
            if (goalArray.indexOf(goalval) && goalArray.indexOf(goalval) !== id && goalArray.indexOf(goalval) !== -1) {
                $('#goal_name_' + id).val('');
                alert('Goal Name cannot be same');
            } else if (goalval !== undefined && goalval !== "") {
                goalArray[id] = goalval.toLowerCase();
            }
            if ($('#set_for_all').is(":checked") == true) {
                var for_all = 1;
            } else {
                var for_all = 0;
            }
            var staff_user = $('#staff_user').val()
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "<?php echo Staff_Name.'StaffRewardProgram/checkgoalsetting' ?>",
                data: {goal_id: $('#goal_name_' + id).val(), staff_user: staff_user, for_all: for_all},
                success: function(msg) {
                    if (msg == 2) {
                        $('#goal_name_' + id).val('');
                        alert('Goal is already created for this staff user.');
                    }
                    else if (msg == 3) {
                        $('#goal_name_' + id).val('');
                        alert('Goal is already created for this practice.');
                    } else if (msg == 4) {
                        $('#goal_name_' + id).val('');
                        alert('Goal Name doesn\'t exist');
                    }
                }
            });
        }
    }
    function checkstaffuser() {
        if ($('#set_for_all').is(":checked") != true && $('#set_for_practice').is(":checked") != true && $('#staff_user').val() == '') {
            $('#setting_div').hide();
        } else {
            $('#setting_div').show();
        }

    }
    $(document).ready(function() {
        $('.chosen-select').chosen({allow_single_deselect: true});
        //resize the chosen on window resize

        $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
        var checkEmptyGoalName = function() {
            var isEmpty = false;
            $.each($('select[id^="goal_name_"]'), function(i, v) {
                if ($(v).val() == '') {
                    isEmpty = true;
                    return isEmpty;
                }
            });
            return isEmpty;
        };
        var checkEmptyTargetValue = function() {
            var isEmpty = false;
            $.each($('input[id^="target_value_"]'), function(i, v) {
                if ($(v).val() == 0) {
                    isEmpty = true;
                    return isEmpty;
                }
            });
            return isEmpty;
        };
        $('#GoalSettingAddsettingForm').validate({
            rules: {
                goal_name: "required",
                goal_type: "required",
                promotion_id: "required"


            },
            // Specify the validation error messages
            messages: {
                goal_name: "Please enter goal name.",
                goal_type: "Please select goal type.",
                promotion_id: "Please select promotion."

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

                var str = $("#GoalSettingAddsettingForm").serialize();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "<?php echo Staff_Name.'StaffRewardProgram/checkforallstaff' ?>",
                    data: str,
                    success: function(msg) {
                        var r = true;
                        if (msg > 0) {
                            var r = confirm("Goal already set for some of your staff user. Do you want to proceed to update target value?");
                        }
                        if (r == true)
                        {
                            if (checkEmptyGoalName() == true) {
                                alert('Please select Goal Name.');
                            } else if (checkEmptyTargetValue() == true) {
                                alert('Target Value should be greater than zero');
                            } else {
                                form.submit();
                            }
                        } else {
                            return false;
                        }
                    }
                });

            }

        });
    });


    function addsetting() {
        var number_goal = parseInt($('#goal_cnt').val()) + 1;
        var goal_drop = '<div class="col-sm-3"><select class="form-control" id="goal_name_' + number_goal + '" name="goal_name_' + number_goal + '" data-placeholder="Choose a Goal" onchange="checkSameGoal(' + number_goal + ');"><option value="">Choose a Goal</option>';
<?php foreach($Goal as $gl1){ ?>
        goal_drop += '<option value="<?php echo $gl1['Goal']['id']; ?>"><?php echo $gl1['Goal']['goal_name']; ?></option>';
<?php } ?>
        goal_drop += '</select></div>';
        var str = '<div class="form-group" id="goal_' + number_goal + '"><label for="form-field-1" class="col-sm-3 control-label no-padding-right">&nbsp;</label>' + goal_drop + '<div class="col-sm-3"><input type="text" id="target_value_' + number_goal + '" class="form-control" maxlength="50" placeholder="Target Value" name="target_value_' + number_goal + '" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, \'\')"></div><div class="col-sm-3"><input type="button" value="X" class="btn btn-xs" onclick="deletesetting(' + number_goal + ');"></div></div>';
        $('#goal_cnt').val(number_goal);
        $('#goal_div').append(str);
        $(function() {
            $("#goal_name_" + number_goal).autocomplete({
                source: goallist
            });
        });
    }
    function deletesetting(div_id) {
        delete goalArray[div_id];
        $('#goal_' + div_id).remove();
        var number_goal = parseInt($('#goal_cnt').val()) - 1;
        $('#goal_cnt').val(number_goal);

    }
    function setforall() {
        if ($('#set_for_all').is(":checked") != true && $('#set_for_practice').is(":checked") != true && $('#staff_user').val() == '') {
            $('#setting_div').hide();
        } else {
            $('#setting_div').show();
        }
        if ($('#set_for_all').is(":checked") == true) {
            $('#set_for_practice').attr('checked', false);
            $('#staff_user').val('');
            $("#staff_user").attr("disabled", "disabled");
        } else {
            $("#staff_user").removeAttr("disabled");
        }
    }
    function setforclinic() {
        if ($('#set_for_all').is(":checked") != true && $('#set_for_practice').is(":checked") != true && $('#staff_user').val() == '') {
            $('#setting_div').hide();
        } else {
            $('#setting_div').show();
        }
        if ($('#set_for_practice').is(":checked") == true) {
            $('#set_for_all').attr('checked', false);
            $('#staff_user').val('');
            $("#staff_user").attr("disabled", "disabled");
        } else {
            $("#staff_user").removeAttr("disabled");
        }
    }


</script>

