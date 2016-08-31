<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchEndDate
 * Append product name or group buying activity keyword or group buying activity note conditions.
 */
class SearchEndDate extends ConditionStatement {
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
        if(array_key_exists('endDateOpen', $search)&&array_key_exists('endDateClose', $search)) {
            $endOpen                = $search['endDateOpen'];
            $endClose               = $search['endDateClose'];
            $params[':endOpen']     = "$endOpen";
            $params[':endClose']    = "$endClose";
            array_push($conditions, '(gbActivity.end_date >= :endOpen and gbActivity.end_date <= :endClose)');
        }
    }
}
?>