<?php
class levelup_promotion extends AppModel {
	var $name = 'levelup_promotions';

	var $displayField = 'id';
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
