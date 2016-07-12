<?php
class product_service extends AppModel {
	var $name = 'product_service';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		
		'Clinic' => array(
			'className' => 'Clinic',
			'foreignKey' => 'clinic_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


}
?>
