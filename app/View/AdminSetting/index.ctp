<?php 
    
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>
		    <div class="contArea Clearfix">
			<div class="page-header">
<h1>
    <i class="menu-icon fa fa-cog"></i>
Referral Levels
 <!--
<small>
   
<i class="ace-icon fa fa-angle-double-right"></i>
Draggabble Widget Boxes & Containers
</small>
    -->
</h1>
</div>	
     <div class="adminsuper">
		 <table border="0" cellpadding="0" cellspacing="0" id="paging-table" class='addOption'>
		 
			<tr>
			<td>
                            <a href="javascript:void(0)" title="Edit" class="icon-1 info-tooltip adminsetting" id="id-btn-dialog1">Set Local Referral Points</a>
                       
			</td>
                        
			</tr>
			</table>
			<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Industry Name</td>
                            <td width="35%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Referral Level</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Default Referral Points</td>
                            
                      <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Local Referral Points</td>              
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
        
        $settings=array();
                                if(!empty($admin_settings)){
                                    if($admin_settings['AdminSetting']['setting_data']!=''){
                                      $settings=json_decode($admin_settings['AdminSetting']['setting_data']); 
                                    }
                                }
				if(!empty($leads)){
					foreach ($leads as $lead)
					{
					$point1='';
                                    foreach($settings as $set =>$setval){
                                       if($set==$lead['lead_levels']['id']){
                                         $point1=$setval;
                                       }
                                    }
                                    
					?>
        <tr> 
         
          <td width="25%"><?php echo $lead['IndustryType']['name'];?></td>
          <td width="35%" ><?php echo $lead['lead_levels']['leadname'];?></td>
          <td width="20%" ><?php echo $lead['lead_levels']['leadpoints'];?></td>
          <td width="20%" id="<?php echo $lead['lead_levels']['id'];?>_local"><?php if($point1!=''){ echo $point1; }else{ echo "NA"; }?></td>
							
        </tr>
      <?php 	
					}
                                }else{
?>
         <tr> 
         
             <td colspan="4">No record found!</td>
							
        </tr>
        <?php
//Endforeach
                                }
				 ?>
                 </tbody>
       </table>
     
         </div>		
     </div>
     
   </div>
  


<div id="dialog-message" class="hide">
       <form id="settingform_foradmin">
			<div class="innermsg">
                            
                            <div id="option" class="inneropt">
                                <?php 
                                
                              
                                foreach ($leads as $lead)
					{ 
                                    $point='';
                                    foreach($settings as $set =>$setval){
                                       if($set==$lead['lead_levels']['id']){
                                         $point=$setval;
                                       }
                                    }
                                    ?>
                                <label><?=$lead['lead_levels']['leadname']?></label><input type="text" id='<?=$lead['lead_levels']['id']?>' name="<?=$lead['lead_levels']['id']?>" value="<?=$point?>" placeholder="Local Referral Points" required="required" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')">
                                        <?php } ?>
                                </div>
                            <div id="option" class="inneropt">
                                
                                    <input type="checkbox" id='allow' name="allow" <?php if(isset($settings->allow)){ echo "checked";} ?>>Allow To Modify
                                
                                </div>
			</div>
			
                        
                        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                                <input type="button" value="Set" id='change_btn' class="btn btn-primary btn-xs">
               
                </div></div>
                        <div id='status_error' style="color: #FF0000;"></div>
                        </form>
    </div><!-- #dialog-message -->



    <!-----end here------> 
   <script>
       $( "#id-btn-dialog1" ).on('click', function(e) {
					e.preventDefault();
			
					var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
						modal: true,
						title: "Set Local Referral Points",
						title_html: true,
                                              
						
					});
			
				});
       function changeStatusRedeem(){
            $("#dialog").dialog();
            
    }
    $("#change_btn").click(function(){

        <?php foreach ($leads as $lead)
					{ ?>
        if($('#'+<?=$lead['lead_levels']['id']?>).val()==''){
            $("#status_error").html("Please enter referral points");
        }
        <?php } ?>
       
                        else{
                   
                    datasrc_set=$( "#settingform_foradmin" ).serialize();
                    $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "<?php echo Staff_Name ?>AdminSetting/changesetting/",
                   data: datasrc_set,
                   success: function(msg) {
			if(msg.val==''){
                         $("#status_error").html(msg.msg);
                     }else{
                          <?php foreach ($leads as $lead)
					{ ?>
                            
                                 $("#"+<?=$lead['lead_levels']['id']?>+'_local').text(msg.val[<?=$lead['lead_levels']['id']?>]); 
                               <?php }  ?>
                       $("#status_error").html(msg.msg);
                     }
                   }
               }); 
           }
    
    });
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

   















