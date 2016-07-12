<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    //echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    
   // echo $this->Html->css(CDN.'css/facebox.css');
   // echo $this->Html->script(CDN.'js/faceBox/facebox.js');

    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-exchange"></i>
            Redemptions
        </h1>
    </div>

<?php echo $this->Session->flash(); ?>

    <div class="adminsuper">
        <!--<a ><span onclick="bulkUpdate()" style='cursor:pointer;'>Update Bulk Status</span></a>-->
        <div class="table-responsive">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td class="client sorting tdselect9" aria-label="Domain: activate to sort column ascending" >Select</td>
                        <td class="client sorting tdselect14" aria-label="Domain: activate to sort column ascending" >Card Number</td>
                        <td class="client sorting tdselect16" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                        <td class="client sorting tdselect13" aria-label="Domain: activate to sort column ascending">Rewards</td>
                        <td class="client sorting tdselect14" aria-label="Domain: activate to sort column ascending">Pts. Redeem</td>
                        <td class="client sorting tdselect14" aria-label="Domain: activate to sort column ascending">Redeem Date</td>
                        <td class="client sorting tdselect12" aria-label="Domain: activate to sort column ascending">Status</td>
                        <td class="client sorting tdselect12" aria-label="Domain: activate to sort column ascending">View</td>
                    </tr>
                </thead>
                <tbody></tbody>
               <!--<tfoot>
                                       <tr> 
                                                       <td width="15%" colspan="2" class="client sorting" aria-label="Domain: activate to sort column ascending" >
                                                               <a href='javascript:void(0)' id="amazon_proceed_div" >Purchase from Amazon</a>
                                                               &nbsp;<span id='amazon_progress_bar'></span>
                                                       </td>
                                                               
                                                       
                                       </tr>
                               </tfoot> -->

            </table>
        </div>


    </div>

</div>


<!-------dialog start here----------->
<div id="dialog" title="Change Redeem Status" style="display:none;">
    <div class="ui-heading">Choose Status</div>
    <div >
        <select name="redeem_status" id="redeem_status" >
            <option value="">Select Status</option>
            <option value="Redeemed">Redeemed</option>
            <option value="In Office">In Office</option>
            <option value="Ordered/Shipped">Ordered/Shipped</option>
        </select>
        <input type="hidden" id='id' name="id">
    </div>
    <div><input type="button" value="Update" id='change_redeem_btn' class="adjust-btn"></div>
    <div id='status_error' style="color: #FF0000;"></div>
</div>
<!-----end here------> 

<!-----------amazon cartconfirm box--------------->
<div id="dialog2" title="" style="display:none;">
    <div class="ui-heading" id="amazon_sucess_div"></div>

</div><!-----end here------> 

<!-------bulk update dialog start here----------->
<div id="dialog3" title="Change Redemption Status" style="display:none;">
    <div class="ui-heading">Choose Status</div>
    <div >
        <select name="bulk_redeem_status" id="bulk_redeem_status" >
            <option value="">Select Status</option>
            <option value="Redeemed">Redeemed</option>
            <option value="In Office">In Office</option>
            <option value="Ordered/Shipped">Ordered/Shipped</option>
        </select>
        <input type="hidden" id='bulk_update_id' name="bulk_update_id">
    </div>
    <div>
        <input type="button" value="Update" id='bulk_change_redeem_btn' class="adjust-btn">
    </div>
    <div id='bulk_status_error' style="color: #FF0000;"></div>
