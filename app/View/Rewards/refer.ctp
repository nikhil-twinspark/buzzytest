<?php $sessionpatient = $this->Session->read('patient'); ?>
<div class="mobilebanner"> 
    <div id="logo"><?php if(isset($sessionpatient['Themes']['patient_logo_url']) && $sessionpatient['Themes']['patient_logo_url']!=''){ ?>
       <a href="<?=Staff_Name?>rewards/home"><img src="<?=S3Path.$sessionpatient['Themes']['patient_logo_url']?>" width="246" height="88" alt="<?=$sessionpatient['Themes']['api_user']?>" title="<?=$sessionpatient['Themes']['api_user']?>" /></a>
       <?php }else{ ?>
        <a href="<?=Staff_Name?>rewards/home"><?php echo $this->html->image(CDN.'img/reward_imges/logo.jpg',array('width'=>'246','height'=>'88','alt'=>'Pure Smiles'));?></a>
       <?php } ?></div>

    <div id="navimob"><a href="#" id="pull">
            <?php echo $this->html->image(CDN.'img/reward_imges/navigationIcon.png', array('width' => '31', 'height' => '26')); ?>

        </a></div>

</div>
      <div class=" clearfix">
       
        
        <div class="col-lg-9 col-xs-12 rightcont">
        
          <div class="settingArea clearfix">
            
            <ul class="col-md-12 pad-0">
               
             
               
               <li class="col-md-7 col-xs-12">
               <h2>REFER A FRIEND</h2>
               <div class="pointsBox">
 <span>Earn points when the friends and family you refer convert into:</span>
<ul>
    <?php 
     $settings=array();
    if(!empty($admin_settings)){
                                    if($admin_settings['AdminSetting']['setting_data']!=''){
                                      $settings=json_decode($admin_settings['AdminSetting']['setting_data']); 
                                    }
                                }
    
   
    
    foreach($leads as $ld){ 
        	$point1='';
                                    foreach($settings as $set =>$setval){
                                      
                                       if($set==$ld['LeadLevel']['id']){
                                         $point1=$setval;
                                       }
                                    }
        
        ?>
<li><span><?php echo $ld['LeadLevel']['leadname']; ?></span> &gt; <span><?php if($point1!=''){ echo $point1; }else{ echo $ld['LeadLevel']['leadpoints']; }?> points </span></li>
    <?php } ?>
 </ul>
</div>
                <form action="<?=Staff_Name?>rewards/refer/" method="POST" name="pass_form" id="pass_form">
					<input type="hidden" name="user_id" value="<?=$sessionpatient['customer_info']['user']['id']?>">
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label"><span style="color:red;">*</span>First Name:</label>
                   <input class="form-control" type="text" name="first_name"  id="first_name" value="" maxlength="20">
                   </div>
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label"><span style="color:red;">*</span>Last Name:</label>
                   <input class="form-control" type="text" name="last_name"  id="last_name" value="" maxlength="20">
                   </div>
                   <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label"><span style="color:red;">*</span>Email:</label>
                   <input class="form-control" type="email" name="email" id="email"  value="" maxlength="50">
                   </div>
                                        <?php
                                                
                                                if($refer_msg->cnt>1){ ?>
                    <div class="form-group" id="defaultmsg">
                        <?php 
                      
                        for($k=1;$k<=$refer_msg->cnt;$k++){ 
                            $fname='reffralmessage'.$k;
                            ?>
                          <?php if($k==1){
                           ?>
                   <label for="inputEmail3" class="col-sm-2 control-label">Quick Recommendations :</label>
                  
                       <?php }else{ ?>
                   <label for="inputEmail3" class="col-sm-2 control-label">&nbsp;</label>
                  
                       <?php } ?>
                   <div class="form-group">
                       <?php if($k==1){
                           ?>
                  
                       <input class="form-control" type="radio" id="msg" name="msg" checked="checked" onclick="setmsg(<?=$k?>);"><label class="recommendation"><?=$refer_msg->$fname?></label>
                       <?php }else{ ?>
                 
                       <input class="form-control" type="radio" id="msg" name="msg" onclick="setmsg(<?=$k?>);"><label class="recommendation"><?=$refer_msg->$fname?></label>
                       <?php } ?>
                  
                   </div>
                         <?php } ?>
                   </div>
                                          <?php } ?>
                    <div class="form-group msg_refer">
                   <label for="inputEmail3" class="col-sm-2 control-label"><span style="color:red;">*</span>Recommendations (edit):</label>
                   <?php if(empty($refer_msg)){ ?>
                   <textarea class="form-control" name="message" id="massage" style="resize: none;"></textarea>
                   <?php }else{ ?>
                   <textarea class="form-control txt_area" name="message" id="massage" style="resize: none;"><?php echo $refer_msg->reffralmessage1; ?></textarea>
                   
                   
                   <div id="setnext" style="display:none">
						
                                            
						<a onclick="setdefault();" style="cursor: pointer;" title="Change Recommendation"></a>
						
						</div> 
                 
                   
                   <?php } ?>
                   </div>
                <div>
                    <input type="submit" value="Refer" class="btn btn-primary buttondflt btn_new" name="login_submit" onclick="return checkemail();">
                    
                    <input type="button" class="btn btn-primary buttondflt btn_new" onclick="return emailpreview();" value="Email Preview">
                </div>
                </form>
               </li>
           
      </div>
        </div>
         <?php echo $this->element('left_sidebar'); ?>
      </div>
