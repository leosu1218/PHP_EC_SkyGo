<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinConsumerUser extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin( 'consumer_user cu', 'cu.id=uo.consumer_user_id');
        array_push($select, 'cu.name user_name');
        array_push($select, 'cu.account user_account');
        array_push($select, 'cu.email user_email');
    }
}
?>