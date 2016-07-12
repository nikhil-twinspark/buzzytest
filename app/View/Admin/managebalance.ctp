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
            <i class="menu-icon fa fa-briefcase"></i> Manage Balance Status
        </h1>
    </div>


    <div class="adminsuper userMgmBox">
       
        <div class="table-responsive">
         <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
            <tr> 
                <td width="7%" class="client sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;">Clinic Name</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending"  style="padding: 8px 2px !important; font-size: 10px !important;">Account Number</td>
              
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Amount</td>
                <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Date</td>
    <td width="7%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" style="padding: 8px 2px !important; font-size: 10px !important;" >Status</td>
               
             </tr>
     </thead>
     <tbody>
        <?php 
        foreach ($BeanstreamPayment as $invoice){ ?>
        <tr> 
            <td ><?php echo $invoice['BeanstreamPayment']['customer_name']; ?></td>
            <td ><?php echo $invoice['BeanstreamPayment']['account_number']; ?></td>
        
            <td ><?php echo $invoice['BeanstreamPayment']['points']/100; ?> $</td>
            <td ><?php echo $invoice['BeanstreamPayment']['date']; ?></td>
            <td ><?php if($invoice['BeanstreamPayment']['status']=='Completed'){ $st='disabled'; }else{ $st='';} ?>
            
            <select onchange="changestatus(<?php echo $invoice['BeanstreamPayment']['id']; ?>)" id="redeem_status_<?php echo $invoice['BeanstreamPayment']['id']; ?>" name="redeem_status_<?php echo $invoice['BeanstreamPayment']['id']; ?>" <?php echo $st; ?>>
					<option value="">Select Status</option>
                                        <option <?php if($invoice['BeanstreamPayment']['status']=='Pending'){ echo "selected"; } ?> value="Pending">Pending</option>
					<option value="Completed" <?php if($invoice['BeanstreamPayment']['status']=='Completed'){ echo "selected"; } ?>>Completed</option>
					
				</select>
            </td>
          
           

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
      
    "aaSorting": [[ 3, "asc" ]],
        
     
              
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
        function changestatus(tid) {


        var status_name = $("#redeem_status_" + tid).val();
        var id = tid;
        var r = confirm("Are you sure to change status ?");
        if (r == true)
        {
            $.ajax({
                type: "POST",
                url: "<?php echo Staff_Name ?>Admin/changeredeemstatusxml/",
                data: "&id=" + tid + "&status=" + status_name,
                success: function(msg) {
                    if(msg==2){
                    alert('Status changed successfully.');
                    $("#redeem_status_" + tid).attr('disabled',true);
                }else{
                    alert('Try After Sometime.');
                    $("#redeem_status_" + tid).attr('disabled',false);
                }
            }
        });

        } else
        {
            return false;
        }



    }
    
   </script>
