<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    //echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    
   // echo $this->Html->css(CDN.'css/facebox.css');
   // echo $this->Html->script(CDN.'js/faceBox/facebox.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-crosshairs"></i>
            Referrals
        </h1>
    </div>
    <div style="color:red;font-size: 12px;">
                            Remember to check this page so you don’t miss out on referrals coming in from your patients. When a referred patient falls through or moves into treatment, you will need to update the status of the referral. Then, issue points to the 'referred by' patient if the referral is completed.
                            
                        </div>
<?php echo $this->Session->flash(); ?>


    <div class="adminsuper">
        <div class="table-responsive">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 

                        <td class="client sorting tdselect13" aria-label="Domain: activate to sort column ascending" >First Name</td>
                        <td class="client sorting tdselect12" aria-label="Domain: activate to sort column ascending" >Last Name</td>
                        <td class="client sorting tdselect16" aria-label="Domain: activate to sort column ascending" >Referred By</td>
                        <td class="client sorting tdselect19" aria-label="Domain: activate to sort column ascending" >Preferred Time To Talk</td>
                        <td class="client sorting tdselect15" aria-label="Domain: activate to sort column ascending">Phone Number</td>
                        <td  class="client sorting tdselect15" aria-label="Domain: activate to sort column ascending">Date Referred</td>
                        <td  class="aptn sorting tdselect16" aria-label="Domain: activate to sort column ascending">Status</td>

                    </tr>
                </thead>
                <tbody></tbody>


            </table></div>
    </div>


</div>



<!-------dialog start here----------->
<div id="dialog" title="Change Status Of Lead" style="display:none;">

    <div class="innerbox2">
        <select name="status" id="status" onchange="discard();">
            <option value="">Select Status</option>
            <option value="Failed">Failed</option>
            <option value="Completed">Completed:Lead Became Patient</option>

        </select>
                            <?php 
                            $settings=array();
                                        if(!empty($admin_settings)){
                                            if($admin_settings['AdminSetting']['setting_data']!=''){
                                              $settings=json_decode($admin_settings['AdminSetting']['setting_data']); 
                                            }
                                        }
           
                                        ?>
        <div id="option_lead" style="display: none">
            <input type="text" id='card_number_ref' name="card_number_ref" value="" placeholder="New Patient's Card Number">
            <input type="text" id='points' name="points"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" value="" placeholder="Credit Points for New Patient" <?php if(!isset($settings->allow) && !empty($settings)){ echo "readonly";}else{ echo ""; } ?>>
            <select name="level" id="level" onchange="leadpoint(this.value)">
                <option value="">Select Lead Level</option>
                                        <?php 
                                        
                                        
                                        foreach($LeadLevel as $lead){ 
                                            $point=$lead['LeadLevel']['leadpoints'];
                                                foreach($settings as $set =>$setval){
                                                   if($set==$lead['LeadLevel']['id']){
                                                     $point=$setval;
                                                   }
                                                }
                                            ?>
                <option value="<?=$point?>-<?=$lead['LeadLevel']['id']?>"><?=$lead['LeadLevel']['leadname']?></option>
                                        <?php } ?>

            </select>
        </div>
        <input type="hidden" id='id' name="id">
    </div>


    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
            <input type="button" value="Proceed" id='change_btn' class="btn btn-primary btn-xs">

        </div></div>
    <div id='status_error' style="color: #FF0000;"></div>
</div> 
<!-----end here------> 
<!-------dialog start here----------->
<div id="dialognew" title="Change Status Of Lead" style="display:none;">

    <div class="innerbox2">
        <span id="pntgive"></span> points for <span id="ledgive"></span> will be credited to <span id="refby"></span> for referring the patient <span id="refto"></span>. Do you want to change the lead level points? <a href="/AdminSetting">Click here</a>
    </div>
    <input type="hidden" id='lead_id' name="lead_id">
    <input type="hidden" id='lead_status' name="lead_status">
    <input type="hidden" id='lead_card' name="lead_card">
    <input type="hidden" id='lead_point' name="lead_point">
    <input type="hidden" id='lead_level' name="lead_level">
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
            <input type="button" value="Proceed" id='change_btn1' class="btn btn-primary btn-xs">

        </div></div>
    <div id='status_error1' style="color: #FF0000;"></div>
</div> 



