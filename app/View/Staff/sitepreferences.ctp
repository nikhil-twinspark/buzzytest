<?php

$sessionstaff = $this->Session->read('staff');
	$GLOBALS['menus']['public_top_nav']							= array('login'=>'Login','new_account_how'=>'Help');

	$GLOBALS['menus']['loggedin_top_nav']						= array('home'=>'Find Patient','page_one'=>'Contests','rules'=>'Instructions','activate_account'=>'Register Card','new_account'=>'Add Customer','logout'=>'Logout');

	$GLOBALS['menus']['loggedin_customer_top_nav']			= array('add'=>'Reward Points','redeem'=>'Redeem','balance'=>'Patient History','myinfo'=>'Patient Info','page_two'=>'Apointments','page_three'=>'Messages');

	$GLOBALS['menus']['public_footer_links']					= array('login'=>'Login','new_account_how'=>'Help');

	$GLOBALS['menus']['loggedin_footer_links']				= array('home'=>'Find Patient','page_one'=>'Contests','rules'=>'Instructions','activate_account'=>'Register Card','new_account'=>'Add Customer','logout'=>'Logout');

	$GLOBALS['menus']['loggedin_customer_footer_links']	= array('add'=>'Reward Points','redeem'=>'Redeem','balance'=>'Patient History','myinfo'=>'Patient Info','page_two'=>'Apointments','page_three'=>'Messages');
?>
    <div class="contArea">
     <div class="grid100">
      <div class="transactionform">
      <div class="headBox">
      <form action="sitepreferences" method="POST" name="site_preferences_form">

								<input type="hidden" name="action" value="record_site_preferences">

								<input type="hidden" name="fields_tab" value="customers">

								<input type="hidden" name="final_submit" value="yes">
								<input type="hidden" value="<?=$sessionstaff['var']['staff_name']?>" name="user_name">
