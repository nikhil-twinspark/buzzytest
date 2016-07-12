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
	<?php echo $this->Form->create("EmailTemplate",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));
		echo $this->Form->input("id", array("type" => "hidden"));
                //echo "<pre>";print_r($this->request->data);
		?>

<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span>Email For</label>
    <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("textname",array('label'=>false,'div'=>false,'class'=>'col-xs-12 col-sm-5','value'=>$this->request->data['EmailList']['name'],'readonly','maxlength'=>'100')); ?>
        <input type="hidden" id="EmailTemplateEmailFor" required="required" value="<?php echo $this->request->data['EmailTemplate']['email_for']; ?>" name="data[EmailTemplate][email_for]">

    </div>
</div>

<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span>Subject</label>
    <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("subject",array('label'=>false,'div'=>false,'class'=>'col-xs-12 col-sm-5','value'=>$this->request->data['EmailTemplate']['subject'],'placeholder'=>'Subject','required','maxlength'=>'100')); ?>

    </div>
</div>
       <?php if($this->request->data['EmailList']['id']==22){ ?>
<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">Email Head Message</label>
    <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("header_msg",array('label'=>false,'div'=>false,'class'=>'col-xs-12 col-sm-5','value'=>$this->request->data['EmailTemplate']['header_msg'],'placeholder'=>'Email Head Message','maxlength'=>'100')); ?>

    </div>
</div>
         <?php } ?>
<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Email Body

                <?php 
                $keyword_array=array('[credit_amount]'=>'Minimum Deposit Amount','[staff_name]'=>'Staff Name','[clinic_name]'=>'Clinic Name','[link_url]'=>'Buzzydoc Url','[request_type]'=>'Request Type','[description]'=>'Description','[password]'=>'New Password','[username]'=>'Patient Name','[days]'=>'No. of days','[remain_month]'=>'No. of remain months','[points]'=>'Points','[first_name]'=>'First Name','[last_name]'=>'Last Name','[emailid]'=>'Email-Id','[card_number]'=>'Card Number','[account_password]'=>'Password','[click_here]'=>'BuzzyDoc Link','[emailid/staff_name]'=>'Patient Email-Id/Staff Name','[version]'=>'Version (Legacy/Buzzydoc)','[reduced_amount]'=>'Reduced Amount','[current_balance]'=>'Current Balance','[away_amount]'=>'Reach to threshold','[accept_link]'=>'Accept Link','[order_number]'=>'Order Number','[amount]'=>'Amount','[reset_link]'=>'Reset Password','[Identifier]'=>'Unique Identifier','[last_month]'=>'From months');

foreach($keyword_array as $key=>$val){
if (strpos($this->request->data['EmailTemplate']['subject'], $key) !== false || strpos($this->request->data['EmailTemplate']['header_msg'], $key) !== false || strpos($this->request->data['EmailTemplate']['content'], $key) !== false) {
    
    ?>
        <label class="col-sm-10 col-xs-12 control-label no-padding-right"><b><?php echo $key; ?></b> : <?php if($this->request->data['EmailList']['id']==30 && $key=='[amount]'){
        echo 'Credit Amount';
    }else{ echo $val;} ?></label>
        <?php
}}
                 ?>
                <?php if($this->request->data['EmailList']['id']==29){ ?><label class="col-sm-10 col-xs-12 control-label no-padding-right"><b>[text]</b> : a or an</label><?php } ?>
                <?php if($this->request->data['EmailList']['id']==22){ ?><label class="col-sm-10 col-xs-12 control-label no-padding-right"><b>[text]</b> : reward or rewards</label><?php } ?>

    </label>
    <div class="col-sm-9 col-xs-12">
                <?php echo $this->Form->textarea('content', array('class' => 'ckeditor','value'=>$this->request->data['EmailTemplate']['content']));?>
    </div>
</div>
<div class="form-group Clearfix">
    <label class="col-sm-3 col-xs-12 control-label no-padding-right">SMS Body</label>
    <div class="col-sm-9 col-xs-12">
        <input id="sms_body" style="width: 621px;" type="text" maxlength="2048" placeholder="Sms Body" value="<?php echo $this->request->data['EmailTemplate']['sms_body']; ?>" name="data[EmailTemplate][sms_body]">

    </div>
</div>
<div class="col-md-offset-3 col-md-9">

    <input type="submit" value="Save Template" class="btn btn-sm btn-primary" onclick="return validateURL();"><br>
    <br>
    <input type="text" id="emailTo" maxlength="100" placeholder="Send To" value="" class="col-xs-12 col-sm-5" name="emailTo">
    <br>
    <br>
    <input type="button" value="Receive Preview Mail" id="submit-redeem" class="btn btn-sm btn-primary" onclick="getpreview();"><span id="redeemload"></span>
    <br>
    <br>
    <input type="text" id="smsTo" maxlength="10"  onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" placeholder="Send To" value="" class="col-xs-12 col-sm-5" name="smsTo">
    <br>
    <br>
    <input type="button" value="Receive Preview SMS" id="submit-sms" class="btn btn-sm btn-primary" onclick="getsmspreview();"><span id="smsload"></span>
</div>
</form>


<script>
    function validateEmail(email) {
        var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return re.test(email);
    }
    function validateURL() {

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
    function getpreview() {
        var email = $.trim($('#emailTo').val());
        if (email == '') {
            alert('Blank email address');
            return false;
        }
        if (validateEmail(email)) {
            $("#redeemload").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
            $('#submit-redeem').attr('disabled', 'disabled');
            datasrc = $("#EmailTemplateEditForm").serialize();
            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>EmailManagement/emailPreview/",
                success: function (result) {
                    $("#redeemload").html('');
                    $('#submit-redeem').removeAttr('disabled');
                    alert('Preview email sent successfully.');

                }
            });

        } else {
            alert('Invalid Email Address.');
        }
    }
    function getsmspreview() {
        var email = $.trim($('#smsTo').val());
        var sms_body=$('#sms_body').val();
        if (sms_body == '') {
            alert('Blank SMS body');
            return false;
        }
        if (email == '') {
            alert('Blank phone number');
            return false;
        }
        $("#smsload").html('<img alt="Please wait" title="BuzzyDoc" src="<?php echo CDN; ?>img/images_buzzy/loading.gif">');
        $('#submit-sms').attr('disabled', 'disabled');
        datasrc = $("#EmailTemplateEditForm").serialize();
        $.ajax({
            type: "POST",
            data: datasrc,
            url: "<?=Staff_Name?>EmailManagement/smsPreview/",
            success: function (result) {
                $("#smsload").html('');
                $('#submit-sms').removeAttr('disabled');
                alert('Preview sms sent successfully.');

            }
        });
    }
</script>
