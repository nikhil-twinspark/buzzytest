<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 


$sessionstaff = $this->Session->read('staff');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Payment History
        </h1>
    </div>
    <div class="adminsuper">
         <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="12%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Invoice Id</td>
                        <td width="16%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                        <td width="12%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Email</td>
                        <td width="12%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Date</td>
                        <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Current Balance</td>
                        <td width="12%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Mode</td>
                        <td width="12%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Amount</td>
                        <td width="12%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Tran. Fees</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($Invoices as $Invoice)
					{
					?> 
                    <tr> 
                        <td width="12%"><?php echo $Invoice['Invoice']['invoice_id'];?></td>
                        <td width="16%"><?php echo $Invoice['Invoice']['username'];?></td>
                        <td width="12%"><?php echo $Invoice['Invoice']['email'];?></td>
                        <td width="12%"><?php echo $Invoice['Invoice']['payed_on'];?></td>
                        <td width="12%" ><?php echo $Invoice['Invoice']['current_balance'];?></td>

                        <td width="12%"><?php echo $Invoice['Invoice']['mode']; ?>
                        </td>
                        <td width="12%"><?php echo $Invoice['Invoice']['amount']; ?>
                        </td>	
                        <td width="12%"><?php echo $Invoice['Invoice']['transaction_fee']; ?>
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

<script>
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aaSorting": [[3, "desc"]],
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


</script>
















