<?php

class transaction extends AppModel {

    var $name = 'transaction';
    var $displayField = 'id';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Promotion' => array(
            'className' => 'Promotion',
            'foreignKey' => 'promotion_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Reward' => array(
            'className' => 'Reward',
            'foreignKey' => 'reward_id',
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
     * Get detailed report of unique transactions by staff per day
     * 
     * @param string $type (d:date, w:weekly, m:monthly,o:overall) Type of report
     * $duration string (date range | number of weeks | number of months | null)
     * 
     * @return Array
     */
    public function getStaffTransactionReport($type, $duration = null, $clinic_id = null) {
        $where = ' WHERE';
        if ($clinic_id != null) {
            $where = $where . " transactions.clinic_id=$clinic_id AND";
        }
        if ($type == 'd' && $duration != null) {
            $from = new DateTime($duration[0]);
            $from = $from->format('Y-m-d');

            $to = new DateTime($duration[1]);
            $to = $to->format('Y-m-d');
            $where = " $where activity_type = 'N'
        AND transactions.staff_id != 'null' AND DATE(date) >='$from' AND DATE(date) <= '$to'";
        } elseif ($type == 'w') {
            if ($duration != null) {
                $duration = $duration * 7;
                $where = " $where activity_type = 'N'
                AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            } else {
                $where = " $where activity_type = 'N'
                AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            }
        } elseif ($type == 'm') {
            if ($duration != null) {
                $duration = $duration * 31;
                $where = " $where activity_type = 'N'
            AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            } else {
                $where = " $where activity_type = 'N'
            AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            }
        } else {
            $where = "";
        }
        $sql = "SELECT  staff_name, COUNT(staff_id)  AS user_count FROM
        (
        SELECT
        transactions.staff_id,staffs.staff_id AS staff_name
        FROM
        transactions LEFT JOIN staffs ON transactions.`staff_id` = staffs.id
        $where
        GROUP BY staff_id, DATE(transactions.DATE), transactions.user_id
        ) AS staff GROUP BY staff_id order by user_count desc";
        $data = $this->query($sql);
        return $data;
    }

    /**
     * Get detailed report of unique transactions by staff weekly
     * 
     * If duration is more than 3 months it will generate report till 3 months
     * 
     * @param string $type (d:date, w:weekly, m:monthly,o:overall) Type of report
     * $duration string (date range | number of weeks | number of months | null)
     * 
     * @return Array
     */
    public function getStaffWeeklyTransactionReport($type, $duration = null, $clinic_id = null, $staff_id = null) {
        $where = ' WHERE';
        if ($clinic_id != null) {
            $where = $where . " transactions.clinic_id=$clinic_id AND";
        }
        if ($staff_id != null) {
            $where = $where . " transactions.staff_id=$staff_id AND";
        }

        if ($type == 'd' && $duration != null) {

            $from = new DateTime($duration[0]);
            $from = $from->format('Y-m-d');

            $to = new DateTime($duration[1]);
            $to = $to->format('Y-m-d');

            $where = " $where activity_type = 'N'
        AND transactions.staff_id != 'null' AND DATE(date) >='$from' AND DATE(date) <= '$to'";
        } elseif ($type == 'w') {
            if ($duration != null) {
                $duration = $duration * 7;
                $where = " $where activity_type = 'N'
                AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            } else {
                $where = " $where activity_type = 'N'
                AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            }
        } elseif ($type == 'm') {
            if ($duration != null) {
                $duration = $duration * 31;
                $where = " $where activity_type = 'N'
            AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            } else {
                $where = " $where activity_type = 'N'
            AND transactions.staff_id != 'null' AND DATE(date) >= DATE(NOW()) - INTERVAL $duration DAY";
            }
        } else {
            $where = "";
        }
        $sql = "SELECT 
    staff_name, weeks, COUNT(counts) AS week_count
FROM
  (
  
  SELECT 
  staffs.staff_id AS staff_name,
    transactions.id AS id,
    transactions.`staff_id` AS staff_id,
    YEAR(DATE) AS years,
    MONTH(DATE) AS months,
    WEEK(DATE) AS weeks,
    GROUP_CONCAT(user_id) AS user_ids,
    IF(COUNT(user_id) > 1, 1, 1) AS counts,
    activity_type,
    transactions.clinic_id AS clinic_id,
    DATE 
  FROM
    transactions 
    
    LEFT JOIN staffs 
      ON transactions.`staff_id` = staffs.id 
      
  $where
  GROUP BY staff_id,
    DATE(DATE),
    user_id,
    MONTH(DATE),
    WEEK(DATE)
    
    ) AS subquery 
GROUP BY staff_id, weeks ;";
        $data = $this->query($sql);
        $dataSet = $clients = array();
        if ($data) {
            foreach ($data as $key => $val) {
                $weekRange = explode(',', $val[0]['week_range']);
                $count = count($weekRange);
                if ($count > 1) {
                    $range = $weekRange[0] . ' to ' . $weekRange[$count - 1];
                } else {
                    $range = $val[0]['week_range'];
                }
                $dataSet[$val['subquery']['staff_name']][] = $val[0]['week_count'];
                $clients[] = $val['subquery']['staff_name'];
            }
        }
        return empty($dataSet) ? 0 : array('dataSet' => $dataSet, 'clients' => $clients);
    }

    public function getTransactionReportForClinic($type, $duration = null, $clinic_id = null, $staff_id = null, $promotion_id = null) {
        $duration = explode(' - ', $duration);
        $where = ' WHERE';
        if ($clinic_id != null) {
            $where = $where . " transactions.clinic_id=$clinic_id AND";
        }
        if ($staff_id != null) {
            $where = $where . " transactions.staff_id=$staff_id AND";
        }
        if ($promotion_id != null) {
            $where = $where . " transactions.promotion_id=$promotion_id AND";
        }
        if ($duration != null) {
            $from = new DateTime($duration[0]);
            $from = $from->format('Y-m-d');

            $to = new DateTime($duration[1]);
            $to = $to->format('Y-m-d');
            $where = " $where transactions.staff_id != 'null' AND DATE(date) >='$from' AND DATE(date) <= '$to'";
        } else {
            $where = "";
        }
        $sql = "SELECT  staff_name, COUNT(staff_id)  AS user_count, weeks FROM
        (
        SELECT
        transactions.staff_id,staffs.staff_id AS staff_name,WEEK(DATE) AS weeks
        FROM
        transactions LEFT JOIN staffs ON transactions.`staff_id` = staffs.id
        $where
        GROUP BY staff_id, DATE(transactions.DATE), transactions.user_id
        ) AS staff GROUP BY staff_id order by user_count desc";
        $data = $this->query($sql);
        return $data;
    }

}

?>
