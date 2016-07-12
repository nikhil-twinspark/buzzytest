   <?php 
    
     $sessionAdmin = $this->Session->read('Admin'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    
 
?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-book"></i> Rewards
        </h1>
    </div>
        <?php 
            //echo $this->element('messagehelper'); 
            echo $this->Session->flash('good');
            echo $this->Session->flash('bad');
        ?>
    <div class="adminsuper userMgmBox">
        <span  class="add_rewards col-sm-12" >
            <a href="<?php echo $this->Html->url(array("controller"=>"RewardManagement","action"=>"add"));?>" class="icon-add info-tooltip">Add Reward</a>
        </span>
        <div class="table-responsive">		
            <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="35%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Description</td>
                        <td width="35%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Category</td>
                        <td width="10%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Point</td>
                        <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>


                    </tr>
                </thead>
                <tbody>
       <?php foreach ($rewards as $reward){ ?>
                    <tr> 
                        <td width="35%"><?php echo $reward['Rewards']['description'];?></td>
                        <td width="35%" >
              <?php 
                $cat=explode(';',$reward['Rewards']['category']);
                echo $cat[0]; 
              ?>
                        </td>
                        <td width="10%" >
              <?php echo $reward['Rewards']['points'];?>
                        </td>
                        <td width="20%"  class='addOption'>
                <?php if($reward['Rewards']['amazon_id']==''){ ?>
                            <a title="Edit Reward" href="<?=Staff_Name?>RewardManagement/edit/<?php echo $reward['Rewards']['id'] ?>" class="btn btn-xs btn-info"><i class="ace-icon glyphicon glyphicon-pencil"></i></a>
                <?php   }else{ ?>
                            <a title="View Reward" href="<?=Staff_Name?>RewardManagement/edit/<?php echo $reward['Rewards']['id'] ?>" class="btn btn-xs btn-info"><i class="ace-icon fa fa-search-plus bigger-110"></i></a>
                <?php    } ?>


                            &nbsp;<form id="rdfrm_<?php echo $reward['Rewards']['id'] ?>" action="<?=Staff_Name?>RewardManagement/delete" method="post"  style="display: inline-block;" name="rdfrm_<?php echo $reward['Rewards']['id'] ?>">
                    <?php 
                        $serch='';
                        if(isset($_GET) && !empty($_GET)){
                            foreach($_GET as $ser=>$val){
                                $serch.=''.$ser.'='.$val.'&';
                            }
                        }
                    ?>
                                <input type="hidden" name="data[search_field]" value="<?php echo rtrim($serch,'&'); ?>">
                                <input type="hidden" name="data[page_no]" value="<?php echo $this->Paginator->current(); ?>">
                                <input type="hidden" name="data[Rewards][id]" value="<?=$reward['Rewards']['id']?>">
                                <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Delete" onclick='deleteRewards("<?php echo 'rdfrm_'.$reward['Rewards']['id'] ?>")' >
                                    <i class="ace-icon glyphicon glyphicon-trash"></i>
                                </a>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <tbody>

            </table>
        </div>


    </div>

</div>
</div><!-- container -->

<script language="Javascript">
    function deleteRewards(ptr) {
        $("#" + ptr).submit();

    }
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

</script>

