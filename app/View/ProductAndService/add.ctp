<?php 
	$sessionstaff = $this->Session->read('staff');
	$access = $sessionstaff['staffaccess']['AccessStaff'];
?>
    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Add Product/Service<?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?>/Coupon <?php }?>
</h1>
</div>

    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
              <?php echo $this->Form->create("productandservices",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));
     
 ?>
        <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Title</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Title" id="title" name="title" value="">
        </div>
        </div>
         <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Type</label>

<div class="col-sm-9">
	<select id="type" class="col-xs-10 col-sm-5" name="data[type]">
        <option value="1">Product</option>
	<option value="2">Service</option>
       <?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?> 
       	<option value="3">Coupon</option>
       	<?php } ?>
       
	</select>
</div>
 </div>

     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Points</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Points" id="points" name="points" value="">
</div>
 </div>
  <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star" id="mendet"></span>Description</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="" >
        </div>
        </div>
  
        <div class="form-group Clearfix" id="couponImage" style="display: none;">
        <div id="ermsgbuzzydoc" class="message"></div>
  
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Coupon Image<span class="star">*</span></label>
        <div class="col-sm-9 col-xs-12">
              
                  <?php
                 
                  echo $this->Form->input("coupon_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'','class'=>'',"onchange"=>"checkimg('productandservicesCouponImage','blu');"));
                  ?>
            <a onclick="removeimg('productandservicesCouponImage', 'blu');" class="" id="blu"></a>
                  
        </div>
    </div>
    
<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save" class="btn btn-info">
       
									</div>  
      </form>

  
 
     
   </div>
  


<script language="Javascript">
function checkimg(filename, aname) {
        var sluval = $('#' + filename).val();
        if (sluval != '') {
            $('#' + aname).text('x');
            $('#' + aname).addClass('icon-top hand-icon');
        }
    }
    function removeimg(filename, aname) {
        $('#' + filename).val('');
        $('#' + aname).text('');
        $('#' + aname).removeClass('icon-top hand-icon');
    }
$('#type').on('change',function(){
$('#description').removeAttr('required');
$('#mendet').text('');
$("#couponImage").hide();
var selecttext=$("#type option:selected").text();
    if($(this).val()==3 || selecttext=='Coupon' || selecttext=='coupon'){
        $('#description').attr('required','required');
        $('#mendet').text('*');
        $("#couponImage").show();
    }
});


$(document).ready(function() { 
			
	
	 
			
        $('#productandservicesAddForm').validate({
		rules: {
                        title: "required",
			points: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        title: "Please enter title",
			points: {
				required: "Please enter points",
				number: "Please enter a valid point value"
			},

			description: "Please enter reward description"

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
            var selecttext=$("#type").val();
        if($('#productandservicesCouponImage').val()=='' && selecttext==3){
            alert('Please upload coupon image');
            return false;
        }
               if($('#points').val()>0){
            form.submit();
        }else{
            alert('Points should be greater then 0.');
            return false;
        }
        
        
        }  
            
         });
});
</script>

