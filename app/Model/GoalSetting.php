<?php
/**
 * This model for store goal setting for practice.
 * This model content these fileds:-
 * id -Prmiary key auto increment.
 * goal_id - setting for the goal.
 * clinic_id - practice id
 * staff_id - goal setting for staff user.
 * target_value - target set for staff user or practice.
 * goal_start_date - start date for goal setting.
 */
class GoalSetting extends AppModel {

    var $name = 'GoalSetting';
    var $displayField = 'id';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Goal' => array(
            'className' => 'Goal',
            'foreignKey' => 'goal_id',
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
        ),
        'Staff' => array(
            'className' => 'Staff',
            'foreignKey' => 'staff_id',
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
    public function getAllSettings($clinic_id, $staff_id = null) {
        
        if ($staff_id != null) {
        $goalsettings = $this->find('all', array(
            'conditions' => array('GoalSetting.clinic_id' => $clinic_id,'GoalSetting.status'=>1,'Goal.status'=>0,'GoalSetting.staff_id'=>$staff_id),
            'fields' => array('Goal.id','Goal.goal_name','Goal.goal_type','Goal.promotion_id','GoalSetting.*','Staff.staff_first_name','Staff.staff_last_name','Staff.staff_id','Clinic.api_user')
        )); 
        }else{
        $goalsettings = $this->find('all', array(
            'conditions' => array('GoalSetting.clinic_id' => $clinic_id,'GoalSetting.status'=>1,'Goal.status'=>0),
            'fields' => array('Goal.id','Goal.goal_name','Goal.goal_type','Goal.promotion_id','GoalSetting.*','Staff.staff_first_name','Staff.staff_last_name','Staff.staff_id','Clinic.api_user')
        ));
        }
        return $goalsettings;
    }

}

?>
