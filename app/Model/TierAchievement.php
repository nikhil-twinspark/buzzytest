<?php
/**
 * This model for get the details for patient who achieve accelerated reward porgram level.
 * This model content these fileds:-
 * id -Prmiary key auto increment.
 * user_id - Accelerated program start for this patient.
 * clinic_id -patient belong to this clinic.
 * cycle - current cycle. 
 * current_level -current level of accelerated program.
 * tier_id - current level id from tier_setting table.
 * start_tier - cycle start date.
 * end_tier -cycle end date.
 */
class TierAchievement extends AppModel {

    var $name = 'TierAchievement';
    var $displayField = 'id';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'User' => array(
            'className' => 'user',
            'foreignKey' => 'user_id',
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
    /**
     * Getting the all Tier Accieved by patient
     * @param type $user_id
     * @param type $clinic_id
     * @return type
     */
    public function getAllTierForPatient($user_id, $clinic_id) {
        $gettier = $this->find('all', array(
            'conditions' => array('TierAchievement.clinic_id' => $clinic_id, 'TierAchievement.user_id' => $user_id),
            'fields'=>array('TierAchievement.*')
        ));
        return $gettier;
    }
    /**
     * Get the current active Tier for patient
     * @param type $user_id
     * @param type $clinic_id
     * @return type
     */
    public function getCurrentTierForPatient($user_id, $clinic_id) {
        $gettier = $this->find('first', array(
            'conditions' => array('TierAchievement.clinic_id' => $clinic_id, 'TierAchievement.user_id' => $user_id),
            'fields'=>array('TierAchievement.*'),
            'order' => array('TierAchievement.id desc')
        ));
        return $gettier;
    }
    /**
     * Creating new Achievement level for Petient.
     * @param type $user_id
     * @param type $clinic_id
     * @param type $cycle
     * @param type $level
     * @param type $tier_id
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    public function setLevelForPatient($user_id, $clinic_id, $cycle, $level, $tier_id, $startdate, $enddate) {
        $tierlevel['TierAchievement'] = array(
            'user_id' => $user_id,
            'clinic_id' => $clinic_id,
            'cycle' => $cycle,
            'current_level' => $level,
            'tier_id' => $tier_id,
            'start_tier' => $startdate,
            'end_tier' => $enddate
        );

        $this->create();
        $this->save($tierlevel['TierAchievement']);
        return $this->getLastInsertId();
    }
    /**
     * Update current level of achievment according to point earn in time frame.
     * @param type $id
     * @param type $user_id
     * @param type $clinic_id
     * @param type $cycle
     * @param type $level
     * @param type $tier_id
     * @param type $enddate
     * @return type
     */
    public function updateLevelForPatient($id,$user_id, $clinic_id, $cycle, $level, $tier_id, $enddate) {
        $tierlevel['TierAchievement'] = array(
            'id' => $id,
            'user_id' => $user_id,
            'clinic_id' => $clinic_id,
            'cycle' => $cycle,
            'current_level' => $level,
            'tier_id' => $tier_id,
            'end_tier' => $enddate
        );
        $this->save($tierlevel['TierAchievement']);
        return $this->getLastInsertId();
    }

}

?>
