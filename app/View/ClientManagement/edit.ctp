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
            width: '420', 
            height: '220', 
            relative_urls : true 
        });" 
    ); 
?> 
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-tachometer"></i> Clients
        </h1>
    </div>
    <?php 
     echo $this->Session->flash('good');
     echo $this->Session->flash('bad');
     ?>
        <?php echo $this->Form->create("Clinic",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));
        echo $this->Form->input("id", array("type" => "hidden"));
        ?>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"> Site Name</label>
        <div class="col-sm-9 col-xs-12">
                <?php echo $this->Form->input("api_user",array('label'=>false,'div'=>false,'placeholder'=>'Site Name','disabled' => 'disabled','required','class'=>'col-xs-12 col-sm-5','maxlength'=>'22')); ?>
            <input type="hidden" value="<?php echo $this->request->data['Clinic']['api_user']; ?>" name="site_name" id="site_name" >
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span> Display Name</label>
        <div class="col-sm-9 col-xs-12">

                <?php echo $this->Form->input("display_name",array('label'=>false,'div'=>false,'placeholder'=>'Display Name','class'=>'col-xs-12 col-sm-5')); ?>


        </div>
    </div> 
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Industry Type</label>
        <div class="col-sm-9 col-xs-12">
            <select name="data[Clinic][industry_type]" id="industry_type" required="required" class="col-xs-12 col-sm-5">
                <option value="">Select Industry Type</option>

                <?php foreach($indsurty as $ind){ ?>
                <option value="<?=$ind['IndustryType']['id']?>" <?php if($this->request->data['Clinic']['industry_type']==$ind['IndustryType']['id']){ echo "selected"; } ?>><?=$ind['IndustryType']['name']?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label  class="col-sm-3 col-xs-12 control-label no-padding-right" for="form-field-1"><span class="star">*</span>About Clinic</label>
        <div class="col-sm-9 col-xs-12">
            <?php 
                echo $this->Form->input("about",array('type' => 'text','label'=>false,'div'=>false,'value'=>$this->request->data['Clinic']['about'],'class'=>'col-xs-12 col-sm-5','maxlength'=>'1000')); 
                ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Staff Url</label>
        <div class="col-sm-9 col-xs-12">
                <?php echo $this->Form->input("staff_url",array('label'=>false,'div'=>false,'placeholder'=>'Staff Url','required','class'=>'col-xs-12 col-sm-5')); ?>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Patient Url</label>
        <div class="col-sm-9 col-xs-12">
                <?php echo $this->Form->input("patient_url",array('label'=>false,'div'=>false,'placeholder'=>'Patient Url','required','class'=>'col-xs-12 col-sm-5'));?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <div id="ermsgpatient" class="message"></div>
        <input type="hidden" id="error_patient_logo" name="error_patient_logo" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Rewards Site Client Logo</label>
        <div class="col-sm-9 col-xs-12">
                <?php
                if(isset($this->request->data['Clinic']['patient_logo_url']) && $this->request->data['Clinic']['patient_logo_url']!=''){
                  echo $this->Form->input("patient_logo_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientLogoUrl','plu');"));
                ?>
            <a onclick="removeimg('ClinicPatientLogoUrl', 'plu');" class="" id="plu"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['patient_logo_url']?>" height="100" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("patient_logo_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientLogoUrl','plu');"));
                  ?>
            <a onclick="removeimg('ClinicPatientLogoUrl', 'plu');" class="" id="plu"></a>
                  <?php
                  } ?>
        </div>
    </div>
    
    
       <div class="form-group Clearfix">
        <div id="ermsgbuzzydoc" class="message"></div>
        <input type="hidden" id="error_buzzdoc_logo" name="error_buzzdoc_logo" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">BuzzyDoc Site Client Logo</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['buzzydoc_logo_url']) && $this->request->data['Clinic']['buzzydoc_logo_url']!=''){
                  echo $this->Form->input("buzzydoc_logo_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicBuzzydocLogoUrl','blu');"));
                  ?>
            <a onclick="removeimg('ClinicBuzzydocLogoUrl', 'blu');" class="" id="blu"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['buzzydoc_logo_url']?>" height="136" width="71">
                  <?php
                  }else{
                  echo $this->Form->input("buzzydoc_logo_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicBuzzydocLogoUrl','blu');"));
                  ?>
            <a onclick="removeimg('ClinicBuzzydocLogoUrl', 'blu');" class="" id="blu"></a>
                  <?php
                  } ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"> Question Mark Image</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['patient_question_mark']) && $this->request->data['Clinic']['patient_question_mark']!=''){
                  echo $this->Form->input("patient_question_mark",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon',"onchange"=>"checkimg('ClinicPatientQuestionMark','pqm');"));
                  ?>
            <a onclick="removeimg('ClinicPatientQuestionMark', 'pqm');" class="" id="pqm"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['patient_question_mark']?>" height="200" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("patient_question_mark",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon',"onchange"=>"checkimg('ClinicPatientQuestionMark','pqm');"));
                  ?>
            <a onclick="removeimg('ClinicPatientQuestionMark', 'pqm');" class="" id="pqm"></a>
                  <?php
                  } ?>
        </div>
    </div>

    <div class="form-group Clearfix">
        <div id="ermsgpatientbk" class="message"></div>
        <input type="hidden" id="error_patient_bk" name="error_patient_bk" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Background Image</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['backgroud_image_url']) && $this->request->data['Clinic']['backgroud_image_url']!=''){
                echo $this->Form->input("backgroud_image_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon',"onchange"=>"checkimg('ClinicBackgroudImageUrl','bi');"));
                ?>
            <a onclick="removeimg('ClinicBackgroudImageUrl', 'bi');" class="" id="bi"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['backgroud_image_url']?>">
                <?php
                }else{
                        echo $this->Form->input("backgroud_image_url",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'hand-icon',"onchange"=>"checkimg('ClinicBackgroudImageUrl','bi');"));
                ?>
            <a onclick="removeimg('ClinicBackgroudImageUrl', 'bi');" class="" id="bi"></a>
                <?php
                } ?>

        </div>
    </div>
     <div class="form-group Clearfix">
        <div id="ermsgpatientsld1" class="message"></div>
        <input type="hidden" id="error_patient_slider_1" name="error_patient_slider_1" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Rewards Site Slider Image 1</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['patient_slider_image_1']) && $this->request->data['Clinic']['patient_slider_image_1']!=''){
                  echo $this->Form->input("patient_slider_image_1",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientSliderImage1','psl1');"));
                  ?>
            <a onclick="removeimg('ClinicPatientSliderImage1', 'psl1');" class="" id="psl1"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['patient_slider_image_1']?>" height="100" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("patient_slider_image_1",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientSliderImage1','psl1');"));
                  ?>
            <a onclick="removeimg('ClinicPatientSliderImage1', 'psl1');" class="" id="psl1"></a>
                  <?php
                  } ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <div id="ermsgpatientsld1" class="message"></div>
        <input type="hidden" id="error_patient_slider_2" name="error_patient_slider_2" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Rewards Site Slider Image 2</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['patient_slider_image_2']) && $this->request->data['Clinic']['patient_slider_image_2']!=''){
                  echo $this->Form->input("patient_slider_image_2",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientSliderImage2','psl2');"));
                  ?>
            <a onclick="removeimg('ClinicPatientSliderImage2', 'psl2');" class="" id="psl2"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['patient_slider_image_2']?>" height="100" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("patient_slider_image_2",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientSliderImage2','psl2');"));
                  ?>
            <a onclick="removeimg('ClinicPatientSliderImage2', 'psl2');" class="" id="psl2"></a>
                  <?php
                  } ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Reward Site Content</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->textarea('reward_site_content', array('class' => 'mceEditor'));?>
        </div>
    </div>
    
    <div class="form-group Clearfix">
        <div id="ermsgpatientmobimg" class="message"></div>
        <input type="hidden" id="error_patient_mobile_image" name="error_patient_mobile_image" value="0">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Rewards Site Image (Mobile)</label>
        <div class="col-sm-9 col-xs-12">
                <?php if(isset($this->request->data['Clinic']['patient_mobile_image']) && $this->request->data['Clinic']['patient_mobile_image']!=''){
                  echo $this->Form->input("patient_mobile_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientMobileImage','psmi');"));
                  ?>
            <a onclick="removeimg('ClinicPatientMobileImage', 'psmi');" class="" id="psmi"></a>
            <img src="<?=S3Path.$this->request->data['Clinic']['patient_mobile_image']?>" height="100" width="200">
                  <?php
                  }else{
                  echo $this->Form->input("patient_mobile_image",array('type' => 'file','label'=>false,'div'=>false,'accept'=>'image/*','class'=>'',"onchange"=>"checkimg('ClinicPatientMobileImage','psmi');"));
                  ?>
            <a onclick="removeimg('ClinicPatientMobileImage', 'psmi');" class="" id="psmi"></a>
                  <?php
                  } ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Reward Site Content (Mobile)</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->textarea('reward_site_mobile_content', array('class' => 'mceEditor')); ?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">HOW IT WORKS (Content)</label>
        <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->textarea('how_it_works', array('class' => 'mceEditor'));?>
        </div>
    </div>
    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Google Analytics Code</label>
        <div class="col-sm-9 col-xs-12">
                <?php 
                echo $this->Form->input("analytic_code",array('type' => 'text','label'=>false,'div'=>false,'value'=>$analytic_code,'class'=>'col-xs-12 col-sm-5','maxlength'=>'1000')); 
                ?>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Facebook App Id</label>
        <div class="col-sm-9 col-xs-12">
                    <?php
                         if(isset($this->request->data['Clinic']['fb_app_id'])){ $fb_app_id=$this->request->data['Clinic']['fb_app_id']; }else{ $fb_app_id=''; }
                         if(isset($this->request->data['Clinic']['fb_app_key'])){ $fb_app_key=$this->request->data['Clinic']['fb_app_key']; }else{ $fb_app_key=''; }

                        echo $this->Form->input("fb_app_id",array('type' => 'text','label'=>false,'div'=>false,'value'=>$fb_app_id,'class'=>'col-xs-12 col-sm-5','maxlength'=>'50')); ?>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Facebook App Secret Key</label>
        <div class="col-sm-9 col-xs-12">
                        <?php echo $this->Form->input("fb_app_key",array('type' => 'text','label'=>false,'div'=>false,'value'=>$fb_app_key,'class'=>'col-xs-12 col-sm-5','maxlength'=>'50')); ?>
        </div>
    </div>



    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><b>Staff User</b></label></div>


    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Staff First Name</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="20" class="col-xs-12 col-sm-5" placeholder="First Name" id="first_name" name="first_name" value="<?php echo $this->request->data['Clinic']['staff_first_name']; ?>" required>
            <input type="hidden" id="sid" name="sid" value="<?php echo $this->request->data['Clinic']['sid']; ?>" >
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Staff Last Name</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="20" class="col-xs-12 col-sm-5" placeholder="Last Name" id="last_name" name="last_name" value="<?php echo $this->request->data['Clinic']['staff_last_name']; ?>" required>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Staff Email</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Email" id="staff_email" name="staff_email" value="<?php echo $this->request->data['Clinic']['staff_email']; ?>" required>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>UserName</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="UserName" id="staff_id" name="staff_id" value="<?php echo $this->request->data['Clinic']['staff_id']; ?>" required>
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Current Password</label>
        <div class="col-sm-9 col-xs-12">
            <input type="password"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Current password" id="new_password3" name="new_password3" value="">
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Change Password</label>
        <div class="col-sm-9 col-xs-12">
            <input type="password"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Change password" id="new_password" name="new_password" value="">
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Verify Password</label>
        <div class="col-sm-9 col-xs-12">
            <input type="password"  maxlength="50" class="col-xs-12 col-sm-5" placeholder="Change password" id="new_password2" name="new_password2" value="">
        </div>
    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Landing Page</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="data[Clinic][landing]" id="landing" class="" <?php if( $this->request->data['Clinic']['landing']==1){ echo "checked"; } ?> > Search Page
            <input type="radio" value="0" name="data[Clinic][landing]" id="landing" class="" <?php if( $this->request->data['Clinic']['landing']==0){ echo "checked"; } ?> > Dashboard Page
        </div>

    </div>

    <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Is BuzzyDoc ?</label>


        <div class="col-sm-9 col-xs-12" id='isBuzzy'>
			<?php if($remainday>0){ ?>
            <input type="hidden" id="in_notice" name="in_notice" value="1">
			<input type="radio" value="1" name="is_buzzydoc" id="is_buzzydoc" class="" disabled>
            Yes

            <input type="radio" value="0" name="is_buzzydoc" id="is_buzzydoc" class="" checked disabled>
            No &nbsp; <b>In Notice period (<?php echo $remainday;?> days remain)</b> &nbsp;<a href="javascript:void(0);" onclick="cancelDowngrade();">Cancel Request</a>
			<?php }else{ ?>
            <input type="hidden" id="in_notice" name="in_notice" value="0">
            <input type="radio" value="1" name="is_buzzydoc" id="is_buzzydoc" class="" <?php if( $this->request->data['Clinic']['is_buzzydoc']==1){ echo "checked"; } ?> onclick="islite();">
            Yes

            <input type="radio" value="0" name="is_buzzydoc" id="is_buzzydoc" class="" <?php if( $this->request->data['Clinic']['is_buzzydoc']==0){ echo "checked"; } ?> onclick="islite();">
            No
            <?php } ?>
        </div>
    </div>

    <div class="form-group Clearfix" id="liteversion">
            <?php if($this->request->data['Clinic']['is_buzzydoc']==1){
                $chkdespl="block";
                ?>
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Is Lite Version ?</label>
        <div class="col-sm-9 col-xs-12">
            <input type="radio" value="1" name="data[Clinic][is_lite]" id="is_lite" class="" <?php if( $this->request->data['Clinic']['is_lite']==1){ echo "checked"; } ?>> Yes

            <input type="radio" value="0" name="data[Clinic][is_lite]" id="is_lite" class="" <?php if( $this->request->data['Clinic']['is_lite']==0){ echo "checked"; } ?>> No
        </div>
                <?php }else{
                    $chkdespl="none";
                } ?>
    </div>
    <div class="form-group Clearfix" id="mDep" style="display: <?php echo $chkdespl; ?>;">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right"><span class="star">*</span>Minimum Deposit</label>
        <div class="col-sm-9 col-xs-12">
            <input type="text"  maxlength="10" class="col-xs-12 col-sm-5" placeholder="Minimum Deposit ($)" id="minimum_deposit" name="data[Clinic][minimum_deposit]" value="<?=$this->request->data['Clinic']['minimum_deposit']?>" onkeyup="this.value = this.value.replace(/\D/g, '')">
        </div>
    </div>

    <div class="col-md-offset-3 col-md-9">
        <input type="submit" value="Save Credentials" class="btn btn-sm btn-primary" onclick="return validateURL();">
    </div>
