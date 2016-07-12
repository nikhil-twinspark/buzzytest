<?php
/**
 * This model use for store all notification like (leads,redeemed,rss feeds,rate and review).
 * This model content these fileds:-
 * id -Prmiary key auto increment.
 * clinic_id - nitification related to clinic.
 * notification_id - notification id from diffrent tables.
 * notification_type - type (leads,redeemed,rss feeds,rate and review).
 * details- json format details.
 * status - old or new.
 * date - notification date.
 */
class ClinicNotification extends AppModel {

    var $name = 'ClinicNotification';
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
     * Getting the access for clinic
     * @param type $clinic_id
     * @param type $controller
     * @return type
     */
    public function getNotification($clinic_id) {
        $getnotification = $this->find('all', array(
            'conditions' => array('ClinicNotification.clinic_id' => $clinic_id, 'ClinicNotification.status' => 0),
            'order'=>array('ClinicNotification.date desc'),
            'fields'=>array('ClinicNotification.*'),
            'limit'=>5
        ));
        return $getnotification;
    }

}

?>
