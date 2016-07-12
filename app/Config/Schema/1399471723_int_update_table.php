<?php
class IntUpdateTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'documents' => array(
					'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null)
				),
				'notifications' => array(
						'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null)
					),
				'promotions' => array(
						'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
						'staff_id' => array('type' => 'integer', 'null' => true, 'default' => null),
					),
				'refers' => array(
						'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
						
					),
				
				'staffs' => array(
						'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
						
					),
			),
			'rename_field' => array(
				
				'notifications' => array(
					'customer_card' => 'user_id',
				),
				'refers' => array(
					'card_number' => 'user_id',
					'massage'=>'message'
				),
				
				'themes' => array(
					'client_credential_id' => 'clinic_id'
				),
				'wish_lists' => array(
					'customer_card' => 'user_id',
					'client_id'=>'clinic_id'
				)
			),
			'alter_field' => array(
				//'notifications' => array(
				//	'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
				//),
				'wish_lists' => array(
					'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
				)
				
			)

		),
		'down' => array(
			'drop_field' => array(
				'notifications' => array(
					'client_id'
				),
				'documents' => array(
					'campaign_id'
				),
				'promotions' => array(
					'campaign_id',
					'promotion_id',
					'promo_custom_id'
				),
				'refers' => array(
					'client_id'
				),
				'staffs' => array(
					'staff_allowed_campaigns',
					'client_id',
					'staff_pin',
					'staff_language',
					'staff_language_custom',
					'staff_timezone'
				)
			),
			'drop_table' => array(
				'reward_lists',
				'redeem_rewards',
				'balance_histories',
				'patients',
				'campaigns',
				'client_credentials'
			)
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		
		if ($direction === 'up') {
			$db = ConnectionManager::getDataSource($this->connection); 
			$db->cacheSources = false;
			//insert sticky street data for client_credentials table to clinic table
			$client_credentials = ClassRegistry::init('client_credentials');
			$allclient=$client_credentials->find('all');
				foreach($allclient as $ac){ 
					$clinics = $this->generateModel('clinics');
					$rd = array(
                        "clinics" => array(
                            "api_user" => $ac['client_credentials']['api_user'],
                            "api_key"=>$ac['client_credentials']['api_key'],
                            'accountId'=>$ac['client_credentials']['accountId'],
                            "api_url"=>$ac['client_credentials']['api_url'],
                            'site_window_name'=>$ac['client_credentials']['site_window_name'],
                            'staff_url'=>$ac['client_credentials']['staff_url'],
                            'patient_url'=>$ac['client_credentials']['patient_url'],
                            'client_id'=>$ac['client_credentials']['client_id'],
                            'site_name'=>$ac['client_credentials']['site_name']
                        )
                    
					);
                if ($clinics->save($rd)){
					$this->callback->out('Clinics table has been initialized');
					}
			}
			/*--------------------------*/
			//insert default value in profilefield table
					
					$data[0]['profile_fields']['profile_field'] = 'first_name';
					$data[0]['profile_fields']['type'] = 'varchar';
					$data[0]['profile_fields']['length'] = '100';
					$data[0]['profile_fields']['staff_id'] = '0';
					$data[1]['profile_fields']['profile_field'] = 'last_name';
					$data[1]['profile_fields']['type'] = 'varchar';
					$data[1]['profile_fields']['length'] = '50';
					$data[1]['profile_fields']['staff_id'] = '0';
					$data[2]['profile_fields']['profile_field'] = 'customer_password';
					$data[2]['profile_fields']['type'] = 'varchar';
					$data[2]['profile_fields']['length'] = '50';
					$data[2]['profile_fields']['staff_id'] = '0';
					$data[3]['profile_fields']['profile_field'] = 'phone';
					$data[3]['profile_fields']['type'] = 'bigint';
					$data[3]['profile_fields']['length'] = '20';
					$data[3]['profile_fields']['staff_id'] = '0';
					$data[4]['profile_fields']['profile_field'] = 'email';
					$data[4]['profile_fields']['type'] = 'varchar';
					$data[4]['profile_fields']['length'] = '100';
					$data[4]['profile_fields']['staff_id'] = '0';
					$data[5]['profile_fields']['profile_field'] = 'street1';
					$data[5]['profile_fields']['type'] = 'varchar';
					$data[5]['profile_fields']['length'] = '255';
					$data[5]['profile_fields']['staff_id'] = '0';
					$data[6]['profile_fields']['profile_field'] = 'street2';
					$data[6]['profile_fields']['type'] = 'varchar';
					$data[6]['profile_fields']['length'] = '255';
					$data[6]['profile_fields']['staff_id'] = '0';
					$data[7]['profile_fields']['profile_field'] = 'city';
					$data[7]['profile_fields']['type'] = 'varchar';
					$data[7]['profile_fields']['length'] = '50';
					$data[7]['profile_fields']['staff_id'] = '0';
					$data[8]['profile_fields']['profile_field'] = 'state';
					$data[8]['profile_fields']['type'] = 'varchar';
					$data[8]['profile_fields']['length'] = '50';
					$data[8]['profile_fields']['staff_id'] = '0';
					$data[9]['profile_fields']['profile_field'] = 'postal_code';
					$data[9]['profile_fields']['type'] = 'integer';
					$data[9]['profile_fields']['length'] = '11';
					$data[9]['profile_fields']['staff_id'] = '0';
					$data[10]['profile_fields']['profile_field'] = 'gender';
					$data[10]['profile_fields']['type'] = 'varchar';
					$data[10]['profile_fields']['length'] = '10';
					$data[10]['profile_fields']['staff_id'] = '0';
					$data[11]['profile_fields']['profile_field'] = 'main_interest';
					$data[11]['profile_fields']['type'] = 'varchar';
					$data[11]['profile_fields']['length'] = '255';
					$data[11]['profile_fields']['staff_id'] = '0';
					$data[12]['profile_fields']['profile_field'] = 'custom_date';
					$data[12]['profile_fields']['type'] = 'date';
					$data[12]['profile_fields']['length'] = '';
					$data[12]['profile_fields']['staff_id'] = '0';
					$data[13]['profile_fields']['profile_field'] = 'parents_email';
					$data[13]['profile_fields']['type'] = 'varchar';
					$data[13]['profile_fields']['length'] = '50';
					$data[13]['profile_fields']['staff_id'] = '0';
					
               
					foreach($data as $profile){ 

					$profile_fields = $this->generateModel('profile_fields');
					
					if ($profile_fields->save($profile)){
						$this->callback->out('profile_fields table has been initialized');
					}
			}
			
			
			
			
			
			
			//insert clinic id  to documents table
			$documents = ClassRegistry::init('documents');
			$alldocuments=$documents->find('all');
				foreach($alldocuments as $doc){ 
					
					$campa = ClassRegistry::init('campaigns');
					$campaigns=$campa->find('first', array(
						'joins' => array(
							array(
								'table' => 'clinics',
								'alias' => 'Clinics',
								'type' => 'INNER',
								'conditions' => array(
								'Clinics.api_user = campaigns.client_id'
								)
						)
						),
							'conditions' => array(
								'campaigns.campaign_id' => $doc['documents']['campaign_id']
						),
							'fields' => array('Clinics.*')
						));	
								
					
					$rd_doc = array(
                        "documents" => array(
                            "id" => $doc['documents']['id'],
                            "document"=>$doc['documents']['document'],
                            'title'=>$doc['documents']['title'],
                            "clinic_id"=>$campaigns['Clinics']['id']
                        )
                    
					);
                if ($documents->save($rd_doc)){
					$this->callback->out('documents table has been initialized');
					}
			}
			/*--------------------------*/
			
			
			//insert card number and update transaction table
			$clinic = ClassRegistry::init('clinics');
			$clinics=$clinic->find('all');	//condition need to remove after test ,array('conditions'=>array('clinics.id'=>1))
			
				foreach($clinics as $cln){
					$cg = ClassRegistry::init('campaigns');
					$campg=$cg->find('first', array(
							'conditions' => array(
								'campaigns.client_id' => $cln['clinics']['api_user']
						)));
					
					$url='https://api.clienttoolbox.com/?user_id='.$cln['clinics']['api_user'].'&user_password='.$cln['clinics']['api_key'].'&account_id='.$cln['clinics']['accountId'].'&type=reports&report=customers_all';
					$result=file_get_contents($url);
					try {
						$allcust 	= new SimpleXMLElement(str_replace('&','&amp;',$result));
						}
					catch(Exception $e) {
						CakeLog::write('error', 'Data-Rohitortho-'.$url.'-resul-Rohitortho'.print_r($allcust, true) );
		
						}
					if (isset($allcust) && (string)$allcust->attributes()->status == 'success') {
						foreach($allcust->customer as $cus){
							if($cus->card_number!=''){
							$card_number = $this->generateModel('card_numbers');
								$rd_card_number = array(
										"card_numbers" => array(
											"clinic_id"=>$cln['clinics']['id'],
											'card_number'=>$cus->card_number
											)
                    
								);
						
						 if ($card_number->save($rd_card_number)){
							//if($cus->card_number=='88889'){//need to remove after test
							$url_history='https://api.clienttoolbox.com/?user_id='.$cln['clinics']['api_user'].'&user_password='.$cln['clinics']['api_key'].'&account_id='.$cln['clinics']['accountId'].'&type=balance&campaign_id='.$campg['campaigns']['campaign_id'].'&code='.$cus->customer_code;
							$result_history=file_get_contents($url_history);
								try {
									$allhistory 	= new SimpleXMLElement(str_replace('&','&amp;',$result_history));
								}
								catch(Exception $e) {
									CakeLog::write('error', 'Data-Rohitortho-'.$url_history.'-resul-Rohitortho'.print_r($allhistory, true) );
		
								}
							
								if (isset($allhistory) && (string)$allhistory->attributes()->status == 'success') {
									if(isset($allhistory->campaign->customer->transactions)){
									foreach($allhistory->campaign->customer->transactions->transaction as $trans){
									
									
									
											$transaction = $this->generateModel('transactions');
											$rd_transaction = array(
												"transactions" => array(
													'card_number'=>$allhistory->campaign->customer->card_number,
													'activity_type'=>$trans->redeemed,
													'authorization'=>$trans->authorization,
													'amount'=>$trans->amount,
													'clinic_id'=>$cln['clinics']['id'],
													'date'=>$trans->date,
													'status'=>'New'
											)
                    
								);
						
							if ($transaction->save($rd_transaction)){
								$this->callback->out('transactions table has been initialized');
							}
									
									}
								}
							}
						//}//need to remove after test
						}	
					}	
				}
			}
		}			
				
               /*--------------------------*/
			
			
			//insert sticky street data for patients table to user table
				$patients = ClassRegistry::init('patients');
				$allPatients=$patients->find('all');
				foreach($allPatients as $ap){ 
					$cl_id = ClassRegistry::init('clinics');
					$clinic_id=$cl_id->find('first',array('conditions'=>array('api_user'=>$ap['patients']['client_id'])));
					$users = $this->generateModel('users');
					$records = array(
                        "users" => array(
                            "enrollment_stamp"=>$ap['patients']['enrollment_stamp'],
                            'facebook_id'=>$ap['patients']['facebook_id'],
                            'is_facebook'=>$ap['patients']['is_facebook'],
                            'facebook_like_status'=>$ap['patients']['facebook_like_status'],
                            'status'=>$ap['patients']['status'],
                            'is_verified'=>$ap['patients']['is_varified'],
                            'blocked'=>$ap['patients']['blocked']
                        )
                    
					);
					
					
					if ($users->save($records)){
						$this->callback->out('users table has been initialized');
					}
					
					$trans = ClassRegistry::init('transactions');
					$alltrans=$trans->find('first',array('conditions'=>array('transactions.card_number'=>$ap['patients']['card_number'],'transactions.clinic_id'=>$clinic_id['clinics']['id']),'fields'=>array('SUM(transactions.amount) AS points')'group' => array(
            'transactions.card_number'
        )));
					
					
					$user_id=$users->getLastInsertId();
						$clinic_users = $this->generateModel('clinic_users');
						$clinicusers_vl = array(
                        "clinic_users" => array(
                            "clinic_id" => $clinic_id['clinics']['id'],
                            "user_id"=>$user_id,
                            "card_number"=>$ap['patients']['card_number'],
                            "points"=>$alltrans['transactions']['points']
                        )
                    
					);
				
					if ($clinic_users->save($clinicusers_vl)){
					    $this->callback->out('clinic_users table has been initialized');
					}
					
					foreach($ap['patients'] as $val=>$index){
					
					$prfield = ClassRegistry::init('profile_fields');
					$profile_id=$prfield->find('first',array('conditions'=>array('profile_fields.profile_field'=>$val)));
					if(!empty($profile_id)){
						$profile_field_users = $this->generateModel('profile_field_users');
						$records_pf_vl = array(
                        "profile_field_users" => array(
                            "user_id" => $user_id,
                            "profilefield_id"=>$profile_id['profile_fields']['id'],
                            "value"=>$index
                        )
                    
					);
				
					if ($profile_field_users->save($records_pf_vl)){
					    $this->callback->out('profile_field_users table has been initialized');
					}
						
					}
					}
					
					
					
					
					
			}
			
			/*--------------------------*/
			
			
			
			
			
			//insert clinic id and user id  to notifications table
			$notification = ClassRegistry::init('notifications');
			$allnotifications=$notification->find('all');
				foreach($allnotifications as $not){ 
					
					$clinic = ClassRegistry::init('clinics');
					$clinics=$clinic->find('first', array(
							'conditions' => array(
								'clinics.api_user' => $not['notifications']['client_id']
						)));	
					$user = ClassRegistry::init('users');
					$users=$user->find('first', array(
							'conditions' => array(
								'users.card_number' => $not['notifications']['user_id']
						)));				
					if(isset($users['users']['id']) && $users['users']['id']!=''){
					$rd_not = array(
                        "notifications" => array(
                            "id" => $not['notifications']['id'],
                            "user_id"=>$users['users']['id'],
                            
                            'order_status'=>$not['notifications']['order_status'],
                            'earn_points'=>$not['notifications']['earn_points'],
                            'reward_challenges'=>$not['notifications']['reward_challenges'],
                            'clinic_id'=>$clinics['clinics']['id']
                        )
                    
					);
				
                if ($notification->save($rd_not)){
					$this->callback->out('notifications table has been initialized');
					}
				}
			}
			/*--------------------------*/
			
			
			
			

			//update promotion table
			$promotion = ClassRegistry::init('promotions');
			$allpromotions=$promotion->find('all');
				foreach($allpromotions as $promo){ 
					
					$campa = ClassRegistry::init('campaigns');
					$campaigns=$campa->find('first', array(
						'joins' => array(
							array(
								'table' => 'clinics',
								'alias' => 'Clinics',
								'type' => 'INNER',
								'conditions' => array(
								'Clinics.api_user = campaigns.client_id'
								)
						)
						),
							'conditions' => array(
								'campaigns.campaign_id' => $promo['promotions']['campaign_id']
						),
							'fields' => array('Clinics.*')
						));	
								
					
					$rd_pro = array(
                        "promotions" => array(
                            "id" => $promo['promotions']['id'],
                            "operand"=>$promo['promotions']['operand'],
                            'value'=>$promo['promotions']['value'],
                            'description'=>$promo['promotions']['description'],
                            "clinic_id"=>$campaigns['Clinics']['id']
                        )
                    
					);

                if ($promotion->save($rd_pro)){
					$this->callback->out('promotions table has been initialized');
					}
			}
			/*--------------------------*/
			
			
			
			
			
			//update refer table
			$refer = ClassRegistry::init('refers');
			$allrefers=$refer->find('all');
				foreach($allrefers as $ref){ 
					$clinic = ClassRegistry::init('clinics');
					$clinics=$clinic->find('first', array(
							'conditions' => array(
								'clinics.api_user' => $ref['refers']['client_id']
						)));	
					$user = ClassRegistry::init('users');
					$users=$user->find('first', array(
							'conditions' => array(
								'users.card_number' => $ref['refers']['user_id']
						)));	
						if(isset($users['users']['id']) && $users['users']['id']!=''){			
					$rd_ref = array(
                        "refers" => array(
                            "id" => $ref['refers']['id'],
                            "user_id"=>$users['users']['id'],
                            
                            'first_name'=>$ref['refers']['first_name'],
                            'last_name'=>$ref['refers']['last_name'],
                            'email'=>$ref['refers']['email'],
                            'message'=>$ref['refers']['message'],
                            'status'=>$ref['refers']['status'],
                            'refdate'=>$ref['refers']['refdate'],
                            'clinic_id'=>$clinics['clinics']['id']
                        )
                    
					);
			
                if ($refer->save($rd_ref)){
					$this->callback->out('refer table has been initialized');
					}
				}
			}
			/*--------------------------*/
			
			
			
			
				//update promotion table
			$rewardlist = ClassRegistry::init('reward_lists');
			//this condition need to remove after test ,array('conditions'=>array('reward_lists.campaign_id'=>'4682656697246646'))
			$allrewardlists=$rewardlist->find('all');
				foreach($allrewardlists as $rdlist){ 
					$campa = ClassRegistry::init('campaigns');
					$campaigns=$campa->find('first', array(
						'joins' => array(
							array(
								'table' => 'clinics',
								'alias' => 'Clinics',
								'type' => 'INNER',
								'conditions' => array(
								'Clinics.api_user = campaigns.client_id'
								)
						)
						),
							'conditions' => array(
								'campaigns.campaign_id' => $rdlist['reward_lists']['campaign_id']
						),
							'fields' => array('Clinics.*')
						));	
								
					$rewards = $this->generateModel('rewards');
					$rd_reward = array(
                        "rewards" => array(
                            "clinic_id"=>$campaigns['Clinics']['id'],
                            'points'=>$rdlist['reward_lists']['points'],
                            'description'=>$rdlist['reward_lists']['description'],
                            "category"=>$rdlist['reward_lists']['category'],
                            "imagepath"=>$rdlist['reward_lists']['imagepath'],
                            "created"=>date('Y-m-d H:i:s')
                        )
                    
					);
                if ($rewards->save($rd_reward)){
					$this->callback->out('rewards table has been initialized');
					}
			}
			/*--------------------------*/
			
			
			
			
				//update staff table
			$staff = ClassRegistry::init('staffs');
			$allstaffs=$staff->find('all');
				foreach($allstaffs as $stf){
					$clinic = ClassRegistry::init('clinics');
					$clinics=$clinic->find('first', array(
							'conditions' => array(
								'clinics.api_user' => $stf['staffs']['client_id']
						)));	
								
					$rd_stf = array(
                        "staffs" => array(
                            "id" => $stf['staffs']['id'],
                            'clinic_id'=>$clinics['clinics']['id']
                        )
                    
					);
                if ($staff->save($rd_stf)){
					$this->callback->out('staff table has been initialized');
					}
			}
			/*--------------------------*/
			
			
			
				//update thems table
			$theme = ClassRegistry::init('themes');
			$allthemes=$theme->find('all');
				foreach($allthemes as $thm){
					$cl_crd = ClassRegistry::init('client_credentials');
					$cl_crds=$cl_crd->find('first', array(
							'conditions' => array(
								'client_credentials.id' => $thm['themes']['clinic_id']
						)));
						if(isset($cl_crds['client_credentials']['api_user']) && $cl_crds['client_credentials']['api_user']!=''){
					$clinic = ClassRegistry::init('clinics');
					$clinics=$clinic->find('first', array(
							'conditions' => array(
								'clinics.api_user' => $cl_crds['client_credentials']['api_user']
						)));	
								
					$rd_thm = array(
                        "themes" => array(
                            "id" => $thm['themes']['id'],
                            'clinic_id'=>$clinics['clinics']['id']
                        )
                    
					);
                if ($theme->save($rd_thm)){
					$this->callback->out('theme table has been initialized');
					}
				}
			}
			/*--------------------------*/
			
	
		}
		
		return true;
	}
	
}
