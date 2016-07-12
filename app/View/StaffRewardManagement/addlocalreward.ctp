
<style>
    
    .help-block {
    color: #FF0000 !important;
    display: block;
    margin-bottom: 7px !important;
    margin-left: 0;
    margin-top: -51px !important;
}
</style>
<?php $sessionstaff = $this->Session->read('staff');
//echo "<pre>";print_r($sessionstaff);die;	?>
<div class="contArea Clearfix">

    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-cubes"></i>
            Rewards
        </h1>
    </div>
    <div class="adminsuper">
   <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
?>

        <form name='addrewardfrm' id='addrewardfrm' class="form-horizontal" enctype="multipart/form-data" method='post' action='<?php echo Staff_Name ?>StaffRewardManagement/postlocalreward' >
            <table border="0" cellpadding="0" cellspacing="0" width='100%'>
                <tr>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" width='100%'>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?php if(isset($rewardInfo['Reward'])){ if(isset($rewardInfo['Reward']['amazon_id']) && $rewardInfo['Reward']['amazon_id']!='' && $rewardInfo['Reward']['amazon_id']!=Null){ echo "View"; }else{ echo "Edit"; } }else{ echo "Add"; } ?> Rewards to your portal</td>
                            </tr>

                            <tr>
                                <td style="height: 50px">
                                    <label for="reward_point">Product Type 
                                        <b style="color: red">*</b>
                                    </label>
                                </td>
                                <td>
                                    <div class="pregInput" style="position: relative;">
										<?php if(isset($rewardInfo['Reward'])){ 
										if($rewardInfo['Reward']['amazon_id']!=''){?>
                                        <input type='text' value="amazon" id='product_type' name='product_type' readonly>
											<?php }else{ ?>
                                        <input type='text' value="in-office" id='product_type' name='product_type' readonly>
										<?php }}else{
                                                                                     if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['is_lite']==0 && !isset($rewardInfo['Reward'])){echo 'In-office';
                                                                                     ?>
                                        <input type='hidden' value="in-office" id='product_type' name='product_type' >
                                        <?php
                                                                                     }else{
                                                                                         
                                                                                    ?>
                                        <input type='radio' value="amazon" id='product_type' name='product_type' onclick='setAmazonProductType("amazon")' required <?php if(isset($rewardInfo['Reward']['amazon_id']) && $rewardInfo['Reward']['amazon_id']!='' && $rewardInfo['Reward']['amazon_id']!=Null){ echo "checked"; }?> <?php if(isset($rewardInfo['Reward']['id'])){ echo "readonly"; } ?>>&nbsp;Amazon
                                        <input type='radio' value="in-office" id='product_type' name='product_type' onclick='setAmazonProductType("in-office")' required <?php if(isset($rewardInfo['Reward']['amazon_id']) && $rewardInfo['Reward']['amazon_id']=='' && $rewardInfo['Reward']['amazon_id']==Null){ echo "checked"; }?> <?php if(isset($rewardInfo['Reward']['id'])){ echo "readonly"; } ?>>&nbsp;In-office

                                                                                     <?php }} ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style="height: 50px">
                                    <label for="reward_point">Category 
                                        <b style="color: red">*</b>
                                    </label>
                                </td>
                                <td>
                                    <div class="pregInput" style="position: relative;">
                                        <select name='reward_category' id='reward_category' required >
                                            <option value=''>select</option>
												<?php foreach($category as $val){ ?>
                                            <option value='<?php echo $val['Category']['category'] ?>' <?php if(isset($rewardInfo['Reward']['category']) && $rewardInfo['Reward']['category']==$val['Category']['category']){ echo "selected"; } ?> >
														<?php echo $val['Category']['category'] ?>
                                            </option>
												<?php } ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>

                            <tr id="reward_name_div" >
									<?php if(isset($rewardInfo['Reward']['description']) && isset($rewardInfo['Reward']['id'])){ ?>
                                <td style='height: 50px'>
                                    <label for='reward_name'>Reward Name <b style='color: red'>*</b></label>
                                </td>
                                <td>
                                    <div class='pregInput' style='position: relative;'>
                                        <input type='text' name='reward_name' id='reward_name' required value="<?php echo (isset($rewardInfo['Reward']['description']) ? $rewardInfo['Reward']['description'] : '') ?>">
                                    </div>
                                </td>
									<?php } ?>
                            </tr>

                            <tr id="amazon_point_div" >
									<?php if(isset($rewardInfo['Reward']['points'])){ ?>
                                <td style='height: 50px'>
                                    <label for='reward_point'>Points<b style='color: red'>*</b></label>
                                </td>
                                <td>
                                    <div class='pregInput' style='position: relative;'>



                                        <input type="text" value="<?php echo (isset($rewardInfo['Reward']['points']) ? $rewardInfo['Reward']['points'] : '') ?>" required="" maxlength="6" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" id="reward_point" name="reward_point">
                                    </div>
                                </td>
									<?php } ?>
                            </tr>
								<?php if(isset($rewardInfo['Reward']['amazon_id']) && $rewardInfo['Reward']['amazon_id']!='' && $rewardInfo['Reward']['amazon_id']!=Null){
                                                                    ?>
                            <tr>
                                <td><div class="normal_img">
                                    	<?php if(isset($rewardInfo['Reward']['imagepath'])){ 
								$imgpath=$rewardInfo['Reward']['imagepath'];
								$img_status='yes';
							}else{
								$imgpath='';
								$img_status='no';
							} ?>
							<?php if($imgpath!=''){ echo $this->html->image($imgpath,array('id'=>'blah','width'=>'140','height'=>'160','alt'=>'','title'=>'Reward Image')); }?>


                                    </div></td>
                                <td>&nbsp;</td>
                            </tr>
                                                                <?php
								}else{ ?>
                            <tr id="amazon_url_div" >
									<?php if(isset($rewardInfo['Reward']['imagepath']) && ($rewardInfo['Reward']['amazon_id'] < 1)){ ?>
                                <td style='height: 50px'>
                                    <label for='reward_image'>Image </label>
                                </td>
                                <td>
                                    <div class='pregInput' style='position: relative;'>
                                        <input type='file' name='reward_image' id='reward_image'>
                                    </div>
                                </td>
									<?php } ?>

                            </tr>
                            <tr>
                                <td><div class="normal_img">
                                    	<?php if(isset($rewardInfo['Reward']['imagepath'])){ 
								$imgpath=$rewardInfo['Reward']['imagepath'];
								$img_status='yes';
							}else{
								$imgpath='';
								$img_status='no';
							} ?>
							<?php if($imgpath!=''){ echo $this->html->image($imgpath,array('id'=>'blah','width'=>'140','height'=>'160','alt'=>'','title'=>'Reward Image')); }?>
                                        <input type='hidden' name='img_status' id='img_status' value="<?php echo $img_status ?>" >
                                        <input type='hidden' name='edit_id' id='edit_id' value="<?php echo (isset($rewardInfo['Reward']['id']) ? $rewardInfo['Reward']['id'] : '') ?>" >
                                        <input type='hidden' name='amazon_product_url' id='amazon_product_url' value=''>
                                        <input type='hidden' name='amazon_id' id='amazon_id' value=''>
                                    </div></td>
                                <td>
                                    <input type='submit' name='addBtn' value='Save' onclick="return checkreward();" >&nbsp;
										<?PHP if(isset($rewardInfo['Reward'])){ }else{ ?><input type='reset' value='Reset' id="reset"><?php } ?>
                                </td>
                            </tr>
                            <tr id="amazon_prd_list_div" ><!--ajax product here--></tr>



								<?php } ?>
                        </table>
                    </td>

                </tr>


            </table>
        </form>


    </div>

