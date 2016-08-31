<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchGeneralActivityIds
 */
class SearchGeneralActivityIds extends ConditionStatement {
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
        if(array_key_exists('ids', $search)) {
            $statement = "";
            foreach($search["ids"] as $index => $id) {
                $statement .= "act.id=:activity_id$index OR ";
                $params[":activity_id$index"] = $id;
            }
            $statement = substr($statement, 0, -3);
            array_push($conditions, "($statement)");
        }
    }
}
?>