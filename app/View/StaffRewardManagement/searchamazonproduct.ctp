
<td style="height: 50px" colspan='2'>
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
                                        
                                        if($responseData[$a]['price']>0){
                                            $cls='productBoxSM';
                                        }else{
                                           $cls='productBoxSM'; 
                                        }
				?>
            <!-- start here -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 <?=$cls?>">
                    <p><a title="<?php echo $responseData[$a]['title'] ?>"><?php echo $title1 ?></a></p>
                                                                        <?php if($responseData[$a]['price']>0){ ?>
                    <img alt="" src="<?php echo $responseData[$a]['url'] ?>"  onclick="setAmazonProduct('<?php echo $responseData[$a]['sku'] ?>', '<?php echo $responseData[$a]['url'] ?>', '<?php echo $title1 ?>', '<?php echo $responseData[$a]['price'] ?>')">
                                                                        <?php }else{ ?>
                    <img alt="" src="<?php echo $responseData[$a]['url'] ?>"  >
                                                                        <?php } ?>
                    <h3><?php if($responseData[$a]['price']>0){
                                                                            echo $responseData[$a]['price'].' points';
                                                                        }else{ echo 'Not Available'; }?> 

                        <span class="headTopCorner"></span>
                        <span class="headrightcorner"></span>
                    </h3>
                    <!--
<span class="headTopCorner"><?php echo $responseData[$a]['price'] ?></span>
<span style='text-align:right' id="progress_div_<?php echo $responseData[$a]['sku'] ?>" ></span>
                    <span class="headrightcorner"></span> -->
                </div>
            </div>
            <!---end here-->
                                        <?php 
                                        } ?>	
			<?php } ?>
            <!----------------------------add by sahoo-------------------------->
            <span style="display: block;float: left;text-align: right;width: 100%; margin-left: -57px;" class="clearfix">
					<?php
						//echo "total : ".$total_pages;
						//echo "<br/>current : ".$current_page;
						if($total_pages > 1){ 
							
								for($a=1; $a <= 5; $a++){
                                                                    if($current_page == $a){
                                                                     ?>
                <span style="color: red;"><?php echo $a ?></span>
                                    <?php
                                                                    }else{
                                                                    ?>
                <span onclick="searchAmazonProduct(<?php echo $a ?>)" style='cursor:pointer;'><?php echo $a ?></span>

                                                                    <?php }}
						
							 ?>
                &nbsp; <span id='progress_amz_prd_div_page' ></span>
						<?php } ?>
            </span>

            <!---------------------------end here------------------------------->	
			<?php }else{ ?>
				<?php echo $responseData[0]['error'] ?>
			<?php } ?> 
        </div>
    </div>
</td>



<script type='text/javascript'>


    function setAmazonProduct(sku, url, title, price) {
        var datasrc = "amazon_id=" + sku;

        $.ajax({
            type: "POST",
            url: "<?php echo Staff_Name ?>StaffRewardManagement/checkreward",
            data: datasrc,
            success: function(msg) {
                if (msg == 1) {
                    alert('Reward Already Exist.')
                    return false;
                }
                else {

                    $('#progress_div_' + sku).html("<img src='<?php echo CDN; ?>img/loading-bar.gif' >");
                    var msg = "<td style='height: 50px' colspan='2'>";
                    msg += "<div class='loginArea'>";
                    msg += "<div class='contBox pad-top-13'>";

                    msg += "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>";
                    msg += "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 productBoxSM'>";
                    msg += "<p><a title='" + title + "'>" + title + "</a></p>";
                    msg += "<img  alt='' src='" + url + "' >";
                    msg += "<h3>" + price + " points <span class='headTopCorner'></span>";

                    msg += "<span class='headrightcorner'></span></h3>";
                    msg += "</div>";
                    msg += "</div>";

                    msg += "</div>";
                    msg += "</div>";
                    msg += "</td>";

                    $('#amazon_prd_list_div').html(msg);
                    $('#reward_name_div').html("<td style='height: 50px'><label for='reward_name'>Reward Name </label></td><td><div class='pregInput' style='position: relative;'><input type='text' name='reward_name' id='reward_name' required value='" + title + "' readonly ></div></td>");
                    $('#amazon_point_div').html("<td style='height: 50px'><label for='reward_point'>Points<b style='color: red'>*</b></label></td><td><div class='pregInput' style='position: relative;'><input type='text' name='reward_point' id='reward_point' required value='" + price + "' readonly maxlength='6' ></div></td>");
                    $('#amazon_product_url').val(url);
                    $('#amazon_id').val(sku);
                    $('input[type="submit"]').css('cursor', 'pointer');
                    $('input[type="submit"]').removeAttr('disabled');

                }
            }
        });
        return false;
    }
</script>

<style>
    div.pagination {
        padding: 3px;
        margin: 3px;
        text-align:center;
    }

    div.pagination a {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #fff;

        text-decoration: underline;
        color: #000099;
    }
    div.pagination a:hover{
        border: 1px solid #000099;

        color: #000;
    }
    div.pagination a:active {
        border: 1px solid #000099;

        color: #f00;
    }
    div.pagination span.current {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #fff;

        font-weight: bold;
        background-color: #fff;
        color: #000;
    }
    div.pagination span.disabled {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #EEE;

        color: #DDD;
    }
</style>
