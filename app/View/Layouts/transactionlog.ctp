<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Transaction Log</title>
<?php

		echo $this->Html->css(CDN.'css/style.css');
		echo $this->Html->css(CDN.'css/template.css');
		 echo $this->Html->css(CDN.'css/form_error.css');
                echo $this->Html->script(CDN.'js/jquery.js');
                echo $this->Html->script(CDN.'js/jquery.validate.js');
                echo $this->Html->script(CDN.'js/common.js');

	?>
<link type="text/css" rel="stylesheet" href="http://fast.fonts.net/cssapi/6c213e3f-cce4-43fd-820d-c65d4bf4609a.css"/>
</head>

<body>
 <div id="topNav">
   <div class="container">
    
     <div class="grid50 topContact pull-right ">
             
     </div>
   </div>
 </div><!--top navigation-->
  <div class="container">
   <div class="header">
   <div class="grid50 pull-left">
    <div class="logoArea">
    <?php echo $this->html->image(CDN.'img/logo.png',array('width'=>'220','height'=>'50','alt'=>'logo','title'=>'logo'));?>
    </div>
   </div><!--logo area -->
    </div><!--header -->
			
			<?php echo $this->fetch('content'); ?>
		<div class="Clearfix"></div>
		<div id="footer"></div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
