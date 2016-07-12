
<?php $sessionstaff = $this->Session->read('staff');	?>
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
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));

 ?>
        <div class="form-group">
      <input type="hidden"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="id" name="id" value="<?php echo $AcceleratedReward['TierSetting']['id']; ?>">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="tier_name" name="tier_name" value="<?php echo $AcceleratedReward['TierSetting']['tier_name']; ?>">
        </div>
        </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Multiplier Value</label>

<div class="col-sm-9">

    <input type="text"  maxlength="255" class="col-xs-10 col-sm-5" placeholder="Multiplier Value" id="multiplier_value" name="multiplier_value" value="<?php echo $AcceleratedReward['TierSetting']['multiplier_value']; ?>" disabled="disabled">
</div>
 </div>
       
  
       <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" >Coupon</label>

<div class="col-sm-9">

    <select id="coupon_id" class="col-xs-10 col-sm-5" name="coupon_id">
<option value="">Select Coupon</option>
<?php foreach($ProductService as $ps){ ?>
<option value="<?php echo $ps['ProductService']['id']; ?>" <?php if($AcceleratedReward['TierSetting']['coupon_id']==$ps['ProductService']['id']){ echo "selected"; }; ?>><?php echo $ps['ProductService']['title']." - ".$ps['ProductService']['points'].""; ?></option>
<?php } ?>
</select>
</div>
 </div>

      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Point</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Point" id="points" name="points" value="<?php echo $AcceleratedReward['TierSetting']['points']; ?>"  disabled="disabled">
</div>
 </div>

<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Accelerated Reward" class="btn btn-info">
       
									</div>  
      </form>
 
    
     
   </div>
 

<script language="Javascript">


$(document).ready(function() { 
			
	
	 
			
        $('#AcceleratedRewardEditForm').validate({
		rules: {
                        tier_name: "required",
			points: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        tier_name: "Please enter name",			
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
            var pnt=$('#points').val();
            if(pnt>0){
            form.submit();
        }else{
            alert('Point should be greater then zero.')
        }
        }
          
            
         });
});
</script>




