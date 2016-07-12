

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <title>BuzzyDoc | Emailer</title>
    </head>

    <body>
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
            a { color: #2ecc71; text-decoration: none; }
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
            /*hide preheader by deafult */
            div.preheader{line-height:0px;font-size:0px;height:0px;display:none !important;display:none;visibility:hidden;}
        </style>
        <div class="preheader"></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">

            <tr>
                <td>
                    <!-- 1/1 content center -->
                    <table class="table600" width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                        <!-- title -->
                        <tr>
                            <td align="left" colspan="2" style="font-family: 'Open Sans', Arial, sans-serif; font-size:18px;">
                                <single label="title"><?php echo $msg; ?></single>
                            </td>
                        </tr>
                        <!-- end title -->
                        <tr>
                            <td style="margin-bottom: 50px;">
                                &nbsp;
                            </td>
                        </tr>

                        <!-- content -->

                        <!-- end content -->
                    </table>
                    <!-- end 1/1 content center -->

                    <!-- footer -->
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
