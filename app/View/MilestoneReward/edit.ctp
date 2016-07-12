
<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
 <div class="page-header">
<h1>
    <i class="menu-icon fa fa-magic"></i>
Milestone Rewards
</h1>
</div>
    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    
  
      <?php echo $this->Form->create("MilestoneReward",array('class'=>'form-horizontal'));
     
echo $this->Form->input('action', array('type' => 'hidden','value'=>'update'));

 ?>
        <div class="form-group">
      <input type="hidden"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="id" name="id" value="<?php echo $MilestoneReward['MilestoneReward']['id']; ?>">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Name</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Name" id="name" name="name" value="<?php echo $MilestoneReward['MilestoneReward']['name']; ?>">
        </div>
        </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Description</label>

<div class="col-sm-9">

    <input type="text"  maxlength="255" class="col-xs-10 col-sm-5" placeholder="Description" id="description" name="description" value="<?php echo $MilestoneReward['MilestoneReward']['description']; ?>">
</div>
 </div>
       
  
       <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" ><span class="star">*</span>Coupon</label>

<div class="col-sm-9">

    <select id="coupon_id" class="col-xs-10 col-sm-5" name="coupon_id" onchange="getearning();">
<option value="">Select Coupon</option>
<?php foreach($ProductService as $ps){ ?>
<option value="<?php echo $ps['ProductService']['id']; ?>" <?php if($MilestoneReward['MilestoneReward']['coupon_id']==$ps['ProductService']['id']){ echo "selected"; }; ?>><?php echo $ps['ProductService']['title']." - ".$ps['ProductService']['points'].""; ?></option>
<?php } ?>
</select>
</div>
 </div>

      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Point</label>

<div class="col-sm-9">

    <input type="text"  maxlength="7" class="col-xs-10 col-sm-5" placeholder="Point" id="points" name="points" value="<?php echo $MilestoneReward['MilestoneReward']['points']; ?>" onkeyup="getearning();">
</div>
 </div>
    <div style="color:red;font-size: 12px;margin-bottom: 10px;margin-left: 270px;" id="showmsg">
        <?php $perearn=round(($ProductServicecur['ProductService']['points']/$MilestoneReward['MilestoneReward']['points'])*100,2); 
        echo $perearn.' % Earning';
        ?>
                            
                            
                        </div>
<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Milestone Reward" class="btn btn-info">
       
									</div>  
      </form>
 
    
     
   </div>
 

<script language="Javascript">
function getearning(){
    var pntval=$("#coupon_id option:selected").text();
    var milepnt=$('#points').val();
    if(pntval!='' && milepnt!=''){
    var getpnt=pntval.split('- ');
    var getper=(parseInt(getpnt[1])/milepnt)*100;
    var perval=Math.round(getper).toFixed(2);
    $('#showmsg').text(perval+' % Earning');
    }
}

$(document).ready(function() { 
			
	
	 
			
        $('#MilestoneRewardEditForm').validate({
		rules: {
                        name: "required",
                        coupon_id: "required",
			description: "required",

			points: { 
				required: true,
				number: true
			}
		},
        
        // Specify the validation error messages
		messages: {
                        name: "Please enter name",
                        coupon_id:"Please select coupon",
			description: "Please enter reward description",
			
			points: {
				required: "Please enter point",
				number: "Please enter a valid point"
				
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
            var pnt=$('#points').val();
            if(pnt>0){
            form.submit();
        }else{
            alert('Point should be greater then zero.')
        }
        }
          
            
         });
});
</script>




