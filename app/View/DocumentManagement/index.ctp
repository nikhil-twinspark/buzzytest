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
    <i class="menu-icon fa fa-book"></i>
Documents
 <!--
<small>
   
<i class="ace-icon fa fa-angle-double-right"></i>
Draggabble Widget Boxes & Containers
</small>
    -->
</h1>
</div>
                        <div style="color:red;font-size: 12px;">
                            Upload the important documents and forms that you want to make available to your patients.
                            
                        </div>
     <div class="adminsuper">
		<span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "DocumentManagement",
							    "action" => "add"
                            ));?>" title="Edit" class="icon-1 info-tooltip">Add Document</a></span>
			
			<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="10%" class="client sorting" aria-label="Domain: activate to sort column ascending" >#</td>
                            <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Title <span style="font-style: italic;">(as seen by patients)</span></td>
                            <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Document <span style="font-style: italic;">(as uploaded)</span></td>
                            <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Delete</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
        <?php 
				
					$s=1;
					foreach ($Documents as $Document)
					{
					
					?>
        <tr> 
          <td width="10%"><?php echo $s;?></td>
          <td width="40%"><?php echo $Document['Document']['title'];?></td>
          <td width="40%" ><?php
          $doc=explode('/',$Document['Document']['document']);
          $doc=end($doc);
          
           ?>
           <a href="<?php echo $Document['Document']['document']; ?>" target="_blank"><?php echo $doc; ?></a>
           </td>
          
        
							<td width="10%" class="editbtn response_btn">
                                                        <a title="Delete" href="<?= Staff_Name ?>DocumentManagement/delete/<?php echo $Document['Document']['id'] ?>"   class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
                                                        </td>
        </tr>
      <?php 	
					$s++;}//Endforeach
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
   


















