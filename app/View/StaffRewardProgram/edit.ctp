<?php

$sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-flask"></i>
            Goal
        </h1>
    </div>
 <?php 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    echo $this->Form->create("Goal",array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
    echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));
 ?>

    <input type="hidden"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="id" name="id" value="<?php echo $Goal['id']; ?>">
                <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Goal Name</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text" id="goal_name" maxlength="50" class="col-xs-12 col-sm-5" required="required" placeholder="Goal Name" name="goal_name" value="<?php echo $Goal['goal_name']; ?>">
        </div>
    </div>


    <div class="form-group">
        <label  for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Goal Type:</label>
        <div class="col-sm-9">
            <select onchange="selectdefault();" id="goal_type" name="goal_type" class="col-xs-10 col-sm-5" disabled="">
                <option value="">Select Type</option>
                <option value="1" <?php if($Goal['goal_type']==1){ echo "selected"; } ?>>Engagement</option>
                <option value="2" <?php if($Goal['goal_type']==2){ echo "selected"; } ?>>Promotion</option>

            </select>


        </div>

    </div>
    <?php if(!empty($Promotion)){ ?>
    <div class="form-group" id="goal_promotion">
        <label  for="form-field-1" class="col-sm-3 control-label no-padding-right"><span class="star">*</span>Promotions:</label>
        <div class="col-sm-9">
            <input type="text" id="promotion_id" maxlength="50" class="col-xs-12 col-sm-5" required="required" placeholder="Promotion" name="promotion_id" value="<?php if($Promotion['display_name']!=''){ echo $Promotion['display_name'];}else{ echo $Promotion['description'];} ?>" readonly="readonly">
        </div>

    </div>
    <?php } ?>      
   <div class="col-md-offset-3 col-md-9">
        <input type="submit" value="Update Goal" class="btn btn-sm btn-primary">
    </div>
</form>



</div>



<script language="Javascript">

  $(document).ready(function() {




        $('#GoalEditForm').validate({
            rules: {
                goal_name: "required"
            },
            // Specify the validation error messages
            messages: {
                goal_name: "Please enter goal name."

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
</script>

