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
            width: '520', 
            height: '420', 
            relative_urls : true 
        });" 
    ); 
?> 
<div class="contArea Clearfix">
    <div class="page-header">
        <h1><i class="menu-icon fa fa-tachometer"></i>Service Management</h1>
    </div>
    <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    <?php echo $this->Form->create("Service",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>
    <div class="form-group Clearfix">
        <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1">Service Name</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("service_name",array('label'=>false,'div'=>false,'placeholder'=>'Service Name','class'=>'col-xs-12 col-sm-5','maxlength'=>'30')); ?>
        </div>
    </div>
    <!-- <div class="form-group Clearfix">
        <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1">Service Image</label>
        <div class="col-sm-9 col-xs-12">
        	<?php //echo $this->Form->input("service_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon')); ?>
        </div>
    </div> -->
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Service Content</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->textarea('service_content', array('class' => 'mceEditor')); ?>
        </div>
    </div>
    <div class="col-md-offset-3 col-md-9">
        <input type="submit" value="Save" class="btn btn-sm btn-primary">
    </div>
    <?php echo $this->Form->end();?>
</div>