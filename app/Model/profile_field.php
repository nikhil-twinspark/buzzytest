<?php
class profile_field extends AppModel {
	var $name = 'profile_field';

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
