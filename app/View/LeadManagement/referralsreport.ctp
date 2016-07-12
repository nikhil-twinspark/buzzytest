<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">

<div class="page-header">
<h1>
    <i class="menu-icon ace-icon fa fa-bar-chart-o"></i>
Referrals Report
</h1>
</div>
     <div class="adminsuper">
				
					<div class="col-md-12 basicReportmain">	
					 <div class="col-md-4 col-xs-12 basicrptCol2">
							<ol>
								<li class="reportol"><span>Total Referrals Made By Patients:</span> <?=$TotalRefer?></li>
                                                                <li class="reportol"><span>Total Referrals in Failed Stage:</span> <?=$TotalReferFailed?></li>
                                                                <li class="reportol"><span>Total Referrals in Pending Stage:</span> <?=$TotalReferPending?></li>
								<li class="reportol"><span>Total Referrals in Accepted Stage:</span> <?=$TotalReferAccpeted?></li>
                                                                
								<li class="reportol"><span>Referrals Completed in Total:</span> <?php  if(!empty($TotalReferCompleted)){ ?>
									<ol>
										<?php 
									
										foreach($TotalReferCompleted as $comp){ ?>
										<li class="reportol">
										<?=$comp['leadname']?>: <?=$comp['total']?>
										</li>
										<?php } ?>
									</ol>
									<?php }else{ ?>
									0
									<?php } ?>
								</li>
							<ol>
					 </div>
					 </div>
         
     </div>
     
   </div>

