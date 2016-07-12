<?php
/**
 * This model use for Getting the access set by super admin for legacy and buzzydoc pratice.
 * This model content these fileds:-
 * id -Prmiary key auto increment
 * access - Getting the controller name (comma seperated) for pratice have access.
 * clinic_id -clinic id for access is set.
 * clinic_type - 3 types of clinic (legacy,lite,buzzydoc)
 * created_on - setting date.
 */
class AccessStaff extends AppModel {

    var $name = 'AccessStaff';
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
     * Getting the access setting for clinic
     * @param type $user_id
     * @param type $clinic_id
     * @return type
     */
    public function getAccessForClinic($clinic_id) {
        $getaccess = $this->find('first', array(
            'conditions' => array('AccessStaff.clinic_id' => $clinic_id),
            'fields'=>array('AccessStaff.*')
        ));
        return $getaccess;
    }

}

?>
