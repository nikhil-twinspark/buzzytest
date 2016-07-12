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
            <i class="menu-icon fa fa-key"></i> Badges
        </h1>
    </div>
    <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
    ?>
        
     <div class="adminsuper">
            <span style='float:right;' class="add_rewards" >
                <a href="<?php echo $this->Html->url(array("controller"=>"BadgesManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Badges</a>
            </span>
	
		
       <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
                        <tr> 
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Badge Name</td>
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Value</td>
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Type</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
					foreach ($Badge as $Badges)
					{
                        //pr($Badges);
					
					switch ($Badges['Badge']['type']) {
						case 1:
							$type= 'Global Promotion Badge';
						break;
						
						case 2:
							$type= 'Combo Badge';
						break;
						default:
						$type = 'Normal Badge';
					}
					?>
        <tr> 
          <td width="30%"><?php echo $Badges['Badge']['name'];?></td>
                                        <td width="30%"><?php  if($Badges['Badge']['value']>0){ echo $Badges['Badge']['value'];}else{ echo 'NA'; } ?></td>
          <td width="30%"><?php echo $type;?></td>
         <td width="15%" class="managementbtn">
             <?php if($Badges['Badge']['type']==1){ ?>
             <a href="javascript:void(0)" title="Edit Badges" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil grey'></i></a>
                                                         | <a title="Delete" href="javascript:void(0)"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash grey'></i></a>
             <?php }else{ ?>
               <a href="<?php echo $this->Html->url(array(
							    "controller" => "BadgesManagement",
							    "action" => "edit",
							    $Badges['Badge']['id']
							));?>" title="Edit Badges" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
                                                         | <a title="Delete" href="<?= Staff_Name ?>BadgesManagement/delete/<?php echo $Badges['Badge']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>                                             
             <?php } ?>                                            
       </td>
   
         			
		
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

           
      
    "aaSorting": [[ 1, "asc" ]],
        
     
              
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















