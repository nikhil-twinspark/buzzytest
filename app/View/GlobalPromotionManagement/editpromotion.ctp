    <div class="contArea Clearfix">
       <div class="page-header">
             <h1>
                 <i class="menu-icon fa fa-exchange"></i> Global Promotions
             </h1>
         </div>
        <?php 
            //echo $this->element('messagehelper'); 
            echo $this->Session->flash('good');
            echo $this->Session->flash('bad');
            ?>
        <?php echo $this->Form->create("Promotion",array('class'=>'form-horizontal')); ?>
		<div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Display Name</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Display Name" id="display_name" name="data[Promotion][display_name]" value="<?php echo $Promotion['Promotion']['display_name']; ?>" required="">
            </div>
        </div>
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Description</label>
            <div class="col-sm-9 col-xs-12">
                <input type="hidden" name="data[Promotion][id]" value="<?php echo $Promotion['Promotion']['id'];?>">
                <input type="text" id="description" name="data[Promotion][description]" value="<?php echo $Promotion['Promotion']['description'];?>" placeholder="Description" class="col-xs-12 col-sm-5" readonly="">
            </div>
       </div>
        
<!--       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Value</label>
            <div class="col-sm-9 col-xs-12">
                <input type="number"  maxlength="7" class="col-xs-12 col-sm-5" placeholder="Value" id="value" name="data[Promotion][value]" value="<?php echo $Promotion['Promotion']['value']; ?>" required="">
            </div>
        </div>-->
       
<!--       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Operand</label>
            <div class="col-sm-9 col-xs-12">
                <select id="PromotionOperand" name="data[Promotion][operand]" class="col-xs-12 col-sm-5">
                    <option value="+" <?php if($Promotion['Promotion']['operand']=='+'){ echo "selected"; } ?>>+</option>
                    <option value="x" <?php if($Promotion['Promotion']['operand']=='x'){ echo "selected"; } ?>>x</option>
                </select>
            </div>
       </div>-->
        
<!--        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Badge</label>
            <?php if($badge_name){
            ?>
            <div class="col-sm-9 col-xs-12"  style="font-size: 14px; padding-top: 4px;">
            <?php 
            	echo $badge_name;
            	?>
            	</div>
            	<?php 
            } else { ?>
            <div class="col-sm-9 col-xs-12"  style="font-size: 14px; padding-top: 4px;">
                <select name="data[Promotion][badge_id]" id="badge_id">
                <?php 
                	foreach($badges as $badge){
                		?>
                		<option value="<?php echo $badge['id'];?>"><?php echo $badge['name'];?></option>
                		<?php
                	}
                ?>
                </select>
                </div>
                <?php } ?>
        </div>-->
        
        <div class="col-md-offset-3 col-md-9">
                <input type="submit" value="Save Promotion" class="btn btn-sm btn-primary">
         </div>
        
     </div>
     </form>
    
<script>
var badgeId="<?php echo $Promotion['Promotion']['badge_id']; ?>";
$(document).ready(function() { 
		$('#badge_id option[value='+badgeId+']').attr('selected','selected').trigger('change');
			
        $('#PromotionEditpromotionForm').validate({
		rules: {
                        display_name: "required",
			

			value: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        display_name:"Please enter display name",
		
			
			value: {
				required: "Please enter promotion value",
				number: "Please enter a valid promotion value"
				
			}
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
            form.submit();
        }  
            
         });
});

</script>
