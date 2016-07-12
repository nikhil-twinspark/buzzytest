<?php

echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-gift"></i>
            Level Up Treatment Plans
        </h1>
    </div>

    <div class="adminsuper">

  <?php  //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>

        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="levelup_settings" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="13%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Treatment Name</td>
                        <td width="10%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Total Visits</td>
                        <td width="13%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Visits Breakdown</td>
                        <td width="10%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Total Points</td>
                        <td width="13%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points Breakdown</td>
                        <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Promotions Used</td>
                        <td width="11%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Available Bonus</td>
                        <td width="10%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Action</td>

                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($phaseDistribution as $index=>$value)
					{
				?>
                    <tr> 
                        <td width="13%"><?php echo $value['UpperLevelSetting']['treatment_name'];?></td>
                        <td width="10%" ><?php echo $value['UpperLevelSetting']['total_visits'];?></td>
                        <td width="13%" >
                        <?php 
                        $i=1;
                        $points = array();
                        foreach($value['phase_distribution'] as $key=>$val){
                            $visits = '['.$val['visits'].']';
                            $points[] = '['.$val['points'].']';
                            if($i!=count($value['phase_distribution'])){
                                $visits =$visits.' , ';
                            }
                            echo $visits;
                            $i++;
                        }?>
                        </td>
                        <td width="10%" ><?php echo $value['UpperLevelSetting']['total_points'];?></td>
                        <td width="13%" ><?php echo implode(' , ',$points); ?></td>
                        <td width="20%" >
                            <?php 
                            $i=1;
                            foreach($value['UpperLevelSetting']['promotion_names'] as $pro){
                                echo $i.' : '.$pro.'<br>';
                                $i++;
                            }
                            ?>
                        </td>
                        <td width="11%" ><?php if($value['UpperLevelSetting']['bonus_points']>0){ echo $value['UpperLevelSetting']['bonus_points'].'-('.$value['UpperLevelSetting']['bonus_message'].')'; }else{ echo "Not Available"; } ?></td>
                        <td width="15%" ><a class="btn btn-xs btn-danger" href="javascript:void(0);" title="Delete" onclick="deletetreatment(<?php echo $value['UpperLevelSetting']['id'];?>,<?php echo $value['UpperLevelSetting']['clinic_id'];?>)">
                                <i class="ace-icon glyphicon glyphicon-trash"></i>
                            </a></td>


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

    function deletetreatment(treatment_id, clinic_id) {

        var datasrc = "treatment_id=" + treatment_id + '&clinic_id=' + clinic_id;

        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>ClientManagement/checkdeleteTreatment/",
            success: function(result) {

                if (result == 0) {
                    var r = confirm("WARNING: The following action will delete a treatment plan that is currently in use. Would you like to proceed?");
                } else {
                    var r = confirm("You are about to permanently delete this treatment plan. Would you like to proceed?");
                }
                
                if (r == true)
                {
                    $.ajax({
                        type: "POST",
                        data: datasrc,
                        url: "<?=Staff_Name?>ClientManagement/deleteTreatment/",
                        success: function() {
                            alert('Treatment deleted successfully');
                            location.reload();
                        }
                    });
                } else {
                    return false;
                }


            }
        });



    }

    $(document).ready(function() {
        var initDatatable = function() {
            var dataGrid = $('#levelup_settings').dataTable({
                "bDestroy": true,
                "bRetrieve": true,
                "aaSorting": [],
                "sPaginationType": "full_numbers",
            });
        }
        initDatatable();
    });
</script>
