</div>
<!-----end here------> 
<div class="ui-widget-overlay ui-front" id="backgrey" style="display: none;"></div>
<script>
    function bulkUpdate() {
        $('.ui-button-text').click();
        var chkId = '';
        $('input[type=checkbox]:checked').each(function() {
            chkId += $(this).val() + ",";
        });
        chkId = chkId.slice(0, -1);
        if (chkId == '') {
            alert('Please select at least one product');
        } else {
            var r = confirm("Do you want to update the status?");
            $('#bulk_update_id').val("");
            if (r) {
                $('#bulk_update_id').val(chkId);
                $("#dialog3").dialog();
                $('#backgrey').css('display', 'block');
            }
        }
    }

    $('#bulk_change_redeem_btn').click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var transaction_id = $('#bulk_update_id').val();
        var transaction_status = $('#bulk_redeem_status').val();
        if (transaction_id == '') {
            $('input[type="button"]').removeAttr('disabled');
            $('#bulk_status_error').html('Please select at least one product');
        } else if (transaction_status == '') {
            $('input[type="button"]').removeAttr('disabled');
            $('#bulk_status_error').html('Please select redemption status');
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>StaffRedeem/bulkupdate",
                data: "&transaction_id=" + transaction_id + "&transaction_status=" + transaction_status,
                success: function(msg) {
                    $('#bulk_redeem_status').val('');
                    if (msg == '1') {
                        var statusvar = '<option value="">Select Status</option><option value="Redeemed"';
                        if (transaction_status == 'Redeemed') {
                            statusvar += ' selected ';
                        }
                      
                        statusvar += '>Redeemed</option><option value="In Office"';
                        if (transaction_status == 'In Office') {
                            statusvar += ' selected ';
                        }
                        statusvar += '>In Office</option><option value="Ordered/Shipped"';
                        if (transaction_status == 'Ordered/Shipped') {
                            statusvar += ' selected ';
                        }
                        statusvar += '>Ordered/Shipped</option>';
                        var res = transaction_id.split(",");
                        var n = res.length;
                        for (var i = 0; i < n; i++) {
                            $('#redeem_status_' + res[i]).html(statusvar);
                        }
                        $('input[type="button"]').removeAttr('disabled');
                        $('#bulk_status_error').html('Updated successfully');
                    } else {
                        $('input[type="button"]').removeAttr('disabled');
                        $('#bulk_status_error').html('Fail to update');
                    }
                }
            });
        }
    });
    $(function() {
        $('#amazon_proceed_div').click(function() {

            var chkId = '';
            $('input[type=checkbox]:checked').each(function() {
                chkId += $(this).val() + ",";
            });
            chkId = chkId.slice(0, -1);
            if (chkId == '') {
                alert('Please select at least one amazon product');
            } else {
                var normal_prd = $('input[class=ajax-normal]:checked').length;
                if (normal_prd) {
                    alert('You have selected a non-Amazon product. Please unselect the product(s) to proceed');
                    return false;
                }

                $('#amazon_progress_bar').html("<img src='<?php echo CDN; ?>img/loading.gif' >&nbsp;Wait...");
                $.ajax({
                    type: "POST",
                    url: "<?php echo Staff_Name ?>StaffRedeem/amazoncartcreate",
                    data: "&amazon_prd_sku=" + chkId,
                    success: function(msg) {
                        $('#amazon_progress_bar').html("");
                        obj = JSON.parse(msg);
                        if (obj.success == 'true') {
                            $('#amazon_sucess_div').html('Products have been successfully added to cart. <a href="' + obj.PurchaseURL + '" target="_blank" onclick="do_updateBluck();" >Click here </a>to proceed');
                        }
                        $("#dialog2").dialog();
                    }
                });
            }

        });
    });
    function do_updateBluck() {
        $('.ui-button-text').click();
        var r = confirm("You will be redirected to Amazon.com for further processing. Click OK to confirm.");
        if (r) {
            $('#amazon_sucess_div').html('Would you like to update the product status now? <a onclick="bulkUpdate();" style="cursor:pointer" >Yes </a> | <a href="" style="cursor:pointer" > No </a>');
            $("#dialog2").dialog();
        }
    }
 var is_buzzydoc = "<?php echo $sessionstaff['is_buzzydoc'];?>"; 

    $(document).ready(function() {
   var callbackurl = "getRedeem";
   var bServerSide = true;
   if(is_buzzydoc==1){
  	 callbackurl = "getnonlegacyredemptions";
  	 bServerSide = false;
   }
     
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aoColumnDefs": [
                {
                    "bSortable": false, "aTargets": [0, 7],
                }

            ],
            "order": [[5, "desc"]],
            "bProcessing": true,
            "bServerSide": bServerSide,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "/StaffRedeem/"+callbackurl

        });
    });
    
    function changeStatusRedeem(redeem_id) {
        $('#redeem_status').val('');
        $("#id").val(redeem_id);
        $("#dialog").dialog();
    }

    $("#change_redeem_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var status_name = $("#redeem_status").val();
        var id = $("#id").val();
        if (status_name == '') {
            $('input[type="button"]').removeAttr('disabled');
            $("#status_error").html("Please select a status");
        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>StaffRedeem/changeredeemstatusxml/",
                data: "&id=" + id + "&status=" + status_name,
                success: function(msg) {

                    if (msg == 1) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#id").val("");
                        $("#" + id + "_redeem_td_id").html("Redeemed");
                        $(".ui-button-text").click();
                    } else if (msg == 2) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#id").val("");
                        $("#" + id + "_redeem_td_id").html("In Office");
                        $(".ui-button-text").click();
                    } else if (msg == 3) {
                        $('input[type="button"]').removeAttr('disabled');
                        $("#id").val("");
                        $("#" + id + "_redeem_td_id").html("Ordered/Shipped");
                        $(".ui-button-text").click();
                    } else {
                        $("#id").val("");
                        $("#status_error").html("Error found try again");
                        $('input[type="button"]').removeAttr('disabled');
                    }

                }
            });
        }

    });
    
    if(is_buzzydoc==1){
		function changestatus(tid,clinic_id) {
	        var status_name = $("#redeem_status_"+ tid).val();
	        var id = tid;
	        var r = confirm("Are you sure to change status ?");
	        if (r == true)
	        {
                    $('#redeem_status_'+tid).attr('disabled','disabled');
	            $.ajax({
	                type: "POST",
	                url: "<?php echo Staff_Name ?>StaffRedeem/changeredeemstatusxml/",
	                data: "&id=" + tid + "&status=" + status_name+"&clinic_id=" + clinic_id,
	                success: function(msg) {
	                if(msg==1){
	                	$('#redeem_status_'+tid).attr('disabled','disabled');
	                }else{
                            $('#redeem_status_'+tid).removeAttr('disabled');
                        }
	                    alert('Status changed successfully.');
	                }
	            });
	        } else
	        {
	            return false;
	        }
	    }
    }else{
    	function changestatus(tid) {
	        var status_name = $("#redeem_status_" + tid).val();
	        var id = tid;
	        var r = confirm("Are you sure to change status ?");
	        if (r == true)
	        {
	            $.ajax({
	                type: "POST",
	                url: "<?php echo Staff_Name ?>StaffRedeem/changeredeemstatusxml/",
	                data: "&id=" + tid + "&status=" + status_name,
	                success: function(msg) {
	                    //alert('Status changed successfully.');
	                }
	            });
	        } else
	        {
	            return false;
	        }
    	}
    }
    
    function showAllRedeemStatus(redeem_id) {
        $("#" + redeem_id + "_dropdown_redeem_div").show();
        $("#" + redeem_id + "_input_redeem_div").hide();
    }
    $(document).on('click', ".ui-button", function() {

        $('#bulk_status_error').text('');
        $('#backgrey').css('display', 'none');
    });
</script>
