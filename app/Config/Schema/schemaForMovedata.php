<?php 
App::uses('ClassRegistry', 'Utility'); 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
		
				$client_credentials = ClassRegistry::init('ClientCredentials');
				$allclient=$client_credentials->find('all');
				foreach($allclient as $ac){ 
					App::uses('Clinics', 'Model');
					$Clinics = ClassRegistry::init('Clinics');
                
					$Clinics->create();
					$rd = array(
                        "Clinics" => array(
                            "api_user" => $ac['ClientCredentials']['api_user'],
                            "api_key"=>$ac['ClientCredentials']['api_key'],
                            'accountId'=>$ac['ClientCredentials']['accountId'],
                            "api_url"=>$ac['ClientCredentials']['api_url'],
                            'site_window_name'=>$ac['ClientCredentials']['site_window_name'],
                            'staff_url'=>$ac['ClientCredentials']['staff_url'],
                            'patient_url'=>$ac['ClientCredentials']['patient_url'],
                            'client_id'=>$ac['ClientCredentials']['client_id'],
                            'site_name'=>$ac['ClientCredentials']['site_name']
                        )
                    
					);
              
                $Clinics->save($rd);
			}
			
			
				$patients = ClassRegistry::init('Patients');
				$allPatients=$patients->find('all');
				foreach($allPatients as $ap){ 
					App::uses('Users', 'Model');
					$users = ClassRegistry::init('Users');
					$users->create();
					$cl_id = ClassRegistry::init('ClientCredentials');
					$clinic_id=$cl_id->find('first',array('conditions'=>array('api_user'=>$ap['Patients']['client_id'])));
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
              
					$users->save($records);
			}
			
	}

	


}
