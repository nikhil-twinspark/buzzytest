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
		var connector = "/js/tiny_mce/file_manager.php";
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
                    <i class="menu-icon fa fa-cubes"></i> Industries
                </h1>
            </div>
        
         <?php 
            //echo $this->element('messagehelper'); 
            echo $this->Session->flash('good');
            echo $this->Session->flash('bad');
        ?>
     
     <div class="adminsuper">
 
		<?php echo $this->Form->create("Refpromotion",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>
         
                <div class="form-group Clearfix">
                    <label class="col-sm-3 col-xs-12 control-label no-padding-right" ><span class="star">*</span> Referral Promotion Name</label>
                    <input type="hidden" name="data[Refpromotion][industry_id]" value="<?php echo $industryid;?>">
                    <div class="col-sm-9 col-xs-12">
                        <?php echo $this->Form->input("promotion_name",array('label'=>false,'div'=>false,'placeholder'=>'Referral Promotion Name','required','class'=>'col-xs-12 col-sm-5')); ?>
                    </div>
                </div>
      
		
		<div class="form-group Clearfix">
                    <label class="col-sm-3 col-xs-12 control-label no-padding-right" ></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php echo $this->Form->textarea('promotion_area', array('class' => 'ckeditor'));?>
                    </div>
                </div>
       
                		
        <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Save Referral Promotion" class="btn btn-sm btn-primary" onclick="return validateURL();" >
        </div>

      
     </div>
     </form>
   </div>
   </div><!-- container -->

<script>

    
function validateURL() {

        var contestname=$('#RefpromotionPromotionName').val();
		if(contestname==''){
			$( "#RefpromotionPromotionName" ).focus();
			alert('Please enter Referral Promotion Name.');
                        return false;
		}
       
	 var content = tinyMCE.get('RefpromotionPromotionArea').getContent(); // msg = textarea id

        if( content == "" || content == null){
             alert( 'Please fill a referral promotion area' );
            return false;
        }

}



</script>
