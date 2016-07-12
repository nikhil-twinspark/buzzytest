	 <?php
      $flashMsg = strip_tags($this->Session->flash());
      if($flashMsg!=''){
      $exp_msg=explode(':',$flashMsg);
      //print_r($exp_msg);die;
      if(isset($exp_msg[0]) && $exp_msg[0]=='ERR'){ 
		 ?>
		 <script>
		 var flashMsg = '<?php echo $exp_msg[1];?>';
		 alert(flashMsg);
		 </script>
		 <?php
		 }else{ ?>
			
			 <div class="message" id="flashMessage"><?php  echo $exp_msg[0]; ?></div>
		<?php
		 }
	 }
		 ?>
