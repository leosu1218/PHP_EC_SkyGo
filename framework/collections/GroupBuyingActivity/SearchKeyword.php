<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchKeyword
 * Append product name or group buying activity keyword or group buying activity note conditions.
 */
class SearchKeyword extends ConditionStatement {
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
            array_push($conditions, '(gbActivity.name like :word or master.name like :word or gbActivity.note like :word)');
            $params[':word'] = "%$keyword%";
        }
    }
}
?>