<?php 
    
    $sessionstaff = $this->Session->read('staff'); 
    
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
              <i class="menu-icon fa fa-list-ul"></i> Global Users
          </h1>
      </div>
     <div class="adminsuper">
		
			
	<div class="table-responsive">		
       <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
       <thead>
                        <tr> 
							
			    <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Card Number</td>
			    <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Clinic Name</td>
                            <td width="10%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Points Earn</td>
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Points Redeemed</td>
                            <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Action</td>

                        
                         </tr>
                 </thead>
                 <tbody>
        <?php 
      
					foreach ($users as $user)
					{
					
					?>
        <tr> 
          <td width="15%"><?php echo $user['clinic_users']['card_number'];?></td>
          <td width="25%"><?php echo $user['clinics']['api_user'];?></td>
          <td width="15%"><?php if($user['allocatepoint']!=''){ echo $user['allocatepoint'];}else{ echo "0";}?></td>
          <td width="20%"><?php if($user['redeemedpoint']!=''){ echo ltrim($user['redeemedpoint'],'-');}else{ echo "0";}?></td>
         <td width="15%" ><a href="<?php echo $this->Html->url(array(
							    "controller" => "GlobalUserManagement",
							    "action" => "viewprofile",
							    $user['User']['id'].'-'.$user['clinic_users']['clinic_id'].'-'.$user['clinic_users']['card_number']
							));?>" title="View Profile">View Profile</a>
         <?php if($user['clinic_users']['card_number']!='' && $user['User']['id']!=''){
         
            $userid=rtrim($user['clinics']['patient_url'], '/')."/rewards/login/".base64_encode('redeem')."/".base64_encode($user['clinic_users']['card_number'])."/".base64_encode($user['User']['id']);
            ?>
            <a title='Login To Patient' href='<?php echo $userid; ?>' target='_blank' class='btn btn-xs btn-info'>
                <?php
            }else{ 
                ?>
             <a title='Login To Patient' href='javascript:void(0)' class='btn btn-xs btn-info'>
           <?php } ?>
            
            <i class='ace-icon fa fa-fighter-jet'></i>
            </a>
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










