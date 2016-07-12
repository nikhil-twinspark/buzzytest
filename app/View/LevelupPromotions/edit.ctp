
<?php $sessionstaff = $this->Session->read('staff');	?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Edit Promotion
        </h1>
    </div>
    	<?php 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>


      <?php echo $this->Form->create("LevelupPromotion",array('class'=>'form-horizontal'));
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));

 ?>
    <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>BuzzyDoc Promotion Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Promotion Display Name" readonly="readonly" id="promotion_display_name" name="promotion_display_name" value="<?php echo $promotion['LevelupPromotion']['promotion_display_name']; ?>">
        </div>
    </div>
    <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Promotion Display Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Promotion Display Name" id="display_name" name="display_name" value="<?php echo $promotion['LevelupPromotion']['display_name']; ?>" required="required">
        </div>
    </div>

    <div class="form-group">
        <input type="hidden"  id="id" name="id" value="<?php echo $promotion['LevelupPromotion']['id']; ?>">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Description</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="<?php echo $promotion['LevelupPromotion']['description']; ?>"  required="required">
        </div>
    </div>
    <div class="col-md-offset-3 col-md-9">

        <input type="submit" value="Save Promotion" class="btn btn-info">

    </div>  
</form>



</div>


<script language="Javascript">


    $(document).ready(function() {
        $('#LevelupPromotionEditForm').validate({
            rules: {
                display_name: "required",
                description: "required"
            },
            // Specify the validation error messages
            messages: {
                display_name: "Please enter promotion display name",
                description: "Please enter promotion description"
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

    function isalloted() {

        var isallot = $('input:radio[name=alloted]').filter(":checked").val();

        if (isallot == 1) {
            $("#value").attr("disabled", "disabled");

        } else {
            $("#value").removeAttr("disabled");
        }
    }
</script>




