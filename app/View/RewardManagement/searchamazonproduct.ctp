
<td style="height: 50px" colspan="2">
	<div class="loginArea">
		<div class="contBox pad-top-13">
			<?php 
                        
                        
                        if($responseData[0]['status_code']==200){ ?>
			<?php for($a=0; $a < count($responseData); $a++){ ?>
				<?php if($responseData[$a]['url']!=''){
                                        $title=str_replace('"',' ',$responseData[$a]['title']);
					$title=str_replace("'",' ',$title);
                                        if(strlen($title)>50){
                                        $title1=substr($title, 0,50).'..';
                                        }
                                        else{
                                         $title1=$title;   
                                        }
					$category=str_replace("'",' ',$responseData[$a]['category']);
                                        if($responseData[$a]['price']>0){
                                            $cls='productBoxSM';
                                        }else{
                                           $cls='productBoxSM'; 
                                        }
                                  
				?>
					   <!-- start here -->
						   <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 <?=$cls?>">
									<p><a title="<?php echo $title ?>"><?php echo $title1 ?></a></p>
                                                                        <?php if($responseData[$a]['price']>0){ ?>
									<img alt="" src="<?php echo $responseData[$a]['url'] ?>"  accept='image/*'  onclick="setAmazonProduct('<?php echo $responseData[$a]['sku'] ?>','<?php echo $responseData[$a]['url'] ?>','<?php echo $title1 ?>','<?php echo $responseData[$a]['price'] ?>')">
                                                                        <?php }else{ ?>
                                                                        	<img alt="" src="<?php echo $responseData[$a]['url'] ?>"  accept='image/*'>
                                                                        <?php } ?>
                                                                        <h3><?php if($responseData[$a]['price']>0){ echo $responseData[$a]['price'].' points'; }else{ echo "Not Available"; } ?> 
                                                                        
                                                                       <span class="headTopCorner"></span>
                                                            <span class="headrightcorner"></span>
                                                        </h3>
								</div>
							</div>
						<!---end here-->
				<?php } ?>	
			<?php } ?>
                                                <span  style="display: block;float: left;text-align: right;width: 100%; margin-left: -57px;" class="clearfix">
					<?php
						//echo "total : ".$total_pages;
						//echo "<br/>current : ".$current_page;
						if($total_pages > 1){ 
							
								for($a=1; $a <= 5; $a++){
                                                                    if($current_page == $a){
                                                                     ?>
                                    			<span  style="color: red;"><?php echo $a ?></span>
                                    <?php
                                                                    }else{
                                                                    ?>
									<span onclick="searchAmazonProduct(<?php echo $a ?>)" style='cursor:pointer'><?php echo $a ?></span>
								
                                                                    <?php }}
						
							 ?>
                                                                       &nbsp; <span id='progress_amz_prd_div_page' ></span>
						<?php } ?>
						</span>
			<?php }else{ ?>
				<?php echo $responseData[0]['error'] ?>
			<?php } ?> 
        </div>
    </div>
</td>



<script type='text/javascript'>
	function setAmazonProduct(sku,url,title,price){
			
			var datasrc="amazon_id="+sku;
		
			$.ajax({
				type: "POST",
				url: "<?php echo Staff_Name ?>RewardManagement/checkreward",
				data: datasrc,
				success: function(msg){
					if(msg==1){
					alert('Reward Already Exist.')
					return false;
					}
					else{
				 
			$('#progress_div_'+sku).html("<img src='<?php echo CDN; ?>img/loading-bar.gif' >");
			var msg="<div class='product-boxx col-sm-6 col-xs-12'>";
				msg +="<div class='loginArea'>";
				msg +="<div class='contBox pad-top-13'>";
						   
				msg +="<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
				msg +="<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 productBoxSM'>";
				msg +="<p><a title='"+title+"'>"+title+"</a></p>";
				msg +="<img alt='' src='"+url+"' >";
				msg +="<h3>"+price+" points <span class='headTopCorner'></span>";
									
				msg +="<span class='headrightcorner'></span></h3>";
				msg +="</div>";
				msg +="</div>";
							
				msg +="</div>";
				msg +="</div>";
				msg +="</div>";
				
				$('#amazon_prd_list_div').html(msg);
				$('#reward_name_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Reward Name</label><div class='col-sm-9 col-xs-12'><input type='text' name='reward_name' id='reward_name' class='col-xs-12 col-sm-5' required value='"+title+"' readonly ></div></div>");
				$('#reward_point_div').html("<div class='form-group Clearfix' ><label class='col-sm-3 col-xs-12 control-label no-padding-right'><span class='star'>*</span>Points</label><div class='col-sm-9 col-xs-12'><input type='text' name='reward_point' id='reward_point' value='"+price+"' readonly class='col-xs-12 col-sm-5' maxlength='6' required ></div></div>");
				$('#amazon_product_url').val(url);
				$('#amazon_id').val(sku);
				$('input[type="submit"]').removeAttr('disabled');
				$('#savebutton').css("cursor", "pointer");
		
					}
				}
			});
			return false;
		
	}
</script>