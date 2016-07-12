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
        <h1><i class="menu-icon fa fa-tachometer"></i>Service Management</h1>
    </div>

<?php
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
?>

    <div class="adminsuper userMgmBox">
        <span style='float:right;' class="add_rewards" >
            <a href="<?php echo $this->Html->url(array("controller"=>"Services","action"=>"add"));?>" class="icon-add info-tooltip">Add Client</a>  
        </span>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Service Name</td>
                        <td width="50%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Service Description</td>
                        <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Options</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $service_data){ ?>
                    <tr> 
                        <td>
                            <a href="javascript:void(0)" >
                                <?php echo $service_data['Service']['service_name'];?>
                            </a>
                        </td>
                        <td>
                            <?php echo $service_data['Service']['service_content'];?>
                        </td>
                        <td>
                            <a class="btn btn-xs btn-danger" onclick="service_delete('<?php echo $service_data['Service']['id'];?>');" href="javascript:void(0);" title="Delete"><i class="ace-icon glyphicon glyphicon-trash"></i></a>
                        </td>
                    </tr>
                <?php }?>
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
    
    function service_delete(id) {
        alert(id);
        var r = confirm("Are you sure to delete service?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                data: "id=" + id,
                dataType: "json",
                url: "/services/delete",
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
