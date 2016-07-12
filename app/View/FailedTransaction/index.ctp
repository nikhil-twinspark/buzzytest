<?php 
    
    $sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>
    <div class="contArea Clearfix">
      <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-ban"></i> Failed Transaction
        </h1>
    </div>
    <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
    ?>
        
     <div class="adminsuper">
          
	
		
       <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
                        <tr> 
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Practice</td>
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Payment Gateway Reason</td>
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Action</td>
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Date</td>
              
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
					foreach ($FailedTransaction as $FTransaction)
					{

					?>
        <tr> 
          <td width="25%"><?php echo $FTransaction['Clinic']['api_user'];?></td>
                                       
         <td width="25%"><?php echo $FTransaction['FailedPayment']['subject'];?></td>
         <td width="25%"><?php echo $FTransaction['FailedPayment']['description'];?></td>
         <td width="25%"><?php echo $FTransaction['FailedPayment']['date'];?></td>
         
   
         			
		
        </tr>
      <?php 	
					}//Endforeach
				 ?>
        </tbody>
       </table>

   
			
     </div>
     
   </div>
   </div><!-- container -->
   
   
 <script>


   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {

           
      
    "aaSorting": [[ 3, "desc" ]],
        
     
              
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