</div>



<script type='text/javascript'>
    <?php if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['is_lite']==0 && !isset($rewardInfo['Reward'])){ ?>
        setAmazonProductType("in-office");
    <?php } ?>
            function setAmazonProductType(ptr){


            if (ptr == 'amazon'){
            $('input[type="submit"]').attr('disabled', 'disabled');
                    $('input[type="submit"]').css('cursor', 'not-allowed');
                    $('#amazon_prd_list_div').html("");
                    $('#amazon_url_div').html("<td style='height: 50px'><label for='reward_amazon_url'>Search Amazon Product<b style='color: red'>*</b></label></td><td><div class='pregInput' style='position: relative;'><input type='text' required value='' name='reward_amazon_url' id='reward_amazon_url' >&nbsp;<span onclick='searchAmazonProduct(1)' style='cursor: pointer;'>Search</span>&nbsp;<span id='progress_amz_prd_div' ></span></div></td>");
                    $('#amazon_point_div').html("");
                    $('#reward_name_div').html("");
                    $('#amazon_product_url').val('');
                    $('#amazon_id').val('');
            } else if (ptr == 'in-office'){
            $('input[type="submit"]').removeAttr('disabled');
                    $('#amazon_prd_list_div').html("");
                    $('#reward_name_div').html("<td style='height: 50px'><label for='reward_name'>Reward Name <b style='color: red'>*</b></label></td><td><div class='pregInput' style='position: relative;'><input type='text' name='reward_name' id='reward_name' required ></div></td>");
                    //$('#amazon_point_div').html("<td style='height: 50px'><label for='reward_point'>Points<b style='color: red'>*</b></label></td><td><div class='pregInput' style='position: relative;'><input type='text' value='' name='reward_point' id='reward_point' required onkeyup='this.value.replace(/[^0-9\.]/g, \'\')' maxlength='6' ></div></td>");
                    $('#amazon_point_div').html('<td style="height: 50px"><label for="reward_point">Points<b style="color: red">*</b></label></td><td><div class="pregInput" style="position: relative;"><input type="text" value="" required="" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, \'\')" id="reward_point" name="reward_point"></div></td>');
                    $('#amazon_url_div').html("<td style='height: 50px'><label for='reward_image'>Image <b style='color: red'>*</b></label></td><td><div class='pregInput' style='position: relative;'><input type='file' name='reward_image' id='reward_image'  required ></div></td>");
                    $('#amazon_product_url').val('');
                    $('#amazon_id').val('');
            }

            }
    //function resetform(){
    $("#reset").on("click", function(){

    $('#amazon_prd_list_div').html("");
            setTimeout(function(){
            $('#reward_name').val('');
                    $('#reward_point').val('');
            }, 100);
       ;
    });
            function searchAmazonProduct(pageid){

            $('#amazon_id').val('');
                    var ptr = $('#reward_amazon_url').val();
                    if (ptr != ''){

            $('#progress_amz_prd_div').html("<img src='<?php echo CDN; ?>img/loading.gif' > wait...");
                    $('#progress_amz_prd_div_page').html("<img src='<?php echo CDN; ?>img/loading.gif' > wait...");
                    $.ajax({
                    type: "POST",
                            url: "<?php echo Staff_Name ?>StaffRewardManagement/searchamazonproduct/" + pageid,
                            data: "keywords=" + ptr,
                            success: function(msg){
                            $('#amazon_point_div').html("");
                                    $('#reward_name_div').html("");
                                    $('#amazon_product_url').val('');
                                    $('#amazon_id').val('');
                                    $('input[type="submit"]').attr('disabled', 'disabled');
                                    $('input[type="submit"]').css('cursor', 'not-allowed');
                                    $('#progress_amz_prd_div').html('');
                                    $('#progress_amz_prd_div_page').html('');
                                    $('#amazon_prd_list_div').html(msg);
                                    $('.productBoxSM').click(function(){
                            $('.productBoxSM').removeClass('active_prdct');
                                    $(this).addClass('active_prdct');
                            });
                            }
                    });
            }
            }

    function checkImageUrl(){
    var reward_image = $('#reward_image').val();
            var amazon_url = $('#reward_amazon_url').val();
            var reward_point = $('#reward_point').val();
            var img_status = $('#img_status').val();
            if ((reward_image == '') && (amazon_url == '') && (img_status == 'no')){
    $('#reward_image').css('background-color', 'pink');
            $('#reward_amazon_url').css('background-color', 'pink');
            $('#reward_amazon_url').focus();
            $('#error_id').text('Reward image or amazon url can not be blank');
            return false;
    } else if (reward_point < 1){
    $('#reward_point').css('background-color', 'pink');
            $('#reward_point').focus();
            $('#error_id').text('Reward point can not be zero');
            return false;
    }
    return true;
    }
    function checkreward(){

    if ($('#reward_image').val() != undefined && $('#reward_image').val() != ''){
    var ext = $('#reward_image').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == - 1) {
    alert('invalid extension!');
            $('#reward_image').focus();
            return false;
    }
    }
    var datasrc = '';
            if ($('#reward_name').val() != undefined && $('#reward_name').val() != ''){
    datasrc = datasrc + "reward_name=" + $('#reward_name').val();
    }
    if ($('#edit_id').val() != undefined && $('#edit_id').val() != ''){
    datasrc = datasrc + '&reward_id=' + $('#edit_id').val();
    }
    $.ajax({
    type: "POST",
            url: "<?php echo Staff_Name ?>StaffRewardManagement/checkreward",
            data: datasrc,
            success: function(msg){
            if (msg == 1){
            alert('Reward Already Exist.')
                    return false;
            }
            else{
            $('input[type="submit"]').css('cursor', 'pointer');
                    $("#addrewardfrm").submit();
                    return true;
            }
            }
    });
            return false;
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

    $(document).ready(function() {




    $('#addrewardfrm').validate({
    errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
            product_type: "required",
                    reward_category:"required",
                    reward_amazon_url:"required",
                    reward_name:"required",
                    reward_point: {
                    required: true
                    }
                       <?php if(!isset($rewardInfo['Reward']['id'])){ ?>,
                    reward_image:"required"
                       <?php } ?>
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
                       <?php if(!isset($rewardInfo['Reward']['id'])){ ?>,
                    reward_image:"Please select reward image"
                       <?php } ?>
            },
            highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
            $(e).closest('.form-group').removeClass('has-error'); //.addClass('has-info');
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
            errorPlacement: function(error, element) {
            if (element.attr("type") == "radio") {
                error.insertBefore(element);
            } else {
                error.insertAfter(element);
            }
        },
            submitHandler: function(form) {
            $('input[type="submit"]').css('cursor', 'pointer');
                    form.submit();
            }

 });
            });





</script>






















