    <div class="contArea Clearfix">
          <div class="page-header">
            <h1>
                <i class="menu-icon fa fa-magic"></i> Redemption
            </h1>
        </div>
        
        <?php 
            //echo $this->element('messagehelper'); 
            echo $this->Session->flash('good');
            echo $this->Session->flash('bad');
        ?>
     
     <div class="adminsuper form_box">
      <?php echo $this->Form->create("Transaction",array('class'=>'form-horizontal'));
      
      echo $this->Form->input("action",array('type' => 'hidden','value'=>'update'));
      echo $this->Form->input("id",array('type' => 'hidden','value'=>$red['Transaction']['id']));
       ?>
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right">Transaction Id</label>
            <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("Transaction Id",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['id'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
            </div>
        </div>
         
       <div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right">Transaction Date</label>
            <div class="col-sm-9 col-xs-12">
                    <?php echo $this->Form->input("Transaction Date",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['date'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
            </div>
        </div>
         
	<div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">First name</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("first name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['first_name'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
		
        </div>
        </div>
         
        <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Last name</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("last name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['last_name'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
        </div>
        </div>
         
        <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Clinic</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("clinic",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Clinics']['api_user'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
        </div>	
        </div>
         
         <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Reward</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("Reward",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['authorization'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
        </div>
        </div>
         
        <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Card number</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("Card number",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['card_number'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
        </div>
        </div>
       
       
        <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Redeemed Amount</label>
        <div class="col-sm-9 col-xs-12">
		<?php echo $this->Form->input("Redeemed Amount",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['Transaction']['amount'],'class'=>'col-xs-12 col-sm-5','style'=>'color: #333 !important')); ?>
        </div>
        </div>
       
        <div class="form-group Clearfix">
        <label class="col-sm-3 col-xs-12 control-label no-padding-right">Status</label>
        <div class="col-sm-9 col-xs-12">
		<select name="data[Transaction][status]" required>
                        <option value="">Select Status</option>
                        <option value="Redeemed" <?php if($red['Transaction']['status']=='Redeemed'){ ?> selected="selected" <?php } ?>>Redeemed</option>
                        <option value="In Office" <?php if($red['Transaction']['status']=='In Office'){ ?> selected="selected" <?php } ?>>In Office</option>
                        <option value="Ordered/Shipped" <?php if($red['Transaction']['status']=='Ordered/Shipped'){ ?> selected="selected" <?php } ?>>Ordered/Shipped</option>
        </select>
        </div>
        </div>
         
         <div class="col-md-offset-3 col-md-9">
          <input type="submit" value="Update" class="btn btn-sm btn-primary">
        </div>
         
        </form>
     </div>
     

    
    
    
    </div>
     
   </div>
   </div><!-- container -->

