 <?php
	echo $this->Html->css(CDN.'css/jquery.dataTables.css');
	echo $this->Html->css(CDN.'css/facebox.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
	echo $this->Html->script(CDN.'js/faceBox/facebox.js');
 ?>

<?php $sessionstaff = $this->Session->read('staff');	?>
		    <div class="contArea Clearfix">
		<div class="page-header">
<h1>
    <i class="menu-icon fa fa-trophy"></i>
Referral Promotions
</h1>
</div>		
     <div class="adminsuper tab_index">

     <div class="tabular_data">
  <div id='tab1'>
      <div class="table-responsive">
          <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
                            <td width="45%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Referral Promotion Name</td>
                 
                            <td width="5%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Published</td>
                         </tr>
                 </thead>
                 <tbody> </tbody>
         </table>
      </div>
	</div>

    </div>
	<!-------------end here------------------------->		
     </div>
     
   </div>
 
   
<script>
      
   $(document).ready( function () {
       $('a[rel*=facebox]').facebox(); //this is for light box
       //"aoColumns":[{"mData":"Reward.description"},{"mData":"Reward.points"},{"mData":"Reward.updated"}]
      $('#example').dataTable( {
            "columnDefs": [ { "targets": 1, "orderable": false } ],
"bProcessing": true,
"bServerSide": true,
"order": [[ 0, "asc" ]],
"sPaginationType": "full_numbers",
"sAjaxSource": "/StaffReferralPromotionManagement/getGlobalReferralPromotion",
"oLanguage": {
 "sZeroRecords": "No matching records found"
  },"fnDrawCallback":function(){
     if ( $('#example_paginate span a.paginate_button').size()) {
     $('#example_paginate')[0].style.display = "block";
    } else {
    $('#example_paginate')[0].style.display = "none";
  }
}
     

           
} );



});


function setPublished(promotion_id){
	
	$.ajax({
		type: "POST",
		url: "/StaffReferralPromotionManagement/setPublishGlobalReferralPromotion",
		data: "promotion_id="+promotion_id,
		success: function(msg){
		
			if(parseInt(msg)==1){
				$('#publish_'+promotion_id).attr('checked',true);
			}else if(parseInt(msg)==2){
				$('#publish_'+promotion_id).attr('checked',false);
			}else if(parseInt(msg)==3){
				$('#publish_'+promotion_id).attr('checked',false);
			}else if(parseInt(msg)==4){
				$('#publish_'+promotion_id).attr('checked',true);
			}
			
		}
	});

}
</script>
