<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');

?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Accelerated Rewards
        </h1>
    </div>
    <?php if($sessionstaff['staffaccess']['AccessStaff']['no_of_tier']>0 && $sessionstaff['staffaccess']['AccessStaff']['no_of_tier']<=count($AcceleratedReward)){ ?>
        <span class="add_rewards" style="float:right;">
            <a href="javascript:void(0);" title="Add" class="icon-1 info-tooltip" onclick="alert('Notice: The maximum of <?php echo $sessionstaff['staffaccess']['AccessStaff']['no_of_tier']; ?> Accelerated Rewards are in use on your account. Please delete a Accelerated Rewards before a new one can be added.');">Add Accelerated Reward</a>

        </span>

        <?php }else{ ?>
        <span class="add_rewards" style="float:right;">
            <a href="<?php echo $this->Html->url(array(
							    "controller" => "AcceleratedReward",
							    "action" => "add"
							));?>" title="Add" class="icon-1 info-tooltip">Add Accelerated Reward Tier</a>
        </span>
        <?php } ?>
    <div class="adminsuper">
        <table border="0" cellpadding="0" cellspacing="0" id="paging-table" class='addOption'>

            <tr>
                <td>
                    <a href="javascript:void(0)" title="Edit" class="icon-1 info-tooltip adminsetting" id="id-btn-dialog1">Set Accelerated Reward Time Frame</a>
                    <a href="javascript:void(0)" title="Edit" class="icon-1 info-tooltip adminsetting" id="id-btn-dialog2_base">Set Base Value</a>
                </td>

            </tr>
        </table>
        <?php
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Tier Name</td>
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Multiplier Value</td>
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Tier Points</td>
                        <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($AcceleratedReward as $mb)
					{
					
					?>
                    <tr> 
                        <td width="25%"><?php echo $mb['TierSetting']['tier_name'];?></td>
                        <td width="25%"><?php echo $mb['TierSetting']['multiplier_value'];?></td>
                        <td width="25%"><?php echo $mb['TierSetting']['points'];?></td>
                        <td width="25%" class="editbtn response_btn">
                            <a title="Edit" href="<?= Staff_Name ?>AcceleratedReward/edit/<?php echo $mb['TierSetting']['id']; ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;<a title="Delete" href="<?php echo Staff_Name; ?>AcceleratedReward/delete/<?php echo $mb['TierSetting']['id'];?>"  class="btn btn-xs btn-danger"><i class="ace-icon glyphicon glyphicon-trash"></i></a>

                        </td>
                        </td>
                    </tr>
      <?php 	
					}//Endforeach
				 ?>
                </tbody>
            </table>

        </div>		
    </div>

</div>

<div id="dialog-message" class="hide">
    <form id="settingform_foradmin">
        <div class="innermsg">

            <div id="option" class="inneropt">

                <label>Time Frame (days)</label><input type="text" id='tier_timeframe' name="tier_timeframe" value="<?php if($sessionstaff['staffaccess']['AccessStaff']['tier_timeframe']==''){ echo '0'; }else{ echo $sessionstaff['staffaccess']['AccessStaff']['tier_timeframe']; }?>" placeholder="TimeFrame">

            </div>

        </div>


        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                <input type="button" value="Set" id='change_btn' class="btn btn-primary btn-xs">

            </div></div>
        <div id='status_error' style="color: #FF0000;"></div>
    </form>
</div><!-- #dialog-message -->
<div id="dialog-message1_base" class="hide">
    <form id="settingform_foradmin">
        <div class="innermsg">

            <div id="option" class="inneropt">

                <label>Base Value</label><input type="text" id='base_value' name="base_value" value="<?php if($sessionstaff['staffaccess']['AccessStaff']['base_value']==''){ echo '0'; }else{ echo $sessionstaff['staffaccess']['AccessStaff']['base_value']; }?>" placeholder="Base Value" >

            </div>

        </div>


        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                <input type="button" value="Set" id='base_btn' class="btn btn-primary btn-xs">

            </div></div>
        <div id='status_error_base' style="color: #FF0000;"></div>
    </form>
</div><!-- #dialog-message -->
<script>
    
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aaSorting": [[0, "desc"]],
            "sPaginationType": "full_numbers",
        });
        $('#example').dataTable().columnFilter({
            aoColumns: [{type: "text"},
                {type: "select"},
                {type: "text"},
                {type: "number"},
                null,
                {type: "text"},
                null
            ]

        });
    });
    $("#id-btn-dialog1").on('click', function(e) {
        $('#status_error').html('');
        e.preventDefault();

        var dialog = $("#dialog-message").removeClass('hide').dialog({
            modal: true,
            title: "Set Accelerated Reward Time Frame",
            title_html: true,
        });

    });
    $("#change_btn").click(function() {
            if($('#tier_timeframe').val()>0){
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>AcceleratedReward/changetimeframe/",
                data: {tier_timeframe: $('#tier_timeframe').val()},
                success: function(msg) {
                    if (msg==0) {
                        $("#status_error").html('Try Again Leter.');
                    } else {
                        $("#status_error").html('time frame Set successfully.');
                    }
                }
            });
            }else{
            $("#status_error").html('time frame should be greater then 0.');
            }

    });
    $("#id-btn-dialog2_base").on('click', function(e) {
        $('#status_error_base').html('');
        e.preventDefault();

        var dialog = $("#dialog-message1_base").removeClass('hide').dialog({
            modal: true,
            title: "Set Base Value (In %)",
            title_html: true,
        });

    });
    $("#base_btn").click(function() {
            if($('#base_value').val()>0){
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>AcceleratedReward/setBaseValue/",
                data: {base_value: $('#base_value').val()},
                success: function(msg) {
                    if (msg==0) {
                        $("#status_error_base").html('Try Again Leter.');
                    } else {
                        $("#status_error_base").html('base value Set successfully.');
                    }
                }
            });
            }else{
            $("#status_error_base").html('base value should be greater then 0.');
            }

    });
</script>
















