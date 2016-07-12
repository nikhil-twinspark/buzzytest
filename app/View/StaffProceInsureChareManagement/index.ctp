 <?php
	echo $this->Html->css(CDN.'css/jquery.dataTables.css');
	echo $this->Html->css(CDN.'css/facebox.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
	echo $this->Html->script(CDN.'js/faceBox/facebox.js');
 ?>

<?php $sessionstaff = $this->Session->read('staff');

?>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-cubes"></i>
            Practice Profile Extras
        </h1>
    </div>
    <div style="color:red;font-size: 12px;">
                            The published items will be shown on the BuzzyDoc website under Top Practices.
                            
                        </div>
    <div class="tabbable">


        <div class="tab-content">

            <a ><span style='cursor:pointer; background: none repeat scroll 0 0 #6FB3E0;color: #FFFFFF;float: right;padding: 10px 10px 10px 12px;margin: 5px;' id="id-btn-request">Request to Add</span></a>
            <div id="home4" class="tab-pane in active">
                <div class="table-responsive">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="example" class="table table-striped table-bordered table-hover"> 
                        <thead>
                            <tr> 
                                <td width="45%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Name</td>
                                <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Type</td>

                                <td width="15%" class="aptn sorting" aria-label="Domain: activate to sort column ascending">Published</td>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                </div>
            </div>




        </div>
    </div>

   <!-- Modal -->
        <div id="request-message" class="hide">
        
                   
                            <div class="row inquerybox1">
                                <div class="col-xs-12 clearfix inquerybox2">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 enquiry_box">
                                      
                                        <form action="" method="POST" name="request_form" id='request_form' >
                                        <table border='0' width='100%'>
                                            <tr>
                                                <td>Type<span style='color:red;'>*</span> </td>
                                                <td>
                                                    <div class="relative">
                                                        <select id="request_type" name="request_type">
                                                            <option value="">Select Type</option>
                                                            <option value="Characteristic">Characteristic</option>
                                                            <option value="Insurance">Insurance</option>
                                                            <option value="Procedure">Procedure</option>
                                                        </select>
                                                   <div class="fix"></div>
                                                   </div>
                                            </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Description<span style='color:red;'>*</span> </td>
                                                <td>
                                                        <div class="relative">
                                                            <input type="text" name='description' id='description' maxlength="100">
                                                    <div class="fix"></div>
</div>
                                                </td>
                                            </tr>
                                          
                                           

                                            
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value='Send Request' id='send_request' class="btn btn-primary buttondflt back_icon"  ></td>
                                            </tr>
                                            <tr>
                                            <td  colspan='2'><span id='request_status_div' style="display:none"><?php echo $this->html->image(CDN.'img/loading.gif');?> &nbsp;Please wait...</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan='2' id='error_request' style='color:green;margin-left:100px;'></td>
                                            </tr>
                                            
                                        </table>
                                        </form>
                                    </div>

                                   
                                </div>

                            </div>
                        
                    
                
            
        </div>   <!--popup--> 
 

</div>

<script>
    
    $("#id-btn-request").on('click', function(e) {
        $("#error_request").html("");
        $(".error_validate").html("");
        $("#request_type").val("");
        $("#description").val("");
        e.preventDefault();

        var dialog = $("#request-message").removeClass('hide').dialog({
            modal: true,
            title: "Request to Add:",
            title_html: true,
        });

    });

    $(document).ready(function() {
        $('a[rel*=facebox]').facebox(); //this is for light box
        //"aoColumns":[{"mData":"Reward.description"},{"mData":"Reward.points"},{"mData":"Reward.updated"}]
        $('#example').dataTable({
            "columnDefs": [{"targets": 2, "orderable": false}],
            "bProcessing": true,
            "bServerSide": true,
            "order": [[0, "asc"]],
            "sPaginationType": "full_numbers",
            "sAjaxSource": "/StaffProceInsureChareManagement/getProfileExtra",
            "oLanguage": {
                "sZeroRecords": "No matching records found"
            }, "fnDrawCallback": function() {
                if ($('#example_paginate span a.paginate_button').size()) {
                    $('#example_paginate')[0].style.display = "block";
                } else {
                    $('#example_paginate')[0].style.display = "none";
                }
            }

        });

          $('#request_form').validate({
              rules: {
			request_type: "required",
			description: "required"
                    },
                    messages: {
			request_type: "Please select type",
			description: "Please enter description"
                    },
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
                      $("#request_status_div").show();
                        var request_type    =   $("#request_type").val();
                        var description   =   $("#description").val();
                    
                        
                       $.ajax({
                            type: "POST",
                            data: "request_type=" + request_type+"&description="+description,
                            url: "<?php echo Staff_Name ?>StaffProceInsureChareManagement/requestnew/",
                            success: function(result) {
                                   $("#request_status_div").hide();

                                    $("#request_type").val("");
                                    $("#description").val("");
                                  
                                   $("#error_request").text(result);
                            }
                    });
                }   
            
         });
    });


    function setPublished(reward_id) {

        $.ajax({
            type: "POST",
            url: "/StaffProceInsureChareManagement/setPublishProfileextra",
            data: "reward_id=" + reward_id,
            success: function(msg) {

                if (parseInt(msg) == 1) {
                    $('#publish_' + reward_id).attr('checked', true);
                } else if (parseInt(msg) == 2) {
                    $('#publish_' + reward_id).attr('checked', false);
                } else if (parseInt(msg) == 3) {
                    $('#publish_' + reward_id).attr('checked', false);
                } else if (parseInt(msg) == 4) {
                    $('#publish_' + reward_id).attr('checked', true);
                }

            }
        });

    }
</script>




