</form>
</div>   
<?php if($this->request->data['Clinic']['is_buzzydoc']==1){ $chckbuz=$this->request->data['Clinic']['is_buzzydoc']; }else{ $chckbuz=0; }?>
<script>
    function islite() {

        var isbuzzy = $('input:radio[name=is_buzzydoc]').filter(":checked").val();

        if (isbuzzy == 1) {
            $('#liteversion').html('<label class="col-sm-3 col-xs-12 control-label no-padding-right">Is Lite Version ?</label> <div class="col-sm-9 col-xs-12"><input type="radio" value="1" name="data[Clinic][is_lite]" id="is_lite" class=""> Yes <input type="radio" value="0" name="data[Clinic][is_lite]" id="is_lite" class=""> No</div>');
            $('#mDep').show();
            
        } else {
			
            $('#liteversion').html('');
            $('#mDep').hide();
        }
    }
    function cancelDowngrade(){
		var clinicid = $('#ClinicId').val();
        datasrc = "clinic_id=" + clinicid;
    var r = confirm("You have applied to cancel request for downgrade, Do you want to continue ?");
            if (r == true) {
            $.ajax({
                    type: "POST",
                    data: datasrc,
                    url: "<?=Staff_Name?>ClientManagement/cancelDowngrade/",
                    success: function(result) {
						$('#isBuzzy').html('<input type="hidden" id="in_notice" name="in_notice" value="0"><input type="radio" value="1" name="is_buzzydoc" id="is_buzzydoc" class="" checked> Yes<input type="radio" value="0" name="is_buzzydoc" id="is_buzzydoc" class=""> No');
                    }
                });
            } else {
                return false;
            }
    }
    function validateURL() {


        var staffurl = $('#ClinicStaffUrl').val();
        var patienturl = $('#ClinicPatientUrl').val();
        var indtype = $('#industry_type').val();


        var urlregex = new RegExp("^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
        var staffchk = urlregex.test(staffurl);
        var patientchk = urlregex.test(patienturl);
        var display = $('#ClinicDisplayName').val();

        if ($.trim(display) == '') {
            $("#ClinicDisplayName").focus();
            alert('Please enter display name.');
            return false;
        }
        if (indtype == '') {
            $("#industry_type").focus();
            alert('Please select Industry Type.');
            return false;
        }
        var about = $('#ClinicAbout').val();
        if ($.trim(about) == '') {
            $("#ClinicAbout").focus();
            alert('Please enter about clinic.')
            return false;
        }

        if (staffurl == '') {
            $("#ClinicStaffUrl").focus();
            alert('Please enter Staff Url.');
            return false;
        }
        if (staffchk == false && staffurl != '') {
            $("#ClinicStaffUrl").focus();
            alert('Please enter valid Staff Url.');
            return false;
        }

        if (patienturl == '') {
            $("#ClinicPatientUrl").focus();
            alert('Please enter Patient Url.');
            return false;
        }
        if (patientchk == false && patienturl != '') {
            $("#ClinicPatientUrl").focus();
            alert('Please enter valid Patient Url.')
            return false;
        }

        var stafflgerr = $('#error_staff_logo').val();
        var patlgerr = $('#error_patient_logo').val();
        var buzzlgerr = $('#error_buzzdoc_logo').val();
        var patftlgerr = $('#error_patient_logo_ftr').val();
        var patbkerr = $('#error_patient_bk').val();
        if (stafflgerr == 1) {
            $("#ClinicStaffLogoUrl").focus();
            return false;
        }
        if (patlgerr == 1) {
            $("#ClinicPatientLogoUrl").focus();
            return false;
        }
        if (buzzlgerr == 1) {
            $("#ClinicBuzzydocLogoUrl").focus();
            return false;
        }
        if (patftlgerr == 1) {
            $("#ClinicPatientFooterLogoUrl").focus();
            return false;
        }
        if (patbkerr == 1) {
            $("#ClinicBackgroudImageUrl").focus();
            return false;
        }
        var patsld1 = $('#error_patient_slider_1').val();
        if (patsld1 == 1) {
            $("#ClinicPatientSliderImage1").focus();
            return false;
        }
        var patsld2 = $('#error_patient_slider_2').val();
        if (patsld2 == 1) {
            $("#ClinicPatientSliderImage2").focus();
            return false;
        }
        var patsmi = $('#error_patient_mobile_image').val();
        if (patsmi == 1) {
            $("#ClinicPatientMobileImage").focus();
            return false;
        }
        var stafffname = $('#first_name').val();
        if ($.trim(stafffname) == '') {
            $("#first_name").focus();
            alert('Please enter Staff First Name.')
            return false;
        }
        var stafflname = $('#last_name').val();
        if ($.trim(stafflname) == '') {
            $("#last_name").focus();
            alert('Please enter Staff Last Name.')
            return false;
        }
        var staffemail = $('#staff_email').val();
        if (staffemail == '') {
            $("#staff_email").focus();
            alert('Please enter Staff Email.')
            return false;
        }
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        var emailchk = regex.test(staffemail);
        if (emailchk == false) {
            $("#staff_email").focus();
            alert('Please enter valid Staff Email.')
            return false;
        }
        var staffuname = $('#staff_id').val();
        if ($.trim(staffuname) == '') {
            $("#staff_id").focus();
            alert('Please enter Staff Username.')
            return false;
        }
        var isbuzzy = $('input:radio[name=is_buzzydoc]').filter(":checked").val();
        var mininum = $('#minimum_deposit').val();
        if ($.trim(mininum) == '' && isbuzzy==1) {
            $("#minimum_deposit").focus();
            alert('Please enter Minimum Deposit.')
            return false;
        }
//        if ($.trim(mininum) < 250 && isbuzzy==1) {
//            $("#minimum_deposit").focus();
//            alert('Minimum deposit should be equal to 250 or above')
//            return false;
//        }




        var curpassword = '<?php echo $this->request->data['Clinic']['staff_password']; ?>';
        var cpwd = $('#new_password3').val();
        var npwd = $('#new_password').val();
        var cnfpwd = $('#new_password2').val();

        if (cpwd != '') {

            if (cpwd != curpassword) {
                alert('Wrong current Password.');
                return false;
            }
            if (npwd == '') {
                alert('Please enter change password.');
                return false;
            }
        }
        if (npwd != '' && npwd.length < 6) {
            alert('Password must be at least 6 characters long');
            return false;
        }

        if (npwd != '' && npwd != cnfpwd) {
            alert('Please enter same password again.');
            return false;
        }

        var id = $('#sid').val();
        var clinicid = $('#ClinicId').val();
        var staff_id = $('#staff_id').val();
        var staff_email = $('#staff_email').val();
        datasrc = "clinic_id=" + clinicid + '&staff_id=' + staff_id + '&id=' + id + '&staff_email=' + staff_email;
        var checkbuzzy = $('input:radio[name=is_buzzydoc]').filter(":checked").val();
        if (checkbuzzy == 0 && <?php echo $chckbuz; ?> == 1 && $('#in_notice').val()==0) {
            var r = confirm("You have applied for a downgrade , A downgrade will result in unavailability of many function, Do you want to continue ?");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    data: datasrc,
                    url: "<?=Staff_Name?>ClientManagement/checkapiuser/",
                    success: function(result) {

                        if (result == 1) {
                            alert('Staff already exist.');
                            return false;
                        } else if (result == 2) {
                            alert('Staff email already exist.');
                            return false;
                        } else {
                            $("#ClinicEditForm").submit();
                            return true;
                        }
                    }
                });
            } else {
                return false;
            }
        } else {
            $.ajax({
                type: "POST",
                data: datasrc,
                url: "<?=Staff_Name?>ClientManagement/checkapiuser/",
                success: function(result) {

                    if (result == 1) {
                        alert('Staff already exist.');
                        return false;
                    } else if (result == 2) {
                        alert('Staff email already exist.');
                        return false;
                    } else {
                        $("#ClinicEditForm").submit();
                        return true;
                    }
                }
            });
        }

        return false;
    }
