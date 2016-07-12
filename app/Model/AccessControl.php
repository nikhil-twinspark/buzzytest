<?php
/**
 * This model use for Getting the access control for legacy and buzzydoc pratice.
 * This model content these fileds:-
 * id -Prmiary key auto increment
 * access - Getting the controller name (comma seperated) for pratice have access.
 * clinic_id -clinic id for access is set.
 * clinic_type - 3 types of clinic (legacy,lite,buzzydoc)
 * created_on - setting date.
 */
class AccessControl extends AppModel {

    var $name = 'AccessControl';
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
    public function getAccessForClinic($clinic_id,$controller) {
        $getaccess = $this->find('first', array(
            'conditions' => array('AccessControl.clinic_id' => $clinic_id, 'AccessControl.access like' => "%" . $controller . "%"),
            'fields'=>array('AccessControl.*')
        ));
        return $getaccess;
    }

}

?>
