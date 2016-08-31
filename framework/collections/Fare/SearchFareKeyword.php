<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchFareKeyword
 * Append search fare's record by keyword statement.
 */
class SearchFareKeyword extends ConditionStatement {
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
        if(array_key_exists('keyword', $search)) {
            $keyword = $search['keyword'];
            array_push($conditions, '(f.id like :id or f.amount like :amount or f.type like :type or f.target_amount like :target_amount or f.global like :global)');
            $params[':id'] = "%$keyword%";
            $params[':amount'] = "%$keyword%";
            $params[':type'] = "%$keyword%";
            $params[':target_amount'] = "%$keyword%";
            $params[':global'] = "%$keyword%";
        }
    }
}
?>