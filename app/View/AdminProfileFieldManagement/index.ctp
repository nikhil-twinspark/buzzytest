<?php 
    
    $sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    echo $this->Html->css(CDN.'css/jquery-ui.css');
    echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-user"></i> Profile Fields
        </h1>
    </div>
    <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
    ?>

<div class="add_profile">
    <span style='float:right;' class="add_rewards" >
            <a href="<?php echo $this->Html->url(array("controller"=>"AdminProfileFieldManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Profile Field</a>
        </span>
    
    </div>
    <div class="adminsuper">
        <div class="table-responsive">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover" > 
                    <thead>
                            <tr> 
                                <td width="40%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                                <td width="40%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Type</td>
                                <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Published</td>


                             </tr>
                     </thead>
                     <tbody>
                            <tr> 
                                <td width="40%">First Name</td>
                                <td width="40%">Text</td>
                                <td width="20%">
                                    <a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil grey"></i>
                                        </a> &nbsp; <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash grey"></i>
                                    </a>
                                </td>

                            </tr> 

                            <tr> 
                                <td width="40%">Last Name</td>
                                <td width="40%">Text</td>
                                <td width="20%">
                                    <a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil grey"></i>
                                        </a> &nbsp; <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash grey"></i>
                                    </a>
                                </td>
                             </tr> 

                             <tr> 
                                <td width="40%">Email</td>
                                <td width="40%">Text</td>
                                <td width="20%">
                                    <a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil grey"></i>
                                        </a> &nbsp; <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash grey"></i>
                                    </a>
                                </td>
                             </tr> 

                             <tr> 
                                <td width="40%">Custom Date</td>
                                <td width="40%">Text</td>
                                <td width="20%">
                                    <a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil grey"></i>
                                        </a> &nbsp; <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash grey"></i>
                                    </a>
                                </td>
                             </tr> 
                     <?php foreach ($ProfileFields as $ProfileField){ ?>
                             <tr> 
                                <td width="40%"><?php 
                                    if($ProfileField['ProfileField']['profile_field']=='street1'){
                                            echo "Address1";
                                    }elseif($ProfileField['ProfileField']['profile_field']=='street2'){
                                        echo "Address2";
                                    }else{
                                        echo ucwords(str_replace('_',' ',$ProfileField['ProfileField']['profile_field']));
                                    } ?>
                               </td>
                                <td width="40%"><?php if($ProfileField['ProfileField']['type']=='Varchar'){ echo "Text"; }elseif($ProfileField['ProfileField']['type']=='Text'){ echo "MultiText"; }else{ echo $ProfileField['ProfileField']['type']; } ?></td>
                                <td width="20%">
                                    <?php if(!in_array($ProfileField['ProfileField']['profile_field'],array('phone','street1','street2','state','city','postal_code','gender'))){ ?>
                                        <a title="Edit" href="<?=Staff_Name?>AdminProfileFieldManagement/edit/<?php echo $ProfileField['ProfileField']['id'] ?>" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;

                                    <a title="Delete" href="<?= Staff_Name ?>AdminProfileFieldManagement/delete/<?php echo $ProfileField['ProfileField']['id'] ?>" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash"></i>
                                    </a>

                                    <?php }else{ ?>
                                    <a title="Edit" href="javascript:void(0)" class="btn btn-xs btn-info">
                                                <i class="ace-icon glyphicon glyphicon-pencil grey"></i>
                                        </a> &nbsp; <a title="Delete" href="javascript:void(0)" class="btn btn-xs btn-danger">
                                            <i class="ace-icon glyphicon glyphicon-trash grey"></i>
                                    </a>
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




</div><!-- container -->


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
    
   
</script>