<input type="hidden" value="<?php echo $sessionstaff['var']['staff_password'];?>" name="user_password">
<input type="hidden" value="<?php echo $sessionstaff['admin']['loginName']; ?>" name="admin_name">
<input type="hidden" value="<?php echo $sessionstaff['admin']['loginPassword']; ?>" name="admin_password">
       <label> Turn on Text Editing: </label>
        <input type="checkbox" name="edit_text" value="true" <?php if (!empty($sessionstaff['preferences']['edit_text']) && $sessionstaff['preferences']['edit_text'] == 'true') { echo 'CHECKED';} ?>>
        <input	type="button" value="Save Changes"	class="button up large hand-icon"	name="myinfo_submit" onClick="this.form.submit();">

								</div>
     
     </div>
        <div class="siteprefrence">
           <div class="grid50 pull-left">
           <div class="prefrencecont">
           <h1>Nav Bar: Not Logged-In</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['public_top_nav'] as $public_top_nav_item=>$top_nav_val) { ?>
             <li>
             <input type="checkbox" name="public_top_nav_item[]" value="<?=$public_top_nav_item?>" <?php if (in_array($public_top_nav_item, $sessionstaff['preferences']['public_top_nav'])) { echo ' CHECKED';} ?>>
              <label ><?=$top_nav_val?></label>
              </li>
             <?php } ?>
            </ul>
            </div>
            <div class="prefrencecont">
           <h1>Nav Bar: Logged-In</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['loggedin_top_nav'] as $loggedin_top_nav_item=>$top_nav1_val) { ?>
             <li>
             <input type="checkbox" name="loggedin_top_nav_item[]" value="<?=$loggedin_top_nav_item?>" <?php if (in_array($loggedin_top_nav_item, $sessionstaff['preferences']['loggedin_top_nav'])) { echo ' CHECKED';} ?>>
              <label ><?=$top_nav1_val?></label>
              </li>
            <?php } ?>
            </ul>
            </div>
            <div class="prefrencecont">
           <h1>Logged-in Customer Top Nav</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['loggedin_customer_top_nav'] as $loggedin_top_nav_item=>$loggedin_top_nav_item_val) { ?>
             <li>
             <input type="checkbox" name="loggedin_customer_top_nav_item[]" value="<?=$loggedin_top_nav_item?>" <?php if (in_array($loggedin_top_nav_item, $sessionstaff['preferences']['loggedin_customer_top_nav'])) { echo ' CHECKED';} ?>>
              <label ><?=$loggedin_top_nav_item_val?></label>
              </li>
             <?php } ?>  
            </ul>
            </div>
          </div>
          <div class="grid50 pull-right ">
          
          <div class="prefrencecont marginleft15">
           <h1 >Footer Links: Not Logged-In</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['public_footer_links'] as $public_footer_nav_item=>$footer_nav_val) { ?>
             <li>
              <input type="checkbox" name="public_footer_nav_item[]" value="<?=$public_footer_nav_item?>" <?php if (in_array($public_footer_nav_item, $sessionstaff['preferences']['public_footer_links'])) { echo ' CHECKED';} ?>>
              <label ><?=$footer_nav_val?></label>
             </li>
               <?php } ?>
            </ul>
            </div>
           <div class="prefrencecont marginleft15">
           <h1>Footer Links: Logged-In</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['loggedin_footer_links'] as $loggedin_footer_nav_item=>$loggedin_footer_nav_item_val) { ?>
             <li>
             <input type="checkbox" name="loggedin_footer_nav_item[]" value="<?=$loggedin_footer_nav_item?>" <?php if (in_array($loggedin_footer_nav_item, $sessionstaff['preferences']['loggedin_footer_links'])) { echo ' CHECKED';} ?>>
              <label ><?=$loggedin_footer_nav_item_val?></label>
              </li>
              
            <?php } ?>
            </ul>
            </div>
            <div class="prefrencecont marginleft15">
           <h1>Logged-in Customer Footer</h1>
            <ul>
				<?php foreach ($GLOBALS['menus']['loggedin_customer_footer_links'] as $loggedin_footer_nav_item=>$loggedin_footer_nav_item_val) { ?>
             <li>
             <input type="checkbox" name="loggedin_customer_footer_nav_item[]" value="<?=$loggedin_footer_nav_item?>" <?php if (in_array($loggedin_footer_nav_item, $sessionstaff['preferences']['loggedin_customer_footer_links'])) { echo ' CHECKED';} ?>>
              <label ><?=$loggedin_footer_nav_item_val?></label>
              </li>
             <?php } ?>
            </ul>
            </div>
          </div>
          <div class="grid50 pull-left">
          <div class="customer">
          <div class="headBox_customer">
              <h1>Customer data fields
              <?php if (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'transactions') { ?>
              <input type="button"	value="Edit" class="default marginbottom15 hand-icon"	onClick="this.form.fields_tab.value='customers';this.form.final_submit.value='no';this.form.submit();">
              <?php } ?>
             <!-- <span class="backcard">Click and drag to reorder the fields:</span> -->
            
              </h1>
            </div>
            <?php if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) { ?>
            <ul>
			<?php if (!empty($sessionstaff['preferences']['customer_fields_order'])) {

				// itterate through each preference.

				foreach ($sessionstaff['preferences']['customer_fields_order'] as $field_sorted) {
					

					if (!empty($sessionstaff['customer_fields'][$field_sorted])) {

						if ($sessionstaff['customer_fields'][$field_sorted]['show'] != 'N') { ?>
            <li>
			<input type="checkbox" name="fields_to_show[]" value="<?=$field_sorted?>" <?php if (is_array($sessionstaff['preferences']['fields_to_show']) && in_array($field_sorted, $sessionstaff['preferences']['fields_to_show'])) {

													echo 'CHECKED ';

												} ?>>
            <label><?=$sessionstaff['customer_fields'][$field_sorted]['label']?></label>
            </li>
                <?php } } } }
                if (!empty($sessionstaff['customer_fields'])) {

				foreach ($sessionstaff['customer_fields'] as $customer_field_name => $customer_field_data) {

					if ($customer_field_data['show'] != 'N') {

						if (empty($sessionstaff['preferences']['customer_fields_order'])

							 || (is_array($sessionstaff['preferences']['customer_fields_order'])

								  && !in_array($customer_field_name, $sessionstaff['preferences']['customer_fields_order']))) { ?>
								  <li>
			<input type="checkbox" name="fields_to_show[]" value="<?=$customer_field_name?>" <?php if (is_array($sessionstaff['preferences']['fields_to_show']) && in_array($customer_field_name, $sessionstaff['preferences']['fields_to_show'])) {

													echo 'CHECKED ';

												} ?>>
            <label><?=$customer_field_data['label']?></label>
            </li>
            <?php } } } } ?>
            </ul>
            <input type="hidden" name="customer_fields_order" id="customer_fields_order" value="<?php

			if (!empty($sessionstaff['preferences']['customer_fields_order'])) {

				echo implode('|', $sessionstaff['preferences']['customer_fields_order']);

			} ?>">
            <?php }else {
				?>
				<ul>
					<?php 
				if (!empty($sessionstaff['preferences']['customer_fields_order'])) {

				// itterate through each preference.

				foreach ($sessionstaff['preferences']['customer_fields_order'] as $field_sorted) {

					if (!empty($sessionstaff['customer_fields'][$field_sorted])) {

						if ($sessionstaff['customer_fields'][$field_sorted]['show'] != 'N') {

							if (in_array($field_sorted, $sessionstaff['preferences']['fields_to_show'])) {
				?> 
             <li><label><?=$sessionstaff['customer_fields'][$field_sorted]['label']?></label></li>
            <?php } else{ ?>
            <li><label class="grey"><?=$sessionstaff['customer_fields'][$field_sorted]['label']?></label></li>
            <?php } } } } }
            if (!empty($sessionstaff['customer_fields'])) {

				foreach ($sessionstaff['customer_fields'] as $customer_field_name => $customer_field_data) {

					if ($customer_field_data['show'] != 'N') {

						if (empty($sessionstaff['preferences']['customer_fields_order'])

							 || (is_array($sessionstaff['preferences']['customer_fields_order'])

								  && !in_array($customer_field_name, $sessionstaff['preferences']['customer_fields_order']))) { ?>

<li><label class="grey"><?=$customer_field_data['label']?></label></li>
						<?php }

					}

				}

			} ?>
            
            
            
            <?php } ?>
           </div>
          </div>
          
          
          
          
           <div class="grid50 pull-right">
           <div class="customer marginleft15">
          <div class="headBox_customer">
              <h1>Transaction data fields
              <?php if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) { ?>
			<input	type="button" class="default marginbottom15 hand-icon" value="Edit" onClick="this.form.fields_tab.value='transactions';this.form.final_submit.value='no';this.form.submit();">				
              <?php } ?>
              </h1>
            
            </div>
            <?php if (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'transactions') { ?>
            <ul class="Clearfix">
				<?php if (!empty($sessionstaff['preferences']['transaction_fields_order'])) {

				// itterate through each preference.

				foreach ($sessionstaff['preferences']['transaction_fields_order'] as $field_sorted) {

					if ($field_sorted == 'amount') { ?>
            <li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('amount', $sessionstaff['preferences']['transaction_fields_to_show'])) {

							echo 'CHECKED ';

						}?> name="transaction_fields_to_show[]" value="amount"><p>Amount:</p></li>
            <?php }	elseif ($field_sorted == 'promo_id') { ?>
             <li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('promo_id', $sessionstaff['preferences']['transaction_fields_to_show'])) {

							echo 'CHECKED ';

						}?> name="transaction_fields_to_show[]" value="promo_id"><p>Accomplishments:</p></li>
						<?php }	elseif ($field_sorted == 'service_product') { ?>
			 <li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('service_product', $sessionstaff['preferences']['transaction_fields_to_show'])) {

							echo 'CHECKED ';

						}?> name="transaction_fields_to_show[]" value="service_product"><p>Choose Item:</p></li>			
						<?php }elseif ($field_sorted == 'transaction_description') { ?>
			<li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_to_show'])) {

							echo 'CHECKED ';

						}
						?> name="transaction_fields_to_show[]" value="transaction_description"><p>Staff Member Initials:</p></li>			
				<?php }elseif ($field_sorted == 'send_transaction_email') { ?>
				<li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_to_show'])) {

							echo 'CHECKED ';

						}
						?> name="transaction_fields_to_show[]" value="send_transaction_email"><p>Send Email Receipt?</p></li>			
				<?php }

					elseif (!empty($sessionstaff['transaction_fields'][$field_sorted])) {

						if ($sessionstaff['transaction_fields'][$field_sorted]['show'] != 'N') { ?>
				<li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array($field_sorted, $sessionstaff['preferences']['transaction_fields_to_show'])) {

														echo 'CHECKED ';

													}
						?> name="transaction_fields_to_show[]" value="<?=$field_sorted?>"><p><?=$sessionstaff['transaction_fields'][$field_sorted]['label']?></p></li>			
				
				<?php } } } }
				if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('amount', $sessionstaff['preferences']['transaction_fields_order']))) {
 ?>
			<li><input type="checkbox" <?php

						if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('amount', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="amount"><p>Amount:</p></li>			
				
			<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('promo_id', $sessionstaff['preferences']['transaction_fields_order']))) {
?>
			<li><input type="checkbox" <?php
if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('promo_id', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="promo_id"><p>Accomplishments:</p></li>			
				<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('service_product', $sessionstaff['preferences']['transaction_fields_order']))) {
?>
			
			<li><input type="checkbox" <?php
if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('service_product', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="service_product"><p>Choose Item:</p></li>
			<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_order']))) {
?>
<li><input type="checkbox" <?php
if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="transaction_description"><p>Staff Member Initials:</p></li>
			<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_order']))) {
     ?>
<li><input type="checkbox" <?php
if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="send_transaction_email"><p>Send Email Receipt?</p></li>
<?php 	}

			if (!empty($sessionstaff['transaction_fields'])) {

				foreach ($sessionstaff['transaction_fields'] as $transaction_field_name => $transaction_field_data) {

					if ($transaction_field_data['show'] != 'N') {

						if (empty($sessionstaff['preferences']['transaction_fields_order'])

							 || (is_array($sessionstaff['preferences']['transaction_fields_order'])

								  && !in_array($transaction_field_name, $sessionstaff['preferences']['transaction_fields_order']))) { ?>
								  
			<li><input type="checkbox" <?php
if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_to_show'])) {

					echo ' CHECKED';

				}
						?> name="transaction_fields_to_show[]" value="<?=$transaction_field_name?>"><p><?=$transaction_field_data['label']?></p></li>					  
								  
				<?php } } } } ?>				  
		</ul>

		<input type="hidden" name="transaction_fields_order" id="transaction_fields_order" value="<?php

			if (!empty($sessionstaff['preferences']['transaction_fields_order'])) {	echo implode('|', $sessionstaff['preferences']['transaction_fields_order']);

			} ?>">
			
			
			
			
			
            <?php }else{ ?>
            
            
            
            <ul class="Clearfix">
			<?php if (!empty($sessionstaff['preferences']['transaction_fields_order'])) {
			foreach ($sessionstaff['preferences']['transaction_fields_order'] as $field_sorted) {		
			if ($field_sorted == 'amount') { ?>
            <li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

						 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('promo_id', $sessionstaff['preferences']['transaction_fields_to_show']))) {

							echo ' grey';

						} ?>">Amount:</p></li>
				<?php 
			}	elseif ($field_sorted == 'promo_id') {		?>
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

						 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('amount', $sessionstaff['preferences']['transaction_fields_to_show']))) {

							echo ' grey';

						} ?>">Accomplishments:</p></li>	
			<?php 
			}	elseif ($field_sorted == 'service_product') {			 ?>
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

						 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('service_product', $sessionstaff['preferences']['transaction_fields_to_show']))) {

							echo ' grey';

						} ?>">Choose Item:</p></li>			
			<?php 
			}	elseif ($field_sorted == 'transaction_description') {			 ?>
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

						 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_to_show']))) {

							echo ' grey';

						} ?>"><Staff Member Initials:</p></li>			
			<?php 
			}	elseif ($field_sorted == 'send_transaction_email') {			 ?>
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

						 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_to_show']))) {

							echo ' grey';

						} ?>">Send Email Receipt?</p></li>			
			<?php }

			elseif (!empty($sessionstaff['transaction_fields'][$field_sorted])) {

						if ($sessionstaff['transaction_fields'][$field_sorted]['show'] != 'N') {

							if (is_array($sessionstaff['preferences']['transaction_fields_to_show']) && in_array($field_sorted, $sessionstaff['preferences']['transaction_fields_to_show'])) { ?>
			<li><p><?=$sessionstaff['transaction_fields'][$field_sorted]['label']?></p></li>	
			<?php }	else {							?>
			<li><p class="grey"><?=$sessionstaff['transaction_fields'][$field_sorted]['label']?></p></li>
			<?php 
			}
			}
			}
			}
			}
			
			
			
			
			//aa
			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('amount', $sessionstaff['preferences']['transaction_fields_order']))) {
 ?>
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

				 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('amount', $sessionstaff['preferences']['transaction_fields_to_show']))) {

					echo ' grey';

				} ?>">Amount:</p></li>
				<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('promo_id', $sessionstaff['preferences']['transaction_fields_order']))) {
?>		
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

				 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('promo_id', $sessionstaff['preferences']['transaction_fields_to_show']))) {

					echo ' grey';

				} ?>">Accomplishments:</p></li>	
				<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('service_product', $sessionstaff['preferences']['transaction_fields_order']))) {
?>		
			<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

				 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('service_product', $sessionstaff['preferences']['transaction_fields_to_show']))) {

					echo ' grey';

				} ?>">Choose Item:</p></li>			
			<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_order']))) {
