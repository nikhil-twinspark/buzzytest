<?php 
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
	$sessionstaff = $this->Session->read('staff');
        function mask_other( $email )
{
        $len = strlen($email);
$showLen = floor($len/4);
$str_arr = str_split($email);
for($ii=0;$ii<$len-$showLen;$ii++){
    $str_arr[$ii] = 'x';
}

$em[0] = implode('',$str_arr); 
$new_name = implode($em);
        return( $new_name);
}
?>

		    <div class="contArea Clearfix">
<div class="page-header">
<h1>
    <i class="menu-icon fa fa-credit-card"></i>
Bank Account
</h1>
</div>
     <div class="adminsuper">
         <?php if(empty($BankAccount)){ ?>
		 <span class="add_rewards" style="float:right;">
			<?php if($sessionstaff['staff_role']=='Doctor'){ ?><a href="<?php echo $this->Html->url(array(
							    "controller" => "BankAccount",
							    "action" => "add"
							));?>" title="Add" class="icon-1 info-tooltip">Add Bank Account</a>
                        <?php } ?>
         </span> <?php } ?>
  <?php  //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="product_service_table" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="20%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Customer Name</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Account Number</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Account Type</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Transit Number</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                         </tr>
                 </thead>
                 <tbody>
     <?php foreach($BankAccount as $bank){ ?>
     
        <tr> 
          <td width="20%"><?php echo $bank['BankAccount']['customer_name'];?></td>
          <td width="20%" ><?php echo mask_other( $bank['BankAccount']['account_number'], 'x', 80 );?></td>
		  <td width="20%"><?php echo $bank['BankAccount']['account_type'];?></td>
                  <td width="20%"><?php echo mask_other( $bank['BankAccount']['transit_number'], 'x', 70 )?></td>	
		  <td width="20%" class="editbtn response_btn">
                          <?php if($sessionstaff['staff_role']=='Doctor'){ ?>
                      <a title="Edit" href="<?= Staff_Name ?>BankAccount/edit/<?php echo $bank['BankAccount']['id']; ?>" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil'></i></a>
                          <?php }else{ ?>
                      <a title="Edit" href="javascript:void(0);" class="btn btn-xs btn-info"><i class='ace-icon glyphicon glyphicon-pencil grey'></i></a>
                          <?php } ?>	  
		  </td>
		  						
        </tr>
     <?php } ?>
                 </tbody>
       </table>
     
         </div>		
     </div>
     
   </div>

   <script>
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
















