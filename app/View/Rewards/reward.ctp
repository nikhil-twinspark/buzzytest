<style type="text/css">
    .detail h2.challengeDetail { margin-top:0;}
</style>
<?php $sessionpatient = $this->Session->read('patient'); 
    
?>
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

            <div class="row page_main">
                <div class="col-lg-12 page_sort">
                    SORT BY <span> &#62 </span>
                    Category :
                    <form name="reward_category_frm" id="reward_category_frm" action="<?php echo Staff_Name?>rewards/reward" method="post">
                        <select name="category" id="category" onchange="do_shoring_rewards()">
                            <option value=''>Select Category</option>
                            <?php foreach ($rowCategory as $val) { ?>
                                <option value="<?php echo $val['Category']['category'] ?>" <?php if(isset($sessionpatient['category_name']) && $sessionpatient['category_name']==$val['Category']['category']){ echo "selected"; } ?>><?php echo $val['Category']['category'] ?></option>
                            <?php } ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="contBox pad-top-13">
                <?php
               
					$thispage = Staff_Name . 'rewards/reward';
					$num = count($Reward); // number of items in list
					$per_page = 6; // Number of items to show per page
					$showeachside = 5; //  Number of items to show either side of selected page
					$pg = floor($num / $per_page);
					if($num==($pg*6)){
					$pg=$pg-1;
					}

					$end_page = $pg * 6;
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
					$reward_array_final[$k] = $Reward[$x];
					$k++;
					};
                
                if (isset($sessionpatient['customer_info']['ClinicUser']['local_points'])) {
                    
                     $current_balance = $sessionpatient['customer_info']['ClinicUser']['local_points'];   
                    
                } else {
                    $current_balance = '0';
                }
            
                if (isset($reward_array_final) && sizeof($reward_array_final) > 0) {
               //echo "<pre>";print_r($reward_array_final);die;
                    foreach ($reward_array_final as $reward_item => $discard) {
                        $need_more = $discard[0]['points'] - $current_balance;
                        if (!empty($discard[0]['description']) && !empty($discard[0]['points'])) {  // ignore blank entries
                       ?>
                              
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                                   
                                            <form action="<?= Staff_Name ?>rewards/rewarddetail/" method="POST" name="reward_form_<?= $discard[0]['id'] ?>">

                                                <input type="hidden" name="which_reward_id" value="<?= $discard[0]['id'] ?>">
                                                <input type="hidden" name="which_reward_description" value="<?= urlencode($discard[0]['description']) ?>">
                                                <input type="hidden" name="which_reward_level" value="<?= $discard[0]['points'] ?>">
                                              
                                               

                                                <?php
                                                $uploadFolder = "rewards/" . $sessionpatient['api_user'];
                                                //full path to upload folder
                                                $uploadPath = WWW_ROOT . 'img/' . $uploadFolder;
                                          
                                                    ?>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 productBoxSM">
                                                        <p><a title="<?= $discard[0]['description'] ?>" ><?php
                                                                if (strlen($discard[0]['description']) > 40) {
                                                                    echo substr($discard[0]['description'], 0, 40) . '...';
                                                                } else {
                                                                    echo $discard[0]['description'];
                                                                }
                                                                ?></a></p>
                                                        <?php
                                                        echo '<img src="' . $discard[0]['imagepath'] . '" alt="" width="175" height="117">';
                                                        ?>
                                                        <h3><?= $discard[0]['points'] ?> points
                                                            <?php if ($need_more > 0) { ?>
                                                                <span>You need <?= $need_more ?> more points.</span>
                    <?php }else{ ?>
                     <?php if ($sessionpatient['is_mobile'] == 0) { ?>
                    <span>Bravo! <a class="hand-icon" onclick="lightbox(<?= $discard[0]['id'] ?>);">Click to redeem now</a></span>
                                            
                                                    <?php } else { ?>
                                                    <span>Bravo! <a class="hand-icon" onClick="document.reward_form_<?=$discard[0]['id']?>.submit();">Click to redeem now</a></span>
                                                
                                                    <?php } ?>  
                    <?php } ?>
                                                            <span class="headTopCorner"></span>
                                                            <span class="headrightcorner"></span>
                                                        </h3>
                                                    </div>
                                                </form>
                                            </div>

                <?php 
            
        }
    }
} else { ?>
                            <div class="col-md-4 col-xs-6">
                                <div class="col-lg-12 productBoxSM">
                                    <p>No Rewards Available!!</p>
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







        <!-- Modal -->
        <div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-sm">
                <div class="modal-content popup ">
                    <div class="row rowcont">
                        <div class="modal-header col-md-12">
                            <a class="close closebtn" onclick="close_form();">&times;</a>
                        </div>
                    </div>
                    <form action="" method="POST" name="reward" id="RewardForm">
                        <input type="hidden" name="action" value="confirm_redeem">
                        <input type="hidden" name="reward_id" id="reward_id" value="">
                        <div class="modal-body clearfix">
                            <div class=" row  detail">
                                <div class="col-xs-12 clearfix">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div id="rewardImg"></div>
										
                                    <?php
                                    if ($sessionpatient['profile_comp'] < 100) { ?>
                                        <span class="complete">
                                            <?php
                                        echo 'Click the link below to complete your profile, then you can redeem!';
                                        ?>
                                        </span>
                                        <a href="<?= Staff_Name ?>rewards/editprofile/" class="btn btn-primary buttondflt pull-righ profile_comp">COMPLETE YOUR PROFILE</a>
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" class="btn btn-primary buttondflt redeem" value="REDEEM NOW" >
                                    <?php } ?>
                                        <div id="wishlist_div">
                                        </div>

                                        <div class="socialIcon">
                                            <p class="pull-left">SHARE</p>


                                            <a href="" id='twlink' target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/twitter.png', array('width' => '23', 'height' => '22', 'alt' => 'twitter', 'title' => 'twitter')); ?></a>
                                            <a href="" id="fblink" target="_blank"><?php echo $this->html->image(CDN.'img/reward_imges/facebook.png', array('width' => '23', 'height' => '22', 'alt' => 'facebook', 'title' => 'facebook')); ?></a>
                                            <a href="" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                                    return false;" id="gplink"><?php echo $this->html->image(CDN.'img/reward_imges/googleplus.png', array('width' => '23', 'height' => '22', 'alt' => 'googleplus', 'title' => 'googleplus')); ?></a>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h2 class="challengeDetail">


                                            <span id="reward_name"></span>
                                        </h2>
                                        <p class="description">
                                            <strong>Details/Instructions:</strong>
                                        <div id="desc"></div>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>   <!--popup-->     
        <div class="" id="Mymodel1"></div>

        <script>


            function lightbox(reward_id) {

                $('#myModal').addClass("modal fade popupBox in");
                $('#myModal').attr('aria-hidden', false);
                $('#myModal').css('display', 'block');
                $('#Mymodel1').addClass('modal-backdrop fade in');
                $.ajax({
                    type: "POST",
                    data: "reward_id=" + reward_id,
                    dataType: "json",
                    url: "<?= Staff_Name ?>rewards/getreward/",
                    success: function(result) {
                        $('#reward_name').text(result.rewards.Reward['points'] + ' Points');
                        $('#desc').text(result.rewards.Reward['description']);
                        $('#rewardImg').html(result.rewards.Reward['imagepath']);
                        $('#reward_id').val(result.rewards.Reward['id']);
                        $("a#fblink").attr("href", "http://www.facebook.com/sharer.php?u=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&t=Share on Facebook");
                        $("a#twlink").attr("href", "https://twitter.com/intent/tweet?url=<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "&amp;text=Share On twitter&amp;via=buzzydoc");
                        $("a#gplink").attr("href", "https://plus.google.com/share?url={<?php echo $_SERVER['HTTP_HOST']; ?>/rewards/redeemreward/" + result.rewards.Reward['id'] + "}");
                        $("#RewardForm").attr("action", "<?= Staff_Name ?>rewards/redeemreward/" + result.rewards.Reward['id'])
                        if (result.WishLists == 1) {
                            $('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Added To WishList">');
                        }
                        else {
                            $('#wishlist_div').html('<input type="button" class="btn btn-primary buttondflt pull-righ" value="Add To WishList" onclick="wish();">');
                        }
                    }});


            }
            function close_form() {

                $('#myModal').addClass("modal fade popupBox");
                $('#myModal').attr('aria-hidden', true);
                $('#myModal').css('display', 'none');
                $('#Mymodel1').removeClass('modal-backdrop fade in');
            }



            function wish() {
                var reward_id = $('#reward_id').val();
                
                $.ajax({
                    type: "POST",
                    data: "reward_id=" + reward_id,
                    dataType: "json",
                    url: "<?= Staff_Name ?>rewards/addwishlist/",
                    success: function(result) {
                        
                        if (result == 1) {
                            $('#wishlist_div').html('<input type="button" class="btn buttondflt pull-righ" value="Added To WishList">');
                        }
                    }});
            }
            function do_shoring_rewards(category_name){
                     document.reward_category_frm.submit();
                
            }
            
            
        </script>
