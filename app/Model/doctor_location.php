<?php
class doctor_location extends AppModel {
	var $name = 'doctor_location';

	var $primaryKey = 'n';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Doctor' => array(
			'className' => 'Doctor',
			'foreignKey' => 'doctor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Clinic_location' => array(
			'className' => 'Clinic_location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
