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
    <i class="menu-icon fa fa-cubes"></i>
Rewards
</h1>
</div>
											<div class="tabbable">
											<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
												<li class="active">
													<a data-toggle="tab" href="#home4">BuzzyDoc Rewards</a>
												</li>

												<li>
													<a data-toggle="tab" href="#profile4">My Office Rewards</a>
												</li>

												
											</ul>

											<div class="tab-content">
												<div id="home4" class="tab-pane in active">
                                                                                                    <div class="table-responsive">
                                                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
                            <td width="45%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                            <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>
                            <td width="25%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Category</td>
                            <td width="15%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Published</td>
                         </tr>
                 </thead>
                 <tbody> </tbody>
         </table>
                                                                                                    </div>									</div>

												<div id="profile4" class="tab-pane">
												<span style='float:right;' class="add_rewards">
			<a href="<?php echo Staff_Name ?>StaffRewardManagement/addlocalreward" title="">
				Add Rewards
			</a>
		</span>
                                                                                                    <div class="table-responsive">
                                                                                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example1" class="table table-striped table-bordered table-hover"> 
                <thead>
                        <tr> 
                            <td width="45%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                            <td width="10%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>
                            <td width="35%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Category</td>
                            <td width="20%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Action</td>
                         </tr>
                 </thead>
                 <tbody> </tbody>
                </table></div>
												</div>

												
											</div>
										</div>
			
   
     
   </div>
  
   
<script>
      
   $(document).ready( function () {
       $('a[rel*=facebox]').facebox(); //this is for light box
       //"aoColumns":[{"mData":"Reward.description"},{"mData":"Reward.points"},{"mData":"Reward.updated"}]
      $('#example').dataTable( {
          "columnDefs": [ { "targets": 3, "orderable": false } ],
"bProcessing": true,
"bServerSide": true,
"order": [[ 1, "asc" ]],
"sPaginationType": "full_numbers",
"sAjaxSource": "/StaffRewardManagement/getGlobalRewards",
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

$('#example1').dataTable( {
    "columnDefs": [ { "targets": 3, "orderable": false } ],
"bProcessing": true,
"bServerSide": true,
"order": [[ 1, "asc" ]],
"sPaginationType": "full_numbers",
"sAjaxSource": "/StaffRewardManagement/getLocalRewards"
      ,"fnDrawCallback":function(){
     if ( $('#example1_paginate span a.paginate_button').size()) {
     $('#example1_paginate')[0].style.display = "block";
    } else {
    $('#example1_paginate')[0].style.display = "none";
  }
}
     

           
} );

});
 $('ul.tabs').each(function(){
    var $active, $content, $links = $(this).find('a');

    $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
    $active.addClass('active');

    $content = $($active[0].hash);

    // Hide the remaining content
    $links.not($active).each(function () {
		$(this.hash).hide();
    });

    // Bind the click event handler
    $(this).on('click', 'a', function(e){
      // Make the old tab inactive.
      $active.removeClass('active');
      $content.hide();

      // Update the variables with the new link and content
      $active = $(this);
      $content = $(this.hash);

      // Make the tab active.
      $active.addClass('active');
      $content.show();

      // Prevent the anchor's default click action
      e.preventDefault();
    });
  });

function setPublished(reward_id){
	
	$.ajax({
		type: "POST",
		url: "/StaffRewardManagement/setPublishGlobalReward",
		data: "reward_id="+reward_id,
		success: function(msg){
		
			if(parseInt(msg)==1){
				$('#publish_'+reward_id).attr('checked',true);
			}else if(parseInt(msg)==2){
				$('#publish_'+reward_id).attr('checked',false);
			}else if(parseInt(msg)==3){
				$('#publish_'+reward_id).attr('checked',false);
			}else if(parseInt(msg)==4){
				$('#publish_'+reward_id).attr('checked',true);
			}
			
		}
	});

}
</script>
<style>

	.tabs li {
		list-style:none;
		display:inline;
		border-radius: 5px 5px 0 0;
	}

	.tabs a {
		padding:10px 10px;
		display:inline-block;
		background: #e0e0e0;
		color:#000;
		text-decoration:none;
		border-radius: 5px 5px 0 0;
	}

	.tabs a.active {
		background:#666;
		color:#fff;
	}

</style>
   


















