
<div class="contArea">


     <?php if($auth==1){ ?>
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-info-circle"></i>
            Points Issued Report (<?php echo $time; ?>)
        </h1>
    </div> 
    <div class="adminsuper">
        <div class="breadcrumb_staff"><b>Total Points Issued : </b> <?php echo $totalpointgiventhisweek.' ( '.($totalpointgiventhisweek/50).' $)'; ?></div>
        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="17%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                        <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                        <td width="26%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Promotion</td>

                        <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>                      <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Amount ($)</td>
                        <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date</td>
                        <td width="18%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Given By</td>
                    </tr>
                </thead>
                <tbody>
        <?php 
				
					if(!empty($allgiventrans)){
					foreach ($allgiventrans as $agt)
					{
					
					?>
                    <tr> 
                        <td width="17%"><?php echo $agt['name']; ?></td>
                        <td width="15%"><?php echo $agt['card_number']; ?></td>
                        <td width="26%" ><?php echo $agt['description']; ?></td>
                        <td width="12%" class="editbtn response_btn"><?php echo $agt['points']; ?></td>
                        <td width="12%" class="editbtn response_btn"><?php echo $agt['points_dol']; ?></td>
                        <td width="15%" class="editbtn response_btn"><?php echo $agt['date']; ?></td>
                        <td width="18%" class="editbtn response_btn"><?php echo $agt['givenby']; ?></td>
                    </tr>
      <?php 	
                                        }}else{//Endforeach
				 ?>
                    <tr> 
                        <td colspan="7">No record found!</td>

                    </tr>
                                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-info-circle"></i>
            Points Redeemed Report (<?php echo $time; ?>)
        </h1>
    </div> 
    <div class="adminsuper">
        <div class="breadcrumb_staff"><b>Total Points Redeemed : </b> <?php echo $totalamountredeemed.' ( '.($totalamountredeemed/50).' $)'; ?> </div>
        <div class="breadcrumb_staff"><b>Total Prizes Redeemed : </b> <?php echo count($allredeemtrans); ?> </div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example1" class="table table-striped table-bordered table-hover"> 
            <thead>
                <tr> 
                    <td width="17%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                    <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                    <td width="26%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Description</td>

                    <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>                      <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Amount ($)</td>
                    <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date</td>
                    <td width="18%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Given By</td>
                </tr>
            </thead>
            <tbody>
        <?php 
				
					if(!empty($allredeemtrans)){
					foreach ($allredeemtrans as $art)
					{
					
					?>
                <tr> 
                    <td width="17%"><?php echo $art['name']; ?></td>
                    <td width="15%"><?php echo $art['card_number']; ?></td>
                    <td width="26%" ><?php echo $art['description']; ?></td>
                    <td width="12%" class="editbtn response_btn"><?php echo $art['points']; ?></td>
                    <td width="12%" class="editbtn response_btn"><?php echo $art['points_dol']; ?></td>
                    <td width="15%" class="editbtn response_btn"><?php echo $art['date']; ?></td>
                    <td width="18%" class="editbtn response_btn"><?php echo $art['givenby']; ?></td>
                </tr>
      <?php 	
                                        }}else{//Endforeach
				 ?>
                <tr> 
                    <td colspan="7">No record found!</td>

                </tr>
                                        <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-info-circle"></i>
            Referred Report (<?php echo $time; ?>)
        </h1>
    </div> 
    <div class="adminsuper">
        <div class="breadcrumb_staff"><b>Referred : </b> <?php echo count($allrefer); ?></div>

        <div class="table-responsive">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example2" class="table table-striped table-bordered table-hover"> 
                <thead>
                    <tr> 
                        <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending" >Referred By </td>
                        <td width="40%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Referred To</td>
                        <td width="40%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Email</td>
                        <td width="10%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Status</td>


                    </tr>
                </thead>
                <tbody>
        <?php 
				
					if(!empty($allrefer)){
					foreach ($allrefer as $ref)
					{
					
					?>
                    <tr> 
                        <td width="10%"><?php echo $ref['Refer']['user'];?></td>
                        <td width="40%"><?php echo $ref['Refer']['first_name'].' '.$ref['Refer']['last_name'];?></td>
                        <td width="40%" >
          <?php echo $ref['Refer']['email']; ?>
                        </td>


                        <td width="10%" class="editbtn response_btn">
							<?php echo $ref['Refer']['status']; ?>	</td>
                    </tr>
      <?php 	
                                        }}else{//Endforeach
				 ?>
                    <tr> 
                        <td colspan="4">No record found!</td>

                    </tr>
                                        <?php } ?>
                </tbody>
            </table>	
        </div>
    </div>
     <?php }else{ ?>
    <div class="adminsuper">You are not authorized user.</div>
     <?php } ?>
</div>
</div><!-- container -->

<script>
    if ($.cookie('navigationOn') == undefined) {
        $("#minNavigate").attr('class', 'ace-icon fa fa-angle-double-right');
        $("#sidebar").attr('class', 'sidebar responsive menu-min');
        $.cookie('navigationOn', 0);
    }
</script>

















