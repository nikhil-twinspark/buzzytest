<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-gift"></i>
            Active Promotions
        </h1>
    </div>
<div style="color:red;font-size: 12px;">
    <?php if (!empty($Promotions)) {
        
    }else{
                            echo "Activate a promotion to add a treatment plan";
    } ?>
                            
                        </div>
    <div class="adminsuper">

  <?php  //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="product_service_table" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Buzzydoc Promotion name</td>

                        <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Active</td>
                        <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Edit Active Promotion</td>
                    </tr>
                </thead>
                <tbody>

        <?php 
				
					foreach ($GlobalPromotions as $value)
					{

					if (in_array($value['Promotion']['id'], $Promotions)) {
                                        $chk='checked';
                                        }else{
                                        $chk='';
                                        }

					?>
                    <tr> 
                        <td width="30%"><?php echo $value['Promotion']['description'];?></td>
              <!--          <td width="20%" ><?php echo $value['Promotion']['value'];?></td>-->

                        <td width="30%" class="editbtn response_btn">
                            <input type='checkbox' name="set_public_<?php echo $value['Promotion']['id'] ?>" id="set_public_<?php echo $value['Promotion']['id'] ?>" <?php echo $chk;?> onclick="setpublic(<?php echo $value['Promotion']['id'] ?>)">
                        </td>
                        <td width="30%" class="editbtn response_btn" id="editactive_<?php echo $value['Promotion']['id'] ?>">
                            <?php
                        if (in_array($value['Promotion']['id'], $Promotions)) {
                                       ?>
                                           <a title='Edit' href="<?= Staff_Name ?>LevelupPromotions/edit/<?php echo $value['Promotion']['id']; ?>"  class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
                                           <?php
                                        }else{
                                       ?>
                            <a title='Edit' href="javascript:void(0);"  class='btn btn-xs btn-info'><i class='ace-icon glyphicon glyphicon-pencil grey'></i></a>
                            <?php
                                        }
                                        ?>
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

    function setpublic(id) {
        $.ajax({
            type: "POST",
            url: "/LevelupPromotions/setpropublic/",
            data: "&pro_id=" + id,
            success: function(msg) {
                if(msg==0){
                    $('input#set_public_'+id).prop('checked', true);
                    alert('Promotion already in use.You cant unpublish this levelup promotion.');
                    
                }else{
                    if(msg==1){
                    $("#editactive_"+id).html('<a title="Edit" href="<?php echo Staff_Name; ?>LevelupPromotions/edit/'+id+'"  class="btn btn-xs btn-info"><i class="ace-icon glyphicon glyphicon-pencil"></i></a>');
                }else{
                    $("#editactive_"+id).html('<a title="Edit" href="javascript:void(0)"  class="btn btn-xs btn-info"><i class="ace-icon glyphicon glyphicon-pencil grey"></i></a>');
                }
                }
                
            }
        });
    }


    $(document).ready(function() {
        var initDatatable = function() {
            var dataGrid = $('#product_service_table').dataTable({
                "bDestroy": true,
                "bRetrieve": true,
                "aaSorting": [],
                "sPaginationType": "full_numbers",
            });
        }
        initDatatable();
    });
</script>
















