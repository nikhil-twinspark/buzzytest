	 <?php
     $flashMsg = $this->Session->flash();
      if($flashMsg!=''){ 
		 ?>
		 <script>
		 var flashMsg = '<?php echo strip_tags($flashMsg);?>';
		 alert(flashMsg);
		 </script>
		 <?php
		 }
		 ?>
