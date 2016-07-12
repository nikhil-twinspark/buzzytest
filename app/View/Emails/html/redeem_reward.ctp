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

            <!-- header -->

            <tr height="20">
                            <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;"><?=$msg?></td>
                        </tr>
            <!-- end header -->

            <!-- 1/1 content center -->
            <tr>
                <td>
                    <table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="50"></td>
                        </tr>

                        <tr>
                            <td height="10"></td>
                        </tr>

                    </table>
                </td>
            </tr>

            <!-- 1/3 right -->
            <tr>
                <td>
                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td bgcolor="#ecf0f1" background="img/1-3-pattern.png">
                                <table class="table600" align="center" width="600" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" style="border:1px solid #ffffff;  font-family: 'Open Sans',Arial,sans-serif; font-size: 14px;">
                                                <tbody>
                                                    <tr style="background:#333333;color:#fff;font-weight:bold">
                                                        <td align="center" style="text-align:center;padding:10px" colspan="2">Your order is successfully placed. </td>
                                                    </tr>
                                                    <tr style="background:#ffffff;font-weight:bold">
                                                        <td style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Item Selected:</td>
                                                        <td style="text-align:left;padding:8px 10px;border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff">
                                                            <b><?=$redeem_data['which_reward_description']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px" colspan="2">Verify:</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">First Name</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['first_name']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Last Name</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['last_name']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Card Number</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['card_number']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Phone No.</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['phone']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Email</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['email']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Address</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['street1']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">&nbsp;</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['street2']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">City</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['city']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">State</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['state']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Postal Code</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b><?=$redeem_data['postal_code']?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px">Order Status</td>
                                                        <td align="center" style="border-bottom:1px solid #ffffff;border-right:1px solid #ffffff;border-right:1px solid #ffffff;text-align:left;padding:8px 10px;width:85%">
                                                            <b>Redeemed</b></td>
                                                    </tr>
                                                </tbody></table>


                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
<tr height="30">
                            <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;"><p>Thanks,</p>
<p>The BuzzyDoc Team</p></td>
                        </tr>
            <!-- end 1/3 right -->

            <!-- space divider -->
            <tr>
                <td>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="50"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- end space divider -->





            <!-- footer -->
            <!-- footer -->
            <tr>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    <!-- 1/1 content center -->
                    <table class="table600" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-top:2px solid;">
                        <tr>
                            <td style="margin-bottom: 50px;">
                                &nbsp;
                            </td>
                        </tr>
                        <!-- title -->
                        <tr>
                            <td align="center" colspan="2" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;">
                                <single label="title">BuzzyDoc Patient Rewards Help Desk</single>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;">
                                <single label="title"><a target="_blank" href="mailto:help@buzzydoc.com">help@buzzydoc.com</a> | (888) 696-4753</single>
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-bottom: 30px;">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;">
                                <single label="title"><img src="<?php echo CDN; ?>img/fullcolor-high-rez_logo.jpg" height="50" width="200"></single>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
                                                                    </table>
                                                                    </body>
                                                                    </html>
