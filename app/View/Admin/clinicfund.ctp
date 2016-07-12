<?php 
    
    $sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-briefcase"></i> BuzzyDoc Bank
        </h1>
    </div>


    <div class="adminsuper userMgmBox">
       
        <div class="table-responsive">
         <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
            <tr> 
                <td width="7%" class="client sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;">Clinic Name</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending"  style="padding: 8px 2px !important; font-size: 10px !important;">Outstanding points</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Outstanding $</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Minimum Deposit %</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Threshold %</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Min. Deposit $</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Threshold</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Actual Min. Deposit $</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Actual Threshold</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Current Transactions</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Live Min Deposit $</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Redemptions this month</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending"  style="padding: 8px 2px !important; font-size: 10px !important;">Avg Mo Redemptions</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Avg. Redemption $</td>
               
             </tr>
     </thead>
     <tbody>
        <?php 
        foreach ($invoices as $invoice){ ?>
        <tr> 
            <td ><?php echo $invoice['Invoice']['clinic_name']; ?></td>
            <td ><?php echo $invoice['Invoice']['outstanding_points']; ?></td>
            <td ><?php echo number_format($invoice['Invoice']['outstanding_points_dol'],2); ?></td>
            <td ><?php echo $invoice['Invoice']['minimum_dep_per']; ?></td>
            <td ><?php echo $invoice['Invoice']['threshold_per']; ?></td>
            <td ><?php echo $invoice['Invoice']['minimum_dep_dol']; ?></td>
            <td ><?php echo number_format($invoice['Invoice']['threshold_dol'],2); ?></td>
            <td ><?php echo $invoice['Invoice']['actual_minimum_dep_dol']; ?></td>
            <td ><?php echo $invoice['Invoice']['actual_threshold_dol']; ?></td>
            <td ><?php echo number_format($invoice['Invoice']['current_transaction'],2); ?></td>
            <td ><?php echo number_format($invoice['Invoice']['live_min_deposit'],2); ?></td>
            <td ><?php echo $invoice['Invoice']['redemption_this_month']; ?></td>
            <td ><?php echo $invoice['Invoice']['avg_redemption_month']; ?></td>
            <td ><?php echo number_format($invoice['Invoice']['avg_redemption'],4); ?></td>
           

        </tr>
      <?php  }//Endforeach ?>
        </tbody>
       </table>
    </div>
     

    </div>

</div>

<script>

 
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {

           "columnDefs": [  ],
      
    "aaSorting": [[ 0, "asc" ]],
        
     
              
                "sPaginationType": "full_numbers",
                
               
                
} );
        $('#example').dataTable().columnFilter({
                aoColumns: [ { type: "text" },
                             { type: "select" },
                             { type: "text" },
                             { type: "number" },
                             null,
                            { type: "text" },
                             null
                        ]

        });
    });
    
   </script>
