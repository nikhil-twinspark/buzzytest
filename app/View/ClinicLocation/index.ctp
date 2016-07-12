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
            <i class="menu-icon fa fa-home"></i>
            Clinic Locations
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
							    "controller" => "ClinicLocation",
							    "action" => "add"
							));?>" title="Add Location" class="icon-1 info-tooltip">Add Location</a>
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
                        <td width="50%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Address</td>
                        <td width="25%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">City</td>
                        <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($Locations as $Location)
					{
					
					?>
                    <tr> 

                        <td width="50%"><?php echo $Location['ClinicLocation']['address'];?></td>
                        <td width="25%" ><?php echo $Location['ClinicLocation']['city'];?></td>

                        <td width="25%" class="editbtn response_btn">
                            <a title="Edit" href="<?= Staff_Name ?>ClinicLocation/edit/<?php echo $Location['ClinicLocation']['id'] ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a> 
                             <a title="Delete" href="<?= Staff_Name ?>ClinicLocation/delete/<?php echo $Location['ClinicLocation']['id'] ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i> </a> set as prime location <input type='checkbox' name="<?php echo $Location['ClinicLocation']['id'] ?>" id="<?php echo $Location['ClinicLocation']['id'] ?>" <?php if($Location['ClinicLocation']['is_prime']==1){ echo "checked"; }?> onclick="setprime(<?php echo $Location['ClinicLocation']['id']; ?>)">
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
    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "aaSorting": [[0, "asc"]],
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
    function setprime(id) {



        $.ajax({
            type: "POST",
            url: "/ClinicLocation/setprime/",
            data: "&location_id=" + id,
            success: function(msg) {
                    <?php
                   
                  foreach ($Locations as $Location)
                                     { ?>
                if (id ==<?php echo $Location['ClinicLocation']['id']; ?>) {
                    $("#<?php echo $Location['ClinicLocation']['id']; ?>").attr("checked", true);
                } else {
                    $("#<?php echo $Location['ClinicLocation']['id']; ?>").attr("checked", false);
                }
                                     <?php } ?>
            }
        });



    }

</script>




















