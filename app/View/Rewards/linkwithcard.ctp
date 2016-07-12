
<section class="clearfix loginArea">
    <div class="col-lg-12 col-xs-12" style="padding :0px;">
  
    <div class="settingArea clearfix upper_case bg_white">
            <ul class="col-md-12 clearfix pad-0">
    <li class="col-md-7 col-xs-12" id="lirefer">
                    <h2>To Link - Please select an account</h2>
                    <table class="table clearfix">
                        <tr>
                            <td><strong>Email</strong></td>
                            <td><strong>Name</strong></td>
                            <td><strong>Date Of Birth</strong></td>
                            <td><strong>Clinic</strong></td>
                            <td><strong>Patient Type</strong></td>
                            <td><strong>Link</strong></td>
                        </tr>
                        <?php
                            foreach ($email_link as $link)
					{
                                $date1_chd = $link['User']['custom_date'];
						
				$date1_chd = date('Y-m-d', strtotime('+4 days', strtotime($date1_chd)));
				$date2_chd = date('Y-m-d');
				$diff_chd = abs(strtotime($date2_chd) - strtotime($date1_chd));
				$years_chd = floor($diff_chd / (365*60*60*24));
                                ?>
                                <tr>
                                    <td><?php echo $link['User']['email']; ?></td>
                                    <td><?php echo $link['User']['first_name'].' '.$link['User']['last_name']; ?></td>
                                    <td><?php echo $link['User']['custom_date']; ?></td>
                                    <td><?php echo $link['clinics']['api_user']; ?></td>
                                    <td><?php if($years_chd<18){ echo "Child"; }else{ echo "Parent"; } ?></td>
                                    <td style="padding: 0px;"><form method="post">
                                        <input type="hidden" name='card_number' id='card_number' value='<?=$card_number?>'>
                                        <input type="hidden" name='user_id' id='user_id' value='<?php echo $link['User']['id']; ?>'>
                                        <input type="hidden" name='email' id='email' value='<?php echo $link['User']['email']; ?>'>
                                        <input style="margin-bottom: 5px; margin-top:0px;" type="submit" value="Link" class="btn btn-primary buttondflt btn_new" name="link_to_email" id="link_to_email">
                                        </form></td>
                                </tr>
                            <?php }
                          ?>
                    </table>
                    
                </li>
            </ul>		
     </div>
        	
          

         </div>
    </section><!--loginform-->

    <script>

    </script>
