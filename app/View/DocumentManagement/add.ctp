<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-book"></i>
Documents
 <!--
<small>
   
<i class="ace-icon fa fa-angle-double-right"></i>
Draggabble Widget Boxes & Containers
</small>
    -->
</h1>
</div>

 <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>  
  
      <?php echo $this->Form->create("Document",array('class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return match()'));
      echo $this->Form->input('clinic_id', array('type' => 'hidden','value'=>$sessionstaff['clinic_id'],'class'=>'editable'));  ?>
      <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Title</label>

<div class="col-sm-9">
<?php echo $this->Form->input("title",array('label'=>false,'div'=>false,'placeholder'=>'Title','class'=>'col-xs-10 col-sm-5','onkeypress'=>'changeErrorMessage(this.id)')); ?>
</div>
 </div>
  <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Document(doc and pdf)</label>

<div class="col-sm-9">
<?php	echo $this->Form->input('document', array('type' => 'file','label'=>false,'div'=>false,'class'=>'hand-icon','onclick'=>'changeErrorMessage(this.id)',"onchange"=>"checkimg('DocumentsDocument','li');")); ?>
<a onclick="removeimg('DocumentDocument','li');" id="li" class=""></a>
</div>
 </div>      
        

<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Save Document" class="btn btn-info">
									</div>

      </form>
 
     
     
   </div>
  

<script>

function match() {
			
		if($("#DocumentTitle").val()==''){
                $("#DocumentTitle").css('background-color','#FF9966');
                alert("Enter a title");
                $("#DocumentTitle").focus();
                return false;
            }
           
            else if($("#DocumentDocument").val()==''){
                $("#DocumentDocument").css('background-color','#FF9966');
                alert("Select a document");
                $("#DocumentDocument").focus();
                return false;
            }
            
            
           
        }
        function changeErrorMessage(ptr){
                $("#"+ptr).css('background-color','');
				$("#error_"+ptr).html("");

           }
function removeimg(filename,aname){
	$('#'+filename).val('');
	$('#'+aname).addClass('required');
	$('#'+aname).text('');
	$('#'+aname).removeClass('icon-top hand-icon');
}
function checkimg(filename,aname){
	var sluval=$('#'+filename).val();
	if(sluval!=''){
		$('#'+aname).text('x');
		$('#'+aname).addClass('icon-top hand-icon');
	}
}
</script>


