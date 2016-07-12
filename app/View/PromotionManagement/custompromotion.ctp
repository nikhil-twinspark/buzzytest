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
Custom Promotions
</h1>
</div>
     <div class="adminsuper">
         <?php if ($sessionstaff['is_lite'] != 1) {

             if(count($Promotions)<3 || 1 /*Allowing unlimited custom promotions temporarily. FIXME as more specs are learned*/){

             ?>
		 <span class="add_rewards" style="float:right;">
			<a href="<?php echo $this->Html->url(array(
							    "controller" => "PromotionManagement",
							    "action" => "add"
							));?>" title="Edit" class="icon-1 info-tooltip">Add Promotions</a>
                 </span>
             <?php  }else{ ?>
                 <span class="add_rewards" style="float:right;">
                     <a href="javascript:void(0);" title="Edit" class="icon-1 info-tooltip" onclick="alert('Notice: The maximum of 3 Custom Promotions are in use on your account. Please delete a promotion before a new one can be added.');">Add Promotions</a>
                 </span>
             <?php }}
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
         <div class="table-responsive">
             <span style="color:#438eb9;font-weight:bold;">Note: Change the order for Custom Promotions by drag and drop</span>
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
           <thead>
                        <tr> 
                            <td width="35%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Promotion Display Name</td>
                            <td width="20%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Point Value</td>
                     
                            <td width="25%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Options</td>
                         </tr>
                 </thead>
                 <tbody>
                 </tbody>
       </table>
     
         </div>		
     </div>
     
   </div>

   <script>
   var length = 10;
   if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
  		length = 5;
    }
    $(document).ready( function () {
        //$('a[rel*=facebox]').facebox(); //this is for light box
        
        function initDatatable(length){
        var pageNumber = '';
			
			 var dataGrid =    $('#example').dataTable( {
    	 "fnDrawCallback" : function() {
				pageNumber = this.fnPagingInfo().iPage;
			},
			"bDestroy" : true,
			"bRetrieve" : true,
			"aaSorting": [],
			"aoColumns" : [ {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : true
			}, {
				sClass : "",
				"bSortable" : false
			} ],
			"aLengthMenu" : [ [ 5, 10, 25, 50, 100, -1 ],
					[ 5, 10, 25, 50, 100, "All" ] ],
			"bProcessing" : true,
			"sServerMethod" : "GET",
			"sAjaxSource" : "/PromotionManagement/getdatacustom",
			"iDisplayLength" : length,
			"oLanguage" : {
				"sInfo" : "_END_ of _TOTAL_ entries",
				"sInfoEmpty" : "_END_ of _TOTAL_ entries"
			}
		});
			var startPosition;
	var endPosition;
	$("#example tbody").sortable({
		cursor : "move",
		start : function(event, ui) {
			startPosition = ui.item.prevAll().length + 1;
		},
		update : function(event, ui) {
			var dataset = [];
			
			if (pageNumber == 0) {
				j = 1;
			} else {
				j = (pageNumber * 10) + 1;
			}
			
			$('#example tbody>tr').each(function(i, v) {
				var array = {
					'id' : $(v).find('td > a').attr('m_id'),
					'position' : j
				};
				dataset.push(array);
				j++;
			});
			
			
			$.ajax({
				type : "POST",
				url : "/PromotionManagement/sortpromotions",
				data : {
					data : JSON.stringify(dataset)
				},
				dataType : "json",
				success : function(result) {
				}
			}); 
		}
	});
}
 initDatatable(length);
         $('#example_filter input[aria-controls="example"]').on('keyup', function () {
	        if ($(this).val() == '') {
	            $('#example').dataTable().fnReloadAjax();
	        }
		}); 
    });
    
    function setpublic(id) {
        $.ajax({
            type: "POST",
            url: "/PromotionManagement/setpublic/",
            data: "&promotion_id=" + id,
            success: function(msg) {
                
            }
        });
    }
</script>
















