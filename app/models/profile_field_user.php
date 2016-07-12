<?php
class profile_field_user extends AppModel {
	var $name = 'profile_field_user';

	var $primaryKey = 'n';
	var $displayField = 'user_id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Profilefield' => array(
			'className' => 'Profilefield',
			'foreignKey' => 'profilefield_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
