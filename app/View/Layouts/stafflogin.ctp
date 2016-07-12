<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
                <link rel="shortcut icon" href="<?php echo CDN; ?>img/favicon.ico">
		<title>Staff</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
                <?php 
                //echo $this->Html->css(CDN.'css/style.css');
                echo $this->Html->css(CDN.'css/assets/css/bootstrap.min.css');
                echo $this->Html->css(CDN.'css/assets/css/font-awesome.min.css');
                echo $this->Html->css(CDN.'css/assets/css/custom.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-fonts.css');
                echo $this->Html->css(CDN.'css/assets/css/ace.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace-rtl.min.css');
                echo $this->Html->css(CDN.'css/assets/css/ace.onpage-help.css');
             
                echo $this->Html->script(CDN.'js/jquery.js');
                echo $this->Html->script(CDN.'js/jquery.mobile.custom.min.js');
              
                ?>
                
	<script type="text/javascript">
      window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var n=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(n?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(a,o);for(var r=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["clearEventProperties","identify","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=r(p[c])};
      heap.load("3420711851");
    </script>
	</head>

	<?php echo $this->fetch('content'); ?>
</html>
