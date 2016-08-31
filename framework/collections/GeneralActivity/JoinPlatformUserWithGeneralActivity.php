<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class JoinPlatformUserWithGeneralActivity
 */
class JoinPlatformUserWithGeneralActivity extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin( 'platform_user master', 'master.id=act.master_id');
        array_push($select, 'master.name masterName');
    }
}
?>