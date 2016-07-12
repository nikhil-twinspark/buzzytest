   <div class="contArea Clearfix">
        <div class="page-header">
           <h1>
               <i class="menu-icon fa fa-user"></i> Profile Fields
           </h1>
       </div>
    </div>
      <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
    ?>

     <?php echo $this->element('messagehelper'); ?>
     
     <form accept-charset="utf-8" method="post" id="ProfileFieldAddForm" class="form-horizontal" action="/AdminProfileFieldManagement/edit/<?php echo $ProfileFields['ProfileField']['id']; ?>">
            <input type="hidden"  name="data[ProfileField][id]" value="<?php echo $ProfileFields['ProfileField']['id']; ?>">
            <input type="hidden"  name="data[action]" value="update">
            
            <div class="form-group Clearfix">
                <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Name</label>
                <div class="col-sm-9 col-xs-12">
                   <?php $name=ucwords(str_replace('_',' ',$ProfileFields['ProfileField']['profile_field'])); ?>
                    <input type="text" id="ProfileFieldProfileField" maxlength="100" class="col-xs-10 col-sm-5" placeholder="Description" required="required" name="data[ProfileField][profile_field]" value="<?php echo $name; ?>">

                </div>
            </div>
            
            <div class="form-group Clearfix">
                <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Type</label>
                <div class="col-sm-9 col-xs-12">
                    <select id="ProfileFieldType" name="data[ProfileField][type]" onchange="getval();" class="col-xs-12 col-sm-5">
                        <option value="CheckBox" <?php if($ProfileFields['ProfileField']['type']=='CheckBox'){ echo "selected='selected'"; } ?>>CheckBox</option>
                        <option value="RadioButton" <?php if($ProfileFields['ProfileField']['type']=='RadioButton'){ echo "selected='selected'"; } ?>>RadioButton</option>
                        <option value="MultiSelect" <?php if($ProfileFields['ProfileField']['type']=='MultiSelect'){ echo "selected='selected'"; } ?>>MultiSelect</option>


                    </select>  
                </div>
            </div>
            

       
        <div class="form-group Clearfix" id="options">
           <div id="optionval">
        <?php 
        $other=explode(',',$ProfileFields['ProfileField']['options']);
        $othercheck=explode('(',end($other));
        if(count($othercheck)>1){
        $ProfileFields['ProfileField']['options']=str_replace(','.end($other),'',$ProfileFields['ProfileField']['options']);    
        }
        if($ProfileFields['ProfileField']['options']!=''){
        $opt=explode(',',$ProfileFields['ProfileField']['options']);
         ?>
        
        <input type="hidden" id="cnt" name="cnt" value="<?php echo count($opt); ?>">
        <?php 
        $i=1;
        foreach($opt as $option){ ?>
        <div id="field<?php echo $i; ?>" class="input_option">
        
        <?php if($i==1){ ?>
            <?php if($ProfileFields['ProfileField']['type']=='MultiSelect'){
                $new="multidiv";
                ?>
            <label class="col-sm-12 col-xs-12 control-label no-padding-right" for="form-field-1">
                <span class="star">*</span><span class="f_option">Field Options</span>
            <?php }else{ $new=""; ?>
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                    <span class="star">*</span>Field Options
            <?php } ?>
        
        
            <?php if($ProfileFields['ProfileField']['type']!='MultiSelect'){ ?>
                       <label class="col-sm-10 col-xs-12 control-label no-padding-right"><input type="checkbox" id="other" name="other" <?php if(count($othercheck)>1){ echo "checked";} ?>>Other</label></label>
            <?php } ?>
        <?php }else{ ?><label class="col-sm-3 col-xs-12 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><?php } ?>
        
            <div class="col-sm-9 col-xs-12 pull-right <?=$new?>">
                <input type="text" id="Option<?php echo $i; ?>" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]" value="<?php echo $option; ?>">
        <?php if($i>2){ ?>
        <div onclick="removeoption(field<?php echo $i; ?>)" class="x-btn">x</div>
        <?php } if($i==1){ ?>
        <div class="add_profile icon-1" onclick="addoptionmore();">Add</div>
        <?php } ?>
        </div>
        </div>
        <?php $i++;} ?>
        <?php } ?>
           </div>
              <div id="demo" class="col-md-6 col-sm-6">
                  <?php if($ProfileFields['ProfileField']['type']=='CheckBox'){  ?>
            <div class="form-group">
                <span style='display:block; font-weight:bold;'><?=$name?>:</span>
                <div class="check_prev">
                      <?php foreach($opt as $option){ ?>
                        <label class="checkbox-inline">
                        <input type="checkbox"><?=$option?></label>
                      <?php } if(count($othercheck)>1){ ?>
                      <label class="checkbox-inline">
                      <input type="checkbox" onclick="opt()" id="getopt">Other</label>
                      <?php } ?>
                <div class="clearfix prevhtml" id="othertext"></div>
                  </div>
            </div>
                  <?php } 
                   if($ProfileFields['ProfileField']['type']=='MultiSelect'){  ?>
                  <div class="form-group">
                      <span style="display:block; font-weight:bold;"><?=$name?>:</span>
                      <select size="4" multiple="multiple" class="form-control select-info" style="height:80px">
                          <option>Please Select</option>
                             <?php foreach($opt as $option){ ?>
                          <option><?=$option?></option>
                             <?php } ?>
                      </select></div>
                 <?php } 
                   if($ProfileFields['ProfileField']['type']=='RadioButton'){  ?>
                  <div class="form-group">
                      <span style="display:block; font-weight:bold;"><?=$name?>:</span>
                      <div class="radio_prev">
                          <?php foreach($opt as $option){ ?>
                          <div class="col-xs-6 pull-left">
                              <input type="radio" class="form-control"  name="radiobox" onclick="opt1('<?=$option?>')">
                              <label class=" control-label"><?=$option?></label>
                          </div>
                          <?php } if(count($othercheck)>1){ ?>
                  <div class="col-xs-6 pull-left">
                              <input type="radio" class="form-control" onclick="opt1('other')" name="radiobox">
                              <label class=" control-label">Other</label>
                          </div>
                   
                      <?php } ?>
                      </div></div>
                   <?php }  ?>
                <div class="clearfix prevhtml" id="othertext"></div>
       </div>
       </div>
         
            <div class="col-md-offset-3 col-md-9">
               <input type="button" value="Preview" class="btn btn-sm btn-primary" onclick="getpreview();">
               <input type="submit" value="Save Profile Field" class="btn btn-sm btn-primary">
            </div>
     </form>
  
     