<script>
    function proStatusRedeem(id, status_name, card_number, points, level, levelid, refby, refto) {
        
        $("#dialog").dialog('close');
        $("#dialognew").dialog({modal: true});
        $("#lead_id").val(id);
        $("#lead_status").val(status_name);
        $("#lead_card").val(card_number);
        $("#lead_point").val(points);
        $("#lead_level").val(levelid);
        $("#pntgive").text(points);
        $("#ledgive").text(level);
        $("#refby").text(refby);
        $("#refto").text(refto);
    }
    function leadpoint(points) {
        var myArray = points.split('-');
        $('#points').val(myArray[0]);
    }
    function discard() {
        var status = $('#status').val();

        if (status == 'Failed') {
            $('#option_lead').css('display', 'none');

        } else {
            $('#option_lead').css('display', 'block');

        }
    }
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aoColumnDefs": [
                {
                    "bSortable": false, "aTargets": [0],
                }

            ],
            "order": [[5, "desc"]],
            "bProcessing": true,
            "bServerSide": true,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "/LeadManagement/getLead"

        });

    });


    function changeStatusRedeem(id) {
        $('input[type="button"]').removeAttr('disabled');
        $('#status_error').text('');
        $('#status_error1').text('');
        $('#card_number_ref').val('');
        $('#level').val('');
        $('#points').val('');
        $('#status').val('');
        $("#id").val(id);
        $('#option_lead').css('display', 'none');
        $("#dialog").dialog({modal: true});
    }

    $("#change_btn").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var status_name = $("#status").val();
        var card_number = $("#card_number_ref").val();
        var points = $("#points").val();
        var level = $("#level").val();
        var levelname = $("#level option:selected").html();
        
        var id = $("#id").val();
        if (status_name == '') {
            $("#status_error").html("Please select a status");
            $('input[type="button"]').removeAttr('disabled');
        }
        else if (card_number == '' && status_name == 'Completed') {
            $("#status_error").html("Please enter card number");
            $('input[type="button"]').removeAttr('disabled');
        }
        else if (points == '' && status_name == 'Completed') {
            $("#status_error").html("Please enter points");
            $('input[type="button"]').removeAttr('disabled');
        }
        else if (level == '' && status_name == 'Completed') {
            $("#status_error").html("Please select a level");
            $('input[type="button"]').removeAttr('disabled');
        }
        else {
            if (status_name == 'Completed') {

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo Staff_Name ?>LeadManagement/getleaddetail/",
                    data: "&id=" + id,
                    success: function(msg) {

                        proStatusRedeem(id, status_name, card_number, points, levelname, level,msg.refby,msg.refto);
                        $('input[type="button"]').removeAttr('disabled');
                    }
                });


            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Staff_Name ?>LeadManagement/changestatus/",
                    data: "&id=" + id + "&status=" + status_name + "&card_number=" + card_number + "&points=" + points + "&level=" + level,
                    success: function(msg) {

                        if (msg == 1) {
                            $("#id").val("");
                            $('#card_number_ref').val('');
                            $('#level').val('');
                            $('#points').val('');
                            $("#" + id + "_redeem_td_id").html("Failed");
                            $("#status_error").html("Lead status changed successfully.");
                            $("#" + id + "_redeem_td_id").removeClass("label label-warning newstatus");
                            $("#" + id + "_redeem_td_id").addClass("label label-inverse arrowed newstatus");
                            $(".ui-button-text").click();
                        } else if (msg == 2) {
                            $("#id").val("");
                            $('#card_number_ref').val('');
                            $('#level').val('');
                            $('#points').val('');
                            $("#" + id + "_redeem_td_id").addClass("label label-success arrowed-in arrowed-in-right newstatus");

                            $("#" + id + "_redeem_td_id").html("Completed");
                            $("#status_error").html("Lead status changed successfully.");
                            $("#" + id + "_redeem_td_id").attr('onclick', '').unbind('click');
                            $(".ui-button-text").click();
                        }
                        else if (msg == 3) {

                            $("#status_error").html("Card number does not exist");
                            $('input[type="button"]').removeAttr('disabled');
                        }
                        else {

                            $("#status_error").html("Error found try again");
                            $('input[type="button"]').removeAttr('disabled');
                        }

                    }
                });
            }
        }

    });
    $("#change_btn1").click(function() {
        $('input[type="button"]').attr('disabled', 'disabled');
        var status_name = $("#lead_status").val();
        var card_number = $("#lead_card").val();
        var points = $("#lead_point").val();
        var level = $("#lead_level").val();

        var id = $("#lead_id").val();


        $.ajax({
            type: "POST",
            url: "<?php echo Staff_Name ?>LeadManagement/changestatus/",
            data: "&id=" + id + "&status=" + status_name + "&card_number=" + card_number + "&points=" + points + "&level=" + level,
            success: function(msg) {

                if (msg == 1) {
                    $("#id").val("");
                    $('#card_number_ref').val('');
                    $('#level').val('');
                    $('#points').val('');

                    $("#" + id + "_redeem_td_id").html("Failed");
                    $("#" + id + "_redeem_td_id").removeClass("label label-warning newstatus");
                    $("#" + id + "_redeem_td_id").addClass("label label-inverse arrowed newstatus");
                    $(".ui-button-text").click();
                } else if (msg == 2) {
                    $("#id").val("");
                    $('#card_number_ref').val('');
                    $('#level').val('');
                    $('#points').val('');
                    $("#" + id + "_redeem_td_id").removeClass("label label-warning newstatus");
                    $("#" + id + "_redeem_td_id").removeClass("label label-inverse arrowed newstatus");
                    $("#" + id + "_redeem_td_id").addClass("label label-success arrowed-in arrowed-in-right newstatus");

                    $("#" + id + "_redeem_td_id").html("Completed");
                    $("#status_error1").html("Lead status changed successfully.");
                    $("#" + id + "_redeem_td_id").attr('onclick', '').unbind('click');
                    $(".ui-button-text").click();
                }
                else if (msg == 3) {

                    $("#status_error1").html("Card number does not exist");
                    $('input[type="button"]').removeAttr('disabled');
                }
                else {

                    $("#status_error1").html("Error found try again");
                    $('input[type="button"]').removeAttr('disabled');
                }

            }
        });



    });

    function showAllRedeemStatus(redeem_id) {
        $("#" + redeem_id + "_dropdown_redeem_div").show();
        $("#" + redeem_id + "_input_redeem_div").hide();
    }

</script>
