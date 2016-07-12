<?php 
    
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>
    <div class="contArea Clearfix">
      <div class="tabBox">
        <ul>
            <li><a href="<?php
                echo $this->Html->url(array(
                    "controller" => "ClientManagement",
                    "action" => "index"
                ));
                ?>">Client Management</a></li>

            <li><a href="<?php
                echo $this->Html->url(array(
                    "controller" => "RewardManagement",
                    "action" => "index"
                ));
                ?>">Rewards Management</a></li>
            <li><a href="<?php
                echo $this->Html->url(array(
                    "controller" => "Redeem",
                    "action" => "index"
                ));
                ?>" >Redemption</a></li>
            <li><a href="<?php
                   echo $this->Html->url(array(
                       "controller" => "AdminProfileFieldManagement",
                       "action" => "index"
                   ));
                ?>">Profile Field Management</a></li>
            <li><a href="<?php
                   echo $this->Html->url(array(
                       "controller" => "GlobalUserManagement",
                       "action" => "index"
                   ));
                ?>">Global User Management</a></li>
            <li><a href="<?php
                   echo $this->Html->url(array(
                       "controller" => "IndustryManagement",
                       "action" => "index"
                   ));
                ?>"  class="active">Industry Management</a></li>
                                <li><a href="<?php
                   echo $this->Html->url(array(
                       "controller" => "ContestManagement",
                       "action" => "index"
                   ));
                ?>">Contest Management</a></li>
                
        </ul>
    </div>
     
     <div class="adminsuper">
		 <table border="0" cellpadding="0" cellspacing="0" id="paging-table" class='addOption'>
		
			<tr>
			
			<td>
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "IndustryManagement",
							    "action" => "addpromotion",
                                                            $industryid
							));?>" title="Add Promotion" class="icon-1 info-tooltip">Add Promotion</a>
			</td>
			</tr>
			</table>
			<?php echo $this->Session->flash(); ?>
			<div class="breadcrumb_staff"><a href="<?php echo $this->Html->url(array(
						    "controller" => "IndustryManagement",
							"action"=>"index"
						));?>" class="active">Industry Management</a> >> <b>Manage Promotion</b> </div>
       <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" > 
       <thead>
                        <tr> 
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Description</td>
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Value</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
			
					foreach ($Promotion as $pro)
					{
					
					?>
        <tr> 
          <td width="30%"><?php echo $pro['IndustryPromotion']['description'];?></td>
          <td width="30%"><?php echo $pro['IndustryPromotion']['value'];?></td>
         <td width="15%" class="managementbtn"><a href="<?php echo $this->Html->url(array(
							    "controller" => "IndustryManagement",
							    "action" => "editpromotion",
                                                            $industryid,
							    $pro['IndustryPromotion']['id']
							));?>" title="Edit Promotion" class="edit_icon"></a>
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















