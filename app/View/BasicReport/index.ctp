<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">

<div class="page-header">
<h1>
    <i class="menu-icon ace-icon fa fa-bar-chart-o"></i>
Basic Report
</h1>
</div>
     <div class="adminsuper">
					<div class="col-md-12 basicReportmain">
					 <div class="col-md-4 col-xs-12 basicrptCol">
							<h3>Points</h3>
								<ol>
									<li class="reportol"><span>Total Points Dispensed:</span> <?=$PointDisbursed?></li>
									<li class="reportol"><span>Total Points Redeemed:</span> <?php if($PointRedeemed==''){ echo '0';}else{ echo $PointRedeemed; } ?></li>
								</ol>
					 </div>
					</div>
					<div class="col-md-12 basicReportmain">
					 <div class="col-md-4 col-xs-12 basicrptCol1">
							<h3>Redemptions</h3>
							<ol>
								<li class="reportol"><span>Total Orders Redeemed:</span> <?php echo $OrderRedeemed + $OrderShipped + $OrderInoffice?></li>
							</ol>
					 </div>
					</div>
<!--					<div class="col-md-12 basicReportmain">	
					 <div class="col-md-4 col-xs-12 basicrptCol2">
							<h3>Referrals</h3>
							<ol>
								<li class="reportol"><span>Total Referrals Made By Patients:</span> <?=$TotalRefer?></li>
								<li class="reportol"><span>Total Referrals in Accepted Stage:</span> <?=$TotalReferAccpeted?></li>
                                                                <li class="reportol"><span>Total Referrals in Failed Stage:</span> <?=$TotalReferFailed?></li>
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
					 </div>-->
         
         
         
                    
           
         <!-- <ul>
             <li class="report">
                    Card Summary:
                    <ol>
                        <li class="reportol">
                    Total Cards Purchased: <?=$Totalcardpurch?>
                    </li>
                    <li class="reportol">
                    Cards Issued to Patients: <?=$Totalcardissue?>
                    </li>
                    <li class="reportol">
                    Cards Registered: <?=$Totalcardreg?>
                    </li>
                     <li class="reportol">
                    Cards Balance: <?=$Totalcardbalance?>
                    </li>
                    </ol>
             </li>
         </ul> -->
     </div>
     
   </div>

