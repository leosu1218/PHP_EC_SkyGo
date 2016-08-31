<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinWholesaleProdcutSpec extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin( 'wholesale_product_spec wps', 'wps.id=ohs.spec_id');
        array_push($select, 'wps.name spec_name');
        array_push($select, 'wps.product_id spec_product_id');
        array_push($select, 'wps.serial spec_serial');
    }
}
?>