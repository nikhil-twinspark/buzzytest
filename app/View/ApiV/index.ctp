
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-flask"></i>
            Login To Staff
            <!--
           <small>
              
           <i class="ace-icon fa fa-angle-double-right"></i>
           Draggabble Widget Boxes & Containers
           </small>
            -->
        </h1>
    </div>
     <?php 
    //echo $this->element('messagehelper'); 
    print_r($responce['status']['message']);
    
    ?>


<form accept-charset="utf-8" method="post" id="LoginToStaffIndexForm" enctype="multipart/form-data" class="form-horizontal" action="http://lamparski.localhost.com/ApiV1/">
    <div class="tab-content profile-edit-tab-content">
        <div id="edit-basic" class="tab-pane in active">


            <div class="row">


                <div class="col-xs-12 col-sm-8">


                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1"><span class="star">*</span>Api Token:</label>
                        <div class="col-sm-9">
                            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Api Token" id="api_token" name="api_token" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Staff User Name:</label>
                        <div class="col-sm-9">
                            <input type="text"  maxlength="50" class="col-xs-10 col-sm-5" placeholder="Staff User Name" id="staff_id" name="staff_id" value="">
                        </div>
                    </div>
                </div>
            </div>




        </div>									</div>


    <div class="clearfix form-actions">

        <div class="col-md-offset-3 col-md-9">

            <input type="submit" value="Login To Staff" class="btn btn-info">
        </div></div>
</form>




