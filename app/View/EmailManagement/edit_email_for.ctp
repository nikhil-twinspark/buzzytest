

    <div class="contArea Clearfix">
         <div class="page-header">
           <h1>
               <i class="menu-icon fa fa-users"></i>Email Management
           </h1>
        </div>
    </div>

      <?php 
        //echo $this->element('messagehelper'); 
        echo $this->Session->flash('good');
        echo $this->Session->flash('bad');
     ?>
	<?php echo $this->Form->create("EmailList",array('enctype'=>'multipart/form-data','class'=>'form-horizontal'));
		echo $this->Form->input("id", array("type" => "hidden"));
                //echo "<pre>";print_r($this->request->data);
		?>
		


<div class="form-group Clearfix">
            <label class="col-sm-3 col-xs-12 control-label no-padding-right"> <span class="star">*</span>Subject</label>
            <div class="col-sm-9 col-xs-12">
            <?php echo $this->Form->input("name",array('label'=>false,'div'=>false,'class'=>'col-xs-12 col-sm-5','value'=>$this->request->data['EmailList']['name'],'placeholder'=>'Subject','required','maxlength'=>'100')); ?>
       
       </div>
       </div>


      
      
      
    <div class="col-md-offset-3 col-md-9">
                    <input type="submit" value="Save Email Template" class="btn btn-sm btn-primary" >
             </div>
     </form>
    