<div class="modal fade popupBox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-sm modal-dialog-sm" id='VIEWPREv'></div>
             
        </div>   <!--popup-->     
        <div class="" id="Mymodel1"></div>
   <script>
	   function setmsg(id){
		  
	   $.ajax({
                   type: "POST",
                   url: "<?php echo Staff_Name ?>Rewards/getmsg/",
                   data: "&id="+id,
                   success: function(msg) {
					   $("#massage").focus();
					   $('#massage').val(msg);
                                          
                                           $("#defaultmsg").css("display", "none");
                                           $("#setnext").css("display", "block");
                   }
               }); 
            } 
            function emailpreview(){
		  var msges=$('#massage').val();
	   $.ajax({
                   type: "POST",
                   url: "<?php echo Staff_Name ?>Rewards/referpreview/",
                   data: "&message="+msges,
                   success: function(msg) {
                       $('#myModal').addClass("modal fade popupBox in");
                       $('#myModal').attr('aria-hidden', false);
                       $('#myModal').css('display', 'block');
                       $('#Mymodel1').addClass('modal-backdrop fade in');
		       $('#VIEWPREv').html(msg);
                   }
               }); 
            } 
            function setdefault(){
            $("#defaultmsg").css("display", "block");
            $("#setnext").css("display", "none");
            }
       function checkemail(){

	
		var email=$('#email').val();
                if(email!=''){
		datasrc="email="+email;

  $.ajax({
	  type:"POST",
	  data:datasrc,
	  url:"<?=Staff_Name?>rewards/checkemailrefer/",
	  success:function(result){
		if(result==1){
		$("#email").focus();
			alert('Email Id already Registered.');
			return false;
		
		}
		else{
		$( "#pass_form" ).submit();
		}
	}
  });
 return false;
 }
}
function close_form() {

                $('#myModal').addClass("modal fade popupBox");
                $('#myModal').attr('aria-hidden', true);
                $('#myModal').css('display', 'none');
                $('#Mymodel1').removeClass('modal-backdrop fade in');
            }

   $(document).ready(function() {  
        $('#pass_form').validate({
		rules: {
			first_name: "required",
			last_name: "required",
			email: {
				required: true,
				email: true
			},
			message: "required"
			
		},
        
        // Specify the validation error messages
		messages: {
			first_name: "Please enter your first name",
                        last_name: "Please enter your last name",
			email: "Please enter a valid email address",
			message: "Please enter your message",
			
			
			
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
});
   </script>
