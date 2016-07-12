<?php

$sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Accelerated Rewards
        </h1>
    </div>

    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>

      <?php echo $this->Form->create("AcceleratedReward",array('class'=>'form-horizontal'));
       ?>
    <input type="hidden" id="clinic_id" name="clinic_id" value="<?=$sessionstaff['clinic_id']?>">
    <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Name</label>

        <div class="col-sm-9">

            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="tier_name" name="tier_name" value="">
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Multiplier Value</label>

        <div class="col-sm-9">

            <input type="text"  maxlength="255" class="col-xs-10 col-sm-5" placeholder="Multiplier Value" id="multiplier_value" name="multiplier_value" value="">
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" >Coupon</label>

        <div class="col-sm-9">

            <select id="coupon_id" class="col-xs-10 col-sm-5" name="coupon_id">
                <option value="">Select Coupon</option>
<?php foreach($ProductService as $ps){ ?>
                <option value="<?php echo $ps['ProductService']['id']; ?>"><?php echo $ps['ProductService']['title']." - ".$ps['ProductService']['points'].""; ?></option>
<?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Point</label>

        <div class="col-sm-9">

            <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Point" id="points" name="points" value="">
        </div>
    </div>


    <div class="col-md-offset-3 col-md-9">

        <input type="submit" value="Save Accelerated Reward" class="btn btn-info">

    </div>  

</form>



</div>



<script language="Javascript">


    $(document).ready(function() {




        $('#AcceleratedRewardAddForm').validate({
            rules: {
                name: "required",
                multiplier_value: "required",
                points: {
                    required: true,
                    number: true
                }
            },
            // Specify the validation error messages
            messages: {
                name: "Please enter name",
                multiplier_value: "Please enter multiplier value",
                points: {
                    required: "Please enter point",
                    number: "Please enter a valid point"

                }
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
                var mlval = $('#multiplier_value').val();
                if (mlval < 1){
                    alert('Multiplier value should be greater then zero.');
                    return false;
                }
                var pnt = $('#points').val();
                if (pnt < 1){
                    alert('Point should be greater then zero.');
                    return false;
                }
                
                
                form.submit();
            }


        });
    });
</script>

