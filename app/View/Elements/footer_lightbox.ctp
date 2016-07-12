

 <!-- Modal -->
        <div id="dialog-message1" class="hide">
        
                   
                            <div class="row inquerybox1">
                                <div class="col-xs-12 clearfix inquerybox2">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
                                      
                                        <form action="" method="POST" name="footer_enquiry_form" id='footer_enquiry_form' >
                                        <table border='0' width='100%'>
                                            <tr>
                                                <td>Name<span style='color:red;'>*</span> </td>
                                                <td>
                                                    <div class="relative">
                                                    <input type="text" name='enquiry_name' id='enquiry_name'  class="required" style="width:55% !important;">
                                                   <div class="fix"></div>
                                                   </div>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Email<span style='color:red;'>*</span> </td>
                                                <td>
                                                        <div class="relative">
                                                    <input type="email" name='enquiry_email' id='enquiry_email'  class="required email" style="width:55% !important;">
                                                    <div class="fix"></div>
</div>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Subject<span style='color:red;'>*</span> </td>
                                                <td>
                                                <div class="relative">
                                                    <input type="text" name='enquiry_subject' id='enquiry_subject'  class="required" style="width:55% !important;">
                                                    <div class="fix"></div>
                                                 </div>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Message<span style='color:red;'>*</span> </td>
                                                <td>
                                                 <div class="relative">
                                                    <textarea name='enquiry_msg' id='enquiry_msg' rows='6' cols="20" class="required" style="width:55% !important; resize:none;"></textarea>
                                                    <div class="fix"></div>
                                                    </div>
                                                    </td>
                                            </tr>

                                            
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value='Send mail' id='send_mail' class="btn btn-primary buttondflt back_icon"  ></td>
                                            </tr>
                                            <tr>
                                            <td  colspan='2'><span id='email_status_div' style="display:none"><?php echo $this->html->image(CDN.'img/loading.gif');?> &nbsp;Please wait...</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan='2' id='footer_error_enquiry' style='color:green;margin-left:100px;'></td>
                                            </tr>
                                            
                                        </table>
                                        </form>
                                    </div>

                                   
                                </div>

                            </div>
                        
                    
                
            
        </div>   <!--popup--> 
       
     

        <script>
         $( "#id-btn-dialog2" ).on('click', function(e) {
$("#footer_error_enquiry").html("");
$(".error_validate").html("");
$("#enquiry_name").val("");
$("#enquiry_email").val("");
$("#enquiry_subject").val("");
$("#enquiry_msg").val("");
					e.preventDefault();
			
					var dialog = $( "#dialog-message1" ).removeClass('hide').dialog({
						modal: true,
						title: "Write To Us:",
						title_html: true,
                                              
						
					});
			
				});   
     function do_lightbox() {
       //$('.error_validate').remove();
       $('div.relative').children('div.error_validate').remove();

        $("#enquiry_name").val("");
        $("#enquiry_email").val("");
        $("#enquiry_subject").val("");
        $("#enquiry_msg").val("");

        $('#myModal_ft').addClass("modal fade popupBox in");
        $('#myModal_ft').attr('aria-hidden', false);
        $('#myModal_ft').css('display', 'block');
        $('#Mymodel1').addClass('modal-backdrop fade in');
        $("#footer_error_enquiry").html("");
    }
     function close_form_ft() {

        $('#myModal_ft').addClass("modal fade popupBox");
        $('#myModal_ft').attr('aria-hidden', true);
        $('#myModal_ft').css('display', 'none');
        $('#Mymodel1').removeClass('modal-backdrop fade in');
    }
    

 
$(document).ready(function() {
       
        $('#footer_enquiry_form').validate({
        showErrors: function(errorMap, errorList) {
			if (errorList.length) {
				var s = errorList.shift();
				var n = [];
				n.push(s);
				this.errorList = n;
			}
			this.defaultShowErrors();
		},
               submitHandler: function(form) {
                        $("#email_status_div").show();
                        var enquiry_name    =   $("#enquiry_name").val();
                        var enquiry_email   =   $("#enquiry_email").val();
                        var enquiry_subject =   $("#enquiry_subject").val();
                        var enquiry_msg =       $("#enquiry_msg").val();
                        
                       $.ajax({
                            type: "POST",
                            data: "enquiry_name=" + enquiry_name+"&enquiry_email="+enquiry_email+"&enquiry_subject="+enquiry_subject+"&enquiry_msg="+enquiry_msg,
                            url: "<?php echo Staff_Name ?>rewards/dofootenquirysubmit/",
                            success: function(result) {
                                    $("#email_status_div").hide();

                                    $("#enquiry_name").val("");
                                    $("#enquiry_email").val("");
                                    $("#enquiry_subject").val("");
                                    $("#enquiry_msg").val("");
                                   $("#footer_error_enquiry").text(result);
                            }
                    });
                }   
            
         });
});



 </script>


