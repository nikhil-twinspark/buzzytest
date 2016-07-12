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
            file: connector,
            title: 'File Manager',
            width: 650,
            height: 550,
            resizable: "yes",
            inline: "yes",
            close_previous: "no"
        }, {
            window: win,
            input: field_name
        });
    }

</script>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-envelope"></i>Email Management
        </h1>
    </div>
</div>
    <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
     ?>


        <?php echo $this->Form->create("EmailTemplate",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));?>

<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Email For</label>
    <div class="col-sm-9 col-xs-12">

        <select class="" name="data[EmailTemplate][email_for]" id="EmailTemplateEmailFor">
            <option value="">Select Email For</option>
                <?php foreach($email_list as $list){ ?>
            <option value="<?php echo $list['email_lists']['id']; ?>"><?php echo $list['email_lists']['name']; ?></option>
                <?php } ?>
        </select>
    </div>
</div>

<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Subject</label>
    <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("subject",array('label'=>false,'div'=>false,'placeholder'=>'Subject','required','class'=>'col-xs-12 col-sm-5','maxlength'=>'100')); ?>
    </div>
</div>
<!--            <div class="form-group Clearfix">
                <label class="col-sm-3 col-xs-12 control-label no-padding-right">Email Head Message</label>
                <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("header_msg",array('label'=>false,'div'=>false,'placeholder'=>'Email Head Message','class'=>'col-xs-12 col-sm-5','maxlength'=>'100')); ?>
                </div>
            </div>-->
<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Email Body
                <?php 
                $keyword_array=array('[credit_amount]'=>'Minimum Deposit Amount','[staff_name]'=>'Staff Name','[clinic_name]'=>'Clinic Name','[link_url]'=>'Buzzydoc Url','[request_type]'=>'Request Type','[description]'=>'Description','[password]'=>'New Password','[username]'=>'Patient Name','[days]'=>'No. of days','[remain_month]'=>'No. of remain months','[points]'=>'Points','[first_name]'=>'First Name','[last_name]'=>'Last Name','[emailid]'=>'Email-Id','[card_number]'=>'Card Number','[account_password]'=>'Password','[click_here]'=>'BuzzyDoc Link','[emailid/staff_name]'=>'Patient Email-Id/Staff Name','[version]'=>'Version (Legacy/Buzzydoc)','[reduced_amount]'=>'Reduced Amount','[current_balance]'=>'Current Balance','[away_amount]'=>'Reach to threshold','[accept_link]'=>'Accept Link','[order_number]'=>'Order Number','[amount]'=>'Amount','[reset_link]'=>'Reset Password','[Identifier]'=>'Unique Identifier','[last_month]'=>'From months');

foreach($keyword_array as $key=>$val){
    ?>
        <label class="col-xs-12 control-label no-padding-right"><b><?php echo $key; ?></b> : <?php  echo $val; ?></label>
<?php } ?>
    </label>
    <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->textarea('content', array('class' => 'ckeditor'));?>
    </div>
</div>

<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">SMS Body</label>
    <div class="col-sm-9 col-xs-12">
            <input id="sms_body" style="width: 621px;" type="text" maxlength="2048" placeholder="Sms Body" value="" name="data[EmailTemplate][sms_body]">

    </div>
</div>


<div class="col-md-offset-3 col-md-9">
    <input type="submit" value="Save Email Template" class="btn btn-sm btn-primary" onclick="return validateURL();">
</div>

</form>


<script>

    function validateURL() {
        var stafflgerr = $('#error_staff_logo').val();

        var contestname = $('#EmailTemplateEmailFor').val();
        if (contestname == '') {
            $("#EmailTemplateEmailFor").focus();
            alert('Please enter Email for.');
            return false;
        }
        var subject = $('#EmailTemplateSubject').val();
        if (subject == '') {
            $("#EmailTemplateSubject").focus();
            alert('Please enter Subject.');
            return false;
        }

        var contentdesc = tinyMCE.get('EmailTemplateContent').getContent(); // msg = textarea id

        if (contentdesc == "" || contentdesc == null) {
            $("#EmailTemplateContent").focus();
            alert('Please enter Email Body.');
            return false;
        }


    }


</script>