<script>
    function opt(){

        if ($('#getopt').is(":checked"))
{
 $('#othertext').html('<input type="text" value="" placeholder="Other" class="editable1">');
}else{
     $('#othertext').html('');
    }
       
    }
    function opt1(val){

if(val=='other'){
 $('#othertext').html('<input type="text" value="" placeholder="Other" class="editable1">');
 }else{
       $('#othertext').html('');
 }
    }
function getpreview(){
         var name=$('#ProfileFieldProfileField').val();
         var cnt=$('#cnt').val();
         for(var i=1;i<=cnt;i++){
             if($('#Option'+i).val()==''){
                 alert('Please fill all required fields for Preview')
                 return false;
             }
         }
         if(name==''){
             alert('Please fill all required fields for Preview')
             return false;
         }
         datasrc=$( "#ProfileFieldAddForm" ).serialize();
	 $.ajax({
	  type:"POST",
	  data:datasrc,
          
	  url:"<?=Staff_Name?>AdminProfileFieldManagement/getpreview/",
	  success:function(result){
            $("#demo").html(result);
            $("#demo").css("display", "block");
           
	}
  });
  

}
function getval(){
	var fieldtype=$('#ProfileFieldType').val();
                if(fieldtype=='MultiSelect'){
                   $('#optionval').html('<input type="hidden" id="cnt" name="cnt" value="2">\n\
       <div id="field1" class="input_option"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1">\n\
<span class="star">*</span>Field Options</label><div class="col-sm-9 col-xs-12"><input type="text" id="Option1" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"><div class="add_profile icon-1" onclick="addoptionmore();">Add</div></div><div id="field2" class="input_option"><label  class="col-sm-3 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><div class="col-sm-9 col-xs-12 pull-right"><input type="text" id="Option2" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"></div></div>'); 
                }else{
                    $('#optionval').html('<input type="hidden" id="cnt" name="cnt" value="2"><div id="field1" class="input_option"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Field Options<label class="col-sm-10 control-label no-padding-right"><input type="checkbox" id="other" name="other">Other</label></label><div class="col-sm-9 col-xs-12"><input type="text" id="Option1" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"><div class="add_profile icon-1" onclick="addoptionmore();">Add</div></div><div id="field2" class="input_option"><label  class="col-sm-3 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><div class="col-sm-9 col-xs-12 pull-right"><input type="text" id="Option2" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"></div></div>')
                }
                $("#demo").css("display", "none");
}
function addoptionmore(){
	var cnt=$('#cnt').val();
	var inccnt=parseInt(cnt)+1;
	$( "#optionval" ).append('<div id="field'+inccnt+'" class="input_option"><label  class="col-sm-3 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><div class="col-sm-9 col-xs-12 pull-right"><input type="text" id="Option'+inccnt+'" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"><div onclick="removeoption(field'+inccnt+')" class="x-btn">x</div></div></div>');
	$('#cnt').val(inccnt);
}
function removeoption(id){
	var cnt=$('#cnt').val();
	var deccnt=parseInt(cnt)-1;
	$(id).remove();
	$('#cnt').val(deccnt);
}
</script>


