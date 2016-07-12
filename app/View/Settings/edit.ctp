
<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
 <div class="page-header">
<h1>
    <i class="ace-icon fa fa-cogs"></i>
Social Links
</h1>
</div>
    	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    
  
      <?php echo $this->Form->create("Clinic",array('class'=>'form-horizontal'));
     

 ?>
        <div class="form-group">
            <input type="hidden"  id="id" name="id" value="<?php echo $Clinics['Clinic']['id']; ?>">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Facebook Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Facebook Url" id="facebook_url" name="facebook_url" value="<?php echo $Clinics['Clinic']['facebook_url']; ?>">
        </div>
        </div>
   <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Pintrest Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Pintrest Url" id="pintrest_url" name="pintrest_url" value="<?php echo $Clinics['Clinic']['pintrest_url']; ?>">
        </div>
        </div>
    <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Twitter Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Twitter Url" id="twitter_url" name="twitter_url" value="<?php echo $Clinics['Clinic']['twitter_url']; ?>">
        </div>
        </div>
         <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Instagram Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Instagram Url" id="instagram_url" name="instagram_url" value="<?php echo $Clinics['Clinic']['instagram_url']; ?>">
        </div>
        </div>
     <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Google Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="255" class="col-xs-10 col-sm-5" placeholder="Google Url" id="google_url" name="google_url" value="<?php echo $Clinics['Clinic']['google_url']; ?>">
        </div>
        </div>
         <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Yelp Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Yelp Url" id="yelp_url" name="yelp_url" value="<?php echo $Clinics['Clinic']['yelp_url']; ?>">
        </div>
        </div>
        <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Youtube Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Youtube Url" id="youtube_url" name="youtube_url" value="<?php echo $Clinics['Clinic']['youtube_url']; ?>">
        </div>
        </div>
        <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Healthgrade Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Healthgrade Url" id="healthgrade_url" name="healthgrade_url" value="<?php echo $Clinics['Clinic']['healthgrade_url']; ?>">
        </div>
        </div>
        <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Website Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="100" class="col-xs-10 col-sm-5" placeholder="Website Url" id="website_url" name="website_url" value="<?php echo $Clinics['Clinic']['website_url']; ?>">
        </div>
        </div>
<!--        <div class="form-group">

        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Google Business Page Url</label>

        <div class="col-sm-9">
            <input type="text"  maxlength="255" class="col-xs-10 col-sm-5" placeholder="Google Business Page Url" id="google_review_page_url" name="google_review_page_url" value="<?php echo $Clinics['Clinic']['google_review_page_url']; ?>">
        </div>
        </div>-->
        
<div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Update" class="btn btn-info"  onclick="return validateURL();">
       
									</div>  
      </form>
 
    
     
   </div>
 

<script language="Javascript">
 function validateURL() {


  
 
  var urlregex = new RegExp( "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
 
 
 
  var fburl=$('#facebook_url').val();
  var fbchk=urlregex.test(fburl);
  if(fbchk==false  && fburl!=''){
	   $( "#facebook_url" ).focus();
  alert('Please enter valid Facebook Url.')
  return false;
  }
  var pturl=$('#pintrest_url').val();
 
  var ptchk=urlregex.test(pturl);
  if(ptchk==false   && pturl!=''){
	    $( "#pintrest_url" ).focus();
  alert('Please enter valid Pintrest Url.')
  return false;
  }
  var twurl=$('#twitter_url').val();
  var twchk=urlregex.test(twurl);
  if(twchk==false  && twurl!=''){
	  $( "#twitter_url" ).focus();
  alert('Please enter valid Twitter Url.')
  return false;
  }
  var insurl=$('#instagram_url').val();
  var inschk=urlregex.test(insurl);
  if(inschk==false  && insurl!=''){
	   $( "#instagram_url" ).focus();
  alert('Please enter valid Instagram Url.')
  return false;
  }
  var gglurl=$('#google_url').val();
  var gglchk=urlregex.test(gglurl);
  if(gglchk==false  && gglurl!=''){
	  $( "#google_url" ).focus();
  alert('Please enter valid Google Url.')
  return false;
  }
  var yelurl=$('#yelp_url').val();
  var yelchk=urlregex.test(yelurl);
  if(yelchk==false  && yelurl!=''){
	    $( "#yelp_url" ).focus();
  alert('Please enter valid Yelp Url.')
  return false;
  }
  var yturl=$('#youtube_url').val();
  var ytchk=urlregex.test(yturl);
  if(ytchk==false  && yturl!=''){
	   $( "#youtube_url" ).focus();
  alert('Please enter valid Youtube Url.')
  return false;
  }
  var hdurl=$('#healthgrade_url').val();
  var hdchk=urlregex.test(hdurl);
  if(hdchk==false  && hdurl!=''){
	  $( "#healthgrade_url" ).focus();
  alert('Please enter valid Health Grade Url.')
  return false;
  }
  var weburl=$('#website_url').val();
  var webchk=urlregex.test(weburl);
  if(webchk==false  && weburl!=''){
	  $( "#website_url" ).focus();
  alert('Please enter valid Website Url.')
  return false;
  }

}
</script>




