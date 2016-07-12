
<?php $sessionpatient = $this->Session->read('patient'); ?>
<?php //print_r($Question);die; ?>
    <section class="clearfix loginArea" style="display: block">
        <div class="col-md-6 col-sm-6 col-xs-12 userSign clearfix" id="totaldiv">
            <?php if(isset($log) && $log<31){
                $day=31-$log;
                ?>
            You have already completed self check-in.You can do self check-in after <?php echo $day; ?> days.
            <?php }else{ ?>
            <a href="<?php echo $this->Html->url(array(
							    "controller" => "Rewards",
							    "action" => "editprofile",
                'selfcheckin'
                ));?>" title="Document" class="icon-1 info-tooltip">Click Here</a> to complete your profile before self checkin...
            <?php } ?>
        </div>
   

    </section>

<!--loginform-->

