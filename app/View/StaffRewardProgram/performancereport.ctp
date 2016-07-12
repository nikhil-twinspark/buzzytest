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
            Performance Report
        </h1>
    </div>
    <div class="adminsuper">
        <?php if(!empty($Reports)){ ?>
        <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
   "controller" => "StaffRewardProgram",
"action"=>"exportReport"
));?>">Export</a>
                 </span>
        <?php  }
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-bordered"> 
                <thead>
                    <tr> 
                        <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Goal Name</td>
                        <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Goal For</td>
                        <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Target (Weekly)</td>
                        <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Target Achieved</td>
                        <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Week Number</td>
                        <td width="21%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date Range</td>



                    </tr>
                </thead>

                <tbody>

        <?php 
				
					foreach ($Reports as $report)
					{
                                         $persent_val=$report['GoalAchievement']['actual_value']/$report['GoalAchievement']['target_value'];
					if($persent_val>=1){
                                            $cls='bg-success';
                                        }else if($persent_val> 0.5){
                                            $cls='bg-warning';
                                        }else{
                                            $cls='bg-danger';
                                        }
					?>
                    <tr > 

                        <td width="20%"><?php echo $report['Goal']['goal_name'];?></td>
                        <td width="20%" ><?php if($report['Staff']['staff_id']!=''){ echo $report['Staff']['staff_id']." (Staff User)"; }else{ echo $report['Clinic']['api_user']." (Practice)"; } ?></td>
                        <td width="12%"><?php echo $report['GoalAchievement']['target_value'];?></td>
                        <td width="12%" class="<?php echo $cls; ?>"><?php echo $report['GoalAchievement']['actual_value'];?></td>
                        <td width="15%"><?php echo $report['GoalAchievement']['year'].' ('.$report['GoalAchievement']['week_number'].')';?></td>
                        <td width="21%"><?php 
         
            $date = strtotime("+6 day", strtotime($report['GoalAchievement']['goal_start_date']));
            $start_date=explode(' ',$report['GoalAchievement']['goal_start_date']);
          echo $start_date[0].' - '.date('Y-m-d', $date);?></td>

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
                <label>Target Value</label><input type="text" id='target_value' name="target_value" value="" placeholder="Target Value" required="required" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')">

            </div>

        </div>


        <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"><div class="ui-dialog-buttonset">
                <input type="button" value="Set" id='change_btn' class="btn btn-primary btn-xs">

            </div></div>
        <div id='status_error' style="color: #FF0000;"></div>
    </form>
</div><!-- #dialog-message -->
<script>

    $(document).ready(function() {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        $('#example').dataTable({
            "columnDefs": [{"targets": 0, "orderable": false}, {"targets": 1, "orderable": false}, {"targets": 4, "orderable": false}],
            "aaSorting": [[5, "desc"]],
            "sPaginationType": "full_numbers",
        });
        $('#example').dataTable().columnFilter({
            sPlaceHolder: "head:before",
            aoColumns: [{type: "select"},
                {type: "select"},
                null,
                null,
                {type: "select"}
            ]

        });
    });


</script>




















