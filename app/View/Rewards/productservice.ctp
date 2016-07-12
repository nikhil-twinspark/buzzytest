
<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
    <div id="logo"><?php if (isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url'] != '') { ?>
        <a href="<?= Staff_Name ?>rewards/home"><img src="<?= S3Path.$sessionpatient['Themes']['patient_logo_url'] ?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
        <?php } else { ?>
        <a href="<?= Staff_Name ?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg', array('width' => '246', 'height' => '88', 'alt' => 'Pure Smiles')); ?></a>
        <?php } ?></div>

    <div id="navimob"><a href="#" id="pull">
            <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png', array('width' => '31', 'height' => '26')); ?>

        </a></div>

    <?php echo $this->html->image(CDN.'img/reward_imges/reward_mobile.jpg'); ?>

</div>


<div class=" clearfix">
    <?php echo $this->element('left_sidebar'); ?>
    <div class="col-lg-9 rightcont">
        <div class="banner"> 
        <?php
        //echo "<pre>";print_r($sessionpatient['Themes']);die;
        if(isset($sessionpatient['Themes']['backgroud_image_url'])){ ?>
            <img src="<?=S3Path.$sessionpatient['Themes']['backgroud_image_url']?>" width='730' height='250' class="img-responsive"/>
        <?php }else{ ?>
         <?php echo $this->html->image(CDN.'img/reward_imges/banner2.png', array('width' => '730', 'height' => '250', 'class' => 'img-responsive')); ?>
        <?php }
         ?>

        </div>
        <div class="loginArea">

            <div class="contBox pad-top-13">
                <?php
              
               
					$thispage = Staff_Name . 'rewards/productservice';
					$num = count($ProductService); // number of items in list
					$per_page = 12; // Number of items to show per page
					$showeachside = 5; //  Number of items to show either side of selected page
					$pg = floor($num / $per_page);
					if($num==($pg*12)){
					$pg=$pg-1;
					}

					$end_page = $pg * 12;
					if (!isset($_GET['start'])) {
					$start = 0;  // Current start position
					} else {
					$start = $_GET['start'];
					}
					$max_pages = ceil($num / $per_page); // Number of pages
					$cur = ceil($start / $per_page) + 1; // Current page number
					$reward_array_final = array();
					$k = 0;
					for ($x = $start; $x < min($num, ($start + $per_page)); $x++) {
					$reward_array_final[$k] = $ProductService[$x];
					$k++;
					};

                if (isset($sessionpatient['customer_info']['user']['points'])) {
                    
                     $current_balance = $sessionpatient['customer_info']['user']['points'];   
                    
                } else {
                    $current_balance = '0';
                }
            
                if (isset($reward_array_final) && sizeof($reward_array_final) > 0) {
               //echo "<pre>";print_r($reward_array_final);die;
                    foreach ($reward_array_final as $reward_item => $discard) {
                        if($discard['ProductService']['from_us']==1){
                            $need_more = round($discard['ProductService']['points'] - $sessionpatient['perclinicbuzzypoints'][$sessionpatient['clinic_id']]);
                        }else{
                             $need_more = $discard['ProductService']['points'] - $current_balance;
                        }
                        
                
                        if (!empty($discard['ProductService']['title']) && !empty($discard['ProductService']['points'])) {  // ignore blank entries
                       ?>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >

                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 productBoxSM1">
                        <p title="<?= $discard['ProductService']['title'] ?>" ><?php
                                                                if (strlen($discard['ProductService']['title']) > 40) {
                                                                    echo substr($discard['ProductService']['title'], 0, 40) . '...';
                                                                } else {
                                                                    echo $discard['ProductService']['title'];
                                                                }
                                                                ?></p>

                        <h3><?= $discard['ProductService']['points'] ?> points
                                                            <?php if ($need_more > 0) { ?>
                            <span>You need <?= $need_more ?> more points.</span>
                    <?php }else{ ?>
                     
                            <span>Bravo! 
                                <a class="hand-icon" onclick="redemed(<?php echo $sessionpatient['customer_info']['user']['id']; ?>,<?php echo $discard['ProductService']['id']; ?>,<?php echo $discard['ProductService']['points']; ?>);">Click to redeem now</a>
                        
                            </span>

                    <?php } ?>
                            <span class="headTopCorner"></span>
                            <span class="headrightcorner"></span>
                        </h3>
                    </div>

                </div>

                <?php 
            
        }
    }
} else { ?>
                <div class="col-md-4 col-xs-6">
                    <div class="col-lg-12 productBoxSM">
                        <p>No Products/Services Available!!</p>
                    </div>
                </div>
                            <?php } ?>
                <span class="pagination"> 
                            <?php
                            $eitherside = ($showeachside * $per_page);
                            if ($start + 1 > $eitherside) {
                                ?>
                    <a class="" href="<?php print("$thispage"); ?>"><<</a>
                            <?php
                            }
                            $pg = 1;
                            for ($y = 0; $y < $num; $y+=$per_page) {
                                $class = ($y == $start) ? "pageselected" : "";
                                if (($y > ($start - $eitherside)) && ($y < ($start + $eitherside))) {
                                    ?>
                    &nbsp;<a class="<?php print($class); ?>" href="<?php print("$thispage" . ($y > 0 ? ("?start=") . $y : "")); ?>"><?php print($pg); ?></a>&nbsp; 
                                    <?php
                                }
                                $pg++;
                            }
                            if (($start + $eitherside) < $num) {
                                ?>
                    <a class="" href="<?php print("$thispage" . ($end_page > 0 ? ("?start=") . $end_page : "")); ?>">>></a>
<?php }
?>
                </span>
            </div>
        </div>
    </div>
</div>
<div id="redeemload" style="display: none;"><?php echo $this->html->image(CDN.'img/loading52.gif'); ?></div>
<script>


    function redemed(user_id,product_id,points) {
        var r = confirm("Are you sure you want to redeem this product?");
        if (r == true)
        {
            $("#redeemload").css("display", "block");
            $.ajax({
                type: "POST",
                data: "user_id=" + user_id+'&product_id='+product_id+'&points='+points,
                dataType: "json",
                url: "<?= Staff_Name ?>rewards/redeemlocproduct/",
                success: function(result) {
                    if(result==1){
                        alert('You have redeemed product successfully.');
                        location.reload();
                        $("#redeemload").css("display", "none");
                    }else if(result==2){
                        alert('You do not have sufficient balance.');
                    }else{
                        alert('Unable to redeem. Please contact buzzydoc admin.');
                    }
                }
                });

        }
        else
        {
            return false;
        }


    }



</script>
