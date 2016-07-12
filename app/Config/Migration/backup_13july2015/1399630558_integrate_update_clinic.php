<?php

class IntegrateUpdateClinic extends CakeMigration {

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
                'clinics' => array(
                    'display_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'buzzydoc_logo_url' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'about' => array('type' => 'text', 'null' => false, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'is_lite' => array('type' => 'boolean', 'null' => true, 'default' => null),
                    'landing' => array('type' => 'boolean', 'null' => true, 'default' => null),
                    'minimum_deposit' => array('type' => 'float', 'null' => true, 'default' => null),
                    'down_date' => array('type' => 'date', 'null' => false, 'default' => null),
                ),
                'industry_promotions' => array(
                    'display_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'promotions' => array(
                    'display_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
                    'is_lite' => array('type' => 'integer', 'null' => true, 'default' => null)
                ),
                'refers' => array(
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'is_recom' => array('type' => 'boolean', 'null' => false, 'default' => null)
                ),
                'staffs' => array(
                    'dob' => array('type' => 'date', 'null' => false, 'default' => null)
                )
                ,
                'transactions' => array(
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'order_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'unreg_transactions' => array(
                    'doctor_id' => array('type' => 'integer', 'null' => true, 'default' => null)
                ),
                'users' => array(
                    'is_buzzydoc' =>array('type' => 'boolean', 'null' => false, 'default' => null)
                )
                
                
            ),
           
            'alter_field' => array(
                'admin_settings' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                ),
                'card_logs' => array(
                    'range_to' => array('type' => 'biginteger', 'null' => false, 'default' => null),
                ),
                'industry_types' => array(
                    'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'lead_levels' => array(
                    'leadname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'profile_fields' => array(
                    'profile_field' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'rewards' => array(
                    'points' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1')
                ),
                'transactions' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null)
                ),
                'transaction_delete_logs' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null)
                ),
                'unreg_transactions' => array(
                    'clinic_id' => array('type' => 'integer', 'null' => true, 'default' => null)
                ),
                'users' => array(
                    'points' => array('type' => 'integer', 'null' => true, 'default' => null)
                )
            )
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
        if ($direction === 'up') {
            return true;
        }
    }

}
