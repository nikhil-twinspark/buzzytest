<?php
$sessionStaff = $this->Session->read('staff');
if($sessionStaff['is_buzzydoc']==1){
	echo $this->element('buzzydocredemptions');
}else{
	echo $this->element('legacyredemptions');
}

?>