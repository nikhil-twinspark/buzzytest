<?php

$sessionstaff = $this->Session->read('staff'); 
    
    echo $this->Html->css(CDN.'css/jquery.dataTables.css');
    echo $this->Html->script(CDN.'js/jquery.dataTables.js');
    //echo $this->Html->script(CDN.'js/jquery.dataTables.columnFilter.js');

    echo $this->Html->css('https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css');
    echo $this->Html->css('https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css');
    echo $this->Html->script('https://code.jquery.com/jquery-1.12.0.min.js');
    echo $this->Html->script('https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js');
    echo $this->Html->script('https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js');
    echo $this->Html->script('https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js');
    echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js');
    echo $this->Html->script('https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js');
    echo $this->Html->script('https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js');
    echo $this->Html->script('https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js');
    echo $this->Html->script('https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js');
    echo $this->Html->script(CDN.'js/assets/js/date-time/moment.min.js');
    echo $this->Html->script(CDN.'js/assets/js/date-time/daterangepicker.min.js');
    echo $this->Html->css(CDN.'css/assets/css/daterangepicker.css');
?>

<div class="contArea Clearfix">
    <div class="page-header">
        <h1>
            <i class="menu-icon fa fa-gift"></i>
            Points History
        </h1>
    </div>
	<?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
    <div class="row">
        <div class="col-xs-12">

            <form action="<?=Staff_Name?>PointsHistory/index" method="POST" name="myinfo_form1" class="form-horizontal" id="myinfo_form1">
                <div class="form-group">
                    <label for="id-date-range-picker-1" class="col-sm-3 control-label no-padding-right">Select Date</label>
                    <div class="col-xs-5 col-sm-4">
                        <!-- #section:plugins/date-time.daterangepicker -->
                        <div class="input-group" style="padding-left: 5px;">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar bigger-110"></i>
                            </span>

                            <input type="text" id="date_range_picker" name="date_range_picker" class="form-control active" readonly="" value="<?php echo $date_range; ?>">
                        </div>

                        <!-- /section:plugins/date-time.daterangepicker -->
                    </div>
                    <div id="date_range_picker_validate" class="col-sm-2 control-label no-padding-right" style="text-align: left;"> </div>
                </div>

                <div class="form-group">	
                    <label class="col-sm-3 control-label no-padding-right">Point Report Type</label>
                    <div class="col-xs-5 col-sm-4" style="padding-top: 4px;">
                        <label>
                            <?php if(isset($type) && $type!=''){
                                $type=$type;
                            }else{
                                $type ='Y';
                            } ?>
                            <input type="radio" class="ace" name="transaction_type" value='Y' <?php if($type=='Y'){ echo 'checked'; } ?>>
                            <span class="lbl"> Points Redeemed</span>
                        </label>
                        <label>
                            <input type="radio" class="ace" name="transaction_type" value='N' <?php if($type=='N'){ echo 'checked'; } ?>>
                            <span class="lbl"> Points Awarded</span>
                        </label>
                    </div>		
                </div>			
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-sm btn-primary" id="asgnlink" name="asgnlink">Get Details</button>
                </div> 
            </form>


        </div>
    </div>
    
    <?php if(!empty($transaction_array)){ ?>
    <div class="form-group"></div>
    <div class="col-md-12">
            <?php if($type=='Y'){ ?>
            <div class="infobox infobox-blue col-sm-12">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-magic"></i>
                </div>

                <div class="infobox-data">
                    <span><?php echo $totalamount.' ( '.($totalamount/50).' $)';?></span>
                    <div class="infobox-content">Total Points Redeemed</div>
                </div>
            </div>

            <div class="infobox infobox-pink col-sm-12">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-shopping-cart"></i>
                </div>

                <div class="infobox-data">
                    <span><?php echo count($transaction_array);?></span>
                    <div class="infobox-content" >Total Prizes Redeemed</div>
                </div>

            </div>
            <?php }else{ ?>
            <div class="infobox infobox-red col-sm-12">
                <div class="infobox-icon">
                    <i class="ace-icon fa fa-exchange"></i>
                </div>

                <div class="infobox-data">
                    <span><?php echo $totalamount.' ( '.($totalamount/50).' $)';?></span>
                    <div class="infobox-content">Total Points Awarded</div>
                </div>
            </div>
            <?php } ?>
    <form action="<?=Staff_Name?>PointsHistory/exportUserpoints" method="POST" name="myinfo_form2" class="form-horizontal" id="myinfo_form2">
        <input type="hidden" name="date_range" value='<?php echo $search_result['date_range_picker']; ?>' >
        <input type="hidden" name="type" value='<?php echo $type; ?>' >
        
        <div class="table-responsive" style="clear: both; padding-top: 10px;">
                <div class="dt-buttons" style="left: 164px; position: absolute; z-index: 9;">            
                    <input type="submit" name="submit" value="Excel">            
                </div>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="example" class="display"> 
                    <thead>
                        <tr> 
                            <td width="17%" class="client sorting" aria-label="Domain: activate to sort column ascending" >Patient Name</td>
                            <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Card Number</td>
                            <td width="26%" class="campaign sorting" aria-label="Domain: activate to sort column ascending"><?php if($type=='Y'){ echo "Description"; } else{ echo "Promotion"; } ?></td>

                            <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Points</td>                      <td width="12%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Amount ($)</td>
                            <td width="15%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Date</td>
                            <td width="18%" class="campaign sorting" aria-label="Domain: activate to sort column ascending">Given By</td>
                        </tr>
                    </thead>
                    <tbody>

        <?php 
				
					foreach ($transaction_array as $trans)
					{
					
					?>
                        <tr> 

                            <td width="17%"><?php echo $trans['name'];?></td>
                            <td width="15%" ><?php echo $trans['card_number'];?></td>
                            <td width="26%" ><?php echo $trans['description'];?></td>
                            <td width="12%" ><?php echo $trans['points'];?></td>
                            <td width="12%" ><?php echo $trans['points_dol'];?></td>
                            <td width="15%" ><?php echo $trans['date'];?></td>
                            <td width="18%" ><?php echo $trans['givenby'];?></td>						
                        </tr>
      <?php 	
					}//Endforeach
				 ?>
                    </tbody>
                </table>
        </div>
    </form>
    <?php } ?>
</div>
<script>
    $(document).ready(function() {
        $('#example').dataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ],
            "iDisplayLength": 20,
            "aaSorting": [[5, "desc"]],
            "sPaginationType": "full_numbers",
        });
    });
    $(function() {
        $('input[name="date_range_picker"]').daterangepicker({
            format: 'YYYY-MM-DD',
            maxDate: new Date(),
        });
    });
    $('#myinfo_form1').validate({
        focusInvalid: false,
        rules: {
            date_range_picker: {
                required: true,
            }
        },
        errorPlacement: function(error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
        // Specify the validation error messages
        messages: {
            date_range_picker: {
                required: "Please select Date",
            }
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
            $(e).remove();
        },
        showErrors: function(errorMap, errorList) {
            console.log(errorList);
            if (errorList.length) {
                var s = errorList.shift();
                var n = [];
                n.push(s);
                this.errorList = n;
            }
            this.defaultShowErrors();
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


</script>
