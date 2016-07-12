    <?php echo $this->Html->script('ckeditor/ckeditor'); ?>
    <div class="contArea Clearfix">
         <div class="page-header">
            <h1>
                <i class="menu-icon fa fa-cubes"></i> Industries
            </h1>
        </div>
     
    
 
        <?php echo $this->Form->create("IndustryType",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>
            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span> Industry Name</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'placeholder'=>'Industry Name','required','class'=>'col-xs-12 col-sm-5')); ?>
                </div>
            </div>
        
            <input type="hidden" id="cnt" name="cnt" value="1">
            <div id="refmsgbox" class="ind_manage">
                
            <div class="form-group Clearfix" id="msg1" >
                <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span>Default Referral Message</label> 
                <div class="col-sm-9 col-xs-12">
                    <textarea class="col-xs-12 col-sm-5" name="reffralmessage1" id="reffralmessage1" maxlength="255" style="resize: none;" required></textarea><div onclick="addoptionmore();" class="x-btn add-more">Add More Messages</div>
                </div>
            </div>
                
            </div>

            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Industry" class="btn btn-sm btn-primary" onclick="return validateURL();">
             </div>
        </form>
   
<script>

function addoptionmore(){
	var cnt=$('#cnt').val();
	var inccnt=parseInt(cnt)+1;
	$( "#refmsgbox" ).append('<div class="form-group Clearfix" id="msg'+inccnt+'"><label class="col-sm-3 col-xs-12 control-label no-padding-right">&nbsp;</label><textarea class="col-xs-12 col-sm-5" name="reffralmessage'+inccnt+'" id="reffralmessage'+inccnt+'" maxlength="255" style="resize: none;" required></textarea><div onclick="removeoption(msg'+inccnt+')" class="x-btn">x</div></div>');
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

