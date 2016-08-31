<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );
require_once( dirname(__FILE__) . "/../../models/GroupBuyingActivity.php" );
require_once( dirname(__FILE__) . "/../../models/GeneralActivity.php" );
require_once( dirname(__FILE__) . "/JoinMaster.php" );


/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinActivity extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {

//        TODO refactoring assign other object
        if(array_key_exists("activityType", $search)) {
            if($search["activityType"] == GroupBuyingActivity::TYPE_NAME) {
                $dao->leftJoin( 'groupbuying_activity ga', 'ga.id=uo.activity_id');
                array_push($conditions, '(uo.activity_type=:activityType OR uo.activity_type=:reorderActivityType)');
                $params[':activityType'] = $search["activityType"];
                $params[':reorderActivityType'] = "reorder_" . $search["activityType"];
                array_push($select, 'ga.master_id master_id');
                array_push($select, 'ga.name activity_name');
                $joinMaster = new JoinMaster();
                $joinMaster->append($dao, $params, $conditions, $select, $search);
            }

            if($search["activityType"] == GeneralActivity::TYPE_NAME) {
                $dao->leftJoin( 'general_activity ga', 'ga.id=uo.activity_id');
                array_push($conditions, '(uo.activity_type=:activityType OR uo.activity_type=:reorderActivityType)');
                $params[':activityType'] = $search["activityType"];
                $params[':reorderActivityType'] = "reorder_" . $search["activityType"];
                array_push($select, 'ga.master_id master_id');
                array_push($select, 'ga.name activity_name');
                $joinMaster = new JoinMaster();
                $joinMaster->append($dao, $params, $conditions, $select, $search);
            }
        }
    }
}
?>