</script>
<script>
    function check() {
        var ptlog = $('#error_patient_logo').val();
        var ptlogft = $('#error_patient_logo_ftr').val();
        if (ptlogft == 1 || ptlog == 1) {
            return false;
        }
    }
    function removeimg(filename, aname) {
        $('#' + filename).val('');
        $('#' + aname).text('');
        $('#' + aname).removeClass('icon-top hand-icon');
    }
    function checkimg(filename, aname) {
        var sluval = $('#' + filename).val();
        if (sluval != '') {
            $('#' + aname).text('x');
            $('#' + aname).addClass('icon-top hand-icon');
        }
    }
    var _URL = window.URL || window.webkitURL;

    $("#ClinicPatientLogoUrl").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 246 && this.height <= 88) {
                    $('#ermsgpatient').text('');
                    $('#error_patient_logo').val(0);
                } else {

                    $('#ermsgpatient').text('Rewards Site Client Logo size should be less then 246x88');
                    $('#error_patient_logo').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicBuzzydocLogoUrl").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 71 && this.height <= 136) {
                    $('#ermsgbuzzydoc').text('');
                    $('#error_buzzdoc_logo').val(0);
                } else {

                    $('#ermsgbuzzydoc').text('Buzzydoc Site Client Logo size should be less then 71x136');
                    $('#error_buzzdoc_logo').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicPatientFooterLogoUrl").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 112 && this.height <= 68) {
                    $('#ermsgpatientftr').text('');
                    $('#error_patient_logo_ftr').val(0);
                } else {
                    $('#ermsgpatientftr').text('Patient Logo Footer Image size should be less then 112x68');
                    $('#error_patient_logo_ftr').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicBackgroudImageUrl").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 730 && this.height <= 250) {
                    $('#ermsgpatientbk').text('');
                    $('#error_patient_bk').val(0);
                } else {
                    $('#ermsgpatientbk').text('Reward Background Image size should be less then 730x250');
                    $('#error_patient_bk').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicPatientSliderImage1").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 962 && this.height <= 342) {
                    $('#ermsgpatientsld1').text('');
                    $('#error_patient_slider_1').val(0);
                } else {

                    $('#ermsgpatientsld1').text('Rewards Site Slider Image 1 size should be less then 961x342');
                    $('#error_patient_slider_1').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicPatientSliderImage2").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 962 && this.height <= 342) {
                    $('#ermsgpatientsld2').text('');
                    $('#error_patient_slider_2').val(0);
                } else {

                    $('#ermsgpatientsld2').text('Rewards Site Slider Image 2 size should be less then 961x342');
                    $('#error_patient_slider_2').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    $("#ClinicPatientMobileImage").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {

                if (this.width <= 639 && this.height <= 410) {
                    $('#ermsgpatientmobimg').text('');
                    $('#error_patient_mobile_image').val(0);
                } else {

                    $('#ermsgpatientmobimg').text('Rewards Site Image (Mobile) size should be less then 639x410');
                    $('#error_patient_mobile_image').val(1);
                    return false;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
    });

</script>
