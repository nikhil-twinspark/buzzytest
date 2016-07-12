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
        <form name="forgot-form" class="login-form" action="" method="post" onsubmit="return false;">
            <div class="header">
                <h1 class="sign-in-heading">Forgot Password</h1>
            </div>

        <div class="content form-content-wrap cf">
           

            <div class="content-col-2">
              
              <input name="femail" id="femail" type="text" class="input username" placeholder="Email Id" onkeypress="clearMsg('error-msg-forgot')" onblur="checkuserexist();" onmouseout="checkuserexist();"/>
                                    <div id="cardnumber">&nbsp;
                                    </div>

            </div>
            <p id="error-msg-forgot" class="err-msg"></p>
        </div>
     

            <div class="footer">
                <span id="forgot-progress"></span>
                <input type="button" name="forgotBtn" id="forgotBtn" value="Submit" class="button" />
               
                
            <div>
            
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
      
      
        function checkuserexist(){
        $('#forgotBtn').attr('disabled');
        var email=$('#femail').val();
        
        if($.trim(email)!=''){
        $.ajax({
	  type:"POST",
	  data:"email="+email,
	  url:"/buzzydoc/checkuser/",
	  success:function(result){
              $('#forgotBtn').removeAttr("disabled")
              $('#cardnumber').html(result);
              
	}
    });
    }
    return false;
    }

    </script>
    <?php echo $this->Html->script(CDN.'js/buzzydoclanding.js'); ?>

  </body>
</html>
