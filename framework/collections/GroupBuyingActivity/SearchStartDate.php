<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchStartDate
 * Append product name or group buying activity keyword or group buying activity note conditions.
 */
class SearchStartDate extends ConditionStatement {
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
        if(array_key_exists('startDateOpen', $search)&&array_key_exists('startDateClose', $search)) {
            $startOpen 	= $search['startDateOpen'];
            $startClose = $search['startDateClose'];
            array_push($conditions, '(gbActivity.start_date >= :startOpen and gbActivity.start_date <= :startClose)');
            $params[':startOpen'] 	= "$startOpen";
            $params[':startClose'] 	= "$startClose";
        }
    }
}
?>