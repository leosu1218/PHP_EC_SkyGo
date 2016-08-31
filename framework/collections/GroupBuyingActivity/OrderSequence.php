<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class OrderSequence
 * Append order name Sequence
 */
class OrderSequence extends ConditionStatement {
    /**
     * Append order condition statement to dao.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search)
    {
        if (array_key_exists('order', $search)) {
            if ($search['order'] == 'new') {
                $dao->order('gbActivity.start_date  DESC');
            } elseif ($search['order'] == 'hot') {
                $dao->order('gbActivity.buyer_counter DESC');
            } elseif ($search['order'] == 'end') {
                $dao->order('gbActivity.end_date ');
            }
        }
    }
}
?>