<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-bell"></i>
            Notifications
        </h1>
    </div>
    <div class="adminsuper">
         <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="70%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Notifications</td>
                        <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Date</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($notifications as $allnot)
					{
					if($allnot['ClinicNotification']['status']==0){
                                            $cls='bg-success';
                                        }else{
                                            $cls='';
                                        }
					?>
                    <tr> 
                        <td width="70%" class="<?php echo $cls; ?>"><?php  
                                    $detailnot=json_decode($allnot['ClinicNotification']['details']);
                                    if($allnot['ClinicNotification']['notification_type']==1){  ?>
                            <a href="<?php echo $this->Html->url(array("controller" => "StaffRedeem","action" => "index",$allnot['ClinicNotification']['id']));?>" title="Redeem" class="icon-1 info-tooltip Doc">
				<?php echo $detailnot->first_name.' '.$detailnot->last_name.' redeemed '.$detailnot->authorization; ?>
                            </a>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==2){
                                        
                                                                       ?>
                            <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "PatientRateReview",
                                                                    "action" => "index",$allnot['ClinicNotification']['id']
                                                                    ));?>" title="Reviews" class="icon-1 info-tooltip Doc">
										<?php echo $detailnot->first_name.' '.$detailnot->last_name.' has just reviewed the clinic on different social platform.'; ?>
                            </a>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==3){
                                        
                                                                       ?>
                            <a href="<?php echo $this->Html->url(array(
                                                                    "controller" => "LeadManagement",
                                                                    "action" => "index",$allnot['ClinicNotification']['id']
                                                                    ));?>" title="Referrals" class="icon-1 info-tooltip Doc">

                                <span class="msg-body">
                                    <span class="msg-title">

                                                                                        <?php echo $detailnot->referrer.' referred '.$detailnot->first_name.' '.$detailnot->last_name; ?>
                                    </span>


                                </span>
                            </a>

                                    <?php }if($allnot['ClinicNotification']['notification_type']==4){
                                        
                                                                       ?>
                            <a href="<?php echo $detailnot->link;?>" target="_blank" onclick="readnotification1(<?php echo $allnot['ClinicNotification']['id']; ?>);" class="icon-1 info-tooltip Doc">

                                <span class="msg-body">
                                    <span class="msg-title">

                                                                                       <?php echo $detailnot->title; ?>
                                    </span>


                                </span>
                            </a>

                                    <?php }?></td>
                        <td width="30%" class="<?php echo $cls; ?>"><?php echo $allnot['ClinicNotification']['date'];?></td>

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
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aaSorting": [[1, "desc"]],
            "sPaginationType": "full_numbers",
        });
        $('#example').dataTable().columnFilter({
            aoColumns: [{type: "text"},
                {type: "select"},
                {type: "text"},
                {type: "number"},
                null,
                {type: "text"},
                null
            ]

        });
    });

    function readnotification1(id) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Staff_Name.'PatientManagement/readnotification' ?>",
            data: {notification_id: id},
            success: function(msg) {
                if(msg==1){
                location.reload();
            }
            }
        });
    }
</script>
















