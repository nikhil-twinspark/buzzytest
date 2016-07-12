<?php 
    $sessionAdmin = $this->Session->read('Admin');  
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
?>

<div class="contArea Clearfix">
     <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-list-ul"></i> Global Users
        </h1>
    </div>
    <?php 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
    ?>
    <div class="adminsuper">
        <div class="table-responsive">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover" > 
                <thead>
                        <tr>              
                            <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Age</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Email</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Actions</td>
                         </tr>
                 </thead>
                 <tbody>
                </tbody>
         </table>
        </div>
    </div>
</div>
</div><!-- container -->
<script>
      
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {
           "columnDefs": [ { "targets": 4, "orderable": false } ],
			"bProcessing": true,
			"bServerSide": true,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "/GlobalUserManagement/getUser"
       
		} );
    });
    function showAllRedeemStatus(redeem_id){
        $("#"+redeem_id+"_dropdown_redeem_div").show();
        $("#"+redeem_id+"_input_redeem_div").hide();
    }
</script>