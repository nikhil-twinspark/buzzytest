<?php 
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
	$sessionstaff = $this->Session->read('staff');
	$access = $sessionstaff['staffaccess']['AccessStaff'];
?>

		    <div class="contArea Clearfix">
<div class="page-header">
<h1>
    <i class="menu-icon fa fa-gift"></i>
Manage Other Rewards
</h1>
</div>
                        <div style="color:red;font-size: 12px;">
                            You can now add Products and Services as redeemable rewards for your patients. These rewards will only be redeemable with points earned at your office.
                            
                        </div>
     <div class="adminsuper">
		 <span class="add_rewards" style="float:right;">
                     <?php if(empty($BankAccount) && DEBIT_FROM_BANK==1){ ?>
                     <a href="javascript:void(0);" title="Add" class="icon-1 info-tooltip" onclick="gotobank();">Add Product/Service<?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?>/Coupon <?php }?></a>
                     <?php }else{ ?>
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "ProductAndService",
							    "action" => "add"
							));?>" title="Add" class="icon-1 info-tooltip" >Add Product/Service<?php if(($access['milestone_reward']==1 || $access['tier_setting']==1) && $access['product_service']==1) {?>/Coupon <?php }?></a>
                     <?php } ?>
                 </span>
  <?php  //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="product_service_table" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="30%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Title</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Type</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>
                            <td width="30%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                         </tr>
                 </thead>
                 <tbody>
     
        <?php 
				
					foreach ($data as $value)
					{
					
					?>
        <tr> 
          <td width="30%"><?php echo $value['title'];?></td>
          <td width="20%" ><?php if($value['type']==1){ echo 'Product'; }else if($value['type']==2){ echo 'Service'; }else { echo 'Coupon'; }?></td>
		  <td width="20%"><?php echo $value['points'];?></td>	
		  <td width="30%" class="editbtn response_btn">
			  <a title="Edit" href="<?= Staff_Name ?>ProductAndService/edit/<?php echo $value['id']; ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
			  <a title="Delete" href="<?= Staff_Name ?>ProductAndService/delete/<?php echo $value['id']; ?>"  class="btn btn-xs btn-danger"><i class='ace-icon glyphicon glyphicon-trash'></i></a>
                          Set As Public <input type='checkbox' name="set_public_<?php echo $value['id'] ?>" id="set_public_<?php echo $value['id'] ?>" <?php if($value['status']==1){ echo "checked"; }?> onclick="setpublic(<?php echo $value['id'] ?>)">
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
            url: "/ProductAndService/setproductpublic/",
            data: "&pro_id=" + id,
            success: function(msg) {
                
            }
        });
    }
       function gotobank(){
           var r = confirm("Please Add bank account details before adding product or service. Do you want to add bank account now?");
        if (r == true)
        {
            window.location.href="/BankAccount";
        }
       }
    $(document).ready( function () {
        var initDatatable = function(){
	     var dataGrid =    $('#product_service_table').dataTable( {
				"bDestroy" : true,
				"bRetrieve" : true,
			   	"aaSorting": [],
			    "sPaginationType": "full_numbers",
			});
        }
        initDatatable();
    });
</script>
















