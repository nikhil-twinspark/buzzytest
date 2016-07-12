<?php 
    $sessionAdmin = $this->Session->read('Admin'); 
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
            <i class="menu-icon fa fa-users"></i> Contest
        </h1>
    </div>
    <?php 
      //echo $this->element('messagehelper'); 
      echo $this->Session->flash('good');
      echo $this->Session->flash('bad');
  ?>
    
    
    <div class="adminsuper">
            <span style='float:right;' class="add_rewards" >
                <a href="<?php echo $this->Html->url(array("controller"=>"ContestManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Contest</a>
            </span>
         <div class="table-responsive">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
			
			
                            <td width="60%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Contest Name</td>
                            <td width="40%" class="client sorting" aria-label="Domain: activate to sort column ascending">Action</td>
                         </tr>
                 </thead>
                 <tbody></tbody>
               
                
         </table>
         </div>
     

    </div>

</div>



<!-------dialog start here----------->
		<div id="dialog" title="Assign To Practice" style="display:none;">
                    <form id="settingform_foradmin" style="height: 200px;
overflow: auto;
margin-bottom: 10px;">
			<div >
                            <input type="hidden" id='cid' name="cid">
                             <?php 
                                
                              
                                foreach ($clinics as $clinic)
								{ 
                                    
                                 ?>
                                <div id="option">
                               
                                    <input type="checkbox" id='clinic<?=$clinic['Clinic']['id']?>' name="clinicid[]" value="<?=$clinic['Clinic']['id']?>" style="display: inline-block;
width: auto;
margin-right: 5px;"><?=$clinic['Clinic']['api_user']?>
                           
                                </div>
                                  <?php } ?>
			</div>
						
                        </form>
                        <div class="assign_btn">
                        <div><input type="button" value="Assign" id='assign_btn'></div>
                        <div id='status_error' style="color: #FF0000;font-size: 12px;">&nbsp;</div>
                        </div>
		</div>
    <!-----end here------> 
    
  

</div><!-- container -->

<div class="ui-widget-overlay ui-front" id="backgrey" style="display: none;"></div>
<script>

	

    
function assignclinic(contest_id){
	<?php foreach ($clinics as $clinic){ ?>
		$("#clinic"+<?php echo $clinic['Clinic']['id']; ?>).prop('checked', false);
		<?php } ?>
		$("#status_error").html("");
           $.ajax({
                   type: "POST",
                   url: "<?php echo Staff_Name ?>ContestManagement/getlist/",
                   data: "&contest_id="+contest_id,
                   dataType: "json",
                   success: function(msg) {
					   for (i = 0; i < msg.length; i++) {
						$("#clinic"+msg[i].clinic_id).prop('checked', true);
						
						}
					  
                   }
               }); 
            $("#cid").val(contest_id);

            $("#dialog").dialog();
            $('#backgrey').css('display', 'block');
    }
    
    $("#assign_btn").click(function(){
        $('input[type="button"]').attr('disabled','disabled');
       
			datasrc=$( "#settingform_foradmin" ).serialize();
            $.ajax({
                   type: "POST",
                   url: "<?php echo Staff_Name ?>ContestManagement/assignclinic/",
                   data: datasrc,
                   success: function(msg) {
                               $('input[type="button"]').removeAttr('disabled');
								$("#status_error").html("Contest successfully assign to practice.");
                   }
               }); 
           
    
    });
	
   $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
       $('#example').dataTable( {
			"aoColumnDefs": [
				{ 
					"bSortable": false, "aTargets": [ 1 ],
				}
				
			],
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "/ContestManagement/getContest"
       
		} );
       
    });
    

    
    function showAllRedeemStatus(redeem_id){
        $("#"+redeem_id+"_dropdown_redeem_div").show();
        $("#"+redeem_id+"_input_redeem_div").hide();
    }
$(document).on('click',".ui-button", function(){
        
        $('#status_error').text('');
        $('#backgrey').css('display', 'none');
    });
</script>
