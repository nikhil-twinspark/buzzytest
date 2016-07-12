<?php
class user extends AppModel {
	var $name = 'user';


	var $displayField = 'id';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasOne=array(
		'Notification' => array(
			'className' => 'Notification',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

	var $hasMany = array(

		'Refer' => array(
			'className' => 'Refer',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Transaction' => array(
			'className' => 'Transaction',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'WishList' => array(
			'className' => 'WishList',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	var $hasAndBelongsToMany = array(
		'Clinic' => array(
			'className' => 'Clinic',
			'joinTable' => 'clinic_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'clinic_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'ProfileField' => array(
			'className' => 'ProfileField',
			'joinTable' => 'profile_field_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'profilefield_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'User' => array(
			'className' => 'User',
			'joinTable' => 'profile_field_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'user_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

	public function getUsers($conditions=null,$clinicId=null){
	    $users = array();
	    if($clinicId){
	          $users =  $this->find('all', array(
	        'joins' => array(
	            array(
	                'table' => 'clinic_users',
	                'alias' => 'clinic_users',
	                'type' => 'INNER',
	                'conditions' => array(
	                    'clinic_users.user_id = id'
	                )
	            )),"conditions"=> array('OR' => array('clinic_users.card_number LIKE'=>'%' .$conditions. '%','email LIKE '=>'%' .$conditions. '%'
	            ,'parents_email LIKE'=>'%' .$conditions. '%','CONCAT(first_name, " ", last_name) LIKE'=>'%' .$conditions. '%','internal_id LIKE'=>'%' .$conditions. '%')
	               , 'clinic_users.clinic_id' => $clinicId
       ),
	        'recursive' => -1,
	        'fields' => array('clinic_users.card_number,clinic_users.user_id,clinic_users.clinic_id', 'CONCAT(first_name, " ", last_name) AS first_name', 'email', 'custom_date')
	    ));
	    }else{
	        $users =  $this->find('all', array(
	            'joins' => array(
	                array(
	                    'table' => 'clinic_users',
	                    'alias' => 'clinic_users',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'clinic_users.user_id = id'
	                    )
	                )),"conditions"=> array('OR' => array('clinic_users.card_number LIKE'=>'%' .$conditions. '%','email LIKE '=>'%' .$conditions. '%'
	                    ,'parents_email LIKE'=>'%' .$conditions. '%','CONCAT(first_name, " ", last_name) LIKE'=>'%' .$conditions. '%')
	                ),
	            'recursive' => -1,
	            'fields' => array('clinic_users.card_number,clinic_users.user_id,clinic_users.clinic_id', 'CONCAT(first_name, " ", last_name) AS first_name', 'email', 'custom_date')
	        ));
	    }
	    return $users;
	}
}
?>
