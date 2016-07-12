

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-pencil-square-o"></i> Create Goal
        </h1>
    </div>
     <?php 
     //echo $this->element('messagehelper'); 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     ?>

        <?php echo $this->Form->create("Goal",array('class'=>'form-horizontal'));?>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Goal Name</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" id="goal_name" maxlength="50" class="col-xs-12 col-sm-5" required="required" placeholder="Goal Name" name="goal_name" >
        </div>
    </div>


    <div class="form-group">
        <label  for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Goal Type:</label>
        <div class="col-sm-9">
            <select onchange="selectdefault();" id="goal_type" name="goal_type" class="col-xs-10 col-sm-5">
                <?php if($CanAdd==1){
                    $display='"display:none;"';
                    ?>
                <option value="">Select Type</option>
                <option value="1">Engagement</option>
                <option value="2">Promotion</option>
                <?php }else{ ?>
                <option value="2">Promotion</option>
                <?php } ?>

            </select>


        </div>

    </div>
    <div class="form-group" id="goal_promotion" style="<?php echo $display; ?>">
        <label  for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Promotions:</label>
        <div class="col-sm-9">
            <select  id="promotion_id" name="promotion_id" class="col-xs-10 col-sm-5">
                <option value="">Select Promotion</option>
                                <?php foreach($Promotion as $pro){ ?>
                <option value="<?php echo $pro['Promotion']['id']; ?>"><?php if($pro['Promotion']['display_name']!=''){ echo $pro['Promotion']['display_name']; }else{ echo $pro['Promotion']['description'];} ?></option>

                                <?php } ?>
            </select>


        </div>

    </div>


    <div class="col-md-offset-3 col-md-9">
        <input type="submit" value="Create Goal" class="btn btn-sm btn-primary">
    </div>

</form>
</div>
<script>
    $(document).ready(function() {




        $('#GoalAddForm').validate({
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
                form.submit();
            }

        });
    });

    function selectdefault() {
        var type = $('#goal_type').val();
        if (type == 2) {
            $('#goal_promotion').show();
        } else {
            $('#goal_promotion').hide();
        }
    }

</script>