?>			
		<li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

				 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('transaction_description', $sessionstaff['preferences']['transaction_fields_to_show']))) {

					echo ' grey';

				} ?>"><Staff Member Initials:</p></li>				
			<?php }

			if (empty($sessionstaff['preferences']['transaction_fields_order'])

			|| (is_array($sessionstaff['preferences']['transaction_fields_order']) && !in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_order']))) {
?>						
         <li><p class="<?php if (empty($sessionstaff['preferences']['transaction_fields_to_show'])

				 ||(is_array($sessionstaff['preferences']['transaction_fields_to_show']) && !in_array('send_transaction_email', $sessionstaff['preferences']['transaction_fields_to_show']))) {

					echo ' grey';

				} ?>">Send Email Receipt?</p></li>
			<?php }

			if (!empty($sessionstaff['transaction_fields'])) {

				foreach ($sessionstaff['transaction_fields'] as $transaction_field_name => $transaction_field_data) {

					if ($transaction_field_data['show'] != 'N') {

						if (empty($sessionstaff['preferences']['transaction_fields_order'])

							 || (is_array($sessionstaff['preferences']['transaction_fields_order'])

								  && !in_array($transaction_field_name, $sessionstaff['preferences']['transaction_fields_order']))) {
?>
										<li class="grey"><p><?=$transaction_field_data['label']?></p></li>
<?php 
						}

					}

				}

			} //aaa
		
			 ?>
            </ul>
            <?php } ?>
           </div>
          </div>
          
          
          
          
          <div class="grid100 pull-left">
            <div class="emailBox">
             <label>The email address to send redemption orders to:</label>
             <input class="text_field" type="text" name="notification_email" size="40" maxlength="255" border="0" value="<?php

		 if (!empty($sessionstaff['preferences']['notification_email'])) {

			echo $sessionstaff['preferences']['notification_email'];

		} ?>">
             <input	type="button" class="default hand-icon" value="Save Changes"	name="myinfo_submit" onClick="this.form.submit();">
             
           </div>
          </div>
       </div>
       </form>
      </div>
     </div>
   </div>
   </div><!-- container -->
   <div class="Clearfix"></div>
  
