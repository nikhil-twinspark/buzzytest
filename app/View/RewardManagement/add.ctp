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
    <div id="typeerror" style="padding-left:26%;color: red;"></div>
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">
        <span class="star">*</span>Product Type
    </label>
    <div class="col-sm-9 col-xs-12">
        <input type='radio' value="amazon" name='product_type' onclick='setAmazonProductType("amazon"), clearError();'  >&nbsp;Amazon
        <input type='radio' value="normal" name='product_type' onclick='setAmazonProductType("in-office"), clearError();'  >&nbsp;In-Office
    </div>
</div>

<div class="form-group Clearfix">
    <div id="caterror" style="padding-left:26%;color: red;"></div>
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">
        <span class="star">*</span>Category
    </label>
    <div class="col-sm-9 col-xs-12">
        <select size="4" name="reward_category" id="reward_category" class="col-xs-12 col-sm-5" onclick='clearErrorcat();'>
            <option value="">select</option>
                    <?php foreach($category as $val){ ?>
            <option value='<?php echo $val['Category']['category'] ?>' >
                    <?php echo $val['Category']['category'] ?>
            </option>
                    <?php } ?>
        </select>
    </div>
</div>
<span id='product_type_div' ></span>
<span id='reward_name_div' ></span>
<span id='reward_point_div' ></span>
<span id='reward_image_div' ></span>
<div id="amazon_prd_list_div"  class="col-sm-12 col-xs-12"></div>


<input type='hidden' name='amazon_product_url' id='amazon_product_url' value=''>
<input type='hidden' name='amazon_id' id='amazon_id' value=''>

<div class="col-md-offset-3 col-md-9 col-sm-12 col-xs-12">
    <input type="submit" value="Save Reward" class="btn btn-sm btn-primary" onclick="return checkreward();">
</div>
</form>




<script>
    function clearError() {
        $("#typeerror").text('');
    }
    function clearErrorcat() {
        $("#caterror").text('');
    }
    function clearErrorname() {
        $("#nameerror").text('');
    }
    function clearErrorpoint() {
        $("#pointerror").text('');
    }
    function clearErrorimg() {
        $("#imgerror").text('');
    }
    /***************start here*************************/
    function setAmazonProductType(ptr) {
        if (ptr == 'amazon') {
            $('input[type="submit"]').attr('disabled', 'disabled');
            $('#savebutton').css("cursor", "");
            $('#amazon_prd_list_div').html('');
            $('#product_type_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Amazon Product Search</label><div class='col-sm-9 col-xs-12'><input type='text' class='col-xs-12 col-sm-5' name='reward_amazon_url' id='reward_amazon_url' required style='width:400px;' >&nbsp;<span onclick='searchAmazonProduct(1)' style='cursor: pointer;' class='add_profile icon-1'>Search</span>&nbsp;<span id='progress_amz_prd_div' style='float:right;' ></span></div></div>");
            $('#reward_name_div').html('');
            $('#reward_point_div').html('');
            $('#reward_image_div').html('');
        } else if (ptr == 'in-office') {
            $('input[type="submit"]').removeAttr('disabled');
            $('#savebutton').css("cursor", "pointer");
            $('#amazon_prd_list_div').html('');
            $('#product_type_div').html('');
            $('#reward_name_div').html("<div class='form-group Clearfix' ><div id='nameerror' style='padding-left:26%;color: red;'></div><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Reward Name</label><div class='col-sm-9 col-xs-12'><input type='text' name='reward_name' id='reward_name' class='col-xs-12 col-sm-5' onclick='clearErrorname();'></div></div>");
            $('#reward_point_div').html("<div class='form-group Clearfix' ><div id='pointerror' style='padding-left:26%;color: red;'></div><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Points</label><div class='col-sm-9 col-xs-12'><input type='text' name='reward_point' id='reward_point' class='col-xs-12 col-sm-5' maxlength='6' onclick='clearErrorpoint();'></div></div>");
            $('#reward_image_div').html("<div class='form-group Clearfix' ><div id='imgerror' style='padding-left:26%;color: red;'></div><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Reward Image</label><div class='col-sm-9 col-xs-12'><input type='file' name='reward_image' id='reward_image' class='col-xs-12 col-sm-5'  onclick='clearErrorimg();' ></div></div>");
        }

    }
    function validatePhone(email) {
        var re = /^([0-9])+$/;
        return re.test(email);
    }
    function checkreward() {

        var type = $('input[name="product_type"]:checked').length;
        if (type) {

        } else {
            $('#typeerror').text('Please select product type');
            return false;
        }
        var cat = $('#reward_category').val();
        if ($.trim(cat) == '') {
            $('#caterror').text('Please select product category');
            return false;
        }
        var name = $('#reward_name').val();
        if ($.trim(name) == '') {
            $('#nameerror').text('Please enter reward name');
            return false;
        }
        var point = $('#reward_point').val();
        if ($.trim(point) == '') {
            $('#pointerror').text('Please enter reward point');
            return false;
        }
        if (!validatePhone(point)) {
            $('#pointerror').text('Only numeric value should be allowed');
            return false;
        }
        var img = $('#reward_image').val();
        if (img == '') {
            $('#imgerror').text('Please select reward image');
            return false;
        }
        if ($('#reward_image').val() != undefined && $('#reward_image').val() != '') {
            var ext = $('#reward_image').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert('invalid extension!');
                $('#reward_image').focus();
                return false;
            }
        }

        var datasrc = '';
        if ($('#reward_name').val() != undefined && $('#reward_name').val() != '') {
            datasrc = datasrc + "reward_name=" + $('#reward_name').val();
        }

        $.ajax({
            type: "POST",
            url: "<?php echo Staff_Name ?>RewardManagement/checkreward",
            data: datasrc,
            success: function(msg) {
                if (msg == 1) {
                    alert('Reward Already Exist.')
                    return false;
                }
                else {
                    $("#RewardsAddForm").submit();
                    return true;
                }
            }
        });
        return false;
    }
    function searchAmazonProduct(pageid) {
        var ptr = $('#reward_amazon_url').val();
        if ($.trim(ptr) != '') {
            $('#progress_amz_prd_div').html("<img src='<?php echo CDN; ?>img/loading.gif' > wait...");
            $('#progress_amz_prd_div_page').html("<img src='<?php echo CDN; ?>img/loading.gif' > wait...");
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>RewardManagement/searchamazonproduct/" + pageid,
                data: "keywords=" + ptr,
                success: function(msg) {
                    $('#reward_point_div').html("");
                    $('#reward_name_div').html("");
                    $('#amazon_product_url').val('');
                    $('#amazon_id').val('');

                    $('input[type="submit"]').attr('disabled', 'disabled');
                    $('#progress_amz_prd_div').html('');
                    $('#progress_amz_prd_div_page').html('');
                    $('#amazon_prd_list_div').html(msg);
                }
            });
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah')
                        .attr('src', e.target.result)
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    /***************end here*************************/





</script>
