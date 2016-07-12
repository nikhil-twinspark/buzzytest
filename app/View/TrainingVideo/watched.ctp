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
            <i class="menu-icon fa fa-pencil-square-o"></i> Training Video
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
                            <td width="33%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Staff</td>
                            <td width="33%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Video</td>
                            <td width="33%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Watched Date</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
					foreach ($watchlist as $watched)
					{

					?>
        <tr> 
          <td width="30%"><?php echo $watched['Staff']['staff_id'];?></td>
          <td width="30%"><?php echo $watched['TrainingVideo']['title'];?></td>
          <td width="30%"><?php echo $watched['WatchList']['watched_on'];?></td>
                                       

   
         			
		
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

           
      
    "aaSorting": [[ 2, "desc" ]],
        
     
              
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















