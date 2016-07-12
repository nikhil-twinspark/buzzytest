<?php

    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');
    echo $this->Html->script(CDN.'js/assets/js/date-time/moment.min.js');
    echo $this->Html->script(CDN.'js/assets/js/date-time/daterangepicker.min.js');
    echo $this->Html->css(CDN.'css/assets/css/daterangepicker.css');
?>
<style>
.heading-Box{
	color: #438eb9;
    font-weight: bold;
    margin:0;
    padding:0;
    text-align:center;
    font-size:20px;
}
.warning-msg{
	padding: 7px;
    margin-top: 12px;
}
</style>
<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-gift"></i>
           Staff Usage Report
        </h1>
    </div>
	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
<div class="row">
    <div class="col-xs-12 col-sm-12" style="padding-top:15px;">

        <div class="form-horizontal">
        <div class="form-group col-sm-12">
        <label for="id-date-range-picker-1" class="col-sm-12 col-sm-2 control-label no-padding-right">Select</label>
    		<div class="col-xs-12 col-sm-4">
    			<!-- #section:plugins/date-time.daterangepicker -->
    			<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 60%">
    				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
    				<span id="date_range_picker"></span>
                        </div>
    			<!-- /section:plugins/date-time.daterangepicker -->
    		</div>
    		<div class="col-xs-12 col-md-3" style="">
                <button class="btn btn-sm btn-primary" id="asgnlink" name="asgnlink">Get Details</button>
            </div> 
		</div><!-- select box -->
        </div>	
    		
        </div>
            
<div class="col-sm-12" style="text-align: center; margin-bottom: 30px;">
	<a href="#" class="overall btn btn-primary" style="display:none;">Export Overall Report to CSV</a>  <a href="#" class="weekly btn btn-primary" style="display:none;">Export Weekly Report to CSV</a>
</div>	
                <div class="col-sm-7" style="margin:auto; clear:both;float:none;display:none;" id="report_div">
                 <h3 class="heading-Box">Overall</h3>
                            <div class="widget-box transparent">


                <div class="widget-body" style="display: block;">
                <div class="widget-main">
                        <table class="table table-striped table-bordered table-hover" id="report_table">
                                <tbody>

                                </tbody>
                        </table>
                </div><!-- /.widget-main -->
                </div><!-- /.widget-body -->
                            </div><!-- /.widget-box -->
                </div>
    <div class="col-sm-7 heading-Box" style="clear:both;display:none;margin-left: 21%;font-size: 18px;margin-bottom: 20px;" id="report_total_count">
    </div>
    </div>
   
    <div class="col-sm-12" style="margin:auto; clear:both;float:none;display:none;padding:0;" id="report_weekly_div">
    <h3 class="heading-Box">Weekly</h3>
    <div class="alert alert-info warning-msg">
											<strong>Alert!</strong>

											This report supports a maximum of 12 weeks of data.
											<br>
										</div>
										
			<table class="table table-striped table-bordered table-hover" id="report_weekly_table" style="margin-top:12px;">


				<tbody>
				</tbody>
			</table>
    </div>
    
</div>
</div>
<script type="text/javascript" src="/js/staffusagereport.js"></script>

