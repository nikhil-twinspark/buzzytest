<?php

class TierSetting extends AppModel {

    var $name = 'TierSetting';
    var $displayField = 'id';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'Clinic' => array(
            'className' => 'Clinic',
            'foreignKey' => 'clinic_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Getting the Accelerated Reward by id
     * @param type $id
     * @return type Detail of Accelerated rewards
     */
    public function getRecordById($id) {
        $Acceleratedrewards = $this->find('first', array(
            'conditions' => array('TierSetting.id' => $id),
            'fields' => array('TierSetting.*')
        ));
        return $Acceleratedrewards;
    }
    /**
     * Getting the list of Tier setting by points.
     * @param type $clinic_id
     * @param type $points
     * @return type
     */
    public function getListOfTierByPoints($clinic_id, $points) {
        $tierlist = $this->find('all', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id, 'TierSetting.points <=' => $points),
            'order' => array('TierSetting.points desc'),
            'fields' => array('TierSetting.*')
        ));
        return $tierlist;
    }
    /**
     * Getting the list of all Tier setting by clinic id.
     * @param type $clinic_id
     * @return type
     */
    public function getListOfTierByClinic($clinic_id) {
        $tierlist = $this->find('all', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id),
            'order' => array('TierSetting.points asc'),
            'fields' => array('TierSetting.*')
        ));
        return $tierlist;
    }
    
    /**
     * Getting the default first Tier setting by clinic id.
     * @param type $clinic_id
     * @return type
     */
    public function getFirstTier($clinic_id) {
        $firstlist = $this->find('first', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id),
            'order' => array('TierSetting.points asc'),
            'fields' => array('TierSetting.*')
        ));
        return $firstlist;
    }
    /**
     * Getting the default next Tier setting by clinic id.
     * @param type $clinic_id
     * @return type
     */
    public function getNextTier($clinic_id,$points) {
        $firstlist = $this->find('first', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id,'TierSetting.points >'=>$points),
            'order' => array('TierSetting.points asc'),
            'fields' => array('TierSetting.*')
        ));
        return $firstlist;
    }
    /**
     * Getting the default top Tier setting by clinic id.
     * @param type $clinic_id
     * @return type
     */
    public function getTopTier($clinic_id) {
        $Toptier = $this->find('first', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id),
            'order' => array('TierSetting.points desc'),
            'fields' => array('TierSetting.*')
        ));
        return $Toptier;
    }
    /**
     * Get current Tier setting by points.
     * @param type $clinic_id
     * @param type $points
     * @return type
     */
    public function getCurrentTierByPoints($clinic_id, $points) {
        $tierlist = $this->find('first', array(
            'conditions' => array(
                'TierSetting.clinic_id' => $clinic_id, 'TierSetting.points <=' => $points),
            'order' => array('TierSetting.points desc'),
            'fields' => array('TierSetting.*')
        ));
        return $tierlist;
    }

}

?>
