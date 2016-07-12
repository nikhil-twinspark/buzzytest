
<?php $sessionpatient = $this->Session->read('patient'); ?>
<?php //print_r($Question);die; ?>
    <section class="clearfix loginArea" style="display: block">
        <div class="userSign clearfix" id="totaldiv" style="width: 975px;">
            <?php
            if(isset($sessionpatient['selfcheckin']['current_question']) && $sessionpatient['selfcheckin']['current_question']>$TotalQuestion){
           echo 'Log in to Patient site to view Self-Check in point allocation'; 
          }
          else{
           
          ?>
            <div class="selfcheckin"><b>Note: (Test would be considered completed if user clicks on Log Out.)</b></div>
            <div><b><p>Fill out the questionnaire to earn big points (50 points for every correct answer)</p></b></div>
            <p>Question <span id="question_no"><?=$Question['id']?></span> : <span id="quescnt"><?=$Question['quescnt']?></span></p>
           <form id="questionform">
           
           <div id="matter">
           <?=$Question['question']?>
            </div>
          <input type="hidden" name="question_id" id="question_id" value="<?=$Question['id']?>">
          
          <?php if(isset($sessionpatient['selfcheckin']['current_question']) && $sessionpatient['selfcheckin']['current_question']==$TotalQuestion){
           $butval='Finish';  
          }
          else{
           $butval='Next';    
          }
          ?>
          <input type="hidden" name="btnval" id="btnval" value="<?=$butval?>">
          <input class="btn btn-primary buttondflt col-md-4 col-sm-4 col-xs-4"  type="button" value="<?=$butval?>" onclick="getanswer();" id="submitbutton">
        
        
          </form>
          <?php } ?>
         </div>
   

    </section>

<!--loginform-->

<script>
   function getanswer(){
       if($("#questionform").valid()){
         datasrc=$( "#questionform" ).serialize();
	 $.ajax({
	  type:"POST",
	  data:datasrc,
          dataType: "json",
	  url:"<?=Staff_Name?>rewards/selfcheckinportalques/",
	  success:function(result){
              if(result.id=='finish'){
                $('#totaldiv').html('Log in to Patient site to view Self-Check in point allocation');  
              }else{
                $('#question_no').text(result.id);
                $('#question_id').val(result.id);
                $('#submitbutton').val(result.submitbutton);
                $('#quescnt').text(result.quescnt);
                $('#btnval').val(result.submitbutton);
                $('#matter').html(result.question);
		return false;
            }
	}
  });
  }
}
//
//$(document).ready(function() {  
//
//   
//
//        $('#questionform').validate({
//		rules: {
//
//			
//			question: {
//				required:true
//				
//			}
//			
//		},
//        
//        // Specify the validation error messages
//		messages: {
//			
//			question: {
//				required: "Please select option.."
//				
//			}
//		},
//		showErrors: function(errorMap, errorList) {
//			if (errorList.length) {
//				var s = errorList.shift();
//				var n = [];
//				n.push(s);
//				this.errorList = n;
//			}
//			this.defaultShowErrors();
//		},
//        submitHandler: function(form) {
//            form.submit();
//        }  
//            
//         });
//});
    </script>