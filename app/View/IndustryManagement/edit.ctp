   <div class="contArea Clearfix">
         <div class="page-header">
            <h1>
                <i class="menu-icon fa fa-list-ul"></i> Industries
            </h1>
        </div>
    </div>
 
    <?php 
         //echo $this->element('messagehelper'); 
         echo $this->Session->flash('good');
         echo $this->Session->flash('bad');
     ?>
	<?php echo $this->Form->create("IndustryType",array('class'=>'form-horizontal'));
	
		?>
		
        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Industry Name</label>
            <input type="hidden" name="data[IndustryType][id]" value="<?php echo $indsurty['IndustryType']['id'];?>">
            <div class="col-sm-9 col-xs-12">
                <input type="text" class="col-xs-12 col-sm-5" id="IndustryTypeName" name="data[IndustryType][name]" value="<?php echo $indsurty['IndustryType']['name'];?>" placeholder="Industry Name">
            </div>
       </div>
       
       <?php $rfermsg=json_decode($indsurty['IndustryType']['reffralmessages']);
       if($rfermsg!=''){
		
       ?>
       <input type="hidden" id="cnt" name="cnt" value="<?=$rfermsg->cnt?>">
       <div id="refmsgbox" class="ind_manage">
		   <?php for($i=1;$i<=$rfermsg->cnt;$i++){ 
			   $fname='reffralmessage'.$i;?>
       <div class="form-group Clearfix" id="msg<?=$i?>">
       <?php if($i==1){ ?> 
           <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span> Default Referral Message</label>
               <?php }else{ ?>
                    <label class="col-sm-3 col-xs-12 control-label no-padding-right">&nbsp;</label>
               <?php } ?>
            <div class="col-sm-9 col-xs-12"> 
                 <textarea class="col-xs-12 col-sm-5" name="reffralmessage<?=$i?>" id="reffralmessage<?=$i?>" maxlength="255" style="resize: none;" required><?=$rfermsg->$fname?></textarea>
                     <?php if($i==1){ ?>
                        <div onclick="addoptionmore();" class="x-btn add-more">Add More Messages</div>
                     <?php }else{ ?>
                        <div onclick="removeoption(msg<?=$i?>)" class="x-btn ">x</div>
                     <?php } ?>
            </div>
       </div>
       <?php } ?>
      </div>
      <?php }else{ ?>
      <input type="hidden" id="cnt" name="cnt" value="1">
       <div id="refmsgbox">
		
       <div class="form-group Clearfix" id="msg1">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span> Default Referral Message</label>
            <div class="col-sm-9 col-xs-12"> 
                <textarea class="col-xs-12 col-sm-5" name="reffralmessage1" id="reffralmessage1" style="resize: none;" maxlength="255" required></textarea><div onclick="addoptionmore();" class="x-btn">Add More Messages</div>
            </div>
       </div>
       </div>
       <?php } ?>
      
       <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Industry" class="btn btn-sm btn-primary" onclick="return validateURL();">
        </div>
      
     </div>
     </form>
    

<script>
	
	function addoptionmore(){
	var cnt=$('#cnt').val();
	var inccnt=parseInt(cnt)+1;
	$( "#refmsgbox" ).append('<div class="form-group Clearfix" id="msg'+inccnt+'"><label class="col-sm-3 col-xs-12 control-label no-padding-right">&nbsp;</label><div class="col-sm-9 col-xs-12"> <textarea class="col-xs-12 col-sm-5" name="reffralmessage'+inccnt+'" id="reffralmessage'+inccnt+'" maxlength="255" style="resize: none;" required ></textarea><div onclick="removeoption(msg'+inccnt+')" class="x-btn">x</div></div></div>');
	$('#cnt').val(inccnt);
}
function removeoption(id){
	var cnt=$('#cnt').val();
	var deccnt=parseInt(cnt)-1;
	$(id).remove();
	$('#cnt').val(deccnt);
}
  function validateURL() {
  var name=$('#IndustryTypeName').val();

    if(name==''){
 $( "#IndustryTypeName" ).focus();
  alert('Please enter Industry Name.')
  return false;
  }

}

</script>
