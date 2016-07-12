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
     <form accept-charset="utf-8" method="post" id="ProfileFieldAddForm" class="form-horizontal" action="/AdminProfileFieldManagement/add">
         
        <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Name</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text" id="ProfileFieldProfileField" maxlength="100" class="col-xs-12 col-sm-5" placeholder="Description" required="required" name="data[ProfileField][profile_field]">
            </div>
        </div>
         
        <div class="form-group Clearfix">
            <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Type</label>
            <div class="col-sm-9 col-xs-12 ">
                <select id="ProfileFieldType" name="data[ProfileField][type]" onchange="getval();" class="col-xs-12 col-sm-5">
                        <option value="CheckBox">CheckBox</option>
                        <option value="RadioButton">RadioButton</option>
                        <option value="MultiSelect">MultiSelect</option>
                </select>
            </div>
        </div>
         
       <div class="form-group Clearfix"  id="options">
            <div id="optionval">
                <input type="hidden" id="cnt" name="cnt" value="2">
                <div id="field1" class="input_option">
                    <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1">
                            <span class="star">*</span>
                            Field Options
                            <label class="col-sm-10 control-label no-padding-right">
                            <input type="checkbox" id="other" name="other">Other</label>
                    </label>
                    <div class="col-sm-9 col-xs-12 pull-right">

                      <input type="text" id="Option1" maxlength="20" class="col-xs-9 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]">
                      <div class="add_profile icon-1" onclick="addoptionmore();">Add</div>
                    </div>
               </div>
                <div id="field2" class="input_option">
                    <label  class="col-sm-3 col-xs-12 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label>
                    <div class="col-sm-9 col-xs-12 pull-right">
                        <input type="text" id="Option2" maxlength="20" class="col-xs-9 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]">
                    </div>
                </div>
            </div>
            <div id="demo" class="col-md-6 col-sm-6" style="display:none;"></div>
        </div>
         
         
            
        <div class="col-md-offset-3 col-md-9">
           <input type="button" value="Preview" class="btn btn-sm btn-primary" onclick="getpreview();">
           <input type="submit" value="Save Profile Field" class="btn btn-sm btn-primary">
       </div>
         
     </form>
  
    

<script>
function getval(){
	var fieldtype=$('#ProfileFieldType').val();
                if(fieldtype=='MultiSelect'){
                   $('#optionval').html('<input type="hidden" id="cnt" name="cnt" value="2">\n\
       <div id="field1" class="input_option"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1">\n\
<span class="star">*</span>Field Options</label><div class="col-sm-9 col-xs-12"><input type="text" id="Option1" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"><div class="add_profile icon-1" onclick="addoptionmore();">Add</div></div><div id="field2" class="input_option"><label  class="col-sm-3 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><div class="col-sm-9 col-xs-12 pull-right"><input type="text" id="Option2" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"></div></div>'); 
                }else{
                    $('#optionval').html('<input type="hidden" id="cnt" name="cnt" value="2"><div id="field1" class="input_option"><label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Field Options<label class="col-sm-10 control-label no-padding-right"><input type="checkbox" id="other" name="other">Other</label></label><div class="col-sm-9"><input type="text" id="Option1" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"><div class="add_profile icon-1" onclick="addoptionmore();">Add</div></div><div id="field2" class="input_option"><label  class="col-sm-3 control-label no-padding-right blank_lab" for="form-field-1">&nbsp;</label><div class="col-sm-9  col-xs-12 pull-right"><input type="text" id="Option2" maxlength="20" class="col-xs-10 col-sm-4" placeholder="Option" required="required" name="data[ProfileField][options][]"></div></div>');
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
</script>


