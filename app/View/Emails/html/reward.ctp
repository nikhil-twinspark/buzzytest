<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <title>BuzzyDoc Emailer</title>
        <style type="text/css">
            .ReadMsgBody { width: 100%; background-color: #ffffff; }
            .ExternalClass { width: 100%; background-color: #ffffff; }
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
            html { width: 100%; }
            body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }
            table { border-spacing: 0; border-collapse: collapse; }
            img { display: block !important; }
            table td { border-collapse: collapse; }
            .yshortcuts a { border-bottom: none !important; }
            a { color: #3498db; text-decoration: none; }
            .textbutton a { font-family: 'open sans', arial, sans-serif !important; color: #ffffff !important; }
            .footer-link a { color: #7f8c8d !important; }
            @media only screen and (max-width: 640px) {
                body { width: auto !important; }
                table[class="table600"] { width: 450px !important; }
                table[class="table-inner"] { width: 90% !important; }
                table[class="table2-2"] { width: 47% !important; text-align: left !important; }
                table[class="table3-3"] { width: 100% !important; text-align: center !important; }
                table[class="table1-3"] { width: 29% !important; }
                table[class="table3-1"] { width: 64% !important; text-align: left !important; }
                /* Image */
                img[class="img1"] { width: 100% !important; height: auto !important; }
            }
            @media only screen and (max-width: 479px) {
                body { width: auto !important; }
                table[class="table600"] { width: 290px !important; }
                table[class="table-inner"] { width: 82% !important; }
                table[class="table2-2"] { width: 100% !important; text-align: left !important; }
                table[class="table3-3"] { width: 100% !important; text-align: center !important; }
                table[class="table1-3"] { width: 100% !important; }
                table[class="table3-1"] { width: 100% !important; text-align: center !important; }
                /* image */
                img[class="img1"] { width: 100% !important; }
                img[class="img-hide"] { width: 0px !important; height: 0px !important; line-height: 0px !important; max-height: 0px !important; max-width: 0px !important; }
            }
        </style>
    </head>

    <body>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">



            <!-- end header -->

            <!-- 1/1 content center -->
            <tr>
                <td>
                    <table class="table600" width="600" border="0" align="left" cellpadding="0" cellspacing="0">
                        <!-- title -->
                        <tr>
                            <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;"><?=$msg?></td>
                        </tr>


                        <!-- content -->
                        <tr>
                            <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; line-height:24px;">
                           <?php if(isset($reward_name) && $reward_name!=''){ ?> <p style="font-family: 'Open Sans', Arial, sans-serif; padding: 0px; margin: 0px;"><strong>Reward Name:</strong><?=$reward_name?></p>
                                <strong>Reward Image:</strong> <?=$reward_image?><br /><?php } else if(isset($order_number) && $order_number!=''){ ?> <p style="font-family: 'Open Sans', Arial, sans-serif; padding: 0px; margin: 0px;"><strong>Order Number:</strong><?=$order_number?></p>
                                <strong>Status:</strong> <?=$status?><br /><?php }else if(isset($card_number) && $card_number!=''){ ?> <p style="font-family: 'Open Sans', Arial, sans-serif; padding: 0px; margin: 0px;"><strong>Assigned Card Number:</strong><?=$card_number?></p>
                            <?php } ?>
                             <?php if(isset($link) && $link!=''){ echo $link; } ?></td>
                        </tr>
                        <!-- end content -->
                    </table>
                </td>
            </tr>

            <tr>

                <td>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="50"></td>
                        </tr>
                        <tr>
                            <td bgcolor="#333333">

                                <table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">

                                    <tr>
                                        <td>

                                            <table class="table3-3" width="183" border="0" align="left" cellpadding="0" cellspacing="0">

                                                <tr>
                                                    <td height="16" style="padding-top: 10px;">
                                                        <a href="#"><img src="<?php echo CDN; ?>img/lamparski/lamparski_footer_image" alt="logo" title="logo"/> </a>
                                                    </td>
                                                </tr>

                                            </table>

                                            <!--End Space-->

                                            <table class="table3-3" width="392" border="0" align="right" cellpadding="0" cellspacing="0">

                                                <tr>
                                                    <td  align="right" style=" padding-top: 5px;">
                                                        <table>

                                                            <tr>
                                                                <td>
                                                                    <span style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; text-align: right; color:#fff; line-height:28px;">Follow Us</span>
                                                                </td>
                                                            <?php if(isset($theme['twitter_url']) && $theme['twitter_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['twitter_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/twitter.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['facebook_url']) && $theme['facebook_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['facebook_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/facebook.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['google_url']) && $theme['google_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['google_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/googleplus.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['instagram_url']) && $theme['instagram_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['instagram_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/instagram.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['pintrest_url']) && $theme['pintrest_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['pintrest_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/pinterest.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['yelp_url']) && $theme['yelp_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['yelp_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/yelp.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['youtube_url']) && $theme['youtube_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['youtube_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/you-tube.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>
                                                           <?php if(isset($theme['healthgrade_url']) && $theme['healthgrade_url']!=''){ ?>
                                                                <td>
                                                                    <a href="<?php echo $theme['healthgrade_url'];?>" target="_blank"><img src="<?php echo CDN; ?>img/reward_imges/HealthGrades.png" height=""/> </a>
                                                                </td>
                                                           <?php } ?>

                                                            </tr>
                                                        </table>
                                                    </td>

                                                </tr>
                                                <!-- content -->
                                                <tr>
                                                    <td class="footer-link" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; text-align: right; color:#fff; line-height:28px;">


                                                        <span >Support: help@buzzydoc.com<br>
                                                                (888) 696-4753<br>
                                                                    Your information is safe <br>
                                                        <?php if(isset($Unsubscribe) && $Unsubscribe!=''){ ?>
                                                                        <a href="<?php echo $Unsubscribe; ?>">Unsubscribe</a>
                              <?php } ?>
                                                                        </span>
                                                                        </td>
                                                                        </tr>
                                                                        <!-- end content -->
                                                                        </table>
                                                                        </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="20"></td>
                                                                        </tr>
                                                                        </table>
                                                                        </td>
                                                                        </tr>

                                                                        </table>
                                                                        </td>
                                                                        </tr>   
                                                                        </table>
                                                                        </body>
                                                                        </html>
