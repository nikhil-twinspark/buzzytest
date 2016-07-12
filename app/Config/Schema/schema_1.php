<?php 
App::uses('ClassRegistry', 'Utility'); 
App::uses('Users', 'Model');
class AppSchema extends CakeSchema {

	public $file = 'schema_1.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
		
				
			
				$patients = ClassRegistry::init('Patients');
				$allPatients=$patients->find('all');
				foreach($allPatients as $ap){ 
					$db = ConnectionManager::getDataSource($this->connection); 
					$db->cacheSources = false;
					$cl_id = ClassRegistry::init('ClientCredentials');
					$clinic_id=$cl_id->find('first',array('conditions'=>array('api_user'=>$ap['Patients']['client_id'])));
					
					$users = ClassRegistry::init('Users');
					$records = array(
                        "Users" => array(
                            "code" => $ap['Patients']['code'],
                            "clinic_id"=>$clinic_id['ClientCredentials']['id'],
                            "card_number"=>$ap['Patients']['card_number'],
                            'facebook_id'=>$ap['Patients']['facebook_id'],
                            'is_facebook'=>$ap['Patients']['is_facebook'],
                            'facebook_like_status'=>$ap['Patients']['facebook_like_status'],
                            'status'=>$ap['Patients']['status'],
                            'is_varified'=>$ap['Patients']['is_varified'],
                            'blocked'=>$ap['Patients']['blocked']
                        )
                    
					);
					$users->create();
					$users->save($records);
			}
	}
	
	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'code' => array('type' => 'biginteger', 'null' => false, 'default' => null),
		'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'card_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'facebook_id' => array('type' => 'biginteger', 'null' => true, 'default' => null),
		'is_facebook' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
		'facebook_like_status' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
		'is_varified' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
		'blocked' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);



}
