<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchGeneralActivityTag1
 */
class SearchGeneralActivityTag2 extends ConditionStatement {
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
        if(array_key_exists('tag2', $search)) {
            $tag2 = $search['tag2'];
            array_push($conditions, '( wp.tag like :tag2)');
            $params[':tag2'] = "%$tag2%";
        }
    }
}
?>