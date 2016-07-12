<?php 
    
    $sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->script(CDN.'js/fnReloadAjax.js');
    echo $this->Html->script(CDN.'js/fnpaginginfo.js');
    //echo $this->Html->css(CDN.'css/jquery-ui.css');
    //echo $this->Html->script(CDN.'js/jquery-ui.js');
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
 


$sessionstaff = $this->Session->read('staff');
?>

		    <div class="contArea Clearfix">
<div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Manage Orders
</h1>
</div>
     <div class="adminsuper">
<?php    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="manageorders" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                        	<td width="13%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Order No.</td>
                            <td width="15%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                            <td width="14%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Product/Service</td>
                            <td width="13%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points Redeemed</td>
                            <td width="15%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Credit Amount ($)</td>
                            <td width="15%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Order Date</td>
                            <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Status</td>
                         </tr>
                 </thead>
                 <tbody>
                 </tbody>
       </table>
     
         </div>		
     </div>
     
   </div>

   <script>
   	jQuery(document).ready(function(){
   	
   	
        var pageNumber = '';
			 var dataGrid =    $('#manageorders').dataTable( {
			"bDestroy" : true,
			"bRetrieve" : true,
			"aaSorting": [[ 0, "desc" ]],
			"aoColumns" : [ {
				sClass : "",
				"bSortable" : true
			},
			{
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			} ],
			"aLengthMenu" : [ [ 5, 10, 25, 50, 100, -1 ],
					[ 5, 10, 25, 50, 100, "All" ] ],
			"bProcessing" : true,
			"sServerMethod" : "GET",
			"sAjaxSource" : "/ProductAndService/getorders",
			"iDisplayLength" : length,
			"oLanguage" : {
				"sInfo" : "_END_ of _TOTAL_ entries",
				"sInfoEmpty" : "_END_ of _TOTAL_ entries"
			}
		});

		$('#manageorders').on('change','select[id^=orderStatus_]',function(){
		  var id = $(this).attr('id').replace('orderStatus_','');
		  var status = $(this).val();
		  $.ajax({
					type : "POST",
					url : "/ProductAndService/changeorderstatus",
					data : {
						id:id,
						status:status
					},
					dataType : "json",
					success : function(result) {
						if(result.success==1 && status==2){
							$('#orderStatus_'+id).attr('disabled','');
							alert('Status change successfully.');
						}
					}
				}); 
		});
		
   	});
   </script>