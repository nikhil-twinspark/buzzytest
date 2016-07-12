    <div class="contArea Clearfix">
   <div class="page-header">
<h1>
    <i class="menu-icon fa fa-exchange"></i>
Redemptions
</h1>
</div>
     <?php 
    //echo $this->element('messagehelper'); 
    echo $this->Session->flash('good');
    echo $this->Session->flash('bad');
    ?>
     
  
      <?php echo $this->Form->create("Transaction",array('class'=>'form-horizontal'));
      echo $this->Form->input("action",array('type' => 'hidden','value'=>'update'));
      echo $this->Form->input("id",array('type' => 'hidden','value'=>$red['id']));
      echo $this->Form->input("clinic_id",array('type' => 'hidden','value'=>$red['clinic_id']));
       ?>
        
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Transaction Id</label>

<div class="col-sm-9">
<?php echo $this->Form->input("Transaction Id",array('disabled' => 'disabled','class'=>'col-xs-10 col-sm-5','label'=>false,'div'=>false,'value'=>$red['id'],'style'=>'color: #333 !important')); ?>
</div>
 </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Transaction Date</label>

<div class="col-sm-9">
<?php echo $this->Form->input("Transaction Date",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['date'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">First name</label>

<div class="col-sm-9">
<?php echo $this->Form->input("first name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['first_name'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
	<div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Last name</label>

<div class="col-sm-9">
<?php echo $this->Form->input("last name",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['last_name'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Clinic</label>

<div class="col-sm-9">
<?php echo $this->Form->input("clinic",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['api_user'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Reward</label>

<div class="col-sm-9">
<?php echo $this->Form->input("Reward",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['authorization'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
          <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Card number</label>

<div class="col-sm-9">
<?php echo $this->Form->input("Card number",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['card_number'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>

        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Redeemed Amount</label>

<div class="col-sm-9">
<?php echo $this->Form->input("Redeemed Amount",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$red['amount'],'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); ?>
</div>
 </div>
        <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Status</label>

<div class="col-sm-9">
    <select name="data[Transaction][status]" class="col-xs-10 col-sm-5" required>
    <?php if($red['status']!='Active'){
    	?>
    	 <option value="Redeemed" selected="selected">Redeemed</option>
    	<?php 
    }else{
    ?>
     <option value="Active">Active</option>
   	 <option value="Redeemed">Redeemed</option> 
   <?php 
   }
    ?>
      
    </select>
</div>


 </div>
  <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Redeemed By</label>

<div class="col-sm-9">
<?php if($red['staff_id']!='' && $red['staff_id']!=0){
$redeemedBy =  $red['staff_name'];
}else{
$redeemedBy =  'Patient';
} 
echo $this->Form->input("redeemed_by",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$redeemedBy,'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); 
?>

</div>
 </div>
 
  <div class="form-group">
        <label  class="col-sm-3 control-label no-padding-right" for="form-field-1">Notes</label>

<div class="col-sm-9">
<?php 
if($red['redeem_notes_by_staff']!=''){
    $notes =  $red['redeem_notes_by_staff'];
}else{
    $notes =  '';
}
echo $this->Form->textarea("notes",array('disabled' => 'disabled','label'=>false,'div'=>false,'value'=>$notes,'class'=>'col-xs-10 col-sm-5','style'=>'color: #333 !important')); 
?>

</div>
 </div>
 
 
       <div class="clearfix form-actions">
 <div class="col-md-offset-3 col-md-9">
        
               <input type="submit" value="Update" class="btn btn-info">
       

       
</div>
									</div> 
       
        </form>
     
     

    
    
    
    </div>
     
   </div>


