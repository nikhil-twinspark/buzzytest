<?php

class IntCreateTable extends CakeMigration {

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
            'create_table' => array(
                'access_controls' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'access' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'clinic_type' => array('type' => 'boolean', 'null' => true, 'default' => null,'length'=>2),
                    'created_on' => array('type' => 'timestamp', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'appointments' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'reason' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'appointment_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'status' => array('type' => 'boolean', 'null' => false, 'default' => null,'length'=>4),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'user_id' => array('column' => 'user_id')
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'badges' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'value' => array('type' => 'integer', 'null' => false, 'default' => null),
                    
                    'description' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                  
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'name' => array('column' => 'name')
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'characteristic_insurances' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                  
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'name' => array('column' => 'name')
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
               'characteristic_insurance_likes' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'characteristic_insurance_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                  
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'characteristic_insurance_id' => array('column' => 'characteristic_insurance_id')
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'clinic_char_insu_proces' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'char_insue_proce_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
              
                'clinic_locations' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'state' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'pincode' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'phone' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'fax' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'latitude' => array('type' => 'float', 'null' => false, 'default' => null),
                    'longitude' => array('type' => 'float', 'null' => false, 'default' => null),
                    'status' => array('type' => 'boolean', 'null' => false, 'default' => null,'length'=>4),
                    'is_prime' => array('type' => 'boolean', 'null' => false, 'default' => null),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'old_users' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'custom_date' => array('type' => 'date', 'null' => true, 'default' => null),
                    'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'parents_email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'customer_password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'points' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'enrollment_stamp' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'is_facebook' => array('type' => 'boolean', 'null' => true, 'default' => null),
                    'is_buzzydoc' =>array('type' => 'boolean', 'null' => false, 'default' => null),
                    'clinic' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'email' => array('column' => 'email'),
                        'first_name' => array('column' => 'first_name'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                 'doctors' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'degree' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'specialty' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'phone' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 11, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'gender' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'state' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    
                    
                     'address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'pincode' => array('type' => 'integer', 'null' => false, 'default' => null),
                
                     'description' => array('type' => 'text', 'null' => false, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                     'dob' => array('type' => 'date', 'null' => false, 'default' => null),
                     'procedures' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'status' => array('type' => 'boolean', 'null' => false, 'default' => null,'length'=>4),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'doctor_locations' => array(
                    'doctor_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'location_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'days' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'facebook_likes' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'like_status' => array('type' => 'boolean', 'null' => false, 'default' => null,'length'=>4),
                    'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'global_redeems' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'staff_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'card_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
                    'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
                    'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
                    'promotion_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'reward_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'activity_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
                    'authorization' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
                    'amount' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'date' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
                    'is_buzzydoc' => array('type' => 'boolean', 'null' => true, 'default' => null),
                    'order_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'user_id' => array('column' => 'user_id'),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'invoices' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'amount' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 11),
                    'invoice_id' => array('type' => 'biginteger', 'null' => false, 'default' => null),
                    'mode' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10),
                    'current_balance' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 11),
                    'payed_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                 
                    'status' => array('type' => 'boolean', 'null' => false, 'default' => null),
                 
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'payment_details' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'doctor_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'clinic_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'customer_account_id' => array('type' => 'biginteger', 'null' => false, 'default' => null),
                    'customer_account_profile_id' => array('type' => 'biginteger', 'null' => false, 'default' => null),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                 'updated_on' => array('type' => 'timestamp', 'null' => false, 'default' => null),
                 
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'rate_reviews' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'rate' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'review' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'checkin' => array('type' => 'boolean', 'null' => true, 'default' => null),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
               
                 
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'clinic_id' => array('column' => 'clinic_id'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                 'save_likes' => array(
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                     'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'date' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                'tango_accounts' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'customer' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
                    'identifier' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
                    'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100),
                    'available_balance' => array('type' => 'float', 'null' => false, 'default' => null),
                    'cc_tokan' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'cvv' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                 
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'customer' => array('column' => 'customer'),
                    ),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
                 'users_badges' => array(
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'badge_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                
                    'created_on' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
                ),
            ),
        ),
        'down' => array(
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
        return true;
    }

}
