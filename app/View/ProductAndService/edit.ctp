
<?php $sessionstaff = $this->Session->read('staff');	
$access = $sessionstaff['staffaccess']['AccessStaff'];
    	
  ?>
    <div class="contArea Clearfix">
 <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Edit Product/Service<?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?>/Coupon <?php }?>
</h1>
</div>
    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    
  
      <?php echo $this->Form->create("productandservices",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));
 ?>
        <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Title</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Title" id="title" name="title" value="<?php echo $data['ProductService']['title']; ?>">
        </div>
        </div>
         <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Type</label>

<div class="col-sm-9">
	<select id="type" class="col-xs-10 col-sm-5" name="data[type]">
        <option value="1" <?php if($data['ProductService']['type']=='1'){ echo "selected"; } ?>>Product</option>
	<option value="2" <?php if($data['ProductService']['type']=='2'){ echo "selected"; } ?>>Service</option>
       <?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?> 
       	<option value="3" <?php if($data['ProductService']['type']=='3'){ echo "selected"; } ?>>Coupon</option>
       	<?php } ?>
       
	</select>
</div>
 </div>
            <input type="hidden"  id="id" name="id" value="<?php echo $data['ProductService']['id']; ?>">
     <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Points</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Points" id="points" name="points" value="<?php echo $data['ProductService']['points']; ?>">
</div>
 </div>
  <div class="form-group">
      
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><?php if($data['ProductService']['type']=='3'){ $req="required"; ?><span class="star" id="mendet">*</span><?php }else{ $req=""; ?><span class="star" id="mendet"></span> <?php } ?>Description</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="<?php echo $data['ProductService']['description']; ?>" <?php echo $req; ?>>
        </div>
        </div>
       <?php if($data['ProductService']['type']=='3'){  ?>
    <div class="form-group Clearfix" id="couponImage">
        <div id="ermsgbuzzydoc" class="message"></div>
  
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Coupon Image<span class="star">*</span></label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($data['ProductService']['coupon_image'])){
                  echo $this->Form->input("coupon_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'','class'=>'',"onchange"=>"checkimg('productandservicesCouponImage','blu');"));
                  ?>
            <a onclick="removeimg('productandservicesCouponImage', 'blu');" class="" id="blu"></a>
            <img src="<?=S3Path.$data['ProductService']['coupon_image']?>" height="136" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("coupon_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'','class'=>'',"onchange"=>"checkimg('productandservicesCouponImage','blu');"));
                  ?>
            <a onclick="removeimg('productandservicesCouponImage', 'blu');" class="" id="blu"></a>
                  <?php
                  } ?>
        </div>
    </div>
       <?php } ?>
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

	$('#productandservicesEditForm').validate({
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

			description: "Please enter reward description",

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




