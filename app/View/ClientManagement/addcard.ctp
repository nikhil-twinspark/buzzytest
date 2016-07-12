        <?php //echo $this->Html->script('ckeditor/ckeditor'); ?>
        <div class="contArea Clearfix">
              <div class="page-header">
                 <h1>
                     <i class="menu-icon fa fa-tachometer"></i> Clients
                 </h1>
             </div>
     </div>
         <?php 
         //echo $this->element('messagehelper'); 
         echo $this->Session->flash('good');
         echo $this->Session->flash('bad');
         ?>

    <form name="addcard" id="addcard" action="/ClientManagement/addcardlog" method="post" class="form-horizontal">
        <input type="hidden" id="clinic_id" name="clinic_id" value="<?php echo $clinic_id; ?>">
        <input type="hidden" id="error_dis" name="error_dis" value="">
        
        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right">Number Of Card</label>
            <div class="col-sm-9 col-xs-12">
                <input type="number" id="no_of_card" maxlength="12" class="col-xs-12 col-sm-5"  name="no_of_card" value="" required onblur="getval();" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')">
            </div>
        </div>

        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right">Range From</label>
            <div class="col-sm-9 col-xs-12">
                <input type="number" id="range_from" maxlength="20" class="col-xs-12 col-sm-5" name="range_from" value=""  onblur="getval();" required onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')">
            </div>
        </div>
        
        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right">Range To</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" id="range_to" maxlength="20" class="col-xs-12 col-sm-5"  readonly name="range_to" value="" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '')">
            </div>
        </div>

         <div class="col-md-offset-3 col-md-9">
            <input type="button" id="add_card" value="Add Card" class="btn btn-sm btn-primary">
            
     </div>
        

    </form>
    

<script>
$("#add_card").click(function(){
        if($("#addcard").valid()){
	setTimeout(function(){
	
           


		var error_dis=$('#error_dis').val();
		if(error_dis==1){
		
			return false;
		}
		if(error_dis==''){
	
			return false;
		}
		else{
                        if(getval()){
                            document.addcard.submit();
                            return true;
                        }
                        else{
                            return false;
                        }
		}
	},800);
    }
});
//}

function getval(){
	var nocard=$('#no_of_card').val();
	if(nocard==0 && nocard!=''){
	$('#error_dis').val(1);
        alert('Please enter correct count.');
	return false;
	}
	var rangefrom=$('#range_from').val();
        if(rangefrom==0 && rangefrom!=''){
	$('#error_dis').val(1);
        alert('Please enter valid range from.');
	return false;
	}
	if(rangefrom!=undefined && rangefrom!='' && nocard!=undefined && nocard!=''){
	var cid=$('#clinic_id').val();
	datasrc="no_of_card="+nocard+"&range_from="+rangefrom+"&clinic_id="+cid;
	 $.ajax({
	  type:"POST",
	  data:datasrc,
	  url:"<?=Staff_Name?>ClientManagement/checkcard/",
	  success:function(result){

		if(result==1){
			$('#error_dis').val(1);
			
			$('#range_to').val(0);
			alert('Card Range already exist.');
                        return false;
		}
		else{
		$('#range_to').val(parseInt(rangefrom)+parseInt(nocard)-1);
		$('#error_dis').val(0);
		}
		
	}
  });
  
	return true;
	}
}
	$(document).ready(function() {  
        $('#addcard').validate({
		rules: {

			
			no_of_card: {
				required:true,
				number: true
			},
			range_from: {
				required:true,
				number: true
			}
			
		},
        
        // Specify the validation error messages
		messages: {
			
			no_of_card: {
				required: "Field can not be empty",
				number: "Please enter a valid count",
				
			},
			range_from: {
				required: "Please enter a Range From",
				number: "Please enter a valid Range From",
				
			}
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
