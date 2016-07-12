<style>

    @media (min-width: 640px) {     
        .col-sm-6{
            width: 50%;
        }
    }

    @media (max-width: 640px) {     
        .col-sm-6{
            width: 102%;
        }
    }
</style> 

<?php 
 echo $this->Html->script(CDN.'js/canvasjs.min.js');
$sessionstaff = $this->Session->read('staff');
echo $this->Html->script(CDN.'js/assets/js/jquery-ui.custom.min.js');
                
                echo $this->Html->script(CDN.'js/assets/js/jquery.easypiechart.min.js');
                echo $this->Html->script(CDN.'js/assets/js/flot/jquery.flot.min.js');
                echo $this->Html->script(CDN.'js/assets/js/flot/jquery.flot.pie.min.js');
                echo $this->Html->script(CDN.'js/assets/js/flot/jquery.flot.resize.min.js');
                ?>
<script type="text/javascript">
    var search_val1 = $.cookie('searchCust');
    $('#find_customer_textbox').val(search_val1);
    if ($('#ownclinic').is(":checked") == true) {
        ownclinic1 = 1;
    } else {
        ownclinic1 = 0;
    }
    if(search_val1!=''){
    searchPatients(search_val1, ownclinic1, isBuzzyDoc, clinicId);
    }
        </script>
