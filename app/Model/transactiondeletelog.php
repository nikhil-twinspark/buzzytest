<?php
class transactiondeletelog extends AppModel {
	var $name = 'transactiondeletelog';

	var $displayField = 'id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Promotion' => array(
			'className' => 'Promotion',
			'foreignKey' => 'promotion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Reward' => array(
			'className' => 'Reward',
			'foreignKey' => 'reward_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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
