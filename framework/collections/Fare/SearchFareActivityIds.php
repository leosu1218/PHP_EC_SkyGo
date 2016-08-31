<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );
require_once( FRAMEWORK_PATH . 'models/GroupBuyingActivity.php' );
require_once( FRAMEWORK_PATH . 'models/GeneralActivity.php' );

/**
 * Class SearchFareActivityIds
 * Append search fare's record by activity id statement.
 */
class SearchFareActivityIds extends ConditionStatement {
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
        if(array_key_exists('activityIds', $search) && array_key_exists('activityType', $search)) {
            $activityIds            = $search['activityIds'];
            $activityType          = $search['activityType'];
            $where = "" ;
            $dao->leftJoin( 'product_has_fare phf', 'f.id=phf.fare_id');

            foreach($activityIds as $index => $id) {
                $where .= "act.id=:activity_id_$index or ";
                $params[":activity_id_$index"] = $id;
            }
            $where .= "f.global=:global";
            array_push($conditions, $where);
            $params[":global"]   = 1;

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