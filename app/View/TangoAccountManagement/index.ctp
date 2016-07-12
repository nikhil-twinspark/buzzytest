<?php

$sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-credit-card"></i> Tango Account
        </h1>
    </div>
    <?php 
       //echo $this->element('messagehelper'); 
       echo $this->Session->flash('good');
       echo $this->Session->flash('bad');
   ?>
    <div class="adminsuper">
           <?php if(empty($TangoAccount)){ ?> <span style='float:right;' class="add_rewards" >
            <a href="<?php echo $this->Html->url(array("controller"=>"TangoAccountManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Tango Account</a>
        </span>
           <?php } ?>
        <div class="table-responsive">


            <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Customer</td>
                        <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Email</td>
                        <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Available Balance ($)</td>

                        <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Identifier</td>
                        <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Action</td>          

                    </tr>
                </thead>
                <tbody>
        <?php 
				
			
					foreach ($TangoAccount as $tac)
					{
					
					?>
                    <tr> 
                        <td width="20%"><?php echo $tac['TangoAccount']['customer'];?></td>
                        <td width="20%"><?php echo $tac['TangoAccount']['email'];?></td>
                        <td width="20%" id="avail_bal"><?php echo $tac['TangoAccount']['available_balance'];?></td>
                        <td width="20%"><?php echo $tac['TangoAccount']['identifier'];?></td>  
                        <td width="20%"><a href="<?php echo $this->Html->url(array(
							    "controller" => "TangoAccountManagement",
							    "action" => "addfund",
							    $tac['TangoAccount']['id']
                                ));?>" title="Add Fund" class="btn btn-xs btn-info"><i class="ace-icon glyphicon glyphicon-tint"></i></a> &nbsp;<a id="syn_38" class="btn btn-xs btn-danger" href="javascript:void(0)" title="Refresh Stats" onclick="updatefund('<?php echo $tac['TangoAccount']['customer'];?>','<?php echo $tac['TangoAccount']['identifier'];?>');">
<i class="ace-icon fa fa-exchange"></i>
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
</div><!-- container -->


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
function updatefund(customer,identifier) {
        $.ajax({
            type: "POST",
            data: "customer=" + customer+'&identifier='+identifier,
            dataType: "json",
            url: "/TangoAccountManagement/updatefund",
            success: function(result) {
                if (result>0) {
                    $('#avail_bal').html(result);
                    alert('Tango latest balance updated succssfully.');
                }else{
                    alert('Please Try again leter.');
                }

            }});
    }
</script>















