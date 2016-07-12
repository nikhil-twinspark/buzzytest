<?php
class lead_level extends AppModel {
	var $name = 'lead_level';

	var $displayField = 'id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'IndustryType' => array(
			'className' => 'IndustryType',
			'foreignKey' => 'industryId',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
