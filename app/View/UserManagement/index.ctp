<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-user"></i>
            Users
        </h1>
    </div>
	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>

    <div class="adminsuper userMgmBox">
<!--        <span class="export">
            <a href="<?php echo $this->Html->url(array(
   "controller" => "UserManagement",
"action"=>"exportStaffUser"
));?>">Export</a></span>-->
        <div class="table-responsive">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                        <td width="30%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                        <!--<td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Gender</td> -->
                        <td width="30%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Email</td>
                        <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Edit</td>

                    </tr>
                </thead>
                <tbody>

                </tbody>


            </table>
        </div>


    </div>

</div>







<script>

    function user_search(id) {
        $("#user_" + id).submit();
    }
    function changeStatusRedeem(redeem_id) {
        $('#redeem_status').val('');
        $("#id").val(redeem_id);

        $("#dialog").dialog();
    }
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        
        $('#example').dataTable({
            "columnDefs": [ { "targets": 3, "orderable": false } ],
            "bProcessing": true,
            "bServerSide": true,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "/UserManagement/getuser"
        });

    });


</script>
