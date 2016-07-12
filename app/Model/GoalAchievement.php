<?php
/**
 * This model for store staff goal achievement data weekly.
 * This model content these fileds:-
 * id -Prmiary key auto increment.
 * goal_id - goal id.
 * clinic_id - practice id.
 * staff_id - staff user id.
 * target_value - target set for staff user.
 * actual_value - current goal achived count.
 * week_number - current week number.
 * goal_start_date - start date for goal.
 */
class GoalAchievement extends AppModel {

    var $name = 'GoalAchievement';
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
     * Getting the list of report for clinic
     * @param type $clinic_id
     * @return type list of goal
     */
    public function getAllreport($clinic_id, $staff_id = null) {
        if ($staff_id != null) {
        $goalsettings = $this->find('all', array(
            'conditions' => array('GoalAchievement.clinic_id' => $clinic_id,'GoalAchievement.staff_id'=>$staff_id,'Goal.status'=>0),
            'fields' => array('GoalAchievement.*','Staff.staff_id','Goal.goal_name'),
            'order' => array('GoalAchievement.year desc','GoalAchievement.week_number desc')
        )); 
        }else{
        $goalsettings = $this->find('all', array(
            'conditions' => array('GoalAchievement.clinic_id' => $clinic_id,'Goal.status'=>0),
            'fields' => array('GoalAchievement.*','Goal.goal_name','Staff.staff_id','Clinic.api_user'),
            'order' => array('GoalAchievement.year desc','GoalAchievement.week_number desc')
        ));
        }
        return $goalsettings;
    }

}

?>
