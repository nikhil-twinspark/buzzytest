<?php 
    
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>
		    <div class="contArea Clearfix">
				<div class="page-header">
<h1>
    <i class="menu-icon fa fa-flask"></i>
Goals
</h1>
</div>
     <div class="adminsuper">
         <?php if($CanAdd==0){ ?>
		 <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "StaffRewardProgram",
							    "action" => "add"
							));?>" title="Create Goal" class="icon-1 info-tooltip">Create Goal</a>
                 </span>
         <?php  }
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Goal Name</td>
                            <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Goal Type</td>
                            <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
				
					foreach ($Goals as $Goal)
					{
					
					?>
        <tr> 
         
          <td width="50%"><?php echo $Goal['Goal']['goal_name'];?></td>
          <td width="25%" ><?php if($Goal['Goal']['goal_type']==1){ echo 'Engagement'; }else{ echo "Promotion"; } ?></td>
          
          <td width="25%" class="editbtn response_btn">
              <a title="Edit" href="<?= Staff_Name ?>StaffRewardProgram/edit/<?php echo $Goal['Goal']['id'] ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a> 
               <a title="Delete" href="<?= Staff_Name ?>StaffRewardProgram/delete/<?php echo $Goal['Goal']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
            
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

   


















