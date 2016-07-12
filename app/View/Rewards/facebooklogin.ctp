      <style type="text/css">
#Style {
position:absolute;
visibility:hidden;
/*border:solid 1px #CCC;*/
padding: 5px;
right: -143px;}
.buttondflt { background: url(<?php echo CDN; ?>img/reward_imges/button-arrow.png) no-repeat 78% 11px #3f7ae8;}
</style>
    <?php $sessionpatient = $this->Session->read('patient'); ?>
    <section class="clearfix loginArea">
        <div class="col-md-5 userSign clearfix">
         </div>
         
		<div class="col-md-7 userSign">
         <p>Existing/returning user Sign in</p>
          <?php echo $this->Session->flash(); ?>

		<form action="<?=Staff_Name?>rewards/facebooklogin/" method="POST" name="new_account_form_submit" class="side_padding">
		<input type="hidden" name="action" value="facebook_signup">
			 <input type="hidden" name="first_name" value="<?=$patientdetails['first_name']?>">
			 <input type="hidden" name="last_name" value="<?=$patientdetails['last_name']?>">
			 <input type="hidden" name="email" value="<?=$patientdetails['email']?>">
			 <input type="hidden" name="customer_username" value="<?=$patientdetails['customer_username']?>">
			 <input type="hidden" name="gender" value="<?=$patientdetails['gender']?>">
			 <input type="hidden" name="custom_date" value="<?=$patientdetails['custom_date']?>">
			 <input type="hidden" name="facebook_id" value="<?=$patientdetails['facebook_id']?>">
			 <input type="hidden" name="is_facebook" value="<?=$patientdetails['is_facebook']?>">
			 <div class="form-group">
			 <label>Sign up using the doctor provided number 
             <span class="helpicon" style="position:relative;">
             <a href="#" class="showhim" onMouseOver="ShowPicture('Style',1)" onMouseOut="ShowPicture('Style',0)">?</a>
             <div id="Style">
             <?php if(isset($sessionpatient['Themes']['patient_question_mark'])){ ?>
             <img src="<?=S3Path.$sessionpatient['Themes']['patient_question_mark']?>" width="182" height="148"/>
             <?php }else{ ?>
             <?php echo $this->html->image(CDN.'img/reward_imges/imghover.png',array('width'=>'182','height'=>'148'));?>
             <?php } ?>
             </div> 
             </span>
             </label>
             <?php echo $this->Form->input("card1",array('type'=>'text','label'=>false,'div'=>false,'placeholder'=>'Card Number','class'=>'form-control','required')); ?>
            </div>
            <?php //echo $this->Form->end("Sign Up",array('class'=>'btn','data-toggle'=>'modal','data-target'=>'#myModal')); ?>

          <input class="btn btn-primary buttondflt"  type="button" value="Submit" onclick="lightbox();">
         </form>
      </div>
    </section><!--loginform-->
<script language="Javascript">

function ShowPicture(id,Source) {
if (Source=="1"){
if (document.layers) document.layers[''+id+''].visibility = "show"
else if (document.all) document.all[''+id+''].style.visibility = "visible"
else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "visible"
}
else
if (Source=="0"){
if (document.layers) document.layers[''+id+''].visibility = "hide"
else if (document.all) document.all[''+id+''].style.visibility = "hidden"
else if (document.getElementById) document.getElementById(''+id+'').style.visibility = "hidden"
}
}
function lightbox(){

var card_number=$('#card1').val();
if(card_number==''){
alert('Please fill the card number provided by orthodontist.');
return false;
}else{
  $.ajax({
	  type:"POST",
	  data:"card_number="+card_number,
	  dataType: "json",
	  url:"<?=Staff_Name?>rewards/verifycard/",
	  success:function(result){
           
	  if(result.status==1){
	  alert('Card Number Already Exist');
	  return false;
	  }
	  if(result.status==0){
	  alert('Invalid Card Number.');
	  return false;
	  }
	  if(result.status==3){
	  alert('Clinic does not exist for this card number.');
	  return false;
	  }
	  if(result.status==2){
		document.new_account_form_submit.submit();
	  }
  }});



 }
}
</script>
