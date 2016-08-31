<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchReturnedIds
 * Append returned id list.
 */
class SearchReturnedIds extends ConditionStatement {
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
        if(array_key_exists('returnedIds', $search)) {
            $statement = "";
            foreach($search["returnedIds"] as $index => $id) {
                $statement .= "ur.id=:returnedIds$index OR ";
                $params[":returnedIds$index"] = $id;
            }
            $statement = substr($statement, 0, -3);
            array_push($conditions, "($statement)");
        }
    }
}
?>