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
    <i class="menu-icon fa fa-users"></i>
Staff
</h1>
</div>
<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>

<div style="text-align: right;">*Note: Administrators can add/remove other managers.</div>
<?php if($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator' || $sessionstaff['staff_role']=='Doctor'){ ?>
<div class="add_profile"><span class="add_rewards" style="float:right;"><a href="<?php echo $this->Html->url(array(
							    "controller" => "UserStaffManagement",
							    "action" => "add"
        ));?>" title="Edit" class="icon-add info-tooltip">Add Staff</a></span></div> <?php } ?>
    <div class="adminsuper">
        <div class="table-responsive">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
                            <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >User Name</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">User Role</td>
                            <td width="60%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                            
                           
                         </tr>
                 </thead>
                 <tbody>
                 <?php 
             
                 foreach ($Staffs as $Staff){
                
                                                  
                     ?>
                         <tr> 
                              
                           
                            <td width="20%"><?php echo $Staff['Staff']['staff_id'];?></td>
                            <td width="20%"><?php if($Staff['Staff']['staff_role']=='A' || $Staff['Staff']['staff_role']=='Administrator'){ echo "Administrator";}else if($Staff['Staff']['staff_role']=='D' || $Staff['Staff']['staff_role']=='Doctor'){ echo "Super Doctor";}else{ echo "Manager"; } ?></td>
                            <td width="60%">
								
								<?php if($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator'|| $sessionstaff['staff_role']=='Doctor'){ 
								if($Staff['Staff']['id']==$sessionstaff['staff_id']){
									?>
									<a  class="btn btn-xs btn-info" title="Edit" href="<?= Staff_Name ?>UserStaffManagement/edit/<?php echo $Staff['Staff']['id'] ?>" ><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
                                                                        <a  class="btn btn-xs btn-danger"><i class='ace-icon grey glyphicon glyphicon-trash'></i></a>
									<?php
								}
                                                                else if($Staff['Staff']['id']!=$sessionstaff['staff_id'] && ($Staff['Staff']['staff_role']=='A' || $Staff['Staff']['staff_role']=='Administrator')){
									?>
									<a  class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil grey'></i></a>
                                                        <a title="Delete"  class="btn btn-xs btn-danger"><i class='ace-icon grey glyphicon glyphicon-trash'></i></a>
									<?php
								}
                                                                else{ ?>
							<a  class="btn btn-xs btn-info" title="Edit" href="<?= Staff_Name ?>UserStaffManagement/edit/<?php echo $Staff['Staff']['id'] ?>" ><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
							<a title="Delete" href="<?= Staff_Name ?>UserStaffManagement/delete/<?php echo $Staff['Staff']['id'] ?>"   class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
							<?php }}else{
								 if($Staff['Staff']['id']==$sessionstaff['staff_id']){
									?>
									<a title="Edit" href="<?= Staff_Name ?>UserStaffManagement/edit/<?php echo $Staff['Staff']['id'] ?>"  class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
							 
									<?php
								}else{ ?>
							<a  class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil grey'></i></a>
							
							<?php }
							} if($sessionstaff['staff_role']=='A' || $sessionstaff['staff_role']=='Administrator' || $sessionstaff['staff_role']=='Doctor'){ ?>
                                                        Receive Appointments <input type='checkbox' name="appointment_<?php echo $Staff['Staff']['id'] ?>" id="appointment_<?php echo $Staff['Staff']['id'] ?>" <?php if($Staff['Staff']['is_prime']==1){ echo "checked"; }?>>
                                                        Get Redemption Email <input type='checkbox' name="redemption_mail_<?php echo $Staff['Staff']['id'] ?>" id="redemption_mail_<?php echo $Staff['Staff']['id'] ?>" <?php if($Staff['Staff']['redemption_mail']==1){ echo "checked"; }?>>
                                                        Get Report Email <input type='checkbox' name="report_mail_<?php echo $Staff['Staff']['id'] ?>" id="report_mail_<?php echo $Staff['Staff']['id'] ?>" <?php if($Staff['Staff']['report_mail']==1){ echo "checked"; }?> onclick="getredeemmail(<?php echo $Staff['Staff']['id'] ?>)">
                                                        Get Review Email <input type='checkbox' name="review_mail_<?php echo $Staff['Staff']['id'] ?>" id="review_mail_<?php echo $Staff['Staff']['id'] ?>" <?php if($Staff['Staff']['review_mail']==1){ echo "checked"; }?> onclick="getreviewmail(<?php echo $Staff['Staff']['id'] ?>)">
                                                        Active <input type='checkbox' name="set_active_<?php echo $Staff['Staff']['id'] ?>" id="set_active_<?php echo $Staff['Staff']['id'] ?>" <?php if($Staff['Staff']['active']==1){ echo "checked"; }?> onclick="setactive(<?php echo $Staff['Staff']['id'] ?>)">
                                                        <?php } ?>
							</td>
                            
                         </tr> 
                         
                 <?php }  ?>  
                </tbody>
                <tfoot>
                        <tr>
                            <td width="15%" class="client"  >Name</td>
                            <td width="15%" class="campaign">Type</td>
                            <td width="20%" class="aptn" >&nbsp;</td>
                           
                        </tr>
                </tfoot>
                
         </table>

        </div>

    </div>

</div>




<script>
      
      function user_search(id){
       $( "#user_"+id ).submit();
      }
     function changeStatusRedeem(redeem_id){
            $('#redeem_status').val('');
            $("#id").val(redeem_id);

            $("#dialog").dialog();
    }
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {
"columnDefs": [ { "targets": 2, "orderable": false } ],
           
      
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
    
    $('input[id^="redemption_mail_"]').on('change', function() {
    	$('input[id^="redemption_mail"]').not(this).prop('checked', false);
    	var id = $(this).attr('id').replace('redemption_mail_','');
    	if(!$(this).is(':checked')){
    		id=0;
    	}
    	   $.ajax({
            type: "POST",
            url: "/UserStaffManagement/setredeemnotification/",
            data: "&staff_id=" + id,
            success: function(msg) {
                   
            }
        });
    	  
	});
	
	 $('input[id^="appointment_"]').on('change', function() {
    	$('input[id^="appointment_"]').not(this).prop('checked', false);
    	var id = $(this).attr('id').replace('appointment_','');
    	if(!$(this).is(':checked')){
    		id=0;
    	}
    	   $.ajax({
            type: "POST",
            url: "/UserStaffManagement/setprime/",
            data: "&staff_id=" + id,
            success: function(msg) {
                   
            }
        });
    	  
	});
function setactive(id) {
        $.ajax({
            type: "POST",
            url: "/UserStaffManagement/setactive/",
            data: "&staff_id=" + id,
            success: function(msg) {
                
            }
        });
    }
    function getredeemmail(id) {
        $.ajax({
            type: "POST",
            url: "/UserStaffManagement/setreportnotification/",
            data: "&staff_id=" + id,
            success: function(msg) {
                
            }
        });
    }
    function getreviewmail(id) {
        $.ajax({
            type: "POST",
            url: "/UserStaffManagement/setreviewnotification/",
            data: "&staff_id=" + id,
            success: function(msg) {
                
            }
        });
    }
</script>
