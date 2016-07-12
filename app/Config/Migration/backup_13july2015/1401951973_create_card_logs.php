<?php

class CreateCardLogs extends CakeMigration {

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
            $db = ConnectionManager::getDataSource($this->connection);
            $db->cacheSources = false;
            //add tango details

            $tango = ClassRegistry::init('tango_accounts');
            $tango->query("INSERT INTO `tango_accounts` (`id`, `customer`, `identifier`, `email`, `available_balance`,`cc_tokan`,`cvv`,`created_on`) VALUES (1, 'BuzzyDocLive', 'BuzzyDocLive', 'david.levine.19@gmail.com', '1.9','143295030','269','" . date('Y-m-d H:i:s') . "');");

            //add badges
            $badges = ClassRegistry::init('badges');
            $allbadges = $badges->query("INSERT INTO `badges` (`id`, `name`, `value`, `description`, `created_on`) VALUES (1, 'Newbie', 1, 'this is for earn 1 points', '0000-00-00 00:00:00'), (2, 'Level 1', 250, 'this is for earn 250 points', '0000-00-00 00:00:00'),(3, 'Level 2', 500, 'this is for earn 500 points', '0000-00-00 00:00:00'),(4, 'Level 3', 1000, 'this is for earn 1000 points', '0000-00-00 00:00:00'),(5, 'Level 4', 2500, 'this is for earn 2500 points', '0000-00-00 00:00:00'),(6, 'Level 5', 5000, 'this is for earn 5000 points', '0000-00-00 00:00:00'),(7, 'Level 6', 10000, 'this is for earn 10000 points', '0000-00-00 00:00:00'),(8, 'Level 7', 15000, 'this is for earn 15000 points', '0000-00-00 00:00:00'),(11, 'level 8', 20000, 'this is for earn 20000 points', '0000-00-00 00:00:00');");
            //add lite promotions for all clinics
            $inspromnottrans = ClassRegistry::init('transactions');
            $inspromnottrans->query("UPDATE  `transactions` SET  `is_buzzydoc` =  '0'");
            $inspromnotislite = ClassRegistry::init('promotions');
            $inspromnotislite->query("UPDATE  `promotions` SET  `is_lite` =  '0'");
            $insprom = ClassRegistry::init('promotions');
            $insliteprom = $insprom->query('INSERT INTO promotions (id, operand, value, display_name, description, clinic_id, is_lite) VALUES (NULL, "+", "50", "Referral", "For Referral", "0", "1"),(NULL, "+", "50", "Social", "For Social", "0", "1"),(NULL, "+", "50", "Review", "for Review", "0", "1"),(NULL, "+", "50", "Check In", "For Check In", "0", "1");');

            $clinics = ClassRegistry::init('clinics');
            $allclinics = $clinics->query('SELECT  id FROM clinics order by id');

            foreach ($allclinics as $allclinic) {
                $access = array(
                    "access_controls" => array(
                        'access' => 'Documents,Promotions,Users,Rewards,Staffs,Redeems,Leads,Admin Setting,Basic Report,Referral Promotions,Instructions,Clinic Locations,Doctors,staffprocedure,Review,PaymentDetail',
                        'clinic_id' => $allclinic['clinics']['id'],
                        'clinic_type' => 0,
                        'created_on' => date('Y-m-d')
                    )
                );

                $accessinst = $this->generateModel('access_controls');
                if ($accessinst->save($access)) {
                    $this->callback->out('table has been inserted with access control');
                };
                $promotions = ClassRegistry::init('Promotion');
                $allpromotions = $promotions->query('SELECT  * FROM promotions where clinic_id=0 and is_lite=1');

                foreach ($allpromotions as $litepro) {
                    $log_card = array(
                        "promotions" => array(
                            'description' => $litepro['promotions']['description'],
                            'value' => $litepro['promotions']['value'],
                            'operand' => $litepro['promotions']['operand'],
                            'display_name' => $litepro['promotions']['display_name'],
                            'clinic_id' => $allclinic['clinics']['id'],
                            'is_lite' => 1
                        )
                    );

                    $card_logs = $this->generateModel('promotions');
                    if ($card_logs->save($log_card)) {
                        $this->callback->out('table has been inserted with lite promotions');
                    };
                }


                /* -------------------------- */
            }


            //update local points




            $clinicuser = ClassRegistry::init('clinic_users');
            $allclinicuser = $clinicuser->query('SELECT  * FROM clinic_users');

            foreach ($allclinicuser as $allcluser) {


                $trans = ClassRegistry::init('transactions');
                $totalsum = $trans->query("SELECT sum( amount ) AS total FROM `transactions` WHERE user_id =" . $allcluser['clinic_users']['user_id'] . " AND clinic_id =" . $allcluser['clinic_users']['clinic_id'] . " GROUP BY user_id");
                if (empty($totalsum)) {
                    $pnt = 0;
                } else {
                    $pnt = $totalsum[0][0]['total'];
                }
                $clinicuser1 = ClassRegistry::init('clinic_users');
                $updateclinicuser = $clinicuser1->query("UPDATE `clinic_users` SET `local_points` = " . $pnt . "  WHERE `clinic_id` =" . $allcluser['clinic_users']['clinic_id'] . " and user_id='" . $allcluser['clinic_users']['user_id'] . "'");
                $this->callback->out('clinic user updated with local points');

                /* -------------------------- */
            }
            $users = ClassRegistry::init('users');
            $usersupdt = $users->query("UPDATE `users` SET `points` = 0");


           
            return true;
        }
    }

}
