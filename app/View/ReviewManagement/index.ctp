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
Doctor-to-Doctor Reviews
</h1>
</div>
     <div class="adminsuper">
         <?php if ($sessionstaff['is_lite'] != 1) { ?>
		 <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "ReviewManagement",
							    "action" => "add"
							));?>" title="Edit" class="icon-1 info-tooltip">Add Review</a>
                 </span>
         <?php  }
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Review For</td>
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Reviewed By</td>
                            <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Review</td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
				
					foreach ($Reviews as $Review)
					{
					
					?>
        <tr> 
         <td width="25%"><?php echo $Review['clinics']['api_user'];?></td>
          <td width="25%"><?php echo $Review['doctors']['first_name'].' '.$Review['doctors']['last_name'];?></td>
          <td width="40%" ><?php echo $Review['RateReview']['review'];?></td>
          
          <td width="10%" class="editbtn response_btn">              
              <a title="Delete" href="<?= Staff_Name ?>ReviewManagement/delete/<?php echo $Review['RateReview']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
             
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
















