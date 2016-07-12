<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-magic"></i>
            Rating/Reviews Promotions
        </h1>
    </div>
    <div class="adminsuper">
         <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="40%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Promotion Display Name</td>
                        <td width="40%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Point Value</td>
                        <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>


                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($promotionlist as $pro)
					{
					
					?>
                    <tr> 
                        <td width="15%"><?php echo $pro['Promotion']['display_name'];?></td>
                        <td width="15%"><?php echo $pro['Promotion']['value'];?></td>
                        <td width="10%" class="editbtn response_btn"> 
                           <a title="Edit" href="<?= Staff_Name ?>PromotionManagement/editratereviewpromotion/<?php echo $pro['Promotion']['id']; ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
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
            "aaSorting": [[4, "desc"]],
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
















