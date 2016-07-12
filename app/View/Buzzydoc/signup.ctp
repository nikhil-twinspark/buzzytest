<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BuzzyDoc | Sign-up</title>

    <?php echo $this->Html->css(CDN.'css/mobile_home.css'); ?>
    <?php echo $this->Html->css(CDN.'css/mobile_jquery-ui.default-min.css'); ?>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="form-bg">
        <header class="form-main-header">
            <a href="/buzzydoc/login">
        <?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo')); ?>
            </a>
        </header>
        <div id="form-sign-up" class="wrap-form">
            <form name="sign-up-form" class="login-form" action="/buzzydoc/login" method="post">
                <div class="header">
                    <h1>Sign Up</h1>
                </div>

                <div class="header">
                    <p class="asterisk-info">[*] Asterisk fields are mandatory.</p>
                </div>

                <div class="content cf">
                    <div class="content-col-form">
                                    Card Number :<input name="card_number" id="card_number" type="text" class="input username" placeholder="Card Number [*]"  maxlength="30" value="<?php echo $card_number; ?>" readonly="readonly"/>
                                    <input name="clinic_id" id="clinic_id" type="hidden" maxlength="30" value="<?php echo $clinic_id; ?>"/>
                                    <input type="hidden" name="actionType" value="record_new_account" id='actionType'>
                                </div>
                    <div class="content-col-1">
                        <input name="first_name" id="first_name" type="text" class="input username" placeholder="First Name [*]" onkeypress="clearError()" maxlength="30" autofocus />
                    </div>

                    <div class="content-col-2">
                        <input name="last_name" id="last_name" type="text" class="input username" placeholder="Last Name [*]" onkeypress="clearError()" maxlength="20"/>
                    </div>

                    <div class="content-col-1">
                        <input name="signup-password" id="signup-password" type="password" class="input password" placeholder="Password [*]" onkeypress="clearError()" maxlength="15"/>
                    </div>

                    <div class="content-col-2">
                        <input name="signup-confirm-password" id="signup-confirm-password" type="password" class="input password" placeholder="Confirm Password [*]" onkeypress="clearError()" maxlength="15"/>
                    </div>

                    <div class="content-col-1">
                        <p class="radio-btn-wrap">
                            Gender:
                            <input type="radio" name="gender" id="male"> <label for="male">Male</label>
                            <input type="radio" name="gender" id="female"> <label for="female">Female</label>
                        </p>
                    </div>

                    <div class="content-col-2">
                        <input name="custom_date" id="custom_date" type="text" class="input username" placeholder="Date of Birth [*]" readonly=""/>
                        <div id="date_pick" style="height: 0px; position: relative; top: -10px; z-index:9999" ></div>
                    </div>

                    <span id="email_field">
                        <div class="content-col-1">
                            <input name="email" id="email" type="email" class="input email" placeholder="Email [*]" onblur="checkpatientexist();" maxlength="50"/>
                        </div>

                        <div class="content-col-2" id="pemail"></div>
                    </span>
                    <div id="forLink">
                        <div class="content-col-1">
                            <input name="street1" id="street1" type="text" class="input username" placeholder="Address Line 1 [*]" onkeypress="clearError()" maxlength="200"/>
                        </div>

                        <div class="content-col-2">
                            <input name="street2" id="street2" type="text" class="input username" placeholder="Address Line 2" onkeypress="clearError()" maxlength="200"/>
                        </div>

                        <div class="content-col-1">
                            <select name="state" id="state" onchange="clearError(), getCity(this.value)">
                                <option value="">Select State [*]</option>
                    <?php foreach ($states as $st) { ?>
                                <option value="<?php echo $st['State']['state'] ?>"><?php echo $st['State']['state'] ?></option>
                    <?php } ?>
                            </select>
                            <span id="state-progress"></span>
                        </div>

                        <div class="content-col-2">
                            <select name="city" id="city" onchange="clearError()">
                                <option value="">Select City [*]</option>
                            </select>
                        </div>

                        <div class="content-col-1">
                            <input name="postal_code" id="postal_code" type="text" class="input username" placeholder="Zip Code [*]" onkeypress="clearError()" maxlength="6" onkeyup="this.value = this.value.replace(/\D/g, '')"/>
                        </div>

                        <div class="content-col-2">
                            <input name="phone" id="phone" type="tel" class="input username" placeholder="Phone [*]" onkeypress="clearError()" maxlength="10" onkeyup="this.value = this.value.replace(/\D/g, '')" />
                        </div>
                    </div>
                    <p class="err-msg clear" id="signup-error-msg"></p>
                </div><!--END CONTENT-->

                <div class="footer" id="hid_submit">
                    <input type="button" name="submit" id="signUpBtn" value="Sign Up" class="button" />&nbsp;
                    <span id="signup-progress"></span>
                </div>
                <div class="footer" id="emailexistlink" style="display:none;">
                    <span>This email id exists with us. Click on the Link button to link your account.</span>
                    <input type="button" name="submit" id="signUpBtn" value="Link" class="button" />&nbsp;
                    <span id="signup-progress"></span>
                </div>
            </form> 
        </div>


        <!-- jQuery (JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
                                if (typeof jQuery == 'undefined') {
                                    document.write(unescape("%3Cscript src='js/jquery-1.11.1.min.js' type='text/javascript'%3E%3C/script%3E"));
                                }
        </script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>


    <?php echo $this->Html->script(CDN.'js/buzzydoclanding.js'); ?>

    </body>
</html>
