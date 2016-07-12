
 <!-- Modal -->
        <div class="modal fade popupBox" id="myModal_ft" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-sm">
                <div class="modal-content popup ">
                    <div class="row rowcont">
                        <div class="modal-header col-md-12" style="position:relative;">
                            <a class="close closebtn" onclick="close_form_ft();">&times;</a>
                        </div>
                    </div>
                    
                        <div class="modal-body clearfix wid-100">
                            <div class=" row">
                                <div class="col-xs-12 clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
                                        <h3>Write to us: </h3>
                                        <form action="" method="POST" name="footer_enquiry_form" id='footer_enquiry_form' >
                                        <table border='0' width='100%'>
                                            <tr>
                                                <td>Name<span style='color:red;'>*</span> </td>
                                                <td>
                                                    <div class="relative">
                                                    <input type="text" name='enquiry_name' id='enquiry_name'  class="required" >
                                                   <div class="fix"></div>
                                                   </div>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Email<span style='color:red;'>*</span> </td>
                                                <td>
                                                        <div class="relative">
                                                    <input type="email" name='enquiry_email' id='enquiry_email'  class="required email" >
                                                    <div class="fix"></div>
</div>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Subject<span style='color:red;'>*</span> </td>
                                                <td>
                                                <div class="relative">
                                                    <input type="text" name='enquiry_subject' id='enquiry_subject'  class="required" >
                                                    <div class="fix"></div>
                                                 </div>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Message<span style='color:red;'>*</span> </td>
                                                <td>
                                                 <div class="relative">
                                                    <textarea name='enquiry_msg' id='enquiry_msg' rows='6' cols="20" class="required" ></textarea>
                                                    <div class="fix"></div>
                                                    </div>
                                                    </td>
                                            </tr>

                                            
                                            <tr>
                                                <td><span id='email_status_div' style="display:none"><?php echo $this->html->image(CDN.'img/loading.gif');?> &nbsp;Please wait...</span></td>
                                                <td><input type="submit" value='Send mail' id='send_mail' class="btn btn-primary buttondflt back_icon"  ></td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan='2' id='footer_error_enquiry' style='color:green;margin-left:100px;'></td>
                                            </tr>
                                            
                                        </table>
                                        </form>
                                    </div>

                                   
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>   <!--popup--> 
       
        <div class="" id="Mymodel1"></div>  

        <script>
            
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
$(".error_validate").html("");
    }
     function close_form_ft() {

        $('#myModal_ft').addClass("modal fade popupBox");
        $('#myModal_ft').attr('aria-hidden', true);
        $('#myModal_ft').css('display', 'none');
        $('#Mymodel1').removeClass('modal-backdrop fade in');
 $("#footer_error_enquiry").html("");
    }
    
 /*
 function doSubmitFooterEnquiry(event){ 
        event.preventDefault();
           
        var enquiry_name    =   $("#enquiry_name").val();
        var enquiry_email   =   $("#enquiry_email").val();
        var enquiry_subject =   $("#enquiry_subject").val();
        var enquiry_msg =       $("#enquiry_msg").val();
        

         $.ajax({
                type: "POST",
                data: "enquiry_name=" + enquiry_name+"&enquiry_email="+enquiry_email+"&enquiry_subject="+enquiry_subject+"&enquiry_msg="+enquiry_msg,
                url: "<?php echo Staff_Name ?>rewards/dofootenquirysubmit/",
                success: function(result) {

                        $("#enquiry_name").val("");
                        $("#enquiry_email").val("");
                        $("#enquiry_subject").val("");
                        $("#enquiry_msg").val("");
                       $("#footer_error_enquiry").text(result);
                }
        }); 
       

 } */
 
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
