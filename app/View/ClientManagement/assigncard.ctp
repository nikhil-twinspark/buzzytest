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
            <a href="<?php echo $this->Html->url(array("controller"=>"ClientManagement","action"=>"addcard",$clinic_id));?>" class="icon-add info-tooltip">Add Card</a>
        </span>
        <div class="table-responsive">
         <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
            <tr> 
               <td width="30%" class="aptn">Total Card</td>
               <td width="20%"  class="aptn">Range From</td>
               <td width="20%"  class="aptn">Range To</td>
               <td width="30%"  class="aptn">Date</td>
               
             </tr>
     </thead>
        <tbody>
            <?php
		foreach ($cardlogs as $cardlog){ ?>
            <tr> 
                <td width="20%"><?php echo $cardlog['CardLog']['no_of_card']; ?></td>
                <td width="20%"><?php echo $cardlog['CardLog']['range_from']; ?></td>
                <td width="30%"><?php echo $cardlog['CardLog']['range_to']; ?></td>
                <td width="30%"><?php echo $cardlog['CardLog']['log_date']; ?></td>
            </tr>
      <?php }//Endforeach
	?>
        </tbody>
       </table>
    </div>
     

    </div>

</div>

<script>
 function syncclient(id){
 $('#syn_'+id).html('Please wait..');
 $.ajax({
                    type: "POST",
                    data: "client_id=" + id,
                    dataType: "json",
                    url: "/ClientManagement/sync",
                    success: function(result) {
                        if (result.result== 1) {
                            $('#syn_'+id).html('Sync');
                        }
                        
                    }});
 }
   function cunfdelete(){
	   var r = confirm("Are you sure to delete client?");
if (r == true)
  {
 return true;
  }
else
  {
  return false;
  }
   }
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {

           
      
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
