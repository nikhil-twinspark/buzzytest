<?php 
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
	$sessionstaff = $this->Session->read('staff');
?>

		    <div class="contArea Clearfix">
<div class="page-header">
<h1>
    <i class="menu-icon fa fa-gift"></i>
Balance Status
</h1>
</div>

     <div class="adminsuper">
		
  <?php  //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="product_service_table" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Account Number</td>
                          
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Amount</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Status</td>
                       
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
				
					foreach ($BeanstreamPayment as $value)
					{
					
					?>
        <tr> 
          <td width="30%"><?php echo $value['BeanstreamPayment']['account_number'];?></td>
         
		  <td width="20%"><?php echo $value['BeanstreamPayment']['points']/100;?> $</td>	
                  <td width="20%"><?php echo $value['BeanstreamPayment']['date'];?></td>
                  <td width="20%"><?php echo $value['BeanstreamPayment']['status'];?></td>
		 
		  						
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


    $(document).ready( function () {
        var initDatatable = function(){
	     var dataGrid =    $('#product_service_table').dataTable( {
				"bDestroy" : true,
				"bRetrieve" : true,
			   	"aaSorting": [],
			    "sPaginationType": "full_numbers",
			});
        }
        initDatatable();
    });
</script>
















