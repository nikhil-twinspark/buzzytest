      <?php echo $this->Html->script('/js/tiny_mce/tiny_mce.js'); ?>
<?php 
    echo $this->Html->scriptBlock( 
        "tinyMCE.init({ 
            mode : 'textareas', 
            theme : 'advanced', 
            theme_advanced_buttons1 : 'forecolor, bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,undo,redo,|,link,unlink,|,image,emotions,code',
            theme_advanced_buttons2 : '', 
            theme_advanced_buttons3 : '', 
            theme_advanced_toolbar_location : 'top', 
            theme_advanced_toolbar_align : 'left', 
            theme_advanced_path_location : 'bottom', 
            extended_valid_elements : 'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]',
            file_browser_callback: 'fileBrowserCallBack', 
            width: '620', 
            height: '480', 
            relative_urls : true 
        });" 
    ); 
?> 

<script>
function fileBrowserCallBack(field_name, url, type, win) {
		var connector = "/js/tiny_mce/file_manager_contest.php";
		my_field = field_name;
		my_win = win;
		switch (type) {
			case "image":
				connector += "?type=img";
				break;
			
		}
		tinyMCE.activeEditor.windowManager.open({
			file : connector,
			title : 'File Manager',
			width : 650,  
			height : 550,
			resizable : "yes",
			inline : "yes",  
			close_previous : "no"
		    }, {
			window : win,
			input : field_name
		    });
	}
   
</script>
    <div class="contArea Clearfix">
        <div class="page-header">
           <h1>
               <i class="menu-icon fa fa-users"></i>Contest
           </h1>
       </div>
    </div>
    <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
     ?>


        <?php echo $this->Form->create("Contest",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Contest Name</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("contest_name",array('label'=>false,'div'=>false,'placeholder'=>'Contest Name','required','class'=>'col-xs-12 col-sm-5','maxlength'=>'36')); ?>
                </div>
            </div>


            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Contest Description</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->textarea('contest_description', array('class' => 'ckeditor'));?>
                </div>
            </div>

            <div class="form-group Clearfix">
                <div id="ermsgstafflogo" class="message" style="color: red;margin-left:270px;"></div>
                <input type="hidden" id="error_staff_logo" name="error_staff_logo" value="0">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right">Contest Image</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("contest_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon',"onchange"=>"checkimg('ContestContestImage','ci');")); ?>
                    <a onclick="removeimg('ContestContestImage','ci');" class="" id="ci"></a>
                </div>
            </div>

            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Contest Area</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->textarea('contest_area', array('class' => 'ckeditor'));?>
                </div>
            </div>


            <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Contest" class="btn btn-sm btn-primary" onclick="return validateURL();">
             </div>
    
     </form>
  

<script>
var _URL = window.URL || window.webkitURL;
$("#ContestContestImage").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
	
			if(this.width<=730 && this.height<=300){
			$('#ermsgstafflogo').text('');
            $('#error_staff_logo').val(0);
			}else{
			
			$('#ermsgstafflogo').text('Staff Logo Image size should be less then 730x300');
            $('#error_staff_logo').val(1);
            return false;
		}
        };
        img.src = _URL.createObjectURL(file);
    }
});
function validateURL() {
     var stafflgerr=$('#error_staff_logo').val();
 if(stafflgerr==1){
	  $( "#ContestContestImage" ).focus();
	  return false;
  }
        var contestname=$('#ContestContestName').val();
		if(contestname==''){
			$( "#ContestContestName" ).focus();
			alert('Please enter Contest Name.');
                        return false;
		}
              var contentdesc = tinyMCE.get('ContestContestDescription').getContent(); // msg = textarea id

        if( contentdesc == "" || contentdesc == null){
            $( "#ContestContestDescription" ).focus();
             alert( 'Please enter Contest Description.' );
            return false;
        }
	
         var content = tinyMCE.get('ContestContestArea').getContent(); // msg = textarea id

        if( content == "" || content == null){
            $( "#ContestContestArea" ).focus();
             alert( 'Please fill a contest area' );
            return false;
        }

}
function removeimg(filename,aname){
	$('#'+filename).val('');
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
