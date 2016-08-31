<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinWholesaleProdcut extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        $dao->leftJoin( 'wholesale_product wsp', 'wsp.id=wps.product_id');
        array_push($select, 'wsp.id product_id');
        array_push($select, 'wsp.name product_name');
        array_push($select, 'wsp.weight weight');
        array_push($select, 'wsp.cover_photo_img  cover_photo_img');
    }
}
?>