<?php $sessionstaff = $this->Session->read('staff');	?>
    <div class="contArea Clearfix">
     
     <div class="breadcrumb_staff"><a href="<?php echo $this->Html->url(array(
						    "controller" => "promotionManagement",
							"action"=>"index"
						));?>" class="active">Promotion</a> >> <b>Add</b> </div>
     <?php echo $this->element('messagehelper'); ?>
     <div class="adminsuper">
      <?php echo $this->Form->create("Promotion",array('class'=>'admin'));
      echo $this->Form->input('clinic_id', array('type' => 'hidden','value'=>$sessionstaff['clinic_id'],'class'=>'editable'));  ?>
      <div class="groupAdmin">
        <label><span class="star">*</span>Description</label>

<?php echo $this->Form->input("description",array('required','label'=>false,'div'=>false,'placeholder'=>'Description','class'=>'editable')); ?>
 </div>
       <div class="groupAdmin">
        <label><span class="star">*</span>Value</label>

<?php echo $this->Form->input("value",array('required','label'=>false,'div'=>false,'placeholder'=>'Value','pattern'=>'[0-9]{5,1}','class'=>'editable'));?>
 </div>
       
       <div class="groupAdmin">
        <label><span class="star">*</span>Operand</label>
        <?php echo $this->Form->input("operand",array('options'=>array('+'=>'+','x'=>'x'),'label'=>false,'div'=>false)); ?>
       </div>
       
<div class="submit"><input type="submit" value="Save Reward" class="hand-icon"></div>
      </form>
  
     </div>
     
   </div>
   </div><!-- container -->




