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
    <i class="menu-icon fa fa-flask"></i>
Goal Settings
</h1>
</div>
     <div class="adminsuper">
       
		 <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "StaffRewardProgram",
							    "action" => "addsetting"
							));?>" title="Create Goal Setting" class="icon-1 info-tooltip">Set Goal</a>
                 </span>
         <?php  
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Goal Name</td>
                            <td width="25%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Goal For</td>
                            <td width="25%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Target (Weekly)</td>
                            <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                            
                           
                         </tr>
                 </thead>
                 
                 <tbody>
     
        <?php 
				
					foreach ($GoalSettings as $GoalSetting)
					{
					
					?>
        <tr> 
         
          <td width="25%"><?php echo $GoalSetting['Goal']['goal_name'];?></td>
          <td width="25%" ><?php if($GoalSetting['GoalSetting']['staff_id']>0){ echo $GoalSetting['Staff']['staff_id']." (Staff User)"; }else{ echo $GoalSetting['Clinic']['api_user']." (Practice)"; } ?></td>
          <td width="25%" id="setting_<?php echo $GoalSetting['GoalSetting']['id'] ?>"><?php echo $GoalSetting['GoalSetting']['target_value'];?></td>
          <td width="25%" class="editbtn response_btn">
              
              <a href="javascript:void(0)" title="Edit" class="btn btn-xs btn-info" id="id-btn-dialog1" onclick="settarget(<?php echo $GoalSetting['GoalSetting']['id'] ?>,<?php echo $GoalSetting['GoalSetting']['target_value'];?>,'<?php if($GoalSetting['GoalSetting']['staff_id']>0){ echo $GoalSetting['Staff']['staff_id']." (Staff User)"; }else{ echo $GoalSetting['Clinic']['api_user']." (Practice)"; } ?>');"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
              <a title="Delete" href="<?= Staff_Name ?>StaffRewardProgram/deletesetting/<?php echo $GoalSetting['GoalSetting']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
            
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
    <div id="dialog-message" class="hide">
       <form id="settingform_foradmin">
			<div class="innermsg">
                            
                            <div id="option" class="inneropt">
                                <input type="hidden" id='goal_settung_id' name="goal_settung_id" value="">
                                <label>Target Value</label><input type="text" id='target_value' name="target_value" value="" placeholder="Target Value" required="required" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')">
                                       
                                </div>
                           
			</div>
			
                        
                        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                                <input type="button" value="Set" id='change_btn' class="btn btn-primary btn-xs">
               
                </div></div>
                        <div id='status_error' style="color: #FF0000;"></div>
                        </form>
    </div><!-- #dialog-message -->
   <script>
    $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {

           "columnDefs": [ { "targets": 0, "orderable": false },{ "targets": 1, "orderable": false } ],
      
    "aaSorting": [[ 0, "asc" ]],
        
     
              
                "sPaginationType": "full_numbers",
                
               
                
} );
        $('#example').dataTable().columnFilter({
            sPlaceHolder: "head:before",
                aoColumns: [ { type: "select" },
                             { type: "select" },
                             
                            
                             null,
                           
                             null
                        ]

        });
    });
                                    function settarget(id,target,goal_for){
                                        $('#target_value').val(target);
                                        $('#goal_settung_id').val(id);
					var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
						modal: true,
						title: "Set Target for "+goal_for,
						title_html: true,
                                              
						
					});
			
				
    }
    
    $("#change_btn").click(function(){
                   
                   var target_value= $('#target_value').val();
                   if(target_value<1){
                    $("#status_error").html('Target Value should be greater than zero');   
                   }else{
                   var setting_id= $('#goal_settung_id').val();
                    $.ajax({
                   type: "POST",
                   dataType: "json",
                   url: "<?php echo Staff_Name ?>StaffRewardProgram/updatesetting/",
                   data: {id: setting_id, target_value: target_value},
                   success: function(msg) {
                        if(msg==1){
			$('#setting_'+setting_id).text(target_value);
                        $("#status_error").html('Targer Updated successfully.');
                    }else{
                        $("#status_error").html('try Again Leter.');
                    }
                   }
               
               }); 
               }
    
    });
   
</script>

   


















