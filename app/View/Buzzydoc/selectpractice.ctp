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
        <?php $sessionfbuser = $this->Session->read('fbuserdetail'); ?>
        <div id="form-sign-up" class="wrap-form">
            <form name="sign-up-form" class="login-form" action="/buzzydoc/signup" method="post" id="practiceselectform">
                <div class="header">
                    <h1>Sign Up</h1>
                </div>

                <div class="header">
                    <p class="asterisk-info">[*] Asterisk fields are mandatory.</p>
                </div>

                <div class="content cf">
                    <div class="content-col-new">
                        <select name="search_type" id="search_type" onchange="searchClinic();" required="">
                            <option value="">Select Practice Type [*]</option>
                                            <?php foreach ($industryType as $indType) { ?>
                            <option value="<?php echo $indType['IndustryType']['id'] ?>"><?php echo $indType['IndustryType']['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="content cf">
                    <div class="content-col-new">
                        <?php if(isset($sessionfbuser) && !empty($sessionfbuser)){ ?>
                                        <select name="search_practice" id="search_practice" onchange="selectClinic(),checkpatientexist();">
                                            <option value="">Select Practice Name [*]</option>
                                        </select>
                                        <?php }else{ ?>
                                        <select name="search_practice" id="search_practice" onchange="selectClinic();">
                                            <option value="">Select Practice Name [*]</option>
                                        </select>
                                        <?php } ?>
                    </div>
                    <input name="send_card_number" id="send_card_number" type="hidden" maxlength="30" value=""/>
                    <p class="err-msg clear" id="card-error-msg"></p>
                </div>
                <div class="footer">
                    <?php if(isset($sessionfbuser) && !empty($sessionfbuser)){ ?>
                                    <input name="clinic_id" id="clinic_id" type="hidden" value=""/>
                                    <input name="custom_date" id="custom_date" type="hidden" value="<?php echo $sessionfbuser['custom_date']; ?>"/>
                                    <input name="email" id="email" type="hidden" value="<?php echo $sessionfbuser['email']; ?>"/>
                                    <input name="parents_email" id="parents_email" type="hidden" value=""/>
                                    <input name="first_name" id="first_name" type="hidden" value="<?php echo $sessionfbuser['first_name']; ?>"/>
                                    <input name="last_name" id="last_name" type="hidden" value="<?php echo $sessionfbuser['last_name']; ?>"/>
                                    <input name="facebook_id" id="facebook_id" type="hidden" value="<?php echo $sessionfbuser['facebook_id']; ?>"/>
                                    <input name="gender" id="gender" type="hidden" value="<?php echo $sessionfbuser['gender']; ?>"/>
                                    <input name="fb_password" id="fb_password" type="hidden" value="<?php echo $sessionfbuser['password']; ?>"/>
                                    <input name="is_facebook" id="is_facebook" type="hidden" value="1"/>
                                    <input type="hidden" name="actionType" value="record_new_account" id='actionType'>
                                    <div id="hid_submit">
                                        <input type="button" name="submit" id="signFbUpBtn" value="Sign Up" class="button" />&nbsp;<span id="fb-signup-progress"></span>
                                    </div>
                                    <div id="emailexistlink" style="display:none;">
                                        <span>This email id exists with us. Click on the Link button to link your account.</span>
                                        <input type="button" name="submit" id="signFbUpBtn" value="Link" class="button" />&nbsp;
                                        <span id="fb-signup-progress"></span>
                                    </div>
                                    <?php }else{ ?>
                                    <input type="button" name="submit" id="proceedBtn" value="PROCEED" class="button" />
                                    <?php } ?>
                </div>
            </form> 
        </div>


        <!-- jQuery (JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
                            if (typeof jQuery == 'undefined') {
                                document.write(unescape("%3Cscript src='js/jquery-1.11.1.min.js' type='text/javascript'%3E%3C/script%3E"));
                            }
                            
                            
                            $('#practiceselectform').validate({
		rules: {
			search_type: "required",
                search_practice: "required"
			
			
		},
        
        // Specify the validation error messages
		messages: {
			search_type: "Please select practice type.",
                search_practice: "Please select practice name."
			
		},
		showErrors: function(errorMap, errorList) {
			if (errorList.length) {
				var s = errorList.shift();
				var n = [];
				n.push(s);
				this.errorList = n;
			}
			this.defaultShowErrors();
		},
        submitHandler: function(form) {
            form.submit();
        }  
            
         });
        </script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>


    <?php echo $this->Html->script(CDN.'js/buzzydoclanding.js'); ?>

    </body>
</html>