<?php
if($dashboard==0){ ?>
<div class="page-header">
    <h1>
        <i class="ace-icon fa fa-search nav-search-icon"></i>
        Search Patients
    </h1>
</div>
<div class="contArea">
    <div class="col-md-12 grid100 pull-left patientCardNo">
        <div id="login">
			<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
            <div class="widget-box">
                <div class="widget-header headBox">
                    <h1 class="widget-title">
                        Enter Patient Information
                    </h1>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <form  class="loginBox patientCardBox " id="searchCustomer" action="/PatientManagement/recordpoint" method="post">
                            <!-- <legend>Form</legend> -->
                            <fieldset>
<?php if(isset($sessionstaff['ownclinic']) && $sessionstaff['ownclinic']==0){ $chk="0"; }else{ $chk= "1"; } ?>
                                <input type="hidden" class="ace" name="ownclinic" id="ownclinic" value="<?php echo $chk; ?>">
                                <input type="text" placeholder="Scan Card or Find Patient using Card Number, Name or Email..." name="customer_card" id="find_customer_textbox1" required>
                            </fieldset>

                            <div class="form-actions center">
                                <input type="submit" class="btn btn-info" value="Find">
                                <?php if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['staffaccess']['AccessStaff']['self_registration']==1 && $sessionstaff['staffaccess']['AccessStaff']['auto_assign']==1){ ?>
                                <a href="javascript:void(0);" class="btn btn-info" onClick="submitQuickSearchForm(0,<?php echo $FreeCardDetails['card_number'];?>, 1)">Assign New Card</a>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div><!-- formarea-->

    </div>

</div>
</div><!-- container -->
<div class="Clearfix"></div>
<script>
    $(document).ready(function () {
        $("#find_customer_textbox1").focus();

        $('.patientCardBox').on('submit', function () {
            searchPatients($('#find_customer_textbox1').val(), 1, isBuzzyDoc, clinicId);
            return false;
        });
    });
    function setSearch(){
        $.removeCookie('searchCust', {path: '/'});
    }
</script>


<?php }else{ 
    
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>



<div class="page-header">
    <h1>
        <i class="menu-icon fa fa-tachometer"></i>
        Dashboard &nbsp;&nbsp;  <span><?php if($sessionstaff['display_name']!=''){ echo $sessionstaff['display_name']; }else{ echo $sessionstaff['api_user']; }?></span>
    </h1>

</div>
<div class="row">
    <div class="space-6"></div>
    <div class="col-sm-6 infobox-container">
        <?php if(!empty($StaffReport)){ ?>
        <div class="widget-box col-md-12">
            <div class="widget-header widget-header-flat widget-header-small" >
                <h5 class="widget-title col-md-12" style="text-align: center;">
                    Staff Performance Report
                </h5>

                <select id="getstaffreport" name="getstaffreport" class="" onchange="getperformancereport();" style="font-size: 16px;margin-bottom: 5px;padding: 0; width: 150px;">
                    <?php $gl=1;foreach($StaffReport as $reportname){ ?>
                    <option value="<?php echo $reportname['goal_id']; ?>" <?php if($gl==1){ echo "selected"; } ?>><?php echo $reportname['goal_name']; ?></option>
                    <?php $gl++;} ?>
                </select>
            </div>

            <div id="chartContainer" style="height: 300px; width: 100%;">
                <div style="height: 100px;width: 100px; margin-left:35%;">
                    <img class="img-responsive" alt="BuzzyDoc logo" title="BuzzyDoc" src="<?php echo CDN; ?>img/loading52.gif">
                </div>
            </div>

        </div>
        <?php } ?>
        <!-- #section:pages/dashboard.infobox -->
        <div class="widget-box col-md-12">
            <div class="widget-header widget-header-flat widget-header-small" >
                <h5 class="widget-title col-md-12" style="text-align: center;">

                    Basic Report

                </h5>

                <select id="getreport" name="getreport" class="" onchange="getreport();" style="font-size: 16px;margin-bottom: 5px;padding: 0; width: 150px;">
                    <option value="week">Week</option>
                    <option value="overall" selected="selected">Overall</option>
                </select>
            </div>


            <div class="infobox infobox-green col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-crosshairs"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id=""><?php echo $PointIssueToday;?></span>
                    <div class="infobox-content">Points Issued Today</div>
                </div>

            </div>
        <?php if($sessionstaff['is_buzzydoc']==1){ ?>
            <div class="infobox infobox-orange col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-briefcase"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="buzzydocbalance"><?=$CurrentBalance?></span>
                    <div class="infobox-content">BuzzyDoc Balance</div>
                </div>

            </div>
        <?php } ?>
            <div class="infobox infobox-green col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-crosshairs"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="totalrefer"><?=$TotalRefer?></span>
                    <div class="infobox-content">Referrals</div>
                </div>

            </div>

            <div class="infobox infobox-blue col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-magic"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="pointdispensed"><?=$PointDisbursed?></span>
                    <div class="infobox-content">Points Dispensed</div>
                </div>


            </div>

            <div class="infobox infobox-pink col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-shopping-cart"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="orderredeemed"><?=$OrderRedeemed?></span>
                    <div class="infobox-content" >Orders Redeemed</div>
                </div>

            </div>

            <div class="infobox infobox-red col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-exchange"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="pointredeemed"><?=$PointRedeemed?></span>
                    <div class="infobox-content">Points Redeemed</div>
                </div>
            </div>


            <?php if($sessionstaff['is_buzzydoc']==1 && $sessionstaff['staffaccess']['AccessStaff']['product_service']==1){ ?>
            <div class="infobox infobox-wood col-sm-6">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-flask"></i>
                </div>

                <div class="infobox-data">
                    <span class="infobox-data-number" id="creditbalance"><?=$CreditBalance?></span>
                    <div class="infobox-content">Credit Balance</div>
                </div>
            </div>
            <?php } ?>
        </div>
        <!-- /section:pages/dashboard.infobox.dark -->
    </div>
    <div class="vspace-12-sm"></div>
    <div class="col-sm-6">
        <div class="widget-box">
            <div class="widget-header widget-header-flat widget-header-small">
                <h5 class="widget-title">
                    <i class="ace-icon fa fa-signal"></i>
                    Cards Purchased - <?=$Totalcardpurch?>
                </h5>


            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <!-- #section:plugins/charts.flotchart -->
                    <div id="piechart-placeholder"></div>

                    <!-- /section:plugins/charts.flotchart -->
                    <div class="hr hr8 hr-double"></div>

                    <div class="clearfix">
                        <!-- #section:custom/extra.grid -->


                        <div class="grid4 chartDescp clearfix">
                            <div class="grey">Issued Total</div>
                            <h4 class="bigger totalAmt"><?php echo $Totalcardissue;?></h4>
                        </div>

                        <div class="grid4 chartDescp clearfix">
                            <div class="grey">Registered Total</div>
                            <h4 class="bigger totalAmt"><?php echo $Totalcardreg;?></h4>
                        </div>
                        <div class="grid4 chartDescp clearfix">
                            <div class="grey">Unregistered Total</div>
                            <h4 class="bigger totalAmt"><?php echo $Totalcardbalance;?></h4>
                        </div>
                        <div class="grid4 chartDescp clearfix">
                            <div class="grey">Active Total</div>
                            <h4 class="bigger totalAmt"><?php echo $TotalActive;?><a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="ace-icon fa fa-info-circle"></i>
                                </a>
                                <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close admin-nav">
                                    <li class="dropdown-footer">
                                        Patients with activity on their card in the past year.
                                    </li>
                                </ul></h4>
                        </div>
                        <!-- /section:custom/extra.grid -->
                    </div>
                </div><!-- /.widget-main -->
            </div><!-- /.widget-body -->
        </div><!-- /.widget-box -->
    </div><!-- /.col -->
</div><!-- /.row -->

			<?php
                       
                        
                        $getissued=100*$Totalcardissue/$Totalcardpurch;
                        $getregistered=100*$Totalcardreg/$Totalcardpurch;
                        $balance=100-($getissued+$getregistered);
                        ?>					


<!-- PAGE CONTENT ENDS -->
                                          
<script type="text/javascript">

    <?php if(!empty($StaffReport)){ ?>
    getperformancereport();
    <?php } ?>
    function getperformancereport() {
        $('#chartContainer').html('<div style="height: 100px;width: 100px; margin-left:35%;"><img class="img-responsive" alt="BuzzyDoc logo" title="BuzzyDoc" src="<?php echo CDN; ?>img/loading52.gif"></div>');
        var goal_id = $('#getstaffreport').val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Staff_Name.'PatientManagement/getStaffReport' ?>",
            data: {goal_id: goal_id},
            success: function (msg) {
                var tg = parseInt(msg.target);
                var glach = parseInt(msg.goal_achieved);
                var chart = new CanvasJS.Chart("chartContainer",
                        {
                            title: {
                                text: ""
                            },
                            animationEnabled: false,
                            axisY: {
                                title: msg.goal_name
                            },
                            legend: {
                                verticalAlign: "bottom",
                                horizontalAlign: "center"
                            },
                            theme: "theme2",
                            data: [
                                {
                                    type: "column",
                                    showInLegend: false,
                                    dataPoints: [
                                        {y: tg, label: "Target"},
                                        {y: glach, label: "Achieved"},
                                    ]
                                }
                            ]
                        });

                chart.render();
            }
        });
    }

    function getreport() {

        var get = $('#getreport').val();
        var datasrc = "get=" + get;

        $.ajax({
            type: "POST",
            dataType: "json",
            data: datasrc,
            url: "<?=Staff_Name?>PatientManagement/getrecord/",
            success: function (result) {
                $('#totalrefer').text(result.TotalRefer);
                $('#pointdispensed').text(result.PointDisbursed);
                $('#orderredeemed').text(result.OrderRedeemed);
                $('#pointredeemed').text(result.PointRedeemed);
                $('#buzzydocbalance').text(result.CurrentBalance);
                $('#creditbalance').text(result.CreditBalance);
            }
        });


    }

    jQuery(function ($) {
        $.resize.throttleWindow = false;

        var placeholder = $('#piechart-placeholder').css({'width': '90%', 'min-height': '150px'});
        var data = [
            {label: "Issued Total", data: <?=$getissued?>, color: "#2091CF"},
            {label: "Registered Total", data: <?=$getregistered?>, color: "#AF4E96"},
            {label: "Unregistered Total", data: <?=$balance?>, color: "#FF0040"},
        ]
        function drawPieChart(placeholder, data, position) {
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        tilt: 0.8,
                        highlight: {
                            opacity: 0.25
                        },
                        stroke: {
                            color: '#fff',
                            width: 2
                        },
                        startAngle: 2
                    }
                },
                legend: {
                    show: true,
                    position: position || "ne",
                    labelBoxBorderColor: null,
                    margin: [-30, 15]
                }
                ,
                grid: {
                    hoverable: true,
                    clickable: true
                }
            })
        }
        drawPieChart(placeholder, data);
        placeholder.data('chart', data);
        placeholder.data('draw', drawPieChart);

    });
    
</script>

<?php } ?>
