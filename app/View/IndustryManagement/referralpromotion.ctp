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
                <i class="menu-icon fa fa-cubes"></i> Industries
            </h1>
        </div>
        
          <?php 
            //echo $this->element('messagehelper'); 
            echo $this->Session->flash('good');
            echo $this->Session->flash('bad');
        ?>
     
     <div class="adminsuper">
          <span style='float:right;' class="add_rewards" >
                <a href="<?php echo $this->Html->url(array(
							    "controller" => "IndustryManagement",
							    "action" => "addreferralpromotion",
                                                            $industryid
							));?>" title="Add Referral Promotion" class="icon-add info-tooltip">Add Referral Promotion</a>
            </span>
		
	 <div class="table-responsive">
       <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover" > 
       <thead>
                        <tr> 
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Referral Promotion Name</td>
                            
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Action</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
			
					foreach ($ReferralPromotion as $pro)
					{
					
					?>
        <tr> 
          <td width="30%"><?php echo $pro['Refpromotion']['promotion_name'];?></td>
        
         <td width="15%" class="">
             <a href="<?php echo $this->Html->url(array(
                "controller" => "IndustryManagement",
                "action" => "editreferralpromotion",
                $industryid,
                $pro['Refpromotion']['id']
            ));?>" title="Edit Referral Promotion" class="btn btn-xs btn-info"><i class="ace-icon glyphicon glyphicon-pencil"></i></a>

            &nbsp; <a class="btn btn-xs btn-danger" title="Delete Referral Promotion" href="<?php echo $this->Html->url(array(
                "controller" => "IndustryManagement",
                "action" => "deletereferralpromotion",
                $industryid,
                $pro['Refpromotion']['id']
                    ));?>"  title="Delete Referral Promotion"><i class="ace-icon glyphicon glyphicon-trash"></i></a>
                    set as default <input type='checkbox' name="<?php echo $pro['Refpromotion']['id'] ?>" id="<?php echo $pro['Refpromotion']['id'] ?>" <?php if($pro['Refpromotion']['dafault']==1){ echo "checked"; }?> onclick="setprime(<?php echo $pro['Refpromotion']['id']; ?>,<?php echo $industryid; ?>)">
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
    
    
     function setprime(id,ind_id) {



        $.ajax({
            type: "POST",
            url: "/IndustryManagement/setprime/",
            data: "&refpro_id=" + id+"&ind_id="+ind_id,
            success: function(msg) {
                    <?php
                   
                  foreach ($ReferralPromotion as $Location)
                                     { ?>
                if (id ==<?php echo $Location['Refpromotion']['id']; ?>) {
                    $("#<?php echo $Location['Refpromotion']['id']; ?>").attr("checked", true);
                } else {
                    $("#<?php echo $Location['Refpromotion']['id']; ?>").attr("checked", false);
                }
                                     <?php } ?>
            }
        });



    }
   </script>















