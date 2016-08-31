<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingActivity.php' );
require_once( FRAMEWORK_PATH . 'models/GeneralActivity.php' );

/**
 * Class SearchFareActivityId
 * Append search fare's record by activity id statement.
 */
class SearchFareActivityId extends ConditionStatement {
    /**
     * Append search condition statement to dao.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists('activityId', $search) && array_key_exists('activityType', $search)) {
            $activityId            = $search['activityId'];
            $activityType          = $search['activityType'];
            $params[':activity_id'] = $activityId;
            $params[':global']      = 1;

            $dao->leftJoin( 'product_has_fare phf', 'f.id=phf.fare_id');
            array_push($conditions, '(act.id=:activity_id or f.global=:global)');

            if($activityType == GroupBuyingActivity::TYPE_NAME) {
                $dao->leftJoin( 'groupbuying_activity act', 'act.product_id=phf.product_id');
            }
            else if($activityType == GeneralActivity::TYPE_NAME) {
                $dao->leftJoin( 'general_activity act', 'act.product_id=phf.product_id');
            }
            else {
                throw new InvalidAccessParamsException("Invalid activity type [$activityType].");
            }
        }
    }
}
?>