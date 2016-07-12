<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 


$sessionstaff = $this->Session->read('staff');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Milestone Rewards
        </h1>
    </div>
    <div class="adminsuper">
        <span class="add_rewards" style="float:right;">
            <a href="<?php echo $this->Html->url(array(
							    "controller" => "MilestoneReward",
							    "action" => "add"
							));?>" title="Add" class="icon-1 info-tooltip">Add Milestone Reward</a>
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
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Description</td>
                        <td width="25%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Points</td>
                        <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($MilestoneReward as $mb)
					{
					
					?>
                    <tr> 
                        <td width="25%"><?php echo $mb['MilestoneReward']['name'];?></td>
                        <td width="25%"><?php echo $mb['MilestoneReward']['description'];?></td>
                        <td width="25%"><?php echo $mb['MilestoneReward']['points'];?></td>
                        <td width="25%" class="editbtn response_btn">
                            <a title="Edit" href="<?= Staff_Name ?>MilestoneReward/edit/<?php echo $mb['MilestoneReward']['id']; ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>&nbsp;<a title="Delete" href="<?php echo Staff_Name; ?>MilestoneReward/delete/<?php echo $mb['MilestoneReward']['id'];?>"  class="btn btn-xs btn-danger"><i class="ace-icon glyphicon glyphicon-trash"></i></a>
			 
		  </td>
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
            "aaSorting": [[0, "desc"]],
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


</script>
















