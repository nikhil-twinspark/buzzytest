<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuzzyDoc | Sign-in</title>

    <?php echo $this->Html->css(CDN.'css/mobile_home.css'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="form-bg">
    <header class="form-main-header">
      <a href="/login">
        <?php echo $this->html->image(CDN.'img/images_buzzy/BuzzyDoc_regular_logo.png', array('title' => 'BuzzyDoc', 'alt' => 'BuzzyDoc logo')); ?>
      </a>
    </header>
    <div id="form-sign-up" class="wrap-form">
      <form name="sign-up-form" class="login-form" action="" method="post">
            <div class="header">
                <h1 class="sign-in-heading">Sign In</h1>
            </div>

        <div class="content form-content-wrap cf">
            <div class="content-col-1">
                  <input name="username" id="username" type="text" class="input username" placeholder="Email Id Username" onkeypress="clearMsg('error-msg')"/>
            </div>

            <div class="content-col-2">
              <input name="password" id="password" type="password" class="input password" placeholder="Password" onkeypress="clearMsg('error-msg')" maxlength="15"/>

            </div>
            <p id="error-msg" class="err-msg"></p>
        </div>
     

            <div class="footer">
                                    <div class="content">
                                        <p style="text-align: left; margin-bottom: 10px; ">
                                        <a href="/buzzydoc/mforgot" title="Forgot Password" id="home-login3" style="color: #666;" >Forgot Password ?</a>
                                        </p>
                                    <span id="sign-progress"></span>
                                    <p>
                                        <input type="button" name="submitBtn" id="submitBtn" value="Login" class="button" /></p>
                                    </div>
                                    
                                </div>
            
          </div>
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
    <?php echo $this->Html->script(CDN.'js/buzzydoclanding.js'); ?>

  </body>
</html>
