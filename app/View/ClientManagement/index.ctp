<?php

$sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
//    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
    echo $this->Html->script(CDN.'js/jquery.cookie.js');
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-tachometer"></i> Clients
        </h1>
    </div>

<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
?>

    <div class="adminsuper userMgmBox">
        <span style='float:right;' class="add_rewards" >
            <a href="<?php echo $this->Html->url(array("controller"=>"ClientManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Client</a>
        </span>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="70%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Site Name</td>
                        <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Options</td>

                    </tr>
                </thead>
                <tbody>
        <?php 
        foreach ($clients as $client){
          
            ?>
                    <tr> 
                        <td >
                <?php if($client['Clinic']['is_lite']==1){ ?>
                            <a href="javascript:void(0)" ><?php echo $client['Clinic']['api_user'];?></a>
                <?php }else{
                    if(Domain_Name=='integratestg.sourcefuse.com' && $client['Clinic']['id']!=52 && $client['Clinic']['id']!=67 && $client['Clinic']['id']!=70){
                        $pturl='http://'.str_replace(' ','', $client['Clinic']['api_user']).'.'.Domain_Name.'/rewards/login';
                    }else{
                        $pturl=$client['Clinic']['patient_url'];
                    }
                    ?>
                            <a href="<?php echo $pturl;?>" target="_blank" ><?php echo $client['Clinic']['api_user'];?></a>
                <?php } ?>
                        </td>
                        <td  class="managementbtn">

                            <a href="<?php echo $this->Html->url(array(
							    "controller" => "ClientManagement",
							    "action" => "assigncard",
							    $client['Clinic']['id']
							));?>" title="Assign Card" class="btn btn-xs btn-danger" ><i class="ace-icon fa fa-external-link"></i></a> &nbsp;  
                                    <?php 
                                    if(isset($client['Clinic']['staff_id'])){ 
?>
                            <a href="javascript:void(0);" id-staff="<?php echo $client['Clinic']['id']; ?>" title="Login To Staff" class="btn btn-xs btn-info" id="id-btn-dialog1">
                                <i class="ace-icon fa fa-fighter-jet"></i>
                            </a>
            <?php }else{ ?>
                            <a href="javascript:void(0)" title="Login To Staff" class="btn btn-xs btn-info"><i class="ace-icon fa fa-fighter-jet grey"></i> </a>
            <?php } ?>


                            &nbsp; <a  onclick="syncclient(<?php echo $client['Clinic']['id']; ?>);" id="syn_<?php echo $client['Clinic']['id']; ?>" title="Sync" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-exchange"></i></a>  &nbsp;

                            <a title="Edit" href="/ClientManagement/edit/<?php echo $client['Clinic']['id']; ?>" class="btn btn-xs btn-info">
                                <i class="ace-icon glyphicon glyphicon-pencil"></i>
                            </a>

                            <?php if($client['Clinic']['treatment_avail']==1){ ?>
                            <a title="Treatment List" href="/ClientManagement/treatmentplans/<?php echo $client['Clinic']['id']; ?>" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-wrench"></i>
                            </a>
                            <?php }else{ ?>
                            <a title="Treatment List" href="javascript:void(0);" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-wrench grey"></i>
                            </a>
                            <?php } ?>



                            <a title="Staff Access Control" href="/ClientManagement/staff_access/<?php echo $client['Clinic']['id']; ?>" class="btn btn-xs btn-info">
                                <i class="ace-icon fa fa-cogs"></i>
                            </a>

                            <a title="Delete" href="javascript:void(0);" onclick="cunfdelete('<?php echo $client['Clinic']['id']; ?>');"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>

                        </td>


                    </tr>
      <?php  }//Endforeach ?>
                </tbody>
            </table>
        </div>


    </div>

</div>

<div id="dialog-message" class="hide">
    <form id="settingform_foradmin">
        <div id="staffsel" class="inneropt"></div>
        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                <input type="button" value="Login" id='change_btn' class="btn btn-primary btn-xs">
            </div></div>
        <div id='status_error' style="color: #FF0000;"></div>
    </form>
</div><!-- #dialog-message -->
<script>
    $('#example').on('search.dt', function () {
        var value = $('.dataTables_filter input').val();
        if(value!=''){
            $.cookie('serachVal', value, {path: '/'});
        }
    });
    
    $('a[id^="id-btn-dialog1"]').on('click', function (e) {

        $('#staffsel').html('');
        $.ajax({
            type: "POST",
            data: "clinic_id=" + $(this).attr('id-staff'),
            url: "/ClientManagement/getstaff",
            success: function (result) {
                $('#staffsel').html(result);
            }});
        var dialog = $("#dialog-message").removeClass('hide').dialog({
            modal: true,
            title: "Choose Staff For Login",
            title_html: true,
        });

    });
    $("#change_btn").click(function () {
        datasrc_set = $("#staffchk").val();
        window.open(datasrc_set, '_blank');
    });
    function syncclient(id) {
        $('#syn_' + id).html('Please wait..');
        $.ajax({
            type: "POST",
            data: "client_id=" + id,
            dataType: "json",
            url: "/ClientManagement/sync",
            success: function (result) {
                if (result.result == 1) {
                    $('#syn_' + id).html('Sync');
                }

            }});
    }

    function cunfdelete(id) {
        var r = confirm("Are you sure to delete client?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                data: "client_id=" + id,
                dataType: "json",
                url: "/ClientManagement/delete",
                success: function (result) {
                    alert('Client deleted successfully.')
                    location.reload();
                }});
        } else
        {
            return false;
        }
    }
    $(document).ready(function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "columnDefs": [{"targets": 1, "orderable": false}],
            "aaSorting": [[0, "asc"]],
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
        $('.dataTables_filter input').val($.cookie('serachVal')).trigger($.Event("keyup", { keyCode: 13 }));
    });

</script>
