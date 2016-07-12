<?php

/**
 * This model for store goal details.
 * This model content these fileds:-
 * id -Prmiary key auto increment.
 * clinic_id - clinic for goal created.
 * goal_name - name of goal.
 * goal_type -goal type 1-"point assign",2="promotion" etc.
 * promotion_id - if type is 2 then store promotion id.
 * created_on - date of creation goal
 */
class Goal extends AppModel {

    var $name = 'Goal';
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
     * Getting the list of all goals for clinic
     * @param type $clinic_id
     * @return type list of goal
     */
    public function getAllRecord($clinic_id) {
        $goals = $this->find('all', array(
            'conditions' => array('Goal.clinic_id' => $clinic_id,'Goal.status' =>0),
            'fields' => array('Goal.*')
        ));
        return $goals;
    }
    
    /**
     * Getting all goals by promotion
     * @param type $clinic_id
     * @return type list of goal
     */
    public function getAllRecordWithPromo($clinic_id) {
        $goals = $this->find('all', array(
            'conditions' => array('Goal.clinic_id' => $clinic_id,'Goal.promotion_id >' =>0,'Goal.status' =>0),
            'fields' => array('Goal.promotion_id')
        ));
        return $goals;
    }
    /**
     * Getting all goals for assigmnet
     * @param type $clinic_id
     * @return type list of goal
     */
    public function getGoalForAssignment($clinic_id) {
        $goals = $this->find('all', array(
            'conditions' => array('Goal.clinic_id' => $clinic_id,'Goal.goal_type' =>1,'Goal.status' =>0),
            'fields' => array('Goal.*')
        ));
        return $goals;
    }
    /**
     * Getting goal by goal name
     * @param type $clinic_id
     * @param type $goal_name
     * @return type list of goal
     */
    public function getGoalByName($clinic_id,$goal_name) {
        $goals = $this->find('first', array(
            'conditions' => array('Goal.clinic_id' => $clinic_id,'Goal.goal_name' =>$goal_name,'Goal.status' =>0),
            'fields' => array('Goal.id')
        ));
        return $goals;
    }

}

?>
