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
Doctors
 <!--
<small>
   
<i class="ace-icon fa fa-angle-double-right"></i>
Draggabble Widget Boxes & Containers
</small>
    -->
</h1>
</div>
     <div class="adminsuper">
		 <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "Doctor",
							    "action" => "add"
							));?>" title="Add Doctor" class="icon-1 info-tooltip">Add Doctor</a>
                 </span>
			 <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Doctor Name</td>
                            <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Email</td>
                            <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
				
					foreach ($Doctors as $Doctor)
					{
					
					?>
        <tr> 
         
          <td width="50%"><?php echo $Doctor['Doctor']['first_name'].' '.$Doctor['Doctor']['last_name'];?></td>
          <td width="25%" ><?php echo $Doctor['Doctor']['email'];?></td>
          
          <td width="25%" class="editbtn response_btn">
              <a title="Edit" href="<?= Staff_Name ?>Doctor/edit/<?php echo $Doctor['Doctor']['id'] ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a> 
               <a title="Delete" href="<?= Staff_Name ?>Doctor/delete/<?php echo $Doctor['Doctor']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
               set as default <input type='checkbox' name="<?php echo $Doctor['Doctor']['id'] ?>" id="<?php echo $Doctor['Doctor']['id'] ?>" <?php if($Doctor['Doctor']['default']==1){ echo "checked"; }?> onclick="setprime(<?php echo $Doctor['Doctor']['id']; ?>)">
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
          function setprime(id) {

      var getchk= $('#'+id)[0].checked;
      if(getchk==false){
          id=0;
      }
        $.ajax({
            type: "POST",
            url: "/Doctor/setprime/",
            data: "&doctor_id=" + id,
            success: function(msg) {
                    <?php
                   
                  foreach ($Doctors as $Doctor)
                                     { ?>
                if (id ==<?php echo $Doctor['Doctor']['id']; ?>) {
                    $("#<?php echo $Doctor['Doctor']['id']; ?>").attr("checked", true);
                } else {
                    $("#<?php echo $Doctor['Doctor']['id']; ?>").attr("checked", false);
                }
                                     <?php } ?>
            }
        });



    }
   
</script>

   


















