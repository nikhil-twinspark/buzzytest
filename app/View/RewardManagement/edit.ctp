    <div class="contArea Clearfix">
        <div class="page-header">
           <h1>
               <i class="menu-icon fa fa-book"></i> Rewards
           </h1>
       </div>
    </div>
  <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    
    <?php echo $this->Form->create("Rewards",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));  ?>
        <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Product Type</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if(isset($rewardInfo['Rewards']['amazon_id']) && ($rewardInfo['Rewards']['amazon_id']!='')){ ?>
                     <input type='hidden' value="amazon" name='product_type'>&nbsp;Amazon
                    <?php }else{ ?>
                     <input type='hidden' value="normal" name='product_type'>&nbsp;In-Office
                    <?php } ?>
                </div>
        </div>
			
        <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Category</label>
                <div class="col-sm-9 col-xs-12">
                    <select size="4" name="reward_category" id="reward_category" class="col-xs-12 col-sm-5" required>
                            <option value="">select</option>
                            <?php foreach($category as $val){

                                    if(isset($rewardInfo['Rewards']['category'])){
                                            $category=$rewardInfo['Rewards']['category'];
                                    }else{
                                            $category='';
                                    }

                            ?>
                                    <option value='<?php echo $val['Category']['category'] ?>' <?php echo (($val['Category']['category']==$category) ? "selected='selected'" : '') ?> >
                                            <?php echo $val['Category']['category'] ?>
                                    </option>
                            <?php } ?>
                    </select>
            </div>
        </div>
			
        <span id='reward_name_div' >
            <div class='form-group Clearfix' >
                    <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class='star'>*</span>Reward Name</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type='text' name='reward_name' id='reward_name' class='col-xs-12 col-sm-5' required value="<?php echo (isset($rewardInfo['Rewards']['description']) ? $rewardInfo['Rewards']['description'] : '') ?>" >
                    </div>
            </div>
        </span>
			
        <span id='reward_point_div' >
                <div class="form-group Clearfix" >
                        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Points</label>
                        <div class="col-sm-9 col-xs-12">
                                <input type="number" name="reward_point" id="reward_point" class="col-xs-12 col-sm-5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')" maxlength="6" required value="<?php echo (isset($rewardInfo['Rewards']['points']) ? $rewardInfo['Rewards']['points'] : '') ?>" >
                        </div>
                </div>
        </span>
			
    <span id='reward_image_div' >
            <?php if(isset($rewardInfo['Rewards']['amazon_id']) && $rewardInfo['Rewards']['amazon_id']!=''){ ?>
                    <div class='form-group Clearfix' >
                        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Reward Image</label>
                       
                    </div>
            <?php }else{ ?>
        <div class='form-group Clearfix' >
                        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Reward Image</label>
                        <div class='form-group Clearfix' >
                            <input type='file' name='reward_image' id='reward_image' class='col-xs-12 col-sm-5'>
                        </div>
                    </div>
            <?php } ?>
    </span>
			
    <span id="amazon_prd_list_div" >
        <div class="form-group Clearfix" >
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"></label>
            <div class='col-sm-9 col-xs-12' >
                <img src="<?php echo (isset($rewardInfo['Rewards']['imagepath']) ? $rewardInfo['Rewards']['imagepath'] : '') ?>" id='blah' width='140' height='160' alt='' title='Reward Image' >
            </div>
        </div>
    </span>
			
        <?php if($rewardInfo['Rewards']['amazon_id']=='' && $rewardInfo['Rewards']['amazon_id']==Null){ ?>
			
        <input type='hidden' name='amazon_product_url' id='amazon_product_url' value=''>
        <input type='hidden' name='amazon_id' id='amazon_id' value='<?php echo $rewardInfo['Rewards']['amazon_id']; ?>'>
			
        <div class="col-md-offset-3 col-md-9">
            <input type="submit" value="Edit Reward" class="btn btn-sm btn-primary" onclick="return checkreward();">
        </div>
			<?php } ?>
   </form>
 

<script>
	/***************start here*************************/
	function setAmazonProductType(ptr){
		if(ptr=='amazon'){
			$('#amazon_prd_list_div').html('');
			$('#product_type_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Amazon Product Search</label><div class='col-sm-9 col-xs-12' ><input type='text' name='reward_name' id='reward_name' class='col-xs-12 col-sm-5' name='reward_amazon_url' id='reward_amazon_url' onblur='searchAmazonProduct(this.value)' required style='width:400px;' >&nbsp;<span id='progress_amz_prd_div' style='float:right;width:354px;' ></span></div></div>");
			$('#reward_name_div').html('');
			$('#reward_point_div').html('');
			$('#reward_image_div').html('');
		}else if(ptr=='in-office'){
			$('#amazon_prd_list_div').html('');
			$('#product_type_div').html('');
			$('#reward_name_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Reward Name</label><div class='col-sm-9 col-xs-12' ><input type='text' name='reward_name' id='reward_name' class='col-xs-12 col-sm-5' required ></div></div>");
			$('#reward_point_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Points</label><div class='col-sm-9 col-xs-12' ><input type='text' name='reward_point' id='reward_point' class='col-xs-12 col-sm-5' onkeyup='this.value=this.value.replace(/[^0-9\.]/g, \'\')' maxlength='6' required ></div></div>");
			$('#reward_image_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Reward Image</label><div class='col-sm-9 col-xs-12' ><input type='file' name='reward_image' id='reward_image' class='col-xs-12 col-sm-5' onchange='readURL(this);' required  accept='image/*'></div></div>");
		}
		
	}
	function checkreward(){

		if($('#reward_image').val()!=undefined && $('#reward_image').val()!=''){
		var ext = $('#reward_image').val().split('.').pop().toLowerCase();
            
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			alert('invalid extension!');
			$('#reward_image').focus();
			return false;
		}
		}
            
		
		var datasrc="reward_name="+$('#reward_name').val()+"&reward_category="+$('#reward_category').val()+'&reward_id='+<?php echo $rewardInfo['Rewards']['id']; ?>;
		
		$.ajax({
				type: "POST",
				url: "<?php echo Staff_Name ?>RewardManagement/checkreward",
				data: datasrc,
				success: function(msg){
					if(msg==1){
					alert('Reward Already Exist.')
					return false;
					}
					else{
					$( "#RewardsEditForm" ).submit();
					return true;
					}
				}
			});
		return false;	
	}
	function searchAmazonProduct(ptr){
		if(ptr!=''){
			$('#progress_amz_prd_div').html("<img src='<?php echo CDN; ?>img/loading.gif' > wait...");
			$.ajax({
				type: "POST",
				url: "<?php echo Staff_Name ?>RewardManagement/searchamazonproduct",
				data: "keywords="+ptr,
				success: function(msg){
					$('#progress_amz_prd_div').html('');
					$('#amazon_prd_list_div').html(msg);
				}
			});
		}
	}
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#blah')
				.attr('src', e.target.result)
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
/***************end here*************************/

$(document).ready(function() { 
			
	
	 
			
        $('#RewardsEditForm').validate({
		rules: {
			product_type: "required",
			reward_category:"required",
			reward_amazon_url:"required",
			reward_name:"required",
			reward_point: { 
				required: true
			}
		},
        
        // Specify the validation error messages
		messages: {
			product_type: "Please select product type",
			reward_category: "Please select category",
			reward_amazon_url: "Please enter name of amazon product",
			reward_name:"Please enter reward name",
			reward_point: {
				required: "Please enter reward point"
				
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
            form.submit();
        }  
            
         });
});

</script>

