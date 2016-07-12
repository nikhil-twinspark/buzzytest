<?php 
    $sessionAdmin = $this->Session->read('Admin'); 
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    //echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    
   // echo $this->Html->css(CDN.'css/facebox.css');
   // echo $this->Html->script(CDN.'js/faceBox/facebox.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
?>

<div class="contArea Clearfix">
      <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-envelope"></i> Email Management
        </h1>
    </div>
    <?php 
      //echo $this->element('messagehelper'); 
      echo $this->Session->flash('good');
      echo $this->Session->flash('bad');
  ?>
    
    
    <div class="adminsuper">
<!--            <span style='float:right;' class="add_rewards" >
                <a href="<?php echo $this->Html->url(array("controller"=>"EmailManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Email Template</a>
            </span>-->
         <div class="table-responsive">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
			
			
                            <td width="60%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Email For</td>
                            <td width="40%" class="client sorting" aria-label="Domain: activate to sort column ascending">Action</td>
                         </tr>
                 </thead>
                 <tbody></tbody>
               
                
         </table>
         </div>
     

    </div>

</div>



    
  

</div><!-- container -->

<script>

	

	
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {
			"aoColumnDefs": [
				{ 
					"bSortable": false, "aTargets": [ 1 ],
				}
				
			],
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "/EmailManagement/getEmail"
       
		} );
       
    });
    

</script>
